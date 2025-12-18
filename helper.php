<?php
class Helper
{

    public static function sanitize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        }

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function generatePasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
?>
// In includes/functions.php or Helper class:
class Helper {
    // ... existing code ...
    
    /**
     * Check if email already exists in database
     */
    public static function emailExists($email) {
        global $db;
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Validate password strength
     */
    public static function isStrongPassword($password) {
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special char
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
    }
    
    /**
     * Check if email is from university domain
     */
    public static function isValidUniversityEmail($email) {
        $allowed_domains = ['hu.edu', 'harvard.edu']; // Add your university domains
        $domain = substr(strrchr($email, "@"), 1);
        return in_array($domain, $allowed_domains);
    }
    
    /**
     * Enhanced sanitization
     */
    public static function sanitize($data, $type = 'string') {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        
        switch ($type) {
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
            case 'phone':
                return preg_replace('/[^0-9+]/', '', $data);
            case 'int':
                return (int)$data;
            case 'float':
                return (float)$data;
            default:
                return $data;
        }
    }
    
    /**
     * Send verification email
     */
    public static function sendVerificationEmail($to, $name, $link) {
        $subject = "Verify Your Account - HU CMS";
        $message = "
            <html>
            <body>
                <h2>Welcome to HU CMS, $name!</h2>
                <p>Please click the link below to verify your account:</p>
                <p><a href='$link'>Verify Account</a></p>
                <p>Or copy this link: $link</p>
                <p>This link will expire in 24 hours.</p>
            </body>
            </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@hu.edu" . "\r\n";
        
        return mail($to, $subject, $message, $headers);
    }
}