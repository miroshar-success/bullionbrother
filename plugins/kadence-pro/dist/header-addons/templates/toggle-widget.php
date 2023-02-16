<?php
/**
 * Template part for displaying the header toggle widget
 *
 * @package kadence_pro
 */

namespace Kadence_Pro;

?>
<div class="site-header-item site-header-focus-item" data-section="kadence_customizer_header_toggle_widget">
	<?php
	/**
	 * Kadence Header Toggle Widget
	 *
	 * Hooked Kadence_Pro\header_toggle_widget
	 */
	do_action( 'kadence_header_toggle_widget' );
	?>
</div><!-- data-section="header_toggle_widget" -->
