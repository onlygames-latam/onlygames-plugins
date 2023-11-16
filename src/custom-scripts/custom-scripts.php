<?php

/**
 * Plugin Name: Custom Scripts Plugin
 * Description: Add custom scripts to the footer with the ability to enable/disable each script.
 * Version: 1.0
 * Author: Diego Martin Marmol
 * Author URI: https://diegomarmol.com
 * Plugin URI: https://github.com/onlygames-latam/onlygames-plugins
 */

// Include settings file
include(plugin_dir_path(__FILE__) . 'settings.php');

// Register admin menu
add_action('admin_menu', 'custom_scripts_menu');

function custom_scripts_menu()
{
    add_menu_page(
        'Custom Scripts',
        'Custom Scripts',
        'manage_options',
        'custom-scripts',
        'custom_scripts_page'
    );
}

// Admin page content
function custom_scripts_page()
{
?>
    <div class="wrap">
        <h2>Scripts Personalizados</h2>
        <p>En esta pagina podras administrar los <code>&lt;scripts&gt;</code> que necesites</p>
        <form method="post" action="options.php">
            <?php settings_fields('custom-scripts-group'); ?>
            <?php do_settings_sections('custom-scripts'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}


// Display Scripts in the footer
// Display Scripts in the footer
function custom_scripts_output()
{
    $options = get_option('custom_scripts');

    if ($options && is_array($options['script_code'])) {
        foreach ($options['script_code'] as $key => $script) {
            if ($options['script_enabled'][$key]) {
                echo '<script type="text/javascript">' . $script . '</script>';
            }
        }
    }
}

add_action('wp_footer', 'custom_scripts_output');
?>