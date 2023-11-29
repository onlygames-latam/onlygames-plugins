<?php
function custom_scripts_list_entities()
{
    // Load the WordPress List Table class
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

    // Define a custom List Table class for your entities
    class Custom_Scripts_List_Table extends WP_List_Table
    {
        function get_columns()
        {
            return array(
                'id' => 'id',
                'label' => 'Label',
                'code' => 'Code',
                'enabled' => 'Enabled'
            );
        }

        function prepare_items()
        {
            $options = get_option(WP_CUSTOM_SCRIPTS_OPTION_NAME);
            $entities = isset($options['entities']) ? $options['entities'] : array();

            $this->_column_headers = array($this->get_columns(), array(), array());


            // Transform entities into the expected format
            $formatted_entities = array();
            foreach ($entities as $index => $entity) {
                $id = isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['id']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['id']] : $index;
                $formatted_entities[$index] = array(
                    'id' => $id,
                    'label' => isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['label']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['label']] : '',
                    'code' => isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['code']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['code']] : '',
                    'enabled' => isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']] : '',
                );
            }

            $this->items = $formatted_entities;
        }

        function column_default($item, $column_name)
        {
            return isset($item[$column_name]) ? esc_html($item[$column_name]) : '';
        }

        function column_label($item)
        {
            return sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=custom_scripts&edit_entity=' . $item['id'])), esc_html($item['label']));
        }

        function column_enabled($item)
        {
            $enabled = isset($item['enabled']) ? $item['enabled'] : false;

            if ($enabled === true) {
                // Render an icon for true/enabled
                return '<span class="dashicons dashicons-yes" style="color: green;"></span>';
            } else {
                // Render an icon for false/disabled
                return '<span class="dashicons dashicons-no" style="color: red;"></span>';
            }
        }
    }

    // Create an instance of your custom List Table
    $list_table = new Custom_Scripts_List_Table();
    $list_table->prepare_items();
?>
    <div class="wrap">
        <h2>Custom Scripts</h2>
        <p>En esta pagina podras administrar los <code>&lt;scripts&gt;</code> que necesites</p>
        <a href="admin.php?page=custom_scripts&edit_entity=new" class="button button-primary">Agregar nuevo</a>
        <?php $list_table->display(); ?>
    </div>
<?php
}
