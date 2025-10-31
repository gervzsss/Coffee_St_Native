<?php

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/backend/helpers/sessions.php';

logoutUser();

header('Location: /Coffee_St/public/index.php');
exit;
