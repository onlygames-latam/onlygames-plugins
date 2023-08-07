<?php
/**
 * Plugin Name: Planetshine GoodGame Theme Extension
 * Plugin URI: 
 * Description: Powers major features like admin, homepage layouts, mega menus, galleries, shortcodes and more
 * Version: 1.0.2
 * Author: Planetshine
 * Author URI: http://planetshine.net
 * License: A "Slug" license name e.g. GPL2
 */


// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if ( ! class_exists( 'GoodGame_Extension' ) ) :

    /*
     * Main Class
     * 0.1
     */
    Class GoodGame_Extension {
        
		/*
         * Static var for instance
         */
        protected static $_instance = null;
        
        /*
        * Main Instance
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
        * constructor 
        */
        public function __construct() {
			
			$this->includes();
            $this->hooks();
        }
	
        /*
         * Add WordPress hooks
         */
        public function hooks() {
            
            
        } 
        
        /*
         * Include files used
         */
        public function includes() {
            include_once( 'includes/gallery/gallery.class.php' );   //add galleries
            include_once( 'includes/platform/platform.class.php' );   //add galleries
            include_once( 'includes/demo-import/demo-import.php' );   //add demo import
            include_once( 'includes/status/planetshine-status.php' );   //add status functions
            include_once( 'includes/constellation/constellation.php' );   //add mega menu
            include_once( 'includes/vc-block/vc-block-base.class.php' );   //add base class for vc blocks. blocks themselves are inside the theme
            include_once( 'includes/shortcodes/shortcodes.php' );   //add shortcodes
            include_once( 'includes/curl/curl.php' );   //add curl remote request
        }
        
        /*
         * Add pretty admin menus
         */
        public static function add_admin_menus($theme_name, $theme_slug, $icon_url) {
            
            add_menu_page( $theme_name, $theme_name, 'administrator', $theme_slug . '-admin', 'goodgame_admin', $icon_url, 3);
            add_submenu_page( $theme_slug . '-admin', 'Theme Options', 'Theme Options', 'administrator', $theme_slug . '-admin', 'goodgame_admin');
        }
	}
	
endif;

//Create an instance
GoodGame_Extension::instance();