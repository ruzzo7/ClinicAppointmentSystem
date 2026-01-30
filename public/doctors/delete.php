<?php
/**
 * Delete Doctor
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';

startSecureSession();
requireLogin(); // Require authentication


// Get doctor ID
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    setFlashMessage('error', 'Invalid doctor ID');
    redirect(url('doctors/index.php'));
}

// Delete doctor
if (deleteDoctor($id)) {
    setFlashMessage('success', 'Doctor deleted successfully!');
} else {
    setFlashMessage('error', 'Failed to delete doctor');
}

redirect(url('doctors/index.php'));
