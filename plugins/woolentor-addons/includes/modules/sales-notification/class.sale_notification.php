<?php

/**
* Class Sale Notification
*/
class Woolentor_Sale_Notification{

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){

        add_action( 'wp_head',[ $this, 'woolentor_ajaxurl' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'woolentor_inline_styles' ] );

        // ajax function
        add_action('wp_ajax_nopriv_woolentor_purchased_products', [ $this, 'purchased_new_products' ] );
        add_action('wp_ajax_woolentor_purchased_products', [ $this, 'purchased_new_products' ] );

        add_action( 'wp_footer', [ $this, 'woolentor_ajax_request' ] );
    }

    public function purchased_new_products(){

        $cachekey = 'woolentor-new-products';
        $products = get_transient( $cachekey );

        if ( ! $products ) {
            $args = array(
                'post_type' => 'shop_order',
                'post_status' => array('wc-completed', 'wc-pending', 'wc-processing', 'wc-on-hold'),
                'orderby' => 'ID',
                'order' => 'DESC',
                'posts_per_page' => woolentor_get_option( 'notification_limit','woolentor_sales_notification_tabs','5' ),
                'date_query' => array(
                    'after' => date('Y-m-d', strtotime('-'.woolentor_get_option('notification_uptodate','woolentor_sales_notification_tabs','5' ).' days'))
                )
            );
            $posts = get_posts($args);

            $products = array();
            $check_wc_version = version_compare( WC()->version, '3.0', '<') ? true : false;

            foreach( $posts as $post ) {

                $order = new WC_Order( $post->ID );
                $order_items = $order->get_items();

                if( !empty( $order_items ) ) {

                    if( woolentor_get_option( 'showallproduct','woolentor_sales_notification_tabs','off' ) == 'on' ){

                        foreach( $order_items as $item ){
                            $product = wc_get_product( $item['product_id'] );
                            if( !empty( $product ) ){
                                preg_match( '/src="(.*?)"/', $product->get_image( 'thumbnail' ), $imgurl );
                                $p = array(
                                    'id'    => $item['order_id'],
                                    'name'  => $product->get_title(),
                                    'url'   => $product->get_permalink(),
                                    'date'  => $post->post_date_gmt,
                                    'image' => count($imgurl) === 2 ? $imgurl[1] : null,
                                    'price' => $this->woolentor_productprice($check_wc_version ? $product->get_display_price() : wc_get_price_to_display($product) ),
                                    'buyer' => $this->woolentor_buyer_info($order)
                                );
                                $p = apply_filters( 'woolentor_product_data',$p );
                                array_push( $products, $p);
                            }
                        }

                    }else{
                        $first_item = array_values( $order_items )[0];
                        $product_id = $first_item['product_id'];

                        $product = wc_get_product( $product_id );
                        if( !empty( $product ) ){
                            preg_match( '/src="(.*?)"/', $product->get_image( 'thumbnail' ), $imgurl );
                            $p = array(
                                'id'    => $first_item['order_id'],
                                'name'  => $product->get_title(),
                                'url'   => $product->get_permalink(),
                                'date'  => $post->post_date_gmt,
                                'image' => count($imgurl) === 2 ? $imgurl[1] : null,
                                'price' => $this->woolentor_productprice($check_wc_version ? $product->get_display_price() : wc_get_price_to_display($product) ),
                                'buyer' => $this->woolentor_buyer_info($order)
                            );
                            $p = apply_filters( 'woolentor_product_data',$p );
                            array_push( $products, $p);
                        }
                    }

                }

            }
            set_transient( $cachekey, $products, 60 ); // Cache the results for 1 minute
        }
        echo( json_encode( $products ) );
        wp_die();

    }

    // Product Price
    private function woolentor_productprice($price) {
        if( empty( $price ) ){
            $price = 0;
        }
        return sprintf(
            get_woocommerce_price_format(),
            get_woocommerce_currency_symbol(),
            number_format($price,wc_get_price_decimals(),wc_get_price_decimal_separator(),wc_get_price_thousand_separator())
        );  
    }

    // Buyer Info
    private function woolentor_buyer_info( $order ){
        $address = $order->get_address('billing');
        if(!isset($address['city']) || strlen($address['city']) == 0 ){
            $address = $order->get_address('shipping');
        }
        $buyerinfo = array(
            'fname' => isset( $address['first_name']) && strlen($address['first_name'] ) > 0 ? ucfirst($address['first_name']) : '',
            'lname' => isset( $address['last_name']) && strlen($address['last_name'] ) > 0 ? ucfirst($address['last_name']) : '',
            'city' => isset( $address['city'] ) && strlen($address['city'] ) > 0 ? ucfirst($address['city']) : 'N/A',
            'state' => isset( $address['state']) && strlen($address['state'] ) > 0 ? ucfirst($address['state']) : 'N/A',
            'country' =>  isset( $address['country']) && strlen($address['country'] ) > 0 ? WC()->countries->countries[$address['country']] : 'N/A',
        );
        return $buyerinfo;
    }

    // Ajax URL Create
    function woolentor_ajaxurl() {
        ?>
            <script type="text/javascript">
                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            </script>
        <?php
    }

    // Inline CSS
    function woolentor_inline_styles() {
        wp_enqueue_style( 'woolentor-animate' );
        $bgcolor = woolentor_get_option( 'background_color','woolentor_sales_notification_tabs', '#ffffff' );
        $headingcolor = woolentor_get_option( 'heading_color','woolentor_sales_notification_tabs', '#000000' );
        $contentcolor = woolentor_get_option( 'content_color','woolentor_sales_notification_tabs', '#7e7e7e' );
        $crosscolor = woolentor_get_option( 'cross_color','woolentor_sales_notification_tabs', '#000000' );
        $width = woolentor_get_option( 'notification_width','woolentor_sales_notification_tabs', '550px' );
        $mobile_width = woolentor_get_option( 'notification_mobile_width','woolentor_sales_notification_tabs', '90%' );

        $custom_css = "";

        if( $width ){
            $custom_css .= "
                .woolentor-notification-content{
                    width: {$width} !important;
                }
            ";
        }
        if( $bgcolor ){
            $custom_css .= "
                .woolentor-notification-content{
                    background: {$bgcolor} !important;
                }
            ";
        }

        if( $headingcolor ){
            $custom_css .= "
                .wlnotification_content h4,.wlnotification_content h6{
                    color: {$headingcolor} !important;
                }
            ";
        }

        if( $contentcolor ){
            $custom_css .= "
                .wlnotification_content p,.woolentor-buyername{
                    color: {$contentcolor} !important;
                }
            ";
        }

        if( $crosscolor ){
            $custom_css .= "
                .wlcross{
                    color: {$crosscolor} !important;
                }
            ";
        }

        if( $mobile_width ){
            $custom_css .= "
                @media (max-width: 767px) {
                    .woolentor-notification-content{
                        width: {$mobile_width} !important;
                    }
                }
            ";
        }

        wp_add_inline_style( 'woolentor-widgets', $custom_css );
    }

    // Ajax request
    function woolentor_ajax_request() {

        $intervaltime  = (int)woolentor_get_option( 'notification_time_int','woolentor_sales_notification_tabs', '4' )*1000;
        $duration      = (int)woolentor_get_option( 'notification_loadduration','woolentor_sales_notification_tabs', '3' )*1000;
        $showing       = (int)woolentor_get_option( 'notification_time_showing','woolentor_sales_notification_tabs', '5' )*1000;
        $inanimation   = woolentor_get_option( 'notification_inanimation','woolentor_sales_notification_tabs', 'fadeInLeft' );
        $outanimation  = woolentor_get_option( 'notification_outanimation','woolentor_sales_notification_tabs', 'fadeOutRight' );
        $notposition   = woolentor_get_option( 'notification_pos','woolentor_sales_notification_tabs', 'bottomleft' );
        $notlayout     = woolentor_get_option( 'notification_layout','woolentor_sales_notification_tabs', 'imageleft' );

        // Set Nonce
        $ajax_nonce = wp_create_nonce( "woolentor-ajax-request" );
        ?>
            <script>
                ;jQuery( document ).ready( function( $ ) {

                    var notposition = '<?php echo $notposition; ?>',
                        notlayout = ' '+'<?php echo $notlayout; ?>';

                    var other_text = {
                        price:'<?php echo esc_html__('Price :','woolentor');?>',
                        priceby:'<?php echo esc_html__('By','woolentor');?>',
                    };

                    $('body').append('<div class="woolentor-sale-notification"><div class="woolentor-notification-content '+notposition+notlayout+'"></div></div>');

                    var data = {
                        action: 'woolentor_purchased_products',
                        security: '<?php echo $ajax_nonce; ?>',
                        whatever: 1234
                    };
                    
                    var intervaltime = <?php echo $intervaltime; ?>,
                        duration = <?php echo $duration; ?>,
                        showing_time = <?php echo $showing; ?>,
                        inanimation = '<?php echo $inanimation; ?>',
                        outanimation = '<?php echo $outanimation; ?>',
                        i = 0;

                    
                    function woolentor_get_order_data() {
                        $.post(
                            ajaxurl, 
                            data,
                            function( response ){
                                var wcpobj = $.parseJSON( response );
                                if( wcpobj.length > 0 ){
                                    woolentor_start_notification( wcpobj );
                                }
                            }
                        );
                    }

                    function woolentor_start_notification ( wcpobj ) {
                        window.setTimeout( function(){
                            woolentor_notification_content( wcpobj, i );
                            setTimeout( function () { 
                                $('.woolentor-notification-content').removeClass(inanimation).addClass(outanimation);
                                i++;
                            }, showing_time );
                            woolenor_notification_loop_start( wcpobj );
                        }, duration );

                    }

                    function woolentor_notification_content( wlpobj, i ){
                        $('.woolentor-notification-content').html('');
                        $('.woolentor-notification-content').css('padding','15px');
                        var ordercontent = `<div class="wlnotification_image"><img src="${wlpobj[i].image}" alt="${wlpobj[i].name}" /></div><div class="wlnotification_content"><h4><a href="${wlpobj[i].url}">${wlpobj[i].name}</a></h4><p>${wlpobj[i].buyer.city + ' ' + wlpobj[i].buyer.state + ', ' + wlpobj[i].buyer.country }.</p><h6>${other_text.price+wlpobj[i].price}</h6><span class="woolentor-buyername">${other_text.priceby + ' ' + wlpobj[i].buyer.fname + ' ' + wlpobj[i].buyer.lname}</span></div><span class="wlcross">&times;</span>`;
                        $('.woolentor-notification-content').append( ordercontent ).addClass('animated '+inanimation).removeClass(outanimation);
                    }

                    function woolenor_notification_loop_start( wcpobj ){
                        var interval = parseInt( intervaltime ) + parseInt( showing_time );
                        setInterval(function ( wcpobj ) {
                            if( i == wcpobj.length ){ i = 0; }

                            woolentor_notification_content( wcpobj, i );

                            var notification = wcpobj;
                            if ( notification != undefined ) {
                                setTimeout( function () { 
                                    $('.woolentor-notification-content').removeClass(inanimation).addClass(outanimation);
                                    i++;
                                }, showing_time );
                            }
                        }, interval, wcpobj );
                    }

                    // Close Button
                    $('.woolentor-notification-content').on('click', '.wlcross', function(e){
                        e.preventDefault()
                        $(this).closest('.woolentor-notification-content').removeClass(inanimation).addClass(outanimation);
                    });

                    woolentor_get_order_data();

                });
            </script>
        <?php 
    }


}

Woolentor_Sale_Notification::instance();