<?php

namespace OM4\Zapier;

use OM4\WooCommerceZapier\Logger as NewLogger;
use OM4\WooCommerceZapier\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Legacy Logger, extending 2.0 logger so that Legacy code can use it.
 *
 * @deprecated 2.0.0 Replaced by OM4\WooCommerceZapier\Logger
 */
class Logger extends NewLogger {

	/**
	 * Logger constructor.
	 */
	public function __construct() {
		parent::__construct( new Settings() );
		$this->context = array( 'source' => 'woocommerce-zapier-legacy' );
	}
}
