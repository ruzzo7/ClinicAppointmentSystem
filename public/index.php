<?php
/**
 * Home Page - Main entry point
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Get statistics for homepage
$stats = [
    'patients' => fetchOne("SELECT COUNT(*) as count FROM patients")['count'] ?? 0,
    'doctors' => fetchOne("SELECT COUNT(*) as count FROM doctors")['count'] ?? 0,
    'appointments' => fetchOne("SELECT COUNT(*) as count FROM appointments")['count'] ?? 0,
    'today' => fetchOne("SELECT COUNT(*) as count FROM appointments WHERE appointment_date = CURDATE()")['count'] ?? 0,
];

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('home', [
    'stats' => $stats,
    'flash' => $flash
]);
