<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-archive-data-area' );

!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$data       = woolentor_get_archive_data();
$title_tag  = woolentor_validate_html_tag( $settings['titleTag'] );

$title          = ( $settings['showTitle'] == true && !empty( $data['title'] ) ) ? sprintf( "<%s class='woolentor-archive-title'>%s</%s>", $title_tag, esc_html( $data['title'] ), $title_tag  ) : '';
$description    = ( $settings['showDescription'] == true && !empty( $data['desc'] ) ) ? sprintf( "<div class='woolentor-archive-desc'>%s</div>", esc_html( $data['desc'] )  ) : '';
$image          = ( $settings['showImage'] == 'yes' && !empty( $data['image_url'] ) ) ? sprintf( "<div class='woolentor-archive-image'><img src='%s' alt='%s'></div>", esc_url( $data['image_url'] ), esc_attr( $data['title'] )  ) : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';
	echo sprintf( '%s %s %s', $image, $title, $description );
echo '</div>';