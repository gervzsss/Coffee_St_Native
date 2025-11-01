<?php
/**
 * Unified Auth API
 * Handles login, register, and logout operations
 */

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';
require_once BASE_PATH . '/backend/helpers/helpers.php';
require_once BASE_PATH . '/backend/db.php';
require_once BASE_PATH . '/backend/models/User.php';
require_once BASE_PATH . '/backend/models/Cart.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// Only POST allowed for auth operations
if ($method !== 'POST') {
  respondError('Method not allowed', 405);
}

$input = getJsonInput();

// Determine action from input
if (!isset($input['action'])) {
  respondError('Action is required (login, register, or logout)');
}

$action = strtolower(trim($input['action']));

try {
  $pdo = require BASE_PATH . '/backend/db.php';

  switch ($action) {
    // ========================================
    // LOGIN
    // ========================================
    case 'login':
      if (!isset($input['email'], $input['password'])) {
        respondError('Email and password are required');
      }

      $email = trim($input['email']);
      $password = $input['password'];

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respondError('Invalid email format');
      }

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

      // Get cart count (no migration needed since guests can't add items)
      $cartId = Cart::getActiveCart($pdo, $user['id']);
      $cartCount = Cart::getItemCount($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
          'id' => $user['id'],
          'email' => $user['email'],
          'first_name' => $user['first_name'],
          'last_name' => $user['last_name'],
          'full_name' => $user['first_name'] . ' ' . $user['last_name']
        ],
        'cart_count' => $cartCount
      ]);
      break;

    // ========================================
    // REGISTER
    // ========================================
    case 'register':
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

      // Get cart count (new user will have empty cart)
      $cartId = Cart::getActiveCart($pdo, $userId);
      $cartCount = Cart::getItemCount($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'message' => 'Registration successful',
        'user' => [
          'id' => $userId,
          'email' => $email,
          'first_name' => $firstName,
          'last_name' => $lastName,
          'full_name' => $firstName . ' ' . $lastName
        ],
        'cart_count' => $cartCount
      ], 201);
      break;

    // ========================================
    // LOGOUT
    // ========================================
    case 'logout':
      logoutUser();

      jsonResponse([
        'success' => true,
        'message' => 'Logout successful'
      ]);
      break;

    // ========================================
    // Invalid action
    // ========================================
    default:
      respondError('Invalid action. Use: login, register, or logout');
  }
} catch (Exception $e) {
  respondError('Authentication failed: ' . $e->getMessage(), 500);
}
