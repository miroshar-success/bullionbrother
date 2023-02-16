<?php
/**
 * WC Customer.
 */

namespace WLEA\Workflow\Tasks;

/**
 * Class.
 */
class WC_Customer {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'woocommerce_created_customer', array( $this, 'created_customer' ), 999, 2 );
        add_action( 'woocommerce_order_status_completed', array( $this, 'customer_stats' ), 999, 2 );
    }

	/**
     * Created customer.
     */
    public function created_customer( $customer_id = 0, $customer_data = array() ) {
        $customer_id = absint( $customer_id );
        $customer_data = wlea_cast( $customer_data, 'array' );

        if ( empty( $customer_id ) || empty( $customer_data ) ) {
            return;
        }

        $recipient = ( isset( $customer_data['user_email'] ) ? wlea_cast( $customer_data['user_email'], 'email' ) : '' );

        if ( empty( $recipient ) ) {
            return;
        }

        $args = array(
            'recipient'   => $recipient,
            'customer_id' => $customer_id,
        );

        $event = 'wc_customer_account_created';

        new \WLEA\Workflow\Tasks\Create( $event, $args );
    }

    /**
     * Customer statistics.
     */
    public function customer_stats( $order_id = 0, $order = null ) {
        if ( ! is_object( $order ) || empty( $order ) || ! ( $order instanceof \WC_Order ) ) {
            return;
        }

        $customer_id = $order->get_customer_id();
        $customer_id = absint( $customer_id );

        if ( empty( $customer_id ) ) {
            return;
        }

        $customer = new \WC_Customer( $customer_id );

        if ( empty( $customer ) ) {
            return;
        }

        $recipient = $customer->get_email();
        $recipient = wlea_cast( $recipient, 'email' );

        if ( empty( $recipient ) ) {
            return;
        }

        $args = array(
            'recipient'   => $recipient,
            'customer_id' => $customer_id,
        );

        $total_spend_event = 'wc_customer_total_spend_reaches';
        $order_count_event = 'wc_customer_order_count_reaches';

        new \WLEA\Workflow\Tasks\Create( $total_spend_event, $args );
        new \WLEA\Workflow\Tasks\Create( $order_count_event, $args );
    }

}