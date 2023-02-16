<?php
/**
 * WC Order.
 */

namespace WLEA\Workflow\Tasks;

/**
 * Class.
 */
class WC_Order {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'woocommerce_checkout_order_created', array( $this, 'order_created' ), 999 );
        add_action( 'woocommerce_payment_complete', array( $this, 'payment_complete' ), 999 );
        add_action( 'woocommerce_order_status_changed', array( $this, 'status_changed' ), 999, 3 );
        add_action( 'woocommerce_order_note_added', array( $this, 'note_added' ), 999, 2 );
    }

    /**
     * Order created.
     */
    public function order_created( $order ) {
        if ( ! is_object( $order ) || empty( $order ) || ! ( $order instanceof \WC_Order ) ) {
            return;
        }

        $order_id = $order->get_id();

        $recipient = $order->get_billing_email();
        $recipient = wlea_cast( $recipient, 'email' );

        if ( empty( $recipient ) ) {
            return;
        }

        $args = array(
            'order_id'  => $order_id,
            'recipient' => $recipient,
        );

        $event = 'wc_order_created';

        new \WLEA\Workflow\Tasks\Create( $event, $args );
    }

    /**
     * Payment complete.
     */
    public function payment_complete( $order_id = 0 ) {
        $order_id = absint( $order_id );

        if ( empty( $order_id ) ) {
            return;
        }

        $order = wc_get_order( $order_id );

        if ( empty( $order ) ) {
            return;
        }

        $recipient = $order->get_billing_email();
        $recipient = wlea_cast( $recipient, 'email' );

        if ( empty( $recipient ) ) {
            return;
        }

        $args = array(
            'order_id'  => $order_id,
            'recipient' => $recipient,
        );

        $event = 'wc_order_paid';

        new \WLEA\Workflow\Tasks\Create( $event, $args );
    }

    /**
     * Status changed.
     */
    public function status_changed( $order_id = 0, $old_status = '', $new_status = '' ) {
        $order_id = absint( $order_id );

        if ( empty( $order_id ) || ( $new_status === $old_status ) || ( 'shop_order' !== get_post_type( $order_id ) ) ) {
            return;
        }

        $order = wc_get_order( $order_id );

        if ( empty( $order ) ) {
            return;
        }

        $recipient = $order->get_billing_email();
        $recipient = wlea_cast( $recipient, 'email' );

        if ( empty( $recipient ) ) {
            return;
        }

        $args = array(
            'order_id'  => $order_id,
            'recipient' => $recipient,
        );

        $event = ( ( 'wc-' === substr( $new_status, 0, 3 ) ) ? substr( $new_status, 3 ) : $new_status );
        $event = 'wc-order-' . $event;
        $event = wlea_key_to_abskey( $event );

        new \WLEA\Workflow\Tasks\Create( $event, $args );
    }

    /**
     * Order created.
     */
    public function note_added( $comment_id = 0, $order = null ) {
        if ( ! is_object( $order ) || empty( $order ) || ! ( $order instanceof \WC_Order ) ) {
            return;
        }

        $order_id = $order->get_id();

        $recipient = $order->get_billing_email();
        $recipient = wlea_cast( $recipient, 'email' );

        if ( empty( $recipient ) ) {
            return;
        }

        $args = array(
            'order_id'  => $order_id,
            'recipient' => $recipient,
        );

        $event = 'wc_order_note_added';

        new \WLEA\Workflow\Tasks\Create( $event, $args );
    }

}