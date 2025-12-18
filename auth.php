<?php
require_once 'database.php';
require_once 'functions.php';

class Auth
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Register new user - NO HASH
    public function register($data)
    {
        $required = ['first_name', 'last_name', 'email', 'password', 'role'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'error' => "$field is required"];
            }
        }

        if (!Helper::isValidEmail($data['email'])) {
            return ['success' => false, 'error' => 'Invalid email address'];
        }

        $existing = Database::fetch("SELECT id FROM users WHERE email = ?", [$data['email']]);
        if ($existing) {
            return ['success' => false, 'error' => 'Email already registered'];
        }

        // ❌ NO HASH — Save plain password
        $password = $data['password'];

        // Combine first_name and last_name into full_name
        $full_name = Helper::sanitize($data['first_name']) . ' ' . Helper::sanitize($data['last_name']);

        $userData = [
            'full_name' => $full_name,
            'email' => Helper::sanitize($data['email']),
            'password' => $password, // plain text
            'user_role' => Helper::sanitize($data['role']),
            'phone' => Helper::sanitize($data['phone'] ?? ''),
            'department' => Helper::sanitize($data['department'] ?? ''),
            'status' => 'active'
        ];

        try {
            $user_id = Database::insert('users', $userData);
            return [
                'success' => true,
                'user_id' => $user_id,
                'message' => 'Registration successful.'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Registration failed: ' . $e->getMessage()];
        }
    }

    // Login user - NO HASH
    public function login($email, $password)
    {
        $user = Database::fetch(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );

        if (!$user) {
            return ['success' => false, 'error' => 'Invalid credentials'];
        }

        // ❌ NO HASH — plain text comparison
        if ($password !== $user['password']) {
            return ['success' => false, 'error' => 'Invalid credentials'];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['login_time'] = time();
        $_SESSION['logged_in'] = true;

        return [
            'success' => true,
            'role' => $user['role'],
            'user' => [
                'id' => $user['id'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ];
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
        session_start();

        return ['success' => true];
    }
}

if (!isset($auth)) {
    $auth = new Auth();
}
?>