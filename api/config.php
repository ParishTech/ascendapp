<?php
// Ascend Configuration
// api/config.php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'ascend_user');
define('DB_PASS', 'your_password_here');
define('DB_NAME', 'ascend');
define('DB_PORT', 3306);

// API Configuration
define('API_URL', 'http://localhost/api');
define('APP_URL', 'http://localhost');

// Security
define('SESSION_TIMEOUT', 3600); // 1 hour
define('TOKEN_LENGTH', 32);

// Claude API Configuration
define('CLAUDE_API_KEY', getenv('CLAUDE_API_KEY'));
define('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages');

// Bible API Configuration
define('BIBLE_API_KEY', getenv('BIBLE_API_KEY'));
define('BIBLE_API_URL', 'https://api.api-bible.com/v1');

// Roles
define('ROLE_SERVER', 'server');
define('ROLE_MC', 'mc');
define('ROLE_TRAINER', 'trainer');
define('ROLE_ADMIN', 'admin');

// Error Reporting
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
