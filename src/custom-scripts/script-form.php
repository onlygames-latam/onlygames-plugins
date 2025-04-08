<?php
include(plugin_dir_path(__FILE__) . 'constants.php');

add_action('admin_init', 'custom_scripts_settings');

function custom_scripts_settings()
{
    // Register settings
    register_setting(CUSTOM_SCRIPTS_SETTINGS_GROUP, 'custom_scripts', 'sanitize_custom_scripts');

    add_settings_section(
        'custom_scripts-section',
        'Custom Scripts Settings',
        'custom_scripts_section_callback',
        WP_CUSTOM_SCRIPTS_OPTION_NAME
    );

    add_settings_field(
        CUSTOM_SCRIPTS_ENTITY_FIELD['id'],
        'ID',
        'script_id_callback',
        WP_CUSTOM_SCRIPTS_OPTION_NAME,
        'custom_scripts-section'
    );

    add_settings_field(
        CUSTOM_SCRIPTS_ENTITY_FIELD['label'],
        'Nombre',
        'script_label_callback',
        WP_CUSTOM_SCRIPTS_OPTION_NAME,
        'custom_scripts-section'
    );

    add_settings_field(
        CUSTOM_SCRIPTS_ENTITY_FIELD['code'],
        'Codigo',
        'script_code_callback',
        WP_CUSTOM_SCRIPTS_OPTION_NAME,
        'custom_scripts-section'
    );

    add_settings_field(
        CUSTOM_SCRIPTS_ENTITY_FIELD['enabled'],
        'Habilitado',
        'script_enabled_callback',
        WP_CUSTOM_SCRIPTS_OPTION_NAME,
        'custom_scripts-section'
    );
}

function custom_scripts_section_callback()
{
    echo '<p>Add and manage custom scripts to be included in the footer.</p>';
}

/**
 * Form UI
 */
function get_entity_data($options)
{
    $label_id = CUSTOM_SCRIPTS_ENTITY_FIELD['id'];
    $label_label = CUSTOM_SCRIPTS_ENTITY_FIELD['label'];
    $label_code = CUSTOM_SCRIPTS_ENTITY_FIELD['code'];
    $label_enabled = CUSTOM_SCRIPTS_ENTITY_FIELD['enabled'];

    // Check if in edit mode or create mode
    $edit_mode = is_edit_mode();
   
    $defaultFormValues = array(
        // Generate a unique ID (GUID) for the new entity
        $label_id => wp_generate_uuid4(),
        $label_label => "",
        $label_code => "",
        $label_enabled => "",
    );

    if ($edit_mode) {
        $entity_id = $_GET['edit_entity'];
        $entity = isset($options['entities']) ? $options['entities'][$entity_id] : $defaultFormValues;
    } else {
        $entity = $defaultFormValues;
    }


    // Get the entity index if in edit mode
    $id = $entity[$label_id];
    $label = esc_attr($entity[$label_label]);
    $code = esc_attr($entity[$label_code]);
    $enabled = checked(1, $entity[$label_enabled], false);

    // Get the entity data based on edit mode and entity index
    $entity_data = array(
        'edit_mode' => $edit_mode,
        $label_id => $id,
        $label_label => $label,
        $label_code => $code,
        $label_enabled => $enabled,
    );

    return $entity_data;
}

function script_id_callback()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $entity_data = get_entity_data($options);
    $field_name = CUSTOM_SCRIPTS_ENTITY_FIELD["id"];
    $entity_name = $entity_data[$field_name];
    $entity_id = $entity_name;
    
    $field_id = CUSTOM_SCRIPTS_ENTITY_FIELD["id"];
    $entity_name = WP_CUSTOM_SCRIPTS_OPTION_NAME . "[entities][" . $field_id . "]";
?>
    <input readonly name="<?php echo $entity_name; ?>" value="<?php echo esc_attr($entity_id); ?>" />
<?php
}

function script_label_callback()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);

    $entity_data = get_entity_data($options);
    $field_name = CUSTOM_SCRIPTS_ENTITY_FIELD["label"];
    $entity_name = WP_CUSTOM_SCRIPTS_OPTION_NAME . "[entities][" . $field_name  . "]";
?>
    <input type="text" name="<?php echo $entity_name; ?>" value="<?php echo $entity_data[$field_name] ?> " />
<?php
}

function script_code_callback()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $entity_data = get_entity_data($options);
    $field_name = CUSTOM_SCRIPTS_ENTITY_FIELD["code"];
    $entity_name = WP_CUSTOM_SCRIPTS_OPTION_NAME . "[entities][" . $field_name  . "]";
?>
    <textarea name="<?php echo $entity_name; ?>" rows="5" cols="50"><?php echo $entity_data[$field_name] ?></textarea>
<?php
}

function script_enabled_callback()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $entity_data = get_entity_data($options);
    $field_name = CUSTOM_SCRIPTS_ENTITY_FIELD["enabled"];
    $entity_name = WP_CUSTOM_SCRIPTS_OPTION_NAME . "[entities][" . $field_name  . "]";
    $default_value = false; // Set your default value here

    $entity = $entity_data[$field_name];
    $checked = isset($entity) ? checked(1, $entity, false) : checked(1, $default_value, false);
?>
    <input type="checkbox" name="<?php echo $entity_name; ?>" value="1" <?php echo $checked; ?> />
<?php
}


function sanitize_custom_scripts($input)
{
    $sanitized_input = array();

    foreach ($input as $key => $value) {
        if (is_array($value)) {
            // If the value is an array, apply stripslashes recursively
            $sanitized_input[$key] = array_map(function ($v) {
                return is_array($v) ? array_map('stripslashes', $v) : stripslashes($v);
            }, $value);
        } else {
            // If the value is a string, apply stripslashes
            $sanitized_input[$key] = strip_tags(stripslashes($value));
        }
    }

    return $sanitized_input;
}
