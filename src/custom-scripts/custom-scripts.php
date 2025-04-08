<?php

/**
 * Plugin Name: Custom Scripts Plugin
 * Description: Add custom scripts to the footer with the ability to enable/disable each script.
 * Version: 1.0.1
 * Author: Diego Martin Marmol
 * Author URI: https://diegomarmol.com
 * Plugin URI: https://github.com/onlygames-latam/onlygames-plugins
 */

// Include settings file
include(plugin_dir_path(__FILE__) . 'constants.php');
include(plugin_dir_path(__FILE__) . 'functions.php');
include(plugin_dir_path(__FILE__) . 'script-form.php');
include(plugin_dir_path(__FILE__) . 'pages/pages.php');
include(plugin_dir_path(__FILE__) . 'frontend.php');

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
        WP_CUSTOM_SCRIPTS_OPTION_NAME, // 'custom_scripts',
        'custom_scripts_page'
    );
}

/**
 * Callback
 * 
 * This function is called to render the UI
 */
function custom_scripts_page()
{
    if (is_edit_mode()) {
        custom_scripts_edit_entity_page();
        return;
    }
    
    if (is_create_mode()) {
        custom_scripts_create_entity_page();
        return;
    }
    
    // Display the list of entities
    custom_scripts_list_entities();
}

/** 
 * Render each saved script at the page footer HTML
 * This is the practical implementation of the plugins purpose
 */
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
