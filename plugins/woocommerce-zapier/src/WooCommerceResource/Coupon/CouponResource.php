<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Coupon;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;

defined( 'ABSPATH' ) || exit;


/**
 * Definition of the Coupon resource type.
 *
 * This resource is only enabled to users if WooCommerce core's coupons functionality is enabled.
 *
 * @since 2.1.0
 */
class CouponResource extends CustomPostTypeResource {

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * {@inheritDoc}
	 *
	 * @param FeatureChecker $checker FeatureChecker instance.
	 */
	public function __construct( FeatureChecker $checker ) {
		$this->checker             = $checker;
		$this->key                 = 'coupon';
		$this->name                = __( 'Coupon', 'woocommerce-zapier' );
		$this->metabox_screen_name = 'shop_coupon';
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_enabled() {
		return $this->checker->is_coupon_enabled();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id Resource ID.
	 *
	 * @return string|null
	 */
	public function get_description( $resource_id ) {
		$coupon_code = \wc_get_coupon_code_by_id( $resource_id );
		if ( '' !== $coupon_code ) {
			return $coupon_code;
		}
		return null;
	}

}
