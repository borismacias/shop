<?php

/**
 * @package wpStickies
 * @version 2.0.2
 */
/*

Plugin Name: wpStickies
Plugin URI: http://codecanyon.net/user/kreatura/
Description: Premium Image Tagging Plugin for WordPress
Version: 2.0.2
Author: Kreatura Media
Author URI: http://kreaturamedia.com/
*/


/********************************************************/
/*                        Actions                       */
/********************************************************/

	include 'js/class.php';
	$GLOBALS['wpsPluginVersion'] = '2.0.2';
	$GLOBALS['wpsPluginPath'] = plugins_url('', __FILE__);
	$GLOBALS['wpsDatabaseVersion'] = '1.0';
	$GLOBALS['wpsAutoUpdateBox'] = true;
	$GLOBALS['wpsRepoAPI'] = 'http://repo.kreatura.hu/';
	$GLOBALS['wpsPluginSlug'] = basename(dirname(__FILE__));


	// Activation hook for creating the initial DB table
	register_activation_hook(__FILE__, 'wpstickes_activation_scripts');

	// Run activation scripts when adding new sites to a multisite installation
	add_action('wpmu_new_blog', 'wpstickes_new_site');

	// Auto update
	if(get_option('wpstickies-validated', '0')) {
		add_filter('pre_set_site_transient_update_plugins', 'wpstickies_check_for_plugin_update');
		add_filter('plugins_api', 'wpstickies_plugin_api_call', 10, 3);
	}

	// Register custom settings menu
	add_action('admin_menu', 'wpstickies_settings_menu');

	// Link content resources
	add_action('wp_enqueue_scripts', 'wpstickies_enqueue_content_res');

	// Link admin resources
	add_action('admin_enqueue_scripts', 'wpstickies_enqueue_admin_res');

	// Init wpStickies
	add_action('wp_head', 'wpstickies_js');

	// Help menu
	add_filter('contextual_help', 'wpstickies_help', 10, 3);

	// Load plugin locale
	add_action('plugins_loaded', 'wpstickies_load_lang');

	// Preview
	add_action('init', 'wpstickies_load_preview');

	// Load plugin locale
	add_action('plugins_loaded', 'wpstickes_load_lang');

	// Add admin ajax actions
	add_action('wp_ajax_wpstickies_verify_purchase_code', 'wpstickies_verify_purchase_code');
	add_action('wp_ajax_wpstickies_accept', 'wpstickies_accept');
	add_action('wp_ajax_wpstickies_reject', 'wpstickies_reject');
	add_action('wp_ajax_wpstickies_restore', 'wpstickies_restore');
	add_action('wp_ajax_wpstickies_delete', 'wpstickies_delete');

	// Front-end actions
	add_action('wp_ajax_wpstickies_insert', 'wpstickies_insert');
	add_action('wp_ajax_nopriv_wpstickies_insert', 'wpstickies_insert');

	add_action('wp_ajax_wpstickies_update', 'wpstickies_update');
	add_action('wp_ajax_nopriv_wpstickies_update', 'wpstickies_update');

	add_action('wp_ajax_wpstickies_get', 'wpstickies_get');
	add_action('wp_ajax_nopriv_wpstickies_get', 'wpstickies_get');

	add_action('wp_ajax_wpstickies_remove', 'wpstickies_remove');
	add_action('wp_ajax_nopriv_wpstickies_remove', 'wpstickies_remove');

	add_action('wp_ajax_wpstickies_image_settings', 'wpstickies_image_settings');
	add_action('wp_ajax_nopriv_wpstickies_image_settings', 'wpstickies_image_settings');


/********************************************************/
/*                   wpStickies locale                  */
/********************************************************/
function wpstickes_load_lang() {
	load_plugin_textdomain('wpStickies', false, basename(dirname(__FILE__)) . '/languages/' );
}

/********************************************************/
/*             wpStickies activation scripts            */
/********************************************************/

function wpstickes_activation_scripts() {

	// Multi-site
	if(is_multisite()) {

		// Get WPDB Object
		global $wpdb;

		// Get current site
		$old_site = $wpdb->blogid;

		// Get all sites
		$sites = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));

		// Iterate over the sites
		foreach($sites as $site) {
			switch_to_blog($site);
			wpstickes_create_db_tables();
		}

		// Switch back the old site
		switch_to_blog($old_site);

	// Single-site
	} else {
		wpstickes_create_db_tables();
	}
}


/********************************************************/
/*            wpStickies new site activation            */
/********************************************************/

function wpstickes_new_site($blog_id) {

    // Get WPDB Object
    global $wpdb;

    // Get current site
	$old_site = $wpdb->blogid;

	// Switch to new site
	switch_to_blog($blog_id);

	// Run activation scripts
	wpstickes_create_db_tables();

	// Switch back the old site
	switch_to_blog($old_site);

}


/********************************************************/
/*                 Activation Scripts                   */
/********************************************************/
function wpstickes_create_db_tables() {

	// Create a new role for users who has capability to
	// manage and create stickes
	add_role( 'wpstickiesadmins', 'wpStickies Admins', array('read', 'level_0') );

	// Get WPDB Object and WP Stickies DB version
	global $wpdb;
	$wpstickies_db_version = $GLOBALS['wpsDatabaseVersion'];

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Building the query
	$sql = "CREATE TABLE $table_name (
				id INT(12) NOT NULL AUTO_INCREMENT,
				image VARCHAR(200) NOT NULL,
				image_original VARCHAR(200) NOT NULL,
				data TEXT NOT NULL,
				user_id INT(12) NOT NULL,
				user_name VARCHAR(50) NOT NULL,
				date_c INT(10) NOT NULL,
				date_m INT(10) NOT NULL,
				flag_hidden TINYINT(1) NOT NULL DEFAULT  1,
				flag_deleted TINYINT(1) NOT NULL DEFAULT  0,
				PRIMARY KEY  (id)
			);";

	// Table name
	$table_name = $wpdb->prefix . "wpstickies_images";

	// Building the query for images settings
	$sql2 = "CREATE TABLE $table_name (
				id INT(12) NOT NULL AUTO_INCREMENT,
				image VARCHAR(200) NOT NULL,
				image_original VARCHAR(200) NOT NULL,
				data TEXT NOT NULL,
				date_c INT(10) NOT NULL,
				date_m INT(10) NOT NULL,
				PRIMARY KEY  (id),
				UNIQUE KEY image (image)
			);";

	// Executing the query
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	dbDelta($sql2);

	// Save DB version
	update_option('wpstickies-db-version', $wpstickies_db_version);
}

/********************************************************/
/*               Convert to relative URLs               */
/********************************************************/
function wpstickies_convert_absolute_urls() {

	// Get WPDB Object
	global $wpdb;

	//
	// STICKIES
	//

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get stickies
	$stickies = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY date_c DESC" );

	foreach($stickies as $item) {

		// Get url
		$url = $item->image;

		// New URL
		$new_url = parse_url($url, PHP_URL_PATH);

		if($url == $new_url) {
			continue;
		}

		// Save
		$wpdb->query(
			$wpdb->prepare("UPDATE $table_name SET
							image = '%s'
						WHERE id = '%d'
						ORDER BY date_m DESC
						LIMIT 1",
						$new_url,
						$item->id
			)
		);
	}

	//
	// IMAGE SETTINGS
	//

	// Table name
	$table_name = $wpdb->prefix . "wpstickies_images";

	// Get stickies
	$images = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY date_c DESC" );

	foreach($images as $item) {

		// Get url
		$url = $item->image;

		// New URL
		$new_url = parse_url($url, PHP_URL_PATH);

		if($url == $new_url) {
			continue;
		}

		// Save
		$wpdb->query(
			$wpdb->prepare("UPDATE $table_name SET
							image = '%s'
						WHERE id = '%d'
						ORDER BY date_m DESC
						LIMIT 1",
						$new_url,
						$item->id
			)
		);
	}

}

/********************************************************/
/*               Convert to absolute URLs               */
/********************************************************/
function wpstickies_convert_relative_urls() {

	// Get WPDB Object
	global $wpdb;

	//
	// STICKIES
	//

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get stickies
	$stickies = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY date_c DESC" );

	foreach($stickies as $item) {

		// Get url
		$url = $item->image_original;

		// Save
		$wpdb->query(
			$wpdb->prepare("UPDATE $table_name SET
							image = '%s'
						WHERE id = '%d'
						ORDER BY date_m DESC
						LIMIT 1",
						$url,
						$item->id
			)
		);
	}

	//
	// IMAGE SETTINGS
	//

	// Table name
	$table_name = $wpdb->prefix . "wpstickies_images";

	// Get stickies
	$images = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY date_c DESC" );

	foreach($images as $item) {

		// Get url
		$url = $item->image_original;

		// Save
		$wpdb->query(
			$wpdb->prepare("UPDATE $table_name SET
							image = '%s'
						WHERE id = '%d'
						ORDER BY date_m DESC
						LIMIT 1",
						$url,
						$item->id
			)
		);
	}

}

/********************************************************/
/*                 Check purchase code                  */
/********************************************************/
function wpstickies_verify_purchase_code() {

	// Build URL
	$url = 'http://activate.kreatura.hu?';
	$url.= 'plugin='.urlencode('wpStickies').'&';
	$url.= 'code='.urlencode($_POST['purchase_code']);

	// Store purchase code
	update_option('wpstickies-purchase-code', $_POST['purchase_code']);

	// Make the call
	$response = wp_remote_post($url);
	$response = $response['body'];

	// Check validation
	if($response == 'valid') {

		// Store validity
		update_option('wpstickies-validated', '1');

		// Show message
		die(json_encode(array('success' => true, 'message' => __('Thank you for purchasing wpStickies. You successfully validated your purchase code for auto-updates.', 'wpStickies'))));

	} else {

		// Store validity
		update_option('wpstickies-validated', '0');

		// Show message
		die(json_encode(array('success' => false, 'message' => __("Your purchase code doesn't appear to be valid. Please make sure that you entered your purchase code correctly.", "wpStickies"))));
	}
}

/********************************************************/
/*                wpStickies Auto-update                */
/********************************************************/

function wpstickies_check_for_plugin_update($checked_data) {

	// Get WP version
	global  $wp_version;

	// Get purchase code
	$code = get_option('wpstickies-purchase-code', '');

	//Comment out these two lines during testing.
	if (empty($checked_data->checked))
		return $checked_data;

	$args = array(
		'slug' => $GLOBALS['wpsPluginSlug'],
		'version' => $checked_data->checked[$GLOBALS['wpsPluginSlug'] .'/'. strtolower($GLOBALS['wpsPluginSlug']) .'.php'],
	);

	$request_string = array(
			'body' => array(
				'action' => 'basic_check',
				'code' => $code,
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url'))
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

	// Start checking for an update
	$raw_response = wp_remote_post($GLOBALS['wpsRepoAPI'], $request_string);

	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);

	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$GLOBALS['wpsPluginSlug'] .'/'. strtolower($GLOBALS['wpsPluginSlug']) .'.php'] = $response;

	return $checked_data;
}

function wpstickies_plugin_api_call($def, $action, $args) {

	// Get WP version
	global $wp_version;

	// Get purchase code
	$code = get_option('wpstickies-purchase-code', '');

	if (!isset($args->slug) || ($args->slug != $GLOBALS['wpsPluginSlug']))
		return false;

	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$GLOBALS['wpsPluginSlug'] .'/'. strtolower($GLOBALS['wpsPluginSlug']) .'.php'];
	$args->version = $current_version;

	$request_string = array(
			'body' => array(
				'action' => $action,
				'code' => $code,
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url'))
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

	$request = wp_remote_post($GLOBALS['wpsRepoAPI'], $request_string);

	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>', 'wpStickies'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);

		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred', 'wpStickies'), $request['body']);
	}

	return $res;
}

/********************************************************/
/*                   wpStickies locale                  */
/********************************************************/
function wpstickies_load_lang() {
	load_plugin_textdomain('wpStickies', false, basename(dirname(__FILE__)) . '/languages/' );
}

/********************************************************/
/*               Enqueue Content Scripts                */
/********************************************************/

	function wpstickies_enqueue_content_res() {

		wp_enqueue_script('wpstickies_js', $GLOBALS['wpsPluginPath'].'/js/wpstickies.kreaturamedia.jquery.js', array('jquery'), '2.0.2' );
		wp_localize_script( 'wpstickies_js', 'WPStickies', array( 'ajaxurl' => admin_url('admin-ajax.php') ) );

		wp_enqueue_script('jquery_easing', $GLOBALS['wpsPluginPath'].'/js/jquery-easing-1.3.js', array('jquery'), '1.3.0' );
		wp_enqueue_style('wpstickies_css', $GLOBALS['wpsPluginPath'].'/css/wpstickies.css', array(), '2.0.2' );
	}


/********************************************************/
/*                Enqueue Admin Scripts                 */
/********************************************************/

	function wpstickies_enqueue_admin_res() {

		if(strstr($_SERVER['REQUEST_URI'], 'wpstickies')) {

			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');

			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');

			wp_enqueue_script('wpstickies_admin', $GLOBALS['wpsPluginPath'].'/js/admin.js', array('jquery'), '2.0.2' );
			wp_enqueue_style('wpstickies_admin', $GLOBALS['wpsPluginPath'].'/css/admin.css', array(), '2.0.2' );
			wp_enqueue_script('tags_input_js', $GLOBALS['wpsPluginPath'].'/js/jquery.tagsinput.min.js', array('jquery'), '2.0.2' );
			wp_enqueue_style('tags_input_css', $GLOBALS['wpsPluginPath'].'/css/jquery.tagsinput.css', array(), '2.0.2' );
		}
	}

/********************************************************/
/*                 Loads settings menu                  */
/********************************************************/
function wpstickies_settings_menu() {

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get custom capabilities
	$options['capability'] = empty($options['capability']) ? 'manage_options' : $options['capability'];

	// Create new top-level menu
	$GLOBALS['options_page'] = add_menu_page('wpStickies', 'wpStickies', $options['capability'], 'wpstickies', 'wpstickies_settings_page', $GLOBALS['wpsPluginPath'].'/img/icon_16x16.png');

	// Call register settings function
	add_action( 'admin_init', 'wpstickies_register_settings' );
}

/********************************************************/
/*                    wpStickies Help                   */
/********************************************************/
function wpstickies_help($contextual_help, $screen_id, $screen) {


	if(strstr($_SERVER['REQUEST_URI'], 'wpstickies')) {

		if(function_exists('file_get_contents')) {

			// Overview
			$screen->add_help_tab(array(
			   'id' => 'wps_overview',
			   'title' => 'Overview',
			   'content' => file_get_contents(dirname(__FILE__).'/docs/overview.html')
			));

			// What's new?
			$screen->add_help_tab(array(
			   'id' => 'wps_whats_new',
			   'title' => 'What\'s new?',
			   'content' => file_get_contents(dirname(__FILE__).'/docs/whatsnew.html')
			));

			// Getting started
			$screen->add_help_tab(array(
			   'id' => 'wps_gettingstarted',
			   'title' => 'Getting started',
			   'content' => file_get_contents(dirname(__FILE__).'/docs/gettingstarted.html')
			));

			// About selectors
			$screen->add_help_tab(array(
			   'id' => 'wps_selectors',
			   'title' => 'About selectors',
			   'content' => file_get_contents(dirname(__FILE__).'/docs/selectors.html')
			));

			// Permission control
			$screen->add_help_tab(array(
			   'id' => 'wps_permissions',
			   'title' => 'Permission control',
			   'content' => file_get_contents(dirname(__FILE__).'/docs/permissions.html')
			));

			// Language support
			$screen->add_help_tab(array(
			   'id' => 'wps_language',
			   'title' => 'Language support',
			   'content' => file_get_contents(dirname(__FILE__).'/docs/language.html')
			));

		} else {

			// Error
			$screen->add_help_tab(array(
				'id' => 'error',
				'title' => 'Error',
				'content' => 'This help section couldn\'t show you the documentation because your server don\'t support the "file_get_contents" function'
			));
		}
	}
}

/********************************************************/
/*                  Register settings                   */
/********************************************************/
function wpstickies_register_settings() {

	// Save settings
	if(isset($_POST['posted']) && strstr($_SERVER['REQUEST_URI'], 'wpstickies')) {

		// Retrieve options
		$options = get_option('wpstickies-options');
		$options = empty($options) ? array() : $options;
		$options = is_array($options) ? $options : unserialize($options);

		// Get users data
		global $current_user;
		get_currentuserinfo();

		// Get user role
		$role = wpstickies_get_user_role($current_user->ID);

		// Test user role and permission to change settings
		if($role != 'administrator' && !isset($options['allow_settings_change'])) {
			die('You don\'t have permission to change plugin settings!');
		}

		// Add option if it is not extists yet
		if(get_option('wpstickies-options') === false) {
			add_option('wpstickies-options', serialize($_POST['wpstickies-options']));

		// Update option
		} else {
			update_option('wpstickies-options', serialize($_POST['wpstickies-options']));
		}

		// Convert URLs?
		if(
			(isset($options['relative_urls']) && !isset($_POST['wpstickies-options']['relative_urls'])) ||
			(!isset($options['relative_urls']) && isset($_POST['wpstickies-options']['relative_urls']))
		) {
			//echo 'convert';
			if(isset($_POST['wpstickies-options']['relative_urls'])) {
				wpstickies_convert_absolute_urls();
			} else {
				wpstickies_convert_relative_urls();
			}
		}

		die('SUCCESS');
	}
}

/********************************************************/
/*                  Settings page HTML                  */
/********************************************************/
function wpstickies_settings_page() {

	include(dirname(__FILE__).'/settings.php');

}

/********************************************************/
/*                    Head init code                    */
/********************************************************/

function wpstickies_js() {

	// Retrieve options
	$option = get_option('wpstickies-options');
	$options = empty($option) ? array() : $option;
	$options = is_array($options) ? $options : unserialize($options);

	include(dirname(__FILE__).'/init.php');

	echo $data;
}

/********************************************************/
/*                    Head init code                    */
/********************************************************/

function wpstickies_get_user_role($uid) {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	if(isset($current_user->roles) && is_array($current_user->roles)) {
		foreach($current_user->roles as $item) {
			$role = $item;
			break;
		}
	}

	if(!isset($role)) {
		return 'non-user';
	}

	return $role;
}

/********************************************************/
/*                        PREVIEW                       */
/********************************************************/

function wpstickies_load_preview() {

	// Product page
	if(isset($_GET['page']) && $_GET['page'] == 'wpstickies_preview') {

		if(file_exists( dirname( __FILE__ ) . '/preview.php')) {
			include( dirname( __FILE__ ) . '/preview.php') ;
			exit;
		}
	}
}

function wpstickies_allow_creatation($uid) {

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Default values
	$options['create_roles'] = empty($options['create_roles']) ? 'administrator' : strtolower($options['create_roles']);
	$options['create_custom_roles'] = empty($options['create_custom_roles']) ? array() : explode(',', $options['create_custom_roles']);

	// Get user's role
	$role = wpstickies_get_user_role($uid);

	// Set default values
	$allowCreate = false;
	$hidden = 1;

	// Identify permissions
	if($options['create_roles'] == 'everyone') {

		if($role == 'administrator') {
			$allowCreate = true;
			$hidden = 0;
		} else {
			$allowCreate = true;
		}

	} elseif($options['create_roles'] == 'administrator') {

		if($role == 'administrator') {
			$allowCreate = true;
			$hidden = 0;
		}

	} elseif($options['create_roles'] == 'wpstickiesadmins') {

		if($role == 'administrator') {
			$allowCreate = true;
			$hidden = 0;

		} elseif($role == 'wpstickiesadmins') {
			$allowCreate = true;
		}

	} elseif($options['create_roles'] == 'subscribers') {

		if($role == 'administrator') {
			$allowCreate = true;
			$hidden = 0;

		} elseif($role == 'wpstickiesadmins') {
			$allowCreate = true;

		} elseif($role == 'subscriber') {
			$allowCreate = true;
		}

	} elseif($options['create_roles'] == 'custom') {

		if($role == 'administrator') {
			$allowCreate = true;
			$hidden = 0;
		} else {

			if(in_array($role, $options['create_custom_roles'], false)) {
				$allowCreate = true;
				$hidden = 1;
			}
		}
	}

	if(isset($options['create_auto_accept'])) {
		$hidden = 0;
	}

	return array($allowCreate, $hidden);
}


function wpstickies_allow_modification($uid, $sid) {

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Default values
	$options['modify_roles'] = empty($options['modify_roles']) ? 'administrator' : strtolower($options['modify_roles']);
	$options['modify_custom_roles'] = empty($options['modify_custom_roles']) ? array() : explode(',', $options['modify_custom_roles']);

	// Get user's role
	$role = wpstickies_get_user_role($uid);

	// Get WPDB
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get sticky data
	$data = $wpdb->get_row("SELECT * FROM $table_name WHERE id = ".(int)$sid." ORDER BY date_c DESC LIMIT 1" , ARRAY_A);

	// Set default values
	$allowModify = false;
	$hidden = 1;

	// Identify permissions
	if($data['user_id'] == $uid && $data['flag_hidden'] == 1) {

		$allowModify = true;

		if($options['requirereconfirmation'] == 'auto_accept') {
			$hidden = 0;

		} elseif($options['requirereconfirmation'] == 'no' && $data['flag_hidden'] == 0) {
			$hidden = 0;
		}

	} else if($options['modify_roles'] == 'administrator') {

		if($role == 'administrator') {
			$allowModify = true;
			$hidden = 0;
		}
	} elseif($options['modify_roles'] == 'wpstickiesadmins') {

		if($role == 'administrator') {
			$allowModify = true;
			$hidden = 0;

		} elseif($role == 'wpstickiesadmins' && $data['user_id'] == $uid) {
			$allowModify = true;

			if($options['requirereconfirmation'] == 'auto_accept') {
				$hidden = 0;

			} elseif($options['requirereconfirmation'] == 'no' && $data['flag_hidden'] == 0) {
				$hidden = 0;
			}
		}

	} elseif($options['modify_roles'] == 'subscribers') {

		if($role == 'administrator') {
			$allowModify = true;
			$hidden = 0;

		} elseif($role == 'wpstickiesadmins' && $data['user_id'] == $uid) {
			$allowModify = true;

			if($options['requirereconfirmation'] == 'auto_accept') {
				$hidden = 0;

			} elseif($options['requirereconfirmation'] == 'no' && $data['flag_hidden'] == 0) {
				$hidden = 0;
			}

		} elseif($role == 'subscriber' && $data['user_id'] == $uid) {
			$allowModify = true;

			if($options['requirereconfirmation'] == 'auto_accept') {
				$hidden = 0;

			} elseif($options['requirereconfirmation'] == 'no' && $data['flag_hidden'] == 0) {
				$hidden = 0;
			}
		}

	} elseif($options['modify_roles'] == 'custom') {

		if($role == 'administrator') {
			$allowModify = true;
			$hidden = 0;
		} else {

			if(in_array($role, $options['modify_custom_roles'], false) && $data['user_id'] == $uid) {
				$allowModify = true;

				if($options['requirereconfirmation'] == 'auto_accept') {
					$hidden = 0;

				} elseif($options['requirereconfirmation'] == 'no' && $data['flag_hidden'] == 0) {
					$hidden = 0;
				}
			}
		}
	}

	return array($allowModify, $hidden);
}


/********************************************************/
/*         Action to accept pending stickies            */
/********************************************************/
function wpstickies_accept() {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get custom capabilities
	$options['capability'] = empty($options['capability']) ? 'manage_options' : $options['capability'];

	if(!array_key_exists($options['capability'], $current_user->allcaps)) {
		die('ERROR');
	}

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get entry ID
	$id = (int) $_POST['id'];

	$wpdb->query("UPDATE $table_name SET
					flag_hidden = '0',
					flag_deleted = '0',
					date_m = '".time()."'
				  WHERE id = '$id' LIMIT 1");

	die('SUCCESS');
}

/********************************************************/
/*         Action to restore removed stickies           */
/********************************************************/
function wpstickies_restore() {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get custom capabilities
	$options['capability'] = empty($options['capability']) ? 'manage_options' : $options['capability'];

	if(!array_key_exists($options['capability'], $current_user->allcaps)) {
		die('ERROR');
	}

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get entry ID
	$id = (int) $_POST['id'];

	$wpdb->query("UPDATE $table_name SET
					flag_deleted = '0',
					flag_hidden = '0',
					date_m = '".time()."'
				  WHERE id = '$id' LIMIT 1");

	die('SUCCESS');
}


/********************************************************/
/*         Action to reject pending stickies            */
/********************************************************/
function wpstickies_reject() {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get custom capabilities
	$options['capability'] = empty($options['capability']) ? 'manage_options' : $options['capability'];

	if(!array_key_exists($options['capability'], $current_user->allcaps)) {
		die('ERROR');
	}

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get entry ID
	$id = (int) $_POST['id'];

	$wpdb->query("UPDATE $table_name SET
					flag_hidden = '0',
					flag_deleted = '1',
					date_m = '".time()."'
				  WHERE id = '$id' LIMIT 1");

	die('SUCCESS');
}

/********************************************************/
/*        Action to delete stickies permanently         */
/********************************************************/
function wpstickies_delete() {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get custom capabilities
	$options['capability'] = empty($options['capability']) ? 'manage_options' : $options['capability'];

	if(!array_key_exists($options['capability'], $current_user->allcaps)) {
		die('ERROR');
	}

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get entry ID
	$id = (int) $_POST['id'];

	$wpdb->query("DELETE FROM $table_name
				  WHERE id = '$id' LIMIT 1");

	die('SUCCESS');
}

/********************************************************/
/*               Action to remove stickies              */
/********************************************************/
function wpstickies_remove() {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Gather user data
	$user_id = $current_user->ID;

	// Get sticky ID
	$id = (int) $_POST['id'];

	// Permission check
	$allowModify = wpstickies_allow_modification($user_id, $id);
	$allowModify = $allowModify[0];

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get custom capabilities
	$options['capability'] = empty($options['capability']) ? 'manage_options' : $options['capability'];

	if(array_key_exists($options['capability'], $current_user->allcaps)) {
		$allowModify = true;
	}
	// Get WPDB Object
	global $wpdb;

	// Get image
	$image = $wpdb->escape($_POST['image']);

	// Relative URL?
	if(isset($options['relative_urls'])) {
		$image = parse_url($image, PHP_URL_PATH);
	}

	// Check per image settings
	$table_name = $wpdb->prefix . "wpstickies_images";
	$settings = $wpdb->get_row("SELECT * FROM $table_name WHERE image = '$image' LIMIT 1", ARRAY_A);
	$data = json_decode($settings['data'], true);


	if($data['disabled'] == 'true') {
		$allowModify = 0;
	}

	if(!$allowModify) {

		// Retrieve options
		$options = get_option('wpstickies-options');
		$options = empty($options) ? array() : $options;
		$options = is_array($options) ? $options : unserialize($options);
		$options['lang_err_remove'] = empty($options['lang_err_remove']) ? 'wpStickies: The following error occurred during remove: You don\'t have permission to remove this sticky' : stripslashes($options['lang_err_remove']);

		die(json_encode(array('message' => $options['lang_err_remove'], 'errorCount' => 1)));
	}

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	$wpdb->query("UPDATE $table_name SET
					flag_hidden = '0',
					flag_deleted = '1',
					date_m = '".time()."'
				  WHERE id = '$id' LIMIT 1");

	die(json_encode(array('message' => '', 'errorCount' => 0)));
}


/********************************************************/
/*              Action to add new stickies              */
/********************************************************/

function wpstickies_insert() {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Gather user data
	$user_id = $current_user->ID;
	$user_name = $current_user->user_login;


	// Get permissions
	$data = wpstickies_allow_creatation($user_id);
	$allowCreate = $data[0];
	$hidden = $data[1];

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get WPDB Object
	global $wpdb;

	// Get image
	$original = $wpdb->escape($_POST['image']);

	// Relative URL?
	if(isset($options['relative_urls'])) {
		$image = parse_url($original, PHP_URL_PATH);
	} else {
		$image = $original;
	}

	// Check per image settings
	$table_name = $wpdb->prefix . "wpstickies_images";
	$settings = $wpdb->get_row("SELECT * FROM $table_name WHERE image = '$image' LIMIT 1", ARRAY_A);
	$data = json_decode($settings['data'], true);


	if($data['disabled'] == 'true') {
		$allowCreate = 0;
		$hidden = 1;
	}

	// Permission test
	if(!$allowCreate) {

		// Retrieve options
		$options = get_option('wpstickies-options');
		$options = empty($options) ? array() : $options;
		$options = is_array($options) ? $options : unserialize($options);
		$options['lang_err_create'] = empty($options['lang_err_create']) ? 'wpStickies: The following error occurred during save: You don\'t have permission to create new stickies!' : stripslashes($options['lang_err_create']);

		die(json_encode(array('message' => $options['lang_err_create'], 'errorCount' => 1)));
	}

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Build and execute query
	$wpdb->query(
		$wpdb->prepare("INSERT INTO $table_name
							(
								image, image_original, data, user_id, user_name, date_c, date_m, flag_hidden,
								flag_deleted
							)
						VALUES
							(
								'%s', '%s', '%s', '%d', '%s', '%d', '%d', '%d', '%d'
							)",
							$image,
							$original,
        					addslashes(json_encode($_POST['data'])),
							$user_id,
							$user_name,
							time(),
							time(),
							$hidden,
							0
		)
	);

	// Get the ID
	$id = mysql_insert_id();

	// Modify permission
	$allowToModify = wpstickies_allow_modification($user_id, $id);

	// Parsed stickie content
	$parsed = do_shortcode(stripslashes($_POST['data']['spot']['contentRaw']));

	// Response
	die(json_encode(array('message' => '', 'errorCount' => 0, 'id' => $id, 'allowToModify' => $allowToModify[0], 'content' => $parsed)));
}

/********************************************************/
/*               Action to modify stickies              */
/********************************************************/

function wpstickies_update() {

	// Get sticky ID
	$id = (int) $_POST['id'];

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Gather user data
	$user_id = $current_user->ID;
	$user_name = $current_user->user_login;

	// Get permissions
	$data = wpstickies_allow_modification($user_id, $id);
	$allowModify = $data[0];
	$hidden = $data[1];

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get WPDB Object
	global $wpdb;

	// Get image
	$image = $wpdb->escape($_POST['image']);

	// Relative URL?
	if(isset($options['relative_urls'])) {
		$image = parse_url($image, PHP_URL_PATH);
	}

	// Check per image settings
	$table_name = $wpdb->prefix . "wpstickies_images";
	$settings = $wpdb->get_row("SELECT * FROM $table_name WHERE image = '$image' LIMIT 1", ARRAY_A);
	$data = json_decode($settings['data'], true);


	if($data['disabled'] == 'true') {
		$allowModify = 0;
		$hidden = 1;
	}

	// Permission test
	if(!$allowModify) {

		$options['lang_err_modify'] = empty($options['lang_err_modify']) ? 'wpStickies: The following error occurred during save: You don\'t have permission to modify this sticky!' : stripslashes($options['lang_err_modify']);

		die(json_encode(array('message' => $options['lang_err_modify'], 'errorCount' => 1)));
	}

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Build and execute query
	$wpdb->query(
		$wpdb->prepare("UPDATE $table_name SET
							data = '%s',
							date_m = '%d',
							flag_hidden = '%d'
						WHERE id = '%d'
						ORDER BY date_m DESC
						LIMIT 1",

						addslashes(json_encode($_POST['data'])),
						time(),
						$hidden,
						$id
		)
	);

	// Parsed stickie content
	$parsed = do_shortcode(stripslashes($_POST['data']['spot']['contentRaw']));

	die(json_encode(array('message' => '', 'errorCount' => 0, 'content' => $parsed)));
}

/********************************************************/
/*                Action to get stickies                */
/********************************************************/

function wpstickies_get() {

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Gather user data
	$user_id = $current_user->ID;

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "wpstickies";

	// Get image URL
	$url = $wpdb->escape($_GET['image']);

	// Relative URL?
	if(isset($options['relative_urls'])) {
		$url = parse_url($url, PHP_URL_PATH);
	}

	// Get latest stickies
	if(isset($options['display_pending_stickies']) && $options['display_pending_stickies'] == 'visible') {
		$stickies = $wpdb->get_results("SELECT * FROM $table_name
										WHERE image = '$url' AND flag_deleted = '0'
										ORDER BY date_c DESC LIMIT 50", ARRAY_A);
	} else {
		$stickies = $wpdb->get_results("SELECT * FROM $table_name
						WHERE
							( image = '$url' AND flag_hidden = '0' AND flag_deleted = '0' ) OR
							( image = '$url' AND user_id = '$user_id' AND user_id != '0' AND flag_deleted = '0' )
						ORDER BY date_c DESC LIMIT 100", ARRAY_A);
	}

	// Set an empty array for results
	$ret = array('settings' => array(), 'stickies' => array());

	// Build an array
	foreach($stickies as $key => $val) {

		// Get the data
		$data = json_decode(stripslashes($val['data']), true);

		$ret['stickies'][$key] = $data;
		$ret['stickies'][$key]['sticky']['id'] = $val['id'];

		$allowToModify = wpstickies_allow_modification($user_id, $val['id']);
		$ret['stickies'][$key]['sticky']['allowToModify'] = $allowToModify[0];

		// Stipslashes
		$ret['stickies'][$key]['spot']['title'] = stripslashes($data['spot']['title']);
		$ret['stickies'][$key]['spot']['content'] = do_shortcode(stripslashes($data['spot']['content']));
		$ret['stickies'][$key]['area']['caption'] = stripslashes($data['area']['caption']);

		if(isset($ret['stickies'][$key]['spot']['contentRaw'])) {
			$ret['stickies'][$key]['spot']['contentRaw'] = stripslashes($data['spot']['contentRaw']);
		}
	}


	// Query down image settings
	$table_name = $wpdb->prefix . "wpstickies_images";
	$settings = $wpdb->get_row("SELECT * FROM $table_name WHERE image = '$url' LIMIT 1", ARRAY_A);

	if(empty($settings)) {
		$ret['settings']['disabled'] = 'false';
	} else {
		$ret['settings'] = json_decode($settings['data']);
	}

	die(json_encode($ret));
}


/********************************************************/
/*           Per image settings for admins              */
/********************************************************/
function wpstickies_image_settings() {

	// Only admins
	if(!current_user_can('manage_options')) {
		die(json_encode(array('message' => 'You don\'t have permission to edit this image settings', 'errorCount' => 1)));
	}

	// Retrieve options
	$options = get_option('wpstickies-options');
	$options = empty($options) ? array() : $options;
	$options = is_array($options) ? $options : unserialize($options);

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "wpstickies_images";

	// Get image
	$original = $wpdb->escape($_POST['image']);

	// Relative URL?
	if(isset($options['relative_urls'])) {
		$image = parse_url($original, PHP_URL_PATH);
	} else {
		$image = $original;
	}

	$data = addslashes(json_encode($wpdb->escape($_POST['data'])));

	$wpdb->query("INSERT INTO $table_name
					(image, image_original, data, date_c, date_m) VALUES
					('$image', '$original', '$data', '".time()."', '".time()."')
				  ON DUPLICATE KEY UPDATE
				  	data = '$data', date_m = '".time()."'");

	die(json_encode(array('message' => '', 'errorCount' => 0)));
}
?>