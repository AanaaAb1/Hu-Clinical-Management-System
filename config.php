<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'haramaya_cms');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site Configuration
define('SITE_NAME', 'Haramaya University Clinical Management System');
define('SITE_URL', 'http://localhost/haramaya-cms');
define('ADMIN_EMAIL', 'admin@hu-cms.edu.et');

// Security Configuration
define('ENCRYPTION_KEY', 'hu-cms-secure-key-2024');
define('SESSION_TIMEOUT', 1800); // 30 minutes

// File Upload Configuration
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone
date_default_timezone_set('Africa/Addis_Ababa');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>