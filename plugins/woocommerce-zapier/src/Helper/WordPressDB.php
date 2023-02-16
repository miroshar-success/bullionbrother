<?php

namespace OM4\WooCommerceZapier\Helper;

defined( 'ABSPATH' ) || exit;

use wpdb;

/**
 * Helps accessing the global $wpdb class.
 *
 * @method string                 esc_like( string $text )
 * @method string                 get_charset_collate()
 * @method array                  get_col( string|null $query = null, int $x = 0 )
 * @method array|object|null      get_results( string $query = null, string $output = OBJECT )
 * @method array|object|null|void get_row( string|null $query = null, string $output = OBJECT, int $y = 0 )
 * @method string|null            get_var( string|null $query = null, int $x = 0, int $y = 0 )
 * @method int|false              has_cap( string $db_cap )
 * @method int|false              insert( string $table, array $data, array|string $format = null )
 * @method string|void            prepare( string $query, array|mixed ...$args )
 * @method int|bool               query( string $query )
 * @method int|false              update( string $table, array $data, array $where, array|string $format = null, array|string $where_format = null )
 * @method int|false              delete( string $table, array $where, array|string $where_format = null )
 * @property int $insert_id
 * @property string $posts
 * @property string $prefix
 * @since 2.0.0
 */
class WordPressDB {

	/**
	 * WordPress Database (wpdb) instance.
	 *
	 * @var wpdb
	 */
	protected $wpdb;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Pass every call to wpdb
	 *
	 * @param string $name      Method Name.
	 * @param array  $arguments Arguments for the method.
	 *
	 * @return mixed
	 */
	public function __call( $name, array $arguments ) {
		return $this->wpdb->$name( ...$arguments );
	}

	/**
	 * Get a property from wpdb
	 *
	 * @param string $name property name.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->wpdb->$name;
	}

	/**
	 * Set s wpdb property
	 *
	 * @param string $name  property name.
	 * @param mixed  $value Value to set.
	 *
	 * @return void
	 */
	public function __set( $name, $value ) {
		$this->wpdb->$name = $value;
	}

	/**
	 * Unset a wpdb property
	 *
	 * @param string $name Name of the property.
	 *
	 * @return void
	 */
	public function __unset( $name ) {
		unset( $this->wpdb->$name );
	}

	/**
	 * Checks for a wpdb property
	 *
	 * @param string $name Name of the property.
	 *
	 * @return boolean
	 */
	public function __isset( $name ) {
		return isset( $this->wpdb->$name );
	}
}
