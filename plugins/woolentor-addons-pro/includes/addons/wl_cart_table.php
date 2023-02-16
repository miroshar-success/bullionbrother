<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Cart_Table_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-cart-table';
    }
    
    public function get_title() {
        return __( 'WL: Cart Table', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-product-breadcrumbs';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets-pro',
        ];
    }

    public function get_keywords(){
        return ['cart','table','woocommerce cart table','woocommerce cart','customize cart table'];
    }

    protected function register_controls() {

        // Cart Table Row Content
        $this->start_controls_section(
            'cart_content_general',
            [
                'label' => esc_html__( 'General', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'style',
                [
                    'label' => esc_html__( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''        => esc_html__( 'Default', 'woolentor-pro' ),
                        'wl-cart-style-1' => esc_html__( 'Style 1', 'woolentor-pro' ),
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_content',
            [
                'label' => esc_html__( 'Manage Table Row', 'woolentor-pro' ),
            ]
        );
            
            $repeater = new Repeater();
            $repeater->add_control(
                'table_items',
                [
                    'label' => esc_html__( 'Table Item', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'remove',
                    'options' => [
                        'remove'    => esc_html__( 'Remove', 'woolentor-pro' ),
                        'thumbnail' => esc_html__( 'Image', 'woolentor-pro' ),
                        'name'      => esc_html__( 'Product Title', 'woolentor-pro' ),
                        'price'     => esc_html__( 'Price', 'woolentor-pro' ),
                        'quantity'  => esc_html__( 'Quantity', 'woolentor-pro' ),
                        'subtotal'  => esc_html__( 'Total', 'woolentor-pro' ),
                        'customadd' => esc_html__( 'Custom', 'woolentor-pro' ),
                    ],
                ]
            );

            $repeater->add_control(
                'table_heading_title', 
                [
                    'label' => esc_html__( 'Heading Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Product title' , 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_responsive_control(
                'table_cell_width',
                [
                    'label' => esc_html__( 'Column Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.shop_table_responsive.cart tr td{{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .shop_table.shop_table_responsive.cart tr th{{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $repeater->add_responsive_control(
                'table_heading_cell_self_align',
                [
                    'label'        => __( 'Text Alignment', 'woolentor-pro' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.shop_table_responsive.cart tr th{{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_item_list',
                [
                    'label' => __( 'Table Item List', 'woolentor-pro' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'table_items'           => 'remove',
                            'table_heading_title'   => esc_html__( 'Remove', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'thumbnail',
                            'table_heading_title'   => esc_html__( 'Image', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'name',
                            'table_heading_title'   => esc_html__( 'Product Title', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'price',
                            'table_heading_title'   => esc_html__( 'Price', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'quantity',
                            'table_heading_title'   => esc_html__( 'Quantity', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'subtotal',
                            'table_heading_title'   => esc_html__( 'Total', 'woolentor-pro' ),
                        ],
                    ],
                    'title_field' => '{{{ table_heading_title }}}',
                    'condition'=> [
                        'style' => '',
                    ],
                ]
            );

            $this->add_control(
                'table_item_list_2',
                [
                    'label' => __( 'Table Item List', 'woolentor-pro' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'table_items'           => 'remove',
                            'table_heading_title'   => esc_html__( 'Remove', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'thumbnail',
                            'table_heading_title'   => esc_html__( 'Product Title', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'price',
                            'table_heading_title'   => esc_html__( 'Price', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'quantity',
                            'table_heading_title'   => esc_html__( 'Quantity', 'woolentor-pro' ),
                        ],
                        [
                            'table_items'           => 'subtotal',
                            'table_heading_title'   => esc_html__( 'Total', 'woolentor-pro' ),
                        ]
                    ],
                    'title_field' => '{{{ table_heading_title }}}',
                    'condition'=>[
                        'style' => 'wl-cart-style-1',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_table_remove_icon',
            [
                'label' => esc_html__( 'Remove Icon', 'woolentor-pro' ),
                'condition'=>[
                    'style!' => '',
                ],
            ]
        );

            $this->add_control(
                'cart_remove_icon_style',
                [
                    'label' => esc_html__( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'wl-remove-icon-style-1',
                    'options' => [
                        'wl-remove-icon-style-1' => esc_html__( 'Style 1', 'woolentor-pro' ),
                        'wl-remove-icon-style-2' => esc_html__( 'Style 2', 'woolentor-pro' ),
                    ],
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'remove_icon',
                [
                    'label'       => esc_html__( 'Select Icon', 'woolentor-pro' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-times',
                        'library' => 'solid',
                    ],
                    'label_block' => true,
                    'fa4compatibility' => 'removeicon'
                ]
            );

        $this->end_controls_section();


        $this->start_controls_section(
            'cart_table_thubnail',
            [
                'label' => esc_html__( 'Thumbnail', 'woolentor-pro' ),
                'condition'=>[
                    'style!' => '',
                ],
            ]
        );

            $this->add_control(
                'delete_remove_icon',
                [
                    'label'         => esc_html__( 'Remove Product Button', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'thumbnail_content_lineup',
                [
                    'label'         => esc_html__( 'Inline Content', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'content_vertical_align',
                [
                    'label' => __('Vertical Alignment', 'woolentor-pro'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => __('Top', 'woolentor-pro'),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'center' => [
                            'title' => __('Center', 'woolentor-pro'),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'flex-end' => [
                            'title' => __('Bottom', 'woolentor-pro'),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => false,
                    'condition'=>[
                        'style!' => '',
                        'thumbnail_content_lineup' => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .cart_item .woolentor-cart-product.inline' => 'align-items:{{VALUE}};'
                    ]
                ]
            );

            $this->add_responsive_control(
                'remove_icon_position',
                [
                    'label'        => __( 'Remoe Icon Alignment', 'woolentor-pro' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'condition'=>[
                        'style!' => '',
                        'delete_remove_icon' => '',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_table_quantity',
            [
                'label' => esc_html__( 'Quantity', 'woolentor-pro' ),
                'condition'=>[
                    'style!' => '',
                ],
            ]
        );

            $this->add_control(
                'cart_quantity_lay_out',
                [
                    'label' => esc_html__( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''              => esc_html__( 'Default', 'woolentor-pro' ),
                        'wlqty-style-1' => esc_html__( 'Style 1', 'woolentor-pro' ),
                        'wlqty-style-2' => esc_html__( 'Style 2', 'woolentor-pro' ),
                        'wlqty-style-3' => esc_html__( 'Style 3', 'woolentor-pro' ),
                        'wlqty-style-4' => esc_html__( 'Style 4', 'woolentor-pro' ),
                    ]
                ]
            );

            $this->add_control(
                'quantity_plus_icon',
                [
                    'label'       => esc_html__( 'Select Plus Icon', 'woolentor-pro' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-plus',
                        'library' => 'solid',
                    ],
                    'label_block' => true,
                    'fa4compatibility' => 'quantiytplusicon',
                    'condition'=>[
                        'cart_quantity_lay_out!' => '',
                    ],
                ]
            );

            $this->add_control(
                'quantity_minus_icon',
                [
                    'label'       => esc_html__( 'Select Minus Icon', 'woolentor-pro' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-minus',
                        'library' => 'solid',
                    ],
                    'label_block' => true,
                    'fa4compatibility' => 'quantiytminusicon',
                    'condition'=>[
                        'cart_quantity_lay_out!' => '',
                    ],
                ]
            );

        $this->end_controls_section();

        // Pricing Discount Roles
        $this->start_controls_section(
            'discount_rules',
            [
                'label' => esc_html__( 'Pricing Discount Rules', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'custom_price_discount_rule',
                [
                    'label'         => __( 'Enable Discount Rules', 'plugin-domain' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $roles_fileds = new Repeater();

            $roles_fileds->add_control(
                'discount_qtn',
                [
                    'label' => esc_html__( 'Quantity', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                ]
            );

            $roles_fileds->add_control(
                'discount_amount',
                [
                    'label' => esc_html__( 'Percent / Amount', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                ]
            );

            $roles_fileds->add_control(
                'discount_type',
                [
                    'label' => esc_html__( 'Type', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'percent',
                    'options' => [
                        'percent' => esc_html__( 'Percent Of', 'woolentor-pro' ),
                        'amount'  => esc_html__( 'Amount Of', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'discount_rule_list',
                [
                    'label' => __( 'Discount Rules', 'woolentor-pro' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $roles_fileds->get_controls(),
                    'title_field' => 'Quantity: {{{ discount_qtn }}} Amount: {{{ discount_amount }}}',
                    'prevent_empty' => false,
                    'condition' => [
                        'custom_price_discount_rule' => 'yes'
                    ]
                ]
            );

        $this->end_controls_section();

        // Product Gift Roles
        $this->start_controls_section(
            'product_gift_rules',
            [
                'label' => esc_html__( 'Buy One Get One Rule', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'brougth_pro_id',
                [
                    'label' => __( 'Select Bought Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_post_name( 'product' ),
                ]
            );
            
            $this->add_control(
                'gifted_pro_id',
                [
                    'label' => __( 'Select Gifted Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_post_name( 'product' ),
                    'description'=>esc_html__( 'This product must be set price = 0', 'woolentor-pro' )
                ]
            );

        $this->end_controls_section();

        // Cart table Action
        $this->start_controls_section(
            'cart_table_action',
            [
                'label' => esc_html__( 'Cart Table Action', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'show_update_button',
                [
                    'label'         => esc_html__( 'Update Cart', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                ]
            );

            $this->add_control(
                'update_cart_button_txt',
                [
                    'label' => __( 'Update cart button text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Update cart', 'woolentor-pro' ),
                    'placeholder' => __( 'Update cart button text', 'woolentor-pro' ),
                    'condition'=>[
                        'show_update_button'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_responsive_control(
                'update_cart_button_align',
                [
                    'label'        => __( 'Cart Button Alignment', 'woolentor-pro' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'condition'=>[
                        'show_update_button'=>'yes',
                        'show_coupon_form!'=>'yes',
                    ],
                    'default'  => 'right',
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart.wl_cart_table .actions' => 'text-align: {{VALUE}}',
                        'body #content {{WRAPPER}} .wl_cart_table td.actions' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'show_continue_button',
                [
                    'label'         => esc_html__( 'Continue Shopping', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'continue_button_txt',
                [
                    'label' => __( 'Continue Shopping button text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Continue Shopping', 'woolentor-pro' ),
                    'placeholder' => __( 'Continue Shopping button text', 'woolentor-pro' ),
                    'condition'=>[
                        'show_continue_button'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_responsive_control(
                'continue_button_align',
                [
                    'label'        => __( 'Continue Button Alignment', 'woolentor-pro' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'condition'=>[
                        'show_continue_button'=>'yes',
                        'show_coupon_form!'=>'yes',
                    ],
                    'default'  => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'float: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'show_coupon_form',
                [
                    'label'         => esc_html__( 'Coupon Form', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'coupon_form_button_txt',
                [
                    'label' => __( 'Coupon form button text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Apply coupon', 'woolentor-pro' ),
                    'placeholder' => __( 'Apply coupon button text', 'woolentor-pro' ),
                    'condition'=>[
                        'show_coupon_form'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'coupon_form_pl_txt',
                [
                    'label' => __( 'Placeholder text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Coupon code', 'woolentor-pro' ),
                    'placeholder' => __( 'Coupon code', 'woolentor-pro' ),
                    'condition'=>[
                        'show_coupon_form'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

        $this->end_controls_section();

        // Cart table Extra option Start
        $this->start_controls_section(
            'cart_table_extra_options',
            [
                'label' => esc_html__( 'Extra Options', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'disable_user_adj_qtn',
                [
                    'label'         => esc_html__( 'Disable users adjusting quantity', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'remove_product_link',
                [
                    'label'         => esc_html__( 'Remove Product link', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'show_product_category',
                [
                    'label'         => esc_html__( 'Show Product Categories', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'show_product_stock',
                [
                    'label'         => esc_html__( 'Show Product Stock', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                    'separator'     => 'before',
                ]
            );

            $this->add_control(
                'show_product_sku',
                [
                    'label'         => esc_html__( 'Show Product SKU', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'separator'     => 'before',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'show_product_watch_variation',
                [
                    'label'         => esc_html__( 'Show Product Variation', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off'     => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value'  => 'yes',
                    'default'       => 'yes',
                    'separator'     => 'before',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

        $this->end_controls_section();
        // Cart table Extra option End

        // Style tab
        $this->start_controls_section(
            'cart_heading_style_section',
            [
                'label' => __( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'heading_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .shop_table.cart th, {{WRAPPER}} .shop_table.cart thead',
                ]
            );

            $this->add_control(
                'heading_text_color',
                [
                    'label' => __( 'Text Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart th',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'heading_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart th',
                ]
            );

            $this->add_responsive_control(
                'heading_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_text_align',
                [
                    'label'        => __( 'Text Alignment', 'woolentor-pro' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart thead th' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Cart Table
        $this->start_controls_section(
            'cart_table_style_section',
            [
                'label' => __( 'Table', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_table_border',
                    'label' => __( 'Table Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart',
                ]
            );

            $this->add_responsive_control(
                'cart_table_padding',
                [
                    'label' => __( 'Table Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'cart_table_row_style',
            [
                'label' => __( 'Table Row', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'style!' => '',
                ],
            ]
        );

            $this->add_responsive_control(
                'table_cell_tr_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr td:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}} ; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .shop_table.cart tr th:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}} ; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .shop_table.cart tr td:last-child' => 'border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
                        '{{WRAPPER}} .shop_table.cart tr th:last-child' => 'border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'table_cell_tr_sapcing',
                [
                    'label' => __( 'Row Spacing', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart' => 'border-collapse: separate',
                        '{{WRAPPER}} .shop_table.cart' => 'border-spacing: 0 {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Cart Table Content
        $this->start_controls_section(
            'cart_content_style_section',
            [
                'label' => __( 'Table Cell', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'table_cell_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart tr td',
                ]
            );

            $this->add_responsive_control(
                'table_cell_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'table_cell_border_radius',
                [
                    'label' => __( 'Bordr Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'table_cell_text_align',
                [
                    'label'        => __( 'Text Alignment', 'woolentor-pro' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cart_table_background',
                    'label' => __( 'Backgroundss', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .shop_table.cart',
                    'condition'=>[
                        'style' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cart_table_style2_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item td',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

        $this->end_controls_section();

        // Remove Button Style
        $this->start_controls_section(
            'cart_product_remove_style',
            array(
                'label' => __( 'Remove Icon', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'remove_icon_flip',
                [
                    'label'         => esc_html__( 'Flip Icon', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-remove.wl-remove-icon-style-2' => ' -webkit-transform: scaleX(-1); transform: scaleX(-1);',
                    ],
                    'condition'=>[
                        'cart_remove_icon_style' => 'wl-remove-icon-style-2',
                        'style!' => '',
                    ],
                ]
            );

            $this->start_controls_tabs( 'cart_remove_style_tabs' );

                // Normal Style
                $this->start_controls_tab( 
                    'product_remove_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_remove_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove' => 'color: {{VALUE}} !important',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item .woolentor-cart-img .woolentor-cart-product-remove' => 'color: {{VALUE}} !important'
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_remove_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove' => 'background: {{VALUE}} !important',
                            '{{WRAPPER}} .shop_table.cart tr.cart_item .woolentor-cart-img .woolentor-cart-product-remove' => 'background: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_product_remove_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove,{{WRAPPER}} .shop_table.cart tr.cart_item .woolentor-cart-img .woolentor-cart-product-remove',
                        ]
                    );

                    $this->add_responsive_control(
                        'table_remove_cell_text_align',
                        [
                            'label'        => __( 'Text Alignment', 'woolentor-pro' ),
                            'type'         => Controls_Manager::CHOOSE,
                            'options'      => [
                                'left'   => [
                                    'title' => __( 'Left', 'woolentor-pro' ),
                                    'icon'  => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => __( 'Center', 'woolentor-pro' ),
                                    'icon'  => 'eicon-text-align-center',
                                ],
                                'right'  => [
                                    'title' => __( 'Right', 'woolentor-pro' ),
                                    'icon'  => 'eicon-text-align-right',
                                ],
                                'justify' => [
                                    'title' => __( 'Justified', 'woolentor-pro' ),
                                    'icon' => 'eicon-text-align-justify',
                                ],
                            ],
                            'default'      => 'left',
                            'selectors' => [
                                '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item td.product-remove' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_product_remove_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item .woolentor-cart-img .woolentor-cart-product-remove' => 'border-radius : {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style
                $this->start_controls_tab( 
                    'product_remove_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_remove_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove:hover' => 'color: {{VALUE}} !important',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item .woolentor-cart-img .woolentor-cart-product-remove:hover' => 'color: {{VALUE}} !important'
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_remove_background_hover_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove:hover' => 'background: {{VALUE}} !important',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item .woolentor-cart-img .woolentor-cart-product-remove:hover' => 'background: {{VALUE}}'
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_product_remove_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-remove a.remove:hover,{{WRAPPER}} .shop_table.cart tr.cart_item .woolentor-cart-img .woolentor-cart-product-remove:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'product_remove_icon_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],

                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'product_remove_icon_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],

                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_remove_icon_height',
                [
                    'label' => __( 'Height', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-remove' => 'height: {{SIZE}}{{UNIT}}',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'product_remove_icon_width',
                [
                    'label' => __( 'Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-remove' => 'width: {{SIZE}}{{UNIT}}',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'product_remove_icon_size',
                [
                    'label' => __( 'Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-remove' => 'font-size: {{SIZE}}{{UNIT}}',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Image
        $this->start_controls_section(
            'cart_product_image_style',
            array(
                'label' => __( 'Product Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_image_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-thumbnail img',
                ]
            );

            $this->add_responsive_control(
                'product_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_image_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-thumbnail img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_image_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-thumbnail img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_image_width',
                [
                    'label' => __( 'Image Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-thumbnail img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_image_height',
                [
                    'label' => __( 'Image Height', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-thumbnail img' => 'height: {{SIZE}}{{UNIT}}!important;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Title
        $this->start_controls_section(
            'cart_product_title_style',
            array(
                'label' => __( 'Product Title', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs( 'cart_item_style_tabs' );

                // Product Title Normal Style
                $this->start_controls_tab( 
                    'product_title_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_title_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-name' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-name a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-title a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'cart_product_title_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-name,{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-title',
                        )
                    );

                $this->end_controls_tab();

                // Product Title Hover Style
                $this->start_controls_tab( 
                    'product_title_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_title_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-name:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-name a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-thumbnail .woolentor-cart-product-title a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'cart_product_title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                    '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Price
        $this->start_controls_section(
            'cart_product_price_style',
            array(
                'label' => __( 'Product Price', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'cart_product_price_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-price' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-price .amount' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_product_price_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-price,{{WRAPPER}} .shop_table.cart tr.cart_item td.product-price .amount',
                )
            );

        $this->end_controls_section();
        
        // Product Quantity Field
        $this->start_controls_section(
            'cart_product_quantity_field_style',
            array(
                'label' => __( 'Quantity', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'quantity_button_heading',
                [
                    'label' => __( 'Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->start_controls_tabs( 'cart_qunatity_button_tab' );

                // Normal Style
                $this->start_controls_tab( 
                    'cart_quantity_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                        'condition'=>[
                            'style!' => '',
                        ],
                    ]
                );
                    
                    $this->add_control(
                        'cart_quantity_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn' => 'color: {{VALUE}}',
                            ],
                            'condition'=>[
                                'style!' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_quantity_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn' => 'background: {{VALUE}}',
                            ],
                            'condition'=>[
                                'style!' => '',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_qunatity_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn',
                            'condition'=>[
                                'style!' => '',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style
                $this->start_controls_tab( 
                    'cart_quantity_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                        'condition'=>[
                            'style!' => '',
                        ],
                    ]
                );
                    
                    $this->add_control(
                        'cart_quantity_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn:hover' => 'color: {{VALUE}}'
                            ],
                            'condition'=>[
                                'style!' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_quantity_background_hover_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn:hover' => 'background: {{VALUE}} '
                            ],
                            'condition'=>[
                                'style!' => '',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_quantity_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn:hover',
                            'condition'=>[
                                'style!' => '',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'cart_quantity_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator'     => 'before',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_quantity_button_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_quantity_button_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'quantity_field_button_width',
                [
                    'label' => __( 'Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn' => 'width: {{SIZE}}{{UNIT}}!important;',
                    ],
                    'condition'=>[
                        'style!' => '',
                        'cart_quantity_lay_out!' => 'wlqty-style-4'
                    ],
                ]
            );

            $this->add_control(
                'quantity_field_button_height',
                [
                    'label' => __( 'Height', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn' => 'height: {{SIZE}}{{UNIT}}!important;',
                    ],
                    'condition'=>[
                        'style!' => '',
                        'cart_quantity_lay_out!' => 'wlqty-style-4'
                    ],
                ]
            );

            $this->add_control(
                'quantity_field_button_fontsize',
                [
                    'label' => __( 'Font Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity button.woolentor-cart-product-quantity-btn i' => 'font-size: {{SIZE}}{{UNIT}}!important;',
                    ],
                    'condition'=>[
                        'style!' => '',
                        'cart_quantity_lay_out!' => 'wlqty-style-4'
                    ],
                ]
            );

            $this->add_control(
                'quantity_field_heading',
                [
                    'label' => __( 'Input Field', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'=>[
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'cart_quantity_field_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-quantity input[type=number]' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_quantity_field_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-quantity input[type=number]',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_quatity_input_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-quantity input[type=number]',
                )
            );

            $this->add_responsive_control(
                'cart_quantity_input_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity .quantity input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_quantity_input_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity .quantity input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '.theme-blocksy {{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity .quantity input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'quantity_field_input_width',
                [
                    'label' => __( 'Field Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity .quantity input' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'quantity_field_input_height',
                [
                    'label' => __( 'Field Height', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity .quantity input' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'style!' => '',
                    ]
                ]
            );

            $this->add_control(
                'quantity_box_heading',
                [
                    'label' => __( 'Quantity Box', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                    'condition'=>[
                        'style!' => '',
                        'cart_quantity_lay_out' => 'wlqty-style-3'
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_quantity_box_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity.wlqty-style-3',
                    'condition'=>[
                        'style!' => '',
                        'cart_quantity_lay_out' => 'wlqty-style-3'
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_quantity_box_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity.wlqty-style-3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'style!' => '',
                        'cart_quantity_lay_out' => 'wlqty-style-3'
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_quantity_box_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .woolentor-cart-product-quantity.wlqty-style-3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'style!' => '',
                        'cart_quantity_lay_out' => 'wlqty-style-3'
                    ],
                ]
            );

        $this->end_controls_section();

        // Meta data
        $this->start_controls_section(
            'cart_table_meta_data',
            array(
                'label' => __( 'Meta Data', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'style!' => '',
                ],
            )
        );
            $this->add_responsive_control(
                'cart_product_meta_data_spacing',
                [
                    'label' => __( 'Spacing', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'description' => 'Only for SKU and Category',
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-cats .posted_in a:nth-child(2)' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-id-wraper p' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'after',
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'heading_cart_product_meta_data_label',
                    [
                    'label' => __( 'Label', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_cart_product_meta_data_label_typo',
                    'selector' => '{{WRAPPER}} .wl-variation-key,{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-id-wraper label,{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .woolentor-cart-cats .posted_in label',
                ]
            );

            $this->add_control(
                'cart_product_meta_data_label_color',
                [
                    'label'=> __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-variation-key' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-id-wraper label' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .woolentor-cart-cats .posted_in label' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'heading_cart_product_meta_data_value',
                    [
                    'label' => __( 'Value', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_cart_product_meta_data_value_typo',
                    'selector' => '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-id,{{WRAPPER}} .wl-variation-value,{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .woolentor-cart-cats .posted_in a',
                ]
            );

            $this->add_control(
                'cart_product_meta_data_value_color',
                [
                    'label'=> __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .product-thumbnail .woolentor-cart-product-id' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .wl-variation-value' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 tr.cart_item .woolentor-cart-cats .posted_in a' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Meta data - variations
        $this->start_controls_section(
            'cart_table_product_meta_data_variations',
            array(
                'label' => __( 'Meta Data - Variations  ', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'style!' => '',
                ],
            )
        );
            $this->add_responsive_control(
                'cart_table_product_meta_data_variations_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .product-thumbnail .woolentor-cart-product-content .wl-variations' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_table_product_meta_data_variations_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .product-thumbnail .woolentor-cart-product-content .wl-variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Meta data - variations
        $this->start_controls_section(
            'cart_table_product_meta_data_sku',
            array(
                'label' => __( 'Meta Data - SKU  ', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'style!' => '',
                ],
            )
        );
            $this->add_responsive_control(
                'cart_table_product_meta_data_sku_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .product-thumbnail .woolentor-cart-product-content .woolentor-cart-product-id-wraper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_table_product_meta_data_sku_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_cart_table.wl-cart-style-1 .product-thumbnail .woolentor-cart-product-content .woolentor-cart-product-id-wraper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Price Total
        $this->start_controls_section(
            'cart_product_subtotal_price_style',
            array(
                'label' => __( 'Total Price', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'cart_product_subtotal_price_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-subtotal' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_product_subtotal_price_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart tr.cart_item td.product-subtotal',
                )
            );

        $this->end_controls_section();

        // Update cart
        $this->start_controls_section(
            'cart_update_button_style',
            array(
                'label' => __( 'Update Cart Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_update_button'=>'yes',
                ],
            )
        );

            $this->start_controls_tabs( 'cart_update_style_tabs' );

                // Product Title Normal Style
                $this->start_controls_tab( 
                    'cart_update_button_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_update_button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_update_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'cart_update_button_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_update_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Update cart button hover style
                $this->start_controls_tab( 
                    'cart_update_button_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_update_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_update_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_update_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions .wl_update_cart_shop input.button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Continue Button Style
        $this->start_controls_section(
            'cart_continue_button_style',
            array(
                'label' => __( 'Continue Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_continue_button'=>'yes',
                ],
            )
        );

            $this->start_controls_tabs( 'cart_continue_style_tabs' );

                // Continue Button Normal Style
                $this->start_controls_tab( 
                    'cart_continue_button_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_continue_button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_continue_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'cart_continue_button_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_continue_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Cart continue Button hover style
                $this->start_controls_tab( 
                    'cart_continue_button_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_continue_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_continue_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_continue_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_continue_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart td.actions a.wlbutton-continue-shopping:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Apply coupon
        $this->start_controls_section(
            'cart_coupon_style',
            array(
                'label' => __( 'Apply coupon', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_coupon_form'=>'yes',
                ],
            )
        );

            $this->add_control(
                'cart_coupon_button_heading',
                [
                    'label' => __( 'Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_coupon_button_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart td.actions .coupon .button',
                )
            );

            $this->add_control(
                'cart_coupon_button_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_coupon_button_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_button_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart td.actions .coupon .button',
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_button_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_button_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );


            $this->add_control(
                'cart_coupon_button_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_coupon_button_hover_bg_color',
                [
                    'label' => __( 'Hover Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon .button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_hover_button_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart td.actions .coupon .button:hover',
                ]
            );

            $this->add_control(
                'cart_coupon_inputbox_heading',
                [
                    'label' => __( 'Input Box', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'cart_coupon_inputbox_color',
                [
                    'label' => __( 'Input Box Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_coupon_inputbox_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_inputbox_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text',
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_width',
                [
                    'label' => __( 'Input Box Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 140,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart td.actions .coupon input.input-text' => 'width: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'global_font_typography_section',
            [
                'label' => __('Global Font Family', 'woolentor-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'global_font_typography',
                [
                    'label'       => __( 'Font Family', 'woolentor-pro' ),
                    'description' => __('Set a specific font family for this widget.', 'woolentor-pro'),
                    'type'        => Controls_Manager::FONT,
                    'default'     => '',
                    'selectors' => [
                        '{{WRAPPER}} *:not(i)' => 'font-family: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section();

    }

    protected function render() {
        $settings  = $this->get_settings_for_display();
        
        if( 'wl-cart-style-1' == $settings['style'] ){
            $table_items = ( isset( $settings['table_item_list_2'] ) ? $settings['table_item_list_2'] : array() );
        }else{
            $table_items = ( isset( $settings['table_item_list'] ) ? $settings['table_item_list'] : array() );
        }

        // Cart Option
        $cart_table_opt = array(
            'cart_layout_sytle'  => $settings['style'],
            'qty_layout_sytle'   => $settings['cart_quantity_lay_out'],
            'remove_icon_style'  => $settings['cart_remove_icon_style'],
            'remove_icon'        => $settings['remove_icon'],
            'remove_icon_position'  => $settings['remove_icon_position'],
            'thumbnail_remove_icon' => $settings['delete_remove_icon'],
            'thumbnail_content_alignment' => $settings['thumbnail_content_lineup'],
            'quantity_plus_icon' => $settings['quantity_plus_icon'],
            'quantity_minus_icon' => $settings['quantity_minus_icon'],
            'update_cart_button' => array(
                'enable'    => $settings['show_update_button'],
                'button_txt'=> $settings['update_cart_button_txt'],
            ),
            'continue_shop_button'=> array(
                'enable'    => $settings['show_continue_button'],
                'button_txt'=> $settings['continue_button_txt'],
            ),
            'coupon_form' => array(
                'enable'        => $settings['show_coupon_form'],
                'button_txt'    => $settings['coupon_form_button_txt'],
                'placeholder'   => $settings['coupon_form_pl_txt'],
            ),
            'extra_options' => array(
                'disable_qtn'   => $settings['disable_user_adj_qtn'],
                'remove_link'   => $settings['remove_product_link'],
                'show_category' => $settings['show_product_category'],
                'show_sku'      => $settings['show_product_sku'],
                'show_variation'=> $settings['show_product_watch_variation'],
                'show_stock'    => $settings['show_product_stock'],
            ),
        );

        $by_one_get_one = [
            'bought_id' => !empty( $settings['brougth_pro_id'] ) ? $settings['brougth_pro_id'] : '',
            'gifted_id' => !empty( $settings['gifted_pro_id'] ) ? $settings['gifted_pro_id'] : ''
        ];



        if( class_exists('\WC_Shortcode_Cart') ){
            WooLentor_Shortcode_Cart::byOneGetone( $by_one_get_one );
            if( $settings['custom_price_discount_rule'] === 'yes' ){
                add_action( 'woocommerce_before_calculate_totals', [ $this, 'quantity_based_pricing' ], 9999 );
            }
            WooLentor_Shortcode_Cart::output( $atts = array(), $table_items, $cart_table_opt );
        }
        
    }

    public function quantity_based_pricing( $cart ) {

        if ( is_admin() && !defined( 'DOING_AJAX' ) ) return;

        $discount_rule_list = $this->get_settings_for_display('discount_rule_list');

        if( isset( $discount_rule_list ) && is_array( $discount_rule_list ) && count( $discount_rule_list ) > 0 ){
            foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
                $cart_item['data']->set_price( $this->apply_discount( $discount_rule_list, $cart_item ) );
            }
        }
        
    }

    /**
     * [apply_discount]
     * @param  [array] $threshold Discount data array
     * @param  [array] $cart_item cart data
     * @return [int]   price
     */
    public function apply_discount( $threshold, $cart_item ){

        $pre_price = $price = $cart_item['data']->get_price();
        foreach ( $threshold as $key => $data ) {
            if( $cart_item['quantity'] >= $data['discount_qtn'] && !empty( $data['discount_amount'] ) ){
                if( $data['discount_type'] === 'amount' ){
                    $price = $pre_price - ( $data['discount_amount'] / $cart_item['quantity'] );
                }else{
                    $price = round( $pre_price * ( 1 - ( $data['discount_amount'] / 100 ) ), 2 );
                }
            }
        }
        return $price;

    }


}


/**
 * Cart Shortcode
 *
 * Used on the cart page, the cart shortcode displays the cart contents and interface for coupon codes and other cart bits and pieces.
 *
 * @package WooCommerce/Shortcodes/Cart
 * @version 2.3.0
 */
if( class_exists('\WC_Shortcode_Cart') ){
    class WooLentor_Shortcode_Cart extends \WC_Shortcode_Cart{
        /**
         * Output the cart shortcode.
         */
        public static function output( $atts = '', $cartitem = [], $cartopt = [] ) {
            // Constants.
            wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

            $atts        = shortcode_atts( array(), $atts, 'woocommerce_cart' );
            $nonce_value = wc_get_var( $_REQUEST['woocommerce-shipping-calculator-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

            // Update Shipping. Nonce check uses new value and old value (woocommerce-cart). @todo remove in 4.0.
            if ( ! empty( $_POST['calc_shipping'] ) && ( wp_verify_nonce( $nonce_value, 'woocommerce-shipping-calculator' ) || wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) ) { // WPCS: input var ok.
                
                //self::calculate_shipping();

                // Also calc totals before we check items so subtotals etc are up to date.
                \WC()->cart->calculate_totals();
            }

            // Check cart items are valid.
            do_action( 'woocommerce_check_cart_items' );

            // Calc totals.
            \WC()->cart->calculate_totals();

            if ( \WC()->cart->is_empty() ) {
                wc_get_template( 'cart/cart-empty.php');
            } else {
                if( 'wl-cart-style-1' === $cartopt['cart_layout_sytle']){
                    if( file_exists( WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table-2.php' ) ){
                        include WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table-2.php';
                    }
                }else{
                    if( file_exists( WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table.php' ) ){
                        include WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart/cart-table.php';
                    }
                }
            }

        }

        // By One get one apply
        public static function byOneGetone( $by_one_get_one ){

            $product_bought_id = !empty( $by_one_get_one['bought_id'] ) ? $by_one_get_one['bought_id'] : '';
            $product_gifted_id = !empty( $by_one_get_one['gifted_id'] ) ? $by_one_get_one['gifted_id'] : '';

            if( !empty( $product_gifted_id ) ){

                // see if product id in cart
                $product_bought_cart_id = \WC()->cart->generate_cart_id( $product_bought_id );
                $product_bought_in_cart = \WC()->cart->find_product_in_cart( $product_bought_cart_id );

                // see if gift id in cart
                $product_gifted_cart_id = \WC()->cart->generate_cart_id( $product_gifted_id );
                $product_gifted_in_cart = \WC()->cart->find_product_in_cart( $product_gifted_cart_id );


                // if not in cart remove gift, else add gift
                if ( ! $product_bought_in_cart ) {
                    if ( $product_gifted_in_cart ) \WC()->cart->remove_cart_item( $product_gifted_in_cart );
                } else {
                    if ( ! $product_gifted_in_cart ) \WC()->cart->add_to_cart( $product_gifted_id );
                }

            }

        }


    }
}