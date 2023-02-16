<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Admin_Pre_Orders extends Woolentor_Pre_Orders{

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

        // Manage Product Post column
        add_filter( 'manage_edit-product_columns', [ $this, 'product_columns' ] );
        add_action( 'manage_product_posts_custom_column', [ $this, 'posts_custom_column' ], 10, 2 );

        // Manage Shop Order Post column
        add_filter( 'manage_edit-shop_order_columns', [ $this, 'shop_order_columns' ] );
        add_action( 'manage_shop_order_posts_custom_column', [ $this, 'shop_order_posts_custom_column' ], 10, 2 );

        // Add Pre order status
        add_action( 'woocommerce_after_order_itemmeta', [ $this, 'after_order_itemmeta' ], 10, 3 );

    }

    /**
     * Manage Product Column
     *
     * @param [array] $columns
     * @return void
     */
    public function product_columns( $columns ){

        $column_date     = $columns['date'];
        $column_featured = $columns['featured'];

        unset( $columns['date'], $columns['featured'] );

        $columns['woolentor_pre_order'] = esc_html__( 'Pre-ordering will end after', 'woolentor-pro' );
        $columns['date']     = esc_html( $column_date );
        $columns['featured'] = $column_featured;

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

        if ( 'woolentor_pre_order' === $column_name ) {
            if ( $this->get_pre_order_status( $post_id ) ) {

                $pre_order_date = $this->get_saved_data( $post_id, 'woolentor_pre_order_available_date', 'woolentor_pre_order_available_date' );
                $pre_order_time = $this->get_saved_data( $post_id, 'woolentor_pre_order_available_time', 'woolentor_pre_order_available_time' );

                $gmt_offdet      = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
                $date_str        = strtotime( $pre_order_date );
                $time_total      = $gmt_offdet + $date_str;
                $current_date    = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
                $date_format     = date_i18n( get_option( 'date_format' ), $time_total );
                // $time_remaining  = $time_total - $current_date;

                $remainingTime   = ( strtotime( $pre_order_time ) - strtotime( 'TODAY' ) + strtotime( $pre_order_date ) ) - current_time( 'timestamp' );

                $current_time= new DateTime( '@0' );
                $different   = new DateTime( "@$remainingTime" );
                if ( $remainingTime > 0 ) {
                    if ( $remainingTime > 86400 ) {
                        $due_time = $current_time->diff( $different )->format( '%a days %h hours' );
                        echo '<div class="woolentor-pre-order-after" style="color:#ec5858;">' . esc_html( $due_time ) . '</div>';
                    } else {
                        $due_time = $current_time->diff( $different )->format( '%h hours %i minutes' );
                        echo '<div class="woolentor-pre-order-after" style="color:#ec5858;">' . esc_html( $due_time ) . '</div>';
                    }
                }

            }
        }

    }

    /**
     * Manage Shop Order Column
     *
     * @param [array] $columns
     * @return void
     */
    public function shop_order_columns( $columns ){

        $column_order_total = $columns['order_total'];

        unset( $columns['order_total'] );

        $columns['woolentor_pre_order'] = esc_html__( 'Pre-order Product', 'woolentor-pro' );
        $columns['order_total'] = esc_html( $column_order_total );

		return $columns;

    }

    /**
     * Check Pre-Order Item In order list
     *
     * @param [int] $product_id
     * @param [int] $order_created_timestamp
     * @return boolean
     */
    public function has_pre_order_item( $product_id, $order_created_timestamp ){

        $pre_order_date = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_date', 'woolentor_pre_order_available_date' );
        $pre_order_time = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_time', 'woolentor_pre_order_available_time' );
        $pre_order_date_timestamp  = ( strtotime( $pre_order_time ) - strtotime( 'TODAY' ) + strtotime( $pre_order_date ) );

        if( ( get_post_meta( $product_id, 'woolentor_pre_order_enable', true ) === 'yes' ) && ( $pre_order_date_timestamp >= $order_created_timestamp ) ){
            return true;
        }else{
            return false;
        }

    }

    /**
     * Manage Custom column content
     *
     * @param [string] $column_name
     * @param [int] $post_id
     * @return void
     */
    public function shop_order_posts_custom_column( $column_name, $post_id ){

        if ( 'woolentor_pre_order' === $column_name ) {

            $order          = wc_get_order( $post_id );
            $order_item     = $order->get_items();
            $date_created   = $order->get_date_created();

            foreach ( $order_item as $item_id => $item ) {
                $product = $item->get_product();
                if ( $product ) {
                    $product_id   = $item->get_product_id();
                    $variation_id = $item->get_variation_id();
                    $name         = $item->get_name();
                    $product_type = $product->get_type();
                    $quantity     = $item->get_quantity();

                    $order_has_pre_order = $order->get_meta( 'woolentor_pre_order' , true );
                    $status = $this->has_pre_order_item( $product_id, $date_created->getTimestamp() );
                    if ( $status === true && $order_has_pre_order === 'yes' ) {
                        echo esc_html( $name ) . ' ' . 'x' . ' ' . esc_html( $quantity ) . '<br>';
                    }

                }
            }

        }

    }

    /**
     * Add Pre order status
     *
     * @param [int] $item_id
     * @param [object] $item
     * @param [object] $product
     * @return void
     */
    public function after_order_itemmeta( $item_id, $item, $product ) {
		if ( ! $item->is_type( 'line_item' ) ) {
			return;
		}
        $order = wc_get_order( $item->get_order_id() );
        $date_created   = $order->get_date_created();
		if ( $product ) {
			$product_id   = $item->get_product_id();
			$variation_id = $item->get_variation_id();
			$product_type = $product->get_type();

            $order_has_pre_order = $order->get_meta( 'woolentor_pre_order' , true );
            $status = $this->has_pre_order_item( $product_id, $date_created->getTimestamp() );
            if ( $status === true && $order_has_pre_order === 'yes' ) {
                echo __( apply_filters('woolentor_text_pre_order_line_items', '<span style="background: #ec5858;border-radius: 3px;padding: 2px 8px;color: #fff;">Pre-Order</span> : Yes' ), 'woolentor-pro' );
            }

		}

	}


}