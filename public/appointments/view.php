<?php
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

// Require login
requireLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    setFlashMessage('error', 'Appointment ID is required');
    redirect(url('appointments/index.php'));
}

$appointment = getAppointmentById($id);

if (!$appointment) {
    setFlashMessage('error', 'Appointment not found');
    redirect(url('appointments/index.php'));
}

echo renderView('appointments.view', [
    'appointment' => $appointment
]);
