<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
	$classes 	 = array( $uniqClass, 'ht-brand-wrap' );
	$areaClasses = array( 'woolentor-brand-area' );

	!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';

	!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-grid-columns-'.$settings['columns']['desktop'] : 'woolentor-grid-columns-4';
	!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-grid-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-grid-columns-laptop-3';
	!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-grid-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-grid-columns-tablet-2';
	!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-grid-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-grid-columns-mobile-1';

	$default_img = '<img src="'.WOOLENTOR_BLOCK_URL.'/src/assets/images/brand.png'.'" alt="'.esc_html__('Brand Logo','woolentor').'">';
	$brands = $settings['brandLogoList'];
		
?>
<div class="<?php echo implode(' ', $areaClasses ); ?>">
	<div class="<?php echo implode(' ', $classes ); ?>">
		<?php
			if( is_array( $brands ) ){
				echo '<div class="woolentor-grid '.( $settings['noGutter'] === true ? 'woolentor-no-gutters' : '' ).'">';
					foreach ( $brands as $key => $brand ) {
	
						$image = !empty( $brand['image']['id'] ) ? wp_get_attachment_image( $brand['image']['id'], 'full' ) : $default_img;
						$logo  = !empty( $brand['link'] ) ? sprintf('<a href="%s" target="_blank">%s</a>',esc_url( $brand['link'] ), $image ) : $image;
	
						?>
							<div class="woolentor-grid-column">
								<div class="wl-single-brand">
									<?php echo $logo; ?>
								</div>
							</div>
						<?php
					}
				echo '</div>';
			}
		?>
	</div>
</div>