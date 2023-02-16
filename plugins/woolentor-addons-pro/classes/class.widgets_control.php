<?php
namespace WooLentorPro;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Widgets Control
*/
class Widgets_Control{
    
    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->init();
    }

    public function init() {
        add_filter( 'woolentor_widget_list', [ $this, 'widget_list' ] );
    }

    // Get Widget List
    public function widget_list( $widget_list ){

        $widget_list['common']['wl_product_grid'] = [
            'title'    => esc_html__('Product Grid','woolentor-pro'),
            'is_pro'   => true,
        ];

        $widget_list['common']['wl_product_expanding_grid'] = [
            'title'    => esc_html__('Product expandding Grid','woolentor-pro'),
            'is_pro'   => true,
        ];

        $widget_list['common']['wl_product_filterable_grid'] = [
            'title'    => esc_html__('Product Filterable Grid','woolentor-pro'),
            'is_pro'   => true,
        ];

        $widget_list['common']['wl_template_selector'] = [
            'title'    => esc_html__('Template Selector','woolentor-pro'),
            'is_pro'   => true,
        ];

        if( woolentor_get_option_pro( 'ajaxsearch', 'woolentor_others_tabs', 'off' ) == 'on' ){
            $widget_list['common']['ajax_search_form'] = [
                'title'    => esc_html__('Ajax Search Form','woolentor-pro'),
                'is_pro'   => true,
            ];
        }

        if( woolentor_get_option( 'enable', 'woolentor_product_filter_settings', 'off' ) == 'on' ){
            $widget_list['builder_common']['wl_advance_product_filter'] = [
                'title'  => esc_html__('Advance Product Filter','woolentor-pro'),
                'is_pro' => true,
            ];
        }

        /* Single Product */
        $widget_list['single']['wl_product_advance_thumbnails'] = [
            'title'    => esc_html__('Product advance thumbnails','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_product_advance_thumbnails_zoom'] = [
            'title'    => esc_html__('Product advance thumbnails zoom','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_social_shere'] = [
            'title'    => esc_html__('Product Social share','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_stock_progress_bar'] = [
            'title'    => esc_html__('Product stock progress bar','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_single_product_sale_schedule'] = [
            'title'    => esc_html__('Product sale schedule','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_related_product'] = [
            'title'    => esc_html__('Related Product (Custom)','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_product_upsell_custom'] = [
            'title'    => esc_html__('Upsell Product (Custom)','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_quickview_product_image'] = [
            'title'    => esc_html__('Product Quickview','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['single']['wl_single_pdoduct_navigation'] = [
            'title'    => esc_html__('Product Navigation','woolentor-pro'),
            'is_pro'   => true,
        ];

        /* Shop / Archive */
        $widget_list['shop']['wl_custom_archive_layout'] = [
            'title'    => esc_html__('Archive Layout Default (Custom)','woolentor-pro'),
            'is_pro'   => true,
        ];

        /* Cart Page */
        $widget_list['cart']['wl_cart_table'] = [
            'title'    => esc_html__('Cart Table','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['cart']['wl_cart_table_list'] = [
            'title'    => esc_html__('Cart Table - List','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['cart']['wl_cart_total'] = [
            'title'    => esc_html__('Cart Total','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['cart']['wl_cross_sell'] = [
            'title'    => esc_html__('Cross sells','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['cart']['wl_cross_sell_custom'] = [
            'title'    => esc_html__('Cross sells (Custom)','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['cart']['wl_cartempty_shopredirect'] = [
            'title'    => esc_html__('Return To Shop','woolentor-pro'),
            'is_pro'   => true,
        ];

        $widget_list['cart']['wl_checkout_coupon_form'] = [
            'title'    => esc_html__('Coupon form','woolentor-pro'),
            'is_pro'   => true,
        ];

        /* Empty Cart Page */
        $widget_list['emptycart']['wl_cartempty_message'] = [
            'title'    => esc_html__('Empty Cart Message','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['emptycart']['wl_cartempty_shopredirect'] = [
            'title'    => esc_html__('Return To Shop','woolentor-pro'),
            'is_pro'   => true,
        ];

        /* Side Mini Cart */
        $widget_list['minicart']['wl_mini_cart'] = [
            'title'    => esc_html__('Mini Cart','woolentor-pro'),
            'is_pro'   => true,
        ];

        /* Checkout Page */
        if( woolentor_get_option_pro( 'multi_step_checkout', 'woolentor_others_tabs', 'off' ) == 'on' ){
            $widget_list['checkout']['wl_checkout_multi_step'] = [
                'title'    => esc_html__('Checkout multi step','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_multi_step_style_2'] = [
                'title'    => esc_html__('Checkout multi step style 2','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_multi_step_style_2_nav'] = [
                'title'    => esc_html__('Checkout multi step style 2 Nav','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_login_form'] = [
                'title'    => esc_html__('Checkout login form','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_order_review'] = [
                'title'    => esc_html__('Checkout order overview','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_coupon_form'] = [
                'title'    => esc_html__('Coupon form','woolentor-pro'),
                'is_pro'   => true,
            ];
        }else{
            $widget_list['checkout']['wl_checkout_billing'] = [
                'title'    => esc_html__('Checkout billing form','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_shipping_form'] = [
                'title'    => esc_html__('Checkout shipping form','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_additional_form'] = [
                'title'    => esc_html__('Checkout Additional form','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_payment'] = [
                'title'    => esc_html__('Checkout payment method','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_coupon_form'] = [
                'title'    => esc_html__('Coupon form','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_login_form'] = [
                'title'    => esc_html__('Checkout login form','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_order_review'] = [
                'title'    => esc_html__('Checkout order overview','woolentor-pro'),
                'is_pro'   => true,
            ];
            $widget_list['checkout']['wl_checkout_shipping_method'] = [
                'title'    => esc_html__('Checkout shipping method','woolentor-pro'),
                'is_pro'   => true,
            ];
        }

        /* My Account page */
        $widget_list['myaccount']['wl_myaccount_account'] = [
            'title'    => esc_html__('My Account All in One','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_navigation'] = [
            'title'    => esc_html__('My Account Navigation','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_dashboard'] = [
            'title'    => esc_html__('My Account Dashboard','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_download'] = [
            'title'    => esc_html__('My Account Download','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_edit_account'] = [
            'title'    => esc_html__('My Account edit account','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_address'] = [
            'title'    => esc_html__('My Account address','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_login_form'] = [
            'title'    => esc_html__('My Account login form','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_register_form'] = [
            'title'    => esc_html__('My Account register form','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_logout'] = [
            'title'    => esc_html__('My Account logout','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['myaccount']['wl_myaccount_order'] = [
            'title'    => esc_html__('My Account order','woolentor-pro'),
            'is_pro'   => true,
        ];

        /* Loast Password */
        $widget_list['lostpassword']['wl_myaccount_lostpassword'] = [
            'title'    => esc_html__('Lost Password Form','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['lostpassword']['wl_myaccount_resetpassword'] = [
            'title'    => esc_html__('Reset Password Form','woolentor-pro'),
            'is_pro'   => true,
        ];

        /* Thunkyou page */
        $widget_list['thankyou']['wl_thankyou_order'] = [
            'title'    => esc_html__('Thankyou order','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['thankyou']['wl_thankyou_customer_address_details'] = [
            'title'    => esc_html__('Thankyou customer addedd details','woolentor-pro'),
            'is_pro'   => true,
        ];
        $widget_list['thankyou']['wl_thankyou_order_details'] = [
            'title'    => esc_html__('Thankyou order details','woolentor-pro'),
            'is_pro'   => true,
        ];

        return $widget_list;

    }


}

Widgets_Control::instance();