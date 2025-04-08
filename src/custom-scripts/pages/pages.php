<?php
include(plugin_dir_path(__FILE__) . '../constants.php');
include(plugin_dir_path(__FILE__) . 'create-script.php');
include(plugin_dir_path(__FILE__) . 'edit-script.php');
include(plugin_dir_path(__FILE__) . 'list-script.php');

function custom_scripts_edit_entity_page()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $param_entity_id = $_GET['edit_entity'];
    $entity_exist = isset($options['entities'][$param_entity_id]);
    
    if (!$entity_exist) {
        error_log('Editing an existing entity.');
        throw new Exception("There's no entity with GUID '$param_entity_id' in the database");
    }

    $entity = $options['entities'][$param_entity_id];
    $entity_id = $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['id']];

    // Check if the index is valid
    // Redundant check, but I like resilience :)
    if ($entity_id === $param_entity_id) {
        $entity = $options['entities'][$entity_id];
        // Display the form for editing an existing entity
        edit_entity_page($entity_id);
    } else {
        // Debug output
        error_log('Editing an existing entity.');
        echo '<div class="wrap"><p>Invalid entity index.</p></div>';
    }
}

function custom_scripts_create_entity_page()
{
    $entity_id = $_GET['edit_entity'];

    if ($entity_id === 'new') {
        // Display the form for creating a new entity
        create_entity_page();
    } else {
        // Debug output
        error_log('Creating an existing entity.');
        
        echo '<div class="wrap"><p>Error while creating a new entity.</p></div>';
    }
}
