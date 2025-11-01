/**
 * Database Cart Handler
 * Handles add-to-cart functionality with authentication check
 */

$(function () {
  // Check if user is logged in (from window.user set by PHP)
  function isUserLoggedIn() {
    return window.user && window.user.isLoggedIn === true;
  }

  // Show login modal
  function showLoginModal() {
    if (typeof window.openLoginModal === 'function') {
      window.openLoginModal();
    } else {
      // Fallback
      console.error('Login modal function not available');
    }
  }

  // Add item to database cart
  function addToCartDB(productData) {
    return $.ajax({
      url: "/Coffee_St/backend/api/cart.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(productData),
    });
  }

  // Get cart from database
  function getCartDB() {
    return $.ajax({
      url: "/Coffee_St/backend/api/cart.php",
      method: "GET",
    });
  }

  // Update cart count badge in header
  function updateCartBadge(count) {
    const $badge = $(".cart-count, .cart-badge");
    if ($badge.length) {
      $badge.text(count > 0 ? count : "0");
      if (count > 0) {
        $badge.removeClass("hidden");
      }
    }
  }

  // Expose updateCartBadge globally so cart-page.js can use it
  window.updateCartBadge = updateCartBadge;

  // Handle Add to Cart button click
  $(document).on("click", ".add-to-cart", function (e) {
    e.preventDefault();
    const $btn = $(this);

    // Visual feedback
    $btn.addClass("scale-95");
    setTimeout(() => {
      $btn.removeClass("scale-95");
    }, 150);

    // Check if user is logged in
    if (!isUserLoggedIn()) {
      // Show login modal for guests
      showToast("Please login to add items to cart", { type: "warning" });
      showLoginModal();
      return;
    }

    // Get product data from button or card
    const $card = $btn.closest(".product-card, .featured-card");
    const productData = {
      product_id: parseInt($btn.data("product-id") || $card.data("product-id")),
      unit_price: parseFloat($btn.data("price") || $card.data("price")),
      quantity: 1,
      variant_name: $btn.data("size") || "Medium",
    };

    // Validate product data
    if (!productData.product_id || !productData.unit_price) {
      showToast("Invalid product data", "error");
      console.error("Missing product data:", productData);
      return;
    }

    // Add to cart via API
    $btn.prop("disabled", true).addClass("opacity-50");

    addToCartDB(productData)
      .done((response) => {
        if (response.success) {
          showToast("Item added to cart", "success");

          // Update cart badge
          if (response.cart && response.cart.item_count) {
            updateCartBadge(response.cart.item_count);
          }

          // Animate button
          $btn.html(
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Added'
          );

          setTimeout(() => {
            $btn.html(
              '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Add to Cart'
            );
          }, 2000);
        } else {
          showToast(response.message || "Failed to add item", "error");
        }
      })
      .fail((xhr) => {
        const errorMsg =
          xhr.responseJSON && xhr.responseJSON.message
            ? xhr.responseJSON.message
            : "Failed to add item to cart";

        if (xhr.status === 401) {
          showToast("Please login to continue", "info");
          showLoginModal();
        } else {
          showToast(errorMsg, "error");
        }
      })
      .always(() => {
        $btn.prop("disabled", false).removeClass("opacity-50");
      });
  });

  // Load cart count on page load for logged-in users (skip if on cart page)
  const isCartPage = window.location.pathname.includes('/cart.php');

  if (isUserLoggedIn() && !isCartPage) {
    getCartDB()
      .done((response) => {
        if (response.success && response.cart) {
          updateCartBadge(response.cart.item_count);
        }
      })
      .fail(() => {
        // Silent fail - don't show error for cart count
        console.warn("Failed to load cart count");
      });
  }

  // Update cart count after login/register success
  $(document).on("user:loggedIn", function (e, data) {
    if (data.cart_count !== undefined) {
      updateCartBadge(data.cart_count);
    }
  });
});
