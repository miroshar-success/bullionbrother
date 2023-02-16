<?php
/**
 * Duplicator.
 */

namespace WLEA\Admin;

/**
 * Class.
 */
class Duplicator {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 20, 2 );
	}

	/**
	 * Row actions.
	 */
	public function row_actions( $actions, $post ) {
		if ( ! isset( $post->ID ) || ! isset( $post->post_type ) || ! is_array( $actions ) || empty( $actions ) ) {
			return $actions;
		}

		$post_id = absint( $post->ID );
		$post_type = sanitize_key( $post->post_type );

		if ( empty( $post_id ) || ( ( 'wlea-email' !== $post_type ) && ( 'wlea-workflow' !== $post_type ) ) ) {
			return $actions;
		}

		$new_actions = array();

		foreach ( $actions as $key => $value ) {
			if ( 'trash' === $key ) {
				$label = esc_html__( 'Duplicate', 'woolentor-pro' );
				$duplicating_label = esc_html__( 'Duplicating', 'woolentor-pro' );

				$new_actions['wlea-duplicator'] = '<a href="' . esc_url( remove_query_arg( 'wlea_fake_query_arg' ) ) . '" class="wlea-duplicator-button" data-wlea-post-id="' . esc_attr( $post_id ) . '" data-wlea-label="' . esc_attr( $label ) . '" data-wlea-duplicating-label="' . esc_attr( $duplicating_label ) . '" title="' . esc_attr( $label ) . '" aria-label="' . esc_attr( $label ) . '">' . esc_html( $label ) . '</a>';
			}

			$new_actions[ $key ] = $value;
		}

		return ( is_array( $new_actions ) && ! empty( $new_actions ) ? $new_actions : $actions );
	}

}