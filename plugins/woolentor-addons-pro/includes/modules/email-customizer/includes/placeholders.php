<?php
/**
 * Placeholders.
 */

// Get common placeholders list.
if ( ! function_exists( 'woolentor_email_get_common_placeholders_list' ) ) {
    /**
     * Get common placeholders list.
     */
    function woolentor_email_get_common_placeholders_list( $email_type = '' ) {
        $list = array(
            '{site_title}',
            '{site_address}',
            '{site_url}',
            '{admin_email}',
            '{email_from_name}',
            '{email_from_address}',
        );

        return $list;
    }
}

// Get order placeholders list.
if ( ! function_exists( 'woolentor_email_get_order_placeholders_list' ) ) {
    /**
     * Get order placeholders list.
     */
    function woolentor_email_get_order_placeholders_list( $email_type = '' ) {
        $list = array(
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

        return $list;
    }
}

// Get customer placeholders list.
if ( ! function_exists( 'woolentor_email_get_customer_placeholders_list' ) ) {
    /**
     * Get customer placeholders list.
     */
    function woolentor_email_get_customer_placeholders_list( $email_type = '' ) {
        $list = array(
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

        return $list;
    }
}

// Get user placeholders list.
if ( ! function_exists( 'woolentor_email_get_user_placeholders_list' ) ) {
    /**
     * Get user placeholders list.
     */
    function woolentor_email_get_user_placeholders_list( $email_type = '' ) {
        $list = array();

        if ( ( 'customer_new_account' !== $email_type ) && ( 'customer_reset_password' !== $email_type ) ) {
            return $list;
        }

        $list = array(
            '{user_id}',
            '{username}',
            '{user_login}',
            '{user_email}',
            '{user_nicename}',
            '{user_display_name}',
        );

        if ( 'customer_new_account' === $email_type ) {
            $list[] = '{user_password}';
        }

        $list[] = '{user_registered_date}';
        $list[] = '{user_registered_time}';

        if ( 'customer_new_account' === $email_type ) {
            $list[] = '{set_password_url}';
            $list[] = '{set_password_link}';
        }

        if ( 'customer_reset_password' === $email_type ) {
            $list[] = '{reset_password_url}';
            $list[] = '{reset_password_link}';
        }

        return $list;
    }
}

// Get woocommerce placeholders list.
if ( ! function_exists( 'woolentor_email_get_woocommerce_placeholders_list' ) ) {
    /**
     * Get woocommerce placeholders list.
     */
    function woolentor_email_get_woocommerce_placeholders_list() {
        $list = array(
            '{shop_page_url}',
            '{cart_page_url}',
            '{checkout_page_url}',
            '{my_account_page_url}',
            '{woocommerce}',
            '{WooCommerce}',
        );

        return $list;
    }
}

// Get placeholders list.
if ( ! function_exists( 'woolentor_email_get_placeholders_list' ) ) {
    /**
     * Get placeholders list.
     */
    function woolentor_email_get_placeholders_list( $contexts = array(), $email_type = '' ) {
        $list = array();

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'common', $contexts ) ) {
            $common = woolentor_email_get_common_placeholders_list( $email_type );
            $list = array_merge( $list, $common );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'order', $contexts ) ) {
            $order = woolentor_email_get_order_placeholders_list( $email_type );
            $list = array_merge( $list, $order );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'customer', $contexts ) ) {
            $customer = woolentor_email_get_customer_placeholders_list( $email_type );
            $list = array_merge( $list, $customer );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'user', $contexts ) ) {
            $user = woolentor_email_get_user_placeholders_list( $email_type );
            $list = array_merge( $list, $user );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'woocommerce', $contexts ) ) {
            $woocommerce = woolentor_email_get_woocommerce_placeholders_list( $email_type );
            $list = array_merge( $list, $woocommerce );
        }

        return $list;
    }
}

// Get placeholders list as HTML.
if ( ! function_exists( 'woolentor_email_get_placeholders_list_as_html' ) ) {
    /**
     * Get placeholders list as HTML.
     */
    function woolentor_email_get_placeholders_list_as_html( $contexts = array(), $email_type = '' ) {
        $html = '';
        $lists = array();

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'common', $contexts ) ) {
            $lists['common'] = array(
                'head'  => esc_html__( 'Common', 'woolentor-pro' ),
                'items' => woolentor_email_get_common_placeholders_list( $email_type ),
            );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'order', $contexts ) ) {
            $lists['order'] = array(
                'head'  => esc_html__( 'Order', 'woolentor-pro' ),
                'items' => woolentor_email_get_order_placeholders_list( $email_type ),
            );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'customer', $contexts ) ) {
            $lists['customer'] = array(
                'head'  => esc_html__( 'Customer', 'woolentor-pro' ),
                'items' => woolentor_email_get_customer_placeholders_list( $email_type ),
            );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'user', $contexts ) ) {
            $lists['user'] = array(
                'head'  => esc_html__( 'User', 'woolentor-pro' ),
                'items' => woolentor_email_get_user_placeholders_list( $email_type ),
            );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'woocommerce', $contexts ) ) {
            $lists['woocommerce'] = array(
                'head'  => esc_html__( 'WooCommerce', 'woolentor-pro' ),
                'items' => woolentor_email_get_woocommerce_placeholders_list( $email_type ),
            );
        }

        if ( ! empty( $lists ) ) {
            foreach ( $lists as $list ) {
                $head = $list['head'];
                $items = $list['items'];

                if ( ! empty( $items ) ) {
                    $html .= '<div class="woolentor-email-placeholders-list">';
                    $html .= '<div class="woolentor-email-placeholders-head">' . esc_html( $head ) . '</div>';

                    foreach ( $items as $item ) {
                        $html .= '<div class="woolentor-email-placeholder-item">';
                        $html .= '<div class="woolentor-email-placeholder-content"><code>' . esc_html( $item ) . '</code></div>';
                        $html .= '<div class="woolentor-email-placeholder-action">';
                        $html .= '<div class="woolentor-email-placeholder-copy" title="' . esc_html__( 'Copy', 'woolentor-pro' ) . '">';
                        $html .= '<span class="woolentor-email-icon woolentor-email-icon-copy"></span>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }

                    $html .= '</div>';
                }
            }

            if ( ! empty( $html ) ) {
                $html = '<div class="woolentor-email-placeholders">' . $html . '</div>';
                $html .= '<script>!function(e){"use strict";e(".woolentor-email-placeholder-copy").on("click",function(o){o.preventDefault();let l=e(this),c=l.closest(".woolentor-email-placeholder-item").find(".woolentor-email-placeholder-content").text(),t=e(\'<input class="woolentor-email-placeholder-copy-temp-input">\');"string"==typeof c&&0<c.length&&(e("body").append(t),t.val(c).select(),document.execCommand("copy"),t.remove(),l.addClass("woolentor-email-placeholder-copy-success"),l.removeClass("woolentor-email-placeholder-copy-error"),setTimeout(function(){l.removeClass("woolentor-email-placeholder-copy-success"),l.removeClass("woolentor-email-placeholder-copy-error")},500))})}(jQuery);</script>';
            } else {
                $html = '';
            }
        }

        return $html;
    }
}

// Get common placeholders.
if ( ! function_exists( 'woolentor_email_get_common_placeholders' ) ) {
    /**
     * Get common placeholders.
     */
    function woolentor_email_get_common_placeholders() {
        $domain = wp_parse_url( home_url(), PHP_URL_HOST );
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        $admin_email = get_option( 'admin_email' );

        $email = woolentor_email_get_email();

        if ( is_object( $email ) && ! empty( $email ) ) {
            $email_from_name = $email->get_from_name();
            $email_from_address = $email->get_from_address();
        } else {
            $email_from_name = '';
            $email_from_address = '';
        }

        $data = array(
            '{site_title}'         => $blogname,
            '{site_address}'       => $domain,
            '{site_url}'           => $domain,
            '{admin_email}'        => $admin_email,
            '{email_from_name}'    => $email_from_name,
            '{email_from_address}' => $email_from_address,
        );

        return $data;
    }
}

// Get order placeholders.
if ( ! function_exists( 'woolentor_email_get_order_placeholders' ) ) {
    /**
     * Get order placeholders.
     */
    function woolentor_email_get_order_placeholders() {
        $data = array();
        $order = woolentor_email_get_order();

        if ( ! is_object( $order ) || empty( $order ) || ! ( $order instanceof \WC_Order ) ) {
            return $data;
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

        $data = array(
            '{order_id}'             => $order_id,
            '{parent_order_id}'      => $parent_order_id,
            '{order_status}'         => $order_status,
            '{order_total}'          => $order_total,
            '{order_currency}'       => $order_currency,
            '{order_received_url}'   => $order_received_url,
            '{order_paid_date}'      => $order_paid_date,
            '{order_paid_time}'      => $order_paid_time,
            '{order_created_date}'   => $order_created_date,
            '{order_created_time}'   => $order_created_time,
            '{order_modified_date}'  => $order_modified_date,
            '{order_modified_time}'  => $order_modified_time,
            '{order_completed_date}' => $order_completed_date,
            '{order_completed_time}' => $order_completed_time,
            '{payment_url}'          => $payment_url,
            '{payment_method}'       => $payment_method,
            '{shipping_method}'      => $shipping_method,
            '{billing_address}'      => $billing_address,
            '{billing_first_name}'   => $billing_first_name,
            '{billing_last_name}'    => $billing_last_name,
            '{billing_company}'      => $billing_company,
            '{billing_address_1}'    => $billing_address_1,
            '{billing_address_2}'    => $billing_address_2,
            '{billing_city}'         => $billing_city,
            '{billing_state}'        => $billing_state,
            '{billing_postcode}'     => $billing_postcode,
            '{billing_country}'      => $billing_country,
            '{billing_email}'        => $billing_email,
            '{billing_phone}'        => $billing_phone,
            '{shipping_address}'     => $shipping_address,
            '{shipping_first_name}'  => $shipping_first_name,
            '{shipping_last_name}'   => $shipping_last_name,
            '{shipping_company}'     => $shipping_company,
            '{shipping_address_1}'   => $shipping_address_1,
            '{shipping_address_2}'   => $shipping_address_2,
            '{shipping_city}'        => $shipping_city,
            '{shipping_state}'       => $shipping_state,
            '{shipping_postcode}'    => $shipping_postcode,
            '{shipping_country}'     => $shipping_country,
            '{shipping_phone}'       => $shipping_phone,
        );

        return $data;
    }
}

// Get woocommerce placeholders.
if ( ! function_exists( 'woolentor_email_get_customer_placeholders' ) ) {
    /**
     * Get woocommerce placeholders.
     */
    function woolentor_email_get_customer_placeholders() {
        $data = array();
        $customer = woolentor_email_get_customer();

        if ( ! is_object( $customer ) || empty( $customer ) || ! ( $customer instanceof \WC_Customer ) ) {
            return $data;
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

        $data = array(
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
        );

        return $data;
    }
}

// Get user placeholders.
if ( ! function_exists( 'woolentor_email_get_user_placeholders' ) ) {
    /**
     * Get user placeholders.
     */
    function woolentor_email_get_user_placeholders() {
        $data = array();
        $email = woolentor_email_get_email();

        if ( ! is_object( $email ) || empty( $email ) ) {
            return $data;
        }

        $email_type = ( isset( $email->id ) ? $email->id : '' );

        if ( ( 'customer_new_account' !== $email_type ) && ( 'customer_reset_password' !== $email_type ) ) {
            return $data;
        }

        $user = ( isset( $email->object ) ? $email->object : null );

        if ( ! is_object( $user ) || empty( $user ) || ! ( $user instanceof \WP_User ) ) {
            return $data;
        }

        $user_id = ( isset( $user->ID ) ? $user->ID : 0 );
        $user_data = ( isset( $user->data ) ? $user->data : null );

        if ( empty( $user_id ) || ! is_object( $user_data ) ) {
            return $data;
        }

        $username = ( isset( $email->user_login ) ? $email->user_login : '' );
        $user_login = ( isset( $email->user_login ) ? $email->user_login : '' );
        $user_email = ( isset( $email->user_email ) ? $email->user_email : '' );
        $user_password = ( isset( $email->user_password ) ? $email->user_password : '' );

        $user_nicename = ( isset( $user_data->user_nicename ) ? $user_data->user_nicename : '' );
        $user_display_name = ( isset( $user_data->display_name ) ? $user_data->display_name : '' );

        $user_registered = ( isset( $user_data->user_registered ) ? $user_data->user_registered : '' );
        $user_registered = ( ! empty( $user_registered ) ? wc_string_to_datetime( $user_registered ) : '' );

        $user_registered_date = wc_format_datetime( $user_registered );
        $user_registered_time = wc_format_datetime( $user_registered, wc_time_format() );

        $data = array(
            '{user_id}'           => $user_id,
            '{username}'          => $username,
            '{user_login}'        => $user_login,
            '{user_email}'        => $user_email,
            '{user_nicename}'     => $user_nicename,
            '{user_display_name}' => $user_display_name,
        );

        if ( 'customer_new_account' === $email_type ) {
            $data['{user_password}'] = $user_password;
        }

        $data['{user_registered_date}'] = $user_registered_date;
        $data['{user_registered_time}'] = $user_registered_time;

        if ( 'customer_new_account' === $email_type ) {
            $set_password_url = $email->set_password_url;

            $data['{set_password_url}'] = $set_password_url;
            $data['{set_password_link}'] = $set_password_url;
        }

        if ( 'customer_reset_password' === $email_type ) {
            $reset_key = $email->reset_key;
            $reset_endpoint_url = wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) );

            $reset_password_url = esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), $reset_endpoint_url ) );

            $data['{reset_password_url}'] = $reset_password_url;
            $data['{reset_password_link}'] = $reset_password_url;
        }

        return $data;
    }
}

// Get woocommerce placeholders.
if ( ! function_exists( 'woolentor_email_get_woocommerce_placeholders' ) ) {
    /**
     * Get woocommerce placeholders.
     */
    function woolentor_email_get_woocommerce_placeholders() {
        $data = array();

        $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
        $cart_page_url = get_permalink( wc_get_page_id( 'cart' ) );
        $checkout_page_url = wc_get_checkout_url();
        $my_account_page_url = get_permalink( wc_get_page_id( 'myaccount' ) );
        $woocommerce = esc_html__( 'WooCommerce', 'woolentor-pro' );
        $woocommerce = sprintf( '<a href="https://woocommerce.com">%1$s</a>', $woocommerce );

        $data = array(
            '{shop_page_url}'       => $shop_page_url,
            '{cart_page_url}'       => $cart_page_url,
            '{checkout_page_url}'   => $checkout_page_url,
            '{my_account_page_url}' => $my_account_page_url,
            '{woocommerce}'         => $woocommerce,
            '{WooCommerce}'         => $woocommerce,
        );

        return $data;
    }
}

// Get placeholders.
if ( ! function_exists( 'woolentor_email_get_placeholders' ) ) {
    /**
     * Get placeholders.
     */
    function woolentor_email_get_placeholders( $contexts = array() ) {
        $data = array();

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'common', $contexts ) ) {
            $common = woolentor_email_get_common_placeholders();
            $data = array_merge( $data, $common );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'order', $contexts ) ) {
            $order = woolentor_email_get_order_placeholders();
            $data = array_merge( $data, $order );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'customer', $contexts ) ) {
            $customer = woolentor_email_get_customer_placeholders();
            $data = array_merge( $data, $customer );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'user', $contexts ) ) {
            $user = woolentor_email_get_user_placeholders();
            $data = array_merge( $data, $user );
        }

        if ( empty( $contexts ) || in_array( 'all', $contexts ) || in_array( 'woocommerce', $contexts ) ) {
            $woocommerce = woolentor_email_get_woocommerce_placeholders();
            $data = array_merge( $data, $woocommerce );
        }

        return $data;
    }
}

// Replace placeholders.
if ( ! function_exists( 'woolentor_email_replace_placeholders' ) ) {
    /**
     * Replace placeholders.
     */
    function woolentor_email_replace_placeholders( $content = '' ) {
        if ( empty( $content ) ) {
            return $content;
        }

        $order_id = woolentor_email_get_order_id();
        $placeholders = woolentor_email_get_placeholders();

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

// Strip placeholders.
if ( ! function_exists( 'woolentor_email_strip_placeholders' ) ) {
    /**
     * Strip placeholders.
     */
    function woolentor_email_strip_placeholders( $content = '' ) {
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
if ( ! function_exists( 'woolentor_email_replace_protocols' ) ) {
    /**
     * Replace protocols.
     */
    function woolentor_email_replace_protocols( $content = '' ) {
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

// Replace placeholders all.
if ( ! function_exists( 'woolentor_email_replace_placeholders_all' ) ) {
    /**
     * Replace placeholders all.
     */
    function woolentor_email_replace_placeholders_all( $content = '' ) {
        $content = woolentor_email_replace_placeholders( $content );
        $content = woolentor_email_strip_placeholders( $content );
        $content = woolentor_email_replace_protocols( $content );

        return $content;
    }
}