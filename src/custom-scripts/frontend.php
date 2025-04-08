<?php

/**
 * Frontend related functions
 */

function custom_scripts_admin_scripts($hook) {
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_style('wp-jquery-ui-dialog'); // default WP dialog styles
}
add_action('admin_enqueue_scripts', 'custom_scripts_admin_scripts');


function custom_scripts_init_delete_dialog() {
    ?>
    <script>
        const dialogId = "<?= DELETE_SCRIPT_ACTION . '_confirm_dialog' ?>"

        jQuery(document).ready(function($) {
            $('.delete_script_link').on('click', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).data('url');
                const message = $(this).data('message');
                

                $('#' + dialogId).dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        Eliminar: function() {
                            window.location.href = deleteUrl;
                        },
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    }
                });
            });
        });
        </script>
    <?php
}