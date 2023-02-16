<?php
/**
 * Lost Password Form
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/global/xoo-el-lostpw-section.php
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/easy-login-woocommerce/
 * @version 2.1
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$fields = array(
	'user_login' => array(
		'input_type' 	=> 'text',
		'icon' 			=> 'fas fa-key',
		'placeholder' 	=> __( 'Username / Email', 'easy-login-woocommerce' ),
		'cont_class' 	=> array( 'xoo-aff-group' ),
		'required' 		=> 'yes'
	),
);

$fields = apply_filters( 'xoo_el_lostpw_fields', $fields, $args );

?>


<span class="xoo-el-form-txt"><?php _e('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.','easy-login-woocommerce'); ?></span>

<?php

foreach ( $fields as $field_id => $field_args ) {
	xoo_el()->aff->fields->get_input_html( $field_id, $field_args );
}

?>

<?php do_action( 'xoo_el_lostpw_add_fields', $args ); ?>

<input type="hidden" name="_xoo_el_form" value="lostPassword">

<?php wp_referer_field(); ?>

<button type="submit" class="button btn xoo-el-action-btn xoo-el-lostpw-btn"><?php _e('Email Reset Link','easy-login-woocommerce'); ?></button>