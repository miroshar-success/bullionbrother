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

$cross_sells = null;
if( \WC()->cart ) {
    $cross_sells = \WC()->cart->get_cross_sells();
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_cross_sell' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';
!empty( $settings['contentAlignment'] ) ? $areaClasses[] = 'woolentor-content-align-'.$settings['contentAlignment'] : '';
if( isset( $settings['saleTagShow'] ) && $settings['saleTagShow'] === false){
    $areaClasses[] = 'woolentor-archive-sale-badge-hide';
}else{
    !empty( $settings['saleTagPosition'] ) ? $areaClasses[] = 'woolentor-archive-sale-badge-'.$settings['saleTagPosition'] : '';
}

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-products-columns-'.$settings['columns']['desktop'] : 'woolentor-products-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-products-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-products-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-products-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-products-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-products-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-products-columns-mobile-1';

echo '<div class="'.implode(' ', $areaClasses ).'">';
    if( ! empty( $cross_sells ) ) {
        woocommerce_cross_sell_display( $settings['limit'], $settings['columns']['desktop'], $settings['orderBy'], $settings['order'] );
    }else{
        if( $block['is_editor'] ){
            echo '<p>'.esc_html__( 'There are no cross-sale products are available.','woolentor-pro' ).'</p>';
        }
    }
echo '</div>';