/**
 * Database Cart Page Handler
 * Handles cart display logic using HTML templates from cart-content.php
 */

$(function () {
  const $loginRequired = $("#login-required-message");
  const $emptyCartMessage = $("#empty-cart-message");
  const $cartContainer = $("#cart-items-container");
  const $cartSummary = $("#cart-summary");

  // Check if user is logged in
  function isUserLoggedIn() {
    return window.user && window.user.isLoggedIn === true;
  }

  // Show login required message
  function showLoginRequired() {
    $loginRequired.removeClass("hidden");
    $emptyCartMessage.addClass("hidden");
    $cartContainer.addClass("hidden");
    $cartSummary.addClass("hidden");
  }

  // Show empty cart message
  function showEmptyCart() {
    $loginRequired.addClass("hidden");
    $emptyCartMessage.removeClass("hidden");
    $cartContainer.addClass("hidden");
    $cartSummary.addClass("hidden");
  }

  // Load cart from database
  function loadCart() {
    if (!isUserLoggedIn()) {
      showLoginRequired();
      return;
    }

    $.ajax({
      url: "/Coffee_St/backend/api/cart.php",
      method: "GET",
      success: function (response) {
        if (response.success && response.cart) {
          renderCart(response.cart);
          // Update cart badge on cart page
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(response.cart.item_count || 0);
          }
        } else {
          showEmptyCart();
          // Update badge to 0 if cart is empty
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(0);
          }
        }
      },
      error: function (xhr) {
        if (xhr.status === 401) {
          showLoginRequired();
        } else {
          showToast("Failed to load cart", { type: "error" });
          showEmptyCart();
        }
      },
    });
  }

  // Render cart items using template
  function renderCart(cart) {
    if (cart.is_empty || !cart.items || cart.items.length === 0) {
      showEmptyCart();
      return;
    }

    // Hide messages, show cart
    $loginRequired.addClass("hidden");
    $emptyCartMessage.addClass("hidden");
    $cartContainer.removeClass("hidden").empty();
    $cartSummary.removeClass("hidden");

    // Get template
    const template = document.getElementById("cart-item-template");

    // Render each item using template
    cart.items.forEach((item) => {
      const clone = template.content.cloneNode(true);
      const $clone = $(clone);

      // Set product ID on container
      $clone.find(".cart-item").attr("data-product-id", item.product_id);

      // Set image
      $clone.find(".cart-item-image")
        .attr("src", item.image_url || "/default-product.jpg")
        .attr("alt", item.name);

      // Set product info
      $clone.find(".cart-item-name").text(item.name);
      $clone.find(".cart-item-price").text(`₱${parseFloat(item.unit_price).toFixed(2)}`);
      $clone.find(".cart-item-total").text(`₱${parseFloat(item.line_total).toFixed(2)}`);

      // Set variant if exists
      if (item.variant_name) {
        $clone.find(".cart-item-variant")
          .text(`Size: ${item.variant_name}`)
          .removeClass("hidden");
      }

      // Set quantity
      $clone.find(".cart-qty-input").val(item.quantity);

      // Set data attributes for buttons
      $clone.find(".cart-qty-decrease").attr("data-product-id", item.product_id);
      $clone.find(".cart-qty-increase").attr("data-product-id", item.product_id);
      $clone.find(".cart-qty-input").attr("data-product-id", item.product_id);
      $clone.find(".cart-item-remove").attr("data-product-id", item.product_id);

      $cartContainer.append($clone);
    });

    // Update summary
    $("#cart-subtotal").text(`₱${parseFloat(cart.subtotal).toFixed(2)}`);
    $("#cart-tax-rate").text((cart.tax_rate * 100).toFixed(0));
    $("#cart-tax").text(`₱${parseFloat(cart.tax).toFixed(2)}`);
    $("#cart-total").text(`₱${parseFloat(cart.total).toFixed(2)}`);
  }

  // Update item quantity
  function updateQuantity(productId, newQuantity) {
    $.ajax({
      url: "/Coffee_St/backend/api/cart.php",
      method: "PUT",
      contentType: "application/json",
      data: JSON.stringify({
        product_id: productId,
        quantity: newQuantity,
      }),
      success: function (response) {
        if (response.success) {
          renderCart(response.cart);
          showToast(response.message, { type: "success" });
          // Update cart badge
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(response.cart.item_count || 0);
          }
        }
      },
      error: function () {
        showToast("Failed to update quantity", { type: "error" });
      },
    });
  }

  // Remove item from cart
  function removeItem(productId) {
    $.ajax({
      url: `/Coffee_St/backend/api/cart.php?product_id=${productId}`,
      method: "DELETE",
      success: function (response) {
        if (response.success) {
          renderCart(response.cart);
          showToast("Item removed from cart", { type: "success" });
          // Update cart badge
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(response.cart.item_count || 0);
          }
        }
      },
      error: function () {
        showToast("Failed to remove item", { type: "error" });
      },
    });
  }

  // Event handlers
  $(document).on("click", "#cart-login-btn", function () {
    if (typeof window.openLoginModal === "function") {
      window.openLoginModal();
    }
  });

  $(document).on("click", ".cart-qty-increase", function () {
    const productId = $(this).data("product-id");
    const $input = $(`.cart-qty-input[data-product-id="${productId}"]`);
    const currentQty = parseInt($input.val()) || 1;
    updateQuantity(productId, currentQty + 1);
  });

  $(document).on("click", ".cart-qty-decrease", function () {
    const productId = $(this).data("product-id");
    const $input = $(`.cart-qty-input[data-product-id="${productId}"]`);
    const currentQty = parseInt($input.val()) || 1;
    if (currentQty > 1) {
      updateQuantity(productId, currentQty - 1);
    }
  });

  $(document).on("click", ".cart-item-remove", function () {
    const productId = $(this).data("product-id");
    if (confirm("Remove this item from cart?")) {
      removeItem(productId);
    }
  });

  $(document).on("click", ".cart-checkout-btn", function () {
    // For now, just show a message. You can implement actual checkout later
    showToast("Checkout functionality coming soon!", { type: "info" });

    // Uncomment when ready to implement checkout:
    // window.location.href = '/Coffee_St/public/pages/checkout.php';
  });

  // Load cart on page load
  loadCart();
});
