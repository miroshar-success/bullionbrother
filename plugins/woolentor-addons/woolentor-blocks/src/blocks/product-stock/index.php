<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'product_stock_status' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$product = wc_get_product();
if ( empty( $product ) ) { return; }
if ( $product->get_manage_stock() ) {
	echo '<div class="'.implode(' ', $areaClasses ).'">';
		echo wc_get_stock_html( $product );
	echo '</div>';
}