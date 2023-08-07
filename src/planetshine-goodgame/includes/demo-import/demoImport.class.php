<?php

class GoodGame_Demo_Import {
    
    public $demo_data = array();
    private $demo_name = '';
    private $category_id_cache = array();
	private $platform_id_cache  = array();
	private $product_cat_id_cache = array();
    private $sidebar_rename_log = array();
    private $default_thumb = false;
    
    //log all the inserts
    private $import_log =  array(
        'categories'			=> array(),
		'product_categories'    => array(),
		'platforms'   			=> array(),
		'product_attrs'			=> array(),
        'pages'					=> array(),
        'posts'					=> array(),
		'products'				=> array(),
        'menus'					=> array(),
        'constellation'			=> false,   //no need for ids. simply remove all.
        'sidebars'				=> true,
        'galleries'				=> array(),
        'preset'				=> false
    );
    
    //log all the option replacements
    private $replace_log = array(
        'constellation' => array(),
        'sidebars'      => array(),
    );
    
    public function __construct($demo_name) {
        		
        $this->demo_name = $demo_name;
        
        //load variable of demo data
        $this->demo_data = unserialize(base64_decode($this->getFileContent($demo_name)));
    }
    
	public function getFileContent($demo) {
		
		global $wp_filesystem;
		
		// Initialize the WP filesystem, no more using 'file-put-contents' function
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		$path = plugin_dir_path(__FILE__) . 'imports/' . $demo . '.txt';
				
		return $wp_filesystem->get_contents( $path );
	}
	
    public function importAll() {
                
        //$this->setupDefaultThumb();
        $this->importCategories();
		$this->importPlatforms();
		$this->importProductCategories();
		$this->importPlatforms();
		//$this->importProductAttributes();
        $this->importPages();
		$this->importHomeAndBlog();
        $this->importPosts();
		$this->importProducts();
        $this->importMenus();
        $this->importConstellation();
        $this->importSidebarsWidgets();
        $this->importCustomSidebars();
        $this->importGalleries();
        $this->modifyPreset();
        $this->saveImportLogToDB();
    }
    
    /* Setup the default thumb used for all posts, galleries etc */
    public function setupDefaultThumb() {
        
        $image_source_path = get_template_directory() . '/theme/' . 'assets/images/no-image.png';
        $image_dest_path = GOODGAME_UPLOAD_PATH . 'no-image.png';
        
        if(file_exists($image_source_path) && !file_exists($image_dest_path))
        {
            $dir_exists = true;
            
            //create directory if it does not exist
            if(!file_exists(GOODGAME_UPLOAD_PATH))
            {
                $dir_exists = mkdir(GOODGAME_UPLOAD_PATH);
            }
        
            if($dir_exists)
            {
                $this->default_thumb = copy($image_source_path, $image_dest_path);
            }
        }
        elseif(file_exists($image_dest_path))
        {
            $this->default_thumb = true;
        }
    }
    
    /* Categories */
    public function importCategories() {
        
        $cat_ids = array();
        $categories = $this->demo_data['categories'];
        
        if(empty($categories)) return false;
        
        foreach($categories as $cat) {
            
            $return = wp_insert_category(array(
                'cat_ID' => 0,
                'cat_name' => $cat['cat_name'],
                'category_description' => $cat['category_description'],
                'category_nicename' => $cat['category_nicename'],
                'category_parent' => '',
                'taxonomy' => $cat['taxonomy']
            ));

            if(is_integer($return) && $return > 0)
            {
                $this->category_id_cache[$cat['category_nicename']] = $return;
                $this->import_log['categories'][] = $return;    //log category
            }
            else    //if there was an arror - category exists or something similar check if the cat can be found
            {
                $cat_obj = get_category_by_slug($cat['category_nicename']);
                if($cat_obj)
                {
                    $this->category_id_cache[$cat['category_nicename']] = $cat_obj->cat_ID;
                }
            }
        }
    }
    
	/* Product Categories */
    public function importProductCategories() {
        
        $cat_ids = array();
        $categories = $this->demo_data['product_cats'];
		
		if(!goodgame_is_woocommerce_active()) return false;
        
        if(empty($categories)) return false;
        
        foreach($categories as $cat) {
            
			$return = wp_insert_term(
				$cat['cat_name'],
				'product_cat',
				array(
					'description'=> $cat['category_description'],
					'slug' => $cat['category_nicename'],
					'parent'=> ''
				)
			);

			if(is_array($return))
			{
				if(!empty($return['term_id']) && $return['term_id'] > 0)
				{
					$this->product_cat_id_cache[$cat['category_nicename']] = $return['term_id'];
					$this->import_log['product_categories'][] = $return['term_id'];    //log category
				}
				else    //if there was an arror - category exists or something similar check if the cat can be found
				{
					$cat_obj =  get_term_by( 'slug', $cat['category_nicename'], 'product_cat' );
					if($cat_obj)
					{
						$this->product_cat_id_cache[$cat['category_nicename']] = $cat_obj->term_id;
					}
				}
			}
        }
    }


	/* Platforms */
	public function importPlatforms() {

		$cat_ids = array();
		$categories = $this->demo_data['platforms'];

		if(empty($categories)) return false;

		foreach($categories as $cat) {

			$return = wp_insert_term(
				$cat['cat_name'],
				'platform',
				array(
					'description'=> $cat['category_description'],
					'slug' => $cat['category_nicename'],
					'parent'=> ''
				)
			);

			if(is_array($return))
			{
				if(!empty($return['term_id']) && $return['term_id'] > 0)
				{
					$this->platform_id_cache[$cat['category_nicename']] = $return['term_id'];
					$this->import_log['platforms'][] = $return['term_id'];    //log category
				}
				else    //if there was an arror - category exists or something similar check if the cat can be found
				{
					$cat_obj =  get_term_by( 'slug', $cat['category_nicename'], 'product_cat' );
					if($cat_obj)
					{
						$this->platform_id_cache[$cat['category_nicename']] = $cat_obj->term_id;
					}
				}
			}
		}
	}
	
	/* Product Attributes - Not working */
	/*
	public function importProductAttributes() {

		global $wpdb;
		
		if(!goodgame_is_woocommerce_active()) return false;
		
		$attrs = $this->demo_data['product_attrs'];
		
		if(empty($attrs)) return false;
		
		foreach($attrs as $attribute)
		{
			
			if ( empty( $attribute['attribute_name'] ) || empty( $attribute['attribute_label'] ) ) 
			{
				return new WP_Error( 'error', __( 'Please, provide an attribute name and slug.', 'woocommerce' ) );
			}
			elseif ( taxonomy_exists( wc_attribute_taxonomy_name( $attribute['attribute_name'] ) ) ) 
			{
				return new WP_Error( 'error', sprintf( __( 'Slug "%s" is already in use. Change it, please.', 'woocommerce' ), sanitize_title( $attribute['attribute_name'] ) ) );
			}
			
			$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
			$this->import_log['product_attributes'][] = $wpdb->insert_id;    //log attribute
			
			do_action( 'woocommerce_attribute_added', $wpdb->insert_id, $attribute );
		}
		
		flush_rewrite_rules();	
	}
	 * 
	 */
	
    /* Pages */
    public function importPages() {

        $pages = $this->demo_data['pages'];
        
        if(empty($pages)) return false;
        
		$all_pages = get_pages();
		$page_title_hash = array();
		foreach($all_pages as $ap)
		{
			$page_title_hash[$ap->post_name] = true;
		}
		unset($all_pages);
		
        foreach($pages as $page)
        {
            if(empty($page_title_hash[$page['post_name']]))		//check if this page already does not exist
			{
				$return = wp_insert_post($page, true);
				if(is_integer($return))
				{
					//insert post meta
					if(!empty($post['post_meta']))
					{
						foreach($post['post_meta'] as $key => $value)
						{
							if(is_array($value) && count($value) == 1)
							{
								$value = $value[0];
							}
							add_post_meta($return, $key, $value, true);
						}
					}

					$this->import_log['pages'][] = $return;    //log pages
				}
			}
        } 
    }
    
    /* page import - used in single page mode */
    public static function importSinglePage($page = null, $set_role = false) {
        
        if(empty($page)) return false;
                
        $page_data = unserialize(base64_decode($page['data']));
        $pageid = 0;
        		
        if(!empty($page_data))
        {
            $return = wp_insert_post($page_data[0], true);
            if(is_integer($return))
            {
                $pageid = $return;
            }
        }
        
        if(!empty($set_role) && $pageid > 0)
        {
            if($page['role'] == 'home')
            {
                update_option( 'page_on_front', $pageid );
                update_option( 'show_on_front', 'page' );
            }
            elseif($page['role'] == 'blog')
            {
                update_option( 'page_for_posts', $pageid );
            }
        }
		
		if($pageid > 0)
        {
			//insert post meta
			if(!empty($page_data[0]['post_meta']))
			{
				foreach($page_data[0]['post_meta'] as $key => $value)
				{
					if(is_array($value) && count($value) == 1)
					{
						$value = $value[0];
					}
					add_post_meta($pageid, $key, $value, true);
				}
			}
		}
        
        return $pageid;
    }
     
	/* set home and blog */
	public function importHomeAndBlog() {
		
		$homeblog = $this->demo_data['homeblog'];
        
        if(empty($homeblog)) return false;
		
		//log the current home and blog pages
		$this->import_log['homeblog'] = array(
			'page_on_front'  => get_option('page_on_front', false),
			'show_on_front'  => get_option('show_on_front', 'page'),
			'page_for_posts' => get_option('page_for_posts', false),
		);
		
		//iterate over all imported pages and check their titles for home and blog
		if(!empty($this->import_log['pages']))
		{
			foreach($this->import_log['pages'] as $page)
			{
				$page_obj = get_post($page);
								
				if(!empty($page_obj))
				{					
					if($page_obj->post_title == $homeblog['home'])
					{
						update_option( 'page_on_front', $page_obj->ID );
						update_option( 'show_on_front', 'page' );
					}	
					elseif($page_obj->post_title == $homeblog['blog'])
					{
						update_option( 'page_for_posts', $page_obj->ID );
					}
				}
			}
		}
	}
     
    /* Posts */
    public function importPosts() {

        $posts = $this->demo_data['posts'];
        
        if(empty($posts)) return false;
        
        foreach($posts as $post)
        {
            $post_cats = explode(',', $post['post_category']);
            $post['post_category'] = array();
            foreach($post_cats as $pc)
            {
                if(!empty($this->category_id_cache[$pc]))
                {
                    $post['post_category'][] = $this->category_id_cache[$pc];
                }
            }

            $return = wp_insert_post($post, true);
            if(is_integer($return))
            {
                //insert post meta
				if(!empty($post['post_meta']))
				{
					foreach($post['post_meta'] as $key => $value)
					{
						if($key != '_thumbnail_id')
                        {
                            if(is_array($value) && count($value) == 1)
                            {
                                $value = $value[0];
                            }
                            add_post_meta($return, $key, $value, true);
                        }
					}
				}

				//insert platforms
				if(!empty($post['post_platform']))
				{
					//insert product cats
					$post_platforms = explode(',', $post['post_platform']);
					foreach($post_platforms as $pc)
					{
						wp_add_object_terms($return, $pc, 'platform');
					}
				}

				
				$this->import_log['posts'][] = $return;    //log posts
            }
        }
    }
	
	/* Posts */
    public function importProducts() {

        $posts = $this->demo_data['products'];
        
        if(empty($posts)) return false;
		if(!goodgame_is_woocommerce_active()) return false;
        
        foreach($posts as $post)
        {
            $return = wp_insert_post($post, true);
            
			if(is_integer($return))
            {
                //insert product cats
				$post_cats = explode(',', $post['product_cat']);
				$post['product_cat'] = array();
				foreach($post_cats as $pc)
				{					
					wp_add_object_terms($return, $pc, 'product_cat');
				}

				//insert post meta
				if(!empty($post['post_meta']))
				{
					foreach($post['post_meta'] as $key => $value)
					{
						if(!in_array($key, array('_thumbnail_id', '_wc_rating_count', '_wc_review_count', '_wc_average_rating')))
                        {
                            if(is_array($value) && count($value) == 1)
                            {
                                $value = $value[0];
                            }
                            add_post_meta($return, $key, $value, true);
                        }
					}
				}
				
				$this->import_log['products'][] = $return;    //log posts
            }
        }
    }
     
    /* Menus */
    public function importMenus() {
    
        $menus = $this->demo_data['menus'];
        
        if(empty($menus)) return false;
        
        $all_locations = get_registered_nav_menus();
        foreach($menus as $menu)
        {
            $menu_parent_title_cache = array();
            $menu_id = wp_create_nav_menu($menu['name']);

            if(is_integer($menu_id))
            {
                $this->import_log['menus'][] = $menu_id;    //log menu
                
                foreach($menu['items'] as $item)
                {
                    //Find and set correct objects ID
                    if($item['menu-item-object'] == 'page')
                    {
                        $object = get_page_by_title($item['menu-item-title'], OBJECT, array('page', 'post'));
                        if($object)
                        {
                            $item['menu-item-object-id'] = $object->ID;
                        }
                    }
                    elseif($item['menu-item-object'] == 'category')
                    {
                        $term = get_term_by('name', $item['menu-item-title'], 'category');
                        if($term)
                        {
                            $item['menu-item-object-id'] = $term->term_id;
                        }
                    }
                    elseif($item['menu-item-object'] == 'custom')
                    {
                        if(strpos($item['menu-item-url'], 'http') === false)
                        {
                            $item['menu-item-url'] = home_url('/') . $item['menu-item-url'];
                        }
                    }

                    if(!empty($item['menu-item-parent-title']))
                    {
                        $key = array_search($item['menu-item-parent-title'], $menu_parent_title_cache);
                        if($key)
                        {
                            $item['menu-item-parent-id'] = $key;
                        }
                    }
                    
                    if($item['menu-item-type'] == 'custom' || !empty($item['menu-item-object-id']))
                    {
                        $return = wp_update_nav_menu_item($menu_id, 0, $item);
                        if(is_integer($return))
                        {
                            $menu_parent_title_cache[$return] = $item['menu-item-title'];
                        }
                    }
                }

                if(!empty($menu['location']) && !empty($all_locations[$menu['location']]))
                {
                    $locations = get_theme_mod('nav_menu_locations');
                    $locations[$menu['location']] = $menu_id;
                    set_theme_mod( 'nav_menu_locations', $locations );
                }
            }
        }
    }
    
    
    /* Constellation */
    public function importConstellation() {
    
        $constellation = $this->demo_data['constellation'];
        $new_sidebars = array();
        
        if(empty($constellation)) return false;
                
        //make a list of menu item names for easy access
        $cm_nav_titles = array();
        if(!empty($constellation['cm_sidebars']))
        {    
            foreach($constellation['cm_sidebars'] as $menu_item)
            {
                $cm_nav_titles[$menu_item['post_id']] = $menu_item['title'];
            }
        }
            
        //find correct menu item ids and save the setting
        if(!empty($constellation['menu_data']))
        {
            foreach($constellation['menu_data'] as $menu)
            {
                $items = wp_get_nav_menu_items($menu);
                foreach($items as $item)    //empty for some reason
                {
                    if($item->menu_item_parent == 0) //first level only
                    {
                        $sidebar_item = array_search($item->title, $cm_nav_titles);
                        if($sidebar_item)
                        {
                            $new_sidebars[$item->ID] = array(
                                'post_id' => $item->ID,
                                'sidebar_id' => 'mega_menu_' . $item->ID,
                                'title' => $item->title
                            );

                            //take note of sidebar rename
                            $this->sidebar_rename_log[$constellation['cm_sidebars'][$sidebar_item]['sidebar_id']] = 'mega_menu_' . $item->ID;

                            update_post_meta( $item->db_id, '_cm_mega_menu_status', 1 );

                            unset($cm_nav_titles[$sidebar_item]);
                        }
                    }
                }
            }
        }
        
        if(!empty($new_sidebars))
        {
            //log impeding option change
            $this->replace_log['constellation']['cm_sidebars'] = array('old' => get_option('cm_sidebars'), 'new' => json_encode($new_sidebars));
            $this->replace_log['constellation']['constellation_menu_data'] = array('old' => get_option('constellation_menu_data'), 'new' => json_encode($constellation['menu_data']));
            
            $this->import_log['constellation'] = true;    //log menu
            
            update_option('cm_sidebars', json_encode($new_sidebars));
            update_option('constellation_menu_data', json_encode($constellation['menu_data']));
        }
    }
    
        /* Sidebars and Widgets */
    public function importSidebarsWidgets() {
    
        $sidebars = $this->demo_data['sidebars'];
        
        if(empty($sidebars)) return false;
                
        $sidebars_items = $sidebars['sidebars'];
        
        //update sidebar ids to reflect constellation changes
        if(!empty($this->sidebar_rename_log))
        {
            foreach($this->sidebar_rename_log as $key => $value)
            {
				if($key != $value && !empty($sidebars_items[$key]))
                {
                    $temp = $sidebars_items[$key]; 
                    unset($sidebars_items[$key]);
                    $sidebars_items[$value] = $temp;
                }
            }
        }
        
        //log the impeding change
        $this->replace_log['sidebars']['sidebars_widgets'] = array('old' => get_option('sidebars_widgets'), 'new' => $sidebars_items);
        $this->import_log['sidebars'] = true;    //log menu
        
        update_option( 'sidebars_widgets', $sidebars_items );

        foreach($sidebars['widget_options'] as $name => $widget)
        {
            //log the impeding change
            $this->replace_log['sidebars'][$name] = array('old' => get_option($name), 'new' => $widget);
            
            update_option($name, $widget);
        }
    }
    
    /* Custom sidebars */
    public function importCustomSidebars() {
        
        $sidebars = $this->demo_data['custom_sidebars'];
        if(empty($sidebars)) return false;
        
        //log the impeding change
        $this->replace_log['custom_sidebars'] = array('old' => goodgame_gs('sidebars'), 'new' => $sidebars);
        
        goodgame_ss('sidebars', $sidebars);
    }
    
    
    /* Import Galleries */
    public function importGalleries() {
        
        $galleries = $this->demo_data['galleries'];
        
        foreach($galleries as $gallery)
        {
            $id = wp_insert_post($gallery, true);
            if(is_integer($id) && $this->default_thumb) //if posts was inserted and if the thumb in is place
            {
                $this->import_log['galleries'][] = $id;
                if(!empty($gallery['attachments']))
                {
                    /*$attachments = json_decode($gallery['attachments'], true);
                    if(!empty($attachments['goodgame_galleries']))
                    {
                        foreach($attachments['goodgame_galleries'] as &$attach)
                        {
                            $attach_id = goodgame_insert_attachment($id, GOODGAME_UPLOAD_PATH . 'no-image.png', GOODGAME_UPLOAD_DOMAIN);
                            if(is_integer($attach_id))
                            {
                                $attach['id'] = $attach_id;
                            }
                        }
                        add_post_meta($id, 'attachments', json_encode($attachments));
                    }*/
                }
            }
        } 
    }
    
    
    // Modify preset
    public function modifyPreset() {
        
        if(!empty($this->demo_data['preset']))
        {
            $preset_name = $this->demo_data['preset'];
        
            $all_presets = goodgame_gs('presets', false);
            if(!empty($all_presets[$preset_name]))
            {
                $settings = $all_presets[$preset_name];
                $this->import_log['preset'] = true;    //log that preset has been changed
                
                foreach($settings as $setting_group)
                {
                    foreach($setting_group as $key => $value)
                    {
                        set_theme_mod($key, $value);
                    }
                }
            }
        }
    }
    
    //Save the import log to DB to enable rollback/undo
    public function saveImportLogToDB() {
                
        $index = json_decode(get_option('goodgame_import_index', json_encode(array())), true);
        $index_key = 'goodgame_import_' . $this->demo_name  . '_' . count($index);
        $index[] = $index_key; //improvised auto increment
        
        $data = array(
            'demo'      => $this->demo_name,
            'import'    => $this->import_log,
            'replace'   => $this->replace_log,
            'date'      => date("Y-m-d H:i:s"),
            'status'    => 'active'
        );
        
        add_option($index_key, json_encode($data));
        update_option('goodgame_import_index', json_encode($index));
    }
    
    //rollback the installed demo to a previous state
    public static function rollbackDemo() {
        
        $current = self::getCurrentImport();
        if($current)
        {            
            //Reverse the data imported
            if(!empty($current['import']))
            {
                //remove pages
                if(!empty($current['import']['pages']))
                {
                    $pages = $current['import']['pages'];
                    foreach($pages as $page)
                    {
                        $return = wp_delete_post($page, true);
                    }
                }
                
                //remove posts
                if(!empty($current['import']['posts']))
                {
                    $posts = $current['import']['posts'];
                    foreach($posts as $post)
                    {
                        $return = wp_delete_post($post, true);
                    }
                }
				
				//remove products
                if(!empty($current['import']['products']))
                {
                    $posts = $current['import']['products'];
                    foreach($posts as $post)
                    {
                        $return = wp_delete_post($post, true);
                    }
                }
                
                //remove galleries
                if(!empty($current['import']['galleries']))
                {
                    $galleries = $current['import']['galleries'];
                    foreach($galleries as $gallery)
                    {
                        $return = wp_delete_post($gallery, true);
                    }
                }
                
                //remove categories
                if(!empty($current['import']['categories']))
                {
                    $categories = $current['import']['categories'];
                    foreach($categories as $category)
                    {
                        $return = wp_delete_category($category);
                    }
                }
				
				//remove products categories
                if(!empty($current['import']['product_categories']))
                {
                    $categories = $current['import']['product_categories'];
                    foreach($categories as $category)
                    {
						$return = wp_delete_term( $category, 'product_cat' );
                    }
                }
                
                //remove menus
                if(!empty($current['import']['menus']))
                {
                    $menus = $current['import']['menus'];
                    foreach($menus as $menu)
                    {
                        $return = wp_delete_nav_menu($menu);
                    }
                }
				
				//remove attributes - not functional yet
				/*
				if(!empty($current['import']['product_attributes']))
                {
                    $attrs = $current['import']['product_attributes'];
                    foreach($attrs as $attr)
                    {
                        $return = wp_delete_nav_menu($menu);
                    }
                }*/
            }
            
			
            
            //Reverse the options written
            if(!empty($current['replace']))
            {
                //constellation
                if(!empty($current['replace']['constellation']))
                {
                    foreach($current['replace']['constellation'] as $key => $option)
                    {
                        update_option($key, $option['old']);
                    }
                }
                
                //sidebar widgets
                if(!empty($current['replace']['sidebars']))
                {
                    foreach($current['replace']['sidebars'] as $key => $option)
                    {
                        update_option($key, $option['old']);
                    }
                }
                
                //custom sidebars
                if(!empty($current['replace']['custom_sidebars']))
                {
                    goodgame_ss('sidebars', $current['replace']['custom_sidebars']['old']);
                }
            }
            
            //Set preset to default
			/*
            $all_presets = goodgame_gs('presets', false); 
			$preset_name = key($all_presets);	//first is default
			
            if(!empty($all_presets[$preset_name]))
            {
                $settings = $all_presets[$preset_name];
                
                foreach($settings as $setting_group)
                {
                    foreach($setting_group as $key => $value)
                    {
                        set_theme_mod($key, $value);
                    }
                }
            }
        	*/
			
			//reset home and blog pages to previous settings
			update_option('page_on_front', $current['import']['homeblog']['page_on_front']);
			update_option('show_on_front', $current['import']['homeblog']['show_on_front']);
			update_option('page_for_posts', $current['import']['homeblog']['page_for_posts']);
            
			
            //mark import as inactive
            $current['status'] = 'deactivated';
            $index = json_decode(get_option('goodgame_import_index', json_encode(array())), true);
            if(!empty($index) && !empty($index[count($index)-1]))
            {
                $key = $index[count($index)-1];
                update_option($key, json_encode($current));
            }
			
		            
            return true;
        }
        else 
        {
            return false;
        }
        
    }
    
    //get import log index
    public static function retrieveLog() {
        
        $index = json_decode(get_option('goodgame_import_index', json_encode(array())), true);
        debug($index, 0);
        
        if(!empty($index))
        {
            foreach($index as $item)
            {
                $import = json_decode(get_option($item));
                debug($import, 0);
            }
        }
    }
    
    //get the current import data
    public static function getCurrentImport() {
                
        $index = json_decode(get_option('goodgame_import_index', json_encode(array())), true);
		
        if(!empty($index) && !empty($index[count($index)-1]))
        {
            $key = $index[count($index)-1];
            $option = json_decode(get_option($key, json_encode(false)), true);
            
            //check if the option is active
            if(!empty($option) && !empty($option['status']) && $option['status'] != 'active')
            {
                return false;
            }
            return $option;
        }
        
        return false;
    }
    
}