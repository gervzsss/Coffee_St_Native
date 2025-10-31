<?php

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';
require_once BASE_PATH . '/backend/helpers/helpers.php';
require_once BASE_PATH . '/backend/db.php';
require_once BASE_PATH . '/backend/models/User.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respondError('Method not allowed', 405);
}

$input = getJsonInput();

if (!isset($input['email'], $input['password'])) {
  respondError('Email and password are required');
}

$email = trim($input['email']);
$password = $input['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  respondError('Invalid email format');
}

try {
  $pdo = require BASE_PATH . '/backend/db.php';

  $user = User::verifyPassword($pdo, $email, $password);

  if (!$user) {
    respondError('Invalid email or password', 401);
  }

  loginUser(
    $user['id'],
    $user['email'],
    $user['first_name'],
    $user['last_name']
  );

  jsonResponse([
    'success' => true,
    'message' => 'Login successful',
    'user' => [
      'id' => $user['id'],
      'email' => $user['email'],
      'first_name' => $user['first_name'],
      'last_name' => $user['last_name'],
      'full_name' => $user['first_name'] . ' ' . $user['last_name']
    ]
  ]);
} catch (Exception $e) {
  respondError('Login failed: ' . $e->getMessage(), 500);
}
