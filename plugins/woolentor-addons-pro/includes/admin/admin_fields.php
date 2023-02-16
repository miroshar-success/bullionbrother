<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Admin_Fields_Pro {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Woolentor_Admin_Fields]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_filter('woolentor_admin_fields',[ $this, 'admin_fields' ], 10, 1 );

        // Element tabs admin fields
        add_filter('woolentor_elements_tabs_admin_fields',[ $this, 'elements_tabs_admin_fields' ], 10, 1 );
        add_filter('woolentor_elements_tabs_admin_fields',[ $this, 'elements_tabs_additional_widget_admin_fields' ], 100, 1 );

        // Template Builder
        add_filter('woolentor_template_menu_tabs',[ $this, 'template_menu_navs' ], 10, 1 );
        add_filter('woolentor_template_types',[ $this, 'template_type' ], 10, 1 );
        
    }

     /**
     * [admin_fields] Admin Fields
     * @return [array]
     */
    public function admin_fields( $fields ){

        $fields['woolentor_woo_template_tabs'] = array(

            array(
                'name'  => 'enablecustomlayout',
                'label' => esc_html__( 'Enable / Disable Template Builder', 'woolentor-pro' ),
                'desc'  => esc_html__( 'You can enable/disable template builder from here.', 'woolentor-pro' ),
                'type'  => 'checkbox',
                'default' => 'on'
            ),

            array(
                'name'  => 'shoppageproductlimit',
                'label' => esc_html__( 'Product Limit', 'woolentor-pro' ),
                'desc'  => esc_html__( 'You can handle the product limit for the Shop page limit', 'woolentor-pro' ),
                'min'               => 1,
                'max'               => 100,
                'step'              => '1',
                'type'              => 'number',
                'default'           => '2',
                'sanitize_callback' => 'floatval',
                'condition'         => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'singleproductpage',
                'label'   => esc_html__( 'Single Product Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can select a custom template for the product details page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('single') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productarchivepage',
                'label'   => esc_html__( 'Product Shop Page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can select a custom template for the Shop page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('shop','archive') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productallarchivepage',
                'label'   => esc_html__( 'Product Archive Page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can select a custom template for the Product Archive page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('shop','archive') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productcartpage',
                'label'   => esc_html__( 'Cart Page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can select a template for the Cart page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('cart') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productemptycartpage',
                'label'   => esc_html__( 'Empty Cart Page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can select Custom empty cart page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('emptycart') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productcheckoutpage',
                'label'   => esc_html__( 'Checkout Page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can select a template for the Checkout page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('checkout') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productcheckouttoppage',
                'label'   => esc_html__( 'Checkout Page Top Content', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can checkout top content(E.g: Coupon form, login form etc)', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('checkouttop') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productthankyoupage',
                'label'   => esc_html__( 'Thank You Page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'Select a template for the Thank you page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('thankyou') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productmyaccountpage',
                'label'   => esc_html__( 'My Account Page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'Select a template for the My Account page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('myaccount') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productmyaccountloginpage',
                'label'   => esc_html__( 'My Account Login page Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'Select a template for the Login page layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('myaccountlogin') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'productquickview',
                'label'   => esc_html__( 'Product Quick View Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'Select a template for the product\'s quick view layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('quickview') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

            array(
                'name'    => 'mini_cart_layout',
                'label'   => esc_html__( 'Mini Cart Template', 'woolentor-pro' ),
                'desc'    => esc_html__( 'Select a template for the mini cart layout', 'woolentor-pro' ),
                'type'    => 'selectgroup',
                'default' => '0',
                'options' => [
                    'group'=>[
                        'woolentor' => [
                            'label' => __( 'WooLentor', 'woolentor' ),
                            'options' => function_exists('woolentor_wltemplate_list') ? woolentor_wltemplate_list( array('minicart') ) : null
                        ],
                        'elementor' => [
                            'label' => __( 'Elementor', 'woolentor' ),
                            'options' => woolentor_elementor_template()
                        ]
                    ]
                ],
                'condition' => array( 'enablecustomlayout', '==', 'true' )
            ),

        );

        $fields['woolentor_gutenberg_tabs'] = array(
            
            'settings' => array(

                array(
                    'name'    => 'css_add_via',
                    'label'   => esc_html__( 'Add CSS through', 'woolentor' ),
                    'desc'    => esc_html__( 'Choose how you want to add the newly generated CSS.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'internal',
                    'options' => array(
                        'internal' => esc_html__('Internal','woolentor'),
                        'external' => esc_html__('External','woolentor'),
                    )
                ),

                array(
                    'name'  => 'container_width',
                    'label' => esc_html__( 'Container Width', 'woolentor' ),
                    'desc'  => esc_html__( 'You can set the container width from here.', 'woolentor' ),
                    'min'               => 1,
                    'max'               => 10000,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '1140',
                    'sanitize_callback' => 'floatval'
                ),

            ),

            'blocks' => array(

                array(
                    'name'      => 'general_blocks_heading',
                    'headding'  => esc_html__( 'General', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'    => 'product_tab',
                    'label'   => esc_html__( 'Product Tab', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_grid',
                    'label'   => esc_html__( 'Product Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'customer_review',
                    'label'   => esc_html__( 'Customer Review', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'promo_banner',
                    'label'   => esc_html__( 'Promo Banner', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'special_day_offer',
                    'label'   => esc_html__( 'Special Day Offer', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'image_marker',
                    'label'   => esc_html__( 'Image Marker', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'store_feature',
                    'label'   => esc_html__( 'Store Feature', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'brand_logo',
                    'label'   => esc_html__( 'Brand Logo', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'category_grid',
                    'label'   => esc_html__( 'Category Grid', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'faq',
                    'label'   => esc_html__( 'FAQ', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_curvy',
                    'label'   => esc_html__( 'Product Curvy', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'archive_title',
                    'label'   => esc_html__( 'Archive Title', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'breadcrumbs',
                    'label'   => esc_html__( 'Breadcrumbs', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'      => 'shop_blocks_heading',
                    'headding'  => esc_html__( 'Shop / Archive', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'    => 'shop_archive_product',
                    'label'   => esc_html__( 'Product Archive (Default)', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'      => 'single_blocks_heading',
                    'headding'  => esc_html__( 'Single Product', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'   => 'product_title',
                    'label'  => esc_html__('Product Title','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_price',
                    'label'   => esc_html__('Product Price','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'   => 'product_addtocart',
                    'label'  => esc_html__('Product Add To Cart','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_short_description',
                    'label'   => esc_html__('Product Short Description','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_description',
                    'label'   => esc_html__('Product Description','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_rating',
                    'label'   => esc_html__('Product Rating','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_image',
                    'label'   => esc_html__('Product Image','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_meta',
                    'label'   => esc_html__('Product Meta','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_additional_info',
                    'label'   => esc_html__('Product Additional Info','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_tabs',
                    'label'   => esc_html__('Product Tabs','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_stock',
                    'label'   => esc_html__('Product Stock','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_qrcode',
                    'label'   => esc_html__('Product QR Code','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_related',
                    'label'   => esc_html__('Product Related','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_upsell',
                    'label'   => esc_html__('Product Upsell','woolentor'),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'      => 'cart_blocks_heading',
                    'headding'  => esc_html__( 'Cart', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'name'  => 'cart_table',
                    'label' => esc_html__( 'Product Cart Table', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'cart_total',
                    'label' => esc_html__( 'Product Cart Total', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'corss_sell',
                    'label' => esc_html__( 'Product Cross Sell', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'return_to_shop',
                    'label' => esc_html__( 'Return To Shop Button', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'cart_empty_message',
                    'label' => esc_html__( 'Empty Cart Message', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'      => 'checkout_blocks_heading',
                    'headding'  => esc_html__( 'Checkout', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'name'  => 'checkout_billing_form',
                    'label' => esc_html__( 'Checkout Billing Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'checkout_shipping_form',
                    'label' => esc_html__( 'Checkout Shipping Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'checkout_additional_form',
                    'label' => esc_html__( 'Checkout Additional Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'checkout_coupon_form',
                    'label' => esc_html__( 'Checkout Coupon Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'checkout_payment',
                    'label' => esc_html__( 'Checkout Payment Method', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'checkout_order_review',
                    'label' => esc_html__( 'Checkout Order Review', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'checkout_login_form',
                    'label' => esc_html__( 'Checkout Login Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'      => 'myaccount_blocks_heading',
                    'headding'  => esc_html__( 'My Account', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'name'  => 'my_account',
                    'label' => esc_html__( 'My Account', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'my_account_navigation',
                    'label' => esc_html__( 'My Account Navigation', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'my_account_dashboard',
                    'label' => esc_html__( 'My Account Dashboard', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'my_account_download',
                    'label' => esc_html__( 'My Account Download', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
                array(
                    'name'  => 'my_account_edit',
                    'label' => esc_html__( 'My Account Edit', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'my_account_address',
                    'label' => esc_html__( 'My Account Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'my_account_order',
                    'label' => esc_html__( 'My Account Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'my_account_logout',
                    'label' => esc_html__( 'My Account Logout', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'my_account_login_form',
                    'label' => esc_html__( 'Login Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'my_account_registration_form',
                    'label' => esc_html__( 'Registration Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'my_account_lost_password',
                    'label' => esc_html__( 'Lost Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'my_account_reset_password',
                    'label' => esc_html__( 'Reset Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),

                array(
                    'name'      => 'thankyou_blocks_heading',
                    'headding'  => esc_html__( 'Thank You', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),
                array(
                    'name'  => 'thankyou_order',
                    'label' => esc_html__( 'Thank You Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'thankyou_address_details',
                    'label' => esc_html__( 'Thank You Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),
                array(
                    'name'  => 'thankyou_order_details',
                    'label' => esc_html__( 'Thank You Order Details', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on',
                ),

            )

        );

        $fields['woolentor_elements_tabs'] = apply_filters( 'woolentor_elements_tabs_admin_fields', array() );

        $fields['woolentor_others_tabs'] = array(

            'modules'=> array(

                array(
                    'name'     => 'rename_label_settings',
                    'label'    => esc_html__( 'Rename Label', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_rename_label_tabs',
                    'option_id'=> 'enablerenamelabel',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/change-woocommerce-text/'),
                    'setting_fields' => array(
                        
                        array(
                            'name'  => 'enablerenamelabel',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable rename label from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class'   =>'enablerenamelabel woolentor-action-field-left',
                        ),
        
                        array(
                            'name'      => 'shop_page_heading',
                            'headding'  => esc_html__( 'Shop Page', 'woolentor-pro' ),
                            'type'      => 'title',
                            'class'     => 'depend_enable_rename_label',
                        ),
                        
                        array(
                            'name'        => 'wl_shop_add_to_cart_txt',
                            'label'       => esc_html__( 'Add to Cart Button Text', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Change the Add to Cart button text for the Shop page.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Add to Cart', 'woolentor-pro' ),
                            'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                        ),
        
                        array(
                            'name'      => 'product_details_page_heading',
                            'headding'  => esc_html__( 'Product Details Page', 'woolentor-pro' ),
                            'type'      => 'title',
                            'class'     => 'depend_enable_rename_label',
                        ),
        
                        array(
                            'name'        => 'wl_add_to_cart_txt',
                            'label'       => esc_html__( 'Add to Cart Button Text', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Change the Add to Cart button text for the Product details page.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Add to Cart', 'woolentor-pro' ),
                            'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                        ),
        
                        array(
                            'name'        => 'wl_description_tab_menu_title',
                            'label'       => esc_html__( 'Description', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Change the tab title for the product description.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Description', 'woolentor-pro' ),
                            'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                        ),
                        
                        array(
                            'name'        => 'wl_additional_information_tab_menu_title',
                            'label'       => esc_html__( 'Additional Information', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Change the tab title for the product additional information', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Additional information', 'woolentor-pro' ),
                            'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                        ),
                        
                        array(
                            'name'        => 'wl_reviews_tab_menu_title',
                            'label'       => esc_html__( 'Reviews', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Change the tab title for the product review', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => __( 'Reviews', 'woolentor-pro' ),
                            'class'       =>'depend_enable_rename_label woolentor-action-field-left',
                        ),
        
                        array(
                            'name'      => 'checkout_page_heading',
                            'headding'  => esc_html__( 'Checkout Page', 'woolentor-pro' ),
                            'type'      => 'title',
                            'class'     => 'depend_enable_rename_label',
                        ),
        
                        array(
                            'name'        => 'wl_checkout_placeorder_btn_txt',
                            'label'       => esc_html__( 'Place order', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Change the label for the Place order field.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Place order', 'woolentor-pro' ),
                            'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                        ),

                    )
                ),

                array(
                    'name'     => 'sales_notification_settings',
                    'label'    => esc_html__( 'Sales Notification', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_sales_notification_tabs',
                    'option_id'=> 'enableresalenotification',
                    'require_settings'=> true,
                    'documentation' => esc_url('https://woolentor.com/doc/sales-notification-for-woocommerce/'),
                    'setting_fields' => array(

                        array(
                            'name'  => 'enableresalenotification',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable sales notification from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),
                        
                        array(
                            'name'    => 'notification_content_type',
                            'label'   => esc_html__( 'Notification Content Type', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Select Content Type', 'woolentor-pro' ),
                            'type'    => 'radio',
                            'default' => 'actual',
                            'options' => array(
                                'actual' => esc_html__('Real','woolentor-pro'),
                                'fakes'  => esc_html__('Manual','woolentor-pro'),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'    => 'noification_fake_data',
                            'label'   => esc_html__( 'Choose Template', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Choose template for manual notification.', 'woolentor-pro' ),
                            'type'    => 'multiselect',
                            'default' => '',
                            'options' => woolentor_elementor_template(),
                            'condition' => array( 'notification_content_type', '==', 'fakes' ),
                        ),
        
                        array(
                            'name'    => 'notification_pos',
                            'label'   => esc_html__( 'Position', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Set the position of the Sales Notification Position on frontend.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => 'bottomleft',
                            'options' => array(
                                'topleft'       => esc_html__( 'Top Left','woolentor-pro' ),
                                'topright'      => esc_html__( 'Top Right','woolentor-pro' ),
                                'bottomleft'    => esc_html__( 'Bottom Left','woolentor-pro' ),
                                'bottomright'   => esc_html__( 'Bottom Right','woolentor-pro' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'    => 'notification_layout',
                            'label'   => esc_html__( 'Image Position', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Set the image position of the notification.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => 'imageleft',
                            'options' => array(
                                'imageleft'   => esc_html__( 'Image Left','woolentor-pro' ),
                                'imageright'  => esc_html__( 'Image Right','woolentor-pro' ),
                            ),
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class'   => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'    => 'notification_timing_area_title',
                            'headding'=> esc_html__( 'Notification Timing', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),
        
                        array(
                            'name'    => 'notification_loadduration',
                            'label'   => esc_html__( 'First loading time', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'When to start notification load duration.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => '3',
                            'options' => array(
                                '2'    => esc_html__( '2 seconds','woolentor-pro' ),
                                '3'    => esc_html__( '3 seconds','woolentor-pro' ),
                                '4'    => esc_html__( '4 seconds','woolentor-pro' ),
                                '5'    => esc_html__( '5 seconds','woolentor-pro' ),
                                '6'    => esc_html__( '6 seconds','woolentor-pro' ),
                                '7'    => esc_html__( '7 seconds','woolentor-pro' ),
                                '8'    => esc_html__( '8 seconds','woolentor-pro' ),
                                '9'    => esc_html__( '9 seconds','woolentor-pro' ),
                                '10'   => esc_html__( '10 seconds','woolentor-pro' ),
                                '20'   => esc_html__( '20 seconds','woolentor-pro' ),
                                '30'   => esc_html__( '30 seconds','woolentor-pro' ),
                                '40'   => esc_html__( '40 seconds','woolentor-pro' ),
                                '50'   => esc_html__( '50 seconds','woolentor-pro' ),
                                '60'   => esc_html__( '1 minute','woolentor-pro' ),
                                '90'   => esc_html__( '1.5 minutes','woolentor-pro' ),
                                '120'  => esc_html__( '2 minutes','woolentor-pro' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'    => 'notification_time_showing',
                            'label'   => esc_html__( 'Notification showing time', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'How long to keep the notification.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => '4',
                            'options' => array(
                                '2'   => esc_html__( '2 seconds','woolentor-pro' ),
                                '4'   => esc_html__( '4 seconds','woolentor-pro' ),
                                '5'   => esc_html__( '5 seconds','woolentor-pro' ),
                                '6'   => esc_html__( '6 seconds','woolentor-pro' ),
                                '7'   => esc_html__( '7 seconds','woolentor-pro' ),
                                '8'   => esc_html__( '8 seconds','woolentor-pro' ),
                                '9'   => esc_html__( '9 seconds','woolentor-pro' ),
                                '10'  => esc_html__( '10 seconds','woolentor-pro' ),
                                '20'  => esc_html__( '20 seconds','woolentor-pro' ),
                                '30'  => esc_html__( '30 seconds','woolentor-pro' ),
                                '40'  => esc_html__( '40 seconds','woolentor-pro' ),
                                '50'  => esc_html__( '50 seconds','woolentor-pro' ),
                                '60'  => esc_html__( '1 minute','woolentor-pro' ),
                                '90'  => esc_html__( '1.5 minutes','woolentor-pro' ),
                                '120' => esc_html__( '2 minutes','woolentor-pro' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'    => 'notification_time_int',
                            'label'   => esc_html__( 'Time Interval', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Set the interval time between notifications.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => '4',
                            'options' => array(
                                '2'   => esc_html__( '2 seconds','woolentor-pro' ),
                                '4'   => esc_html__( '4 seconds','woolentor-pro' ),
                                '5'   => esc_html__( '5 seconds','woolentor-pro' ),
                                '6'   => esc_html__( '6 seconds','woolentor-pro' ),
                                '7'   => esc_html__( '7 seconds','woolentor-pro' ),
                                '8'   => esc_html__( '8 seconds','woolentor-pro' ),
                                '9'   => esc_html__( '9 seconds','woolentor-pro' ),
                                '10'  => esc_html__( '10 seconds','woolentor-pro' ),
                                '20'  => esc_html__( '20 seconds','woolentor-pro' ),
                                '30'  => esc_html__( '30 seconds','woolentor-pro' ),
                                '40'  => esc_html__( '40 seconds','woolentor-pro' ),
                                '50'  => esc_html__( '50 seconds','woolentor-pro' ),
                                '60'  => esc_html__( '1 minute','woolentor-pro' ),
                                '90'  => esc_html__( '1.5 minutes','woolentor-pro' ),
                                '120' => esc_html__( '2 minutes','woolentor-pro' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'    => 'notification_product_display_option_title',
                            'headding'=> esc_html__( 'Product Query Option', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class'   => 'element_section_title_area',
                        ),
        
                        array(
                            'name'              => 'notification_limit',
                            'label'             => esc_html__( 'Limit', 'woolentor-pro' ),
                            'desc'              => esc_html__( 'Set the number of notifications to display.', 'woolentor-pro' ),
                            'min'               => 1,
                            'max'               => 100,
                            'default'           => '5',
                            'step'              => '1',
                            'type'              => 'number',
                            'sanitize_callback' => 'number',
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'name'  => 'showallproduct',
                            'label' => esc_html__( 'Show/Display all products from each order', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Manage show all product from each order.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class'   => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'name'    => 'notification_uptodate',
                            'label'   => esc_html__( 'Order Upto', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Do not show purchases older than.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => '7',
                            'options' => array(
                                '1'   => esc_html__( '1 day','woolentor-pro' ),
                                '2'   => esc_html__( '2 days','woolentor-pro' ),
                                '3'   => esc_html__( '3 days','woolentor-pro' ),
                                '4'   => esc_html__( '4 days','woolentor-pro' ),
                                '5'   => esc_html__( '5 days','woolentor-pro' ),
                                '6'   => esc_html__( '6 days','woolentor-pro' ),
                                '7'   => esc_html__( '1 week','woolentor-pro' ),
                                '10'  => esc_html__( '10 days','woolentor-pro' ),
                                '14'  => esc_html__( '2 weeks','woolentor-pro' ),
                                '21'  => esc_html__( '3 weeks','woolentor-pro' ),
                                '28'  => esc_html__( '4 weeks','woolentor-pro' ),
                                '35'  => esc_html__( '5 weeks','woolentor-pro' ),
                                '42'  => esc_html__( '6 weeks','woolentor-pro' ),
                                '49'  => esc_html__( '7 weeks','woolentor-pro' ),
                                '56'  => esc_html__( '8 weeks','woolentor-pro' ),
                            ),
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'name'    => 'notification_animation_area_title',
                            'headding'=> esc_html__( 'Animation', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),
        
                        array(
                            'name'    => 'notification_inanimation',
                            'label'   => esc_html__( 'Animation In', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Choose entrance animation.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => 'fadeInLeft',
                            'options' => array(
                                'bounce'            => esc_html__( 'bounce','woolentor-pro' ),
                                'flash'             => esc_html__( 'flash','woolentor-pro' ),
                                'pulse'             => esc_html__( 'pulse','woolentor-pro' ),
                                'rubberBand'        => esc_html__( 'rubberBand','woolentor-pro' ),
                                'shake'             => esc_html__( 'shake','woolentor-pro' ),
                                'swing'             => esc_html__( 'swing','woolentor-pro' ),
                                'tada'              => esc_html__( 'tada','woolentor-pro' ),
                                'wobble'            => esc_html__( 'wobble','woolentor-pro' ),
                                'jello'             => esc_html__( 'jello','woolentor-pro' ),
                                'heartBeat'         => esc_html__( 'heartBeat','woolentor-pro' ),
                                'bounceIn'          => esc_html__( 'bounceIn','woolentor-pro' ),
                                'bounceInDown'      => esc_html__( 'bounceInDown','woolentor-pro' ),
                                'bounceInLeft'      => esc_html__( 'bounceInLeft','woolentor-pro' ),
                                'bounceInRight'     => esc_html__( 'bounceInRight','woolentor-pro' ),
                                'bounceInUp'        => esc_html__( 'bounceInUp','woolentor-pro' ),
                                'fadeIn'            => esc_html__( 'fadeIn','woolentor-pro' ),
                                'fadeInDown'        => esc_html__( 'fadeInDown','woolentor-pro' ),
                                'fadeInDownBig'     => esc_html__( 'fadeInDownBig','woolentor-pro' ),
                                'fadeInLeft'        => esc_html__( 'fadeInLeft','woolentor-pro' ),
                                'fadeInLeftBig'     => esc_html__( 'fadeInLeftBig','woolentor-pro' ),
                                'fadeInRight'       => esc_html__( 'fadeInRight','woolentor-pro' ),
                                'fadeInRightBig'    => esc_html__( 'fadeInRightBig','woolentor-pro' ),
                                'fadeInUp'          => esc_html__( 'fadeInUp','woolentor-pro' ),
                                'fadeInUpBig'       => esc_html__( 'fadeInUpBig','woolentor-pro' ),
                                'flip'              => esc_html__( 'flip','woolentor-pro' ),
                                'flipInX'           => esc_html__( 'flipInX','woolentor-pro' ),
                                'flipInY'           => esc_html__( 'flipInY','woolentor-pro' ),
                                'lightSpeedIn'      => esc_html__( 'lightSpeedIn','woolentor-pro' ),
                                'rotateIn'          => esc_html__( 'rotateIn','woolentor-pro' ),
                                'rotateInDownLeft'  => esc_html__( 'rotateInDownLeft','woolentor-pro' ),
                                'rotateInDownRight' => esc_html__( 'rotateInDownRight','woolentor-pro' ),
                                'rotateInUpLeft'    => esc_html__( 'rotateInUpLeft','woolentor-pro' ),
                                'rotateInUpRight'   => esc_html__( 'rotateInUpRight','woolentor-pro' ),
                                'slideInUp'         => esc_html__( 'slideInUp','woolentor-pro' ),
                                'slideInDown'       => esc_html__( 'slideInDown','woolentor-pro' ),
                                'slideInLeft'       => esc_html__( 'slideInLeft','woolentor-pro' ),
                                'slideInRight'      => esc_html__( 'slideInRight','woolentor-pro' ),
                                'zoomIn'            => esc_html__( 'zoomIn','woolentor-pro' ),
                                'zoomInDown'        => esc_html__( 'zoomInDown','woolentor-pro' ),
                                'zoomInLeft'        => esc_html__( 'zoomInLeft','woolentor-pro' ),
                                'zoomInRight'       => esc_html__( 'zoomInRight','woolentor-pro' ),
                                'zoomInUp'          => esc_html__( 'zoomInUp','woolentor-pro' ),
                                'hinge'             => esc_html__( 'hinge','woolentor-pro' ),
                                'jackInTheBox'      => esc_html__( 'jackInTheBox','woolentor-pro' ),
                                'rollIn'            => esc_html__( 'rollIn','woolentor-pro' ),
                                'rollOut'           => esc_html__( 'rollOut','woolentor-pro' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'    => 'notification_outanimation',
                            'label'   => esc_html__( 'Animation Out', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Choose exit animation.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => 'fadeOutRight',
                            'options' => array(
                                'bounce'             => esc_html__( 'bounce','woolentor-pro' ),
                                'flash'              => esc_html__( 'flash','woolentor-pro' ),
                                'pulse'              => esc_html__( 'pulse','woolentor-pro' ),
                                'rubberBand'         => esc_html__( 'rubberBand','woolentor-pro' ),
                                'shake'              => esc_html__( 'shake','woolentor-pro' ),
                                'swing'              => esc_html__( 'swing','woolentor-pro' ),
                                'tada'               => esc_html__( 'tada','woolentor-pro' ),
                                'wobble'             => esc_html__( 'wobble','woolentor-pro' ),
                                'jello'              => esc_html__( 'jello','woolentor-pro' ),
                                'heartBeat'          => esc_html__( 'heartBeat','woolentor-pro' ),
                                'bounceOut'          => esc_html__( 'bounceOut','woolentor-pro' ),
                                'bounceOutDown'      => esc_html__( 'bounceOutDown','woolentor-pro' ),
                                'bounceOutLeft'      => esc_html__( 'bounceOutLeft','woolentor-pro' ),
                                'bounceOutRight'     => esc_html__( 'bounceOutRight','woolentor-pro' ),
                                'bounceOutUp'        => esc_html__( 'bounceOutUp','woolentor-pro' ),
                                'fadeOut'            => esc_html__( 'fadeOut','woolentor-pro' ),
                                'fadeOutDown'        => esc_html__( 'fadeOutDown','woolentor-pro' ),
                                'fadeOutDownBig'     => esc_html__( 'fadeOutDownBig','woolentor-pro' ),
                                'fadeOutLeft'        => esc_html__( 'fadeOutLeft','woolentor-pro' ),
                                'fadeOutLeftBig'     => esc_html__( 'fadeOutLeftBig','woolentor-pro' ),
                                'fadeOutRight'       => esc_html__( 'fadeOutRight','woolentor-pro' ),
                                'fadeOutRightBig'    => esc_html__( 'fadeOutRightBig','woolentor-pro' ),
                                'fadeOutUp'          => esc_html__( 'fadeOutUp','woolentor-pro' ),
                                'fadeOutUpBig'       => esc_html__( 'fadeOutUpBig','woolentor-pro' ),
                                'flip'               => esc_html__( 'flip','woolentor-pro' ),
                                'flipOutX'           => esc_html__( 'flipOutX','woolentor-pro' ),
                                'flipOutY'           => esc_html__( 'flipOutY','woolentor-pro' ),
                                'lightSpeedOut'      => esc_html__( 'lightSpeedOut','woolentor-pro' ),
                                'rotateOut'          => esc_html__( 'rotateOut','woolentor-pro' ),
                                'rotateOutDownLeft'  => esc_html__( 'rotateOutDownLeft','woolentor-pro' ),
                                'rotateOutDownRight' => esc_html__( 'rotateOutDownRight','woolentor-pro' ),
                                'rotateOutUpLeft'    => esc_html__( 'rotateOutUpLeft','woolentor-pro' ),
                                'rotateOutUpRight'   => esc_html__( 'rotateOutUpRight','woolentor-pro' ),
                                'slideOutUp'         => esc_html__( 'slideOutUp','woolentor-pro' ),
                                'slideOutDown'       => esc_html__( 'slideOutDown','woolentor-pro' ),
                                'slideOutLeft'       => esc_html__( 'slideOutLeft','woolentor-pro' ),
                                'slideOutRight'      => esc_html__( 'slideOutRight','woolentor-pro' ),
                                'zoomOut'            => esc_html__( 'zoomOut','woolentor-pro' ),
                                'zoomOutDown'        => esc_html__( 'zoomOutDown','woolentor-pro' ),
                                'zoomOutLeft'        => esc_html__( 'zoomOutLeft','woolentor-pro' ),
                                'zoomOutRight'       => esc_html__( 'zoomOutRight','woolentor-pro' ),
                                'zoomOutUp'          => esc_html__( 'zoomOutUp','woolentor-pro' ),
                                'hinge'              => esc_html__( 'hinge','woolentor-pro' ),
                            ),
                            'class' => 'woolentor-action-field-left'
                        ),
                        
                        array(
                            'name'    => 'notification_style_area_title',
                            'headding'=> esc_html__( 'Style', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class' => 'element_section_title_area',
                        ),
        
                        array(
                            'name'        => 'notification_width',
                            'label'       => esc_html__( 'Width', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'You can handle the sales notification width.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( '550px', 'woolentor-pro' ),
                            'placeholder' => esc_html__( '550px', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'        => 'notification_mobile_width',
                            'label'       => esc_html__( 'Width for mobile', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'You can handle the sales notification width.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( '90%', 'woolentor-pro' ),
                            'placeholder' => esc_html__( '90%', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'  => 'background_color',
                            'label' => esc_html__( 'Background Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Set the background color of the sales notification.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class' => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'name'  => 'heading_color',
                            'label' => esc_html__( 'Heading Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Set the heading color of the sales notification.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class' => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'name'  => 'content_color',
                            'label' => esc_html__( 'Content Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Set the content color of the sales notification.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'condition' => array( 'notification_content_type', '==', 'actual' ),
                            'class' => 'woolentor-action-field-left',
                        ),
        
                        array(
                            'name'  => 'cross_color',
                            'label' => esc_html__( 'Cross Icon Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Set the cross icon color of the sales notification.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),

                    )
                ),

                array(
                    'name'     => 'shopify_checkout_settings',
                    'label'    => esc_html__( 'Shopify Style Checkout', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_shopify_checkout_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-make-woocommerce-checkout-like-shopify/'),
                    'setting_fields' => array(

                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable shopify style checkout page from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'logo',
                            'label'   => esc_html__( 'Logo', 'woolentor' ),
                            'desc'    => esc_html__( 'You can upload your logo for shopify style checkout page from here.', 'woolentor' ),
                            'type'    => 'image_upload',
                            'options' => [
                                'button_label'        => esc_html__( 'Upload', 'woolentor' ),   
                                'button_remove_label' => esc_html__( 'Remove', 'woolentor' ),   
                            ],
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'custommenu',
                            'label'   => esc_html__( 'Bottom Menu', 'woolentor' ),
                            'desc'    => esc_html__( 'You can choose menu for shopify style checkout page.', 'woolentor' ),
                            'type'    => 'select',
                            'default' => '0',
                            'options' => array( '0'=> esc_html__('Select Menu','woolentor') ) + woolentor_get_all_create_menus(),
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'show_phone',
                            'label'   => esc_html__( 'Show Phone Number Field', 'woolentor' ),
                            'desc'    => esc_html__( 'Show the Phone Number Field.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'show_company',
                            'label'   => esc_html__( 'Show Company Name Field', 'woolentor' ),
                            'desc'    => esc_html__( 'Show the Company Name Field.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'hide_cart_nivigation',
                            'label'   => esc_html__( 'Hide Cart Navigation', 'woolentor' ),
                            'desc'    => esc_html__( 'Hide the "Cart" menu and "Return to cart" button.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'hide_shipping_step',
                            'label'   => esc_html__( 'Hide Shipping Step', 'woolentor' ),
                            'desc'    => esc_html__( 'Turn it ON to hide the "Shipping" Step.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'customize_labels',
                            'label'       => esc_html__( 'Rename Labels?', 'woolentor' ),
                            'desc'        => esc_html__( 'Enable it to customize labels of the checkout page.', 'woolentor' ),
                            'type'        => 'checkbox',
                            'class'       => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'labels_list',
                            'label'       => esc_html__( 'Labels', 'woolentor' ),
                            'type'        => 'repeater',
                            'title_field' => 'select_tab',
                            'condition'   => array( 'customize_labels', '==', '1' ),
                            'fields'  => [

                                array(
                                    'name'    => 'select_tab',
                                    'label'   => esc_html__( 'Select Tab', 'woolentor' ),
                                    'desc'    => esc_html__( 'Select the tab for which you want to change the labels. ', 'woolentor' ),
                                    'type'    => 'select',
                                    'class'   => 'woolentor-action-field-left',
                                    'default' => 'informations_tab',
                                    'options' => array(
                                        'information'  => esc_html__('Information','woolentor'),
                                        'shipping'      => esc_html__('Shipping','woolentor'),
                                        'payment'       => esc_html__('Payment','woolentor'),
                                    ),
                                ),

                                array(
                                    'name'        => 'tab_label',
                                    'label'       => esc_html__( 'Tab Label', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                ),

                                array(
                                    'name'        => 'label_1',
                                    'label'       => esc_html__( 'Button Label One', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                ),

                                array(
                                    'name'        => 'label_2',
                                    'label'       => esc_html__( 'Button Label Two', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                ),

                            ]
                        ),
                        
                    )

                ),
                
                array(
                    'name'     => 'woolentor_flash_sale_event_settings',
                    'label'    => esc_html__( 'Flash Sale Countdown', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_flash_sale_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/enable-sales-countdown-timer-in-woocommerce/'),
                    'setting_fields' => array(

                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable flash sale from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'deals',
                            'label'       => esc_html__( 'Sale Events', 'woolentor' ),
                            'type'        => 'repeater',
                            'title_field' => 'title',
                            'fields'  => [

                                array(
                                    'name'        => 'title',
                                    'label'       => esc_html__( 'Event Name', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left',
                                    'condition' => array( 'status', '==', 'true' ),
                                ),

                                array(
                                    'name'        => 'status',
                                    'label'       => esc_html__( 'Enable', 'woolentor' ),
                                    'desc'        => esc_html__( 'Enable / Disable', 'woolentor' ),
                                    'type'        => 'checkbox',
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'start_date',
                                    'label'       => esc_html__( 'Valid From', 'woolentor' ),
                                    'desc'        => __( 'The date and time the event should be enabled. Please set time based on your server time settings. Current Server Date / Time: '. current_time('Y M d'), 'woolentor' ),
                                    'type'        => 'date',
                                    'condition' => array( 'status', '==', 'true' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'end_date',
                                    'label'       => esc_html__( 'Valid To', 'woolentor' ),
                                    'desc'        => esc_html__( 'The date and time the event should be disabled.', 'woolentor' ),
                                    'type'        => 'date',
                                    'condition' => array( 'status', '==', 'true' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'apply_on_all_products',
                                    'label'       => esc_html__( 'Apply On All Products', 'woolentor' ),
                                    'type'        => 'checkbox',
                                    'default'     => 'off',
                                    'condition'   => array( 'status', '==', 'true' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'categories',
                                    'label'       => esc_html__( 'Select Categories', 'woolentor' ),
                                    'desc'        => esc_html__( 'Select the categories in which products the discount will be applied.', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options'     => woolentor_taxonomy_list('product_cat','term_id'),
                                    'condition'   => array( 'status|apply_on_all_products', '==|==', 'true|false' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'products',
                                    'label'       => esc_html__( 'Select Products', 'woolentor' ),
                                    'desc'        => esc_html__( 'Select individual products in which the discount will be applied.', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options'     => woolentor_post_name( 'product' ),
                                    'condition'   => array( 'status|apply_on_all_products', '==|==', 'true|false' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'exclude_products',
                                    'label'       => esc_html__( 'Exclude Products', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options'     => woolentor_post_name( 'product' ),
                                    'condition'   => array( 'status', '==', 'true' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'discount_type',
                                    'label'       => esc_html__( 'Discount Type', 'woolentor' ),
                                    'type'        => 'select',
                                    'default'     => 'percentage_discount',
                                    'options'     => array(
                                        'fixed_discount'      => esc_html__( 'Fixed Discount', 'woolentor' ),
                                        'percentage_discount' => esc_html__( 'Percentage Discount', 'woolentor' ),
                                        'fixed_price'         => esc_html__( 'Fixed Price', 'woolentor' ),
                                    ),
                                    'condition'   => array( 'status', '==', 'true' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'discount_value',
                                    'label' => esc_html__( 'Discount Value', 'woolentor-pro' ),
                                    'min'               => 0.0,
                                    'step'              => 0.01,
                                    'type'              => 'number',
                                    'default'           => '50',
                                    'sanitize_callback' => 'floatval',
                                    'condition'         => array( 'status', '==', 'true' ),
                                    'class'             => 'woolentor-action-field-left',
                                ),

                                array(
                                    'name'        => 'apply_discount_only_for_registered_customers',
                                    'label'       => esc_html__( 'Apply Discount Only For Registered Customers', 'woolentor' ),
                                    'type'        => 'checkbox',
                                    'condition'   => array( 'status', '==', 'true' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                            ]
                        ),

                        array(
                            'name'        => 'manage_price_label',
                            'label'       => esc_html__( 'Manage Price Label', 'woolentor' ),
                            'desc'        => esc_html__( 'Manage how you want the price labels to appear, or leave it blank to display only the flash-sale price without any labels. Available placeholders: {original_price}, {flash_sale_price}', 'woolentor' ),
                            'type'        => 'text',
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'override_sale_price',
                            'label'   => esc_html__( 'Override Sale Price', 'woolentor' ),
                            'type'    => 'checkbox',
                            'default' => 'off',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'enable_countdown_on_product_details_page',
                            'label'   => esc_html__( 'Show Countdown On Product Details Page', 'woolentor' ),
                            'type'    => 'checkbox',
                            'default' => 'on',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'      => 'countdown_style',
                            'label'     => esc_html__( 'Countdown Style', 'woolentor' ),
                            'type'      => 'select',
                            'options'   => array(
                               '1'      => esc_html__('Style One', 'woolentor'),
                               '2'      => esc_html__('Style Two', 'woolentor'),
                            ),
                            'default'   => '2',
                            'condition' => array( 'enable_countdown_on_product_details_page', '==', 'true' ),
                            'class'     => 'woolentor-action-field-left'
                        ),

                         array(
                             'name'        => 'countdown_position',
                             'label'       => esc_html__( 'Countdown Position', 'woolentor' ),
                             'type'        => 'select',
                             'options'     => array(
                                'woocommerce_before_add_to_cart_form'      => esc_html__('Add to cart - Before', 'woolentor'),
                                'woocommerce_after_add_to_cart_form'       => esc_html__('Add to cart - After', 'woolentor'),
                                'woocommerce_product_meta_start'           => esc_html__('Product meta - Before', 'woolentor'),
                                'woocommerce_product_meta_end'             => esc_html__('Product meta - After', 'woolentor'),
                                'woocommerce_single_product_summary'       => esc_html__('Product summary - Before', 'woolentor'),
                                'woocommerce_after_single_product_summary' => esc_html__('Product summary - After', 'woolentor'),
                             ),
                             'condition'   => array( 'enable_countdown_on_product_details_page', '==', 'true' ),
                             'class'       => 'woolentor-action-field-left'
                         ),

                        array(
                            'name'    => 'countdown_timer_title',
                            'label'   => esc_html__( 'Countdown Timer Title', 'woolentor' ),
                            'type'    => 'text',
                            'default' => esc_html__('Hurry Up! Offer ends in', 'woolentor'),
                            'condition' => array( 'enable_countdown_on_product_details_page', '==', 'true' ),
                            'class'   => 'woolentor-action-field-left'
                        ),
                        
                    )

                ),

                array(
                    'name'     => 'partial_payment',
                    'label'    => esc_html__( 'Partial Payment', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_partial_payment_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-accept-partial-payment-in-woocommerce/'),
                    'setting_fields' => array(

                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable partial payment from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'amount_type',
                            'label'   => esc_html__( 'Amount Type', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Choose how you want to received the partial payment.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => 'percentage',
                            'options' => [
                                'fixedamount' => esc_html__('Fixed Amount','woolentor-pro'),
                                'percentage' => esc_html__('Percentage','woolentor-pro'),
                            ],
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'  => 'amount',
                            'label' => esc_html__( 'Amount', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Enter the partial payment amount based on the amount type you chose above (should not be more than 99 for percentage or more than order total for fixed )', 'woolentor-pro' ),
                            'min'               => 0.0,
                            'step'              => 0.01,
                            'type'              => 'number',
                            'default'           => '50',
                            'sanitize_callback' => 'floatval',
                            'class'             => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'default_selected',
                            'label'   => esc_html__( 'Default payment type', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Select a payment type that you want to set by default.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => 'partial',
                            'options' => [
                                'partial' => esc_html__('Partial Payment','woolentor-pro'),
                                'full'    => esc_html__('Full Payment','woolentor-pro'),
                            ],
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'disallowed_payment_method_ppf',
                            'label'   => esc_html__( 'Disallowed payment method for first installment', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Select payment methods that you want to disallow for first installment.', 'woolentor-pro' ),
                            'type'    => 'multiselect',
                            'options' => function_exists('woolentor_get_payment_method') ? woolentor_get_payment_method() : ['notfound'=>esc_html__('Not Found','woolentor-pro')],
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'disallowed_payment_method_pps',
                            'label'   => esc_html__( 'Disallowed payment method for second installment', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Select payment methods that you want to disallow for second installment.', 'woolentor-pro' ),
                            'type'    => 'multiselect',
                            'options' => function_exists('woolentor_get_payment_method') ? woolentor_get_payment_method() : ['notfound'=>esc_html__('Not Found','woolentor-pro')],
                            'class' => 'woolentor-action-field-left'
                        ),

                        // array(
                        //     'name'  => 'payment_reminder',
                        //     'label' => esc_html__( 'Second installment payment reminder date in day', 'woolentor-pro' ),
                        //     'desc'  => esc_html__( 'Send a reminder email before second payment due date', 'woolentor-pro' ),
                        //     'type'              => 'number',
                        //     'default'           => '5',
                        //     'sanitize_callback' => 'floatval',
                        //     'class'             => 'woolentor-action-field-left',
                        // ),

                        array(
                            'name'    => 'shop_loop_btn_area_title',
                            'headding'=> esc_html__( 'Shop / Product Loop', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),

                        array(
                            'name'        => 'partial_payment_loop_btn_text',
                            'label'       => esc_html__( 'Add to cart button text', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'You can change the add to cart button text for the products that allow partial payment.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Partial Payment', 'woolentor-pro' ),
                            'default'     => esc_html__( 'Partial Payment', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'single_product_custom_text_title',
                            'headding'=> esc_html__( 'Single Product', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),

                        array(
                            'name'        => 'partial_payment_button_text',
                            'label'       => esc_html__( 'Partial payment button label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Insert the label for the partial payment option.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'Partial Payment', 'woolentor-pro' ),
                            'default'     => esc_html__( 'Partial Payment', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'full_payment_button_text',
                            'label'       => esc_html__( 'Full payment button label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Insert the label for the full payment option.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Full Payment', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'Full Payment', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'partial_payment_discount_text',
                            'label'       => esc_html__( 'First deposit label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Insert the first deposit label from here. Available placeholders: {price} ', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'First Instalment : {price} Per item', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'First Installment', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'checkout_custom_text_title',
                            'headding'=> esc_html__( 'Cart / Checkout', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),

                        array(
                            'name'        => 'first_installment_text',
                            'label'       => esc_html__( 'First installment amount label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Enter the first installment amount label.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'First Installment', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'First Installment', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'second_installment_text',
                            'label'       => esc_html__( 'Second installment amount label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Enter the second installment amount label.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Second Installment', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'Second Installment', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'to_pay',
                            'label'       => esc_html__( 'Amount to pay label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Enter the label for amount to pay.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'To Pay', 'woolentor-pro' ),
                            'placeholder' => esc_html__( 'To Pay', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        
                    )

                ),

                array(
                    'name'     => 'pre_orders',
                    'label'    => esc_html__( 'Pre Orders', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_pre_order_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-set-pre-order-for-woocommerce/'),
                    'setting_fields' => array(

                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable pre orders from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'add_to_cart_btn_text',
                            'label'       => esc_html__( 'Add to cart button text', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'You can change the add to cart button text for the products that allow pre order.', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__('Pre Order','woolentor-pro'),
                            'placeholder' => esc_html__( 'Pre Order', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'manage_price_lavel',
                            'label'       => esc_html__( 'Manage Price Label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Manage how you want the price labels to appear, or leave it blank to display only the pre-order price without any labels. Available placeholders: {original_price}, {preorder_price}', 'woolentor-pro' ),
                            'default'     => esc_html__( '{original_price} Pre order price: {preorder_price}', 'woolentor-pro' ),
                            'type'        => 'text',
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'availability_date',
                            'label'       => esc_html__( 'Availability date label', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Manage how you want the availability date labels to appear. Available placeholders: {availability_date}, {availability_time}', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Available on: {availability_date} at {availability_time}', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'  => 'show_countdown',
                            'label' => esc_html__( 'Show Countdown', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable pre orders countdown from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'countdown_heading_title',
                            'headding'=> esc_html__( 'Countdown Custom Label', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                            'condition' => array( 'show_countdown', '==', 'true' ),
                        ),

                        array(
                            'name'        => 'customlabel_days',
                            'label'       => esc_html__( 'Days', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Days', 'woolentor-pro' ),
                            'condition'   => array( 'show_countdown', '==', 'true' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        array(
                            'name'        => 'customlabel_hours',
                            'label'       => esc_html__( 'Hours', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Hours', 'woolentor-pro' ),
                            'condition'   => array( 'show_countdown', '==', 'true' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        array(
                            'name'        => 'customlabel_minutes',
                            'label'       => esc_html__( 'Minutes', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Min', 'woolentor-pro' ),
                            'condition'   => array( 'show_countdown', '==', 'true' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        array(
                            'name'        => 'customlabel_seconds',
                            'label'       => esc_html__( 'Seconds', 'woolentor-pro' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'Sec', 'woolentor-pro' ),
                            'condition'   => array( 'show_countdown', '==', 'true' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                    ),
                ),

                array(
                    'name'     => 'woolentor_backorder_settings',
                    'label'    => esc_html__( 'Backorder', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_backorder_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-enable-woocommerce-backorder/'),
                    'setting_fields' => array(
                    
                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'  => esc_html__( 'You can enable / disable backorder module from here.', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'backorder_limit',
                            'label'   => esc_html__( 'Backorder Limit', 'woolentor' ),
                            'desc'    => esc_html__( 'Set "Backorder Limit" on all "Backorder" products across the entire website. You can also set limits for each product individually from the "Inventory" tab.', 'woolentor' ),
                            'type'    => 'number',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'backorder_availability_date',
                            'label'   => esc_html__( 'Availability Date', 'woolentor' ),
                            'type'    => 'date',
                            'class'   => 'woolentor-action-field-left'
                        ),
                    
                        array(
                            'name'        => 'backorder_availability_message',
                            'label'       => esc_html__( 'Availability Message', 'woolentor' ),
                            'desc'        => esc_html__( 'Manage how you want the "Message" to appear. Use this {availability_date} placeholder to display the date you set. ', 'woolentor' ),
                            'type'        => 'text',
                            'default'     => esc_html__( 'On Backorder: Will be available on {availability_date}', 'woolentor' ),
                            'class'       => 'woolentor-action-field-left',
                        ),
                        
                    )
                    
                ),

                array(
                    'name'     => 'woolentor_checkout_field_settings',
                    'label'    => esc_html__( 'Checkout Fields Manager', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_checkout_fields',
                    'option_id'=> 'billing_enable,shipping_enable,additional_enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/checkout-field-editor/'),
                    'setting_fields' => array(

                        array(
                            'name'  => 'billing_enable',
                            'label' => esc_html__( 'Modify Billing Field', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'billing',
                            'label'       => esc_html__( 'Manage Billing Form Field', 'woolentor' ),
                            'type'        => 'repeater',
                            'title_field' => 'field_label',
                            'condition'   => array( 'billing_enable', '==', 'true' ),
                            'fields'  => [

                                array(
                                    'name'        => 'field_key',
                                    'label'       => esc_html__( 'Field name', 'woolentor' ),
                                    'type'        => 'select',
                                    'options' => [
                                        'first_name'=> esc_html__( 'First Name', 'woolentor-pro' ),
                                        'last_name' => esc_html__( 'Last Name', 'woolentor-pro' ),
                                        'company'   => esc_html__( 'Company', 'woolentor-pro' ),
                                        'country'   => esc_html__( 'Country', 'woolentor-pro' ),
                                        'address_1' => esc_html__( 'Street address', 'woolentor-pro' ),
                                        'address_2' => esc_html__( 'Apartment address', 'woolentor-pro' ),
                                        'city'      => esc_html__( 'Town / City', 'woolentor-pro' ),
                                        'state'     => esc_html__( 'District', 'woolentor-pro' ),
                                        'postcode'  => esc_html__( 'Postcode / ZIP', 'woolentor-pro' ),
                                        'phone'     => esc_html__( 'Phone', 'woolentor-pro' ),
                                        'email'     => esc_html__( 'Email', 'woolentor-pro' ),
                                        'customadd' => esc_html__( 'Add Custom', 'woolentor-pro' )
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_type',
                                    'label'       => esc_html__( 'Field Type', 'woolentor' ),
                                    'type'        => 'select',
                                    'options'     => class_exists('WooLentor_Checkout_Field_Manager') ? WooLentor_Checkout_Field_Manager::instance()->field_types() : [],
                                    'condition'   => array( 'field_key', '==', 'customadd' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_key_custom',
                                    'label'       => esc_html__( 'Custom key', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_key', '==', 'customadd' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),
                                
                                array(
                                    'name'        => 'field_label',
                                    'label'       => esc_html__( 'Label', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'title_tag',
                                    'label'       => esc_html__( 'Title Tag', 'woolentor' ),
                                    'type'        => 'select',
                                    'options'     => function_exists('woolentor_html_tag_lists') ? woolentor_html_tag_lists() : [],
                                    'default'     => 'h3',
                                    'condition'   => array( 'field_type', '==', 'heading' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_placeholder',
                                    'label'       => esc_html__( 'Placeholder', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_type','not-any','radio,heading,checkbox,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_default_value',
                                    'label'       => esc_html__( 'Default Value', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_type','not-any','heading,checkbox' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_validation',
                                    'label'       => esc_html__( 'Validation', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options' => [
                                        'email'     => esc_html__( 'Email', 'woolentor-pro' ),
                                        'phone'     => esc_html__( 'Phone', 'woolentor-pro' ),
                                        'postcode'  => esc_html__( 'Postcode', 'woolentor-pro' ),
                                        'state'     => esc_html__( 'State', 'woolentor-pro' ),
                                        'number'    => esc_html__( 'Number', 'woolentor-pro' )
                                    ],
                                    'condition'   => array( 'field_type', 'not-any', 'heading,multiselect,checkbox,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_class',
                                    'label'       => esc_html__( 'Class', 'woolentor-pro' ),
                                    'type'        => 'text',
                                    'desc'        => esc_html__( 'You can use ( form-row-first, form-row-last, form-row-wide, woolentor-one-third )' , 'woolentor-pro' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_options',
                                    'label'       => esc_html__( 'Options', 'woolentor-pro' ),
                                    'type'        => 'textarea',
                                    'desc'        => 'Add a single option by using the format: Value, Label<br/>For multiple options, use a pipe symbol to separate them. For instance: value_1, label_1 | value_2, label_2  | value_3, label_3',
                                    'placeholder' => esc_html__('one,Select One','woolentor-pro'),
                                    'condition'   => array( 'field_type', 'any', 'select,radio,multiselect,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_required',
                                    'label' => esc_html__( 'Required', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_type','!=','heading' ),
                                    'class' => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_show_email',
                                    'label' => esc_html__( 'Show in Email', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_key|field_type', '==|!=', 'customadd|heading' ),
                                    'class' => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_show_order',
                                    'label' => esc_html__( 'Show in Order Detail Page', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_key|field_type', '==|!=', 'customadd|heading' ),
                                    'class' => 'woolentor-action-field-left'
                                )

                            ],

                            'default' => class_exists('WooLentor_Checkout_Field_Manager') && !empty(WooLentor_Checkout_Field_Manager::instance()->get_previous_fields('billing') ) ?WooLentor_Checkout_Field_Manager::instance()->get_previous_fields('billing') : [
                                [
                                    'field_key'             => 'first_name',
                                    'field_label'           => esc_html__( 'First Name', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-first',
                                    'field_required'        => 'on',
                                ],
                                [
                                    'field_key'             => 'last_name',
                                    'field_label'           => esc_html__( 'Last Name', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-last',
                                    'field_required'        => 'on',
                                ],
                                [
                                    'field_key'             => 'company',
                                    'field_label'           => esc_html__( 'Company name', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide',
                                    'field_required'        => 'off',
                                ],
                                [
                                    'field_key'             => 'country',
                                    'field_label'           => esc_html__( 'Country', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field,update_totals_on_change',
                                    'field_required'        => 'on',
                                ],
                                [
                                    'field_key'             => 'address_1',
                                    'field_label'           => esc_html__( 'Street address', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'off',
                                ],
                                [
                                    'field_key'             => 'address_2',
                                    'field_label'           => esc_html__( 'Apartment address','woolentor-pro'),
                                    'field_placeholder'     => esc_html__( 'Apartment, suite, unit etc. (optional)', 'woolentor-pro' ),
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'off',
                                ],
                                [
                                    'field_key'             => 'city',
                                    'field_label'           => esc_html__( 'Town / City', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'on',
                                ],
                                [
                                    'field_key'             => 'state',
                                    'field_label'           => esc_html__( 'State / County', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => ['state'],
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'off',
                                ],
                                [
                                    'field_key'             => 'postcode',
                                    'field_label'           => esc_html__( 'Postcode / ZIP', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => ['postcode'],
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'on',
                                ],
                                [
                                    'field_key'             => 'phone',
                                    'field_label'           => esc_html__( 'Phone', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => ['phone'],
                                    'field_class'           => 'form-row-wide',
                                    'field_required'        => 'on',
                                ],
                                [
                                    'field_key'             => 'email',
                                    'field_label'           => esc_html__( 'Email address', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => ['email'],
                                    'field_class'           => 'form-row-wide',
                                    'field_required'        => 'on',
                                ],
                            ]
                        ),

                        array(
                            'name'  => 'shipping_enable',
                            'label' => esc_html__( 'Modify Shipping Field', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'shipping',
                            'label'       => esc_html__( 'Manage Shipping Form Field', 'woolentor' ),
                            'type'        => 'repeater',
                            'title_field' => 'field_label',
                            'condition'   => array( 'shipping_enable', '==', 'true' ),
                            'fields'  => [

                                array(
                                    'name'        => 'field_key',
                                    'label'       => esc_html__( 'Field name', 'woolentor' ),
                                    'type'        => 'select',
                                    'options' => [
                                        'first_name'=> esc_html__( 'First Name', 'woolentor-pro' ),
                                        'last_name' => esc_html__( 'Last Name', 'woolentor-pro' ),
                                        'company'   => esc_html__( 'Company', 'woolentor-pro' ),
                                        'country'   => esc_html__( 'Country', 'woolentor-pro' ),
                                        'address_1' => esc_html__( 'Street address', 'woolentor-pro' ),
                                        'address_2' => esc_html__( 'Apartment address', 'woolentor-pro' ),
                                        'city'      => esc_html__( 'Town / City', 'woolentor-pro' ),
                                        'state'     => esc_html__( 'District', 'woolentor-pro' ),
                                        'postcode'  => esc_html__( 'Postcode / ZIP', 'woolentor-pro' ),
                                        'customadd' => esc_html__( 'Add Custom', 'woolentor-pro' )
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_type',
                                    'label'       => esc_html__( 'Field Type', 'woolentor' ),
                                    'type'        => 'select',
                                    'options'     => class_exists('WooLentor_Checkout_Field_Manager') ? WooLentor_Checkout_Field_Manager::instance()->field_types() : [],
                                    'condition'   => array( 'field_key', '==', 'customadd' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_key_custom',
                                    'label'       => esc_html__( 'Custom key', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_key', '==', 'customadd' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),
                                
                                array(
                                    'name'        => 'field_label',
                                    'label'       => esc_html__( 'Label', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'title_tag',
                                    'label'       => esc_html__( 'Title Tag', 'woolentor' ),
                                    'type'        => 'select',
                                    'options'     => function_exists('woolentor_html_tag_lists') ? woolentor_html_tag_lists() : [],
                                    'default'     => 'h3',
                                    'condition'   => array( 'field_type', '==', 'heading' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_placeholder',
                                    'label'       => esc_html__( 'Placeholder', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_type','not-any','radio,heading,checkbox,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_default_value',
                                    'label'       => esc_html__( 'Default Value', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_type','not-any','heading,checkbox' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_validation',
                                    'label'       => esc_html__( 'Validation', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options' => [
                                        'email'     => esc_html__( 'Email', 'woolentor-pro' ),
                                        'phone'     => esc_html__( 'Phone', 'woolentor-pro' ),
                                        'postcode'  => esc_html__( 'Postcode', 'woolentor-pro' ),
                                        'state'     => esc_html__( 'State', 'woolentor-pro' ),
                                        'number'    => esc_html__( 'Number', 'woolentor-pro' )
                                    ],
                                    'condition'   => array( 'field_type', 'not-any', 'heading,multiselect,checkbox,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_class',
                                    'label'       => esc_html__( 'Class', 'woolentor-pro' ),
                                    'type'        => 'text',
                                    'desc'        => esc_html__( 'You can use ( form-row-first, form-row-last, form-row-wide, woolentor-one-third )' , 'woolentor-pro' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_options',
                                    'label'       => esc_html__( 'Options', 'woolentor-pro' ),
                                    'type'        => 'textarea',
                                    'desc'        => 'Add a single option by using the format: Value, Label<br/>For multiple options, use a pipe symbol to separate them. For instance: value_1, label_1 | value_2, label_2  | value_3, label_3',
                                    'placeholder' => esc_html__('one,Select One','woolentor-pro'),
                                    'condition'   => array( 'field_type', 'any', 'select,radio,multiselect,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_required',
                                    'label' => esc_html__( 'Required', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_type','!=','heading' ),
                                    'class' => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_show_email',
                                    'label' => esc_html__( 'Show in Email', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_key|field_type', '==|!=', 'customadd|heading' ),
                                    'class' => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_show_order',
                                    'label' => esc_html__( 'Show in Order Detail Page', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_key|field_type', '==|!=', 'customadd|heading' ),
                                    'class' => 'woolentor-action-field-left'
                                )
                            ],

                            'default' => class_exists('WooLentor_Checkout_Field_Manager') && !empty(WooLentor_Checkout_Field_Manager::instance()->get_previous_fields('shipping') ) ?WooLentor_Checkout_Field_Manager::instance()->get_previous_fields('shipping') : [
                                [
                                    'field_key'             => 'first_name',
                                    'field_label'           => esc_html__( 'First Name', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-first',
                                    'field_required'        => 'yes',
                                ],
                                [
                                    'field_key'             => 'last_name',
                                    'field_label'           => esc_html__( 'Last Name', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-last',
                                    'field_required'        => 'yes',
                                ],
                                [
                                    'field_key'             => 'company',
                                    'field_label'           => esc_html__( 'Company name', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide',
                                    'field_required'        => 'no',
                                ],
                                [
                                    'field_key'             => 'country',
                                    'field_label'           => esc_html__( 'Country', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field,update_totals_on_change',
                                    'field_required'        => 'yes',
                                ],
                                [
                                    'field_key'             => 'address_1',
                                    'field_label'           => esc_html__( 'Street address', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'yes',
                                ],
                                [
                                    'field_key'             => 'address_2',
                                    'field_label'           => esc_html__( 'Apartment address','woolentor-pro'),
                                    'field_placeholder'     => esc_html__( 'Apartment, suite, unit etc. (optional)', 'woolentor-pro' ),
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'no',
                                ],
                                [
                                    'field_key'             => 'city',
                                    'field_label'           => esc_html__( 'Town / City', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'yes',
                                ],
                                [
                                    'field_key'             => 'state',
                                    'field_label'           => esc_html__( 'State / County', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => ['state'],
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'no',
                                ],
                                [
                                    'field_key'             => 'postcode',
                                    'field_label'           => esc_html__( 'Postcode / ZIP', 'woolentor-pro' ),
                                    'field_placeholder'     => '',
                                    'field_default_value'   => '',
                                    'field_validation'      => ['postcode'],
                                    'field_class'           => 'form-row-wide,address-field',
                                    'field_required'        => 'yes',
                                ]
                                
                            ]
                        ),

                        array(
                            'name'  => 'additional_enable',
                            'label' => esc_html__( 'Modify Additional Field', 'woolentor' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'additional',
                            'label'       => esc_html__( 'Manage Additional Form Field', 'woolentor' ),
                            'type'        => 'repeater',
                            'title_field' => 'field_label',
                            'condition'   => array( 'additional_enable', '==', 'true' ),
                            'fields'  => [
                                array(
                                    'name'        => 'field_key',
                                    'label'       => esc_html__( 'Field name', 'woolentor' ),
                                    'type'        => 'select',
                                    'options' => [
                                        'order_comments' => esc_html__( 'Order Notes', 'woolentor-pro' ),
                                        'customadd'      => esc_html__( 'Add Custom', 'woolentor-pro' ),
                                    ],
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_type',
                                    'label'       => esc_html__( 'Field Type', 'woolentor' ),
                                    'type'        => 'select',
                                    'options'     => class_exists('WooLentor_Checkout_Field_Manager') ? WooLentor_Checkout_Field_Manager::instance()->field_types() : [],
                                    'condition'   => array( 'field_key', '==', 'customadd' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_key_custom',
                                    'label'       => esc_html__( 'Custom key', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_key', '==', 'customadd' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),
                                
                                array(
                                    'name'        => 'field_label',
                                    'label'       => esc_html__( 'Label', 'woolentor' ),
                                    'type'        => 'text',
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'title_tag',
                                    'label'       => esc_html__( 'Title Tag', 'woolentor' ),
                                    'type'        => 'select',
                                    'options'     => function_exists('woolentor_html_tag_lists') ? woolentor_html_tag_lists() : [],
                                    'default'     => 'h3',
                                    'condition'   => array( 'field_type', '==', 'heading' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_placeholder',
                                    'label'       => esc_html__( 'Placeholder', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_type','not-any','radio,heading,checkbox,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_default_value',
                                    'label'       => esc_html__( 'Default Value', 'woolentor' ),
                                    'type'        => 'text',
                                    'condition'   => array( 'field_type','not-any','heading,checkbox' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_validation',
                                    'label'       => esc_html__( 'Validation', 'woolentor' ),
                                    'type'        => 'multiselect',
                                    'options' => [
                                        'email'     => esc_html__( 'Email', 'woolentor-pro' ),
                                        'phone'     => esc_html__( 'Phone', 'woolentor-pro' ),
                                        'postcode'  => esc_html__( 'Postcode', 'woolentor-pro' ),
                                        'state'     => esc_html__( 'State', 'woolentor-pro' ),
                                        'number'    => esc_html__( 'Number', 'woolentor-pro' )
                                    ],
                                    'condition'   => array( 'field_type', 'not-any', 'heading,multiselect,checkbox,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_class',
                                    'label'       => esc_html__( 'Class', 'woolentor-pro' ),
                                    'type'        => 'text',
                                    'desc'        => esc_html__( 'You can use ( form-row-first, form-row-last, form-row-wide, woolentor-one-third )' , 'woolentor-pro' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'        => 'field_options',
                                    'label'       => esc_html__( 'Options', 'woolentor-pro' ),
                                    'type'        => 'textarea',
                                    'desc'        => 'Add a single option by using the format: Value, Label<br/>For multiple options, use a pipe symbol to separate them. For instance: value_1, label_1 | value_2, label_2  | value_3, label_3',
                                    'placeholder' => esc_html__('one,Select One','woolentor-pro'),
                                    'condition'   => array( 'field_type', 'any', 'select,radio,multiselect,checkboxgroup' ),
                                    'class'       => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_required',
                                    'label' => esc_html__( 'Required', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_type','!=','heading' ),
                                    'class' => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_show_email',
                                    'label' => esc_html__( 'Show in Email', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_key|field_type', '==|!=', 'customadd|heading' ),
                                    'class' => 'woolentor-action-field-left'
                                ),

                                array(
                                    'name'  => 'field_show_order',
                                    'label' => esc_html__( 'Show in Order Detail Page', 'woolentor' ),
                                    'type'  => 'checkbox',
                                    'default' => 'off',
                                    'condition'   => array( 'field_key|field_type', '==|!=', 'customadd|heading' ),
                                    'class' => 'woolentor-action-field-left'
                                )

                            ],

                            'default' => class_exists('WooLentor_Checkout_Field_Manager') && !empty(WooLentor_Checkout_Field_Manager::instance()->get_previous_fields('additional') ) ?WooLentor_Checkout_Field_Manager::instance()->get_previous_fields('additional') : [
                                [
                                    'field_key'             => 'order_comments',
                                    'field_label'           => esc_html__( 'Order Notes', 'woolentor-pro' ),
                                    'field_placeholder'     => 'Notes about your order, e.g. special notes for delivery.',
                                    'field_default_value'   => '',
                                    'field_validation'      => '',
                                    'field_class'           => 'notes',
                                    'field_required'        => false,
                                ],
        
                            ]

                        )
                        
                    )

                ),

                array(
                    'name'     => 'gtm_conversion_tracking',
                    'label'    => esc_html__( 'GTM Conversion Tracking', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_gtm_convertion_tracking_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'setting_fields' => array(

                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable GTM Conversion tracking from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'gtm_id',
                            'label'       => esc_html__( 'Google Tag Manager ID', 'woolentor-pro' ),
                            'type'        => 'text',
                            'placeholder' => esc_html__( 'GTM-XXXXX', 'woolentor-pro' ),
                            'desc'        => wp_kses_post( 'Enter your google tag manager id (<a href="'.esc_url('https://developers.google.com/tag-manager/quickstart').'" target="_blank">Lookup your ID</a>)' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'gtm_container_template_generate',
                            'label'       => esc_html__( 'Generate GTM Container Template', 'woolentor-pro' ),
                            'type'        => 'button',
                            'html'        => wp_kses_post( '<a class="woolentor-admin-btn woolentor-admin-btn-primary hover-effect-1" href="'.esc_url('https://hasthemes.com/tool/gtm-container-template-generator/').'" target="_blank">'.esc_html__('Generate Now','woolentor-pro').'</a>' ),
                            'desc'        => esc_html__( 'We\'ve developed a new tool that generates a Google Tag Manager template file in less than two minutes. Connecting and integrating tracking tools such as Facebook pixels, Google Analytics, and Google Ads Remarketing with GTM normally takes 2-3 hours. We made it simple, and faster than ever.', 'woolentor-pro' ),
                            'class'       => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'tracking_event_heading_title',
                            'headding'=> esc_html__( 'Tracking Event', 'woolentor-pro' ),
                            'type'    => 'title',
                            'size'    => 'margin_0 regular',
                            'class'   => 'element_section_title_area',
                        ),

                        array(
                            'name'  => 'shop_enable',
                            'label' => esc_html__( 'Shop / Archive Page Items view tracking', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Enable this option to track the Shop/Archive page items.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'  => 'product_enable',
                            'label' => esc_html__( 'Single Product Page Tracking', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Enable this option to track the single product page content.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'  => 'cart_enable',
                            'label'  => esc_html__( 'Cart Page Tracking', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Enable this option to track the all cart items.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'  => 'checkout_enable',
                            'label'  => esc_html__( 'Checkout Page Tracking', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Enable this option to track the user data on the checkout page.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'  => 'thankyou_enable',
                            'label'  => esc_html__( 'Thankyou page Tracking', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Enable this option to track the user order data on the thankyou page.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'  => 'add_to_cart_enable',
                            'label'  => esc_html__( 'Add to cart Tracking', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Enable this option to track the user behavior on the add to cart.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'  => 'single_add_to_cart_enable',
                            'label'  => esc_html__( 'Add to cart Tracking from single product', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Enable this option to track the add to cart on single product page.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'  => 'remove_from_cart_enable',
                            'label'  => esc_html__( 'Remove from cart', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Enable this option to track the remove cart item.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'on',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'product_brands',
                            'label'   => esc_html__( 'Product brands', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Select which taxonomy of products you want to set for the product brand in the data layer.', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default'=>'none',
                            'options' => array( 'none' => esc_html__( 'Select Taxonomy', 'woolentor-pro' ) ) + woolentor_get_taxonomies('product', true),
                            'class' => 'woolentor-action-field-left'
                        ),
        
                        array(
                            'name'  => 'use_sku',
                            'label'  => esc_html__( 'Use SKU instead of ID', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Enable this option to track your e-commerce business using the product SKUs instead of the IDs in the data layer.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                    ),
                ),

                array(
                    'name'     => 'size_chart',
                    'label'    => esc_html__( 'Size Chart', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_size_chart_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/woocommerce-product-size-chart/'),
                    'setting_fields' => array(
                
                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable size chart from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'show_as',
                            'label'       => esc_html__( 'Show As', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Choose where/how the size chart should be displayed.', 'woolentor-pro' ),
                            'type'        => 'select',
                            'options'     => array(
                                'additional_tab' => esc_html__('Additional Tab', 'woolentor'),
                                'popup'          => esc_html__('Popup', 'woolentor'),
                            ),
                            'class'       => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'additional_tab_label',
                            'label'   => esc_html__( 'Additional Tab Text', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Rename size chart tab label.', 'woolentor-pro' ),
                            'type'    => 'text',
                            'default' => esc_html__( 'Size Chart', 'woolentor-pro' ),
                            'condition' => array( 'show_as', '==', 'additional_tab' ),
                            'class'   => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'popup_button_text',
                            'label'   => esc_html__( 'Button Text', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'The text appears on the button that opens the popup.', 'woolentor-pro' ),
                            'type'    => 'text',
                            'default' => esc_html__( 'Size Chart', 'woolentor-pro' ),
                            'condition' => array( 'show_as', '==', 'popup' ),
                            'class'   => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'popup_button_positon',
                            'label'       => esc_html__( 'Button Position', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'You can popup button position from here.', 'woolentor-pro' ),
                            'type'        => 'select',
                            'options'     => array(
                                'woocommerce_before_add_to_cart_form'      => esc_html__('Add to cart - Before', 'woolentor-pro'),
                                'woocommerce_after_add_to_cart_form'       => esc_html__('Add to cart - After', 'woolentor-pro'),
                                'woocommerce_product_meta_start'           => esc_html__('Product meta - Before', 'woolentor-pro'),
                                'woocommerce_product_meta_end'             => esc_html__('Product meta - After', 'woolentor-pro'),
                                'woocommerce_single_product_summary'       => esc_html__('Product summary - Before', 'woolentor-pro'),
                                'woocommerce_after_single_product_summary' => esc_html__('Product summary - After', 'woolentor-pro'),
                            ),
                            'condition' => array( 'show_as', '==', 'popup' ),
                            'class'       => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'hide_popup_title',
                            'label'   => esc_html__( 'Hide Title', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Hide the chart name on popup title.', 'woolentor-pro' ),
                            'type'    => 'checkbox',
                            'condition' => array( 'show_as', '==', 'popup' ),
                            'class'   => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'button_icon',
                            'label'   => esc_html__( 'Button Icon', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'You can manage the size chart button icon.', 'woolentor-pro' ),
                            'type'    => 'text',
                            'default' => 'sli sli-chart',
                            'condition' => array( 'show_as', '==', 'popup' ),
                            'class'   => 'woolentor_icon_picker woolentor-action-field-left'
                        ),

                        array(
                            'name'  => 'button_margin',
                            'label' => esc_html__( 'Button Margin', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can manage button margin from here.', 'woolentor-pro' ),
                            'type'  => 'dimensions',
                            'options' => [
                                'top'   => esc_html__( 'Top', 'woolentor-pro' ),
                                'right' => esc_html__( 'Right', 'woolentor-pro' ),   
                                'bottom'=> esc_html__( 'Bottom', 'woolentor-pro' ),   
                                'left'  => esc_html__( 'Left', 'woolentor-pro' ),
                                'unit'  => esc_html__( 'Unit', 'woolentor-pro' ),
                            ],
                            'condition' => array( 'show_as', '==', 'popup' ),
                            'class' => 'woolentor-action-field-left woolentor-dimention-field-left',
                        ),

                        array(
                            'name'      => 'design_options_heading',
                            'headding'  => esc_html__( 'Chart Table Style', 'woolentor-pro' ),
                            'type'      => 'title'
                        ),

                        array(
                            'name'  => 'table_head_bg_color',
                            'label' => esc_html__( 'Head BG Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Size chart table header background.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'  => 'table_head_text_color',
                            'label' => esc_html__( 'Head Text Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Size chart table header text color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'  => 'table_even_row_bg_color',
                            'label' => esc_html__( 'Even Row BG Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Size chart table even row background color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'  => 'table_even_row_text_color',
                            'label' => esc_html__( 'Even Row Text Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Size chart table even row text color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'  => 'table_odd_row_bg_color',
                            'label' => esc_html__( 'Odd Row BG Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Size chart table odd row background color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'  => 'table_odd_row_text_color',
                            'label' => esc_html__( 'Odd Row Text Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Size chart table odd row text color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),
                        
                    )
                
                ),

                array(
                    'name'     => 'swatch_settings',
                    'label'    => esc_html__( 'Variation Swatches', 'woolentor' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_swatch_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/variation-swatches/'),
                    'setting_fields' => array(

                        array(
                            'name'    => 'enable',
                            'label'   => esc_html__( 'Enable / Disable', 'woolentor' ),
                            'desc'    => esc_html__( 'Enable / disable this module.', 'woolentor' ),
                            'type'    => 'checkbox',
                            'default' => 'off',
                            'class'   => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'       => 'sp_enable_swatches',
                            'label'      => esc_html__( 'Enable On Product Details Page', 'woolentor' ),
                            'desc'       => esc_html__( 'Enable Swatches for the Product Details pages.', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),

                        array(
                            'name'       => 'pl_enable_swatches',
                            'label'      => esc_html__( 'Enable On Shop / Archive Page', 'woolentor' ),
                            'desc'       => esc_html__( 'Enable Swatches for the products in the Shop / Archive Pages', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'off',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),

                        array(
                            'name'       => 'heading_1',
                            'type'       => 'title',
                            'headding'   => esc_html__( 'General Options', 'woolentor' ),
                            'size'       => 'woolentor_style_seperator',
                            'condition'  => array('enable', '==', '1')
                        ),
        
                        array(
                            'name'       => 'auto_convert_dropdowns_to_label',
                            'label'      => esc_html__( 'Auto Convert Dropdowns To Label', 'woolentor' ),
                            'desc'       => esc_html__( 'Automatically convert dropdowns to "label swatch" by default.', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),

                        array(
                            'name'       => 'auto_convert_dropdowns_to_image',
                            'label'      => esc_html__( 'Auto Convert Dropdowns To Image', 'woolentor' ),
                            'desc'       => esc_html__( 'Automatically convert dropdowns to "Image Swatch" if variation has an image.', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'off',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),

                        array(
                            'name'    => 'auto_convert_dropdowns_to_image_condition',
                            'label'   => esc_html__( 'Apply Auto Image For', 'woolentor' ),
                            'type'    => 'select',
                            'class'   => 'woolentor-action-field-left',
                            'default' => 'first_attribute',
                            'options' => array(
                                'first_attribute' => esc_html__('The First attribute', 'woolentor'),
                                'maximum'         => esc_html__('The attribute with Maximum variations count', 'woolentor'),
                                'minimum'         => esc_html__('The attribute with Minimum variations count', 'woolentor'),
                            ),
                            'condition'  => array('enable|auto_convert_dropdowns_to_image', '==|==', '1|1')
                        ),

                        array(
                            'name'       => 'tooltip',
                            'label'      => esc_html__( 'Tooltip', 'woolentor' ),
                            'desc'       => esc_html__( 'Enable Tooltip', 'woolentor' ),
                            'type'       => 'checkbox',
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),
                        
                        array(
                            'name'    => 'swatch_width_height',
                            'label'   => esc_html__( 'Swatch Width & Height', 'woolentor' ),
                            'desc'    => esc_html__( 'Change Swatch Width and Height From Here.', 'woolentor' ),
                            'type'    => 'dimensions',
                            'options' => [
                                'width'   => esc_html__( 'Width', 'woolentor' ),
                                'height'  => esc_html__( 'Height', 'woolentor' ),
                                'unit'    => esc_html__( 'Unit', 'woolentor' ),
                            ],
                            'default' => array(
                                'unit' => 'px'
                            ),
                            'class'       => 'woolentor-action-field-left woolentor-dimention-field-left',
                            'condition'   => array('enable', '==', '1')
                        ),

                        array(
                            'name'    => 'tooltip_width_height',
                            'label'   => esc_html__( 'Tooltip Width', 'woolentor' ),
                            'desc'    => esc_html__( 'Change Tooltip Width From Here.', 'woolentor' ),
                            'type'    => 'dimensions',
                            'options' => [
                                'width'   => esc_html__( 'Width', 'woolentor' ),
                                'unit'    => esc_html__( 'Unit', 'woolentor' ),  
                            ],
                            'default' => array(
                                'unit' => 'px'
                            ),
                            'class'       => 'woolentor-action-field-left woolentor-dimention-field-left',
                            'condition'   => array('enable', '==', '1')
                        ),

                        array(
                            'name'       => 'show_swatch_image_in_tooltip',
                            'type'       => 'checkbox',
                            'label'      => esc_html__('Swatch Image as Tooltip', 'woolentor'),
                            'desc'       => esc_html__('If you check this options. When a watch type is "image" and has an image. The image will be shown into the tooltip.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),
                        
                        array(
                            'name'       => 'ajax_variation_threshold',
                            'type'       => 'number',
                            'label'      => esc_html__('Change AJAX Variation Threshold', 'woolentor'),
                            'placeholder'=> '30',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1'),
                            'tooltip'    => [
                                'text' => __('If a variable product has over 30 variants, WooCommerce doesn\'t allow you to show which combinations are unavailable for purchase. That\'s why customers need to check each combination to see if it is available or not. Although you can increase the threshold, keeping it at a standard value is recommended, so it doesn\'t negatively impact your website\'s performance.
                                <br/>Here "standard value" refers to the number of highest combinations you have set for one of your products.','woolentor'),
                                'placement' => 'top',
                            ],
                        ),

                        array(
                            'name'    => 'shape_style',
                            'type'    => 'select',
                            'label'   => esc_html__('Shape Style', 'woolentor'),
                            'options' => array(
                                'squared' => esc_html__('Squared', 'woolentor'),
                                'rounded' => esc_html__('Rounded', 'woolentor'),
                                'circle'  => esc_html__('Circle', 'woolentor'),
                            ),
                            'default'    => 'squared',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),

                        array(
                            'name'       => 'enable_shape_inset',
                            'type'       => 'checkbox',
                            'label'      => esc_html__('Enable Shape Inset', 'woolentor'),
                            'desc'       => esc_html__('Shape inset is the empty space arround the swatch.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),

                        array(
                            'name'       => 'show_selected_attribute_name',
                            'type'       => 'checkbox',
                            'label'      => esc_html__('Show Selected Variation Name', 'woolentor'),
                            'default'    => 'on',
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1')
                        ),

                        array(
                            'name'         => 'variation_label_separator',
                            'type'         => 'text',
                            'label'        => esc_html__('Variation Label Separator', 'woolentor'),
                            'default'      => esc_html__(' : ', 'woolentor'),
                            'class'        => 'woolentor-action-field-left',
                            'condition'    => array( 'enable|show_selected_attribute_name', '==|==', '1|1' ),
                        ),

                        array(
                            'name'  => 'disabled_attribute_type',
                            'type'  => 'select',
                            'label' => esc_html__('Disabled Attribute Type', 'woolentor'),
                            'options' => array(
                                ''                => esc_html__('Cross Sign', 'woolentor'),
                                'blur_with_cross' => esc_html__('Blur With Cross', 'woolentor'),
                                'blur'            => esc_html__('Blur', 'woolentor'),
                                'hide'            => esc_html__('Hide', 'woolentor'),
                            ),
                            'desc'       => esc_html__('Note: It will not effective when you have large number of variations but the "Ajax Variation Threshold" value is less than the number of variations.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1'),
                        ),

                        array(
                            'name'       => 'disable_out_of_stock',
                            'type'       => 'checkbox',
                            'label'      => esc_html__('Disable Variation Form for The "Out of Stock" Products', 'woolentor'),
                            'desc'       => esc_html__('If disabled, an out of stock message will be shown instead of showing the variations form / swatches.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable', '==', '1'),
                        ),

                        // Archive page options
                        array(
                            'name'      => 'heading_2',
                            'type'      => 'title',
                            'headding'  => esc_html__( 'Shop / Archive Page Swatch Options', 'woolentor' ),
                            'size'      => 'woolentor_style_seperator',
                            'condition' => array( 'enable|pl_enable_swatches', '==|==', '1|1' ),
                        ),

                        array(
                            'name'      => 'pl_show_swatches_label',
                            'type'      => 'checkbox',
                            'label'     =>  esc_html__('Show Swatches Label', 'woolentor'),
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array( 'enable|pl_enable_swatches', '==|==', '1|1' ),
                        ),

                        array(
                            'name'      => 'pl_show_clear_link',
                            'type'      => 'checkbox',
                            'label'     =>  esc_html__('Show Clear Button', 'woolentor'),
                            'class'     => 'woolentor-action-field-left',
                            'default'   => 'on',
                            'condition' => array( 'enable|pl_enable_swatches', '==|==', '1|1' ),
                        ),

                        array(
                            'name'    => 'pl_align',
                            'type'    => 'select',
                            'label'   => esc_html__('Swatches Align', 'woolentor'),
                            'options' => array(
                                'left'   => esc_html__('Left', 'woolentor'),
                                'center' => esc_html__('Center', 'woolentor'),
                                'right'  => esc_html__('Right', 'woolentor'),
                            ),
                            'default'   => 'center',
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array( 'enable|pl_enable_swatches', '==|==', '1|1' ),
                        ),

                        array(
                            'name'    => 'pl_position',
                            'type'    => 'select',
                            'label'   => esc_html__('Swatches Position', 'woolentor'),
                            'options' => array(
                                'before_title'    => esc_html__('Before Title', 'woolentor'),
                                'after_title'     => esc_html__('After Title', 'woolentor'),
                                'before_price'    => esc_html__('Before Price', 'woolentor'),
                                'after_price'     => esc_html__('After Price', 'woolentor'),
                                'custom_position' => esc_html__('Custom Position', 'woolentor'),
                                'shortcode'       => esc_html__('Use Shortcode', 'woolentor'),
                            ),
                            'default'   => 'after_title',
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array( 'enable|pl_enable_swatches', '==|==', '1|1' ),
                        ),

                        array(
                            'name'       => 'pl_custom_position_hook_name',
                            'type'       => 'text',
                            'label'      =>  esc_html__('Hook Name', 'woolentor'),
                            'desc'       =>  esc_html__('e.g: woocommerce_after_shop_loop_item_title', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable|pl_enable_swatches|pl_position', '==|==|==', '1|1|custom_position'),
                        ), 

                        array(
                            'name'       => 'pl_custom_position_hook_priority',
                            'type'       => 'text',
                            'label'      =>  esc_html__('Hook Priority', 'woolentor'),
                            'desc'       =>  esc_html__('Default: 10', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable|pl_enable_swatches|pl_position', '==|==|==', '1|1|custom_position'),
                        ), 

                        array(
                            'name'        => 'pl_product_thumbnail_selector',
                            'type'        => 'text',
                            'label'       =>  esc_html__('Product Thumbnail Selector', 'woolentor'),
                            'placeholder' => esc_html__('Example: img.attachment-woocommerce_thumbnail', 'woolentor'),
                            'class'       => 'woolentor-action-field-left',
                            'condition'   => array( 'enable|pl_enable_swatches', '==|==', '1|1' ),
                            'tooltip'     => [
                                'text' => esc_html__( 'Some themes remove the default product image. In this case, variation image will not be changed after choose a variation. Here you can place the CSS selector of the product thumbnail, so the product image will be chagned once a variation is choosen.', 'woolentor' ),
                                'placement' => 'top',
                            ],
                        ), 

                        array(
                            'name'         => 'pl_enable_ajax_add_to_cart',
                            'type'         => 'checkbox',
                            'label'        =>  esc_html__('Enable AJAX Add to Cart', 'woolentor'),
                            'class'        => 'woolentor-action-field-left',
                            'condition'    => array('enable|pl_enable_swatches', '==|==', '1|1')
                        ),

                        array(
                            'name'       => 'pl_add_to_cart_text',
                            'type'       => 'text',
                            'label'      =>  esc_html__('Add to Cart Text', 'woolentor'),
                            'desc'       =>  esc_html__('Leave it empty for default.', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable|pl_enable_swatches|pl_enable_ajax_add_to_cart', '==|==|==', '1|1|1'),
                        ),

                        array(
                            'name'       => 'pl_hide_wc_forward_button',
                            'type'       => 'checkbox',
                            'label'      =>  esc_html__('Hide "View Cart" button after Added to Cart', 'woolentor'),
                            'class'      => 'woolentor-action-field-left',
                            'condition'  => array('enable|pl_enable_swatches|pl_enable_ajax_add_to_cart', '==|==|==', '1|1|1'),
                            'tooltip'     => [
                                'text' => esc_html__('After successfully add to cart, a new button shows linked to the cart page. You can controll of that button from here. Note: If redirect option is enable from WooCommerce it will not work.', 'woolentor'),
                                'placement' => 'top',
                            ],
                        ),

                        array(
                            'name'         => 'pl_enable_cart_popup_notice',
                            'type'         => 'checkbox',
                            'label'        =>  esc_html__('Enable poupup notice after added to cart', 'woolentor'),
                            'class'        => 'woolentor-action-field-left',
                            'condition'  => array('enable|pl_enable_swatches|pl_enable_ajax_add_to_cart', '==|==|==', '1|1|1'),
                            'tooltip'     => [
                                'text' => esc_html__('After successfully add to cart, a pupup notice will be generated containing a button linked to the cart page. Note: If redirect option is enable from WooCommerce it will not work.', 'woolentor'),
                                'placement' => 'top',
                            ],
                        ),
                        

                    )

                ),

                array(
                    'name'     => 'product_filter',
                    'label'    => esc_html__( 'Product Filter', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_product_filter_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'setting_fields' => apply_filters( 'woolentor_pro_product_filter_fields', array() ),

                ),

                // order bump
                array(
                    'name'              => 'order_bump',
                    'label'             => esc_html__( 'Order Bump', 'woolentor-pro' ),
                    'type'              => 'module',
                    'default'           => 'off',
                    'section'           => 'woolentor_order_bump_settings',
                    'option_id'         => 'enable',
                    'require_settings'  => true,
                    'documentation'     => esc_url(''),
                    'setting_fields' => array(
                        array(
                            'name'      => 'enable',
                            'label'     => esc_html__( 'Enable', 'woolentor-pro' ),
                            'type'      => 'checkbox',
                            'desc'      => esc_html__( 'Enable Order Bump Module.', 'woolentor-pro' ),
                            'default'   => 'off',
                            'class'     => 'woolentor-action-field-left'
                        ),
                        array(
                            'name'      => 'enable_test_mode',
                            'label'     => esc_html__( 'Test Mode', 'woolentor-pro' ),
                            'type'      => 'checkbox',
                            'desc'      => esc_html__( 'Test mode displays order bumps only for the Administrator when enabled.', 'woolentor-pro' ),
                            'default'   => 'off',
                            'class'     => 'woolentor-action-field-left',
                            'condition'   => array( 'enable', '==', '1' ),
                        ),
                        array(
                            'name'        => 'discount_base_price',
                            'label'       => esc_html__( 'Discount Base Price', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Specify which price should be used for "Order Bump" discount calculation.', 'woolentor-pro' ),
                            'type'        => 'select',
                            'options'     => array(
                                'regular_price' => esc_html__('Regular Price', 'woolentor-pro'),
                                'sale_price'    => esc_html__('Sale Price', 'woolentor-pro'),
                            ),
                            'condition'   => array( 'enable', '==', '1' ),
                            'class'       => 'woolentor-action-field-left'
                        ),
                    )
                ),

                array(
                    'name'     => 'email_customizer',
                    'label'    => esc_html__( 'Email Customizer', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_email_customizer_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/email-customizer/'),
                    'setting_fields' => array(

                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable email customizer from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'width',
                            'label'       => esc_html__( 'Width (px)', 'woolentor-pro' ),
                            'desc'        => esc_html__( 'Insert email template width.', 'woolentor-pro' ),
                            'type'        => 'number',
                            'default'     => '600',
                            'placeholder' => '600',
                            'class'       => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'        => 'go_email_template_builder',
                            'label'       => esc_html__( 'Go Builder', 'woolentor-pro' ),
                            'html'        => wp_kses_post( '<a href="'.admin_url('edit.php?post_type=woolentor-template&template_type=emails&tabs=emails').'" target="_blank">Create your own customized Email.</a>', 'woolentor-pro' ),
                            'type'        => 'html',
                            'class'       => 'woolentor-action-field-left'
                        ),

                    )

                ),

                array(
                    'name'     => 'email_automation',
                    'label'    => esc_html__( 'Email Automation', 'woolentor-pro' ),
                    'type'     => 'module',
                    'default'  => 'off',
                    'section'  => 'woolentor_email_automation_settings',
                    'option_id'=> 'enable',
                    'require_settings'  => true,
                    'setting_fields' => array(

                        array(
                            'name'  => 'enable',
                            'label' => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'You can enable / disable email automation from here.', 'woolentor-pro' ),
                            'type'  => 'checkbox',
                            'default' => 'off',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'    => 'email_from_name',
                            'label'   => esc_html__( 'From name', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'How the sender name appears in outgoing email.', 'woolentor-pro' ),
                            'type'    => 'text',
                            'default' => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
                            'class'   => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'    => 'email_from_address',
                            'label'   => esc_html__( 'From address', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'How the sender email appears in outgoing email.', 'woolentor-pro' ),
                            'type'    => 'text',
                            'default' => get_option( 'admin_email' ),
                            'class'   => 'woolentor-action-field-left',
                        ),

                        array(
                            'name'        => 'go_email_template_builder',
                            'label'       => esc_html__( 'Go Congiration', 'woolentor-pro' ),
                            'html'        => wp_kses_post( 'Before you &nbsp;<a href="'.admin_url('edit.php?post_type=wlea-email').'" target="_blank">Configure the Email Automation</a> please make sure that you have enabled the automation and saved the change(s).', 'woolentor-pro' ),
                            'type'        => 'html',
                            'class'       => 'woolentor-action-field-left'
                        ),

                    )

                ),

                array(
                    'name'     => 'wishlist',
                    'label'    => esc_html__( 'Wishlist', 'woolentor-pro' ),
                    'type'     => 'element',
                    'default'  => 'off',
                    'documentation' => esc_url('https://woolentor.com/doc/wishlist-for-woocommerce/')
                ),

                array(
                    'name'     => 'compare',
                    'label'    => esc_html__( 'Compare', 'woolentor-pro' ),
                    'type'     => 'element',
                    'default'  => 'off',
                    'documentation' => esc_url('https://woolentor.com/doc/woocommerce-product-compare/')
                ),

                array(
                    'name'  => 'ajaxsearch',
                    'label' => esc_html__( 'AJAX Search Widget', 'woolentor-pro' ),
                    'desc'  => esc_html__( 'AJAX Search Widget', 'woolentor-pro' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'documentation' => esc_url('https://woolentor.com/doc/how-to-use-woocommerce-ajax-search/')
                ),
    
                array(
                    'name'   => 'ajaxcart_singleproduct',
                    'label'  => esc_html__( 'Single Product AJAX Add To Cart', 'woolentor-pro' ),
                    'desc'   => esc_html__( 'AJAX Add to Cart on Single Product page', 'woolentor-pro' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'documentation' => esc_url('https://woolentor.com/doc/single-product-ajax-add-to-cart/')
                ),
    
                array(
                    'name'   => 'single_product_sticky_add_to_cart',
                    'label'  => esc_html__( 'Single Product Sticky Add To Cart', 'woolentor-pro' ),
                    'desc'   => esc_html__( 'Sticky Add to Cart on Single Product page', 'woolentor-pro' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'class'  =>'single_product_sticky_add_to_cart',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/single-product-sticky-add-to-cart/'),
                    'setting_fields' => array(
                        
                        array(
                            'name'  => 'sps_add_to_cart_color',
                            'label' => esc_html__( 'Sticky cart button color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Single product sticky add to cart button color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),
            
                        array(
                            'name'  => 'sps_add_to_cart_bg_color',
                            'label' => esc_html__( 'Sticky cart button background color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Single product sticky add to cart button background color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),
            
                        array(
                            'name'  => 'sps_add_to_cart_hover_color',
                            'label' => esc_html__( 'Sticky cart button hover color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Single product sticky add to cart button hover color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),
            
                        array(
                            'name'  => 'sps_add_to_cart_bg_hover_color',
                            'label' => esc_html__( 'Sticky cart button hover background color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Single product sticky add to cart button hover background color.', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left',
                        ),
            
                        array(
                            'name'    => 'sps_add_to_cart_padding',
                            'label'   => esc_html__( 'Sticky cart button padding', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Single product sticky add to cart button padding.', 'woolentor-pro' ),
                            'type'    => 'dimensions',
                            'options' => [
                                'top'   => esc_html__( 'Top', 'woolentor-pro' ),
                                'right' => esc_html__( 'Right', 'woolentor-pro' ),
                                'bottom'=> esc_html__( 'Bottom', 'woolentor-pro' ),
                                'left'  => esc_html__( 'Left', 'woolentor-pro' ),
                                'unit'  => esc_html__( 'Unit', 'woolentor-pro' ),
                            ],
                            'class' => 'woolentor-action-field-left woolentor-dimention-field-left',
                        ),

                        array(
                            'name'    => 'sps_add_to_cart_border_radius',
                            'label'   => esc_html__( 'Sticky cart button border radius', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Single product sticky add to cart button border radius.', 'woolentor-pro' ),
                            'type'    => 'dimensions',
                            'options' => [
                                'top'   => esc_html__( 'Top', 'woolentor-pro' ),
                                'right' => esc_html__( 'Right', 'woolentor-pro' ),
                                'bottom'=> esc_html__( 'Bottom', 'woolentor-pro' ),
                                'left'  => esc_html__( 'Left', 'woolentor-pro' ),
                                'unit'  => esc_html__( 'Unit', 'woolentor-pro' ),
                            ],
                            'class' => 'woolentor-action-field-left woolentor-dimention-field-left',
                        ),

                    )
                ),

                array(
                    'name'   => 'mini_side_cart',
                    'label'  => esc_html__( 'Side Mini Cart', 'woolentor-pro' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'class'  =>'side_mini_cart',
                    'require_settings'  => true,
                    'documentation' => esc_url('https://woolentor.com/doc/side-mini-cart-for-woocommerce/'),
                    'setting_fields' => array(
                        
                        array(
                            'name'    => 'mini_cart_position',
                            'label'   => esc_html__( 'Position', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'Set the position of the Mini Cart .', 'woolentor-pro' ),
                            'type'    => 'select',
                            'default' => 'left',
                            'options' => array(
                                'left'   => esc_html__( 'Left','woolentor-pro' ),
                                'right'  => esc_html__( 'Right','woolentor-pro' ),
                            ),
                            'class' => 'woolentor-action-field-left',
                        ),
            
                        array(
                            'name'    => 'mini_cart_icon',
                            'label'   => esc_html__( 'Icon', 'woolentor-pro' ),
                            'desc'    => esc_html__( 'You can manage the side mini cart toggler icon.', 'woolentor-pro' ),
                            'type'    => 'text',
                            'default' => 'sli sli-basket-loaded',
                            'class'   => 'woolentor_icon_picker woolentor-action-field-left'
                        ),
            
                        array(
                            'name'  => 'mini_cart_icon_color',
                            'label' => esc_html__( 'Icon color', 'woolentor' ),
                            'desc'  => esc_html__( 'Side mini cart icon color', 'woolentor' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),
            
                        array(
                            'name'  => 'mini_cart_icon_bg_color',
                            'label' => esc_html__( 'Icon background color', 'woolentor' ),
                            'desc'  => esc_html__( 'Side mini cart icon background color', 'woolentor' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),
            
                        array(
                            'name'  => 'mini_cart_icon_border_color',
                            'label' => esc_html__( 'Icon border color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Side mini cart icon border color', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),
            
                        array(
                            'name'  => 'mini_cart_counter_color',
                            'label' => esc_html__( 'Counter color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Side mini cart counter color', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),
            
                        array(
                            'name'  => 'mini_cart_counter_bg_color',
                            'label' => esc_html__( 'Counter background color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Side mini cart counter background color', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'      => 'mini_cart_button_heading',
                            'headding'  => esc_html__( 'Buttons', 'woolentor-pro' ),
                            'type'      => 'title'
                        ),

                        array(
                            'name'  => 'mini_cart_buttons_color',
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Side mini cart buttons color', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),
                        array(
                            'name'  => 'mini_cart_buttons_bg_color',
                            'label' => esc_html__( 'Background color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Side mini cart buttons background color', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),

                        array(
                            'name'  => 'mini_cart_buttons_hover_color',
                            'label' => esc_html__( 'Hover color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Side mini cart buttons hover color', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),
                        array(
                            'name'  => 'mini_cart_buttons_hover_bg_color',
                            'label' => esc_html__( 'Hover background color', 'woolentor-pro' ),
                            'desc'  => esc_html__( 'Side mini cart buttons hover background color', 'woolentor-pro' ),
                            'type'  => 'color',
                            'class' => 'woolentor-action-field-left'
                        ),

                    )
                ),

                array(
                    'name'   => 'redirect_add_to_cart',
                    'label'  => esc_html__( 'Redirect to Checkout', 'woolentor-pro' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'documentation' => esc_url('https://woolentor.com/doc/redirect-to-checkout/')
                ),
    
                array(
                    'name'   => 'multi_step_checkout',
                    'label'  => esc_html__( 'Multi Step Checkout', 'woolentor-pro' ),
                    'type'   => 'element',
                    'default'=> 'off',
                    'documentation' => esc_url('https://woolentor.com/doc/woocommerce-multi-step-checkout/')
                ),

            ),

            'others' => array(

                array(
                    'name'  => 'loadproductlimit',
                    'label' => esc_html__( 'Load Products in Elementor Widget', 'woolentor-pro' ),
                    'desc'  => esc_html__( 'Set the number of products to load in Elementor Widgets.', 'woolentor-pro' ),
                    'min'               => 1,
                    'max'               => 100,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '20',
                    'sanitize_callback' => 'floatval'
                )

            ),

        );

        // Post Duplicator Condition
        if( !is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php') ){
            
            $post_types = woolentor_get_post_types( array('defaultadd'=>'all') );
            if ( did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' ) ) {
                $post_types['elementor_library'] = esc_html__( 'Templates', 'woolentor' );
            }

            $fields['woolentor_others_tabs']['modules'][] = [
                'name'  => 'postduplicator',
                'label'  => esc_html__( 'Post Duplicator', 'woolentor-pro' ),
                'type'  => 'element',
                'default'=>'off',
                'require_settings'  => true,
                'documentation' => esc_url('https://woolentor.com/doc/duplicate-woocommerce-product/'),
                'setting_fields' => array(
                    
                    array(
                        'name'    => 'postduplicate_condition',
                        'label'   => esc_html__( 'Post Duplicator Condition', 'woolentor' ),
                        'desc'    => esc_html__( 'You can enable duplicator for individual post.', 'woolentor' ),
                        'type'    => 'multiselect',
                        'default' => '',
                        'options' => $post_types
                    )

                )
            ];

        }

        // FlashSale Addons
        if( woolentor_get_option('enable', 'woolentor_flash_sale_settings') == 'on' ){
            $fields['woolentor_elements_tabs'][] = [
                'name'    => 'product_flash_sale',
                'label'   => esc_html__( 'Product Flash Sale', 'woolentor' ),
                'type'    => 'element',
                'default' => 'on'
            ];
        }

        // Wishsuite Addons
        if( class_exists('WishSuite_Base') || class_exists('Woolentor_WishSuite_Base') ){
            $fields['woolentor_elements_tabs'][] = [
                'name'      => 'wb_wishsuite_table',
                'label'     => esc_html__( 'WishSuite Table', 'woolentor' ),
                'type'      => 'element',
                'default'   => 'on',
            ];
            $fields['woolentor_elements_tabs'][] = [
                'name'      => 'wb_wishsuite_counter',
                'label'     => esc_html__( 'WishSuite Counter', 'woolentor' ),
                'type'      => 'element',
                'default'   => 'on',
            ];
        }

        // Ever Compare Addons
        if( class_exists('Ever_Compare') || class_exists('Woolentor_Ever_Compare') ){
            $fields['woolentor_elements_tabs'][] = [
                'name'      => 'wb_ever_compare_table',
                'label'     => esc_html__( 'Ever Compare', 'woolentor' ),
                'type'      => 'element',
                'default'   => 'on',
            ];
        }

        // JustTable Addons
        if( is_plugin_active('just-tables/just-tables.php') || is_plugin_active('just-tables-pro/just-tables-pro.php') ){
            $fields['woolentor_elements_tabs'][] = [
                'name'   => 'wb_just_table',
                'label'  => esc_html__( 'JustTable', 'woolentor' ),
                'type'   => 'element',
                'default' => 'on'
            ];
        }

        // whols Addons
        if( is_plugin_active('whols/whols.php') || is_plugin_active('whols-pro/whols-pro.php') ){
            $fields['woolentor_elements_tabs'][] = [
                'name'   => 'wb_whols',
                'label'  => esc_html__( 'Whols', 'woolentor' ),
                'type'   => 'element',
                'default' => 'on'
            ];
        }

        // Multicurrency Addons
        if( is_plugin_active('wc-multi-currency/wcmilticurrency.php') || is_plugin_active('multicurrencypro/multicurrencypro.php') ){
            $fields['woolentor_elements_tabs'][] = [
                'name'   => 'wb_wc_multicurrency',
                'label'  => esc_html__( 'Multi Currency', 'woolentor' ),
                'type'   => 'element',
                'default' => 'on'
            ];
        }

        return $fields;

    }

     /**
     * [elements_tabs_admin_fields] Elements tabs admin fields
     * @return [array]
     */
    public function elements_tabs_admin_fields( $fields ){
        $fields = array_merge( $fields, array(
            array(
                'name'      => 'general_widget_heading',
                'headding'  => esc_html__( 'General', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'product_tabs',
                'label' => esc_html__( 'Product Tab', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'universal_product',
                'label' => esc_html__( 'Universal Product', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'product_curvy',
                'label' => esc_html__( 'WL: Product Curvy', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'product_image_accordion',
                'label' => esc_html__( 'WL: Product Image Accordion', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'product_accordion',
                'label' => esc_html__( 'WL: Product Accordion', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'add_banner',
                'label' => esc_html__( 'Ads Banner', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'special_day_offer',
                'label' => esc_html__( 'Special Day Offer', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_customer_review',
                'label' => esc_html__( 'Customer Review', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_image_marker',
                'label' => esc_html__( 'Image Marker', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_category',
                'label' => esc_html__( 'Category List', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_category_grid',
                'label' => esc_html__( 'Category Grid', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_onepage_slider',
                'label' => esc_html__( 'One page slider', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_testimonial',
                'label' => esc_html__( 'Testimonial', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_grid',
                'label' => esc_html__( 'Product Grid', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_recently_viewed_products',
                'label' => esc_html__( 'Recently Viewed Products', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_expanding_grid',
                'label' => esc_html__( 'Product Expanding Grid', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_filterable_grid',
                'label' => esc_html__( 'Product Filterable Grid', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_store_features',
                'label' => esc_html__( 'Store Features', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_faq',
                'label' => esc_html__( 'FAQ', 'woolentor' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_brand',
                'label' => esc_html__( 'Brand Logo', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_template_selector',
                'label' => esc_html__( 'Template Selector', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'      => 'archive_widget_heading',
                'headding'  => esc_html__( 'Shop / Archive', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'wb_archive_product',
                'label' => esc_html__( 'Product Archive (Default)', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_custom_archive_layout',
                'label' => esc_html__( 'Product Archive Layout (Custom)', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_archive_result_count',
                'label' => esc_html__( 'Archive Result Count', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_archive_catalog_ordering',
                'label' => esc_html__( 'Archive Catalog Ordering', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_archive_title',
                'label' => esc_html__( 'Archive Title', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_filter',
                'label' => esc_html__( 'Product Filter', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_horizontal_filter',
                'label' => esc_html__( 'Product Horizontal Filter', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_advance_product_filter',
                'label' => esc_html__( 'Advanced Product Filter', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'      => 'single_widget_heading',
                'headding'  => esc_html__( 'Single Product', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'wb_product_title',
                'label' => esc_html__( 'Product Title', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_add_to_cart',
                'label' => esc_html__( 'Add to Cart Button', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_breadcrumbs',
                'label' => esc_html__( 'Breadcrumbs', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_additional_information',
                'label' => esc_html__( 'Additional Information', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_data_tab',
                'label' => esc_html__( 'Product data Tab', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_related',
                'label' => esc_html__( 'Related Product', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_related_product',
                'label' => esc_html__( 'Related Product..( Custom )', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_description',
                'label' => esc_html__( 'Product Description', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_short_description',
                'label' => esc_html__( 'Product Short Description', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_price',
                'label' => esc_html__( 'Product Price', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_rating',
                'label' => esc_html__( 'Product Rating', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_reviews',
                'label' => esc_html__( 'Product Reviews', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_image',
                'label' => esc_html__( 'Product Image', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_advance_thumbnails',
                'label' => __( 'Advance Product Image', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),
            
            array(
                'name'  => 'wl_product_advance_thumbnails_zoom',
                'label' => __( 'Product Image With Zoom', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_video_gallery',
                'label' => esc_html__( 'Product Video Gallery', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_upsell',
                'label' => esc_html__( 'Product Upsell', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_product_upsell_custom',
                'label' => esc_html__( 'Upsell Product..( Custom )', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_stock',
                'label' => esc_html__( 'Product Stock Status', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_meta',
                'label' => esc_html__( 'Product Meta Info', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_sku',
                'label' => esc_html__( 'Product SKU', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_tags',
                'label' => esc_html__( 'Product Tags', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_categories',
                'label' => esc_html__( 'Product Categories', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_social_shere',
                'label' => esc_html__( 'Product Social Share', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_stock_progress_bar',
                'label' => esc_html__( 'Stock Progressbar', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_single_product_sale_schedule',
                'label' => esc_html__( 'Product Sale Schedule', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_call_for_price',
                'label' => esc_html__( 'Call for Price', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_suggest_price',
                'label' => esc_html__( 'Suggest Price', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wb_product_qr_code',
                'label' => esc_html__( 'QR Code', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_single_pdoduct_navigation',
                'label' => __( 'Product Navigation', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'      => 'cart_widget_heading',
                'headding'  => esc_html__( 'Cart', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'wl_cart_table',
                'label' => esc_html__( 'Product Cart Table', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_cart_table_list',
                'label' => esc_html__( 'Product Cart Table (List Style)', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_cart_total',
                'label' => esc_html__( 'Product Cart Total', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_cartempty_shopredirect',
                'label' => esc_html__( 'Return To Shop Button', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_cross_sell',
                'label' => esc_html__( 'Product Cross Sell', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_cross_sell_custom',
                'label' => esc_html__( 'Cross Sell Product..( Custom )', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_cartempty_message',
                'label' => esc_html__( 'Empty Cart Message', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'      => 'checkout_widget_heading',
                'headding'  => esc_html__( 'Checkout', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'wl_checkout_billing',
                'label' => esc_html__( 'Checkout Billing Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_checkout_shipping_form',
                'label' => esc_html__( 'Checkout Shipping Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_checkout_shipping_method',
                'label' => esc_html__( 'Checkout Shipping Method', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_checkout_additional_form',
                'label' => esc_html__( 'Checkout Additional..', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_checkout_payment',
                'label' => esc_html__( 'Checkout Payment', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_checkout_coupon_form',
                'label' => esc_html__( 'Checkout Coupon Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_checkout_login_form',
                'label' => esc_html__( 'Checkout Login Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_order_review',
                'label' => esc_html__( 'Checkout Order Review', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'      => 'myaccount_widget_heading',
                'headding'  => esc_html__( 'My Account', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'wl_myaccount_account',
                'label' => esc_html__( 'My Account', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_navigation',
                'label' => esc_html__( 'My Account Navigation', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_dashboard',
                'label' => esc_html__( 'My Account Dashboard', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_download',
                'label' => esc_html__( 'My Account Download', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_edit_account',
                'label' => esc_html__( 'My Account Edit', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_address',
                'label' => esc_html__( 'My Account Address', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_login_form',
                'label' => esc_html__( 'Login Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_register_form',
                'label' => esc_html__( 'Registration Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_logout',
                'label' => esc_html__( 'My Account Logout', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_order',
                'label' => esc_html__( 'My Account Order', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_lostpassword',
                'label' => esc_html__( 'Lost Password Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_myaccount_resetpassword',
                'label' => esc_html__( 'Reset Password Form', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'      => 'thankyou_widget_heading',
                'headding'  => esc_html__( 'Thank You', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'wl_thankyou_order',
                'label' => esc_html__( 'Thank You Order', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_thankyou_customer_address_details',
                'label' => esc_html__( 'Thank You Cus.. Address', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_thankyou_order_details',
                'label' => esc_html__( 'Thank You Order Details', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            )

        ) );

        return $fields;
    }


     /**
     * [elements_tabs_additional_widget_admin_fields] Elements tabs admin fields
     * @return [array]
     */
    public function elements_tabs_additional_widget_admin_fields( $fields ){
        $fields = array_merge( $fields, array(
            array(
                'name'      => 'additional_widget_heading',
                'headding'  => esc_html__( 'Additional', 'woolentor-pro' ),
                'type'      => 'title',
                'class'     => 'woolentor_heading_style_two'
            ),

            array(
                'name'  => 'wl_quickview_product_image',
                'label' => esc_html__( 'Quick view .. image', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            ),

            array(
                'name'  => 'wl_mini_cart',
                'label' => esc_html__( 'Mini Cart', 'woolentor-pro' ),
                'type'  => 'element',
                'default' => 'on'
            )
        ) );

        return $fields;
    }

     /**
     * [template_menu_navs] Admin Post Type tabs
     * @return [array]
     */
    public function template_menu_navs( $navs ){

        $tabs = [
			'cart' => [
				'label'		=>__('Cart','woolentor'),
				'submenu' 	=>[
					'emptycart' => [
						'label'	=>__('Empty Cart','woolentor-pro')
					],
					'minicart' => [
						'label'		=> __('Side Mini Cart' ,'woolentor-pro')
					],
				]
			],
			'checkout' => [
				'label'	=>__('Checkout','woolentor-pro'),
				'submenu' => [
					'checkouttop' => [
						'label'	=>__('Checkout Top','woolentor-pro')
					],
				]
			],
			'thankyou' => [
				'label'	=>__('Thank You','woolentor')
			],
			'myaccount' => [
				'label'	  =>__('My Account','woolentor'),
				'submenu' => [
					'myaccountlogin' => [
						'label'	=> __('Login / Register','woolentor-pro')
					],
					'dashboard' => [
						'label'	=> __('Dashboard','woolentor-pro')
					],
					'orders' => [
						'label'	=> __('Orders','woolentor-pro')
					],
					'downloads' => [
						'label'	=> __('Downloads','woolentor-pro')
					],
					'edit-address' => [
						'label'	=> __('Address','woolentor-pro')
					],
					'edit-account' => [
						'label'	=> __('Account Details','woolentor-pro')
					],
					'lost-password' => [
						'label'	=> __('Lost Password','woolentor-pro')
					],
					'reset-password' => [
						'label'	=> __('Reset Password','woolentor-pro')
					],
				]
			]
			
		];

        if ( ! did_action( 'elementor/loaded' ) ) {
            unset( $tabs['cart']['submenu']['minicart'] );
            unset( $tabs['checkout']['submenu']['checkouttop'] );
        }

        if ( did_action( 'elementor/loaded' ) ) {
            $tabs['quickview'] = [
				'label'	=> __('QuickView','woolentor-pro')
			];
        }

        $navs = array_merge( $navs, $tabs );
        return $navs;

    }

     /**
     * [template_type] Template types
     * @return [array]
     */
    function template_type( $types ){

        $template_type = [
			'cart' => [
				'label'		=>__('Cart','woolentor'),
				'optionkey'	=>'productcartpage'
			],
			'emptycart' => [
				'label'		=>__('Empty Cart','woolentor'),
				'optionkey'	=>'productemptycartpage'
			],
			'checkout' => [
				'label'		=>__('Checkout','woolentor'),
				'optionkey'	=>'productcheckoutpage'
			],
			'checkouttop' => [
				'label'		=>__('Checkout Top','woolentor'),
				'optionkey'	=>'productcheckouttoppage'
			],
			'thankyou' => [
				'label'		=>__('Thank You','woolentor'),
				'optionkey'	=>'productthankyoupage'
			],
			'myaccount' => [
				'label'		=>__('My Account','woolentor'),
				'optionkey'	=>'productmyaccountpage'
			],
			'myaccountlogin' => [
				'label'		=> __('My Account Login / Register','woolentor'),
				'optionkey'	=> 'productmyaccountloginpage'
			],
            'dashboard' => [
                'label'	    => __('My Account Dashboard','woolentor-pro'),
                'optionkey'	=> 'dashboard'
            ],
            'orders' => [
                'label'	=> __('My Account Orders','woolentor-pro'),
                'optionkey'	=> 'orders'
            ],
            'downloads' => [
                'label'	=> __('My Account Downloads','woolentor-pro'),
                'optionkey'	=> 'downloads'
            ],
            'edit-address' => [
                'label'	=> __('My Account Address','woolentor-pro'),
                'optionkey'	=> 'edit-address'
            ],
            'edit-account' => [
                'label'	=> __('My Account Details','woolentor-pro'),
                'optionkey'	=> 'edit-account'
            ],
            'lost-password' => [
                'label'	=> __('My Account Lost Password','woolentor-pro'),
                'optionkey'	=> 'lost-password'
            ],
            'reset-password' => [
                'label'	=> __('My Account Reset Password','woolentor-pro'),
                'optionkey'	=> 'reset-password'
            ]
		];

        if ( ! did_action( 'elementor/loaded' ) ) {
            unset( $template_type['checkouttop'] );
        }

        if ( did_action( 'elementor/loaded' ) ) {
            $template_type['quickview'] = [
				'label'		=> __('QuickView','woolentor'),
				'optionkey'	=> 'productquickview'
			];

            $template_type['minicart'] = [
				'label'		=> __('Side Mini Cart' ,'woolentor-pro'),
				'optionkey'	=> 'mini_cart_layout'
			];
        }

        $types = array_merge( $types, $template_type );

        return $types;

    }

}

Woolentor_Admin_Fields_Pro::instance();