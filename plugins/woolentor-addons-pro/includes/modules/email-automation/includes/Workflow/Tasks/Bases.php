<?php
/**
 * Bases.
 */

namespace WLEA\Workflow\Tasks;

/**
 * Class.
 */
class Bases {

    /**
     * Products.
     */
    public static function products( $operator = '', $value = '', $order = null ) {
        $result = false;

        $value = wlea_cast( $value, 'array' );
        $value = array_map( 'absint', $value );

        if ( empty( $value ) || empty( $order ) ) {
            return $result;
        }

        $items = $order->get_items( 'line_item' );

        $tresult = true;

        foreach ( $items as $item ) {
			if ( ! empty( $item ) && $item->is_type( 'line_item' ) ) {
				$product = $item->get_product();

                if ( ! empty( $product ) ) {
                    $product_id = ( ( 'variation' === $product->get_type() ) ? $product->get_parent_id() : $product->get_id() );
                    $product_id = absint( $product_id );

                    if ( ! empty( $product_id ) ) {
                        if ( 'in' === $operator ) {
                            if ( in_array( $product_id, $value ) ) {
                                $result = true;
                            }
                        } elseif ( 'not_in' === $operator ) {
                            if ( in_array( $product_id, $value ) ) {
                                $tresult = false;
                            }
                        }
                    }
                }
			}
		}

        if ( ( 'not_in' === $operator ) && ( true === $tresult ) ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Customer type.
     */
    public static function customer_type( $operator = '', $value = '', $order = null ) {
        $result = false;

        $value = wlea_cast( $value, 'key' );

        if ( empty( $value ) || empty( $order ) ) {
            return $result;
        }

        $customer_id = $order->get_customer_id();

        if ( empty( $customer_id ) ) {
            return $result;
        }

        $customer = wlea_get_customer_by_id( $customer_id );

        if ( 'equal' === $operator ) {
            if ( ( 'guest' === $value ) && empty( $customer ) ) {
                $result = true;
            } elseif ( ( 'registered' === $value ) && ! empty( $customer ) ) {
                $result = true;
            }
        } elseif ( 'not_equal' === $operator ) {
            if ( ( 'guest' === $value ) && ! empty( $customer ) ) {
                $result = true;
            } elseif ( ( 'registered' === $value ) && empty( $customer ) ) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Order spend.
     */
    public static function order_spend( $operator = '', $value = '', $order = null ) {
        $result = false;

        $value = wlea_cast( $value, 'float' );

        if ( empty( $order ) ) {
            return $result;
        }

        $order_total = $order->get_total();
        $order_total = wlea_cast( $order_total, 'float' );

        if ( ( 'equal' === $operator ) && ( $order_total === $value ) ) {
            $result = true;
        } elseif ( ( 'less_than' === $operator ) && ( $order_total < $value ) ) {
            $result = true;
        } elseif ( ( 'greater_than' === $operator ) && ( $order_total > $value ) ) {
            $result = true;
        } elseif ( ( 'equal_or_less_than' === $operator ) && ( ( $order_total === $value ) || ( $order_total < $value ) ) ) {
            $result = true;
        } elseif ( ( 'equal_or_greater_than' === $operator ) && ( ( $order_total === $value ) || ( $order_total > $value ) ) ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Product count.
     */
    public static function product_count( $operator = '', $value = '', $order = null ) {
        $result = false;

        $value = wlea_cast( $value, 'int' );

        if ( empty( $order ) ) {
            return $result;
        }

        $product_count = 0;

        $items = $order->get_items( 'line_item' );

        foreach ( $items as $item ) {
			if ( ! empty( $item ) && $item->is_type( 'line_item' ) ) {
				$product = $item->get_product();

                if ( ! empty( $product ) ) {
                    $product_count++;
                }
			}
		}

        if ( ( 'equal' === $operator ) && ( $product_count === $value ) ) {
            $result = true;
        } elseif ( ( 'less_than' === $operator ) && ( $product_count < $value ) ) {
            $result = true;
        } elseif ( ( 'greater_than' === $operator ) && ( $product_count > $value ) ) {
            $result = true;
        } elseif ( ( 'equal_or_less_than' === $operator ) && ( ( $product_count === $value ) || ( $product_count < $value ) ) ) {
            $result = true;
        } elseif ( ( 'equal_or_greater_than' === $operator ) && ( ( $product_count === $value ) || ( $product_count > $value ) ) ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Customer country.
     */
    public static function customer_country( $operator = '', $value = '', $customer = null ) {
        $result = false;

        $value = wlea_cast( $value, 'array' );
        $value = array_map( 'sanitize_key', $value );

        if ( empty( $value ) || empty( $customer ) ) {
            return $result;
        }

        $country = $customer->get_billing_country();
        $country = wlea_cast( $country, 'key' );

        if ( ( 'in' === $operator ) && in_array( $country, $value ) ) {
            $result = true;
        } elseif ( ( 'not_in' === $operator ) && ! in_array( $country, $value ) ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Total spend.
     */
    public static function total_spend( $operator = '', $value = '', $customer = null ) {
        $result = false;

        $value = wlea_cast( $value, 'float' );

        if ( empty( $customer ) ) {
            return $result;
        }

        $total_spent = $customer->get_total_spent();
        $total_spent = wlea_cast( $total_spent, 'float' );

        if ( ( 'equal' === $operator ) && ( $total_spent === $value ) ) {
            $result = true;
        } elseif ( ( 'less_than' === $operator ) && ( $total_spent < $value ) ) {
            $result = true;
        } elseif ( ( 'greater_than' === $operator ) && ( $total_spent > $value ) ) {
            $result = true;
        } elseif ( ( 'equal_or_less_than' === $operator ) && ( ( $total_spent === $value ) || ( $total_spent < $value ) ) ) {
            $result = true;
        } elseif ( ( 'equal_or_greater_than' === $operator ) && ( ( $total_spent === $value ) || ( $total_spent > $value ) ) ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Order count.
     */
    public static function order_count( $operator = '', $value = '', $customer = null ) {
        $result = false;

        $value = wlea_cast( $value, 'int' );

        if ( empty( $customer ) ) {
            return $result;
        }

        $order_count = $customer->get_order_count();
        $order_count = wlea_cast( $order_count, 'absint' );

        if ( ( 'equal' === $operator ) && ( $order_count === $value ) ) {
            $result = true;
        } elseif ( ( 'less_than' === $operator ) && ( $order_count < $value ) ) {
            $result = true;
        } elseif ( ( 'greater_than' === $operator ) && ( $order_count > $value ) ) {
            $result = true;
        } elseif ( ( 'equal_or_less_than' === $operator ) && ( ( $order_count === $value ) || ( $order_count < $value ) ) ) {
            $result = true;
        } elseif ( ( 'equal_or_greater_than' === $operator ) && ( ( $order_count === $value ) || ( $order_count > $value ) ) ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Trigger date.
     */
    public static function trigger_date( $operator = '', $value = '' ) {
        $result = false;

        $value = wlea_cast( $value, 'text' );

        if ( empty( $value ) ) {
            return $result;
        }

        $timezone = new \DateTimeZone( wp_timezone_string() );

        $start  = sprintf( '%1$s %2$s', $value, '00:00:00' );
        $ending = sprintf( '%1$s %2$s', $value, '23:59:59' );

        $start_object  = \DateTime::createFromFormat( 'Y-m-d H:i:s', $start, $timezone );
        $ending_object = \DateTime::createFromFormat( 'Y-m-d H:i:s', $ending, $timezone );

        if ( ( false === $start_object ) || ( false === $ending_object ) ) {
            return $result;
        }

        $start_timestamp  = $start_object->getTimestamp();
        $ending_timestamp = $ending_object->getTimestamp();

        $current_timestamp = current_time( 'U' );

        if ( ( 'equal' === $operator ) && ( $current_timestamp >= $start_timestamp ) && ( $current_timestamp <= $ending_timestamp ) ) {
            $result = true;
        } elseif ( ( 'before' === $operator ) && ( $current_timestamp < $start_timestamp ) ) {
            $result = true;
        } elseif ( ( 'after' === $operator ) && ( $current_timestamp > $ending_timestamp ) ) {
            $result = true;
        } elseif ( ( 'equal_or_before' === $operator ) && ( $current_timestamp <= $ending_timestamp ) ) {
            $result = true;
        } elseif ( ( 'equal_or_after' === $operator ) && ( $current_timestamp >= $start_timestamp ) ) {
            $result = true;
        }

        return $result;
    }

}