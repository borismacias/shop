<?php
/**
 * Plugin Name: Club tacones tags :D
 * Plugin URI: http://github.com/de5pair/taconestags
 * Description: Ver plugin name.
 * Version: 0.00000001
 * Author: Despair
 * Author URI: http://github.com/de5pair
 * License: asd
 */



/*
Agregando link al plugin en Settings->ClubTacones Tags
*/


    function club_tacones_tags_menu(){
        add_options_page( "LookBook Editor","Editor de lookbook","manage_options", "cttags","create_club_tacones_tags_page");
    }

    add_action("admin_menu","club_tacones_tags_menu");

    function create_club_tacones_tags_page(){

        if(!current_user_can("manage_options")){
            wp_die("No tienes los permisos suficientes!");
        }

        require('inc/club-tacones-page-wrapper.php');

    }
 
    function clubtacones_tags_admin_script() {
        wp_enqueue_media();
        wp_register_script('clubtacones_tags_script',plugin_dir_url( __FILE__ ).'js/clubtacones_tags.js', array('jquery'));
        wp_register_script('heatmap',plugin_dir_url( __FILE__ ).'js/heatmap.js', array('jquery'));
        wp_register_script('tiptip',plugin_dir_url( __FILE__ ).'js/tiptip.js', array('jquery','jqueryui'));
        wp_enqueue_script('clubtacones_tags_script');
        wp_enqueue_script('heatmap');
        wp_enqueue_script('tiptip');
    }

    add_action('admin_enqueue_scripts', 'clubtacones_tags_admin_script');

    //Activando selector imagenes

    function wp_gear_manager_admin_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery');
    }

        function wp_gear_manager_admin_styles() {
        wp_enqueue_style('thickbox');
    }

    add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
    add_action('admin_print_styles', 'wp_gear_manager_admin_styles');

    // //SETUP
    // function clubtacones_tags_install(){
    //     //Do some installation work
    // }
    // //register_activation_hook(__FILE__,'clubtacones_tags_install');

    // function clubtacones_tags_scripts(){
    //     wp_register_script('clubtacones_tags_script',plugin_dir_url( __FILE__ ).'js/clubtacones_tags.js');
    //     wp_enqueue_script('clubtacones_tags_script');
    // }
    // add_action('wp_enqueue_scripts','clubtacones_tags_scripts');

    // //HOOKS
    // add_action('init','clubtacones_tags_init');
    // add_action('admin_menu',array($this,'add_page'));
    // /********************************************************/
    // /* FUNCTIONS
    // ********************************************************/
    // function clubtacones_tags_init(){
    //     //do work
    //     run_sub_process();
    // }

    // function run_sub_process(){
    //     //
    // }


?>