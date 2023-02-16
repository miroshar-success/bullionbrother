<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$uniqClass = 'woolentorblock-'.$settings['blockUniqId'];
	$areaClasses = array( 'woolentor-marker-area' );
	$classes = array( $uniqClass, 'wlb-marker-wrapper' );
	!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
	!empty( $settings['className'] ) ? $classes[] = $settings['className'] : '';
	!empty( $settings['style'] ) ? $classes[] = 'wlb-marker-style-'.$settings['style'] : 'wlb-marker-style-1';

	$background_image = woolentorBlocks_Background_Control( $settings, 'bgProperty' );

?>
<div class="<?php echo implode(' ', $areaClasses ); ?>">
	<div class="<?php echo implode(' ', $classes ); ?>" style="<?php echo $background_image; ?> position:relative;">

		<?php
			foreach ( $settings['markerList'] as $item ):
				
				$horizontalPos = !empty( $item['horizontal'] ) ? 'left:'.$item['horizontal'].'%;' : 'left:50%;';
				$verticlePos = !empty( $item['verticle'] ) ? 'top:'.$item['verticle'].'%;' : '15%;';

			?>
				<div class="wlb_image_pointer" style="<?php echo $horizontalPos.$verticlePos; ?>">
					<div class="wlb_pointer_box">
						<?php
							if( !empty( $item['title'] ) ){
								echo '<h4>'.esc_html__( $item['title'], 'woolentor' ).'</h4>';
							}
							if( !empty( $item['content'] ) ){
								echo '<p>'.esc_html__( $item['content'], 'woolentor' ).'</p>';
							}
						?>
					</div>
				</div>
				
			<?php
			endforeach;
		?> 
	</div>
</div>