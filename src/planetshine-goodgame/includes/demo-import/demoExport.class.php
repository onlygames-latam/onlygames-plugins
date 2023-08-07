<?php

class GoodGame_Demo_Export {

	public static function launchExport($preset = 'export') {
		
		$export = array();
		$export['pages'] = GoodGame_Demo_Export::exportPages();
		$export['posts'] = GoodGame_Demo_Export::exportPosts();
		$export['categories'] = GoodGame_Demo_Export::exportCategories();
		$export['platforms'] = GoodGame_Demo_Export::exportPlatforms();
		$export['menus'] = GoodGame_Demo_Export::exportMenus();
		$export['constellation'] = GoodGame_Demo_Export::exportConstellation();
		$export['products'] = GoodGame_Demo_Export::exportProducts();
		$export['product_cats'] = GoodGame_Demo_Export::exportProductCategories();
		//$export['product_attrs'] = GoodGame_Demo_Export::exportProductAttributes();
		$export['sidebars'] = GoodGame_Demo_Export::exportSidebarsWidgets();
        $export['custom_sidebars'] = GoodGame_Demo_Export::exportCustomSidebars();
		$export['galleries'] = GoodGame_Demo_Export::exportGalleries();
		$export['homeblog'] = GoodGame_Demo_Export::exportHomepageAndBlog();
		$export['preset'] = $preset;

		self::writeExportToFile($export, $preset);
		//Single Page (set ID manually)
		//GoodGame_Demo_Export::exportPages(array('p' => 2251));
		
	}
	
	public static function writeExportToFile($data, $preset) {
		
		global $wp_filesystem;
		
		// Initialize the WP filesystem, no more using 'file-put-contents' function
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		$upload_dir = wp_upload_dir();
		$path = $upload_dir['basedir'].'/'. $preset . '.txt';
				
		if(!$wp_filesystem->put_contents( $path, base64_encode(serialize($data)), 0644) ) {
			return __('Failed to put file', 'planetshine');
		}
		
	}
	
    public static function exportPages($params = array()) {
        
        $page_export = array();
        $pages = goodgame_get_post_collection($params, 999, 1, '', 'date', 'DESC', 'page');

		$shop_id = get_option( 'woocommerce_shop_page_id', false);
		
        foreach($pages as $page)
        {
			//make sure that Shop page is always titled "Shop"
			if($shop_id == $page->ID)
			{
				$page->post_name = 'Shop';
			}
			
			//remove revslider from post content
			if(strstr($page->post_content, 'rev_slider_vc'))
			{
				$page->post_content = preg_replace('/\[rev_slider_vc(.*?)\]/s', '', $page->post_content);
			}
			
			$meta = get_post_meta($page->ID);
			if(!empty($meta['page_rev_slider']))
			{
				unset($meta['page_rev_slider']);
			}
			
			$page_export[] = array(
                'post_name' => $page->post_name,
                'post_content' => $page->post_content,
                'post_title' => $page->post_title,
                'post_status' => $page->post_status,
                'post_type' => $page->post_type,
                'post_excerpt' => $page->post_excerpt,
                'post_date' => $page->post_date,
                'post_date_gmt' => $page->post_date_gmt,
                'page_template' => $page->page_template,
				'post_meta'		=> $meta,
            );
        }
        		
        return $page_export;
    }
	
	public static function exportHomepageAndBlog($params = array()) {
	
		$data = array();
		$show_on_front = get_option('show_on_front', false);
		
		if($show_on_front == 'page')
		{
			$page_on_front = get_option('page_on_front', false);
			$page_for_posts = get_option('page_for_posts', false);
			
			if($page_on_front)
			{
				$object = get_post($page_on_front);
				$data['home'] = $object->post_title;
			}
			
			if($page_for_posts)
			{
				$object = get_post($page_for_posts);
				$data['blog'] = $object->post_title;
			}	
		}
				
		return $data;
	}	
    
    public static function exportPosts() {
        
        $post_export = array();
        $posts = goodgame_get_post_collection(array(), 999, 1, '', 'date', 'DESC', 'post');
        foreach($posts as $item)
        {   
            $cats_list = wp_get_post_categories($item->ID, array('fields' => 'slugs'));
            $cats = implode(',', $cats_list);

            $tags_list = wp_get_post_tags($item->ID, array('fields' => 'names'));
            $tags = implode(',', $tags_list);

			$platform_list = wp_get_object_terms($item->ID, 'platform', array('fields' => 'slugs'));
			$platforms = implode(',', $platform_list);

            $post_export[] = array(
                'post_name'     => $item->post_name,
                'post_content'  => $item->post_content,
                'post_title'    => $item->post_title,
                'post_status'   => $item->post_status,
                'post_type'     => $item->post_type,
                'post_excerpt'  => $item->post_excerpt,
                'post_date'     => $item->post_date,
                'post_date_gmt' => $item->post_date_gmt,
                'page_template' => $item->page_template,
                'post_category' => $cats,
                'tags_input'    => $tags,
				'post_platform' => $platforms,
				'post_meta'		=> get_post_meta($item->ID)
            );   
        }
        
        return $post_export;
    }
    
	public static function exportProducts() {
        
        $product_export = array();
        $posts = goodgame_get_post_collection(array(), 999, 1, '', 'date', 'DESC', 'product');
        foreach($posts as $item)
        {   
            $cats_list = wp_get_object_terms($item->ID, 'product_cat', array('fields' => 'slugs'));
            $cats = implode(',', $cats_list);

            $tags_list = wp_get_post_tags($item->ID, 'product_tag', array('fields' => 'names'));
            $tags = implode(',', $tags_list);

			$meta = get_post_meta($item->ID);
			if(!empty($meta['_product_image_gallery']))
			{
				unset($meta['_product_image_gallery']);
			}
			
            $product_export[] = array(
                'post_name'     => $item->post_name,
                'post_content'  => $item->post_content,
                'post_title'    => $item->post_title,
                'post_status'   => $item->post_status,
                'post_type'     => $item->post_type,
                'post_excerpt'  => $item->post_excerpt,
                'post_date'     => $item->post_date,
                'post_date_gmt' => $item->post_date_gmt,
                'page_template' => $item->page_template,
                'product_cat'	=> $cats,
                'product_tag'   => $tags,
				'post_meta'		=> $meta,
				'post_type'		=> 'product'
            );
			
        }
        		
        return $product_export;
    }
	
    public static function exportCategories() {
        
        $categories_objects = get_categories();
        $categories = array();

        foreach($categories_objects as $cat_obj)
        {
            $categories[] = array(
                'cat_name' => $cat_obj->name,
                'category_description' => $cat_obj->category_description,
                'category_nicename' => $cat_obj->category_nicename,
                'taxonomy' => $cat_obj->taxonomy,
            );
        }
                
        return $categories;
    }

	public static function exportPlatforms() {

		$categories_objects = get_categories(array( 'taxonomy' => 'platform' ));
		$categories = array();

		foreach($categories_objects as $cat_obj)
		{
			$categories[] = array(
				'cat_name' => $cat_obj->name,
				'category_description' => $cat_obj->category_description,
				'category_nicename' => $cat_obj->category_nicename,
				'taxonomy' => $cat_obj->taxonomy,
			);
		}

		return $categories;
	}
    
	public static function exportProductCategories() {
        
        $categories_objects = get_categories(array( 'taxonomy' => 'product_cat' ));
        $categories = array();

        foreach($categories_objects as $cat_obj)
        {
            $categories[] = array(
                'cat_name' => $cat_obj->name,
                'category_description' => $cat_obj->category_description,
                'category_nicename' => $cat_obj->category_nicename,
                'taxonomy' => $cat_obj->taxonomy,
            );
        }
        		
        return $categories;
    }
	
	/* Not working 
	public static function exportProductAttributes() {
		
		$attrs = wc_get_attribute_taxonomies();
		
		if(!empty($attrs))
		{
			foreach($attrs as $key => $attr)
			{
				$attr = get_object_vars($attr);
				unset($attr['attribute_id']);
				$attrs[$key] = $attr;
			}
		}
		return $attrs;
	}
	*/
	
	
    public static function exportMenus() {
        
        $menu_export = array();

        $navs = get_terms( 'nav_menu' );
        $parent_titles = array();
        $locations = get_nav_menu_locations();
		$shop_id = get_option( 'woocommerce_shop_page_id', false);
		
        foreach($navs as $nav)
        {
            $export_items = array();

            $menu_id = $nav->term_id;
            $location = array_search($menu_id, $locations);
            $menu_items = wp_get_nav_menu_items($menu_id);
            foreach($menu_items as $mi)
            {
                $title = '';
                
                if($mi->object == 'category')
                {
                    $title = get_the_category_by_ID($mi->object_id);
                }
                else
                {
                    $post_object = get_post($mi->object_id);
                    $title = $post_object->post_title;  //get actual, not given title
                }
                
				$new_item = array(
                    'menu-item-object' => $mi->object,
                    'menu-item-parent-id' => $mi->menu_item_parent,
                    'menu-item-position' => 0,
                    'menu-item-type' => $mi->type,      //??
                    'menu-item-title' => $title,
                    'menu-item-url' => $mi->url,
                    'menu-item-description' => $mi->description,
                    'menu-item-attr-title' => $mi->attr_title,
                    'menu-item-target' => $mi->target,
                    'menu-item-classes' => implode(' ', $mi->classes),
                    'menu-item-xfn' => $mi->xfn,
                    'menu-item-status' => $mi->post_status, 
                );
                
                
                //make sure that Shop page is always titled "Shop"
                if($mi->object == 'page')
                {
                    if($shop_id == $post_object->ID)
                    {
                        $new_item['menu-item-title'] = 'Shop';
                    }
                }
                
                $parent_titles[$mi->ID] = $title;   //cache titles for parents
                
                if($mi->type == 'custom')
                {
                    $home_url = home_url('/');
                    $new_url = str_replace($home_url, '', $mi->url);
                    $new_item['menu-item-url'] = $new_url;
                }

				
                if($mi->menu_item_parent != 0 && !empty($parent_titles[$mi->menu_item_parent]))
                {
                    $new_item['menu-item-parent-title'] = $parent_titles[$mi->menu_item_parent];
                }
                				
                $export_items[] = $new_item;
            }

            $menu_export[] = array('name' => $nav->name, 'location'=> $location, 'items' => $export_items);
        }
        
        return $menu_export;
    }

    public static function exportConstellation() {
        
        if(class_exists('Constellation'))
        {
            $cm_menu_data = json_decode(get_option('constellation_menu_data'), true);
            $cm_sidebars = json_decode(get_option('cm_sidebars'), true);

            $constellation_export = array('menu_data' => $cm_menu_data, 'cm_sidebars' => $cm_sidebars);

            return $constellation_export;
        }
    
    }
    
    public static function exportSidebarsWidgets() {
        
        $sidebar_export = array();
        $active_sidebars = get_option( 'sidebars_widgets' );
        $sidebar_export['sidebars'] = $active_sidebars;

        foreach($active_sidebars as $key => $sidebar)
        {
            if(!in_array($key, array('wp_inactive_widgets', 'array_version')) && !empty($sidebar))
            {
                foreach($sidebar as $widget)
                {
                    //remove last - the version numbet
                    $widget_name_parts = explode('-', $widget);
                    if(count($widget_name_parts) > 1)
                    {
                        array_pop($widget_name_parts);
                    }
                    $widget_name =  'widget_' . implode('-', $widget_name_parts);
                    $widget_option = get_option( $widget_name );

                    $sidebar_export['widget_options'][$widget_name] = $widget_option;
                }
            }
        }
        
        return $sidebar_export;
    }
    
    public static function exportCustomSidebars() {
        
        return goodgame_gs('sidebars');
    }
    
    public static function exportGalleries() {
        
        $gallery_export = array();
        $galleries = goodgame_get_post_collection(array(), NULL, 1, '', 'date', 'DESC', 'gallery');
        
        foreach($galleries as $gallery)
        {
            $attachment_meta = get_post_meta($gallery->ID, 'attachments');
            
            $attachments = NULL;
            if(!empty($attachment_meta))
            {
                $attachments = $attachment_meta[0];
            }
            
            $gallery_export[] = array(
                'post_name'     => $gallery->post_name,
                'post_content'  => $gallery->post_content,
                'post_title'    => $gallery->post_title,
                'post_status'   => $gallery->post_status,
                'post_type'     => $gallery->post_type,
                'post_excerpt'  => $gallery->post_excerpt,
                'post_date'     => $gallery->post_date,
                'post_date_gmt' => $gallery->post_date_gmt,
                'page_template' => $gallery->page_template,
                'attachments'   => $attachments
            );
        }
        
        return $gallery_export;
        
    }
    
}
