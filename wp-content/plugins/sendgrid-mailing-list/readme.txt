=== SendGrid Mailing List ===
Contributors: mattpramschufer
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mattpram%40gmail%2ecom
Tags: sendgrid.com, SendGrid, Mailing List, Newsletter Subscription, SendGrid Subscription
Requires at least: 3.3
Tested up to: 3.5.2
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick and easy way to add an email subscription form to your website which 
enters users into a SendGrid mailing list.

== Description ==

This plugin is a very basic simple plugin that utilizes the SendGrid.com API to 
be able to have users enter their email to a particular recipient list.

Currently SendGrid.com does not have a html web form widget to allow users to 
embed a subscription form on their website.  This plugin provides a simple form 
with just Email Address and a go button.  You specify your List Name, and your 
API Username / Password in the settings file.

###Usage

= Shortcode = 
`[sg-subscription-form]`
By default the short code will take options set in the plugin options
= Options =
* Specify List: `[sg-subscription-form list='Mailing List']`
* Redirect to URL After Success: `[sg-subscription-form redirect='http://www.google.com']`

== Installation ==

1. Activate the plugin through the `Plugins` menu in WordPress
1. Go to `Settings->SendGrid Mailing List` and enter in your Recipient List name, API Username and API Key. *(These are the same credentials that you login to SendGrid.com with)*
1. You can then place the shortcode `[sg-mailinglist]` in a widget area or directly in a page/post to display the subscription form.  

== Frequently asked questions ==

Please send any questions to matt@pramschufer.com
= How do I put a subscription form in my sidebar =
In your Widgets area use a Text widget and then input the shortcode `[sg-mailinglist]`

= How do I put a subscription code in my template with PHP? =
Open your template file and the put in `<?php do_shortcode('[sg-mailinglist]'); ?>`

= How do I redirect a user to a different page after subscription? =
Add in the redirect option into your short code 
`[sg-subscription-form redirect='http://www.google.com']`

= Can I specify different mailing lists for each short code? = 
Yes you can.  Utilize the list option in the shortcode 
`[sg-subscription-form list='My Mailing List']`  
This can be different than what you have specified in the plugin options as your
default mailing list.


== Screenshots ==

1. Settings Screen

== Changelog ==

= 1.0 =
* Initial Release
= 1.1.1 =
* Changed Template Structure
= 1.1.2 = 
* Refactored AJAX processing

== Upgrade notice ==
None.