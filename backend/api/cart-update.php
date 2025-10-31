<?php

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';
require_once BASE_PATH . '/backend/helpers/helpers.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respondError('Method not allowed', 405);
}

$input = getJsonInput();

if (!isset($input['action'])) {
  respondError('Action is required');
}

$action = $input['action'];

try {
  switch ($action) {
    case 'add':
      if (!isset($input['product_id'], $input['name'], $input['price'])) {
        respondError('Missing required fields: product_id, name, price');
      }

      $productId = $input['product_id'];
      $name = $input['name'];
      $price = floatval($input['price']);
      $quantity = isset($input['quantity']) ? intval($input['quantity']) : 1;
      $image = $input['image'] ?? null;
      $size = $input['size'] ?? 'Medium';

      addToCart($productId, $name, $price, $quantity, $image, $size);

      jsonResponse([
        'success' => true,
        'message' => 'Item added to cart',
        'cart_count' => getCartCount(),
        'cart_total' => getCartTotal()
      ]);
      break;

    case 'update':
      if (!isset($input['item_key'], $input['quantity'])) {
        respondError('Missing required fields: item_key, quantity');
      }

      $itemKey = $input['item_key'];
      $quantity = intval($input['quantity']);

      $result = updateCartQuantity($itemKey, $quantity);

      if ($result) {
        jsonResponse([
          'success' => true,
          'message' => 'Cart updated',
          'cart_count' => getCartCount(),
          'cart_total' => getCartTotal()
        ]);
      } else {
        respondError('Item not found in cart', 404);
      }
      break;

    case 'remove':
      if (!isset($input['item_key'])) {
        respondError('Missing required field: item_key');
      }

      $itemKey = $input['item_key'];
      $result = removeFromCart($itemKey);

      if ($result) {
        jsonResponse([
          'success' => true,
          'message' => 'Item removed from cart',
          'cart_count' => getCartCount(),
          'cart_total' => getCartTotal()
        ]);
      } else {
        respondError('Item not found in cart', 404);
      }
      break;

    case 'clear':
      clearCart();

      jsonResponse([
        'success' => true,
        'message' => 'Cart cleared',
        'cart_count' => 0,
        'cart_total' => 0
      ]);
      break;

    case 'get':
      $summary = getCartSummary();

      jsonResponse([
        'success' => true,
        'data' => $summary
      ]);
      break;

    default:
      respondError('Invalid action');
  }
} catch (Exception $e) {
  respondError('Server error: ' . $e->getMessage(), 500);
}
