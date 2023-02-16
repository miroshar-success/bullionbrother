<?php
/**
 * This template adds the mobile menu drawer
 *
 * @package Genesis Block Theme
 * @since Genesis Block Theme 1.0
 */

?>

<div class="mobile-navigation">
	<button class="menu-toggle button-toggle">
		<span>
			<i class="gbi gbicon-bars"></i>
			<?php esc_html_e( 'Menu', 'genesis-block-theme' ); ?>
		</span>
		<span>
			<i class="gbi gbicon-times"></i>
			<?php esc_html_e( 'Close', 'genesis-block-theme' ); ?>
		</span>
	</button><!-- .overlay-toggle -->
</div>

<div class="drawer-wrap">
	<div class="drawer drawer-menu-explore">
		<nav id="drawer-navigation" class="drawer-navigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
				)
			);
			?>
		</nav><!-- #site-navigation -->
	</div><!-- .drawer -->
</div>
