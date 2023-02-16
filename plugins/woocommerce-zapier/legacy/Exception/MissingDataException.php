<?php

namespace OM4\Zapier\Exception;

use OM4\WooCommerceZapier\Exception\BaseException;

defined( 'ABSPATH' ) || exit;

/**
 * Exception to trigger when data for property is missing.
 *
 * @deprecated 2.0.0
 */
class MissingDataException extends BaseException {

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param string     $class The name of the class.
	 * @param int|string $key   The name of the property.
	 * @param string     $type  Type of the data is missing.
	 * @param int        $code  [optional] The Exception code.
	 */
	public function __construct( $class, $key, $type, $code = 0 ) {
		$message = sprintf(
			'Missing %s data for %s in class %s.',
			$type,
			$key,
			$class
		);
		parent::__construct( $message, $code );
	}
}
