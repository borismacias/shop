<?php
/*
Template name: My Account Sidebar
This templates add My account to the sidebar. 
*/

global $post, $yith_wcwl;

$current_url = get_permalink();
$wishlist_url = '';

if (in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
$wishlist_url = $yith_wcwl->get_wishlist_url();
}

$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
$myaccount_page = get_permalink( $myaccount_page_id );

if (!is_user_logged_in() && $current_url != $myaccount_page && $wishlist_url != $current_url) {
  header( "Location: $myaccount_page" );
}

get_header(); 
?>
<div class="page-header">
<?php if( has_excerpt() ) the_excerpt();?>
</div>

<div  class="page-wrapper my-account">
<div class="row">
<div id="content" class="large-12 columns" role="main">

<?php if(is_user_logged_in()){?> 

<div class="row collapse vertical-tabs">
<div class="large-3 columns">
	<?php if(is_user_logged_in()){?>
		<div class="account-user hide-for-small">
		<?php 
			 	$current_user = wp_get_current_user();
			 	$user_id = $current_user->ID;
				echo get_avatar( $user_id, 60 );
	    ?>

	    <span class="user-name"><?php echo $current_user->display_name?> <em><?php echo '#'.$user_id;?></em></span>
	   	<span class="logout-link"><a href="<?php echo wp_logout_url(); ?>">Log out</a></span>		 

	    <br>
	</div>
	<?php } ?>
	<div class="account-nav">
		 <?php if ( has_nav_menu( 'my_account' ) ) : ?>
			<?php  
					wp_nav_menu(array(
						'theme_location' => 'my_account',
						'menu_class'      => 'tabs-nav',
						'depth' => 0,
					));
			?>
		 <?php else: ?>
	        Define your 'My Account' navigation <b>Apperance > Menus</b>
	    <?php endif; ?>
	</div><!-- .account-nav -->
</div><!-- .large-3 -->

<div class="large-9 columns">
	<div class="tabs-inner active">		
	

			<?php while ( have_posts() ) : the_post(); ?>
				<h1><?php the_title(); ?></h1>

				<?php the_content(); ?>
			
			<?php endwhile; // end of the loop. ?>		


	</div><!-- .tabs-inner -->
	</div><!-- .large-9 -->
</div><!-- .row .vertical-tabs -->

<?php } else { ?>  
	
		<?php while ( have_posts() ) : the_post(); ?>
			<h1><?php the_title(); ?></h1>

			<?php the_content(); ?>
		
		<?php endwhile; // end of the loop. ?>		

<?php } ?>


</div><!-- end #content large-12 -->
</div><!-- end row -->
</div><!-- end page-right-sidebar container -->



<?php get_footer(); ?>

