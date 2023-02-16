<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment_List{

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
        
        // Manage Partial Payment order columns
        add_filter( 'manage_edit-woolentor_pp_payment_columns', [ $this, 'order_columns' ] );
        add_filter( 'manage_edit-woolentor_pp_payment_sortable_columns', [ $this, 'sortable_columns' ] );
        add_action( 'manage_woolentor_pp_payment_posts_custom_column', [ $this, 'custom_columns' ], 10, 2 );
        add_action( 'pre_get_posts', [ $this, 'show_all_orders' ], 10, 1 );
        add_action( 'pre_get_posts', [ $this, 'order_by_columns' ], 10, 1 );

    }

    /**
     * Manage Partial Payment Columns
     *
     * @param [array] $columns
     * @return array
     */
    public function order_columns( $columns ){

        unset( $columns['title'], $columns['comments'], $columns['date'] );

        $columns['partial_id'] = esc_html__( 'Partial Payment ID', 'woolentor-pro' );
        $columns['partial_status'] = esc_html__( 'Status', 'woolentor-pro' );
        $columns['partial_date']  = esc_html__( 'Date', 'woolentor-pro' );
        $columns['partial_total'] = esc_html__( 'Total', 'woolentor-pro' );
        $columns['parent_order']  = esc_html__( 'Order', 'woolentor-pro' );

        return $columns;

    }

    /**
     * Manage Shortable Columns
     *
     * @param [array] $columns
     * @return void
     */
    public function sortable_columns( $columns ){
        $columns['partial_id']   = 'partial_id';
        $columns['parent_order'] = 'parent_order';
        $columns['partial_date'] = 'partial_date';

        return $columns;
    }

    /**
     * Manage Custom Columns
     *
     * @param [string] $column_name
     * @param [ind] $post_id
     * @return void
     */
    public function custom_columns( $column_name, $post_id ){

        $order = wc_get_order( $post_id );

        $parent_order_id = $order->get_parent_id();
        $parent_order    = wc_get_order( $parent_order_id ) ? wc_get_order( $parent_order_id ) : $order;

        switch ( $column_name ) {

            case 'partial_id':

                if ( $parent_order->get_billing_first_name() || $parent_order->get_billing_last_name() ) {
                    $buyer_name = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $parent_order->get_billing_first_name(), $parent_order->get_billing_last_name() ) );
                } elseif ( $parent_order->get_billing_company() ) {
                    $buyer_name = trim( $parent_order->get_billing_company() );
                } elseif ( $parent_order->get_customer_id() ) {
                    $user       = get_user_by( 'id', $parent_order->get_customer_id() );
                    $buyer_name = ucwords( $user->display_name );
                }else{
                    $buyer_name = '';
                }

                if ( $order->get_status() === 'trash' ) {
                    echo '<strong>#' . esc_attr( $order->get_order_number() ) . ' ' . esc_html( $buyer_name ) . '</strong>';
                } else {
                    echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $order->get_id() ) ) . '&action=edit' ) . '" class="order-view"><strong>#' . esc_attr( $order->get_order_number() ) . ' ' . esc_html( $buyer_name ) . '</strong></a>';
                }
                break;
            
            case 'partial_status':
                echo sprintf( '<mark class="order-status %s tips"><span>%s</span></mark>', esc_attr( sanitize_html_class( 'status-' . $order->get_status() ) ), wc_get_order_status_name( $order->get_status() ) );
                break;

            case 'partial_date':

                $order_timestamp = $order->get_date_created() ? $order->get_date_created()->getTimestamp() : '';

                if ( $order_timestamp ) {
                    // Check if the order was created within the last 24 hours, and not in the future.
                    if ( $order_timestamp > strtotime( '-1 day', current_time( 'timestamp', true ) ) && $order_timestamp <= current_time( 'timestamp', true ) ) {
                        $show_date = sprintf(
                        /* translators: %s: human-readable time difference */
                            _x( '%s ago', '%s = human-readable time difference', 'woocommerce' ),
                            human_time_diff( $order->get_date_created()->getTimestamp(), current_time( 'timestamp', true ) )
                        );
                    } else {
                        $show_date = $order->get_date_created()->date_i18n( apply_filters( 'woocommerce_admin_order_date_format', esc_html__( 'M j, Y', 'woocommerce' ) ) );
                    }
                    printf(
                        '<time datetime="%1$s" title="%2$s">%3$s</time>',
                        esc_attr( $order->get_date_created()->date( 'c' ) ),
                        esc_html( $order->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ),
                        esc_html( $show_date )
                    );
                }else{
                    echo '&ndash;';
                }

                break;

            case 'partial_total': 
                echo wc_price( $order->get_total() );
                break;

            case 'parent_order':
                echo '<a href="' . esc_url( admin_url( 'post.php?post=' . $parent_order_id ) . '&action=edit' ) . '" class="order-view">#' . $parent_order_id . '</a>';
                break;

        }

    }

    /**
     * Show All partial payment order list
     *
     * @param [object] $query
     * @return void
     */
    public function show_all_orders( $query ) {

        if ( is_admin() && $query->is_main_query() && $query->get( 'post_type' ) == "woolentor_pp_payment" ) {
            if ( !isset( $_GET['post_status'] ) ) {
                $query->set( 'post_status', 'any' );
            }
        }

    }

    /**
     * Manage order by
     *
     * @param [object] $query
     * @return void
     */
    public function order_by_columns( $query ) {
        if ( !is_admin() ) {
            return;
        }

        if ( $query->is_main_query() && $query->get( 'post_type' ) == "woolentor_pp_payment" && !isset( $_GET['post_status'] ) ) {
            $orderby = $query->get( 'orderby' );
            $order   = $query->get( 'order' );
            if( 'partial_id' == $orderby || 'parent_order' == $orderby ){
                $query->set( 'order', $order );
                $query->set( 'orderby', 'ID' );
            }elseif( 'partial_date' == $orderby ){
                $query->set( 'order', $order );
                $query->set( 'orderby', 'date' );
            }
        }

    }


}