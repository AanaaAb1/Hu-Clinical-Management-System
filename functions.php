<?php
require_once 'database.php';

class Helper
{
    private $db;

    public function __construct()
    {
        // FIXED: Use Database::connect() instead of getInstance()
        $this->db = Database::connect(); // Changed from Database::getInstance()
    }

    // Sanitize input
    public static function sanitize($input)
    {
        if (is_array($input)) {
            return array_map('self::sanitize', $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    // Check if user is logged in
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // Check user role
    public static function hasRole($role)
    {
        if (!isset($_SESSION['user_role'])) {
            return false;
        }
        return $_SESSION['user_role'] === $role;
    }

    // Redirect with message
    public static function redirect($url, $message = '', $type = 'success')
    {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        header("Location: $url");
        exit();
    }

    // Get flash message
    public static function getFlashMessage()
    {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            $type = $_SESSION['flash_type'] ?? 'info';
            unset($_SESSION['flash_message'], $_SESSION['flash_type']);
            return ['message' => $message, 'type' => $type];
        }
        return null;
    }

    // Generate CSRF token
    public static function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Validate CSRF token
    public static function validateCSRFToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Validate email
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validate phone number
    public static function isValidPhone($phone)
    {
        return preg_match('/^(\+251|0)[0-9]{9}$/', $phone);
    }

    // Format date
    public static function formatDate($date, $format = 'F j, Y')
    {
        if (empty($date))
            return '';
        return date($format, strtotime($date));
    }

    // Get age from date of birth
    public static function calculateAge($dob)
    {
        if (empty($dob))
            return 0;
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        return $today->diff($birthDate)->y;
    }

    // Generate random password
    public static function generateRandomPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }

    // Check session timeout (define constant if not defined)
    public static function checkSessionTimeout()
    {
        // Define constant if not already defined
        if (!defined('SESSION_TIMEOUT')) {
            define('SESSION_TIMEOUT', 3600); // 1 hour default
        }
        
        if (isset($_SESSION['login_time'])) {
            if (time() - $_SESSION['login_time'] > SESSION_TIMEOUT) {
                session_destroy();
                self::redirect('login.php', 'Session expired. Please login again.', 'warning');
            }
            $_SESSION['login_time'] = time(); // Update session time
        }
    }
    // Get user info - FIXED: This is NOT static, it uses $this->db
    public function getUser($id)
    {
        // You need to check what methods your Database class has
        // Assuming your Database class has a query/fetch method
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Log activity - FIXED: This is NOT static
    public function logActivity($activity, $module = null)
    {
        $user_id = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // You need to check what methods your Database class has
        $stmt = $this->db->prepare("INSERT INTO activity_logs (user_id, activity, module, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $activity, $module, $ip, $user_agent]);
    }
}

// Don't auto-initialize - let calling code decide when to create instance
// Remove this line: $helper = new Helper();
?>