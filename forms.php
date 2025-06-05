<?php
function validate_font_size_percentage($new_font_size_percentage) {
    // Validate input format
    if (!preg_match('/^\d+%$/', $new_font_size_percentage)) {
        return false;
    }
    return true;
}

function validate_button_type($new_button_type) {
    // Validate input format
    if (!preg_match('/^\w+$/', $new_button_type)) {
        return false;
    }
    return true;
}

if (validate_button_type($new_button_type)) {
    set_button_type($new_button_type);
}


