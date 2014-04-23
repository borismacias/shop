<?php
/*
Plugin Name: SuperCarousel
Plugin URI: http://www.taraprasad.com/supercarousel
Description: A responsive Image and Content carousel wordpress plugin 
Version: 1.0
Author: Taraprasad Swain
Author URI: http://www.taraprasad.com

Copyright 2013 by NT Company (email : ntcomp.themeforest@gmail.com)
*/

include("config.php");

add_action('wp_enqueue_scripts', 'super_scripts_method');

add_filter('enter_title_here', 'change_default_super_title');

add_action('init', 'create_super_post_types');

add_shortcode('supercarousel', 'supercarousel_func' );

add_action('save_post', 'save_super_post_meta');

add_action('admin_init', 'register_super_meta_box');

add_action('admin_print_scripts', 'admin_inline_js');

add_filter('manage_edit-supercarousel_columns', 'set_custom_edit_supercarousel_columns' );

add_action('manage_supercarousel_posts_custom_column' , 'custom_supercarousel_column', 10, 2 );

function register_super_meta_box() {
	add_meta_box('superimage_meta', __('Super Image Slides'), 'supercarousel_image_meta', 'superimage', 'normal', 'high');
	add_meta_box('super_meta', __('Super Carousel Settings'), 'supercarousel_meta', 'supercarousel', 'normal', 'high');
}

function admin_inline_js() {
	wp_enqueue_script('adminsuperjs', plugins_url('/js/admin.js', __FILE__));
	wp_enqueue_style('adminsuperstyles', plugins_url('/css/admin.css', __FILE__));
	
	wp_enqueue_media();
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox-js', site_url() . '/' . WPINC .'/js/thickbox/thickbox.js');
	wp_enqueue_style('thickbox-css', site_url() . '/' . WPINC .'/js/thickbox/thickbox.css');
	?>
	<script type='text/javascript'>
	/* <![CDATA[ */
	var thickboxL10n = {
		next: "Next >",
		prev: "< Prev",
		image: "Image",
		of: "of",
		close: "Close"
	};
	try{convertEntities(thickboxL10n);}catch(e){};
	/* ]]> */
var tb_closeImage = "<?php bloginfo('wpurl'); ?>/wp-includes/js/thickbox/tb-close.png";
var tb_pathToImage = "<?php bloginfo('wpurl'); ?>/wp-includes/js/thickbox/loadingAnimation.gif";
</script>
	<?php
}

function supercarousel_image_meta() {
	include('admin-views/supercarousel-image-meta.php');
}

function supercarousel_meta() {
	include('admin-views/supercarousel-meta.php');
}

function supercarousel_func($atts) {
	$postid = (int)$atts['id'];
	
	ob_start();
	if($postid>0) {
		$post = get_post($postid);
		if($post->post_type=='supercarousel') {
			include('views/supercarousel.php');
		}
	}
	$string = ob_get_contents();
	ob_end_clean();
	return $string;
}

function create_super_post_types() {
	register_post_type( 'supercarousel',
	array(
      'labels' => array(
        'name' => __( 'Super Carousel' ),
		'add_new_item' => __('Add New Carousel'),
        'singular_name' => __( 'Menu item' )
	),
	'public' => true,
	'exclude_from_search' => true,
	'show_in_nav_menus' => false,
	'show_in_menu' => true,
	'supports' => array( 'title', 'page-attributes', 'thumbnail'),
	'rewrite' => false
	)
	);
	
	register_taxonomy(
		'super_category',
		'supercontent',
		array(
			'labels' => array(
			'name' => _x( 'Categories', 'taxonomy general name' ),
			'search_items' =>  __( 'Search Content Categories' ),
			'all_items' => __( 'All Content Categories' ),
			'parent_item' => __( 'Parent Category' ),
			'parent_item_colon' => __( 'Parent Category:' ),
			'edit_item' => __( 'Edit Category' ),
			'update_item' => __( 'Update Category' ),
			'add_new_item' => __( 'Add New Content Category' ),
			'new_item_name' => __( 'New Category Name' ),
			'menu_name' => __( 'Categories' ),
		),
		'public' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
		'show_admin_column'=>false,
		'hierarchical'=>true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => false
		)
	);
	
	register_post_type( 'supercontent',
	array(
      'labels' => array(
        'name' => __( 'Super Content' ),
		'add_new_item' => __('Add Content Slide'),
        'singular_name' => __( 'Menu item' )
	),
	'taxonomies' => array('super_category'),
	'public' => true,
	'has_archive' => false,
	'exclude_from_search' => true,
	'show_in_nav_menus' => true,
	'show_in_menu' => true,
	'supports' => array( 'title', 'editor', 'page-attributes'),
	'rewrite' => false,
	'query_var' => false
	)
	);
	
	register_post_type( 'superimage',
	array(
      'labels' => array(
        'name' => __( 'Super Image' ),
		'add_new_item' => __('Add Images'),
        'singular_name' => __( 'Menu item' )
	),
	'public' => true,
	'exclude_from_search' => true,
	'show_in_nav_menus' => false,
	'show_in_menu' => true,
	'supports' => array( 'title', 'page-attributes'),
	'rewrite' => false
	)
	);
}

function save_super_post_meta($postId) {
	
	if(function_exists('get_current_screen')) {
		$screen = get_current_screen();
		
		if($screen->post_type=='superimage' and isset($_POST)) {
			//update_post_meta($postId, 'title_text', $_POST['title_text']);
			if(isset($_POST['images']) and is_array($_POST['images'])) {
				foreach($_POST['images']['caption'] as $i=>$row) {
					$_POST['images']['caption'][$i] = urlencode($_POST['images']['caption'][$i]);
				}
				//, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
				$data = json_encode($_POST['images']);
			}else {
				$data = array();
			}
			update_post_meta($postId, 'images', $data);
		}else if($screen->post_type=='supercarousel' and isset($_POST)) {
			//update_post_meta($postId, 'title_text', $_POST['title_text']);
			if(isset($_POST['super']) and is_array($_POST['super'])) {
				$data = json_encode($_POST['super']);
			}else {
				$data = array();
			}
			update_post_meta($postId, 'supersettings', $data);
		}
	}
}

function change_default_super_title( $title ){
	$screen = get_current_screen();

	if($screen->post_type == 'superimage') {
		$title = 'Enter Image Carousel Name';
	}else if($screen->post_type == 'supercontent') {
		$title = 'Enter Content Slide Name';
	}else if($screen->post_type == 'supercarousel') {
		$title = 'Enter Carousel Name';
	}
	
	return $title;
}

function super_scripts_method() {
	wp_enqueue_script('sucarousel', plugins_url('js/jquery.supercarousel.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('imageloaded', plugins_url('js/jquery.imagesloaded.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('ismouseover', plugins_url('js/jquery.ismouseover.js', __FILE__), array('jquery'));
	wp_enqueue_script('easing', plugins_url('js/jquery.easing.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('easingCompatible', plugins_url('js/jquery.easing.compatibility.js', __FILE__), array('jquery'));
	wp_enqueue_script('mousewheel', plugins_url('js/jquery.mousewheel.js', __FILE__), array('jquery'));
	wp_enqueue_script('touchSwipe', plugins_url('js/jquery.touchSwipe.min.js', __FILE__), array('jquery'));
	
	wp_enqueue_script('prettyPhoto', plugins_url('js/jquery.prettyPhoto.js', __FILE__), array('jquery'));
	
	wp_enqueue_script('watch-js', plugins_url('js/jquery.watch.js', __FILE__), array('jquery'));
	
	wp_enqueue_style('styles', plugins_url('css/supercarousel.css', __FILE__));
	
	wp_enqueue_style('prettyPhotoStyles', plugins_url('css/prettyPhoto.css', __FILE__));
}

function set_custom_edit_supercarousel_columns($columns) {
	$tempdate = $columns['date'];
	unset($columns['date']);
	return $columns + array('short_code' => __('Short Code'), 'date'=> $tempdate);
}

function custom_supercarousel_column( $column, $post_id ) {
    switch($column) {
      case 'short_code':
		echo '<input type="text" onclick="this.select();" value="[supercarousel id='.$post_id.']" />';
        break;
    }
}

function supershow($val) {
	if(is_array($val) or is_object($val)) {
		echo "<pre>";
		print_r($val);
		echo "</pre>";
	}else {
		echo $val;
	}
}

function get_super_thumb($img) {
	$imgx = array_reverse(explode('.', $img));
	$imgx[1] = $imgx[1] . '-150x150';
	$imgx = array_reverse($imgx);
	return join('.', $imgx);
}

function decode_string( $encode , $options ) {
    $escape = '\\\0..\37';
    $needle = array();
    $replace = array();

    if ( $options & JSON_HEX_APOS ) {
        $needle[] = "'";
        $replace[] = 'u0027';
    } else {
        $escape .= "'";
    }

    if ( $options & JSON_HEX_QUOT ) {
        $needle[] = '"';
        $replace[] = 'u0022';
    } else {
        $escape .= '"';
    }

    if ( $options & JSON_HEX_AMP ) {
        $needle[] = '&';
        $replace[] = 'u0026';
    }

    if ( $options & JSON_HEX_TAG ) {
        $needle[] = '<';
        $needle[] = '>';
        $replace[] = 'u003C';
        $replace[] = 'u003E';
    }

    //$encode = addcslashes( $encode , $escape );
    $encode = str_replace( $replace, $needle , $encode );

    return $encode;
}

function generate_super_checkbox($name, $value, $check) {
	$selected = '';
	if($check==$value) {
		$selected = ' checked="checked"';
	}
	return '<input type="checkbox" name="'.$name.'" id="'.$name.'" value="'.$value.'"'.$selected.' />';
}

function generate_super_textbox($name, $value='', $param='') {
	return '<input type="text" name="'.$name.'" id="'.$name.'" value="'.$value.'" '.$param.' />';
}
?>