<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		
$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'product_title' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$title_html_tag = woolentor_validate_html_tag( $settings['titleTag'] );
// $title = $block['is_editor'] ? get_the_title( woolentorBlocks_get_last_product_id() ) : get_the_title();
$title = get_the_title();

echo sprintf( "<%s class='%s'>%s</%s>", $title_html_tag, implode(' ', $areaClasses ), $title, $title_html_tag  );