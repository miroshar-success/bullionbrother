<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		
$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-product-description' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$product = wc_get_product();
if ( empty( $product ) ) { return; }
echo '<div class="'.implode(' ', $areaClasses ).'">';
	echo '<div class="woocommerce_product_description">';
		the_content();
	echo '</div>';
echo '</div>';