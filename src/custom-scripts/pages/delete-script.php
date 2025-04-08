<?php
if (!defined('DELETE_SCRIPT_ACTION')) {
    define('DELETE_SCRIPT_ACTION', 'delete_script');
}

if (!defined('DELETE_SCRIPT_NONCE')) {
    define('DELETE_SCRIPT_NONCE', DELETE_SCRIPT_ACTION . '_nonce');
}


add_action('admin_post_' . DELETE_SCRIPT_ACTION, 'handle_delete_entity');

function handle_delete_entity()
{
    if (!isset($_GET['entity_id'])) {
        throw new Exception('There was no entity id provided');
    }
    
    $entity_id = sanitize_text_field($_GET['entity_id']);

    if (!current_user_can('manage_options')) {
        wp_die('No tienes permisos.');
    }

    $nonce_action = DELETE_SCRIPT_NONCE . $entity_id;
    if (!isset($_GET[DELETE_SCRIPT_NONCE]) || !wp_verify_nonce($_GET[DELETE_SCRIPT_NONCE], $nonce_action)) {
        wp_die('Security check failed.');
    }

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        // Throw an error if the request method is not GET
        wp_die('Invalid request method.');
    }

    
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME, []);
    if (isset($options['entities'][$entity_id])) {
        unset($options['entities'][$entity_id]);
        update_option(WP_CUSTOM_SCRIPTS_OPTION_NAME, $options);
    }

    wp_redirect(admin_url('admin.php?page='. WP_CUSTOM_SCRIPTS_OPTION_NAME .'&deleted=1'));
    exit;

}