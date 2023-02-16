<?php

$settings = array(

	/* Main Style */
	array(
		'callback' 		=> 'select',
		'title' 		=> 'Popup Position',
		'id' 			=> 'sy-popup-pos',
		'section_id' 	=> 'sy_popup',
		'args'			=> array(
			'options' => array(
				'top' 		=> 'Top',
				'middle' 	=> 'Middle'
			)
		),
		'default' 		=> 'middle'
	),


	array(
		'callback' 		=> 'number',
		'title' 		=> 'Popup Width',
		'id' 			=> 'sy-popup-width',
		'section_id' 	=> 'sy_popup',
		'default' 		=> 800,
		'desc' 			=> 'size in px'
	),

	array(
		'callback' 		=> 'number',
		'title' 		=> 'Popup Height',
		'id' 			=> 'sy-popup-height',
		'section_id' 	=> 'sy_popup',
		'default' 		=> 600,
		'desc' 			=> 'size in px'
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Popup Padding',
		'id' 			=> 'sy-popup-padding',
		'section_id' 	=> 'sy_popup',
		'default' 		=> '40px 30px',
		'desc' 			=> 'Default: 45px 30px'
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Background Color',
		'id' 			=> 'sy-popup-bgcolor',
		'section_id' 	=> 'sy_popup',
		'default' 		=> '#ffffff',
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Text Color',
		'id' 			=> 'sy-popup-txtcolor',
		'section_id' 	=> 'sy_popup',
		'default' 		=> '#000000',
	),

	array(
		'callback' 		=> 'upload',
		'title' 		=> 'Sidebar Image',
		'id' 			=> 'sy-sidebar-img',
		'section_id' 	=> 'sy_popup',
		'default' 		=> XOO_EL_URL.'/assets/images/popup-sidebar.jpg',
	),


	array(
		'callback' 		=> 'select',
		'title' 		=> 'Sidebar Position',
		'id' 			=> 'sy-sidebar-pos',
		'section_id' 	=> 'sy_popup',
		'args'			=> array(
			'options' => array(
				'left' 		=> 'Left',
				'right' 	=> 'Right'
			)
		),
		'default' 		=> 'left'
	),


	array(
		'callback' 		=> 'number',
		'title' 		=> 'Sidebar width',
		'id' 			=> 'sy-sidebar-width',
		'section_id' 	=> 'sy_popup',
		'default' 		=> 40,
		'desc' 			=> 'Width in percentage'
	),


	/* Form Style */
	array(
		'callback' 		=> 'color',
		'title' 		=> 'Tab Background Color',
		'id' 			=> 'sy-tab-bgcolor',
		'section_id' 	=> 'sy_form',
		'default' 		=> '#eeeeee',
	),

	array(
		'callback' 		=> 'color',
		'title' 		=> 'Active Tab Background Color',
		'id' 			=> 'sy-taba-bgcolor',
		'section_id' 	=> 'sy_form',
		'default' 		=> '#000000',
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Tab Text Color',
		'id' 			=> 'sy-tab-txtcolor',
		'section_id' 	=> 'sy_form',
		'default' 		=> '#000000',
	),

	array(
		'callback' 		=> 'color',
		'title' 		=> 'Active Tab Text Color',
		'id' 			=> 'sy-taba-txtcolor',
		'section_id' 	=> 'sy_form',
		'default' 		=> '#ffffff',
	),


	array(
		'callback' 		=> 'select',
		'title' 		=> 'Button Design',
		'id' 			=> 'sy-btns-theme',
		'section_id' 	=> 'sy_form',
		'args' 			=> array(
			'options' 	=> array(
				'theme'		=> 'Use theme button design & colors',
				'custom' 	=> 'Custom',
			),
		),
		'default' 	=> 'custom',
		'desc' 		=> 'Below color options will be ineffective if set to theme design.'
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Button Background Color',
		'id' 			=> 'sy-btn-bgcolor',
		'section_id' 	=> 'sy_form',
		'default' 		=> '#000000',
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Button Text Color',
		'id' 			=> 'sy-btn-txtcolor',
		'section_id' 	=> 'sy_form',
		'default' 		=> '#ffffff',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Button Border',
		'id' 			=> 'sy-btn-border',
		'section_id' 	=> 'sy_form',
		'default' 		=> '2px solid #000000',
		'desc' 			=> 'Default: 2px solid #000000'
	),


	array(
		'callback' 		=> 'number',
		'title' 		=> 'Button Height',
		'id' 			=> 'sy-btn-height',
		'section_id' 	=> 'sy_form',
		'default' 		=> '40',
		'desc' 			=> 'size in px'
	),


	/** Fields Style **/
	array(
		'callback' 		=> 'links',
		'title' 		=> 'Form Fields Style',
		'id' 			=> 'fake',
		'section_id' 	=> 'sy_fields',
		'args' 			=> array(
			'options' 	=> array(
				admin_url('admin.php?page=xoo-el-fields&tab=general') => 'Manage'
			)
		)
	),

);

return apply_filters( 'xoo_el_admin_settings', $settings, 'style' );