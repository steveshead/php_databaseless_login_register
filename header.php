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
            $size_class = 'btn-' . $scheme['button_size'];
            echo '.btn:not(.btn-sm):not(.btn-lg) { ';
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
                        echo 'color: ' . $scheme['primary_color'] . ' !important; border-color: ' . $scheme['primary_color'] . ' !important;';
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
                        echo 'background-color: ' . $scheme['primary_color'] . ' !important; color: #fff !important;';
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
                        echo 'background-color: transparent !important; border-color: transparent !important; color: ' . $scheme['primary_color'] . ' !important; text-decoration: underline !important;';
                        break;
                }

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
