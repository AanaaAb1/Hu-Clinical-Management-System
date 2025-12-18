<?php
require_once 'config.php';
require_once 'functions.php';

// Check session timeout if logged in
if (Helper::isLoggedIn()) {
    Helper::checkSessionTimeout();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Flash Messages -->
    <?php
    $flash = Helper::getFlashMessage();
    if ($flash): ?>
        <div class="flash-message flash-<?php echo $flash['type']; ?>">
            <div class="container">
                <span><?php echo htmlspecialchars($flash['message']); ?></span>
                <button class="flash-close">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <i class="fas fa-hospital-alt"></i>
                    <div class="logo-text">
                        <span class="logo-title">HU CMS</span>
                        <span class="logo-subtitle">Clinical Management System</span>
                    </div>
                </a>

                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="nav-container" id="navContainer">
                    <ul class="nav-menu">
                        <li><a href="index.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                                <i class="fas fa-home"></i> Home
                            </a></li>
                        <li><a href="pages/about.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                                <i class="fas fa-info-circle"></i> About
                            </a></li>
                        <li><a href="pages/features.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) == 'features.php' ? 'active' : ''; ?>">
                                <i class="fas fa-star"></i> Features
                            </a></li>
                        <li><a href="pages/documentation.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) == 'documentation.php' ? 'active' : ''; ?>">
                                <i class="fas fa-book"></i> Documentation
                            </a></li>
                        <li><a href="pages/contact.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                                <i class="fas fa-envelope"></i> Contact
                            </a></li>
                    </ul>
                    <div class="nav-actions">
                        <?php if (Helper::isLoggedIn()): ?>
                            <div class="user-dropdown">
                                <button class="user-btn">
                                    <i class="fas fa-user-circle"></i>
                                    <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                    <a href="dashboard/profile.php"><i class="fas fa-user"></i> Profile</a>
                                    <a href="dashboard/settings.php"><i class="fas fa-cog"></i> Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline btn-sm">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="register.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="main-content"></main>