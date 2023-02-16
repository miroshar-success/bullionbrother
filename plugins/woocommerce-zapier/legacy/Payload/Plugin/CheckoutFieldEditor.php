<?php

namespace OM4\Zapier\Payload\Plugin;

use OM4\Zapier\Payload\Base\Item;

defined( 'ABSPATH' ) || exit;

/**
 * Implement base structure requirements for "Checkout Field Editor" Object.
 *
 * @deprecated 2.0.0
 */
class CheckoutFieldEditor extends Item {

	/**
	 * Signalling this class is structured or not
	 *
	 * @var bool
	 */
	protected static $is_structured = false;
}
