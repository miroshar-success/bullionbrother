<?php

namespace OM4\Zapier\Exception;

use OM4\WooCommerceZapier\Exception\BaseException;

defined( 'ABSPATH' ) || exit;

/**
* Exception to trigger when violation of immutable behavior occurs.
 *
 * @deprecated 2.0.0
 */
final class JsonErrorException extends BaseException {

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param string $class      The name of the class.
	 * @param int    $last_error JSON last error.
	 * @param int    $code       [optional] The Exception code.
	 */
	public function __construct( $class, $last_error, $code = 0 ) {

		switch ( $last_error ) {
			case constant( 'JSON_ERROR_NONE' ):
				$error_text = 'No error has occurred.';
				break;
			case constant( 'JSON_ERROR_DEPTH' ):
				$error_text = 'Maximum stack depth exceeded.';
				break;
			case constant( 'JSON_ERROR_STATE_MISMATCH' ):
				$error_text = 'Invalid or malformed JSON.';
				break;
			case constant( 'JSON_ERROR_CTRL_CHAR' ):
				$error_text = 'Control character error, possibly incorrectly encoded.';
				break;
			case constant( 'JSON_ERROR_SYNTAX' ):
				$error_text = 'Syntax error.';
				break;
			case constant( 'JSON_ERROR_UTF8' ):
				$error_text = 'Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
			case constant( 'JSON_ERROR_RECURSION' ):
				$error_text = 'One or more recursive references in the value to be encoded.';
				break;
			case constant( 'JSON_ERROR_INF_OR_NAN' ):
				$error_text = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case constant( 'JSON_ERROR_UNSUPPORTED_TYPE' ):
				$error_text = 'A value of a type that cannot be encoded was given.';
				break;
			case constant( 'JSON_ERROR_INVALID_PROPERTY_NAME' ):
				$error_text = 'A property name that cannot be encoded was given.';
				break;
			case constant( 'JSON_ERROR_UTF16' ):
				$error_text = 'Malformed UTF-16 characters, possibly incorrectly encoded.';
				break;
			default:
				$error_text = 'Unknown error';
				break;
		}

		$message = sprintf(
			'Could not create JSON from class %s. %s ',
			$class,
			$error_text
		);
		parent::__construct( $message, $code );
	}
}
