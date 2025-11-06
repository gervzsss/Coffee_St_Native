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
    <a href="/Coffee_St_Native/public/pages/products.php"
      class="inline-flex items-center px-6 py-3 bg-[#30442B] text-white rounded-lg hover:bg-[#405939] transition-colors">
      Browse Products
    </a>
  </div>
</div>

<!-- Cart Wrapper (visible when cart has items) -->
<div id="cart-wrapper" class="hidden">
  <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3 items-start">
    <!-- Items Column -->
    <section class="lg:col-span-2 space-y-4">
      <div
        class="rounded-lg border bg-white p-4 shadow-sm flex flex-wrap items-center justify-between gap-3 text-neutral-700">
        <div class="flex flex-col gap-1">
          <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-[#30442B] font-medium">
            <input type="checkbox" id="cart-select-all"
              class="h-4 w-4 rounded border-neutral-300 text-[#30442B] focus:ring-[#30442B]">
            <span id="cart-select-all-label">Select All Items (0)</span>
          </label>
          <p id="cart-items-count-label" class="text-xs text-neutral-500">0 items in your cart</p>
        </div>
        <button type="button" id="cart-remove-selected"
          class="inline-flex items-center gap-2 rounded border border-red-500 px-3 py-1.5 text-sm font-medium text-red-600 opacity-60 cursor-not-allowed"
          disabled>
          <span aria-hidden="true">🗑</span>
          <span>Remove Selected</span>
        </button>
      </div>

      <!-- Cart Items Container -->
      <div id="cart-items-container" class="space-y-4"></div>

      <div class="flex items-center gap-4 pt-2">
        <a href="/Coffee_St_Native/public/pages/products.php"
          class="inline-flex items-center px-5 py-2.5 border border-[#30442B] text-[#30442B] rounded-full font-medium hover:text-white hover:bg-[#30442B] transition">
          Continue Shopping
        </a>
      </div>
    </section>

    <!-- Summary Column -->
    <aside class="lg:col-span-1">
      <div id="cart-summary" class="rounded-lg border bg-white p-6 shadow-sm sticky top-28 space-y-4">
        <div>
          <h2 class="text-lg font-semibold text-[#30442B]">Order Summary</h2>
          <p id="cart-summary-label" class="text-sm text-neutral-500">No items selected.</p>
        </div>
        <ul id="cart-summary-items" class="space-y-1 text-sm text-neutral-700"></ul>
        <dl class="space-y-2 text-sm text-neutral-700">
          <div class="flex justify-between py-2 border-b border-neutral-100">
            <dt>Subtotal</dt>
            <dd>₱<span id="cart-subtotal">0.00</span></dd>
          </div>
          <div class="flex justify-between py-2 border-b border-neutral-100">
            <dt>Tax (<span id="cart-tax-rate">0</span>%)</dt>
            <dd>₱<span id="cart-tax">0.00</span></dd>
          </div>
          <div class="flex justify-between items-center text-lg font-semibold text-[#30442B] pt-2">
            <dt>Total</dt>
            <dd>₱<span id="cart-total">0.00</span></dd>
          </div>
        </dl>
        <button
          class="cart-checkout-btn w-full bg-[#30442B] cursor-pointer text-white py-3 rounded-lg hover:bg-[#405939] transition-colors">
          Proceed to Checkout
        </button>
        <p class="text-xs text-neutral-500">Estimated delivery: 25-35 minutes</p>
      </div>
    </aside>
  </div>
</div>

<!-- Cart Item Template (hidden, cloned by JS for each item) -->
<template id="cart-item-template">
  <div class="cart-item rounded-lg border bg-white p-4 shadow-sm" data-product-id="">
    <div class="flex items-center gap-4">
      <label class="flex h-5 w-5 items-center justify-center">
        <input type="checkbox"
          class="cart-item-select h-4 w-4 rounded border-neutral-300 cursor-pointer text-[#30442B] focus:ring-[#30442B]"
          data-product-id="">
      </label>
      <img src="" alt="" class="cart-item-image h-20 w-20 rounded object-cover">
      <div class="flex-1">
        <div class="flex flex-wrap items-start justify-between gap-2">
          <div>
            <h3 class="cart-item-name font-semibold text-lg text-[#30442B]"></h3>
            <p class="cart-item-variant text-sm text-neutral-500 hidden"></p>
            <p class="cart-item-price text-sm text-neutral-500"></p>
          </div>
          <span class="cart-item-total text-lg font-semibold text-[#30442B]"></span>
        </div>
        <div class="mt-4 flex flex-wrap items-center gap-3">
          <div class="flex items-center gap-2">
            <button
              class="cart-qty-decrease h-8 w-8 rounded-full border border-neutral-200 flex items-center justify-center cursor-pointer text-lg leading-none text-[#30442B] hover:border-[#30442B]"
              data-product-id="">-</button>
            <input type="number"
              class="cart-qty-input w-14 text-center border border-neutral-200 rounded-lg text-neutral-700" value="1"
              min="1" data-product-id="" readonly>
            <button
              class="cart-qty-increase h-8 w-8 rounded-full border border-neutral-200 flex items-center justify-center cursor-pointer text-lg leading-none text-[#30442B] hover:border-[#30442B]"
              data-product-id="">+</button>
          </div>
          <button
            class="cart-item-remove text-sm cursor-pointer text-red-600 hover:text-red-700 flex items-center gap-1"
            data-product-id="">
            <span aria-hidden="true">🗑</span>
            Remove
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Load cart page handler -->
<script src="/Coffee_St_Native/src/resources/js/cart-page.js" defer></script>