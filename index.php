<?php
session_start();
$page_title = "Welcome to Login System";
$is_logged_in = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <!-- Bootstrap 5.3.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <?php if (isset($_SESSION['site_settings']) && isset($_SESSION['site_settings']['font_family'])): ?>
    <link href="https://fonts.googleapis.com/css2?family=<?php echo str_replace(' ', '+', $_SESSION['site_settings']['font_family']); ?>&display=swap" rel="stylesheet">
    <?php else: ?>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <?php endif; ?>
    <!-- Custom styles -->
    <link rel="stylesheet" href="style.css">
    <?php
    // Apply user's site settings if available
    if (isset($_SESSION['site_settings'])) {
        $scheme = $_SESSION['site_settings'];
        echo '<style>';
        echo ':root {';
        if (isset($scheme['primary_color'])) {
            echo '--bs-primary: ' . $scheme['primary_color'] . ' !important;';
            echo '--bs-primary-rgb: ' . implode(', ', sscanf($scheme['primary_color'], "#%02x%02x%02x")) . ' !important;';
        }
        echo '}';

        if (isset($scheme['bg_color'])) {
            echo 'body { background-color: ' . $scheme['bg_color'] . ' !important; }';
        }

        if (isset($scheme['card_bg_color'])) {
            echo '.card { background-color: ' . $scheme['card_bg_color'] . ' !important; }';
        }

        if (isset($scheme['text_color'])) {
            echo 'body { color: ' . $scheme['text_color'] . ' !important; }';
        }

        if (isset($scheme['font_size'])) {
            echo 'body { font-size: ' . $scheme['font_size'] . ' !important; }';
        }

        if (isset($scheme['font_family'])) {
            echo 'body { font-family: \'' . $scheme['font_family'] . '\', sans-serif !important; }';
        }

        // Update link colors if primary color is set
        if (isset($scheme['primary_color'])) {
            echo 'a { color: ' . $scheme['primary_color'] . '; }';
            // Darken for hover (simple approach)
            echo 'a:hover { color: ' . $scheme['primary_color'] . '; filter: brightness(0.8); }';
            echo '.btn-primary { background-color: ' . $scheme['primary_color'] . ' !important; border-color: ' . $scheme['primary_color'] . ' !important; }';
            echo '.btn-outline-primary { color: ' . $scheme['primary_color'] . ' !important; border-color: ' . $scheme['primary_color'] . ' !important; }';
            echo '.btn-outline-primary:hover { background-color: ' . $scheme['primary_color'] . ' !important; color: #fff !important; }';
            echo '.bg-primary { background-color: ' . $scheme['primary_color'] . ' !important; }';
        }

        echo '</style>';
    }
    ?>
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="index.php">Login System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if ($is_logged_in): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-grow-1 d-flex align-items-center py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-3">Welcome to Login System</h1>
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
                                    <li class="list-group-item bg-transparent">Secure user authentication</li>
                                    <li class="list-group-item bg-transparent">User profile management</li>
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
