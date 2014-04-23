<?php
global $wpdb, $superdbkeys;

$supersettings = stripslashes(get_post_meta($post->ID, 'supersettings', true));
$supersettings = json_decode($supersettings);
foreach($superdbkeys as $key=>$row) {
	$$key = isset($supersettings->$key) ? $supersettings->$key : '';
}

$rand = rand(111111, 999999);

$my_sourceid = explode(':', $source);

$cardata = array();
if($my_sourceid[0]=='content') {
	$args = array(
	'post_type' => 'supercontent',
	'posts_per_page'=> -1,
    'tax_query' => array( 
	        array( 
	            'taxonomy' => 'super_category', 
	            'terms' => array( 
	                $my_sourceid[1] 
	            ), 
	        )
	    )
	);
	
	if($superrandom=='1') {
		$args['orderby']='rand';
	}else {
		$args['orderby']='menu_order';
		$args['order'] = 'DESC';
	}
	
	wp_reset_query();
	$loop = new WP_Query( $args );

	foreach($loop->posts as $row) {
		$cardata[] = $row->post_content;
	}
}else if($my_sourceid[0]=='image') {
	
	$post = get_post($my_sourceid[1]);
	$images = stripslashes(get_post_meta($post->ID, 'images', true));
	
	$images = json_decode($images);
	
	//supershow($images);
	if(isset($images->image)) {
		foreach($images->image as $i=>$row) {
			$temp = '';
			if($images->caption[$i]!='') {
				$temp .= '<div class="supercaption">'.stripslashes(urldecode($images->caption[$i])).'</div>';
			}
			
			if($images->lightboxurl[$i]!='') {
				$temp .= '<a href="' . $images->lightboxurl[$i] . '" class="blk" rel="prettyPhoto[grp1]"><img class="imgslide" src="' . $images->image[$i] . '" /></a>';
			}else if($images->linkurl[$i]!='') {
				$temp .= '<a href="' . $images->linkurl[$i] . '" target="' . $images->target[$i] . '"><img class="imgslide" src="' . $images->image[$i] . '" /></a>';
			}else {
				$temp .= '<img class="imgslide" src="' . $images->image[$i] . '" />';
			}
			$cardata[] = $temp;
		}
	}
	
	if($superrandom=='1') {
		shuffle($cardata);
	}
}

?>
<script>
jQuery(document).ready(function() {
	var opt = {};
	<?php
	foreach($superdbkeys as $key=>$val) {
		if(isset($$key)) {
			if(is_numeric($$key)) {
				echo "opt['$key'] = ".$$key.";";
			}else {
				echo "opt['$key'] = '".$$key."';";
			}
		}
	}
	if($nextPrev=='1') {
		echo "opt['next'] = '#next$rand';";
		echo "opt['prev'] = '#prev$rand';";
	}
	
	if($paging=='1') {
		echo "opt['paging'] = '#pag$rand';";
	}
	
	if($my_sourceid[0]=='image') {
		echo "opt['type'] = 'image';";
	}
	?>
	opt.onload = function($$) {
		var tot = $$.find('>div').length/3;
		$$.find('>div:lt('+(tot)+') a[rel^="prettyPhoto[grp1]"]').attr('rel', 'prettyPhoto[x]');
		$$.find('>div:gt('+((tot*2)-1)+') a[rel^="prettyPhoto[grp1]"]').attr('rel', 'prettyPhoto[y]');
		jQuery("a[rel^='prettyPhoto']").prettyPhoto({
			animation_speed:'fast',
			slideshow:5000,
			hideflash: true,
			autoplay: false
			});
		}
	jQuery("#supercarousel<?php echo $rand; ?>").supercarousel(opt);
});
</script>
<div class="supercrsl<?php echo ($navpadding=='1') ? ' pdgwnav' : ''; ?>">
<div class="supercarousel" id="supercarousel<?php echo $rand; ?>">
<?php
foreach($cardata as $row) {
?>
<div>
<?php echo do_shortcode($row); ?>
</div>
<?php
}
?>
</div><div class="clear"></div>
<?php
if($nextPrev=='1') {
?>
<a class="prev<?php echo ($smallbut=='1') ? ' small' : ''; ?>" id="prev<?php echo $rand; ?>" href="#"><span>prev</span></a>
<a class="next<?php echo ($smallbut=='1') ? ' small' : ''; ?>" id="next<?php echo $rand; ?>" href="#"><span>next</span></a>
<?php
}
?>
<div class="pagination" id="pag<?php echo $rand; ?>"></div>
</div>