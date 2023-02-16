<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();


	// Support for WooCommerce Germanized Plugin
	if( class_exists('Vendidero\Germanized\Autoloader') ){
	    add_action( 'woolentor_after_checkout_order', 'woocommerce_gzd_template_order_submit' );
	}

	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
	
?>

<div class="woocommerce woolentor-woocommerce-checkout">
	<?php
		do_action( 'woocommerce_before_checkout_form', $checkout );

		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woolentor-pro' ) ) );
			return;
		}
		
	?>
	<?php do_action('woolentor_checkout_top_content'); ?>
		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<?php do_action('woolentor_checkout_content'); ?>
			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
		</form>
	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>