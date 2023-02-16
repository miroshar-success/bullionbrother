<?php
/**
 * Template part for displaying the mobile header color switch
 *
 * @package kadence_pro
 */

namespace Kadence_Pro;

?>
<div class="site-header-item site-header-focus-item" data-section="kadence_customizer_mobile_dark_mode">
	<?php
	/**
	 * Kadence Mobile Header Color Switcher
	 *
	 * Hooked Kadence_Pro\mobile_color_switcher
	 */
	do_action( 'kadence_mobile_dark_mode' );
	?>
</div><!-- data-section="mobile_dark_mode" -->
