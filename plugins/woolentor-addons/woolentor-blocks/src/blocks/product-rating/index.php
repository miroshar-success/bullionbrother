<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-product-rating' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$product = wc_get_product();
if ( empty( $product ) ) { return; }
echo '<div class="'.implode(' ', $areaClasses ).'">';
	woocommerce_template_single_rating();
echo '</div>';