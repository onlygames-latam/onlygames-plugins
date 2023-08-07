<?php
if(function_exists('vc_map'))
{
	class GoodGame_VC_Block_Base {

		public $shortcode = '';
		public $classname = '';
		public $params = array();

		/*
		 * Construct the class - register shortcode
		 */
		function __construct() {

			add_shortcode($this->shortcode, array($this->classname, 'shortcode'));	//replace with get_called_class(); when 5.2 support is dropped
			$this->map();
		}

		/*
		 * Parent method for shortcode functionality
		 */
		public static function shortcode($atts = array(), $content = '') {}

		/*
		 * Parent method for shortcode params
		 */
		public function getParams() {}
		
		/*
		 * Map Shortcode to VC block
		 */
		public function map() {
			vc_map($this->getParams());
		}

	}
}