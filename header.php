<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);

// Helper functions for styling
function getPrimaryColor($scheme) {
    return (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors'] && isset($scheme['bootstrap_primary_color'])) 
        ? $scheme['bootstrap_primary_color'] 
        : $scheme['primary_color'];
}

function getButtonColorCSS($type, $scheme, $isOutline = false) {
    $css = '';

    if ($isOutline) {
        $css .= 'background-color: transparent !important; ';

        switch ($type) {
            case 'primary':
                $primary_color = getPrimaryColor($scheme);
                $css .= 'color: ' . $primary_color . ' !important; border-color: ' . $primary_color . ' !important;';
                break;
            case 'secondary':
                $css .= 'color: #6c757d !important; border-color: #6c757d !important;';
                break;
            case 'success':
                $css .= 'color: #198754 !important; border-color: #198754 !important;';
                break;
            case 'danger':
                $css .= 'color: #dc3545 !important; border-color: #dc3545 !important;';
                break;
            case 'warning':
                $css .= 'color: #ffc107 !important; border-color: #ffc107 !important;';
                break;
            case 'info':
                $css .= 'color: #0dcaf0 !important; border-color: #0dcaf0 !important;';
                break;
            case 'light':
                $css .= 'color: #f8f9fa !important; border-color: #f8f9fa !important;';
                break;
            case 'dark':
                $css .= 'color: #212529 !important; border-color: #212529 !important;';
                break;
        }
    } else {
        switch ($type) {
            case 'primary':
                $primary_color = getPrimaryColor($scheme);
                $css .= 'background-color: ' . $primary_color . ' !important; border-color: ' . $primary_color . ' !important; color: #fff !important;';
                break;
            case 'secondary':
                $css .= 'background-color: #6c757d !important; border-color: #6c757d !important; color: #fff !important;';
                break;
            case 'success':
                $css .= 'background-color: #198754 !important; border-color: #198754 !important; color: #fff !important;';
                break;
            case 'danger':
                $css .= 'background-color: #dc3545 !important; border-color: #dc3545 !important; color: #fff !important;';
                break;
            case 'warning':
                $css .= 'background-color: #ffc107 !important; border-color: #ffc107 !important; color: #212529 !important;';
                break;
            case 'info':
                $css .= 'background-color: #0dcaf0 !important; border-color: #0dcaf0 !important; color: #212529 !important;';
                break;
            case 'light':
                $css .= 'background-color: #f8f9fa !important; border-color: #f8f9fa !important; color: #212529 !important;';
                break;
            case 'dark':
                $css .= 'background-color: #212529 !important; border-color: #212529 !important; color: #fff !important;';
                break;
            case 'link':
                $primary_color = getPrimaryColor($scheme);
                $css .= 'background-color: transparent !important; border-color: transparent !important; color: ' . $primary_color . ' !important; text-decoration: underline !important;';
                break;
        }
    }

    return $css;
}

function getButtonHoverCSS($type, $scheme) {
    $css = '';

    switch ($type) {
        case 'primary':
            $primary_color = getPrimaryColor($scheme);
            $css .= 'background-color: ' . $primary_color . ' !important; color: #fff !important;';
            break;
        case 'secondary':
            $css .= 'background-color: #6c757d !important; color: #fff !important;';
            break;
        case 'success':
            $css .= 'background-color: #198754 !important; color: #fff !important;';
            break;
        case 'danger':
            $css .= 'background-color: #dc3545 !important; color: #fff !important;';
            break;
        case 'warning':
            $css .= 'background-color: #ffc107 !important; color: #212529 !important;';
            break;
        case 'info':
            $css .= 'background-color: #0dcaf0 !important; color: #212529 !important;';
            break;
        case 'light':
            $css .= 'background-color: #f8f9fa !important; color: #212529 !important;';
            break;
        case 'dark':
            $css .= 'background-color: #212529 !important; color: #fff !important;';
            break;
    }

    return $css;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Login System'; ?></title>
    <!-- Bootstrap 5.3.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts and Custom Styles -->
    <?php
    // Load global site settings
    $global_settings_file = "global_settings.json";
    $scheme = [];
    $font_family = 'Quicksand'; // Default font

    if (file_exists($global_settings_file)) {
        $scheme = json_decode(file_get_contents($global_settings_file), true);
        if (isset($scheme['font_family'])) {
            $font_family = $scheme['font_family'];
        }
    }
    ?>
    <link href="https://fonts.googleapis.com/css2?family=<?php echo str_replace(' ', '+', $font_family); ?>&display=swap" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="style.css">
    <?php
    // Apply global site settings
    if (!empty($scheme)) {
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

        // Apply global font size percentage
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
        if (isset($scheme['button_type'])) {
            // Get the button type
            $type = $scheme['button_type'];

            // First, reset all buttons without specific styling
            echo '.btn:not([class*="btn-"]) { ';
            echo 'background-color: transparent !important; border: 1px solid transparent !important; color: #212529 !important;';
            echo ' }';

            if (strpos($type, 'outline-') === 0) {
                // Handle outline button types
                $color_type = str_replace('outline-', '', $type);

                // Apply to buttons without specific classes
                echo '.btn:not([class*="btn-outline-"]):not([class*="btn-"]) { ';
                echo getButtonColorCSS($color_type, $scheme, true);
                echo ' }';

                // Add hover effect for outline buttons
                echo '.btn:not([class*="btn-outline-"]):not([class*="btn-"]):hover { ';
                echo getButtonHoverCSS($color_type, $scheme);
                echo ' }';
            } else {
                // Handle solid button types
                // Apply to buttons without specific classes
                echo '.btn:not([class*="btn-"]) { ';
                echo getButtonColorCSS($type, $scheme);
                echo ' }';
            }

            // Apply button type to all buttons with specific classes
            // This ensures the button style is applied consistently across all pages

            // First, apply to buttons with the exact class that matches the button type
            echo '.btn-' . $type . ' { ';
            if (strpos($type, 'outline-') === 0) {
                // For outline buttons
                $color_type = str_replace('outline-', '', $type);
                echo getButtonColorCSS($color_type, $scheme, true);
                echo ' }';

                // Add hover effect
                echo '.btn-' . $type . ':hover { ';
                echo getButtonHoverCSS($color_type, $scheme);
                echo ' }';
            } else {
                // For solid buttons
                echo getButtonColorCSS($type, $scheme);
                echo ' }';
            }

            // Now apply to all buttons with specific classes, regardless of what those classes are
            if (strpos($type, 'outline-') === 0) {
                // For outline buttons, override all outline buttons
                echo '.btn-outline-primary, .btn-outline-secondary, .btn-outline-success, .btn-outline-danger, .btn-outline-warning, .btn-outline-info, .btn-outline-light, .btn-outline-dark { ';
                $color_type = str_replace('outline-', '', $type);
                echo getButtonColorCSS($color_type, $scheme, true);
                echo ' }';

                // Add hover effect for outline buttons
                echo '.btn-outline-primary:hover, .btn-outline-secondary:hover, .btn-outline-success:hover, .btn-outline-danger:hover, .btn-outline-warning:hover, .btn-outline-info:hover, .btn-outline-light:hover, .btn-outline-dark:hover { ';
                echo getButtonHoverCSS($color_type, $scheme);
                echo ' }';
            } else {
                // For solid buttons, override all btn-primary, btn-secondary, etc.
                echo '.btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark { ';
                echo getButtonColorCSS($type, $scheme);
                echo ' }';

                // Also override all outline buttons
                echo '.btn-outline-primary, .btn-outline-secondary, .btn-outline-success, .btn-outline-danger, .btn-outline-warning, .btn-outline-info, .btn-outline-light, .btn-outline-dark { ';
                echo getButtonColorCSS($type, $scheme, true);
                echo ' }';

                // Add hover effect for outline buttons
                echo '.btn-outline-primary:hover, .btn-outline-secondary:hover, .btn-outline-success:hover, .btn-outline-danger:hover, .btn-outline-warning:hover, .btn-outline-info:hover, .btn-outline-light:hover, .btn-outline-dark:hover { ';
                echo getButtonHoverCSS($type, $scheme);
                echo ' }';
            }
        }

        echo '</style>';
    }
    ?>
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="index.php">Login & Registration System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['username'])): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'secret.php' ? 'active' : ''; ?>" href="secret.php">Secret</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'login.php' ? 'active' : ''; ?>" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'register.php' ? 'active' : ''; ?>" href="register.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-grow-1 d-flex align-items-center py-4">
