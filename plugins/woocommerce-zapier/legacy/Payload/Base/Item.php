<?php

namespace OM4\Zapier\Payload\Base;

use ArrayIterator;
use Iterator;
use OM4\Zapier\Exception\InvalidPropertyException;
use OM4\Zapier\Exception\InvalidTypeException;
use OM4\Zapier\Exception\MissingDataException;
use OM4\Zapier\Exception\MissingSampleException;
use OM4\Zapier\Payload\Base\Base;
use OM4\Zapier\Payload\Base\Collection;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Traversable;

defined( 'ABSPATH' ) || exit;

/**
 * Implement base structure requirements for Payload classes.
 *
 * @deprecated 2.0.0
 */
abstract class Item extends Base {

	/**
	 * Signalling this class is structured or not
	 *
	 * @var bool
	 */
	protected static $is_structured = true;

	/**
	 * Holds the type information for validate
	 *
	 * @var array
	 */
	protected static $property_types = array();

	/**
	 * Fill the Object in one step if an appropriate array available.
	 *
	 * @param   array|Iterator $data Data to fill properties at once.
	 * @return  static
	 */
	final public static function from_data( $data ) {
		$item = new static();
		foreach ( $data as $key => $value ) {
			$type = $item->get_type( $key );
			try {
				$reflector = new ReflectionClass( $type );
				$is_class  = $reflector->IsInstantiable();
			} catch ( ReflectionException $e ) {
				$is_class = false;
			}
			if ( $is_class ) {
				$item->__set( $key, $type::from_data( $value ) );
			} else {
				$item->__set( $key, $value );
			}
		}
		return $item;
	}

	/**
	 * Fill this object in one step from sample data.
	 *
	 * @throws  MissingDataException Validation is missing for an accessed property.
	 * @return  static
	 */
	final public static function from_sample() {
		$payload = static::from_data(
			static::get_sample_from_json( get_called_class() )
		);

		foreach ( $payload->list_unfilled_properties() as $property ) {
			throw new MissingDataException( get_called_class(), $property, 'sample' );
		}

		return $payload;
	}

	/**
	 * Get sample from json file
	 *
	 * @param   string $class  Class name which loading a sample for.
	 * @throws  MissingSampleException Sample is missing.
	 * @return  array
	 */
	final protected static function get_sample_from_json( $class ) {
		$prefix   = 'OM4\\Zapier\\Payload';
		$length   = strlen( $prefix );
		$relative = substr( $class, $length );
		$base_dir = dirname( WC_ZAPIER_PLUGIN_FILE ) . '/sample/';
		$file     = $base_dir . str_replace( '\\', '/', $relative ) . '.json';
		if ( ! file_exists( $file ) ) {
			throw new MissingSampleException( get_called_class() );
		}
		$json = file_get_contents( $file );

		if ( ! $json ) {
			return array();
		}

		$data = json_decode( $json, true );

		array_walk(
			$data,
			$date_function = function( &$value, $key ) use ( &$date_function ) {
				$position = strpos( $key, 'date' );
				if ( false !== $position ) {
					$value = gmdate( 'c', time() + (int) $value[1] * 60 * 60 * 24 );
				} elseif ( is_array( $value ) ) {
					array_walk( $value, $date_function );
				}
			}
		);

		return $data;
	}

	/**
	 * Sanitize & validate properties to proper types to use in a Payload.
	 *
	 * @param   string $key              Name of property to validate.
	 * @param   mixed  $value            The property to validate.
	 * @throws  InvalidPropertyException Accessing non-existent property.
	 * @throws  InvalidTypeException     Type not allowed for accessed property.
	 */
	final protected function validate( $key, $value ) {
		// Check if property is exists (for structured items).
		if ( static::$is_structured && ! property_exists( $this, $key )
		) {
			throw new InvalidPropertyException( get_called_class(), $key );
		}

		$type = $this->get_type( $key );

		// Check if value is in allowed type.
		if (
			( is_scalar( $value ) xor $value instanceof \OM4\Zapier\Payload\Base\Base ) &&
			$this->equal_type( $value, $type )
		) {
			return;
		}

		// Everything else throws an error.
		throw new InvalidTypeException( get_called_class(), $key, $type, $value );
	}

	/**
	 * Get the type from a key
	 *
	 * @param   string $key Name of property to get type from.
	 * @throws  MissingDataException Validation is missing for an accessed property.
	 * @return  string
	 */
	final public function get_type( $key ) {
		if ( ! static::$is_structured ) {
			return 'scalar';
		}
		if ( ! isset( static::$property_types[ $key ] ) ) {
			throw new MissingDataException( get_called_class(), $key, 'validation' );
		}
		return static::$property_types[ $key ];
	}

	/**
	 * Check if value is the expected type,
	 * supports union type with the `|` character.
	 *
	 * @param mixed  $value The property to validate.
	 * @param string $type  The expected type.
	 *
	 * @return  bool
	 */
	final protected function equal_type( $value, $type ) {
		// Allow any scalar for unstructured.
		if ( 'scalar' === $type && null !== $value && is_scalar( $value ) ) {
			return true;
		}

		// Allow union type, see issue #188 & #233.
		if ( false !== strpos( $type, '|' ) ) {
			$is_equal = false;
			foreach ( explode( '|', $type ) as $subtype ) {
				$is_equal = $is_equal || $this->equal_type( $value, $subtype );
			}
			return $is_equal;
		}

		// Check scalar and object matches.
		return $value instanceof $type || false !== stripos( gettype( $value ), $type );
	}

	/**
	 * Run when checking the protected properties.
	 *
	 * @param   string $key Name of the property.
	 * @return  bool        True if property exist, false otherwise.
	 */
	final public function __isset( $key ) {
		return isset( $this->$key );
	}

	/**
	 * Run when reading from protected properties.
	 *
	 * @param   string $key             Name of the property.
	 * @throws InvalidPropertyException Key unavailable.
	 * @return  mixed                   The value of the property.
	 */
	final public function __get( $key ) {
		if ( ! property_exists( $this, $key ) ) {
			throw new InvalidPropertyException( get_called_class(), $key );
		}
		return $this->$key;
	}

	/**
	 * Run when writing to protected properties.
	 *
	 * @param   string $key   Name of the property.
	 * @param   mixed  $value The value of the property.
	 */
	final public function __set( $key, $value ) {
		$this->validate( $key, $value );
		$this->$key = $value;
	}

	/**
	 * Run when un-setting protected properties.
	 *
	 * @param   string $key Name of the property.
	 */
	final public function __unset( $key ) {
		unset( $this->$key );
	}

	/**
	 * Get an array of properties that used in output.
	 *
	 * @return  array Array of properties prepared for output.
	 */
	final protected function get_properties() {
		return get_object_vars( $this );
	}
}
