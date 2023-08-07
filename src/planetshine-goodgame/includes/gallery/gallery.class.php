<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if ( ! class_exists( 'GoodGame_Gallery' ) ) :

    /*
     * Main Constellation Class
     * 0.1
     */
    Class GoodGame_Gallery {
        
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
			
			add_action( 'init', array($this, 'register_gallery_post_type' ));
			add_action( 'after_setup_theme', array($this, 'attachment_plugin_setup' ));
			add_action( 'admin_head', array($this, 'add_menu_icons_styles'));
        }
	

		/*
		 * Register gallery post type
		 */
		function register_gallery_post_type() 
		{
			$labels = array(
				'name' => __('Galleries', 'goodgame'),
				'singular_name' => __('Gallery item', 'goodgame'),
				'add_new' => __('Add New', 'goodgame'),
				'add_new_item' => __('Add New Item', 'goodgame'),
				'edit_item' => __('Edit Item', 'goodgame'),
				'new_item' => __('New Item', 'goodgame'),
				'all_items' => __('All Gallery Items', 'goodgame'),
				'view_item' => __('View Item', 'goodgame'),
				'search_items' => __('Search Gallery Items', 'goodgame'),
				'not_found' =>  __('No gallery items found', 'goodgame'),
				'not_found_in_trash' => __('No gallery items found in Trash', 'goodgame'), 
				'parent_item_colon' => __('Parent Gallery:', 'goodgame'),
				'menu_name' => __('Galleries', 'goodgame')
			);

			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true, 
				'show_in_menu' => true, 
				'query_var' => true,
				'has_archive' => true,
				'menu_icon' => '',
				'menu_position' => 5,
				'rewrite' => array('slug' => 'gallery', 'with_front' => false),
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array( 'title', 'editor', )
			); 

			register_post_type( 'gallery', $args );
		}

		/*
		 * Attachments plugin setup for galleries
		 */
		function goodgame_galleries( $attachments )
		{
			$fields         = array(
				array(
					'name'      => 'caption',                       // unique field name
					'type'      => 'textarea',                      // registered field type
					'label'     => __( 'Caption', 'attachments' ),  // label to display
					'default'   => 'caption',                       // default value upon selection
				)
			);

			$args = array(

				// title of the meta box (string)
				'label'         => 'Gallery',

				// all post types to utilize (string|array)
				'post_type'     => array( 'gallery' ),

				// meta box position (string) (normal, side or advanced)
				'position'      => 'normal',

				// meta box priority (string) (high, default, low, core)
				'priority'      => 'high',

				// allowed file type(s) (array) (image|video|text|audio|application)
				'filetype'      => array('image'),  // no filetype limit

				// include a note within the meta box (string)
				'note'          => 'Attach images to gallery using the button below!',

				// by default new Attachments will be appended to the list
				// but you can have then prepend if you set this to false
				'append'        => true,

				// text for 'Attach' button in meta box (string)
				'button_text'   => __( 'Attach Images', 'goliath' ),

				// text for modal 'Attach' button (string)
				'modal_text'    => __( 'Attach', 'goliath' ),

				// which tab should be the default in the modal (string) (browse|upload)
				'router'        => 'browse',

				// whether Attachments should set 'Uploaded to' (if not already set)
				'post_parent'   => false,

				// fields array
				'fields'        => $fields,

			);

			$attachments->register( 'goodgame_galleries', $args ); // unique instance name
		}

		/*
		 * Add menu item icons for gallery
		 */
		function add_menu_icons_styles() {
		?>
			<style>
			#menu-posts-gallery div.wp-menu-image:before {
			  content: "\f232";
			}
			</style>
		<?php
		}

		/*
		 * Add filters for attachments plugin
		 */
		function attachment_plugin_setup()
		{

			/* Setup gallery attachments */
			if( class_exists( 'Attachments' ) )
			{
				add_filter( 'attachments_default_instance', '__return_false' ); // disable the default instance
				add_action( 'attachments_register', array($this, 'goodgame_galleries'));
			}
		}
		
	}
	
endif;

//Create an instance
GoodGame_Gallery::instance();