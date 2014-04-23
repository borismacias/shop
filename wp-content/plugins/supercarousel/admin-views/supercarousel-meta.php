<?php

global $targets, $supereasings, $superalign, $superdirection, $supereffect, $superdbkeys;



if(isset($_REQUEST['post']) && is_numeric($_REQUEST['post'])) {

	$post = (int)$_REQUEST['post'];

	$post = get_post($post);

	$supersettings = stripslashes(get_post_meta($post->ID, 'supersettings', true));

	$supersettings = json_decode($supersettings);

	foreach($superdbkeys as $keyx=>$row) {

		$$keyx = isset($supersettings->$keyx) ? $supersettings->$keyx : '';

	}

}else {

	foreach($superdbkeys as $keyx=>$row) {

		$$keyx = $row;

	}

}



$cats = get_terms('super_category', 'hide_empty=0');



$args = array('post_type'=>'superimage', 'posts_per_page'=> -1, 'orderby' => 'menu_order', 'order'=>'DESC');

$loop = new WP_Query($args);

//supershow($loop->posts);

?>

<ul class="sprcrsl-admin">
<li class="w2 sainput"><b>Carousel Source</b></li>
<li class="w8">
<select name="super[source]">

<option value="">None</option>

<?php

foreach($loop->posts as $row) {

?>

<option value="image:<?php echo $row->ID; ?>"<?php echo ("image:".$row->ID == $source) ? ' selected="selected"' : ''; ?>>Image-<?php echo $row->post_title; ?></option>

<?php

}



foreach($cats as $row) {

?>

<option value="content:<?php echo $row->term_id; ?>"<?php echo ('content:'.$row->term_id==$source) ? ' selected="selected"' : ''; ?>>Content-<?php echo $row->name; ?></option>

<?php

}

?>

</select>
</li>
</ul>

<ul class="sprcrsl-admin">
    <li class="w10 satitle">
        <strong>Slide Settings</strong>
        <span class="note">Note: Please enter any of the below value</span>
    </li>
    
    <li class="w1 sainput clearL txt-right">
        <label for="visible">Visible</label> 
    </li>
    <li class="w2">
        <input type="text" id="visible" class="smallip" name="super[visible]" value="<?php echo $visible; ?>" />
        <span class="bigtxt">Or</span> 
    </li>
    
    <li class="w15 sainput txt-right">
        <label for="itemwidth">Item Width</label> 
    </li>
    <li class="w2">
        <input type="text" id="itemwidth" class="smallip" value="<?php echo $itemWidth; ?>" name="super[itemWidth]" />px
        <span class="bigtxt">Or</span> 
    </li>
    
    </li>
    <li class="w15 sainput txt-right">
        <label for="itemheight">Item Height</label> 
    </li>
    <li class="w2">
        <input type="text" id="itemheight" class="smallip" value="<?php echo $itemHeight; ?>" name="super[itemHeight]" />px
    </li>
</ul>



<ul class="sprcrsl-admin">
    <li class="w10 satitle">
        <strong>Mobile/Tablet Settings</strong>
		<span class="note">Note: If browser width is less than Mobile Width or Tablet Width, the carousel will consider Mobile Visible or Table Visible regardless. If you want to remove the responsive behaviour just put "0" in Mobile Visible or Tablet Visible</span>
    </li>

    <li class="w2 sainput clearL">
		<label for="mobilevisible">Mobile Visible</label> 
	</li>
    <li class="w2">
		<input type="text" id="mobilevisible" class="smallip" value="<?php echo $mobileVisible; ?>" name="super[mobileVisible]" />
    </li>
    <li class="w2 sainput">
		<label for="mobilewidth">Mobile Width</label> 
    </li>
    <li class="w2">
		<input type="text" id="mobilewidth" class="smallip" value="<?php echo $mobileWidth; ?>" name="super[mobileWidth]" />
    </li>
    <li class="w2 sainput clearL">
		<label for="tabletvisible">Tablet Visible</label> 
    </li>
    <li class="w2">
		<input type="text" id="tabletvisible" class="smallip" value="<?php echo $tabletVisible; ?>" name="super[tabletVisible]" />
    </li>
    <li class="w2 sainput">
		<label for="tabletwidth">Tablet Width</label> 
    </li>
    <li class="w2">
		<input type="text" id="tabletwidth" class="smallip" value="<?php echo $tabletWidth; ?>" name="super[tabletWidth]" />
    </li>
</ul>


<ul class="sprcrsl-admin">
    <li class="w10 satitle clearL">
        <strong>Animation Settings</strong>
		<span class="note">Note:Easing Time is calculated in miliseconds, Step is the number of slides it will move in one go. If you want full-width scroll, just put "0" in Step(s) box</span>
    </li>

    <li class="w1 sainput">
		<label for="direction">Direction</label> 
    </li>
    <li class="w2">
        <select id="direction" class="sel1" name="super[direction]">
        <?php
        foreach($superdirection as $row) {
        ?>
        <option value="<?php echo $row; ?>"<?php echo ($row==$direction) ? ' selected="selected"' : ''; ?>><?php echo $row; ?></option>
        <?php
        }
        ?>
        </select> 
    </li>
    <li class="w1 sainput">
		<label for="effect">Effect</label> 
    </li>
    <li class="w2">
        <select id="effect" class="sel1" name="super[effect]">
        <?php
        foreach($supereffect as $row) {
        ?>
        <option value="<?php echo $row; ?>"<?php echo ($row==$effect) ? ' selected="selected"' : ''; ?>><?php echo $row; ?></option>
        <?php
        }
        ?>
        </select> 
    </li>
    
    <li class="w1 sainput">
		<label for="easing">Easing</label> 
    </li>
    
    <li class="w3">
        <select id="easing" name="super[easing]">
        <?php foreach($supereasings as $row) { ?>
        <option value="<?php echo $row; ?>"<?php echo ($row==$easing) ? ' selected="selected"' : ''; ?>><?php echo $row; ?></option>
        <?php } ?>
        </select>
    </li>
    <li class="w1 sainput clearL">
		<label for="easingtime">Easing Time</label> 
    </li>
    <li class="w2">
		<input id="easingtime" type="text" class="smallip" value="<?php echo $easingTime; ?>" name="super[easingTime]" /> 
    </li>
    <li class="w1 sainput">
		<label for="step">Step(s)</label> 
    </li>
    <li class="w1 sainput">
		<input id="step" type="text" class="smallip" value="<?php echo $step; ?>" name="super[step]" />
	</li>
</ul>



<ul class="sprcrsl-admin">
    <li class="w10 satitle clearL">
        <strong>Automatic Settings</strong>
		<span class="note">Note:Pause Time is calculated in miliseconds. Slide Gap is calculated in pixels.</span>
    </li>

    <li class="w15 sainput clearL">
		<label for="autoplay">Auto Play</label> 
		<input id="autoplay" type="checkbox" class="smallip ck"<?php echo ($auto=='1') ? ' checked="checked"' : ''; ?> value="1" name="super[auto]" /> 
	</li>
    <li class="w15 sainput">
    	<label for="pausetime">Pause Time</label>
    </li>
    <li class="w1">
	    <input id="pausetime" type="text" class="smallip" value="<?php echo $pauseTime; ?>" name="super[pauseTime]" /> 
    </li>
    <li class="w1">&nbsp;</li>
    
    <li class="w2 sainput clearL">
		<label for="pauseover">Pause Over</label> 
        <input id="pauseover" type="checkbox" class="smallip ck"<?php echo ($pauseOver=='1') ? ' checked="checked"' : ''; ?> value="1" name="super[pauseOver]" /> 
	</li>

    <li class="w2 sainput">
        <label for="autoheight">Auto Height</label> 
        <input id="autoheight" type="checkbox" class="smallip ck"<?php echo ($autoHeight=='1')? ' checked="checked"' : ''; ?> value="1" name="super[autoHeight]" />
	</li>
    
    <li class="w2 sainput">
        <label for="superrandom">Random</label> 
        <input id="superrandom" type="checkbox" class="smallip ck"<?php echo ($superrandom=='1') ? ' checked="checked"' : ''; ?> value="1" name="super[superrandom]" />
	</li>
    
    <li class="w3 sainput">
        <label for="slidegap">Slide Gap</label> 
        <input id="slidegap" type="text" class="smallip" value="<?php echo $slideGap; ?>" name="super[slideGap]" /> 
	</li>
</ul>

<ul class="sprcrsl-admin no-brdr">
    <li class="w10 satitle">
        <strong>Navigation Settings</strong>
		<span class="note">Note:Keyboard enables next/prev/up/down arrow keys and numeric keys.</span>
    </li>

    <li class="w3 sainput clearL">
        <label for="nextprev">Next / Prev</label> 
        <input id="nextprev" type="checkbox" class="smallip ck"<?php echo ($nextPrev=='1') ? ' checked="checked"' : ''; ?> value="1" name="super[nextPrev]" />
	</li>

    <li class="w3 sainput">
        <label for="pagination">Pagination</label> 
        <input id="pagination" type="checkbox" class="smallip ck"<?php echo ($paging=='1') ? ' checked="checked"' : ''; ?> value="1" name="super[paging]" /> 
	</li>

    <li class="w3 sainput">
        <label for="circular">Circular</label> 
        <input id="circular" type="checkbox" class="smallip ck" value="1"<?php echo ($circular=='1') ? ' checked="checked"' : ''; ?> name="super[circular]" /> 
	</li>

    <li class="w3 sainput clearL">
        <label for="mousewheel">Mouse Wheel</label> 
        <input id="mousewheel" type="checkbox" class="smallip ck" value="1"<?php echo ($mouseWheel=='1') ? ' checked="checked"' : ''; ?> name="super[mouseWheel]" /> 
	</li>
    
    <li class="w3 sainput">
        <label for="touchswipe">Touch Swipe</label> 
        <input id="touchswipe" type="checkbox" class="smallip ck" value="1"<?php echo ($swipe=='1') ? ' checked="checked"' : ''; ?> name="super[swipe]" /> 
	</li>
    
    <li class="w3 sainput">
        <label for="keyboard">Keyboard</label> 
        <input id="keyboard" type="checkbox" class="smallip ck" value="1"<?php echo ($key=='1') ? ' checked="checked"' : ''; ?> name="super[key]" /> 
	</li>
	
	<li class="w3 sainput">
        <label for="smallbut">Small Buttons</label> 
        <input id="smallbut" type="checkbox" class="smallip ck" value="1"<?php echo ($smallbut=='1') ? ' checked="checked"' : ''; ?> name="super[smallbut]" /> 
	</li>
	
	<li class="w3 sainput">
        <label for="navpadding">Next/Prev Padding</label> 
        <input id="navpadding" type="checkbox" class="smallip ck" value="1"<?php echo ($navpadding=='1') ? ' checked="checked"' : ''; ?> name="super[navpadding]" /> 
	</li>
</ul>