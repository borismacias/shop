<?php

/*
  Plugin Name: SendGrid Mailing List
  Plugin URI: www.emoxie.com
  Description: Handles email subscription to SendGrid
  Version: 1.1.2
  Author: E-Moxie
  Author URI: www.emoxie.com
 */

if (!class_exists('Sendgrid_Mailing_List')) :

    class Sendgrid_Mailing_List {

        /**
         * Initilaztion function for enqueuing scripts and wordpress shortcodes
         */
        public static function init() {
            add_filter('widget_text', 'do_shortcode');
            add_action('wp_enqueue_scripts', 'SendgridMailingList_enqueue_scripts');
            add_shortcode('sg-preference-center', 'SendgridMailingListPreferenceCenter_shortcode');
            add_shortcode('sg-subscription-form', 'SendgridMailingListSubscriptionForm_shortcode');
            add_action('wp_ajax_the_ajax_hook', 'SendgridMailingList_ajax_handle');
            add_action('wp_ajax_nopriv_the_ajax_hook', 'SendgridMailingList_ajax_handle'); 

            // If logged into administration area call the Admin functions of the Sendgrid
            // Plugin
            if (is_admin()) {
                require_once dirname(__FILE__) . '/sendgridMailingList-admin.php';
                Sendgrid_Mailing_List_Admin::init();
            }
        }

        /**
         * BuildSubscriptionForm, builds the Sendgrid subscription form on frontend
         * Called by short code [sg-subscription-form list=$list $redirect=$redirect]
         * @param type $list
         * @param type $redirect
         * @return type
         */
        public static function BuildSubscriptionForm($list = null, $redirect = null) {
            // If no list is specified in shortcode pull default list out of plugin settings
            if ($list == null) {
                $list = get_option('SGML_list');
            }
            // Start output buffering capture
            ob_start();
            require dirname(__FILE__) . '/tpl/subscription-form.php';

            // Grab output buffer and store in var and delete created buffer
            $return = ob_get_clean();
            
            return $return;
        }

        /** Adds a subscriber to Sendgrid using their Email and the Sendgrid API
         * 
         * @param type $email
         * @param type $userName
         * @param type $password
         * @param type $mailingList
         * @return boolean
         */
        static public function addSendgridSubscriber($email, $userName, $password, $mailingList) {
            //include Sendgrid Class
            require_once dirname(__FILE__) . '/lib/sendgrid-php/SendGrid_loader.php';

            try {
                    $sendgrid = new SendGrid($userName, $password);
                    $response = $sendgrid
                            ->marketing
                            ->setEmail($email)
                            ->setListID($mailingList)
                            ->execute();

                    $response = json_decode($response);
                //error_log(print_r($response,true) . '----' . print_r($sendgrid,true));
                if (empty($response->error)) {
                    return true;
                } else {
                    return false;
                }                    

            } catch (Exception $e) {
                // Handle connection exceptions.
                error_log('Error Connecting to Email Service Provider');
                return false;
            }            
            
            
        }

    } // End Class
    
    /*
     * Functions used Throughout Plugin
     */

    function SendgridMailingList_enqueue_scripts() {
        wp_enqueue_script('SendgridMailingList_ajax_handle', plugin_dir_url(__FILE__) . 'js/ajax.js', array('jquery'));
        wp_localize_script('SendgridMailingList_ajax_handle', 'SendgridMailingList_ajax_script', array('ajaxurl' => plugin_dir_url(__FILE__) . 'ajax.php'));
        wp_register_style('sendgrid-mailing-list-styles', plugins_url('/css/sgml.css', __FILE__));
        wp_enqueue_style('sendgrid-mailing-list-styles');
    }

    /**
     * Shortcode to display email subscription form.
     *
     * [sendgrid-mailinglist]
     */
    function SendgridMailingListSubscriptionForm_shortcode($atts) {
        extract(shortcode_atts(array(
            'list' => '',
            'redirect' => '',
                        ), $atts));

        return Sendgrid_Mailing_List::BuildSubscriptionForm($list, $redirect);
    }

    Sendgrid_Mailing_List::init();

endif;