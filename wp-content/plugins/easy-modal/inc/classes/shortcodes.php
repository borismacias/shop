<?php
class EM_Shortcodes
{
	public function __construct()
	{
		add_shortcode( 'modal', array(&$this, 'modal'));	
	}
	public function modal($atts, $content = NULL)
	{
		extract( shortcode_atts( array(
			'id' => "",
			'title' => "",
			'theme' => 1,
			'size' => "normal",
			'width' => "",
			'widthUnit' => "px",
			'height' => "",
			'heightUnit' => "px",
			'animation' => "fade",
			'duration' => 350,
			'direction' => "down",
			'overlayClose' => false,
			'overlayEscClose' => false,
			'closeDisabled' => false,
		), $atts ) );
		$output = "<div ".
				"id='eModal-" . esc_attr($id) . "' ".
				"class='modal " . esc_attr($size != 'custom' ? "{$size} " : ""). "theme-{$theme}' ".
				"data-Theme='{$theme}' ".
				"data-size='{$size}' ".
				($size == 'custom' ? 
					"style='width:{$width}{$widthUnit};height:{$height}{$heightUnit};margin-left:-".($width/2)."{$widthUnit}' ".
					"data-userHeight='{$height}{$heightUnit}' ".
					"data-userWidth='{$width}{$widthUnit}' "
				: "").
				"data-Animation='{$animation}' ".
				"data-direction='{$direction}' ".
				"data-duration='{$duration}' ".
				($overlayClose ? "data-overlaylose='true' " : "") .
				($overlayEscClose ? "data-escclose='true' " : "") .
				($closeDisabled ? "data-closedisabled='true' " : "") .
			">";
			if($title != '')
			{
				$output .= "<div class='title'>" . esc_html($title) . "</div>";
			}
			$output .= apply_filters('em_modal_content', $content);
			if(!$closeDisabled)
			{
				$output .= "<a class='close-modal'>&#215;</a>";
			}			
		$output .= "</div>";
		return $output;
	}
}
$EM_Shortcodes = new EM_Shortcodes;