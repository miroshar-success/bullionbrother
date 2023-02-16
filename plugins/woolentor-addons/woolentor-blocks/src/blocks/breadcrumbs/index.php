<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-breadcrumb' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$args = [
	'delimiter'   => !empty( $settings['separator'] ) ? '<span class="breadcrumb-separator">'.$settings['separator'].'</span>' : '<span class="breadcrumb-separator">&nbsp;&#47;&nbsp;</span>',
	'wrap_before' => '<nav class="woocommerce-breadcrumb">',
	'wrap_after'  => '</nav>',
];

echo '<div class="'.implode(' ', $areaClasses ).'">';
	woocommerce_breadcrumb( $args );
echo '</div>';