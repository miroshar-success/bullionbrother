<?php

namespace OM4\WooCommerceZapier\WooCommerceResource;

use OM4\WooCommerceZapier\Exception\InvalidImplementationException;

defined( 'ABSPATH' ) || exit;

/**
 * Represents a resource type that is based on a WordPress Custom Post Type.
 *
 * @since 2.0.0
 */
abstract class CustomPostTypeResource extends Base {
	/**
	 * The name of this resource's Custom Post Type.
	 *
	 * @var string
	 */
	protected $metabox_screen_name;

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id Resource ID.
	 */
	public function get_admin_url( $resource_id ) {
		return admin_url( "post.php?post={$resource_id}&action=edit" );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws InvalidImplementationException If metabox_screen_name isn't set.
	 */
	public function get_metabox_screen_name() {
		if ( ! is_string( $this->metabox_screen_name ) ) {
			throw new InvalidImplementationException( '`metabox_screen_name` needs to be set', 1 );
		}
		return $this->metabox_screen_name;
	}
}
