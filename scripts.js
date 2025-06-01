    // Update preview in real-time as colors are changed
    document.addEventListener('DOMContentLoaded', function() {
    const primaryColorInput = document.getElementById('primary_color');
    const bgColorInput = document.getElementById('bg_color');
    const cardBgColorInput = document.getElementById('card_bg_color');
    const textColorInput = document.getElementById('text_color');
    const fontSizeInput = document.getElementById('font_size');
    const globalFontSizePercentageInput = document.getElementById('global_font_size_percentage');
    const fontFamilyInput = document.getElementById('font_family');
    const buttonSizeInput = document.getElementById('button_size');
    const buttonTypeInput = document.getElementById('button_type');

    // Bootstrap color override inputs
    const overrideBootstrapColorsInput = document.getElementById('override_bootstrap_colors');
    const bootstrapColorsSection = document.getElementById('bootstrap_colors_section');
    const bootstrapPrimaryColorInput = document.getElementById('bootstrap_primary_color');
    const bootstrapSecondaryColorInput = document.getElementById('bootstrap_secondary_color');
    const bootstrapSuccessColorInput = document.getElementById('bootstrap_success_color');
    const bootstrapDangerColorInput = document.getElementById('bootstrap_danger_color');
    const bootstrapWarningColorInput = document.getElementById('bootstrap_warning_color');
    const bootstrapInfoColorInput = document.getElementById('bootstrap_info_color');
    const bootstrapLightColorInput = document.getElementById('bootstrap_light_color');
    const bootstrapDarkColorInput = document.getElementById('bootstrap_dark_color');

    const bgColorText = document.getElementById('bg_color_text');
    const cardBgColorText = document.getElementById('card_bg_color_text');
    const textColorText = document.getElementById('text_color_text');

    // Bootstrap color override text inputs
    const bootstrapPrimaryColorText = document.getElementById('bootstrap_primary_color_text');
    const bootstrapSecondaryColorText = document.getElementById('bootstrap_secondary_color_text');
    const bootstrapSuccessColorText = document.getElementById('bootstrap_success_color_text');
    const bootstrapDangerColorText = document.getElementById('bootstrap_danger_color_text');
    const bootstrapWarningColorText = document.getElementById('bootstrap_warning_color_text');
    const bootstrapInfoColorText = document.getElementById('bootstrap_info_color_text');
    const bootstrapLightColorText = document.getElementById('bootstrap_light_color_text');
    const bootstrapDarkColorText = document.getElementById('bootstrap_dark_color_text');

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

    // Update preview when global font size percentage changes
    globalFontSizePercentageInput.addEventListener('input', function() {
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

    // Toggle bootstrap color override section
    overrideBootstrapColorsInput.addEventListener('change', function() {
    bootstrapColorsSection.classList.toggle('d-none', !this.checked);
    updatePreview();
});

    // Update preview when bootstrap color inputs change
    bootstrapPrimaryColorInput.addEventListener('input', function() {
    bootstrapPrimaryColorText.value = this.value;
    updatePreview();
});

    bootstrapSecondaryColorInput.addEventListener('input', function() {
    bootstrapSecondaryColorText.value = this.value;
    updatePreview();
});

    bootstrapSuccessColorInput.addEventListener('input', function() {
    bootstrapSuccessColorText.value = this.value;
    updatePreview();
});

    bootstrapDangerColorInput.addEventListener('input', function() {
    bootstrapDangerColorText.value = this.value;
    updatePreview();
});

    bootstrapWarningColorInput.addEventListener('input', function() {
    bootstrapWarningColorText.value = this.value;
    updatePreview();
});

    bootstrapInfoColorInput.addEventListener('input', function() {
    bootstrapInfoColorText.value = this.value;
    updatePreview();
});

    bootstrapLightColorInput.addEventListener('input', function() {
    bootstrapLightColorText.value = this.value;
    updatePreview();
});

    bootstrapDarkColorInput.addEventListener('input', function() {
    bootstrapDarkColorText.value = this.value;
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

    // Update color picker when bootstrap color text inputs change
    bootstrapPrimaryColorText.addEventListener('input', function() {
    bootstrapPrimaryColorInput.value = this.value;
    updatePreview();
});

    bootstrapSecondaryColorText.addEventListener('input', function() {
    bootstrapSecondaryColorInput.value = this.value;
    updatePreview();
});

    bootstrapSuccessColorText.addEventListener('input', function() {
    bootstrapSuccessColorInput.value = this.value;
    updatePreview();
});

    bootstrapDangerColorText.addEventListener('input', function() {
    bootstrapDangerColorInput.value = this.value;
    updatePreview();
});

    bootstrapWarningColorText.addEventListener('input', function() {
    bootstrapWarningColorInput.value = this.value;
    updatePreview();
});

    bootstrapInfoColorText.addEventListener('input', function() {
    bootstrapInfoColorInput.value = this.value;
    updatePreview();
});

    bootstrapLightColorText.addEventListener('input', function() {
    bootstrapLightColorInput.value = this.value;
    updatePreview();
});

    bootstrapDarkColorText.addEventListener('input', function() {
    bootstrapDarkColorInput.value = this.value;
    updatePreview();
});

    // Reset to default colors, font size, font family, and button settings
    resetButton.addEventListener('click', function() {
    primaryColorInput.value = '#0d6efd';
    bgColorInput.value = '#f8f9fa';
    cardBgColorInput.value = '#ffffff';
    textColorInput.value = '#212529';
    fontSizeInput.value = '16px';
    globalFontSizePercentageInput.value = '100%';
    fontFamilyInput.value = 'Quicksand';
    buttonSizeInput.value = ''; // Default size
    buttonTypeInput.value = 'primary'; // Default type

    // Reset bootstrap color override settings
    overrideBootstrapColorsInput.checked = false;
    bootstrapColorsSection.classList.add('d-none');
    bootstrapPrimaryColorInput.value = '#0d6efd';
    bootstrapSecondaryColorInput.value = '#6c757d';
    bootstrapSuccessColorInput.value = '#198754';
    bootstrapDangerColorInput.value = '#dc3545';
    bootstrapWarningColorInput.value = '#ffc107';
    bootstrapInfoColorInput.value = '#0dcaf0';
    bootstrapLightColorInput.value = '#f8f9fa';
    bootstrapDarkColorInput.value = '#212529';

    bgColorText.value = '#f8f9fa';
    cardBgColorText.value = '#ffffff';
    textColorText.value = '#212529';

    // Reset bootstrap color override text inputs
    bootstrapPrimaryColorText.value = '#0d6efd';
    bootstrapSecondaryColorText.value = '#6c757d';
    bootstrapSuccessColorText.value = '#198754';
    bootstrapDangerColorText.value = '#dc3545';
    bootstrapWarningColorText.value = '#ffc107';
    bootstrapInfoColorText.value = '#0dcaf0';
    bootstrapLightColorText.value = '#f8f9fa';
    bootstrapDarkColorText.value = '#212529';

    updatePreview();

    // Submit the form to save the changes
    document.querySelector('form').submit();
});

    function updatePreview() {
    const previewContainer = document.querySelector('.site-preview-container');
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

    previewContainer.style.fontSize = globalFontSizePercentageInput.value;

    preview.style.backgroundColor = bgColorInput.value;
    preview.style.color = textColorInput.value;
    preview.style.fontSize = fontSizeInput.value;
    preview.style.fontFamily = `'${fontFamilyInput.value}', sans-serif`;

    // Apply primary color to navbar (or override if bootstrap colors are overridden)
    if (overrideBootstrapColorsInput.checked) {
    // Create a style element for bootstrap color overrides in the preview
    let previewStyleEl = document.getElementById('preview-bootstrap-overrides');
    if (!previewStyleEl) {
    previewStyleEl = document.createElement('style');
    previewStyleEl.id = 'preview-bootstrap-overrides';
    document.head.appendChild(previewStyleEl);
}

    // Update the style element with the overridden bootstrap colors
    previewStyleEl.textContent = `
                .preview-navbar.bg-primary { background-color: ${bootstrapPrimaryColorInput.value} !important; }
                .preview-navbar.bg-secondary { background-color: ${bootstrapSecondaryColorInput.value} !important; }
                .preview-navbar.bg-success { background-color: ${bootstrapSuccessColorInput.value} !important; }
                .preview-navbar.bg-danger { background-color: ${bootstrapDangerColorInput.value} !important; }
                .preview-navbar.bg-warning { background-color: ${bootstrapWarningColorInput.value} !important; }
                .preview-navbar.bg-info { background-color: ${bootstrapInfoColorInput.value} !important; }
                .preview-navbar.bg-light { background-color: ${bootstrapLightColorInput.value} !important; }
                .preview-navbar.bg-dark { background-color: ${bootstrapDarkColorInput.value} !important; }
            `;

    // Add bootstrap classes to the preview navbar for demonstration
    previewNavbar.className = 'preview-navbar bg-primary';
    // Set the navbar background color directly to ensure it updates
    previewNavbar.style.backgroundColor = bootstrapPrimaryColorInput.value;

    // Force a repaint to ensure the navbar updates
    previewNavbar.style.display = 'none';
    previewNavbar.offsetHeight; // Trigger a reflow
    previewNavbar.style.display = 'block';
} else {
    // Remove the style element if bootstrap colors are not overridden
    const previewStyleEl = document.getElementById('preview-bootstrap-overrides');
    if (previewStyleEl) {
    document.head.removeChild(previewStyleEl);
}

    // Set the navbar background color directly
    previewNavbar.style.backgroundColor = primaryColorInput.value;

    // Force a repaint to ensure the navbar updates
    previewNavbar.style.display = 'none';
    previewNavbar.offsetHeight; // Trigger a reflow
    previewNavbar.style.display = 'block';
}

    previewCard.style.backgroundColor = cardBgColorInput.value;

    // Force a repaint to ensure the card updates
    previewCard.style.display = 'none';
    previewCard.offsetHeight; // Trigger a reflow
    previewCard.style.display = 'block';

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
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapPrimaryColorInput.value : primaryColorInput.value;
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapPrimaryColorInput.value : primaryColorInput.value}`;
    break;
    case 'secondary':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapSecondaryColorInput.value : '#6c757d';
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapSecondaryColorInput.value : '#6c757d'}`;
    break;
    case 'success':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapSuccessColorInput.value : '#198754';
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapSuccessColorInput.value : '#198754'}`;
    break;
    case 'danger':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapDangerColorInput.value : '#dc3545';
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapDangerColorInput.value : '#dc3545'}`;
    break;
    case 'warning':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapWarningColorInput.value : '#ffc107';
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapWarningColorInput.value : '#ffc107'}`;
    break;
    case 'info':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapInfoColorInput.value : '#0dcaf0';
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapInfoColorInput.value : '#0dcaf0'}`;
    break;
    case 'light':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapLightColorInput.value : '#f8f9fa';
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapLightColorInput.value : '#f8f9fa'}`;
    break;
    case 'dark':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapDarkColorInput.value : '#212529';
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapDarkColorInput.value : '#212529'}`;
    break;
    default:
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapPrimaryColorInput.value : primaryColorInput.value;
    buttonBorder = `1px solid ${overrideBootstrapColorsInput.checked ? bootstrapPrimaryColorInput.value : primaryColorInput.value}`;
}
} else {
    // Handle solid button types
    switch (buttonTypeInput.value) {
    case 'primary':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapPrimaryColorInput.value : primaryColorInput.value;
    break;
    case 'secondary':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapSecondaryColorInput.value : '#6c757d';
    break;
    case 'success':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapSuccessColorInput.value : '#198754';
    break;
    case 'danger':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapDangerColorInput.value : '#dc3545';
    break;
    case 'warning':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapWarningColorInput.value : '#ffc107';
    buttonText = '#212529';
    break;
    case 'info':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapInfoColorInput.value : '#0dcaf0';
    buttonText = '#212529';
    break;
    case 'light':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapLightColorInput.value : '#f8f9fa';
    buttonText = '#212529';
    break;
    case 'dark':
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapDarkColorInput.value : '#212529';
    break;
    case 'link':
    buttonBg = 'transparent';
    buttonText = overrideBootstrapColorsInput.checked ? bootstrapPrimaryColorInput.value : primaryColorInput.value;
    buttonBorder = 'none';
    break;
    default:
    buttonBg = overrideBootstrapColorsInput.checked ? bootstrapPrimaryColorInput.value : primaryColorInput.value;
}
}

    previewButton.style.backgroundColor = buttonBg;
    previewButton.style.color = buttonText;
    previewButton.style.border = buttonBorder;

    // Force a repaint to ensure the button updates
    previewButton.style.display = 'none';
    previewButton.offsetHeight; // Trigger a reflow
    previewButton.style.display = 'inline-block';
}
});
