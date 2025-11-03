<?php
if (session_status() === PHP_SESSION_NONE) {
  ini_set('session.cookie_httponly', '1');
  ini_set('session.use_only_cookies', '1');
  ini_set('session.cookie_samesite', 'Lax');

  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
  }

  session_name('COFFEE_ST_SESSION');
  session_start();
}

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if (!isset($_SESSION['user'])) {
  $_SESSION['user'] = [
    'is_logged_in' => false,
    'id' => null,
    'email' => null,
    'first_name' => null,
    'last_name' => null,
    'full_name' => 'Guest'
  ];
}

function addToCart($productId, $name, $price, $quantity = 1, $image = null, $size = 'Medium')
{
  $itemKey = $productId . '_' . strtolower($size);

  if (isset($_SESSION['cart'][$itemKey])) {
    $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$itemKey] = [
      'product_id' => $productId,
      'name' => $name,
      'price' => floatval($price),
      'quantity' => intval($quantity),
      'size' => $size,
      'image' => $image,
      'added_at' => time()
    ];
  }

  return true;
}

function updateCartQuantity($itemKey, $quantity)
{
  if (isset($_SESSION['cart'][$itemKey])) {
    if ($quantity <= 0) {
      unset($_SESSION['cart'][$itemKey]);
    } else {
      $_SESSION['cart'][$itemKey]['quantity'] = intval($quantity);
    }
    return true;
  }
  return false;
}

function removeFromCart($itemKey)
{
  if (isset($_SESSION['cart'][$itemKey])) {
    unset($_SESSION['cart'][$itemKey]);
    return true;
  }
  return false;
}

function getCart()
{
  return $_SESSION['cart'] ?? [];
}

function getCartCount()
{
  $count = 0;
  foreach ($_SESSION['cart'] as $item) {
    $count += $item['quantity'];
  }
  return $count;
}

function getCartSubtotal()
{
  $subtotal = 0;
  foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
  }
  return $subtotal;
}

function getCartTax()
{
  return getCartSubtotal() * 0.08;
}

function getCartTotal()
{
  return getCartSubtotal() + getCartTax();
}

function clearCart()
{
  $_SESSION['cart'] = [];
}

function isCartEmpty()
{
  return empty($_SESSION['cart']);
}

function getCartSummary()
{
  return [
    'items' => getCart(),
    'item_count' => getCartCount(),
    'subtotal' => getCartSubtotal(),
    'tax' => getCartTax(),
    'total' => getCartTotal()
  ];
}

function loginUser($userId, $email, $firstName, $lastName)
{
  session_regenerate_id(true);

  $_SESSION['user'] = [
    'is_logged_in' => true,
    'id' => $userId,
    'email' => $email,
    'first_name' => $firstName,
    'last_name' => $lastName,
    'full_name' => $firstName . ' ' . $lastName,
    'login_time' => time()
  ];

  return true;
}

function logoutUser()
{
  $_SESSION['user'] = [
    'is_logged_in' => false,
    'id' => null,
    'email' => null,
    'first_name' => null,
    'last_name' => null,
    'full_name' => 'Guest'
  ];

  session_regenerate_id(true);

  return true;
}

function isLoggedIn()
{
  return isset($_SESSION['user']['is_logged_in']) && $_SESSION['user']['is_logged_in'] === true;
}

function getUser()
{
  return $_SESSION['user'] ?? [
    'is_logged_in' => false,
    'id' => null,
    'email' => null,
    'first_name' => null,
    'last_name' => null,
    'full_name' => 'Guest'
  ];
}

function getUserId()
{
  return $_SESSION['user']['id'] ?? null;
}

function getUserEmail()
{
  return $_SESSION['user']['email'] ?? null;
}

function getUserFullName()
{
  return $_SESSION['user']['full_name'] ?? 'Guest';
}

function getUserFirstName()
{
  return $_SESSION['user']['first_name'] ?? 'Guest';
}

function requireAuth($redirectTo = '/Coffee_St/public/index.php')
{
  if (!isLoggedIn()) {
    header("Location: $redirectTo");
    exit;
  }
}

function updateUser($data)
{
  foreach ($data as $key => $value) {
    if (isset($_SESSION['user'][$key])) {
      $_SESSION['user'][$key] = $value;
    }
  }
}
