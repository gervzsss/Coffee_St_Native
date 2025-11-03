<?php

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/helpers.php';
require_once BASE_PATH . '/backend/db.php';
require_once BASE_PATH . '/backend/models/Product.php';

$pdo = require BASE_PATH . '/backend/db.php';

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

respondError('Method not allowed', 405);
