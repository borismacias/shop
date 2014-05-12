<?php   

/*
Plugin Name: Wordpress Image Mapper
Plugin URI: http://codecanyon.net/item/imapper-wordpress-image-mapper-pinner/4719958
Description: Downloaded from 96down.com. Image Mapper for Wordpress
Author: Br0
Version: 1.5.1
Author URI: http://www.shindiristudio.com/
*/

if (!class_exists("ImageMapperAdmin")) 
{
	require_once dirname( __FILE__ ) . '/image_mapper_class.php';	
	$imagemapper = new ImageMapperAdmin (__FILE__);
}

?>