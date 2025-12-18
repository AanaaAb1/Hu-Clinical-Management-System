<?php
// register-patient.php
session_start();
require_once '../includes/config.php';

// Database connection
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

$page_title = "Register New Patient - HU CMS";
$user_name = $_SESSION['full_name'] ?? 'Nurse';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $db = getDB();

        // Generate patient ID
        $patient_id = 'PAT' . date('Ymd') . strtoupper(substr(uniqid(), -6));

        // Prepare data
        $data = [
            'patient_id' => $patient_id,
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'gender' => $_POST['gender'],
            'date_of_birth' => $_POST['date_of_birth'],
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'emergency_contact_name' => $_POST['emergency_contact_name'] ?? '',
            'emergency_contact_phone' => $_POST['emergency_contact_phone'] ?? '',
            'blood_type' => $_POST['blood_type'] ?? '',
            'allergies' => $_POST['allergies'] ?? '',
            'medical_history' => $_POST['medical_history'] ?? '',
            'insurance_provider' => $_POST['insurance_provider'] ?? '',
            'insurance_number' => $_POST['insurance_number'] ?? '',
            'marital_status' => $_POST['marital_status'] ?? '',
            'occupation' => $_POST['occupation'] ?? '',
            'nationality' => $_POST['nationality'] ?? '',
            'religion' => $_POST['religion'] ?? '',
            'language' => $_POST['language'] ?? '',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert into database
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO patients ($columns) VALUES ($placeholders)";

        $stmt = $db->prepare($sql);
        $stmt->execute($data);

        $patient_id = $db->lastInsertId();

        $success = "✅ Patient registered successfully!<br>Patient ID: <strong>$patient_id</strong>";

        // Clear form on success
        $_POST = [];

    } catch (Exception $e) {
        $error = "❌ Error: " . $e->getMessage();
    }
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
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left h1 {
            color: var(--dark);
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 15px;
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
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #545b62;
            transform: translateX(-5px);
        }
        
        /* Form Container */
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 25px;
        }
        
        .form-header h2 {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
        }
        
        .form-body {
            padding: 30px;
        }
        
        /* Form Sections */
        .form-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--primary);
            font-size: 20px;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef2ff;
        }
        
        .section-title i {
            background: #eef2ff;
            padding: 12px;
            border-radius: 10px;
        }
        
        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-label {
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
        
        .required::after {
            content: " *";
            color: #e74c3c;
        }
        
        .form-control {
            padding: 14px;
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
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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
        
        /* Buttons */
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 32px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            text-decoration: none;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        /* Animations */
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1><i class="fas fa-user-plus"></i> Register New Patient</h1>
                <p>Add new patient to the system</p>
            </div>
            <a href="patient-records.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Patient Records
            </a>
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

        <!-- Patient Registration Form -->
        <div class="form-container">
            <form method="POST" action="" id="patientForm">
                <!-- Personal Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-circle"></i> Personal Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="fas fa-user"></i> First Name
                            </label>
                            <input type="text" class="form-control" name="first_name" 
                                   value="<?php echo $_POST['first_name'] ?? ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="fas fa-user"></i> Last Name
                            </label>
                            <input type="text" class="form-control" name="last_name" 
                                   value="<?php echo $_POST['last_name'] ?? ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="fas fa-venus-mars"></i> Gender
                            </label>
                            <select class="form-control" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo ($_POST['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($_POST['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo ($_POST['gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="fas fa-birthday-cake"></i> Date of Birth
                            </label>
                            <input type="date" class="form-control" name="date_of_birth" 
                                   value="<?php echo $_POST['date_of_birth'] ?? ''; ?>" 
                                   max="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-heartbeat"></i> Blood Type
                            </label>
                            <select class="form-control" name="blood_type">
                                <option value="">Select Blood Type</option>
                                <option value="A+" <?php echo ($_POST['blood_type'] ?? '') == 'A+' ? 'selected' : ''; ?>>A+</option>
                                <option value="A-" <?php echo ($_POST['blood_type'] ?? '') == 'A-' ? 'selected' : ''; ?>>A-</option>
                                <option value="B+" <?php echo ($_POST['blood_type'] ?? '') == 'B+' ? 'selected' : ''; ?>>B+</option>
                                <option value="B-" <?php echo ($_POST['blood_type'] ?? '') == 'B-' ? 'selected' : ''; ?>>B-</option>
                                <option value="AB+" <?php echo ($_POST['blood_type'] ?? '') == 'AB+' ? 'selected' : ''; ?>>AB+</option>
                                <option value="AB-" <?php echo ($_POST['blood_type'] ?? '') == 'AB-' ? 'selected' : ''; ?>>AB-</option>
                                <option value="O+" <?php echo ($_POST['blood_type'] ?? '') == 'O+' ? 'selected' : ''; ?>>O+</option>
                                <option value="O-" <?php echo ($_POST['blood_type'] ?? '') == 'O-' ? 'selected' : ''; ?>>O-</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-ring"></i> Marital Status
                            </label>
                            <select class="form-control" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="single" <?php echo ($_POST['marital_status'] ?? '') == 'single' ? 'selected' : ''; ?>>Single</option>
                                <option value="married" <?php echo ($_POST['marital_status'] ?? '') == 'married' ? 'selected' : ''; ?>>Married</option>
                                <option value="divorced" <?php echo ($_POST['marital_status'] ?? '') == 'divorced' ? 'selected' : ''; ?>>Divorced</option>
                                <option value="widowed" <?php echo ($_POST['marital_status'] ?? '') == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-address-book"></i> Contact Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label required">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="tel" class="form-control" name="phone" 
                                   value="<?php echo $_POST['phone'] ?? ''; ?>" 
                                   pattern="[0-9]{10,15}" required>
                            <small style="color: var(--gray); font-size: 12px;">Format: 0912345678</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo $_POST['email'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <textarea class="form-control" name="address" rows="3"><?php echo $_POST['address'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flag"></i> Nationality
                            </label>
                            <input type="text" class="form-control" name="nationality" 
                                   value="<?php echo $_POST['nationality'] ?? 'Ethiopian'; ?>">
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-ambulance"></i> Emergency Contact
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-friends"></i> Emergency Contact Name
                            </label>
                            <input type="text" class="form-control" name="emergency_contact_name" 
                                   value="<?php echo $_POST['emergency_contact_name'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone-alt"></i> Emergency Contact Phone
                            </label>
                            <input type="tel" class="form-control" name="emergency_contact_phone" 
                                   value="<?php echo $_POST['emergency_contact_phone'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-file-medical"></i> Medical Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-allergies"></i> Allergies
                            </label>
                            <textarea class="form-control" name="allergies" rows="3" 
                                      placeholder="List any allergies (e.g., Penicillin, Nuts, etc.)"><?php echo $_POST['allergies'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-history"></i> Medical History
                            </label>
                            <textarea class="form-control" name="medical_history" rows="3" 
                                      placeholder="Previous medical conditions, surgeries, etc."><?php echo $_POST['medical_history'] ?? ''; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i> Additional Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-briefcase"></i> Occupation
                            </label>
                            <input type="text" class="form-control" name="occupation" 
                                   value="<?php echo $_POST['occupation'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-church"></i> Religion
                            </label>
                            <select class="form-control" name="religion">
                                <option value="">Select Religion</option>
                                <option value="christian" <?php echo ($_POST['religion'] ?? '') == 'christian' ? 'selected' : ''; ?>>Christian</option>
                                <option value="muslim" <?php echo ($_POST['religion'] ?? '') == 'muslim' ? 'selected' : ''; ?>>Muslim</option>
                                <option value="other" <?php echo ($_POST['religion'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-language"></i> Language
                            </label>
                            <input type="text" class="form-control" name="language" 
                                   value="<?php echo $_POST['language'] ?? 'Amharic'; ?>">
                        </div>
                    </div>
                </div>

                <!-- Insurance Information -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-shield-alt"></i> Insurance Information
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building"></i> Insurance Provider
                            </label>
                            <input type="text" class="form-control" name="insurance_provider" 
                                   value="<?php echo $_POST['insurance_provider'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-hashtag"></i> Insurance Number
                            </label>
                            <input type="text" class="form-control" name="insurance_number" 
                                   value="<?php echo $_POST['insurance_number'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="patient-records.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Register Patient
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Form validation
        document.getElementById('patientForm').addEventListener('submit', function(e) {
            const firstName = document.querySelector('input[name="first_name"]');
            const lastName = document.querySelector('input[name="last_name"]');
            const phone = document.querySelector('input[name="phone"]');
            
            // Basic validation
            if (!firstName.value.trim()) {
                e.preventDefault();
                showAlert('Please enter first name', 'error');
                firstName.focus();
                return;
            }
            
            if (!lastName.value.trim()) {
                e.preventDefault();
                showAlert('Please enter last name', 'error');
                lastName.focus();
                return;
            }
            
            if (!phone.value.trim()) {
                e.preventDefault();
                showAlert('Please enter phone number', 'error');
                phone.focus();
                return;
            }
            
            // Phone validation
            const phoneRegex = /^[0-9]{10,15}$/;
            if (!phoneRegex.test(phone.value)) {
                e.preventDefault();
                showAlert('Please enter a valid phone number (10-15 digits)', 'error');
                phone.focus();
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';
            submitBtn.disabled = true;
            
            // Re-enable after 5 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
        
        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <div>${message}</div>
            `;
            
            // Insert after header
            const header = document.querySelector('.header');
            header.parentNode.insertBefore(alertDiv, header.nextSibling);
            
            // Remove after 5 seconds
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        }
        
        // Calculate age from date of birth
        const dobInput = document.querySelector('input[name="date_of_birth"]');
        dobInput.addEventListener('change', function() {
            if (this.value) {
                const dob = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                // Optional: Show age somewhere
                console.log('Patient age:', age);
            }
        });
    </script>
</body>
</html>