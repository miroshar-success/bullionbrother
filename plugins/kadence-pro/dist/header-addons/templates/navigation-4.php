<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;

?>
<div class="site-header-item site-header-focus-item site-header-item-main-navigation header-navigation-layout-stretch-<?php echo ( kadence()->option( 'quaternary_navigation_stretch' ) ? 'true' : 'false' ); ?> header-navigation-layout-fill-stretch-<?php echo ( kadence()->option( 'quaternary_navigation_fill_stretch' ) ? 'true' : 'false' ); ?>" data-section="kadence_customizer_quaternary_navigation">
	<?php
	/**
	 * Kadence quaternary Navigation
	 *
	 * Hooked Kadence_Pro\quaternary_navigation
	 */
	do_action( 'kadence_quaternary_navigation' );
	?>
</div><!-- data-section="quaternary_navigation" -->
