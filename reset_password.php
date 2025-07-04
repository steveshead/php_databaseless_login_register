<?php
$page_title = "Reset Password - Login System";
// Start session in header.php
require_once 'header.php';
require_once 'password_functions.php';

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Initialize variables
$token = isset($_GET['token']) ? urldecode($_GET['token']) : '';
$success = $error = "";
$token_valid = false;
$user_key = -1;
$username = "";

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = md5(uniqid(mt_rand(), true));
}

// Validate reset token
if (!empty($token)) {
    $users_file = "users.json";

    if (file_exists($users_file)) {
        $users_json = file_get_contents($users_file);
        $users = json_decode($users_json, true);

        foreach ($users as $key => $user) {
            if (isset($user["reset_token"]) && $user["reset_token"] === $token) {
                // Check if token has expired
                if (isset($user["reset_expires"]) && $user["reset_expires"] > time()) {
                    $token_valid = true;
                    $user_key = $key;
                    $username = $user["username"];
                } else {
                    $error = "Password reset link has expired. Please request a new one.";
                }
                break;
            }
        }

        if (!$token_valid && empty($error)) {
            $error = "Invalid password reset link. Please request a new one.";
        }
    } else {
        $error = "User database not found. Please contact the administrator.";
    }
} else {
    $error = "Invalid request. No reset token provided.";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && $token_valid) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Security verification failed. Please try again.";
    } else {
        // Get form data
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Validate input using the shared function
        $validation_result = validate_password($password, $confirm_password);

        if (!$validation_result['valid']) {
            $error = $validation_result['error'];
        } else {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update user's password
            $users[$user_key]["password"] = $hashed_password;

            // Remove reset token and expiration
            unset($users[$user_key]["reset_token"]);
            unset($users[$user_key]["reset_expires"]);

            // Save updated users data
            file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));

            // Show success message
            $success = "Your password has been reset successfully. You can now login with your new password.";
            $token_valid = false; // Hide the form after successful reset
        }
    }
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="card-title text-center mb-4">Reset Password</h1>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success text-center"><?php echo $success; ?></div>
                            <div class="text-center mt-3">
                                <a href="login.php" class="btn btn-primary">Go to Login</a>
                            </div>
                        <?php elseif (!empty($error)): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                            <?php if (strpos($error, "expired") !== false || strpos($error, "Invalid") !== false): ?>
                                <div class="text-center mt-3">
                                    <a href="forgot_password.php" class="btn btn-primary">Request New Reset Link</a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($token_valid): ?>
                            <p class="text-center mb-4">Please enter your new password below.</p>
                            <form action="reset_password.php?token=<?php echo htmlspecialchars(urlencode($token)); ?>" method="post">
                                <!-- CSRF Protection -->
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="form-text">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <div class="d-grid gap-2 mb-3">
                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                </div>
                            </form>
                        <?php elseif (empty($success) && empty($error)): ?>
                            <div class="alert alert-danger text-center">Invalid request. Please use the reset link sent to your email.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
