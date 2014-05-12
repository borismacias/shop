<?php
/*
 * Administration functionality for Sendgrid Mailing List Plugin
 * Author: Matt Pramschufer of E-Moxie
 * URL: www.emoxie.com
 * Date: 7/3/2013
 */

define('SGML_ID', 'sgml-plugin-options');
define('SGML_NICK', 'Sendgrid Mailing List');

class Sendgrid_Mailing_List_Admin {

    public static function init() {
        add_action('admin_init', array(__CLASS__, 'register'));
        add_action('admin_menu', array(__CLASS__, 'menu'));
    }

    public static function register() {
        register_setting(SGML_ID . '_options', 'SGML_database_id');
        register_setting(SGML_ID . '_options', 'SGML_contact_lists');
        register_setting(SGML_ID . '_options', 'SGML_api_username');
        register_setting(SGML_ID . '_options', 'SGML_api_password');
        register_setting(SGML_ID . '_options', 'SGML_endpoint');
        register_setting(SGML_ID . '_options', 'SGML_default_list');
        register_setting(SGML_ID . '_options', 'SGML_success_message');        
    }

    public static function menu() {
        // Create menu tab
        add_options_page(SGML_NICK . ' Plugin Options', SGML_NICK, 'manage_options', SGML_ID . '_options', array('Sendgrid_Mailing_List_Admin', 'options_page'));
    }

    public static function options_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $plugin_id = SGML_ID;
        // display options page
        require_once dirname(__FILE__) . '/tpl/admin-options.php';
    }

}