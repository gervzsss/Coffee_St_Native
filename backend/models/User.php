<?php
class User
{
  public static function all(PDO $pdo)
  {
    $stmt = $pdo->query('SELECT id, email, first_name, last_name, phone, address, created_at, updated_at FROM users ORDER BY id DESC');
    return $stmt->fetchAll();
  }
  public static function find(PDO $pdo, $id)
  {
    $stmt = $pdo->prepare('SELECT id, email, first_name, last_name, phone, address, created_at, updated_at FROM users WHERE id = ?');
    $stmt->execute([(int) $id]);
    return $stmt->fetch();
  }
  public static function findByEmail(PDO $pdo, $email)
  {
    $stmt = $pdo->prepare('SELECT id, email, first_name, last_name, phone, address, password, created_at, updated_at FROM users WHERE email = ?');
    $stmt->execute([trim($email)]);
    return $stmt->fetch();
  }
  public static function emailExists(PDO $pdo, $email)
  {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([trim($email)]);
    return $stmt->fetch() !== false;
  }
  public static function create(PDO $pdo, array $data)
  {
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('
      INSERT INTO users (email, password, first_name, last_name, phone, address, created_at)
      VALUES (?, ?, ?, ?, ?, ?, NOW())
    ');

    $stmt->execute([
      trim($data['email']),
      $hashedPassword,
      trim($data['first_name']),
      trim($data['last_name']),
      trim($data['phone'] ?? ''),
      trim($data['address'] ?? ''),
    ]);

    return (int) $pdo->lastInsertId();
  }
  public static function update(PDO $pdo, $id, array $data)
  {
    $stmt = $pdo->prepare('
      UPDATE users 
      SET email = ?, first_name = ?, last_name = ?, phone = ?, address = ?, updated_at = NOW()
      WHERE id = ?
    ');

    return $stmt->execute([
      trim($data['email'] ?? ''),
      trim($data['first_name'] ?? ''),
      trim($data['last_name'] ?? ''),
      trim($data['phone'] ?? ''),
      trim($data['address'] ?? ''),
      (int) $id,
    ]);
  }
  public static function updatePassword(PDO $pdo, $id, $newPassword)
  {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?');
    return $stmt->execute([$hashedPassword, (int) $id]);
  }
  public static function verifyPassword(PDO $pdo, $email, $password)
  {
    $user = self::findByEmail($pdo, $email);

    if (!$user) {
      return false;
    }

    return password_verify($password, $user['password']) ? $user : false;
  }
  public static function delete(PDO $pdo, $id)
  {
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    return $stmt->execute([(int) $id]);
  }
}
