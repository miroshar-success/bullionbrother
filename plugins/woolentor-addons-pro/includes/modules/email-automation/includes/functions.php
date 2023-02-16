<?php
/**
 * Functions.
 */

// Clean variables recursively.
if ( ! function_exists( 'wlea_clean' ) ) {
    /**
     * Clean variables recursively.
     */
    function wlea_clean( $var ) {
        if ( is_array( $var ) ) {
            return array_map( 'wlea_clean', $var );
        } else {
            return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        }
    }
}

// Clean array of ID.
if ( ! function_exists( 'wlea_clean_array_of_id' ) ) {
    /**
     * Clean array of ID.
     */
    function wlea_clean_array_of_id( $ids = array() ) {
        $array_of_id = array();

        if ( is_array( $ids ) && ! empty( $ids ) ) {
            foreach ( $ids as $id ) {
                $id = is_scalar( $id ) ? absint( $id ) : 0;

                if ( 0 !== $id ) {
                    $array_of_id[] = $id;
                }
            }
        }

        return $array_of_id;
    }
}

// Clean array of key.
if ( ! function_exists( 'wlea_clean_array_of_key' ) ) {
    /**
     * Clean array of key.
     */
    function wlea_clean_array_of_key( $keys = array() ) {
        $array_of_key = array();

        if ( is_array( $keys ) && ! empty( $keys ) ) {
            foreach ( $keys as $key ) {
                $key = is_scalar( $key ) ? sanitize_key( $key ) : '';

                if ( '' !== $key ) {
                    $array_of_key[] = $key;
                }
            }
        }

        return $array_of_key;
    }
}

// Key to absolute key.
if ( ! function_exists( 'wlea_key_to_abskey' ) ) {
    /**
     * Key to absolute key.
     */
    function wlea_key_to_abskey( $key = '' ) {
        $key = is_scalar( $key ) ? sanitize_key( $key ) : '';
        $key = str_replace( '-', '_', $key );

        return $key;
    }
}

// Type cast.
if ( ! function_exists( 'wlea_cast' ) ) {
    /**
     * Type cast.
     */
    function wlea_cast( $var = '', $type = 'text', $clean = true ) {
        $clean = rest_sanitize_boolean( $clean );

        switch ( $type ) {
            case 'key':
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_key( $var ) : $var );
                break;

            case 'textarea':
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_textarea_field( $var ) : $var );
                break;

            case 'array':
                $var = ( is_array( $var ) ? $var : array() );
                $var = ( true === $clean ? wlea_clean( $var ) : $var );
                break;

            case 'bool':
            case 'boolean':
                $var = rest_sanitize_boolean( $var );
                break;

            case 'int':
            case 'integer':
                $var = intval( $var );
                break;

            case 'absint':
            case 'absinteger':
                $var = absint( $var );
                break;

            case 'float':
                $var = floatval( $var );
                break;

            case 'absfloat':
                $var = floatval( $var );
                $var = ( ( 0 > $var ) ? ( $var * ( -1 ) ) : $var );
                $var = floatval( $var );
                break;

            case 'email':
                $var = ( is_email( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_email( $var ) : $var );
                break;

            default:
                $var = ( is_string( $var ) ? $var : '' );
                $var = ( true === $clean ? sanitize_text_field( $var ) : $var );
                break;
        }

        return $var;
    }
}

// Schedule in seconds.
if ( ! function_exists( 'wlea_schedule_in_seconds' ) ) {
    /**
     * Schedule in seconds.
     */
    function wlea_schedule_in_seconds( $schedule = array() ) {
        $in_seconds = 0;

        if ( ! is_array( $schedule ) || ! isset( $schedule['duration'] ) || ! isset( $schedule['unit'] ) ) {
            return $in_seconds;
        }

        $duration = ( isset( $schedule['duration'] ) ? wlea_cast( $schedule['duration'], 'int' ) : 0 );
        $unit = ( isset( $schedule['unit'] ) ? wlea_cast( $schedule['unit'], 'key' ) : '' );

        switch ( $unit ) {
            case 'minute':
            case 'minutes':
                $in_seconds = ( $duration * MINUTE_IN_SECONDS );
                break;

            case 'hour':
            case 'hours':
                $in_seconds = ( $duration * HOUR_IN_SECONDS );
                break;

            case 'day':
            case 'days':
                $in_seconds = ( $duration * DAY_IN_SECONDS );
                break;

            case 'week':
            case 'weeks':
                $in_seconds = ( $duration * WEEK_IN_SECONDS );
                break;

            case 'month':
            case 'months':
                $in_seconds = ( $duration * MONTH_IN_SECONDS );
                break;

            case 'year':
            case 'years':
                $in_seconds = ( $duration * YEAR_IN_SECONDS );
                break;

            default:
                $in_seconds = $duration;
                break;
        }

        return $in_seconds;
    }
}

// Get HTML as string.
if ( ! function_exists( 'wlea_get_html_as_string' ) ) {
    /**
     * Get HTML as string.
     */
    function wlea_get_html_as_string( $html = '', $search = array(), $replace = array() ) {
        $html = ( ( is_string( $html ) && ! empty( $html ) ) ? trim( $html ) : '' );
        $html = preg_replace( array( '/\s{2,}/', '/[\t\n]/'), ' ', $html );
        $html = str_replace( '> <', '><', $html );

        if ( is_array( $search ) && ! empty( $search ) && is_array( $replace ) && ! empty( $replace ) ) {
            $html = str_replace( $search, $replace, $html );
        }

        return $html;
    }
}

// Get woocommerce order statuses.
if ( ! function_exists( 'wlea_get_wc_order_statuses' ) ) {
    /**
     * Get woocommerce order statuses.
     */
    function wlea_get_wc_order_statuses( $contexts = '' ) {
        $statuses = array();

        $wc_statuses = wc_get_order_statuses();
        $wc_statuses = ( is_array( $wc_statuses ) ? $wc_statuses : array() );

        if ( 'events' === $contexts ) {
            foreach ( $wc_statuses as $wc_status_key => $wc_status_label ) {
                $wc_status_key = ( ( 'wc-' === substr( $wc_status_key, 0, 3 ) ) ? substr( $wc_status_key, 3 ) : $wc_status_key );
                $wc_status_key = 'wc-order-' . $wc_status_key;
                $wc_status_key = wlea_key_to_abskey( $wc_status_key );

                $wc_status_label = ( ( false === strpos( $wc_status_label, 'Order' ) ) ? sprintf( esc_html__( 'Order %1$s', 'woolentor-pro' ), $wc_status_label ) : $wc_status_label );
                $wc_status_label = ucwords( $wc_status_label );

                $statuses[ $wc_status_key ] = $wc_status_label;
            }
        } else {
            $statuses = $wc_statuses;
        }

        return $statuses;
    }
}

// Get trigger events.
if ( ! function_exists( 'wlea_get_trigger_events' ) ) {
    /**
     * Get trigger events.
     */
    function wlea_get_trigger_events() {
        $events = array(
            'wc_order_created' => esc_html__( 'Order Created', 'woolentor-pro' ),
            'wc_order_paid'    => esc_html__( 'Order Paid', 'woolentor-pro' ),
        );

        $events = array_merge( $events, wlea_get_wc_order_statuses( 'events' ) );

        $events = array_merge( $events, array(
            'wc_order_note_added'             => esc_html__( 'Order Note Added', 'woolentor-pro' ),
            'wc_customer_account_created'     => esc_html__( 'Customer Account Created', 'woolentor-pro' ),
            'wc_customer_total_spend_reaches' => esc_html__( 'Customer Total Spend Reaches', 'woolentor-pro' ),
            'wc_customer_order_count_reaches' => esc_html__( 'Customer Order Count Reaches', 'woolentor-pro' ),
        ) );

        return $events;
    }
}

// Get customer by id.
if ( ! function_exists( 'wlea_get_customer_by_id' ) ) {
    /**
     * Get customer by id.
     */
    function wlea_get_customer_by_id( $id = 0 ) {
        $customer = false;

        if ( empty( $id ) ) {
            return $customer;
        }

        $customer = new \WC_Customer( $id );

        return $customer;
    }
}

// Get customer by email.
if ( ! function_exists( 'wlea_get_customer_by_email' ) ) {
    /**
     * Get customer by email.
     */
    function wlea_get_customer_by_email( $email = '' ) {
        $customer = false;

        $user = get_user_by( 'email', $email );

        if ( ! is_object( $user ) || empty( $user ) ) {
            return $customer;
        }

        $user_id = ( isset( $user->ID ) ? absint( $user->ID ) : 0 );

        if ( empty( $user_id ) ) {
            return $customer;
        }

        $customer = new \WC_Customer( $user_id );

        return $customer;
    }
}

// Get countries.
if ( ! function_exists( 'wlea_get_countries' ) ) {
    /**
     * Get countries.
     */
    function wlea_get_countries() {
        $output = array();

        $countries = new \WC_Countries();

        if ( is_object( $countries ) && ! empty( $countries ) ) {
            $countries = $countries->get_countries();

            if ( is_array( $countries ) && ! empty( $countries ) ) {
                $output = $countries;
            }
        }

        return $output;
    }
}

// Get published email IDs.
if ( ! function_exists( 'wlea_get_published_email_ids' ) ) {
    /**
     * Get published email IDs.
     */
    function wlea_get_published_email_ids() {
        $args = array(
            'post_type'      => 'wlea-email',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );

        $post_ids = get_posts( $args );

        return ( is_array( $post_ids ) ? $post_ids : array() );
    }
}

// Get published workflow IDs.
if ( ! function_exists( 'wlea_get_published_workflow_ids' ) ) {
    /**
     * Get published workflow IDs.
     */
    function wlea_get_published_workflow_ids() {
        $args = array(
            'post_type'      => 'wlea-workflow',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );

        $post_ids = get_posts( $args );

        return ( is_array( $post_ids ) ? $post_ids : array() );
    }
}