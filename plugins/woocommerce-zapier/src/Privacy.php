<?php

namespace OM4\WooCommerceZapier;

use WC_Abstract_Privacy;

defined( 'ABSPATH' ) || exit;

/**
 * Adds privacy/GDPR related suggestions for the store owner's privacy policy.
 * Requires WordPress 4.9.6+ and WooCommerce 3.4+
 *
 * @since 2.0.0
 */
class Privacy extends WC_Abstract_Privacy {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( __( 'WooCommerce Zapier', 'woocommerce-zapier' ) );
	}

	/**
	 * Gets the message of the privacy to display.
	 *
	 * @return string
	 */
	public function get_privacy_message() {
		$content =
			'<p>' . __( 'By using this extension, you may be sharing personal data with an external service (Zapier). Customer information provided during the purchase (checkout) process is sent to Zapier if you have one or more Zapier Feeds configured.', 'woocommerce-zapier' ) . '</p>' .
			'<p>' . __( 'Please see the <a href="https://zapier.com/privacy/">Zapier Privacy Policy</a> for more details.', 'woocommerce-zapier' ) . '</p>' .
			'<p>' . __( 'Once this personal information is sent to Zapier, it is then sent to various third party services. You should list the service(s) that are used in the Action part(s) of your WooCommerce Zaps, so that your customers understand which third party services their personal data is sent to after it is sent to Zapier.', 'woocommerce-zapier' ) . '</p>';

		return $content;
	}
}

