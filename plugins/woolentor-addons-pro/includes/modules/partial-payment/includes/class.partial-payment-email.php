<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment_Email{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        add_action('woocommerce_email_order_details', [ $this, 'partial_payment_details' ], 20, 4);
        add_action('woocommerce_email_order_details', [ $this, 'original_order_summary' ], 20, 4);

        add_filter('woocommerce_email_enabled_new_order', [ $this, 'disable_payment_emails' ], 999, 3 );
        add_filter('woocommerce_email_enabled_customer_on_hold_order', [ $this, 'disable_payment_emails' ], 999, 3 );
        add_filter('woocommerce_email_enabled_customer_completed_order', [ $this, 'disable_payment_emails' ], 999, 3 );

    }

    /**
     * Disable Order mail if partial payment is exists
     *
     * @param [bool] $enabled
     * @param [object] $order
     * @param [string] $email
     * @return void
     */
    public function disable_payment_emails( $enabled, $order, $email ){
        if( !is_object( $order ) ) {
            return $enabled;
        }
        $order = wc_get_order( $order->get_id() );
        if ( $order && $order->get_type() == 'woolentor_pp_payment' ){
            $enabled = false;
        }
        return $enabled;
    }

    /**
     * Partial Payment order summery
     *
     * @param [object] $order
     * @param boolean $sent_to_admin
     * @param boolean $plain_text
     * @param string $email
     * @return void
     */
    public function partial_payment_details( $order, $sent_to_admin = false, $plain_text = false, $email = '' ){

        $partial_payment_status = $order->get_meta( '_woolentor_partial_payment_status', true );
        if( $partial_payment_status === 'yes' ){
            $payment_schedule = $order->get_meta( '_woolentor_payment_schedule', true );

            if ( ! empty( $payment_schedule ) ){

                wc_get_template(
                    'emails/email-partial-payment-details.php', array(
                        'order'         => $order,
                        'sent_to_admin' => $sent_to_admin,
                        'plain_text'    => $plain_text,
                        'email'         => $email,
                        'payment_schedule'=> $payment_schedule
                    ),
                    '',
                    WOOLENTOR_PARTIAL_PAYMENT_TEMPLATE_PATH
                );

            }

        }


    }

    /**
     * Orginal Order summery
     *
     * @param [type] $order
     * @param boolean $sent_to_admin
     * @param boolean $plain_text
     * @param string $email
     * @return void
     */
    public function original_order_summary( $order, $sent_to_admin = false, $plain_text = false, $email = '' ) {

        if ( $order->get_type() === 'woolentor_pp_payment' ){
            $parent = wc_get_order( $order->get_parent_id() );

            echo '<p>'.esc_html__( 'Below is a summary of original order', 'woolentor-pro' ).'<strong>'.$parent->get_order_number().'</strong></p>';

            if ( $plain_text ) {
                wc_get_template(
                    'emails/plain/email-order-details.php',
                    array(
                        'order'         => $parent,
                        'sent_to_admin' => $sent_to_admin,
                        'plain_text'    => $plain_text,
                        'email'         => $email,
                    )
                );
            } else {
                wc_get_template(
                    'emails/email-order-details.php',
                    array(
                        'order'         => $parent,
                        'sent_to_admin' => $sent_to_admin,
                        'plain_text'    => $plain_text,
                        'email'         => $email,
                    )
                );
            }

        }
        
    }

}