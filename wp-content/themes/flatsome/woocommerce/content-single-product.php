<?php

    /**
     * The template for displaying lookbook product style content within loops.
     *
     * Override this template by copying it to yourtheme/woocommerce/content-product.php
     *
     * @author      WooThemes
     * @package     WooCommerce/Templates
     * @version     1.6.4
     */

 
    global $post, $product, $flatsome_opt;
    // Get category permalink

    $permalinks     = get_option( 'woocommerce_permalinks' );
    $category_slug  = empty( $permalinks['category_base'] ) ? _x( 'product-category', 'slug', 'woocommerce' ) : $permalinks['category_base'];
?>

<style type="text/css">
.prod-dropdown a:even{
    background: none!important;
}
</style>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>> 
    
<div class="row">    
        <?php
            /**
             * woocommerce_before_single_product hook
             *
             * @hooked woocommerce_show_messages - 10
             */
             do_action( 'woocommerce_before_single_product' );
        ?>    
        <div class="large-6 columns product-gallery">        
        
            <?php
                /**
                 * woocommerce_show_product_images hook
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );
            ?>
        
        </div><!-- end large-6 - product-gallery -->
        
        <div class="product-info large-4 small-12 columns left">
                <?php
                    /**
                     * woocommerce_single_product_summary hook
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked ProductShowReviews() (inc/template-tags.php) - 15
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     */
                    do_action( 'woocommerce_single_product_summary' );
                ?>
        
        </div><!-- end product-info large-4 -->

<div class="product-page-aside large-2 small-12 columns text-center hide-for-small">
    
    <div class="next-prev-nav">
        <?php // edit this in inc/template-tags.php // ?>
        <?php next_post_link_product('%link', 'icon-angle-left next', true); ?>
        <?php previous_post_link_product('%link', 'icon-angle-right prev', true); ?>
    </div>

     <?php  woocommerce_get_template('single-product/up-sells.php');?> 

</div><!-- .product-page-aside -->
     
        
</div><!-- end row -->
    
    
<?php
    //Get the Thumbnail URL for pintrest
    $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
?>


    
<div class="row">
    <div class="large-12 columns">
        <div class="product-details <?php echo $flatsome_opt['product_display']; ?>-style">
               <div class="row">

                    <div class="large-12 columns ">
                    <?php woocommerce_get_template('single-product/tabs/tabs.php'); ?>
                    </div><!-- .large-9 -->
                
               </div><!-- .row -->
        </div><!-- .product-details-->

        <hr/><!-- divider -->
    </div><!-- .large-12 -->
</div><!-- .row -->


    <div class="related-product">
        <?php
            /**
             * woocommerce_after_single_product_summary hook
             *
             * @hooked woocommerce_output_related_products - 20
             */

            do_action( 'woocommerce_after_single_product_summary' );

            $name = get_query_var('name');
            echo "<div class='row'><div class='large-12 columns'><h3 class='section-title'><span>&iquest;C&oacute;mo lo uso?</span></h3>";
            if($name == 'collar-sweet-flower'){
                echo do_shortcode('[supercarousel id=1808]');
            }
            else{
                echo do_shortcode('[block id='.$name.'-look]');
            }
            echo "</div></div>";
            $terms = get_the_terms($post->ID,'product_cat');
            if(isset($terms[109])){
                $cat = $terms[109];
            }
            else{
                $cat = end($terms);
            }
            $slug = $cat->slug;
            echo do_shortcode('[ux_custom_products cat="'.$slug.'" products="5" columns="5" title="M&aacute;s de esta marca"]' );

        ?>
    </div><!-- related products -->

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>


<script type="text/javascript">
    jQuery(document).ready(function(){
         window.setTimeout(function(){
             jQuery(".prod-dropdown a:even").css("background","none");
         },300)
        
     });
</script>
