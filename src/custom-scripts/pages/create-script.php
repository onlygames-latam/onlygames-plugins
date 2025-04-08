<?php

if (!defined('SAVE_SCRIPT_ACTION')) {
    define('SAVE_SCRIPT_ACTION', 'save_script');
}

if (!defined('SAVE_SCRIPT_NONCE')) {
    define('SAVE_SCRIPT_NONCE', SAVE_SCRIPT_ACTION . '_nonce');
}

// Assuming this is in your main plugin file or where you include your settings.php
add_action('admin_post_' . SAVE_SCRIPT_ACTION, 'handle_create_entity');

function handle_create_entity()
{
    if (!current_user_can('manage_options')) {
        wp_die('No tienes permisos.');
    }

    if (!isset($_POST[SAVE_SCRIPT_NONCE]) || !wp_verify_nonce($_POST[SAVE_SCRIPT_NONCE], SAVE_SCRIPT_NONCE)) {
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
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $default_value = wp_parse_args(array('entities' => array()));
    
    if (empty($options)) {
        $options = $default_value;
    }
    
    // Append the new entity to the existing array
    $options['entities'][$value_id] = $new_entity;

    // Update the options
    update_option(WP_CUSTOM_SCRIPTS_OPTION_NAME, $options);

    // Redirect to the List page
    wp_redirect(admin_url('admin.php?page=' . WP_CUSTOM_SCRIPTS_OPTION_NAME));

    // Make sure to call exit() after wp_redirect to stop further execution
    exit();

}

function create_entity_page()
{
    $method = "post";
    $action = esc_url(admin_url('admin-post.php?action=' . SAVE_SCRIPT_ACTION));
    ?>
    <div class="wrap">
        <h2>Crear nuevo Script</h2>
        <form method="<?= $method ?>" action="<?= $action ?>">

            <?php
            settings_fields(CUSTOM_SCRIPTS_SETTINGS_GROUP);
            wp_nonce_field(SAVE_SCRIPT_NONCE, SAVE_SCRIPT_NONCE);
            ?>
            <input type="hidden" id="action" name="action" value="<?= SAVE_SCRIPT_ACTION ?>">

            <?php
            // No need to pass an entity for creation
            do_settings_sections(WP_CUSTOM_SCRIPTS_OPTION_NAME);
            // submit_button('Guardar nuevo Script', 'primary', 'save_script', false);
            submit_button('Guardar nuevo Script', 'primary');
            ?>
        </form>
    </div>
    <?php
}
