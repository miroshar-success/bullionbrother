<?php
/**
 * Template part for displaying the header HTML2 Modual
 *
 * @package kadence_pro
 */

namespace Kadence_Pro;

use function Kadence\kadence;

$link_style = ( kadence()->option( 'header_widget1_link_style' ) ? kadence()->option( 'header_widget1_link_style' ) : 'normal' );
?>
<aside class="widget-area site-header-item site-header-focus-item header-widget1 header-widget-lstyle-<?php echo esc_attr( $link_style ); ?>" data-section="sidebar-widgets-header1">
	<div class="header-widget-area-inner site-info-inner">
		<?php
		dynamic_sidebar( 'header1' );
		?>
	</div>
</aside><!-- .header-widget1 -->
