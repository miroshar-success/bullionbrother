<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment_Checkout extends Woolentor_Partial_Payment{

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
        add_filter( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkout_create_order_line_item' ], 10, 4 );
        add_action( 'woocommerce_available_payment_gateways', [ $this, 'available_payment_gateways' ] );
        add_action( 'woocommerce_review_order_after_order_total', [ $this, 'review_order_after_order_total' ] );
    }

    /**
     * Add Order Meta
     *
     * @param [type] $item
     * @param [type] $cart_item_key
     * @param [type] $values
     * @param [type] $order
     * @return void
     */
    public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ){
        $cart_item = WC()->cart->get_cart()[ $cart_item_key ];

		if ( isset( $cart_item['woolentor_partial_payment'] ) && $cart_item['woolentor_partial_payment']['enable'] === true ) {
            $item->add_meta_data('woolentor_partial_payment_meta', $cart_item['woolentor_partial_payment'], true);
		}

    }

    /**
     * Manage Payment Method
     *
     * @param [array] $available_gateways
     * @return void
     */
    public function available_payment_gateways( $available_gateways ){

        $payment_status = 'no';
        $installment    = null;

        $pay_endpoint = get_option('woocommerce_checkout_pay_endpoint', 'order-pay');
        $order_id     = absint( get_query_var( $pay_endpoint ) );

        if ( $order_id > 0 ) {
            $order = wc_get_order( $order_id );
            if ( ! $order || $order->get_type() !== 'woolentor_pp_payment' ) {
                return $available_gateways;
            }

            $payment_status = $order->get_meta( '_woolentor_partial_payment_status', true );
            $installment    = $order->get_meta( '_woolentor_partial_payment_installment', true );

        }else{
            if ( $this->has_partial_payment_in_cart() ) {
                $payment_status = 'yes';
                $installment    = 'first';
            }
        }

        if( $payment_status === 'yes' ){

            if( $installment == 'first' ){
                $disallowed_payment_method = woolentor_get_option_pro( 'disallowed_payment_method_ppf', 'woolentor_partial_payment_settings' ) ? woolentor_get_option_pro( 'disallowed_payment_method_ppf', 'woolentor_partial_payment_settings' ) : [];
            }else{
                $disallowed_payment_method = woolentor_get_option_pro( 'disallowed_payment_method_pps', 'woolentor_partial_payment_settings' ) ? woolentor_get_option_pro( 'disallowed_payment_method_pps', 'woolentor_partial_payment_settings' ) : [];
            }

            foreach ( $available_gateways as $key => $available_gateway ) {
                if ( in_array( $key, $disallowed_payment_method ) ) {
                    unset( $available_gateways[ $key ] );
                }
            }

        }

        return $available_gateways;

    }

    /**
     * Add table Row in checkout page order overview table
     *
     * @return void
     */
    public function review_order_after_order_total(){
        if ( WC()->cart ) {

            $order_total = WC()->cart->get_total( 'f' );
            $subtotal_first_installment = $subtotal_second_installment = 0;

            foreach ( WC()->cart->get_cart() as $key => $cart_item ) {
                $product_id =  $cart_item['product_id'] ;

                if( ! $this->get_partial_payment_status( $product_id ) ){
                    unset( WC()->cart->get_cart()[$key]['woolentor_partial_payment'] ) ;
                }else{

                    if ( isset( $cart_item['woolentor_partial_payment'] ) && $cart_item['woolentor_partial_payment']['enable'] === true  ) {

                        $variation_id = isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : null;
                        $product_data = $variation_id ? $this->get_variation_product( $variation_id ) : $this->get_product_data( $product_id );
    
                        $first_installment_amount  = $this->get_partial_amount_calculate( $product_id, $product_data->get_price() ) * $cart_item['quantity'];
                        $second_installment_amount = ( $product_data->get_price() * $cart_item['quantity'] ) - $first_installment_amount;
    
                        $subtotal_first_installment  += $first_installment_amount;
                        $subtotal_second_installment += $second_installment_amount;
    
                    }

                }

            }

            if ( $subtotal_second_installment > 0 ) {
                ?>
                    <tr class="order-first-installment">
                        <th> <?php echo esc_html( $this->get_option_data('first_installment_text','First Installment') ) ?> </th>
                        <td>  <?php echo wc_price( $subtotal_first_installment ); ?>  </td>
                    </tr>
                    <tr class="order-second-installment">
                        <th> <?php echo esc_html( $this->get_option_data('second_installment_text','Second Installment') ) ?></th>
                        <td> <?php echo wc_price( $subtotal_second_installment ); ?> </td>
                    </tr>
                    <tr class="order-paid">
                        <th><?php echo esc_html( $this->get_option_data('to_pay','To Pay') ) ?></th>
                        <td><?php echo wc_price( $order_total - $subtotal_second_installment ); ?></td>
                    </tr>
                <?php
            }

        }

    }


}