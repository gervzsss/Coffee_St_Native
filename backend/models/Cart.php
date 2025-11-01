<?php

class Cart
{
  /**
   * Get or create user's active cart
   * Returns cart ID
   */
  public static function getActiveCart($pdo, $userId)
  {
    $stmt = $pdo->prepare("
      SELECT id FROM carts 
      WHERE user_id = ? AND status = 'active' 
      LIMIT 1
    ");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart) {
      return $cart['id'];
    }

    // Create new active cart
    $stmt = $pdo->prepare("
      INSERT INTO carts (user_id, status) 
      VALUES (?, 'active')
    ");
    $stmt->execute([$userId]);
    return $pdo->lastInsertId();
  }

  /**
   * Add item to cart or update quantity if exists
   */
  public static function addItem($pdo, $cartId, $productId, $quantity, $unitPrice, $variantId = null, $variantName = null, $priceDelta = 0.00)
  {
    // Check if item already exists in cart
    $stmt = $pdo->prepare("
      SELECT id, quantity FROM cart_items 
      WHERE cart_id = ? AND product_id = ?
    ");
    $stmt->execute([$cartId, $productId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
      // Update quantity
      $newQuantity = $existing['quantity'] + $quantity;
      $stmt = $pdo->prepare("
        UPDATE cart_items 
        SET quantity = ?, updated_at = NOW() 
        WHERE id = ?
      ");
      $stmt->execute([$newQuantity, $existing['id']]);
      return $existing['id'];
    }

    // Insert new item
    $stmt = $pdo->prepare("
      INSERT INTO cart_items 
      (cart_id, product_id, variant_id, variant_name, quantity, unit_price, price_delta) 
      VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
      $cartId,
      $productId,
      $variantId,
      $variantName,
      $quantity,
      $unitPrice,
      $priceDelta
    ]);
    return $pdo->lastInsertId();
  }

  /**
   * Update item quantity in cart
   */
  public static function updateItemQuantity($pdo, $cartId, $productId, $quantity)
  {
    if ($quantity <= 0) {
      return self::removeItem($pdo, $cartId, $productId);
    }

    $stmt = $pdo->prepare("
      UPDATE cart_items 
      SET quantity = ?, updated_at = NOW() 
      WHERE cart_id = ? AND product_id = ?
    ");
    return $stmt->execute([$quantity, $cartId, $productId]);
  }

  /**
   * Remove item from cart
   */
  public static function removeItem($pdo, $cartId, $productId)
  {
    $stmt = $pdo->prepare("
      DELETE FROM cart_items 
      WHERE cart_id = ? AND product_id = ?
    ");
    return $stmt->execute([$cartId, $productId]);
  }

  /**
   * Get all items in cart with product details
   */
  public static function getItems($pdo, $cartId)
  {
    $stmt = $pdo->prepare("
      SELECT 
        ci.id,
        ci.product_id,
        ci.variant_id,
        ci.variant_name,
        ci.quantity,
        ci.unit_price,
        ci.price_delta,
        p.name,
        p.image_url,
        p.category,
        (ci.unit_price + ci.price_delta) * ci.quantity AS line_total
      FROM cart_items ci
      INNER JOIN products p ON ci.product_id = p.id
      WHERE ci.cart_id = ?
      ORDER BY ci.created_at ASC
    ");
    $stmt->execute([$cartId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Get cart totals (subtotal, tax, total)
   */
  public static function getCartTotals($pdo, $cartId, $taxRate = 0.08)
  {
    $stmt = $pdo->prepare("
      SELECT 
        SUM((unit_price + price_delta) * quantity) AS subtotal
      FROM cart_items
      WHERE cart_id = ?
    ");
    $stmt->execute([$cartId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $subtotal = $result['subtotal'] ?? 0.00;
    $tax = $subtotal * $taxRate;
    $total = $subtotal + $tax;

    return [
      'subtotal' => round($subtotal, 2),
      'tax_rate' => $taxRate,
      'tax' => round($tax, 2),
      'total' => round($total, 2)
    ];
  }

  /**
   * Get cart item count
   */
  public static function getItemCount($pdo, $cartId)
  {
    $stmt = $pdo->prepare("
      SELECT SUM(quantity) AS total_items 
      FROM cart_items 
      WHERE cart_id = ?
    ");
    $stmt->execute([$cartId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int) ($result['total_items'] ?? 0);
  }

  /**
   * Clear all items from cart
   */
  public static function clearCart($pdo, $cartId)
  {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ?");
    return $stmt->execute([$cartId]);
  }

  /**
   * Mark cart as converted (after order creation)
   */
  public static function markAsConverted($pdo, $cartId)
  {
    $stmt = $pdo->prepare("
      UPDATE carts 
      SET status = 'converted', updated_at = NOW() 
      WHERE id = ?
    ");
    return $stmt->execute([$cartId]);
  }

  /**
   * Check if cart is empty
   */
  public static function isEmpty($pdo, $cartId)
  {
    return self::getItemCount($pdo, $cartId) === 0;
  }

  /**
   * Get full cart summary (items + totals)
   */
  public static function getCartSummary($pdo, $cartId)
  {
    $items = self::getItems($pdo, $cartId);
    $totals = self::getCartTotals($pdo, $cartId);
    $itemCount = self::getItemCount($pdo, $cartId);

    return [
      'cart_id' => $cartId,
      'items' => $items,
      'item_count' => $itemCount,
      'subtotal' => $totals['subtotal'],
      'tax_rate' => $totals['tax_rate'],
      'tax' => $totals['tax'],
      'total' => $totals['total'],
      'is_empty' => count($items) === 0
    ];
  }
}
