<?php

/**
 * Content for the notice that is displayed to users upgrading from 1.9 to 2.0.
 *
 * @since 2.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2><?php echo esc_html( __( 'Welcome to WooCommerce Zapier 2.0', 'woocommerce-zapier' ) ); ?></h2>
<p><?php echo esc_html( __( 'Thank you for updating to the latest version of WooCommerce Zapier.', 'woocommerce-zapier' ) ); ?></p>
<h3><?php echo esc_html( __( 'What\'s New?', 'woocommerce-zapier' ) ); ?></h3>
<ul>
	<li><?php echo wp_kses_post( __( '<strong>Two-Way Integration:</strong> Now with 15 brand new Actions and Searches. You can now use Zaps to create new and update existing Coupon, Customer, Product, Order or Subscription data in WooCommerce.', 'woocommerce-zapier' ) ); ?></li>
	<li><?php echo wp_kses_post( __( '<strong>New Triggers:</strong> Four times (4x) the number of available trigger events. This gives you four times as many reasons to send your WooCommerce store data to Zapier.', 'woocommerce-zapier' ) ); ?></li>
	<li><?php echo wp_kses_post( __( '<strong>Simplified Zap Creation:</strong> Zaps are now created entirely in the Zapier.com Zap editor interface. No need to create Zap specific Zapier Feeds in WooCommerce. ', 'woocommerce-zapier' ) ); ?></li>
	<li><?php echo wp_kses_post( __( '<strong>New Data Types:</strong> Adds support for Products, Coupons and Bookings, in addition to the already supported Orders, Customers and Subscriptions', 'woocommerce-zapier' ) ); ?></li>
	<li><?php echo wp_kses_post( __( '<strong>Powered by the REST API:</strong> Giving you access to many more data fields as well as more robust and reliable data delivery.', 'woocommerce-zapier' ) ); ?></li>
</ul>

<h3><?php echo esc_html( __( 'Next Steps', 'woocommerce-zapier' ) ); ?></h3>
<p><?php echo esc_html( __( 'Using Legacy Zapier Feeds is no longer supported.', 'woocommerce-zapier' ) ); ?></p>
<p><?php echo esc_html( __( ' Your existing Zaps and Feeds need to be re-built using new REST API based Zaps.', 'woocommerce-zapier' ) ); ?></p>
<p>
<?php
	echo wp_kses_post(
		sprintf(
			'<a class="button button-primary" target="_blank" href="%s">%s</a>',
			esc_attr( $migration_guide_url ),
			__( 'Learn how to Migrate a Zap', 'woocommerce-zapier' )
		)
	);
	?>
</p>
