<?php
/**
 * Edit Patient Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Get patient ID
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    setFlashMessage('error', 'Invalid patient ID');
    redirect(url('patients/index.php'));
}

// Get patient data
$patient = getPatientById($id);
if (!$patient) {
    setFlashMessage('error', 'Patient not found');
    redirect(url('patients/index.php'));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect('/public/patients/edit.php?id=' . $id);
    }
    
    // Sanitize and validate input
    $data = [
        'name' => sanitizeInput($_POST['name'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'phone' => sanitizeInput($_POST['phone'] ?? ''),
        'address' => sanitizeInput($_POST['address'] ?? ''),
        'date_of_birth' => sanitizeInput($_POST['date_of_birth'] ?? ''),
        'gender' => sanitizeInput($_POST['gender'] ?? '')
    ];
    
    // Server-side validation
    $errors = [];
    
    if (empty($data['name']) || strlen($data['name']) < 2) {
        $errors[] = 'Name must be at least 2 characters long';
    }
    
    if (!validateEmail($data['email'])) {
        $errors[] = 'Invalid email address';
    }
    
    if (!validatePhone($data['phone'])) {
        $errors[] = 'Invalid phone number';
    }
    
    if (!validateDate($data['date_of_birth'])) {
        $errors[] = 'Invalid date of birth';
    }
    
    if (!in_array($data['gender'], ['male', 'female', 'other'])) {
        $errors[] = 'Invalid gender selection';
    }
    
    // If no errors, update patient
    if (empty($errors)) {
        if (updatePatient($id, $data)) {
            setFlashMessage('success', 'Patient updated successfully!');
            redirect(url('patients/index.php'));
        } else {
            setFlashMessage('error', 'Failed to update patient. Email may already exist.');
            redirect('/public/patients/edit.php?id=' . $id);
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('patients.form', [
    'patient' => $patient,
    'csrf_token' => generateCSRFToken(),
    'flash' => $flash
]);
