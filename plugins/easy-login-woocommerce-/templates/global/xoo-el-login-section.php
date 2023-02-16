<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/global/xoo-el-login-section.php
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

$redirect 	= xoo_el_helper()->get_general_option( 'm-red-login' );
$redirect 	= !empty( $redirect ) ? $redirect : sanitize_text_field( $_SERVER['REQUEST_URI'] );

$fields = array(
	'xoo-el-username' => array(
		'input_type' 		=> 'text',
		'icon' 				=> 'far fa-user',
		'placeholder' 		=> __( 'Username / Email', 'easy-login-woocommerce' ),
		'cont_class' 		=> array( 'xoo-aff-group' ),
		'required' 			=> 'yes',
		'autocomplete' => 'email'
	),

	'xoo-el-password' => array(
		'input_type' 	=> 'password',
		'icon' 			=> 'fas fa-key',
		'placeholder' 	=> __( 'Password', 'easy-login-woocommerce' ),
		'cont_class' 	=> array( 'xoo-aff-group' ),
		'required' 		=> 'yes'
	),
);

$fields = apply_filters( 'xoo_el_login_fields', $fields, $args );

foreach ( $fields as $field_id => $field_args ) {
	xoo_el()->aff->fields->get_input_html( $field_id, $field_args );
}

?>

<div class="xoo-aff-group xoo-el-login-btm-fields">
	<label class="xoo-el-form-label">
		<input type="checkbox" name="xoo-el-rememberme" value="forever" />
		<span><?php _e( 'Remember me', 'easy-login-woocommerce' ); ?></span>
	</label>
	<a class="xoo-el-lostpw-tgr"><?php _e('Forgot Password?','easy-login-woocommerce'); ?></a>
</div>

<?php do_action( 'xoo_el_login_add_fields', $args ); ?>

<input type="hidden" name="_xoo_el_form" value="login">

<button type="submit" class="button btn xoo-el-action-btn xoo-el-login-btn" <?php if( !xoo_el_is_limit_login_ok() ) echo "disabled"; ?>><?php _e('Sign In','easy-login-woocommerce'); ?></button>

<input type="hidden" name="xoo_el_redirect" value="<?php echo esc_url( $redirect ); ?>">