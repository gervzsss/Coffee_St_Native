$(function () {
  $("#menuToggle").on("click", function () {
    $("#menu").toggleClass("hidden");
  });

  $("#mobile-menu-button").on("click", function () {
    $("#mobile-menu").slideToggle();
  });

  $(window).on("scroll", function () {
    var scrollTop = $(window).scrollTop();
    var docHeight = $(document).height() - $(window).height();
    var progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
    $("#scroll-progress").css("width", progress + "%");
  });

});