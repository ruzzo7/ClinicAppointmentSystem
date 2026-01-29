<?php
/**
 * Create Appointment Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Get patients and doctors for dropdowns
$patients = getAllPatients();
$doctors = getAllDoctors();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect(url('appointments/create.php'));
    }
    
    // Sanitize and validate input
    $data = [
        'patient_id' => filter_var($_POST['patient_id'] ?? 0, FILTER_VALIDATE_INT),
        'doctor_id' => filter_var($_POST['doctor_id'] ?? 0, FILTER_VALIDATE_INT),
        'appointment_date' => sanitizeInput($_POST['appointment_date'] ?? ''),
        'appointment_time' => sanitizeInput($_POST['appointment_time'] ?? ''),
        'reason' => sanitizeInput($_POST['reason'] ?? ''),
        'status' => sanitizeInput($_POST['status'] ?? 'scheduled')
    ];
    
    // Server-side validation
    $errors = [];
    
    if (!$data['patient_id']) {
        $errors[] = 'Please select a patient';
    }
    
    if (!$data['doctor_id']) {
        $errors[] = 'Please select a doctor';
    }
    
    if (!validateDate($data['appointment_date'])) {
        $errors[] = 'Invalid appointment date';
    } elseif (strtotime($data['appointment_date']) < strtotime(date('Y-m-d'))) {
        $errors[] = 'Appointment date cannot be in the past';
    }
    
    if (!validateTime($data['appointment_time'])) {
        $errors[] = 'Invalid appointment time';
    }
    
    if (empty($data['reason'])) {
        $errors[] = 'Reason for visit is required';
    }
    
    // If no errors, create appointment
    if (empty($errors)) {
        if (createAppointment($data)) {
            setFlashMessage('success', 'Appointment booked successfully!');
            redirect(url('appointments/index.php'));
        } else {
            setFlashMessage('error', 'Failed to book appointment. The selected time slot may no longer be available.');
            redirect(url('appointments/create.php'));
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('appointments.form', [
    'patients' => $patients,
    'doctors' => $doctors,
    'csrf_token' => generateCSRFToken(),
    'flash' => $flash
]);
