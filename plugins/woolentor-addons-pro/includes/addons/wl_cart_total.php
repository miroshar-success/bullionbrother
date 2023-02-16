<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Cart_Total_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-cart-total';
    }

    public function get_title() {
        return __( 'WL: Cart Total', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
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
        return ['cart total','total','cart'];
    }

    protected function register_controls() {

        // Cart Total Content
        $this->start_controls_section(
            'cart_total_content',
            [
                'label' => esc_html__( 'Cart Total', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'default_layout',
                [
                    'label' => esc_html__( 'Default', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'description'=> esc_html__('If you choose yes then layout are come from your theme/WooCommerce Plugin','woolentor-pro'),
                ]
            );

            $this->add_control(
                'cart_total_layout',
                [
                    'label'   => __( 'Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style 1', 'woolentor-pro' ),
                        '2'   => __( 'Style 2', 'woolentor-pro' ),
                    ],
                    'condition'=>[
                        'default_layout' => '',
                    ],
                ]
            );

            $this->add_control(
                'section_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Cart totals', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Cart totals', 'woolentor-pro' ),
                    'condition'=>[
                        'default_layout!'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'subtotal_heading',
                [
                    'label' => esc_html__( 'Sub total heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Subtotal', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Subtotal', 'woolentor-pro' ),
                    'condition'=>[
                        'default_layout!'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'total_heading',
                [
                    'label' => esc_html__( 'Total heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Total', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Total', 'woolentor-pro' ),
                    'condition'=>[
                        'default_layout!'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'proceed_to_checkout',
                [
                    'label' => esc_html__( 'Proceed To Checkout Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Proceed to checkout', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Proceed to checkout', 'woolentor-pro' ),
                    'condition'=>[
                        'default_layout!'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

        $this->end_controls_section();
        
        // Heading
        $this->start_controls_section(
            'cart_total_heading_style',
            array(
                'label' => __( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_total_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .cart_totals > h2',
                )
            );
            $this->add_control(
                'cart_total_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals > h2' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_total_heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals > h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_total_heading_align',
                [
                    'label'        => __( 'Alignment', 'woolentor-pro' ),
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
                    'prefix_class' => 'elementor%s-align-',
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals > h2' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();
        
        // Table
        $this->start_controls_section(
            'table_style',
            array(
                'label' => __( 'Table', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'table_border',
                    'selector' => '{{WRAPPER}} .cart_totals .shop_table',
                ]
            );

        $this->end_controls_section();

        // Cart Total Table cell
        $this->start_controls_section(
            'cart_total_table_style',
            array(
                'label' => __( 'Table Cell', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'cart_total_table_link_color',
                [
                    'label' => __( 'Link Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} td a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_total_table_border',
                    'selector' => '{{WRAPPER}} .cart_totals .shop_table tr th, {{WRAPPER}} .cart_totals .shop_table tr td',
                ]
            );
        
            $this->add_responsive_control(
                'cart_total_table_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr th, {{WRAPPER}} .cart_totals .shop_table tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        
            $this->add_responsive_control(
                'cart_total_table_align',
                [
                    'label'        => __( 'Alignment', 'woolentor-pro' ),
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
                    'prefix_class' => 'elementor%s-align-',
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr th, {{WRAPPER}} .cart_totals .shop_table tr td' => 'text-align: {{VALUE}}',
                    ],
                    'condition' => [
                        'cart_total_layout' => '1',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'cart_total_table_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .cart_totals .shop_table',
                ]
            );

        $this->end_controls_section();

        // Cart Total Table heading
        $this->start_controls_section(
            'cart_total_table_heading_style',
            array(
                'label' => __( 'Table Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_control(
                'cart_total_table_heading_text_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_total_table_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .cart_totals .shop_table tr th',
                )
            );

            $this->add_responsive_control(
                'cart_total_table_heading_align',
                [
                    'label'        => __( 'Alignment', 'woolentor-pro' ),
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
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr th' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_table_verticle_align',
                [
                    'label' => __('Vertical Alignment', 'woolentor-pro'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'top' => [
                            'title' => __('Top', 'woolentor-pro'),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'middle' => [
                            'title' => __('Center', 'woolentor-pro'),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __('Bottom', 'woolentor-pro'),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'top',
                    'toggle' => false,
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr th' => 'vertical-align:{{VALUE}};'
                    ]
                ]
            );

            $this->add_responsive_control(
                'cart_total_table_headin_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

         // Cart Total Price
        $this->start_controls_section(
            'cart_total_table_price_style',
            array(
                'label' => __( 'Price', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'cart_total_table_heading',
                [
                    'label' => __( 'Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_total_table_subtotal_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .cart_totals .shop_table tr.cart-subtotal td',
                )
            );

            $this->add_control(
                'cart_total_table_subtotal_color',
                [
                    'label' => __( 'Subtotal Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr.cart-subtotal td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_table_totalprice_heading',
                [
                    'label' => __( 'Total Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_total_table_total_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .cart_totals .shop_table tr.order-total th, {{WRAPPER}} .cart_totals .shop_table tr.order-total td .amount',
                )
            );

            $this->add_control(
                'cart_total_table_total_color',
                [
                    'label' => __( 'Total Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr.order-total th' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .cart_totals .shop_table tr.order-total td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_table_shipping_heading',
                [
                    'label' => __( 'Shipping', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'cart_total_table_shipping_color',
                [
                    'label' => __( 'Shipping Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .cart_totals .shop_table tr.shipping th' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .cart_totals .shop_table tr.shipping td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cart_total_table_shipping_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .cart_totals .shop_table tr.shipping th, {{WRAPPER}} .cart_totals .shop_table tr.shipping td',
                )
            );

        $this->end_controls_section();

        // Radio Button
        $this->start_controls_section(
            'radio_button_style',
            array(
                'label' => __( 'Radio Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'radio_button_active_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} li input[type="radio"]' => 'accent-color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'radio_button_width',
                [
                    'label' => __( 'Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} li input[type="radio"]' => 'border:0; width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'radio_button_height',
                [
                    'label' => __( 'Height', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} li input[type="radio"]' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'radio_button_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        'body {{WRAPPER}} ul#shipping_method li input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
        
        // Checkout button
        $this->start_controls_section(
            'cart_total_checkout_button_style',
            array(
                'label' => __( 'Checkout Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_responsive_control(
                'button_width',
                [
                    'label' => __( 'Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 50,
                            'max' => 500,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        'body {{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs( 'cart_total_checkout_button_style_tabs' );
        
                $this->start_controls_tab( 
                    'cart_total_checkout_button_style_normal',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'cart_total_checkout_button_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_total_checkout_button_border',
                            'label' => __( 'Button Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button',
                        ]
                    );

                    $this->add_control(
                        'cart_total_checkout_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_total_checkout_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                
                    $this->add_control(
                        'cart_total_checkout_button_text_color',
                        [
                            'label' => __( 'Text Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                
                    $this->add_control(
                        'cart_total_checkout_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'cart_total_checkout_button_box_shadow',
                            'selector' => '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_total_checkout_button_alignment',
                        [
                            'label'        => __( 'Alignment', 'woolentor-pro' ),
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
                            'prefix_class' => 'wl-heading-alignment%s-',
                            'selectors' => [
                                'body {{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button' => 'float: {{VALUE}}; margin-{{VALUE}}: 0px;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_total_checkout_button_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .cart_totals.wl-style--2 .wc-proceed-to-checkout' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .cart_totals.wl-style--1 .wc-proceed-to-checkout' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
            
                $this->end_controls_tab();
        
                $this->start_controls_tab( 
                    'cart_total_checkout_button_style_hover',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                
                    $this->add_control(
                        'cart_total_checkout_button_hover_text_color',
                        [
                            'label' => __( 'Text Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                
                    $this->add_control(
                        'cart_total_checkout_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );
                
                    $this->add_control(
                        'cart_total_checkout_button_hover_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button:hover' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_total_checkout_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'cart_total_checkout_button_hover_box_shadow',
                            'selector' => '{{WRAPPER}} .wc-proceed-to-checkout .button.checkout-button:hover',
                        ]
                    );
                
                $this->end_controls_tab();
        
            $this->end_controls_tabs();
        
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

        if( Plugin::instance()->editor->is_edit_mode() ){
            if( $settings['cart_total_layout'] ){
                wc_get_template(
                    'cart/cart-totals.php', 
                    array(
                        'config' => $settings
                    ),
                    '/wl-woo-templates/',
                    WOOLENTOR_ADDONS_PL_PATH_PRO. '/wl-woo-templates/'
                );
            } else {
                wc_get_template( 'cart/cart-totals.php' );
            }
        } else {
            woocommerce_cart_totals();
        }
    }
}