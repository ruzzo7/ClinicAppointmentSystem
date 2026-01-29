<?php
/**
 * Search Appointments Page
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

// Initialize results
$results = null;

// Handle search
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    $criteria = [
        'date' => sanitizeInput($_GET['date'] ?? ''),
        'doctor_id' => filter_var($_GET['doctor_id'] ?? 0, FILTER_VALIDATE_INT),
        'patient_id' => filter_var($_GET['patient_id'] ?? 0, FILTER_VALIDATE_INT),
        'status' => sanitizeInput($_GET['status'] ?? '')
    ];
    
    // Remove empty criteria
    $criteria = array_filter($criteria);
    
    // Search appointments
    $results = searchAppointments($criteria);
}

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('appointments.search', [
    'patients' => $patients,
    'doctors' => $doctors,
    'results' => $results,
    'flash' => $flash
]);
