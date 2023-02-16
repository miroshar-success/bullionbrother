<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_checkout_coupon_form' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';
    
    if( ( is_checkout() && wc_coupons_enabled() ) || $block['is_editor'] ){
        $apply_button_text = !empty( $settings['formApplyButton'] ) ? $settings['formApplyButton'] : esc_html__('Apply coupon', 'woolentor-pro');
        ?>
            <div class="woolentor-checkout-coupon-form">
                <div class="checkout-coupon-toggle">
                    <div class="woocommerce-info">
                        <?php echo esc_html( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__('Have a coupon?', 'woolentor-pro') ) ); ?>
                        <a href="#" class="show-coupon"><?php echo esc_html__('Click here to enter your code', 'woolentor-pro') ?></a>
                    </div>
                </div>
                <div class="coupon-form" style="display:none">
                    <?php if( $settings['formDescription'] ): ?>
                        <p class="woolentor-info"><?php echo wp_kses_post( $settings['formDescription'] );?></p>
                    <?php endif; ?>

                    <p class="form-row form-row-first">
                        <input type="text" name="coupon_code" class="input-text" placeholder="<?php echo esc_attr('Coupon code', 'woolentor');?>" id="coupon_code" value="" />
                    </p>
                    <p class="form-row form-row-last">
                        <button type="button" class="button" name="apply_coupon" value="<?php echo esc_attr( $apply_button_text );?>"><?php echo esc_html( $apply_button_text ) ?></button>
                    </p>
                    <div class="clear"></div>
                </div>
            </div>
        <?php
    }
    
echo '</div>';