<?php
namespace WooLentorPro;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Blocks Control
*/
class Blocks_Control{
    
    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        add_filter( 'woolentor_block_list', [ $this, 'block_list' ] );
    }

    /**
     * Block List manager
     *
     * @param [array] $block_list
     * @return array
     */
    public function block_list( $block_list ){

        $pro_block = [
            'product_grid' => [
                'label'  => __('Product Grid','woolentor'),
                'name'   => 'woolentor/product-grid',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'is_pro' => true,
                'style' => 'woolentor-product-grid',
                'script' => 'woolentor-widgets-scripts',
                'enqueue_assets' => function(){
                    wp_enqueue_style('dashicons');
                },
            ],
            'customer_review' => [
                'label'  => __('Customer Review','woolentor'),
                'name'   => 'woolentor/customer-review',
                'server_side_render' => true,
                'type'   => 'common',
                'is_pro' => true,
                'active' => true,
            ],

            'cart_table' => [
                'title'  => __('Cart Table','woolentor'),
                'name'   => 'woolentor/cart-table',
                'server_side_render' => true,
                'type'   => 'cart',
                'is_pro' => true,
                'active' => true
            ],
            'cart_total' => [
                'title'  => __('Cart Total','woolentor'),
                'name'   => 'woolentor/cart-total',
                'server_side_render' => true,
                'type'   => 'cart',
                'is_pro' => true,
                'active' => true
            ],
            'corss_sell' => [
                'title'  => __('Cross Sell','woolentor'),
                'name'   => 'woolentor/cross-sell',
                'server_side_render' => true,
                'type'   => 'cart',
                'is_pro' => true,
                'active' => true
            ],
            'return_to_shop' => [
                'title'  => __('Return To Shop','woolentor'),
                'name'   => 'woolentor/return-to-shop',
                'server_side_render' => true,
                'type'   => 'cart',
                'is_pro' => true,
                'active' => true
            ],
            'cart_empty_message' => [
                'title'  => __('Empty Cart Message','woolentor'),
                'name'   => 'woolentor/cart-empty-message',
                'server_side_render' => true,
                'type'   => 'cart',
                'is_pro' => true,
                'active' => true
            ],

            'checkout_billing_form' => [
                'title'  => __('Checkout Billing Form','woolentor'),
                'name'   => 'woolentor/checkout-billing-form',
                'server_side_render' => true,
                'type'   => 'checkout',
                'is_pro' => true,
                'active' => true
            ],
            'checkout_shipping_form' => [
                'title'  => __('Checkout Shipping Form','woolentor'),
                'name'   => 'woolentor/checkout-shipping-form',
                'server_side_render' => true,
                'type'   => 'checkout',
                'is_pro' => true,
                'active' => true
            ],
            'checkout_additional_form' => [
                'title'  => __('Checkout Additional Form','woolentor'),
                'name'   => 'woolentor/checkout-additional-form',
                'server_side_render' => true,
                'type'   => 'checkout',
                'is_pro' => true,
                'active' => true
            ],
            'checkout_coupon_form' => [
                'title'  => __('Checkout Coupon Form','woolentor'),
                'name'   => 'woolentor/checkout-coupon-form',
                'server_side_render' => true,
                'type'   => 'checkout',
                'is_pro' => true,
                'active' => true
            ],
            'checkout_payment' => [
                'title'  => __('Checkout Payment','woolentor'),
                'name'   => 'woolentor/checkout-payment',
                'server_side_render' => true,
                'type'   => 'checkout',
                'is_pro' => true,
                'active' => true
            ],
            'checkout_order_review' => [
                'title'  => __('Checkout Order Review','woolentor'),
                'name'   => 'woolentor/checkout-order-review',
                'server_side_render' => true,
                'type'   => 'checkout',
                'is_pro' => true,
                'active' => true
            ],
            'checkout_login_form' => [
                'title'  => __('Checkout Login Form','woolentor'),
                'name'   => 'woolentor/checkout-login-form',
                'server_side_render' => true,
                'type'   => 'checkout',
                'is_pro' => true,
                'active' => true
            ],

            'my_account' => [
                'title'  => __('My Account','woolentor'),
                'name'   => 'woolentor/my-account',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_navigation' => [
                'title'  => __('My Account Navigation','woolentor'),
                'name'   => 'woolentor/my-account-navigation',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_dashboard' => [
                'title'  => __('My Account Dashboard','woolentor'),
                'name'   => 'woolentor/my-account-dashboard',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_download' => [
                'title'  => __('My Account Download','woolentor'),
                'name'   => 'woolentor/my-account-download',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_edit' => [
                'title'  => __('My Account Edit','woolentor'),
                'name'   => 'woolentor/my-account-edit',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_address' => [
                'title'  => __('My Account Address','woolentor'),
                'name'   => 'woolentor/my-account-address',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_order' => [
                'title'  => __('My Account Order','woolentor'),
                'name'   => 'woolentor/my-account-order',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_logout' => [
                'title'  => __('My Account Logout','woolentor'),
                'name'   => 'woolentor/my-account-logout',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_login_form' => [
                'title'  => __('My Account Login Form','woolentor'),
                'name'   => 'woolentor/my-account-login-form',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_register_form' => [
                'title'  => __('My Account Register Form','woolentor'),
                'name'   => 'woolentor/my-account-register-form',
                'server_side_render' => true,
                'type'   => 'myaccount',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_lost_password' => [
                'title'  => __('My Account Lost Password','woolentor'),
                'name'   => 'woolentor/my-account-lost-password',
                'server_side_render' => true,
                'type'   => 'lostpassword',
                'is_pro' => true,
                'active' => true
            ],
            'my_account_reset_password' => [
                'title'  => __('My Account Rest Password Form','woolentor'),
                'name'   => 'woolentor/my-account-reset-password',
                'server_side_render' => true,
                'type'   => 'lostpassword',
                'is_pro' => true,
                'active' => true
            ],
            
            'thankyou_order' => [
                'title'  => __('Thank you order','woolentor'),
                'name'   => 'woolentor/thankyou-order',
                'server_side_render' => true,
                'type'   => 'thankyou',
                'is_pro' => true,
                'active' => true
            ],
            'thankyou_order_details' => [
                'title'  => __('Thank you Order Details','woolentor'),
                'name'   => 'woolentor/thankyou-order-details',
                'server_side_render' => true,
                'type'   => 'thankyou',
                'is_pro' => true,
                'active' => true
            ],
            'thankyou_address_details' => [
                'title'  => __('Thank you Address Details','woolentor'),
                'name'   => 'woolentor/thankyou-address-details',
                'server_side_render' => true,
                'type'   => 'thankyou',
                'is_pro' => true,
                'active' => true
            ]

        ];

        $block_list = array_merge( $block_list, $pro_block );

        return $block_list;
    }


}

Blocks_Control::instance();