<?php

namespace OM4\Zapier\Payload\Base;

use ArrayAccess;
use Countable;
use Iterator;
use IteratorAggregate;
use OM4\Zapier\Exception\IncompletePayloadException;
use OM4\Zapier\Exception\InvalidPropertyException;
use OM4\Zapier\Exception\InvalidTypeException;
use OM4\Zapier\Exception\JsonErrorException;
use OM4\Zapier\Payload\Contract;

defined( 'ABSPATH' ) || exit;

/**
 * All Payload class inherit from this.
 *
 * @deprecated 2.0.0
 */
abstract class Base implements Contract {

	/**
	 * Sanitize & validate properties to proper types to use in a Payload.
	 *
	 * @param   string $key              Name of property to validate.
	 * @param   mixed  $value            The property to validate.
	 * @throws  InvalidTypeException     Type not allowed for accessed property.
	 * @throws  InvalidPropertyException Accessing non-existent property.
	 * @return  void
	 */
	abstract protected function validate( $key, $value );

	/**
	 * Convert the Object to an array.
	 *
	 * @return  array The array representation of the class data.
	 */
	abstract protected function get_properties();

	/**
	 * Convert the Object to an array.
	 *
	 * @return  array The array representation of the class data.
	 */
	final public function to_array() {
		return $this->jsonSerialize();
	}

	/**
	 * Return the object as array and an array of unfilled properties.
	 *
	 * @param   array  $buffer     The array to search for properties with null value.
	 * @param   string $parent_key Parent key name to create dot notation style names.
	 * @return  array              The array of the class data, and The array of missing keys.
	 */
	final protected function proper_array_and_nulls( $buffer, $parent_key = '' ) {
		$nulls = array();
		array_walk(
			$buffer,
			function ( &$value, $key ) use ( &$nulls, $parent_key ) {
				$value = $value instanceof self ? $value->to_array() : $value;
				if ( is_null( $value ) ) {
					$nulls[] = $parent_key . $key;
				} elseif ( is_array( $value ) ) {
					list( , $child_nulls ) = $this->proper_array_and_nulls( $value, $parent_key . $key . '.' );
					$nulls                 = array_merge( $nulls, $child_nulls );
				}
			}
		);
		return array( $buffer, $nulls );
	}

	/**
	 * Convert the Object to JSON string.
	 *
	 * @throws  JsonErrorException Error in JSON conversion.
	 * @return  string             The JSON representation of the class data.
	 */
	final public function to_json() {
		$buffer = json_encode( $this->jsonSerialize() );
		if ( false === $buffer ) {
			throw new JsonErrorException( get_called_class(), json_last_error() );
		}
		return $buffer;
	}

	/**
	 * Getting information how well the object filled with actual data
	 *
	 * @return  bool True if object fully filled, false otherwise.
	 */
	final public function is_complete() {
		list( , $nulls ) = $this->proper_array_and_nulls( $this->get_properties() );
		return empty( $nulls );
	}

	/**
	 * Collecting the properties which left null
	 *
	 * @return  array Array of not filled property names.
	 */
	public function list_unfilled_properties() {
		list( , $nulls ) = $this->proper_array_and_nulls( $this->get_properties() );
		return $nulls;
	}

	/**
	 * Specify data which should be serialized to JSON using the JsonSerializable interface
	 *
	 * @throws IncompletePayloadException Payload not completely filled.
	 * @return mixed                      Data which can be serialized by json_encode.
	 */
	#[\ReturnTypeWillChange]
	final public function jsonSerialize() {
		list( $buffer, $nulls ) = $this->proper_array_and_nulls( $this->get_properties() );
		if ( ! empty( $nulls ) ) {
			throw new IncompletePayloadException( get_called_class(), $nulls );
		}
		return $buffer;
	}
}
