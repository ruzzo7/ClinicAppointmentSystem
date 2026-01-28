<?php
/**
 * Helper Functions for the Application
 */

/**
 * Get the project root path (e.g. /clinic)
 * @return string
 */
function getProjectRoot() {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    // Explode by /public/ to find the root
    if (strpos($scriptName, '/public/') !== false) {
        $parts = explode('/public/', $scriptName);
        return $parts[0];
    }
    // Fallback usually not needed if structure is respected
    return dirname(dirname($scriptName));
}

/**
 * Generate full URL for a public page
 * @param string $path Path relative to public folder
 * @return string
 */
function url($path = '') {
    return getProjectRoot() . '/public/' . ltrim($path, '/');
}

/**
 * Generate full URL for an asset
 * @param string $path Path relative to assets folder
 * @return string
 */
function asset($path) {
    return getProjectRoot() . '/assets/' . ltrim($path, '/');
}

/**
 * Check if current page matches the given path
 * @param string $path Path to check
 * @return bool
 */
function isCurrentPage($path) {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $currentPath = rtrim($currentPath, '/');
    $path = trim($path, '/');
    
    if ($path === 'index.php') {
        // Strict match for home page
        return str_ends_with($currentPath, '/public/index.php') || 
               str_ends_with($currentPath, '/public');
    }
    
    // For other paths, check if the current path contains the component
    return strpos($currentPath, '/' . $path) !== false;
}

/**
 * Format date for display
 * @param string $date Date string
 * @return string
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Format time for display
 * @param string $time Time string
 * @return string
 */
function formatTime($time) {
    return date('g:i A', strtotime($time));
}

/**
 * Get all patients
 * @return array
 */
function getAllPatients() {
    return fetchAll("SELECT * FROM patients ORDER BY name ASC");
}

/**
 * Get patient by ID
 * @param int $id Patient ID
 * @return array|false
 */
function getPatientById($id) {
    return fetchOne("SELECT * FROM patients WHERE id = ?", [$id]);
}

/**
 * Create new patient
 * @param array $data Patient data
 * @return bool
 */
function createPatient($data) {
    $sql = "INSERT INTO patients (name, email, phone, address, date_of_birth, gender) 
            VALUES (?, ?, ?, ?, ?, ?)";
    try {
        executeQuery($sql, [
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['date_of_birth'],
            $data['gender']
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Create Patient Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update patient
 * @param int $id Patient ID
 * @param array $data Patient data
 * @return bool
 */
function updatePatient($id, $data) {
    $sql = "UPDATE patients 
            SET name = ?, email = ?, phone = ?, address = ?, date_of_birth = ?, gender = ?
            WHERE id = ?";
    try {
        executeQuery($sql, [
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['date_of_birth'],
            $data['gender'],
            $id
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Update Patient Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete patient
 * @param int $id Patient ID
 * @return bool
 */
function deletePatient($id) {
    try {
        // First delete all appointments for this patient
        executeQuery("DELETE FROM appointments WHERE patient_id = ?", [$id]);
        // Then delete the patient
        executeQuery("DELETE FROM patients WHERE id = ?", [$id]);
        return true;
    } catch (PDOException $e) {
        error_log("Delete Patient Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all doctors
 * @return array
 */
function getAllDoctors() {
    return fetchAll("SELECT * FROM doctors ORDER BY name ASC");
}

/**
 * Get doctor by ID
 * @param int $id Doctor ID
 * @return array|false
 */
function getDoctorById($id) {
    return fetchOne("SELECT * FROM doctors WHERE id = ?", [$id]);
}

/**
 * Create new doctor
 * @param array $data Doctor data
 * @return bool
 */
function createDoctor($data) {
    $sql = "INSERT INTO doctors (name, email, phone, specialization, qualification, available_days, start_time, end_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    try {
        executeQuery($sql, [
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['specialization'],
            $data['qualification'],
            $data['available_days'],
            $data['start_time'],
            $data['end_time']
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Create Doctor Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update doctor
 * @param int $id Doctor ID
 * @param array $data Doctor data
 * @return bool
 */
function updateDoctor($id, $data) {
    $sql = "UPDATE doctors 
            SET name = ?, email = ?, phone = ?, specialization = ?, qualification = ?, available_days = ?, start_time = ?, end_time = ?
            WHERE id = ?";
    try {
        executeQuery($sql, [
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['specialization'],
            $data['qualification'],
            $data['available_days'],
            $data['start_time'],
            $data['end_time'],
            $id
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Update Doctor Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete doctor
 * @param int $id Doctor ID
 * @return bool
 */
function deleteDoctor($id) {
    try {
        // First delete all appointments for this doctor
        executeQuery("DELETE FROM appointments WHERE doctor_id = ?", [$id]);
        // Then delete the doctor
        executeQuery("DELETE FROM doctors WHERE id = ?", [$id]);
        return true;
    } catch (PDOException $e) {
        error_log("Delete Doctor Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all appointments with patient and doctor details
 * @return array
 */
function getAllAppointments() {
    $sql = "SELECT a.*, p.name as patient_name, d.name as doctor_name, d.specialization
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";
    return fetchAll($sql);
}

/**
 * Get appointment by ID
 * @param int $id Appointment ID
 * @return array|false
 */
function getAppointmentById($id) {
    $sql = "SELECT a.*, p.name as patient_name, d.name as doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id
            WHERE a.id = ?";
    return fetchOne($sql, [$id]);
}

/**
 * Check if time slot is available
 * @param int $doctor_id Doctor ID
 * @param string $date Appointment date
 * @param string $time Appointment time
 * @param int|null $exclude_id Appointment ID to exclude (for updates)
 * @return bool
 */
function isTimeSlotAvailable($doctor_id, $date, $time, $exclude_id = null) {
    $sql = "SELECT COUNT(*) as count FROM appointments 
            WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'";
    $params = [$doctor_id, $date, $time];
    
    if ($exclude_id !== null) {
        $sql .= " AND id != ?";
        $params[] = $exclude_id;
    }
    
    $result = fetchOne($sql, $params);
    return $result['count'] == 0;
}

/**
 * Create new appointment
 * @param array $data Appointment data
 * @return bool
 */
function createAppointment($data) {
    // Check if time slot is available
    if (!isTimeSlotAvailable($data['doctor_id'], $data['appointment_date'], $data['appointment_time'])) {
        return false;
    }
    
    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    try {
        executeQuery($sql, [
            $data['patient_id'],
            $data['doctor_id'],
            $data['appointment_date'],
            $data['appointment_time'],
            $data['reason'],
            $data['status'] ?? 'scheduled'
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Create Appointment Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update appointment
 * @param int $id Appointment ID
 * @param array $data Appointment data
 * @return bool
 */
function updateAppointment($id, $data) {
    // Check if time slot is available (excluding current appointment)
    if (!isTimeSlotAvailable($data['doctor_id'], $data['appointment_date'], $data['appointment_time'], $id)) {
        return false;
    }
    
    $sql = "UPDATE appointments 
            SET patient_id = ?, doctor_id = ?, appointment_date = ?, appointment_time = ?, reason = ?, status = ?
            WHERE id = ?";
    try {
        executeQuery($sql, [
            $data['patient_id'],
            $data['doctor_id'],
            $data['appointment_date'],
            $data['appointment_time'],
            $data['reason'],
            $data['status'],
            $id
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Update Appointment Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete appointment
 * @param int $id Appointment ID
 * @return bool
 */
function deleteAppointment($id) {
    try {
        executeQuery("DELETE FROM appointments WHERE id = ?", [$id]);
        return true;
    } catch (PDOException $e) {
        error_log("Delete Appointment Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Search appointments by criteria
 * @param array $criteria Search criteria
 * @return array
 */
function searchAppointments($criteria) {
    $sql = "SELECT a.*, p.name as patient_name, d.name as doctor_name, d.specialization
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($criteria['date'])) {
        $sql .= " AND a.appointment_date = ?";
        $params[] = $criteria['date'];
    }
    
    if (!empty($criteria['doctor_id'])) {
        $sql .= " AND a.doctor_id = ?";
        $params[] = $criteria['doctor_id'];
    }
    
    if (!empty($criteria['patient_id'])) {
        $sql .= " AND a.patient_id = ?";
        $params[] = $criteria['patient_id'];
    }
    
    if (!empty($criteria['status'])) {
        $sql .= " AND a.status = ?";
        $params[] = $criteria['status'];
    }
    
    $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
    
    return fetchAll($sql, $params);
}

/**
 * Get available time slots for a doctor on a specific date
 * @param int $doctor_id Doctor ID
 * @param string $date Date
 * @return array
 */
function getAvailableTimeSlots($doctor_id, $date) {
    // Get doctor data
    $doctor = getDoctorById($doctor_id);
    if (!$doctor) {
        return [];
    }
    
    // Validate day of week matches available_days
    $dayOfWeek = date('l', strtotime($date)); // e.g., "Monday"
    $availableDays = explode(',', $doctor['available_days']);
    $availableDays = array_map('trim', $availableDays); // Remove whitespace
    
    if (!in_array($dayOfWeek, $availableDays)) {
        // Doctor not available on this day
        return [];
    }
    
    // Use doctor's actual start/end time
    $start = strtotime($doctor['start_time']);
    $end = strtotime($doctor['end_time']);
    $interval = 30 * 60; // 30 minutes
    
    // Generate slots based on doctor's working hours
    $slots = [];
    for ($time = $start; $time < $end; $time += $interval) {
        $timeStr = date('H:i:s', $time);
        $slots[] = [
            'time' => $timeStr,
            'formatted' => formatTime($timeStr),
            'available' => isTimeSlotAvailable($doctor_id, $date, $timeStr)
        ];
    }
    
    return $slots;
}

/**
 * Get appointments by patient ID
 * @param int $patient_id Patient ID
 * @return array
 */
function getAppointmentsByPatientId($patient_id) {
    $sql = "SELECT a.*, d.name as doctor_name, d.specialization 
            FROM appointments a 
            JOIN doctors d ON a.doctor_id = d.id 
            WHERE a.patient_id = ? 
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";
    return fetchAll($sql, [$patient_id]);
}

/**
 * Get appointments by doctor ID
 * @param int $doctor_id Doctor ID
 * @return array
 */
function getAppointmentsByDoctorId($doctor_id) {
    $sql = "SELECT a.*, p.name as patient_name 
            FROM appointments a 
            JOIN patients p ON a.patient_id = p.id 
            WHERE a.doctor_id = ? 
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";
    return fetchAll($sql, [$doctor_id]);
}
