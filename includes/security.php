<?php
/**
 * Security Functions
 * Implements CSRF protection, XSS prevention, and input validation
 */

// Start session securely
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
        session_name($_ENV['SESSION_NAME'] ?? 'clinic_session');
        session_start();
    }
}

/**
 * Generate CSRF token
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return bool
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input to prevent XSS
 * @param string $data Input data
 * @return string
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 * @param string $email Email address
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (basic validation)
 * @param string $phone Phone number
 * @return bool
 */
function validatePhone($phone) {
    return preg_match('/^[0-9\-\+\(\)\s]{10,20}$/', $phone);
}

/**
 * Validate date format (YYYY-MM-DD)
 * @param string $date Date string
 * @return bool
 */
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Validate time format (HH:MM or HH:MM:SS)
 * @param string $time Time string
 * @return bool
 */
function validateTime($time) {
    if (!$time) return false;
    // Supports HH:MM and HH:MM:SS
    return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $time);
}

/**
 * Redirect to a page
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Set flash message
 * @param string $type Message type (success, error, warning, info)
 * @param string $message Message content
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * @return array|null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Hash password securely
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

/**
 * Verify password against hash
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Authenticate user with username/email and password
 * @param string $username Username or email
 * @param string $password Password
 * @return array|false User data if authenticated, false otherwise
 */
function authenticateUser($username, $password) {
    require_once __DIR__ . '/../config/db.php';
    
    $sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1";
    $user = fetchOne($sql, [$username, $username]);
    
    if ($user && verifyPassword($password, $user['password'])) {
        // Update last login
        executeQuery("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
        return $user;
    }
    
    return false;
}

/**
 * Login user and create session
 * @param array $user User data
 */
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_time'] = time();
    
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);
}

/**
 * Logout user and destroy session
 */
function logoutUser() {
    $_SESSION = [];
    
    // Delete session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Get current logged-in user data
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'full_name' => $_SESSION['full_name'],
        'role' => $_SESSION['role']
    ];
}

/**
 * Check if user has specific role
 * @param string|array $roles Role(s) to check
 * @return bool
 */
function hasRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $userRole = $_SESSION['role'] ?? '';
    
    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }
    
    return $userRole === $roles;
}

/**
 * Require login - redirect to login page if not authenticated
 * @param string $redirectUrl URL to redirect after login
 */
function requireLogin($redirectUrl = null) {
    if (!isLoggedIn()) {
        if ($redirectUrl) {
            $_SESSION['redirect_after_login'] = $redirectUrl;
        }
        setFlashMessage('error', 'Please login to access this page.');
        redirect(url('auth/login.php'));
        exit();
    }
}

/**
 * Require specific role - redirect if user doesn't have required role
 * @param string|array $roles Required role(s)
 */
function requireRole($roles) {
    requireLogin();
    
    if (!hasRole($roles)) {
        setFlashMessage('error', 'You do not have permission to access this page.');
        redirect(url('index.php'));
        exit();
    }
}

/**
 * Create a new user
 * @param array $data User data
 * @return bool|int User ID if successful, false otherwise
 */
function createUser($data) {
    require_once __DIR__ . '/../config/db.php';
    
    $sql = "INSERT INTO users (username, email, password, full_name, role) 
            VALUES (?, ?, ?, ?, ?)";
    
    try {
        executeQuery($sql, [
            $data['username'],
            $data['email'],
            hashPassword($data['password']),
            $data['full_name'],
            $data['role'] ?? 'staff'
        ]);
        return lastInsertId();
    } catch (PDOException $e) {
        error_log("Create User Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update user password
 * @param int $userId User ID
 * @param string $newPassword New password
 * @return bool
 */
function updateUserPassword($userId, $newPassword) {
    require_once __DIR__ . '/../config/db.php';
    
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    
    try {
        executeQuery($sql, [hashPassword($newPassword), $userId]);
        return true;
    } catch (PDOException $e) {
        error_log("Update Password Error: " . $e->getMessage());
        return false;
    }
}
