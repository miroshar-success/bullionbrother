<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Metaboxes{
    protected static $_instance = null;
    
    /**
     * Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->general_meta_box();
        $this->rules_meta_box();
    }

    /**
     * General meta box.
     */
    public function general_meta_box() {
        $args = array(
            'id'       => '_woolentor_order_bump',
            'title'    => esc_html__( 'Order Bump Options', 'woolentor-pro' ),
            'screen'   => 'woolentor-template',
            'context'  => 'advanced',
            'priority' => 'default',
            'fields'   => array(
                // style
                array(
                    'id'       => 'style',
                    'title'    => __( 'Style', 'woolentor-pro' ),
                    'type'     => 'select',
                    'options'  => array(
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                        '3' => __( 'Style 3', 'woolentor-pro' ),
                        '4' => __( 'Style 4', 'woolentor-pro' ),
                        '5' => __( 'Style 5', 'woolentor-pro' ),
                        '6' => __( 'Style 6', 'woolentor-pro' ),
                    )
                ),

                // product
                array(
                    'id'          => 'product',
                    'title'       => __( 'Select Product', 'woolentor-pro' ),
                    'desc'        => __( 'Choose the product you want to offer as an order bump.', 'woolentor-pro' ),
                    'type'        => 'select',
                    'placeholder' => __( 'Choose Products', 'woolentor-pro' ),
                    'ajax'        => true,
                    'multiple'    => false,
                    'query_args'  => array(
                        'post_type' => 'product',
                    ),
                ),

                // product_title
                array(
                    'id'          => 'product_title',
                    'title'       => __( 'Product Title', 'woolentor-pro' ),
                    'desc'        => __( 'Here you can change the default product title.', 'woolentor-pro' ),
                    'type'        => 'text',
                ),

                // product_desc
                array(
                    'id'          => 'product_desc',
                    'title'       => __( 'Product Description', 'woolentor-pro' ),
                    'desc'        => __( 'Here you can change the default product description.', 'woolentor-pro' ),
                    'type'        => 'textarea',
                ),
                
                // qty
                array(
                    'id'          => 'qty',
                    'title'       => __( 'Quantity', 'woolentor-pro' ),
                    'desc'        => __( 'Specify how much product will be added to the order.', 'woolentor-pro' ),
                    'placeholder' => __( '1', 'woolentor-pro' ),
                    'type'        => 'number',
                    'attributes'  => array(
                        'min'  => 1,
                        'step' => 1,
                    ),
                ),

                // discount_base_price
                array(
                    'id'       => 'discount_base_price',
                    'title'    => __( 'Discount Base Price', 'woolentor-pro' ),
                    'type'     => 'select',
                    'options'  => array(
                        '' => __( 'Use Global Option', 'woolentor-pro' ),
                        'regular_price' => __( 'Regular Price', 'woolentor-pro' ),
                        'sale_price'   => __( 'Sale Price', 'woolentor-pro' ),
                    ),
                    'desc' => __( 'To use module settings, set "Use Global Option".', 'woolentor-pro' ),
                ),

                // discount_type
                array(
                    'id'       => 'discount_type',
                    'title'    => __( 'Discount Type', 'woolentor-pro' ),
                    'desc'     => __( 'Use "None" if you do not want to apply any discount.', 'woolentor-pro' ),
                    'type'     => 'select',
                    'options'  => array(
                        'percent_amount' => __( 'Percentage Amount', 'woolentor-pro' ),
                        'fixed_amount'   => __( 'Fixed Amount', 'woolentor-pro' ),
                        'fixed_price'    => __( 'Set Fixed Price', 'woolentor-pro' ),
                        ''               => __( 'None', 'woolentor-pro' ),
                    ),
                    'default'   => 'percent_amount'
                ),

                // discount_amount
                array(
                    'id'       => 'discount_amount',
                    'title'    => __( 'Amount', 'woolentor-pro' ),
                    'type'     => 'number',
                    'desc'     => __( 'Enter the amount you want to discount or Set as new price.', 'woolentor-pro' ),
                    'default'  => '50',
                    'attributes'  => array(
                        'min'  => 0,
                        'step' => 1,
                    ),
                ),

                // position
                array(
                    'id'       => 'position',
                    'title'    => __( 'Position', 'woolentor-pro' ),
                    'type'     => 'select',
                    'options'  => Helper::get_postion_hooks()
                ),

                // label_grab_this_offer
                array(
                    'id'          => 'label_grab_this_offer',
                    'title'       => __( 'Label - Grab this offer!', 'woolentor-pro' ),
                    'desc'        => __( 'Here you can change checkbox label.', 'woolentor-pro' ),
                    'type'        => 'text',
                ),
            ),
        );

        \WLOPTF\Meta_Boxes::instance( $args ); // @todo change by global class
    }

    /**
     * Rules meta box.
     */
    public function rules_meta_box() {
        $args = array(
            'id'       => '_woolentor_order_bump_rules',
            'title'    => esc_html__( 'Rules - To Trigger Order Bump', 'woolentor-pro' ),
            'screen'   => 'woolentor-template',
            'context'  => 'advanced',
            'priority' => 'default',
            'fields'   => array(

                // ignore_rules
                array(
                    'id'       => 'ignore_rules',
                    'title'    => __( 'Ignore Rules', 'woolentor-pro' ),
                    'desc'     => __( 'You can display Order Bump without setting any rules.', 'woolentor-pro' ),
                    'type'     => 'select',
                    'options'  => array(
                        '0'   => __( 'No', 'woolentor-pro' ),
                        '1'   => __( 'Yes', 'woolentor-pro' ),
                    )
                ),

                // rules
                array(
                    'id'       => 'rules',
                    'title'    => __( 'Rules', 'woolentor-pro' ),
                    'desc'     => __( 'Set the rules for triggering the order bump.', 'woolentor-pro' ),
                    'type'     => 'rules',
                    'settings' => array(
                        'default'   => $this->order_rules_opt(),
                    ),
                    'control'  => array(
                        'name'  => '_woolentor_order_bump_rules_trigger',
                        'event' => 'change',
                        'value' => 'default',
                    ),
                    'button'  => __( 'Add Rule', 'woolentor-pro' ),
                ),

            ),
        );

        \WLOPTF\Meta_Boxes::instance( $args ); // @todo change by global class
    }

    /**
     * Order rules options.
     */
    protected function order_rules_opt() {
        $opt = array(
            // customer
            'customer' => array(
                'title'    => esc_html__( 'Customer', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'any'  => esc_html__( 'Matches any of', 'woolentor-pro' ),
                        'none' => esc_html__( 'Matches none of', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'        => 'select',
                    'placeholder' => esc_html__( 'Choose User Role', 'woolentor-pro' ),
                    'options'     => woolentor_get_users(),
                    'multiple'    => true,
                ),
            ),

            // customer_user_role
            'customer_user_role' => array(
                'title'    => esc_html__( 'Customer - User Role', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'any'  => esc_html__( 'Matches any of', 'woolentor-pro' ),
                        'none' => esc_html__( 'Matches none of', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'        => 'select',
                    'placeholder' => esc_html__( 'Choose User Role', 'woolentor-pro' ),
                    'options'     => woolentor_get_user_roles(),
                    'multiple'    => true,
                ),
            ),

            // customer_login_status
            'customer_login_status' => array(
                'title'    => esc_html__( 'Customer - Login Status', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'equal'     => esc_html__( 'Is', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'    => 'select',
                    'options' => array(
                        'logged_in'     => esc_html__( 'Logged in', 'woolentor-pro' ),
                        'not_logged_in' => esc_html__( 'Not Logged in', 'woolentor-pro' ),
                    ),
                ),
            ),

            // checkout_shipping_address_country
            'checkout_shipping_address_country' => $this->customer_shipping_country_opt(),

            // checkout_billing_address_country
            'checkout_billing_address_country' => $this->customer_billing_country_opt(),

            // cart_items_categories
            'cart_items_categories' => array(
                'title'    => esc_html__( 'Cart Items - Categories', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'any'  => esc_html__( 'Matches any of', 'woolentor-pro' ),
                        'all'  => esc_html__( 'Matches all of', 'woolentor-pro' ),
                        'none' => esc_html__( 'Matches none of', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'        => 'select',
                    'placeholder' => esc_html__( 'Choose Categories', 'woolentor-pro' ),
                    'ajax'        => false,
                    'multiple'    => true,
                    'query_type'  => 'taxonomy_term',
                    'query_args'  => array(
                        'taxonomy'     => 'product_cat',
                    ),
                ),
            ),

            // cart_items_products
            'cart_items_products' => array(
                'title'    => esc_html__( 'Cart Items - Products', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'any'  => esc_html__( 'Matches any of', 'woolentor-pro' ),
                        'all'  => esc_html__( 'Matches all of', 'woolentor-pro' ),
                        'none' => esc_html__( 'Matches none of', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'        => 'select',
                    'placeholder' => esc_html__( 'Choose Products', 'woolentor-pro' ),
                    'ajax'        => true,
                    'multiple'    => true,
                    'query_args'  => array(
                        'post_type' => 'product',
                        'meta_query' => array(
                            array(
                                'key'     => '_stock_status',
                                'value'   => 'outofstock',
                                'compare' => '!=',
                            ),
                        ),
                    ),
                ),

                
            ),
            
            // cart_applied_coupons
            'cart_applied_coupons' => array(
                'title'    => esc_html__( 'Cart - Applied Coupons', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'any'  => esc_html__( 'Matches any of', 'woolentor-pro' ),
                        'all'  => esc_html__( 'Matches all of', 'woolentor-pro' ),
                        'none' => esc_html__( 'Matches none of', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type'        => 'select',
                    'placeholder' => esc_html__( 'Choose Coupons', 'woolentor-pro' ),
                    'ajax'        => false,
                    'multiple'    => true,
                    'query_type'  => 'post',
                    'query_args'  => array(
                        'post_type' => 'shop_coupon',
                    ),
                ),
            ),

            // cart_total
            'cart_total' => array(
                'title'    => esc_html__( 'Cart - Totals', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'at_least'       => esc_html__( 'At least', 'woolentor-pro' ),
                        'more_than'      => esc_html__( 'More than', 'woolentor-pro' ),
                        'not_mroe_than'  => esc_html__( 'Not more than', 'woolentor-pro' ),
                        'less_than'      => esc_html__( 'Less than', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type' => 'number',
                    'attributes' => array(
                        'step' => 'any',
                    ),
                )
            ),

            // cart_subtotal
            'cart_subtotal' => array(
                'title'    => esc_html__( 'Cart - Subtotals', 'woolentor-pro' ),
                'operator' => array(
                    'type'    => 'select',
                    'options' => array(
                        'at_least'       => esc_html__( 'At least', 'woolentor-pro' ),
                        'more_than'      => esc_html__( 'More than', 'woolentor-pro' ),
                        'not_mroe_than'  => esc_html__( 'Not more than', 'woolentor-pro' ),
                        'less_than'      => esc_html__( 'Less than', 'woolentor-pro' ),
                    ),
                ),
                'value'    => array(
                    'type' => 'number',
                )
            ),
        );

        return $opt;
    }

    /**
     * Customer shipping country options.
     */
    protected function customer_shipping_country_opt() {
        $opt = array(
            'title'    => esc_html__( 'Customer - Shipping Country', 'woolentor-pro' ),
            'operator' => array(
                'type'    => 'select',
                'options' => array(
                    'any'  => esc_html__( 'Matches any of', 'woolentor-pro' ),
                    'none' => esc_html__( 'Matches none of', 'woolentor-pro' ),
                ),
            ),
            'value' => array(
                'type'        => 'select',
                'placeholder' => esc_html__( 'Choose Countries', 'woolentor-pro' ),
                'multiple'    => true,
                'options'     => woolentor_get_countries(),
            ),
        );

        return $opt;
    }

    /**
     * Customer billing country options.
     */
    protected function customer_billing_country_opt() {
        $opt = array(
            'title'    => esc_html__( 'Customer - Billing Country', 'woolentor-pro' ),
            'operator' => array(
                'type'    => 'select',
                'options' => array(
                    'any'  => esc_html__( 'Matches any of', 'woolentor-pro' ),
                    'none' => esc_html__( 'Matches none of', 'woolentor-pro' ),
                ),
            ),
            'value' => array(
                'type'        => 'select',
                'placeholder' => esc_html__( 'Choose Countries', 'woolentor-pro' ),
                'multiple'    => true,
                'options'     => woolentor_get_countries(),
            ),
        );

        return $opt;
    }
}