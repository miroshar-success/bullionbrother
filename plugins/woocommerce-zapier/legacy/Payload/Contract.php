<?php

namespace OM4\Zapier\Payload;

use Iterator;
use JsonSerializable;
use OM4\Zapier\Exception\JsonErrorException;

defined( 'ABSPATH' ) || exit;

/**
 * All Payload class inherit from this.
 *
 * @deprecated 2.0.0
 */
interface Contract extends JsonSerializable {

	/**
	 * Fill this object in one step from sample data.
	 *
	 * @return  static
	 */
	public static function from_sample();

	/**
	 * Fill this object in one step. Assumed there is no other data manipulation
	 * necessary after this.
	 *
	 * @param   array|Iterator $data Data to fill properties at once.
	 * @return  static
	 */
	public static function from_data( $data );

	/**
	 * Convert the Object to an array.
	 *
	 * @return  array The array representation of the class data.
	 */
	public function to_array();

	/**
	 * Convert the Object to JSON string.
	 *
	 * @throws  JsonErrorException Error in JSON conversion.
	 * @return  string             The JSON representation of the class data.
	 */
	public function to_json();

	/**
	 * Getting information how well the object filled with actual data
	 *
	 * @return  bool True if object fully filled, false otherwise.
	 */
	public function is_complete();

	/**
	 * Collecting the properties which left null
	 *
	 * @return  array Array of nit filled property names.
	 */
	public function list_unfilled_properties();
}
