<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment_Cart extends Woolentor_Partial_Payment{

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

        add_filter( 'woocommerce_get_item_data', [ $this, 'get_item_data' ], 99, 2 );
        add_action('woocommerce_add_to_cart', [ $this, 'save_original_price' ] );
        add_filter( 'woocommerce_cart_item_subtotal', [ $this, 'cart_item_subtotal' ], 99, 3 );
        add_action( 'woocommerce_cart_totals_after_order_total', [ $this, 'cart_totals_after_order_total' ] );

    }

    /**
     * Add additional info
     *
     * @param [array] $item_data
     * @param [array] $cart_item
     * @return array
     */
    public function get_item_data( $item_data, $cart_item ){

        $product_id   =  $cart_item['product_id'];
        $variation_id = isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : null;
        $product_data = $variation_id ? $this->get_variation_product( $variation_id ) : $this->get_product_data( $product_id );

		if (  isset( $cart_item['woolentor_partial_payment'] ) && $cart_item['woolentor_partial_payment']['enable'] === true && $this->get_partial_payment_status( $product_id ) ) {

            $first_installment_amount  = $this->get_partial_amount_calculate( $product_id, $product_data->get_price() ) * $cart_item['quantity'];
            $second_installment_amount = ( $product_data->get_price() * $cart_item['quantity'] ) - $first_installment_amount;

            $item_data[] = array(
                'name'      => esc_html( $this->get_option_data('first_installment_text','First Installment') ),
                'display'   => wc_price( $first_installment_amount ),
                'value'     => 'wc_woolentor_first_partial_payment_amount',
            );

            $item_data[] = array(
                'name'      => esc_html( $this->get_option_data('second_installment_text','Second Installment') ),
                'display'   => wc_price( $second_installment_amount ),
                'value'     => 'wc_woolentor_second_partial_payment_amount',
            );


        }

        return $item_data;

    }

    /**
     * Save Original Price after add to cart
     *
     * @param [string] $cart_item_key
     * @return void
     */
    public function save_original_price( $cart_item_key ){

        $cart_item = WC()->cart->get_cart_item( $cart_item_key );

        if( isset( $cart_item['woolentor_partial_payment'] ) && $cart_item['woolentor_partial_payment']['enable'] === true ){

            $product = $cart_item['data'];
            WC()->cart->cart_contents[$cart_item_key]['woolentor_partial_payment']['original_price'] = $product->get_price();

        }
    }

    /**
     * Update subtotal
     *
     * @param [string] $subtotal
     * @param [array] $cart_item
     * @param [string] $cart_item_key
     * @return void
     */
    public function cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ){

        $product_id   =  $cart_item['product_id'];
        $variation_id = isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : null;
        $product_data = $variation_id ? $this->get_variation_product( $variation_id ) : $this->get_product_data( $product_id );

        if (  isset( $cart_item['woolentor_partial_payment'] ) && $cart_item['woolentor_partial_payment']['enable'] === true && $this->get_partial_payment_status( $product_id ) ) {

			$partial_ammount = $this->get_partial_amount_calculate( $product_id, $product_data->get_price() ) * $cart_item['quantity'];

			$subtotal = wc_price( $partial_ammount );
		}

		return $subtotal;

    }

    /**
     * Add table Row in cart total table
     *
     * @return void
     */
    public function cart_totals_after_order_total(){

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