<?php

	class ImageMapperAdmin
	{
		var $main, $path, $name, $url;
		
		function __construct($file)
		{
			$this->main = $file;
			$this->init();
			return $this;
		}
		
		function init() 
		{
			$this->path = dirname( __FILE__ );
			$this->name = basename( $this->path );
			$this->url = plugins_url( "/{$this->name}/" );
			
			if(is_admin()) 
			{
				register_activation_hook( $this->main , array(&$this, 'activate') );
				add_action('admin_menu', array(&$this, 'admin_menu')); 
				add_action('wp_ajax_mapper_save', array(&$this, 'ajax_save'));  
				add_action('wp_ajax_mapper_preview', array(&$this, 'ajax_preview'));
				add_action('wp_ajax_mapper_frontend_get', array(&$this, 'ajax_frontend_get'));
				add_action('wp_ajax_nopriv_mapper_frontend_get', array(&$this, 'ajax_frontend_get'));
			}
			else
			{
				add_action('wp', array(&$this, 'frontend_includes'));
				add_shortcode('image_mapper', array(&$this, 'shortcode') );
			}
		}
		
		function activate() 
		{
			global $wpdb;
		
			$table_name = $wpdb->base_prefix . 'image_mapper';
		
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				$image_mapper_sql = "CREATE TABLE " . $table_name . " 
							(
							  id mediumint(9) NOT NULL AUTO_INCREMENT,
							  name tinytext NOT NULL COLLATE utf8_general_ci,
							  settings text NOT NULL COLLATE utf8_general_ci,
							  items text NOT NULL COLLATE utf8_general_ci,
							  PRIMARY KEY (id)
							);";
		
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($image_mapper_sql);			
			}
	
		}	
		
		function admin_menu() 
		{
			$mappermenu = add_menu_page( 'Image Mapper', 'Image Mapper', 'manage_options', 'imagemapper', array(&$this, 'admin_page'));
			$submenu = add_submenu_page( 'imagemapper', 'Image Mapper', 'Add New', 'manage_options', 'imagemapper_edit', array(&$this, 'admin_edit_page'));
			
			add_action('load-'.$mappermenu, array(&$this, 'admin_menu_scripts')); 
			add_action('load-'.$submenu, array(&$this, 'admin_menu_scripts')); 
			add_action('load-'.$mappermenu, array(&$this, 'admin_menu_styles')); 
			add_action('load-'.$submenu, array(&$this, 'admin_menu_styles')); 
		}
		
		function admin_menu_scripts() 
		{
			wp_enqueue_script('post');
			wp_enqueue_script('farbtastic');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('image-mapper-admin-js', $this->url . 'js/image_mapper_admin.js' );
			wp_enqueue_script('jQuery-easing', $this->url . 'js/frontend/jquery.easing.1.3.js' );
			
			wp_enqueue_script('jQuery-mousew', $this->url . 'js/frontend/jquery.mousewheel.min.js' );
			wp_enqueue_script('jQuery-customScroll-imapper', $this->url . 'js/frontend/jquery.mCustomScrollbar.min.js' );
			
			wp_enqueue_script('jquery-ui-core', array(), 1.0, true);
			wp_enqueue_script('jquery-ui-widget', array(), 1.0, true);
			wp_enqueue_script('jquery-ui-sortable', array(), 1.0, true);
			wp_enqueue_script('jquery-ui-slider', array(), 1.0, true);
			wp_enqueue_script('jquery-ui-draggable', array(), 1.0, true);
			wp_deregister_script('iris');
			wp_enqueue_script('iris-imapper', $this->url . 'js/iris.min.js', array(), 1.0, true);
			
			wp_enqueue_script('rollover-imapper', $this->url . 'js/frontend/rollover.js' );
		}
		
		function admin_menu_styles() 
		{
			wp_enqueue_style('farbtastic');	
			wp_enqueue_style('thickbox');
			wp_enqueue_style( 'image-mapper-admin-css', $this->url . 'css/image_mapper_admin.css' );
			wp_enqueue_style( 'image-mapper-thick-css', $this->url . 'css/thickbox.css' );
			wp_enqueue_style( 'image-mapper-css', $this->url . 'css/frontend/image_mapper.css' );
			wp_enqueue_style( 'customScroll-css', $this->url . 'css/frontend/jquery.mCustomScrollbar.css' );
			wp_enqueue_style('font-awesome-css', $this->url . 'font-awesome/css/font-awesome.css');
		}
		
		function ajax_save() 
		{
			$id = false;
			$settings = '';
			$items = '';
			foreach( $_POST as $key => $value) 
			{
				if ($key != 'action') 
				{
					if ($key == 'image_mapper_id')
					{
						if ($value != '')
						{
							$id = (int)$value;
						}
					}
					else if ($key == 'image_mapper_title')
					{
						$name = stripslashes($value);
					}
					else if(strpos($key,'sort') === 0)
					{
						if (substr($key, 4, 1) != '-')
							$items .= $key . '::' . stripslashes($value) . '||';
					}
					else 
					{
						$settings .= $key . '::' . stripslashes($value) . '||';
					}
				}
			}
			if ($items != '') $items = substr($items,0,-2);
			if ($settings != '') $settings = substr($settings,0,-2);
			global $wpdb;
			$table_name = $wpdb->base_prefix . 'image_mapper';
			if($id) 
			{	
				$wpdb->update(
					$table_name,
					array(
						'name'=>$name,
						'settings'=>$settings,
						'items'=>$items),
					array( 'id' => $id ),
					array( 
						'%s',
						'%s',
						'%s'),
					array('%d')
				);
			}
			else 
			{
				$wpdb->insert(
					$table_name,
					array(
						'name'=>$name,
						'settings'=>$settings,
						'items'=>$items),	
					array(
						'%s',
						'%s',
						'%s')						
					
				);
				$id = $wpdb->insert_id;
			}
			
				
			echo $id;
			die();
		}
		
		function admin_page() 
		{
			include_once($this->path . '/pages/image_mapper_index.php');
		}
	
		function admin_edit_page() 
		{
			include_once($this->path . '/pages/image_mapper_edit.php');
		}
	
		function shortcode($atts) 
		{
			extract(shortcode_atts(array
			(
				'id' => ''
			), $atts));

			include($this->path . '/pages/image_mapper_frontend.php');
			$frontHtml = preg_replace('/\s+/', ' ',$frontHtml);

			return do_shortcode($frontHtml);
		}
		
		function frontend_includes() 
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('jQuery-ui', 'http://code.jquery.com/ui/1.10.1/jquery-ui.js');
			wp_enqueue_script('jQuery-easing-imapper', $this->url . 'js/frontend/jquery.easing.1.3.js' );
			wp_enqueue_script('jQuery-image-mapper', $this->url . 'js/frontend/jquery.image_mapper.js' );
			wp_enqueue_script('jQuery-mousew-imapper', $this->url . 'js/frontend/jquery.mousewheel.min.js' );
			wp_enqueue_script('jQuery-customScroll-imapper', $this->url . 'js/frontend/jquery.mCustomScrollbar.min.js' );
			wp_enqueue_script('jQuery-ui-imapper');
			wp_enqueue_script('rollover-imapper', $this->url . 'js/frontend/rollover.js' );
			wp_enqueue_script('jquery-prettyPhoto-imapper', $this->url . 'js/frontend/jquery.prettyPhoto.js' );
			//wp_enqueue_script('imapper-pie', $this->url . 'js/PIE.js');
			
			wp_enqueue_style( 'image-mapper-css', $this->url . 'css/frontend/image_mapper.css' );
			wp_enqueue_style( 'customScroll-css-imapper', $this->url . 'css/frontend/jquery.mCustomScrollbar.css' );
			wp_enqueue_style( 'prettyPhoto-css-imapper', $this->url . 'css/frontend/prettyPhoto.css' );
			wp_enqueue_style('font-awesome-css', $this->url . 'font-awesome/css/font-awesome.css');
			
		}
	}
?>