<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] ){
	\WooLentor_Default_Data::instance()->theme_hooks('woolentor-product-archive-addons');
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_archive_default' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';
!empty( $settings['contentAlignment'] ) ? $areaClasses[] = 'woolentor-content-align-'.$settings['contentAlignment'] : '';

if( isset( $settings['saleTagShow'] ) && $settings['saleTagShow'] === false){
	$areaClasses[] = 'woolentor-archive-sale-badge-hide';
}else{
	!empty( $settings['saleTagPosition'] ) ? $areaClasses[] = 'woolentor-archive-sale-badge-'.$settings['saleTagPosition'] : '';
}
// Manage Column
!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-products-columns-'.$settings['columns']['desktop'] : 'woolentor-products-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-products-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-products-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-products-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-products-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-products-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-products-columns-mobile-1';

//Product Filter Module
$contentClasses = array();
$areaAttributes = array();
$filterable = ( isset( $settings['filterable'] ) ? rest_sanitize_boolean( $settings['filterable'] ) : true );
if ( true === $filterable ) {
	$areaClasses[] = 'wl-filterable-products-wrap';
	$contentClasses[] = 'wl-filterable-products-content';
	$areaAttributes[] = 'data-wl-widget-name="woolentor-product-archive-addons"';
	$areaAttributes[] = 'data-wl-widget-settings="' . esc_attr( htmlspecialchars( wp_json_encode( $settings ) ) ) . '"';
}

if ( WC()->session && function_exists( 'wc_print_notices' ) ) {
	wc_print_notices();
}

if ( ! isset( $GLOBALS['post'] ) ) {
	$GLOBALS['post'] = null;
}

$options = [
	'query_post_type'	=> ! empty( $settings['paginate'] ) ? 'current_query' : '',
	'columns' 			=> $settings['columns']['desktop'],
	'rows' 				=> $settings['rows'],
	'paginate' 			=> !empty( $settings['paginate'] ) ? 'yes' : 'no',
	'editor_mode' 		=> $block['is_editor'],
];

if( !empty( $settings['paginate'] ) ){
	$options['paginate'] = 'yes';
	$options['allow_order'] = !empty( $settings['allowOrder'] ) ? 'yes' : 'no';
	$options['show_result_count'] = !empty( $settings['showResultCount'] ) ? 'yes' : 'no';
}else{
	$options['order'] 	= !empty( $settings['order'] ) ? $settings['order'] : 'desc';
	$options['orderby'] = !empty( $settings['orderBy'] ) ? $settings['orderBy'] : 'date';
}

$shortcode 	= new \Archive_Products_Render( $options, 'products', $filterable );
$content 	= $shortcode->get_content();
$not_found_content = woolentor_products_not_found_content();

echo '<div class="'.implode(' ', $areaClasses ).'" '.implode(' ', $areaAttributes ).'>';
	echo ( ( true === $filterable ) ? '<div class="'.implode(' ', $contentClasses ).'">' : '' );
		if ( strip_tags( trim( $content ) ) ) {
			echo $content;
		} else{
			echo $not_found_content;
		}
	echo ( ( true === $filterable ) ? '</div>' : '' );
echo '</div>';