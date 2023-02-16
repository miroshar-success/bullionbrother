<?php
/**
 * Frontend.
 */

/**
 * Cast product type.
 */
if ( ! function_exists( 'wlpf_cast_product_type' ) ) {
    function wlpf_cast_product_type( $type ) {
        switch ( $type ) {
            case 'recent':
                $product_type = 'recent_products';
                break;

            case 'sale':
                $product_type = 'sale_products';
                break;

            case 'best_selling':
                $product_type = 'best_selling_products';
                break;

            case 'top_rated':
                $product_type = 'top_rated_products';
                break;

            case 'featured':
                $product_type = 'featured';
                break;

            case 'mixed_order':
                $product_type = 'random';
                break;

            default:
                $product_type = 'products';
                break;
        }

        return $product_type;
    }
}

/**
 * Get range prices.
 */
if ( ! function_exists( 'wlpf_get_range_prices' ) ) {
    function wlpf_get_range_prices() {
        $all_prices = array();

        $min_price = 0;
        $max_price = 0;

        $products = wc_get_products( array(
            'limit' => -1,
        ) );

        foreach ( $products as $product ) {
            $all_prices[] = wlpf_cast( $product->get_price(), 'float' );
        }

        $min_price = wlpf_cast( floor( min( $all_prices ) ), 'absint' );
        $max_price = wlpf_cast( ceil( max( $all_prices ) ), 'absint' );

        $prices = array(
            'min' => $min_price,
            'max' => $max_price,
        );

        return $prices;
    }
}

/**
 * Get range values.
 */
if ( ! function_exists( 'wlpf_get_range_values' ) ) {
    function wlpf_get_range_values() {
        return wlpf_get_range_prices();
    }
}

/**
 * Get price with symbol.
 */
if ( ! function_exists( 'wlpf_get_price_with_symbol' ) ) {
    function wlpf_get_price_with_symbol( $price = 0, $number_wrap = true, $symbol_wrap = true ) {
        $output = '';

        $price = wlpf_cast( $price, 'absint' );

        $number_wrap = wlpf_cast( $number_wrap, 'bool' );
        $symbol_wrap = wlpf_cast( $symbol_wrap, 'bool' );

        $symbol = wlpf_cast( get_woocommerce_currency_symbol(), 'text', false );
        $position = wlpf_cast( get_option( 'woocommerce_currency_pos' ), 'key', false );

        if ( true === $number_wrap ) {
            $price = sprintf( '<span class="wlpf-price-number">%1$d</span>', $price );
        }

        if ( true === $symbol_wrap ) {
            $symbol = sprintf( '<span class="wlpf-price-symbol">%1$s</span>', $symbol );
        }

        switch ( $position ) {
            case 'left':
                $output = sprintf( '%2$s%1$s', $symbol, $price );
                break;

            case 'right':
                $output = sprintf( '%1$s%2$s', $price, $symbol );
                break;

            case 'left_space':
                $output = sprintf( '%2$s %1$s', $symbol, $price );
                break;

            case 'right_space':
                $output = sprintf( '%1$s %2$s', $price, $symbol );
                break;

            default:
                $output = sprintf( '%2$s%1$s', $symbol, $price );
                break;
        }

        return $output;
    }
}

/**
 * Get ordering args.
 */
if ( ! function_exists( 'wlpf_get_ordering_args' ) ) {
    function wlpf_get_ordering_args( $default_orderby = '', $default_order = '' ) {
        $default_orderby = explode( '-', $default_orderby );

        $orderby = strtolower( $default_orderby[0] );
        $order = strtoupper( ! empty( $default_orderby[1] ) ? $default_orderby[1] : $default_order );

        $ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $order );

        return array(
            'orderby' => $ordering_args['orderby'],
            'order' => $ordering_args['order'],
            'meta_key' => ( isset( $ordering_args['meta_key'] ) ? $ordering_args['meta_key'] : '' ),
        );
    }
}

/**
 * Get fixed search filter args.
 */
if ( ! function_exists( 'wlpf_get_fixed_search_filter_args' ) ) {
    function wlpf_get_fixed_search_filter_args( $filter_args = array() ) {
        if ( isset( $_GET ) && is_array( $_GET ) && isset( $_GET['s'] ) ) {
            $search_keyword = sanitize_text_field( $_GET['s'] );

            if ( ! empty( $search_keyword ) ) {
                $filter_args['search'] = $search_keyword;
            }
        }

        return $filter_args;
    }
}

/**
 * Get fixed sorting filter args.
 */
if ( ! function_exists( 'wlpf_get_fixed_sorting_filter_args' ) ) {
    function wlpf_get_fixed_sorting_filter_args( $filter_args = array() ) {
        if ( isset( $_GET ) && is_array( $_GET ) && isset( $_GET['orderby'] ) ) {
            $sorting_option = sanitize_text_field( $_GET['orderby'] );
            $sorting_options = wlpf_get_sorting_options();

            if ( ! empty( $sorting_option ) && in_array( $sorting_option, $sorting_options ) ) {
                $filter_args['sorting'] = $sorting_option;
            }
        }

        return $filter_args;
    }
}

/**
 * Get fixed taxonomy filter args.
 */
if ( ! function_exists( 'wlpf_get_fixed_taxonomy_filter_args' ) ) {
    function wlpf_get_fixed_taxonomy_filter_args( $filter_args = array() ) {
        global $wp_query;

        if ( is_object( $wp_query ) && is_main_query( $wp_query ) && $wp_query->is_tax() ) {
            $queried_object = $wp_query->get_queried_object();

            if ( is_object( $queried_object ) ) {
                $taxonomies = wlpf_get_product_global_taxonomies();

                $taxonomy = $queried_object->taxonomy;
                $taxonomy_term = $queried_object->slug;
                $taxonomy_term_id = $queried_object->term_id;

                if ( ! empty( $taxonomy ) && ! empty( $taxonomy_term ) && isset( $taxonomies[ $taxonomy ] ) ) {
                    $filter_args['taxonomy'] = $taxonomy;
                    $filter_args['taxonomy_term'] = $taxonomy_term;
                    $filter_args['taxonomy_term_id'] = $taxonomy_term_id;
                }
            }
        }

        return $filter_args;
    }
}

/**
 * Get fixed filter args.
 */
if ( ! function_exists( 'wlpf_get_fixed_filter_args' ) ) {
    function wlpf_get_fixed_filter_args() {
        $filter_args = array();

        $filter_args = wlpf_get_fixed_search_filter_args( $filter_args );
        $filter_args = wlpf_get_fixed_sorting_filter_args( $filter_args );
        $filter_args = wlpf_get_fixed_taxonomy_filter_args( $filter_args );

        return $filter_args;
    }
}

/**
 * Get selected filters data.
 */
if ( ! function_exists( 'wlpf_get_selected_filters_data' ) ) {
    function wlpf_get_selected_filters_data() {
        return \WLPF\Frontend\Selected::get_data();
    }
}

/**
 * Cast product archive addons settings.
 */
if ( ! function_exists( 'wlpf_cast_product_archive_addons_settings' ) ) {
    function wlpf_cast_product_archive_addons_settings( $pre_settings = array() ) {
        $columns = ( isset( $pre_settings['columns'] ) ? wlpf_cast( $pre_settings['columns'], 'text' ) : '4' );
        $rows = ( isset( $pre_settings['rows'] ) ? wlpf_cast( $pre_settings['rows'], 'text' ) : '' );
        $paginate = ( isset( $pre_settings['paginate'] ) ? wlpf_cast( $pre_settings['paginate'], 'key' ) : '' );
        $allow_order = ( isset( $pre_settings['allow_order'] ) ? wlpf_cast( $pre_settings['allow_order'], 'key' ) : '' );
        $show_result_count = ( isset( $pre_settings['show_result_count'] ) ? wlpf_cast( $pre_settings['show_result_count'], 'key' ) : '' );

        if ( empty( $columns ) ) {
            $columns = apply_filters( 'loop_shop_columns', wc_get_default_products_per_row() );
        }

        if ( empty( $rows ) ) {
            $rows = apply_filters( 'loop_shop_rows', wc_get_default_product_rows_per_page() );
        }

        $columns = apply_filters( 'wlpf_loop_shop_columns', $columns );
        $rows = apply_filters( 'wlpf_loop_shop_rows', $rows );

        $limit = apply_filters( 'wlpf_loop_shop_per_page', absint( $columns * $rows ) );

        $settings = array(
            'limit'             => $limit,
            'columns'           => $columns,
            'orderby'           => '',
            'order'             => '',
            'paginate'          => $paginate,
            'allow_order'       => $allow_order,
            'show_result_count' => $show_result_count,
        );

        return $settings;
    }
}

/**
 * Cast default archive settings.
 */
if ( ! function_exists( 'wlpf_cast_default_archive_settings' ) ) {
    function wlpf_cast_default_archive_settings() {
        $columns = '';
        $rows = '';
        $paginate = 'yes';
        $allow_order = 'yes';
        $show_result_count = 'yes';

        if ( empty( $columns ) ) {
            $columns = apply_filters( 'loop_shop_columns', wc_get_default_products_per_row() );
        }

        if ( empty( $rows ) ) {
            $rows = apply_filters( 'loop_shop_rows', wc_get_default_product_rows_per_page() );
        }

        $columns = apply_filters( 'wlpf_loop_shop_columns', $columns );
        $rows = apply_filters( 'wlpf_loop_shop_rows', $rows );

        $limit = apply_filters( 'loop_shop_per_page', absint( $columns * $rows ) );
        $limit = apply_filters( 'wlpf_loop_shop_per_page', $limit );

        $settings = array(
            'limit'             => $limit,
            'columns'           => $columns,
            'orderby'           => '',
            'order'             => '',
            'paginate'          => $paginate,
            'allow_order'       => $allow_order,
            'show_result_count' => $show_result_count,
        );

        return $settings;
    }
}

/**
 * Hooked paginate links.
 */
if ( ! function_exists( 'wlpf_hooked_paginate_links' ) ) {
    function wlpf_hooked_paginate_links( $link ) {
        return add_query_arg( array( 'wlpf_page' => false ), $link );
    }
}

/**
 * Hooked before shop loop.
 */
if ( ! function_exists( 'wlpf_hooked_before_shop_loop' ) ) {
    function wlpf_hooked_before_shop_loop() {
        global $wp_query;

        if ( is_object( $wp_query ) && is_main_query( $wp_query ) && ( $wp_query->is_tax() || $wp_query->is_archive() ) ) {
            echo '<div class="wlpf-products-wrap">';
        }
    }
}

/**
 * Hooked after shop loop.
 */
if ( ! function_exists( 'wlpf_hooked_after_shop_loop' ) ) {
    function wlpf_hooked_after_shop_loop() {
        global $wp_query;

        if ( is_object( $wp_query ) && is_main_query( $wp_query ) && ( $wp_query->is_tax() || $wp_query->is_archive() ) ) {
            echo '</div>';
        }
    }
}