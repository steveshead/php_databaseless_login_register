<?php
$page_title = "Login - Login System";
// Start session in header.php
require_once 'header.php';

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Validate input
    $error = "";

    if (empty($username) || empty($password)) {
        $error = "Username and password are required";
    } else {
        // Check if user exists
        $users_file = "users.json";

        if (file_exists($users_file)) {
            $users_json = file_get_contents($users_file);
            $users = json_decode($users_json, true);

            $user_found = false;

            foreach ($users as $user) {
                if ($user["username"] === $username) {
                    $user_found = true;

                    // Verify password
                    if (password_verify($password, $user["password"])) {
                        // Set required session variables
                        $_SESSION["username"] = $username;
                        $_SESSION["email"] = $user["email"];
                        $_SESSION["role"] = isset($user["role"]) ? $user["role"] : "user";

                        // Set optional session variables using array of field names
                        $optional_fields = ["first_name", "last_name", "site_settings"];
                        foreach ($optional_fields as $field) {
                            if (isset($user[$field])) {
                                $_SESSION[$field] = $user[$field];
                            }
                        }

                        // Set profile picture with default fallback
                        if (isset($user["profile_picture"])) {
                            $_SESSION["profile_picture"] = $user["profile_picture"];
                        } else {
                            // Default based on role
                            $default_pic = ($_SESSION["role"] === "admin") 
                                ? "uploads/profile_pictures/default_admin.png" 
                                : "uploads/profile_pictures/default_user.png";
                            $_SESSION["profile_picture"] = $default_pic;
                        }

                        // Redirect to dashboard page
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Invalid password";
                    }
                    break;
                }
            }

            if (!$user_found) {
                $error = "User not found";
            }
        } else {
            $error = "No users registered yet";
        }
    }
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="card-title text-center mb-4">Login</h1>
                        <?php if (isset($error) && !empty($error)): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if (isset($_GET['registered']) && $_GET['registered'] === 'true'): ?>
                            <div class="alert alert-success text-center">Registration successful! Please login.</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['logout']) && $_GET['logout'] === 'true'): ?>
                            <div class="alert alert-success text-center">You have been logged out successfully.</div>
                        <?php endif; ?>
                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                            <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
