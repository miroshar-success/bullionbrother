<?php
/**
 * Selected.
 */

namespace WLPF\Frontend;

/**
 * Class.
 */
class Selected {

	/**
     * Element.
     */
    protected $element;

    /**
     * Prefix.
     */
    protected $prefix;

    /**
     * Query.
     */
    protected $query;

    /**
     * Data.
     */
    protected $data;

    /**
     * Constructor.
     */
    public function __construct( $element = 'all' ) {
        $prefix = wlpf_get_query_args_prefix();

        $query = ( isset( $_GET ) ? wlpf_cast( $_GET, 'array', false ) : array() );
        $query = $this->filter_query( $query, $prefix );

        $this->element = $element;
        $this->prefix = $prefix;
        $this->query = $query;
        $this->data = array();

        $this->prepare_data();
    }

    /**
     * Filter query.
     */
    protected function filter_query( $query, $prefix ) {
        $fquery = array();
        $offset = strlen( $prefix );
        $gkeys = array( 'page', 'search', 'min_price', 'max_price', 'sorting', 'vendor' );

        foreach ( $query as $key => $data ) {
            if ( 0 === strpos( $key, $prefix ) ) {
                $key = substr( $key, $offset );

                if ( ! in_array( $key, $gkeys, true ) ) {
                    if ( 0 === strpos( $key, 'pa_' ) ) {
                        $fquery[ 'attr_query' ][ $key ] = $data;
                    } else {
                        $fquery[ 'tax_query' ][ $key ] = $data;
                    }
                } else {
                    $fquery[ $key ] = $data;
                }
            } elseif ( ( 's' === $key ) || ( 'orderby' === $key ) ) {
                $fquery[ $key ] = $data;
            }
        }

        return $fquery;
    }

    /**
     * Prepare data.
     */
    protected function prepare_data() {
        $this->page_data();
        $this->search_data();
        $this->prices_data();
        $this->sorting_data();
        $this->author_data();
        $this->attribute_data();
        $this->taxonomy_data();
    }

    /**
     * Page data.
     */
    protected function page_data() {
        if ( ( 'all' !== $this->element ) && ( 'page' !== $this->element ) ) {
            return;
        }

        $query = $this->query;

        if ( isset( $query['page'] ) ) {
            $page = wlpf_cast( $query['page'], 'absint' );
            $page = max( 1, $page );

            unset( $query['page'] );

            $this->data['page'] = $page;
            $this->query = $query;
        }
    }

    /**
     * Search data.
     */
    protected function search_data() {
        if ( ( 'all' !== $this->element ) && ( 'search' !== $this->element ) ) {
            return;
        }

        $query = $this->query;

        if ( isset( $query['search'] ) || isset( $query['s'] ) ) {
            if ( isset( $query['search'] ) ) {
                $search = wlpf_cast( $query['search'], 'text' );
            } else {
                $search = wlpf_cast( $query['s'], 'text' );
            }

            unset( $query['search'] );
            unset( $query['s'] );

            $this->data['search'] = $search;
            $this->query = $query;
        }
    }

    /**
     * Prices data.
     */
    protected function prices_data() {
        if ( ( 'all' !== $this->element ) && ( 'prices' !== $this->element ) ) {
            return;
        }

        $query = $this->query;

        if ( isset( $query['min_price'] ) && isset( $query['max_price'] ) ) {
            $min_price = ( isset( $query['min_price'] ) ? wlpf_cast( $query['min_price'], 'absint' ) : 0 );
            $max_price = ( isset( $query['max_price'] ) ? wlpf_cast( $query['max_price'], 'absint' ) : $min_price );

            $prices = array(
                'min' => $min_price,
                'max' => $max_price,
            );

            unset( $query['min_price'] );
            unset( $query['max_price'] );

            $this->data['prices'] = $prices;
            $this->query = $query;
        }
    }

    /**
     * Sorting data.
     */
    protected function sorting_data() {
        if ( ( 'all' !== $this->element ) && ( 'sorting' !== $this->element ) ) {
            return;
        }

        $query = $this->query;

        if ( isset( $query['sorting'] ) || isset( $query['orderby'] ) ) {
            if ( isset( $query['sorting'] ) ) {
                $term = wlpf_cast( $query['sorting'], 'key' );
            } else {
                $term = wlpf_cast( $query['orderby'], 'key' );
            }

            $terms_info = array();

            $options = wlpf_get_sorting_options();

            if ( isset( $options[ $term ] ) ) {
                $label = $options[ $term ];

                $terms_info[ $term ] = array(
                    'key'   => $term,
                    'label' => $label,
                );
            }

            $sorting = array(
                'term' => $term,
                'terms_info' => $terms_info,
            );

            unset( $query['sorting'] );
            unset( $query['orderby'] );

            $this->data['sorting'] = $sorting;
            $this->query = $query;
        }
    }

    /**
     * Author data.
     */
    protected function author_data() {
        if ( ( 'all' !== $this->element ) && ( 'author' !== $this->element ) ) {
            return;
        }

        $query = $this->query;

        if ( isset( $query['vendor'] ) ) {
            $terms = ( isset( $query['vendor'] ) ? wlpf_cast( $query['vendor'], 'text' ) : '' );
            $terms = wlpf_string_to_array_of_key( $terms );

            $terms_ids = array();
            $terms_info = array();

            if ( ! empty( $terms ) ) {
                foreach ( $terms as $term ) {
                    $user = get_user_by( 'login', $term );

                    if ( is_object( $user ) ) {
                        $term_info = array();

                        $id       = ( isset( $user->ID ) ? wlpf_cast( $user->ID, 'absint' ) : 0 );
                        $name     = ( isset( $user->display_name ) ? wlpf_cast( $user->display_name, 'text' ) : '' );
                        $username = ( isset( $user->user_login ) ? wlpf_cast( $user->user_login, 'text' ) : '' );
                        $count    = count_user_posts( $id, 'product' );

                        $term_info = array(
                            'id'       => $id,
                            'name'     => $name,
                            'username' => $username,
                            'count'    => $count,
                        );

                        $terms_ids[] = $id;
                        $terms_info[ $id ] = $term_info;
                    }
                }
            }

            $vendor = array(
                'terms' => $terms_ids,
                'terms_info' => $terms_info,
            );

            unset( $query['vendor'] );

            $this->data['vendor'] = $vendor;
            $this->query = $query;
        }
    }

    /**
     * Attribute data.
     */
    protected function attribute_data() {
        if ( ( 'all' !== $this->element ) && ( 'attribute' !== $this->element ) ) {
            return;
        }

        $query = $this->query;

        if ( isset( $query['attr_query'] ) ) {
            $attr_query = ( isset( $query['attr_query'] ) ? wlpf_cast( $query['attr_query'], 'array' ) : array() );
            $attr_filter = array();

            foreach ( $attr_query as $key => $terms ) {
                $terms = wlpf_string_to_array_of_key( $terms );

                $terms_ids = array();
                $terms_info = array();

                if ( ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $term = get_term_by( 'slug', $term, $key );

                        if ( empty( $term ) || ! is_object( $term ) ) {
                            $term = get_terms( array( 'taxonomy' => $key, 'slug' => $term, 'hide_empty' => false ) );
                            $term = ( ( is_array( $term ) && isset( $term[0] ) ) ? $term[0] : array() );
                        }

                        if ( ! empty( $term ) && is_object( $term ) ) {
                            $term_info = array();

                            $id     = ( isset( $term->term_id ) ? wlpf_cast( $term->term_id, 'absint' ) : 0 );
                            $parent = ( isset( $term->parent ) ? wlpf_cast( $term->parent, 'absint' ) : 0 );
                            $slug   = ( isset( $term->slug ) ? wlpf_cast( $term->slug, 'key' ) : '' );
                            $name   = ( isset( $term->name ) ? wlpf_cast( $term->name, 'text' ) : '' );
                            $count  = ( isset( $term->count ) ? wlpf_cast( $term->count, 'absint' ) : 0 );

                            $term_info = array(
                                'id'     => $id,
                                'parent' => $parent,
                                'slug'   => $slug,
                                'name'   => $name,
                                'count'  => $count,
                            );

                            $terms_ids[] = $id;
                            $terms_info[ $id ] = $term_info;
                        }
                    }
                }

                if ( ! empty( $terms_ids ) && ! empty( $terms_info ) ) {
                    $attr_filter[ $key ] = array(
                        'taxonomy' => $key,
                        'field' => 'term_id',
                        'terms' =>  $terms_ids,
                        'operator' => 'IN',
                        'include_children' => true,
                        'terms_info' => $terms_info,
                    );
                }
            }

            unset( $query['attr_query'] );

            $this->data['attr_filter'] = $attr_filter;
            $this->query = $query;
        }
    }

    /**
     * Taxonomy data.
     */
    protected function taxonomy_data() {
        if ( ( 'all' !== $this->element ) && ( 'taxonomy' !== $this->element ) ) {
            return;
        }

        $query = $this->query;

        if ( isset( $query['tax_query'] ) ) {
            $tax_query = ( isset( $query['tax_query'] ) ? wlpf_cast( $query['tax_query'], 'array' ) : array() );
            $tax_filter = array();

            foreach ( $tax_query as $key => $terms ) {
                $terms = wlpf_string_to_array_of_key( $terms );

                $terms_ids = array();
                $terms_info = array();

                if ( ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $term = get_term_by( 'slug', $term, $key );

                        if ( empty( $term ) || ! is_object( $term ) ) {
                            $term = get_terms( array( 'taxonomy' => $key, 'slug' => $term, 'hide_empty' => false ) );
                            $term = ( ( is_array( $term ) && isset( $term[0] ) ) ? $term[0] : array() );
                        }

                        if ( ! empty( $term ) && is_object( $term ) ) {
                            $term_info = array();

                            $id     = ( isset( $term->term_id ) ? wlpf_cast( $term->term_id, 'absint' ) : 0 );
                            $parent = ( isset( $term->parent ) ? wlpf_cast( $term->parent, 'absint' ) : 0 );
                            $slug   = ( isset( $term->slug ) ? wlpf_cast( $term->slug, 'key' ) : '' );
                            $name   = ( isset( $term->name ) ? wlpf_cast( $term->name, 'text' ) : '' );
                            $count  = ( isset( $term->count ) ? wlpf_cast( $term->count, 'absint' ) : 0 );

                            $term_info = array(
                                'id'     => $id,
                                'parent' => $parent,
                                'slug'   => $slug,
                                'name'   => $name,
                                'count'  => $count,
                            );

                            $terms_ids[] = $id;
                            $terms_info[ $id ] = $term_info;
                        }
                    }
                }

                if ( ! empty( $terms_ids ) && ! empty( $terms_info ) ) {
                    $tax_filter[ $key ] = array(
                        'taxonomy' => $key,
                        'field' => 'term_id',
                        'terms' =>  $terms_ids,
                        'operator' => 'IN',
                        'include_children' => true,
                        'terms_info' => $terms_info,
                    );
                }
            }

            unset( $query['tax_query'] );

            $this->data['tax_filter'] = $tax_filter;
            $this->query = $query;
        }
    }

    /**
     * Get data.
     */
    public static function get_data( $element = 'all' ) {
        $instance = new self( $element );

        $data = $instance->data;
        $data = ( is_array( $data ) ? $data : array() );

        return $data;
    }

}