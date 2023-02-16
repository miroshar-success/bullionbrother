<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment_Admin extends Woolentor_Partial_Payment{

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

        // Include Nessary file
        $this->include();

        // Manage Order Post column
        add_filter( 'manage_edit-shop_order_columns', [ $this, 'shop_order_columns' ] );
        add_action( 'manage_shop_order_posts_custom_column', [ $this, 'posts_custom_column' ], 10, 2 );

        // Manage Partial Payment data
        add_action( 'woocommerce_admin_order_totals_after_total', [ $this, 'admin_order_totals_after_total' ] );
        add_action( 'add_meta_boxes', [ $this, 'partial_payments_metabox' ], 31 );

        // Add Status In Product Column
        add_action( 'woocommerce_admin_stock_html', [ $this, 'add_status_in_stock_column' ], 10, 2 );

        // Partial Payment List manager
        Woolentor_Partial_Payment_List::get_instance();

    }

    /**
     * Inclode Nessery file
     *
     * @return void
     */
    public function include(){
        // Partial Payment List
        include_once( __DIR__. '/class.partial-payment-list.php' );
    }

    /**
     * Manage Post Column
     *
     * @param [array] $columns
     * @return void
     */
    public function shop_order_columns( $columns ){

        $column_order_total = $columns['order_total'];
        $column_wc_actions  = $columns['wc_actions'];

        unset( $columns['order_total'], $columns['wc_actions'] );

        $columns['woolentor_order_partial_payment'] = esc_html__( 'Partial Payment', 'woolentor-pro' );
        $columns['order_total']  = esc_html( $column_order_total );
        $columns['wc_actions']   = esc_html( $column_wc_actions );

		return $columns;

    }

    /**
     * Manage Custom column content
     *
     * @param [string] $column_name
     * @param [int] $post_id
     * @return void
     */
    public function posts_custom_column( $column_name, $post_id ){

        if ( 'woolentor_order_partial_payment' === $column_name ) {

            $order = wc_get_order( $post_id );

            if( $order ){
                $order_has_partial_payment = $order->get_meta( '_woolentor_partial_payment_status' , true );

                if( $order_has_partial_payment === 'yes' ){
                    echo '<span class="woolentor_has_partial_payment">'.esc_html__('Yes','woolentor-pro').'</span>';
                } else {
                    echo '<span class="woolentor_has_partial_payment">'.esc_html__('No','woolentor-pro').'</span>';
                }

            }
        }

    }

    /**
     * Manage Partial paymanet metabox
     *
     * @return void
     */
    public function partial_payments_metabox() {
		global $post;
		if ( is_null( $post ) ) {
			return;
		}
		$order = wc_get_order( $post->ID );

		if ( $order && $order->get_meta( '_woolentor_partial_payment_status' ) == 'yes' ) {

			$parent_order = $order->get_meta( '_woolentor_partial_payment_parent_order' );

			if ( $order->get_type() === 'woolentor_pp_payment' ) {

				add_meta_box( 'woolentor_partial_payments',
					esc_html__( 'Main Order', 'woolentor-pro' ),
					[ $this, 'original_order_metabox' ],
					'woolentor_pp_payment',
					'side',
					'high'
				);

			} else {

				if ( $parent_order == 'yes' ) {
					add_meta_box( 'woolentor_partial_payments',
						esc_html__( 'Partial Payments', 'woolentor-pro' ),
						[ $this, 'partial_payments_summary' ],
						'shop_order',
						'normal',
						'high' 
                    );
				}

			}
		}
	}

    /**
     * Main Order visit
     *
     * @return void
     */
    public function original_order_metabox() {
		global $post;
		$order = wc_get_order( $post->ID );
		if ( ! $order ) {
			return;
		}

		$parent = wc_get_order( $order->get_parent_id() );

		if ( ! $parent ) {
			return;
		}

		?>
            <p><?php echo sprintf( esc_html__( 'This is a partial payment for order %s', 'woolentor-pro' ), $parent->get_order_number() ); ?></p>
            <a class="button btn" href="<?php echo esc_url( $parent->get_edit_order_url() ); ?> "> 
                <?php esc_html_e( 'View', 'woolentor-pro' ); ?> 
            </a>
		<?php
	}

    /**
     * Partial Payment metabox order summary
     *
     * @return void
     */
	public function partial_payments_summary() {

        global $post;
        $order = wc_get_order( $post->ID );

		include( WOOLENTOR_PARTIAL_PAYMENT_TEMPLATE_PATH . 'admin/partial-payments-summery.php' );

	}

    /**
     * Add table row in admin order total table
     *
     * @param [int] $order_id
     * @return void
     */
    public function admin_order_totals_after_total( $order_id ) {

		$order = wc_get_order( $order_id );

        if ( $order->get_type() === 'woolentor_pp_payment' ) {
            return;
        }

        $partial_schedule = $order->get_meta( '_woolentor_payment_schedule', true );
        if ( !is_array( $partial_schedule ) || empty( $partial_schedule ) ){
            return;
        }

        $first_installment_amount = $partial_schedule['installment']['first']['total'];
        $second_installment_amount = $partial_schedule['installment']['second']['total'];
        $total_payment = (float)$partial_schedule['default']['total'];
        $due_payment = $total_payment - $partial_schedule['default']['paid_amount'];

		if ( $order->get_meta( '_woolentor_partial_payment_status' ) == 'yes' ) {

			?>
                <tr>
                    <td class="label">
                        <?php esc_html_e( 'First Installment', 'woolentor-pro' ); ?>:
                    </td>
                    <td width="1%"></td>
                    <td class="total paid"><?php echo wc_price( $first_installment_amount, array( 'currency' => $order->get_currency() ) ); ?></td>
                </tr>

                <tr class="woolentor-remaining">
                    <td class="label"><?php esc_html_e( 'Second Installment', 'woolentor-pro' ); ?>:</td>
                    <td width="1%"></td>
                    <td class="total remaining"><?php echo wc_price( $second_installment_amount, array( 'currency' => $order->get_currency() ) ); ?></td>
                </tr>

                <tr class="woolentor-remaining">
                    <td class="label"><?php esc_html_e( 'Due', 'woolentor-pro' ); ?>:</td>
                    <td width="1%"></td>
                    <td class="total remaining"><?php echo wc_price( $due_payment, array( 'currency' => $order->get_currency() ) ); ?></td>
                </tr>
			<?php

		}

		return;
	}
    
    /**
     * Add Partial Payment Status
     *
     * @param [HTML] $stock_html
     * @param \WC_Product $product
     * @return HTML
     */
    public function add_status_in_stock_column( $stock_html, \WC_Product $product ){
		if ( $product->get_meta( 'woolentor_partial_payment_enable' ) === 'yes' ) {
			$stock_html .= '<mark class="onbackorder">&#160;'. esc_html__('& Partial Payment', 'woolentor-pro') .'</mark>';
		}
		return $stock_html;
	}


}