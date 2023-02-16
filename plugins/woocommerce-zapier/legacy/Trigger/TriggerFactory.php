<?php

namespace OM4\Zapier\Trigger;

use Exception;
use OM4\Zapier\Plugin;
use OM4\Zapier\Plugin\Subscriptions;
use OM4\Zapier\Trigger\Base;
use ReflectionClass;
use ReflectionException;

defined( 'ABSPATH' ) || exit;

/**
 * This class is responsible for instantiating and storing OM4\Zapier\Trigger\Base objects.
 *
 * @deprecated 2.0.0
 */
class TriggerFactory {

	/**
	 * List of supported Zapier Triggers
	 *
	 * @var array
	 */
	public static $triggers = array();

	/**
	 * Load available Trigger definitions.
	 *
	 * @throws ReflectionException If a trigger class does not exist.
	 */
	public static function load_triggers() {
		if ( empty( self::$triggers ) ) {
			// Initialise our triggers.
			$directories = array(
				'OM4\\Zapier\\Trigger\\Customer\\' => Plugin::$plugin_dir . 'legacy/Trigger/Customer',
				'OM4\\Zapier\\Trigger\\Order\\'    => Plugin::$plugin_dir . 'legacy/Trigger/Order',
			);
			if ( Subscriptions::is_available() ) {
				$directories['OM4\\Zapier\\Trigger\\Subscription\\'] = Plugin::$plugin_dir . 'legacy/Trigger/Subscription';
			}

			/**
			 * Override the directories where WC Zapier Triggers PHP files are loaded from.
			 *
			 * @since 1.6.0
			 * @deprecated 2.0.0 Legacy Zapier Feed Triggers should be replaced with WooCommerce REST API Webhook Topics.
			 *
			 * @param array $directories The array of directories to scan for PHP files.
			 */
			$directories = apply_filters_deprecated( 'wc_zapier_trigger_directories', array( $directories ), '2.0.0', false, 'Legacy Zapier Feed Triggers should be replaced with WooCommerce REST API Webhook Topics.' );

			foreach ( $directories as $prefix => $directory ) {
				foreach ( scandir( $directory ) as $trigger_file ) {
					// Only take into account PHP files (and not directories).
					if ( strpos( $trigger_file, '.php' ) !== false ) {
						$prefix = ! is_string( $prefix ) ? '' : $prefix;
						// Empty the prefix for backward compatibility.
						$class_name = $prefix . str_replace( '.php', '', $trigger_file );

						// Don't instantiate/initialise classes that are abstract.
						$reflector = new ReflectionClass( $class_name );
						if ( ! $reflector->IsInstantiable() ) {
							continue;
						}

						$trigger                                       = new $class_name();
						self::$triggers[ $trigger->get_trigger_key() ] = $trigger;
					}
				}
			}
		}
	}

	/**
	 * Obtain the OM4\Zapier\Trigger\Base class that corresponds to the specified trigger key
	 *
	 * @param string $trigger_key The key of the Trigger.
	 *
	 * @throws Exception In case trigger not found.
	 * @return Base
	 */
	public static function get_trigger_with_key( $trigger_key ) {
		if ( isset( self::$triggers[ $trigger_key ] ) ) {
			return self::$triggers[ $trigger_key ];
		} else {
			throw new Exception( "Trigger not found with key: $trigger_key" );
		}
	}

	/**
	 * Whether or not a trigger exists with the specified key.
	 *
	 * @param string $trigger_key The key of the Trigger.
	 *
	 * @return bool
	 */
	public static function trigger_exists( $trigger_key ) {
		try {
			$trigger_key = trim( (string) $trigger_key );
			if ( empty( $trigger_key ) ) {
				return false;
			}
			self::get_trigger_with_key( $trigger_key );
			return true;
		} catch ( Exception $ex ) {
			return false;
		}
		return false;
	}

	/**
	 * Return an array of supported triggers and title/description.
	 * Sorted in the correct sort order.
	 *
	 * @return array
	 */
	public static function get_triggers_for_display() {
		$triggers = array();
		foreach ( self::get_triggers_sorted() as $trigger ) {
			$triggers[ $trigger->get_trigger_key() ] = $trigger->get_trigger_title();
		}
		return $triggers;
	}

	/**
	 * Obtain a list of registered Triggers, sorted by the Base::sort_order property.
	 *
	 * @return Base[] array of OM4\Zapier\Trigger\Base objects.
	 */
	public static function get_triggers_sorted() {
		$triggers = array();
		foreach ( self::$triggers as $trigger ) {
			$triggers[ $trigger->sort_order ] = $trigger;
		}
		ksort( $triggers, SORT_NUMERIC );
		return $triggers;
	}

	/**
	 * Obtain a list of trigger keys for all triggers.
	 *
	 * @return array
	 */
	public static function get_trigger_keys() {
		$triggers = array();
		foreach ( self::get_triggers_sorted() as $trigger ) {
			$triggers[] = $trigger->get_trigger_key();
		}
		return $triggers;
	}

	/**
	 * Obtain the name/title of a trigger based on it's internal key/slug.
	 *
	 * @param string $trigger_key The key of the Trigger.
	 *
	 * @return string
	 */
	public static function get_trigger_name( $trigger_key ) {
		return isset( self::$triggers[ $trigger_key ] ) ? self::$triggers[ $trigger_key ]->get_trigger_title() : '';
	}
}
