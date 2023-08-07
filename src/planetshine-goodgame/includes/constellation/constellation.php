<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if ( ! class_exists( 'Constellation' ) ) :

    /*
     * Main Constellation Class
     * 0.1
     */
    Class Constellation {
        
        /*
         * Static var for instance
         */
        protected static $_instance = null;
        
        /*
        * Main Constellation Instance
        *
        * 0.1
        */
        public static function instance() {
           if ( is_null( self::$_instance ) ) {
               self::$_instance = new self();
           }
           return self::$_instance;
        }
        
       /*
        * Constellation constructor 
        */
        public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ), 10 );
            add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ), 10 );
            
            add_action( 'wp_update_nav_menu_item', array( $this, 'custom_nav_item_update' ),10, 3);
            add_action( 'wp_update_nav_menu', array( $this, 'custom_nav_update' ),10, 3);
            add_action( 'wp_ajax_get_cm_fields', array( $this, 'get_menu_item_fields' ) );
            add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
            add_filter( 'wp_nav_menu_args', array( $this, 'attach_constellation' ) );
        }
       
        /*
        * Load frontend stylesheets
        */
        public static function load_frontend_scripts($hook) {
            wp_enqueue_style( 'cm-frontend', plugin_dir_url( __FILE__ ) . '/assets/css/frontend.css' );
        }
        
       /*
        * Load admin scripts and stylesheets
        */
        public static function load_admin_scripts($hook) {
                       
            if( 'nav-menus.php' != $hook ) {
                return;
            }

            $menu_data = json_decode(get_option('constellation_menu_data', json_encode(array())), true);
            
            //make sure indexes start with 0
            if(!empty($menu_data))
            {
                $menu_data = array_values($menu_data);
            }
            
			$cm_status = '<fieldset class="menu-settings-group constellation-status">'
					. '<legend class="menu-settings-group-name howto">' . __('Mega menu', 'constellation') . '</legend>'
					. '<div class="menu-settings-input checkbox-input">'
					. '<input type="checkbox" name="constellation-status" id="constellation-status">'
					. '<label for="constellation-status">' . __('Use Mega Menu for this menu', 'constellation') . '</label>'
					. '</div>'
					. '</fieldset>';
            
            wp_enqueue_style( 'cm-style', plugin_dir_url( __FILE__ ) . '/assets/css/admin.css' );
            wp_enqueue_script( 'cm-js', plugin_dir_url( __FILE__ ) . '/assets/js/scripts.js' );
            wp_localize_script( 'cm-js', 'cm_data', array( 
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'menu_data' => $menu_data,
                'cm_status_form' => $cm_status
            ));
        }
       
       /*
        * Save custom menu item values
        */
        public static function custom_nav_item_update($menu_id, $menu_item_db_id, $args ) {
           
            if ( 
                !empty($_REQUEST['cm-mega-menu'])
                &&
                !empty($_REQUEST['cm-mega-menu'][$menu_item_db_id])
                &&
                $_REQUEST['cm-mega-menu'][$menu_item_db_id] == 'on'
            ) 
            {
                $menu_item = $_REQUEST['cm-mega-menu'][$menu_item_db_id];
                update_post_meta( $menu_item_db_id, '_cm_mega_menu_status', 1 );
            }
            else
            {
                update_post_meta( $menu_item_db_id, '_cm_mega_menu_status', 0 );
            }
        }

        
        /* 
         * Save custom menu values
         */
        public static function custom_nav_update($menu_id, $menu_data=array(), $args=array()) {
            
            if(!empty($menu_data) && !empty($menu_data['menu-name'])) {
            
                $cm_data = json_decode(get_option( 'constellation_menu_data', json_encode(array())), true);
                if(empty($cm_data))
                {
                    $cm_data = array();
                }
                
                $menu_name = $menu_data['menu-name'];

                if(!empty($_REQUEST['constellation-status']))
                {
                    if(!in_array($menu_name, $cm_data))
                    {
                        $cm_data[] = $menu_name;
                    }
                }
                else
                {
                    if(($key = array_search($menu_name, $cm_data)) !== false) 
                    {
                        unset($cm_data[$key]);
                    }
                }
                
                update_option('constellation_menu_data', json_encode($cm_data));
            }
        }

        /*
         * Return AJAX content for menu item fields in admin
         */
        public static function get_menu_item_fields() {
            ob_start();
            $id = intval($_POST['cm_item']);
            $status = get_post_meta($id, '_cm_mega_menu_status', true);
            $mega_menus = json_decode(get_option('cm_sidebars', json_encode(array())), true);
            $sidebar = false;
            if(!empty($mega_menus[$id]))
            {
                $sidebar = true;
                $title = $mega_menus[$id]['title'];
            }
            
            ?>       
            <div class="cm-settings-wrapper" id="cm-settings-wrapper-<?php echo $id; ?>">
                <p>
                    <input type="checkbox" id="cm-mega-menu-<?php echo $id; ?>" name="cm-mega-menu[<?php echo $id; ?>]" <?php if($status == 1) { echo 'checked'; } ?>>
                    <label for="cm-mega-menu-<?php echo $id; ?>"><?php _e('Has Mega Menu Dropdown', 'constellation'); ?></label>
                </p>
                <div class="cm-extra-settings <?php if($status == 1) { echo 'active'; } ?>">
                    <p>
                    <?php
                        if($sidebar)
                        {
                            $widget_url = get_admin_url() . 'widgets.php';
                            printf( __('Go to <a href="%2$s">Widgets</a> to add content. Widget block name is <strong>Mega Menu - %1$s</strong>.', 'constellation'), $title, $widget_url );
                        }
                        else
                        {
                            _e('Save this menu to complete the Mega Menu initialization.', 'constellation');
                        }
                    ?>
                    </p>
                </div>
            </div>
            <?php
            $output = ob_get_contents();
            ob_end_clean();
            die($output);
        }
        

        /*
         * Register all sidebar associated with mega menu
         */
        public static function register_sidebars() {
            
           $mega_menus = self::get_posts_by_meta('_cm_mega_menu_status', 1);
	       $save_data = array();
          
           if(empty($mega_menus)) return false;
          
		
		   $sidebar_settings = array(
		   	   'before_widget' => '<div id="%1$s" class="constellation-widget %2$s">',
               'after_widget'  => '</div>',
               'before_title'  => '',
               'after_title'   => ''
		   );

           foreach($mega_menus as $mega_menu)
           {
				$menu_item = wp_setup_nav_menu_item($mega_menu);

                $args = array(
                    'name' => __('Mega Menu - ', 'constellation') . '"' . $menu_item->title . '"',
                    'id'   => 'mega_menu_' . $menu_item->ID,
                    'description' => __('Content of Mega Menu dropdown - ', 'constellation') . '"' . $menu_item->title . '"',
                    'class' => '',
                    'before_widget' => '<div id="%1$s" class="constellation-widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '',
                    'after_title'   => ''
               );
               $sidebar = apply_filters('constellation_sidebar_args', $args);
               register_sidebar($sidebar);
              
               $save_data[$menu_item->ID] = array('post_id' => $menu_item->ID, 'sidebar_id' => 'mega_menu_' . $menu_item->ID, 'title' => $menu_item->title );
           }
          
           update_option('cm_sidebars', json_encode($save_data));
        }
        
        
        /**
         * Load posts by a meta field value
         * @global type $wpdb
         * @param type $key
         * @param type $value
         * @param type $count
         * @param type $page
         * @param type $post_type
         * @return type
         */
        public static function get_posts_by_meta($key, $value, $count=999, $page=1, $post_type = 'nav_menu_item')
        {
            global $wpdb;
            $limit = ($page-1) * $count;

            $querydetails = "
                SELECT wposts.*
                FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
                WHERE wposts.ID = wpostmeta.post_id
                AND wpostmeta.meta_key = '" . goodgame_dbSE($key) . "'
                AND wpostmeta.meta_value = '" . goodgame_dbSE($value) . "'
                AND wposts.post_status = 'publish'
                AND wposts.post_type = '" . goodgame_dbSE($post_type) . "'
                ORDER BY wposts.post_date DESC
                LIMIT $limit, $count
            ";
            return $wpdb->get_results($querydetails, OBJECT);
        }
        
        /**
         * Return dropdown content
         */
        public static function get_dropdown_content($id)
        {
            ob_start();
            
            $mega_menus = json_decode(get_option('cm_sidebars', json_encode(array())), true);
            $sidebar = false;
            if(!empty($mega_menus[$id]))
            {
                dynamic_sidebar($mega_menus[$id]['sidebar_id']);
            }
            
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
        
        /**
         * Check if menu has constellation enabled
         * @param type $menu_id
         */
        public static function menu_has_constellation($args)
        {
            $data = json_decode(get_option('constellation_menu_data', json_encode(array())), true);
            if(empty($data))
            {
                $data = array();
            }
            $menu_name = false;
            
            //first try to check theme locations
            if(!empty($args['theme_location']))
            {
                $menu_locations = get_nav_menu_locations();
                $menus = wp_get_nav_menus();
                
                if(!empty($menu_locations[$args['theme_location']]))
                {
                    $term_id = $menu_locations[$args['theme_location']];
                    foreach($menus as $menu)
                    {
                        if($menu->term_id == $term_id)
                        {
                            $menu_name = $menu->name;
                        }
                    }
                }                
            }
            
            if($menu_name)
            {
                if(in_array($menu_name, $data))
                {
                    return true;
                }
            }
            
            //check if menu object has info ony menu name
            if(is_object($args['menu']) && !empty($args['menu']->name))
            {
                if(in_array($args['menu']->name, $data))
                {
                    return true;
                }
            }
                        
            return false;
        }
        
        /**
         * Attach constellation to wp_nav_menu
         */
        public static function attach_constellation($args)
        {
            if(
                self::menu_has_constellation($args)
                &&
                    (
                        empty($args['no_constellation'])
                        ||
                        (
                            !empty($args['no_constellation'])
                            &&
                            $args['no_constellation'] === false
                        )
                    )
            )
            {
                $args['depth'] = 3;
                $args['container'] = 'div';
                $args['menu_class'] .= ' constellation';
                $args['fallback_cb'] = 'wp_bootstrap_navwalker::fallback';
                $args['walker'] = new Constellation_Nav_Walker();
            }
                        			
            return $args;
        }
        
    }
    

endif;

/* Include nawwaler */
require_once(plugin_dir_path( __FILE__ ) . 'constellation_navwalker.php');

/**
 * Returns the main instance of Constellation to prevent the need to use globals.
 *
 * @since  0.1
 * @return Constellation
 */

function CM_Instance() {
	return Constellation::instance();
}

$GLOBALS['constellation'] = CM_Instance();


if(!function_exists('goodgame_dbSE'))
{
    function goodgame_dbSE($value)
    {
        global $wpdb;
        return $wpdb->_real_escape($value);
    }
}

if(!function_exists('debug'))
{
    function debug($variable, $die=true)
    {
        if ((is_scalar($variable)) || (is_null($variable)))
        {
            if (is_null($variable))
            {
                $output = '<i>NULL</i>';
            }
            elseif (is_bool($variable))
            {
                $output = '<i>' . (($variable) ? 'TRUE' : 'FALSE') . '</i>';
            }
            else 
            {
                $output = $variable;
            }
            echo '<pre>variable: ' . $output . '</pre>';
        }
        else // non-scalar
        {
            echo '<pre>';
            print_r($variable);
            echo '</pre>';
        }

        if ($die)
        {
            die();
        }
    }
}   