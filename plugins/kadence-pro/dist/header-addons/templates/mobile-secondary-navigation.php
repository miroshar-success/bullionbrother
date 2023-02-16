<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package kadence
 */

namespace Kadence;

?>
<div class="site-header-item site-header-focus-item site-header-item-mobile-navigation mobile-secondary-navigation-layout-stretch-<?php echo ( kadence()->option( 'mobile_secondary_navigation_stretch' ) ? 'true' : 'false' ); ?>" data-section="kadence_customizer_mobile_secondary_navigation">
	<?php
	/**
	 * Kadence Mobile Secondary Navigation
	 *
	 * Hooked Kadence\mobile_secondary_navigation
	 */
	do_action( 'kadence_mobile_secondary_navigation' );
	?>
</div><!-- data-section="mobile_secondary_navigation" -->
