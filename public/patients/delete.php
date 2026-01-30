<?php
/**
 * Delete Patient
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';

startSecureSession();
requireLogin(); // Require authentication


// Get patient ID
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    setFlashMessage('error', 'Invalid patient ID');
    redirect(url('patients/index.php'));
}

// Delete patient
if (deletePatient($id)) {
    setFlashMessage('success', 'Patient deleted successfully!');
} else {
    setFlashMessage('error', 'Failed to delete patient');
}

redirect(url('patients/index.php'));
