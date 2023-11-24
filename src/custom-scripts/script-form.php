<?php
include(plugin_dir_path(__FILE__) . 'constants.php');

add_action('admin_init', 'custom_scripts_settings');

function custom_scripts_settings()
{
    // Register settings
    register_setting(CUSTOM_SCRIPTS_SETTINGS_GROUP, 'custom_scripts', 'sanitize_custom_scripts');

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

/**
 * Form UI
 */
function get_entity_data($options)
{
    // Check if in edit mode or create mode
    $edit_mode = isset($_GET['edit_entity']) && $_GET['edit_entity'] !== 'new';
    $entities = isset($options['entities']) ? $options['entities'] : array();

    // Get the entity index if in edit mode
    $entity_index = $edit_mode ? intval($_GET['edit_entity']) : 0;

    $label = $edit_mode && isset($entities[$entity_index]['script_label'])
        ? esc_attr($entities[$entity_index]['script_label'])
        : '';

    $code = $edit_mode && isset($options['entities'][$entity_index]['script_code'])
        ? esc_attr($options['entities'][$entity_index]['script_code'])
        : '';

    $enabled = $edit_mode && isset($options['entities'][$entity_index]['script_enabled'])
        ? checked(1, $options['entities'][$entity_index]['script_enabled'], false)
        : '';

    // Get the entity data based on edit mode and entity index
    $entity_data = array(
        'edit_mode' => $edit_mode,
        'entity_index' => $entity_index,
        'label' => $label,
        'code' => $code,
        'enabled' => $enabled,
    );

    return $entity_data;
}

function script_label_callback()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $entity_data = get_entity_data($options);
    $entity_id = $entity_data['entity_index'] === 'new' ? uniqid('script_') : $entity_data['entity_index'];
    $entity_name = 'custom_scripts[entities][' . $entity_id . '][script_label]'
?>
    <input type="text" name="<?php echo $entity_name; ?>" value="<?php echo $entity_data['label'] ?> " />
<?php
}

function script_code_callback()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $entity_data = get_entity_data($options);
    $entity_id = $entity_data['entity_index'] === 'new' ? uniqid('script_') : $entity_data['entity_index'];
    $entity_name = 'custom_scripts[entities][' . $entity_id . '][script_code]';
?>
    <textarea name="<?php echo $entity_name; ?>" rows="5" cols="50"><?php echo $entity_data['code'] ?></textarea>
<?php
}

function script_enabled_callback()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $entity_data = get_entity_data($options);
    $entity_id = $entity_data['entity_index'] === 'new' ? uniqid('script_') : $entity_data['entity_index'];
    $entity_name = 'custom_scripts[entities][' . $entity_id . '][script_enabled]';
?>
    <input type="checkbox" name="<?php echo $entity_name; ?>" value="1" <?php echo $entity_data['enabled'] ?> />
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

    // Generate a unique ID (GUID) for the new entity
    $entity_id = wp_generate_uuid4();

    // Store the GUID in the entities array
    $sanitized_input['entities']['new_entity_id'] = $entity_id;

    return $sanitized_input;
}
