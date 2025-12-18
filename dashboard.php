<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!Helper::isLoggedIn()) {
    Helper::redirect('login.php', 'Please login to access the dashboard.', 'warning');
}

// Redirect to role-specific dashboards
$user_role = $_SESSION['user_role'] ?? 'patient';
if ($user_role === 'admin') {
    header('Location: dashboard/admin-dashboard.php');
    exit();
} elseif ($user_role === 'doctor') {
    header('Location: dashboard/doctor-dashboard.php');
    exit();
} elseif ($user_role === 'nurse') {
    header('Location: dashboard/nurse-dashboard.php');
    exit();
}

$page_title = "Dashboard - HU CMS";
$user_name = $_SESSION['user_name'] ?? 'User';

// Get dashboard statistics based on role
$stats = [];
$recent_appointments = [];
$recent_activities = [];

try {
    $today = date('Y-m-d');
    
    // Get stats based on role
    if ($user_role === 'admin') {
        $stats['today_appointments'] = Database::count('appointments', ['appointment_date' => $today]);
        $stats['total_patients'] = Database::count('patients', ['status' => 'active']);
        $stats['total_doctors'] = Database::count('users', ['user_role' => 'doctor', 'status' => 'active']);
        $stats['total_staff'] = Database::count('users', ['status' => 'active']);
    } elseif ($user_role === 'doctor') {
        $user_id = $_SESSION['user_id'];
        $stats['today_appointments'] = Database::fetch(
            "SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ? AND appointment_date = ?",
            [$user_id, $today]
        )['count'] ?? 0;
        $stats['total_patients'] = Database::fetch(
            "SELECT COUNT(DISTINCT patient_id) as count FROM appointments WHERE doctor_id = ?",
            [$user_id]
        )['count'] ?? 0;
        $stats['pending_prescriptions'] = 0;
        $stats['completed_today'] = Database::fetch(
            "SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status = 'completed'",
            [$user_id, $today]
        )['count'] ?? 0;
    } elseif ($user_role === 'nurse') {
        $stats['today_appointments'] = Database::count('appointments', ['appointment_date' => $today]);
        $stats['total_patients'] = Database::count('patients', ['status' => 'active']);
        $stats['pending_tests'] = 0;
        $stats['pending_prescriptions'] = 0;
    } else {
        $stats['today_appointments'] = 0;
        $stats['total_patients'] = 0;
        $stats['pending_prescriptions'] = 0;
        $stats['pending_tests'] = 0;
    }

    // Get recent appointments based on role
    if ($user_role === 'doctor') {
        $recent_appointments = Database::query(
            "SELECT a.*, p.first_name, p.last_name, p.patient_id 
             FROM appointments a 
             LEFT JOIN patients p ON a.patient_id = p.id 
             WHERE a.doctor_id = ? AND a.appointment_date >= ? 
             ORDER BY a.appointment_date, a.appointment_time 
             LIMIT 10",
            [$_SESSION['user_id'], $today]
        );
    } else {
        $recent_appointments = Database::query(
            "SELECT a.*, p.first_name, p.last_name, p.patient_id 
             FROM appointments a 
             LEFT JOIN patients p ON a.patient_id = p.id 
             WHERE a.appointment_date >= ? 
             ORDER BY a.appointment_date, a.appointment_time 
             LIMIT 10",
            [$today]
        );
    }

} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $stats = [
        'today_appointments' => 0,
        'total_patients' => 0,
        'pending_prescriptions' => 0,
        'pending_tests' => 0
    ];
    $recent_appointments = [];
}

include 'includes/header.php';
?>

<!-- Dashboard CSS -->
<style>
    .dashboard {
        padding: 2rem 0;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .welcome-message h1 {
        margin-bottom: 0.5rem;
        color: var(--primary-color);
    }

    .welcome-message p {
        color: var(--gray-color);
        margin-bottom: 0;
    }

    .date-display {
        background: var(--light-color);
        padding: 10px 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
        color: var(--dark-color);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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

    .stat-icon.patients {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .stat-icon.appointments {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .stat-icon.prescriptions {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
    }

    .stat-icon.tests {
        background: linear-gradient(135deg, #f39c12, #e67e22);
    }

    .stat-content h3 {
        font-size: 2rem;
        margin-bottom: 0.25rem;
        color: var(--dark-color);
    }

    .stat-content p {
        color: var(--gray-color);
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .dashboard-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .dashboard-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .section-header h3 {
        margin-bottom: 0;
        color: var(--dark-color);
    }

    .view-all {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .appointments-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .appointment-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        transition: var(--transition);
    }

    .appointment-item:hover {
        background-color: var(--light-color);
    }

    .appointment-item:last-child {
        border-bottom: none;
    }

    .appointment-time {
        min-width: 80px;
        text-align: center;
        padding-right: 1rem;
        border-right: 2px solid var(--light-color);
    }

    .appointment-time .time {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        display: block;
    }

    .appointment-time .date {
        font-size: 0.8rem;
        color: var(--gray-color);
        display: block;
    }

    .appointment-details {
        flex: 1;
        padding-left: 1rem;
    }

    .appointment-details h4 {
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .appointment-details p {
        margin-bottom: 0.25rem;
        color: var(--gray-color);
        font-size: 0.9rem;
    }

    .appointment-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-scheduled {
        background: #e3f2fd;
        color: #1976d2;
    }

    .status-confirmed {
        background: #e8f5e9;
        color: #388e3c;
    }

    .status-completed {
        background: #f1f8e9;
        color: #689f38;
    }

    .status-cancelled {
        background: #ffebee;
        color: #d32f2f;
    }

    .activities-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-content {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .activity-user {
        font-weight: 500;
        color: var(--primary-color);
    }

    .activity-time {
        font-size: 0.8rem;
        color: var(--gray-color);
    }

    .quick-actions {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: var(--light-color);
        border-radius: var(--border-radius);
        text-decoration: none;
        color: var(--dark-color);
        transition: var(--transition);
    }

    .action-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
    }

    .action-btn i {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .action-btn span {
        font-weight: 500;
        text-align: center;
    }

    @media (max-width: 992px) {
        .dashboard-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="welcome-message">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                <p>Here's what's happening with your patients today.</p>
            </div>
            <div class="date-display">
                <i class="fas fa-calendar-alt"></i>
                <?php echo date('l, F j, Y'); ?>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <?php if ($user_role === 'admin'): ?>
                <div class="stat-card">
                    <div class="stat-icon patients">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['total_patients'] ?? 0); ?></h3>
                        <p>Total Patients</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon appointments">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['today_appointments'] ?? 0); ?></h3>
                        <p>Today's Appointments</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['total_doctors'] ?? 0); ?></h3>
                        <p>Total Doctors</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon tests">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['total_staff'] ?? 0); ?></h3>
                        <p>Total Staff</p>
                    </div>
                </div>
            <?php elseif ($user_role === 'doctor'): ?>
                <div class="stat-card">
                    <div class="stat-icon appointments">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['today_appointments'] ?? 0); ?></h3>
                        <p>Today's Appointments</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon patients">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['total_patients'] ?? 0); ?></h3>
                        <p>My Patients</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['completed_today'] ?? 0); ?></h3>
                        <p>Completed Today</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon prescriptions">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['pending_prescriptions'] ?? 0); ?></h3>
                        <p>Prescriptions</p>
                    </div>
                </div>
            <?php elseif ($user_role === 'nurse'): ?>
                <div class="stat-card">
                    <div class="stat-icon appointments">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['today_appointments'] ?? 0); ?></h3>
                        <p>Today's Appointments</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon patients">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['total_patients'] ?? 0); ?></h3>
                        <p>Total Patients</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon tests">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['pending_tests'] ?? 0); ?></h3>
                        <p>Pending Tests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon prescriptions">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['pending_prescriptions'] ?? 0); ?></h3>
                        <p>Pending Prescriptions</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="stat-card">
                    <div class="stat-icon patients">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['total_patients'] ?? 0); ?></h3>
                        <p>Total Patients</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon appointments">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($stats['today_appointments'] ?? 0); ?></h3>
                        <p>Today's Appointments</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <!-- Appointments Section -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h3>Today's Appointments</h3>
                    <a href="dashboard/appointments.php" class="view-all">View All</a>
                </div>
                <div class="appointments-list">
                    <?php if (empty($recent_appointments)): ?>
                        <div class="text-center py-3" style="color: var(--gray-color);">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p>No appointments scheduled for today</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_appointments as $appointment): ?>
                            <div class="appointment-item">
                                <div class="appointment-time">
                                    <span
                                        class="time"><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></span>
                                    <span
                                        class="date"><?php echo date('M j', strtotime($appointment['appointment_date'])); ?></span>
                                </div>
                                <div class="appointment-details">
                                    <h4><?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?>
                                    </h4>
                                    <p>ID: <?php echo htmlspecialchars($appointment['patient_id']); ?></p>
                                    <p><?php echo htmlspecialchars($appointment['reason'] ?? 'Regular checkup'); ?></p>
                                    <span class="appointment-status status-<?php echo $appointment['status']; ?>">
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h3>Recent Activities</h3>
                    <a href="admin/logs.php" class="view-all">View All</a>
                </div>
                <div class="activities-list">
                    <?php if (empty($recent_activities)): ?>
                        <div class="text-center py-3" style="color: var(--gray-color);">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p>No recent activities</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_activities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-content">
                                    <span class="activity-user">
                                        <?php echo htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']); ?>
                                    </span>
                                    <?php echo htmlspecialchars($activity['activity']); ?>
                                </div>
                                <div class="activity-time">
                                    <i class="far fa-clock"></i>
                                    <?php echo Helper::formatDate($activity['created_at'], 'M j, h:i A'); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="section-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="actions-grid">
                <?php if ($user_role === 'admin'): ?>
                    <a href="dashboard/patients.php" class="action-btn">
                        <i class="fas fa-users"></i>
                        <span>Manage Patients</span>
                    </a>
                    <a href="dashboard/appointments.php" class="action-btn">
                        <i class="fas fa-calendar-alt"></i>
                        <span>View Appointments</span>
                    </a>
                    <a href="dashboard/profile.php" class="action-btn">
                        <i class="fas fa-user-md"></i>
                        <span>Manage Staff</span>
                    </a>
                    <a href="dashboard/billing.php" class="action-btn">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing & Reports</span>
                    </a>
                    <a href="dashboard/medical-records.php" class="action-btn">
                        <i class="fas fa-file-medical"></i>
                        <span>Medical Records</span>
                    </a>
                    <a href="logout.php" class="action-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                <?php elseif ($user_role === 'doctor'): ?>
                    <a href="dashboard/appointments.php" class="action-btn">
                        <i class="fas fa-calendar-check"></i>
                        <span>My Appointments</span>
                    </a>
                    <a href="dashboard/patients.php" class="action-btn">
                        <i class="fas fa-user-injured"></i>
                        <span>View Patients</span>
                    </a>
                    <a href="dashboard/medical-records.php" class="action-btn">
                        <i class="fas fa-file-medical"></i>
                        <span>Medical Records</span>
                    </a>
                    <a href="dashboard/prescriptions.php" class="action-btn">
                        <i class="fas fa-prescription"></i>
                        <span>Prescriptions</span>
                    </a>
                    <a href="dashboard/profile.php" class="action-btn">
                        <i class="fas fa-user-circle"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="logout.php" class="action-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                <?php elseif ($user_role === 'nurse'): ?>
                    <a href="dashboard/patients.php" class="action-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>Register Patient</span>
                    </a>
                    <a href="dashboard/appointments.php" class="action-btn">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Schedule Appointment</span>
                    </a>
                    <a href="dashboard/medical-records.php" class="action-btn">
                        <i class="fas fa-notes-medical"></i>
                        <span>Patient Records</span>
                    </a>
                    <a href="dashboard/billing.php" class="action-btn">
                        <i class="fas fa-file-invoice"></i>
                        <span>Billing</span>
                    </a>
                    <a href="dashboard/profile.php" class="action-btn">
                        <i class="fas fa-user-circle"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="logout.php" class="action-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="dashboard/patients.php" class="action-btn">
                        <i class="fas fa-user-injured"></i>
                        <span>View Patients</span>
                    </a>
                    <a href="dashboard/appointments.php" class="action-btn">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Appointments</span>
                    </a>
                    <a href="dashboard/profile.php" class="action-btn">
                        <i class="fas fa-user-circle"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="logout.php" class="action-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="js/dashboard.js"></script>
