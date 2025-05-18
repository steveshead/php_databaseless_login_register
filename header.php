<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Login System'; ?></title>
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

        // Apply button size if set
        if (isset($scheme['button_size']) && !empty($scheme['button_size'])) {
            echo '.btn { padding: ' . ($scheme['button_size'] === 'sm' ? '0.25rem 0.5rem' : '0.5rem 1rem') . ' !important; ';
            echo 'font-size: ' . ($scheme['button_size'] === 'sm' ? 'calc(var(--bs-body-font-size) * 0.875)' : 'calc(var(--bs-body-font-size) * 1.25)') . ' !important; }';
        }

        // Apply button type if set (for all buttons that don't have a specific type)
        if (isset($scheme['button_type']) && $scheme['button_type'] !== 'primary') {
            // Remove any existing btn-* classes and add the new one
            echo '.btn:not([class*="btn-outline-"]):not([class*="btn-secondary"]):not([class*="btn-success"]):not([class*="btn-danger"]):not([class*="btn-warning"]):not([class*="btn-info"]):not([class*="btn-light"]):not([class*="btn-dark"]):not([class*="btn-link"]) { ';

            // Handle different button types
            if (strpos($scheme['button_type'], 'outline-') === 0) {
                // For outline buttons
                $baseType = str_replace('outline-', '', $scheme['button_type']);
                $color = '#0d6efd'; // Default primary color

                // Set color based on button type
                switch ($baseType) {
                    case 'secondary': $color = '#6c757d'; break;
                    case 'success': $color = '#198754'; break;
                    case 'danger': $color = '#dc3545'; break;
                    case 'warning': $color = '#ffc107'; break;
                    case 'info': $color = '#0dcaf0'; break;
                    case 'light': $color = '#f8f9fa'; break;
                    case 'dark': $color = '#212529'; break;
                }

                echo 'background-color: transparent !important; ';
                echo 'color: ' . $color . ' !important; ';
                echo 'border-color: ' . $color . ' !important; ';
                echo '}';

                // Add hover effect
                echo '.btn:not([class*="btn-outline-"]):not([class*="btn-secondary"]):not([class*="btn-success"]):not([class*="btn-danger"]):not([class*="btn-warning"]):not([class*="btn-info"]):not([class*="btn-light"]):not([class*="btn-dark"]):not([class*="btn-link"]):hover { ';
                echo 'background-color: ' . $color . ' !important; ';
                echo 'color: ' . ($baseType === 'warning' || $baseType === 'info' || $baseType === 'light' ? '#000' : '#fff') . ' !important; ';
                echo '}';
            } else {
                // For solid buttons
                $color = '#0d6efd'; // Default primary color
                $textColor = '#fff'; // Default text color

                // Set colors based on button type
                switch ($scheme['button_type']) {
                    case 'secondary': $color = '#6c757d'; break;
                    case 'success': $color = '#198754'; break;
                    case 'danger': $color = '#dc3545'; break;
                    case 'warning': $color = '#ffc107'; $textColor = '#000'; break;
                    case 'info': $color = '#0dcaf0'; $textColor = '#000'; break;
                    case 'light': $color = '#f8f9fa'; $textColor = '#000'; break;
                    case 'dark': $color = '#212529'; break;
                    case 'link': $color = 'transparent'; $textColor = '#0d6efd'; break;
                }

                echo 'background-color: ' . $color . ' !important; ';
                echo 'color: ' . $textColor . ' !important; ';
                echo 'border-color: ' . ($scheme['button_type'] === 'link' ? 'transparent' : $color) . ' !important; ';
                echo '}';
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
                <a class="navbar-brand" href="index.php">Login System</a>
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
