<?php

namespace OM4\WooCommerceZapier\WooCommerceResource;

use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\WooCommerceResource\Coupon\CouponResource;
use OM4\WooCommerceZapier\WooCommerceResource\Customer\CustomerResource;
use OM4\WooCommerceZapier\WooCommerceResource\Definition;
use OM4\WooCommerceZapier\WooCommerceResource\Order\OrderResource;
use OM4\WooCommerceZapier\WooCommerceResource\Product\ProductResource;
use ReflectionClass;

defined( 'ABSPATH' ) || exit;

/**
 * Resource Manager.
 *
 * Responsible for loading and accessing WooCommerce Zapier Resource Type definitions.
 *
 * @since 2.0.0
 */
class Manager {

	/**
	 * List of resource definitions (including enabled and disabled resources).
	 *
	 * @var Definition[]
	 */
	protected $resources = array();

	/**
	 * ContainerService instance.
	 *
	 * @var ContainerService
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param ContainerService $container ContainerService instance.
	 */
	public function __construct( ContainerService $container ) {
		$this->container = $container;
	}

	/**
	 * Register all resources.
	 *
	 * @return void
	 */
	public function initialise() {
		$this->resources[] = $this->container->get( CustomerResource::class );
		$this->resources[] = $this->container->get( CouponResource::class );
		$this->resources[] = $this->container->get( OrderResource::class );
		$this->resources[] = $this->container->get( ProductResource::class );

		/**
		 * Extend the default list of supported resources.
		 *
		 * Any classes added using this filter must implement `OM4\WooCommerceZapier\WooCommerceResource\Definition`
		 *
		 * @internal
		 * @since 2.0.0
		 *
		 * @param class-string[] $additional_resources Array of class names (FQN).
		 */
		$additional_resources = apply_filters( 'wc_zapier_additional_resource_classes', array() );
		foreach ( $additional_resources as $resource_class_name ) {
			$class = new ReflectionClass( $resource_class_name );
			if ( $class->implementsInterface( Definition::class ) ) {
				$resource          = $this->container->get( $resource_class_name );
				$this->resources[] = $resource;
			}
		}
	}

	/**
	 * Get all active/enabled resource types.
	 *
	 * @return Definition[]
	 */
	public function get_enabled() {
		$enabled = array();
		foreach ( $this->get_all() as $resource ) {
			if ( $resource->is_enabled() ) {
				$enabled[] = $resource;
			}
		}
		return $enabled;
	}

	/**
	 * Get all registered resource types (including those that aren't enabled).
	 *
	 * @return Definition[]
	 */
	public function get_all() {
		if ( empty( $this->resources ) ) {
			// Not registered. Initialise.
			$this->initialise();
		}
		return $this->resources;
	}

	/**
	 * Get the Resource Definition object for the specified resource name.
	 *
	 * @param string $resource_type Resource key/type (coupon, customer, order, etc).
	 *
	 * @return Definition|false Resource definition, or false if resource definition doesn't exist.
	 */
	public function get_resource( $resource_type ) {
		foreach ( $this->get_all() as $resource ) {
			if ( $resource_type === $resource->get_key() ) {
				return $resource;
			}
		}
		return false;
	}

	/**
	 * Get a list of enabled/active resources.
	 *
	 * The array keys are plural resource keys (eg orders, customers) not singular.
	 *
	 * @return array
	 */
	public function get_enabled_resources_list() {
		$resources = array();
		foreach ( $this->get_enabled() as $resource ) {
			$resources[ $resource->get_key() . 's' ] = $resource->get_name();
		}
		return $resources;
	}

}
