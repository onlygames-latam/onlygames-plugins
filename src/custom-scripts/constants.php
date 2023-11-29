<?php
// constants.php

/** 
 * The name used in the wp_options table
 */
if (!defined('WP_CUSTOM_SCRIPTS_OPTION_NAME')) {
    define('WP_CUSTOM_SCRIPTS_OPTION_NAME', 'custom_scripts');
}
/** 
 * Unsure about this one
 */
if (!defined('CUSTOM_SCRIPTS_SETTINGS_GROUP')) {
    define('CUSTOM_SCRIPTS_SETTINGS_GROUP', 'custom_scripts-group');
}

/**
 * Form Fields used
 */
if (!defined('CUSTOM_SCRIPTS_ENTITY_FIELD')) {
    define('CUSTOM_SCRIPTS_ENTITY_FIELD', array(
        "id" => "script_id",
        "label" => "script_label",
        "code" => "script_code",
        "enabled" => "script_enabled",
    ));
}
