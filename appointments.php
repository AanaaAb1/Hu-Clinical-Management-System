<?php
// appointments.php
session_start();
require_once '../includes/config.php';

// Database connection function
function getDB()
{
    static $db = null;
    if ($db === null) {
        try {
            $db = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $db;
}

// Check login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'nurse') {
    header("Location: ../login.php");
    exit();
}

$page_title = "Schedule Appointment - HU CMS";
$user_name = $_SESSION['full_name'] ?? 'Nurse';

// Handle form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $db = getDB();

        // Validate
        if (
            empty($_POST['patient_id']) || empty($_POST['appointment_date']) ||
            empty($_POST['appointment_time']) || empty($_POST['reason'])
        ) {
            throw new Exception("Please fill in all required fields");
        }

        // Generate appointment ID
        $appointment_id = 'APT' . date('YmdHis') . rand(100, 999);

        // Insert appointment
        $sql = "INSERT INTO appointments (
            appointment_id, patient_id, doctor_id, appointment_date, 
            appointment_time, appointment_type, reason, notes, status, created_by, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'scheduled', ?, NOW())";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $appointment_id,
            $_POST['patient_id'],
            !empty($_POST['doctor_id']) ? $_POST['doctor_id'] : null,
            $_POST['appointment_date'],
            $_POST['appointment_time'],
            $_POST['appointment_type'] ?? 'consultation',
            $_POST['reason'],
            $_POST['notes'] ?? '',
            $_SESSION['user_id']
        ]);

        $success = "✅ Appointment scheduled successfully!<br>Appointment ID: <strong>$appointment_id</strong>";
        $_POST = []; // Clear form

    } catch (Exception $e) {
        $error = "❌ Error: " . $e->getMessage();
    }
}

// Get patients
$db = getDB();
$patients = [];
$doctors = [];
$today_appointments = [];

try {
    // Get patients
    $stmt = $db->query(
        "SELECT id, patient_id, CONCAT(first_name, ' ', last_name) as full_name, 
        phone, date_of_birth, gender
        FROM patients WHERE status = 'active' ORDER BY first_name"
    );
    $patients = $stmt->fetchAll();

    // Get doctors
    $stmt = $db->query(
        "SELECT id, full_name, specialization 
         FROM users 
         WHERE user_role = 'doctor' AND status = 'active' 
         ORDER BY full_name"
    );
    $doctors = $stmt->fetchAll();

    // Get today's appointments
    $today = date('Y-m-d');
    $stmt = $db->prepare(
        "SELECT a.*, p.first_name, p.last_name, p.patient_id, u.full_name as doctor_name
         FROM appointments a 
         LEFT JOIN patients p ON a.patient_id = p.id 
         LEFT JOIN users u ON a.doctor_id = u.id
         WHERE a.appointment_date = ? 
         AND a.status IN ('scheduled', 'confirmed')
         ORDER BY a.appointment_time ASC"
    );
    $stmt->execute([$today]);
    $today_appointments = $stmt->fetchAll();

} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 28px;
        }

        .header-left h1 i {
            color: var(--primary);
            background: #eef2ff;
            padding: 15px;
            border-radius: 12px;
        }

        .header-left p {
            color: var(--gray);
            margin-top: 5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 10px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        /* Main Layout */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 25px;
        }

        .card-header h2 {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
        }

        .card-body {
            padding: 30px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: var(--primary);
            width: 20px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Button */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 32px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Alerts */
        .alert {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            animation: slideIn 0.5s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 5px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 5px solid #dc3545;
        }

        /* Appointment Items */
        .appointment-list {
            max-height: 600px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .appointment-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary);
            transition: all 0.3s;
        }

        .appointment-item:hover {
            transform: translateX(5px);
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .appointment-item.today {
            border-left-color: #28a745;
            background: linear-gradient(to right, #f8fff9, #f0fff4);
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .patient-name {
            font-weight: 700;
            color: var(--dark);
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .appointment-time {
            background: var(--primary);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .appointment-details {
            color: var(--gray);
            line-height: 1.6;
        }

        .appointment-details p {
            margin: 6px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .appointment-id {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 48px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--dark);
            margin-bottom: 10px;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1><i class="fas fa-calendar-plus"></i> Schedule Appointment</h1>
                <p>Book appointments for patients quickly and efficiently</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-nurse"></i>
                </div>
                <div>
                    <strong><?php echo htmlspecialchars($user_name); ?></strong><br>
                    <span style="color: var(--gray); font-size: 14px;">Nurse</span>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle fa-lg"></i>
                <div><?php echo $success; ?></div>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
                <div><?php echo $error; ?></div>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Appointment Form -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-calendar-alt"></i> New Appointment</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <!-- Patient -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-injured"></i> Select Patient *
                            </label>
                            <select class="form-control" name="patient_id" required>
                                <option value="">Choose patient...</option>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo $patient['id']; ?>" <?php echo (!empty($_POST['patient_id']) && $_POST['patient_id'] == $patient['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($patient['full_name'] . ' (ID: ' . $patient['patient_id'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Doctor -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-md"></i> Select Doctor (Optional)
                            </label>
                            <select class="form-control" name="doctor_id">
                                <option value="">General Consultation (No specific doctor)</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?php echo $doctor['id']; ?>" <?php echo (!empty($_POST['doctor_id']) && $_POST['doctor_id'] == $doctor['id']) ? 'selected' : ''; ?>>
                                        Dr. <?php echo htmlspecialchars($doctor['full_name']); ?>
                                        <?php if ($doctor['specialization']): ?>
                                            - <?php echo htmlspecialchars($doctor['specialization']); ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Date & Time -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar"></i> Appointment Date *
                                </label>
                                <input type="date" class="form-control" name="appointment_date"
                                    value="<?php echo !empty($_POST['appointment_date']) ? $_POST['appointment_date'] : date('Y-m-d'); ?>"
                                    min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-clock"></i> Time *
                                </label>
                                <input type="time" class="form-control" name="appointment_time"
                                    value="<?php echo !empty($_POST['appointment_time']) ? $_POST['appointment_time'] : '09:00'; ?>"
                                    min="08:00" max="18:00" required>
                            </div>
                        </div>

                        <!-- Appointment Type -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-stethoscope"></i> Appointment Type *
                            </label>
                            <select class="form-control" name="appointment_type" required>
                                <option value="consultation" <?php echo (!empty($_POST['appointment_type']) && $_POST['appointment_type'] == 'consultation') ? 'selected' : ''; ?>>General
                                    Consultation</option>
                                <option value="followup" <?php echo (!empty($_POST['appointment_type']) && $_POST['appointment_type'] == 'followup') ? 'selected' : ''; ?>>Follow-up Visit
                                </option>
                                <option value="checkup" <?php echo (!empty($_POST['appointment_type']) && $_POST['appointment_type'] == 'checkup') ? 'selected' : ''; ?>>Regular Check-up
                                </option>
                                <option value="emergency" <?php echo (!empty($_POST['appointment_type']) && $_POST['appointment_type'] == 'emergency') ? 'selected' : ''; ?>>Emergency</option>
                                <option value="vaccination" <?php echo (!empty($_POST['appointment_type']) && $_POST['appointment_type'] == 'vaccination') ? 'selected' : ''; ?>>Vaccination
                                </option>
                            </select>
                        </div>

                        <!-- Reason -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-comment-medical"></i> Reason / Symptoms *
                            </label>
                            <textarea class="form-control" name="reason" rows="4"
                                placeholder="Describe the reason for appointment, symptoms, or concerns..."
                                required><?php echo !empty($_POST['reason']) ? htmlspecialchars($_POST['reason']) : ''; ?></textarea>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-notes-medical"></i> Additional Notes (Optional)
                            </label>
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Any additional notes, special instructions, or comments..."><?php echo !empty($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn">
                            <i class="fas fa-calendar-check"></i> Schedule Appointment
                        </button>
                    </form>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745, #218838);">
                    <h2><i class="fas fa-calendar-day"></i> Today's Appointments</h2>
                </div>
                <div class="card-body">
                    <div class="appointment-list">
                        <?php if (empty($today_appointments)): ?>
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h3>No Appointments Today</h3>
                                <p>All clear! No appointments scheduled for today.</p>
                                <p style="margin-top: 20px; color: var(--primary);">
                                    <i class="fas fa-info-circle"></i>
                                    Schedule new appointments using the form
                                </p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($today_appointments as $apt): ?>
                                <div class="appointment-item today">
                                    <div class="appointment-header">
                                        <div class="patient-name">
                                            <i class="fas fa-user"></i>
                                            <?php echo htmlspecialchars($apt['first_name'] . ' ' . $apt['last_name']); ?>
                                        </div>
                                        <div class="appointment-time">
                                            <?php echo date('h:i A', strtotime($apt['appointment_time'])); ?>
                                        </div>
                                    </div>
                                    <div class="appointment-details">
                                        <p>
                                            <i class="fas fa-stethoscope"></i>
                                            <strong>Type:</strong>
                                            <?php echo ucwords(str_replace('_', ' ', $apt['appointment_type'] ?? 'consultation')); ?>
                                        </p>
                                        <?php if (!empty($apt['doctor_name'])): ?>
                                            <p>
                                                <i class="fas fa-user-md"></i>
                                                <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($apt['doctor_name']); ?>
                                            </p>
                                        <?php endif; ?>
                                        <p>
                                            <i class="fas fa-file-medical"></i>
                                            <strong>Reason:</strong>
                                            <?php echo htmlspecialchars(substr($apt['reason'] ?? 'General consultation', 0, 100)); ?>
                                            <?php if (strlen($apt['reason'] ?? '') > 100): ?>...<?php endif; ?>
                                        </p>
                                        <div class="appointment-id">
                                            <i class="fas fa-hashtag"></i>
                                            ID: <?php echo htmlspecialchars($apt['appointment_id'] ?? 'N/A'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            const patientSelect = document.querySelector('select[name="patient_id"]');
            const reasonTextarea = document.querySelector('textarea[name="reason"]');

            if (!patientSelect.value) {
                e.preventDefault();
                showAlert('Please select a patient', 'error');
                patientSelect.focus();
                return;
            }

            if (!reasonTextarea.value.trim()) {
                e.preventDefault();
                showAlert('Please enter reason for appointment', 'error');
                reasonTextarea.focus();
                return;
            }

            // Show loading
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scheduling...';
            submitBtn.disabled = true;

            // Re-enable after 3 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <div>${message}</div>
            `;

            document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.main-content'));

            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        }

        // Set minimum time for today's appointments
        const dateInput = document.querySelector('input[name="appointment_date"]');
        const timeInput = document.querySelector('input[name="appointment_time"]');

        dateInput.addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();

            if (selectedDate.toDateString() === today.toDateString()) {
                const now = new Date();
                const currentHour = now.getHours().toString().padStart(2, '0');
                const currentMinute = now.getMinutes().toString().padStart(2, '0');
                timeInput.min = `${currentHour}:${currentMinute}`;

                if (timeInput.value < timeInput.min) {
                    timeInput.value = timeInput.min;
                }
            } else {
                timeInput.min = '08:00';
            }
        });

        // Auto-refresh appointments every 60 seconds
        setInterval(() => {
            if (!document.hidden) {
                window.location.reload();
            }
        }, 60000);
    </script>
</body>

</html>