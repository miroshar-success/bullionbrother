<?php

namespace OM4\Zapier\Exception;

use OM4\WooCommerceZapier\Exception\BaseException;

defined( 'ABSPATH' ) || exit;

/**
 * Exception to trigger when not implemented method is used.
 *
 * @deprecated 2.0.0
 */
class InvalidMethodException extends BaseException {

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param string $class  The name of the class.
	 * @param mixed  $method The name of the method.
	 * @param int    $code   [optional] The Exception code.
	 */
	public function __construct( $class, $method, $code = 0 ) {
		$message = sprintf(
			'Method %s not implemented in class %s.',
			$method,
			$class
		);
		parent::__construct( $message, $code );
	}
}
