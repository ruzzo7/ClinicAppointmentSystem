<?php
/**
 * Logout Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';

startSecureSession();

// Logout user
logoutUser();

// Redirect to login page
setFlashMessage('success', 'You have been logged out successfully.');
redirect(url('auth/login.php'));
