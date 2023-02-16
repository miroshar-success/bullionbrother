<?php
/**
 * Checkout billing information form
 * 
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-billing-fields">
	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
			$fields = $checkout->get_checkout_fields( 'billing' );
			if( array_key_exists('billing_email', $fields) ){
				Woolentor_Shopify_Like_Checkout::woocommerce_form_field( 'billing_email', $fields['billing_email'], $checkout->get_value( 'billing_email' ) );
			}
		?>
	</div>

	<div class="woolentor-checkout__section-header">
		<?php
			$title_status = array_key_exists('billing_email', $fields) && count($fields) == 1 ? false : true;

			if ( $title_status && wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
			<h3 class="woolentor-checkout__section-title"><?php esc_html_e( 'Billing &amp; Shipping', 'woolentor' ); ?></h3>
		<?php elseif( $title_status ) : ?>
			<h3 class="woolentor-checkout__section-title"><?php esc_html_e( 'Billing address', 'woolentor' ); ?></h3>
		<?php endif; ?>
	</div>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		<?php
			$fields = $checkout->get_checkout_fields( 'billing' );
			foreach ( $fields as $key => $field ) {
				if($key == 'billing_email'){
					continue;
				}
				Woolentor_Shopify_Like_Checkout::woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
			}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woolentor' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php Woolentor_Shopify_Like_Checkout::woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
