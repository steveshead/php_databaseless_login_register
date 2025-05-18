<?php
$page_title = "Site Settings - Login System";
require_once 'header.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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
$font_family = 'Roboto'; // Default font family
$button_size = ''; // Default button size (empty for default size)
$button_type = 'primary'; // Default button type

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

// Get current user data from users.json
$users_file = "users.json";
$users = [];
$current_user_index = -1;

if (file_exists($users_file)) {
    $users_json = file_get_contents($users_file);
    $users = json_decode($users_json, true);

    // Find the current user
    foreach ($users as $index => $user) {
        if ($user["username"] === $username) {
            $current_user_index = $index;

            // Get site settings if it exists
            if (isset($user["site_settings"])) {
                $primary_color = isset($user["site_settings"]["primary_color"]) ? $user["site_settings"]["primary_color"] : $primary_color;
                $bg_color = isset($user["site_settings"]["bg_color"]) ? $user["site_settings"]["bg_color"] : $bg_color;
                $card_bg_color = isset($user["site_settings"]["card_bg_color"]) ? $user["site_settings"]["card_bg_color"] : $card_bg_color;
                $text_color = isset($user["site_settings"]["text_color"]) ? $user["site_settings"]["text_color"] : $text_color;
                $font_size = isset($user["site_settings"]["font_size"]) ? $user["site_settings"]["font_size"] : $font_size;
                $font_family = isset($user["site_settings"]["font_family"]) ? $user["site_settings"]["font_family"] : $font_family;
                $button_size = isset($user["site_settings"]["button_size"]) ? $user["site_settings"]["button_size"] : $button_size;
                $button_type = isset($user["site_settings"]["button_type"]) ? $user["site_settings"]["button_type"] : $button_type;
            }
            break;
        }
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $new_primary_color = trim($_POST["primary_color"]);
    $new_bg_color = trim($_POST["bg_color"]);
    $new_card_bg_color = trim($_POST["card_bg_color"]);
    $new_text_color = trim($_POST["text_color"]);
    $new_font_size = trim($_POST["font_size"]);
    $new_font_family = trim($_POST["font_family"]);
    $new_button_size = trim($_POST["button_size"]);
    $new_button_type = trim($_POST["button_type"]);

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

    // Update user data if no errors
    if (empty($error)) {
        // Validate button settings
        if (!in_array($new_button_size, $allowed_button_sizes)) {
            $error = "Invalid button size selected.";
        } elseif (!in_array($new_button_type, $allowed_button_types)) {
            $error = "Invalid button type selected.";
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
            "button_type" => $new_button_type
        ];

        // Update user in array
        $users[$current_user_index]["site_settings"] = $site_settings;

        // Save to file
        if (file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT))) {
            // Update session variables
            $_SESSION["site_settings"] = $site_settings;

            // Update local variables
            $primary_color = $new_primary_color;
            $bg_color = $new_bg_color;
            $card_bg_color = $new_card_bg_color;
            $text_color = $new_text_color;
            $font_size = $new_font_size;
            $font_family = $new_font_family;
            $button_size = $new_button_size;
            $button_type = $new_button_type;

            // Redirect to dashboard page after site settings changes
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Failed to save site settings changes";
        }
    }
}

// Function to generate site settings preview
function generatePreviewCSS($primary, $bg, $card_bg, $text, $font, $font_family, $button_size = '', $button_type = 'primary') {
    // Determine button padding based on size
    $button_padding = '5px 10px'; // Default
    if ($button_size === 'sm') {
        $button_padding = '3px 8px';
    } elseif ($button_size === 'lg') {
        $button_padding = '8px 16px';
    }

    // Determine button colors based on type
    $button_bg = $primary;
    $button_text = 'white';
    $button_border = 'none';

    // Handle outline button types
    if (strpos($button_type, 'outline-') === 0) {
        $color_type = str_replace('outline-', '', $button_type);
        switch ($color_type) {
            case 'primary':
                $button_bg = 'transparent';
                $button_text = $primary;
                $button_border = '1px solid ' . $primary;
                break;
            case 'secondary':
                $button_bg = 'transparent';
                $button_text = '#6c757d';
                $button_border = '1px solid #6c757d';
                break;
            case 'success':
                $button_bg = 'transparent';
                $button_text = '#198754';
                $button_border = '1px solid #198754';
                break;
            case 'danger':
                $button_bg = 'transparent';
                $button_text = '#dc3545';
                $button_border = '1px solid #dc3545';
                break;
            case 'warning':
                $button_bg = 'transparent';
                $button_text = '#ffc107';
                $button_border = '1px solid #ffc107';
                break;
            case 'info':
                $button_bg = 'transparent';
                $button_text = '#0dcaf0';
                $button_border = '1px solid #0dcaf0';
                break;
            case 'light':
                $button_bg = 'transparent';
                $button_text = '#f8f9fa';
                $button_border = '1px solid #f8f9fa';
                break;
            case 'dark':
                $button_bg = 'transparent';
                $button_text = '#212529';
                $button_border = '1px solid #212529';
                break;
            default:
                $button_bg = 'transparent';
                $button_text = $primary;
                $button_border = '1px solid ' . $primary;
        }
    } else {
        // Handle solid button types
        switch ($button_type) {
            case 'primary':
                $button_bg = $primary;
                break;
            case 'secondary':
                $button_bg = '#6c757d';
                break;
            case 'success':
                $button_bg = '#198754';
                break;
            case 'danger':
                $button_bg = '#dc3545';
                break;
            case 'warning':
                $button_bg = '#ffc107';
                $button_text = '#212529';
                break;
            case 'info':
                $button_bg = '#0dcaf0';
                $button_text = '#212529';
                break;
            case 'light':
                $button_bg = '#f8f9fa';
                $button_text = '#212529';
                break;
            case 'dark':
                $button_bg = '#212529';
                break;
            case 'link':
                $button_bg = 'transparent';
                $button_text = $primary;
                $button_border = 'none';
                break;
            default:
                $button_bg = $primary;
        }
    }

    return "
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

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>

                    <!-- Site Settings Preview -->
                    <style>
                        <?php echo generatePreviewCSS($primary_color, $bg_color, $card_bg_color, $text_color, $font_size, $font_family, $button_size, $button_type); ?>
                    </style>

                    <h5 class="mb-3">Preview:</h5>
                    <div class="site-preview">
                        <div class="preview-navbar">Navigation Bar</div>
                        <div class="preview-card">
                            <h5>Card Title</h5>
                            <p>This is how your content will look with the selected site settings.</p>
                            <div class="preview-button">Button</div>
                        </div>
                    </div>

                    <form action="site_settings.php" method="post">
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

                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary" id="reset_colors">Reset to Defaults</button>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="dashboard.php" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update preview in real-time as colors are changed
document.addEventListener('DOMContentLoaded', function() {
    const primaryColorInput = document.getElementById('primary_color');
    const bgColorInput = document.getElementById('bg_color');
    const cardBgColorInput = document.getElementById('card_bg_color');
    const textColorInput = document.getElementById('text_color');
    const fontSizeInput = document.getElementById('font_size');
    const fontFamilyInput = document.getElementById('font_family');
    const buttonSizeInput = document.getElementById('button_size');
    const buttonTypeInput = document.getElementById('button_type');

    const bgColorText = document.getElementById('bg_color_text');
    const cardBgColorText = document.getElementById('card_bg_color_text');
    const textColorText = document.getElementById('text_color_text');

    const resetButton = document.getElementById('reset_colors');

    // Initialize preview with current values on page load
    updatePreview();

    // Update preview when primary color dropdown changes
    primaryColorInput.addEventListener('change', function() {
        updatePreview();
    });

    bgColorInput.addEventListener('input', function() {
        bgColorText.value = this.value;
        updatePreview();
    });

    cardBgColorInput.addEventListener('input', function() {
        cardBgColorText.value = this.value;
        updatePreview();
    });

    textColorInput.addEventListener('input', function() {
        textColorText.value = this.value;
        updatePreview();
    });

    // Update preview when font size changes
    fontSizeInput.addEventListener('input', function() {
        updatePreview();
    });

    // Update preview when font family changes
    fontFamilyInput.addEventListener('change', function() {
        updatePreview();
    });

    // Update preview when button size changes
    buttonSizeInput.addEventListener('change', function() {
        updatePreview();
    });

    // Update preview when button type changes
    buttonTypeInput.addEventListener('change', function() {
        updatePreview();
    });

    // Update color picker when text input changes
    bgColorText.addEventListener('input', function() {
        bgColorInput.value = this.value;
        updatePreview();
    });

    cardBgColorText.addEventListener('input', function() {
        cardBgColorInput.value = this.value;
        updatePreview();
    });

    textColorText.addEventListener('input', function() {
        textColorInput.value = this.value;
        updatePreview();
    });

    // Reset to default colors, font size, font family, and button settings
    resetButton.addEventListener('click', function() {
        primaryColorInput.value = '#0d6efd';
        bgColorInput.value = '#f8f9fa';
        cardBgColorInput.value = '#ffffff';
        textColorInput.value = '#212529';
        fontSizeInput.value = '16px';
        fontFamilyInput.value = 'Roboto';
        buttonSizeInput.value = ''; // Default size
        buttonTypeInput.value = 'primary'; // Default type

        bgColorText.value = '#f8f9fa';
        cardBgColorText.value = '#ffffff';
        textColorText.value = '#212529';

        updatePreview();
    });

    function updatePreview() {
        const preview = document.querySelector('.site-preview');
        const previewNavbar = document.querySelector('.preview-navbar');
        const previewCard = document.querySelector('.preview-card');
        const previewButton = document.querySelector('.preview-button');

        // Load Google Font dynamically
        const fontLink = document.getElementById('google-font-preview');
        if (fontLink) {
            document.head.removeChild(fontLink);
        }
        const link = document.createElement('link');
        link.id = 'google-font-preview';
        link.rel = 'stylesheet';
        link.href = `https://fonts.googleapis.com/css2?family=${fontFamilyInput.value.replace(/ /g, '+')}&display=swap`;
        document.head.appendChild(link);

        preview.style.backgroundColor = bgColorInput.value;
        preview.style.color = textColorInput.value;
        preview.style.fontSize = fontSizeInput.value;
        preview.style.fontFamily = `'${fontFamilyInput.value}', sans-serif`;
        previewNavbar.style.backgroundColor = primaryColorInput.value;
        previewCard.style.backgroundColor = cardBgColorInput.value;

        // Update button size (padding)
        let buttonPadding = '5px 10px'; // Default
        if (buttonSizeInput.value === 'sm') {
            buttonPadding = '3px 8px';
        } else if (buttonSizeInput.value === 'lg') {
            buttonPadding = '8px 16px';
        }
        previewButton.style.padding = buttonPadding;

        // Update button type (colors and borders)
        let buttonBg = primaryColorInput.value;
        let buttonText = 'white';
        let buttonBorder = 'none';

        // Handle outline button types
        if (buttonTypeInput.value.startsWith('outline-')) {
            const colorType = buttonTypeInput.value.replace('outline-', '');
            switch (colorType) {
                case 'primary':
                    buttonBg = 'transparent';
                    buttonText = primaryColorInput.value;
                    buttonBorder = `1px solid ${primaryColorInput.value}`;
                    break;
                case 'secondary':
                    buttonBg = 'transparent';
                    buttonText = '#6c757d';
                    buttonBorder = '1px solid #6c757d';
                    break;
                case 'success':
                    buttonBg = 'transparent';
                    buttonText = '#198754';
                    buttonBorder = '1px solid #198754';
                    break;
                case 'danger':
                    buttonBg = 'transparent';
                    buttonText = '#dc3545';
                    buttonBorder = '1px solid #dc3545';
                    break;
                case 'warning':
                    buttonBg = 'transparent';
                    buttonText = '#ffc107';
                    buttonBorder = '1px solid #ffc107';
                    break;
                case 'info':
                    buttonBg = 'transparent';
                    buttonText = '#0dcaf0';
                    buttonBorder = '1px solid #0dcaf0';
                    break;
                case 'light':
                    buttonBg = 'transparent';
                    buttonText = '#f8f9fa';
                    buttonBorder = '1px solid #f8f9fa';
                    break;
                case 'dark':
                    buttonBg = 'transparent';
                    buttonText = '#212529';
                    buttonBorder = '1px solid #212529';
                    break;
                default:
                    buttonBg = 'transparent';
                    buttonText = primaryColorInput.value;
                    buttonBorder = `1px solid ${primaryColorInput.value}`;
            }
        } else {
            // Handle solid button types
            switch (buttonTypeInput.value) {
                case 'primary':
                    buttonBg = primaryColorInput.value;
                    break;
                case 'secondary':
                    buttonBg = '#6c757d';
                    break;
                case 'success':
                    buttonBg = '#198754';
                    break;
                case 'danger':
                    buttonBg = '#dc3545';
                    break;
                case 'warning':
                    buttonBg = '#ffc107';
                    buttonText = '#212529';
                    break;
                case 'info':
                    buttonBg = '#0dcaf0';
                    buttonText = '#212529';
                    break;
                case 'light':
                    buttonBg = '#f8f9fa';
                    buttonText = '#212529';
                    break;
                case 'dark':
                    buttonBg = '#212529';
                    break;
                case 'link':
                    buttonBg = 'transparent';
                    buttonText = primaryColorInput.value;
                    buttonBorder = 'none';
                    break;
                default:
                    buttonBg = primaryColorInput.value;
            }
        }

        previewButton.style.backgroundColor = buttonBg;
        previewButton.style.color = buttonText;
        previewButton.style.border = buttonBorder;
    }
});
</script>

<?php require_once 'footer.php'; ?>
