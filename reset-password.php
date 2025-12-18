<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (Helper::isLoggedIn()) {
    Helper::redirect('dashboard.php');
}

$page_title = "Reset Password - HU CMS";
$error = '';
$success = '';
$token = $_GET['token'] ?? '';

// Validate token
if (empty($token)) {
    Helper::redirect('login.php', 'Invalid reset token.', 'error');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!Helper::validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $new_password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $token = $_POST['token'] ?? '';

        if (empty($new_password) || empty($confirm_password)) {
            $error = 'Please enter both password fields';
        } elseif (strlen($new_password) < 8) {
            $error = 'Password must be at least 8 characters long';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Passwords do not match';
        } else {
            // Attempt to reset password
            $result = $auth->resetPassword($token, $new_password);

            if ($result['success']) {
                $success = $result['message'];
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
            <h2>Reset Password</h2>
            <p class="text-muted">Enter your new password below</p>
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
                <p class="mt-2">
                    <a href="login.php" class="btn btn-sm btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Go to Login
                    </a>
                </p>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div class="form-group mb-3">
                    <label for="password"
                        style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color);">
                        <i class="fas fa-lock"></i> New Password
                    </label>
                    <input type="password" id="password" name="password" required class="form-control"
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--border-radius); font-size: 1rem;"
                        placeholder="Enter new password (min 8 characters)">
                </div>

                <div class="form-group mb-3">
                    <label for="confirm_password"
                        style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color);">
                        <i class="fas fa-lock"></i> Confirm New Password
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="form-control"
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--border-radius); font-size: 1rem;"
                        placeholder="Confirm new password">
                </div>

                <div class="mb-3" style="padding: 1rem; background: #f8f9fa; border-radius: var(--border-radius);">
                    <h6 style="margin-bottom: 0.5rem; color: var(--dark-color);">Password Requirements:</h6>
                    <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; color: var(--gray-color);">
                        <li>At least 8 characters long</li>
                        <li>At least one uppercase letter</li>
                        <li>At least one lowercase letter</li>
                        <li>At least one number</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="width: 100%;">
                    <i class="fas fa-save"></i> Reset Password
                </button>
            </form>
        <?php endif; ?>

        <div class="text-center mt-4">
            <p style="color: var(--gray-color);">
                <a href="login.php" style="color: var(--primary-color); font-weight: 500;">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');

        function checkPasswordMatch() {
            if (passwordInput && confirmInput) {
                const password = passwordInput.value;
                const confirm = confirmInput.value;

                if (password && confirm) {
                    if (password === confirm) {
                        confirmInput.style.borderColor = '#27ae60';
                    } else {
                        confirmInput.style.borderColor = '#e74c3c';
                    }
                }
            }
        }

        if (passwordInput && confirmInput) {
            passwordInput.addEventListener('input', checkPasswordMatch);
            confirmInput.addEventListener('input', checkPasswordMatch);
        }
    });
</script>

<?php include 'includes/footer.php'; ?>