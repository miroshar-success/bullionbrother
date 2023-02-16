<?php

namespace OM4\Zapier\Payload\Item;

use OM4\Zapier\Payload\Base\Item;

defined( 'ABSPATH' ) || exit;

/**
 * Holds the data of meta data array.
 *
 * @deprecated 2.0.0
 */
class MetaData extends Item {

	/**
	 * Signalling this class is structured or not
	 *
	 * @var bool
	 */
	protected static $is_structured = false;
}
