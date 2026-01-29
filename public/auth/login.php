<?php
/**
 * Login Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(url('index.php'));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect(url('auth/login.php'));
    }

    // Math Verification
    $userAnswer = intval($_POST['math_answer'] ?? 0);
    $correctAnswer = $_SESSION['math_answer'] ?? null;

    if ($correctAnswer === null || $userAnswer !== $correctAnswer) {
        setFlashMessage('error', 'Security verification failed. Please solve the math problem correctly.');
        redirect(url('auth/login.php'));
    }
    
    // Clear answer
    unset($_SESSION['math_answer']);
    
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        setFlashMessage('error', 'Please enter both username and password.');
    } else {
        // Authenticate user
        $user = authenticateUser($username, $password);
        
        if ($user) {
            // Login successful
            loginUser($user);
            
            // Check for redirect URL
            $redirectUrl = $_SESSION['redirect_after_login'] ?? url('index.php');
            unset($_SESSION['redirect_after_login']);
            
            setFlashMessage('success', 'Welcome back, ' . $user['full_name'] . '!');
            redirect($redirectUrl);
        } else {
            setFlashMessage('error', 'Invalid username or password.');
        }
    }
}

// Get flash message if any
$flash = getFlashMessage();

// Generate Math Challenge
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['math_answer'] = $num1 + $num2;

// Render view
renderView('auth.login', [
    'csrf_token' => generateCSRFToken(),
    'flash' => $flash,
    'math_question' => "$num1 + $num2"
]);
