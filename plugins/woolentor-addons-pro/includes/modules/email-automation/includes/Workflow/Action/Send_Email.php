<?php
/**
 * Send email.
 */

namespace WLEA\Workflow\Action;

/**
 * Class.
 */
class Send_Email {

    /**
     * Elements.
     */
    protected $elements;

    /**
     * Response.
     */
    protected $response;

	/**
     * Constructor.
     */
    public function __construct( $elements = array() ) {
        $this->elements = wlea_cast( $elements, 'array', false );

        $this->trigger();
    }

    /**
     * Trigger.
     */
    protected function trigger() {
        $elements = $this->elements;

        $event = ( isset( $elements['event'] ) ? wlea_cast( $elements['event'], 'key' ) : '' );
        $recipient = ( isset( $elements['recipient'] ) ? wlea_cast( $elements['recipient'], 'email' ) : '' );
        $subject = ( isset( $elements['subject'] ) ? wlea_cast( $elements['subject'], 'text' ) : '' );
        $preheader = ( isset( $elements['preheader'] ) ? wlea_cast( $elements['preheader'], 'text' ) : '' );
        $template = ( isset( $elements['template'] ) ? wlea_cast( $elements['template'], 'absint' ) : 0 );
        $meta = ( isset( $elements['meta'] ) ? wlea_cast( $elements['meta'], 'array' ) : array() );

        $subject = wp_specialchars_decode( $subject );
        $preheader = wp_specialchars_decode( $preheader );

        if ( empty( $event ) || empty( $recipient ) || empty( $subject ) || empty( $template ) || 'wlea-email' !== get_post_type( $template ) || 'publish' !== get_post_status( $template ) ) {
            return;
        }

        $from_name = wlea_get_email_from_name();
        $from_address = wlea_get_email_from_address();

        if ( empty( $from_name ) || empty( $from_address ) ) {
            return;
        }

        $content = get_the_content( null, false, $template );
        $content = wpautop( $content );

        if ( empty( $content ) ) {
            return;
        }

        $message = '';

        if ( 0 < strlen( $preheader ) ) {
            $preheader = sprintf( '%1$s ', $preheader );
            $message .= '<div style="display:none!important;font-size:1px;color:#ffffff;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden">' . $preheader . '</div>';
        }

        $message .= $content;

        if ( empty( $message ) ) {
            return;
        }

        $order_id    = ( isset( $meta['order_id'] ) ? wlea_cast( $meta['order_id'], 'absint' ) : 0 );
        $customer_id = ( isset( $meta['customer_id'] ) ? wlea_cast( $meta['customer_id'], 'absint' ) : 0 );

        switch ( $event ) {
            case 'wc_order_created':
            case 'wc_order_paid':
            case 'wc_order_pending':
            case 'wc_order_processing':
            case 'wc_order_on_hold':
            case 'wc_order_completed':
            case 'wc_order_cancelled':
            case 'wc_order_refunded':
            case 'wc_order_failed':
            case 'wc_order_checkout_draft':
            case 'wc_order_note_added':
                if ( ! empty( $order_id ) ) {
                    $message = wlea_replace_order_placeholders( $message, $order_id );
                }
                break;

            case 'wc_customer_account_created':
            case 'wc_customer_total_spend_reaches':
            case 'wc_customer_order_count_reaches':
                if ( ! empty( $customer_id ) ) {
                    $message = wlea_replace_customer_placeholders( $message, $customer_id );
                }
                break;
        }

        $message = wlea_strip_placeholders( $message );
        $message = wlea_replace_protocols( $message );

        if ( empty( $message ) ) {
            return;
        }

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_address . '>',
        );

        $response = wp_mail( $recipient, $subject, $message, $headers );

        $this->response = $response;
    }

    /**
     * Get response.
     */
    public function get_response() {
        return rest_sanitize_boolean( $this->response );
    }

}