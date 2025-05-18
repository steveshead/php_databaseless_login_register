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

    if (!preg_match($color_pattern, $new_primary_color)) {
        $error = "Invalid primary color format. Use hex format (e.g., #0d6efd).";
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
    } elseif (!in_array($new_button_size, $allowed_button_sizes)) {
        $error = "Invalid button size selected.";
    } elseif (!in_array($new_button_type, $allowed_button_types)) {
        $error = "Invalid button type selected.";
    }

    // Update user data if no errors
    if (empty($error)) {
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
    // Determine button background and text color based on button type
    $button_bg = $primary;
    $button_text = 'white';
    $button_border = $primary;

    // Handle outline button types
    if (strpos($button_type, 'outline-') === 0) {
        $button_bg = 'transparent';
        $button_text = $primary;
        $button_border = $primary;
    }

    // Handle non-primary button types
    if ($button_type !== 'primary' && $button_type !== 'outline-primary') {
        // For simplicity, we'll just use some common colors for the preview
        switch (str_replace('outline-', '', $button_type)) {
            case 'secondary':
                $button_bg = $button_type === 'secondary' ? '#6c757d' : 'transparent';
                $button_text = $button_type === 'secondary' ? 'white' : '#6c757d';
                $button_border = '#6c757d';
                break;
            case 'success':
                $button_bg = $button_type === 'success' ? '#198754' : 'transparent';
                $button_text = $button_type === 'success' ? 'white' : '#198754';
                $button_border = '#198754';
                break;
            case 'danger':
                $button_bg = $button_type === 'danger' ? '#dc3545' : 'transparent';
                $button_text = $button_type === 'danger' ? 'white' : '#dc3545';
                $button_border = '#dc3545';
                break;
            case 'warning':
                $button_bg = $button_type === 'warning' ? '#ffc107' : 'transparent';
                $button_text = $button_type === 'warning' ? 'black' : '#ffc107';
                $button_border = '#ffc107';
                break;
            case 'info':
                $button_bg = $button_type === 'info' ? '#0dcaf0' : 'transparent';
                $button_text = $button_type === 'info' ? 'black' : '#0dcaf0';
                $button_border = '#0dcaf0';
                break;
            case 'light':
                $button_bg = $button_type === 'light' ? '#f8f9fa' : 'transparent';
                $button_text = $button_type === 'light' ? 'black' : '#f8f9fa';
                $button_border = '#f8f9fa';
                break;
            case 'dark':
                $button_bg = $button_type === 'dark' ? '#212529' : 'transparent';
                $button_text = $button_type === 'dark' ? 'white' : '#212529';
                $button_border = '#212529';
                break;
            case 'link':
                $button_bg = 'transparent';
                $button_text = $primary;
                $button_border = 'transparent';
                break;
        }
    }

    // Determine button padding based on button size
    $button_padding = '0.375rem 0.75rem'; // Default
    $button_font_size = $font;

    if ($button_size === 'sm') {
        $button_padding = '0.25rem 0.5rem';
        $button_font_size = 'calc(' . $font . ' * 0.875)';
    } else if ($button_size === 'lg') {
        $button_padding = '0.5rem 1rem';
        $button_font_size = 'calc(' . $font . ' * 1.25)';
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
            border: 1px solid {$button_border};
            padding: {$button_padding};
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            font-size: {$button_font_size};
            text-decoration: none;
            cursor: pointer;
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
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="<?php echo htmlspecialchars($primary_color); ?>" title="Choose primary color">
                                    <input type="text" class="form-control" aria-label="Primary color hex value" value="<?php echo htmlspecialchars($primary_color); ?>" id="primary_color_text" oninput="document.getElementById('primary_color').value = this.value">
                                </div>
                                <div class="form-text">Used for navbar, buttons, and links</div>
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
                                    <option value="" <?php echo $button_size === '' ? 'selected' : ''; ?>>Default</option>
                                    <option value="sm" <?php echo $button_size === 'sm' ? 'selected' : ''; ?>>Small</option>
                                    <option value="lg" <?php echo $button_size === 'lg' ? 'selected' : ''; ?>>Large</option>
                                </select>
                                <div class="form-text">Choose button size (Bootstrap classes: none, btn-sm, btn-lg)</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="button_type" class="form-label">Button Type</label>
                                <select class="form-select" id="button_type" name="button_type">
                                    <optgroup label="Solid Buttons">
                                        <option value="primary" <?php echo $button_type === 'primary' ? 'selected' : ''; ?>>Primary</option>
                                        <option value="secondary" <?php echo $button_type === 'secondary' ? 'selected' : ''; ?>>Secondary</option>
                                        <option value="success" <?php echo $button_type === 'success' ? 'selected' : ''; ?>>Success</option>
                                        <option value="danger" <?php echo $button_type === 'danger' ? 'selected' : ''; ?>>Danger</option>
                                        <option value="warning" <?php echo $button_type === 'warning' ? 'selected' : ''; ?>>Warning</option>
                                        <option value="info" <?php echo $button_type === 'info' ? 'selected' : ''; ?>>Info</option>
                                        <option value="light" <?php echo $button_type === 'light' ? 'selected' : ''; ?>>Light</option>
                                        <option value="dark" <?php echo $button_type === 'dark' ? 'selected' : ''; ?>>Dark</option>
                                        <option value="link" <?php echo $button_type === 'link' ? 'selected' : ''; ?>>Link</option>
                                    </optgroup>
                                    <optgroup label="Outline Buttons">
                                        <option value="outline-primary" <?php echo $button_type === 'outline-primary' ? 'selected' : ''; ?>>Outline Primary</option>
                                        <option value="outline-secondary" <?php echo $button_type === 'outline-secondary' ? 'selected' : ''; ?>>Outline Secondary</option>
                                        <option value="outline-success" <?php echo $button_type === 'outline-success' ? 'selected' : ''; ?>>Outline Success</option>
                                        <option value="outline-danger" <?php echo $button_type === 'outline-danger' ? 'selected' : ''; ?>>Outline Danger</option>
                                        <option value="outline-warning" <?php echo $button_type === 'outline-warning' ? 'selected' : ''; ?>>Outline Warning</option>
                                        <option value="outline-info" <?php echo $button_type === 'outline-info' ? 'selected' : ''; ?>>Outline Info</option>
                                        <option value="outline-light" <?php echo $button_type === 'outline-light' ? 'selected' : ''; ?>>Outline Light</option>
                                        <option value="outline-dark" <?php echo $button_type === 'outline-dark' ? 'selected' : ''; ?>>Outline Dark</option>
                                    </optgroup>
                                </select>
                                <div class="form-text">Choose button type (Bootstrap classes: btn-* or btn-outline-*)</div>
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

    const primaryColorText = document.getElementById('primary_color_text');
    const bgColorText = document.getElementById('bg_color_text');
    const cardBgColorText = document.getElementById('card_bg_color_text');
    const textColorText = document.getElementById('text_color_text');

    const resetButton = document.getElementById('reset_colors');

    // Initialize preview with current values on page load
    updatePreview();

    // Update text input when color picker changes
    primaryColorInput.addEventListener('input', function() {
        primaryColorText.value = this.value;
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
    primaryColorText.addEventListener('input', function() {
        primaryColorInput.value = this.value;
        updatePreview();
    });

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
        buttonSizeInput.value = '';
        buttonTypeInput.value = 'primary';

        primaryColorText.value = '#0d6efd';
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

        // Update button styles based on button type
        let buttonBg = primaryColorInput.value;
        let buttonText = 'white';
        let buttonBorder = primaryColorInput.value;

        // Handle outline button types
        if (buttonTypeInput.value.startsWith('outline-')) {
            buttonBg = 'transparent';
            buttonText = primaryColorInput.value;
            buttonBorder = primaryColorInput.value;
        }

        // Handle non-primary button types
        if (buttonTypeInput.value !== 'primary' && buttonTypeInput.value !== 'outline-primary') {
            // For simplicity, we'll just use some common colors for the preview
            const buttonType = buttonTypeInput.value.replace('outline-', '');
            switch (buttonType) {
                case 'secondary':
                    buttonBg = buttonTypeInput.value === 'secondary' ? '#6c757d' : 'transparent';
                    buttonText = buttonTypeInput.value === 'secondary' ? 'white' : '#6c757d';
                    buttonBorder = '#6c757d';
                    break;
                case 'success':
                    buttonBg = buttonTypeInput.value === 'success' ? '#198754' : 'transparent';
                    buttonText = buttonTypeInput.value === 'success' ? 'white' : '#198754';
                    buttonBorder = '#198754';
                    break;
                case 'danger':
                    buttonBg = buttonTypeInput.value === 'danger' ? '#dc3545' : 'transparent';
                    buttonText = buttonTypeInput.value === 'danger' ? 'white' : '#dc3545';
                    buttonBorder = '#dc3545';
                    break;
                case 'warning':
                    buttonBg = buttonTypeInput.value === 'warning' ? '#ffc107' : 'transparent';
                    buttonText = buttonTypeInput.value === 'warning' ? 'black' : '#ffc107';
                    buttonBorder = '#ffc107';
                    break;
                case 'info':
                    buttonBg = buttonTypeInput.value === 'info' ? '#0dcaf0' : 'transparent';
                    buttonText = buttonTypeInput.value === 'info' ? 'black' : '#0dcaf0';
                    buttonBorder = '#0dcaf0';
                    break;
                case 'light':
                    buttonBg = buttonTypeInput.value === 'light' ? '#f8f9fa' : 'transparent';
                    buttonText = buttonTypeInput.value === 'light' ? 'black' : '#f8f9fa';
                    buttonBorder = '#f8f9fa';
                    break;
                case 'dark':
                    buttonBg = buttonTypeInput.value === 'dark' ? '#212529' : 'transparent';
                    buttonText = buttonTypeInput.value === 'dark' ? 'white' : '#212529';
                    buttonBorder = '#212529';
                    break;
                case 'link':
                    buttonBg = 'transparent';
                    buttonText = primaryColorInput.value;
                    buttonBorder = 'transparent';
                    break;
            }
        }

        // Apply button styles
        previewButton.style.backgroundColor = buttonBg;
        previewButton.style.color = buttonText;
        previewButton.style.borderColor = buttonBorder;

        // Update button size
        let buttonPadding = '0.375rem 0.75rem'; // Default
        let buttonFontSize = fontSizeInput.value;

        if (buttonSizeInput.value === 'sm') {
            buttonPadding = '0.25rem 0.5rem';
            buttonFontSize = `calc(${fontSizeInput.value} * 0.875)`;
        } else if (buttonSizeInput.value === 'lg') {
            buttonPadding = '0.5rem 1rem';
            buttonFontSize = `calc(${fontSizeInput.value} * 1.25)`;
        }

        previewButton.style.padding = buttonPadding;
        previewButton.style.fontSize = buttonFontSize;
    }
});
</script>

<?php require_once 'footer.php'; ?>
