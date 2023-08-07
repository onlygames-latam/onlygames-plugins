<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if ( ! class_exists( 'GoodGame_Platform' ) ) :

    /*
     * Main Platform Class
     * 0.1
     */
    Class GoodGame_Platform {
        
		/*
         * Static var for instance
         */
        protected static $_instance = null;
        
        /*
        * Main Platform Instance
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
        * Platform constructor 
        */
        public function __construct() {
			
//			add_action( 'init', array($this, 'register_gallery_post_type' ));
//			add_action( 'after_setup_theme', array($this, 'attachment_plugin_setup' ));
//			add_action( 'admin_head', array($this, 'add_menu_icons_styles'));
			
			add_action( 'after_setup_theme',  array($this, 'create_platform_taxonomy' ), 10 );
			add_action( 'platform_add_form_fields', array($this, 'add_platform_color_field'), 10, 2 );
			add_action( 'created_platform', array($this, 'save_platform_color_field'), 10, 2 );
			add_action( 'platform_edit_form_fields', array($this, 'edit_platform_color_field'), 10, 2 );
			add_action( 'edited_platform', array($this, 'update_platform_color_field'), 10, 2 );
        }
	
		/*
		 * Register taxanomy for platforms
		 */
		function create_platform_taxonomy()
		{
			$labels = array(
				'name'              => _x( 'Platforms', 'taxonomy general name', 'goodgame' ),
				'singular_name'     => _x( 'Platform', 'taxonomy singular name', 'goodgame' ),
				'search_items'      => __( 'Search Platforms', 'goodgame' ),
				'all_items'         => __( 'All Platforms', 'goodgame' ),
				'edit_item'         => __( 'Edit Platform', 'goodgame' ),
				'update_item'       => __( 'Update Platform', 'goodgame' ),
				'add_new_item'      => __( 'Add New Platform', 'goodgame' ),
				'new_item_name'     => __( 'New Platform Name', 'goodgame' ),
				'menu_name'         => __( 'Platforms', 'goodgame' ),
			);

			$args = array(
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'platform' ),
			);

			register_taxonomy( 'platform', array( 'post' ), $args );
		}

		/*
		 * Add platform color meta field
		 */
		function add_platform_color_field($taxonomy)
		{
			?><div class="form-field term-group">
				<label for="platform-color"><?php esc_html_e('Background color HEX code!', 'goodgame'); ?></label>
				<input type="text" name="platform-color" value="ff2851">
				<br><?php esc_html_e('Example: For green color use code "008000"', 'goodgame'); ?>
			</div><?php
		}

		/*
		 * Get platform color if available
		 */
		public static function get_platform_color($term_id)
		{
			$color = get_term_meta( $term_id, 'platform-color', true );
			return (!empty($color)) ? $color : false;
		}

		/*
		 * Save platform color meta field
		 */
		function save_platform_color_field($term_id, $tt_id)
		{
			if( isset( $_POST['platform-color'] ) && '' !== $_POST['platform-color'] ){
				$color = sanitize_title( $_POST['platform-color'] );
				add_term_meta( $term_id, 'platform-color', $color, true );
			}
		}

		/*
		 * Display color in platform edit page
		 */
		function edit_platform_color_field($term, $taxonomy)
		{
			$current_color = $this->get_platform_color($term->term_id);

			?><tr class="form-field term-group">
				<th scope="row"><label for="platform-color"><?php esc_html_e('Background color HEX code', 'goodgame'); ?></label></th>
				<td><input type="text" name="platform-color" <?php if($current_color) : ?>value="<?php echo esc_attr($current_color); ?>"<?php endif; ?>></td>
			</tr><?php
		}

		/*
		 * Update platform color meta field
		 */
		function update_platform_color_field($term_id, $tt_id) {
			 if( isset( $_POST['platform-color'] ) && '' !== $_POST['platform-color'] ){
				$group = sanitize_title( $_POST['platform-color'] );
				update_term_meta( $term_id, 'platform-color', $group );
			}
		}
		
		
	}
	
endif;

//Create an instance
GoodGame_Platform::instance();