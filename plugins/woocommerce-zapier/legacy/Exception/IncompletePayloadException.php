<?php

namespace OM4\Zapier\Exception;

use OM4\WooCommerceZapier\Exception\BaseException;

defined( 'ABSPATH' ) || exit;

/**
 * Exception to trigger when payload object is not fully filled.
 *
 * @deprecated 2.0.0
 */
class IncompletePayloadException extends BaseException {

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param string $class The name of the class.
	 * @param array  $nulls List empty properties.
	 * @param int    $code  [optional] The Exception code.
	 */
	public function __construct( $class, $nulls, $code = 0 ) {
		$message = sprintf(
			'Properties %s are not set in class %s.',
			implode( ', ', $nulls ),
			$class
		);
		parent::__construct( $message, $code );
	}
}
