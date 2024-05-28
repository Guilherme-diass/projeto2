<?php
session_start();
session_unset();
session_destroy();

// Remover cookies de login
setcookie('user_id', '', time() - 3600, "/");
setcookie('user_name', '', time() - 3600, "/");
setcookie('user_type', '', time() - 3600, "/");

header("Location: login.php");
exit();
?>
