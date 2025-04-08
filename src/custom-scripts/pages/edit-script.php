<?php
if (!defined('EDIT_SCRIPT_ACTION')) {
    define('EDIT_SCRIPT_ACTION', 'edit_script');
}

if (!defined('EDIT_SCRIPT_NONCE')) {
    define('EDIT_SCRIPT_NONCE', SAVE_SCRIPT_ACTION . '_nonce');
}


// Assuming this is in your main plugin file or where you include your settings.php
add_action('admin_post_' . EDIT_SCRIPT_ACTION, 'handle_edit_entity');

function handle_edit_entity()
{
    if (!current_user_can('manage_options')) {
        wp_die('No tienes permisos.');
    }

    if (!isset($_POST[EDIT_SCRIPT_NONCE]) || !wp_verify_nonce($_POST[EDIT_SCRIPT_NONCE], EDIT_SCRIPT_NONCE)) {
        wp_die('Security check failed.');
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        // Throw an error if the request method is not POST
        wp_die('Invalid request method.');
    }

    // Process the form submission
    $post_data = $_POST[WP_CUSTOM_SCRIPTS_OPTION_NAME];
    
    $entity = $post_data['entities'];
    $value_id = $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['id']];
    $value_label = $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['label']];
    $value_code = $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['code']];
    $value_enabled = isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']] : 0;
    
    $new_entity = array(
        CUSTOM_SCRIPTS_ENTITY_FIELD['id'] => $value_id,
        CUSTOM_SCRIPTS_ENTITY_FIELD['label'] => $value_label,
        CUSTOM_SCRIPTS_ENTITY_FIELD['code'] => $value_code,
        CUSTOM_SCRIPTS_ENTITY_FIELD['enabled'] => $value_enabled,
    );

    // Get existing options
    $default_value = wp_parse_args(array('entities' => array()));
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);

    if (empty($options)) {
        $options = $default_value;
    }

    // Append the new entity to the existing array
    $options['entities'][$value_id] = $new_entity;

    // Update the options
    update_option(WP_CUSTOM_SCRIPTS_OPTION_NAME, $options);

    // Redirect to the List page
    wp_redirect(admin_url('admin.php?page=custom_scripts'));

    // Make sure to call exit() after wp_redirect to stop further execution
    exit();
}

// Admin page content
function edit_entity_page($entity_id)
{
    $method = "post";
    $action = esc_url(admin_url('admin-post.php?action=' . EDIT_SCRIPT_ACTION));
    ?>
    <div class="wrap">
        <h2>Editar Script</h2>
        <form method="<?= $method ?>" action="<?= $action ?>">
            <?php
            settings_fields(CUSTOM_SCRIPTS_SETTINGS_GROUP);
            wp_nonce_field(EDIT_SCRIPT_NONCE, EDIT_SCRIPT_NONCE);
            ?>
            <!-- Add a hidden field to store the entity index -->
            <input type="hidden" name="entity_id" value="<?= $entity_id ?>" />
            <input type="hidden" id="action" name="action" value="<?= EDIT_SCRIPT_ACTION ?>">
            <?php
            // Pass the entity to the form rendering function
            do_settings_sections(WP_CUSTOM_SCRIPTS_OPTION_NAME);
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
