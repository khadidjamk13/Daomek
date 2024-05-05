<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to accueil.php
header("location: accueil.php");
exit;
?>
