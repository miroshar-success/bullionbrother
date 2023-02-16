<?php
/**
 * Class Kadence_Pro\Post_Select_Controller
 *
 * @package Kadence Pro
 */

namespace Kadence_Pro;

use WP_REST_Controller;
use WP_REST_Server;
use WP_Query;

use function current_user_can;
use function mysql2date;
use function wp_list_filter;
use function get_object_taxonomies;
/**
 * Class managing the rest response for post searching.
 */
class Post_Select_Controller extends WP_REST_Controller {

	/**
	 * Type property name.
	 */
	const PROP_TYPE = 'type';

	/**
	 * Search property name.
	 */
	const PROP_SEARCH = 'search';

	/**
	 * Include property name.
	 */
	const PROP_INCLUDE = 'include';

	/**
	 * Per page property name.
	 */
	const PROP_PER_PAGE = 'per_page';

	/**
	 * Page property name.
	 */
	const PROP_PAGE = 'page';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->namespace = 'ktp/v1';
		$this->rest_base = 'post-select';
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permission_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);
	}

	/**
	 * Checks if a given request has access to search content.
	 *
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has search access, WP_Error object otherwise.
	 */
	public function get_items_permission_check( $request ) {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Retrieves a collection of objects.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		$search    = $request->get_param( self::PROP_SEARCH );
		$include   = $request->get_param( self::PROP_INCLUDE );
		$prop_type = $request->get_param( self::PROP_TYPE );

		if ( empty( $prop_type ) ) {
			return array();
		}

		$query_args = array(
			'post_type'           => $request->get_param( self::PROP_TYPE ),
			'posts_per_page'      => $request->get_param( self::PROP_PER_PAGE ),
			'paged'               => $request->get_param( self::PROP_PAGE ),
			'tax_query'           => array(),
			'filter_bundles'      => true,
			'ignore_sticky_posts' => 1,
		);

		if ( ! empty( $search ) ) {
			$query_args['s'] = $search;
		}

		foreach ( $this->get_allowed_tax_filters() as $taxonomy ) {
			$base  = ! empty( $taxonomy->rest_base ) ? $taxonomy->rest_base : $taxonomy->name;
			$query = $request->get_param( $base );
			if ( ! empty( $query ) ) {
				$query_args['tax_query'][] = array(
					'taxonomy'         => $taxonomy->name,
					'field'            => 'term_id',
					'terms'            => $query,
					'include_children' => false,
				);
			}
		}

		if ( $include ) {
			$query_args['post__in'] = $include;
			$query_args['orderby']  = 'post__in';
		}

		$query = new WP_Query( $query_args );
		$posts = array();

		foreach ( $query->posts as $post ) {
			$posts[] = $this->prepare_item_for_response( $post, $request );
		}

		$response = rest_ensure_response( $posts );

		$total_posts = $query->found_posts;
		$max_pages   = ceil( $total_posts / (int) $query->query_vars['posts_per_page'] );

		$response->header( 'X-WP-Total', (int) $total_posts );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		return $response;
	}

	/**
	 * Prepares a single result for response.
	 *
	 * @param int             $id      ID of the item to prepare.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function prepare_item_for_response( $post, $request ) {

		$data = array(
			'id' => $post->ID,
			'title' => array(
				'raw'      => $post->post_title,
				'rendered' => get_the_title( $post->ID ),
			),
			'type' => $post->post_type,
			'date' => $this->prepare_date_response( $post->post_date_gmt, $post->post_date ),
			'slug' => $post->post_name,
			'status' => $post->post_status,
			'link' => get_permalink( $post->ID ),
			'author' => absint( $post->post_author ),
		);

		// For drafts, `post_date_gmt` may not be set, indicating that the
		// date of the draft should be updated each time it is saved (see
		// #38883).  In this case, shim the value based on the `post_date`
		// field with the site's timezone offset applied.
		if ( '0000-00-00 00:00:00' === $post->post_date_gmt ) {
			$post_date_gmt = get_gmt_from_date( $post->post_date );
		} else {
			$post_date_gmt = $post->post_date_gmt;
		}

		$data['date_gmt'] = $this->prepare_date_response( $post_date_gmt );

		return $data;
	}

	/**
	 * Checks the post_date_gmt or modified_gmt and prepare any post or
	 * modified date for single post output.
	 *
	 * @param string      $date_gmt GMT publication time.
	 * @param string|null $date     Optional. Local publication time. Default null.
	 * @return string|null ISO8601/RFC3339 formatted datetime.
	 */
	protected function prepare_date_response( $date_gmt, $date = null ) {
		// Use the date if passed.
		if ( isset( $date ) ) {
			return mysql2date( 'Y-m-d\TH:i:s', $date, false );
		}

		// Return null if $date_gmt is empty/zeros.
		if ( '0000-00-00 00:00:00' === $date_gmt ) {
			return null;
		}

		// Return the formatted datetime.
		return mysql2date( 'Y-m-d\TH:i:s', $date_gmt, false );
	}

	/**
	 * Retrieves the query params for the search results collection.
	 *
	 * @return array Collection parameters.
	 */
	public function get_collection_params() {
		$query_params  = parent::get_collection_params();
		$allowed_types = $this->get_allowed_post_types();

		$query_params[ self::PROP_TYPE ] = array(
			'description' => __( 'Limit results to items of an object type.', 'kadence-pro' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'string',
			),
			'sanitize_callback' => array( $this, 'sanitize_post_types' ),
			'validate_callback' => array( $this, 'validate_post_types' ),
			'default' => $allowed_types,
		);

		$query_params[ self::PROP_SEARCH ] = array(
			'description' => __( 'Limit results to items that match search query.', 'kadence-pro' ),
			'type'        => 'string',
		);

		$query_params[ self::PROP_INCLUDE ] = array(
			'description' => __( 'Include posts by ID.', 'kadence-pro' ),
			'type'        => 'array',
			'validate_callback' => array( $this, 'validate_post_ids' ),
			'sanitize_callback' => array( $this, 'sanitize_post_ids' ),
		);

		$query_params[ self::PROP_PER_PAGE ] = array(
			'description' => __( 'Number of results to return.', 'kadence-pro' ),
			'type'        => 'number',
			'sanitize_callback' => array( $this, 'sanitize_post_perpage' ),
			'default' => 25,
		);

		$query_params[ self::PROP_PAGE ] = array(
			'description' => __( 'Page of results to return.', 'kadence-pro' ),
			'type'        => 'number',
			'sanitize_callback' => array( $this, 'sanitize_results_page_number' ),
			'default' => 1,
		);

		foreach ( $this->get_allowed_tax_filters() as $taxonomy ) {
			$base = ! empty( $taxonomy->rest_base ) ? $taxonomy->rest_base : $taxonomy->name;

			$query_params[ $base ] = array(
				/* translators: %s: taxonomy name */
				'description' => sprintf( __( 'Limit result set to all items that have the specified term assigned in the %s taxonomy.', 'kadence-pro' ), $base ),
				'type'        => 'array',
				'items'       => array(
					'type' => 'integer',
				),
				'default'     => array(),
			);
		}

		return $query_params;
	}

	/**
	 * Sanitizes the list of subtypes, to ensure only subtypes of the passed type are included.
	 *
	 * @param string|array    $subtypes  One or more subtypes.
	 * @param WP_REST_Request $request   Full details about the request.
	 * @param string          $parameter Parameter name.
	 * @return array|WP_Error List of valid subtypes, or WP_Error object on failure.
	 */
	public function sanitize_post_types( $post_types, $request ) {
		$allowed_types = $this->get_allowed_post_types();
		return array_unique( array_intersect( $post_types, $allowed_types ) );
	}

	/**
	 * Validates the list of subtypes, to ensure it's an array.
	 *
	 * @param array    $value  One or more subtypes.
	 * @return bool    true or false.
	 */
	public function validate_post_types( $value ) {
		return is_array( $value );
	}

	/**
	 * Sanitizes the list of ids, to ensure it's only numbers.
	 *
	 * @param array    $ids  One or more post ids.
	 * @return array   array of numbers
	 */
	public function sanitize_post_ids( $ids ) {
		return array_map( 'absint', $ids );
	}

	/**
	 * Validates the list of ids, to ensure it's not empty.
	 *
	 * @param array    $ids  One or more post ids.
	 * @return bool    true or false.
	 */
	public function validate_post_ids( $ids ) {
		return count( $ids ) > 0;
	}

	/**
	 * Sanitizes the perpage, to ensure it's only a number.
	 *
	 * @param integer  $val number page page.
	 * @return integer a number
	 */
	public function sanitize_post_perpage( $val ) {
		return min( absint( $val ), 100 );
	}

	/**
	 * Sanitizes the page number, to ensure it's only a number.
	 *
	 * @param integer  $val number page page.
	 * @return integer a number
	 */
	public function sanitize_results_page_number( $val ) {
		return absint( $val );
	}

	/**
	 * Get allowed post types.
	 *
	 * By default this is only post types that have show_in_rest set to true.
	 * You can filter this to support more post types if required.
	 *
	 * @return array
	 */
	public function get_allowed_post_types() {
		$allowed_types = array_values( get_post_types( array(
			'show_in_rest' => true,
		 ) ) );

		$key = array_search( 'attachment', $allowed_types, true );

		if ( false !== $key ) {
			unset( $allowed_types[ $key ] );
		}

		/**
		 * Filter the allowed post types.
		 *
		 * Note that if you allow this for posts that are not otherwise public,
		 * this data will be accessible using this endpoint for any logged in user with the edit_post capability.
		 */
		return apply_filters( 'kadence_pro_post_select_allowed_post_types', $allowed_types );
	}

	/**
	 * Get allowed tax filters.
	 *
	 * @return array
	 */
	public function get_allowed_tax_filters() {
		$taxonomies = array();

		foreach ( $this->get_allowed_post_types() as $post_type ) {
			$taxonomies = array_merge(
				$taxonomies,
				wp_list_filter( get_object_taxonomies( $post_type, 'objects' ), array( 'show_in_rest' => true ) )
			);
		}

		return $taxonomies;
	}
}
