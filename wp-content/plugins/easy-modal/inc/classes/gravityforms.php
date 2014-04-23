<?php
class EM_GravityForms {
	public function __construct()
	{
		add_filter('em_preload_modals_single', array(&$this,'preload_modals'));
	}

	public function preload_modals($modal)
	{
		if(has_shortcode( $modal['content'], 'gravityform' ))
		{
			$regex = "/\[gravityform.*? id=[\"\']?([0-9]*)[\"\']?.*/";
			preg_match_all($regex, $modal['content'],$matches);
			foreach($matches[1] as $form_id)
			{
				add_filter("gform_confirmation_anchor_{$form_id}", create_function("","return false;"));
				gravity_form_enqueue_scripts($form_id, true);
			}
		}
		return $modal;
	}
}
$EM_GravityForms = new EM_GravityForms;