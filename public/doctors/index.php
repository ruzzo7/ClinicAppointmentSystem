<?php
/**
 * Doctors Listing Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Get all doctors
$doctors = getAllDoctors();

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('doctors.index', [
    'doctors' => $doctors,
    'flash' => $flash
]);
