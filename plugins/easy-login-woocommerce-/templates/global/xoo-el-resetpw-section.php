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
	'xoo-el-rp-pass' => array(
		'input_type' 		=> 'password',
		'icon' 				=> 'fas fa-key',
		'placeholder' 		=> __( 'New Password', 'easy-login-woocommerce' ),
		'cont_class' 		=> array( 'xoo-aff-group' ),
		'required' 			=> 'yes',
	),

	'xoo-el-rp-pass-again' => array(
		'input_type' 	=> 'password',
		'icon' 			=> 'fas fa-key',
		'placeholder' 	=> __( 'Confirm Password', 'easy-login-woocommerce' ),
		'cont_class' 	=> array( 'xoo-aff-group' ),
		'required' 		=> 'yes'
	),
);

$regPasswordSettings = xoo_el()->aff->fields->get_field_data( 'xoo_el_reg_pass' )['settings'];

if( isset( $regPasswordSettings['strength_meter'] ) && $regPasswordSettings['strength_meter'] === "yes" ){
	$fields['xoo-el-rp-pass']['custom_attributes'] = array(
		'check_strength' 	=> 'yes',
		'strength_pass' 	=> $regPasswordSettings['strength_meter_pass']
	);
}

$fields['xoo-el-rp-pass']['minlength'] = $regPasswordSettings['minlength'];
$fields['xoo-el-rp-pass']['maxlength'] = $regPasswordSettings['maxlength'];

$fields = apply_filters( 'xoo_el_resetpw_fields', $fields, $args );

$resetpw_args = $args['forms']['resetpw']; 

?>



<?php do_action('xoo_el_resetpassword_form_start'); ?>

<?php if( !isset( $resetpw_args['user'] ) || is_wp_error( $resetpw_args['user'] ) ): ?>

	<span class="xoo-el-form-txt"><?php _e( 'This key is invalid or has already been used. Please reset your password again if needed.', 'easy-login-woocommerce' ); ?></span>

<?php else: ?>

	<span class="xoo-el-form-txt"><?php _e( 'Please enter a new password', 'easy-login-woocommerce' ); ?></span>

	<?php

	foreach ( $fields as $field_id => $field_args ) {
		xoo_el()->aff->fields->get_input_html( $field_id, $field_args );
	}

	?>

	<input type="hidden" name="rp_login" value="<?php echo esc_attr( $resetpw_args['rp_login'] ) ?>">

	<input type="hidden" name="rp_key" value="<?php echo esc_attr( $resetpw_args['rp_key'] ) ?>">

	<input type="hidden" name="_xoo_el_form" value="resetPassword">

	<input type="hidden" name="xoo-el-resetpw-nonce-field" value="<?php echo wp_create_nonce( 'xoo-el-resetpw-nonce' ); ?>">

	<?php do_action( 'xoo_el_resetpw_add_fields', $args ); ?>

	<button type="submit" class="button btn xoo-el-action-btn xoo-el-resetpw-btn"><?php _e( 'Change Password', 'easy-login-woocommerce' ); ?></button>

<?php endif; ?>
