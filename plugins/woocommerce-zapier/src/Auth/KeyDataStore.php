<?php

namespace OM4\WooCommerceZapier\Auth;

use OM4\WooCommerceZapier\Helper\WordPressDB;
use ReflectionMethod;
use stdClass;
use WC_Auth;

defined( 'ABSPATH' ) || exit;

/**
 * Auth Key Data Store
 *
 * Responsible for creating, reading and deleting (revoking) Zapier-specific WooCommerce REST API keys.
 *
 * @since 2.0.0
 */
class KeyDataStore {

	/**
	 * Authentication App name
	 * (shown on the WooCommerce/Settings/Advanced/REST API page).
	 *
	 * @var string
	 */
	protected $app_name = 'Zapier';

	/**
	 * Authentication scope.
	 *
	 * Read/Write access is required so that the Zapier.com WooCommerce app can
	 * read data during Triggers, and change data during actions.
	 *
	 * @var string
	 */
	protected $scope = 'read_write';

	/**
	 * Database table name where WooCommerce (Zapier) Authentication Keys are stored.
	 *
	 * @var string
	 */
	protected $db_table_name;

	/**
	 * WordPressDB instance.
	 *
	 * @var WordPressDB
	 */
	protected $wp_db;

	/**
	 * WC_Auth instance.
	 *
	 * @var WC_Auth|null
	 */
	protected $wc_auth;

	/**
	 * Constructor
	 *
	 * @param WordPressDB $wpdb WordPressDB instance.
	 */
	public function __construct( WordPressDB $wpdb ) {
		$this->wp_db         = $wpdb;
		$this->db_table_name = $this->wp_db->prefix . 'woocommerce_api_keys';
	}

	/**
	 * Get the existing WooCommerce Zapier API key(s) for the specified user.
	 *
	 * The most recently used keys are listed first.
	 *
	 * @param integer $user_id WordPress User ID.
	 *
	 * @return stdClass[]|null Array of key details, or null if no keys found.
	 */
	public function get_existing_keys( $user_id ) {
		$result = $this->wp_db->get_results(
			strval(
				$this->wp_db->prepare(
					"SELECT * FROM {$this->db_table_name} WHERE user_id = %d AND description LIKE %s ORDER BY last_access DESC",
					$user_id,
					// Description begins with Zapier.
					'%' . $this->wp_db->esc_like( $this->app_name ) . '%'
				)
			)
		);
		if ( ! is_array( $result ) ) {
			return array();
		}
		return $result;
	}

	/**
	 * Get the number of existing WooCommerce Zapier API Keys for each user ID.
	 *
	 * @return stdClass[]
	 */
	public function get_key_user_counts() {
		$counts = $this->wp_db->get_results(
			strval(
				$this->wp_db->prepare(
					"SELECT user_id, count(key_id) as num_keys FROM {$this->db_table_name} WHERE description LIKE %s GROUP by user_id",
					// Description begins with Zapier.
					'%' . $this->wp_db->esc_like( $this->app_name ) . '%'
				)
			)
		);
		if ( ! is_array( $counts ) ) {
			return array();
		}
		return $counts;
	}

	/**
	 * Get the number of existing WooCommerce Zapier API Keys.
	 *
	 * @return integer
	 */
	public function count() {
		$table = $this->wp_db->prefix . 'woocommerce_api_keys';
		$count = $this->wp_db->get_var(
			strval(
				$this->wp_db->prepare(
					"SELECT count(*) FROM $table WHERE description LIKE %s",
					'%' . $this->wp_db->esc_like( $this->app_name ) . '%'
				)
			)
		);
		return absint( $count );
	}

	/**
	 * Invoke WcAuth to delete a WooCommerce API key.
	 *
	 * Reflection is necessary because the WC_AUth::maybe_delete_key() function is private.
	 *
	 * @param integer $wc_key_id API Key ID.
	 *
	 * @return void
	 */
	public function delete( $wc_key_id ) {
		$method = $this->get_wc_auth_method( 'maybe_delete_key' );
		$method->invoke( $this->wc_auth, array( 'key_id' => (int) $wc_key_id ) );
	}

	/**
	 * Invoke WcAuth to create a WooCommerce API key.
	 *
	 * Reflection is necessary because the WC_AUth::create_keys() function is protected.
	 *
	 * @param integer $user_id User ID.
	 *
	 * @return array
	 */
	public function create( $user_id ) {
		wp_set_current_user( $user_id );
		$method = $this->get_wc_auth_method( 'create_keys' );
		return $method->invoke( $this->wc_auth, $this->app_name, (string) $user_id, $this->scope );
	}

	/**
	 * Get the desired method from The WC_Auth class as publicly accessible
	 * Also caches the WC_Auth class for subsequent usage.
	 *
	 * @param string $method Class method name.
	 *
	 * @return ReflectionMethod
	 */
	protected function get_wc_auth_method( $method ) {
		$this->wc_auth = is_null( $this->wc_auth ) ? new WC_Auth() : $this->wc_auth;
		$method        = new ReflectionMethod( $this->wc_auth, $method );
		$method->setAccessible( true );
		return $method;
	}
}
