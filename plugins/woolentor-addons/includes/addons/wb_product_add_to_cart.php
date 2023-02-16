<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Add_To_Cart_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-add-to-cart';
    }
    
    public function get_title() {
        return __( 'WL: Add To cart', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-add-to-cart';
    }

    public function get_categories() {
        return array( 'woolentor-addons' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets',
            'elementor-icons-shared-0-css',
            'elementor-icons-fa-brands',
            'elementor-icons-fa-regular',
            'elementor-icons-fa-solid'
        ];
    }

    public function get_keywords(){
        return ['add to cart','cart','button','buy now'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_advance_single_addtocart_content',
            array(
                'label' => __( 'Layout', 'woolentor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );

            $this->add_control(
                'single_product_advance_layout_style',
                [
                    'label' => __( 'Layout', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'wl-style-1',
                    'options' => [
                        'wl-style-1' => __( 'Default', 'woolentor' ),
                        'wl-style-2' => __( 'Layout One', 'woolentor' ),
                        'wl-style-3' => __( 'Layout Two', 'woolentor' ),
                        'wl-style-4' => __( 'Layout Three', 'woolentor' ),
                        'wl-style-5' => __( 'Layout Four', 'woolentor' ),
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_advance_single_addtocart_content_settings',
            array(
                'label' => __( 'Settings', 'woolentor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                        ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                    ]
                ],
            )
        );

            $this->add_control(
                'advance_cart_quantity_text',
                [
                    'label'   => __( 'Quantity Text', 'woolentor' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => 'Quantity',
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-3'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'quantity_plus_icon',
                [
                    'label'       => esc_html__( 'Plus Icon', 'woolentor' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-plus',
                        'library' => 'solid',
                    ],
                    'label_block' => true,
                    'fa4compatibility' => 'buttonicon'
                ]
            );


            $this->add_control(
                'qunantity_minus_icon',
                [
                    'label'       => esc_html__( 'Minus Icon', 'woolentor' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-minus',
                        'library' => 'solid',
                    ],
                    'label_block' => true,
                    'fa4compatibility' => 'buttonicon'
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'add_to_cart_advance_quaantity_style',
            [
                'label' => __( 'Quantity', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'add_to_cart_quantity_label',
                [
                    'label' => __( 'Label', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'text_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'description' => __('Only for Quantity Label.','woolentor'),
                    'selector' => '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .label,{{WRAPPER}} .wl-addto-cart.wl-style-3 form.cart .wl-quantity-wrap .label,{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-quantity-wrap .label',
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-3'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                        ]
                    ],
                ]
            );


            $this->start_controls_tabs(
                'advance_quantity_style_tabs',
                ['separator' => 'before']
            );
                // Normal Style Tab
                $this->start_controls_tab(
                    'style_arvance_quantity_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'advance_quantity_minus_icon_color',
                        [
                            'label'     => __( 'Minus Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity.wl-qunatity-minus' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity.wl-qunatity-minus' => 'color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'relation' => 'or',
                                'terms' => [
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                                ]
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_plus_icon_color',
                        [
                            'label'     => __( 'Plus Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity.wl-qunatity-plus' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity.wl-qunatity-plus' => 'color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'relation' => 'or',
                                'terms' => [
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                                ]
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_number_color',
                        [
                            'label'     => __( 'Quantity Number', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-1 .quantity input[type=number]' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 .quantity input[type=number]' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-3 .quantity input[type=number]' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-4 .quantity input[type=number]' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 .quantity input[type=number]' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_background_color',
                        [
                            'label'     => __( 'Quantity Backgeound', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity-cal' => 'background: {{VALUE}}',
                            ],
                            'condition'=>[
                                'single_product_advance_layout_style' => 'wl-style-4',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_text_color',
                        [
                            'label'     => __( 'Qunantity Text Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap span.label' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-3 form.cart .wl-quantity-wrap span.label' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-quantity-wrap span.label' => 'color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'relation' => 'or',
                                'terms' => [
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-3'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                                ]
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style Tab
                $this->start_controls_tab(
                    'style_arvance_quantity_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'advance_quantity_hover_minus_icon_color',
                        [
                            'label'     => __( 'Minus Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity.wl-qunatity-minus:hover' => 'color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'relation' => 'or',
                                'terms' => [
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                                ]
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_hover_plus_icon_color',
                        [
                            'label'     => __( 'Plus Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity.wl-qunatity-plus:hover' => 'color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'relation' => 'or',
                                'terms' => [
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                                ]
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_hover_number_color',
                        [
                            'label'     => __( 'Quantity Number', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-1 .quantity input[type=number]:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 .quantity input[type=number]:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-3 .quantity input[type=number]:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-4 .quantity input[type=number]:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 .quantity input[type=number]:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_text_hover_color',
                        [
                            'label'     => __( 'Qunantity Text Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap span.label:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-3 form.cart .wl-quantity-wrap span.label:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-quantity-wrap span.label:hover' => 'color: {{VALUE}}',
                            ],
                            'conditions' => [
                                'relation' => 'or',
                                'terms' => [
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-3'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                                ]
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_quantity_background_hover_color',
                        [
                            'label'     => __( 'Quantity Backgeound', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity-cal:hover' => 'background: {{VALUE}}',
                            ],
                            'condition'=>[
                                'single_product_advance_layout_style' => 'wl-style-4',
                            ],
                        ]
                    );

                $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'advance_quantity_border',
                    'label' => __( 'Quantity Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity-cal,{{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity-cal,{{WRAPPER}} .wl-addto-cart.wl-style-1 .quantity input[type=number],{{WRAPPER}} .wl-addto-cart.wl-style-3 .quantity input[type=number],{{WRAPPER}} .wl-addto-cart.wl-style-5 .quantity input[type=number]',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'advance_add_to_cart_quantity_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'size_units' => [ 'px', '%' ],
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity-cal' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity-cal' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-1 .quantity input[type=number]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-3 .quantity input[type=number]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-5 .quantity input[type=number]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'advance_qunatity_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity-cal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity-cal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-3 .quantity input[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-1 .quantity input[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-5 .quantity input[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'single_product_quantity_spacing',
                [
                    'label' => esc_html__( 'Spacing', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 5,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 80,
                    ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity-cal' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'single_product_advance_layout_style' => 'wl-style-2',
                    ],
                ]
            );

            $this->add_control(
                'single_product_quantity_button_size',
                [
                    'label' => esc_html__( 'Font Size', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' =>  0,
                            'max' =>  100,
                            'step' => 1,
                        ],
                    ],
                    'description' => __('Only for quantity plus, minus button and input number','woolentor'),
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-quantity-wrap .wl-quantity-cal .wl-quantity' => 'font-size: {{SIZE}}{{UNIT}};',
                        '.woocommerce {{WRAPPER}} .wl-addto-cart.wl-style-4 form.cart .wl-quantity-wrap .wl-quantity-cal .wl-quantity' => 'font-size: {{SIZE}}{{UNIT}};',
                        '.woocommerce {{WRAPPER}} .wl-addto-cart form.cart .wl-quantity-wrap .wl-quantity-cal .quantity input' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'add_to_cart_advance_icon_style',
            [
                'label' => __( 'Button', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                        ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                    ]
                ],
            ]
        );

            $this->add_control(
                'hide_advance_cart_wishlist_icon',
                [
                    'label'     => __( 'Hide Wishlist', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->add_control(
                'hide_advance_cart_compare_icon',
                [
                    'label'     => __( 'Hide Compare', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->add_control(
                'product_wishlist_compare_btn_position',
                [
                    'label' => __( 'Position', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'description' => __('Only for wishlist and compare button.','woolentor'),
                    'default' => 'both',
                    'options' => [
                        'before' => __( 'Befor Add to Cart', 'woolentor' ),
                        'after' => __( 'After Add to Cart', 'woolentor' ),
                        'both' => __( 'Both Side of Add to Cart', 'woolentor' ),
                    ],
                    'conditions' => [
                        'relation' => 'and',
                        'terms' =>[
                            [
                                'relation' => 'or',
                                'terms' => [
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                                    ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                                ]
                            ],
                            [
                                'relation' => 'and',
                                'terms' => [
                                    ['name' => 'hide_advance_cart_wishlist_icon', 'operator' => '===', 'value' => ''],
                                    ['name' => 'hide_advance_cart_compare_icon', 'operator' => '===', 'value' => ''],
                                ]
                            ]
                        ],
                    ],
                ]
            );

            $this->start_controls_tabs(
                'advance_button_icon_tabs',
                [
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'hide_advance_cart_wishlist_icon', 'operator' => '===', 'value' => ''],
                            ['name' => 'hide_advance_cart_compare_icon', 'operator' => '===', 'value' => ''],
                        ]
                    ],
                ]
            );
                // Normal Style Tab
                $this->start_controls_tab(
                    'style_advance_cart_icon_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' )
                    ]
                );

                    $this->add_control(
                        'advance_cart_icon_wishlist_color',
                        [
                            'label'     => __( 'Wishlist Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.wishlist a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.wishlist a' => 'color: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_wishlist_icon' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_cart_icon_wishlist_background',
                        [
                            'label'     => __( 'Wishlist Background Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.wishlist' => 'background: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.wishlist' => 'background: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_wishlist_icon' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_cart_icon_compare_color',
                        [
                            'label'     => __( 'Compare Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.compare' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.compare' => 'color: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_compare_icon' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_cart_icon_compare_background',
                        [
                            'label'     => __( 'Compare Background Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.compare' => 'background: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.compare' => 'background: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_compare_icon' => '',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'advance_cart_icon_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon,{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon',
                        ]
                    );

                    $this->add_responsive_control(
                        'advance_cart_icon_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'size_units' => [ 'px', '%' ],
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-wrap .wl-cart-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-wrap .wl-cart-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style Tab
                $this->start_controls_tab(
                    'style_advance_icon_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' )
                    ]
                );

                    $this->add_control(
                        'advance_cart_hover_icon_wishlist_color',
                        [
                            'label'     => __( 'Wishlist Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.wishlist:hover > a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.wishlist:hover > a' => 'color: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_wishlist_icon' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_cart_hover_icon_wishlist_background',
                        [
                            'label'     => __( 'Wishlist Background Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.wishlist:hover' => 'background: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.wishlist:hover' => 'background: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_wishlist_icon' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_cart_hover_icon_compare_color',
                        [
                            'label'     => __( 'Compare Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.compare:hover > a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.compare:hover > a' => 'color: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_compare_icon' => '',
                            ],
                        ]
                    );

                    $this->add_control(
                        'advance_cart_hover_icon_compare_background',
                        [
                            'label'     => __( 'Compare Background Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon.compare:hover' => 'background: {{VALUE}}',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.compare:hover' => 'background: {{VALUE}}',
                            ],
                            'condition'=>[
                                'hide_advance_cart_compare_icon' => '',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'advance_cart_hover_icon_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon:hover,{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'advance_cart_hover_icon_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'product_wishlist_compare_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                        ]
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_wishlist_compare_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'product_wishlist_compare_fonsize',
                [
                    'label' => __( 'Font Size', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-cart-icon.wishlist a svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon.wishlist .wishsuite-button svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .wl-cart-icon.compare a' => 'font-size: {{SIZE}}{{UNIT}}',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'product_wishlist_compare_widht',
                [
                    'label' => __( 'Width', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon' => 'width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                        ]
                    ],
                ]
            );

            $this->add_control(
                'product_wishlist_compare_height',
                [
                    'label' => __( 'Height', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-addto-cart.wl-style-2 form.cart .wl-cart-icon' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-5 form.cart .wl-cart-icon' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-2'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-5'],
                        ]
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'add_to_cart_advance_text_style',
            [
                'label' => __( 'Button', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-3'],
                        ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                    ]
                ],
            ]
        );


            $this->add_control(
                'hide_advance_cart_wishlist_text',
                [
                    'label'     => __( 'Hide Wishlist', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->add_control(
                'hide_advance_cart_compare_text',
                [
                    'label'     => __( 'Hide Compare', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->start_controls_tabs(
                'advance_add_to_cart_icon_text_style_tabs'
            );
                // Normal Style Tab
                $this->start_controls_tab(
                    'advance_cart_icon_text_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'advance_quantity_cart_icon_text_color',
                        [
                            'label'     => __( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-wishlist-compare-txt li a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-wishlist-compare-txt li span' => 'color: {{VALUE}}',
                            ]
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style Tab
                $this->start_controls_tab(
                    'advance_quantity_cart_icon_text_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'advance_quantity_cart_icon_text_hover_color',
                        [
                            'label'     => __( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-wishlist-compare-txt li:hover a' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-wishlist-compare-txt li:hover span' => 'color: {{VALUE}}',
                            ]
                        ]
                    );

                $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_control(
                'product_wishlist_compare_button',
                [
                    'label' => __( 'Font Size', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-wishlist-compare-txt li a' => 'font-size: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .wl-wishlist-compare-txt li a svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .wl-wishlist-compare-txt li span' => 'font-size: {{SIZE}}{{UNIT}}',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-3'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                        ]
                    ],
                ]
            ); 

            $this->add_control(
                'product_wishlist_compare_txt_spacing',
                [
                    'label' => __( 'Space Between', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-addto-cart.wl-style-4 span.wishsuite-btn-text' => 'margin-left: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-3 span.wishsuite-btn-text' => 'margin-left: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-4 .htcompare-btn.woolentor-compare' => 'margin-left: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .wl-addto-cart.wl-style-3 .htcompare-btn.woolentor-compare' => 'margin-left: {{SIZE}}{{UNIT}}',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-3'],
                            ['name' => 'single_product_advance_layout_style', 'operator' => '===', 'value' => 'wl-style-4'],
                        ]
                    ],
                ]
            ); 
        $this->end_controls_section();

        $this->start_controls_section(
            'add_to_cart_button_style',
            [
                'label' => __( 'Add to Cart', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'hide_quantity_field',
                [
                    'label'     => __( 'Hide Quantity Field', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .quantity,{{WRAPPER}} form.cart input[type=number]' => 'display: none !important;',
                        '{{WRAPPER}} .wl-addto-cart form.cart .wl-quantity-wrap' => 'display: none;',
                    ],
                ]
            );

            $this->start_controls_tabs('button_normal_style_tabs');
                
                // Button Normal tab
                $this->start_controls_tab(
                    'button_normal_style_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'button_color',
                        [
                            'label'     => __( 'Text Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl-style-1 form.cart button' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'button_typography',
                            'label'     => __( 'Typography', 'woolentor' ),
                            'selector'  => '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button,{{WRAPPER}} .wl-style-1 form.cart button',
                        )
                    );

                    $this->add_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .wl-style-1 form.cart button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_margin',
                        [
                            'label' => __( 'Margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} form.cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button,{{WRAPPER}} .wl-style-1 form.cart button',
                        ]
                    );

                    $this->add_control(
                        'button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .wl-style-1 form.cart button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}} .wl-style-1 form.cart button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Button Hover tab
                $this->start_controls_tab(
                    'button_hover_style_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                ); 
                    
                    $this->add_control(
                        'button_hover_color',
                        [
                            'label'     => __( 'Text Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .wl-style-1 form.cart button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button:hover' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}} .wl-style-1 form.cart button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-addto-cart[class*="wl-style-"] form.cart button:hover' => 'border-color: {{VALUE}}',
                                '{{WRAPPER}} .wl-style-1 form.cart button:hover' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            
        $this->end_controls_section();

    }

    protected function customise_single_product_add_to_cart($settings, $porduct_type){
        $plus_icon = !empty( $settings['quantity_plus_icon']['value'] ) ? woolentor_render_icon( $settings, 'quantity_plus_icon', 'buttonicon' ) : '<i class="ion-plus"></i>';
        $minus_icon = !empty( $settings['qunantity_minus_icon']['value'] ) ? woolentor_render_icon( $settings, 'qunantity_minus_icon', 'buttonicon' ) : '<i class="ion-minus"></i>';

        if( 'grouped' != $porduct_type ){
            add_action( 'woocommerce_before_add_to_cart_quantity', function() use ($settings, $minus_icon) {
               echo '<div class="wl-quantity-wrap">';
               if(!empty($settings['advance_cart_quantity_text'])){
                    echo '<span class="label">'.$settings['advance_cart_quantity_text'].'</span>';
               }
               echo '<div class="wl-quantity-cal">';
               echo '<span class="wl-quantity wl-qunatity-minus" >'.$minus_icon.'</span>';
            });

            add_action( 'woocommerce_after_add_to_cart_quantity', function() use ($settings, $plus_icon) {
               echo '<span class="wl-quantity wl-qunatity-plus" >'.$plus_icon.'</span>';
               echo '</div>';
               echo '</div>';
               echo '<div class="wl-cart-wrap '.$settings['product_wishlist_compare_btn_position'].'">';
                if( 'wl-style-5' !== $settings['single_product_advance_layout_style']){
                    if( true === woolentor_has_wishlist_plugin() && 'yes' !== $settings['hide_advance_cart_wishlist_icon'] ){
                        echo '<span class="wl-cart-icon wishlist">'.woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>';
                    }
                }
            } );
        }else{
            add_action( 'woocommerce_before_add_to_cart_quantity', function() use ($settings, $minus_icon) {
               echo '<div class="wl-quantity-grouped-cal">';
                echo '<span class="wl-quantity wl-qunatity-minus" >'.$minus_icon.'</span>';
            });

            add_action( 'woocommerce_after_add_to_cart_quantity', function() use ($settings, $plus_icon) {
                echo '<span class="wl-quantity wl-qunatity-plus" >'.$plus_icon.'</span>';
               echo '</div>';
            } );
            add_action( 'woocommerce_before_add_to_cart_button', function() use ($settings) {
                echo '<div class="wl-cart-wrap '.$settings['product_wishlist_compare_btn_position'].'">';
                if( 'wl-style-5' !== $settings['single_product_advance_layout_style']){
                    if( true === woolentor_has_wishlist_plugin() && 'yes' !== $settings['hide_advance_cart_wishlist_icon'] ){
                        echo '<span class="wl-cart-icon wishlist">'.woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>';
                    }
                }
            });
        }

        add_action( 'woocommerce_after_add_to_cart_button', function() use ($settings, $porduct_type) {
            if( 'simple' == $porduct_type || 'grouped' == $porduct_type || 'variable' == $porduct_type){
                if( 'wl-style-5' === $settings['single_product_advance_layout_style']){
                    if( true === woolentor_has_wishlist_plugin() && 'yes' !== $settings['hide_advance_cart_wishlist_icon'] ){
                        echo '<span class="wl-cart-icon wishlist">'.woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>';
                    }
                }
                if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() && 'yes' !== $settings['hide_advance_cart_compare_icon'] ){
                    echo '<span class="wl-cart-icon compare">';
                        woolentor_compare_button(
                            array(
                                'style'=> 2,
                                'btn_text'=> '<i class="sli sli-refresh"></i>',
                                'btn_added_txt'=> '<i class="sli sli-check"></i>'
                            )
                        );
                    echo '</span>';
                }
                echo "</div>";
            }elseif ('external' == $porduct_type) {
                if( true === woolentor_has_wishlist_plugin() && 'yes' !== $settings['hide_advance_cart_wishlist_icon'] ){
                    echo '<span class="wl-cart-icon wishlist">'.woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no').'</span>';
                }
                if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() && 'yes' !== $settings['hide_advance_cart_compare_icon'] ){
                    echo '<span class="wl-cart-icon compare">';
                        woolentor_compare_button(
                            array(
                                'style'=> 2,
                                'btn_text'=> '<i class="sli sli-refresh"></i>',
                                'btn_added_txt'=> '<i class="sli sli-check"></i>'
                            )
                        );
                    echo '</span>';
                }
            }
            ?>
               <ul class="wl-wishlist-compare-txt">
                    <?php if( true === woolentor_has_wishlist_plugin() ): ?>
                        <li>
                            <?php if( '' == $settings['hide_advance_cart_wishlist_text']): ?>
                                <?php echo woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes'); ?>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                    <?php if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() ): ?>
                        <li>
                            <?php if( '' == $settings['hide_advance_cart_compare_text']): ?>
                                <span><i class="sli sli-refresh"></i></span>
                                <?php echo woolentor_compare_button(
                                        array(
                                            'style'=> 2,
                                            'btn_text'=> 'Compare',
                                            'btn_added_txt'=> 'Already Compared'
                                        )
                                    ); 
                                ?>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
               </ul> 
            <?php
        } );
    }

    protected function render() {
         $settings = $this->get_settings();
        if( Plugin::instance()->editor->is_edit_mode() ){
            $product = wc_get_product( woolentor_get_last_product_id() );
        }else{
            global $product;
        }

        $product_layout_class = $settings['single_product_advance_layout_style'];
        $poduct_type = $product ? $product->get_type() : '';  

        if( 'wl-style-1' != $product_layout_class ){
            $this->customise_single_product_add_to_cart($settings, $poduct_type);
        }  

        if ( Plugin::instance()->editor->is_edit_mode() ) {
            if('external' == $poduct_type){
                echo '<div class="wl-addto-cart '.esc_attr( $poduct_type ).' '.esc_attr( $settings['product_wishlist_compare_btn_position']).' '.esc_attr( $product_layout_class ).'">';
                    echo \WooLentor_Default_Data::instance()->default( $this->get_name() );
                echo '</div>';
            }else{
                echo '<div class="wl-addto-cart '.esc_attr( $poduct_type ).' '.esc_attr( $product_layout_class ).'">';
                    echo \WooLentor_Default_Data::instance()->default( $this->get_name() );
                echo '</div>';
            }
        }else{
            if ( empty( $product ) ) { return; }
                if('wl-style-1' == $product_layout_class):
            ?>
                <div class="<?php echo esc_attr( $product->get_type() ).' '.esc_attr( $product_layout_class ); ?>">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            <?php
               elseif('external' == $poduct_type):
            ?>
                <div class="wl-addto-cart <?php echo esc_attr( $product->get_type() ).' '.esc_attr($settings['product_wishlist_compare_btn_position']).' '.esc_attr( $product_layout_class ); ?>">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            <?php
                else:
            ?>
                <div class="wl-addto-cart <?php echo esc_attr( $product->get_type() ).' '.esc_attr( $product_layout_class ); ?>">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            <?php
                endif;
        }

        ?>
            <script type="text/javascript">
                ;jQuery(document).ready(function($){ 
                    $('form.cart').on( 'click', 'span.wl-qunatity-plus, span.wl-qunatity-minus', function() {
                        
                        // Get current quantity values
                        <?php if('grouped' != $poduct_type): ?>
                            var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
                            var val = parseFloat(qty.val());
                            var min_val = 1;
                        <?php else: ?>
                            var qty = $( this ).closest( '.wl-quantity-grouped-cal' ).find( '.qty' );
                            var val = !qty.val() ? 0 : parseFloat(qty.val());
                            var min_val = 0;
                        <?php endif; ?> 
                        var max  = parseFloat(qty.attr( 'max' ));
                        var min  = parseFloat(qty.attr( 'min' ));
                        var step = parseFloat(qty.attr( 'step' ));
             
                        // Change the value if plus or minus
                        if ( $( this ).is( '.wl-qunatity-plus' ) ) {
                           if ( max && ( max <= val ) ) {
                              qty.val( max );
                           } 
                           else{
                               qty.val( val + step );
                            }
                        } 
                        else {
                           if ( min && ( min >= val ) ) {
                              qty.val( min );
                           } 
                           else if ( val > min_val ) {
                              qty.val( val - step );
                           }
                        }
                         
                    });
                });        
            </script>
        <?php

    }

}