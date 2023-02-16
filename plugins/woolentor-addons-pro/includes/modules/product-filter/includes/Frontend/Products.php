<?php
/**
 * Products.
 */

namespace WLPF\Frontend;

/**
 * Class.
 */
class Products extends \WC_Shortcode_Products {

	/**
     * Type.
     */
    protected $type;

    /**
     * Settings.
     */
    protected $settings;

    /**
     * Filter args.
     */
    protected $filter_args;

    /**
     * Constructor.
     */
    public function __construct( $settings = array(), $type = '', $filter_args = array() ) {
        $settings = $this->process_settings( $settings );

        $this->type        = $type;
        $this->settings    = $settings;
        $this->filter_args = $filter_args;
        $this->attributes  = $this->parse_attributes( $settings );
        $this->query_args  = $this->parse_query_args();
    }

    /**
     * Process settings.
     */
    protected function process_settings( $settings ) {
        $limit = ( isset( $settings['limit'] ) ? wlpf_cast( $settings['limit'], 'int' ) : -1 );
        $columns = ( isset( $settings['columns'] ) ? wlpf_cast( $settings['columns'], 'int' ) : 0 );
        $orderby = ( isset( $settings['orderby'] ) ? wlpf_cast( $settings['orderby'], 'text' ) : '' );
        $order = ( isset( $settings['order'] ) ? wlpf_cast( $settings['order'], 'text' ) : '' );
        $paginate = ( isset( $settings['paginate'] ) ? wlpf_cast( $settings['paginate'], 'bool' ) : true );
        $allow_order = ( isset( $settings['allow_order'] ) ? wlpf_cast( $settings['allow_order'], 'bool' ) : true );
        $show_result_count = ( isset( $settings['show_result_count'] ) ? wlpf_cast( $settings['show_result_count'], 'bool' ) : true );

        $settings = array();

        if ( '' !== $limit ) {
            $settings['limit'] = $limit;
        }

        if ( '' !== $columns ) {
            $settings['columns'] = $columns;
        }

        if ( '' !== $orderby ) {
            $settings['orderby'] = $orderby;
        }

        if ( '' !== $order ) {
            $settings['order'] = $order;
        }

        $settings['paginate'] = $paginate;

        if ( true === $paginate ) {
            if ( false === $allow_order ) {
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
            }

            if ( false === $show_result_count ) {
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
            }
        }

        remove_action( 'woocommerce_before_shop_loop', 'wlpf_hooked_before_shop_loop', -10000 );
        remove_action( 'woocommerce_after_shop_loop', 'wlpf_hooked_after_shop_loop', 10000 );

        return $settings;
    }

    /**
     * Parse query args.
     *
     * @since  3.2.0
     * @return array
     */
    protected function parse_query_args() {
        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'no_found_rows'       => false === wc_string_to_bool( $this->attributes['paginate'] ),
            'orderby'             => empty( $_GET['orderby'] ) ? $this->attributes['orderby'] : wc_clean( wp_unslash( $_GET['orderby'] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        );

        $orderby_value         = explode( '-', $query_args['orderby'] );
        $orderby               = esc_attr( $orderby_value[0] );
        $order                 = ! empty( $orderby_value[1] ) ? $orderby_value[1] : strtoupper( $this->attributes['order'] );
        $query_args['orderby'] = $orderby;
        $query_args['order']   = $order;

        if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
            $this->attributes['page'] = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        }

        if ( ! empty( $this->attributes['rows'] ) ) {
            $this->attributes['limit'] = $this->attributes['columns'] * $this->attributes['rows'];
        }

        $ordering_args         = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
        $query_args['orderby'] = $ordering_args['orderby'];
        $query_args['order']   = $ordering_args['order'];
        if ( $ordering_args['meta_key'] ) {
            $query_args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        }
        $query_args['posts_per_page'] = intval( $this->attributes['limit'] );
        if ( 1 < $this->attributes['page'] ) {
            $query_args['paged'] = absint( $this->attributes['page'] );
        }
        $query_args['meta_query'] = WC()->query->get_meta_query(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
        $query_args['tax_query']  = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

        // Visibility.
        $this->set_visibility_query_args( $query_args );

        // SKUs.
        $this->set_skus_query_args( $query_args );

        // IDs.
        $this->set_ids_query_args( $query_args );

        // Set specific types query args.
        if ( method_exists( $this, "set_{$this->type}_query_args" ) ) {
            $this->{"set_{$this->type}_query_args"}( $query_args );
        }

        // Attributes.
        $this->set_attributes_query_args( $query_args );

        // Categories.
        $this->set_categories_query_args( $query_args );

        // Tags.
        $this->set_tags_query_args( $query_args );

        $query_args = apply_filters( 'woolentor_filterable_shortcode_products_query', $query_args, $this->filter_args );
        $query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );

        // Always query only IDs.
        $query_args['fields'] = 'ids';

        return $query_args;
    }

}