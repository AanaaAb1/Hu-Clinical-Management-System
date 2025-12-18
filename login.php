<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (Helper::isLoggedIn()) {
    Helper::redirect('dashboard.php');
}

$page_title = "Login - HU CMS";
$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token
    if (!Helper::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {

        $email = Helper::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Validate input
        if (empty($email) || empty($password)) {
            $error = 'Please enter both email and password';
        } elseif (!Helper::isValidEmail($email)) {
            $error = 'Please enter a valid email address';
        } else {

            // Login using plain text password
            $user = Database::fetch(
                "SELECT * FROM users WHERE email = ?",
                [$email]
            );

            if (!$user) {
                $error = "User not found. Please check your email address.";
            } elseif ($user['status'] !== 'active') {
                $error = "Account is not active. Please contact administrator.";
            } else {

                // Compare plain text password
                $stored_password = $user['password'] ?? '';
                
                if ($password !== $stored_password) {
                    $error = "Invalid password. Please try again.";
                } else {

                    // Start session
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['user_role'] ?? $user['role'];
                    $_SESSION['user_name'] = $user['full_name'] ?? ($user['first_name'] . ' ' . $user['last_name']);
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['login_time'] = time();
                    $_SESSION['logged_in'] = true;

                    // Update last login
                    try {
                        Database::update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);
                    } catch (Exception $e) {
                        // Ignore if update fails
                    }

                    // Redirect based on role to separate dashboards
                    $role = $_SESSION['user_role'];
                    if ($role === 'admin') {
                        Helper::redirect('dashboard/admin-dashboard.php', 'Welcome back, Administrator!');
                    } elseif ($role === 'doctor') {
                        Helper::redirect('dashboard/doctor-dashboard.php', 'Welcome back, Doctor!');
                    } elseif ($role === 'nurse') {
                        Helper::redirect('dashboard/nurse-dashboard.php', 'Welcome back, Nurse!');
                    } else {
                        Helper::redirect('dashboard.php', 'Login successful! Welcome back.');
                    }
                }
            }
        }
    }
}

// Generate CSRF token
$csrf_token = Helper::generateCSRFToken();
include 'includes/header.php';
?>

<!-- HTML LOGIN FORM (unchanged) -->
<div class="container" style="max-width: 500px; margin: 100px auto;">
    <div class="card">
        <div class="text-center mb-4">
            <i class="fas fa-hospital-alt"
                style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
            <h2>Login to HU CMS</h2>
            <p class="text-muted">Enter your credentials to access the system</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"
                style="background: #fee; color: #c33; padding: 12px; border-radius: var(--border-radius); margin-bottom: 1.5rem; border-left: 4px solid #c33;">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <div class="form-group mb-3">
                <label for="email"
                    style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color);">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" id="email" name="email" required class="form-control" placeholder="admin@hu.edu.et"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <div class="form-group mb-3">
                <label for="password"
                    style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color);">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required class="form-control"
                        placeholder="●●●●●●●●">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>