<?php

namespace OM4\Zapier\Payload\Collection;

use OM4\Zapier\Payload\Base\Collection;

defined( 'ABSPATH' ) || exit;

/**
 * Holds an array of \OM4\Zapier\Payload\Item\LineItem classes.
 *
 * @deprecated 2.0.0
 */
final class LineItems extends Collection {

	/**
	 * Holds the name of the Item Class which is allowed in this Collection
	 *
	 * @var  string
	 */
	protected static $item_type = '\\OM4\\Zapier\\Payload\\Item\\LineItem';
}
