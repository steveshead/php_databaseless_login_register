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
