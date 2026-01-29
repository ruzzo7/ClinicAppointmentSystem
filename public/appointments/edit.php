<?php
/**
 * Edit Appointment Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Get appointment ID
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    setFlashMessage('error', 'Invalid appointment ID');
    redirect(url('appointments/index.php'));
}

// Get appointment data
$appointment = getAppointmentById($id);
if (!$appointment) {
    setFlashMessage('error', 'Appointment not found');
    redirect(url('appointments/index.php'));
}

// Get patients and doctors for dropdowns
$patients = getAllPatients();
$doctors = getAllDoctors();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect('/public/appointments/edit.php?id=' . $id);
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
    
    // Auto-update time if status changed from "scheduled" to another status
    if ($appointment['status'] === 'scheduled' && $data['status'] !== 'scheduled') {
        $data['appointment_time'] = date('H:i:s'); // Current system time
    }

    
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
    }
    
    if (!validateTime($data['appointment_time'])) {
        $errors[] = 'Invalid appointment time';
    }
    
    if (empty($data['reason'])) {
        $errors[] = 'Reason for visit is required';
    }
    
    if (!in_array($data['status'], ['scheduled', 'completed', 'cancelled'])) {
        $errors[] = 'Invalid status';
    }
    
    // If no errors, update appointment
    if (empty($errors)) {
        if (updateAppointment($id, $data)) {
            setFlashMessage('success', 'Appointment updated successfully!');
            redirect(url('appointments/index.php'));
        } else {
            setFlashMessage('error', 'Failed to update appointment. The selected time slot may no longer be available.');
            redirect('/public/appointments/edit.php?id=' . $id);
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('appointments.form', [
    'appointment' => $appointment,
    'patients' => $patients,
    'doctors' => $doctors,
    'csrf_token' => generateCSRFToken(),
    'flash' => $flash
]);
