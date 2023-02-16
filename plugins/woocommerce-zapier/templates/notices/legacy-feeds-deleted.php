<?php

/**
 * Content for the notice that is displayed to users after they delete their last Legacy Zapier Feed.
 *
 * @since 2.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2><?php echo esc_html( __( 'Congratulations', 'woocommerce-zapier' ) ); ?></h2>
<p><?php echo esc_html( __( 'You have just deleted your last Legacy Zapier Feed.', 'woocommerce-zapier' ) ); ?></p>
<h3><?php echo esc_html( __( 'Next Steps', 'woocommerce-zapier' ) ); ?></h3>
<p><?php echo esc_html( __( 'After clicking the button below, the Legacy Feeds interface will no longer be available. You will then manage your Zaps entirely via the Zapier.com Zap editor.', 'woocommerce-zapier' ) ); ?></p>
<p>
	<?php
	echo wp_kses_post(
		// No href is required due to click JS handler for .dismiss-notice buttons.
		sprintf( '<a class="button button-primary dismiss-notice" href="">%s</a>', __( 'OK', 'woocommerce-zapier' ) )
	);
	?>
</p>
