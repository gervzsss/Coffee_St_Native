$(function () {
  var $contactForm = $("#contact-form");
  if (!$contactForm.length) return;

  function setFieldError($field, message) {
    var id = $field.attr("id");
    var $error = $('.contact-error[data-error-for="' + id + '"]');
    if (!$error.length) return;

    if (message) {
      $error.text(message).removeClass("hidden");
      $field.addClass("border-red-400 focus:border-red-400 focus:ring-red-400");
    } else {
      $error.text("").addClass("hidden");
      $field.removeClass(
        "border-red-400 focus:border-red-400 focus:ring-red-400",
      );
    }
  }

  var nameRegex = /^[A-Za-z\s.\-']+$/;
  var emailRegex = /^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/;

  var validators = {
    "contact-name": function () {
      var value = $.trim($("#contact-name").val());
      if (!value) return "Name is required.";
      if (!nameRegex.test(value)) return "Please enter a valid name.";
      return null;
    },
    "contact-email": function () {
      var value = $.trim($("#contact-email").val());
      if (!value) return "Email is required.";
      if (!emailRegex.test(value)) return "Please enter a valid email address.";
      return null;
    },
    "contact-subject": function () {
      var value = $.trim($("#contact-subject").val());
      if (!value) return "Subject is required.";
      if (value.length < 3) return "Subject should be at least 3 characters.";
      return null;
    },
    "contact-message": function () {
      var value = $.trim($("#contact-message").val());
      if (!value) return "Message is required.";
      if (value.length < 10)
        return "Message should be at least 10 characters.";
      return null;
    },
  };

  var fieldSelector =
    "#contact-name, #contact-email, #contact-subject, #contact-message";

  var formSubmitted = false;
  var fieldInteraction = {};

  $contactForm.on("input", fieldSelector, function () {
    var $field = $(this);
    var id = $field.attr("id");
    fieldInteraction[id] = true;
    var validator = validators[id];
    if (validator) {
      var error = validator();
      setFieldError($field, error);
    }
  });

  $contactForm.on("blur", fieldSelector, function () {
    var $field = $(this);
    var id = $field.attr("id");
    if (!formSubmitted && !fieldInteraction[id]) return;
    var validator = validators[id];
    if (validator) {
      var error = validator();
      setFieldError($field, error);
    }
  });

  $contactForm.on("keydown", "input, textarea", function (e) {
    var tagName = $(this).prop("tagName");
    if (e.key === "Enter" && tagName !== "TEXTAREA") {
      e.preventDefault();
    }
  });

  $contactForm.on("submit", function (e) {
    e.preventDefault();
    formSubmitted = true;

    var allValid = true;
    $.each(validators, function (id, validator) {
      var $field = $("#" + id);
      var error = validator();
      setFieldError($field, error);
      if (error) {
        allValid = false;
      }
    });

    if (!allValid) return;

    if (typeof window.showToast === "function") window.showToast("Message submitted successfully!");

    $contactForm.trigger("reset");
    $contactForm
      .find(".contact-error")
      .text("")
      .addClass("hidden");
    $contactForm
      .find("input, textarea")
      .removeClass("border-red-400 focus:border-red-400 focus:ring-red-400");

    formSubmitted = false;
    $.each(fieldInteraction, function (key) {
      fieldInteraction[key] = false;
    });
  });
});
