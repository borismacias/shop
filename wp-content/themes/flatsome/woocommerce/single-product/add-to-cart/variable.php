<?php
/**
 * Variable product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $post;
?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<style type="text/css">
.boton_ceci{ 
    height: 25px;
	width: 159px;
	padding: 18px;
	padding-top: 1px;
	padding-left: 8px;
	text-align: center;
	color: #FFFFFF;
	font-family: 'Arial';
	font-size: 18px;
	background: #EE2A5C;
	background: -moz-linear-gradient(top, #EE2A5C 0%, #EE2A5C 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#EE2A5C), color-stop(100%,#EE2A5C));
	background: -webkit-linear-gradient(top, #EE2A5C 0%,#EE2A5C 100%);
	background: -o-linear-gradient(top, #EE2A5C 0%,#EE2A5C 100%);
	background: -ms-linear-gradient(top, #EE2A5C 0%,#EE2A5C 100%);
	background: linear-gradient(to bottom, #EE2A5C 0%,#EE2A5C 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#EE2A5C', endColorstr='#EE2A5C', GradientType=0 );
	border-width: 2px;
	border-style: outsetOutset;
	border-color: #E0DDDC;
	border-radius: 44px;
	box-shadow: 0px 1px 0px 0px #7A8EB9;
}
</style>


<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php if ( ! empty( $available_variations ) ) : ?>
		<table class="variations custom" cellspacing="0">
			<tbody>
				<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label( $name ); ?></label></td>
						<td class="value"><div class="select-wrapper"><select class="custom" id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>">
							<option value=""><?php echo __( 'Choose an option', 'woocommerce' ) ?>&hellip;</option>
							<?php
								if ( is_array( $options ) ) {

									if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
										$selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
									} elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
										$selected_value = $selected_attributes[ sanitize_title( $name ) ];
									} else {
										$selected_value = '';
									}

									// Get terms if this is a taxonomy - ordered
									if ( taxonomy_exists( $name ) ) {

										$orderby = wc_attribute_orderby( $name );

										switch ( $orderby ) {
											case 'name' :
												$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
											break;
											case 'id' :
												$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false, 'hide_empty' => false );
											break;
											case 'menu_order' :
												$args = array( 'menu_order' => 'ASC', 'hide_empty' => false );
											break;
										}

										$terms = get_terms( $name, $args );

										foreach ( $terms as $term ) {
											if ( ! in_array( $term->slug, $options ) )
												continue;

											echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
										}
									} else {

										foreach ( $options as $option ) {
											echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
										}

									}
								}
							?>
						</select></div> <?php
							if ( sizeof($attributes) == $loop )
								echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'woocommerce' ) . '</a>';
						?></td>
					</tr>
		        <?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap" style="display:none;">
			<?php do_action( 'woocommerce_before_single_variation' ); ?>

			<div class="single_variation"></div>

			<div class="variations_button">
				<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
				<?php woocommerce_quantity_input(); ?>

			</div>

			<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			<input type="hidden" name="variation_id" value="" />

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php else : ?>

		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

	<?php endif; ?>

</form>

<?php 
	$modals = array("zapatos"=>"1","accesorios"=>"2","ropa"=>"3");
	$slug_categories = array("zapatos","accesorios","ropa");
	$post_categories = get_the_terms($post->ID,'product_cat');
	$modal = "";
	foreach ($post_categories as $key => $value) {
		if(in_array($value->slug,$slug_categories)){
			$modal = $value->slug;
			break;
		}
	}
?>

<input type="button" style="position:relative;top:-14px;"href="#" class="boton_ceci eModal-<?php echo $modals[$modal]?>" value="Ver guía de tallas">
<input type="button" class ="boton_ceci tooltip" style="position:relative;top:-14px;"href="#" value="Horma">
<?php echo do_shortcode('[content_tooltip id="1398" title="horma-chica"]');?>
<!-- <a style="position:relative;top:-14px;"href="#" class="eModal-3">Ver guía de tallas (Ropa)</a> -->

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
