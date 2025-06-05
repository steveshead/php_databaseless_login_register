<?php
require_once 'header.php';
$page_title = "Welcome to Login System";
$is_logged_in = isset($_SESSION['username']);

// Load global site settings
$global_settings_file = "global_settings.json";
$scheme = [];

if (file_exists($global_settings_file)) {
    $scheme = json_decode(file_get_contents($global_settings_file), true);
    if (isset($scheme['font_family'])) {
        $font_family = $scheme['font_family'];
    }
}
?>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-3">Welcome to the Login and Registration System</h1>
                    <p class="lead mb-4">A secure and user-friendly authentication system with profile management capabilities.</p>
                    <?php if ($is_logged_in): ?>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="dashboard.php" class="btn btn-primary btn-lg px-4 me-md-2">Go to Dashboard</a>
                            <a href="edit_profile.php" class="btn btn-outline-primary btn-lg px-4">Edit Profile</a>
                        </div>
                    <?php else: ?>
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="login.php" class="btn btn-primary btn-lg px-4 me-md-2">Login</a>
                            <a href="register.php" class="btn btn-outline-primary btn-lg px-4">Register</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <?php if ($is_logged_in): ?>
                                <h2 class="card-title mb-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                                <p class="card-text mb-3">You are currently logged in. You can access your dashboard to view and manage your account.</p>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item bg-transparent"><a href="dashboard.php" class="text-decoration-none">View Dashboard</a></li>
                                    <li class="list-group-item bg-transparent"><a href="edit_profile.php" class="text-decoration-none">Edit Profile</a></li>
                                    <li class="list-group-item bg-transparent"><a href="logout.php" class="text-decoration-none text-danger">Logout</a></li>
                                </ul>
                            <?php else: ?>
                                <h2 class="card-title mb-3">Features</h2>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item bg-transparent">Admin and user roles</li>
                                    <li class="list-group-item bg-transparent">Secure user authentication</li>
                                    <li class="list-group-item bg-transparent">User profile management</li>
                                    <li class="list-group-item bg-transparent">Site settings for site design</li>
                                    <li class="list-group-item bg-transparent">Profile picture upload</li>
                                    <li class="list-group-item bg-transparent">Email address management</li>
                                    <li class="list-group-item bg-transparent">Responsive design with Bootstrap 5.3.6</li>
                                </ul>
                                <p class="card-text">Already have an account? <a href="login.php">Login now</a> to access your dashboard.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Login System</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Built with Bootstrap 5.3.6</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.6 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
