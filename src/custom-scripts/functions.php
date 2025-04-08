<?php
function is_edit_mode() {
    return isset($_GET['edit_entity']) && $_GET['edit_entity'] !== 'new'; 
}

function is_create_mode() {
    return isset($_GET['edit_entity']) && $_GET['edit_entity'] === 'new'; 
}

function plugin_die($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
    echo '<br />';
    die();
}