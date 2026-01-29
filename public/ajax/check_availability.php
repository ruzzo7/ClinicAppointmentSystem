<?php
/**
 * Ajax Endpoint: Check Available Time Slots
 * This is the key Ajax feature that provides live availability checking
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';

startSecureSession();
requireLogin(); // Require authentication

// Set JSON header
header('Content-Type: application/json');

// Validate input
$doctor_id = filter_var($_GET['doctor_id'] ?? 0, FILTER_VALIDATE_INT);
$date = sanitizeInput($_GET['date'] ?? '');
$exclude_id = filter_var($_GET['exclude_id'] ?? 0, FILTER_VALIDATE_INT);

// Validate required parameters
if (!$doctor_id || !validateDate($date)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid parameters'
    ]);
    exit;
}

// Get available time slots
try {
    $slots = getAvailableTimeSlots($doctor_id, $date);
    
    // If editing an appointment, mark the current slot as available
    if ($exclude_id) {
        $currentAppointment = getAppointmentById($exclude_id);
        if ($currentAppointment) {
            foreach ($slots as &$slot) {
                if ($slot['time'] === $currentAppointment['appointment_time']) {
                    $slot['available'] = true;
                }
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'slots' => $slots,
        'date' => $date,
        'doctor_id' => $doctor_id
    ]);
} catch (Exception $e) {
    error_log("Ajax Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching available slots'
    ]);
}
