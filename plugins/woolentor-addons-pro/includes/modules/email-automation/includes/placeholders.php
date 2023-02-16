<?php
/**
 * Placeholders.
 */

// Get placeholders list.
if ( ! function_exists( 'wlea_get_placeholders_list' ) ) {
    /**
     * Get placeholders list.
     */
    function wlea_get_placeholders_list( $context = 'all' ) {
        $common = array(
            '{site_title}',
            '{site_address}',
            '{site_url}',
            '{admin_email}',
            '{email_from_name}',
            '{email_from_address}',
        );

        $order = array(
            '{order_id}',
            '{parent_order_id}',
            '{order_status}',
            '{order_total}',
            '{order_currency}',
            '{order_received_url}',
            '{order_paid_date}',
            '{order_paid_time}',
            '{order_created_date}',
            '{order_created_time}',
            '{order_modified_date}',
            '{order_modified_time}',
            '{order_completed_date}',
            '{order_completed_time}',
            '{payment_url}',
            '{payment_method}',
            '{shipping_method}',
            '{billing_address}',
            '{billing_first_name}',
            '{billing_last_name}',
            '{billing_company}',
            '{billing_address_1}',
            '{billing_address_2}',
            '{billing_city}',
            '{billing_state}',
            '{billing_postcode}',
            '{billing_country}',
            '{billing_email}',
            '{billing_phone}',
            '{shipping_address}',
            '{shipping_first_name}',
            '{shipping_last_name}',
            '{shipping_company}',
            '{shipping_address_1}',
            '{shipping_address_2}',
            '{shipping_city}',
            '{shipping_state}',
            '{shipping_postcode}',
            '{shipping_country}',
            '{shipping_phone}',
        );

        $customer = array(
            '{customer_first_name}',
            '{customer_last_name}',
            '{customer_display_name}',
            '{customer_email}',
            '{customer_username}',
            '{customer_role}',
            '{customer_created_date}',
            '{customer_created_time}',
            '{customer_modified_date}',
            '{customer_modified_time}',
        );

        if ( 'common' === $context ) {
            return $common;
        } elseif( 'order' === $context ) {
            return $order;
        } elseif( 'customer' === $context ) {
            return $customer;
        } else {
            $all = array_merge( $common, $order );
            $all = array_merge( $all, $customer );

            return $all;
        }
    }
}

// Get order placeholders.
if ( ! function_exists( 'wlea_get_order_placeholders' ) ) {
    /**
     * Get order placeholders.
     */
    function wlea_get_order_placeholders( $order_id = 0 ) {
        $domain = wp_parse_url( home_url(), PHP_URL_HOST );
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        $admin_email = get_option( 'admin_email' );

        $email_from_name = wlea_get_email_from_name();
        $email_from_address = wlea_get_email_from_address();

        $placeholders = array(
            '{site_title}'         => $blogname,
            '{site_address}'       => $domain,
            '{site_url}'           => $domain,
            '{admin_email}'        => $admin_email,
            '{email_from_name}'    => $email_from_name,
            '{email_from_address}' => $email_from_address,
        );

        $order = wc_get_order( $order_id );

        if ( ! is_object( $order ) || empty( $order ) || ! ( $order instanceof \WC_Order ) ) {
            return $placeholders;
        }

        $order_id = $order->get_id();
        $parent_order_id = $order->get_parent_id();
        $order_status = $order->get_status();

        $order_total = $order->get_total();
        $order_currency = $order->get_currency();
        $order_received_url = $order->get_checkout_order_received_url();

        $order_paid_date = wc_format_datetime( $order->get_date_paid() );
        $order_paid_time = wc_format_datetime( $order->get_date_paid(), wc_time_format() );
        $order_created_date = wc_format_datetime( $order->get_date_created() );
        $order_created_time = wc_format_datetime( $order->get_date_created(), wc_time_format() );
        $order_modified_date = wc_format_datetime( $order->get_date_modified() );
        $order_modified_time = wc_format_datetime( $order->get_date_modified(), wc_time_format() );
        $order_completed_date = wc_format_datetime( $order->get_date_completed() );
        $order_completed_time = wc_format_datetime( $order->get_date_completed(), wc_time_format() );

        $payment_url = $order->get_checkout_payment_url();
        $payment_method = $order->get_payment_method();
        $shipping_method = $order->get_shipping_method();

        $billing_address = $order->get_formatted_billing_address();
        $billing_first_name = $order->get_billing_first_name();
        $billing_last_name = $order->get_billing_last_name();
        $billing_company = $order->get_billing_company();
        $billing_address_1 = $order->get_billing_address_1();
        $billing_address_2 = $order->get_billing_address_2();
        $billing_city = $order->get_billing_city();
        $billing_state = $order->get_billing_state();
        $billing_postcode = $order->get_billing_postcode();
        $billing_country = $order->get_billing_country();
        $billing_email = $order->get_billing_email();
        $billing_phone = $order->get_billing_phone();

        $shipping_address = $order->get_formatted_shipping_address();
        $shipping_first_name = $order->get_shipping_first_name();
        $shipping_last_name = $order->get_shipping_last_name();
        $shipping_company = $order->get_shipping_company();
        $shipping_address_1 = $order->get_shipping_address_1();
        $shipping_address_2 = $order->get_shipping_address_2();
        $shipping_city = $order->get_shipping_city();
        $shipping_state = $order->get_shipping_state();
        $shipping_postcode = $order->get_shipping_postcode();
        $shipping_country = $order->get_shipping_country();
        $shipping_phone = $order->get_shipping_phone();

        $placeholders = array_merge( $placeholders, array(
            '{order_id}'               => $order_id,
            '{parent_order_id}'        => $parent_order_id,
            '{order_status}'           => $order_status,
            '{order_total}'            => $order_total,
            '{order_currency}'         => $order_currency,
            '{order_received_url}'     => $order_received_url,
            '{order_paid_date}'        => $order_paid_date,
            '{order_paid_time}'        => $order_paid_time,
            '{order_created_date}'     => $order_created_date,
            '{order_created_time}'     => $order_created_time,
            '{order_modified_date}'    => $order_modified_date,
            '{order_modified_time}'    => $order_modified_time,
            '{order_completed_date}'   => $order_completed_date,
            '{order_completed_time}'   => $order_completed_time,
            '{payment_url}'            => $payment_url,
            '{payment_method}'         => $payment_method,
            '{shipping_method}'        => $shipping_method,
            '{billing_address}'        => $billing_address,
            '{billing_first_name}'     => $billing_first_name,
            '{billing_last_name}'      => $billing_last_name,
            '{billing_company}'        => $billing_company,
            '{billing_address_1}'      => $billing_address_1,
            '{billing_address_2}'      => $billing_address_2,
            '{billing_city}'           => $billing_city,
            '{billing_state}'          => $billing_state,
            '{billing_postcode}'       => $billing_postcode,
            '{billing_country}'        => $billing_country,
            '{billing_email}'          => $billing_email,
            '{billing_phone}'          => $billing_phone,
            '{shipping_address}'       => $shipping_address,
            '{shipping_first_name}'    => $shipping_first_name,
            '{shipping_last_name}'     => $shipping_last_name,
            '{shipping_company}'       => $shipping_company,
            '{shipping_address_1}'     => $shipping_address_1,
            '{shipping_address_2}'     => $shipping_address_2,
            '{shipping_city}'          => $shipping_city,
            '{shipping_state}'         => $shipping_state,
            '{shipping_postcode}'      => $shipping_postcode,
            '{shipping_country}'       => $shipping_country,
            '{shipping_phone}'         => $shipping_phone,
        ) );

        $customer_id = $order->get_customer_id();

        if ( empty( $customer_id ) ) {
            return $placeholders;
        }

        $customer = wlea_get_customer_by_id( $customer_id );

        if ( ! is_object( $customer ) || empty( $customer ) || ! ( $customer instanceof \WC_Customer ) ) {
            return $placeholders;
        }

        $customer_first_name = $customer->get_first_name();
        $customer_last_name = $customer->get_last_name();
        $customer_display_name = $customer->get_display_name();
        $customer_email = $customer->get_email();
        $customer_username = $customer->get_username();
        $customer_role = $customer->get_role();
        $customer_created_date = wc_format_datetime( $customer->get_date_created() );
        $customer_created_time = wc_format_datetime( $customer->get_date_created(), wc_time_format() );
        $customer_modified_date = wc_format_datetime( $customer->get_date_modified() );
        $customer_modified_time = wc_format_datetime( $customer->get_date_modified(), wc_time_format() );

        $placeholders = array_merge( $placeholders, array(
            '{customer_first_name}'    => $customer_first_name,
            '{customer_last_name}'     => $customer_last_name,
            '{customer_display_name}'  => $customer_display_name,
            '{customer_email}'         => $customer_email,
            '{customer_username}'      => $customer_username,
            '{customer_role}'          => $customer_role,
            '{customer_created_date}'  => $customer_created_date,
            '{customer_created_time}'  => $customer_created_time,
            '{customer_modified_date}' => $customer_modified_date,
            '{customer_modified_time}' => $customer_modified_time,
        ) );

        return $placeholders;
    }
}

// Get customer placeholders.
if ( ! function_exists( 'wlea_get_customer_placeholders' ) ) {
    /**
     * Get customer placeholders.
     */
    function wlea_get_customer_placeholders( $customer_id = 0 ) {
        $domain = wp_parse_url( home_url(), PHP_URL_HOST );
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        $admin_email = get_option( 'admin_email' );

        $email_from_name = wlea_get_email_from_name();
        $email_from_address = wlea_get_email_from_address();

        $placeholders = array(
            '{site_title}'         => $blogname,
            '{site_address}'       => $domain,
            '{site_url}'           => $domain,
            '{admin_email}'        => $admin_email,
            '{email_from_name}'    => $email_from_name,
            '{email_from_address}' => $email_from_address,
        );

        $customer = wlea_get_customer_by_id( $customer_id );

        if ( ! is_object( $customer ) || empty( $customer ) || ! ( $customer instanceof \WC_Customer ) ) {
            return $placeholders;
        }

        $customer_first_name = $customer->get_first_name();
        $customer_last_name = $customer->get_last_name();
        $customer_display_name = $customer->get_display_name();
        $customer_email = $customer->get_email();
        $customer_username = $customer->get_username();
        $customer_role = $customer->get_role();
        $customer_created_date = wc_format_datetime( $customer->get_date_created() );
        $customer_created_time = wc_format_datetime( $customer->get_date_created(), wc_time_format() );
        $customer_modified_date = wc_format_datetime( $customer->get_date_modified() );
        $customer_modified_time = wc_format_datetime( $customer->get_date_modified(), wc_time_format() );

        $placeholders = array_merge( $placeholders, array(
            '{customer_first_name}'    => $customer_first_name,
            '{customer_last_name}'     => $customer_last_name,
            '{customer_display_name}'  => $customer_display_name,
            '{customer_email}'         => $customer_email,
            '{customer_username}'      => $customer_username,
            '{customer_role}'          => $customer_role,
            '{customer_created_date}'  => $customer_created_date,
            '{customer_created_time}'  => $customer_created_time,
            '{customer_modified_date}' => $customer_modified_date,
            '{customer_modified_time}' => $customer_modified_time,
        ) );

        return $placeholders;
    }
}

// Replace order placeholders.
if ( ! function_exists( 'wlea_replace_order_placeholders' ) ) {
    /**
     * Replace order placeholders.
     */
    function wlea_replace_order_placeholders( $content = '', $order_id = 0 ) {
        $content = wlea_cast( $content, 'text', false );
        $order_id = wlea_cast( $order_id, 'absint' );

        if ( empty( $content ) || empty( $order_id ) ) {
            return $content;
        }

        $placeholders = wlea_get_order_placeholders( $order_id );

        $search = array_keys( $placeholders );
        $replace = array_values( $placeholders );

        if ( ! empty( $search ) || ! empty( $replace ) ) {
            $content = str_replace( $search, $replace, $content );
        }

        $meta_keys = array();

        preg_match_all( '/\{([^}]+)\}/', $content, $meta_keys, PREG_SET_ORDER, 0 );

        if ( is_array( $meta_keys ) && ! empty( $meta_keys ) ) {
            $search = array();
            $replace = array();

            $meta_keys = array_map( function ( $value ) {
                return ( ( is_array( $value ) && isset( $value[1] ) && ! empty( $value[1] ) ) ? $value[1] : '' );
            }, $meta_keys );

            foreach ( $meta_keys as $meta_key ) {
                $meta_value = wptexturize( get_post_meta( $order_id, $meta_key, true ) );

                if ( is_string( $meta_value ) && ( 1 < strlen( $meta_value ) ) ) {
                    $search[] = sprintf( '{%1$s}', $meta_key );
                    $replace[] = $meta_value;
                }
            }

            if ( ! empty( $search ) && ! empty( $replace ) ) {
                $content = str_replace( $search, $replace, $content );
            }
        }

        return $content;
    }
}

// Replace customer placeholders.
if ( ! function_exists( 'wlea_replace_customer_placeholders' ) ) {
    /**
     * Replace customer placeholders.
     */
    function wlea_replace_customer_placeholders( $content = '', $customer_id = 0 ) {
        $content = wlea_cast( $content, 'text', false );
        $customer_id = wlea_cast( $customer_id, 'absint' );

        if ( empty( $content ) || empty( $customer_id ) ) {
            return $content;
        }

        $placeholders = wlea_get_order_placeholders( $customer_id );

        $search = array_keys( $placeholders );
        $replace = array_values( $placeholders );

        if ( empty( $search ) || empty( $replace ) ) {
            return $content;
        }

        $content = str_replace( $search, $replace, $content );

        return $content;
    }
}

// Strip placeholders.
if ( ! function_exists( 'wlea_strip_placeholders' ) ) {
    /**
     * Strip placeholders.
     */
    function wlea_strip_placeholders( $content = '' ) {
        $content = wlea_cast( $content, 'text', false );

        if ( empty( $content ) ) {
            return $content;
        }

        $keys = array();

        preg_match_all( '/\{([^}]+)\}/', $content, $keys, PREG_SET_ORDER, 0 );

        if ( ! is_array( $keys ) || empty( $keys ) ) {
            return $content;
        }

        $search = array();
        $replace = array();

        $keys = array_map( function ( $value ) {
            return ( ( is_array( $value ) && isset( $value[1] ) && ! empty( $value[1] ) ) ? $value[1] : '' );
        }, $keys );

        foreach ( $keys as $key ) {
            $search[] = sprintf( '{%1$s}', $key );
            $replace[] = '';
        }

        if ( empty( $search ) || empty( $replace ) ) {
            return $content;
        }

        $content = str_replace( $search, $replace, $content );

        return $content;
    }
}

// Replace protocols.
if ( ! function_exists( 'wlea_replace_protocols' ) ) {
    /**
     * Replace protocols.
     */
    function wlea_replace_protocols( $content = '' ) {
        $content = wlea_cast( $content, 'text', false );

        if ( empty( $content ) ) {
            return $content;
        }

        if ( false !== strpos( $content, 'http://http://' ) ) {
            $content = str_replace( 'http://http://', 'http://', $content );
        } elseif ( false !== strpos( $content, 'https://https://' ) ) {
            $content = str_replace( 'https://https://', 'https://', $content );
        } elseif ( false !== strpos( $content, 'https://http://' ) ) {
            $content = str_replace( 'https://http://', 'http://', $content );
        } elseif ( false !== strpos( $content, 'http://https://' ) ) {
            $content = str_replace( 'http://https://', 'https://', $content );
        }

        return $content;
    }
}