<?php
/**
 * Checkout coupon form
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woolentor-checkout__order-summary-section">
    <form class="checkout_coupon woocommerce-form-coupon woocommerce-checkout" method="post" style="display:block">
        <div class="woolentor-checkout__field-full woolentor-checkout__gift-card">
            <div class="woolentor-checkout__field">
                <input class="woolentor-checkout__input input-text" type="text" name="coupon_code" id="gift-card" placeholder="<?php esc_attr_e( 'Coupon code', 'woolentor' ); ?>"  />
                <label class="woolentor-checkout__label" for="gift-card"><?php esc_attr_e( 'Coupon code', 'woolentor' ); ?></label>
            </div>
            <div class="woolentor-checkout__apply-button-box">
                <button class="woolentor-checkout__button" name="apply_coupon" type="submit" value="<?php esc_attr_e( 'Apply coupon', 'woolentor' ); ?>"><?php esc_html_e( 'Apply', 'woolentor' ); ?></button>
            </div>
        </div>
    </form>
</div>