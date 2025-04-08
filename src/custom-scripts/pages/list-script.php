<?php
function custom_scripts_list_entities()
{
    // Load the WordPress List Table class
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

    // Define a custom List Table class for your entities
    class Custom_Scripts_List_Table extends WP_List_Table
    {
        private $_col_id = CUSTOM_SCRIPTS_ENTITY_FIELD['id'];
        private $_col_label = CUSTOM_SCRIPTS_ENTITY_FIELD['label'];
        private $_col_code = CUSTOM_SCRIPTS_ENTITY_FIELD['code'];
        private $_col_enabled = CUSTOM_SCRIPTS_ENTITY_FIELD['enabled'];
        private $_col_delete = 'script_delete';

        function get_columns()
        {
            return array(
                $this->_col_id => 'id',
                $this->_col_label => 'Nombre',
                $this->_col_code => 'Codigo',
                $this->_col_enabled => 'Habilitado',
                $this->_col_delete => 'Eliminar'
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
                $formatted_entities[$index] = array(
                    CUSTOM_SCRIPTS_ENTITY_FIELD['id'] => isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['id']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['id']] : $index,
                    CUSTOM_SCRIPTS_ENTITY_FIELD['label'] => isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['label']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['label']] : '',
                    CUSTOM_SCRIPTS_ENTITY_FIELD['code'] => isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['code']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['code']] : '',
                    CUSTOM_SCRIPTS_ENTITY_FIELD['enabled'] => isset($entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']]) ? $entity[CUSTOM_SCRIPTS_ENTITY_FIELD['enabled']] : '',
                );
            }

            $this->items = $formatted_entities;
        }

        function column_default($item, $column_name)
        {
            return isset($item[$column_name]) ? esc_html($item[$column_name]) : '';
        }

        function column_script_label($item)
        {
            return sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=custom_scripts&edit_entity=' . $item[$this->_col_id])), esc_html($item[$this->_col_label]));
        }

        function column_script_code($item)
        {
            return sprintf('<pre style="margin-top: 0; margin-bottom: 0;">%s</pre>', esc_html($item[$this->_col_code]));
        }

        function column_script_enabled($item)
        {
            $enabled = isset($item[$this->_col_enabled]) ? (bool) $item[$this->_col_enabled] : false;

            // plugin_die($enabled);
            if ($enabled === true) {
                // Render an icon for true/enabled
                return '<span class="dashicons dashicons-yes" style="color: green;"></span>';
            } else {
                // Render an icon for false/disabled
                return '<span class="dashicons dashicons-no" style="color: red;"></span>';
            }
        }

        function column_script_delete($item): string {
            $entity_id = esc_attr($item[CUSTOM_SCRIPTS_ENTITY_FIELD['id']]);
            $entity_label = trim($item[CUSTOM_SCRIPTS_ENTITY_FIELD['label']]);
            $nonce_action = DELETE_SCRIPT_NONCE . $entity_id;
            $nonce = wp_create_nonce($nonce_action);
            $delete_url = admin_url("admin-post.php?action=". DELETE_SCRIPT_ACTION ."&entity_id=" . $entity_id . "&" . DELETE_SCRIPT_NONCE . "=" . $nonce);
            $confirmation_message = "¿Estas seguro que deseas eliminar \"$entity_label\"?";

            return "<button 
                id=". $entity_id . '_' . $nonce ."
                class=\"delete_script_link\"
                data-url=". $delete_url ."
                data-message=\"$confirmation_message\"
            >
                Eliminar
            </button>";
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
        <div id="<?= DELETE_SCRIPT_ACTION . '_confirm_dialog' ?>" title="Confirmar Eliminación" style="display:none;">
            <p>¿Estas seguro que deseas eliminar este Script?</p>
        </div>
    </div>
    <?php custom_scripts_init_delete_dialog() ?>
    <?php
}
