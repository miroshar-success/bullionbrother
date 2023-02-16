<?php
/**
 * Rules.
 */

namespace WLEA\Workflow\Tasks;

/**
 * Class.
 */
class Rules {

	/**
     * Response.
     */
    protected $response;

	/**
     * Constructor.
     */
    public function __construct( $rules = array(), $event = '', $args = array() ) {
        $result = true;

        $rules = wlea_cast( $rules, 'array', false );
        $args  = wlea_cast( $args, 'array' );
        $event = wlea_cast( $event, 'key' );

        if ( ! empty( $args ) && ! empty( $event ) && ! empty( $rules ) && method_exists( $this, $event ) ) {
            $result = false;

            foreach ( $rules as $items ) {
                $items_found = false;
                $group_result = true;

                $items = wlea_cast( $items, 'array', false );

                if ( empty( $items ) ) {
                    continue;
                }

                foreach ( $items as $item ) {
                    $item_found = false;
                    $item_result = true;

                    $item = wlea_cast( $item, 'array', false );

                    if ( empty( $item ) ) {
                        continue;
                    }

                    $item_found = true;
                    $item_result = false;

                    if ( isset( $item['base'] ) && isset( $item['operator'] ) && isset( $item['value'] ) ) {
                        $base     = wlea_cast( $item['base'], 'key' );
                        $operator = wlea_cast( $item['operator'], 'key' );
                        $value    = $item['value'];

                        if ( ! empty( $base ) && ! empty( $operator ) ) {
                            $item_result = $this->{$event}( $base, $operator, $value, $args );
                        }
                    }

                    if ( true === $item_found ) {
                        $items_found = true;
                    }

                    if ( false === $item_result ) {
                        $group_result = false;
                    }
                }

                if ( true === $items_found && true === $group_result ) {
                    $result = true;
                }
            }
        }

        $this->response = $result;
    }

    /**
     * WC order created.
     */
    protected function wc_order_created( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order paid.
     */
    protected function wc_order_paid( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order pending.
     */
    protected function wc_order_pending( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order processing.
     */
    protected function wc_order_processing( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order on hold.
     */
    protected function wc_order_on_hold( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order completed.
     */
    protected function wc_order_completed( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order cancelled.
     */
    protected function wc_order_cancelled( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order refunded.
     */
    protected function wc_order_refunded( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order failed.
     */
    protected function wc_order_failed( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order checkout draft.
     */
    protected function wc_order_checkout_draft( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order note added.
     */
    protected function wc_order_note_added( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = $this->wc_order_rules( $base, $operator, $value, $args );

        return $result;
    }

    /**
     * WC order rules.
     */
    protected function wc_order_rules( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = false;

        $order_id  = ( isset( $args['order_id'] ) ? wlea_clean( $args['order_id'], 'absint' ) : 0 );
        $order = wc_get_order( $order_id );

        if ( empty( $order ) ) {
            return $result;
        }

        switch ( $base ) {
            case 'products':
                $result = \WLEA\Workflow\Tasks\Bases::products( $operator, $value, $order );
                break;

            case 'order_spend':
                $result = \WLEA\Workflow\Tasks\Bases::order_spend( $operator, $value, $order );
                break;

            case 'product_count':
                $result = \WLEA\Workflow\Tasks\Bases::product_count( $operator, $value, $order );
                break;

            case 'customer_type':
                $result = \WLEA\Workflow\Tasks\Bases::customer_type( $operator, $value, $order );
                break;

            case 'customer_country':
                $result = \WLEA\Workflow\Tasks\Bases::customer_country( $operator, $value, $order );
                break;

            case 'trigger_date':
                $result = \WLEA\Workflow\Tasks\Bases::trigger_date( $operator, $value );
                break;
        }

        return $result;
    }

    /**
     * WC customer account created.
     */
    protected function wc_customer_account_created( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = false;

        $customer_id  = ( isset( $args['customer_id'] ) ? wlea_clean( $args['customer_id'], 'absint' ) : 0 );
        $customer = wlea_get_customer_by_id( $customer_id );

        if ( empty( $customer ) ) {
            return $result;
        }

        switch ( $base ) {
            case 'customer_country':
                $result = \WLEA\Workflow\Tasks\Bases::customer_country( $operator, $value, $customer );
                break;

            case 'trigger_date':
                $result = \WLEA\Workflow\Tasks\Bases::trigger_date( $operator, $value );
                break;
        }

        return $result;
    }

    /**
     * WC customer total spend reaches.
     */
    protected function wc_customer_total_spend_reaches( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = false;

        $customer_id  = ( isset( $args['customer_id'] ) ? wlea_clean( $args['customer_id'], 'absint' ) : 0 );
        $customer = wlea_get_customer_by_id( $customer_id );

        if ( empty( $customer ) ) {
            return $result;
        }

        switch ( $base ) {
            case 'total_spend':
                $result = \WLEA\Workflow\Tasks\Bases::total_spend( $operator, $value, $customer );
                break;

            case 'trigger_date':
                $result = \WLEA\Workflow\Tasks\Bases::trigger_date( $operator, $value );
                break;
        }

        return $result;
    }

    /**
     * WC customer order count reaches.
     */
    protected function wc_customer_order_count_reaches( $base = '', $operator = '', $value = '', $args = array() ) {
        $result = false;

        $customer_id  = ( isset( $args['customer_id'] ) ? wlea_clean( $args['customer_id'], 'absint' ) : 0 );
        $customer = wlea_get_customer_by_id( $customer_id );

        if ( empty( $customer ) ) {
            return $result;
        }

        switch ( $base ) {
            case 'order_count':
                $result = \WLEA\Workflow\Tasks\Bases::order_count( $operator, $value, $customer );
                break;

            case 'trigger_date':
                $result = \WLEA\Workflow\Tasks\Bases::trigger_date( $operator, $value );
                break;
        }

        return $result;
    }

    /**
     * Verify rules.
     */
    public static function verify_rules( $rules = array(), $event = '', $args = array() ) {
        $instance = new self( $rules, $event, $args );
        $response = rest_sanitize_boolean( $instance->response );

        return $response;
    }

}