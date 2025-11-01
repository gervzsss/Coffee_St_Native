<?php
/**
 * Unified Cart API
 * Handles all cart operations (GET, POST, PUT, DELETE)
 */

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';
require_once BASE_PATH . '/backend/helpers/helpers.php';
require_once BASE_PATH . '/backend/db.php';
require_once BASE_PATH . '/backend/models/Cart.php';

header('Content-Type: application/json');

// Authentication required for all cart operations
if (!isLoggedIn()) {
  respondError('Authentication required. Please login to access cart.', 401);
}

$method = $_SERVER['REQUEST_METHOD'];
$pdo = require BASE_PATH . '/backend/db.php';
$userId = getUserId();
$cartId = Cart::getActiveCart($pdo, $userId);

try {
  switch ($method) {
    // ========================================
    // GET - Retrieve cart
    // ========================================
    case 'GET':
      $cartSummary = Cart::getCartSummary($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'cart' => $cartSummary
      ]);
      break;

    // ========================================
    // POST - Add item to cart
    // ========================================
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

    // ========================================
    // PUT/PATCH - Update item quantity
    // ========================================
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

    // ========================================
    // DELETE - Remove item from cart
    // ========================================
    case 'DELETE':
      $productId = isset($_GET['product_id']) ? (int) $_GET['product_id'] : null;

      if (!$productId) {
        respondError('Product ID is required');
      }

      Cart::removeItem($pdo, $cartId, $productId);

      $cartSummary = Cart::getCartSummary($pdo, $cartId);

      jsonResponse([
        'success' => true,
        'message' => 'Item removed from cart',
        'cart' => $cartSummary
      ]);
      break;

    // ========================================
    // Invalid method
    // ========================================
    default:
      respondError('Method not allowed', 405);
  }
} catch (Exception $e) {
  respondError('Cart operation failed: ' . $e->getMessage(), 500);
}
