<?php
/**
 * Settings.
 */

// Get settings.
if ( ! function_exists( 'wlpf_get_settings' ) ) {
    /**
     * Get settings.
     */
    function wlpf_get_settings() {
        $settings = get_option( 'woolentor_product_filter_settings' );
        $settings = wlpf_cast( $settings, 'array' );

        return $settings;
    }
}

// Get filters.
if ( ! function_exists( 'wlpf_get_filters' ) ) {
    /**
     * Get filters.
     */
    function wlpf_get_filters() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['filters'] ) ? wlpf_cast( $settings['filters'], 'array' ) : array() );

        return $output;
    }
}

// Get filters list.
if ( ! function_exists( 'wlpf_get_filters_list' ) ) {
    /**
     * Get filters list.
     */
    function wlpf_get_filters_list() {
        $output = array();

        $filters = wlpf_get_filters();

        if ( is_array( $filters ) && ! empty( $filters ) ) {
            foreach ( $filters as $filter ) {
                $label     = ( isset( $filter['filter_label'] ) ? wlpf_cast( $filter['filter_label'], 'text' ) : '' );
                $unique_id = ( isset( $filter['filter_unique_id'] ) ? wlpf_cast( $filter['filter_unique_id'], 'absint' ) : 0 );

                if ( empty( $unique_id ) ) {
                    continue;
                }

                $title = '';

                if ( 0 < strlen( $label ) ) {
                    $title = wlpf_get_item_title_with_label_structure();
                } else {
                    $title = wlpf_get_item_title_structure();
                }

                $title = str_replace( array( '_WLPF_ID_', '_WLPF_LABEL_' ), array( $unique_id, $label ), $title );

                $output[ $unique_id ] = $title;
            }
        }

        return $output;
    }
}

// Get groups.
if ( ! function_exists( 'wlpf_get_groups' ) ) {
    /**
     * Get groups.
     */
    function wlpf_get_groups() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['groups'] ) ? wlpf_cast( $settings['groups'], 'array' ) : array() );

        return $output;
    }
}

// Get groups list.
if ( ! function_exists( 'wlpf_get_groups_list' ) ) {
    /**
     * Get groups list.
     */
    function wlpf_get_groups_list() {
        $output = array();

        $groups = wlpf_get_groups();

        if ( is_array( $groups ) && ! empty( $groups ) ) {
            foreach ( $groups as $group ) {
                $label     = ( isset( $group['group_label'] ) ? wlpf_cast( $group['group_label'], 'text' ) : '' );
                $unique_id = ( isset( $group['group_unique_id'] ) ? wlpf_cast( $group['group_unique_id'], 'absint' ) : 0 );

                if ( empty( $unique_id ) ) {
                    continue;
                }

                $title = '';

                if ( 0 < strlen( $label ) ) {
                    $title = wlpf_get_item_title_with_label_structure();
                } else {
                    $title = wlpf_get_item_title_structure();
                }

                $title = str_replace( array( '_WLPF_ID_', '_WLPF_LABEL_' ), array( $unique_id, $label ), $title );

                $output[ $unique_id ] = $title;
            }
        }

        return $output;
    }
}

// Get ajax filter.
if ( ! function_exists( 'wlpf_get_ajax_filter' ) ) {
    /**
     * Get ajax filter.
     */
    function wlpf_get_ajax_filter() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['ajax_filter'] ) ? wlpf_cast( $settings['ajax_filter'], 'bool' ) : true );

        return $output;
    }
}

// Get add ajax query args to url.
if ( ! function_exists( 'wlpf_get_add_ajax_query_args_to_url' ) ) {
    /**
     * Get add ajax query args to url.
     */
    function wlpf_get_add_ajax_query_args_to_url() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['add_ajax_query_args_to_url'] ) ? wlpf_cast( $settings['add_ajax_query_args_to_url'], 'bool' ) : true );

        return $output;
    }
}

// Get time to take ajax action.
if ( ! function_exists( 'wlpf_get_time_to_take_ajax_action' ) ) {
    /**
     * Get time to take ajax action.
     */
    function wlpf_get_time_to_take_ajax_action() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['time_to_take_ajax_action'] ) ? wlpf_cast( $settings['time_to_take_ajax_action'], 'absint' ) : 500 );

        return $output;
    }
}

// Get time to take none ajax action.
if ( ! function_exists( 'wlpf_get_time_to_take_none_ajax_action' ) ) {
    /**
     * Get time to take none ajax action.
     */
    function wlpf_get_time_to_take_none_ajax_action() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['time_to_take_none_ajax_action'] ) ? wlpf_cast( $settings['time_to_take_none_ajax_action'], 'absint' ) : 1000 );

        return $output;
    }
}

// Get products wrapper selector.
if ( ! function_exists( 'wlpf_get_products_wrapper_selector' ) ) {
    /**
     * Get products wrapper selector.
     */
    function wlpf_get_products_wrapper_selector() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['products_wrapper_selector'] ) ? wlpf_cast( $settings['products_wrapper_selector'], 'text' ) : '.wlpf-products-wrap' );
        $output = ( ( ! empty( $output ) && ( '.wl-filterable-products-wrap' !== $output ) ) ? $output : '.wlpf-products-wrap' );

        return $output;
    }
}

// Get show filter arguments.
if ( ! function_exists( 'wlpf_get_show_filter_arguments' ) ) {
    /**
     * Get show filter arguments.
     */
    function wlpf_get_show_filter_arguments() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['show_filter_arguments'] ) ? wlpf_cast( $settings['show_filter_arguments'], 'bool' ) : true );

        return $output;
    }
}

// Get query args prefix.
if ( ! function_exists( 'wlpf_get_query_args_prefix' ) ) {
    /**
     * Get query args prefix.
     */
    function wlpf_get_query_args_prefix() {
        $settings = wlpf_get_settings();

        $output = ( isset( $settings['query_args_prefix'] ) ? wlpf_cast( $settings['query_args_prefix'], 'text' ) : 'wlpf_' );
        $output = ( ! empty( $output ) ? $output : 'wlpf_' );

        return $output;
    }
}