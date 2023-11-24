<?php
include(plugin_dir_path(__FILE__) . '../constants.php');
include(plugin_dir_path(__FILE__) . 'create-script.php');
include(plugin_dir_path(__FILE__) . 'edit-script.php');
include(plugin_dir_path(__FILE__) . 'list-script.php');

function custom_scripts_edit_entity_page()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);

    if ($_GET['edit_entity'] === 'new') {
        // Display the form for creating a new entity
        create_entity_page();
    } else {
        // Debug output
        error_log('Editing an existing entity.');

        $entity_index = intval($_GET['edit_entity']);
        // Check if the index is valid
        if (isset($options['entities'][$entity_index])) {
            $entity = $options['entities'][$entity_index];
            // Display the form for editing an existing entity
            edit_entity_page($entity_index, $entity);
        } else {
            echo '<div class="wrap"><p>Invalid entity index.</p></div>';
        }
    }
}
