<?php

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';
require_once BASE_PATH . '/backend/helpers/helpers.php';
require_once BASE_PATH . '/backend/db.php';
require_once BASE_PATH . '/backend/models/Cart.php';
require_once BASE_PATH . '/backend/models/Order.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
  respondError('Authentication required', 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respondError('Method not allowed', 405);
}

$input = getJsonInput();
$deliveryFee = isset($input['delivery_fee']) ? (float) $input['delivery_fee'] : 0.00;

try {
  $pdo = require BASE_PATH . '/backend/db.php';

  $userId = getUserId();
  $cartId = Cart::getActiveCart($pdo, $userId);

  if (Cart::isEmpty($pdo, $cartId)) {
    respondError('Cannot checkout with empty cart', 400);
  }

  $orderId = Order::create($pdo, $userId, $cartId, $deliveryFee);

  $order = Order::getOrderDetails($pdo, $orderId, $userId);

  jsonResponse([
    'success' => true,
    'message' => 'Order created successfully',
    'order' => $order
  ], 201);
} catch (Exception $e) {
  respondError('Checkout failed: ' . $e->getMessage(), 500);
}
