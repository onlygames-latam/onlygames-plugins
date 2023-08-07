<?php

if ( ! class_exists( 'GoodGame_Theme_Status' ) ) :

class GoodGame_Theme_Status {

	private $checklist_items = array(
		array( 'name' => 'Install and activate theme', 'method' => 'checkInstallActivate'),
		array( 'name' => 'Install required plugins', 'method' => 'checkPluginsInstalled'),
		array( 'name' => 'Import or setup home and blog pages', 'method' => 'checkHomeBlogPages'),
		array( 'name' => 'Resize thumbnails for pre-existing posts', 'method' => 'checkThumbResize'),
		array( 'name' => 'Make the uploads directory writable', 'method' => 'checkUploadsWrite'),
		array( 'name' => 'Enable PHP file_put_contents and file_get_contents functions', 'method' => 'checkFileContentsFunctions'),
		array( 'name' => 'Set PHP allocated memory limit to at least 128mb', 'method' => 'checkPHPMemoryAmount'),
	);
	
	/*
	 * Function for checking if theme is installed - always true
	 */
	private function checkInstallActivate(){
		return true;
	}

	/*
	 * Function for checking if all REQUIRED plugin are installed
	 */
	private function checkPluginsInstalled(){
		
		
		$plugins = GoodGameInstance()->get_bunlded_plugins();
		$required_active = true; 
		
		foreach($plugins as $plugin)
		{
			$active = false;
			if(
				is_plugin_active($plugin['slug'] . '/' . $plugin['slug'] . '.php')
				||
				is_plugin_active($plugin['slug'] . '/wp-' . $plugin['slug'] . '.php')
				||	
				is_plugin_active($plugin['slug'] . '/init.php')
				||
				is_plugin_active($plugin['slug'] . '/index.php')
				)
			{
				$active = true;
			}
			
			if(!empty($plugin['required']) && $plugin['required'] == true && $active == false)
			{
				$required_active = false;
			}
		}
		
		return $required_active;
	}
	
	/*
	 * Function for checking if front page and blog page is setup
	 */
	private function checkHomeBlogPages(){
		
		$page_on_front = get_option( 'page_on_front' );
		$show_on_front = get_option( 'show_on_front' );
		$page_for_posts = get_option( 'page_for_posts' );

		if($show_on_front == 'page' && intval($page_on_front) > 0 && intval($page_for_posts) > 0)
		{
			return true;
		}		
		
		return false;
	}
	
	
	/*
	 * Function for checking if thumbnails are resized
	 */
	private function checkThumbResize(){
		
		$count = wp_count_posts();
		if($count->publish = 0)	//no posts, no problems
		{
			return true;
		}
		
		//get 10 oldest posts and check if any of them have thumbs and if they are correctly sized
		$args = array(
			'posts_per_page'   => 10,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts_array = get_posts( $args );
		
		foreach($posts_array as $item)
		{
			if(has_post_thumbnail($item->ID))
			{
				$sizes = goodgame_gs('image_sizes');
				
				//find the smallest widht
				$smallest = 999999999;
				$smallest_key = '';
				foreach($sizes as $key => $size_item)
				{
					if($size_item[0] < $smallest)
					{
						$smallest = $size_item[0];
						$smallest_key = $key;
					}
				}
				
				$size = $sizes[$smallest_key];	//get first
				$img = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID), $smallest_key);
								
				if(!empty($img))
				{
					if(strpos($img[0], $size[0].'x'.$size[1]) === false)
					{
						return false;
					}
				}				
			}
		}
		
		return true;
	}
	
	
	/*
	 * Function for checking if thumbnails are resized
	 */
	private function checkUploadsWrite(){
		
		$upload_dir = wp_upload_dir();
		if(is_writable($upload_dir['basedir']))
		{
			return true;
		}
		
		return false;
	}
	
		
	/*
	 * Function for checking if thumbnails are resized
	 */
	private function checkFileContentsFunctions(){
		
		if(function_exists('file_get_contents') && function_exists('file_put_contents'))
		{
			return true;
		}
		
		return false;
	}
	
	
	public function getChecklist(){
		
		$list = array();
		
		foreach($this->checklist_items as $item)
		{
			$list_item = array();
            $list_item['name'] = $item['name'];
            if(method_exists($this, $item['method']))
            {
                $list_item['status'] = $this->{$item['method']}();
                $list[] = $list_item;
            }
		}
		
		return $list;
	}
	
	public function checkPHPMemoryAmount() {
		
		$memory_limit = ini_get('memory_limit');
		
		if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) 
		{
			if ($matches[2] == 'M') 
			{
				$memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
			}
			else if ($matches[2] == 'K') 
			{
				$memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
			}
		}

		$ok = ($memory_limit >= 128 * 1024 * 1024); // at least 128M?
		
		if($ok)
		{
			return true;
		}
		
		return false;
	}
}

endif;