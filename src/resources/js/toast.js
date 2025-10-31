(function (window, $) {

  "use strict";

  function ensureToastContainer(selector) {
    selector = selector || "#toast-container";
    var $c = $(selector);
    if (!$c.length) {
      $c = $(
        '<div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-3" role="status" aria-live="polite"></div>'
      );
      $("body").append($c);
    }
    return $c;
  }

  function showToast(message, options) {
    options = options || {};
    var duration = typeof options.duration === "number" ? options.duration : 2800;
    var containerSelector = options.containerSelector || "#toast-container";
    var type = options.type || "default";
    var dismissible = !!options.dismissible;
    var onClose = typeof options.onClose === "function" ? options.onClose : null;

    var TYPE_STYLES = {
      "default": {
        classes: "bg-[#30442B] shadow-[#30442B]/20",
        icon: '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>'
      },
      "success": {
        classes: "bg-green-600 shadow-green-600/20",
        icon: '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>'
      },
      "error": {
        classes: "bg-red-600 shadow-red-600/20",
        icon: '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>'
      },
      "info": {
        classes: "bg-blue-600 shadow-blue-600/20",
        icon: '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4m0-12a9 9 0 110 18 9 9 0 010-18" /></svg>'
      },
      "warning": {
        classes: "bg-amber-500 shadow-amber-500/20",
        icon: '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14A2 2 0 004 21h16a2 2 0 001.81-3.14l-8.1-14a2 2 0 00-3.42 0z" /></svg>'
      }
    };
    if (!TYPE_STYLES[type]) type = "default";

    var $container = ensureToastContainer(containerSelector);

    var baseClasses =
      "pointer-events-auto select-none rounded-2xl px-5 py-3 text-sm font-medium text-white " +
      "shadow-xl ring-1 ring-white/15 opacity-0 -translate-y-2 transition duration-300";

    var $toast = $("<div>").addClass(baseClasses);
    $toast.addClass(TYPE_STYLES[type].classes).attr("data-toast-type", type);
    $toast.html(
      '<div class="flex items-center gap-3">' +
      '<span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-white">' +
      TYPE_STYLES[type].icon +
      "</span>" +
      '<span class="leading-tight"></span>' +
      "</div>"
    );
    $toast.find(".leading-tight").text(String(message || ""));

    if (dismissible) {
      var $btn = $(
        '<button type="button" class="ml-2 -mr-1 text-white/70 hover:text-white transition" aria-label="Dismiss">×</button>'
      );
      $btn.on("click", function () {
        startHide();
      });
      $toast.find(".flex.items-center").append($btn);
    }

    $container.append($toast);

    requestAnimationFrame(function () {
      $toast.removeClass("opacity-0 -translate-y-2").addClass("opacity-100 translate-y-0");
    });

    var hideTimer = setTimeout(startHide, duration);
    var removed = false;

    function startHide() {
      if (removed) return;
      $toast.one("transitionend", function () {
        if (removed) return;
        removed = true;
        clearTimeout(hideTimer);
        $toast.remove();
        if (onClose) try { onClose(); } catch (e) { }
      });
      $toast.addClass("opacity-0 -translate-y-2").removeClass("opacity-100 translate-y-0");
      setTimeout(function () {
        if (removed) return;
        removed = true;
        clearTimeout(hideTimer);
        $toast.remove();
        if (onClose) try { onClose(); } catch (e) { }
      }, 1200);
    }

    return $toast;
  }

  window.showToast = showToast;
  if ($ && $.fn) {
    $.showToast = showToast;
  }
})(window, window.jQuery);
