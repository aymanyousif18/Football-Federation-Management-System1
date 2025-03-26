<?php
require __DIR__ . '/includes1/config.php';
session_destroy();
redirect('login1.php');
?>