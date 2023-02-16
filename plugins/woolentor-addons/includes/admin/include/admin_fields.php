<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Admin_Fields {

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

    /**
     * [field_sections] Admin Fields section
     * @return [array] section 
     */
    public function field_sections(){

        $sections = array(

            array(
                'id'    => 'woolentor_general_tabs',
                'title' => esc_html__( 'General', 'woolentor' ),
                'icon'  => 'dashicons-admin-home'
            ),

            array(
                'id'    => 'woolentor_woo_template_tabs',
                'title' => esc_html__( 'WooCommerce Template', 'woolentor' ),
                'icon'  => 'wli-store'
            ),

            array(
                'id'    => 'woolentor_gutenberg_tabs',
                'title' => esc_html__( 'Gutenberg', 'woolentor' ),
                'icon'  => 'wli-cog'
            ),

            array(
                'id'    => 'woolentor_elements_tabs',
                'title' => esc_html__( 'Elements', 'woolentor' ),
                'icon'  => 'wli-images'
            ),

            array(
                'id'    => 'woolentor_others_tabs',
                'title' => esc_html__( 'Modules', 'woolentor' ),
                'icon'  => 'wli-grid'
            ),

            array(
                'id'    => 'woolentor_style_tabs',
                'title' => esc_html__( 'Style', 'woolentor' ),
                'icon'  => 'wli-tag'
            ),

            array(
                'id'    => 'woolentor_extension_tabs',
                'title' => esc_html__( 'Extensions', 'woolentor' ),
                'icon'  => 'wli-masonry'
            ),

        );
        return apply_filters( 'woolentor_admin_fields_sections', $sections );

    }

    /**
     * [fields] Admin Fields
     * @return [array] fields 
     */
    public function fields(){

        $settings_fields = array(

            'woolentor_woo_template_tabs' => array(

                array(
                    'name'    => 'enablecustomlayout',
                    'label'   => esc_html__( 'Enable / Disable Template Builder', 'woolentor' ),
                    'desc'    => esc_html__( 'You can enable/disable template builder from here.', 'woolentor' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'  => 'shoppageproductlimit',
                    'label' => esc_html__( 'Product Limit', 'woolentor' ),
                    'desc'  => esc_html__( 'You can handle the product limit for the Shop page', 'woolentor' ),
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
                    'label'   => esc_html__( 'Single Product Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a custom template for the product details page layout', 'woolentor' ),
                    'type'    => 'selectgroup',
                    'default' => '0',
                    'options' => [
                        'group'=>[
                            'woolentor' => [
                                'label' => __( 'WooLentor', 'woolentor' ),
                                'options' => woolentor_wltemplate_list( array('single') )
                            ],
                            'elementor' => [
                                'label' => __( 'Elementor', 'woolentor' ),
                                'options' => woolentor_elementor_template()
                            ]
                        ]
                    ],
                   'condition' => array( 'enablecustomlayout', '==', 'true' ),
                ),

                array(
                    'name'    => 'productarchivepage',
                    'label'   => esc_html__( 'Product Shop Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a custom template for the Shop page layout', 'woolentor' ),
                    'type'    => 'selectgroup',
                    'default' => '0',
                    'options' => [
                        'group'=>[
                            'woolentor' => [
                                'label' => __( 'WooLentor', 'woolentor' ),
                                'options' => woolentor_wltemplate_list( array('shop','archive') )
                            ],
                            'elementor' => [
                                'label' => __( 'Elementor', 'woolentor' ),
                                'options' => woolentor_elementor_template()
                            ]
                        ]
                    ],
                   'condition' => array( 'enablecustomlayout', '==', 'true' ),
                ),

                array(
                    'name'    => 'productallarchivepage',
                    'label'   => esc_html__( 'Product Archive Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a custom template for the Product Archive page layout', 'woolentor' ),
                    'type'    => 'selectgroup',
                    'default' => '0',
                    'options' => [
                        'group'=>[
                            'woolentor' => [
                                'label' => __( 'WooLentor', 'woolentor' ),
                                'options' => woolentor_wltemplate_list( array('shop','archive') )
                            ],
                            'elementor' => [
                                'label' => __( 'Elementor', 'woolentor' ),
                                'options' => woolentor_elementor_template()
                            ]
                        ]
                    ],
                   'condition' => array( 'enablecustomlayout', '==', 'true' ),
                ),

                array(
                    'name'    => 'productcartpagep',
                    'label'   => esc_html__( 'Cart Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a template for the Cart page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select' => esc_html__('Select a template for the cart page layout','woolentor'),
                    ),
                   'condition' => array( 'enablecustomlayout', '==', 'true' ),
                    'is_pro'  => true,
                ),

                array(
                    'name'    => 'productcheckoutpagep',
                    'label'   => esc_html__( 'Checkout Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'You can select a template for the Checkout page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select' => esc_html__('Select a template for the Checkout page layout','woolentor'),
                    ),
                   'condition' => array( 'enablecustomlayout', '==', 'true' ),
                    'is_pro'  => true,
                ),

                array(
                    'name'    => 'productthankyoupagep',
                    'label'   => esc_html__( 'Thank You Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the Thank you page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select' => esc_html__('Select a template for the Thank you page layout','woolentor'),
                    ),
                    'condition' => array( 'enablecustomlayout', '==', 'true' ),
                    'is_pro'    => true,
                ),

                array(
                    'name'    => 'productmyaccountpagep',
                    'label'   => esc_html__( 'My Account Page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the My Account page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select' => esc_html__('Select a template for the My account page layout','woolentor'),
                    ),
                   'condition' => array( 'enablecustomlayout', '==', 'true' ),
                    'is_pro'  => true,
                ),

                array(
                    'name'    => 'productmyaccountloginpagep',
                    'label'   => esc_html__( 'My Account Login page Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the Login page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select' => esc_html__('Select a template for the My account login page layout','woolentor'),
                    ),
                    'condition' => array( 'enablecustomlayout', '==', 'true' ),
                    'is_pro'  => true,
                ),

                array(
                    'name'    => 'productquickviewp',
                    'label'   => esc_html__( 'Quick View Template', 'woolentor' ),
                    'desc'    => esc_html__( 'Select a template for the product\'s quick view layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select' => esc_html__('Select a template for the Quick view layout','woolentor'),
                    ),
                    'condition' => array( 'enablecustomlayout', '==', 'true' ),
                    'is_pro'  => true,
                ),

            ),

            'woolentor_gutenberg_tabs' => array(

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
                        'name'    => 'product_grid',
                        'label'   => esc_html__( 'Product Grid', 'woolentor' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
                    ),
    
                    array(
                        'name'    => 'customer_review',
                        'label'   => esc_html__( 'Customer Review', 'woolentor' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'is_pro'  => true,
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
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_total',
                        'label' => esc_html__( 'Product Cart Total', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'corss_sell',
                        'label' => esc_html__( 'Product Cross Sell', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'return_to_shop',
                        'label' => esc_html__( 'Return To Shop Button', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'cart_empty_message',
                        'label' => esc_html__( 'Empty Cart Message', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
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
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_shipping_form',
                        'label' => esc_html__( 'Checkout Shipping Form', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_additional_form',
                        'label' => esc_html__( 'Checkout Additional..', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_coupon_form',
                        'label' => esc_html__( 'Checkout Coupon Form', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_payment',
                        'label' => esc_html__( 'Checkout Payment Method', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_order_review',
                        'label' => esc_html__( 'Checkout Order Review', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'checkout_login_form',
                        'label' => esc_html__( 'Checkout Login Form', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
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
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_navigation',
                        'label' => esc_html__( 'My Account Navigation', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_dashboard',
                        'label' => esc_html__( 'My Account Dashboard', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_download',
                        'label' => esc_html__( 'My Account Download', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_edit',
                        'label' => esc_html__( 'My Account Edit', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_address',
                        'label' => esc_html__( 'My Account Address', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_order',
                        'label' => esc_html__( 'My Account Order', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_logout',
                        'label' => esc_html__( 'My Account Logout', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_login_form',
                        'label' => esc_html__( 'Login Form', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_registration_form',
                        'label' => esc_html__( 'Registration Form', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_lost_password',
                        'label' => esc_html__( 'Lost Password Form', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'my_account_reset_password',
                        'label' => esc_html__( 'Reset Password Form', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
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
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_address_details',
                        'label' => esc_html__( 'Thank You Address', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),
                    array(
                        'name'  => 'thankyou_order_details',
                        'label' => esc_html__( 'Thank You Order Details', 'woolentor' ),
                        'type'  => 'element',
                        'default' => 'off',
                        'is_pro' => true,
                    ),

                )

            ),

            'woolentor_elements_tabs' => array(

                array(
                    'name'      => 'general_widget_heading',
                    'headding'  => esc_html__( 'General', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'              => 'product_tabs',
                    'label'             => __( 'Product Tab', 'woolentor' ),
                    'type'              => 'element',
                    'default'           => 'on',
                    // 'preview'           => '#',
                    // 'documentation'     => '#',
                    // 'require_settings'  => true,
                    // 'is_pro'            => true
                ),

                array(
                    'name'    => 'universal_product',
                    'label'   => esc_html__( 'Universal Product', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_curvy',
                    'label'   => esc_html__( 'WL: Product Curvy', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'    => 'product_image_accordion',
                    'label'   => esc_html__( 'WL: Product Image Accordion', 'woolentor' ),
                    'type'    => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'product_accordion',
                    'label' => esc_html__( 'WL: Product Accordion', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_recently_viewed_products',
                    'label' => esc_html__( 'Recently Viewed Products', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'add_banner',
                    'label' => esc_html__( 'Ads Banner', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'special_day_offer',
                    'label' => esc_html__( 'Special Day Offer', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_customer_review',
                    'label' => esc_html__( 'Customer Review', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_image_marker',
                    'label' => esc_html__( 'Image Marker', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_category',
                    'label' => esc_html__( 'Category List', 'woolentor' ),
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
                    'label' => esc_html__( 'Brand Logo', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_product_expanding_gridp',
                    'label' => esc_html__( 'Product Expanding Grid', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_product_filterable_gridp',
                    'label' => esc_html__( 'Product Filterable Grid', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_product_pgridp',
                    'label' => esc_html__( 'Product Grid', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'      => 'archive_widget_heading',
                    'headding'  => esc_html__( 'Shop / Archive', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'  => 'wb_archive_product',
                    'label' => esc_html__( 'Product Archive', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_archive_result_count',
                    'label' => esc_html__( 'Archive Result Count', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
    
                array(
                    'name'  => 'wb_archive_catalog_ordering',
                    'label' => esc_html__( 'Archive Catalog Ordering', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_archive_title',
                    'label' => esc_html__( 'Archive Title', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_product_filter',
                    'label' => esc_html__( 'Product Filter', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_product_horizontal_filter',
                    'label' => esc_html__( 'Product Horizontal Filter', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_advance_product_filterp',
                    'label' => esc_html__( 'Advanced Product Filter', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_custom_archive_layoutp',
                    'label' => esc_html__( 'Archive Layout (Custom)', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'      => 'single_widget_heading',
                    'headding'  => esc_html__( 'Single Product', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'  => 'wb_product_title',
                    'label' => esc_html__( 'Product Title', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_add_to_cart',
                    'label' => esc_html__( 'Add to Cart Button', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_breadcrumbs',
                    'label' => esc_html__( 'Breadcrumbs', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_additional_information',
                    'label' => esc_html__( 'Additional Information', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_data_tab',
                    'label' => esc_html__( 'Product Data Tab', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_related',
                    'label' => esc_html__( 'Related Product', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_description',
                    'label' => esc_html__( 'Product Description', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_short_description',
                    'label' => esc_html__( 'Product Short Description', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_price',
                    'label' => esc_html__( 'Product Price', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_rating',
                    'label' => esc_html__( 'Product Rating', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_reviews',
                    'label' => esc_html__( 'Product Reviews', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_image',
                    'label' => esc_html__( 'Product Image', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_product_video_gallery',
                    'label' => esc_html__( 'Product Video Gallery', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_upsell',
                    'label' => esc_html__( 'Product Upsell', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_stock',
                    'label' => esc_html__( 'Product Stock Status', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_meta',
                    'label' => esc_html__( 'Product Meta Info', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_sku',
                    'label' => esc_html__( 'Product SKU', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
    
                array(
                    'name'  => 'wb_product_tags',
                    'label' => esc_html__( 'Product Tags', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),
    
                array(
                    'name'  => 'wb_product_categories',
                    'label' => esc_html__( 'Product Categories', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_call_for_price',
                    'label' => esc_html__( 'Call for Price', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_suggest_price',
                    'label' => esc_html__( 'Suggest Price', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wb_product_qr_code',
                    'label' => esc_html__( 'QR Code', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'on'
                ),

                array(
                    'name'  => 'wl_product_advance_thumbnailsp',
                    'label' => esc_html__( 'Advance Product Image', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_product_advance_thumbnails_zoomp',
                    'label' => esc_html__( 'Product Zoom', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_social_sherep',
                    'label' => esc_html__( 'Product Social Share', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_stock_progress_barp',
                    'label' => esc_html__( 'Stock Progress Bar', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),
                array(
                    'name'  => 'wl_single_product_sale_schedulep',
                    'label' => esc_html__( 'Product Sale Schedule', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_related_productp',
                    'label' => esc_html__( 'Related Pro..( Custom )', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_product_upsell_customp',
                    'label' => esc_html__( 'Upsell Pro..( Custom )', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_single_pdoduct_navigation',
                    'label' => __( 'Product Navigation', 'woolentor-pro' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro'=> true,
                ),

                array(
                    'name'      => 'cart_widget_heading',
                    'headding'  => esc_html__( 'Cart', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'  => 'wl_cart_tablep',
                    'label' => esc_html__( 'Product Cart Table', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_cart_totalp',
                    'label' => esc_html__( 'Product Cart Total', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_cartempty_messagep',
                    'label' => esc_html__( 'Empty Cart Message', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_cartempty_shopredirectp',
                    'label' => esc_html__( 'Empty Cart Re.. Button', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_cross_sellp',
                    'label' => esc_html__( 'Product Cross Sell', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_cross_sell_customp',
                    'label' => esc_html__( 'Cross Sell ..( Custom )', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'      => 'checkout_widget_heading',
                    'headding'  => esc_html__( 'Checkout', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'  => 'wl_checkout_billingp',
                    'label' => esc_html__( 'Checkout Billing Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_checkout_shipping_formp',
                    'label' => esc_html__( 'Checkout Shipping Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_checkout_additional_formp',
                    'label' => esc_html__( 'Checkout Additional..', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_checkout_paymentp',
                    'label' => esc_html__( 'Checkout Payment', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_checkout_coupon_formp',
                    'label' => esc_html__( 'Checkout Co.. Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_checkout_login_formp',
                    'label' => esc_html__( 'Checkout lo.. Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_order_reviewp',
                    'label' => esc_html__( 'Checkout Order Review', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'      => 'myaccount_widget_heading',
                    'headding'  => esc_html__( 'My Account', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'  => 'wl_myaccount_accountp',
                    'label' => esc_html__( 'My Account', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_navigationp',
                    'label' => esc_html__( 'My Account Navigation', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_dashboardp',
                    'label' => esc_html__( 'My Account Dashboard', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_downloadp',
                    'label' => esc_html__( 'My Account Download', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_edit_accountp',
                    'label' => esc_html__( 'My Account Edit', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_addressp',
                    'label' => esc_html__( 'My Account Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_login_formp',
                    'label' => esc_html__( 'Login Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_register_formp',
                    'label' => esc_html__( 'Registration Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_logoutp',
                    'label' => esc_html__( 'My Account Logout', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_orderp',
                    'label' => esc_html__( 'My Account Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_myaccount_lostpasswordp',
                    'label' => esc_html__( 'Lost Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true
                ),
    
                array(
                    'name'  => 'wl_myaccount_resetpasswordp',
                    'label' => esc_html__( 'Reset Password Form', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true
                ),

                array(
                    'name'      => 'thankyou_widget_heading',
                    'headding'  => esc_html__( 'Thank You', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'  => 'wl_thankyou_orderp',
                    'label' => esc_html__( 'Thank You Order', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_thankyou_customer_address_detailsp',
                    'label' => esc_html__( 'Thank You Cus.. Address', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_thankyou_order_detailsp',
                    'label' => esc_html__( 'Thank You Order Details', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'      => 'additional_widget_heading',
                    'headding'  => esc_html__( 'Additional', 'woolentor' ),
                    'type'      => 'title',
                    'class'     => 'woolentor_heading_style_two'
                ),

                array(
                    'name'  => 'wl_mini_cartp',
                    'label' => esc_html__( 'Mini Cart', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

                array(
                    'name'  => 'wl_quickview_product_imgp',
                    'label' => esc_html__( 'Quick view .. image', 'woolentor' ),
                    'type'  => 'element',
                    'default' => 'off',
                    'is_pro' => true,
                ),

            ),

            'woolentor_others_tabs' => array(

                'modules' => array(

                    array(
                        'name'     => 'rename_label_settings',
                        'label'    => esc_html__( 'Rename Label', 'woolentor' ),
                        'type'     => 'module',
                        'default'  => 'off',
                        'section'  => 'woolentor_rename_label_tabs',
                        'option_id'=> 'enablerenamelabel',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://woolentor.com/doc/change-woocommerce-text/'),
                        'setting_fields' => array(
                            
                            array(
                                'name'  => 'enablerenamelabel',
                                'label' => esc_html__( 'Enable / Disable', 'woolentor' ),
                                'desc'  => esc_html__( 'You can enable / disable rename label from here.', 'woolentor' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class'   =>'enablerenamelabel woolentor-action-field-left',
                            ),
            
                            array(
                                'name'      => 'shop_page_heading',
                                'headding'  => esc_html__( 'Shop Page', 'woolentor' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
                            
                            array(
                                'name'        => 'wl_shop_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'woolentor' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Shop page.', 'woolentor' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'woolentor' ),
                                'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                            ),
            
                            array(
                                'name'      => 'product_details_page_heading',
                                'headding'  => esc_html__( 'Product Details Page', 'woolentor' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_add_to_cart_txt',
                                'label'       => esc_html__( 'Add to Cart Button Text', 'woolentor' ),
                                'desc'        => esc_html__( 'Change the Add to Cart button text for the Product details page.', 'woolentor' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Add to Cart', 'woolentor' ),
                                'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                            ),
            
                            array(
                                'name'        => 'wl_description_tab_menu_title',
                                'label'       => esc_html__( 'Description', 'woolentor' ),
                                'desc'        => esc_html__( 'Change the tab title for the product description.', 'woolentor' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Description', 'woolentor' ),
                                'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_additional_information_tab_menu_title',
                                'label'       => esc_html__( 'Additional Information', 'woolentor' ),
                                'desc'        => esc_html__( 'Change the tab title for the product additional information', 'woolentor' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Additional information', 'woolentor' ),
                                'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                            ),
                            
                            array(
                                'name'        => 'wl_reviews_tab_menu_title',
                                'label'       => esc_html__( 'Reviews', 'woolentor' ),
                                'desc'        => esc_html__( 'Change the tab title for the product review', 'woolentor' ),
                                'type'        => 'text',
                                'placeholder' => __( 'Reviews', 'woolentor' ),
                                'class'       =>'depend_enable_rename_label woolentor-action-field-left',
                            ),
            
                            array(
                                'name'      => 'checkout_page_heading',
                                'headding'  => esc_html__( 'Checkout Page', 'woolentor' ),
                                'type'      => 'title',
                                'class'     => 'depend_enable_rename_label',
                            ),
            
                            array(
                                'name'        => 'wl_checkout_placeorder_btn_txt',
                                'label'       => esc_html__( 'Place order', 'woolentor' ),
                                'desc'        => esc_html__( 'Change the label for the Place order field.', 'woolentor' ),
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Place order', 'woolentor' ),
                                'class'       => 'depend_enable_rename_label woolentor-action-field-left',
                            ),

                        )
                    ),

                    array(
                        'name'     => 'sales_notification_settings',
                        'label'    => esc_html__( 'Sales Notification', 'woolentor' ),
                        'type'     => 'module',
                        'default'  => 'off',
                        'section'  => 'woolentor_sales_notification_tabs',
                        'option_id'=> 'enableresalenotification',
                        'require_settings'=> true,
                        'documentation' => esc_url('https://woolentor.com/doc/sales-notification-for-woocommerce/'),
                        'setting_fields' => array(
    
                            array(
                                'name'  => 'enableresalenotification',
                                'label' => esc_html__( 'Enable / Disable', 'woolentor' ),
                                'desc'  => esc_html__( 'You can enable / disable sales notification from here.', 'woolentor' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'class' => 'woolentor-action-field-left'
                            ),
                            
                            array(
                                'name'    => 'notification_content_type',
                                'label'   => esc_html__( 'Notification Content Type', 'woolentor' ),
                                'desc'    => esc_html__( 'Select Content Type', 'woolentor' ),
                                'type'    => 'radio',
                                'default' => 'actual',
                                'options' => array(
                                    'actual' => esc_html__('Real','woolentor'),
                                    'fakes'  => esc_html__('Manual','woolentor'),
                                ),
                                'class' => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'    => 'noification_fake_data',
                                'label'   => esc_html__( 'Choose Template', 'woolentor' ),
                                'desc'    => esc_html__( 'Choose template for manual notification.', 'woolentor' ),
                                'type'    => 'multiselect',
                                'default' => '',
                                'options' => woolentor_elementor_template(),
                                'condition' => array( 'notification_content_type', '==', 'fakes' ),
                            ),
            
                            array(
                                'name'    => 'notification_pos',
                                'label'   => esc_html__( 'Position', 'woolentor' ),
                                'desc'    => esc_html__( 'Set the position of the Sales Notification Position on frontend.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => 'bottomleft',
                                'options' => array(
                                    'topleft'       => esc_html__( 'Top Left','woolentor' ),
                                    'topright'      => esc_html__( 'Top Right','woolentor' ),
                                    'bottomleft'    => esc_html__( 'Bottom Left','woolentor' ),
                                    'bottomright'   => esc_html__( 'Bottom Right','woolentor' ),
                                ),
                                'class' => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'    => 'notification_layout',
                                'label'   => esc_html__( 'Image Position', 'woolentor' ),
                                'desc'    => esc_html__( 'Set the image position of the notification.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => 'imageleft',
                                'options' => array(
                                    'imageleft'   => esc_html__( 'Image Left','woolentor' ),
                                    'imageright'  => esc_html__( 'Image Right','woolentor' ),
                                ),
                                'condition' => array( 'notification_content_type', '==', 'actual' ),
                                'class'   => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'    => 'notification_timing_area_title',
                                'headding'=> esc_html__( 'Notification Timing', 'woolentor' ),
                                'type'    => 'title',
                                'size'    => 'margin_0 regular',
                                'class'   => 'element_section_title_area',
                            ),
            
                            array(
                                'name'    => 'notification_loadduration',
                                'label'   => esc_html__( 'First loading time', 'woolentor' ),
                                'desc'    => esc_html__( 'When to start notification load duration.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => '3',
                                'options' => array(
                                    '2'    => esc_html__( '2 seconds','woolentor' ),
                                    '3'    => esc_html__( '3 seconds','woolentor' ),
                                    '4'    => esc_html__( '4 seconds','woolentor' ),
                                    '5'    => esc_html__( '5 seconds','woolentor' ),
                                    '6'    => esc_html__( '6 seconds','woolentor' ),
                                    '7'    => esc_html__( '7 seconds','woolentor' ),
                                    '8'    => esc_html__( '8 seconds','woolentor' ),
                                    '9'    => esc_html__( '9 seconds','woolentor' ),
                                    '10'   => esc_html__( '10 seconds','woolentor' ),
                                    '20'   => esc_html__( '20 seconds','woolentor' ),
                                    '30'   => esc_html__( '30 seconds','woolentor' ),
                                    '40'   => esc_html__( '40 seconds','woolentor' ),
                                    '50'   => esc_html__( '50 seconds','woolentor' ),
                                    '60'   => esc_html__( '1 minute','woolentor' ),
                                    '90'   => esc_html__( '1.5 minutes','woolentor' ),
                                    '120'  => esc_html__( '2 minutes','woolentor' ),
                                ),
                                'class' => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'    => 'notification_time_showing',
                                'label'   => esc_html__( 'Notification showing time', 'woolentor' ),
                                'desc'    => esc_html__( 'How long to keep the notification.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => '4',
                                'options' => array(
                                    '2'   => esc_html__( '2 seconds','woolentor' ),
                                    '4'   => esc_html__( '4 seconds','woolentor' ),
                                    '5'   => esc_html__( '5 seconds','woolentor' ),
                                    '6'   => esc_html__( '6 seconds','woolentor' ),
                                    '7'   => esc_html__( '7 seconds','woolentor' ),
                                    '8'   => esc_html__( '8 seconds','woolentor' ),
                                    '9'   => esc_html__( '9 seconds','woolentor' ),
                                    '10'  => esc_html__( '10 seconds','woolentor' ),
                                    '20'  => esc_html__( '20 seconds','woolentor' ),
                                    '30'  => esc_html__( '30 seconds','woolentor' ),
                                    '40'  => esc_html__( '40 seconds','woolentor' ),
                                    '50'  => esc_html__( '50 seconds','woolentor' ),
                                    '60'  => esc_html__( '1 minute','woolentor' ),
                                    '90'  => esc_html__( '1.5 minutes','woolentor' ),
                                    '120' => esc_html__( '2 minutes','woolentor' ),
                                ),
                                'class' => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'    => 'notification_time_int',
                                'label'   => esc_html__( 'Time Interval', 'woolentor' ),
                                'desc'    => esc_html__( 'Set the interval time between notifications.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => '4',
                                'options' => array(
                                    '2'   => esc_html__( '2 seconds','woolentor' ),
                                    '4'   => esc_html__( '4 seconds','woolentor' ),
                                    '5'   => esc_html__( '5 seconds','woolentor' ),
                                    '6'   => esc_html__( '6 seconds','woolentor' ),
                                    '7'   => esc_html__( '7 seconds','woolentor' ),
                                    '8'   => esc_html__( '8 seconds','woolentor' ),
                                    '9'   => esc_html__( '9 seconds','woolentor' ),
                                    '10'  => esc_html__( '10 seconds','woolentor' ),
                                    '20'  => esc_html__( '20 seconds','woolentor' ),
                                    '30'  => esc_html__( '30 seconds','woolentor' ),
                                    '40'  => esc_html__( '40 seconds','woolentor' ),
                                    '50'  => esc_html__( '50 seconds','woolentor' ),
                                    '60'  => esc_html__( '1 minute','woolentor' ),
                                    '90'  => esc_html__( '1.5 minutes','woolentor' ),
                                    '120' => esc_html__( '2 minutes','woolentor' ),
                                ),
                                'class' => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'    => 'notification_product_display_option_title',
                                'headding'=> esc_html__( 'Product Query Option', 'woolentor' ),
                                'type'    => 'title',
                                'size'    => 'margin_0 regular',
                                'condition' => array( 'notification_content_type', '==', 'actual' ),
                                'class'   => 'element_section_title_area',
                            ),
            
                            array(
                                'name'              => 'notification_limit',
                                'label'             => esc_html__( 'Limit', 'woolentor' ),
                                'desc'              => esc_html__( 'Set the number of notifications to display.', 'woolentor' ),
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
                                'label' => esc_html__( 'Show/Display all products from each order', 'woolentor' ),
                                'desc'  => esc_html__( 'Manage show all product from each order.', 'woolentor' ),
                                'type'  => 'checkbox',
                                'default' => 'off',
                                'condition' => array( 'notification_content_type', '==', 'actual' ),
                                'class'   => 'woolentor-action-field-left',
                            ),
            
                            array(
                                'name'    => 'notification_uptodate',
                                'label'   => esc_html__( 'Order Upto', 'woolentor' ),
                                'desc'    => esc_html__( 'Do not show purchases older than.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => '7',
                                'options' => array(
                                    '1'   => esc_html__( '1 day','woolentor' ),
                                    '2'   => esc_html__( '2 days','woolentor' ),
                                    '3'   => esc_html__( '3 days','woolentor' ),
                                    '4'   => esc_html__( '4 days','woolentor' ),
                                    '5'   => esc_html__( '5 days','woolentor' ),
                                    '6'   => esc_html__( '6 days','woolentor' ),
                                    '7'   => esc_html__( '1 week','woolentor' ),
                                    '10'  => esc_html__( '10 days','woolentor' ),
                                    '14'  => esc_html__( '2 weeks','woolentor' ),
                                    '21'  => esc_html__( '3 weeks','woolentor' ),
                                    '28'  => esc_html__( '4 weeks','woolentor' ),
                                    '35'  => esc_html__( '5 weeks','woolentor' ),
                                    '42'  => esc_html__( '6 weeks','woolentor' ),
                                    '49'  => esc_html__( '7 weeks','woolentor' ),
                                    '56'  => esc_html__( '8 weeks','woolentor' ),
                                ),
                                'condition' => array( 'notification_content_type', '==', 'actual' ),
                                'class'       => 'woolentor-action-field-left',
                            ),
            
                            array(
                                'name'    => 'notification_animation_area_title',
                                'headding'=> esc_html__( 'Animation', 'woolentor' ),
                                'type'    => 'title',
                                'size'    => 'margin_0 regular',
                                'class'   => 'element_section_title_area',
                            ),
            
                            array(
                                'name'    => 'notification_inanimation',
                                'label'   => esc_html__( 'Animation In', 'woolentor' ),
                                'desc'    => esc_html__( 'Choose entrance animation.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => 'fadeInLeft',
                                'options' => array(
                                    'bounce'            => esc_html__( 'bounce','woolentor' ),
                                    'flash'             => esc_html__( 'flash','woolentor' ),
                                    'pulse'             => esc_html__( 'pulse','woolentor' ),
                                    'rubberBand'        => esc_html__( 'rubberBand','woolentor' ),
                                    'shake'             => esc_html__( 'shake','woolentor' ),
                                    'swing'             => esc_html__( 'swing','woolentor' ),
                                    'tada'              => esc_html__( 'tada','woolentor' ),
                                    'wobble'            => esc_html__( 'wobble','woolentor' ),
                                    'jello'             => esc_html__( 'jello','woolentor' ),
                                    'heartBeat'         => esc_html__( 'heartBeat','woolentor' ),
                                    'bounceIn'          => esc_html__( 'bounceIn','woolentor' ),
                                    'bounceInDown'      => esc_html__( 'bounceInDown','woolentor' ),
                                    'bounceInLeft'      => esc_html__( 'bounceInLeft','woolentor' ),
                                    'bounceInRight'     => esc_html__( 'bounceInRight','woolentor' ),
                                    'bounceInUp'        => esc_html__( 'bounceInUp','woolentor' ),
                                    'fadeIn'            => esc_html__( 'fadeIn','woolentor' ),
                                    'fadeInDown'        => esc_html__( 'fadeInDown','woolentor' ),
                                    'fadeInDownBig'     => esc_html__( 'fadeInDownBig','woolentor' ),
                                    'fadeInLeft'        => esc_html__( 'fadeInLeft','woolentor' ),
                                    'fadeInLeftBig'     => esc_html__( 'fadeInLeftBig','woolentor' ),
                                    'fadeInRight'       => esc_html__( 'fadeInRight','woolentor' ),
                                    'fadeInRightBig'    => esc_html__( 'fadeInRightBig','woolentor' ),
                                    'fadeInUp'          => esc_html__( 'fadeInUp','woolentor' ),
                                    'fadeInUpBig'       => esc_html__( 'fadeInUpBig','woolentor' ),
                                    'flip'              => esc_html__( 'flip','woolentor' ),
                                    'flipInX'           => esc_html__( 'flipInX','woolentor' ),
                                    'flipInY'           => esc_html__( 'flipInY','woolentor' ),
                                    'lightSpeedIn'      => esc_html__( 'lightSpeedIn','woolentor' ),
                                    'rotateIn'          => esc_html__( 'rotateIn','woolentor' ),
                                    'rotateInDownLeft'  => esc_html__( 'rotateInDownLeft','woolentor' ),
                                    'rotateInDownRight' => esc_html__( 'rotateInDownRight','woolentor' ),
                                    'rotateInUpLeft'    => esc_html__( 'rotateInUpLeft','woolentor' ),
                                    'rotateInUpRight'   => esc_html__( 'rotateInUpRight','woolentor' ),
                                    'slideInUp'         => esc_html__( 'slideInUp','woolentor' ),
                                    'slideInDown'       => esc_html__( 'slideInDown','woolentor' ),
                                    'slideInLeft'       => esc_html__( 'slideInLeft','woolentor' ),
                                    'slideInRight'      => esc_html__( 'slideInRight','woolentor' ),
                                    'zoomIn'            => esc_html__( 'zoomIn','woolentor' ),
                                    'zoomInDown'        => esc_html__( 'zoomInDown','woolentor' ),
                                    'zoomInLeft'        => esc_html__( 'zoomInLeft','woolentor' ),
                                    'zoomInRight'       => esc_html__( 'zoomInRight','woolentor' ),
                                    'zoomInUp'          => esc_html__( 'zoomInUp','woolentor' ),
                                    'hinge'             => esc_html__( 'hinge','woolentor' ),
                                    'jackInTheBox'      => esc_html__( 'jackInTheBox','woolentor' ),
                                    'rollIn'            => esc_html__( 'rollIn','woolentor' ),
                                    'rollOut'           => esc_html__( 'rollOut','woolentor' ),
                                ),
                                'class' => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'    => 'notification_outanimation',
                                'label'   => esc_html__( 'Animation Out', 'woolentor' ),
                                'desc'    => esc_html__( 'Choose exit animation.', 'woolentor' ),
                                'type'    => 'select',
                                'default' => 'fadeOutRight',
                                'options' => array(
                                    'bounce'             => esc_html__( 'bounce','woolentor' ),
                                    'flash'              => esc_html__( 'flash','woolentor' ),
                                    'pulse'              => esc_html__( 'pulse','woolentor' ),
                                    'rubberBand'         => esc_html__( 'rubberBand','woolentor' ),
                                    'shake'              => esc_html__( 'shake','woolentor' ),
                                    'swing'              => esc_html__( 'swing','woolentor' ),
                                    'tada'               => esc_html__( 'tada','woolentor' ),
                                    'wobble'             => esc_html__( 'wobble','woolentor' ),
                                    'jello'              => esc_html__( 'jello','woolentor' ),
                                    'heartBeat'          => esc_html__( 'heartBeat','woolentor' ),
                                    'bounceOut'          => esc_html__( 'bounceOut','woolentor' ),
                                    'bounceOutDown'      => esc_html__( 'bounceOutDown','woolentor' ),
                                    'bounceOutLeft'      => esc_html__( 'bounceOutLeft','woolentor' ),
                                    'bounceOutRight'     => esc_html__( 'bounceOutRight','woolentor' ),
                                    'bounceOutUp'        => esc_html__( 'bounceOutUp','woolentor' ),
                                    'fadeOut'            => esc_html__( 'fadeOut','woolentor' ),
                                    'fadeOutDown'        => esc_html__( 'fadeOutDown','woolentor' ),
                                    'fadeOutDownBig'     => esc_html__( 'fadeOutDownBig','woolentor' ),
                                    'fadeOutLeft'        => esc_html__( 'fadeOutLeft','woolentor' ),
                                    'fadeOutLeftBig'     => esc_html__( 'fadeOutLeftBig','woolentor' ),
                                    'fadeOutRight'       => esc_html__( 'fadeOutRight','woolentor' ),
                                    'fadeOutRightBig'    => esc_html__( 'fadeOutRightBig','woolentor' ),
                                    'fadeOutUp'          => esc_html__( 'fadeOutUp','woolentor' ),
                                    'fadeOutUpBig'       => esc_html__( 'fadeOutUpBig','woolentor' ),
                                    'flip'               => esc_html__( 'flip','woolentor' ),
                                    'flipOutX'           => esc_html__( 'flipOutX','woolentor' ),
                                    'flipOutY'           => esc_html__( 'flipOutY','woolentor' ),
                                    'lightSpeedOut'      => esc_html__( 'lightSpeedOut','woolentor' ),
                                    'rotateOut'          => esc_html__( 'rotateOut','woolentor' ),
                                    'rotateOutDownLeft'  => esc_html__( 'rotateOutDownLeft','woolentor' ),
                                    'rotateOutDownRight' => esc_html__( 'rotateOutDownRight','woolentor' ),
                                    'rotateOutUpLeft'    => esc_html__( 'rotateOutUpLeft','woolentor' ),
                                    'rotateOutUpRight'   => esc_html__( 'rotateOutUpRight','woolentor' ),
                                    'slideOutUp'         => esc_html__( 'slideOutUp','woolentor' ),
                                    'slideOutDown'       => esc_html__( 'slideOutDown','woolentor' ),
                                    'slideOutLeft'       => esc_html__( 'slideOutLeft','woolentor' ),
                                    'slideOutRight'      => esc_html__( 'slideOutRight','woolentor' ),
                                    'zoomOut'            => esc_html__( 'zoomOut','woolentor' ),
                                    'zoomOutDown'        => esc_html__( 'zoomOutDown','woolentor' ),
                                    'zoomOutLeft'        => esc_html__( 'zoomOutLeft','woolentor' ),
                                    'zoomOutRight'       => esc_html__( 'zoomOutRight','woolentor' ),
                                    'zoomOutUp'          => esc_html__( 'zoomOutUp','woolentor' ),
                                    'hinge'              => esc_html__( 'hinge','woolentor' ),
                                ),
                                'class' => 'woolentor-action-field-left'
                            ),
                            
                            array(
                                'name'    => 'notification_style_area_title',
                                'headding'=> esc_html__( 'Style', 'woolentor' ),
                                'type'    => 'title',
                                'size'    => 'margin_0 regular',
                                'class' => 'element_section_title_area',
                            ),
            
                            array(
                                'name'        => 'notification_width',
                                'label'       => esc_html__( 'Width', 'woolentor' ),
                                'desc'        => esc_html__( 'You can handle the sales notification width.', 'woolentor' ),
                                'type'        => 'text',
                                'default'     => esc_html__( '550px', 'woolentor' ),
                                'placeholder' => esc_html__( '550px', 'woolentor' ),
                                'class'       => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'        => 'notification_mobile_width',
                                'label'       => esc_html__( 'Width for mobile', 'woolentor' ),
                                'desc'        => esc_html__( 'You can handle the sales notification width.', 'woolentor' ),
                                'type'        => 'text',
                                'default'     => esc_html__( '90%', 'woolentor' ),
                                'placeholder' => esc_html__( '90%', 'woolentor' ),
                                'class'       => 'woolentor-action-field-left'
                            ),
            
                            array(
                                'name'  => 'background_color',
                                'label' => esc_html__( 'Background Color', 'woolentor' ),
                                'desc'  => esc_html__( 'Set the background color of the sales notification.', 'woolentor' ),
                                'type'  => 'color',
                                'condition' => array( 'notification_content_type', '==', 'actual' ),
                                'class' => 'woolentor-action-field-left',
                            ),
            
                            array(
                                'name'  => 'heading_color',
                                'label' => esc_html__( 'Heading Color', 'woolentor' ),
                                'desc'  => esc_html__( 'Set the heading color of the sales notification.', 'woolentor' ),
                                'type'  => 'color',
                                'condition' => array( 'notification_content_type', '==', 'actual' ),
                                'class' => 'woolentor-action-field-left',
                            ),
            
                            array(
                                'name'  => 'content_color',
                                'label' => esc_html__( 'Content Color', 'woolentor' ),
                                'desc'  => esc_html__( 'Set the content color of the sales notification.', 'woolentor' ),
                                'type'  => 'color',
                                'condition' => array( 'notification_content_type', '==', 'actual' ),
                                'class' => 'woolentor-action-field-left',
                            ),
            
                            array(
                                'name'  => 'cross_color',
                                'label' => esc_html__( 'Cross Icon Color', 'woolentor' ),
                                'desc'  => esc_html__( 'Set the cross icon color of the sales notification.', 'woolentor' ),
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
                                        'label' => esc_html__( 'Discount Value', 'woolentor' ),
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
                                'class'      => 'woolentor-action-field-left woolentor-adv-pro-notice',
                                'condition'  => array('enable', '==', '1'),
                                'is_pro'     => true
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
                                    <br/>Here \"standard value\" refers to the number of highest combinations you have set for one of your products.','woolentor'),
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
                                'class'      => 'woolentor-action-field-left  woolentor-adv-pro-notice',
                                'condition'  => array('enable', '==', '1'),
                                'is_pro'     => true
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
                                'class'        => 'woolentor-action-field-left woolentor-adv-pro-notice',
                                'condition'    => array(
                                    'enable|pl_enable_swatches', '==|==', '1|1',
                                ),
                                'is_pro'     => true
                            ),

                            array(
                                'name'       => 'pl_add_to_cart_text',
                                'type'       => 'text',
                                'label'      =>  esc_html__('Add to Cart Text', 'woolentor'),
                                'desc'       =>  esc_html__('Leave it empty for default.', 'woolentor'),
                                'class'      => 'woolentor-action-field-left woolentor-adv-pro-opacity',
                                'condition'  => array('enable|pl_enable_swatches', '==|==', '1|1',),
                                'is_pro'     => true
                            ),

                            array(
                                'name'       => 'pl_hide_wc_forward_button',
                                'type'       => 'checkbox',
                                'label'      =>  esc_html__('Hide "View Cart" button after Added to Cart', 'woolentor'),
                                'desc'       =>  esc_html__('After successfully add to cart, a new button shows linked to the cart page. You can controll of that button from here. Note: If redirect option is enable from WooCommerce it will not work.', 'woolentor'),
                                'class'      => 'woolentor-action-field-left woolentor-adv-pro-opacity',
                                'condition'  => array('enable|pl_enable_swatches', '==|==', '1|1'),
                                'is_pro'     => true
                            ),

                            array(
                                'name'         => 'pl_enable_cart_popup_notice',
                                'type'         => 'checkbox',
                                'label'        =>  esc_html__('Enable poupup notice after added to cart', 'woolentor'),
                                'desc'         =>  esc_html__('After successfully add to cart, a pupup notice will be generated containing a button linked to the cart page. Note: If redirect option is enable from WooCommerce it will not work.', 'woolentor'),
                                'class'        => 'woolentor-action-field-left woolentor-adv-pro-opacity',
                                'condition'    => array('enable|pl_enable_swatches', '==|==', '1|1'),
                                'is_pro'       => true
                            ),
                            

                        )

                    ),

                    array(
                        'name'     => 'wishlist',
                        'label'    => esc_html__( 'Wishlist', 'woolentor' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://woolentor.com/doc/wishlist-for-woocommerce/')
                    ),

                    array(
                        'name'     => 'compare',
                        'label'    => esc_html__( 'Compare', 'woolentor' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://woolentor.com/doc/woocommerce-product-compare/')
                    ),
                    
                    array(
                        'name'    => 'ajaxsearch',
                        'label'   => esc_html__( 'Ajax Search Widget', 'woolentor' ),
                        'desc'    => esc_html__( 'AJAX Search Widget', 'woolentor' ),
                        'type'    => 'element',
                        'default' => 'off',
                        'documentation' => esc_url('https://woolentor.com/doc/how-to-use-woocommerce-ajax-search/')
                    ),
    
                    array(
                        'name'     => 'ajaxcart_singleproduct',
                        'label'    => esc_html__( 'Single Product Ajax Add To Cart', 'woolentor' ),
                        'desc'     => esc_html__( 'AJAX Add to Cart on Single Product page', 'woolentor' ),
                        'type'     => 'element',
                        'default'  => 'off',
                        'documentation' => esc_url('https://woolentor.com/doc/single-product-ajax-add-to-cart/')
                    ),

                    array(
                        'name'   => 'woolentor_checkout_field_settingsp',
                        'label'  => esc_html__( 'Checkout Fields Manager', 'woolentor' ),
                        'desc'   => esc_html__( 'Checkout Fields Manager Module', 'woolentor' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'partial_paymentp',
                        'label'  => esc_html__( 'Partial Payment', 'woolentor' ),
                        'desc'   => esc_html__( 'Partial Payment Module', 'woolentor' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'pre_ordersp',
                        'label'  => esc_html__( 'Pre Orders', 'woolentor' ),
                        'desc'   => esc_html__( 'Pre Orders Module', 'woolentor' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'size_chartp',
                        'label'  => esc_html__( 'Size Chart', 'woolentor' ),
                        'desc'   => esc_html__( 'Size Chart Module', 'woolentor' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'    => 'order_bump',
                        'label'   => esc_html__( 'Order Bump', 'woolentor' ),
                        'type'    => 'module',
                        'default' => 'off',
                        'require_settings' => true,
                        'is_pro'  => true
                    ),

                    array(
                        'name'    => 'product_filterp',
                        'label'   => esc_html__( 'Product Filter', 'woolentor' ),
                        'type'    => 'module',
                        'default' => 'off',
                        'require_settings' => true,
                        'is_pro'  => true
                    ),

                    array(
                        'name'     => 'email_customizerp',
                        'label'    => esc_html__( 'Email Customizer', 'woolentor' ),
                        'type'     => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'     => 'email_automationp',
                        'label'    => esc_html__( 'Email Automation', 'woolentor' ),
                        'type'     => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'gtm_conversion_trackingp',
                        'label'  => esc_html__( 'GTM Conversion Tracking', 'woolentor' ),
                        'desc'   => esc_html__( 'GTM Conversion Tracking Module', 'woolentor' ),
                        'type'   => 'module',
                        'default'=> 'off',
                        'require_settings' => true,
                        'is_pro' => true
                    ),
                    
                    array(
                        'name'   => 'single_product_sticky_add_to_cartp',
                        'label'  => esc_html__( 'Product sticky Add to cart', 'woolentor' ),
                        'desc'   => esc_html__( 'Sticky Add to Cart on Single Product page', 'woolentor' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    ),
    
                    array(
                        'name'   => 'mini_side_cartp',
                        'label'  => esc_html__( 'Side Mini Cart', 'woolentor' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    ),

                    array(
                        'name'   => 'redirect_add_to_cartp',
                        'label'  => esc_html__( 'Redirect to Checkout', 'woolentor' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    ),
    
                    array(
                        'name'   => 'multi_step_checkoutp',
                        'label'  => esc_html__( 'Multi Step Checkout', 'woolentor' ),
                        'type'   => 'element',
                        'default'=> 'off',
                        'is_pro' => true
                    )

                ),

                'others' => array(

                    array(
                        'name'  => 'loadproductlimit',
                        'label' => esc_html__( 'Load Products in Elementor Addons', 'woolentor' ),
                        'desc'  => esc_html__( 'Set the number of products to load in Elementor Addons', 'woolentor' ),
                        'min'               => 1,
                        'max'               => 100,
                        'step'              => '1',
                        'type'              => 'number',
                        'default'           => '20',
                        'sanitize_callback' => 'floatval'
                    )

                ),

            ),

            'woolentor_style_tabs' => array(

                array(
                    'name'     => 'section_area_title_heading',
                    'type'     => 'title',
                    'headding' => esc_html__( 'Universal layout style options', 'woolentor' ),
                    'size'     => 'woolentor_style_seperator',
                ),

                array(
                    'name'      => 'content_area_bg',
                    'label'     => esc_html__( 'Content area background', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),

                array(
                    'name'      => 'section_title_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Title', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'title_color',
                    'label'     => esc_html__( 'Title color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),
                array(
                    'name'      => 'title_hover_color',
                    'label'     => esc_html__( 'Title hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'name'      => 'section_price_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Price', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'sale_price_color',
                    'label'     => esc_html__( 'Sale price color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),
                array(
                    'name'      => 'regular_price_color',
                    'label'     => esc_html__( 'Regular price color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),

                array(
                    'name'      => 'section_category_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Category', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'category_color',
                    'label'     => esc_html__( 'Category color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),
                array(
                    'name'      => 'category_hover_color',
                    'label'     => esc_html__( 'Category hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'name'      => 'section_short_description_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Short Description', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'desc_color',
                    'label'     => esc_html__( 'Description color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),

                array(
                    'name'      => 'section_rating_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Rating', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'empty_rating_color',
                    'label'     => esc_html__( 'Empty rating color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#aaaaaa',
                ),
                array(
                    'name'      => 'rating_color',
                    'label'     => esc_html__( 'Rating color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'name'      => 'section_badge_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Product Badge', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'badge_color',
                    'label'     => esc_html__( 'Badge color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#444444',
                ),

                array(
                    'name'      => 'section_action_btn_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Quick Action Button', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'tooltip_color',
                    'label'     => esc_html__( 'Tool tip color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),
                array(
                    'name'      => 'btn_color',
                    'label'     => esc_html__( 'Button color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#000000',
                ),
                array(
                    'name'      => 'btn_hover_color',
                    'label'     => esc_html__( 'Button hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),

                array(
                    'name'      => 'section_action_list_btn_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Archive List View Action Button', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'list_btn_color',
                    'label'     => esc_html__( 'List View Button color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#000000',
                ),
                array(
                    'name'      => 'list_btn_hover_color',
                    'label'     => esc_html__( 'List View Button Hover color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#dc9a0e',
                ),
                array(
                    'name'      => 'list_btn_bg_color',
                    'label'     => esc_html__( 'List View Button background color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),
                array(
                    'name'      => 'list_btn_hover_bg_color',
                    'label'     => esc_html__( 'List View Button hover background color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ff3535',
                ),

                array(
                    'name'      => 'section_counter_timer_heading',
                    'type'      => 'title',
                    'headding'  => esc_html__( 'Counter Timer', 'woolentor' ),
                    'size'      => 'woolentor_style_seperator',
                ),
                array(
                    'name'      => 'counter_color',
                    'label'     => esc_html__( 'Counter timer color', 'woolentor' ),
                    'desc'      => esc_html__( 'Default Color for universal layout.', 'woolentor' ),
                    'type'      => 'color',
                    'default'   => '#ffffff',
                ),

            ),

        );

        // Post Duplicator Condition
        if( !is_plugin_active('ht-mega-for-elementor/htmega_addons_elementor.php') ){

            $post_types = woolentor_get_post_types( array( 'defaultadd' => 'all' ) );
            if ( did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' ) ) {
                $post_types['elementor_library'] = esc_html__( 'Templates', 'woolentor' );
            }

            $settings_fields['woolentor_others_tabs']['modules'][] = [
                'name'     => 'postduplicator',
                'label'    => esc_html__( 'Post Duplicator', 'woolentor' ),
                'type'     => 'element',
                'default'  => 'off',
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
            $settings_fields['woolentor_elements_tabs'][] = [
                'name'    => 'product_flash_sale',
                'label'   => esc_html__( 'Product Flash Sale', 'woolentor' ),
                'type'    => 'element',
                'default' => 'on'
            ];
        }

        // Wishsuite Addons
        if( class_exists('WishSuite_Base') || class_exists('Woolentor_WishSuite_Base') ){
            $settings_fields['woolentor_elements_tabs'][] = [
                'name'      => 'wb_wishsuite_table',
                'label'     => esc_html__( 'WishSuite Table', 'woolentor' ),
                'type'      => 'element',
                'default'   => 'on',
            ];
            $settings_fields['woolentor_elements_tabs'][] = [
                'name'      => 'wb_wishsuite_counter',
                'label'     => esc_html__( 'WishSuite Counter', 'woolentor' ),
                'type'      => 'element',
                'default'   => 'on',
            ];
        }

        // Ever Compare Addons
        if( class_exists('Ever_Compare') || class_exists('Woolentor_Ever_Compare') ){
            $settings_fields['woolentor_elements_tabs'][] = [
                'name'      => 'wb_ever_compare_table',
                'label'     => esc_html__( 'Ever Compare', 'woolentor' ),
                'type'      => 'element',
                'default'   => 'on',
            ];
        }

        // JustTable Addons
        if( is_plugin_active('just-tables/just-tables.php') || is_plugin_active('just-tables-pro/just-tables-pro.php') ){
            $settings_fields['woolentor_elements_tabs'][] = [
                'name'      => 'wb_just_table',
                'label'     => esc_html__( 'JustTable', 'woolentor' ),
                'type'      => 'element',
                'default'   => 'on',
            ];
        }

        // whols Addons
        if( is_plugin_active('whols/whols.php') || is_plugin_active('whols-pro/whols-pro.php') ){
            $settings_fields['woolentor_elements_tabs'][] = [
                'name'    => 'wb_whols',
                'label'   => esc_html__( 'Whols', 'woolentor' ),
                'type'    => 'element',
                'default' => 'on'
            ];
        }

        // Multicurrency Addons
        if( is_plugin_active('wc-multi-currency/wcmilticurrency.php') || is_plugin_active('multicurrencypro/multicurrencypro.php') ){
            $settings_fields['woolentor_elements_tabs'][] = [
                'name'    => 'wb_wc_multicurrency',
                'label'   => esc_html__( 'Multi Currency', 'woolentor' ),
                'type'    => 'element',
                'default' => 'on'
            ];
        }

        return apply_filters( 'woolentor_admin_fields', $settings_fields );

    }



}