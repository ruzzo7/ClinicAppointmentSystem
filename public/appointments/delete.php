<?php
/**
 * Delete Appointment
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';

startSecureSession();
requireLogin(); // Require authentication


// Get appointment ID
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    setFlashMessage('error', 'Invalid appointment ID');
    redirect(url('appointments/index.php'));
}

// Delete appointment
if (deleteAppointment($id)) {
    setFlashMessage('success', 'Appointment deleted successfully!');
} else {
    setFlashMessage('error', 'Failed to delete appointment');
}

redirect(url('appointments/index.php'));
