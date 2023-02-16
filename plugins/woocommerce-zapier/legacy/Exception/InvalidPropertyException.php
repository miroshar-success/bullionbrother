<?php

namespace OM4\Zapier\Exception;

use OM4\WooCommerceZapier\Exception\BaseException;

defined( 'ABSPATH' ) || exit;

/**
 * Exception to trigger when non existent property is used.
 *
 * @deprecated 2.0.0
 */
class InvalidPropertyException extends BaseException {

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param string $class    The name of the class.
	 * @param mixed  $property The accessed property.
	 * @param int    $code     [optional] The Exception code.
	 */
	public function __construct( $class, $property, $code = 0 ) {
		$message = sprintf(
			'Property %s not exist in class %s.',
			$property,
			$class
		);
		parent::__construct( $message, $code );
	}
}
