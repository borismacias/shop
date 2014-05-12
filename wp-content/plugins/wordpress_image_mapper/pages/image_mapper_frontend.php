<?php
	if($id) 
	{
		global $wpdb;
		$imagemapper = $wpdb->get_results('SELECT * FROM ' . $wpdb->base_prefix . 'image_mapper WHERE id='.$id);
		$imagemapper = $imagemapper[0];
	}
	$title = $imagemapper->name;
	foreach(explode('||',$imagemapper->settings) as $val) 
	{
		if (substr($val, strpos($val, '-')) != 'dummy')
		{
			$expl = explode('::',$val);
			$settings[$expl[0]] = $expl[1];
		}
	}
	
	$frontHtml = '';
	$frontHtml .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $settings['item-font-type'] . '">

	<div id="imagemapper' . $id . '-wrapper" class="imagemapper-wrapper" style="clear: both;">
		<div id="imapper' . $id . '-values" style="display: none">
			<div id="imapper' . $id . '-value-item-open-style">' . $settings['item-open-style'] . '</div>
			<div id="imapper' . $id . '-value-item-design-style">' . $settings['item-design-style'] . '</div>
		</div>
		<img id="imapper' . $id . '-map-image" style="max-width: 100%;" src="' . ($settings['map-image'] != '' ? '' . $settings['map-image'] : $this->url . 'images/no_image.jpg') . '" />';

	$font = str_replace('+', ' ', $settings['item-font-type']);
	$explode = explode('||',$imagemapper->items);
	foreach ($explode as $it) 
	{
		$ex2 = explode('::', $it);
		$key = substr($ex2[0],0,strpos($ex2[0],'-'));
		$subkey = substr($ex2[0],strpos($ex2[0],'-')+1);
		$itemsArray[$key][$subkey] = $ex2[1];
	}
	foreach ($itemsArray as $key => $arr) 
	{
		$num = substr($key,4);

		$itemContent = array_keys($arr); 
		$itemContentIndexes = preg_grep('#imapper-item-content-#', $itemContent); 
		$count = 2;

		$frontHtml .= '<div id="imapper' . $id . '-pin' . $num . '-wrapper" class="imapper' . $id . '-pin-wrapper imapper-pin-wrapper" style="position: absolute; left: ' . $arr['imapper-item-x'] . '; top: ' . $arr['imapper-item-y'] . ';" >
							<img id="imapper' . $id . '-pin' . $num . '" class="imapper' . $id . '-pin" src="' . $settings['item-icon'] . '">';
							
	if (strpos($settings['item-icon'], 'images/icons/5/') !== FALSE)
		$frontHtml .=		'<div id="imapper' . $id . '-pin' . $num . '-color" class="imapper-pin-color" style="background-color: ' . $arr['imapper-item-pin-color'] . ';"></div>
							 <i id="imapper' . $id . '-pin' . $num . '-icon" class="imapper-pin-icon fawesome icon-large ' . $arr['imapper-item-picture'] . '"></i>';
							
	if (strpos($settings['item-icon'], 'images/icons/3/') !== FALSE)
		$frontHtml .=		'<img id="imapper' . $id . '-pin' . $num . '" class="imapper-pin-shadow" src="' . substr($settings['item-icon'], 0, strpos($settings['item-icon'], '/icons/3/') + 9) . '1-1.png'  . '">';
		
		$frontHtml .=		'<div id="imapper' . $id . '-pin' . $num . '-content-wrapper" class="imapper' . $id . '-pin-content-wrapper imapper-content-wrapper" style="color: ' . $settings['item-font-color'] . ';">
								<div id="imapper' . $id . '-pin' . $num . '-content" class="imapper-content" style="background-color: ' . $settings['item-back-color'] . '; border-color: ' . $settings['item-border-color'] . '; border-radius: ' . $settings['item-border-radius'] . 'px; width: ' . $settings['item-width'] . 'px; height: ' . $settings['item-height'] . 'px; font-family: &quot;' . $font . '&quot;;"><p class="imapper-content-header" style="font-size: ' . $settings['item-header-font-size'] . 'px !important;">' . $arr['imapper-item-title'] . '</p><div class="imapper-content-text" style="font-size: ' . $settings['item-font-size'] . 'px;">' . $arr['imapper-item-content'] . '</div></div>';
		
				
	if (array_key_exists('imapper-item-content-2', $arr))
	{
		$frontHtml .= 			'<div id="imapper' . $id . '-pin' . $num . '-content-tab" class="imapper-content-tab" style="background-color: ' . $settings['item-back-color'] . ';"><a href="#" style="color: ' . $settings['item-border-color'] . ';">1</a></div>';
		
		foreach($itemContentIndexes as $i)
		{
			$frontHtml .= 		'<div id="imapper' . $id . '-pin' . $num . '-content-' . $count . '" class="imapper-content imapper-content-additional" style="background-color: ' . $settings['item-back-color'] . '; border-color: ' . $settings['item-border-color'] . '; border-radius: ' . $settings['item-border-radius'] . 'px; width: ' . $settings['item-width'] . 'px; height: ' . $settings['item-height'] . 'px; font-family: &quot;' . $font . '&quot;; font-size: ' . $settings['item-font-size'] . 'px;"><div class="imapper-content-header" style="font-size: ' . $settings['item-header-font-size'] . 'px !important;">' . $arr['imapper-item-title'] . '</div><div class="imapper-content-text">' . $arr[$i] . '</div></div>';
			$frontHtml .=		'<div id="imapper' . $id . '-pin' . $num . '-content-' . $count . '-tab" class="imapper-content-tab" style="background-color: ' . $settings['item-back-color'] . ';"><a href="#" style="color: ' . $settings['item-border-color'] . ';">' . $count . '</a></div>';
			$count++;
		}
		
	}
		
		$frontHtml .=			'<div class="imapper-arrow" style="border-color: ' . $settings['item-back-color'] . ' transparent transparent transparent;"></div>';
		if (strpos($settings['item-icon'], 'images/icons/2/') === FALSE)
			$frontHtml .=		'<div class="arrow-' . $arr['imapper-item-open-position'] . '-border imapper-arrow-border"></div>';
		else
			$frontHtml .=		'<div class="triangle-' . $arr['imapper-item-open-position'] . '-border imapper-triangle-border"></div>';
		
		$frontHtml .=			'</div>
							<div id="imapper' . $id . '-value-item' . $num . '-open-position" class="imapper' . $id . '-value-item-open-position" style="display:none;">' . $arr['imapper-item-open-position'] . '</div>';
							
		if ($count > 2)
			$tabStyle = 'display: block; width: 16px; height: 16px; border-radius: 8px; border: 1px solid #191970; background-color: #4f92d3; color: white; font-size: 10px; line-height: 1.4; text-align: center; position: absolute; top: -70px; left: 2px; z-index: 10;';
		else
			$tabStyle = 'display: none;';
							
		$frontHtml .=		'<div id="imapper' . $id . '-value-item' . $num . '-tab-number" class="imapper-value-tab-number" style="' . $tabStyle . '">' . ($count - 1) . '</div>
							 <div id="imapper' . $id . '-value-item' . $num . '-border-color" class="imapper-value-border-color" style="display: none;">' . $settings['item-border-color'] . '</div>
					   </div>';
	}

$frontHtml .= '</div>';