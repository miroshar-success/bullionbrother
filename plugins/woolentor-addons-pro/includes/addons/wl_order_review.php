<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Order_Review_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-order-review';
    }
    
    public function get_title() {
        return __( 'WL: Checkout Order Review', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-table';
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
            'woolentor-checkout',
        ];
    }

    public function get_script_depends(){
        return [
            'woolentor-checkout',
            'woolentor-widgets-scripts-pro'
        ];
    }

    public function get_keywords(){
        return ['checkout order review','order review','order table','order'];
    }

    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'form_heading_section',
            [
                'label' => __( 'Content', 'woolentor-pro' ),
            ]
        );
            $this->add_control(
                'table_title',
                [
                    'label' => __( 'Table Heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'style',
                [
                    'label'   => __( 'Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => __( 'Default', 'woolentor-pro' ),
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                    ]
                ]
            );

            $this->add_control(
                'hide_heading',
                [
                    'label'     => __( 'Hide Heading', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} #order_review_heading' => 'display: none;',
                    ],
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'hide_products',
                [
                    'label' => __( 'Hide Products', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'woolentor-pro' ),
                    'label_off' => __( 'No', 'woolentor-pro' ),
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'qty_text',
                [
                    'label' => __( '"QTY" Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => false,
                    'condition' => [
                        'style!' => '',
                        'hide_products!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'attributes_display',
                [
                    'label'   => __( 'Attribute Display', 'woolentor-pro' ),
                    'description'    => __( 'Applicable for "Variable" product.', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'inline' => [
                            'title' => __( 'Inline', 'woolentor-pro' ),
                            'icon'  => 'eicon-navigation-horizontal',
                        ],
                        'listitem' => [
                            'title' => __( 'List Item', 'woolentor-pro' ),
                            'icon'  => 'eicon-editor-list-ul',
                        ],
                    ],
                    'default'     => 'inline',
                    'toggle'      => false,
                    'condition' => [
                        'style!' => '',
                        'hide_products!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'contents_display',
                [
                    'label'   => __( 'Contents Display', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'inline' => [
                            'title' => __( 'Inline', 'woolentor-pro' ),
                            'icon'  => 'eicon-navigation-horizontal',
                        ],
                        'block' => [
                            'title' => __( 'Block', 'woolentor-pro' ),
                            'icon'  => 'eicon-editor-list-ul',
                        ],
                    ],
                    'default'     => 'block',
                    'toggle'      => false,
                    'condition' => [
                        'style!' => '',
                        'hide_products!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'hide_order_total_summery',
                [
                    'label' => __( 'Hide Order Total Summery', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'woolentor-pro' ),
                    'label_off' => __( 'No', 'woolentor-pro' ),
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

        $this->end_controls_section();

        // Heading
        $this->start_controls_section(
            'form_heading_style',
            array(
                'label' => __( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hide_heading!' => 'yes',
                ],
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} #order_review_heading',
                )
            );
            

            $this->add_control(
                'form_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} #order_review_heading' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} #order_review_heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_heading_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} #order_review_heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_heading_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} #order_review_heading',
                ]
            );

            $this->add_responsive_control(
                'form_heading_align',
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
                    'default'   => 'left',
                    'prefix_class' => 'wl-heading-alignment%s-',
                    'selectors' => [
                        '{{WRAPPER}} #order_review_heading' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Table Heading
        $this->start_controls_section(
            'checkout_order_table_heading_style',
            array(
                'label' => __( 'Table Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => '',
                ],
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'checkout_order_table_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-checkout-review-order-table th',
                )
            );

            $this->add_control(
                'checkout_order_table_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-checkout-review-order-table th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'checkout_order_table_heading_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table tr th',
                ]
            );

        $this->end_controls_section();

        // Table Content
        $this->start_controls_section(
            'checkout_order_table_content_style',
            array(
                'label' => __( 'Table Content', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => '',
                ],
            )
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'checkout_order_table_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table',
                    'fields_options' => [
                        'border'=>[
                            'label' =>__( 'Table Border', 'woolentor-pro' ),
                        ]
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'checkout_order_table_content_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-checkout-review-order-table td, {{WRAPPER}} .woocommerce-checkout-review-order-table td strong',
                )
            );

            $this->add_control(
                'checkout_order_table_content_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-checkout-review-order-table td' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-checkout-review-order-table td strong' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'checkout_order_table_content_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table tr td',
                ]
            );

        $this->end_controls_section();

            
        $this->start_controls_section(
            'product_item_style_section',
            array(
                'label' => __( 'Product Item', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hide_products!' => 'yes',
                ],
            )
        );  

            $this->add_control(
                'product_price_heading',
                [
                    'label' => __( 'Product Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'product_price_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-product-price-value',
                )
            );

            $this->add_control(
                'product_price_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-price-value' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );
            
            $this->add_control(
                'product_name_heading',
                [
                    'label' => __( 'Product Name', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_name_typo',
                    'selector' => '{{WRAPPER}} .woolentor-product-title',
                ]
            );

            $this->add_control(
                'product_name_color',
                [
                    'label' => __( 'Product Name Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-title' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_name_hover_color',
                [
                    'label' => __( 'Product Name Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-title:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_name_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_name_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'quantity_label_heading',
                [
                    'label' => __( 'Quantity', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'quantity_label_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-product-quantity-label',
                )
            );

            $this->add_control(
                'quantity_label_color',
                [
                    'label'   => __( 'Quantity Label Color', 'woolentor-pro' ),
                    'type'    => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product-quantity-label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'quantity_field_bg_color',
                [
                    'label'   => __( 'Field BG Color', 'woolentor-pro' ),
                    'type'    => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor-review-order-1 .woolentor-quantity-btn' => 'background-color: {{VALUE}};',
                        '.woocommerce {{WRAPPER}} .woolentor-review-order-1 .quantity .qty' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'quantity_field_txt_color',
                [
                    'label'   => __( 'Field Text Color', 'woolentor-pro' ),
                    'type'    => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor-review-order-1 .woolentor-quantity-btn' => 'color: {{VALUE}};',
                        '.woocommerce {{WRAPPER}} .woolentor-review-order-1 .quantity .qty' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'quantity_field_border_color',
                [
                    'label'   => __( 'Field Border Color', 'woolentor-pro' ),
                    'type'    => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor-review-order-1 .quantity' => 'border-color: {{VALUE}};',
                        '.woocommerce {{WRAPPER}} .woolentor-review-order-1 .woolentor-quantity-btn' => 'border-color: {{VALUE}};',
                    ],
                    'separator' => 'after',
                ]
            );
            
            $this->add_control(
                'attribute_list_name_heading',
                [
                    'label' => __( 'Attribute Name', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'attribute_name_typo',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-product dt',
                )
            );

            // Attribute item name color
            $this->add_control(
                'attribute_name_color',
                [
                    'label' => __( 'Attribute Name Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product dt' => 'color: {{VALUE}} !important;',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'attribute_list_value_heading',
                [
                    'label' => __( 'Attribute Value', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'attribute_value_typo',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-product dd',
                )
            );

            // Attribute item value color
            $this->add_control(
                'attribute_value_color',
                [
                    'label' => __( 'Attribute Value Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product dd' => 'color: {{VALUE}} !important;',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'remove_icon_heading',
                [
                    'label' => __( 'Remove Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->start_controls_tabs('remove_icon_style_tabs');
                $this->start_controls_tab(
                    'remove_icon_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    // Remove icon border
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'remove_icon_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .remove_from_cart_button',
                        ]
                    );
                    
                    // Remove icon color
                    $this->add_control(
                        'remove_icon_color',
                        [
                            'label' => __( 'Icon Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'',
                            'selectors' => [
                                '{{WRAPPER}} .remove_from_cart_button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    // Remove icon background
                    $this->add_control(
                        'remove_icon_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'',
                            'selectors' => [
                                '{{WRAPPER}} .remove_from_cart_button' => 'background-color: {{VALUE}}',
                            ],
                            'separator' => 'after'
                        ]
                    );
                $this->end_controls_tab(); //remove_icon_style_normal_tab

                $this->start_controls_tab(
                    'remove_icon_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    // Remove icon border
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'remove_icon_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .remove_from_cart_button:hover',
                        ]
                    );

                    // Remove icon color
                    $this->add_control(
                        'remove_icon_hover_color',
                        [
                            'label' => __( 'Icon Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'',
                            'selectors' => [
                                '{{WRAPPER}} .remove_from_cart_button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    // Remove icon background
                    $this->add_control(
                        'remove_icon_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'',
                            'selectors' => [
                                '{{WRAPPER}} .remove_from_cart_button:hover' => 'background-color: {{VALUE}}',
                            ],
                            'separator' => 'after'
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs(); //product_item_style_tabs

            $this->add_control(
                'item_area_heading',
                [
                    'label' => __( 'Product Item', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            // Thumbnail width
            $this->add_responsive_control(
                'thumbnail_width',
                [
                    'label' => __( 'Thumbnail Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 5,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 125,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-review-order-1  .woolentor-product-thumb' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            
            $this->add_responsive_control(
                'thumbnail_spacing',
                [
                    'label' => __( 'Thumbnail Spacing', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 25,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-review-order-1 .woolentor-product' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            // Item bg
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_bg',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-product',
                ]
            );

            // Item border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-product:not(:last-child)',
                ]
            );

            $this->add_responsive_control(
                'item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            // Margin
            $this->add_responsive_control(
                'product_item_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-product:last-child' => 'margin-bottom: 0;',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                'product_item_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_wrapper_heading',
                [
                    'label' => __( 'Product Wrapper', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'product_wrapper_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-review-order-1 .woolentor-products' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_wrapper_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-review-order-1 .woolentor-products' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after',
                ]
            );

        $this->end_controls_section(); //product_item_style_section

        $this->start_controls_section(
            'order_total_summery_section_style',
            array(
                'label' => __( 'Order Total Summary', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style!' => '',
                ],
            )
        );  
            // Separator border
            $this->add_control(
                'separator_color',
                [
                    'label' => __( 'Separator Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} #order_review.woolentor-review-order-1 table.shop_table .order-total th, .woocommerce {{WRAPPER}} #order_review.woolentor-review-order-1 table.shop_table .order-total td' => 'border-top-color: {{VALUE}};',
                        '{{WRAPPER}} #order_review.woolentor-review-order-1 .woolentor-products' => 'border-bottom-color: {{VALUE}};',
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'calculation_title_heading',
                [
                    'label' => __( 'Subtotal - Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'calculation_title_typo',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr:not(.order-total,.woolentor-save) th',
                )
            );

            $this->add_control(
                'subtotal_title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr:not(.order-total,.woolentor-save) th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'subtotal_title_padding',
                [
                    'label' => __( 'Title Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr:not(.order-total,.woolentor-save) th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'calculation_price_heading',
                [
                    'label' => __( 'Subtotal - Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'calculation_price_typo',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr:not(.order-total,.woolentor-save) td',
                )
            );

            $this->add_control(
                'subtotal_price_color',
                [
                    'label' => __( 'Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr:not(.order-total,.woolentor-save) td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'subtotal_price_padding',
                [
                    'label' => __( 'Price Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr:not(.order-total,.woolentor-save) td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'total_heading',
                [
                    'label' => __( 'Total', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'total_title_typo',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr.order-total th',
                )
            );

            $this->add_control(
                'total_title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr.order-total th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'total_title_padding',
                [
                    'label' => __( 'Title Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review table.shop_table tfoot tr.order-total th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'total_price_heading',
                [
                    'label' => __( 'Total - Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'total_price_typo',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '.woocommerce  {{WRAPPER}} #order_review .woocommerce-checkout-review-order-table tr.order-total td',
                )
            );

            $this->add_control(
                'total_price_color',
                [
                    'label' => __( 'Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review .woocommerce-checkout-review-order-table tr.order-total td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'total_price_padding',
                [
                    'label' => __( 'Price Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.woocommerce  {{WRAPPER}} #order_review .woocommerce-checkout-review-order-table tr.order-total td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section(); //order_total_summery_section_style

        // Price
        $this->start_controls_section(
            'checkout_order_table_price_style',
            array(
                'label' => __( 'Price', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => '',
                ],
                'separator' => 'after'
            )
        );
            
            $this->add_control(
                'checkout_order_table_price_heading',
                [
                    'label' => __( 'Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'checkout_order_table_price_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-checkout-review-order-table td.product-total',
                )
            );

            $this->add_control(
                'checkout_order_table_price_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-checkout-review-order-table td.product-total' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'after'
                ]
            );

            $this->add_control(
                'checkout_order_table_totalprice_heading',
                [
                    'label' => __( 'Total Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'checkout_order_table_totalprice_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-checkout-review-order-table tr.cart-subtotal td .amount, {{WRAPPER}} .woocommerce-checkout-review-order-table tr.order-total td .amount',
                )
            );

            $this->add_control(
                'checkout_order_table_totalprice_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-checkout-review-order-table tr.cart-subtotal td .amount' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-checkout-review-order-table tr.order-total td .amount' => 'color: {{VALUE}}',
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
                        '{{WRAPPER}} :is(*)' => 'font-family: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $title = ( !empty( $settings['table_title'] ) ? $settings['table_title'] : __('Your order','woolentor-pro') );
        $attributes_display = $settings['attributes_display'];
        $contents_display = $settings['contents_display'];

        $style = $settings['style'];

        do_action( 'woolentor_before_checkout_order' );
        if ( Plugin::instance()->editor->is_edit_mode() || is_checkout() ) {
            $wrapper_class = array('woocommerce-checkout-review-order woolentor');
            
            if( $style ){
                $wrapper_class[] = 'woolentor-review-order-1';
                $wrapper_class[] = 'wl_style_' . $style;
                $wrapper_class[] = 'contents-display--' . $contents_display;
                $wrapper_class[] = 'attributes-display--' . $attributes_display;
                $wrapper_class[] =  $settings['hide_order_total_summery'] == 'yes' ? 'wl_summary_hidden' : '';
            }

            if( $style ){
                ?>
                <div id="order_review" class="<?php echo esc_attr(implode(' ', $wrapper_class)) ?>">
                    <div class="woolentor-review-order-inner">
                        <h3 id="order_review_heading" class="order_review_heading"><?php echo esc_html( $title, 'woolentor-pro' ); ?></h3>

                        <div class="woolentor-order-review-content">

                            <?php if( $settings['hide_products'] != 'yes' ): ?>
                            <div class="woolentor-products woolentor-products-<?php echo esc_attr($style) ?>">
                                <?php if ( ! WC()->cart->is_empty() ) :
                                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ):
                                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                                                wc_get_template(
                                                    'checkout/review-order-cart-item.php',
                                                    array(
                                                        '_product'      => $_product,
                                                        'product_id'    => $product_id,
                                                        'cart_item'     => $cart_item,
                                                        'cart_item_key' => $cart_item_key,
                                                        'style'         => $style,
                                                        'settings'      => $settings
                                                    ),
                                                    'wl-woo-templates',
                                                    WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/'
                                                );
                                            ?>
                                            
                                <?php
                                            } // endif $_product->exists()
                                        endforeach;
                                    else :
                                ?>
                                    <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woolentor-pro' ); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if( $settings['hide_order_total_summery'] != 'yes' ): ?>
                            <div class="woolentor-shop-table">
                                <?php
                                    wc_get_template(
                                        'checkout/review-order.php',
                                        array(
                                            'checkout' => WC()->checkout(),
                                        ),
                                        'wl-woo-templates',
                                        WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/'
                                    );
                                ?>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div><!-- woolentor-review-order -->
                <?php

            } else {
                echo '<h3 id="order_review_heading">'.esc_html__( $title, 'woolentor-pro' ).'</h3>';
                echo '<div id="order_review" class="woocommerce-checkout-review-order">';
                woocommerce_order_review();
                echo '</div><!-- woolentor-review-order -->';
            }
            
        } // is_checkout()

        do_action( 'woolentor_after_checkout_order' );

    }   

}