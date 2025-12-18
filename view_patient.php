<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';

if (!Helper::isLoggedIn()) {
    Helper::redirect('../login.php', 'Please login to access this page.', 'warning');
}

$patient_id = $_GET['id'] ?? '';
if (empty($patient_id)) {
    Helper::redirect('patients.php', 'Patient ID is required.', 'error');
}

// Get patient details
$patient = Database::query("SELECT * FROM patients WHERE patient_id = ?", [$patient_id]);
if (!$patient) {
    Helper::redirect('patients.php', 'Patient not found.', 'error');
}

$page_title = "Patient: " . $patient['first_name'] . " " . $patient['last_name'] . " - HU CMS";

?>

<!-- Add CSS for patient view page -->
<style>
    .patient-profile {
        padding: 2rem 0;
    }

    .profile-header {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .patient-avatar {
        width: 120px;
        height: 120px;
        background: var(--light-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--primary-color);
    }

    .patient-info h1 {
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .patient-id {
        font-size: 1.1rem;
        color: var(--gray-color);
        margin-bottom: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .info-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
    }

    .info-card h3 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
    }

    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
    }

    .info-label {
        font-weight: 600;
        width: 150px;
        color: var(--dark-color);
    }

    .info-value {
        flex: 1;
        color: var(--gray-color);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>

<div class="patient-profile">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="patient-avatar">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="patient-info">
                <h1><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></h1>
                <div class="patient-id">Patient ID: <?php echo htmlspecialchars($patient['patient_id']); ?></div>
                <span class="badge badge-active"><?php echo htmlspecialchars(ucfirst($patient['status'])); ?></span>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <!-- Personal Information -->
            <div class="info-card">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <div class="info-row">
                    <div class="info-label">Date of Birth:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['date_of_birth']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gender:</div>
                    <div class="info-value"><?php echo htmlspecialchars(ucfirst($patient['gender'])); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Marital Status:</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars(ucfirst($patient['marital_status'] ?? 'Not specified')); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Occupation:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['occupation'] ?? 'Not specified'); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nationality:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['nationality'] ?? 'Not specified'); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Religion:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['religion'] ?? 'Not specified'); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Language:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['language'] ?? 'Not specified'); ?>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="info-card">
                <h3><i class="fas fa-address-book"></i> Contact Information</h3>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['email'] ?? 'Not specified'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['phone'] ?? 'Not specified'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Emergency Contact:</div>
                    <div class="info-value">
                        <?php
                        $emergency = [];
                        if (!empty($patient['emergency_contact_name']))
                            $emergency[] = $patient['emergency_contact_name'];
                        if (!empty($patient['emergency_contact']))
                            $emergency[] = $patient['emergency_contact'];
                        echo $emergency ? htmlspecialchars(implode(' - ', $emergency)) : 'Not specified';
                        ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Address:</div>
                    <div class="info-value">
                        <?php
                        $address_parts = [];
                        if (!empty($patient['address']))
                            $address_parts[] = $patient['address'];
                        if (!empty($patient['city']))
                            $address_parts[] = $patient['city'];
                        if (!empty($patient['state']))
                            $address_parts[] = $patient['state'];
                        if (!empty($patient['zip_code']))
                            $address_parts[] = $patient['zip_code'];
                        echo $address_parts ? htmlspecialchars(implode(', ', $address_parts)) : 'Not specified';
                        ?>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="info-card">
                <h3><i class="fas fa-heartbeat"></i> Medical Information</h3>
                <div class="info-row">
                    <div class="info-label">Blood Group:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['blood_group'] ?? 'Not specified'); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Allergies:</div>
                    <div class="info-value">
                        <?php echo nl2br(htmlspecialchars($patient['allergies'] ?? 'None recorded')); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Medical History:</div>
                    <div class="info-value">
                        <?php echo nl2br(htmlspecialchars($patient['medical_history'] ?? 'None recorded')); ?>
                    </div>
                </div>
            </div>

            <!-- Insurance Information -->
            <div class="info-card">
                <h3><i class="fas fa-file-invoice-dollar"></i> Insurance Information</h3>
                <div class="info-row">
                    <div class="info-label">Insurance Provider:</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($patient['insurance_provider'] ?? 'Not specified'); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Insurance ID:</div>
                    <div class="info-value"><?php echo htmlspecialchars($patient['insurance_id'] ?? 'Not specified'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="patients.php" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Patients
            </a>
            <a href="edit_patient.php?id=<?php echo $patient['patient_id']; ?>" class="btn btn-secondary">
                <i class="fas fa-edit"></i> Edit Patient
            </a>
            <a href="register_patient.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Register Another Patient
            </a>
        </div>
    </div>
</div>