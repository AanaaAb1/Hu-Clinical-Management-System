<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';

// Check if user is logged in and is admin
if (!Helper::isLoggedIn()) {
    Helper::redirect('../login.php', 'Please login to access the dashboard.', 'warning');
}

if (!Helper::hasRole('admin')) {
    Helper::redirect('../login.php', 'Access denied. Admin only.', 'error');
}

$page_title = "Admin Dashboard - HU CMS";
$user_name = $_SESSION['user_name'] ?? 'Administrator';

// Get admin statistics
$stats = [];
try {
    $today = date('Y-m-d');
    $stats['total_patients'] = Database::count('patients', ['status' => 'active']);
    $stats['total_doctors'] = Database::count('users', ['user_role' => 'doctor', 'status' => 'active']);
    $stats['total_nurses'] = Database::count('users', ['user_role' => 'nurse', 'status' => 'active']);
    $stats['total_staff'] = Database::count('users', ['status' => 'active']);
    $stats['today_appointments'] = Database::count('appointments', ['appointment_date' => $today]);
    $stats['pending_users'] = Database::count('users', ['status' => 'pending']);
} catch (Exception $e) {
    $stats = [
        'total_patients' => 0,
        'total_doctors' => 0,
        'total_nurses' => 0,
        'total_staff' => 0,
        'today_appointments' => 0,
        'pending_users' => 0
    ];
}

// Get recent activities
try {
    $recent_users = Database::query(
        "SELECT * FROM users ORDER BY created_at DESC LIMIT 5"
    );
    $recent_appointments = Database::query(
        "SELECT a.*, p.first_name, p.last_name 
         FROM appointments a 
         LEFT JOIN patients p ON a.patient_id = p.id 
         ORDER BY a.created_at DESC LIMIT 5"
    );
} catch (Exception $e) {
    $recent_users = [];
    $recent_appointments = [];
}


?>

<style>
    .admin-dashboard {
        padding: 2rem 0;
        background: #f5f7fa;
        min-height: calc(100vh - 200px);
    }
    .dashboard-header {
        background: linear-gradient(135deg, #1a5276, #2874a6);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        box-shadow: var(--box-shadow);
    }
    .dashboard-header h1 {
        margin-bottom: 0.5rem;
        color: white;
    }
    .dashboard-header p {
        opacity: 0.9;
        margin: 0;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        display: flex;
        align-items: center;
        transition: var(--transition);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-right: 1rem;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #3498db, #2980b9); }
    .stat-icon.green { background: linear-gradient(135deg, #2ecc71, #27ae60); }
    .stat-icon.purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
    .stat-icon.orange { background: linear-gradient(135deg, #f39c12, #e67e22); }
    .stat-icon.red { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .stat-icon.teal { background: linear-gradient(135deg, #1abc9c, #16a085); }
    .stat-content h3 {
        font-size: 2rem;
        margin-bottom: 0.25rem;
        color: var(--dark-color);
    }
    .stat-content p {
        color: var(--gray-color);
        margin: 0;
        font-size: 0.9rem;
    }
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .action-card {
        background: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        text-align: center;
        text-decoration: none;
        color: var(--dark-color);
        transition: var(--transition);
    }
    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        background: var(--primary-color);
        color: white;
    }
    .action-card i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }
    .action-card span {
        font-weight: 500;
        display: block;
    }
    .content-section {
        background: purple;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
    }
    .section-title {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        color: var(--dark-color);
    }
    .list-item {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .list-item:last-child {
        border-bottom: none;
    }
    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .badge-success { background: #e8f5e9; color: #388e3c; }
    .badge-warning { background: #fff3e0; color: #f57c00; }
    .badge-info { background: #e3f2fd; color: #1976d2; }
</style>

<div class="admin-dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1><i class="fas fa-user-shield"></i> Admin Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($user_name); ?>! Manage your hospital system from here.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_patients']); ?></h3>
                    <p>Total Patients</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_doctors']); ?></h3>
                    <p>Total Doctors</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-user-nurse"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_nurses']); ?></h3>
                    <p>Total Nurses</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_staff']); ?></h3>
                    <p>Total Staff</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon teal">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['today_appointments']); ?></h3>
                    <p>Today's Appointments</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['pending_users']); ?></h3>
                    <p>Pending Approvals</p>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="content-section">
            <h3 class="section-title"><i class="fas fa-tasks"></i> Admin Actions</h3>
            <div class="actions-grid">
                <a href="patients.php" class="action-card">
                    <i class="fas fa-users"></i>
                    <span>Manage Patients</span>
                </a>
                <a href="appointments.php" class="action-card">
                    <i class="fas fa-calendar-alt"></i>
                    <span>View Appointments</span>
                </a>
                <a href="profile.php" class="action-card">
                    <i class="fas fa-user-cog"></i>
                    <span>Manage Staff</span>
                </a>
                <a href="billing.php" class="action-card">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Billing & Finance</span>
                </a>
                <a href="medical-records.php" class="action-card">
                    <i class="fas fa-file-medical"></i>
                    <span>Medical Records</span>
                </a>
                <a href="../logout.php" class="action-card">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="content-section">
            <h3 class="section-title"><i class="fas fa-history"></i> Recent User Registrations</h3>
            <?php if (empty($recent_users)): ?>
                <p style="text-align: center; color: var(--gray-color); padding: 2rem;">No recent users</p>
            <?php else: ?>
                <?php foreach ($recent_users as $user): ?>
                    <div class="list-item">
                        <div>
                            <strong><?php echo htmlspecialchars($user['full_name'] ?? $user['email']); ?></strong>
                            <br>
                            <small style="color: var(--gray-color);">
                                <?php echo htmlspecialchars($user['email']); ?> - 
                                <?php echo ucfirst($user['user_role'] ?? $user['role'] ?? 'user'); ?>
                            </small>
                        </div>
                        <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($user['status']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


