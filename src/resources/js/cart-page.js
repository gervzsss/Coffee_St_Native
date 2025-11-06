$(function () {
  const $loginRequired = $("#login-required-message");
  const $emptyCartMessage = $("#empty-cart-message");
  const $cartWrapper = $("#cart-wrapper");
  const $cartContainer = $("#cart-items-container");
  const $cartSummary = $("#cart-summary");
  const $cartItemsLabel = $("#cart-items-count-label");
  const $cartSubtotal = $("#cart-subtotal");
  const $cartTaxRate = $("#cart-tax-rate");
  const $cartTax = $("#cart-tax");
  const $cartTotal = $("#cart-total");
  const $cartSummaryLabel = $("#cart-summary-label");
  const $cartSummaryItems = $("#cart-summary-items");
  const $selectAll = $("#cart-select-all");
  const $selectAllLabel = $("#cart-select-all-label");
  const $removeSelectedButton = $("#cart-remove-selected");
  const selectedItems = new Set();
  let selectionTouched = false;
  window.__latestCart = window.__latestCart || { items: [], tax_rate: 0 };

  function updateRemoveSelectedButton() {
    if (selectedItems.size > 0) {
      $removeSelectedButton.removeClass("opacity-60 cursor-not-allowed").addClass("cursor-pointer").prop("disabled", false);
    } else {
      $removeSelectedButton.addClass("opacity-60 cursor-not-allowed").removeClass("cursor-pointer").prop("disabled", true);
    }
  }

  function updateSelectAllCheckbox(totalItems) {
    if (totalItems === 0) {
      $selectAll.prop("checked", false).prop("indeterminate", false).prop("disabled", true);
      $selectAllLabel.text("Select All Items (0)");
      return;
    }

    $selectAll.prop("disabled", false);
    $selectAllLabel.text(`Select All Items (${totalItems})`);

    if (selectedItems.size === totalItems) {
      $selectAll.prop("checked", true).prop("indeterminate", false);
    } else if (selectedItems.size === 0) {
      $selectAll.prop("checked", false).prop("indeterminate", false);
    } else {
      $selectAll.prop("checked", false).prop("indeterminate", true);
    }
  }

  function calculateSelectedSummary(items, taxRate) {
    let subtotal = 0;
    let count = 0;
    const lines = [];

    items.forEach((item) => {
      const productKey = String(item.product_id);
      if (selectedItems.has(productKey)) {
        subtotal += parseFloat(item.line_total || 0);
        const quantity = item.quantity ? parseInt(item.quantity, 10) : 1;
        count += quantity;
        lines.push({
          name: item.name || "Item",
          quantity,
          total: parseFloat(item.line_total || 0),
        });
      }
    });

    const tax = subtotal * taxRate;
    const total = subtotal + tax;

    return {
      count,
      subtotal,
      tax,
      total,
      lines,
    };
  }

  function updateOrderSummary(cart) {
    const taxRate = cart.tax_rate || 0;
    const summary = calculateSelectedSummary(cart.items || [], taxRate);

    if (summary.count === 0) {
      $cartSummaryLabel.text("No items selected.");
      $cartSummaryItems.empty();
      $cartSubtotal.text("0.00");
      $cartTaxRate.text((taxRate * 100).toFixed(0));
      $cartTax.text("0.00");
      $cartTotal.text("0.00");
      return;
    }

    const labelText = summary.count === 1 ? "1 item selected" : `${summary.count} items selected`;
    $cartSummaryLabel.text(labelText);
    $cartSummaryItems.empty();
    summary.lines.forEach((line) => {
      const $line = $("<li>").addClass("flex items-center justify-between");
      $line.append($("<span>").text(`${line.quantity}x ${line.name}`));
      $line.append($("<span>").text(`₱${line.total.toFixed(2)}`));
      $cartSummaryItems.append($line);
    });
    $cartSubtotal.text(summary.subtotal.toFixed(2));
    $cartTaxRate.text((taxRate * 100).toFixed(0));
    $cartTax.text(summary.tax.toFixed(2));
    $cartTotal.text(summary.total.toFixed(2));
  }

  function isUserLoggedIn() {
    return window.user && window.user.isLoggedIn === true;
  }

  function showLoginRequired() {
    $loginRequired.removeClass("hidden");
    $emptyCartMessage.addClass("hidden");
    $cartWrapper.addClass("hidden");
    $cartSummary.addClass("hidden");
    $cartContainer.empty();
    selectedItems.clear();
    updateRemoveSelectedButton();
    updateSelectAllCheckbox(0);
    window.__latestCart = { items: [], tax_rate: 0 };
    selectionTouched = false;
    $cartSummaryLabel.text("No items selected.");
  }

  function showEmptyCart() {
    $loginRequired.addClass("hidden");
    $emptyCartMessage.removeClass("hidden");
    $cartWrapper.addClass("hidden");
    $cartSummary.addClass("hidden");
    $cartContainer.empty();
    selectedItems.clear();
    updateRemoveSelectedButton();
    updateSelectAllCheckbox(0);
    window.__latestCart = { items: [], tax_rate: 0 };
    selectionTouched = false;
    $cartSummaryLabel.text("No items selected.");
  }

  function loadCart() {
    if (!isUserLoggedIn()) {
      showLoginRequired();
      return;
    }

    $.ajax({
      url: "/Coffee_St_Native/backend/api/cart.php",
      method: "GET",
      success: function (response) {
        if (response.success && response.cart) {
          renderCart(response.cart);
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(response.cart.item_count || 0);
          }
        } else {
          showEmptyCart();
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

  function renderCart(cart) {
    if (cart.is_empty || !cart.items || cart.items.length === 0) {
      showEmptyCart();
      return;
    }

    $loginRequired.addClass("hidden");
    $emptyCartMessage.addClass("hidden");
    $cartWrapper.removeClass("hidden");
    $cartContainer.empty();
    $cartSummary.removeClass("hidden");

    const template = document.getElementById("cart-item-template");

    const currentProductIds = [];
    const shouldSelectAllByDefault = selectedItems.size === 0 && !selectionTouched;

    cart.items.forEach((item) => {
      const productKey = String(item.product_id);
      currentProductIds.push(productKey);
      const clone = template.content.cloneNode(true);
      const $clone = $(clone);

      $clone.find(".cart-item").attr("data-product-id", item.product_id);

      $clone.find(".cart-item-image")
        .attr("src", item.image_url || "/default-product.jpg")
        .attr("alt", item.name || "Product image");

      $clone.find(".cart-item-name").text(item.name);
      $clone.find(".cart-item-price").text(`₱${parseFloat(item.unit_price).toFixed(2)} each`);
      $clone.find(".cart-item-total").text(`₱${parseFloat(item.line_total).toFixed(2)}`);

      if (item.variant_name) {
        $clone.find(".cart-item-variant")
          .text(`Size: ${item.variant_name}`)
          .removeClass("hidden");
      }

      $clone.find(".cart-qty-input").val(item.quantity);

      $clone.find(".cart-qty-decrease, .cart-qty-increase, .cart-qty-input, .cart-item-remove, .cart-item-select")
        .attr("data-product-id", item.product_id);

      if (shouldSelectAllByDefault) {
        selectedItems.add(productKey);
        $clone.find(".cart-item-select").prop("checked", true);
      } else if (selectedItems.has(productKey)) {
        $clone.find(".cart-item-select").prop("checked", true);
      }

      $cartContainer.append($clone);
    });

    Array.from(selectedItems).forEach((id) => {
      if (!currentProductIds.includes(id)) {
        selectedItems.delete(id);
      }
    });

    window.__latestCart = cart;

    const itemCount = cart.item_count ?? (cart.items ? cart.items.length : 0);
    const labelText = itemCount === 1 ? "1 item in your cart" : `${itemCount} items in your cart`;
    $cartItemsLabel.text(labelText);

    updateSelectAllCheckbox(currentProductIds.length);
    updateOrderSummary(cart);
    updateRemoveSelectedButton();
  }

  function updateQuantity(productId, newQuantity) {
    $.ajax({
      url: "/Coffee_St_Native/backend/api/cart.php",
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

  function removeItem(productId) {
    selectedItems.delete(String(productId));
    $.ajax({
      url: `/Coffee_St_Native/backend/api/cart.php?product_id=${productId}`,
      method: "DELETE",
      success: function (response) {
        if (response.success) {
          renderCart(response.cart);
          showToast("Item removed from cart", { type: "success" });
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(response.cart.item_count || 0);
          }
          updateRemoveSelectedButton();
        }
      },
      error: function () {
        showToast("Failed to remove item", { type: "error" });
      },
    });
  }

  $(document).on("click", "#cart-login-btn", function () {
    if (typeof window.openLoginModal === "function") {
      window.openLoginModal();
    }
  });

  $(document).on("change", ".cart-item-select", function () {
    const productId = String($(this).data("product-id"));
    if ($(this).is(":checked")) {
      selectedItems.add(productId);
    } else {
      selectedItems.delete(productId);
    }
    selectionTouched = true;
    const totalItems = $(".cart-item-select").length;
    updateSelectAllCheckbox(totalItems);
    updateRemoveSelectedButton();
    updateOrderSummary(window.__latestCart || { items: [], tax_rate: 0 });
  });

  $(document).on("click", "#cart-remove-selected", function () {
    if (selectedItems.size === 0) {
      return;
    }

    const ids = Array.from(selectedItems).map((id) => parseInt(id, 10)).filter((id) => !Number.isNaN(id));

    $.ajax({
      url: "/Coffee_St_Native/backend/api/cart.php",
      method: "DELETE",
      contentType: "application/json",
      data: JSON.stringify({ product_ids: ids }),
      success: function (response) {
        if (response.success) {
          selectedItems.clear();
          renderCart(response.cart);
          showToast(response.message || "Selected items removed", { type: "success" });
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(response.cart.item_count || 0);
          }
        } else {
          showToast(response.error || "Failed to remove selected items", { type: "error" });
        }
      },
      error: function () {
        showToast("Failed to remove selected items", { type: "error" });
      },
    });
  });

  $(document).on("change", "#cart-select-all", function () {
    const shouldSelect = $(this).is(":checked") || $(this).prop("indeterminate");
    selectedItems.clear();

    if (shouldSelect) {
      $(".cart-item-select").each(function () {
        const productId = String($(this).data("product-id"));
        selectedItems.add(productId);
        $(this).prop("checked", true);
      });
    } else {
      $(".cart-item-select").prop("checked", false);
    }

    selectionTouched = true;
    const totalItems = $(".cart-item-select").length;
    updateSelectAllCheckbox(totalItems);
    updateRemoveSelectedButton();
    updateOrderSummary(window.__latestCart || { items: [], tax_rate: 0 });
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
    // window.location.href = '/Coffee_St_Native/public/pages/checkout.php';
  });

  loadCart();
});
