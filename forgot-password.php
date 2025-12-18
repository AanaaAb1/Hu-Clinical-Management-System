<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (Helper::isLoggedIn()) {
    Helper::redirect('dashboard.php');
}

$page_title = "Forgot Password - HU CMS";
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!Helper::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $email = Helper::sanitize($_POST['email'] ?? '');

        if (empty($email)) {
            $error = 'Please enter your email address';
        } elseif (!Helper::isValidEmail($email)) {
            $error = 'Please enter a valid email address';
        } else {
            // Attempt to send reset link
            $result = $auth->forgotPassword($email);

            if ($result['success']) {
                $success = $result['message'];
                // For testing, show the reset link
                if (isset($result['reset_link'])) {
                    $success .= ' Test link: ' . $result['reset_link'];
                }
            } else {
                $error = $result['error'];
            }
        }
    }
}

// Generate CSRF token
$csrf_token = Helper::generateCSRFToken();

include 'includes/header.php';
?>

<div class="container" style="max-width: 500px; margin: 100px auto;">
    <div class="card">
        <div class="text-center mb-4">
            <i class="fas fa-key" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
            <h2>Forgot Password</h2>
            <p class="text-muted">Enter your email to receive a password reset link</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"
                style="background: #fee; color: #c33; padding: 12px; border-radius: var(--border-radius); margin-bottom: 1.5rem; border-left: 4px solid #c33;">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"
                style="background: #efe; color: #383; padding: 12px; border-radius: var(--border-radius); margin-bottom: 1.5rem; border-left: 4px solid #383;">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <div class="form-group mb-3">
                <label for="email"
                    style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color);">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" id="email" name="email" required class="form-control"
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--border-radius); font-size: 1rem;"
                    placeholder="Enter your registered email address"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="width: 100%;">
                <i class="fas fa-paper-plane"></i> Send Reset Link
            </button>
        </form>

        <div class="text-center mt-4">
            <p style="color: var(--gray-color);">
                Remember your password?
                <a href="login.php" style="color: var(--primary-color); font-weight: 500;">Login here</a>
            </p>
            <p style="color: var(--gray-color);">
                Need help?
                <a href="pages/contact.php" style="color: var(--primary-color); font-weight: 500;">Contact Support</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>