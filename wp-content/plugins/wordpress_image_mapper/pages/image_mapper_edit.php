<div class="wrap">
	<?php 
	
	$title = '';
	$settings = array
	(
		'item-back-color' => '#888888',
		'item-border-color' => '#444444',
		'item-font-size' => '12',
		'item-header-font-size' => '24',
		'item-font-type' => 'regular',
		'item-font-color' => '#4d4d4d',
		'item-width' => '240',
		'item-height' => '120',
		'item-border-radius' => '10',
		'item-open-style' => 'click',
		'dummy-imapper-item-pin-color' => '#0000ff'
	);
	
	global $wpdb;
	if(isset($_GET['id'])) 
	{
		global $wpdb;
		$imagemapper = $wpdb->get_results('SELECT * FROM ' . $wpdb->base_prefix . 'image_mapper WHERE id='.$_GET['id']);
		$imagemapper = $imagemapper[0];
		$pageName = 'Edit mapper';
	}
	else 
	{
		$pageName = 'New mapper';
	}
	
	if (isset($imagemapper))
	{
		$title = $imagemapper->name;
		foreach(explode('||',$imagemapper->settings) as $val) 
		{
			$expl = explode('::',$val);
			$settings[$expl[0]] = $expl[1];
		}
	}
	?>
	
	<input type="hidden" id="plugin-url" value="<?php echo $this->url; ?>" />
	<h2><?php echo $pageName; ?>
		<a href="<?php echo admin_url( "admin.php?page=imagemapper" ); ?>" class="add-new-h2">Cancel</a>
	</h2>
	<div class="form_result"></div>
	<form name="post_form"  method="post" id="post_form">
	<input type="hidden" name="image_mapper_id" id="image_mapper_id" value="<?php echo $_GET['id']; ?>" />
	
	<div id="poststuf">
	
		<div id="post-body" class="metabox-holder columns-2" style="margin-right:300px; padding:0;">
		
			<div id="post-body-content">
				
				<div id="titlediv">
					<div id="titlewrap">
						<label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title">Enter title here</label>
						<input type="text" name="image_mapper_title" size="30" tabindex="1" value="<?php echo $title; ?>" id="title" autocomplete="off" />
					</div>
				</div>
				
				<h2 class="alignleft" style="padding:0 0 10px 0;">Map</h2>
				<div class="clear"></div>
				
				<div class="map-wrapper">
					<a href="#" id="map-change" class="tsort-change">Change</a>
					<div class="mapper-sort-image">
						<img id="map-image" src="<?php echo (array_key_exists('map-image', $settings) && $settings['map-image'] != '') ? '' . $settings['map-image'] : $this->url . 'images/no_image.jpg'; ?>" />
						<input id="map-input" name="map-image" type="hidden" value="<?php echo $settings['map-image']; ?>" />
						<a href="#" id="map-image-remove" class="tsort-remove">Remove</a>
					</div>
				</div>
				
				<div class="clear"></div>
				<div class="items">
					<h2 class="alignleft" style="padding:0 0 10px 0;">Items</h2>
					<div class="clear"></div>
					<ul id="imapper-sortable-items" class="imapper-sortable">
					
					<?php 
					$itemsArray = array();
					if (isset($imagemapper) && $imagemapper->items != '') {
						$explode = explode('||',$imagemapper->items);
						foreach ($explode as $it) {
							$ex2 = explode('::', $it);
							$key = substr($ex2[0],0,strpos($ex2[0],'-'));
							$subkey = substr($ex2[0],strpos($ex2[0],'-')+1);
							$itemsArray[$key][$subkey] = $ex2[1];
						}
						foreach ($itemsArray as $key => $arr) {
							$num = substr($key,4);
					?>
						
						<li id="<?php echo $key; ?>" class="imapper-sortableItem">
							<div id="imapper-sort<?php echo $num; ?>-header" class="imapper-sort-header">Item <?php echo $num; ?> <small><i>- <span><?php echo $arr['imapper-item-title']; ?></span></i></small> &nbsp;</div><a href="#" class="imapper-delete">delete</a>
						</li>
						
						<?php 	
						}
					} ?>
					
					</ul>
				</div>
				
				<div id="style_preview"></div>
				
				<div class="imapper_items_options">
					<h2 class="alignleft" style="padding:0 0 10px 0;">Item Options</h2>
					<div class="clear"></div>
					<ul id="imapper-sortable-dummy" class="imapper-sortable" style="visibility:hidden;">
						<li>
							<label for="dummy-imapper-item-title">Item Title</label>
							<input style="margin-left: 75px; width: 230px;" id="dummy-imapper-item-title" name="dummy-imapper-item-title" value="" type="text" />
						</li>
						<div class="clear"></div>
						<li>
							<label for="dummy-imapper-item-open-position">Item Open Position</label>
							<select style="margin-left: 20px; width: 230px;" name="dummy-imapper-item-open-position" id="dummy-imapper-item-open-position">
								<option value="left">Left</option>
								<option value="right">Right</option>
								<option value="top">Top</option>
								<option value="bottom">Bottom</option>
							</select>
						</li>
						
						<?php if (array_key_exists('item-icon', $settings) && $settings['item-icon'] == $this->url . '/images/icons/5/1.png') { ?>
						<li id="dummy-li-item-pin-color">
							<label for="dummy-imapper-item-pin-color">Item Pin Color</label>
							<input id="dummy-imapper-item-pin-color" name="dummy-imapper-item-pin-color" class="color-picker-iris" value="<?php echo $settings['dummy-imapper-item-pin-color']; ?>" type="text" style="margin-left: 50px; background:#<?php echo $settings['dummy-imapper-item-pin-color']; ?>;">
							<div class="color-picker-iris-holder"></div>
						</li>
						<?php } ?>
						
						<div class="clear"></div>
						<li id="li-item-content">
							<label style="vertical-align: top;" for="dummy-imapper-item-content">Item Content</label>
							<textarea rows="6" style="margin-left: 55px; width: 230px; resize: none;" id="dummy-imapper-item-content" name="dummy-imapper-item-content" value="" type="text" ></textarea>
						</li>
						
					</ul>
					
					<?php 
					$itemsArray = array();
					if (isset($imagemapper) && $imagemapper->items != '') {
						$explode = explode('||',$imagemapper->items);
						foreach ($explode as $it) {
							$ex2 = explode('::', $it);
							$key = substr($ex2[0],0,strpos($ex2[0],'-'));
							$subkey = substr($ex2[0],strpos($ex2[0],'-')+1);
							$itemsArray[$key][$subkey] = $ex2[1];
						}
						foreach ($itemsArray as $key => $arr) {
							$num = substr($key,4);
					?>
						
						<ul id="imapper-sortable-<?php echo $num; ?>" class="imapper-sortable-real" style="display:none;">
						<li>
							<input type="hidden" id="<?php echo $key; ?>-imapper-item-x" name="<?php echo $key; ?>-imapper-item-x" value="<?php echo $arr['imapper-item-x']; ?>" />
							<input type="hidden" id="<?php echo $key; ?>-imapper-item-y" name="<?php echo $key; ?>-imapper-item-y" value="<?php echo $arr['imapper-item-y']; ?>" />
						</li>
						<li>
							<label style="margin-left:5px;" for="<?php echo $key; ?>-imapper-item-title">Item Title</label>
							<input style="margin-left:5px;" id="<?php echo $key; ?>-imapper-item-title" name="<?php echo $key; ?>-imapper-item-title" value="<?php echo $arr['imapper-item-title']; ?>" type="text" />
						</li>
						<div class="clear"></div>
						<li>
							<label style="margin-left:5px;" for="<?php echo $key; ?>-imapper-item-open-position">Item Open Position</label>
							<select name="<?php echo $key; ?>-imapper-item-open-position" id="<?php echo $key; ?>-imapper-item-open-position">
								<option value="left" <?php echo (($arr['imapper-item-open-position'] == 'left') ? 'selected="selected"' : '' ); ?>>Left</option>
								<option value="right" <?php echo (($arr['imapper-item-open-position'] == 'right') ? 'selected="selected"' : '' ); ?>>Right</option>
								<option value="top" <?php echo (($arr['imapper-item-open-position'] == 'top') ? 'selected="selected"' : '' ); ?>>Top</option>
								<option value="bottom" <?php echo (($arr['imapper-item-open-position'] == 'bottom') ? 'selected="selected"' : '' ); ?>>Bottom</option>
							</select>
						</li>
					<?php if ($settings['item-icon'] == $this->url . '/images/icons/5/1.png') { ?>
						<li>
							<input id="<?php echo $key; ?>-imapper-item-pin-color" name="<?php echo $key; ?>-imapper-item-pin-color" class="imapper-item-pin-color" value="<?php echo ($arr['imapper-item-pin-color'] != '') ? $arr['imapper-item-pin-color'] : '#0000ff'; ?>" type="text" style="">
						</li>
						<li>
							<input id="<?php echo $key; ?>-imapper-item-picture" name="<?php echo $key; ?>-imapper-item-picture" class="imapper-item-picture" value="<?php echo ($arr['imapper-item-picture'] != '') ? $arr['imapper-item-picture'] : 'icon-cloud-download'; ?>" type="text">
						</li>
					<?php } ?>
						<div class="clear"></div>
						<li>
							<label style="margin-left:5px;" for="<?php echo $key; ?>-imapper-item-content">Item Content</label>
							<textarea style="margin-left:5px;" id="<?php echo $key; ?>-imapper-item-content" name="<?php echo $key; ?>-imapper-item-content" value='<?php echo $arr['imapper-item-content']; ?>' type="text"><?php echo $arr['imapper-item-content']; ?></textarea>
							
							<?php 
							$itemContent = array_keys($arr); 
							$itemContentIndexes = preg_grep('#imapper-item-content-#', $itemContent); 
							$count = 2;
							
							foreach ($itemContentIndexes as $i) {?>
								<textarea class="textarea-additional" style="margin-left:5px;" id="<?php echo $key; ?>-imapper-item-content-<?php echo $count; ?>" name="<?php echo $key; ?>-imapper-item-content-<?php echo $count; ?>" value='<?php echo $arr[$i]; ?>' type="text"><?php echo $arr[$i]; ?></textarea>
							<?php $count++;
							} ?>
						</li>
					</ul>
						
						<?php 	
						}
					} ?>
					
				</div>
			
			</div>
			
			<?php
					function im_get_google_fonts($json = false) {
				        $current_date = getdate(date("U"));
				     
				        $current_date = $current_date['weekday'] . $current_date['month'] . $current_date['mday'] . $current_date['year'];
				     
				        if (!get_option('br0_admin_webfonts')) {
				            $file_get = wp_remote_fopen("http://www.shindiristudio.com/responder/fonts.txt");
				            if (strlen($file_get) > 100) {
				                add_option('br0_admin_webfonts', $file_get);
				                add_option('br0_admin_webfonts_date', $current_date);
				            }
				        }
				     
				        if (get_option('br0_admin_webfonts_date') != $current_date || get_option('br0_admin_webfonts_date') == '') {
				            $file_get = wp_remote_fopen("http://www.shindiristudio.com/responder/fonts.txt");
				            if (strlen($file_get) > 100) {
				                update_option('br0_admin_webfonts', wp_remote_fopen("http://www.shindiristudio.com/responder/fonts.txt"));
				                update_option('br0_admin_webfonts_date', $current_date);
				            }
				        }
				     
				     
				        $fontsjson = get_option('br0_admin_webfonts');
				        $decode = json_decode($fontsjson, true);
				        $webfonts = array();
				        $webfonts['default'] = 'Default' . '|' . 'def';
				        foreach ($decode['items'] as $key => $value) {
				            $item_family = $decode['items'][$key]['family'];
				            $item_family_trunc = str_replace(' ', '+', $item_family);
				            $webfonts[$item_family_trunc] = $item_family . '|' . $item_family_trunc;
				        }
				     
				        if ($json)
				            return $fontsjson;
				        return $webfonts;
					}
					
					$googleFonts = im_get_google_fonts();
			?>
			
			<div id="postbox-container-1" class="postbox-container">
				<div class="postbox">
					<h3 class='hndle' style="cursor:auto"><span>Publish</span></h3>
					<div class="inside">
						<div id="save-progress" class="waiting ajax-saved" style="background-image: url(<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>)" ></div>
						<input name="preview-timeline" id="preview-timeline" value="Preview" class="button button-highlighted" style="padding:3px 25px" type="submit" />
						<input name="save-timeline" id="save-timeline" value="Save mapper" class="alignright button button-primary" style="padding:3px 15px" type="submit" />
						<img id="save-loader" src="<?php echo $this->url; ?>images/ajax-loader.gif" class="alignright" />
						<br class="clear" />		
					</div>
				</div>
				
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					
					<div id="bla1" class="postbox" >
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class='hndle'><span>General Options</span></h3>
						<div class="inside">
							<table class="fields-group misc-pub-section">			
							
							<tr class="field-row">
								<td>
									<label for="item-back-color" >Item Back Color</label>
								</td>
								<td>
									<input id="item-back-color" name="item-back-color" class="color-picker-iris"  value="<?php echo $settings['item-back-color']; ?>" type="text" style="background:#<?php echo $settings['item-back-color']; ?>;">	
									<div class="color-picker-iris-holder" style="margin-left: -125px;"></div>
								</td>			
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-border-color" >Item Border Color</label>
								</td>
								<td>
									<input id="item-border-color" name="item-border-color" class="color-picker-iris" value="<?php echo $settings['item-border-color']; ?>" type="text" style="background:#<?php echo $settings['item-border-color']; ?>;">	
									<div class="color-picker-iris-holder" style="margin-left: -125px;"></div>
								</td>			
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-font-size" >Item Font Size</label>
								</td>
								<td>
									<input id="item-font-size" name="item-font-size" value="<?php echo $settings['item-font-size']; ?>" size="5" type="text">
									<span class="unit">px</span>
								</td>
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-header-font-size" >Item Title Font Size</label>
								</td>
								<td>
									<input id="item-header-font-size" name="item-header-font-size" value="<?php echo $settings['item-header-font-size']; ?>" size="5" type="text">
									<span class="unit">px</span>
								</td>
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-font-type" >Item Font Type</label>
								</td>
								<td>
									<select id="item-font-type" name="item-font-type">
										<?php
											foreach ($googleFonts as $font)
											{	
												$f = explode("|", $font);
												echo '<option style="max-width: 130px;" value="' . $f[1] . '" ' . (($settings['item-font-type'] == $f[1]) ? 'selected="selected"' : '') . '>' . $f[0] . '</option>';
											}
										?>
									</select>
								</td>					
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-font-color" >Item Font Color</label>
								</td>
								<td>
									<input id="item-font-color" name="item-font-color" class="color-picker-iris" value="<?php echo $settings['item-font-color']; ?>" type="text" style="background:#<?php echo $settings['item-font-color']; ?>;">	
									<div class="color-picker-iris-holder" style="margin-left: -125px;"></div>
								</td>			
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-width" >Item Width</label>
								</td>
								<td>
									<input id="item-width" name="item-width" value="<?php echo $settings['item-width']; ?>" size="5" type="text">
									<span class="unit">px</span>
								</td>
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-width" >Item Height</label>
								</td>
								<td>
									<input id="item-height" name="item-height" value="<?php echo $settings['item-height']; ?>" size="5" type="text">
									<span class="unit">px</span>
								</td>
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-border-radius" >Item Border Radius</label>
								</td>
								<td>
									<input id="item-border-radius" name="item-border-radius"  value="<?php echo $settings['item-border-radius']; ?>" type="text" size="5">								
									<span class="unit">px</span>
								</td>			
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-open-style" >Item Open Style</label>
								</td>
								<td>
									<select id="item-open-style" name="item-open-style">
										<option value="click" <?php echo (($settings['item-open-style'] == 'click') ? 'selected="selected"' : '' ); ?>>Click</option>
										<option value="hover" <?php echo (($settings['item-open-style'] == 'hover') ? 'selected="selected"' : '' ); ?>>Hover</option>
									</select>
								</td>
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-design-style" >Item Design Style</label>
								</td>
								<td>
									<select id="item-design-style" name="item-design-style">
										<option value="fluid" <?php echo (($settings['item-design-style'] == 'fluid') ? 'selected="selected"' : '' ); ?>>Fluid</option>
										<option value="responsive" <?php echo (($settings['item-design-style'] == 'responsive') ? 'selected="selected"' : '' ); ?>>Responsive</option>
									</select>
								</td>
							</tr>
							
							<tr class="field-row">
								<td>
									<label for="item-icon">Item Icon</label>
								</td>
								<td>
									<div>
										<a href="#" id="icon-change" class="tsort-change" >Change</a>
										<div id="icon-wrapper" style="position: relative; " >
											<img id="item-icon" style="clear:both; max-width:130px; max-height: 70px; border: 1px solid rgb(223, 223, 223);" src="<?php if (array_key_exists('item-icon', $settings) && strpos($settings['item-icon'], 'images/icons/5') !== FALSE) echo $this->url . 'images/icons/5/purple.png'; else if (!array_key_exists('item-icon', $settings)) echo $this->url . 'images/icons/4/1.png'; else if (array_key_exists('item-icon', $settings) && $settings['item-icon'] != '') echo $settings['item-icon']; ?>" />
											<input type="hidden" data-id="" style="margin-left: 5px; float: left;" id="item-icon-input" name="item-icon" value="<?php echo (array_key_exists('item-icon', $settings) && $settings['item-icon'] != '') ? $settings['item-icon'] : $this->url . 'images/icons/4/1.png'; ?>"/>
										</div>
									</div>
								</td>
							</tr>
							
							</table>
						</div>
					</div><!-- /GENERAL OPTIONS -->
					
				</div>
			</div>
		</div>
	</div>
	</form>
</div>