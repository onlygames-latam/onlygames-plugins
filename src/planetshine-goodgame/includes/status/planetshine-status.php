<?php
/*
 * Wrapper file for Status class 
 */

require_once('themeStatus.class.php');

// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if ( ! class_exists( 'GoodGame_Status' ) ) :

    /*
     * Main Constellation Class
     * 0.1
     */
    Class GoodGame_Status {
        
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
        
        public function getChecklist() {
            
            $statusObj = new GoodGame_Theme_Status();
            return $statusObj->getChecklist();
        }
			
	}
	
endif;

//Create an instance
if(!defined('GOODGAME_STATUS')) {
    function GOODGAME_STATUS() {
        return GoodGame_Status::instance();
    }
}

