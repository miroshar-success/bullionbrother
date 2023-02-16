<?php

namespace OM4\WooCommerceZapier\Exception;

use Exception;

defined( 'ABSPATH' ) || exit;

/**
 * All Exception in OM4\WooCommerceZapier namespace must extend this.
 *
 * @since 2.0.0
 */
abstract class BaseException extends Exception {}
