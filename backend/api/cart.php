<?php
if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';
require_once BASE_PATH . '/backend/helpers/helpers.php';
require_once BASE_PATH . '/backend/db.php';
require_once BASE_PATH . '/backend/models/Cart.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
  respondError('Authentication required. Please login to access cart.', 401);
}

$method = $_SERVER['REQUEST_METHOD'];
$pdo = require BASE_PATH . '/backend/db.php';
$userId = getUserId();
$cartId = Cart::getActiveCart($pdo, $userId);

try {
  switch ($method) {

    case 'GET':
      $cartSummary = Cart::getCartSummary($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'cart' => $cartSummary
      ]);
      break;

    case 'POST':
      $input = getJsonInput();

      if (!isset($input['product_id'])) {
        respondError('Product ID is required');
      }

      $productId = (int) $input['product_id'];
      $quantity = isset($input['quantity']) ? (int) $input['quantity'] : 1;
      $unitPrice = isset($input['unit_price']) ? (float) $input['unit_price'] : 0;
      $variantId = isset($input['variant_id']) ? (int) $input['variant_id'] : null;
      $variantName = isset($input['variant_name']) ? trim($input['variant_name']) : null;
      $priceDelta = isset($input['price_delta']) ? (float) $input['price_delta'] : 0.00;

      if ($quantity <= 0) {
        respondError('Quantity must be greater than 0');
      }

      if ($unitPrice <= 0) {
        respondError('Unit price must be greater than 0');
      }

      Cart::addItem($pdo, $cartId, $productId, $quantity, $unitPrice, $variantId, $variantName, $priceDelta);

      $cartSummary = Cart::getCartSummary($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'message' => 'Item added to cart',
        'cart' => $cartSummary
      ], 201);
      break;

    case 'PUT':
    case 'PATCH':
      $input = getJsonInput();

      if (!isset($input['product_id'], $input['quantity'])) {
        respondError('Product ID and quantity are required');
      }

      $productId = (int) $input['product_id'];
      $quantity = (int) $input['quantity'];

      if ($quantity < 0) {
        respondError('Quantity cannot be negative');
      }

      Cart::updateItemQuantity($pdo, $cartId, $productId, $quantity);

      $cartSummary = Cart::getCartSummary($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'message' => $quantity === 0 ? 'Item removed from cart' : 'Cart updated',
        'cart' => $cartSummary
      ]);
      break;

    case 'DELETE':
      $input = getJsonInput();
      $productId = isset($_GET['product_id']) ? (int) $_GET['product_id'] : null;
      $productIds = [];

      if ($productId) {
        $productIds[] = $productId;
      }

      if (isset($input['product_id'])) {
        $productIds[] = (int) $input['product_id'];
      }

      if (isset($input['product_ids']) && is_array($input['product_ids'])) {
        $productIds = array_merge($productIds, $input['product_ids']);
      }

      $productIds = array_values(array_unique(array_map('intval', $productIds)));

      if (empty($productIds)) {
        respondError('Product ID is required');
      }

      if (count($productIds) === 1) {
        Cart::removeItem($pdo, $cartId, $productIds[0]);
        $message = 'Item removed from cart';
      } else {
        Cart::removeItems($pdo, $cartId, $productIds);
        $message = 'Selected items removed from cart';
      }

      $cartSummary = Cart::getCartSummary($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'message' => $message,
        'cart' => $cartSummary
      ]);
      break;

    default:
      respondError('Method not allowed', 405);
  }
} catch (Exception $e) {
  respondError('Cart operation failed: ' . $e->getMessage(), 500);
}
