<?php
// Authentication API
// api/auth.php

require_once 'config.php';
require_once 'database.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($action) {
        case 'register':
            if ($method !== 'POST') throw new Exception('Invalid request method');
            handleRegister();
            break;

        case 'login':
            if ($method !== 'POST') throw new Exception('Invalid request method');
            handleLogin();
            break;

        case 'logout':
            if ($method !== 'POST') throw new Exception('Invalid request method');
            handleLogout();
            break;

        case 'verify':
            if ($method !== 'GET') throw new Exception('Invalid request method');
            handleVerify();
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

function handleRegister() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data['name'] || !$data['email'] || !$data['password']) {
        throw new Exception('Missing required fields');
    }

    $db = Database::getInstance();
    $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);

    $userData = [
        'name' => $data['name'],
        'email' => $data['email'],
        'password_hash' => $password_hash,
        'role' => $data['role'] ?? ROLE_SERVER,
        'parish_id' => $data['parish_id'] ?? null
    ];

    $userId = $db->insert('users', $userData);
    
    echo json_encode([
        'success' => true,
        'message' => 'User registered successfully',
        'user_id' => $userId
    ]);
}

function handleLogin() {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data['email'] || !$data['password']) {
        throw new Exception('Email and password required');
    }

    $db = Database::getInstance();
    $user = $db->fetchOne('SELECT id, name, email, password_hash, role FROM users WHERE email = ?', [$data['email']]);

    if (!$user) {
        throw new Exception('Invalid credentials');
    }

    if (!password_verify($data['password'], $user['password_hash'])) {
        throw new Exception('Invalid credentials');
    }

    // Generate session token
    $token = bin2hex(random_bytes(TOKEN_LENGTH / 2));
    $expires_at = date('Y-m-d H:i:s', time() + SESSION_TIMEOUT);

    $sessionData = [
        'user_id' => $user['id'],
        'token' => $token,
        'expires_at' => $expires_at
    ];

    $db->insert('sessions', $sessionData);

    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);
}

function handleLogout() {
    $token = getBearerToken();
    if (!$token) {
        throw new Exception('No token provided');
    }

    $db = Database::getInstance();
    $db->delete('sessions', "token = '$token'");

    echo json_encode([
        'success' => true,
        'message' => 'Logged out successfully'
    ]);
}

function handleVerify() {
    $token = getBearerToken();
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

    echo json_encode([
        'success' => true,
        'user' => $session
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
