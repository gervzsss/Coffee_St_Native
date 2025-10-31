<?php
if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}
$title = "Products - Coffee St.";
?>

<?php include BASE_PATH . "/src/includes/head.php"; ?>

<body class="font-poppins">

  <?php require_once BASE_PATH . "/src/includes/header.php"; ?>

  <main class="pt-20 md:pt-24 min-h-screen bg-white">

    <?php include BASE_PATH . "/src/views/products-content.php"; ?>

  </main>

  <?php require_once BASE_PATH . "/src/includes/footer.php"; ?>

  <script src="/Coffee_St/src/resources/js/products.js" defer></script>

</body>

</html>