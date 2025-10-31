<?php
// backend/models/Product.php
class Product
{
  public static function all(PDO $pdo)
  {
    // Select all columns to allow flexible schema (e.g., category, image_url, cloudinary_public_id)
    $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
    return $stmt->fetchAll();
  }

  public static function find(PDO $pdo, $id)
  {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([(int) $id]);
    return $stmt->fetch();
  }

  public static function create(PDO $pdo, array $data)
  {
    $stmt = $pdo->prepare('INSERT INTO products (name, price, sku, description) VALUES (?, ?, ?, ?)');
    $stmt->execute([
      $data['name'] ?? null,
      $data['price'] ?? 0,
      $data['sku'] ?? null,
      $data['description'] ?? null,
    ]);
    return (int) $pdo->lastInsertId();
  }

  public static function update(PDO $pdo, $id, array $data)
  {
    $stmt = $pdo->prepare('UPDATE products SET name = ?, price = ?, sku = ?, description = ? WHERE id = ?');
    return $stmt->execute([
      $data['name'] ?? null,
      $data['price'] ?? 0,
      $data['sku'] ?? null,
      $data['description'] ?? null,
      (int) $id,
    ]);
  }

  public static function delete(PDO $pdo, $id)
  {
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    return $stmt->execute([(int) $id]);
  }
}
