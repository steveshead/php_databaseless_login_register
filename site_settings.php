<?php
require_once 'header.php';
$page_title = "Site Settings - Login System";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if user has admin role
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$readonly = !$is_admin;

// Redirect non-admin users to their dashboard
if (!$is_admin) {
    header("Location: dashboard.php");
    exit();
}

// Initialize variables
$username = $_SESSION['username'];
$success_message = isset($_GET['success']) && $_GET['success'] === 'true' ? "Site settings updated successfully!" : '';
$error = '';

// Default colors, font size, font family, and button settings
$primary_color = '#0d6efd'; // Bootstrap default primary
$bg_color = '#f8f9fa'; // Bootstrap default light
$card_bg_color = '#ffffff'; // White
$text_color = '#212529'; // Bootstrap default dark
$font_size = '16px'; // Default font size
$font_size_percentage = '100%'; // Default font size percentage
$font_family = 'Quicksand'; // Default font family
$button_size = ''; // Default button size (empty for default size)
$button_type = 'primary'; // Default button type

// Bootstrap color override settings
$override_bootstrap_colors = false; // Default to not override bootstrap colors
$bootstrap_primary_color = '#0d6efd'; // Bootstrap default primary
$bootstrap_secondary_color = '#6c757d'; // Bootstrap default secondary
$bootstrap_success_color = '#198754'; // Bootstrap default success
$bootstrap_danger_color = '#dc3545'; // Bootstrap default danger
$bootstrap_warning_color = '#ffc107'; // Bootstrap default warning
$bootstrap_info_color = '#0dcaf0'; // Bootstrap default info
$bootstrap_light_color = '#f8f9fa'; // Bootstrap default light
$bootstrap_dark_color = '#212529'; // Bootstrap default dark

// List of allowed Google Fonts
$allowed_fonts = [
    'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Raleway', 'Poppins',
    'Nunito', 'Playfair Display', 'Merriweather', 'Ubuntu', 'Rubik',
    'Source Sans Pro', 'PT Sans', 'Oswald', 'Quicksand'
];

// List of allowed button sizes
$allowed_button_sizes = [
    '', 'sm', 'lg'
];

// List of allowed button types
$allowed_button_types = [
    'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link',
    'outline-primary', 'outline-secondary', 'outline-success', 'outline-danger', 'outline-warning', 'outline-info', 'outline-light', 'outline-dark'
];

// List of allowed Bootstrap 5 primary colors
$allowed_primary_colors = [
    '#0d6efd' => 'Primary (Blue)',
    '#6c757d' => 'Secondary (Gray)',
    '#198754' => 'Success (Green)',
    '#dc3545' => 'Danger (Red)',
    '#ffc107' => 'Warning (Yellow)',
    '#0dcaf0' => 'Info (Cyan)',
    '#f8f9fa' => 'Light (Light Gray)',
    '#212529' => 'Dark (Dark Gray)'
];

// Get global site settings from global_settings.json
$global_settings_file = "global_settings.json";
$global_settings = [];

if (file_exists($global_settings_file)) {
    $global_settings_json = file_get_contents($global_settings_file);
    $global_settings = json_decode($global_settings_json, true);

    // Get site settings if they exist
    if (!empty($global_settings)) {
        $primary_color = isset($global_settings["primary_color"]) ? $global_settings["primary_color"] : $primary_color;
        $bg_color = isset($global_settings["bg_color"]) ? $global_settings["bg_color"] : $bg_color;
        $card_bg_color = isset($global_settings["card_bg_color"]) ? $global_settings["card_bg_color"] : $card_bg_color;
        $text_color = isset($global_settings["text_color"]) ? $global_settings["text_color"] : $text_color;
        $font_size = isset($global_settings["font_size"]) ? $global_settings["font_size"] : $font_size;
        $font_size_percentage = isset($global_settings["font_size_percentage"]) ? $global_settings["font_size_percentage"] : $font_size_percentage;
        $font_family = isset($global_settings["font_family"]) ? $global_settings["font_family"] : $font_family;
        $button_size = isset($global_settings["button_size"]) ? $global_settings["button_size"] : $button_size;
        $button_type = isset($global_settings["button_type"]) ? $global_settings["button_type"] : $button_type;

        // Get bootstrap color override settings if they exist
        $override_bootstrap_colors = isset($global_settings["override_bootstrap_colors"]) ? $global_settings["override_bootstrap_colors"] : $override_bootstrap_colors;
        $bootstrap_primary_color = isset($global_settings["bootstrap_primary_color"]) ? $global_settings["bootstrap_primary_color"] : $bootstrap_primary_color;
        $bootstrap_secondary_color = isset($global_settings["bootstrap_secondary_color"]) ? $global_settings["bootstrap_secondary_color"] : $bootstrap_secondary_color;
        $bootstrap_success_color = isset($global_settings["bootstrap_success_color"]) ? $global_settings["bootstrap_success_color"] : $bootstrap_success_color;
        $bootstrap_danger_color = isset($global_settings["bootstrap_danger_color"]) ? $global_settings["bootstrap_danger_color"] : $bootstrap_danger_color;
        $bootstrap_warning_color = isset($global_settings["bootstrap_warning_color"]) ? $global_settings["bootstrap_warning_color"] : $bootstrap_warning_color;
        $bootstrap_info_color = isset($global_settings["bootstrap_info_color"]) ? $global_settings["bootstrap_info_color"] : $bootstrap_info_color;
        $bootstrap_light_color = isset($global_settings["bootstrap_light_color"]) ? $global_settings["bootstrap_light_color"] : $bootstrap_light_color;
        $bootstrap_dark_color = isset($global_settings["bootstrap_dark_color"]) ? $global_settings["bootstrap_dark_color"] : $bootstrap_dark_color;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is admin before processing form
    if (!$is_admin) {
        $error = "You do not have permission to change site settings. Only administrators can modify settings.";
    } else {
        // Get form data
        $new_primary_color = trim($_POST["primary_color"]);
        $new_bg_color = trim($_POST["bg_color"]);
        $new_card_bg_color = trim($_POST["card_bg_color"]);
        $new_text_color = trim($_POST["text_color"]);
        $new_font_size = trim($_POST["font_size"]);
        // Font size percentage is now a global setting
        $new_font_family = trim($_POST["font_family"]);
        $new_button_size = trim($_POST["button_size"]);
        $new_button_type = trim($_POST["button_type"]);

        // Get global settings data
        $new_global_font_size_percentage = trim($_POST["global_font_size_percentage"]);

        // Get bootstrap color override data
        $new_override_bootstrap_colors = isset($_POST["override_bootstrap_colors"]) ? true : false;
        $new_bootstrap_primary_color = isset($_POST["bootstrap_primary_color"]) ? trim($_POST["bootstrap_primary_color"]) : $bootstrap_primary_color;
        $new_bootstrap_secondary_color = isset($_POST["bootstrap_secondary_color"]) ? trim($_POST["bootstrap_secondary_color"]) : $bootstrap_secondary_color;
        $new_bootstrap_success_color = isset($_POST["bootstrap_success_color"]) ? trim($_POST["bootstrap_success_color"]) : $bootstrap_success_color;
        $new_bootstrap_danger_color = isset($_POST["bootstrap_danger_color"]) ? trim($_POST["bootstrap_danger_color"]) : $bootstrap_danger_color;
        $new_bootstrap_warning_color = isset($_POST["bootstrap_warning_color"]) ? trim($_POST["bootstrap_warning_color"]) : $bootstrap_warning_color;
        $new_bootstrap_info_color = isset($_POST["bootstrap_info_color"]) ? trim($_POST["bootstrap_info_color"]) : $bootstrap_info_color;
        $new_bootstrap_light_color = isset($_POST["bootstrap_light_color"]) ? trim($_POST["bootstrap_light_color"]) : $bootstrap_light_color;
        $new_bootstrap_dark_color = isset($_POST["bootstrap_dark_color"]) ? trim($_POST["bootstrap_dark_color"]) : $bootstrap_dark_color;

        // Validate input (basic validation for hex colors)
        $color_pattern = '/^#[a-fA-F0-9]{6}$/';

        if (!array_key_exists($new_primary_color, $allowed_primary_colors)) {
            $error = "Invalid primary color. Please select a valid Bootstrap 5 color.";
        } elseif (!preg_match($color_pattern, $new_bg_color)) {
            $error = "Invalid background color format. Use hex format (e.g., #f8f9fa).";
        } elseif (!preg_match($color_pattern, $new_card_bg_color)) {
            $error = "Invalid card background color format. Use hex format (e.g., #ffffff).";
        } elseif (!preg_match($color_pattern, $new_text_color)) {
            $error = "Invalid text color format. Use hex format (e.g., #212529).";
        } elseif (!preg_match('/^\d+px$/', $new_font_size)) {
            $error = "Invalid font size format. Use pixel format (e.g., 16px).";
        } elseif (!in_array($new_font_family, $allowed_fonts)) {
            $error = "Invalid font family selected.";
        }

        // Validate global font size percentage
        if (isset($new_global_font_size_percentage) && !preg_match('/^\d+%$/', $new_global_font_size_percentage)) {
            $error = "Invalid global font size percentage format. Use percentage format (e.g., 100%).";
        }

        // Update user data if no errors
        if (empty($error)) {
            // Validate button settings
            if (!in_array($new_button_size, $allowed_button_sizes)) {
                $error = "Invalid button size selected.";
            } elseif (!in_array($new_button_type, $allowed_button_types)) {
                $error = "Invalid button type selected.";
            }

            // Validate bootstrap color override settings if enabled
            if ($new_override_bootstrap_colors) {
                if (!preg_match($color_pattern, $new_bootstrap_primary_color)) {
                    $error = "Invalid primary color format. Use hex format (e.g., #0d6efd).";
                } elseif (!preg_match($color_pattern, $new_bootstrap_secondary_color)) {
                    $error = "Invalid secondary color format. Use hex format (e.g., #6c757d).";
                } elseif (!preg_match($color_pattern, $new_bootstrap_success_color)) {
                    $error = "Invalid success color format. Use hex format (e.g., #198754).";
                } elseif (!preg_match($color_pattern, $new_bootstrap_danger_color)) {
                    $error = "Invalid danger color format. Use hex format (e.g., #dc3545).";
                } elseif (!preg_match($color_pattern, $new_bootstrap_warning_color)) {
                    $error = "Invalid warning color format. Use hex format (e.g., #ffc107).";
                } elseif (!preg_match($color_pattern, $new_bootstrap_info_color)) {
                    $error = "Invalid info color format. Use hex format (e.g., #0dcaf0).";
                } elseif (!preg_match($color_pattern, $new_bootstrap_light_color)) {
                    $error = "Invalid light color format. Use hex format (e.g., #f8f9fa).";
                } elseif (!preg_match($color_pattern, $new_bootstrap_dark_color)) {
                    $error = "Invalid dark color format. Use hex format (e.g., #212529).";
                }
            }

            // Create or update site settings
            $site_settings = [
                "primary_color" => $new_primary_color,
                "bg_color" => $new_bg_color,
                "card_bg_color" => $new_card_bg_color,
                "text_color" => $new_text_color,
                "font_size" => $new_font_size,
                "font_family" => $new_font_family,
                "button_size" => $new_button_size,
                "button_type" => $new_button_type,
                "override_bootstrap_colors" => $new_override_bootstrap_colors,
                "bootstrap_primary_color" => $new_bootstrap_primary_color,
                "bootstrap_secondary_color" => $new_bootstrap_secondary_color,
                "bootstrap_success_color" => $new_bootstrap_success_color,
                "bootstrap_danger_color" => $new_bootstrap_danger_color,
                "bootstrap_warning_color" => $new_bootstrap_warning_color,
                "bootstrap_info_color" => $new_bootstrap_info_color,
                "bootstrap_light_color" => $new_bootstrap_light_color,
                "bootstrap_dark_color" => $new_bootstrap_dark_color,
                "font_size_percentage" => $new_global_font_size_percentage // Include font size percentage in global settings
            ];

            // Save to global settings file
            $global_settings_file = "global_settings.json";
            if (file_put_contents($global_settings_file, json_encode($site_settings, JSON_PRETTY_PRINT))) {
                // Update session variables
                $_SESSION["site_settings"] = $site_settings;

                // Update local variables
                $primary_color = $new_primary_color;
                $bg_color = $new_bg_color;
                $card_bg_color = $new_card_bg_color;
                $text_color = $new_text_color;
                $font_size = $new_font_size;
                $font_size_percentage = null; // Font size percentage is now a global setting
                $font_family = $new_font_family;
                $button_size = $new_button_size;
                $button_type = $new_button_type;
                $override_bootstrap_colors = $new_override_bootstrap_colors;
                $bootstrap_primary_color = $new_bootstrap_primary_color;
                $bootstrap_secondary_color = $new_bootstrap_secondary_color;
                $bootstrap_success_color = $new_bootstrap_success_color;
                $bootstrap_danger_color = $new_bootstrap_danger_color;
                $bootstrap_warning_color = $new_bootstrap_warning_color;
                $bootstrap_info_color = $new_bootstrap_info_color;
                $bootstrap_light_color = $new_bootstrap_light_color;
                $bootstrap_dark_color = $new_bootstrap_dark_color;

                // Set success flag for display
                $success_message = "Site settings updated successfully!";
            } else {
                $error = "Failed to save site settings changes";
            }
        }
    }
}

// Function to generate site settings preview
function generatePreviewCSS($primary, $bg, $card_bg, $text, $font, $font_family, $button_size = '', $button_type = 'primary', $override_bootstrap_colors = false, $bootstrap_primary = '#0d6efd', $bootstrap_secondary = '#6c757d', $bootstrap_success = '#198754', $bootstrap_danger = '#dc3545', $bootstrap_warning = '#ffc107', $bootstrap_info = '#0dcaf0', $bootstrap_light = '#f8f9fa', $bootstrap_dark = '#212529', $font_size_percentage = '100%') {
    // Determine button padding based on size
    $button_padding = '5px 10px'; // Default
    if ($button_size === 'sm') {
        $button_padding = '3px 8px';
    } elseif ($button_size === 'lg') {
        $button_padding = '8px 16px';
    }

    // Determine button colors based on type
    $button_bg = $override_bootstrap_colors ? $bootstrap_primary : $primary;
    $button_text = 'white';
    $button_border = 'none';

    // Handle outline button types
    if (strpos($button_type, 'outline-') === 0) {
        $color_type = str_replace('outline-', '', $button_type);
        switch ($color_type) {
            case 'primary':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_primary : $primary;
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_primary : $primary);
                break;
            case 'secondary':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_secondary : '#6c757d';
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_secondary : '#6c757d');
                break;
            case 'success':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_success : '#198754';
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_success : '#198754');
                break;
            case 'danger':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_danger : '#dc3545';
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_danger : '#dc3545');
                break;
            case 'warning':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_warning : '#ffc107';
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_warning : '#ffc107');
                break;
            case 'info':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_info : '#0dcaf0';
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_info : '#0dcaf0');
                break;
            case 'light':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_light : '#f8f9fa';
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_light : '#f8f9fa');
                break;
            case 'dark':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_dark : '#212529';
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_dark : '#212529');
                break;
            default:
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_primary : $primary;
                $button_border = '1px solid ' . ($override_bootstrap_colors ? $bootstrap_primary : $primary);
        }
    } else {
        // Handle solid button types
        switch ($button_type) {
            case 'primary':
                $button_bg = $override_bootstrap_colors ? $bootstrap_primary : $primary;
                break;
            case 'secondary':
                $button_bg = $override_bootstrap_colors ? $bootstrap_secondary : '#6c757d';
                break;
            case 'success':
                $button_bg = $override_bootstrap_colors ? $bootstrap_success : '#198754';
                break;
            case 'danger':
                $button_bg = $override_bootstrap_colors ? $bootstrap_danger : '#dc3545';
                break;
            case 'warning':
                $button_bg = $override_bootstrap_colors ? $bootstrap_warning : '#ffc107';
                $button_text = '#212529';
                break;
            case 'info':
                $button_bg = $override_bootstrap_colors ? $bootstrap_info : '#0dcaf0';
                $button_text = '#212529';
                break;
            case 'light':
                $button_bg = $override_bootstrap_colors ? $bootstrap_light : '#f8f9fa';
                $button_text = '#212529';
                break;
            case 'dark':
                $button_bg = $override_bootstrap_colors ? $bootstrap_dark : '#212529';
                break;
            case 'link':
                $button_bg = 'transparent';
                $button_text = $override_bootstrap_colors ? $bootstrap_primary : $primary;
                $button_border = 'none';
                break;
            default:
                $button_bg = $override_bootstrap_colors ? $bootstrap_primary : $primary;
        }
    }

    // CSS variables for bootstrap colors if overrides are enabled
    $bootstrap_vars = '';
    if ($override_bootstrap_colors) {
        $bootstrap_vars = "
        :root {
            --bs-primary: {$bootstrap_primary};
            --bs-secondary: {$bootstrap_secondary};
            --bs-success: {$bootstrap_success};
            --bs-danger: {$bootstrap_danger};
            --bs-warning: {$bootstrap_warning};
            --bs-info: {$bootstrap_info};
            --bs-light: {$bootstrap_light};
            --bs-dark: {$bootstrap_dark};
        }";
    }

    return $bootstrap_vars . "
        .site-preview-container {
            font-size: {$font_size_percentage};
        }
        .site-preview {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: {$bg};
            color: {$text};
            font-size: {$font};
            font-family: '{$font_family}', sans-serif;
        }
        .site-preview .preview-navbar {
            background-color: {$primary};
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .site-preview .preview-card {
            background-color: {$card_bg};
            border: 1px solid rgba(0,0,0,0.125);
            border-radius: 5px;
            padding: 10px;
        }
        .site-preview .preview-button {
            background-color: {$button_bg};
            color: {$button_text};
            border: {$button_border};
            padding: {$button_padding};
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .site-preview .preview-button:hover {
            opacity: 0.9;
        }
    ";
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="card-title mb-4">Site Settings</h1>

                    <?php if (!$is_admin): ?>
                        <div class="alert alert-warning">You are viewing site settings in read-only mode. Only administrators can modify these settings.</div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>

                    <!-- Site Settings Preview -->
                    <style>
                        <?php echo generatePreviewCSS($primary_color, $bg_color, $card_bg_color, $text_color, $font_size, $font_family, $button_size, $button_type, $override_bootstrap_colors, $bootstrap_primary_color, $bootstrap_secondary_color, $bootstrap_success_color, $bootstrap_danger_color, $bootstrap_warning_color, $bootstrap_info_color, $bootstrap_light_color, $bootstrap_dark_color, $font_size_percentage); ?>
                    </style>

                    <h5 class="mb-3">Preview:</h5>
                    <div class="site-preview-container">
                        <div class="site-preview">
                            <div class="preview-navbar">Navigation Bar</div>
                            <div class="preview-card">
                                <h5>Card Title</h5>
                                <p>This is how your content will look with the selected site settings.</p>
                                <div class="preview-button">Button</div>
                            </div>
                        </div>
                    </div>

                    <form action="site_settings.php" method="post" id="siteSettingsSubmit">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="primary_color" class="form-label">Primary Color</label>
                                <select class="form-select" id="primary_color" name="primary_color">
                                    <?php foreach ($allowed_primary_colors as $color => $name): ?>
                                        <option value="<?php echo $color; ?>" <?php echo $color === $primary_color ? 'selected' : ''; ?> style="background-color: <?php echo $color; ?>; color: <?php echo in_array($color, ['#f8f9fa', '#ffffff', '#ffc107']) ? '#212529' : '#ffffff'; ?>;">
                                            <?php echo $name; ?> (<?php echo $color; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Used for navbar, buttons, and links (Bootstrap 5 colors only)</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bg_color" class="form-label">Background Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="bg_color" name="bg_color" value="<?php echo htmlspecialchars($bg_color); ?>" title="Choose background color">
                                    <input type="text" class="form-control" aria-label="Background color hex value" value="<?php echo htmlspecialchars($bg_color); ?>" id="bg_color_text" oninput="document.getElementById('bg_color').value = this.value">
                                </div>
                                <div class="form-text">Used for page background</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="card_bg_color" class="form-label">Card Background Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="card_bg_color" name="card_bg_color" value="<?php echo htmlspecialchars($card_bg_color); ?>" title="Choose card background color">
                                    <input type="text" class="form-control" aria-label="Card background color hex value" value="<?php echo htmlspecialchars($card_bg_color); ?>" id="card_bg_color_text" oninput="document.getElementById('card_bg_color').value = this.value">
                                </div>
                                <div class="form-text">Used for card backgrounds</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="text_color" class="form-label">Text Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="text_color" name="text_color" value="<?php echo htmlspecialchars($text_color); ?>" title="Choose text color">
                                    <input type="text" class="form-control" aria-label="Text color hex value" value="<?php echo htmlspecialchars($text_color); ?>" id="text_color_text" oninput="document.getElementById('text_color').value = this.value">
                                </div>
                                <div class="form-text">Used for main text content</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="font_size" class="form-label">Font Size</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="font_size" name="font_size" value="<?php echo htmlspecialchars($font_size); ?>" title="Choose font size">
                                </div>
                                <div class="form-text">Used for text size (e.g., 16px)</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="font_family" class="form-label">Font Family</label>
                                <select class="form-select" id="font_family" name="font_family">
                                    <?php foreach ($allowed_fonts as $font): ?>
                                        <option value="<?php echo $font; ?>" <?php echo $font === $font_family ? 'selected' : ''; ?>><?php echo $font; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Choose from Google Fonts</div>
                            </div>
                        </div>

                        <!-- Font Size Percentage is now a global setting -->

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="button_size" class="form-label">Button Size</label>
                                <select class="form-select" id="button_size" name="button_size">
                                    <?php foreach ($allowed_button_sizes as $size): ?>
                                        <option value="<?php echo $size; ?>" <?php echo $size === $button_size ? 'selected' : ''; ?>>
                                            <?php
                                                if ($size === '') echo 'Default';
                                                elseif ($size === 'sm') echo 'Small';
                                                elseif ($size === 'lg') echo 'Large';
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Choose button size</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="button_type" class="form-label">Button Type</label>
                                <select class="form-select" id="button_type" name="button_type">
                                    <?php foreach ($allowed_button_types as $type): ?>
                                        <option value="<?php echo $type; ?>" <?php echo $type === $button_type ? 'selected' : ''; ?>>
                                            <?php echo ucfirst(str_replace('-', ' ', $type)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Choose button style</div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Bootstrap Color Overrides</h5>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="override_bootstrap_colors" name="override_bootstrap_colors" <?php echo $override_bootstrap_colors ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="override_bootstrap_colors">Override Bootstrap Colors</label>
                            <div class="form-text">Enable to override default Bootstrap colors for navbar, buttons, and other elements</div>
                        </div>

                        <div id="bootstrap_colors_section" class="<?php echo $override_bootstrap_colors ? '' : 'd-none'; ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_primary_color" class="form-label">Primary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_primary_color" name="bootstrap_primary_color" value="<?php echo htmlspecialchars($bootstrap_primary_color); ?>" title="Choose primary color">
                                        <input type="text" class="form-control" aria-label="Primary color hex value" value="<?php echo htmlspecialchars($bootstrap_primary_color); ?>" id="bootstrap_primary_color_text" oninput="document.getElementById('bootstrap_primary_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for primary buttons and navigation</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_secondary_color" class="form-label">Secondary Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_secondary_color" name="bootstrap_secondary_color" value="<?php echo htmlspecialchars($bootstrap_secondary_color); ?>" title="Choose secondary color">
                                        <input type="text" class="form-control" aria-label="Secondary color hex value" value="<?php echo htmlspecialchars($bootstrap_secondary_color); ?>" id="bootstrap_secondary_color_text" oninput="document.getElementById('bootstrap_secondary_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for secondary buttons and elements</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_success_color" class="form-label">Success Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_success_color" name="bootstrap_success_color" value="<?php echo htmlspecialchars($bootstrap_success_color); ?>" title="Choose success color">
                                        <input type="text" class="form-control" aria-label="Success color hex value" value="<?php echo htmlspecialchars($bootstrap_success_color); ?>" id="bootstrap_success_color_text" oninput="document.getElementById('bootstrap_success_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for success messages and buttons</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_danger_color" class="form-label">Danger Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_danger_color" name="bootstrap_danger_color" value="<?php echo htmlspecialchars($bootstrap_danger_color); ?>" title="Choose danger color">
                                        <input type="text" class="form-control" aria-label="Danger color hex value" value="<?php echo htmlspecialchars($bootstrap_danger_color); ?>" id="bootstrap_danger_color_text" oninput="document.getElementById('bootstrap_danger_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for error messages and delete buttons</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_warning_color" class="form-label">Warning Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_warning_color" name="bootstrap_warning_color" value="<?php echo htmlspecialchars($bootstrap_warning_color); ?>" title="Choose warning color">
                                        <input type="text" class="form-control" aria-label="Warning color hex value" value="<?php echo htmlspecialchars($bootstrap_warning_color); ?>" id="bootstrap_warning_color_text" oninput="document.getElementById('bootstrap_warning_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for warning messages and alerts</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_info_color" class="form-label">Info Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_info_color" name="bootstrap_info_color" value="<?php echo htmlspecialchars($bootstrap_info_color); ?>" title="Choose info color">
                                        <input type="text" class="form-control" aria-label="Info color hex value" value="<?php echo htmlspecialchars($bootstrap_info_color); ?>" id="bootstrap_info_color_text" oninput="document.getElementById('bootstrap_info_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for informational messages</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_light_color" class="form-label">Light Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_light_color" name="bootstrap_light_color" value="<?php echo htmlspecialchars($bootstrap_light_color); ?>" title="Choose light color">
                                        <input type="text" class="form-control" aria-label="Light color hex value" value="<?php echo htmlspecialchars($bootstrap_light_color); ?>" id="bootstrap_light_color_text" oninput="document.getElementById('bootstrap_light_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for light backgrounds and elements</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bootstrap_dark_color" class="form-label">Dark Color</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="bootstrap_dark_color" name="bootstrap_dark_color" value="<?php echo htmlspecialchars($bootstrap_dark_color); ?>" title="Choose dark color">
                                        <input type="text" class="form-control" aria-label="Dark color hex value" value="<?php echo htmlspecialchars($bootstrap_dark_color); ?>" id="bootstrap_dark_color_text" oninput="document.getElementById('bootstrap_dark_color').value = this.value">
                                    </div>
                                    <div class="form-text">Used for dark text and backgrounds</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary" id="reset_colors">Reset to Defaults</button>
                        </div>

                        <h5 class="mt-4 mb-3">Global Settings</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="global_font_size_percentage" class="form-label">Global Font Size Percentage</label>
                                <div class="input-group">
                                    <?php
                                    // Load global settings
                                    $global_settings = json_decode(file_get_contents('global_settings.json'), true);
                                    $global_font_size_percentage = isset($global_settings['font_size_percentage']) ? $global_settings['font_size_percentage'] : '100%';
                                    ?>
                                    <input type="text" class="form-control" id="global_font_size_percentage" name="global_font_size_percentage" value="<?php echo htmlspecialchars($global_font_size_percentage); ?>" title="Choose global font size percentage">
                                </div>
                                <div class="form-text">Adjust global font size by percentage for all users (e.g., 100% for default, 120% for larger, 80% for smaller)</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="dashboard.php" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" id="submit_refresh" id="click" class="btn btn-primary" <?php echo $readonly ? 'disabled' : ''; ?>>Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'footer.php'; ?>
