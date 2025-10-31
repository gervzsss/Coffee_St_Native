<?php
if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';

$title = "Contact - Coffee St.";
?>

<?php include BASE_PATH . "/src/includes/head.php"; ?>

<body class="min-h-screen bg-neutral-50 font-poppins text-neutral-900">

  <?php require_once BASE_PATH . "/src/includes/header.php"; ?>

  <main class="flex flex-col pt-20 md:pt-24">

    <?php include BASE_PATH . "/src/views/contact-content.php"; ?>

  </main>

  <?php require_once BASE_PATH . "/src/includes/footer.php"; ?>

  <script src="/Coffee_St/src/resources/js/contact-form.js" defer></script>

</body>

</html>