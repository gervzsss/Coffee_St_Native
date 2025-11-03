<?php
class Order
{
  public static function create($pdo, $userId, $cartId, $deliveryFee = 0.00)
  {
    try {
      $pdo->beginTransaction();

      $cartTotals = Cart::getCartTotals($pdo, $cartId);
      $cartItems = Cart::getItems($pdo, $cartId);

      if (empty($cartItems)) {
        throw new Exception('Cannot create order from empty cart');
      }

      $stmt = $pdo->prepare("
        INSERT INTO orders 
        (user_id, status, subtotal, delivery_fee, tax_rate, tax_amount, tax, total) 
        VALUES (?, 'pending', ?, ?, ?, ?, ?, ?)
      ");

      $total = $cartTotals['total'] + $deliveryFee;

      $stmt->execute([
        $userId,
        $cartTotals['subtotal'],
        $deliveryFee,
        $cartTotals['tax_rate'],
        $cartTotals['tax'],
        $cartTotals['tax'],
        $total
      ]);

      $orderId = $pdo->lastInsertId();

      $stmt = $pdo->prepare("
        INSERT INTO order_items 
        (order_id, product_id, variant_id, variant_name, price_delta, product_name, unit_price, quantity, line_total) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
      ");

      foreach ($cartItems as $item) {
        $lineTotal = ($item['unit_price'] + $item['price_delta']) * $item['quantity'];
        $stmt->execute([
          $orderId,
          $item['product_id'],
          $item['variant_id'],
          $item['variant_name'],
          $item['price_delta'],
          $item['name'],
          $item['unit_price'],
          $item['quantity'],
          $lineTotal
        ]);
      }

      Cart::markAsConverted($pdo, $cartId);

      $pdo->commit();
      return $orderId;
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
  public static function getUserOrders($pdo, $userId, $limit = 50, $offset = 0)
  {
    $stmt = $pdo->prepare("
      SELECT 
        id,
        status,
        subtotal,
        delivery_fee,
        tax_rate,
        tax,
        total,
        created_at,
        updated_at
      FROM orders
      WHERE user_id = ?
      ORDER BY created_at DESC
      LIMIT ? OFFSET ?
    ");
    $stmt->execute([$userId, $limit, $offset]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public static function getOrderDetails($pdo, $orderId, $userId = null)
  {
    $sql = "
      SELECT 
        o.id,
        o.user_id,
        o.status,
        o.subtotal,
        o.delivery_fee,
        o.tax_rate,
        o.tax,
        o.total,
        o.created_at,
        o.updated_at,
        u.first_name,
        u.last_name,
        u.email,
        u.phone,
        u.address
      FROM orders o
      INNER JOIN users u ON o.user_id = u.id
      WHERE o.id = ?
    ";

    if ($userId !== null) {
      $sql .= " AND o.user_id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$orderId, $userId]);
    } else {
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$orderId]);
    }

    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
      return null;
    }

    $stmt = $pdo->prepare("
      SELECT 
        oi.id,
        oi.product_id,
        oi.variant_id,
        oi.variant_name,
        oi.price_delta,
        oi.product_name,
        oi.unit_price,
        oi.quantity,
        oi.line_total,
        p.image_url,
        p.category
      FROM order_items oi
      LEFT JOIN products p ON oi.product_id = p.id
      WHERE oi.order_id = ?
      ORDER BY oi.created_at ASC
    ");
    $stmt->execute([$orderId]);
    $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $order;
  }

  public static function updateStatus($pdo, $orderId, $status)
  {
    $validStatuses = ['pending', 'paid', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
      throw new Exception('Invalid order status');
    }

    $stmt = $pdo->prepare("
      UPDATE orders 
      SET status = ?, updated_at = NOW() 
      WHERE id = ?
    ");
    return $stmt->execute([$status, $orderId]);
  }

  public static function cancel($pdo, $orderId, $userId = null)
  {
    $sql = "
      UPDATE orders 
      SET status = 'cancelled', updated_at = NOW() 
      WHERE id = ? AND status = 'pending'
    ";

    if ($userId !== null) {
      $sql .= " AND user_id = ?";
      $stmt = $pdo->prepare($sql);
      return $stmt->execute([$orderId, $userId]);
    }

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$orderId]);
  }

  public static function getUserOrderCount($pdo, $userId)
  {
    $stmt = $pdo->prepare("
      SELECT COUNT(*) as total 
      FROM orders 
      WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int) $result['total'];
  }

  public static function find($pdo, $orderId)
  {
    return self::getOrderDetails($pdo, $orderId);
  }
}
