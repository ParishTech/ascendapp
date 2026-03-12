<?php
// Performance Notes API
// api/notes.php

require_once 'config.php';
require_once 'database.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    $token = getBearerToken();
    $user = verifyToken($token);

    switch ($action) {
        case 'add':
            if ($method !== 'POST') throw new Exception('Invalid request method');
            handleAddNote($user);
            break;

        case 'get':
            if ($method !== 'GET') throw new Exception('Invalid request method');
            handleGetNotes($user);
            break;

        case 'server':
            if ($method !== 'GET') throw new Exception('Invalid request method');
            handleServerNotes($user);
            break;

        case 'referral':
            if ($method !== 'POST') throw new Exception('Invalid request method');
            handleReferral($user);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
}

function handleAddNote($user) {
    // Only MCs can add notes
    if ($user['role'] !== ROLE_MC) {
        throw new Exception('Only MCs can add performance notes');
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data['mass_id'] || !$data['server_id']) {
        throw new Exception('Mass ID and Server ID required');
    }

    $db = Database::getInstance();

    $noteData = [
        'mass_id' => $data['mass_id'],
        'server_id' => $data['server_id'],
        'mc_id' => $user['id'],
        'timeliness' => intval($data['timeliness'] ?? 3),
        'demeanor' => intval($data['demeanor'] ?? 3),
        'accuracy' => intval($data['accuracy'] ?? 3),
        'notes' => $data['notes'] ?? '',
        'has_referral' => isset($data['referral_reason']) ? 1 : 0,
        'referral_reason' => $data['referral_reason'] ?? null
    ];

    $noteId = $db->insert('performance_notes', $noteData);

    // If referral, add to training_referrals
    if ($noteData['has_referral']) {
        $referralData = [
            'user_id' => $data['server_id'],
            'referred_by' => $user['id'],
            'reason' => $data['referral_reason']
        ];
        $db->insert('training_referrals', $referralData);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Performance note recorded',
        'note_id' => $noteId
    ]);
}

function handleGetNotes($user) {
    $mass_id = $_GET['mass_id'] ?? null;
    
    if (!$mass_id) {
        throw new Exception('Mass ID required');
    }

    $db = Database::getInstance();
    $notes = $db->fetchAll(
        'SELECT pn.*, u.name as server_name FROM performance_notes pn JOIN users u ON pn.server_id = u.id WHERE pn.mass_id = ? ORDER BY pn.created_at DESC',
        [$mass_id]
    );

    echo json_encode([
        'success' => true,
        'notes' => $notes
    ]);
}

function handleServerNotes($user) {
    $server_id = $_GET['server_id'] ?? $user['id'];
    $limit = $_GET['limit'] ?? 10;

    $db = Database::getInstance();
    $notes = $db->fetchAll(
        'SELECT pn.*, m.date, m.time, mc.name as mc_name FROM performance_notes pn JOIN masses m ON pn.mass_id = m.id JOIN users mc ON pn.mc_id = mc.id WHERE pn.server_id = ? ORDER BY pn.created_at DESC LIMIT ?',
        [$server_id, intval($limit)]
    );

    $stats = $db->fetchOne(
        'SELECT AVG(timeliness) as avg_timeliness, AVG(demeanor) as avg_demeanor, AVG(accuracy) as avg_accuracy FROM performance_notes WHERE server_id = ?',
        [$server_id]
    );

    echo json_encode([
        'success' => true,
        'notes' => $notes,
        'stats' => $stats
    ]);
}

function handleReferral($user) {
    // Only MCs and trainers can create referrals
    if (!in_array($user['role'], [ROLE_MC, ROLE_TRAINER])) {
        throw new Exception('Insufficient permissions');
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data['server_id'] || !$data['reason']) {
        throw new Exception('Server ID and reason required');
    }

    $db = Database::getInstance();

    $referralData = [
        'user_id' => $data['server_id'],
        'referred_by' => $user['id'],
        'reason' => $data['reason']
    ];

    $referralId = $db->insert('training_referrals', $referralData);

    echo json_encode([
        'success' => true,
        'message' => 'Training referral created',
        'referral_id' => $referralId
    ]);
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
