<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-product-tabs', 'product' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$product = wc_get_product();
if ( empty( $product ) ) {
	return;
}
echo '<div class="'.implode(' ', $areaClasses ).'">';
	$post = get_post( $product->get_id() );
	wc_get_template( 'single-product/tabs/tabs.php' );
echo '</div>';