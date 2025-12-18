<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';

// Check if user is logged in and is doctor
if (!Helper::isLoggedIn()) {
    Helper::redirect('../login.php', 'Please login to access the dashboard.', 'warning');
}

if (!Helper::hasRole('doctor')) {
    Helper::redirect('../login.php', 'Access denied. Doctor only.', 'error');
}

$page_title = "Doctor Dashboard - HU CMS";
$user_name = $_SESSION['user_name'] ?? 'Doctor';
$user_id = $_SESSION['user_id'];

// Get doctor statistics
$stats = [];
try {
    $today = date('Y-m-d');
    $stats['today_appointments'] = Database::fetch(
        "SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ? AND appointment_date = ?",
        [$user_id, $today]
    )['count'] ?? 0;

    $stats['total_patients'] = Database::fetch(
        "SELECT COUNT(DISTINCT patient_id) as count FROM appointments WHERE doctor_id = ?",
        [$user_id]
    )['count'] ?? 0;

    $stats['completed_today'] = Database::fetch(
        "SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status = 'completed'",
        [$user_id, $today]
    )['count'] ?? 0;

    $stats['pending_appointments'] = Database::fetch(
        "SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ? AND appointment_date >= ? AND status IN ('scheduled', 'confirmed')",
        [$user_id, $today]
    )['count'] ?? 0;
} catch (Exception $e) {
    $stats = [
        'today_appointments' => 0,
        'total_patients' => 0,
        'completed_today' => 0,
        'pending_appointments' => 0
    ];
}

// Get today's appointments
try {
    $today_appointments = Database::query(
        "SELECT a.*, p.first_name, p.last_name, p.patient_id, p.phone 
         FROM appointments a 
         LEFT JOIN patients p ON a.patient_id = p.id 
         WHERE a.doctor_id = ? AND a.appointment_date = ? 
         ORDER BY a.appointment_time ASC",
        [$user_id, $today]
    );
} catch (Exception $e) {
    $today_appointments = [];
}


?>

<style>
    .doctor-dashboard {
        padding: 2rem 0;
        background: #f5f7fa;
        min-height: calc(100vh - 200px);
    }

    .dashboard-header {
        background: linear-gradient(135deg, #27ae60, #229954);
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
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
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

    .stat-icon.green {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #f39c12, #e67e22);
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
    }

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
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: #27ae60;
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
        background: white;
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

    .appointment-item {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .appointment-item:last-child {
        border-bottom: none;
    }

    .appointment-time {
        font-weight: 600;
        color: var(--primary-color);
        min-width: 80px;
    }

    .appointment-details {
        flex: 1;
        padding: 0 1rem;
    }

    .appointment-details strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .appointment-details small {
        color: var(--gray-color);
    }

    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge-scheduled {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-confirmed {
        background: #e8f5e9;
        color: #388e3c;
    }

    .badge-completed {
        background: #f1f8e9;
        color: #689f38;
    }
</style>

<div class="doctor-dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1><i class="fas fa-user-md"></i> Doctor Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($user_name); ?>! Here are your appointments and tasks for
                today.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['today_appointments']); ?></h3>
                    <p>Today's Appointments</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_patients']); ?></h3>
                    <p>My Patients</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['completed_today']); ?></h3>
                    <p>Completed Today</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['pending_appointments']); ?></h3>
                    <p>Pending Appointments</p>
                </div>
            </div>
        </div>

        <!-- Doctor Actions -->
        <div class="content-section">
            <h3 class="section-title"><i class="fas fa-tasks"></i> Quick Actions</h3>
            <div class="actions-grid">
                <a href="appointments.php" class="action-card">
                    <i class="fas fa-calendar-alt"></i>
                    <span>My Appointments</span>
                </a>
                <a href="patients.php" class="action-card">
                    <i class="fas fa-user-injured"></i>
                    <span>View Patients</span>
                </a>
                <a href="medical-records.php" class="action-card">
                    <i class="fas fa-file-medical"></i>
                    <span>Medical Records</span>
                </a>
                <a href="profile.php" class="action-card">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
                <a href="../logout.php" class="action-card">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="content-section">
            <h3 class="section-title"><i class="fas fa-calendar-day"></i> Today's Appointments</h3>
            <?php if (empty($today_appointments)): ?>
                <p style="text-align: center; color: var(--gray-color); padding: 2rem;">
                    <i class="fas fa-calendar-times fa-3x mb-2"></i><br>
                    No appointments scheduled for today
                </p>
            <?php else: ?>
                <?php foreach ($today_appointments as $apt): ?>
                    <div class="appointment-item">
                        <div class="appointment-time">
                            <?php echo date('h:i A', strtotime($apt['appointment_time'])); ?>
                        </div>
                        <div class="appointment-details">
                            <strong><?php echo htmlspecialchars(($apt['first_name'] ?? '') . ' ' . ($apt['last_name'] ?? '')); ?></strong>
                            <small>
                                ID: <?php echo htmlspecialchars($apt['patient_id'] ?? 'N/A'); ?> |
                                Phone: <?php echo htmlspecialchars($apt['phone'] ?? 'N/A'); ?><br>
                                Reason: <?php echo htmlspecialchars($apt['reason'] ?? 'General consultation'); ?>
                            </small>
                        </div>
                        <span class="badge badge-<?php echo $apt['status']; ?>">
                            <?php echo ucfirst($apt['status']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>