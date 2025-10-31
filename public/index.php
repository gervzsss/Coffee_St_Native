<?php
if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 1));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';

$title = "Coffee St. - Your Premium Coffee Destination";
?>

<?php include BASE_PATH . "/src/includes/head.php"; ?>

<body class="min-h-screen bg-neutral-50 text-neutral-900 font-sans">

  <?php require_once BASE_PATH . "/src/includes/header.php"; ?>

  <main class="pt-20 md:pt-24">

    <?php include BASE_PATH . "/src/views/home-content.php"; ?>

  </main>

  <?php require_once BASE_PATH . "/src/includes/footer.php"; ?>

  <script src="/Coffee_St/src/resources/js/home.js" defer></script>

</body>

</html>