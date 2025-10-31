<?php
// backend/api/products.php
// Basic products API. Supports GET (list or single), POST (create), PUT (update), DELETE (delete)

require __DIR__ . '/../lib/helpers.php';

// Load PDO
$pdo = require __DIR__ . '/../lib/db.php';

// Load model
require __DIR__ . '/../models/Product.php';

// Simple router by HTTP method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $id = isset($_GET['id']) ? $_GET['id'] : null;
  if ($id) {
    $item = Product::find($pdo, $id);
    if (!$item)
      respondError('Product not found', 404);
    jsonResponse(['success' => true, 'data' => $item]);
  } else {
    $items = Product::all($pdo);
    jsonResponse(['success' => true, 'data' => $items]);
  }
}

if ($method === 'POST') {
  $data = getJsonInput();
  if (empty($data['name']))
    respondError('Name is required', 400);
  $id = Product::create($pdo, $data);
  jsonResponse(['success' => true, 'id' => $id], 201);
}

if ($method === 'PUT' || $method === 'PATCH') {
  // Expect ?id=1
  $id = isset($_GET['id']) ? $_GET['id'] : null;
  if (!$id)
    respondError('Missing id', 400);
  $data = getJsonInput();
  Product::update($pdo, $id, $data);
  jsonResponse(['success' => true]);
}

if ($method === 'DELETE') {
  $id = isset($_GET['id']) ? $_GET['id'] : null;
  if (!$id)
    respondError('Missing id', 400);
  Product::delete($pdo, $id);
  jsonResponse(['success' => true]);
}

// Fallback
respondError('Method not allowed', 405);
