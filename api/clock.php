<?php
// Clock In/Out API
// api/clock.php

require_once 'config.php';
require_once 'database.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    $token = getBearerToken();
    $user = verifyToken($token);

    switch ($action) {
        case 'in':
            if ($method !== 'POST') throw new Exception('Invalid request method');
            handleClockIn($user);
            break;

        case 'out':
            if ($method !== 'POST') throw new Exception('Invalid request method');
            handleClockOut($user);
            break;

        case 'history':
            if ($method !== 'GET') throw new Exception('Invalid request method');
            handleHistory($user);
            break;

        case 'current':
            if ($method !== 'GET') throw new Exception('Invalid request method');
            handleCurrent($user);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
}

function handleClockIn($user) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data['mass_id']) {
        throw new Exception('Mass ID required');
    }

    $db = Database::getInstance();

    // Check if already clocked in
    $existing = $db->fetchOne(
        'SELECT id FROM clock_records WHERE user_id = ? AND clock_out IS NULL',
        [$user['id']]
    );

    if ($existing) {
        throw new Exception('Already clocked in');
    }

    $clockData = [
        'user_id' => $user['id'],
        'mass_id' => $data['mass_id'],
        'clock_in' => date('Y-m-d H:i:s')
    ];

    $clockId = $db->insert('clock_records', $clockData);

    echo json_encode([
        'success' => true,
        'message' => 'Clocked in successfully',
        'clock_id' => $clockId,
        'clock_in' => $clockData['clock_in']
    ]);
}

function handleClockOut($user) {
    $db = Database::getInstance();

    // Get current clock record
    $record = $db->fetchOne(
        'SELECT id FROM clock_records WHERE user_id = ? AND clock_out IS NULL',
        [$user['id']]
    );

    if (!$record) {
        throw new Exception('Not currently clocked in');
    }

    $clockOut = date('Y-m-d H:i:s');
    $duration = calculateDuration($record['id'], $clockOut);

    $updateData = [
        'clock_out' => $clockOut,
        'duration_minutes' => $duration
    ];

    $db->update('clock_records', $updateData, "id = {$record['id']}");

    echo json_encode([
        'success' => true,
        'message' => 'Clocked out successfully',
        'clock_out' => $clockOut,
        'duration_minutes' => $duration
    ]);
}

function handleHistory($user) {
    $limit = $_GET['limit'] ?? 50;
    $offset = $_GET['offset'] ?? 0;

    $db = Database::getInstance();
    $records = $db->fetchAll(
        'SELECT cr.*, m.date, m.time FROM clock_records cr JOIN masses m ON cr.mass_id = m.id WHERE cr.user_id = ? ORDER BY cr.clock_in DESC LIMIT ? OFFSET ?',
        [$user['id'], intval($limit), intval($offset)]
    );

    $total = $db->fetchOne(
        'SELECT COUNT(*) as count FROM clock_records WHERE user_id = ?',
        [$user['id']]
    );

    echo json_encode([
        'success' => true,
        'records' => $records,
        'total' => $total['count'],
        'limit' => intval($limit),
        'offset' => intval($offset)
    ]);
}

function handleCurrent($user) {
    $db = Database::getInstance();
    $record = $db->fetchOne(
        'SELECT cr.*, m.date, m.time FROM clock_records cr JOIN masses m ON cr.mass_id = m.id WHERE cr.user_id = ? AND cr.clock_out IS NULL',
        [$user['id']]
    );

    if (!$record) {
        echo json_encode([
            'success' => true,
            'clocked_in' => false
        ]);
        return;
    }

    echo json_encode([
        'success' => true,
        'clocked_in' => true,
        'record' => $record
    ]);
}

function calculateDuration($clockId, $clockOut) {
    $db = Database::getInstance();
    $record = $db->fetchOne('SELECT clock_in FROM clock_records WHERE id = ?', [$clockId]);
    
    $clockInTime = strtotime($record['clock_in']);
    $clockOutTime = strtotime($clockOut);
    $duration = ($clockOutTime - $clockInTime) / 60;

    return intval($duration);
}

function getBearerToken() {
    $headers = getallheaders();
    $auth = $headers['Authorization'] ?? '';
    
    if (preg_match('/Bearer\s+(.+)/', $auth, $matches)) {
        return $matches[1];
    }

    return $_GET['token'] ?? null;
}

function verifyToken($token) {
    if (!$token) {
        throw new Exception('No token provided');
    }

    $db = Database::getInstance();
    $session = $db->fetchOne(
        'SELECT u.id, u.name, u.email, u.role FROM sessions s JOIN users u ON s.user_id = u.id WHERE s.token = ? AND s.expires_at > NOW()',
        [$token]
    );

    if (!$session) {
        throw new Exception('Invalid or expired token');
    }

    return $session;
}
