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

<table>
	<tr>
		<td>
			<img src="http://107.170.252.164/shop/wp-content/uploads/2014/05/chicas1.jpg" alt="chicas">
		</td>	
	</tr>
</table>

<?php if ( get_option( 'woocommerce_registration_generate_password' ) == 'yes' && $password_generated ) : ?>

	<p><?php printf( __( "Your password has been automatically generated: <strong>%s</strong>", 'woocommerce' ), esc_html( $user_pass ) ); ?></p>

<?php endif; ?>

<!-- <p><?php printf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?></p>
 -->
<table>
<tr>
<td align="center">
	<button style="display: inline-block;margin-bottom: 0;font-weight: 400;text-align: center;vertical-align: middle;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-color:#e23462important!;color:white;"><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ));?>">Ir de shopping!</a></button>
</td>
</tr>
</table>
 

<?php do_action( 'woocommerce_email_footer' ); ?>