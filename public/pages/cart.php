<?php
if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}
$title = 'Cart - Coffee St.';
?>

<?php include BASE_PATH . "/src/includes/head.php"; ?>

<body class="min-h-screen bg-neutral-50 text-neutral-900 font-sans">

  <?php include BASE_PATH . '/src/includes/header.php'; ?>

  <main class="mx-auto max-w-5xl px-4 py-28">

    <?php include BASE_PATH . '/src/views/cart-content.php'; ?>

  </main>

  <?php include BASE_PATH . '/src/includes/footer.php'; ?>

  <?php include BASE_PATH . '/src/components/auth-modals.php'; ?>

</body>

</html>