<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class bodhi_svg_attributes extends \enshrined\svgSanitize\data\AllowedAttributes {

	/**
	 * Returns an array of attributes
	 *
	 * @return array
	 */
	public static function getAttributes() {

		/**
		 * var array Attributes that are allowed.
		 */
		return apply_filters( 'svg_allowed_attributes', parent::getAttributes() );
	}
}