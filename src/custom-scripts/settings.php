<?php
add_action('admin_init', 'custom_scripts_settings');

function custom_scripts_settings()
{
    register_setting('custom-scripts-group', 'custom_scripts', 'sanitize_custom_scripts');

    add_settings_section(
        'custom-scripts-section',
        'Custom Scripts Settings',
        'custom_scripts_section_callback',
        'custom-scripts'
    );

    add_settings_field(
        'script_label',
        'Nombre',
        'script_label_callback',
        'custom-scripts',
        'custom-scripts-section'
    );

    add_settings_field(
        'script_code',
        'Codigo',
        'script_code_callback',
        'custom-scripts',
        'custom-scripts-section'
    );

    add_settings_field(
        'script_enabled',
        'Habilitado',
        'script_enabled_callback',
        'custom-scripts',
        'custom-scripts-section'
    );
}

function custom_scripts_section_callback()
{
    echo '<p>Add and manage custom scripts to be included in the footer.</p>';
}

function script_label_callback()
{
    $options = get_option('custom_scripts');
    $indexKey = 'script_label';
    $value = isset($options[$indexKey][0]) ? esc_attr($options[$indexKey][0]) : '';
    echo '<input type="text" id="' . $indexKey . '" name="custom_scripts[' . $indexKey . '][]" value="' . $value . '" />';
}

function script_code_callback()
{
    $options = get_option('custom_scripts');
    $indexKey = 'script_code';
    $value = isset($options[$indexKey][0]) ? esc_attr($options[$indexKey][0]) : '';
?>
    <textarea id="<?php echo $indexKey; ?>" name="custom_scripts[<?php echo $indexKey; ?>][]" rows="5" cols="50"><?php echo $value; ?></textarea>
    <div style="margin-top: 8px">
        <p>Recuerda que <strong>No debes</strong> envolver tu codigo dentro de los tags <code>&lt;script&gt;</code> y <code>&lt;/script&gt;</code></p>
    </div>
<?php
}

function script_enabled_callback()
{
    $options = get_option('custom_scripts');
    $indexKey = 'script_enabled';
    $value = isset($options[$indexKey][0]) ? checked(1, $options[$indexKey][0], false) : '';

    echo '<input type="checkbox" id="' . $indexKey . '" name="custom_scripts[' . $indexKey . '][]" value="1" ' . $value . ' />';
}

function sanitize_custom_scripts($input)
{
    $sanitized_input = array();

    foreach ($input as $key => $value) {
        if (is_array($value)) {
            // If the value is an array, apply stripslashes recursively
            $sanitized_input[$key] = array_map('stripslashes', $value);
        } else {
            // If the value is a string, apply stripslashes
            $sanitized_input[$key] = strip_tags(stripslashes($value));
        }
    }

    return $sanitized_input;
}
