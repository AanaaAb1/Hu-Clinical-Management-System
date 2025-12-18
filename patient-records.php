// In the patient card actions section, update these functions:
function editPatient(patientId) {
window.location.href = `edit-patient.php?id=${patientId}`;
}

// Add this new function for the "View" button
function viewPatientDetails(patientId) {
window.location.href = `patient-details.php?id=${patientId}`;
}

// Update the "View" button in patient cards:
// Change from:
<button class="action-btn" onclick="viewPatient(<?php echo $patient['id']; ?>)">
    <i class="fas fa-eye"></i>
    <span>View</span>
</button>

// To:
<button class="action-btn" onclick="viewPatientDetails(<?php echo $patient['id']; ?>)">
    <i class="fas fa-eye"></i>
    <span>View</span>
</button>