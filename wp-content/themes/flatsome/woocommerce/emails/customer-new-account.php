<?php
/**
 * Customer new account email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header'); ?>

<table border="0" cellpadding="10" cellspacing="0" width="100%">
	<tr>
		<td align="center">
			<h1 style="font-family:Georgia, Times New Roman, Times, serif;font-style:italic;font-weight:normal;font-size:50px!important;">Bienvenida al Mundo <br> Club Tacones</h1>
		</td>
	</tr>
</table>


<table>
	<tr>
		<td>
			<img src="http://107.170.252.164/shop/wp-content/uploads/2014/05/chicas1.jpg" alt="chicas">
		</td>	
	</tr>
</table>

<!-- 
<?php if ( get_option( 'woocommerce_registration_generate_password' ) == 'yes' && $password_generated ) : ?>

	<p><?php printf( __( "Your password has been automatically generated: <strong>%s</strong>", 'woocommerce' ), esc_html( $user_pass ) ); ?></p>

<?php endif; ?> -->

<!-- <p><?php printf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?></p>
 -->
<table border="0" cellpadding="10" cellspacing="0" width="100%">
	<tr>
		<td align="center">
			<button style="width:140px;display: inline-block;margin-bottom: 0;font-weight: 400;text-align: center;vertical-align: middle;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;border-radius: 4px;user-select: none;background-color:#e23462;color:white;"><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ));?>" style="color:white!important;text-decoration: none !important;">Ir de shopping!</a></button>
		</td>
	</tr>
</table>
 

<?php do_action( 'woocommerce_email_footer' ); ?>