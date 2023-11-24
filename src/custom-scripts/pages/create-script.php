<?php

if (!defined('SAVE_SCRIPT_ACTION')) {
    define('SAVE_SCRIPT_ACTION', 'save_script');
}

// Assuming this is in your main plugin file or where you include your settings.php
add_action('admin_post_' . SAVE_SCRIPT_ACTION, 'handle_form_submission');

function handle_form_submission($post_data)
{
    if (!isset($_POST['save_script_nonce']) || !wp_verify_nonce($_POST['save_script_nonce'], 'save_script_nonce')) {
        wp_die('Security check failed.');
    }

    // Your form submission logic here
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Process the form submission

        // Assuming your form data is in $_POST['custom_scripts']
        $new_entity = '';
        if (
            isset($post_data[WP_CUSTOM_SCRIPTS_OPTION_NAME]) &&
            is_array($post_data[WP_CUSTOM_SCRIPTS_OPTION_NAME]['entities']) &&
            isset($post_data[WP_CUSTOM_SCRIPTS_OPTION_NAME]['entities'][0]['new_entity_id'])
        ) {
            $new_entity = $post_data[WP_CUSTOM_SCRIPTS_OPTION_NAME]['entities'][0]['new_entity_id'];
        }

        // Get existing options
        $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);

        // Append the new entity to the existing array
        $options['entities'][] = $new_entity;

        // Update the options
        update_option(WP_CUSTOM_SCRIPTS_OPTION_NAME, $options);

        // Redirect to the List page
        wp_redirect(admin_url('admin.php?page=custom-scripts'));

        // Make sure to call exit() after wp_redirect to stop further execution
        exit();
    } else {
        // Throw an error if the request method is not POST
        wp_die('Invalid request method.');
    }
}

function create_entity_page()
{
    $action = esc_url(admin_url('admin-post.php?action=' . SAVE_SCRIPT_ACTION));
    // $action = admin_url('admin-post.php?action=' . SAVE_SCRIPT_ACTION);
    // $action = "options.php";
    $method = "post";
?>
    <div class="wrap">
        <h2>Crear nuevo Script</h2>
        <form method="<?= $method ?>" action="<?= $action ?>">

            <?php
            settings_fields(CUSTOM_SCRIPTS_SETTINGS_GROUP);
            wp_nonce_field('save_script_nonce', 'save_script_nonce');

            // Generate a unique ID (GUID) for the new entity
            $entity_id = wp_generate_uuid4();
            ?>
            <input type="hidden" id="action" name="action" value="<?= SAVE_SCRIPT_ACTION ?>">
            <input type="hidden" name="custom_scripts[entities][new_entity_id]" value="<?php echo esc_attr($entity_id); ?>" />

            <?php
            // No need to pass an entity for creation
            do_settings_sections('custom-scripts');
            submit_button('Guardar nuevo Script', 'primary', 'save_script', false);
            ?>
        </form>
    </div>
<?php
}
