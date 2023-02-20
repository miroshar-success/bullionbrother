<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Authnet_API class.
 *
 * Communicates with Authorize.Net API.
 */
class WC_Authnet_API {

	private static $login_id = '';
	private static $transaction_key = '';
	private static $free_api_method = 'aim';
	private static $testmode;
	private static $logging;
	private static $debugging;
	private static $statement_descriptor;

	const LIVE_URL = 'https://api.authorize.net/xml/v1/request.api';
	const SANDBOX_URL = 'https://apitest.authorize.net/xml/v1/request.api';

	/**
	 * Set API Login ID.
	 *
	 * @param string $login_id
	 */
	public static function set_login_id( $login_id ) {
		self::$login_id = $login_id;
	}

	/**
	 * Set Transaction Key.
	 *
	 * @param string $transaction_key
	 */
	public static function set_transaction_key( $transaction_key ) {
		self::$transaction_key = $transaction_key;
	}

	/**
	 * Set free API method.
	 *
	 * @param string $free_api_method
	 */
	public static function set_free_api_method( $free_api_method ) {
		self::$free_api_method = $free_api_method;
	}

	public static function set_testmode( $testmode ) {
		self::$testmode = $testmode;
	}

	public static function set_logging( $logging ) {
		self::$logging = $logging;
	}

	public static function set_debugging( $debugging ) {
		self::$debugging = $debugging;
	}

	public static function set_statement_descriptor( $statement_descriptor ) {
		self::$statement_descriptor = $statement_descriptor;
	}

	/**
	 * Get API Login ID
	 * @return string
	 */
	public static function get_login_id() {
		if ( ! self::$login_id ) {
			$options = get_option( 'woocommerce_authnet_settings' );

			if ( isset( $options['login_id'] ) ) {
				self::set_login_id( $options['login_id'] );
			}
		}

		return self::$login_id;
	}

	/**
	 * Get Transaction Key.
	 * @return string
	 */
	public static function get_transaction_key() {
		if ( ! self::$transaction_key ) {
			$options = get_option( 'woocommerce_authnet_settings' );

			if ( isset( $options['transaction_key'] ) ) {
				self::set_transaction_key( $options['transaction_key'] );
			}
		}

		return self::$transaction_key;
	}

	/**
	 * Get free API method.
	 * @return string
	 */
	public static function get_free_api_method() {
		$options = get_option( 'woocommerce_authnet_settings' );

		if ( isset( $options['free_api_method'] ) ) {
			self::set_free_api_method( $options['free_api_method'] );
		} else {
			self::set_free_api_method( 'aim' );
		}

		return self::$free_api_method;
	}

	public static function is_testmode() {
		if ( ! is_bool( self::$testmode ) ) {
			$options = get_option( 'woocommerce_authnet_settings' );

			if ( isset( $options['testmode'] ) ) {
				self::set_testmode( $options['testmode'] === 'yes' );
			}
		}

		return self::$testmode;
	}

	public static function is_logging() {
		if ( ! is_bool( self::$logging ) ) {
			$options = get_option( 'woocommerce_authnet_settings' );

			if ( isset( $options['logging'] ) ) {
				self::set_logging( $options['logging'] === 'yes' );
			}
		}

		return self::$logging;
	}

	public static function is_debugging() {
		if ( ! is_bool( self::$debugging ) ) {
			$options = get_option( 'woocommerce_authnet_settings' );

			if ( isset( $options['debugging'] ) ) {
				self::set_debugging( $options['debugging'] === 'yes' );
			}
		}

		return self::$debugging;
	}

	public static function get_statement_descriptor() {
		if ( ! self::$statement_descriptor ) {
			$options = get_option( 'woocommerce_authnet_settings' );

			if ( isset( $options['statement_descriptor'] ) ) {
				self::set_statement_descriptor( $options['statement_descriptor'] );
			} else {
				self::set_statement_descriptor( wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
			}
		}

		return self::$statement_descriptor;
	}

	public static function execute( $request_method, $payment_args = array() ) {

		$request_url = self::is_testmode() ? self::SANDBOX_URL : self::LIVE_URL;
		$request_url = apply_filters( 'wc_authnet_request_url', $request_url );

		$auth_params = array(
			'merchantAuthentication' => array(
				'name'           => self::get_login_id(),
				'transactionKey' => self::get_transaction_key(),
			),
		);
		$auth_params = apply_filters( 'wc_authnet_api_keys', $auth_params );

		$request_args = array(
			$request_method => array_merge( $auth_params, $payment_args ),
		);

		// Setting custom timeout for the HTTP request
		add_filter( 'http_request_timeout', array( 'WC_Authnet_API', 'http_request_timeout' ), 9999 );

		$args = array(
			'headers' => array(	'Content-Type' => 'application/json' ),
			'body' 	  => json_encode( $request_args ),
		);
		$response = wp_remote_post( $request_url, $args );

		$response = preg_replace( '/[\x00-\x1F\x80-\xFF]/', '', wp_remote_retrieve_body( $response ) );
		$result   = is_wp_error( $response ) ? $response : json_decode( $response, true );

		$gateway_debug = ( self::is_logging() && self::is_debugging() );

		// Saving to Log here
		if ( $gateway_debug ) {
			$message = sprintf( "\nPosting to: \n%s\nRequest: \n%s\nResponse: \n%s", $request_url, print_r( $request_args, 1 ), print_r( $result, 1 ) );
			self::log( $message );
		}

		remove_filter( 'http_request_timeout', array( 'WC_Authnet_API', 'http_request_timeout' ), 9999 );

		if ( $result == null ) {
			return new WP_Error( 'cannot_connect', __( 'Unable to process request.', 'wc-authnet' ) );
		}

		if ( $result['messages']['resultCode'] == "Ok" ) {
			if ( ! empty( $result['transactionResponse']['errors'] ) ) {
				$error_messages = $result['transactionResponse']['errors'];
				return new WP_Error( $error_messages[0]['errorCode'], $error_messages[0]['errorText'], $result['transactionResponse'] );
			}
			self::log( 'Request was successful.' );
		} else {
			$error_messages = $result['messages']['message'];
			self::log( 'Error: Request Failed. ' . $error_messages[0]['code'] . ' - ' . $error_messages[0]['text'] );
			return new WP_Error( $error_messages[0]['code'], $error_messages[0]['text'] );
		}

		return $result;

	}

	/**
	 * Logs
	 *
	 * @since 6.0.0
	 * @version 6.0.0
	 *
	 * @param string $message
	 */
	public static function log( $message ) {
		if ( self::is_logging() ) {
			WC_Authnet_Logger::log( $message );
		}
	}

	public static function http_request_timeout( $timeout_value ) {
		return 45; // 45 seconds. Too much for production, only for testing.
	}

}
