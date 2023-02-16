<?php

namespace OM4\Zapier\Exception;

use OM4\WooCommerceZapier\Exception\BaseException;

defined( 'ABSPATH' ) || exit;

/**
 * Exception to trigger when invalid type is used.+
 *
 * @deprecated 2.0.0
 */
class InvalidTypeException extends BaseException {

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param string     $class    The name of the class.
	 * @param int|string $key      The name of the property.
	 * @param string     $expected The expected type.
	 * @param mixed      $value    The actual content of the property.
	 * @param int        $code     [optional] The Exception code.
	 */
	public function __construct( $class, $key, $expected, $value, $code = 0 ) {
		$message = sprintf(
			'Invalid type in %s for %s: Expected %s, actual: %s%s.',
			$class,
			$key,
			$expected,
			is_object( $value ) ? get_class( $value ) : gettype( $value ),
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
			is_object( $value ) ? '' : ', value: ' . var_export( $value, true )
		);
		parent::__construct( $message, $code );
	}
}
