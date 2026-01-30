<?php
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

// Require login
requireLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    setFlashMessage('error', 'Doctor ID is required');
    redirect(url('doctors/index.php'));
}

$doctor = getDoctorById($id);

if (!$doctor) {
    setFlashMessage('error', 'Doctor not found');
    redirect(url('doctors/index.php'));
}

// Get doctor's appointments
$appointments = getAppointmentsByDoctorId($id);

echo renderView('doctors.view', [
    'doctor' => $doctor,
    'appointments' => $appointments
]);
