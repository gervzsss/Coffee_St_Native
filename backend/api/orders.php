<?php

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';
require_once BASE_PATH . '/backend/helpers/helpers.php';
require_once BASE_PATH . '/backend/db.php';
require_once BASE_PATH . '/backend/models/Order.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
  respondError('Authentication required', 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respondError('Method not allowed', 405);
}

try {
  $pdo = require BASE_PATH . '/backend/db.php';
  $userId = getUserId();

  // Get specific order by ID
  if (isset($_GET['id'])) {
    $orderId = (int) $_GET['id'];
    $order = Order::getOrderDetails($pdo, $orderId, $userId);

    if (!$order) {
      respondError('Order not found', 404);
    }

    jsonResponse([
      'success' => true,
      'order' => $order
    ]);
    return;
  }

  // Get all user orders with pagination
  $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50;
  $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

  $orders = Order::getUserOrders($pdo, $userId, $limit, $offset);
  $totalOrders = Order::getUserOrderCount($pdo, $userId);

  jsonResponse([
    'success' => true,
    'orders' => $orders,
    'total' => $totalOrders,
    'limit' => $limit,
    'offset' => $offset
  ]);
} catch (Exception $e) {
  respondError('Failed to retrieve orders: ' . $e->getMessage(), 500);
}
