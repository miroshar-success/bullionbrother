<?php
/**
 * Template part for displaying the header color switch
 *
 * @package kadence_pro
 */

namespace Kadence_Pro;

?>
<div class="site-header-item site-header-focus-item" data-section="kadence_customizer_header_dark_mode">
	<?php
	/**
	 * Kadence Header Color Switcher
	 *
	 * Hooked Kadence_Pro\header_color_switcher
	 */
	do_action( 'kadence_header_dark_mode' );
	?>
</div><!-- data-section="header_dark_mode" -->
