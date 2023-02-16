<?php
/**
 * Functions.
 */

// Get WooCommerce emails.
if ( ! function_exists( 'woolentor_wc_get_emails' ) ) {
/*
    * Get WooCommerce emails.
    */
function woolentor_wc_get_emails( $value = 'all' ) {
    $output = [];
    $value = sanitize_key( $value );

    $emails = WC_Emails::instance();
    $emails = $emails->get_emails();

    foreach ( $emails as $email ) {
        if ( ! is_object( $email ) ) {
            continue;
        }

        $email_id             = ( isset( $email->id ) ? sanitize_text_field( $email->id ) : '' );
        $email_title          = ( isset( $email->title ) ? sanitize_text_field( $email->title ) : '' );
        $email_type           = ( isset( $email->email_type ) ? sanitize_text_field( $email->email_type ) : '' );
        $email_template_html  = ( isset( $email->template_html ) ? sanitize_text_field( $email->template_html ) : '' );
        $email_template_plain = ( isset( $email->template_plain ) ? sanitize_text_field( $email->template_plain ) : '' );

        if ( empty( $email_id ) || empty( $email_title ) ) {
            continue;
        }

        if ( 'id' === $value ) {
            $output[ $email_id ] = $email_id;
        } elseif ( 'title' === $value ) {
            $output[ $email_id ] = $email_title;
        } elseif ( 'type' === $value ) {
            $output[ $email_id ] = $email_type;
        } elseif ( 'template_html' === $value ) {
            $output[ $email_id ] = $email_template_html;
        } elseif ( 'template_plain' === $value ) {
            $output[ $email_id ] = $email_template_plain;
        } else {
            $output[ $email_id ] = array(
                'id'             => $email_id,
                'title'          => $email_title,
                'type'           => $email_type,
                'template_html'  => $email_template_html,
                'template_plain' => $email_template_plain,
            );
        }
    }

    return $output;
}
}

// Is email customizer template.
if ( ! function_exists( 'woolentor_is_email_customizer_template' ) ) {
    /**
     * Is email customizer template.
     */
    function woolentor_is_email_customizer_template() {
        global $post;

        $post_id = ( isset( $post->ID ) ? absint( $post->ID ) : 0 );

        $type = get_post_meta( $post_id, 'woolentor_template_meta_type', true );
        $type = sanitize_text_field( $type );

        $emails = woolentor_wc_get_emails( 'id' );
        $emails = array_map( function ( $email ) { return 'email_' . $email; }, $emails );

        return ( in_array( $type, $emails, true ) ? true : false );
    }
}

// Get type.
if ( ! function_exists( 'woolentor_email_get_type' ) ) {
    /**
     * Get type.
     */
    function woolentor_email_get_type() {
        $email_type = '';

        if ( isset( $_REQUEST ) && is_array( $_REQUEST ) && isset( $_REQUEST['woolentor_email_type'] ) ) {
            $email_type = sanitize_text_field( $_REQUEST['woolentor_email_type'] );
        } else {
            global $post;
            $post_id = ( isset( $post->ID ) ? absint( $post->ID ) : 0 );

            if ( ! empty( $post_id ) ) {
                $template_type = get_post_meta( $post_id, 'woolentor_template_meta_type', true );
                $template_type = sanitize_text_field( $template_type );

                $email_type = str_replace( 'email_', '', $template_type );
            }
        }

        return $email_type;
    }
}

// Get order.
if ( ! function_exists( 'woolentor_email_get_order' ) ) {
    /**
     * Get order.
     */
    function woolentor_email_get_order() {
        $order = null;

        if ( isset( $_REQUEST ) && is_array( $_REQUEST ) && isset( $_REQUEST['woolentor_email_args'] ) ) {
            $args = ( is_array( $_REQUEST['woolentor_email_args'] ) ? $_REQUEST['woolentor_email_args'] : array() );
            $order = ( ( isset( $args['order'] ) && is_object( $args['order'] ) ) ? $args['order'] : null );
        } else {
            $order_id = woolentor_get_last_order_id();

            if ( ! empty( $order_id ) ) {
                $order = wc_get_order( $order_id );
            }
        }

        return $order;
    }
}

// Get email.
if ( ! function_exists( 'woolentor_email_get_email' ) ) {
    /**
     * Get email.
     */
    function woolentor_email_get_email() {
        $email = null;

        if ( isset( $_REQUEST ) && is_array( $_REQUEST ) && isset( $_REQUEST['woolentor_email_args'] ) ) {
            $args = ( is_array( $_REQUEST['woolentor_email_args'] ) ? $_REQUEST['woolentor_email_args'] : array() );
            $email = ( ( isset( $args['email'] ) && is_object( $args['email'] ) ) ? $args['email'] : null );
        } else {
            $email_type = woolentor_email_get_type();

            add_filter( 'woocommerce_email_recipient_' . $email_type, '__return_empty_string', 10, 2 );
            add_filter( 'woocommerce_email_enabled_' . $email_type, '__return_false', 10, 2 );

            if ( isset( $GLOBALS['wc_advanced_notifications'] ) ) {
                unset( $GLOBALS['wc_advanced_notifications'] );
            }

            $emails = new \WC_Emails();

            if ( is_object( $emails ) && method_exists( $emails, 'get_emails' ) ) {
                $emails = $emails->get_emails();

                if ( is_array( $emails ) && ! empty( $emails ) ) {
                    foreach ( $emails as $email_obj ) {
                        if ( ! is_object( $email_obj ) || ! isset( $email_obj->id ) ) {
                            continue;
                        }

                        $email_id = isset( $email_obj->id ) ? sanitize_text_field( $email_obj->id ) : '';

                        if ( $email_id === $email_type ) {
                            $email = $email_obj;
                            break;
                        }
                    }
                }
            }
        }

        return $email;
    }
}

// Is sent to admin.
if ( ! function_exists( 'woolentor_email_is_sent_to_admin' ) ) {
    /**
     * Is send to admin.
     */
    function woolentor_email_is_sent_to_admin() {
        $sent_to_admin = false;

        if ( isset( $_REQUEST ) && is_array( $_REQUEST ) && isset( $_REQUEST['woolentor_email_args'] ) ) {
            $args = ( is_array( $_REQUEST['woolentor_email_args'] ) ? $_REQUEST['woolentor_email_args'] : array() );
            $sent_to_admin = ( isset( $args['sent_to_admin'] ) ? rest_sanitize_boolean( $args['sent_to_admin'] ) : false );
        } else {
            $email = woolentor_email_get_email();
            $sent_to_admin = ( ! $email->is_customer_email() ? true : false );
        }

        return $sent_to_admin;
    }
}

// Is plain text.
if ( ! function_exists( 'woolentor_email_is_plain_text' ) ) {
    /**
     * Is plain text.
     */
    function woolentor_email_is_plain_text() {
        $plain_text = false;

        if ( isset( $_REQUEST ) && is_array( $_REQUEST ) && isset( $_REQUEST['woolentor_email_args'] ) ) {
            $args = ( is_array( $_REQUEST['woolentor_email_args'] ) ? $_REQUEST['woolentor_email_args'] : array() );
            $plain_text = ( isset( $args['plain_text'] ) ? rest_sanitize_boolean( $args['plain_text'] ) : false );
        } else {
            $email = woolentor_email_get_email();
            $plain_text = ( ( 'plain' === $email->get_email_type() ) ? true : false );
        }

        return $plain_text;
    }
}

// Get order ID.
if ( ! function_exists( 'woolentor_email_get_order_id' ) ) {
    /**
     * Get order ID.
     */
    function woolentor_email_get_order_id() {
        $order = woolentor_email_get_order();
        $order_id = ( ( is_object( $order ) && method_exists( $order, 'get_id' ) ) ? $order->get_id() : 0 );

        return $order_id;
    }
}

// Get order status.
if ( ! function_exists( 'woolentor_email_get_order_status' ) ) {
    /**
     * Get order status.
     */
    function woolentor_email_get_order_status() {
        $order = woolentor_email_get_order();
        $order_status = ( ( is_object( $order ) && method_exists( $order, 'get_status' ) ) ? $order->get_status() : '' );

        return $order_status;
    }
}

// Is order needs payment.
if ( ! function_exists( 'woolentor_email_is_order_needs_payment' ) ) {
    /**
     * Is order needs payment.
     */
    function woolentor_email_is_order_needs_payment() {
        $order = woolentor_email_get_order();
        $needs_payment = ( ( is_object( $order ) && method_exists( $order, 'needs_payment' ) ) ? $order->needs_payment() : false );

        return $needs_payment;
    }
}

// Get order data.
if ( ! function_exists( 'woolentor_email_get_order_data' ) ) {
    /**
     * Get order data.
     */
    function woolentor_email_get_order_data() {
        $order = woolentor_email_get_order();
        $order_data = ( ( is_object( $order ) && method_exists( $order, 'get_data' ) ) ? $order->get_data() : array() );

        return $order_data;
    }
}

// Get customer.
if ( ! function_exists( 'woolentor_email_get_customer' ) ) {
    /**
     * Get customer.
     */
    function woolentor_email_get_customer() {
        $customer = null;

        $order = woolentor_email_get_order();
        $customer_id = ( ( is_object( $order ) && method_exists( $order, 'get_customer_id' ) ) ? $order->get_customer_id() : 0 );

        $customer = new \WC_Customer( $customer_id );

        return $customer;
    }
}

// Get conditions order statuses.
if ( ! function_exists( 'woolentor_email_get_conditions_order_statuses' ) ) {
    /**
     * Get conditions order statuses.
     */
    function woolentor_email_get_conditions_order_statuses() {
        $statuses = array();
        $wc_statuses = wc_get_order_statuses();

        foreach ( $wc_statuses as $wc_status_key => $wc_status_label ) {
            $wc_status_key = 'wc-' === substr( $wc_status_key, 0, 3 ) ? substr( $wc_status_key, 3 ) : $wc_status_key;
            $statuses[ $wc_status_key ] = $wc_status_label;
        }

        return $statuses;
    }
}

// Get conditions payment statuses.
if ( ! function_exists( 'woolentor_email_get_conditions_payment_statuses' ) ) {
    /**
     * Get conditions payment statuses.
     */
    function woolentor_email_get_conditions_payment_statuses() {
        $statuses = array(
            'pending'   => esc_html__( 'Pending', 'woolentor-pro' ),
            'completed' => esc_html__( 'Completed', 'woolentor-pro' ),
        );

        return $statuses;
    }
}

// Widget conditions.
if ( ! function_exists( 'woolentor_email_widget_conditions' ) ) {
    /**
     * Widget conditions.
     */
    function woolentor_email_widget_conditions( $settings = array() ) {
        $order_status = woolentor_email_get_order_status();
        $is_order_needs_payment = woolentor_email_is_order_needs_payment();

        $conditions_order_status = isset( $settings['conditions_order_status'] ) ? rest_sanitize_boolean( $settings['conditions_order_status'] ) : false;
        $conditions_payment_status = isset( $settings['conditions_payment_status'] ) ? rest_sanitize_boolean( $settings['conditions_payment_status'] ) : false;

        $conditions_order_statuses = isset( $settings['conditions_order_statuses'] ) ? $settings['conditions_order_statuses'] : array();
        $conditions_order_statuses = ( is_array( $conditions_order_statuses ) && ! empty( $conditions_order_statuses ) ) ? $conditions_order_statuses : array();

        $conditions_payment_statuses = isset( $settings['conditions_payment_statuses'] ) ? $settings['conditions_payment_statuses'] : array();
        $conditions_payment_statuses = ( is_array( $conditions_payment_statuses ) && ! empty( $conditions_payment_statuses ) ) ? $conditions_payment_statuses : array();

        $order_status_match = false;
        $payment_status_match = false;

        if ( false == $conditions_order_status || empty( $conditions_order_statuses ) || in_array( $order_status, $conditions_order_statuses ) ) {
            $order_status_match = true;
        }

        if ( false === $conditions_payment_status || empty( $conditions_payment_statuses ) || ( true === $is_order_needs_payment && in_array( 'pending', $conditions_payment_statuses ) ) || ( false === $is_order_needs_payment && in_array( 'completed', $conditions_payment_statuses ) ) ) {
            $payment_status_match = true;
        }

        return ( ( true === $order_status_match && true === $payment_status_match ) ? true : false );
    }
}

// No order found notice HTML.
if ( ! function_exists( 'woolentor_email_no_order_found_notice_html' ) ) {
    /**
     * No order found notice HTML.
     */
    function woolentor_email_no_order_found_notice_html() {
        return esc_html__( 'No order found in your store. Please create an order to edit this template with full features.', 'woolentor-pro' );
    }
}

// String to array of ID.
if ( ! function_exists( 'woolentor_email_string_to_array_of_id' ) ) {
    /**
     * String to array of ID.
     */
    function woolentor_email_string_to_array_of_id( $string = '' ) {
        $id_array = array();

        if ( is_string( $string ) && ! empty( trim( $string ) ) ) {
            $string = str_replace( ' ', '', $string );
            $string = rtrim( $string, ',' );
            $string_array = explode( ',', $string );

            foreach ( $string_array as $item ) {
                $item = absint( $item );

                if ( 0 !== $item ) {
                    $id_array[] = $item;
                }
            }
        }

        return $id_array;
    }
}