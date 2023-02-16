<?php

namespace OM4\Zapier\Payload\Base;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;
use OM4\Zapier\Exception\InvalidPropertyException;
use OM4\Zapier\Exception\InvalidTypeException;
use OM4\Zapier\Payload\Base\Base;
use Traversable;

defined( 'ABSPATH' ) || exit;

/**
 * Holds an array of Payload Item classes.
 *
 * @deprecated 2.0.0
 */
abstract class Collection extends Base implements ArrayAccess, Countable, IteratorAggregate {

	/**
	 * Holds the arrays of Base objects
	 *
	 * @var  Base[]
	 */
	protected $items = array();

	/**
	 * Holds the name of the Item Class what allowed in store this Collection
	 *
	 * @var  string
	 */
	protected static $item_type = '\\OM4\\Zapier\\Payload\\Base\\Base';

	/**
	 * Fill this object in one step if an appropriate array available.
	 *
	 * @param   array|Iterator $data Data to fill properties at once.
	 * @return  static
	 */
	#[\ReturnTypeWillChange]
	final public static function from_data( $data ) {
		$collection = new static();
		foreach ( $data as $key => $value ) {
			$class              = static::$item_type;
			$collection[ $key ] = $class::from_data( $value );
		}
		return $collection;
	}

	/**
	 * Fill this object in one step from sample data.
	 *
	 * @return  static
	 */
	final public static function from_sample() {
		$collection   = new static();
		$class        = static::$item_type;
		$collection[] = $class::from_sample();
		return $collection;
	}

	/**
	 * Sanitize & validate properties to proper types to use in a Payload.
	 *
	 * @param   int|string $key      Name of property to validate.
	 * @param   mixed      $value    The property to validate.
	 * @throws  InvalidTypeException Type not allowed for accessed property.
	 */
	final protected function validate( $key, $value ) {
		if ( ! $value instanceof static::$item_type ) {
			throw new InvalidTypeException( get_called_class(), $key, static::$item_type, $value );
		}
	}

	/**
	 * Run when checking the protected properties.
	 *
	 * @param   string $key             Name of the property.
	 * @throws InvalidPropertyException Key unavailable.
	 */
	final public function __isset( $key ) {
		throw new InvalidPropertyException( get_called_class(), $key );
	}

	/**
	 * Run when reading data from protected properties.
	 *
	 * @param   string $key             Name of the property.
	 * @throws InvalidPropertyException Key unavailable.
	 */
	final public function __get( $key ) {
		throw new InvalidPropertyException( get_called_class(), $key );
	}

	/**
	 * Run when writing data to protected properties.
	 *
	 * @param   string $key   Name of the property.
	 * @param   mixed  $value The value of the property.
	 * @throws InvalidPropertyException Key unavailable.
	 */
	final public function __set( $key, $value ) {
		throw new InvalidPropertyException( get_called_class(), $key );
	}

	/**
	 * Run when un-setting protected properties.
	 *
	 * @param   string $key Name of the property.
	 * @throws InvalidPropertyException Key unavailable.
	 */
	final public function __unset( $key ) {
		throw new InvalidPropertyException( get_called_class(), $key );
	}

	/**
	 * Whether an offset exists. Part of The ArrayAccess interface.
	 *
	 * @param   int|string $key  Name of the property.
	 * @return  bool             True if property exist, false otherwise.
	 */
	#[\ReturnTypeWillChange]
	final public function offsetExists( $key ) {
		return isset( $this->items[ $key ] );
	}

	/**
	 * Offset to retrieve. Part of The ArrayAccess interface.
	 *
	 * @param   int|string $key         Name of the property.
	 * @throws InvalidPropertyException Key unavailable.
	 * @return  mixed                   The value of the property.
	 */
	#[\ReturnTypeWillChange]
	final public function offsetGet( $key ) {
		if ( ! $this->offsetExists( $key ) ) {
			throw new InvalidPropertyException( get_called_class(), $key );
		}
		return $this->items[ $key ];
	}

	/**
	 * Offset to set. Part of The ArrayAccess interface.
	 *
	 * @param   null|int|string $key   Name of the property.
	 * @param   mixed           $value The value of the property.
	 */
	#[\ReturnTypeWillChange]
	final public function offsetSet( $key, $value ) {
		$key = is_null( $key ) ? count( $this->items ) : $key;
		$this->validate( $key, $value );
		$this->items[ $key ] = $value;
	}

	/**
	 * Offset to unset. Part of The ArrayAccess interface.
	 *
	 * @param   int|string $key Name of the property.
	 */
	#[\ReturnTypeWillChange]
	final public function offsetUnset( $key ) {
		unset( $this->items[ $key ] );
	}

	/**
	 * Count elements of an object. Part of The Countable interface.
	 *
	 * @return  int  The count as integer.
	 */
	#[\ReturnTypeWillChange]
	final public function count() {
		return count( $this->items );
	}

	/**
	 * Retrieve an external iterator. Part of The IteratorAggregate interface.
	 *
	 * @return  Traversable An instance of an object implementing Iterator or Traversable
	 */
	#[\ReturnTypeWillChange]
	final public function getIterator() {
		return new ArrayIterator( $this->items );
	}

	/**
	 * Get an array of properties that used in output.
	 *
	 * @return  array Array of properties prepared for output.
	 */
	final protected function get_properties() {
		return $this->items;
	}
}
