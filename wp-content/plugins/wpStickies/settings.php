<?php

	// Retrieve options
	$option = get_option('wpstickies-options');
	$options = empty($option) ? array() : $option;
	$options = is_array($options) ? $options : unserialize($options);


	$GLOBALS['wpstickies_options'] = $options;

	// Get WPDB Object
	global $wpdb;

	// Table nameempty
	$table_name = $wpdb->prefix . "wpstickies";

	// Get pending stickies
	$GLOBALS['pending_stickies'] = $wpdb->get_results( "SELECT * FROM $table_name
										WHERE flag_hidden = '1' AND flag_deleted = '0'
										ORDER BY date_c DESC LIMIT 100" );

	// Get latest stickies
	$GLOBALS['latest_stickies'] = $wpdb->get_results( "SELECT * FROM $table_name
									WHERE flag_hidden = '0' AND flag_deleted = '0'
									ORDER BY date_c DESC LIMIT 50" );

	// Get latest stickies
	$GLOBALS['removed_stickies'] = $wpdb->get_results( "SELECT * FROM $table_name
									WHERE flag_deleted = '1'
									ORDER BY date_c DESC LIMIT 50" );

	function convert_quotes($str) {
		return str_replace('"', '&quot;', $str);
	}

	//var_dump($options);

	// Auto-updates
	$code = get_option('wpstickies-purchase-code', '');
	$validity = get_option('wpstickies-validated', '0');

	// Defaults
	$relativeURLDefault = false;
	$allowSettingsChangeDefault = false;
	$createAutoAcceptDefault = false;
	$directionInDefault = 'bottom';
	$showMessagesDefault = true;
	$alwaysVisibleDefault = true;
	$autoChangeDirectionDefault = true;
?>
<div class="wrap">

	<!-- Icon and header -->
	<div class="wpstickies-icon"></div>
	<h2><?php _e('wpStickies', 'wpStickies') ?></h2>

	<!-- Main menu -->
	<div id="wps-main-nav-bar">
		<a href="#" class="settings active"><?php _e('Global Settings', 'wpStickies') ?></a>
		<a href="#" class="stickies"><?php _e('Stickies', 'wpStickies') ?></a>
		<a href="#" class="language"><?php _e('Language', 'wpStickies') ?></a>
		<a href="#" class="updates"><?php _e('Auto-Update', 'wpStickies') ?></a>
		<a href="#" class="support unselectable"><?php _e('Documentation', 'wpStickies') ?></a>
		<a href="#" class="clear unselectable"></a>
	</div>

	<!-- Pages -->
	<div id="wps-pages">

		<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" id="wps-form">
			<input type="hidden" name="posted" value="1">

			<!-- Global settings -->
			<div class="wps-page active">
				<div class="wps-box wps-settings">
					<h3 class="header"><?php _e('Global Settings', 'wpStickies') ?></h3>
					<table>

						<!-- Global settings: General -->
						<thead>
							<tr>
								<td colspan="3">
									<span id="wps-icon-settings-general"></span>
									<h4><?php _e('General', 'wpStickies') ?></h4>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php _e('Selectors', 'wpStickies') ?></td>
								<td colspan="2" class="desc">
									<input type="text" name="selector" class="selector input" value="<?php echo !empty($options['selector']) ? htmlspecialchars(stripslashes($options['selector'])) : 'img[class*=&quot;wp-image&quot;], .wpstickies' ?>">
									<?php _e('You can apply wpStickies on any content, even on DIVs with background with the right selector. Please read the documentation for more information about selectors.', 'wpStickies') ?>
								</td>
							</tr>
							<tr>
								<td><?php _e('Use relative URLs', 'wpStickies') ?></td>
								<td><input type="checkbox" name="relative_urls"<?php echo ((empty($option) && $relativeURLDefault == true) || isset($options['relative_urls'])) ? ' checked="checked"' : '' ?>></td>
								<td class="desc"><?php _e('wpStickies will ignore the domain and only use the relative path for identifying images. This helps you if you want to move your site to another server. Please note that this option can cause problems for images linked from 3rd party server/domain.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Custom capability', 'wpStickies') ?></td>
								<td><input type="text" name="capability" class="input" value="<?php echo !empty($options['capability']) ? $options['capability'] : 'manage_options' ?>"></td>
								<td class="desc"><?php _e('You can allow non-administrator users to acccess wpStickies settings page.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Allow plugin settings changes', 'wpStickies') ?></td>
								<td><input type="checkbox" name="allow_settings_change"<?php echo ((empty($option) && $allowSettingsChangeDefault == true) || isset($options['allow_settings_change'])) ? ' checked="checked"' : '' ?>></td>
								<td class="desc"><?php _e('Allow non-administrator users to change plugin settings.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Minimum image width', 'wpStickies') ?></td>
								<td><input type="text" name="image_min_width" class="input" value="<?php echo !empty($options['image_min_width']) ? $options['image_min_width'] : '150' ?>"></td>
								<td class="desc"><?php _e("wpStickies won't be applied on images which has less size what you specify here in pixels. With this option you can prevent wpStickies applying on small and unwanted images.", "wpStickies") ?></td>
							</tr>
							<tr>
								<td><?php _e('Minimum imaeg height', 'wpStickies') ?></td>
								<td><input type="text" name="image_min_height" class="input" value="<?php echo !empty($options['image_min_height']) ? $options['image_min_height'] : '100' ?>"></td>
								<td class="desc"><?php _e("wpStickies won't be applied on images which has less size what you specify here in pixels. With this option you can prevent wpStickies applying on small and unwanted images.", "wpStickies") ?></td>
							</tr>
						</tbody>

						<!-- Global settings: Permissions -->
						<thead>
							<tr>
								<td colspan="3">
									<span id="wps-icon-settings-permissions"></span>
									<h4><?php _e('Permissions', 'wpStickies') ?></h4>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php _e('Users who can create new stickies', 'wpStickies') ?></td>
								<td>
									<select name="create_roles">
										<option value="administrator"<?php echo ($options['create_roles'] == 'administrator') ? ' selected="selected"' : '' ?>><?php _e('Only administrators', 'wpStickies') ?></option>
										<option value="wpstickiesadmins"<?php echo ($options['create_roles'] == 'wpstickiesadmins') ? ' selected="selected"' : '' ?>><?php _e('wpStickies admins', 'wpStickies') ?></option>
										<option value="subscribers"<?php echo ($options['create_roles'] == 'subscribers') ? ' selected="selected"' : '' ?>><?php _e('Subscribers', 'wpStickies') ?></option>
										<option value="everyone"<?php echo ($options['create_roles'] == 'everyone') ? ' selected="selected"' : '' ?>><?php _e('Everyone', 'wpStickies') ?></option>
										<option value="custom"<?php echo ($options['create_roles'] == 'custom') ? ' selected="selected"' : '' ?>><?php _e('Custom', 'wpStickies') ?></option>
									</select>
								</td>
								<td class="desc"><?php _e('You can control here who can create new sticikes. The "wpStickies admins" group created by the plugin with subscriber permissions.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Auto-accept pending stickies', 'wpStickies') ?></td>
								<td><input type="checkbox" name="create_auto_accept"<?php echo ((empty($option) && $createAutoAcceptDefault == true) || isset($options['create_auto_accept'])) ? ' checked="checked"' : '' ?>></td>
								<td class="desc"><?php _e('With this setting you can bypass the pending list and all the stickies will be accepted automatically.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Custom roles for sticky creation', 'wpStickies') ?></td>
								<td colspan="2" class="desc">
									<input type="text" name="create_custom_roles" class="custom_roles input" value="<?php echo !empty($options['create_custom_roles']) ? $options['create_custom_roles'] : '' ?>">
									<?php _e('If you selected "Custom" for users who can create new stickies, here you can specify your custom roles.', 'wpStickies') ?>
								</td>
							</tr>
							<tr>
								<td><?php _e('Pending stickes displayed as', 'wpStickies') ?></td>
								<td>
									<select name="display_pending_stickies">
										<option value="visible"<?php echo ($options['display_pending_stickies'] == 'visible') ? ' selected="selected"' : '' ?>><?php _e('Visible', 'wpStickies') ?></option>
										<option value="invisible"<?php echo (!isset($options['display_pending_stickies']) || $options['display_pending_stickies'] == 'invisible') ? ' selected="selected"' : '' ?>><?php _e('Invisible', 'wpStickies') ?></option>
									</select>
								</td>
								<td class="desc"><?php _e("By default, your pending stickies won't show up on your front-end site until an administrator approve them. Here you can set the visiblity of pending stickies. You can make visible pending stickies while they are still on your pending list and you can always remove them if necessary.", "wpStickies") ?></td>
							</tr>

							<tr>
								<td><?php _e('Users who can modify their own stickies', 'wpStickies') ?></td>
								<td>
									<select name="modify_roles">
										<option value="administrator"<?php echo ($options['modify_roles'] == 'administrator') ? ' selected="selected"' : '' ?>><?php _e('Only administrators', 'wpStickies') ?></option>
										<option value="wpstickiesadmins"<?php echo ($options['modify_roles'] == 'wpstickiesadmins') ? ' selected="selected"' : '' ?>><?php _e('wpStickies admins', 'wpStickies') ?></option>
										<option value="subscribers"<?php echo ($options['modify_roles'] == 'subscribers') ? ' selected="selected"' : '' ?>><?php _e('Subscribers', 'wpStickies') ?></option>
										<option value="everyone"<?php echo ($options['modify_roles'] == 'everyone') ? ' selected="selected"' : '' ?>><?php _e('Everyone', 'wpStickies') ?></option>
										<option value="custom"<?php echo ($options['modify_roles'] == 'custom') ? ' selected="selected"' : '' ?>><?php _e('Custom', 'wpStickies') ?></option>
									</select>
								</td>
								<td class="desc"><?php _e('You can control here who can modify stickies. Non-administrator users can only edit their own stickies.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Custom roles for sticky modification', 'wpStickies') ?></td>
								<td colspan="2" class="desc">
									<input type="text" name="modify_custom_roles" class="custom_roles input" value="<?php echo !empty($options['modify_custom_roles']) ? $options['modify_custom_roles'] : '' ?>">
									<?php _e('If you selected "Custom" for the previous option, here you can specify your custom roles for users who can modify their own stickies.', 'wpStickies') ?>
								</td>
							</tr>
							<tr>
								<td><?php _e('After a modification', 'wpStickies') ?></td>
								<td>
									<select name="requirereconfirmation">
										<option value="yes"<?php echo ($options['requirereconfirmation'] == 'yes') ? ' selected="selected"' : '' ?>><?php _e('Add sticky to the pending list for re-confirmation', 'wpStickies') ?></option>
										<option value="no"<?php echo ($options['requirereconfirmation'] == 'no') ? ' selected="selected"' : '' ?>><?php _e('Treat as confirmed sticky if it was previously accepted', 'wpStickies') ?></option>
										<option value="auto_accept"<?php echo ($options['requirereconfirmation'] == 'auto_accept') ? ' selected="selected"' : '' ?>><?php _e('Always ignore pendig list, accept them automatically', 'wpStickies') ?></option>
									</select>
								</td>
								<td class="desc"><?php _e('You can set here what action you want after a modification. You can force modified stickies to be pending stickies, so you can review the changes and be sure that no one inserted an unwanted content. You can also set that previously approved stickies remains approved after a modification. Or if only trusted members can create / modify stickies, you can ignore pending list and every modified sticky will be accepted and shown on your front-end site automatically.', 'wpStickies') ?></td>
							</tr>
						</tbody>

						<!-- Global settings: Appearance -->
						<thead>
							<tr>
								<td colspan="3">
									<span id="wps-icon-settings-appearance"></span>
									<h4><?php _e('Appearance', 'wpStickies') ?></h4>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="3">
									<table class="removestyle">
										<tbody>
											<tr>
												<th></th>
												<th><?php _e('Direction', 'wpStickies') ?></th>
												<th><?php _e('Easing', 'wpStickies') ?></th>
												<th><?php _e('Duration', 'wpStickies') ?></th>
											</tr>
											<tr>
												<td><?php _e('Mouse enter animation', 'wpStickies') ?></td>
												<td>
													<?php $options['directionin'] = empty($options['directionin']) ? 'bottom' : $options['directionin']; ?>
													<select name="directionin">
						    						    <option value="fade"<?php echo ($options['directionin'] == 'fade' ) ? ' selected="selected"' : '' ?>><?php _e('fade', 'wpStickies') ?></option>
						    						    <option value="top"<?php echo ($options['directionin'] == 'top') ? ' selected="selected"' : '' ?>><?php _e('top', 'wpStickies') ?></option>
						    						    <option value="bottom" <?php echo ($options['directionin'] == 'bottom') ? ' selected="selected"' : '' ?>><?php _e('bottom', 'wpStickies') ?></option>
						    						    <option value="left"<?php echo ($options['directionin'] == 'left') ? ' selected="selected"' : '' ?>><?php _e('left', 'wpStickies') ?></option>
						    						    <option value="right"<?php echo ($options['directionin'] == 'right') ? ' selected="selected"' : '' ?>><?php _e('right', 'wpStickies') ?></option>
						    						</select>
						    					</td>
						    					<td>
						    						<?php $options['easingin'] = empty($options['easingin']) ? 'easeOutQuart' : $options['easingin']; ?>
						    						<select name="easingin">
						    						    <option<?php echo ($options['easingin'] == 'linear' ) ? ' selected="selected"' : '' ?>>linear</option>
						    						    <option<?php echo ($options['easingin'] == 'swing' ) ? ' selected="selected"' : '' ?>>swing</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInQuad' ) ? ' selected="selected"' : '' ?>>easeInQuad</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutQuad' ) ? ' selected="selected"' : '' ?>>easeOutQuad</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutQuad' ) ? ' selected="selected"' : '' ?>>easeInOutQuad</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInCubic' ) ? ' selected="selected"' : '' ?>>easeInCubic</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutCubic' ) ? ' selected="selected"' : '' ?>>easeOutCubic</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutCubic' ) ? ' selected="selected"' : '' ?>>easeInOutCubic</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInQuart' ) ? ' selected="selected"' : '' ?>>easeInQuart</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutQuart' ) ? ' selected="selected"' : '' ?>>easeOutQuart</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutQuart' ) ? ' selected="selected"' : '' ?>>easeInOutQuart</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInQuint' ) ? ' selected="selected"' : '' ?>>easeInQuint</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutQuint' ) ? ' selected="selected"' : '' ?>>easeOutQuint</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutQuint' ) ? ' selected="selected"' : '' ?>>easeInOutQuint</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInSine' ) ? ' selected="selected"' : '' ?>>easeInSine</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutSine' ) ? ' selected="selected"' : '' ?>>easeOutSine</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutSine' ) ? ' selected="selected"' : '' ?>>easeInOutSine</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInExpo' ) ? ' selected="selected"' : '' ?>>easeInExpo</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutExpo' ) ? ' selected="selected"' : '' ?>>easeOutExpo</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutExpo' ) ? ' selected="selected"' : '' ?>>easeInOutExpo</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInCirc' ) ? ' selected="selected"' : '' ?>>easeInCirc</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutCirc' ) ? ' selected="selected"' : '' ?>>easeOutCirc</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutCirc' ) ? ' selected="selected"' : '' ?>>easeInOutCirc</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInElastic' ) ? ' selected="selected"' : '' ?>>easeInElastic</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutElastic' ) ? ' selected="selected"' : '' ?>>easeOutElastic</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutElastic' ) ? ' selected="selected"' : '' ?>>easeInOutElastic</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInBack' ) ? ' selected="selected"' : '' ?>>easeInBack</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutBack' ) ? ' selected="selected"' : '' ?>>easeOutBack</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutBack' ) ? ' selected="selected"' : '' ?>>easeInOutBack</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInBounce' ) ? ' selected="selected"' : '' ?>>easeInBounce</option>
						    						    <option<?php echo ($options['easingin'] == 'easeOutBounce' ) ? ' selected="selected"' : '' ?>>easeOutBounce</option>
						    						    <option<?php echo ($options['easingin'] == 'easeInOutBounce' ) ? ' selected="selected"' : '' ?>>easeInOutBounce</option>
						    						</select>
						    					</td>
						    					<td><input type="text" name="durationin" value="<?php echo !empty($options['durationin']) ? $options['durationin'] : '500' ?>"> ms</td>
						    				</tr>
						    				<tr>
						    					<td><?php _e('Mouse out animation', 'wpStickies') ?></td>
						    					<td>
						    						<?php $options['directionout'] = empty($options['directionout']) ? 'fade' : $options['directionout']; ?>
						    						<select name="directionout">
						    						    <option value="fade"<?php echo ($options['directionout'] == 'fade' ) ? ' selected="selected"' : '' ?>><?php _e('fade', 'wpStickies') ?></option>
						    						    <option value="top"<?php echo ($options['directionout'] == 'top') ? ' selected="selected"' : '' ?>><?php _e('top', 'wpStickies') ?></option>
						    						    <option value="bottom" <?php echo ($options['directionout'] == 'bottom') ? ' selected="selected"' : '' ?>><?php _e('bottom', 'wpStickies') ?></option>
						    						    <option value="left"<?php echo ($options['directionout'] == 'left') ? ' selected="selected"' : '' ?>><?php _e('left', 'wpStickies') ?></option>
						    						    <option value="right"<?php echo ($options['directionout'] == 'right') ? ' selected="selected"' : '' ?>><?php _e('right', 'wpStickies') ?></option>
						    						</select>
						    					</td>
						    					<td>
						    						<?php $options['easingout'] = empty($options['easingout']) ? 'easeInBack' : $options['easingout']; ?>
						    						<select name="easingout">
						    						    <option<?php echo ($options['easingout'] == 'linear' ) ? ' selected="selected"' : '' ?>>linear</option>
						    						    <option<?php echo ($options['easingout'] == 'swing' ) ? ' selected="selected"' : '' ?>>swing</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInQuad' ) ? ' selected="selected"' : '' ?>>easeInQuad</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutQuad' ) ? ' selected="selected"' : '' ?>>easeOutQuad</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutQuad' ) ? ' selected="selected"' : '' ?>>easeInOutQuad</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInCubic' ) ? ' selected="selected"' : '' ?>>easeInCubic</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutCubic' ) ? ' selected="selected"' : '' ?>>easeOutCubic</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutCubic' ) ? ' selected="selected"' : '' ?>>easeInOutCubic</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInQuart' ) ? ' selected="selected"' : '' ?>>easeInQuart</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutQuart' ) ? ' selected="selected"' : '' ?>>easeOutQuart</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutQuart' ) ? ' selected="selected"' : '' ?>>easeInOutQuart</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInQuint' ) ? ' selected="selected"' : '' ?>>easeInQuint</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutQuint' ) ? ' selected="selected"' : '' ?>>easeOutQuint</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutQuint' ) ? ' selected="selected"' : '' ?>>easeInOutQuint</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInSine' ) ? ' selected="selected"' : '' ?>>easeInSine</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutSine' ) ? ' selected="selected"' : '' ?>>easeOutSine</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutSine' ) ? ' selected="selected"' : '' ?>>easeInOutSine</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInExpo' ) ? ' selected="selected"' : '' ?>>easeInExpo</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutExpo' ) ? ' selected="selected"' : '' ?>>easeOutExpo</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutExpo' ) ? ' selected="selected"' : '' ?>>easeInOutExpo</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInCirc' ) ? ' selected="selected"' : '' ?>>easeInCirc</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutCirc' ) ? ' selected="selected"' : '' ?>>easeOutCirc</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutCirc' ) ? ' selected="selected"' : '' ?>>easeInOutCirc</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInElastic' ) ? ' selected="selected"' : '' ?>>easeInElastic</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutElastic' ) ? ' selected="selected"' : '' ?>>easeOutElastic</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutElastic' ) ? ' selected="selected"' : '' ?>>easeInOutElastic</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInBack' ) ? ' selected="selected"' : '' ?>>easeInBack</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutBack' ) ? ' selected="selected"' : '' ?>>easeOutBack</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutBack' ) ? ' selected="selected"' : '' ?>>easeInOutBack</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInBounce' ) ? ' selected="selected"' : '' ?>>easeInBounce</option>
						    						    <option<?php echo ($options['easingout'] == 'easeOutBounce' ) ? ' selected="selected"' : '' ?>>easeOutBounce</option>
						    						    <option<?php echo ($options['easingout'] == 'easeInOutBounce' ) ? ' selected="selected"' : '' ?>>easeInOutBounce</option>
						    						</select>
						    					</td>
						    					<td><input type="text" name="durationout" value="<?php echo !empty($options['durationout']) ? $options['durationout'] : '250' ?>"> ms</td>
						    				</tr>
						    				<tr>
						    					<td><?php _e('Bubble animation', 'wpStickies') ?></td>
						    					<td></td>
						    					<td>
						    						<?php $options['spot_bubble_easing'] = empty($options['spot_bubble_easing']) ? 'easeOutBack' : $options['spot_bubble_easing']; ?>
						    						<select name="spot_bubble_easing">
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'linear' ) ? ' selected="selected"' : '' ?>>linear</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'swing' ) ? ' selected="selected"' : '' ?>>swing</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInQuad' ) ? ' selected="selected"' : '' ?>>easeInQuad</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutQuad' ) ? ' selected="selected"' : '' ?>>easeOutQuad</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutQuad' ) ? ' selected="selected"' : '' ?>>easeInOutQuad</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInCubic' ) ? ' selected="selected"' : '' ?>>easeInCubic</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutCubic' ) ? ' selected="selected"' : '' ?>>easeOutCubic</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutCubic' ) ? ' selected="selected"' : '' ?>>easeInOutCubic</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInQuart' ) ? ' selected="selected"' : '' ?>>easeInQuart</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutQuart' ) ? ' selected="selected"' : '' ?>>easeOutQuart</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutQuart' ) ? ' selected="selected"' : '' ?>>easeInOutQuart</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInQuint' ) ? ' selected="selected"' : '' ?>>easeInQuint</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutQuint' ) ? ' selected="selected"' : '' ?>>easeOutQuint</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutQuint' ) ? ' selected="selected"' : '' ?>>easeInOutQuint</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInSine' ) ? ' selected="selected"' : '' ?>>easeInSine</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutSine' ) ? ' selected="selected"' : '' ?>>easeOutSine</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutSine' ) ? ' selected="selected"' : '' ?>>easeInOutSine</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInExpo' ) ? ' selected="selected"' : '' ?>>easeInExpo</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutExpo' ) ? ' selected="selected"' : '' ?>>easeOutExpo</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutExpo' ) ? ' selected="selected"' : '' ?>>easeInOutExpo</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInCirc' ) ? ' selected="selected"' : '' ?>>easeInCirc</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutCirc' ) ? ' selected="selected"' : '' ?>>easeOutCirc</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutCirc' ) ? ' selected="selected"' : '' ?>>easeInOutCirc</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInElastic' ) ? ' selected="selected"' : '' ?>>easeInElastic</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutElastic' ) ? ' selected="selected"' : '' ?>>easeOutElastic</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutElastic' ) ? ' selected="selected"' : '' ?>>easeInOutElastic</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInBack' ) ? ' selected="selected"' : '' ?>>easeInBack</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutBack' ) ? ' selected="selected"' : '' ?>>easeOutBack</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutBack' ) ? ' selected="selected"' : '' ?>>easeInOutBack</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInBounce' ) ? ' selected="selected"' : '' ?>>easeInBounce</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeOutBounce' ) ? ' selected="selected"' : '' ?>>easeOutBounce</option>
						    						    <option<?php echo ($options['spot_bubble_easing'] == 'easeInOutBounce' ) ? ' selected="selected"' : '' ?>>easeInOutBounce</option>
						    						</select>
						    					</td>
						    					<td><input type="text" name="spot_bubble_duration" value="<?php echo !empty($options['spot_bubble_duration']) ? $options['spot_bubble_duration'] : '200' ?>"> ms</td>
						    				</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td><?php _e('Delay', 'wpStickies') ?></td>
								<td><input type="text" name="delay" class="wpstickies_delay" value="<?php echo !empty($options['wpstickies_delay']) ? $options['wpstickies_delay'] : '30' ?>"></td>
								<td class="desc"><?php _e('(ms) You stickies not animating at the same time, there is a little delay between each of them. You can set the delay time here in millisecs. You can set 0 to disable this behaviour.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Show messages', 'wpStickies') ?></td>
								<td><input type="checkbox" name="show_messages"<?php echo ((empty($option) && $showMessagesDefault == true) || isset($options['show_messages'])) ? ' checked="checked"' : '' ?>></td>
								<td class="desc"><?php _e('There is a built-in message system which will show explanatory messages to help new wpStickies users.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Always visible', 'wpStickies') ?></td>
								<td><input type="checkbox" name="always_visible"<?php echo ((empty($option) && $alwaysVisibleDefault == true) || isset($options['always_visible'])) ? ' checked="checked"' : '' ?>></td>
								<td class="desc"><?php _e('Disabling this option will result that your stickies only will be visible when your users moves their mouse cursor over an image.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Spot bubble direction', 'wpStickies') ?></td>
								<td>
									<select name="spot_bubble_direction">
										<option value="top"<?php echo ($options['spot_bubble_direction'] == 'top' ) ? ' selected="selected"' : '' ?>><?php _e('Top', 'wpStickies') ?></option>
										<option value="right"<?php echo ($options['spot_bubble_direction'] == 'right' ) ? ' selected="selected"' : '' ?>><?php _e('Right', 'wpStickies') ?></option>
										<option value="bottom"<?php echo ($options['spot_bubble_direction'] == 'bottom' ) ? ' selected="selected"' : '' ?>><?php _e('Bottom', 'wpStickies') ?></option>
										<option value="left"<?php echo ($options['spot_bubble_direction'] == 'left' ) ? ' selected="selected"' : '' ?>><?php _e('Left', 'wpStickies') ?></option>
									</select>
								</td>
								<td class="desc"><?php _e('You can set here the spot bubble default position. Possible values: top, right, bottom, left.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Change bubble direction automatically', 'wpStickies') ?></td>
								<td><input type="checkbox" name="auto_change_direction"<?php echo ((empty($option) && $autoChangeDirectionDefault == true) || isset($options['auto_change_direction'])) ? ' checked="checked"' : '' ?>></td>
								<td class="desc"><?php _e('wpStickies is smart and can detect when there is no enough free space for a bubble. In this case, wpStickies will change the position of the bubble unless you want to force its position.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Spot bubble distance', 'wpStickies') ?></td>
								<td><input type="text" name="spot_bubble_distance" value="<?php echo !empty($options['spot_bubble_distance']) ? $options['spot_bubble_distance'] : '2' ?>"></td>
								<td class="desc"><?php _e('The distance (in pixels) of the spot bubble from the spot.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Area minimum width', 'wpStickies') ?></td>
								<td><input type="text" name="area_min_width" value="<?php echo !empty($options['area_min_width']) ? $options['area_min_width'] : '25' ?>"></td>
								<td class="desc"><?php _e('The minimum width of an area in pixels. You can create an area just by drawing a rectangle. You can set here what has to be the minimum size of an area. If the users draw a really small area, it will be converted a spot automatically.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Area minimum height', 'wpStickies') ?></td>
								<td><input type="text" name="area_min_height" value="<?php echo !empty($options['area_min_height']) ? $options['area_min_height'] : '25' ?>"></td>
								<td class="desc"><?php _e('The minimum height of an area in pixels. You can create an area just by drawing a retangle. You can set here what has to be the minimum size of an area. If the users draw a really small area, it will be converted a spot automatically.', 'wpStickies') ?></td>
							</tr>
							<tr>
								<td><?php _e('Spot buttons position', 'wpStickies') ?></td>
								<td>
									<?php $options['spot_bubble_spot_buttons_positiondirection'] = empty($options['spot_bubble_spot_buttons_positiondirection']) ? 'left' : $options['spot_bubble_spot_buttons_positiondirection']; ?>
									<select name="spot_bubble_spot_buttons_positiondirection">
							    		<option value="top"<?php echo ($options['spot_bubble_spot_buttons_positiondirection'] == 'top' ) ? ' selected="selected"' : '' ?>><?php _e('top', 'wpStickies') ?></option>
							    		<option value="right"<?php echo ($options['spot_bubble_spot_buttons_positiondirection'] == 'right' ) ? ' selected="selected"' : '' ?>><?php _e('right', 'wpStickies') ?></option>
							    		<option value="bottom"<?php echo ($options['spot_bubble_spot_buttons_positiondirection'] == 'bottom' ) ? ' selected="selected"' : '' ?>><?php _e('bottom', 'wpStickies') ?></option>
							    		<option value="left"<?php echo ($options['spot_bubble_spot_buttons_positiondirection'] == 'left' ) ? ' selected="selected"' : '' ?>><?php _e('left', 'wpStickies') ?></option>
							    	</select>
							    </td>
							    <td class="desc"><?php _e('The spot buttons alignment. Possible values: left, center, right.', 'wpStickies') ?></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="wps-box wps-publish">
					<h3 class="header"><?php _e('Publish', 'wpStickies') ?></h3>
					<div class="inner">
						<button class="button-primary"><?php _e('Save changes', 'wpStickies') ?></button>
					</div>
				</div>
			</div>

			<!-- Stickies -->
			<div class="wps-page">

				<div id="wps-stickies-tabs">
					<a href="#" class="active"><?php _e('Pending', 'wpStickies') ?></a>
					<a href="#"><?php _e('Latest', 'wpStickies') ?></a>
					<a href="#"><?php _e('Restore', 'wpStickies') ?></a>
					<div class="clear"></div>
				</div>
				<div id="wps-stickies">

					<!-- Stickies: Pending -->
					<div class="wps-box wps-stickies-box active">
						<!--<h2 class="header noborder"><?php _e('Pending stickies', 'wpStickies') ?></h2>-->
						<table class="wps-stickies-table wpstickies-pending-table">
							<thead class="noborder">
							    <tr>
							    	<th class="image"><?php _e('Preview', 'wpStickies') ?></th>
							    	<th class="title"><?php _e('Title / Caption', 'wpStickies') ?></th>
							    	<th class="content"><?php _e('Content', 'wpStickies') ?></th>
							    	<th class="user"><?php _e('User', 'wpStickies') ?></th>
							    	<th class="created"><?php _e('Created', 'wpStickies') ?></th>
							    	<th class="actions" colspan="2"><?php _e('Actions', 'wpStickies') ?></th>
							    </tr>
							</thead>
							<tbody>
							 	<?php foreach($GLOBALS['pending_stickies'] as $item) : ?>
							 	<?php $data = json_decode(stripslashes($item->data), true); ?>
							    <tr>
							    	<td class="image">
							    		<a href="../index.php?page=wpstickies_preview&image=<?php echo base64_encode($item->image)?>">
							    			<img src="<?php echo $item->image ?>">
							    		</a>
							    	</td>
							    	<td class="title">
							    		<?php if($data['sticky']['type'] == 'area') { ?>
							    		<?php echo stripslashes($data['area']['caption']) ?>
							    		<?php } else { ?>
							    		<?php echo stripslashes($data['spot']['title']) ?>
							    		<?php } ?>
							    	</td>
							    	<td class="content">
							    		<?php if($data['sticky']['type'] == 'area') { ?>
							    		<?php _e('No content', 'wpStickies') ?>
							    		<?php } else { ?>
							    		<?php echo htmlspecialchars(stripslashes($data['spot']['content'])) ?>
							    		<?php } ?>
							    	</td>
							    	<td class="user">
							    		<?php if(empty($item->user_id)) { ?>
							    		<?php _e('Unregistered', 'wpStickies') ?>
							    		<?php } else { ?>
							    		<?php echo $item->user_name?>
							    		<?php } ?>
							    	</td>
							    	<td class="created">
							    		<?php echo date(get_option('date_format'), $item->date_c) ?><br>
							    		<?php echo date(get_option('time_format'), $item->date_c) ?>
							    	</td>
							    	<td class="action wpstickies-actions">
							    		<a href="#" class="accept" rel="wpstickies_accept,<?php echo $item->id?>" title="Accept"></a>
							    		<a href="#" class="reject" rel="wpstickies_reject,<?php echo $item->id?>" title="Reject"></a>
							    	</td>
							    </tr>
								<?php endforeach; ?>

								<?php if(empty($GLOBALS['pending_stickies'])) : ?>
								<tr class="empty">
									<td colspan="6"><?php _e('There are no pending stickies at the moment.', 'wpStickies') ?></td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- Stickies: Latest -->
					<div class="wps-box wps-stickies-box">
						<!--<h2 class="header noborder"><?php _e('Latest stickies', 'wpStickies') ?></h2>-->
						<table class="wps-stickies-table wpstickies-latest-table">
							<thead class="noborder">
							    <tr>
							    	<th class="image"><?php _e('Preview', 'wpStickies') ?></th>
							    	<th class="title"><?php _e('Title / Caption', 'wpStickies') ?></th>
							    	<th class="content"><?php _e('Content', 'wpStickies') ?></th>
							    	<th class="user"><?php _e('User', 'wpStickies') ?></th>
							    	<th class="modified"><?php _e('Modified', 'wpStickies') ?></th>
							    	<th class="actions"><?php _e('Actions', 'wpStickies') ?></th>
							    </tr>
							</thead>
							<tbody>
							    <?php foreach($GLOBALS['latest_stickies'] as $item) : ?>
							    <?php $data = json_decode(stripslashes($item->data), true); ?>
							    <tr>
							    	<td class="image">
							    		<a href="../index.php?page=wpstickies_preview&image=<?php echo base64_encode($item->image)?>">
							    			<img src="<?php echo $item->image ?>">
							    		</a>
							    	</td>
							    	<td class="title">
								    	<?php if($data['sticky']['type'] == 'area') { ?>
							    		<?php echo stripslashes($data['area']['caption']) ?>
							    		<?php } else { ?>
							    		<?php echo stripslashes($data['spot']['title']) ?>
							    		<?php } ?>
							    	</td>
							    	<td class="content">
								    	<?php if($data['sticky']['type'] == 'area') { ?>
							    		<?php _e('No content', 'wpStickies') ?>
							    		<?php } else { ?>
							    		<?php echo htmlspecialchars(stripslashes($data['spot']['content'])) ?>
							    		<?php } ?>
							    	</td>
							    	<td class="user"><?php echo $item->user_name ?></td>
							    	<td class="modified">
								    	<?php echo date(get_option('date_format'), $item->date_c) ?><br>
							    		<?php echo date(get_option('time_format'), $item->date_c) ?>
							    	</td>
							    	<td class="action wpstickies-actions"><a href="#" class="remove" rel="wpstickies_remove,<?php echo $item->id?>" title="Remove"></a></td>
							    </tr>
							    <?php endforeach; ?>

							    <?php if(empty($GLOBALS['latest_stickies'])) : ?>
							    <tr class="empty">
							    	<td colspan="6"><?php _e('No stickes yet.', 'wpStickies') ?></td>
							    </tr>
							    <?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- Stickies: Restore -->
					<div class="wps-box wps-stickies-box">
						<!--<h2 class="header noborder"><?php _e('Restore removed stickies', 'wpStickies') ?></h2>-->
						<table class="wps-stickies-table wpstickies-restore-table">
							<thead class="noborder">
							    <tr>
							    	<th class="image"><?php _e('Preview', 'wpStickies') ?></th>
							    	<th class="title"><?php _e('Title / Caption', 'wpStickies') ?></th>
							    	<th class="content"><?php _e('Content', 'wpStickies') ?></th>
							    	<th class="user"><?php _e('User', 'wpStickies') ?></th>
							    	<th class="modified"><?php _e('Modified', 'wpStickies') ?></th>
							    	<th class="actions"><?php _e('Actions', 'wpStickies') ?></th>
							    </tr>
							</thead>
							<tbody>
							    <?php foreach($GLOBALS['removed_stickies'] as $item) : ?>
							    <?php $data = json_decode(stripslashes($item->data), true); ?>
							    <tr>
							    	<td class="image">
							    		<a href="../index.php?page=wpstickies_preview&image=<?php echo base64_encode($item->image)?>">
							    			<img src="<?php echo $item->image ?>">
							    		</a>
							    	</td>
							    	<td class="title">
								    	<?php if($data['sticky']['type'] == 'area') { ?>
							    		<?php echo stripslashes($data['area']['caption']) ?>
							    		<?php } else { ?>
							    		<?php echo stripslashes($data['spot']['title']) ?>
							    		<?php } ?>
							    	</td>
							    	<td class="content">
								    	<?php if($data['sticky']['type'] == 'area') { ?>
							    		<?php _e('No content', 'wpStickies') ?>
							    		<?php } else { ?>
							    		<?php echo htmlspecialchars(stripslashes($data['spot']['content'])) ?>
							    		<?php } ?>
							    	</td>
							    	<td class="user"><?php echo $item->user_name ?></td>
							    	<td class="modified">
								    	<?php echo date(get_option('date_format'), $item->date_c) ?><br>
							    		<?php echo date(get_option('time_format'), $item->date_c) ?>
							    	</td>
							    	<td class="action wpstickies-actions">
							    		<a href="#" class="restore" rel="wpstickies_restore,<?php echo $item->id?>" title="Restore"></a>
							    		<a href="#" class="delete" rel="wpstickies_delete,<?php echo $item->id?>" title="Delete permanently"></a>
							    	</td>
							    </tr>
							    <?php endforeach; ?>

							    <?php if(empty($GLOBALS['removed_stickies'])) : ?>
							    <tr class="empty">
							    	<td colspan="6"><?php _e('There are no removed stickies yet.', 'wpStickies') ?></td>
							    </tr>
							    <?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- Language -->
			<div class="wps-page">
				<div class="wps-box wps-settings wps-language">
					<h3 class="header"><?php _e('Language', 'wpStickies') ?></h3>
					<table>
						<tbody>
							<tr>
								<td><?php _e('Default caption text', 'wpStickies') ?></td>
								<td><input type="text" name="lang_area_caption" value="<?php echo !empty($options['lang_area_caption']) ? htmlspecialchars(stripslashes($options['lang_area_caption'])) : __('add a name or caption', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Default spot title', 'wpStickies') ?></td>
								<td><input type="text" name="lang_spot_title" value="<?php echo !empty($options['lang_spot_title']) ? htmlspecialchars(stripslashes($options['lang_spot_title'])) : __('Sample Title', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Default spot content', 'wpStickies') ?></td>
								<td><input type="text" name="land_spot_content" value="<?php echo !empty($options['land_spot_content']) ? htmlspecialchars(stripslashes($options['land_spot_content'])) : __('You can write here text and you can also use HTML code. For example you can simply include an image or a link.', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Google button', 'wpStickies') ?></td>
								<td><input type="text" name="lang_btn_google" value="<?php echo !empty($options['lang_btn_google']) ? htmlspecialchars(stripslashes($options['lang_btn_google'])) : __('Google', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Youtube button', 'wpStickies') ?></td>
								<td><input type="text" name="lang_btn_youtube" value="<?php echo !empty($options['lang_btn_youtube']) ? htmlspecialchars(stripslashes($options['lang_btn_youtube'])) : __('YouTube', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Vimeo button', 'wpStickies') ?></td>
								<td><input type="text" name="lang_btn_vimeo" value="<?php echo !empty($options['lang_btn_vimeo']) ? htmlspecialchars(stripslashes($options['lang_btn_vimeo'])) : __('Vimeo', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Wikipedia button', 'wpStickies') ?></td>
								<td><input type="text" name="lang_btn_wikipedia" value="<?php echo !empty($options['lang_btn_wikipedia']) ? htmlspecialchars(stripslashes($options['lang_btn_wikipedia'])) : __('Wikipedia', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Facebook button', 'wpStickies') ?></td>
								<td><input type="text" name="lang_btn_facebook" value="<?php echo !empty($options['lang_btn_facebook']) ? htmlspecialchars(stripslashes($options['lang_btn_facebook'])) : __('Facebook', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Hover message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_over" value="<?php echo !empty($options['lang_msg_over']) ? htmlspecialchars(stripslashes($options['lang_msg_over'])) : __('wpStickies: Click on the image to create a new spot or draw an area to tag faces.', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Spot drag message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_drag_spot" value="<?php echo !empty($options['lang_msg_drag_spot']) ? htmlspecialchars(stripslashes($options['lang_msg_drag_spot'])) : __('wpStickies: You can drag this sticky anywhere over the image by taking and moving the spot.', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Area drag message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_drag_area" value="<?php echo !empty($options['lang_msg_drag_area']) ? htmlspecialchars(stripslashes($options['lang_msg_drag_area'])) : __('wpStickies: You can drag this sticky anywhere over the image by taking and moving the area.', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Save button message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_btn_save" value="<?php echo !empty($options['lang_msg_btn_save']) ? htmlspecialchars(stripslashes($options['lang_msg_btn_save'])) : __('wpStickies: SAVE CHANGES', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Remove button message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_btn_remove" value="<?php echo !empty($options['lang_msg_btn_remove']) ? htmlspecialchars(stripslashes($options['lang_msg_btn_remove'])) : __('wpStickies: REMOVE THIS STICKY', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Reposition button message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_btn_reposition" value="<?php echo !empty($options['lang_msg_btn_reposition']) ? htmlspecialchars(stripslashes($options['lang_msg_btn_reposition'])) : __('wpStickies: CHANGE THE DIRECTION OF THE BUBBLE', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Color button message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_btn_color" value="<?php echo !empty($options['lang_msg_btn_color']) ? htmlspecialchars(stripslashes($options['lang_msg_btn_color'])) : __('wpStickies: CHANGE THE COLOR OF THE BUBBLE', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Size button message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_btn_size" value="<?php echo !empty($options['lang_msg_btn_size']) ? htmlspecialchars(stripslashes($options['lang_msg_btn_size'])) : __('wpStickies: CHANGE THE WIDTH OF THE BUBBLE', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Save message','wpStickies') ?></td>
								<td><input type="text" name="lang_msg_save" value="<?php echo !empty($options['lang_msg_save']) ? htmlspecialchars(stripslashes($options['lang_msg_save'])) : __('wpStickies: STICKY SAVED', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Remove message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_remove" value="<?php echo !empty($options['lang_msg_remove']) ? htmlspecialchars(stripslashes($options['lang_msg_remove'])) : __('wpStickies: STICKY REMOVED', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Disable message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_msg_disabled" value="<?php echo !empty($options['lang_msg_disabled']) ? htmlspecialchars(stripslashes($options['lang_msg_disabled'])) : __('Disable wpStickies on this image', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Remove confirmation message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_conf_remove" value="<?php echo !empty($options['lang_conf_remove']) ? htmlspecialchars(stripslashes($options['lang_conf_remove'])) :  __('wpStickies: You clicked to remove this sticky. If you confirm, it will be permanently removed from the database. Are you sure?', 'wpStickies') ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Remove error message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_err_remove" value="<?php echo !empty($options['lang_err_remove']) ? htmlspecialchars(stripslashes($options['lang_err_remove'])) : __("wpStickies: The following error occurred during remove: You don't have permission to remove this sticky!", "wpStickies") ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Create error message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_err_create" value="<?php echo !empty($options['lang_err_create']) ? htmlspecialchars(stripslashes($options['lang_err_create'])) : __("wpStickies: The following error occurred during save: You don't have permission to create new stickies!", "wpStickies") ?>"></td>
							</tr>
							<tr>
								<td><?php _e('Modify error message', 'wpStickies') ?></td>
								<td><input type="text" name="lang_err_modify" value="<?php echo !empty($options['lang_err_modify']) ? htmlspecialchars(stripslashes($options['lang_err_modify'])) : __("wpStickies: The following error occurred during save: You don't have permission to modify this sticky!", "wpStickies") ?>"></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="wps-box wps-publish">
					<h3 class="header"><?php _e('Publish', 'wpStickies') ?></h3>
					<div class="inner">
						<button class="button-primary"><?php _e('Save changes', 'wpStickies') ?></button>
					</div>
				</div>
			</div>
		</form>

		<!-- Updates -->
		<div class="wps-page">
			<?php if($GLOBALS['wpsAutoUpdateBox'] == true) : ?>
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="wps-box wps-settings wps-auto-update">
				<input type="hidden" name="action" value="wpstickies_verify_purchase_code">
				<h3 class="header"><?php _e('Auto-updates', 'wpStickies') ?></h3>
				<table>
					<tbody>
						<tr>
							<td><?php _e('Purchase code', 'wpStickies') ?></td>
							<td class="desc">
								<input type="texT" name="purchase_code" value="<?php echo $code ?>" placeholder="bc8e2b24-3f8c-4b21-8b4b-90d57a38e3c7"><br>
								<?php _e('To receive auto-updates, you need to enter your item purchase code. You can find it on your CodeCanyon downloads page, just click on the "Licence Certificate" button of the corresponding item. This will download a text file which contains your purchase code.', 'wpStickies') ?>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">
								<button class="button"><?php _e('Save changes', 'wpStickies') ?></button>
								<span style="<?php echo ($validity == '0' && $code != '') ? 'color: #c33219;' : 'color: #4b982f'?>">
									<?php
										if($validity == '1') {
											_e('Thank you for purchasing wpStickies. You successfully validated your purchase code for auto-updates.', 'wpStickies');
										} else if($code != '') {
											_e("Your purchase code doesn't appear to be valid. Please make sure that you entered your purchase code correctly.", "wpStickies");
										}
									?>
								</span>
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
			<?php endif; ?>
		</div>
	</div>
</form>