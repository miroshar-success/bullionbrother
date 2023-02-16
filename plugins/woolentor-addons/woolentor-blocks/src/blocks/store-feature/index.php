<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array('woolentor-store-feature-area');
$classes = array( $uniqClass, 'woolentor-blocks ht-feature-wrap' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $classes[] = $settings['className'] : '';
!empty( $settings['layout'] ) ? $classes[] = 'ht-feature-style-'.$settings['layout'] : 'ht-feature-style-1';
!empty( $settings['textAlignment'] ) ? $classes[] = 'woolentor-text-align-'.$settings['textAlignment'] : 'woolentor-text-align-center';

$store_image = !empty( $settings['featureImage']['id'] ) ? wp_get_attachment_image( $settings['featureImage']['id'], 'full' ) : '';

?>
<div class="<?php echo implode(' ', $areaClasses ); ?>">
	<div class="<?php echo implode(' ', $classes ); ?>">
		<div class="ht-feature-inner">
			<?php
				if( !empty( $store_image ) ){
					echo '<div class="ht-feature-img">'.$store_image.'</div>';
				}
			?>
			<div class="ht-feature-content">
				<?php
					if( !empty( $settings['title'] ) ){
						echo '<h4>'.$settings['title'].'</h4>';
					}
					if( !empty( $settings['subTitle'] ) ){
						echo '<p>'.$settings['subTitle'].'</p>';
					}
				?>
			</div>
		</div>
	</div>
</div>