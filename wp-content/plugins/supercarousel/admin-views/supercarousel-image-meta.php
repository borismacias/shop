<?php

global $targets;

if(isset($_REQUEST['post']) && is_numeric($_REQUEST['post'])) {

	$post = (int)$_REQUEST['post'];

	$post = get_post($post);

	$images = get_post_meta($post->ID, 'images', true);
	//supershow($images);
	if($images!='' and !empty($images)) {
		$images = (get_post_meta($post->ID, 'images', true));
	}

	if(is_string($images)) {

		$images = json_decode($images);

	}else {
		$images = (object) array();
		
		$images->image = array();

	}

}else {

	$images = (object) array();
	
	$images->image = array();

}

?>

<center class="mrg10T">
<a class="button" href="javascript: void(0);" onclick="media_lib_img('Select Images');">
<strong>Select or Upload Images</strong>
</a>
<a class="button" href="javascript: void(0);" onclick="add_super_image_url();">
<strong>Insert From URL</strong>
</a>
</center>

<div id="slider_images" class="slider_images">

<?php

foreach($images->image as $i=>$row) {
	if($images->id[$i]!='') {
		$image_attributes = wp_get_attachment_image_src($images->id[$i], 'thumbnail');
	}else {
		$image_attributes = array();
		$image_attributes[0] = $images->image[$i];
	}
?>

<ul class="sprcrsl-admin img-panel">
<li class="w2 txt-center">
<div>
<img width="85%" src="<?php echo $image_attributes[0]; ?>" class="fl pad12 thumbimg" />
<span class="note">Move Image</span>
</div>
<b><?php echo $images->title[$i]; ?></b>
</li>

<li class="w8">
    <div class="clr">
        <div class="deleteslide">Delete</div>
        <input type="hidden" value="<?php echo $images->image[$i]; ?>" name="images[image][]" />
        <input type="hidden" name="images[title][]" value="<?php echo $images->title[$i]; ?>" />
        <input type="hidden" name="images[id][]" value="<?php echo $images->id[$i]; ?>" />
    </div>
    <p>
        <label for="images<?php echo $i; ?>" class="w30pc">Lightbox URL:</label>
        <input type="text" name="images[lightboxurl][]" value="<?php echo $images->lightboxurl[$i]; ?>" id="images<?php echo $i; ?>" class="ip1 w50pc" />
        <a href="javascript: void(0);" onclick="select_image('images<?php echo $i; ?>');" class="button">Image</a>
    </p>
    
    <p>
        <label for="linkurl<?php echo $i; ?>" class="w30pc">Link URL:</label> 
        <input type="text" name="images[linkurl][]" value="<?php echo $images->linkurl[$i]; ?>" class="ip1 w40pc" id="linkurl<?php echo $i; ?>" /> 
        Target
        <select name="images[target][]">
            <?php foreach($targets as $target) { ?>
            <option value="<?php echo $target; ?>"<?php echo ($images->target[$i]==$target) ? ' selected="selected"' : ''; ?>><?php echo $target; ?></option>
            <?php } ?>
        </select>
    </p>
    <p>
        <label for="caption<?php echo $i; ?>" class="w30pc">Caption:</label>
        <textarea cols="60" name="images[caption][]" rows="1" id="caption<?php echo $i; ?>" class="w70pc"><?php echo stripslashes(urldecode($images->caption[$i])); ?></textarea>
    </p>
</li>
</ul>

<?php

}

?>

</div>