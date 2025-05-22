<?php
$page_title = "Global Settings - Login System";

require_once 'forms.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_set_timeout(86400);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if user has admin role
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$readonly = !$is_admin;

// Redirect non-admin users to dashboard
if (!$is_admin) {
    header("Location: dashboard.php");
    exit();
}

// Initialize variables
$username = $_SESSION['username'];
$success_message = isset($_GET['success']) && $_GET['success'] === 'true' ? "Global settings updated successfully!" : '';
$error = '';

// Load global settings
$global_settings_file = "global_settings.json";
$global_settings = [];

if (file_exists($global_settings_file)) {
    $global_settings_json = file_get_contents($global_settings_file);
    $global_settings = json_decode($global_settings_json, true);
}

// Default global settings
$font_size_percentage = isset($global_settings['font_size_percentage']) ? $global_settings['font_size_percentage'] : '100%';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is admin before processing form
    if (!$is_admin) {
        $error = "You do not have permission to change global settings. Only administrators can modify settings.";
    } else {
        // Get form data
        $new_font_size_percentage = trim($_POST["font_size_percentage"]);

        // Validate input
        if (!preg_match('/^\d+%$/', $new_font_size_percentage)) {
            $error = "Invalid font size percentage format. Use percentage format (e.g., 100%).";
        }

        // Update global settings if no errors
        if (empty($error)) {
            // Create or update global settings
            $global_settings = [
                "font_size_percentage" => $new_font_size_percentage
            ];

            // Save to file
            if (file_put_contents($global_settings_file, json_encode($global_settings, JSON_PRETTY_PRINT))) {
                // Update local variables
                $font_size_percentage = $new_font_size_percentage;

                // Set success flag for display
                $success_message = "Global settings updated successfully!";
            } else {
                $error = "Failed to save global settings changes";
            }
        }
    }
}

// CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input data
    $error = '';
    if (!validate_font_size_percentage(trim($_POST["font_size_percentage"]))) {
        $error = 'Invalid font size percentage format. Use percentage format (e.g., 100%).';
    }

    // Update global settings if no errors
    if (empty($error)) {
        // ... (rest of the code remains the same)
    }
}

// Include header after form processing
require_once 'header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="card-title mb-4">Global Settings</h1>

                    <?php if (!$is_admin): ?>
                        <div class="alert alert-warning">You are viewing global settings in read-only mode. Only administrators can modify these settings.</div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>

                    <form action="global_settings.php" method="post">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="font_size_percentage" class="form-label">Font Size Percentage</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="font_size_percentage" name="font_size_percentage" value="<?php echo htmlspecialchars($font_size_percentage); ?>" title="Choose font size percentage" <?php echo $readonly ? 'readonly' : ''; ?>>
                                </div>
                                <div class="form-text">Adjust global font size by percentage for all users (e.g., 100% for default, 120% for larger, 80% for smaller)</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="dashboard.php" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" <?php echo $readonly ? 'disabled' : ''; ?>>Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="global_settings.php" method="post">
    <!-- ... -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <!-- ... -->
</form>


<?php require_once 'footer.php'; ?>
