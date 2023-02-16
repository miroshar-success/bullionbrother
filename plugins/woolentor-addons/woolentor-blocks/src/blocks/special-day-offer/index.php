<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass );
$classes 	 = array( 'wlspcial-banner' );

!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';

!empty( $settings['contentPosition'] ) ? $classes[] = 'woolentor-banner-content-pos-'.$settings['contentPosition'] : '';

$arrContextOptions = [
	'ssl' => [
		'verify_peer' => false,
		'verify_peer_name' => false,
	]
];
$default_img = file_get_contents( WOOLENTOR_BLOCK_URL . '/src/assets/images/banner-image.svg', false, stream_context_create($arrContextOptions) );

$banner_url 	= !empty( $settings['bannerLink'] ) ? $settings['bannerLink'] : '#';
$banner_image 	= !empty( $settings['bannerImage']['id'] ) ? wp_get_attachment_image( $settings['bannerImage']['id'], 'full' ) : $default_img;
$badge_image 	= !empty( $settings['badgeImage']['id'] ) ? wp_get_attachment_image( $settings['badgeImage']['id'], 'full' ) : '';

?>
<div class="<?php echo implode(' ', $areaClasses ); ?>">
	<div class="<?php echo implode(' ', $classes ); ?>">
		
		<div class="banner-thumb">
			<a href="<?php echo esc_url( $banner_url ); ?>">
				<?php echo $banner_image; ?>
			</a>
		</div>

		<?php
			if( !empty( $badge_image ) ){
				echo '<div class="wlbanner-badgeimage">'.$badge_image.'</div>';
			}
		?>

		<div class="banner-content">
			<?php
				if( !empty( $settings['title'] ) ){
					echo '<h2>'.$settings['title'].'</h2>';
				}
				if( !empty( $settings['subTitle'] ) ){
					echo '<h6>'.$settings['subTitle'].'</h6>';
				}
				if( !empty( $settings['offerAmount'] ) ){
					echo '<h5>'.$settings['offerAmount'].'<span>'.$settings['offerTagLine'].'</span></h5>';
				}
				if( !empty( $settings['bannerDescription'] ) ){
					echo '<p>'.$settings['bannerDescription'].'</p>';
				}

				if( !empty( $settings['buttonText'] ) ){
					echo '<a href="'.esc_url( $banner_url ).'">'.esc_html__( $settings['buttonText'],'woolentor' ).'</a>';
				}
			?>
		</div>

	</div>
</div>