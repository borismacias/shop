<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package flatsome
 */

get_header(); 

global $wp_query;
$cat = $wp_query->get_queried_object();
?>

<div class="cat-header">

<?php 
// GET CUSTOM HEADER CONTENT FOR CATEGORY
if($cat->post_title=='Lookbooks'){
	echo do_shortcode('[block id="banner-lookbooks"]');
}
else{

	// GET CUSTOM HEADER CONTENT FOR CATEGORY
	if(function_exists('get_term_meta')){
		print_r($content);
		$queried_object = get_queried_object();
		if (isset($queried_object->term_id)){

			$term_id = $queried_object->term_id;  
			$content = get_term_meta($term_id, 'cat_meta');
			if(isset($content[0]['cat_header']) && $content[0]['cat_header']!=''){
				
				echo do_shortcode($content[0]['cat_header']);
			}
			else if($cat->name == "Diseño Nacional"){
				echo do_shortcode('[ux_banner bg="http://107.170.252.164/shop/wp-content/uploads/2014/04/banner-nacional.jpg" height="250px" animation="flipInX" text_align="center" text_pos="center" text_color="light" text_width="80%" parallax="1"]<h3>'.$cat->name.'<h3>[/ux_banner]');
			}
			else{
				echo do_shortcode('[ux_banner bg="http://107.170.252.164/shop/wp-content/uploads/2014/04/banner-'.$cat->slug.'.jpg" height="250px" animation="flipInX" text_align="center" text_pos="center" text_color="light" text_width="80%" parallax="1"]<h3>'.$cat->name.'<h3>[/ux_banner]');
			}
		}
	}
}

?>
</div>

<div class="page-header">
<?php if( has_excerpt() ) the_excerpt();?>
</div>

<div  class="page-wrapper">
<div class="row">

	
<div id="content" class="large-12 left columns" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>

		<?php endwhile; // end of the loop. ?>

</div><!-- end #content large-9 left -->

</div><!-- end row -->
</div><!-- end page-right-sidebar container -->


<?php get_footer(); ?>
