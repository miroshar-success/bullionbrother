<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] ){
    \WC()->frontend_includes();
    if ( is_null( \WC()->cart ) ) {
        \WC()->session = new \WC_Session_Handler();
        \WC()->session->init();
        \WC()->cart     = new \WC_Cart();
        \WC()->customer = new \WC_Customer(get_current_user_id(), true);
    }
    \WooLentorBlocks\Sample_Data::instance()->add_product_for_empty_cart();
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_checkout_payment' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';
!empty( $settings['headingAlignment'] ) ? $areaClasses[] = 'woolentor-heading-'.$settings['headingAlignment'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';
    if( is_checkout() || ( $block['is_editor'] && !empty( \WC()->cart->cart_contents ) ) ){
        woocommerce_checkout_payment();
    }
echo '</div>';