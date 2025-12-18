<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';

// Check if user is logged in
if (!Helper::isLoggedIn()) {
    Helper::redirect('../login.php', 'Please login to access this page.', 'warning');
}

$page_title = "Patients - HU CMS";
$user_role = $_SESSION['user_role'] ?? 'patient';

// Get all patients
try {
    $patients = Database::query("SELECT * FROM patients WHERE status = 'active' ORDER BY created_at DESC LIMIT 50");
} catch (Exception $e) {
    $patients = [];
}

// Get stats
$total_patients = Database::count('patients');
$active_patients = Database::count('patients', ['status' => 'active']);
$today_patients = Database::fetch("SELECT COUNT(*) as count FROM patients WHERE DATE(created_at) = CURDATE()");


?>

<style>

/* Enhanced Profile Page Styles */
.profile-container {
    padding: 2rem 0;
    background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
    min-height: calc(100vh - 70px);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Profile Header Enhancement */
.profile-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2.5rem;
    color: white;
    box-shadow: 0 15px 35px rgba(44, 62, 80, 0.15);
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.1;
    transform: rotate(15deg);
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db 0%, #8e44ad 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.8rem;
    font-weight: 700;
    margin-right: 2rem;
    border: 5px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    position: relative;
    z-index: 2;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.profile-avatar::after {
    content: '';
    position: absolute;
    top: -8px;
    left: -8px;
    right: -8px;
    bottom: -8px;
    border-radius: 50%;
    background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.15));
    z-index: -1;
    animation: pulse 2s infinite;
}

@keyframes pulse {

    0%,
    100% {
        opacity: 0.5;
    }

    50% {
        opacity: 0.8;
    }
}

.profile-info {
    position: relative;
    z-index: 2;
    flex: 1;
}

.profile-info h1 {
    margin: 0 0 0.5rem 0;
    color: white;
    font-size: 2.5rem;
    font-weight: 700;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    letter-spacing: -0.5px;
}

.profile-role {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 24px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 30px;
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.profile-role:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

.profile-info p {
    margin: 0.8rem 0;
    display: flex;
    align-items: center;
    gap: 12px;
    opacity: 0.9;
    font-size: 1rem;
    line-height: 1.5;
}

.profile-info p i {
    color: #3498db;
    width: 20px;
    text-align: center;
    filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
}

/* Alert Enhancement */
.alert {
    border-radius: 15px;
    padding: 1.2rem 1.8rem;
    margin-bottom: 2.5rem;
    display: flex;
    align-items: center;
    gap: 15px;
    animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border: none;
    position: relative;
    overflow: hidden;
}

.alert::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: currentColor;
    opacity: 0.3;
}

.alert-danger {
    background: linear-gradient(135deg, #ffeaea 0%, #ffd6d6 100%);
    color: #d63031;
    box-shadow: 0 8px 25px rgba(214, 48, 49, 0.15);
}

.alert-success {
    background: linear-gradient(135deg, #e8f6ef 0%, #d4efdf 100%);
    color: #27ae60;
    box-shadow: 0 8px 25px rgba(39, 174, 96, 0.15);
}

.alert i {
    font-size: 1.5rem;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Profile Content Enhancement */
.profile-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 2.5rem;
}

@media (max-width: 1024px) {
    .profile-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}

/* Profile Section Enhancement */
.profile-section {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
}

.profile-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3498db, #8e44ad);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-section:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.profile-section:hover::before {
    opacity: 1;
}

.section-title {
    background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
    padding: 1.8rem 2rem;
    margin: 0;
    color: #2c3e50;
    font-size: 1.4rem;
    font-weight: 700;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.3px;
}

.section-title i {
    color: #3498db;
    font-size: 1.3rem;
    background: white;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
}

/* Form Enhancement */
.form-group {
    margin-bottom: 2rem;
    position: relative;
    padding: 0 2rem;
    padding-top: 1.8rem;
    animation: fadeIn 0.5s ease forwards;
    opacity: 0;
    transform: translateY(10px);
}

@keyframes fadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-group:nth-child(1) {
    animation-delay: 0.1s;
}

.form-group:nth-child(2) {
    animation-delay: 0.2s;
}

.form-group:nth-child(3) {
    animation-delay: 0.3s;
}

.form-group:nth-child(4) {
    animation-delay: 0.4s;
}

.form-group:nth-child(5) {
    animation-delay: 0.5s;
}

.form-group:nth-child(6) {
    animation-delay: 0.6s;
}

.form-group label {
    display: block;
    margin-bottom: 0.8rem;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-group label i {
    color: #3498db;
    width: 20px;
    font-size: 1rem;
}

.form-control {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #f8fafc;
    box-sizing: border-box;
    color: #2c3e50;
    font-weight: 500;
    letter-spacing: 0.3px;
}

.form-control:focus {
    outline: none;
    border-color: #3498db;
    background: white;
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15);
    transform: translateY(-2px);
}

.readonly-field {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%) !important;
    color: #64748b !important;
    cursor: not-allowed;
    border-color: #cbd5e1 !important;
}

.form-control::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

small {
    color: #64748b;
    font-size: 0.85rem;
    margin-top: 6px;
    display: block;
    padding-left: 30px;
    font-style: italic;
    opacity: 0.8;
}

/* Password Requirements Enhancement */
.mb-3 {
    margin: 2rem;
    padding: 1.8rem;
    background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
    border-radius: 15px;
    border-left: 5px solid #3498db;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.mb-3::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    transform: translateX(-100%);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}

.mb-3 h6 {
    margin-bottom: 1.2rem;
    color: #2c3e50;
    font-size: 1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: 0.5px;
}

.mb-3 ul {
    margin: 0;
    padding-left: 0;
    list-style: none;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.8rem;
}

.mb-3 ul li {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #4a5568;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 8px;
    transition: all 0.2s ease;
}

.mb-3 ul li:hover {
    background: white;
    transform: translateX(5px);
}

.mb-3 ul li:before {
    content: '✓';
    color: #2ecc71;
    font-weight: bold;
    font-size: 1rem;
    width: 20px;
    height: 20px;
    background: rgba(46, 204, 113, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Button Enhancement */
.btn-group {
    display: flex;
    gap: 1.2rem;
    margin-top: 2.5rem;
    padding: 0 2rem;
    padding-bottom: 2.5rem;
    flex-wrap: wrap;
}

.btn {
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 35px rgba(52, 152, 219, 0.4);
}

.btn-outline {
    background: white;
    border: 2px solid #3498db;
    color: #3498db;
    padding: 12px 26px;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.15);
}

.btn-outline:hover {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
    border-color: transparent;
}

.btn i {
    font-size: 1.1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 2rem 1.5rem;
    }

    .profile-avatar {
        margin-right: 0;
        margin-bottom: 1.5rem;
        width: 100px;
        height: 100px;
        font-size: 2.2rem;
    }

    .profile-info h1 {
        font-size: 2rem;
    }

    .profile-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .form-group {
        padding: 0 1.5rem;
        padding-top: 1.5rem;
    }

    .btn-group {
        flex-direction: column;
        padding: 0 1.5rem;
        padding-bottom: 2rem;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .section-title {
        padding: 1.5rem;
        font-size: 1.2rem;
    }

    .mb-3 {
        margin: 1.5rem;
        padding: 1.5rem;
    }

    .mb-3 ul {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .profile-container {
        padding: 1.5rem 0;
    }

    .profile-header {
        padding: 1.5rem;
        border-radius: 15px;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        font-size: 2rem;
    }

    .profile-info h1 {
        font-size: 1.8rem;
    }

    .form-group {
        padding: 0 1.2rem;
        padding-top: 1.2rem;
    }

    .form-control {
        padding: 14px 16px;
    }

    .btn-group {
        padding: 0 1.2rem;
        padding-bottom: 1.8rem;
    }
}

/* Additional Micro-Interactions */
input[type="password"] {
    letter-spacing: 2px;
    font-family: 'Courier New', monospace;
}

input[type="password"]::placeholder {
    letter-spacing: normal;
    font-family: inherit;
}

/* Focus styles for accessibility */
.form-control:focus-visible {
    outline: 3px solid #3498db;
    outline-offset: 2px;
}

/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}

/* Print styles */
@media print {
    .profile-container {
        background: white;
    }

    .profile-header,
    .profile-section {
        box-shadow: none;
        border: 1px solid #ddd;
    }

    .btn-group {
        display: none;
    }
}

</style>
<div class="patients-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-user-injured"></i>
                Patient Management
            </h1>
            <div class="action-buttons">
                <a href="register_patient.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Register New Patient
                </a>
                <a href="../dashboard.php" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_patients; ?></h3>
                    <p>Total Patients</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $active_patients; ?></h3>
                    <p>Active Patients</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon today">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $today_patients['count'] ?? 0; ?></h3>
                    <p>Registered Today</p>
                </div>
            </div>
        </div>

        <!-- Patients Table -->
        <div class="patients-table-container">
            <div class="table-header">
                <h3>
                    <i class="fas fa-list"></i>
                    Patient Records
                </h3>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="patientSearch" placeholder="Search patients...">
                </div>
            </div>

            <div class="table-responsive">
                <table id="patientsTable">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($patients)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-user-slash"></i>
                                        <h4>No Patients Found</h4>
                                        <p>No patients have been registered yet. Register your first patient to get started.
                                        </p>
                                        <a href="register_patient.php" class="btn btn-primary">
                                            <i class="fas fa-user-plus"></i> Register First Patient
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $patient): ?>
                                <?php
                                $initials = strtoupper(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1));
                                $age = Helper::calculateAge($patient['date_of_birth']);
                                ?>
                                <tr>
                                    <td>
                                        <strong
                                            style="color: var(--primary-color);"><?php echo htmlspecialchars($patient['patient_id']); ?></strong>
                                    </td>
                                    <td>
                                        <div class="patient-name">
                                            <div class="patient-avatar">
                                                <?php echo $initials; ?>
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></strong><br>
                                                <small style="color: #7f8c8d; font-size: 0.85rem;">
                                                    Age: <?php echo $age; ?> years
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 5px;">
                                            <?php if ($patient['gender'] == 'male'): ?>
                                                <i class="fas fa-mars" style="color: #3498db;"></i>
                                            <?php elseif ($patient['gender'] == 'female'): ?>
                                                <i class="fas fa-venus" style="color: #e91e63;"></i>
                                            <?php else: ?>
                                                <i class="fas fa-transgender" style="color: #9b59b6;"></i>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars(ucfirst($patient['gender'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $dob = new DateTime($patient['date_of_birth']);
                                        echo htmlspecialchars($dob->format('M d, Y'));
                                        ?>
                                    </td>
                                    <td>
                                        <span style="display: flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-phone" style="color: #27ae60;"></i>
                                            <?php echo htmlspecialchars($patient['phone'] ?? 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge <?php echo $patient['status'] == 'active' ? 'badge-active' : 'badge-inactive'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($patient['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-icons">
                                            <a href="view_patient.php?id=<?php echo $patient['patient_id']; ?>"
                                                class="action-icon view" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit_patient.php?id=<?php echo $patient['patient_id']; ?>"
                                                class="action-icon edit" title="Edit Patient">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($user_role == 'admin' || $user_role == 'doctor'): ?>
                                                <a href="delete_patient.php?id=<?php echo $patient['patient_id']; ?>"
                                                    class="action-icon delete" title="Delete Patient"
                                                    onclick="return confirm('Are you sure you want to delete this patient?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div>
                    Showing <strong><?php echo count($patients); ?></strong> of
                    <strong><?php echo $total_patients; ?></strong> patients
                </div>
                <div>
                    <i class="fas fa-clock"></i>
                    Last updated: <?php echo date('h:i A'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this JavaScript for search functionality -->
<script>
    document.getElementById('patientSearch').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const table = document.getElementById('patientsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length - 1; j++) { // -1 to exclude actions column
                const cell = cells[j];
                if (cell.textContent.toLowerCase().includes(searchValue)) {
                    found = true;
                    break;
                }
            }

            if (found) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });

    // Add row hover effects
    const tableRows = document.querySelectorAll('#patientsTable tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function () {
            this.style.transform = 'translateX(5px)';
            this.style.transition = 'transform 0.2s ease';
        });

        row.addEventListener('mouseleave', function () {
            this.style.transform = 'translateX(0)';
        });
    });
</script>

