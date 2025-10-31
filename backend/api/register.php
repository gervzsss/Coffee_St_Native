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

$required = ['email', 'password', 'first_name', 'last_name', 'phone', 'address'];
foreach ($required as $field) {
  if (!isset($input[$field]) || empty(trim($input[$field]))) {
    respondError("Field '{$field}' is required");
  }
}

$email = trim($input['email']);
$password = $input['password'];
$firstName = trim($input['first_name']);
$lastName = trim($input['last_name']);
$phone = trim($input['phone']);
$address = trim($input['address']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  respondError('Invalid email format');
}

if (strlen($password) < 6) {
  respondError('Password must be at least 6 characters long');
}

try {
  $pdo = require BASE_PATH . '/backend/db.php';

  if (User::emailExists($pdo, $email)) {
    respondError('Email already registered', 409);
  }

  $userId = User::create($pdo, [
    'email' => $email,
    'password' => $password,
    'first_name' => $firstName,
    'last_name' => $lastName,
    'phone' => $phone,
    'address' => $address
  ]);

  loginUser($userId, $email, $firstName, $lastName);

  jsonResponse([
    'success' => true,
    'message' => 'Registration successful',
    'user' => [
      'id' => $userId,
      'email' => $email,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'full_name' => $firstName . ' ' . $lastName
    ]
  ], 201);
} catch (Exception $e) {
  respondError('Registration failed: ' . $e->getMessage(), 500);
}
