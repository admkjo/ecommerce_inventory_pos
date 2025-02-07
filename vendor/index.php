<?php
session_start();

// Check if user is logged in 
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// If the user is logged in, show the homepage content
	  header("Location: vendor-panel.php");
	  exit();
?>
