<?php
// backend/db.php
// Create a PDO instance using backend/config.php (copy config.example.php -> config.php)

if (!file_exists(__DIR__ . '/config.php')) {
  // Friendly error to help setup
  header('Content-Type: application/json', true, 500);
  echo json_encode([
    'success' => false,
    'error' => 'Missing backend/lib/config.php. Copy config.example.php to config.php and set your DB credentials.'
  ]);
  exit;
}

$config = require __DIR__ . '/config.php';

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['db_host'], $config['db_name'], $config['db_charset']);
$user = $config['db_user'];
$pass = $config['db_pass'];
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  header('Content-Type: application/json', true, 500);
  echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
  exit;
}

// Export $pdo to included scripts
return $pdo;
