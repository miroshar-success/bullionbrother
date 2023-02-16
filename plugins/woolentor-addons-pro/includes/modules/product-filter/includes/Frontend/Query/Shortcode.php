<?php
/**
 * Shortcode.
 */

namespace WLPF\Frontend\Query;

/**
 * Class.
 */
class Shortcode {

	/**
     * Constructor.
     */
    public function __construct() {
        add_filter( 'woolentor_filterable_shortcode_products_query', array( $this, 'products_query' ), 10, 2 );
    }

    /**
     * Products query.
     */
    public function products_query( $query_args, $filter_args ) {
        $query_args = wlpf_cast( $query_args, 'array' );
        $filter_args = wlpf_cast( $filter_args, 'array' );

        if ( empty( $filter_args ) ) {
            $filter_args = \WLPF\Frontend\Selected::get_data();
        }

        if ( empty( $query_args ) || empty( $filter_args ) ) {
            return $query_args;
        }

        $query_args = $this->fixed_args( $query_args, $filter_args );
        $query_args = $this->taxonomy_args( $query_args, $filter_args );
        $query_args = $this->attribute_args( $query_args, $filter_args );
        $query_args = $this->author_args( $query_args, $filter_args );
        $query_args = $this->sorting_args( $query_args, $filter_args );
        $query_args = $this->prices_args( $query_args, $filter_args );
        $query_args = $this->search_args( $query_args, $filter_args );
        $query_args = $this->page_args( $query_args, $filter_args );

        return $query_args;
    }

    /**
     * Fixed args.
     */
    protected function fixed_args( $query_args, $filter_args ) {
        $fixed_filter = ( isset( $filter_args['fixed_filter'] ) ? wlpf_cast( $filter_args['fixed_filter'], 'array' ) : array() );

        if ( ! empty( $fixed_filter ) ) {
            $search = ( isset( $fixed_filter['search'] ) ? wlpf_cast( $fixed_filter['search'], 'text' ) : '' );
            $sorting = ( isset( $fixed_filter['sorting'] ) ? wlpf_cast( $fixed_filter['sorting'], 'key' ) : '' );
            $taxonomy = ( isset( $fixed_filter['taxonomy'] ) ? wlpf_cast( $fixed_filter['taxonomy'], 'key' ) : '' );
            $taxonomy_term = ( isset( $fixed_filter['taxonomy_term'] ) ? wlpf_cast( $fixed_filter['taxonomy_term'], 'key' ) : '' );

            if ( 0 < strlen( $search ) ) {
                $query_args['s'] = $search;
            }

            if ( 0 < strlen( $sorting ) ) {
                $query_args['orderby'] = $sorting;
            }

            if ( ! empty( $taxonomy ) && ! empty( $taxonomy_term ) ) {
                $query_args[ $taxonomy ] = $taxonomy_term;
                $query_args['taxonomy'] = $taxonomy;
                $query_args['term'] = $taxonomy_term;
            }
        }

        return $query_args;
    }

    /**
     * Taxonomy args.
     */
    protected function taxonomy_args( $query_args, $filter_args ) {
        $tax_query = ( isset( $query_args['tax_query'] ) ? wlpf_cast( $query_args['tax_query'], 'array' ) : array() );
        $tax_filter = ( isset( $filter_args['tax_filter'] ) ? wlpf_cast( $filter_args['tax_filter'], 'array' ) : array() );

        if ( ! empty( $tax_filter ) ) {
            $tax_query = array_merge( $tax_query, array_values( $tax_filter ) );

            $query_args['tax_query'] = $tax_query;
        }

        return $query_args;
    }

    /**
     * Attribute args.
     */
    protected function attribute_args( $query_args, $filter_args ) {
        $tax_query = ( isset( $query_args['tax_query'] ) ? wlpf_cast( $query_args['tax_query'], 'array' ) : array() );
        $attr_filter = ( isset( $filter_args['attr_filter'] ) ? wlpf_cast( $filter_args['attr_filter'], 'array' ) : array() );

        if ( ! empty( $attr_filter ) ) {
            $tax_query = array_merge( $tax_query, array_values( $attr_filter ) );

            $query_args['tax_query'] = $tax_query;
        }

        return $query_args;
    }

    /**
     * Author args.
     */
    protected function author_args( $query_args, $filter_args ) {
        $author = ( isset( $filter_args['author'] ) ? wlpf_cast( $filter_args['author'], 'array' ) : array() );
        $terms = ( isset( $author['terms'] ) ? wlpf_cast( $author['terms'], 'array' ) : array() );

        if ( ! empty( $terms ) ) {
            $query_args[ 'author__in' ] = $terms;
        }

        return $query_args;
    }

    /**
     * Sorting args.
     */
    protected function sorting_args( $query_args, $filter_args ) {
        $sorting = ( isset( $filter_args['sorting'] ) ? wlpf_cast( $filter_args['sorting'], 'array' ) : array() );
        $term = ( isset( $sorting['term'] ) ? wlpf_cast( $sorting['term'], 'key' ) : '' );

        if ( ! empty( $term ) ) {
            $ordering_args = wlpf_get_ordering_args( $term );

            $orderby = $ordering_args['orderby'];
            $order = $ordering_args['order'];
            $meta_key = $ordering_args['meta_key'];

            if ( ! empty( $orderby ) && ! empty( $order ) ) {
                $query_args['orderby'] = $orderby;
                $query_args['order'] = $order;
                $query_args['meta_key'] = $meta_key;
            }
        }

        return $query_args;
    }

    /**
     * Prices args.
     */
    protected function prices_args( $query_args, $filter_args ) {
        $meta_query = ( isset( $query_args['meta_query'] ) ? wlpf_cast( $query_args['meta_query'], 'array' ) : array() );
        $prices = ( isset( $filter_args['prices'] ) ? wlpf_cast( $filter_args['prices'], 'array' ) : array() );

        if ( ! empty( $prices ) ) {
            $min_price = ( isset( $prices['min'] ) ? wlpf_cast( $prices['min'], 'absint' ) : 0 );
            $max_price = ( isset( $prices['max'] ) ? wlpf_cast( $prices['max'], 'absint' ) : $min_price );

            $meta_query[] = array(
                'key'     => '_price',
                'value'   => $min_price,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            );

            $meta_query[] = array(
                'key'     => '_price',
                'value'   => $max_price,
                'compare' => '<=',
                'type'    => 'NUMERIC',
            );

            $query_args['meta_query'] = $meta_query;
        }

        return $query_args;
    }

    /**
     * Search args.
     */
    protected function search_args( $query_args, $filter_args ) {
        $search = ( isset( $filter_args['search'] ) ? wlpf_cast( $filter_args['search'], 'text' ) : '' );

        if ( 0 < strlen( $search ) ) {
            $query_args['s'] = $search;
        }

        return $query_args;
    }

    /**
     * Page args.
     */
    protected function page_args( $query_args, $filter_args ) {
        $page = ( isset( $filter_args['page'] ) ? wlpf_cast( $filter_args['page'], 'absint' ) : null );

        if ( null !== $page ) {
            $query_args['paged'] = $page;
        }

        return $query_args;
    }

}