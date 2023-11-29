<?php

/**
 * Plugin Name: Custom Scripts Plugin
 * Description: Add custom scripts to the footer with the ability to enable/disable each script.
 * Version: 1.1.0
 * Author: Diego Martin Marmol
 * Author URI: https://diegomarmol.com
 * Plugin URI: https://github.com/onlygames-latam/onlygames-plugins
 */

// Include settings file
include(plugin_dir_path(__FILE__) . 'constants.php');
include(plugin_dir_path(__FILE__) . 'script-form.php');
include(plugin_dir_path(__FILE__) . 'pages/pages.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Register admin menu
add_action('admin_menu', 'custom_scripts_menu');

function custom_scripts_menu()
{
    add_menu_page(
        'Custom Scripts',
        'Custom Scripts',
        'manage_options',
        'custom_scripts',
        'custom_scripts_page'
    );
}

/**
 * Render the view
 */
function custom_scripts_page()
{
    // print_r(CUSTOM_SCRIPTS_ENTITIES);
    if (isset($_GET['edit_entity'])) {
        // Display the form for editing a specific entity
        // You'll implement this part later
        custom_scripts_edit_entity_page();
    } else {
        // Display the list of entities
        custom_scripts_list_entities();
    }
}

// Display Scripts in the footer
function custom_scripts_output()
{
    $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
    $entities = $options['entities'];
    $label_code = CUSTOM_SCRIPTS_ENTITY_FIELD['code'];
    $label_enabled = CUSTOM_SCRIPTS_ENTITY_FIELD['enabled'];

    if (is_array($entities)) {
        foreach ($entities as $entity_id => $entity) {
            $script = $entity[$label_code];
            $isEnabled = $entity[$label_enabled];
            if ($isEnabled) {
                echo "<script id=\"" . WP_CUSTOM_SCRIPTS_OPTION_NAME . "_" . $entity_id . "\" type=\"text/javascript\">" . $script . "</script>";
            }
        }
    }
}

add_action('wp_footer', 'custom_scripts_output');
