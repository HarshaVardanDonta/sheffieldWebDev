<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to sign-in page
header("location: sign-in.php");
exit;
?>