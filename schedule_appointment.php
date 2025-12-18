<?php
session_start();
require_once 'db_connection.php';

// Check if nurse is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'nurse') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate appointment ID
    $appointment_id = "APT" . date('Ymd') . rand(1000, 9999);

    // Get form data
    $patient_id = $_POST['patient_id'];
    $patient_name = $_POST['patient_name'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_type = $_POST['appointment_type'];
    $reason = $_POST['reason'];
    $nurse_id = $_SESSION['user_id'];
    $nurse_name = $_SESSION['full_name'];

    // Insert into database
    $sql = "INSERT INTO appointments (appointment_id, patient_id, patient_name, 
            appointment_date, appointment_time, appointment_type, reason, 
            nurse_id, nurse_name, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'scheduled')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sisssssis",
        $appointment_id,
        $patient_id,
        $patient_name,
        $appointment_date,
        $appointment_time,
        $appointment_type,
        $reason,
        $nurse_id,
        $nurse_name
    );

    if ($stmt->execute()) {
        $success = "Appointment scheduled successfully! ID: $appointment_id";
    } else {
        $error = "Error scheduling appointment: " . $stmt->error;
    }
}

// Get patients list for dropdown
$patients_sql = "SELECT id, CONCAT(first_name, ' ', last_name) as full_name FROM patients ORDER BY first_name";
$patients_result = $conn->query($patients_sql);
?>

<!-- HTML form for scheduling appointments -->
<!-- Include form with dropdown for patients, date picker, time selector, etc. -->