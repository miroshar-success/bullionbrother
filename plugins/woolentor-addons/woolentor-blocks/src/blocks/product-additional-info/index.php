<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-product-additional-info' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$product = wc_get_product();
if ( empty( $product ) ) {
	return;
}
echo '<div class="'.implode(' ', $areaClasses ).'">';
	wc_get_template( 'single-product/tabs/additional-information.php' );
echo '</div>';