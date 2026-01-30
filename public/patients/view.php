<?php
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

// Require login
requireLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    setFlashMessage('error', 'Patient ID is required');
    redirect(url('patients/index.php'));
}

$patient = getPatientById($id);

if (!$patient) {
    setFlashMessage('error', 'Patient not found');
    redirect(url('patients/index.php'));
}

// Get patient's appointments
$appointments = getAppointmentsByPatientId($id);

echo renderView('patients.view', [
    'patient' => $patient,
    'appointments' => $appointments
]);
