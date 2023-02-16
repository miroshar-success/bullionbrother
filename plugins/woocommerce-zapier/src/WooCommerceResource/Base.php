<?php

namespace OM4\WooCommerceZapier\WooCommerceResource;

use OM4\WooCommerceZapier\Exception\InvalidImplementationException;
use OM4\WooCommerceZapier\WooCommerceResource\Definition;
use ReflectionObject;

defined( 'ABSPATH' ) || exit;

/**
 * Common implementation of Woocommerce REST API Resource Type.
 *
 * @since 2.0.0
 */
abstract class Base implements Definition {
	/**
	 * Resource's key (internal name/type).
	 *
	 * Must be a-z lowercase characters only, and in singular (non plural) form.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Resource's display name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * {@inheritDoc}
	 */
	public function is_enabled() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws InvalidImplementationException If key aren't set.
	 */
	public function get_key() {
		if ( ! is_string( $this->key ) ) {
			throw new InvalidImplementationException( '`key` needs to be set', 1 );
		}
		return $this->key;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws InvalidImplementationException If name aren't set.
	 */
	public function get_name() {
		if ( ! is_string( $this->name ) ) {
			throw new InvalidImplementationException( '`name` needs to be set', 1 );
		}
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws InvalidImplementationException If controller class isn't found.
	 */
	public function get_controller_name() {
		$controller_class = ( new ReflectionObject( $this ) )->getNamespaceName() . '\\Controller';
		if ( ! class_exists( $controller_class ) ) {
			throw new InvalidImplementationException( 'Controller class not found', 1 );
		}
		return $controller_class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_controller_rest_api_version() {
		return 3;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_payload() {
		return null;
	}
}
