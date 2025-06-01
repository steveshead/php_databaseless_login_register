<?php
function validate_font_size_percentage($new_font_size_percentage) {
    // Validate input format
    if (!preg_match('/^\d+%$/', $new_font_size_percentage)) {
        return false;
    }
    return true;
}