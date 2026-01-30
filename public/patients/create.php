<?php
/**
 * Create Patient Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect(url('patients/create.php'));
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
    
    // If no errors, create patient
    if (empty($errors)) {
        if (createPatient($data)) {
            setFlashMessage('success', 'Patient added successfully!');
            redirect(url('patients/index.php'));
        } else {
            setFlashMessage('error', 'Failed to add patient. Email may already exist.');
            redirect(url('patients/create.php'));
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('patients.form', [
    'csrf_token' => generateCSRFToken(),
    'flash' => $flash
]);
