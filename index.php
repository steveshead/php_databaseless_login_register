<?php
session_start();
$page_title = "Welcome to Login System";
$is_logged_in = isset($_SESSION['username']);

// Load global site settings
$global_settings_file = "global_settings.json";
$scheme = [];
$font_family = 'Roboto'; // Default font

if (file_exists($global_settings_file)) {
    $scheme = json_decode(file_get_contents($global_settings_file), true);
    if (isset($scheme['font_family'])) {
        $font_family = $scheme['font_family'];
    }
}
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
    <link href="https://fonts.googleapis.com/css2?family=<?php echo str_replace(' ', '+', $font_family); ?>&display=swap" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="style.css">
    <?php
    echo '<style>';
    echo ':root {';
        if (isset($scheme['primary_color'])) {
            echo '--bs-primary: ' . $scheme['primary_color'] . ' !important;';
            echo '--bs-primary-rgb: ' . implode(', ', sscanf($scheme['primary_color'], "#%02x%02x%02x")) . ' !important;';
        }

        // Apply bootstrap color overrides if enabled
        if (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors']) {
            if (isset($scheme['bootstrap_primary_color'])) {
                echo '--bs-primary: ' . $scheme['bootstrap_primary_color'] . ' !important;';
                echo '--bs-primary-rgb: ' . implode(', ', sscanf($scheme['bootstrap_primary_color'], "#%02x%02x%02x")) . ' !important;';
            }
            if (isset($scheme['bootstrap_secondary_color'])) {
                echo '--bs-secondary: ' . $scheme['bootstrap_secondary_color'] . ' !important;';
                echo '--bs-secondary-rgb: ' . implode(', ', sscanf($scheme['bootstrap_secondary_color'], "#%02x%02x%02x")) . ' !important;';
            }
            if (isset($scheme['bootstrap_success_color'])) {
                echo '--bs-success: ' . $scheme['bootstrap_success_color'] . ' !important;';
                echo '--bs-success-rgb: ' . implode(', ', sscanf($scheme['bootstrap_success_color'], "#%02x%02x%02x")) . ' !important;';
            }
            if (isset($scheme['bootstrap_danger_color'])) {
                echo '--bs-danger: ' . $scheme['bootstrap_danger_color'] . ' !important;';
                echo '--bs-danger-rgb: ' . implode(', ', sscanf($scheme['bootstrap_danger_color'], "#%02x%02x%02x")) . ' !important;';
            }
            if (isset($scheme['bootstrap_warning_color'])) {
                echo '--bs-warning: ' . $scheme['bootstrap_warning_color'] . ' !important;';
                echo '--bs-warning-rgb: ' . implode(', ', sscanf($scheme['bootstrap_warning_color'], "#%02x%02x%02x")) . ' !important;';
            }
            if (isset($scheme['bootstrap_info_color'])) {
                echo '--bs-info: ' . $scheme['bootstrap_info_color'] . ' !important;';
                echo '--bs-info-rgb: ' . implode(', ', sscanf($scheme['bootstrap_info_color'], "#%02x%02x%02x")) . ' !important;';
            }
            if (isset($scheme['bootstrap_light_color'])) {
                echo '--bs-light: ' . $scheme['bootstrap_light_color'] . ' !important;';
                echo '--bs-light-rgb: ' . implode(', ', sscanf($scheme['bootstrap_light_color'], "#%02x%02x%02x")) . ' !important;';
            }
            if (isset($scheme['bootstrap_dark_color'])) {
                echo '--bs-dark: ' . $scheme['bootstrap_dark_color'] . ' !important;';
                echo '--bs-dark-rgb: ' . implode(', ', sscanf($scheme['bootstrap_dark_color'], "#%02x%02x%02x")) . ' !important;';
            }
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

        // Apply font size percentage if set
        if (isset($scheme['font_size_percentage'])) {
            echo 'html { font-size: ' . $scheme['font_size_percentage'] . ' !important; }';
        }

        // Convert px font size to rem for better scaling with font size percentage
        if (isset($scheme['font_size'])) {
            // Extract the numeric value from the font size (e.g., "16px" -> 16)
            $font_size_px = intval($scheme['font_size']);
            // Convert to rem (assuming 16px base font size)
            $font_size_rem = ($font_size_px / 16) . 'rem';
            echo 'body { font-size: ' . $font_size_rem . ' !important; }';
        }

        if (isset($scheme['font_family'])) {
            echo 'body { font-family: \'' . $scheme['font_family'] . '\', sans-serif !important; }';
        }

        // Update link colors if primary color is set
        if (isset($scheme['primary_color'])) {
            // Use bootstrap_primary_color if override_bootstrap_colors is enabled and bootstrap_primary_color is set
            $primary_color_to_use = (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors'] && isset($scheme['bootstrap_primary_color'])) 
                ? $scheme['bootstrap_primary_color'] 
                : $scheme['primary_color'];

            echo 'a { color: ' . $primary_color_to_use . '; }';
            // Darken for hover (simple approach)
            echo 'a:hover { color: ' . $primary_color_to_use . '; filter: brightness(0.8); }';
            echo '.btn-primary { background-color: ' . $primary_color_to_use . ' !important; border-color: ' . $primary_color_to_use . ' !important; }';
            echo '.btn-outline-primary { color: ' . $primary_color_to_use . ' !important; border-color: ' . $primary_color_to_use . ' !important; }';
            echo '.btn-outline-primary:hover { background-color: ' . $primary_color_to_use . ' !important; color: #fff !important; }';
            echo '.bg-primary { background-color: ' . $primary_color_to_use . ' !important; }';

            // Apply bootstrap color overrides to standard button classes if enabled
            if (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors']) {
                if (isset($scheme['bootstrap_secondary_color'])) {
                    echo '.btn-secondary { background-color: ' . $scheme['bootstrap_secondary_color'] . ' !important; border-color: ' . $scheme['bootstrap_secondary_color'] . ' !important; }';
                    echo '.btn-outline-secondary { color: ' . $scheme['bootstrap_secondary_color'] . ' !important; border-color: ' . $scheme['bootstrap_secondary_color'] . ' !important; }';
                    echo '.btn-outline-secondary:hover { background-color: ' . $scheme['bootstrap_secondary_color'] . ' !important; color: #fff !important; }';
                    echo '.bg-secondary { background-color: ' . $scheme['bootstrap_secondary_color'] . ' !important; }';
                }
                if (isset($scheme['bootstrap_success_color'])) {
                    echo '.btn-success { background-color: ' . $scheme['bootstrap_success_color'] . ' !important; border-color: ' . $scheme['bootstrap_success_color'] . ' !important; }';
                    echo '.btn-outline-success { color: ' . $scheme['bootstrap_success_color'] . ' !important; border-color: ' . $scheme['bootstrap_success_color'] . ' !important; }';
                    echo '.btn-outline-success:hover { background-color: ' . $scheme['bootstrap_success_color'] . ' !important; color: #fff !important; }';
                    echo '.bg-success { background-color: ' . $scheme['bootstrap_success_color'] . ' !important; }';
                }
                if (isset($scheme['bootstrap_danger_color'])) {
                    echo '.btn-danger { background-color: ' . $scheme['bootstrap_danger_color'] . ' !important; border-color: ' . $scheme['bootstrap_danger_color'] . ' !important; }';
                    echo '.btn-outline-danger { color: ' . $scheme['bootstrap_danger_color'] . ' !important; border-color: ' . $scheme['bootstrap_danger_color'] . ' !important; }';
                    echo '.btn-outline-danger:hover { background-color: ' . $scheme['bootstrap_danger_color'] . ' !important; color: #fff !important; }';
                    echo '.bg-danger { background-color: ' . $scheme['bootstrap_danger_color'] . ' !important; }';
                }
                if (isset($scheme['bootstrap_warning_color'])) {
                    echo '.btn-warning { background-color: ' . $scheme['bootstrap_warning_color'] . ' !important; border-color: ' . $scheme['bootstrap_warning_color'] . ' !important; color: #212529 !important; }';
                    echo '.btn-outline-warning { color: ' . $scheme['bootstrap_warning_color'] . ' !important; border-color: ' . $scheme['bootstrap_warning_color'] . ' !important; }';
                    echo '.btn-outline-warning:hover { background-color: ' . $scheme['bootstrap_warning_color'] . ' !important; color: #212529 !important; }';
                    echo '.bg-warning { background-color: ' . $scheme['bootstrap_warning_color'] . ' !important; }';
                }
                if (isset($scheme['bootstrap_info_color'])) {
                    echo '.btn-info { background-color: ' . $scheme['bootstrap_info_color'] . ' !important; border-color: ' . $scheme['bootstrap_info_color'] . ' !important; color: #212529 !important; }';
                    echo '.btn-outline-info { color: ' . $scheme['bootstrap_info_color'] . ' !important; border-color: ' . $scheme['bootstrap_info_color'] . ' !important; }';
                    echo '.btn-outline-info:hover { background-color: ' . $scheme['bootstrap_info_color'] . ' !important; color: #212529 !important; }';
                    echo '.bg-info { background-color: ' . $scheme['bootstrap_info_color'] . ' !important; }';
                }
                if (isset($scheme['bootstrap_light_color'])) {
                    echo '.btn-light { background-color: ' . $scheme['bootstrap_light_color'] . ' !important; border-color: ' . $scheme['bootstrap_light_color'] . ' !important; color: #212529 !important; }';
                    echo '.btn-outline-light { color: ' . $scheme['bootstrap_light_color'] . ' !important; border-color: ' . $scheme['bootstrap_light_color'] . ' !important; }';
                    echo '.btn-outline-light:hover { background-color: ' . $scheme['bootstrap_light_color'] . ' !important; color: #212529 !important; }';
                    echo '.bg-light { background-color: ' . $scheme['bootstrap_light_color'] . ' !important; }';
                }
                if (isset($scheme['bootstrap_dark_color'])) {
                    echo '.btn-dark { background-color: ' . $scheme['bootstrap_dark_color'] . ' !important; border-color: ' . $scheme['bootstrap_dark_color'] . ' !important; }';
                    echo '.btn-outline-dark { color: ' . $scheme['bootstrap_dark_color'] . ' !important; border-color: ' . $scheme['bootstrap_dark_color'] . ' !important; }';
                    echo '.btn-outline-dark:hover { background-color: ' . $scheme['bootstrap_dark_color'] . ' !important; color: #fff !important; }';
                    echo '.bg-dark { background-color: ' . $scheme['bootstrap_dark_color'] . ' !important; }';
                }
            }
        }

        // Apply button size if set
        if (isset($scheme['button_size']) && !empty($scheme['button_size'])) {
            $size_class = 'btn-' . $scheme['button_size'];
            echo '.btn { ';
            if ($scheme['button_size'] === 'sm') {
                echo 'padding: 0.25rem 0.5rem !important; font-size: 0.875rem !important; border-radius: 0.25rem !important;';
            } elseif ($scheme['button_size'] === 'lg') {
                echo 'padding: 0.5rem 1rem !important; font-size: 1.25rem !important; border-radius: 0.5rem !important;';
            }
            echo ' }';
        }

        // Apply button type if set
        if (isset($scheme['button_type']) && $scheme['button_type'] !== 'primary') {
            // First, reset all buttons to have no specific styling
            echo '.btn:not([class*="btn-"]) { ';
            echo 'background-color: transparent !important; border: 1px solid transparent !important; color: #212529 !important;';
            echo ' }';

            // Then apply the selected button type
            $type = $scheme['button_type'];

            if (strpos($type, 'outline-') === 0) {
                // Handle outline button types
                $color_type = str_replace('outline-', '', $type);
                echo '.btn:not([class*="btn-outline-"]):not([class*="btn-"]) { ';
                echo 'background-color: transparent !important; ';

                switch ($color_type) {
                    case 'primary':
                        $primary_color_to_use = (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors'] && isset($scheme['bootstrap_primary_color'])) 
                            ? $scheme['bootstrap_primary_color'] 
                            : $scheme['primary_color'];
                        echo 'color: ' . $primary_color_to_use . ' !important; border-color: ' . $primary_color_to_use . ' !important;';
                        break;
                    case 'secondary':
                        echo 'color: #6c757d !important; border-color: #6c757d !important;';
                        break;
                    case 'success':
                        echo 'color: #198754 !important; border-color: #198754 !important;';
                        break;
                    case 'danger':
                        echo 'color: #dc3545 !important; border-color: #dc3545 !important;';
                        break;
                    case 'warning':
                        echo 'color: #ffc107 !important; border-color: #ffc107 !important;';
                        break;
                    case 'info':
                        echo 'color: #0dcaf0 !important; border-color: #0dcaf0 !important;';
                        break;
                    case 'light':
                        echo 'color: #f8f9fa !important; border-color: #f8f9fa !important;';
                        break;
                    case 'dark':
                        echo 'color: #212529 !important; border-color: #212529 !important;';
                        break;
                }

                echo ' }';

                // Add hover effect for outline buttons
                echo '.btn:not([class*="btn-outline-"]):not([class*="btn-"]):hover { ';
                switch ($color_type) {
                    case 'primary':
                        $primary_color_to_use = (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors'] && isset($scheme['bootstrap_primary_color'])) 
                            ? $scheme['bootstrap_primary_color'] 
                            : $scheme['primary_color'];
                        echo 'background-color: ' . $primary_color_to_use . ' !important; color: #fff !important;';
                        break;
                    case 'secondary':
                        echo 'background-color: #6c757d !important; color: #fff !important;';
                        break;
                    case 'success':
                        echo 'background-color: #198754 !important; color: #fff !important;';
                        break;
                    case 'danger':
                        echo 'background-color: #dc3545 !important; color: #fff !important;';
                        break;
                    case 'warning':
                        echo 'background-color: #ffc107 !important; color: #212529 !important;';
                        break;
                    case 'info':
                        echo 'background-color: #0dcaf0 !important; color: #212529 !important;';
                        break;
                    case 'light':
                        echo 'background-color: #f8f9fa !important; color: #212529 !important;';
                        break;
                    case 'dark':
                        echo 'background-color: #212529 !important; color: #fff !important;';
                        break;
                }
                echo ' }';
            } else {
                // Handle solid button types
                echo '.btn:not([class*="btn-"]) { ';

                switch ($type) {
                    case 'secondary':
                        echo 'background-color: #6c757d !important; border-color: #6c757d !important; color: #fff !important;';
                        break;
                    case 'success':
                        echo 'background-color: #198754 !important; border-color: #198754 !important; color: #fff !important;';
                        break;
                    case 'danger':
                        echo 'background-color: #dc3545 !important; border-color: #dc3545 !important; color: #fff !important;';
                        break;
                    case 'warning':
                        echo 'background-color: #ffc107 !important; border-color: #ffc107 !important; color: #212529 !important;';
                        break;
                    case 'info':
                        echo 'background-color: #0dcaf0 !important; border-color: #0dcaf0 !important; color: #212529 !important;';
                        break;
                    case 'light':
                        echo 'background-color: #f8f9fa !important; border-color: #f8f9fa !important; color: #212529 !important;';
                        break;
                    case 'dark':
                        echo 'background-color: #212529 !important; border-color: #212529 !important; color: #fff !important;';
                        break;
                    case 'link':
                        $primary_color_to_use = (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors'] && isset($scheme['bootstrap_primary_color'])) 
                            ? $scheme['bootstrap_primary_color'] 
                            : $scheme['primary_color'];
                        echo 'background-color: transparent !important; border-color: transparent !important; color: ' . $primary_color_to_use . ' !important; text-decoration: underline !important;';
                        break;
                }

                echo ' }';
            }
        }

        echo '</style>';
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
