<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);

// Define bootstrap colors in a single array for easy reference
$bootstrap_colors = [
    'primary' => ['color' => '#0d6efd', 'text' => '#fff'],
    'secondary' => ['color' => '#6c757d', 'text' => '#fff'],
    'success' => ['color' => '#198754', 'text' => '#fff'],
    'danger' => ['color' => '#dc3545', 'text' => '#fff'],
    'warning' => ['color' => '#ffc107', 'text' => '#212529'],
    'info' => ['color' => '#0dcaf0', 'text' => '#212529'],
    'light' => ['color' => '#f8f9fa', 'text' => '#212529'],
    'dark' => ['color' => '#212529', 'text' => '#fff']
];

// Helper functions for styling
function getPrimaryColor($scheme) {
    return (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors'] && isset($scheme['bootstrap_primary_color'])) 
        ? $scheme['bootstrap_primary_color'] 
        : $scheme['primary_color'];
}

function getColorValue($type, $scheme) {
    global $bootstrap_colors;

    if ($type === 'primary') {
        return getPrimaryColor($scheme);
    }

    $color_key = "bootstrap_{$type}_color";
    if (isset($scheme['override_bootstrap_colors']) && $scheme['override_bootstrap_colors'] && isset($scheme[$color_key])) {
        return $scheme[$color_key];
    }

    return isset($bootstrap_colors[$type]) ? $bootstrap_colors[$type]['color'] : '#0d6efd';
}

function getTextColor($type) {
    global $bootstrap_colors;
    return isset($bootstrap_colors[$type]) ? $bootstrap_colors[$type]['text'] : '#fff';
}

function getButtonColorCSS($type, $scheme, $isOutline = false) {
    $css = '';

    if ($type === 'link') {
        $primary_color = getPrimaryColor($scheme);
        return 'background-color: transparent !important; border-color: transparent !important; color: ' . $primary_color . ' !important; text-decoration: underline !important;';
    }

    $color = getColorValue($type, $scheme);
    $text_color = getTextColor($type);

    if ($isOutline) {
        return 'background-color: transparent !important; color: ' . $color . ' !important; border-color: ' . $color . ' !important;';
    } else {
        return 'background-color: ' . $color . ' !important; border-color: ' . $color . ' !important; color: ' . $text_color . ' !important;';
    }
}

function getButtonHoverCSS($type, $scheme) {
    $color = getColorValue($type, $scheme);
    $text_color = getTextColor($type);

    return 'background-color: ' . $color . ' !important; color: ' . $text_color . ' !important;';
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
            $color_types = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];

            foreach ($color_types as $type) {
                $color_key = "bootstrap_{$type}_color";
                if (isset($scheme[$color_key])) {
                    echo "--bs-{$type}: " . $scheme[$color_key] . " !important;";
                    echo "--bs-{$type}-rgb: " . implode(', ', sscanf($scheme[$color_key], "#%02x%02x%02x")) . " !important;";
                }
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
                $color_types = ['secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];

                foreach ($color_types as $type) {
                    $color_key = "bootstrap_{$type}_color";
                    if (isset($scheme[$color_key])) {
                        $color = $scheme[$color_key];
                        $text_color = ($type === 'warning' || $type === 'info' || $type === 'light') ? '#212529' : '#fff';

                        echo ".btn-{$type} { background-color: {$color} !important; border-color: {$color} !important; color: {$text_color} !important; }";
                        echo ".btn-outline-{$type} { color: {$color} !important; border-color: {$color} !important; }";
                        echo ".btn-outline-{$type}:hover { background-color: {$color} !important; color: {$text_color} !important; }";
                        echo ".bg-{$type} { background-color: {$color} !important; }";
                    }
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
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'login.php' ? 'active' : ''; ?>" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'register.php' ? 'active' : ''; ?>" href="register.php">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-grow-1 d-flex align-items-center py-4">
