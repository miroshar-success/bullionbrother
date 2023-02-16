<?php

namespace OM4\WooCommerceZapier\Auth;

use OM4\WooCommerceZapier\Auth\KeyDataStore;
use OM4\WooCommerceZapier\Logger;
use WP_Error;
use WP_User;

defined( 'ABSPATH' ) || exit;

/**
 * Allows the WooCommerce Zapier extension to provide "Session Authentication".
 *
 * Unfortunately oAuth 2.0 authentication isn't feasible without requiring an
 * oAuth server, so Session Authentication is the next-best option. A WordPress
 * username and password is used to create a WooCommerce Consumer Key and
 * Secret, and the Zapier.com WooCommerce App then uses the Consumer Key and
 * Secret for subsequent requests. To call this endpoint, send a POST request to
 * https://example.com/wc-zapier-auth/v1/authenticate/ with a JSON body
 * containing the username and password. Based on functionality from the
 * `WC_Auth` WooCommerce class.
 *
 * @since 2.0.0
 */
class SessionAuthenticate {

	/**
	 * KeyDataStore instance.
	 *
	 * @var KeyDataStore
	 */
	protected $data_store;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param KeyDataStore $data_store KeyDataStore instance.
	 * @param Logger       $logger Logger instance.
	 */
	public function __construct( KeyDataStore $data_store, Logger $logger ) {
		$this->data_store = $data_store;
		$this->logger     = $logger;
	}

	/**
	 * Initialise functionality by hooking into the relevant WordPress hooks/filters.
	 *
	 * @return void
	 */
	public function initialise() {
		// Add query vars.
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

		// Register auth endpoint.
		add_action( 'init', array( $this, 'add_endpoint' ) );

		// Handle auth requests.
		add_action( 'parse_request', array( $this, 'handle_auth_requests' ), 0 );

		// Flush rewrite rules during plugin activation and upgrade.
		add_action( 'wc_zapier_db_upgrade_v_3_to_4', array( $this, 'flush_rewrite' ) );
	}

	/**
	 * Add query vars to WordPress.
	 *
	 * @param array $vars Query variables.
	 *
	 * @return string[]
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'wc-zapier-auth-version';
		$vars[] = 'wc-zapier-auth-route';
		return $vars;
	}

	/**
	 * Add auth endpoint to WordPress.
	 *
	 * @return void
	 */
	public function add_endpoint() {
		add_rewrite_rule(
			'^wc-zapier-auth/v([1]{1})/(.*)?',
			'index.php?wc-zapier-auth-version=$matches[1]&wc-zapier-auth-route=$matches[2]',
			'top'
		);
	}

	/**
	 * Handle incoming auth requests, transforming incoming parameters
	 * into WordPress query vars so they can be processed.
	 *
	 * @return void
	 */
	public function handle_auth_requests() {
		global $wp;

		// Build `query_vars if WordPress permalink not set correctly
		// `nonce` not available for this requests.
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['wc-zapier-auth-version'] ) ) {
			$wp->query_vars['wc-zapier-auth-version'] = wc_clean( wp_unslash( $_GET['wc-zapier-auth-version'] ) );
		}
		if ( ! empty( $_GET['wc-zapier-auth-route'] ) ) {
			$wp->query_vars['wc-zapier-auth-route'] = wc_clean( wp_unslash( $_GET['wc-zapier-auth-route'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		// Do nothing if request is not ours.
		if (
			empty( $wp->query_vars['wc-zapier-auth-version'] ) ||
			empty( $wp->query_vars['wc-zapier-auth-route'] )
		) {
			return;
		}

		// Do nothing if request is not match.
		if (
			'1' !== $wp->query_vars['wc-zapier-auth-version'] ||
			'authenticate' !== $wp->query_vars['wc-zapier-auth-route']
		) {
			return;
		}

		// Handle Zapier Authentication endpoint requests.
		$this->authenticate();
	}

	/**
	 * Authentication endpoint.
	 *
	 * Verifies that the specified WordPress username and password is correct, and the
	 * corresponding user has permission to create WooCommerce API Keys.
	 *
	 * If successful, creates a new WooCommerce REST API key.
	 *
	 * @return void
	 */
	protected function authenticate() {

		$request = $this->validate_request();

		// Mimic a WordPress REST API request, in case other plugins perform redirects during the login/auth process.
		if ( ! defined( 'REST_REQUEST' ) ) {
			define( 'REST_REQUEST', true );
		}

		$user = $this->authenticate_user( $request['username'], $request['password'] );

		if ( is_wp_error( $user ) ) {
			// Authentication Failed. Log the failure reason, and respond with a generic invalid username/password message.
			$this->logger->notice( 'Authentication attempt failed for user: %s', $request['username'] );
			$this->logger->notice( 'Error Code: %s', (string) $user->get_error_code() );
			$this->logger->notice( 'Error Message: %s', $user->get_error_message() );

			// Respond with a HTTP 406 Error because the Zapier platform doesn't handle a HTTP 401 responses properly.
			$this->respond_auth_error(
				'wc_zapier_rest_cannot_authenticate',
				__( 'The username and/or password you supplied is incorrect.', 'woocommerce-zapier' ),
				406
			);
		}

		/**
		 * The user requires the following capabilities:
		 * - manage_woocommerce (to create/delete WooCommerce webhooks)
		 * - promote_users (to create WooCommerce customers)
		 * - edit_users (to update WooCommerce customers)
		 *
		 * Typically a WordPress Administrator role is required, because a Shop Manager role does not have permission to create customers.
		 */
		if ( ! $user->has_cap( 'manage_woocommerce' ) || ! $user->has_cap( 'promote_users' ) || ! $user->has_cap( 'edit_users' ) ) {
			$this->logger->notice( 'Authentication attempt failed for user: %s. Insufficient user permissions.', $user->user_login );
			$this->respond_auth_error(
				'wc_zapier_rest_cannot_authorize',
				__( 'This user does not have the correct permissions.', 'woocommerce-zapier' ),
				403
			);
		}
		$wc_key_array = $this->data_store->create( $user->ID );
		$this->respond_auth_data( $wc_key_array );
	}

	/**
	 * Authenticate a user, confirming the login credentials are valid.
	 *
	 * Attempts to authenticate using Application Password authentication if available,
	 * then falls back to normal WordPress auth.
	 *
	 * @param string $username Username or Email Address.
	 * @param string $password User password.
	 *
	 * @return WP_User|WP_Error WP_User object if the credentials are valid,
	 *                          otherwise WP_Error.
	 */
	protected function authenticate_user( $username, $password ) {
		if ( function_exists( 'wp_authenticate_application_password' ) ) {
			// The site supports Application Passwords.
			// Attempt authentication using the Application Password mechanism, in order to improve compatibility with many 2FA security plugins.
			$user = wp_authenticate_application_password( null, $username, $password );
			if ( ! is_null( $user ) && is_a( $user, WP_User::class ) ) {
				// Successful authentication using an Application Password.
				return $user;
			}
		}
		return wp_authenticate( $username, $password );
	}

	/**
	 * Validate an incoming Auth request.
	 *
	 * Reads WordPress username/password from the request body.
	 *
	 * @return array
	 */
	protected function validate_request() {
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			$method = isset( $_SERVER['REQUEST_METHOD'] ) ? wc_clean( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'UNAVAILABLE';
			$this->logger->notice( 'Authentication validation failed. Invalid Request Method: %s', $method );
			$this->respond_auth_error(
				'wc_zapier_method_not_allowed',
				__( '405 Method not Allowed', 'woocommerce-zapier' ),
				405
			);
		}

		if ( ! is_ssl() ) {
			$this->logger->notice( 'Authentication validation failed. Not SSL.' );
			$this->respond_auth_error(
				'wc_zapier_bad_request',
				__( '400 Bad Request', 'woocommerce-zapier' ),
				400
			);
		}

		$content = file_get_contents( 'php://input' );

		// NOTE: Not testable in properly configured environment.
		if ( false === $content ) {
			$this->logger->notice( 'Authentication validation failed. Error reading request body.' );
			$this->respond_auth_error(
				'wc_zapier_bad_request',
				__( '400 Bad Request', 'woocommerce-zapier' ),
				400
			);
		}

		$request = json_decode( $content, true );

		if ( JSON_ERROR_NONE !== json_last_error() ) {
			$this->logger->notice( 'Authentication validation failed. Malformed request body.' );
			$this->logger->notice( 'JSON Error: %s', json_last_error_msg() );
			$this->respond_auth_error(
				'wc_zapier_bad_request',
				__( '400 Bad Request', 'woocommerce-zapier' ),
				400
			);
		}

		if ( empty( $request['username'] ) || empty( $request['password'] ) ) {
			$this->logger->notice(
				'Authentication validation failed. Missing credentials. Username is %s, password is %s.',
				array( empty( $request['username'] ) ? 'missing' : 'set', empty( $request['password'] ) ? 'missing' : 'set' )
			);
			$this->respond_auth_error(
				'wc_zapier_bad_request',
				__( '400 Bad Request', 'woocommerce-zapier' ),
				400
			);
		}

		if ( ! is_string( $request['username'] ) || ! is_string( $request['password'] ) ) {
			$this->logger->notice(
				'Authentication validation failed. Wrong credentials. Username type is %s, password type is %s.',
				array( gettype( $request['username'] ), gettype( $request['password'] ) )
			);
			$this->respond_auth_error(
				'wc_zapier_bad_request',
				__( '400 Bad Request', 'woocommerce-zapier' ),
				400
			);
		}

		return $this->read_credentials_from_request( $request );
	}

	/**
	 * Read the user's credentials from the input.
	 *
	 * @param string[] $request Array containing the user's credentials.
	 *
	 * @return array Array containing the username and password
	 */
	protected function read_credentials_from_request( $request ) {
		return array(
			'username' => sanitize_user( trim( wp_unslash( $request['username'] ) ) ),
			// WordPress expects a slashed password as per https://core.trac.wordpress.org/ticket/34297.
			'password' => wp_slash( $request['password'] ),
		);
	}

	/**
	 * Respond with error.
	 *
	 * @param string  $internal_code Error code.
	 * @param string  $response      Error message, pass `__()` output here.
	 * @param integer $status_code   HTTP status code.
	 *
	 * @return void
	 */
	protected function respond_auth_error( $internal_code, $response, $status_code ) {
		$this->wp_send_json(
			array(
				'code'    => $internal_code,
				'message' => $response,
				'data'    => array( 'status' => $status_code ),
			),
			$status_code
		);
	}

	/**
	 * Respond with the specified API key details.
	 *
	 * @param array $wc_key_array WC_Auth::create_key method output.
	 *
	 * @return void
	 */
	protected function respond_auth_data( $wc_key_array ) {
		$this->wp_send_json(
			array(
				'consumer_key'    => $wc_key_array['consumer_key'],
				'consumer_secret' => $wc_key_array['consumer_secret'],
			),
			200
		);
	}

	/**
	 * Flush WordPress' internal rewrite rules so that the auth endpoint works.
	 *
	 * @return void
	 */
	public function flush_rewrite() {
		flush_rewrite_rules();
	}

	/**
	 * Send our JSON response.
	 *
	 * WordPress 5.5 and above triggers a _doing_it_wrong() warning when we use wp_send_json()
	 * because REST_REQUEST is defined as true, so instead implement our own version of it.
	 *
	 * @see Issue #330
	 *
	 * @param array $response Data to encode as JSON and send.
	 * @param int   $status_code HTTP response code.
	 *
	 * @return void
	 */
	protected function wp_send_json( $response, $status_code = 200 ) {
		if ( ! headers_sent() ) {
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
			status_header( $status_code );
		}
		if ( false !== wp_json_encode( $response ) ) {
			echo wp_json_encode( $response );
		}
		die;
	}
}
