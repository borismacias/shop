<?php 

if( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
	exit();

global $wpdb;
$sliders_table = $wpdb->base_prefix . 'image_mapper';
$wpdb->query( "DROP TABLE $sliders_table" );

?>