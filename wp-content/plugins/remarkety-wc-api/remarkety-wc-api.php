<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Name: Remarkety WooCommerce API
 * Plugin URI: http://www.remarkety.com
 * Description: A Woocommerce connector for Remarkety services
 * Version: 1.0.3
 * Author: Remarkety
 * Author URI: http://www.remarkety.com
 * License: GPL2
 */

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
if (! class_exists('remarkety_wc_api')) :
	
	/**
	* Main Remarkety WooCommerce API Class
	*
	* @class remarkety-wc-api
	* @version	1.0.3
	*/
	class remarkety_wc_api {
		
		const OPTION_API_KEY = 'remarkety_api_key';
		const OPTION_DEBUG_MODE = 'remarkety_api_debug';
		const LOG_NAME = 'remarkety-wc-api.log';
		
		static public $logPath;
		static public $debug_mode;
		static public $debugData = null;
		
		public function __construct() {
			add_action('admin_init', array($this, 'admin_init'));
			add_action('admin_menu', array($this, 'add_menu'));
			add_action('admin_enqueue_scripts', array($this, 'load_wp_admin_styles'));
			add_action('woocommerce_cart_updated', array($this, 'wc_cart_update_event'));
			add_action('woocommerce_cart_emptied', array($this, 'wc_cart_empty_event'));
			add_filter('xmlrpc_methods', array($this, 'xml_add_methods'));
			remarkety_wc_api::$debug_mode = (get_option(self::OPTION_DEBUG_MODE) == 'on');
			remarkety_wc_api::$logPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::LOG_NAME;
			set_error_handler(array("remarkety_wc_api","remarketyErrorHandler"));
			if (remarkety_wc_api::$debug_mode == 1){
				error_reporting(E_ALL | E_STRICT);
				ini_set("display_errors", 1);
			}
			remarkety_wc_api::$debugData = null;
		}
	
		function xml_add_methods($methods) {
			$new_methods = array(
					'remarkety_wc_api.store_settings' => 'api_method_get_store_settings',
					'remarkety_wc_api.statuses' => 'api_method_get_statuses',
					'remarkety_wc_api.products' => 'api_method_get_products',
					'remarkety_wc_api.products_count' => 'api_method_get_products_count',
					'remarkety_wc_api.shoppers' => 'api_method_get_shoppers',
					'remarkety_wc_api.shoppers_count' => 'api_method_get_shoppers_count',
					'remarkety_wc_api.orders' => 'api_method_get_orders',
					'remarkety_wc_api.orders_count' => 'api_method_get_orders_count',
					'remarkety_wc_api.create_coupon' => 'api_method_create_coupon',
					'remarkety_wc_api.carts' => 'api_method_get_carts',
					'remarkety_wc_api.debug' => 'api_method_debug',
					);
			
			$methods = array();
			foreach ($new_methods as $k => $v) $methods[$k] = array($this, $v);
			return $methods;
		}
		
		public static function activate() {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			global $wpdb;

			$q = "
				CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}remarkety_carts` (
					`user_id` bigint(20) NOT NULL,
					`created_on` int(11) NOT NULL,
					`updated_on` int(11) NOT NULL,
					`cart_data` longtext CHARACTER SET utf8 NOT NULL,
					PRIMARY KEY (`user_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
			";
			
			dbDelta($q);
			
			add_option(self::OPTION_API_KEY, substr(str_replace('.', '', uniqid(uniqid('', true), true)), 0,32));
			
			// make sure to start clean without old information remaining from after previous deactivation
			$wpdb->query("DELETE FROM {$wpdb->prefix}remarkety_carts");
		}
				
		public static function uninstall() {
			global $wpdb;
			$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}remarkety_carts");	
		}
		
		public function admin_init() {
			register_setting('remarkety_wc_api', self::OPTION_DEBUG_MODE, array($this, 'debug_mode_changed'));
			add_settings_section('remarkety_main', '', array($this, 'settings_section'), __FILE__);
			add_settings_field(self::OPTION_DEBUG_MODE, 'Enable debug mode', array($this, 'setting_debug_mode'), __FILE__, 'remarkety_main');
		}
		
		public function load_wp_admin_styles() {
			wp_register_style('remarkety-wc-api', plugins_url('remarkety-wc-api/assets/css/remarkety.css'));
			wp_enqueue_style('remarkety-wc-api');
// 			wp_enqueue_style('remarkety-wc-api', plugins_url('remarkety-wc-api/assets/css/remarkety.css'));
		}
		
		public function add_menu() {
			add_options_page('Remarkety WC API Settings', 'Remarkety WC API', 'manage_options', 'remarkety_wc_api', array($this, 'plugin_settings_page'));
		}
		
		public function plugin_settings_page() {
			if (!current_user_can('manage_options')) {
				wp_die(__('you do not have sufficient permissions to access this page.'));
			}
				
			echo '<div class="wrap">';
			echo '<form method="post" action="options.php">';
			settings_fields('remarkety_wc_api');
			do_settings_sections(__FILE__);
			@submit_button();
			echo '</form>';
			echo '</div>';
		}
		
		function settings_section() {
			include (sprintf("%s/templates/settings.php", dirname(__FILE__)));
		}
		
		function setting_debug_mode() {
			$d = self::OPTION_DEBUG_MODE;
			$checked = checked(get_option($d), 'on', false);
			echo "<input type='checkbox' name='{$d}' id='{$d}' {$checked} /> <span style=\"font-size:13px\">Log file path: /wp-content/plugins/remarkety-wc-api/remarkety-wc-api.log</span>";
		}
		
		function debug_mode_changed($input) {
			$this->debug_mode = true;
				
			if ($input == 'on') {
				remarkety_wc_api::log('Debug mode turned on');
			} else {
				remarkety_wc_api::log('Debug mode turned off');
			}
				
			return $input;
		}
		
		public function wc_cart_empty_event() {
			global $wpdb;
			if (!is_user_logged_in()) return;
			$user_id = get_current_user_id();
			$q = "DELETE FROM {$wpdb->prefix}remarkety_carts WHERE user_id = %d";
			$wpdb->query($wpdb->prepare($q, $user_id));
			remarkety_wc_api::log("wc_cart_empty_event executed query : {$q}");
		}
		
		public function wc_cart_update_event() {
			
			global $wpdb;

			if (!is_user_logged_in()) return;
			
			$session_cart = WC()->session->cart;
			
			$user_id = get_current_user_id();
			$ts = time();
			$cart = array(
					'cart' => $session_cart,
					'coupons' => WC()->session->applied_coupons,
					'coupon_discounts' => WC()->session->coupon_discount_amounts
					);
			$cart = serialize($cart);
			
			$q = "SELECT count(user_id) as cnt FROM {$wpdb->prefix}remarkety_carts WHERE user_id = {$user_id}";
			$res = $wpdb->get_results($q);
			$update = (isset($res[0]) && ($res[0]->cnt > 0));
			
			if ($update) {
				if(empty($session_cart)){
					//delete record
					$q = "DELETE FROM {$wpdb->prefix}remarkety_carts WHERE user_id = %s";	
					$wpdb->query($wpdb->prepare($q, $user_id));
				}else{
					$q = "UPDATE {$wpdb->prefix}remarkety_carts SET updated_on = %d, cart_data = %s WHERE user_id = %s";
					$wpdb->query($wpdb->prepare($q, $ts, $cart, $user_id));
				}
				
			} else {
				$q = "INSERT INTO {$wpdb->prefix}remarkety_carts (user_id, created_on, updated_on, cart_data) VALUES (%d, %d, %d, %s)";
				$wpdb->query($wpdb->prepare($q, $user_id, $ts, $ts, $cart));
			}
			
			remarkety_wc_api::log("wc_cart_update_event executed query : {$q}");
		}
				
		public function api_method_get_store_settings($args) {
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
				
			$options = array(
					'blogname',
					'woocommerce_email_from_name',
					'woocommerce_email_from_address',
					'woocommerce_currency',
					'woocommerce_currency_pos',
					'woocommerce_price_thousand_sep',
					'woocommerce_price_decimal_sep',
					'woocommerce_price_num_decimals',
					'woocommerce_version',
					'woocommerce_default_country',
					'gmt_offset',
					'timezone_string',
					'home',
					'siteurl'
			);
		
			$res = array('settings' => array());
			foreach ($options as $option) $res['settings'][$option] = get_option($option);
			$res['settings']['is_multisite'] = is_multisite();
			global $wp_version;
			$res['settings']['wp_version'] = $wp_version;
			
			return $res;
		
			// TODO : missing
			// 			$store->phone = $shop_settings['phone'];
			// 			$store->address = $shop_settings['address'];
			// 			$store->created_at = null; 							// Shop creation time seems not to be registered in Magento
			// 			$store->city = '';									// Magento does not have this attribute
			// 			$store->zip = '';									// Magento does not have this attribute
			// 			$store->province = '';								// Magento does not have this attribute
			// 			$store->logoPath = '';								// Magento does not have this attribute
		}
		
		public function api_method_get_shoppers($args) {
			global $wpdb;
			$ids = array();
			remarkety_wc_api::log('Start api_method_get_shoppers');
			remarkety_wc_api::log($args);
			
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();

// 			$updated_min = '';
// 			$updated_max = '';
			$limit = '';
			$starting_id = '';
			
//			if (isset($args[2]) && $args[2] > 0) $updated_min = " AND user_registered > '" . $args[2] . "'";
//			if (isset($args[3]) && $args[3] > 0) $updated_max = " AND user_registered < '" . $args[3] . "'";
			
			if (isset($args[3]) && $args[3] > 0) {
				$rows = $args[3];
				$offset = (isset($args[4])) ? $rows * $args[4] : 0;  
				$limit = $wpdb->prepare(" LIMIT %d, %d", $offset, $rows);
			}
			
			if (isset($args[5]) && $args[5] > 0) $starting_id = $wpdb->prepare(" AND user.ID >= %d", $args[5]);
					
			$q = "
				SELECT user.ID
				FROM {$wpdb->prefix}users as user
				WHERE true
				{$starting_id}
				{$limit}
			";
// 				{$updated_min}
// 				{$updated_max}
			
			$results = $wpdb->get_results($q);
			$res = array('shoppers' => array());
		
			if ($results) {
				foreach ($results as $result){ 
					remarkety_wc_api::log('Result ID = '.$result->ID);
					$ids[] = $result->ID;
				}
			
				// 			$users_per_page = get_option('posts_per_page');
				$q = array(
						'fields'  => 'all_with_meta',
//						'role'    => 'customer',
						'orderby' => 'registered',
						'include' => join(',', $ids),
						// 					'number'  => $users_per_page,
				);
			
				$query = new WP_User_Query($q);
				
				remarkety_wc_api::log('Query = '.print_r($query,true));
			
				/* @var $user WP_User */
				if (!empty($query->results)) {
					foreach ($query->results as $user) {
						$res['shoppers'][] = $this->user_data_array($user);
					}
				}
					
				// limit number of users returned
				// 			if ( ! empty( $args['limit'] ) ) {
				// 				$query_args['number'] = absint( $args['limit'] );
				// 				$users_per_page = absint( $args['limit'] );
				// 			}
		
				// page
				// 			$page = ( isset( $args['page'] ) ) ? absint( $args['page'] ) : 1;
		
				// offset
				// 			if ( ! empty( $args['offset'] ) ) {
				// 				$query_args['offset'] = absint( $args['offset'] );
				// 			} else {
				// 				$query_args['offset'] = $users_per_page * ( $page - 1 );
				// 			}
		
		
				// helper members for pagination headers
				// 			$query->total_pages = ceil( $query->get_total() / $users_per_page );
				// 			$query->page = $page;
		
			}
			$this->addDebugIfNeeded($res);
			return $res;
		}
		
		public function api_method_get_shoppers_count($args) {
			remarkety_wc_api::log('Start api_method_get_shoppers_count');
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
			
			$q = array(
					'fields'  => 'ID',
					'role'    => 'customer',
					'orderby' => 'registered',
			);
				
			$query = new WP_User_Query($q);
			$res = array('count' => count($query));
			remarkety_wc_api::log('End api_method_get_shoppers_count');
			return $res;
		}
		
		public function api_method_get_products($args) {
			global $wpdb;
			remarkety_wc_api::log('Start api_method_get_products');
			remarkety_wc_api::log($args);
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
			
			$updated_min = '';
			$updated_max = '';
			$limit = '';
			$post_id_min = '';
			$post_date_min = '';
			$post_date_max = '';
			$post_status = '';
			$post_id = '';
			
			if (isset($args[1]) && $args[1] > 0) $updated_min = $wpdb->prepare(" AND post_modified_gmt > %s", $args[1]);
			if (isset($args[2]) && $args[2] > 0) $updated_max = $wpdb->prepare(" AND post_modified_gmt < %s", $args[2]);
			if (isset($args[3]) && $args[3] > 0) {
				$rows = $args[3];
				$offset = (isset($args[4])) ? $rows * $args[4] : 0;
				$limit = $wpdb->prepare("LIMIT %d, %d", $offset, $rows);
			}
			if (isset($args[9]) && $args[9] > 0) $post_id_min = $wpdb->prepare(" AND ID >= %d", $args[9]);
			if (isset($args[10]) && $args[10] > 0) $post_date_min = $wpdb->prepare(" AND post_date_gmt >= %s", $args[10]);
			if (isset($args[11]) && $args[11] > 0) $post_date_max = $wpdb->prepare(" AND post_date_gmt >= %s", $args[11]);
			if (isset($args[12]) && $args[12] > 0) $post_status = $wpdb->prepare(" AND post_status = %s", $args[12]);
			if (isset($args[13]) && $args[13] > 0) $post_id = $wpdb->prepare(" AND post_id = %d", $args[13]);
								
			$q = "
				SELECT ID
				FROM {$wpdb->prefix}posts
				WHERE post_type = 'product'
				{$updated_min}
				{$updated_max}
				{$post_id_min}
				{$post_date_min}
				{$post_date_max}
				{$post_status}
				{$post_id}
				{$limit}
			";


//			remarkety_wc_api::log($q);
			
			$res = array('products' => array());
			$results = $wpdb->get_results($q);
			
			if ($results) {
				foreach ($results as $result) {
					$res['products'][] = $this->product_data_array($result->ID);
				}
			}
			$this->addDebugIfNeeded($res);
			remarkety_wc_api::log('End api_method_get_products');
			return $res;
		}
		
		public function api_method_get_products_count($args) {

			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
		
			$query = new WP_Query(array('post_type' => 'product', 'fields' => 'ids'));
			$res = array('count' => count($query->posts));
			return $res;
		}
		
		public function api_method_create_coupon($args) {
			remarkety_wc_api::log('Start api_method_create_coupon');
			remarkety_wc_api::log($args);
			
			if (count($args) < 12) return self::status_auth_error();
			if (!$this->auth($args)) return self::status_params_error();
			
			$coupon_code = 				(string)	$args[1];
			$discount_type = 			(string)	$args[2];
			$permanent =				(bool)		$args[3];		// TODO : currently : true = unlimited use ..
			$amount = 					(float)		$args[4];
			$start_date = 				(string)	$args[5];
			$expiry_date = 				(string)	$args[6];
			$minimum_spent = 			(float)		$args[7];
			$free_shipping = 			(bool)		$args[8];
			$apply_before_tax =			(bool)		$args[9];
			$usage_limit_per_coupon = 	(int)		$args[10];
			$usage_limit_per_user = 	(int)		$args[11];
			
			$coupon_code = strtolower($coupon_code);
			if ($this->coupon_code_exists($coupon_code)) return self::status_fail();
			
			if ($permanent) {
				$usage_limit_per_coupon = 0;
				$usage_limit_per_user = 0;
			}
			
			if ($usage_limit_per_coupon == 0) $usage_limit_per_coupon = '';
			if ($usage_limit_per_user == 0) $usage_limit_per_user = '';
			
			$free_shipping = $free_shipping ? 'yes' : 'no';
			$apply_before_tax = $apply_before_tax ? 'yes' : 'no';
			
			$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => '',
					'post_author' => 1,
					'post_type' => 'shop_coupon',
					'post_excerpt' => 'Remarkety, ' . current_time('mysql'),
					'post_status' => 'publish',
					'post_name' => $coupon_code
			);
			
			$post_date = new DateTime($start_date, new DateTimeZone('UTC'));
			$now = new DateTime(null, new DateTimeZone('UTC'));
			
			if ($post_date > $now) {
				$coupon['post_status'] = 'future';
				$coupon['post_date_gmt'] = $post_date->format('Y-m-d H:i:s');
				$post_date->setTimezone(new DateTimeZone(get_option('gmt_offset', 'UTC')));
				$coupon['post_date'] = $post_date->format('Y-m-d H:i:s');
			} 
				
			$new_coupon_id = wp_insert_post($coupon);
				
			update_post_meta($new_coupon_id, 'discount_type', $discount_type );
			update_post_meta($new_coupon_id, 'coupon_amount', $amount );
			update_post_meta($new_coupon_id, 'individual_use', 'no' );			// TODO : no ? yes ? as parameter ?
			update_post_meta($new_coupon_id, 'product_ids', '' );
			update_post_meta($new_coupon_id, 'exclude_product_ids', '' );
			update_post_meta($new_coupon_id, 'usage_limit', $usage_limit_per_coupon);
			update_post_meta($new_coupon_id, 'usage_limit_per_user', $usage_limit_per_user);
			update_post_meta($new_coupon_id, 'expiry_date', $expiry_date );
			update_post_meta($new_coupon_id, 'apply_before_tax', $apply_before_tax);
			update_post_meta($new_coupon_id, 'free_shipping', $free_shipping);
			update_post_meta($new_coupon_id, 'minimum_amount', $minimum_spent);
			
			remarkety_wc_api::log('End api_method_create_coupon');
			
			return self::status_success();
		}
		
		public function api_method_get_orders($args) {
			remarkety_wc_api::log('Start api_method_get_orders');
			remarkety_wc_api::log($args);
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
		
			$datetime = new DateTime( $args[1], new DateTimeZone( 'UTC' ) );
			$updated_at_min = $datetime->format( 'Y-m-d H:i:s' );
			
			remarkety_wc_api::log('updated_at_min arg[1] = '.$updated_at_min);
			$q = array(
					'post_type' => 'shop_order',
					'date_query' => array(
							'column' => 'post_modified_gmt',
							'after'  => $updated_at_min
					)
			);
		
			$query = new WP_Query($q);
			
			remarkety_wc_api::log('Query = '.print_r($query,true));
		
			$res = array('orders' => array());
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$order_id = $query->post->ID;
					remarkety_wc_api::log('Order ID: '.$order_id);
					$res['orders'][] = $this->get_order($order_id); //new WC_Order($query->post->ID);
				}
			}
			$this->addDebugIfNeeded($res);
			remarkety_wc_api::log('End api_method_get_orders');
			return $res;
		}
		
		// TODO : from all statuses ? no filters ??
		public function api_method_get_orders_count($args) {
		
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
		
			$query = new WP_Query(array('post_type' => 'shop_order'));
			$res = array('count' => $query->post_count);
			return $res;
		}
		
		public function api_method_get_statuses($args) {
		
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
				
			$taxonomies = array('shop_order_status');
			$q = array(
					'hide_empty'    => false,
					'fields'        => 'all',
					'hierarchical'  => false,
			);
		
			$res = array('statuses' => array());
			foreach (get_terms($taxonomies, $q) as $s) {
				$res['statuses'][] = array(
						'status'	=> $s->name,
						'status_id'	=> $s->slug,
				);
			}
				
			$this->addDebugIfNeeded($res);
			return $res;
		}
		
		public function api_method_get_carts($args) {
			global $wpdb;
			remarkety_wc_api::log('Start api_method_get_carts');
			remarkety_wc_api::log($args);
			
			if (count($args) < 1) return self::status_params_error();
			if (!$this->auth($args)) return self::status_auth_error();
			
			$updated_min = '';
			$updated_max = '';
			$limit = '';
			
			if (isset($args[1]) && $args[1] > 0) $updated_min = $wpdb->prepare("AND updated_on >= %s", $args[1]);
			if (isset($args[2]) && $args[2] > 0) $updated_max = $wpdb->perapre("AND updated_on <= %s", $args[2]);
			if (isset($args[3]) && $args[3] > 0) {
				$rows = $args[3];
				$offset = (isset($args[4])) ? $rows * $args[4] : 0;
				$limit = $wpdb->prepare("LIMIT %d, %d", $offset, $rows);
			}
			
			$q = "SELECT * FROM {$wpdb->prefix}remarkety_carts WHERE true {$updated_min} {$updated_max} ORDER BY updated_on {$limit}";
			remarkety_wc_api::log($q);
			
			$results = $wpdb->get_results($q);
			
			// TODO : need to verify that these are the correct options.
			// TODO : see how plugins that allow multi-currency work. load their correct currency if possible per order.
			$currency = get_option('woocommerce_currency');
			$currency_symbol = get_woocommerce_currency_symbol($currency);

			$res = array('carts' => array());
			
			if ($results) {
				remarkety_wc_api::log('Carts count:'.count($results));
				foreach ($results as $result) {
//					remarkety_wc_api::log($result);	
//					remarkety_wc_api::log($result->cart_data);
//					remarkety_wc_api::log(unserialize($result->cart_data));
					
					$res['carts'][] = array(
							'created_on'			=> $result->created_on,
							'updated_on'			=> $result->updated_on,
							'user_id'				=> $result->user_id,
							'shopper_block'			=> $this->user_data_array_by_user_id($result->user_id),
							'cart_data'				=> @unserialize($result->cart_data),
							'currency'				=> $currency,
							'currency_symbol' 		=> $currency_symbol,
					);
				}
			}

			$this->addDebugIfNeeded($res);
			remarkety_wc_api::log('End api_method_get_carts');
			return $res;
		}
		
		private function get_order($id) {
			remarkety_wc_api::log('Start get_order');
			remarkety_wc_api::log('Order: '.$id);
			
			$order = new WC_Order($id);
			$order_post = get_post($id);
			$currency = ($order->get_order_currency() == '') ? get_option('woocommerce_currency') : $order->get_order_currency();
			
			remarkety_wc_api::log('Currency = '.$currency);
			
			remarkety_wc_api::log($order);

			$order_data = array(
					'id'                        => $order->id,
					'order_number'              => $order->get_order_number(),
					'created_at'                => $order_post->post_date_gmt,
					'updated_at'                => $order_post->post_modified_gmt,
					'completed_at'              => $order->completed_date,
					'status_block'				=> array(
							'status_id'					=> $order->status,
							'status'					=> $this->get_status_name_by_slug($order->status),
					),
					'currency'                  => $currency,
					'currency_symbol' 			=> get_woocommerce_currency_symbol($currency),
					'total'                     => $order->get_total(),
					// 					'subtotal'                  => 0, // $this->format_decimal( $this->get_order_subtotal( $order ), 2 ),
					'total_tax'                 => $order->get_total_tax(),
					'total_shipping'            => $order->get_total_shipping(),
					'cart_tax'                  => $order->get_cart_tax(),
					'shipping_tax'              => $order->get_shipping_tax(),
					'total_discount'            => $order->get_total_discount(),
					'cart_discount'             => $order->get_cart_discount(),
					'order_discount'            => $order->get_order_discount(),
					'customer_id'               => $order->customer_user,

					'billing_address' => array(
							'first_name' => $order->billing_first_name,
							'last_name'  => $order->billing_last_name,
							'company'    => $order->billing_company,
							'address_1'  => $order->billing_address_1,
							'address_2'  => $order->billing_address_2,
							'city'       => $order->billing_city,
							'state'      => $order->billing_state,
							'postcode'   => $order->billing_postcode,
							'country'    => $order->billing_country,
							'email'      => $order->billing_email,
							'phone'      => $order->billing_phone,
					),
					'shipping_address' => array(
							'first_name' => $order->shipping_first_name,
							'last_name'  => $order->shipping_last_name,
							'company'    => $order->shipping_company,
							'address_1'  => $order->shipping_address_1,
							'address_2'  => $order->shipping_address_2,
							'city'       => $order->shipping_city,
							'state'      => $order->shipping_state,
							'postcode'   => $order->shipping_postcode,
							'country'    => $order->shipping_country,
					),
					'items'		                => array(),
					'coupon_lines'              => array(),
					'shopper_block'				=> $this->user_data_array_by_user_id($order->customer_user),
			);
			
			remarkety_wc_api::log('Order data = '.print_r($order_data,true));
		
			// add line items
			foreach( $order->get_items() as $item_id => $item ) {
				$product = $order->get_product_from_item($item);
				remarkety_wc_api::log('Product = '.print_r($product,true));
						
				$product_id = (isset($product->variation_id)) ? $product->variation_id : $product->id;
				$order_data['items'][] = array(
				// 						'id'         => $item_id,
						'product_id' 		=> $product_id,
						'sku'        		=> is_object($product) ? $product->get_sku() : null,	// TODO : copied as is, does not seem right
						'quantity'   		=> (int) $item['qty'],
						'price'      		=> $order->get_item_total($item),
						'subtotal'   		=> $order->get_line_subtotal($item),
						'total'      		=> $order->get_line_total($item),
						'total_tax'  		=> $order->get_line_tax($item),
						'name'       		=> $item['name'],
						'product_block' 	=> $this->product_data_array($product_id),
						// 						'tax_class'  => ( ! empty( $item['tax_class'] ) ) ? $item['tax_class'] : null,
				);
			}
				
			// TODO : if the coupon original details are needed they can be fetched from the post and it's metadata
			// the details currently retrieved are : 
			// 		ITEM ID and NOT the coupon ID
			//		DISCOUNT AMOUNT (i.e. * the number of items) and NOT the coupon defined discount (%, $ etc ..)
			foreach ($order->get_items('coupon') as $coupon_item_id => $coupon_item) {
				$order_data['coupon_lines'] = array(
						'id'     => $coupon_item_id,
						'code'   => $coupon_item['name'],
						'amount' => $coupon_item['discount_amount'],
				);
			}
			remarkety_wc_api::log('End get_order');
			return $order_data;
		}
		
		private function user_data_array_by_user_id($id) {
			$user = new WP_User($id);
			$res = $this->user_data_array($user);
			return $res;
		}
		
		private function user_data_array(WP_User $user) {
			remarkety_wc_api::log('Start user_data_array');
			if ($user->ID == 0) return array();	// unregistered user
			
			$fields = array(
					'ID',
					'user_email',
					'first_name',
					'last_name',
					'user_registered',
					'billing_postcode',	
					'billing_country',	
					'billing_state',
					'billing_first_name',
					'billing_last_name',
					'_order_count',
					'_money_spent',
					'billing_phone'
					);
			
			$res = array();
			foreach ($fields as $fld) $res[$fld] = $user->get($fld);
			$res['is_guest'] = false;
			remarkety_wc_api::log($res);
			return $res;
		}
		
		private function product_data_array($post_id) {
			$p = get_product($post_id);
			
			$categories = array();
			foreach (wp_get_post_terms($post_id, 'product_cat', $args) as $term_obj) {
				$categories[] = array(
					'id' => $term_obj->term_id,
					'name' => $term_obj->name,
					);
			}
			
			$res = array(
					'ID' => $p->id,
					'sku' => $p->get_sku(),
					'categories' => $categories, 					//	$p->get_categories(','),
					'link' => $p->get_permalink(),
					'image' =>  $this->product_thumb($p->id),
					'post_title' => $p->get_title(),
					'post_modified_gmt' => $p->post->post_modified_gmt,
					'price' => $p->get_regular_price()					);
			
			return $res;
			
		}
		
		private function product_thumb($post_id) {
			$images = wp_get_attachment_image_src(get_post_thumbnail_id($post_id));
			return array_shift($images);
		}
		
		private function coupon_code_exists($coupon_code) {
			global $wpdb;
			$coupon_code = $wpdb->escape($coupon_code);
			$q = "
				SELECT ID 
				FROM {$wpdb->prefix}posts 
				WHERE post_title = '{$coupon_code}' 
				AND post_status = 'publish' 
				AND post_type = 'shop_coupon'";
			
			$res = $wpdb->get_row($q, 'ARRAY_A');
			if (empty($res)) return false;
			return true;
		}
		
		private function auth($args) {
			if (!isset($args[0])) return false;
			if (empty($args[0])) return false;

			if ($args[0] == get_option(self::OPTION_API_KEY, time())) return true;
			return false;
		}
		
		private function get_status_name_by_slug($status_slug) {
			// TODO : cache responses ??
			$term = get_term_by('slug', sanitize_title($status_slug), 'shop_order_status');
			return $term->name;
		}
		
		private static function status_auth_error() {
			return array('error' => 'Authentication error');
		}
		
		private static function status_params_error() {
			return array('error' => 'Parameters error');
		}
		
		private static function status_success() {
			return array('success' => true);
		}
		
		private static function status_fail() {
			return array('success' => false);
		}
		
		public function api_method_debug($args){
			remarkety_wc_api::log('Start api_method_debug');
			remarkety_wc_api::log($args);
			$isDebug = $args[1] == true ? 'on' : 'off';
			update_option(self::OPTION_DEBUG_MODE, $isDebug);
			$debug_level = $args[2];
			$is_clear_log = $args[3];
			
			$res = array('debug' => array());
			
			if($debug_level == 1){
				
			}else if($debug_level == 2){
				$log = file_get_contents(remarkety_wc_api::$logPath);
				$res['debug']['file'] = $log;
			}
			
			if(!empty($is_clear_log)){
				$this->clearLog();
			}
			
			return $res;
		}

		public static function log($msg) {
			if (remarkety_wc_api::$debug_mode != 1) return;
			if (is_array($msg)) $msg = "(array dump) " . print_r($msg, true);
			if (is_object($msg)) $msg = "(object dump) " . print_r($msg, true);
			$msg = date('Y-m-d H:i:s')." : {$msg}".PHP_EOL;
			file_put_contents(remarkety_wc_api::$logPath, $msg, FILE_APPEND);
			if (remarkety_wc_api::$debug_mode == 1){
				remarkety_wc_api::$debugData .= $msg;
			}
		}
		
		private function addDebugIfNeeded(&$result){
			remarkety_wc_api::log('Start addDebugIfNeeded');
			if (remarkety_wc_api::$debug_mode == 1 && remarkety_wc_api::$debugData != null){
				global $wp_version;
				$woo_version = get_option('woocommerce_version');
				$result['debug'] = remarkety_wc_api::$debugData.PHP_EOL.'wp_version='.$wp_version.' wc_version='.$woo_version.PHP_EOL;
			}
		}
		
		private function clearLog(){
			file_put_contents(remarkety_wc_api::$logPath, '');
		}
		
		static public function remarketyErrorHandler($errno, $errstr, $errfile, $errline){
		    if (!(error_reporting() & $errno)) {
		        // This error code is not included in error_reporting
		        return;
		    }
		
		    switch ($errno) {
			    case E_USER_ERROR:
			    	$errorType = "ERROR";
			        break;
			    case E_USER_WARNING:
			        $errorType = "E_USER_WARNING";
			        break;
			    case E_USER_NOTICE:
			        $errorType = "E_USER_NOTICE";
			        break;
			    case E_ERROR:
			        $errorType = "E_ERROR";
			        break;
			    case E_WARNING:
			        $errorType = "E_WARNING";
			        break;
			    case E_NOTICE:
			        $errorType = "E_NOTICE";
			        break;
			
			    default:
			    	$errorType = "Unknown error type";
			        break;
		    }
		    $errStr = $errorType.": [".$errno."] ".$errstr. " Line ".$errline." in file ".$errfile;
		    remarkety_wc_api::$debugData .= $errStr;
			remarkety_wc_api::log($errStr);
		    /* Don't execute PHP internal error handler */
		    return true;
		}
	}
	
endif;

if (class_exists('remarkety_wc_api')) {
	
	register_activation_hook(__FILE__, array('remarkety_wc_api', 'activate'));
	register_uninstall_hook(__FILE__, array('remarkety_wc_api', 'uninstall'));

	$remarkety_wc_api = new remarkety_wc_api();
}

if (isset($remarkety_wc_api)) {

	function plugin_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=remarkety_wc_api">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	$plugin = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
}


