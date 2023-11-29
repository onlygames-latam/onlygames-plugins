<?php

if (!defined('SAVE_SCRIPT_ACTION')) {
    define('SAVE_SCRIPT_ACTION', 'save_script');
}

// Assuming this is in your main plugin file or where you include your settings.php
add_action('admin_post_' . SAVE_SCRIPT_ACTION, 'handle_form_submission');

function handle_form_submission()
{
    // echo '<pre>';
    // print_r($post_data);
    // echo '</pre>';
    // echo '<br />';
    // die();

    if (!isset($_POST['save_script_nonce']) || !wp_verify_nonce($_POST['save_script_nonce'], 'save_script_nonce')) {
        wp_die('Security check failed.');
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $post_data = $_POST[WP_CUSTOM_SCRIPTS_OPTION_NAME];
        // Process the form submission
        // echo '<pre>';
        // echo '<h1>Entity</h1>';
        // print_r($post_data);
        // echo '</pre>';
        // echo '<br />';
        // die();
        $entity = $post_data['entities'];
        $value_id = $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['id']];
        $value_label = $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['label']];
        $value_code = $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['code']];
        $value_enabled = isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']] : 0;
        // print_r($value_id);
        // echo "<br />";
        // print_r($value_label);
        // echo "<br />";
        // print_r($value_code);
        // echo "<br />";
        // print_r($value_enabled);
        // echo "<br />";
        // die();


        $label_id = CUSTOM_SCRIPTS_ENTITY_FIELD['id'];
        $label_label = CUSTOM_SCRIPTS_ENTITY_FIELD['label'];
        $label_code = CUSTOM_SCRIPTS_ENTITY_FIELD['code'];
        $label_enabled = CUSTOM_SCRIPTS_ENTITY_FIELD['enabled'];
        $new_entity = array(
            $label_id => $value_id,
            $label_label => $value_label,
            $label_code => $value_code,
            $label_enabled => $value_enabled,
        );

        // Get existing options
        $default_value = wp_parse_args(array('entities' => array()));
        $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);

        if (empty($options)) {
            $options = $default_value;
        }


        // echo '<pre>';
        // echo '<h1>Options</h1>';
        // // print_r($default_value);
        // print_r($options);
        // echo '</pre>';
        // echo '<br />';
        // die();

        // Append the new entity to the existing array
        $options['entities'][$value_id] = $new_entity;

        // Update the options
        update_option(WP_CUSTOM_SCRIPTS_OPTION_NAME, $options);

        // Redirect to the List page
        wp_redirect(admin_url('admin.php?page=custom_scripts'));

        // Make sure to call exit() after wp_redirect to stop further execution
        exit();
    } else {
        // Throw an error if the request method is not POST
        wp_die('Invalid request method.');
    }
}

function create_entity_page()
{
    $method = "post";
    $action = esc_url(admin_url('admin-post.php?action=' . SAVE_SCRIPT_ACTION));
    // $action = admin_url('admin-post.php?action=' . SAVE_SCRIPT_ACTION);
    // $action = "options.php";
?>
    <div class="wrap">
        <h2>Crear nuevo Script</h2>
        <form method="<?= $method ?>" action="<?= $action ?>">

            <?php
            settings_fields(CUSTOM_SCRIPTS_SETTINGS_GROUP);
            wp_nonce_field('save_script_nonce', 'save_script_nonce');
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
