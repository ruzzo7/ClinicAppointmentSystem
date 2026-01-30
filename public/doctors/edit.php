<?php
/**
 * Edit Doctor Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Get doctor ID
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    setFlashMessage('error', 'Invalid doctor ID');
    redirect(url('doctors/index.php'));
}

// Get doctor data
$doctor = getDoctorById($id);
if (!$doctor) {
    setFlashMessage('error', 'Doctor not found');
    redirect(url('doctors/index.php'));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect('/public/doctors/edit.php?id=' . $id);
    }
    
    // Sanitize and validate input
    $availableDays = isset($_POST['available_days']) && is_array($_POST['available_days']) 
        ? implode(',', array_map('sanitizeInput', $_POST['available_days']))
        : '';
    
    $data = [
        'name' => sanitizeInput($_POST['name'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'phone' => sanitizeInput($_POST['phone'] ?? ''),
        'specialization' => sanitizeInput($_POST['specialization'] ?? ''),
        'qualification' => sanitizeInput($_POST['qualification'] ?? ''),
        'available_days' => $availableDays,
        'start_time' => sanitizeInput($_POST['start_time'] ?? '09:00:00'),
        'end_time' => sanitizeInput($_POST['end_time'] ?? '17:00:00')
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
    
    if (empty($data['specialization'])) {
        $errors[] = 'Specialization is required';
    }
    
    if (empty($data['qualification'])) {
        $errors[] = 'Qualification is required';
    }
    
    if (empty($data['available_days'])) {
        $errors[] = 'Please select at least one available day';
    }
    
    if (!validateTime($data['start_time'])) {
        $errors[] = 'Invalid start time';
    }
    
    if (!validateTime($data['end_time'])) {
        $errors[] = 'Invalid end time';
    }
    
    if (strtotime($data['start_time']) >= strtotime($data['end_time'])) {
        $errors[] = 'End time must be after start time';
    }
    
    // If no errors, update doctor
    if (empty($errors)) {
        if (updateDoctor($id, $data)) {
            setFlashMessage('success', 'Doctor updated successfully!');
            redirect(url('doctors/index.php'));
        } else {
            setFlashMessage('error', 'Failed to update doctor. Email may already exist.');
            redirect('/public/doctors/edit.php?id=' . $id);
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('doctors.form', [
    'doctor' => $doctor,
    'csrf_token' => generateCSRFToken(),
    'flash' => $flash
]);
