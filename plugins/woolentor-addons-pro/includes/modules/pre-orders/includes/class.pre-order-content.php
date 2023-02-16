<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Pre_Order_Content extends Woolentor_Pre_Orders{

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

        // Single Product
        add_action('woocommerce_before_add_to_cart_button', [ $this, 'before_add_to_cart_button' ], 999, 0 );

        // Cart Item
        add_filter( 'woocommerce_get_item_data', [ $this, 'get_item_data' ], 99, 2 );

        // Order Received page
        add_action( 'woocommerce_order_item_meta_end', [ $this, 'order_item_meta_end' ], 10, 4 );
		

    }

    /**
     * add pre order content
     *
     * @return void
     */
    public function before_add_to_cart_button(){
        global $post;

		if ( $this->get_pre_order_status( $post->ID ) ) {
			echo $this->pre_order_content( $post->ID );
		}

    }

    /**
     * Manage cart page item
     *
     * @param [HTML] $name
     * @param [array] $cart_item
     * @return html
     */
    public function get_item_data( $item_data, $cart_item ){

        $product_id = $cart_item['product_id'];

        if ( $this->get_pre_order_status( $product_id ) ) {
            $item_data[] = array(
                'name'      => 'woolentor_cart_availability',
                'display'   => $this->availability_date( $product_id ),
                'value'     => 'wc_woolentor_pre_order_content',
            );
        }

        return $item_data;

    }

    /**
     * Order Item Meta
     *
     * @param [int] $item_id
     * @param [array] $item
     * @param [object] $order
     * @param [html] $plain_tex
     * @return void
     */
    public function order_item_meta_end( $item_id, $item, $order, $plain_tex ){
        $product_id  = $item['product_id'];

        echo $this->availability_date( $product_id );
        
    }

    /**
     * Pre Order content
     *
     * @param [int] $product_id
     * @return void
     */
    public function pre_order_content( $product_id ){

        $show_countdown         = $this->get_saved_data( $product_id, 'show_countdown', 'show_countdown', 'on' );
        $availability_date_text = $this->get_saved_data( $product_id, 'availability_date', 'availability_date', 'Available on: {availability_date} at {availability_time}' );
        $pre_order_date         = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_date', 'woolentor_pre_order_available_date' );
        $pre_order_time         = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_time', 'woolentor_pre_order_available_time' );
        
        $gmt_offdet             = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
        $date_str               = strtotime( $pre_order_date );
		$time_total             = $gmt_offdet + $date_str;
        $current_date           = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
        $date_format            = date_i18n( get_option( 'date_format' ), $time_total );
        $time_format            = date_i18n( get_option( 'time_format' ), strtotime( $pre_order_time ) - strtotime( 'TODAY' ) );

        $availability_date      = $this->replace_placeholder( '{availability_date}', '<span class="woolentor-availability-date">'.$date_format.'</span>', $availability_date_text );
        $availability_date      = $this->replace_placeholder( '{availability_time}', '<span class="woolentor-availability-time">'.$time_format.'</span>', $availability_date );

        // Coundown
        // $pre_order_end_date = date_i18n( 'Y/m/d h:i:s', $time_total );
        $remainingTime    = ( strtotime( $pre_order_time ) - strtotime( 'TODAY' ) + strtotime( $pre_order_date ) ) - current_time( 'timestamp' );
        $data_customlavel = [];
        $data_customlavel['daytxt']     = $this->get_saved_data( $product_id, 'customlabel_days', 'customlabel_days', 'Days' );
        $data_customlavel['hourtxt']    = $this->get_saved_data( $product_id, 'customlabel_hours', 'customlabel_hours', 'Hours' );
        $data_customlavel['minutestxt'] = $this->get_saved_data( $product_id, 'customlabel_minutes', 'customlabel_minutes', 'Min' );
        $data_customlavel['secondstxt'] = $this->get_saved_data( $product_id, 'customlabel_seconds', 'customlabel_seconds', 'Sec' );

        ob_start();
        ?>
            <div class="woolentor-pre-order-area">
                <?php
                    if ( $remainingTime > 0 ) {
                        echo '<div class="woolentor-availability-date-area"><div class="woolentor-availability-date-inner">'.$availability_date.'</div></div>';
                    }

                    if( $show_countdown === 'on' && ! empty( $pre_order_date ) ){
                        ?>
                            <div class="woolentor-pre-order-countdown" data-countdown="<?php echo esc_attr( $remainingTime ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                        <?php
                    }
                ?>
            </div>
        <?php
        return ob_get_clean();

    }

    /**
     * Manage availability date message
     *
     * @param [int] $product_id
     * @return void
     */
    public function availability_date( $product_id ){

        if ( $this->get_pre_order_status( $product_id ) ) {

            $availability_date_text = $this->get_saved_data( $product_id, 'availability_date', 'availability_date', 'Available on: {availability_date} at {availability_time}' );
            $pre_order_date         = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_date', 'woolentor_pre_order_available_date' );
            $pre_order_time         = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_time', 'woolentor_pre_order_available_time' );
            
            $gmt_offdet             = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
            $date_str               = strtotime( $pre_order_date );
            $time_total             = $gmt_offdet + $date_str;
            $current_date           = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
            $date_format            = date_i18n( get_option( 'date_format' ), $time_total );
            $time_format            = date_i18n( get_option( 'time_format' ), strtotime( $pre_order_time ) - strtotime( 'TODAY' ) );
            $availability_date      = $this->replace_placeholder( '{availability_date}', '<span class="woolentor-availability-date">'.$date_format.'</span>', $availability_date_text );
            $availability_date      = $this->replace_placeholder( '{availability_time}', '<span class="woolentor-availability-time">'.$time_format.'</span>', $availability_date );

            $pre_order_date_content = '<div class="woolentor-pre-order-availability-date-cart">'.$availability_date.'</div>';
            
            return $pre_order_date_content;
            
        }else{
            return '';
        }

    }


}