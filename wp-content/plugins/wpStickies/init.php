<?php

	function convert_quotes($str) {
		return str_replace('\'', '&lsquo;', $str);
	}

	// Get users data
	global $current_user;
	get_currentuserinfo();

	// Gather user data
	$user_id = $current_user->ID;

	// Get user role
	$role = wpstickies_get_user_role($user_id);

	// Check create premission
	$allowToCreate = wpstickies_allow_creatation($user_id);
	$allowToCreate = empty($allowToCreate[0]) ? 'false' : 'true';

	// Defaults
	$allowSettingsChangeDefault = false;
	$createAutoAcceptDefault = false;
	$directionInDefault = 'bottom';
	$showMessagesDefault = true;
	$alwaysVisibleDefault = true;
	$autoChangeDirectionDefault = true;

	// Selector
	$options['selector'] = empty($options['selector']) ? 'img[class*="wp-image"], .wpstickies' : stripslashes($options['selector']);

	// Options : settings
	$options['show_messages'] = ((empty($option) && $showMessagesDefault == true) || isset($options['show_messages'])) ? 'true' : 'false';
	$options['always_visible'] = ((empty($option) && $alwaysVisibleDefault == true) || isset($options['always_visible'])) ? 'true' : 'false';
	$options['image_min_width'] = empty($options['image_min_width']) ? '150' : $options['image_min_width'];
	$options['image_min_height'] = empty($options['image_min_height']) ? '100' : $options['image_min_height'];

	// Options : position
	$options['spot_bubble_direction'] = empty($options['spot_bubble_direction']) ? 'top' : $options['spot_bubble_direction'];
	$options['auto_change_direction'] = ((empty($option) && $autoChangeDirectionDefault == true) || isset($options['auto_change_direction'])) ? 'true' : 'false';
	$options['spot_bubble_distance'] = empty($options['spot_bubble_distance']) ? '2' : $options['spot_bubble_distance'];
	$options['area_min_width'] = empty($options['area_min_width']) ? '25' : $options['area_min_width'];
	$options['area_min_height'] = empty($options['area_min_height']) ? '25' : $options['area_min_height'];
	$options['spot_buttons_position'] = empty($options['spot_buttons_position']) ? 'left' : $options['spot_buttons_position'];

	// Options : animataion
	$options['directionin'] = empty($options['directionin']) ? 'bottom' : $options['directionin'];
	$options['directionout'] = empty($options['directionout']) ? 'fade' : $options['directionout'];
	$options['easingin'] = empty($options['easingin']) ? 'easeOutQuart' : $options['easingin'];
	$options['easingout'] = empty($options['easingout']) ? 'easeInBack' : $options['easingout'];
	$options['durationin'] = empty($options['durationin']) ? '500' : $options['durationin'];
	$options['durationout'] = empty($options['durationout']) ? '250' : $options['durationout'];
	$options['spot_bubble_easing'] = empty($options['spot_bubble_easing']) ? 'easeOutBack' : $options['spot_bubble_easing'];
	$options['spot_bubble_duration'] = empty($options['spot_bubble_duration']) ? '200' : $options['spot_bubble_duration'];
	$options['delay'] = empty($options['delay']) ? '30' : $options['delay'];

	// Options : language
	$options['lang_area_caption'] = empty($options['lang_area_caption']) ? 'add a name or caption' : convert_quotes(stripslashes($options['lang_area_caption']));
	$options['lang_spot_title'] = empty($options['lang_spot_title']) ? 'Sample Title' : convert_quotes(stripslashes($options['lang_spot_title']));
	$options['land_spot_content'] = empty($options['land_spot_content']) ? 'You can write here text and you can also use HTML code. For example you can simply include an image or a link.' : convert_quotes(stripslashes($options['land_spot_content']));
	$options['lang_btn_google'] = empty($options['lang_btn_google']) ? 'Google' : convert_quotes(stripslashes($options['lang_btn_google']));
	$options['lang_btn_youtube'] = empty($options['lang_btn_youtube']) ? 'YouTube' : convert_quotes(stripslashes($options['lang_btn_youtube']));
	$options['lang_btn_vimeo'] = empty($options['lang_btn_vimeo']) ? 'Vimeo' : convert_quotes(stripslashes($options['lang_btn_vimeo']));
	$options['lang_btn_wikipedia'] = empty($options['lang_btn_wikipedia']) ? 'Wikipedia' : convert_quotes(stripslashes($options['lang_btn_wikipedia']));
	$options['lang_btn_facebook'] = empty($options['lang_btn_facebook']) ? 'Facebook' : convert_quotes(stripslashes($options['lang_btn_facebook']));
	$options['lang_msg_over'] = empty($options['lang_msg_over']) ? 'wpStickies: Click on the image to create a new spot or draw an area to tag faces.' : convert_quotes(stripslashes($options['lang_msg_over']));
	$options['lang_msg_drag_spot'] = empty($options['lang_msg_drag_spot']) ? 'wpStickies: You can drag this sticky anywhere over the image by taking and moving the spot.' : convert_quotes(stripslashes($options['lang_msg_drag_spot']));
	$options['lang_msg_drag_area'] = empty($options['lang_msg_drag_area']) ? 'wpStickies: You can drag this sticky anywhere over the image by taking and moving the area.' : convert_quotes(stripslashes($options['lang_msg_drag_area']));
	$options['lang_msg_btn_save'] = empty($options['lang_msg_btn_save']) ? 'wpStickies: SAVE CHANGES' : convert_quotes(stripslashes($options['lang_msg_btn_save']));
	$options['lang_msg_btn_remove'] = empty($options['lang_msg_btn_remove']) ? 'wpStickies: REMOVE THIS STICKY' : convert_quotes(stripslashes($options['lang_msg_btn_remove']));
	$options['lang_msg_btn_reposition'] = empty($options['lang_msg_btn_reposition']) ? 'wpStickies: CHANGE THE DIRECTION OF THE BUBBLE' : convert_quotes(stripslashes($options['lang_msg_btn_reposition']));
	$options['lang_msg_btn_color'] = empty($options['lang_msg_btn_color']) ? 'wpStickies: CHANGE THE DIRECTION OF THE BUBBLE' : convert_quotes(stripslashes($options['lang_msg_btn_color']));
	$options['lang_msg_btn_size'] = empty($options['lang_msg_btn_size']) ? 'wpStickies: CHANGE THE DIRECTION OF THE BUBBLE' : convert_quotes(stripslashes($options['lang_msg_btn_size']));
	$options['lang_msg_save'] = empty($options['lang_msg_save']) ? 'wpStickies: STICKY SAVED' : convert_quotes(stripslashes($options['lang_msg_save']));
	$options['lang_msg_remove'] = empty($options['lang_msg_remove']) ? 'wpStickies: STICKY REMOVED' : convert_quotes(stripslashes($options['lang_msg_remove']));
	$options['lang_conf_remove'] = empty($options['lang_conf_remove']) ? 'wpStickies: You clicked to remove this sticky. If you confirm, it will be permanently removed from the database. Are you sure?' : convert_quotes(stripslashes($options['lang_conf_remove']));
	$options['lang_msg_disabled'] = empty($options['lang_msg_disabled']) ? 'Disable wpStickies on this image	' : convert_quotes(stripslashes($options['lang_msg_disabled']));

$data = '';
$data .= "<script type=\"text/javascript\">
	jQuery(document).ready(function() {
		jQuery('{$options['selector']}').wpStickies({
			settings : {
				role : '$role',
			    allowToCreate : $allowToCreate,
			    showMessages : {$options['show_messages']},
			    allowToModify : true,
			    alwaysVisible : {$options['always_visible']},
			    imageMinWidth : {$options['image_min_width']},
			    imageMinHeight : {$options['image_min_height']}
			},
			position : {
			    spotBubbleDirection : '{$options['spot_bubble_direction']}',
			    autoChangeDirection : {$options['auto_change_direction']},
			    spotBubbleDistance : {$options['spot_bubble_distance']},
			    areaMinWidth : {$options['area_min_width']},
			    areaMinHeight : {$options['area_min_height']},
			    spotButtonsPosition : '{$options['spot_buttons_position']}'
			},
			animation : {
			    directionIn : '{$options['directionin']}',
			    directionOut : '{$options['directionout']}',
			    easingIn : '{$options['easingin']}',
			    easingOut : '{$options['easingout']}',
			    durationIn : {$options['durationin']},
			    durationOut : {$options['durationout']},
			    delay : {$options['delay']},
			    spotBubbleDuration : {$options['spot_bubble_duration']},
			    spotBubbleEasing : '{$options['spot_bubble_easing']}'
			},
			language : {
			    areaCaption : '{$options['lang_area_caption']}',
			    spotTitle : '{$options['lang_spot_title']}',
			    spotContent : '{$options['land_spot_content']}',
			    btnGoogle : '{$options['lang_btn_google']}',
			    btnYouTube : '{$options['lang_btn_youtube']}',
			    btnVimeo : '{$options['lang_btn_vimeo']}',
			    btnWikipedia : '{$options['lang_btn_wikipedia']}',
			    btnFacebook : '{$options['lang_btn_facebook']}',
			    msgOver : '{$options['lang_msg_over']}',
			    msgDragSpot : '{$options['lang_msg_drag_spot']}',
			    msgDragArea : '{$options['lang_msg_drag_area']}',
			    msgBtnSave : '{$options['lang_msg_btn_save']}',
			    msgBtnRemove : '{$options['lang_msg_btn_remove']}',
			    msgBtnReposition : '{$options['lang_msg_btn_reposition']}',
			    msgBtnColor : '{$options['lang_msg_btn_color']}',
			    msgBtnSize : '{$options['lang_msg_btn_size']}',
			    msgSave : '{$options['lang_msg_save']}',
			    msgRemove : '{$options['lang_msg_remove']}',
			    confRemove : '{$options['lang_conf_remove']}',
			    msgDisabled : '{$options['lang_msg_disabled']}'
			}

		});
	});
</script>";