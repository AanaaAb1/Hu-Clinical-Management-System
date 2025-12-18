<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Check if user is logged in
if (!Helper::isLoggedIn()) {
    Helper::redirect('../login.php', 'Please login to access the dashboard.', 'warning');
}

$page_title = "My Profile - HU CMS";
$error = '';
$success = '';

// Get user data
$user = Database::fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update profile information
        $data = [
            'full_name' => Helper::sanitize($_POST['first_name'] ?? '') . ' ' . Helper::sanitize($_POST['last_name'] ?? ''),
            'phone' => Helper::sanitize($_POST['phone'] ?? ''),
            'department' => Helper::sanitize($_POST['department'] ?? '')
        ];

        try {
            Database::update('users', $data, 'id = ?', [$user['id']]);
            $_SESSION['user_name'] = $data['full_name'];
            $success = 'Profile updated successfully';
            $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
        } catch (Exception $e) {
            $error = 'Failed to update profile: ' . $e->getMessage();
        }
    } elseif (isset($_POST['change_password'])) {
        // Change password (plain text)
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'Please fill in all password fields';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } elseif (strlen($new_password) < 8) {
            $error = 'New password must be at least 8 characters long';
        } else {
            // Check current password
            $stored_password = $user['password'] ?? $user['password_hash'] ?? '';
            if ($current_password !== $stored_password) {
                $error = 'Current password is incorrect';
            } else {
                // Update password (plain text)
                try {
                    Database::update('users', ['password' => $new_password], 'id = ?', [$user['id']]);
                    $success = 'Password changed successfully';
                } catch (Exception $e) {
                    $error = 'Failed to change password: ' . $e->getMessage();
                }
            }
        }
    }
}

// Parse full_name into first_name and last_name for display
$name_parts = explode(' ', $user['full_name'] ?? '', 2);
$user['first_name'] = $name_parts[0] ?? '';
$user['last_name'] = $name_parts[1] ?? '';
$user['role'] = $user['user_role'] ?? $user['role'] ?? 'staff';


?>

<!-- Profile CSS -->
<style>
    .profile-container {
        padding: 2rem 0;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        margin-right: 1.5rem;
    }

    .profile-info h1 {
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .profile-role {
        display: inline-block;
        padding: 5px 15px;
        background: var(--light-color);
        color: var(--primary-color);
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .profile-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
    }

    .section-title {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
        color: var(--dark-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--dark-color);
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 82, 118, 0.1);
    }

    .readonly-field {
        background-color: #f8f9fa;
        color: #666;
        cursor: not-allowed;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-avatar {
            margin-right: 0;
            margin-bottom: 1rem;
        }
    }
</style>

<div class="profile-container">
    <div class="container">
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

        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
                <span class="profile-role"><?php echo htmlspecialchars($user['role']); ?></span>
                <p style="margin-top: 0.5rem; color: var(--gray-color);">
                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?>
                    <?php if ($user['phone']): ?>
                        | <i class="fas fa-phone"></i> <?php echo htmlspecialchars($user['phone']); ?>
                    <?php endif; ?>
                </p>
                <p style="color: var(--gray-color);">
                    Member since <?php echo Helper::formatDate($user['created_at']); ?>
                    <?php if ($user['last_login']): ?>
                        | Last login: <?php echo Helper::formatDate($user['last_login'], 'M j, Y h:i A'); ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="profile-content">
            <!-- Profile Information Form -->
            <div class="profile-section">
                <h3 class="section-title">Profile Information</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required
                            value="<?php echo htmlspecialchars($user['first_name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required
                            value="<?php echo htmlspecialchars($user['last_name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control readonly-field"
                            value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        <small style="color: var(--gray-color);">Contact admin to change email</small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="+251XXXXXXXXX"
                            value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="department">Department/Unit</label>
                        <input type="text" id="department" name="department" class="form-control"
                            placeholder="Enter your department"
                            value="<?php echo htmlspecialchars($user['department'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" id="role" name="role" class="form-control readonly-field"
                            value="<?php echo htmlspecialchars(ucfirst($user['role'])); ?>" readonly>
                    </div>

                    <div class="btn-group">
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                        <a href="dashboard.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="profile-section">
                <h3 class="section-title">Change Password</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control"
                            required placeholder="Enter current password">
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required
                            placeholder="Enter new password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                            required placeholder="Confirm new password">
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

                    <div class="btn-group">
                        <button type="submit" name="change_password" class="btn btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

