<?php

/**
 * Content for the notice that is displayed to first-time users.
 *
 * @since 2.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2><?php echo esc_html( __( 'Welcome to WooCommerce Zapier', 'woocommerce-zapier' ) ); ?></h2>
<p><?php echo esc_html( __( 'The next step is to create your first Zap. Please click the button below to get started.', 'woocommerce-zapier' ) ); ?></p>
<p>
<?php
	echo wp_kses_post(
		sprintf(
			'<a class="button button-primary" target="_blank" href="%s">%s</a>',
			esc_attr( $button_url ),
			__( 'Learn how to Create a Zap', 'woocommerce-zapier' )
		)
	);
	?>
</p>
