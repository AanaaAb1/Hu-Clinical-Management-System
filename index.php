<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!Helper::isLoggedIn()) {
    Helper::redirect('../login.php', 'Please login to access the dashboard.', 'warning');
}

// Redirect to main dashboard page
header('Location: ../dashboard.php');
exit();
?>