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

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_checkout_order_review' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';
    do_action( 'woolentor_before_checkout_order' );
        if( is_checkout() || ( $block['is_editor'] && !empty( \WC()->cart->cart_contents ) ) ){
            if( !empty( $settings['heading'] ) ){
                echo '<h3 id="order_review_heading">'.esc_html( $settings['heading'] ).'</h3>';
            }
            echo '<div id="order_review" class="woocommerce-checkout-review-order">';
                woocommerce_order_review();
            echo '</div>';
        }
    do_action( 'woolentor_after_checkout_order' );
echo '</div>';