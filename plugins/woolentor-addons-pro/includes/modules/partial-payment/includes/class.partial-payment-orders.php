<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment_Orders extends Woolentor_Partial_Payment{

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

        // Create Order
        add_filter( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkout_create_order_line_item' ], 10, 4 );
		add_action( 'woocommerce_create_order', [ $this, 'create_order' ], 10, 2 );

        // Order Received Page
		add_filter( 'woocommerce_get_order_item_totals', [ $this, 'get_order_item_totals' ], 10, 2 );
		add_action( 'woocommerce_order_details_after_order_table', [ $this, 'partial_payments_summary' ] );

        // order status
        add_action('woocommerce_order_status_changed', [ $this, 'order_status_changed' ], 10, 4 );
        add_filter('woocommerce_order_status_on-hold', [ $this, 'set_parent_order_on_hold' ] );

    }

    /**
     * Add Order Meta
     *
     * @param [array] $item
     * @param [string] $cart_item_key
     * @param [array] $values
     * @param [object] $order
     * @return void
     */
    public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
		$cart_item = WC()->cart->get_cart()[ $cart_item_key ];
		if ( isset( $cart_item['woolentor_partial_payment'] ) && $cart_item['woolentor_partial_payment']['enable'] === true ) {
            $amount_type  = $this->get_saved_data( $cart_item['product_id'], 'woolentor_partial_payment_amount_type', 'amount_type', 'percentage' );
		    $amount       = $this->get_saved_data( $cart_item['product_id'], 'woolentor_partial_payment_amount', 'amount', '50' );
			$item->update_meta_data( 'woolentor_partial_payment_amount_type_meta', $amount_type );
			$item->update_meta_data( 'woolentor_partial_payment_amount_meta', $amount );
		}
	}

    /**
     * Create Partial Payment order
     *
     * @param [int] $order_id
     * @param [object] $checkout
     * @return void
     */
    public function create_order( $order_id, $checkout ){

        if ( ! $this->has_partial_payment_in_cart() ) {
            return null;
        }

        $data = $checkout->get_posted_data();

        try {

            $order_id           = absint( WC()->session->get( 'order_awaiting_payment' ) );
            $cart_hash          = WC()->cart->get_cart_hash();
            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
            $order              = $order_id ? wc_get_order( $order_id ) : null;

            /**
             * If there is an order pending payment, we can resume it here so
             * long as it has not changed. If the order has changed, i.e.
             * different items or cost, create a new order. We use a hash to
             * detect changes which is based on cart items + order total.
             */
            if ( $order && $order->has_cart_hash( $cart_hash ) && $order->has_status( array( 'pending', 'failed' ) ) ) {
                // Action for 3rd parties.
                do_action( 'woocommerce_resume_order', $order_id );

                // Remove all items - we will re-add them later.
                $order->remove_order_items();
            } else {
                $order = new WC_Order();
            }

            $fields_prefix = array(
                'shipping' => true,
                'billing'  => true,
            );

            $shipping_fields = array(
                'shipping_method' => true,
                'shipping_total'  => true,
                'shipping_tax'    => true,
            );
            foreach ( $data as $key => $value ) {
                if ( is_callable( array( $order, "set_{$key}" ) ) ) {
                    $order->{"set_{$key}"}( $value );
                    // Store custom fields prefixed with wither shipping_ or billing_. This is for backwards compatibility with 2.6.x.
                } elseif ( isset( $fields_prefix[ current( explode( '_', $key ) ) ] ) ) {
                    if ( ! isset( $shipping_fields[ $key ] ) ) {
                        $order->update_meta_data( '_' . $key, $value );
                    }
                }
            }

            $order->hold_applied_coupons( $data['billing_email'] );
            $order->set_created_via( 'checkout' );
            $order->set_cart_hash( $cart_hash );
            $order->set_customer_id( apply_filters( 'woocommerce_checkout_customer_id', get_current_user_id() ) );
            $order_vat_exempt = WC()->cart->get_customer()->get_is_vat_exempt() ? 'yes' : 'no';
            $order->add_meta_data('is_vat_exempt', $order_vat_exempt);
            $order->set_currency( get_woocommerce_currency() );
            $order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
            $order->set_customer_ip_address( WC_Geolocation::get_ip_address() );
            $order->set_customer_user_agent( wc_get_user_agent() );
            $order->set_customer_note( isset( $data['order_comments'] ) ? $data['order_comments'] : '' );
            $order->set_payment_method( '' );
            $checkout->set_data_from_cart( $order );

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

            /**
             * Action hook to adjust order before save.
             *
             * @since 3.0.0
             */
            do_action( 'woocommerce_checkout_create_order', $order, $data );

            // Save the order.
            $order_id = $order->save();

            /**
             * Action hook fired after an order is created used to add custom meta to the order.
             *
             * @since 3.0.0
             */
            do_action( 'woocommerce_checkout_update_order_meta', $order_id, $data );

            $order->read_meta_data();

            $schedule_payment = [
                'installment' => [
                    'first' => [
                        'total' => ( $order_total - $subtotal_second_installment ),
                        'type' => $order->get_meta('woolentor_partial_payment_amount_type_meta'),
                        'status' => 'yes',
                        'paid' => 'no'
                    ],
                    'second' => [
                        'total' => $subtotal_second_installment,
                        'type' => $order->get_meta('woolentor_partial_payment_amount_type_meta'),
                        'status' => 'yes',
                        'paid' => 'no'
                    ],
                ],
                'default' =>[
                    'total'       => $order_total,
                    'paid_amount' => 0,
                ]
            ];

            $partial_payment_id = null;

            if ( $schedule_payment ) {

                foreach ( $schedule_payment['installment'] as $partial_key => $payment ) {

                    $partial_payment = new Woolentor_Create_Partial_Payment();

                    $partial_payment->set_customer_id( apply_filters('woocommerce_checkout_customer_id', get_current_user_id() ) );

                    $amount = $payment['total'];

                    $name = esc_html__('Partial Payment for order %s', 'woolentor-pro');
                    $partial_payment_name = apply_filters('woolentor_partial_payment_order_name', sprintf( $name, $order->get_order_number() ), $payment, $order->get_id() );
                    
                    $item = new WC_Order_Item_Fee();

                    $item->set_props(
                        [
                            'total' => $amount
                        ]
                    );

                    $item->set_name( $partial_payment_name );
                    $partial_payment->add_item( $item );

                    $partial_payment->set_parent_id( $order_id );
                    $partial_payment->add_meta_data( 'is_vat_exempt', $order_vat_exempt );
                    $partial_payment->add_meta_data('_woolentor_partial_payment_type', $payment['type'] );
                    $partial_payment->add_meta_data('_woolentor_partial_payment_status', $payment['status'] );
                    $partial_payment->add_meta_data('_woolentor_partial_payment_installment', $partial_key );
                    $partial_payment->add_meta_data( '_woolentor_payment_schedule', $schedule_payment );
                    
                    $selected_payment_method = $data['payment_method'];
                    $payment_method          = isset( $available_gateways[ $selected_payment_method ] ) ? $available_gateways[ $selected_payment_method ] : $selected_payment_method;
                    if ( $payment_method ) {
                        $partial_payment->set_payment_method( $payment_method );
                    }

                    $partial_payment->set_currency(get_woocommerce_currency());
                    $partial_payment->set_prices_include_tax( 'yes' === get_option('woocommerce_prices_include_tax') );
                    $partial_payment->set_customer_ip_address( WC_Geolocation::get_ip_address() );
                    $partial_payment->set_customer_user_agent($user_agent);
                    $partial_payment->set_total( $amount );
                    $partial_payment_order_id = $partial_payment->save();
                    $schedule_payment['installment'][$partial_key]['id'] = $partial_payment->get_id();

                    if( $partial_key == 'first' ){
                        $partial_payment_id = $partial_payment_order_id;
                    }

                }

            }

            $main_order = wc_get_order( $order_id );
            $main_order->update_meta_data( '_woolentor_payment_schedule', $schedule_payment );
            $main_order->update_meta_data( '_woolentor_partial_payment_status', 'yes' );
            $main_order->update_meta_data( '_woolentor_partial_payment_parent_order', 'yes' );
            $main_order->save();
            return absint( $partial_payment_id );

        } catch (Exception $e) {
            return new WP_Error('checkout-error', $e->getMessage());
        }



    }

    /**
     * Add Row in order received page order table
     *
     * @param [array] $total_rows
     * @param [object] $order
     * @return array
     */
    public function get_order_item_totals( $total_rows, $order ) {

		if ( $order->get_meta( '_woolentor_partial_payment_status' ) !== 'yes' ) {
			return $total_rows;
		}

		$parent_order = $order->get_meta( '_woolentor_partial_payment_parent_order' );
		if ( $parent_order !== 'yes' ) {
			$order = wc_get_order( $order->get_parent_id() );
		}

		// Deposit order no need to show 'order again' button
		remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );

        $first_installment_amount = $order->get_meta( '_woolentor_payment_schedule' )['installment']['first']['total'];
        $second_installment_amount = $order->get_meta( '_woolentor_payment_schedule' )['installment']['second']['total'];
        $total_payment = (float)$order->get_meta( '_woolentor_payment_schedule' )['default']['total'];
        $due_payment = $total_payment - $first_installment_amount;

		// Overwrite  default order tr
		$total_rows['order_total'] = array(
			'label' => apply_filters( 'label_order_total', esc_html__( 'Total:', 'woolentor-pro' ) ),
			'value' => apply_filters( 'woocommerce_deposit_top_pay_html', wc_price( $total_payment,  ['currency' => $order->get_currency()] ) ),
		);

		$total_rows['partial_payment_total'] = array(
			'label' => apply_filters( 'label_partial_payment_first_installment', esc_html__( 'First Installment:', 'woolentor-pro' ) ),
			'value' => apply_filters( 'woocommerce_partial_payment_first_installment_html', wc_price( $first_installment_amount, ['currency' => $order->get_currency()] ) ),
		);

		$total_rows['second_payment_paid'] = array(
			'label' => apply_filters( 'label_partial_payment_second_installment', esc_html__( 'Second Installment:', 'woolentor-pro' ) ),
			'value' => wc_price( $second_installment_amount, ['currency' => $order->get_currency()] ),
		);

		$total_rows['due_payment']  = array(
			'label' => apply_filters( 'label_due_payment', esc_html__( 'Due:', 'woolentor-pro' ) ),
			'value' => wc_price( $due_payment, ['currency' => $order->get_currency()] ),
		);

		return $total_rows;
	}

    /**
     * Partial Payment summary
     *
     * @param [object] $order
     * @return void
     */
	public function partial_payments_summary( $order ) {

		$parent_order = $order->get_meta( '_woolentor_partial_payment_parent_order' );

		$orders = wc_get_orders( [
			'parent'  => $parent_order == 'yes' ? $order->get_id() : $order->get_parent_id(),
			'type'    => 'woolentor_pp_payment',
			'orderby' => 'ID',
			'order'   => 'ASC',
		] );

		$order_has_partial_payment = $order->get_meta( '_woolentor_partial_payment_status', true );

		if ( $order_has_partial_payment === 'yes' ) {

			wc_get_template(
				'order/partial-payment-order-summery.php', 
                [
                    'parent_order_id' => $parent_order == 'yes' ? $order->get_id() : $order->get_parent_id(),
                    'order_id'        => $order->get_id(),
                    'orders'          => $orders
                ],
				'',
				WOOLENTOR_PARTIAL_PAYMENT_TEMPLATE_PATH
			);

		}
	}

    /**
     * Manage Order status
     *
     * @param [int] $order_id
     * @param [string] $old_status
     * @param [string] $new_status
     * @param [object] $order
     * @return void
     */
    public function order_status_changed( $order_id, $old_status, $new_status, $order  ){

        if ( ! $order->get_meta( '_woolentor_partial_payment_status' ) || $order->get_meta( '_woolentor_partial_payment_status' ) !== 'yes' ) {
			return true;
		}

		if ( $order->get_type() == 'woolentor_pp_payment' ) {

			$parent_order = wc_get_order( $order->get_parent_id() );

            $installment = $order->get_meta( '_woolentor_partial_payment_installment' );
            $partial_schedule = $parent_order->get_meta( '_woolentor_payment_schedule', true );

            if ( in_array( $new_status, [ 'processing', 'completed' ] ) ) {

                if ( $installment === 'first' && ( $partial_schedule['installment'][$installment]['paid'] != 'yes' ) ) {
                    $partial_schedule['installment'][$installment]['paid'] = 'yes';
                    $partial_schedule['default']['paid_amount'] = ( $partial_schedule['default']['paid_amount'] + (float)$partial_schedule['installment']['first']['total'] );
                    $parent_order->update_meta_data( '_woolentor_payment_schedule', $partial_schedule );
                }

                if ( $installment === 'second' && ( $partial_schedule['installment'][$installment]['paid'] != 'yes' ) ) {
                    $partial_schedule['installment'][$installment]['paid'] = 'yes';
                    $partial_schedule['default']['paid_amount'] = ( $partial_schedule['default']['paid_amount'] + (float)$partial_schedule['installment']['second']['total'] );
                    $parent_order->update_meta_data( '_woolentor_payment_schedule', $partial_schedule );
                }

                $all_paid = ['yes', 'yes' ];
                $installment_paid = array_column( $partial_schedule['installment'], 'paid' );
                if( $installment_paid == $all_paid ){
                    $parent_order->set_status( 'processing' );
                    $parent_order->save();
                }else{
                    $parent_order->set_status( 'on-hold' );
                    $parent_order->save();
                }

            }

            if ( in_array( $new_status, [ 'on-hold', 'failed', 'cancelled', 'pending' ] ) ) {

                if ( $installment === 'first' && ( $partial_schedule['installment'][$installment]['paid'] == 'yes' ) ) {
                    $partial_schedule['installment'][$installment]['paid'] = 'no';
                    $paid_amount = $partial_schedule['default']['paid_amount'] - (float)$partial_schedule['installment']['first']['total'];
                    $partial_schedule['default']['paid_amount'] = $paid_amount;
                    $parent_order->update_meta_data( '_woolentor_payment_schedule', $partial_schedule );
                }

                if ( $installment === 'second' && ( $partial_schedule['installment'][$installment]['paid'] == 'yes' ) ) {
                    $partial_schedule['installment'][$installment]['paid'] = 'no';
                    $paid_amount = $partial_schedule['default']['paid_amount'] - (float)$partial_schedule['installment']['second']['total'];
                    $partial_schedule['default']['paid_amount'] = $paid_amount;
                    $parent_order->update_meta_data( '_woolentor_payment_schedule', $partial_schedule );
                }
    
                $parent_order->set_status( 'on-hold' );
                $parent_order->save();

            }

		} else{

            $partial_schedule = $order->get_meta( '_woolentor_payment_schedule', true );

            if ( in_array( $old_status, [ 'pending', 'on-hold' ] ) && in_array( $new_status, ['processing','completed'] ) ) {

                foreach ( $partial_schedule['installment'] as $payment) {

                    $partial_payment = wc_get_order( $payment['id'] );

                    if ( $partial_payment ) {
                        if ( $partial_payment->get_status() !== $new_status ) {
                            $partial_payment->set_status( $new_status );
                            $partial_payment->save();
                        }
                    }

                }

            }


            if ( in_array( $old_status, [ 'pending', 'on-hold' ] ) && in_array( $new_status, [ 'failed', 'cancelled' ] ) ) {

                foreach ( $partial_schedule['installment'] as $payment) {

                    $partial_payment = wc_get_order( $payment['id'] );

                    if ( $partial_payment ) {
                        if ( $partial_payment->get_status() !== $new_status ) {
                            $partial_payment->set_status( $new_status );
                            $partial_payment->save();
                        }
                    }

                }

            }

        }

    }

    /**
     * Set Parent order status
     *
     * @param [int] $order_id
     * @return void
     */
    public function set_parent_order_on_hold( $order_id ){
        $order = wc_get_order( $order_id );
        if ( $order && $order->get_type() == 'woolentor_pp_payment' ) {
            $parent = wc_get_order( $order->get_parent_id() );
            if ( $parent ) {
                if ( $order->get_payment_method() == 'bacs' ) {
                    $parent->set_payment_method('bacs');
                }
                $parent->set_status('on-hold');
                $parent->save();
            }
        }
    }


}