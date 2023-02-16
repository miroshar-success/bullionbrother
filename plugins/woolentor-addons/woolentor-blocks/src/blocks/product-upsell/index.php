<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'product_related' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-products-columns-'.$settings['columns']['desktop'] : 'woolentor-products-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-products-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-products-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-products-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-products-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-products-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-products-columns-mobile-1';

$product = wc_get_product();
if ( empty( $product ) ) { return; }

// Get upsell Product
$product_per_page   = '-1';
$columns            = 4;
$orderby            = 'rand';
$order              = 'desc';
if ( ! empty( $settings['columns']['desktop'] ) ) {
	$columns = $settings['columns']['desktop'];
}
if ( ! empty( $settings['orderby'] ) ) {
	$orderby = $settings['orderby'];
}
if ( ! empty( $settings['order'] ) ) {
	$order = $settings['order'];
}
if ( ! empty( $settings['perPage'] ) ) {
	$product_per_page = $settings['perPage'];
}

echo '<div class="'.implode(' ', $areaClasses ).'">';
	woocommerce_upsell_display( $product_per_page, $columns, $orderby, $order );
echo '</div>';