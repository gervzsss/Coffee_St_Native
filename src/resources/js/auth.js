$(document).ready(function () {
  let formSubmitted = false;

  // ============================================
  // VALIDATION PATTERNS & HELPERS
  // ============================================

  const PATTERNS = {
    name: /^[A-Za-z\s.\-']+$/,
    email: /^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/,
    phone: /^\+?\d+$/
  };

  function setFieldError($field, message) {
    const id = $field.attr('id');
    const $err = $(`[data-error-for="${id}"]`);
    if (message) {
      $err.text(message).removeClass('hidden');
      $field.addClass('border-red-400 focus:border-red-400 focus:ring-red-400');
    } else {
      $err.text('').addClass('hidden');
      $field.removeClass('border-red-400 focus:border-red-400 focus:ring-red-400');
    }
  }

  // ============================================
  // VALIDATION FUNCTIONS
  // ============================================

  const validators = {
    // Login validators
    loginEmail: function () {
      const $f = $('#login-email');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'Email is required.'), false);
      if (!PATTERNS.email.test(v)) return (setFieldError($f, 'Enter a valid email.'), false);
      setFieldError($f, null);
      return true;
    },
    loginPassword: function () {
      const $f = $('#login-password');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'Password is required.'), false);
      if (v.length < 6) return (setFieldError($f, 'Minimum 6 characters.'), false);
      setFieldError($f, null);
      return true;
    },

    // Signup validators
    firstName: function () {
      const $f = $('#reg-first');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'First name required.'), false);
      if (!PATTERNS.name.test(v)) return (setFieldError($f, 'Invalid characters.'), false);
      setFieldError($f, null);
      return true;
    },
    lastName: function () {
      const $f = $('#reg-last');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'Last name required.'), false);
      if (!PATTERNS.name.test(v)) return (setFieldError($f, 'Invalid characters.'), false);
      setFieldError($f, null);
      return true;
    },
    address: function () {
      const $f = $('#reg-address');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'Address required.'), false);
      setFieldError($f, null);
      return true;
    },
    email: function () {
      const $f = $('#reg-email');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'Email required.'), false);
      if (!PATTERNS.email.test(v)) return (setFieldError($f, 'Enter a valid email.'), false);
      setFieldError($f, null);
      return true;
    },
    phone: function () {
      const $f = $('#reg-phone');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'Contact number required.'), false);
      if (!PATTERNS.phone.test(v)) return (setFieldError($f, 'Digits only (optional +).'), false);
      setFieldError($f, null);
      return true;
    },
    password: function () {
      const $f = $('#reg-pass');
      const v = $.trim($f.val());
      if (!v) return (setFieldError($f, 'Password required.'), false);
      if (v.length < 6) return (setFieldError($f, 'Minimum 6 characters.'), false);
      setFieldError($f, null);
      validators.passwordConfirm(); // Re-validate confirmation
      return true;
    },
    passwordConfirm: function () {
      const $f = $('#reg-pass-confirm');
      const v = $.trim($f.val());
      const p = $.trim($('#reg-pass').val());
      if (!v) return (setFieldError($f, 'Please confirm password.'), false);
      if (v !== p) return (setFieldError($f, 'Passwords do not match.'), false);
      setFieldError($f, null);
      return true;
    }
  };

  // ============================================
  // MODAL MANAGEMENT
  // ============================================

  function animateIn($modal) {
    $modal.show();
    requestAnimationFrame(() => {
      $modal.removeClass('opacity-0 scale-95').addClass('opacity-100 scale-100');
    });
  }

  function animateOut($modal) {
    $modal.addClass('opacity-0 scale-95').removeClass('opacity-100 scale-100');
    setTimeout(() => $modal.hide(), 180);
  }

  function updateScrollShadows($el) {
    const top = $el.scrollTop();
    const max = $el.prop('scrollHeight') - $el.innerHeight() - 1;
    const atTop = top <= 0;
    const atBottom = top >= max;
    $el.find('.shadow-top').toggleClass('opacity-0', atTop).toggleClass('opacity-100', !atTop);
    $el.find('.shadow-bottom').toggleClass('opacity-0', atBottom).toggleClass('opacity-100', !atBottom);
  }

  function openModal(modalId) {
    const $overlay = $('#modal-overlay');
    $overlay.removeClass('hidden').css('display', 'flex');
    $('body').addClass('overflow-hidden');
    $('.modal-panel').each(function () { $(this).hide(); });

    const $modal = $(modalId);
    $modal.addClass('opacity-0 scale-95');
    animateIn($modal);
    $modal.find('input').first().focus();
    updateScrollShadows($modal);
    $modal.off('scroll.__shadow').on('scroll.__shadow', function () {
      updateScrollShadows($(this));
    });
  }

  // Expose openModal globally for cart-handler.js
  window.openLoginModal = function () {
    openModal('#login-modal');
  };

  window.openSignupModal = function () {
    openModal('#signup-modal');
  };

  function closeModals() {
    const $overlay = $('#modal-overlay');
    $('.modal-panel:visible').each(function () {
      $(this).off('scroll.__shadow');
      animateOut($(this));
    });

    setTimeout(() => {
      $overlay.addClass('hidden').hide();
      $('body').removeClass('overflow-hidden');
      formSubmitted = false;
      $overlay.find('input').val('');
      $overlay.find('[data-error-for]').text('').addClass('hidden');
      $overlay.find('input').removeClass('border-red-400 focus:border-red-400 focus:ring-red-400');
    }, 200);
  }

  // ============================================
  // MODAL EVENT HANDLERS
  // ============================================

  $(document).on('click', '#open-login, [data-open-login="login"]', function (e) {
    e.preventDefault();
    $('#signup-modal').hide();
    openModal('#login-modal');
  });

  $(document).on('click', '#open-signup', function (e) {
    e.preventDefault();
    $('#login-modal').hide();
    openModal('#signup-modal');
  });

  $(document).on('click', '#close-login, #close-signup', function (e) {
    e.preventDefault();
    closeModals();
  });

  $(document).on('click', '#switch-to-signup', function (e) {
    e.preventDefault();
    $('#login-modal').hide();
    openModal('#signup-modal');
  });

  $(document).on('click', '#switch-to-login', function (e) {
    e.preventDefault();
    $('#signup-modal').hide();
    openModal('#login-modal');
  });

  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') closeModals();
  });

  // ============================================
  // PASSWORD TOGGLE
  // ============================================

  $(document).on('click', '.password-toggle', function (e) {
    e.preventDefault();
    const $btn = $(this);
    const $input = $($btn.attr('data-target'));
    if (!$input.length) return;

    const isPassword = $input.attr('type') === 'password';
    $input.attr('type', isPassword ? 'text' : 'password');

    const $svg = $btn.find('svg');
    if (isPassword) {
      $svg.html(
        '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.73 6.73A9.77 9.77 0 0 1 12 6c4.477 0 8.268 2.943 9.542 7a9.77 9.77 0 0 1-1.566 2.566M17.94 17.94A9.77 9.77 0 0 1 12 19c-4.477 0-8.268-2.943-9.542-7a9.77 9.77 0 0 1 1.566-2.566" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 0 1-6 0" />'
      );
    } else {
      $svg.html(
        '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />'
      );
    }
  });

  // ============================================
  // REAL-TIME VALIDATION
  // ============================================

  $(document).on('input', '#login-email', validators.loginEmail);
  $(document).on('input', '#login-password', validators.loginPassword);
  $(document).on('input', '#reg-first', validators.firstName);
  $(document).on('input', '#reg-last', validators.lastName);
  $(document).on('input', '#reg-address', validators.address);
  $(document).on('input', '#reg-email', validators.email);
  $(document).on('input', '#reg-phone', validators.phone);
  $(document).on('input', '#reg-pass', validators.password);
  $(document).on('input', '#reg-pass-confirm', validators.passwordConfirm);

  // Blur validation (only after form submission)
  $(document).on('blur', 'input', function () {
    if (!formSubmitted) return;
    const id = $(this).attr('id');
    const validatorMap = {
      'login-email': validators.loginEmail,
      'login-password': validators.loginPassword,
      'reg-first': validators.firstName,
      'reg-last': validators.lastName,
      'reg-address': validators.address,
      'reg-email': validators.email,
      'reg-phone': validators.phone,
      'reg-pass': validators.password,
      'reg-pass-confirm': validators.passwordConfirm
    };
    if (validatorMap[id]) validatorMap[id]();
  });

  // ============================================
  // FORM SUBMISSION - LOGIN
  // ============================================

  $('#login-form').on('submit', function (e) {
    e.preventDefault();
    formSubmitted = true;

    const email = $('#login-email').val().trim();
    const password = $('#login-password').val();

    // Validate all fields
    const isEmailValid = validators.loginEmail();
    const isPasswordValid = validators.loginPassword();

    if (!isEmailValid || !isPasswordValid) return;

    // Submit login
    $.ajax({
      url: '/Coffee_St/backend/api/auth.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ action: 'login', email, password }),
      success: function (response) {
        if (response.success) {
          const userName = response.user && response.user.first_name
            ? response.user.first_name
            : 'back';

          // Set global user object
          window.user = {
            isLoggedIn: true,
            ...response.user
          };

          // Trigger login event for cart handler
          $(document).trigger('user:loggedIn', {
            user: response.user,
            cart_count: response.cart_count || 0
          });

          if (typeof window.showToast === 'function') {
            window.showToast(`Welcome back, ${userName}!`, { type: 'success', duration: 3000 });
          }

          setTimeout(() => window.location.reload(), 1500);
        }
      },
      error: function (xhr) {
        const response = xhr.responseJSON || {};
        const errorMsg = response.error || 'Login failed. Please try again.';
        setFieldError($('#login-password'), errorMsg);
      }
    });
  });

  // ============================================
  // FORM SUBMISSION - SIGNUP
  // ============================================

  $('#signup-form').on('submit', function (e) {
    e.preventDefault();
    formSubmitted = true;

    // Validate all fields
    const isValid = validators.firstName() &&
      validators.lastName() &&
      validators.address() &&
      validators.email() &&
      validators.phone() &&
      validators.password() &&
      validators.passwordConfirm();

    if (!isValid) return;

    const formData = {
      first_name: $('#reg-first').val().trim(),
      last_name: $('#reg-last').val().trim(),
      address: $('#reg-address').val().trim(),
      email: $('#reg-email').val().trim(),
      phone: $('#reg-phone').val().trim(),
      password: $('#reg-pass').val()
    };

    // Submit registration
    $.ajax({
      url: '/Coffee_St/backend/api/auth.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ action: 'register', ...formData }),
      success: function (response) {
        if (response.success) {
          const userName = response.user && response.user.first_name
            ? response.user.first_name
            : 'there';

          // Set global user object
          window.user = {
            isLoggedIn: true,
            ...response.user
          };

          // Trigger login event for cart handler
          $(document).trigger('user:loggedIn', {
            user: response.user,
            cart_count: response.cart_count || 0
          });

          if (typeof window.showToast === 'function') {
            window.showToast(`Welcome to Coffee St., ${userName}!`, { type: 'success', duration: 3000 });
          }

          setTimeout(() => window.location.reload(), 1500);
        }
      },
      error: function (xhr) {
        const response = xhr.responseJSON || {};
        const errorMsg = response.error || 'Registration failed. Please try again.';

        if (errorMsg.includes('email')) {
          setFieldError($('#reg-email'), errorMsg);
        } else {
          setFieldError($('#reg-pass-confirm'), errorMsg);
        }
      }
    });
  });

  // ============================================
  // LOGOUT HANDLER
  // ============================================

  $(document).on('click', '#logout-btn', function (e) {
    e.preventDefault();

    $.ajax({
      url: '/Coffee_St/backend/api/auth.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ action: 'logout' }),
      success: function (response) {
        if (response.success) {
          // Clear global user object
          window.user = { isLoggedIn: false };

          showToast('Logged out successfully', { type: 'success', duration: 2000 });

          // Redirect to home after short delay
          setTimeout(() => {
            window.location.href = '/Coffee_St/public/index.php';
          }, 1000);
        }
      },
      error: function () {
        // Even if error, still redirect (session might be cleared)
        window.location.href = '/Coffee_St/public/index.php';
      }
    });
  });
});
