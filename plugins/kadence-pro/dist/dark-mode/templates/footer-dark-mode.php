<?php
/**
 * Template part for displaying the Footer color switch
 *
 * @package kadence_pro
 */

namespace Kadence_Pro;

use function Kadence\kadence;
$align        = ( kadence()->sub_option( 'footer_dark_mode_align', 'desktop' ) ? kadence()->sub_option( 'footer_dark_mode_align', 'desktop' ) : 'default' );
$tablet_align = ( kadence()->sub_option( 'footer_dark_mode_align', 'tablet' ) ? kadence()->sub_option( 'footer_dark_mode_align', 'tablet' ) : 'default' );
$mobile_align = ( kadence()->sub_option( 'footer_dark_mode_align', 'mobile' ) ? kadence()->sub_option( 'footer_dark_mode_align', 'mobile' ) : 'default' );

$valign        = ( kadence()->sub_option( 'footer_dark_mode_vertical_align', 'desktop' ) ? kadence()->sub_option( 'footer_dark_mode_vertical_align', 'desktop' ) : 'default' );
$tablet_valign = ( kadence()->sub_option( 'footer_dark_mode_vertical_align', 'tablet' ) ? kadence()->sub_option( 'footer_dark_mode_vertical_align', 'tablet' ) : 'default' );
$mobile_valign = ( kadence()->sub_option( 'footer_dark_mode_vertical_align', 'mobile' ) ? kadence()->sub_option( 'footer_dark_mode_vertical_align', 'mobile' ) : 'default' );
?>
<div class="footer-widget-area widget-area site-footer-focus-item footer-dark-mode content-align-<?php echo esc_attr( $align ); ?> content-tablet-align-<?php echo esc_attr( $tablet_align ); ?> content-mobile-align-<?php echo esc_attr( $mobile_align ); ?> content-valign-<?php echo esc_attr( $valign ); ?> content-tablet-valign-<?php echo esc_attr( $tablet_valign ); ?> content-mobile-valign-<?php echo esc_attr( $mobile_valign ); ?>" data-section="kadence_customizer_footer_dark_mode">
	<div class="footer-widget-area-inner footer-dark-mode-inner">
		<?php
		/**
		 * Kadence Footer Color Switcher
		 *
		 * Hooked Kadence_Pro\footer_color_switcher
		 */
		do_action( 'kadence_footer_dark_mode' );
		?>
	</div>
</div><!-- data-section="footer_dark_mode" -->
