<?php
$page_title = "Register - Login System";
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
    $email = trim($_POST["email"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate input
    $error = "";

    if (empty($username) || empty($email) || empty($first_name) || empty($last_name) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = "Password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password must contain at least one number";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Password must contain at least one special character";
    }

    // Check if username already exists
    if (empty($error)) {
        $users_file = "users.json";
        $users = [];

        if (file_exists($users_file)) {
            $users_json = file_get_contents($users_file);
            $users = json_decode($users_json, true);

            foreach ($users as $user) {
                if ($user["username"] === $username) {
                    $error = "Username already exists";
                    break;
                }
                if ($user["email"] === $email) {
                    $error = "Email already exists";
                    break;
                }
            }
        }

        // Register user if no errors
        if (empty($error)) {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Add user to array
            $users[] = [
                "username" => $username,
                "email" => $email,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "password" => $hashed_password,
                "site_settings" => [
                    "primary_color" => "#0d6efd",
                    "bg_color" => "#f8f9fa",
                    "card_bg_color" => "#ffffff",
                    "text_color" => "#212529",
                    "font_size" => "16px",
                    "font_family" => "Roboto",
                    "button_size" => "",
                    "button_type" => "primary"
                ]
            ];

            // Save to file
            file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));

            // Redirect to login page with success message
            header("Location: login.php?registered=true");
            exit();
        }
    }
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="card-title text-center mb-4">Register</h1>
                        <?php if (isset($error) && !empty($error)): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form action="register.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($first_name) ? htmlspecialchars($first_name) : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($last_name) ? htmlspecialchars($last_name) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.</div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                            <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
