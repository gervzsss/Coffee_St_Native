$(function () {
  function isUserLoggedIn() {
    return window.user && window.user.isLoggedIn === true;
  }

  function showLoginModal() {
    if (typeof window.openLoginModal === 'function') {
      window.openLoginModal();
    } else {
      console.error('Login modal function not available');
    }
  }

  function addToCartDB(productData) {
    return $.ajax({
      url: "/Coffee_St_Native/backend/api/cart.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(productData),
    });
  }

  function getCartDB() {
    return $.ajax({
      url: "/Coffee_St_Native/backend/api/cart.php",
      method: "GET",
    });
  }

  function updateCartBadge(count) {
    const $badge = $(".cart-count, .cart-badge");
    if ($badge.length) {
      $badge.text(count > 0 ? count : "0");
      if (count > 0) {
        $badge.removeClass("hidden");
      }
    }
  }

  window.updateCartBadge = updateCartBadge;

  $(document).on("click", ".add-to-cart", function (e) {
    e.preventDefault();
    const $btn = $(this);

    $btn.addClass("scale-95");
    setTimeout(() => {
      $btn.removeClass("scale-95");
    }, 150);

    if (!isUserLoggedIn()) {
      showToast("Please login to add items to cart", { type: "warning" });
      showLoginModal();
      return;
    }

    const $card = $btn.closest(".product-card, .featured-card");
    const productData = {
      product_id: parseInt($btn.data("product-id") || $card.data("product-id")),
      unit_price: parseFloat($btn.data("price") || $card.data("price")),
      quantity: 1,
      variant_name: $btn.data("size") || "Medium",
    };

    if (!productData.product_id || !productData.unit_price) {
      showToast("Invalid product data", "error");
      console.error("Missing product data:", productData);
      return;
    }

    $btn.prop("disabled", true).addClass("opacity-50");

    addToCartDB(productData)
      .done((response) => {
        if (response.success) {
          showToast("Item added to cart", "success");

          if (response.cart && response.cart.item_count) {
            updateCartBadge(response.cart.item_count);
          }

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

  const isCartPage = window.location.pathname.includes('/cart.php');

  if (isUserLoggedIn() && !isCartPage) {
    getCartDB()
      .done((response) => {
        if (response.success && response.cart) {
          updateCartBadge(response.cart.item_count);
        }
      })
      .fail(() => {
        console.warn("Failed to load cart count");
      });
  }

  $(document).on("user:loggedIn", function (e, data) {
    if (data.cart_count !== undefined) {
      updateCartBadge(data.cart_count);
    }
  });
});
