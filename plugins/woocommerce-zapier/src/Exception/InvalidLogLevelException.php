<?php

namespace OM4\WooCommerceZapier\Exception;

use OM4\WooCommerceZapier\Exception\BaseException;

defined( 'ABSPATH' ) || exit;

/**
 * Exception to trigger when invalid type is used.
 */
class InvalidLogLevelException extends BaseException {

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param string $log_level Called log level.
	 * @param string $message   Original message to log.
	 * @param int    $code      [optional] The Exception code.
	 */
	public function __construct( $log_level, $message, $code = 0 ) {
		$message = sprintf(
			'Log level %s is invalid. Original message: %s.',
			$log_level,
			$message
		);
		parent::__construct( $message, $code );
	}
}
