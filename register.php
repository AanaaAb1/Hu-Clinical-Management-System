<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (Helper::isLoggedIn()) {
    Helper::redirect('dashboard.php');
}

$page_title = "Register - HU CMS";
$error = '';
$success = '';
$form_data = [];

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token
    if (!Helper::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {

        // Collect form data
        $form_data = [
            'first_name' => Helper::sanitize($_POST['first_name'] ?? ''),
            'last_name' => Helper::sanitize($_POST['last_name'] ?? ''),
            'email' => Helper::sanitize($_POST['email'] ?? ''),
            'phone' => Helper::sanitize($_POST['phone'] ?? ''),
            'department' => Helper::sanitize($_POST['department'] ?? ''),
            'role' => Helper::sanitize($_POST['role'] ?? 'staff'),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ];

        // Required fields
        $required = ['first_name', 'last_name', 'email', 'password', 'confirm_password'];
        foreach ($required as $f) {
            if (empty($form_data[$f])) {
                $error = 'Please fill in all required fields';
                break;
            }
        }

        if (!$error) {

            // Validate email
            if (!Helper::isValidEmail($form_data['email'])) {
                $error = 'Please enter a valid email address';
            }

            // Password match
            elseif ($form_data['password'] !== $form_data['confirm_password']) {
                $error = 'Passwords do not match';
            }

            // If OK, register
            if (!$error) {

                unset($form_data['confirm_password']);

                // Register user (NO HASH)
                $result = $auth->register($form_data);

                if ($result['success']) {
                    $success = $result['message'];
                    $form_data = [];
                } else {
                    $error = $result['error'];
                }
            }
        }
    }
}

// Generate CSRF token
$csrf_token = Helper::generateCSRFToken();
include 'includes/header.php';
?>