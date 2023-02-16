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
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_cart_empty_message' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

add_filter( 'wc_empty_cart_message', function( $message ) use ( $settings ){
    if( !empty( $settings['customMessage'] ) ){
        return $settings['customMessage'];
    }else{
        return $message;
    }
}, 1 );

echo '<div class="'.implode(' ', $areaClasses ).'">';
    do_action( 'woocommerce_cart_is_empty' );
echo '</div>';