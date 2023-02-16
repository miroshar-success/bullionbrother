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
    \WC()->cart->calculate_totals();
}

if( !empty( \WC()->cart->cart_contents ) ){
    $uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
    $areaClasses = array( $uniqClass, 'woolentor_block_cart_total' );
    !empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
    !empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

    echo '<div class="'.implode(' ', $areaClasses ).'">';
        if ( ! \WC()->cart->is_empty() ) {
            woocommerce_cart_totals();
        }
    echo '</div>';
}