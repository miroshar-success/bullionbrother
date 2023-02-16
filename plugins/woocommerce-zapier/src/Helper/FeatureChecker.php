<?php

namespace OM4\WooCommerceZapier\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Check Feature/plugin/class availability.
 *
 * @since 2.0.0
 */
class FeatureChecker {

	/**
	 * Check class is available
	 *
	 * @param string $class_name Name of the class to looking for. Preferably FQCN.
	 *
	 * @return boolean
	 */
	public function class_exists( $class_name ) {
		return \class_exists( $class_name );
	}

	/**
	 * Function is available
	 *
	 * @param string $function_name Name of the function to looking for.
	 *
	 * @return boolean
	 */
	public function function_exists( $function_name ) {
		return \function_exists( $function_name );
	}

	/**
	 * Check coupon is enabled
	 *
	 * @return boolean
	 */
	public function is_coupon_enabled() {
		return \wc_coupons_enabled();
	}
}
