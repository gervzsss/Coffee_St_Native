<?php
$cartSummary = getCartSummary();
$cartItems = $cartSummary['items'];
$isEmpty = isCartEmpty();
?>

<h1 class="text-3xl md:text-4xl font-bold text-[#30442B]">Your Cart</h1>
<p class="mt-4 text-neutral-700">
  <?php if ($isEmpty): ?>
    Your cart is currently empty. Start shopping to add items!
  <?php else: ?>
    You have <?php echo $cartSummary['item_count']; ?> item(s) in your cart.
  <?php endif; ?>
</p>

<section class="mt-8 grid gap-6">
  <?php if ($isEmpty): ?>
    <div class="rounded-lg border bg-white p-6 shadow-sm">
      <p class="text-neutral-600">No items in your cart yet.</p>
    </div>
  <?php else: ?>
    <!-- Cart Items -->
    <div class="rounded-lg border bg-white shadow-sm overflow-hidden">
      <?php foreach ($cartItems as $itemKey => $item): ?>
        <div class="p-6 border-b last:border-b-0 flex items-center gap-6">
          <!-- Product Image -->
          <?php if (!empty($item['image'])): ?>
            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"
              class="w-20 h-20 object-cover rounded-lg">
          <?php else: ?>
            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
              <span class="text-gray-400 text-xs">No image</span>
            </div>
          <?php endif; ?>

          <!-- Product Details -->
          <div class="flex-1">
            <h3 class="font-semibold text-lg text-[#30442B]"><?php echo htmlspecialchars($item['name']); ?></h3>
            <p class="text-sm text-neutral-600">Size: <?php echo htmlspecialchars($item['size']); ?></p>
            <p class="text-sm font-medium text-neutral-800 mt-1">₱<?php echo number_format($item['price'], 2); ?></p>
          </div>

          <!-- Quantity Controls -->
          <div class="flex items-center gap-3">
            <button onclick="updateQuantity('<?php echo $itemKey; ?>', <?php echo $item['quantity'] - 1; ?>)"
              class="w-8 h-8 rounded-full border-2 border-[#30442B] text-[#30442B] hover:bg-[#30442B] hover:text-white transition">
              -
            </button>
            <span class="w-12 text-center font-semibold"><?php echo $item['quantity']; ?></span>
            <button onclick="updateQuantity('<?php echo $itemKey; ?>', <?php echo $item['quantity'] + 1; ?>)"
              class="w-8 h-8 rounded-full border-2 border-[#30442B] text-[#30442B] hover:bg-[#30442B] hover:text-white transition">
              +
            </button>
          </div>

          <!-- Subtotal -->
          <div class="w-24 text-right">
            <p class="font-bold text-lg text-[#30442B]">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
            </p>
          </div>

          <!-- Remove Button -->
          <button onclick="removeItem('<?php echo $itemKey; ?>')" class="text-red-600 hover:text-red-800 transition p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Cart Summary -->
    <div class="rounded-lg border bg-white p-6 shadow-sm">
      <h2 class="text-xl font-bold text-[#30442B] mb-4">Order Summary</h2>
      <div class="space-y-2">
        <div class="flex justify-between text-neutral-700">
          <span>Subtotal:</span>
          <span>₱<?php echo number_format($cartSummary['subtotal'], 2); ?></span>
        </div>
        <div class="flex justify-between text-neutral-700">
          <span>Tax (8%):</span>
          <span>₱<?php echo number_format($cartSummary['tax'], 2); ?></span>
        </div>
        <div class="border-t pt-2 mt-2 flex justify-between font-bold text-lg text-[#30442B]">
          <span>Total:</span>
          <span>₱<?php echo number_format($cartSummary['total'], 2); ?></span>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Action Buttons -->
  <div class="flex items-center gap-4">
    <a href="/Coffee_St/public/pages/products.php"
      class="inline-flex items-center px-5 py-2.5 border border-[#30442B] text-[#30442B] rounded-full font-medium hover:text-white hover:bg-[#30442B] transition">
      Continue Shopping
    </a>
    <?php if (!$isEmpty): ?>
      <button onclick="clearCart()"
        class="inline-flex items-center px-5 py-2.5 border border-red-600 text-red-600 rounded-full font-medium hover:text-white hover:bg-red-600 transition">
        Clear Cart
      </button>
      <button
        class="inline-flex items-center px-5 py-2.5 bg-[#30442B] text-white rounded-full font-medium hover:bg-[#3d5a38] transition">
        Proceed to Checkout
      </button>
    <?php else: ?>
      <button disabled
        class="inline-flex items-center px-5 py-2.5 bg-gray-300 text-gray-600 rounded-full font-medium cursor-not-allowed">
        Checkout (cart is empty)
      </button>
    <?php endif; ?>
  </div>
</section>

<script>
  function updateQuantity(itemKey, quantity) {
    fetch('/Coffee_St/backend/api/cart-update.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'update', item_key: itemKey, quantity: quantity })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Failed to update cart');
        }
      });
  }

  function removeItem(itemKey) {
    if (confirm('Remove this item from cart?')) {
      fetch('/Coffee_St/backend/api/cart-update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'remove', item_key: itemKey })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Failed to remove item');
          }
        });
    }
  }

  function clearCart() {
    if (confirm('Clear all items from cart?')) {
      fetch('/Coffee_St/backend/api/cart-update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'clear' })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Failed to clear cart');
          }
        });
    }
  }
</script>