<?php

/*
 * AJAX functionality for Sendgrid Mailing List Plugin
 * Author: Matt Pramschufer of E-Moxie
 * URL: www.emoxie.com
 * Date: 7/3/2013
 */

//Required for WP functionality
include($_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php');

// Include Sendgrid API Class
require_once dirname(__FILE__) . '/lib/sendgrid-php/SendGrid_loader.php';

//Forcing Header for Success.  Sometimes WP returns back 404
header("HTTP/1.1 201 Force Success");

//Grab our Settings/Options from Plugin
$mailingList = get_option('SGML_list');
$userName = get_option('SGML_api_username');
$password = get_option('SGML_api_password');
$successMessage = get_option('SGML_success_message');

if (!empty($_POST['SGML_action']) && isset($_POST['SGML_action'])) {
    $SGML_action = $_POST['SGML_action'];
} else {
    $SGML_action = null;
}


//Trim and Sanitize email before doing anything
$email = sanitize_email($_POST['SGML_email']);

//Check to see if valid email
if (!is_email($email)) {
    SGML_passBack(false, 'Invalid Email');
    die();
}


/*
 * Handles all of the processing of ajax requests from /js/ajax.js
 * subscribe, get, set, delete
 */

switch ($SGML_action) {
    /*
     * Subscribe function.  Takes inputted email address and subscribes to Sendgrid database.
     */
    case 'subscribe':

        // Post our call to Sendgrid
        $subscribe_SendGrid = Sendgrid_Mailing_List::addSendgridSubscriber($email, $userName, $password, $_POST['SGML_list']);

        // if shortcode has been set with a redirect flag 
        // e.g.  [sg-subscription-form redirect=http://www.google.com] 

        if (!empty($_POST['SGML_redirect'])) {
            $successMessage = 'Success... Redirecting to Thank You Page.  <script>window.location="' . $_POST['SGML_redirect'] . '";</script>';
        } else {
            // otherwise we take the success message from our Plugin Settings/Options and display 
            $successMessage = $successMessage;
        }

        // If successfuly subscribed to Sendgrid send back SuccessMessage else Error Message
        if ($subscribe_SendGrid == true) {
            SGML_passBack(true, $successMessage);
        } else {
            SGML_passBack(false, 'Sendgrid Error ' . $subscribe_SendGrid);
        }

        break;


    case 'get';
        /*
         * Function to get all setting about a user from Sendgrid
         * Requires email
         */

        ## Not implemented Yet

        break;

    case 'set';
        /*
         * Function to update or set preferences in Sendgrid
         */

        ## Not implemented Yet

        break;

    case 'delete';

        ## Not implemented Yet

        break;
}

/** Passing JSON back to the javascript letting it know if registration was a successs
 * 
 * @param type $response
 * @param type $responseMsg
 * @param type $data
 */
function SGML_passBack($response, $responseMsg, $data = null) {
    header("HTTP/1.1 201 Force OK");
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

    $return = json_encode(array('response' => $response, 'response_message' => $responseMsg, 'data' => $data));
    echo $return;
}