<?php
/**
 * Appointments Listing Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Get all appointments
$appointments = getAllAppointments();

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('appointments.index', [
    'appointments' => $appointments,
    'flash' => $flash
]);
