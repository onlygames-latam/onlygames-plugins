<?php
// Admin page content
function edit_entity_page($entity_index, $entity)
{
?>
    <div class="wrap">
        <h2>Editar Script</h2>
        <form method="post" action="options.php">
            <?php
            // Add a hidden field to store the entity index
            echo '<input type="hidden" name="entity_index" value="' . esc_attr($entity_index) . '" />';
            settings_fields(CUSTOM_SCRIPTS_SETTINGS_GROUP);
            // Pass the entity to the form rendering function
            do_settings_sections('custom_scripts', $entity);
            submit_button();
            ?>
        </form>
    </div>
<?php
}
