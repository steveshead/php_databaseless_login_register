<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Redirect to dashboard page
header("Location: dashboard.php");
exit();
?>
