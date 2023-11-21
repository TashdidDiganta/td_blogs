<?php 
session_start();
unset($_SESSION['ID']);
unset($_SESSION['username']);
unset($_SESSION['login']);
session_destroy();
header("Location:login.php");
exit;

?>