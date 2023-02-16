<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-qrcode' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$product = wc_get_product();
if( empty( $product ) ){
	$product = wc_get_product( woolentor_get_last_product_id() );
}

$product_id = $product->get_id();

$quantity = ( !empty( $settings['quantity'] ) ? $settings['quantity'] : 1 );
if( $settings['addCartUrl'] == 'yes' ){
	$url = get_the_permalink( $product_id ).sprintf( '?add-to-cart=%s&quantity=%s', $product_id, $quantity );
}else{
	$url = get_the_permalink( $product_id );
}

$title = get_the_title( $product_id );
$product_url   = urlencode( $url );

$size      = ( !empty( $settings['size'] ) ? $settings['size'] : 120 );
$dimension = $size.'x'.$size;
$image_src = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $product_url );

echo '<div class="'.implode(' ', $areaClasses ).'">';
	echo sprintf('<img src="%1$s" alt="%2$s">', $image_src, $title );
echo '</div>';