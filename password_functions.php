<?php
/**
 * Password utility functions for the Login System
 * Contains shared functionality for password validation and management
 */

/**
 * Validates a password against security requirements
 * 
 * @param string $password The password to validate
 * @param string $confirm_password The confirmation password (if provided)
 * @param array $options Additional options for validation
 *   - check_match: Whether to check if password matches confirm_password (default: true)
 *   - current_password_hash: Current password hash to check against (to prevent reusing the same password)
 * @return array Result with 'valid' (boolean) and 'error' (string) keys
 */
function validate_password($password, $confirm_password = '', $options = []) {
    // Default options
    $default_options = [
        'check_match' => !empty($confirm_password),
        'current_password_hash' => null
    ];
    
    $options = array_merge($default_options, $options);
    
    $result = [
        'valid' => true,
        'error' => ''
    ];
    
    // Check if password is empty
    if (empty($password)) {
        $result['valid'] = false;
        $result['error'] = "Password is required";
        return $result;
    }
    
    // Check if passwords match (if check_match is true)
    if ($options['check_match'] && $password !== $confirm_password) {
        $result['valid'] = false;
        $result['error'] = "Passwords do not match";
        return $result;
    }
    
    // Check password length
    if (strlen($password) < 8) {
        $result['valid'] = false;
        $result['error'] = "Password must be at least 8 characters long";
        return $result;
    }
    
    // Check for uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        $result['valid'] = false;
        $result['error'] = "Password must contain at least one uppercase letter";
        return $result;
    }
    
    // Check for lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        $result['valid'] = false;
        $result['error'] = "Password must contain at least one lowercase letter";
        return $result;
    }
    
    // Check for number
    if (!preg_match('/[0-9]/', $password)) {
        $result['valid'] = false;
        $result['error'] = "Password must contain at least one number";
        return $result;
    }
    
    // Check for special character
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $result['valid'] = false;
        $result['error'] = "Password must contain at least one special character";
        return $result;
    }
    
    // Check if new password is the same as current password (if provided)
    if ($options['current_password_hash'] && password_verify($password, $options['current_password_hash'])) {
        $result['valid'] = false;
        $result['error'] = "New password cannot be the same as your current password";
        return $result;
    }
    
    return $result;
}