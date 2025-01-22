<?php

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: Login.php");
exit();
?>


