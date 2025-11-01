<h1 class="text-3xl md:text-4xl font-bold text-[#30442B]">Your Cart</h1>

<!-- Login Required Message (hidden by default, shown by JS if not logged in) -->
<div id="login-required-message" class="mt-8 hidden">
  <div class="rounded-lg border bg-white p-12 shadow-sm text-center">
    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
    </svg>
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Please Login to View Cart</h2>
    <p class="text-gray-600 mb-6">You need to be logged in to add items and view your cart.</p>
    <button id="cart-login-btn"
      class="bg-[#30442B] text-white px-6 py-3 cursor-pointer rounded-lg hover:bg-[#405939] transition-colors">
      Login or Sign Up
    </button>
  </div>
</div>

<!-- Empty Cart Message (hidden by default, shown by JS if cart is empty) -->
<div id="empty-cart-message" class="mt-8 hidden">
  <div class="rounded-lg border bg-white p-12 shadow-sm text-center">
    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
    </svg>
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Your cart is empty</h2>
    <p class="text-gray-600 mb-6">Start shopping to add items to your cart!</p>
    <a href="/Coffee_St/public/pages/products.php"
      class="inline-flex items-center px-6 py-3 bg-[#30442B] text-white rounded-lg hover:bg-[#405939] transition-colors">
      Browse Products
    </a>
  </div>
</div>

<!-- Cart Items Container (populated by JS with data-* attributes) -->
<div id="cart-items-container" class="mt-8 hidden space-y-4"></div>

<!-- Cart Item Template (hidden, cloned by JS for each item) -->
<template id="cart-item-template">
  <div class="cart-item flex items-center gap-4 border-b pb-4" data-product-id="">
    <img src="" alt="" class="cart-item-image w-20 h-20 object-cover rounded">
    <div class="flex-1">
      <h3 class="cart-item-name font-bold text-lg"></h3>
      <p class="cart-item-variant text-sm text-gray-600 hidden"></p>
      <p class="cart-item-price text-[#30442B] font-semibold"></p>
    </div>
    <div class="flex items-center gap-2">
      <button class="cart-qty-decrease bg-gray-200 hover:bg-gray-300 w-8 h-8 rounded" data-product-id="">-</button>
      <input type="number" class="cart-qty-input w-16 text-center border rounded" value="1" min="1" data-product-id=""
        readonly>
      <button class="cart-qty-increase bg-gray-200 hover:bg-gray-300 w-8 h-8 rounded" data-product-id="">+</button>
    </div>
    <div class="text-right">
      <p class="cart-item-total font-bold text-lg"></p>
      <button class="cart-item-remove text-red-600 hover:text-red-800 text-sm" data-product-id="">Remove</button>
    </div>
  </div>
</template>

<!-- Cart Summary (populated by JS) -->
<div id="cart-summary" class="mt-8 hidden">
  <div class="bg-gray-50 p-6 rounded-lg">
    <h3 class="text-xl font-bold mb-4">Order Summary</h3>
    <div class="space-y-2">
      <div class="flex justify-between">
        <span>Subtotal:</span>
        <span id="cart-subtotal">₱0.00</span>
      </div>
      <div class="flex justify-between">
        <span>Tax (<span id="cart-tax-rate">0</span>%):</span>
        <span id="cart-tax">₱0.00</span>
      </div>
      <div class="flex justify-between text-xl font-bold border-t pt-2">
        <span>Total:</span>
        <span id="cart-total" class="text-[#30442B]">₱0.00</span>
      </div>
    </div>
    <button
      class="cart-checkout-btn w-full bg-[#30442B] text-white py-3 rounded-lg mt-4 hover:bg-[#405939] transition-colors">
      Proceed to Checkout
    </button>
  </div>
</div>

<!-- Action Buttons -->
<div class="flex items-center gap-4 mt-8">
  <a href="/Coffee_St/public/pages/products.php"
    class="inline-flex items-center px-5 py-2.5 border border-[#30442B] text-[#30442B] rounded-full font-medium hover:text-white hover:bg-[#30442B] transition">
    Continue Shopping
  </a>
</div>

<!-- Load cart page handler -->
<script src="/Coffee_St/src/resources/js/cart-page.js" defer></script>