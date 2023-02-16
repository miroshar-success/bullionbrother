<?php

$settings = array(

	array(
		'callback' 		=> 'textarea',
		'title' 		=> 'Custom CSS',
		'id' 			=> 'm-custom-css',
		'section_id' 	=> 'av_main',
		'default' 		=> '',
		'args' 			=> array(
			'rows' => 20,
			'cols' => 70
		)
	),

);


return apply_filters( 'xoo_el_admin_settings', $settings, 'advanced' );

?>