<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$base = get_option( 'woocommerce_email_base_color' );

$base_lighter_40 = wc_hex_lighter( $base, 40 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:6px;
    background-color:lightgray;
";

$credit = "
	border:0;
	color: $base_lighter_40;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
";
?>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Footer -->
                                	<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer"  style="<?php echo $template_footer; ?>">
                                    	<tr>
                                        	<td valign="top">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td align="center">
                                                            <a href="http://www.facebook.com/clubtacones">
                                                                <img src="http://107.170.252.164/shop/wp-content/uploads/2014/04/facebook-32.png">
                                                            </a>
                                                            <a href="http://www.twitter.com/clubtacones">
                                                                <img src="http://107.170.252.164/shop/wp-content/uploads/2014/04/twitter-32.png">
                                                            </a>
                                                            <a href="http://www.instagram.com/clubtacones">
                                                                <img src="http://107.170.252.164/shop/wp-content/uploads/2014/04/instagram-32.png">
                                                            </a>
                                                            <a href="http://www.pinterest.com/clubtacones">
                                                                <img src="http://107.170.252.164/shop/wp-content/uploads/2014/04/pinterest-32.png">
                                                            </a>
                                                            <a href="http://plus.google.com/+clubtacones">
                                                                <img src="http://107.170.252.164/shop/wp-content/uploads/2014/04/googleplus-32.png">
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center">
                                                            <span><em>Copyright &copy; 2014 Club Tacones, Todos los derechos reservados.</em><br /></span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Footer -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>