<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Checkout_Coupon_Form_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-coupon-form';
    }
    
    public function get_title() {
        return __( 'WL: Coupon Form', 'woolentor-pro' );
    }

    public function get_icon() {
        return ' eicon-form-horizontal';
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
        return ['checkout form','coupon form','coupon field','checkout'];
    }

    protected function register_controls() {
        // Content
        $this->start_controls_section(
            'content',
            [
                'label' => __( 'Content', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
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
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ]
                ]
            );

            $this->add_control(
                'title',
                [
                    'label' => __( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Apply Coupon', 'woolentor-pro' ),
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'apply_button_text',
                [
                    'label' => __( 'Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );

            $this->add_control(
                'desc',
                [
                    'label' => __( 'Description', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'default' => __( 'If you have a coupon code, please apply it below.', 'woolentor-pro' ),
                    'placeholder' => __( 'If you have a coupon code, please apply it below.', 'woolentor-pro' ),
                ]
            );
        $this->end_controls_section();

         // Title section
         $this->start_controls_section(
            'title_style_section',
            [
                'label' => __( 'Title', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'style!'=>'',
                ],
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'title_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-title',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_align',
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
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section(); //Title style section

        // Desc style section
        $this->start_controls_section(
            'desc_style_section',
            [
                'label' => __( 'Description', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'style!'=>'',
                ],
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'desc_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-info',
                ]
            );

            $this->add_control(
                'desc_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'desc_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'desc_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'desc_align',
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
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-info' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section(); //Title style section

        // Heading
        $this->start_controls_section(
            'form_area_style',
            [
                'label' => __( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'style'=>'',
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'form_area_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info',
                ]
            );

            $this->add_control(
                'form_area_text_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_color',
                [
                    'label' => __( 'Link Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_hover_color',
                [
                    'label' => __( 'Link Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_icon_color',
                [
                    'label' => __( 'Left Icon Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info::before' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_area_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info',
                ]
            );

            $this->add_control(
                'form_area_top_border_color',
                [
                    'label' => __( 'Top Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'form_area_border_border' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info' => 'border-top-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'form_area_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info',
                ]
            );

            $this->add_responsive_control(
                'form_area_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_area_content_align',
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
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info' => 'text-align: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .checkout-coupon-toggle .woocommerce-info::before' => 'position: static;margin-right:10px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Form
        $this->start_controls_section(
            'form_form_style',
            [
                'label' => __( 'Form', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style' => '',
                ],
            ]
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'form_box_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form p:not(.form-row)',
                ]
            );

            $this->add_control(
                'form_box_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_box_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form',
                ]
            );

            $this->add_responsive_control(
                'form_box_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_box_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_box_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Input box
        $this->start_controls_section(
            'form_input_box_style',
            [
                'label' => esc_html__( 'Input Box', 'woolentor-pros' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'form_input_box_width',
                [
                    'label' => __( 'Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form .form-row-first' => 'flex-basis: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                        '.woocommerce {{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form .form-row-first input' => 'width: 100%',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_control(
                'form_input_box_text_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text'                            => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text::-webkit-input-placeholder' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text:-moz-placeholder'           => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text::-moz-placeholder'          => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text:-ms-input-placeholder'      => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'form_input_box_typography',
                    'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text',
                ]
            );

            $this->add_control(
                'form_input_box_bg',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_input_box_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text',
                ]
            );

            $this->add_responsive_control(
                'form_input_box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );
            
            $this->add_responsive_control(
                'form_input_box_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );
            
            $this->add_responsive_control(
                'form_input_box_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after',
                ]
            );

        $this->end_controls_section();

        // Submit button box
        $this->start_controls_section(
            'form_submit_button_style',
            [
                'label' => esc_html__( 'Submit Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->start_controls_tabs('submit_button_style_tabs');
                
                $this->start_controls_tab(
                    'submit_button_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_responsive_control(
                        'submit_button_normal_width',
                        [
                            'label' => __( 'Width', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                    'step' => 1,
                                ],
                                'px' => [
                                    'min' => 0,
                                    'max' => 500,
                                    'step' => 1,
                                ],
                            ],
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form .form-row-last' => 'flex-basis: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};margin-bottom:0px;',
                                '.woocommerce {{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form .form-row-last .button' => 'width: 100%;',
                            ],
                            'condition' => [
                                'style!' => '2',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_position',
                        [
                            'label' => __( 'Button Position', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 50,
                                    'step' => 1,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => '2',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_text_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'form_submit_button_typography',
                            'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_submit_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'form_submit_button_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'form_submit_button_margin',
                        [
                            'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'after'
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'submit_button_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'form_submit_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_hover_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-checkout-coupon-form .coupon-form button.button:hover',
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
                        '{{WRAPPER}} *' => 'font-family: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section();

    }

    protected function render() {
        $settings       = $this->get_settings_for_display();
        $style          = $settings['style'];
        $inline_style   = '';

        $wrapper_classes = array('woolentor-checkout-coupon-form');
        if( !empty($settings['style']) ){
            $wrapper_classes[] = 'woolentor-coupon-1';
            $wrapper_classes[] = !empty($style) ? 'wl_style_' . esc_attr($style) : '';
        }else{
            $wrapper_classes[] = 'woolentor-coupon-default';
        }

        $apply_button_text = esc_html__('Apply', 'woolentor-pro');
        if( $settings['apply_button_text'] ){
            $apply_button_text = $settings['apply_button_text'];
        }
        
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            if (!wc_coupons_enabled() ){
                ?>
                    <div class="woocommerce-info">
                        <?php echo esc_html__('Using coupons is turned off. To enable go to "WooCommerce > Settings > General tab" and check the "Enable coupons" checkbox.', 'woolentor-pro' ); ?>
                    </div>
                <?php

                return;
            }
            ?>
                <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)) ?>">

                    <?php if (empty($style) ):
                        $apply_button_text = esc_html__('Apply coupon', 'woolentor-pro'); 
                    ?>
                    <div class="checkout-coupon-toggle">
                        <div class="woocommerce-info">
                            <?php echo esc_html( apply_filters('woocommerce_checkout_coupon_message', esc_html__('Have a coupon?', 'woolentor-pro') ) ); ?>
                            <a href="#" class="showcoupon"><?php echo esc_html__('Click here to enter your code', 'woolentor-pro') ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($settings['title']): ?>
                        <h3 class="woolentor-title"><?php echo esc_html($settings['title']); ?></h3>
                    <?php endif; ?>
                    
                    <div class="coupon-form" style="display:none;">
                        <?php if($style == '2'): ?>
                                <input type="text" name="coupon_code" class="input-text" placeholder="<?php echo esc_attr('Coupon code', 'woolentor');?>" id="coupon_code" value="" />
                                <button type="button" class="button" name="apply_coupon" value="<?php echo esc_attr($apply_button_text);?>"><?php echo esc_html($apply_button_text) ?></button>
                            <?php else: ?>
                                <?php if($settings['desc']): ?>
                                    <p class="woolentor-info"><?php echo wp_kses_post($settings['desc']);?></p>
                                <?php endif; ?>

                                <p class="form-row form-row-first">
                                    <input type="text" name="coupon_code" class="input-text" placeholder="<?php echo esc_attr('Coupon code', 'woolentor');?>" id="coupon_code" value="" />
                                </p>
                                <p class="form-row form-row-last">
                                    <button type="button" class="button" name="apply_coupon" value="<?php echo esc_attr($apply_button_text);?>"><?php echo esc_html($apply_button_text) ?></button>
                                </p>
                            <?php endif; ?>

                            <div class="clear"></div>
                        </div>

                        <?php if($style == '2' && $settings['desc']): ?>
                            <p class="woolentor-info"><?php echo wp_kses_post($settings['desc']);?></p>
                        <?php endif; ?>
                    </div>

                </div>
            <?php
        }else{
            if( ( is_checkout() || is_cart() ) && wc_coupons_enabled() ){
                ?>
                    <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)) ?>">
                        <?php if(empty($style)):
                            $inline_style = 'display:none';
                            $apply_button_text = esc_html__('Apply coupon', 'woolentor-pro');
                        ?>
                        <div class="checkout-coupon-toggle">
                            <div class="woocommerce-info">
                                <?php echo esc_html( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__('Have a coupon?', 'woolentor-pro') ) ); ?>
                                <a href="#" class="show-coupon"><?php echo esc_html__('Click here to enter your code', 'woolentor-pro') ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($settings['title']): ?>
                            <h3 class="woolentor-title"><?php echo esc_html($settings['title']); ?></h3>
                        <?php endif; ?>

                        <div class="coupon-form" style="<?php echo esc_attr($inline_style) ?>">

                            <?php if($style == '2'): ?>
                                <input type="text" name="coupon_code" class="input-text" placeholder="<?php echo esc_attr('Coupon code', 'woolentor');?>" id="coupon_code" value="" />
                                <button type="button" class="button" name="apply_coupon" value="<?php echo esc_attr($apply_button_text);?>"><?php echo esc_html($apply_button_text) ?></button>
                            <?php else: ?>
                                <?php if($settings['desc']): ?>
                                    <p class="woolentor-info"><?php echo wp_kses_post($settings['desc']);?></p>
                                <?php endif; ?>

                                <p class="form-row form-row-first">
                                    <input type="text" name="coupon_code" class="input-text" placeholder="<?php echo esc_attr('Coupon code', 'woolentor');?>" id="coupon_code" value="" />
                                </p>
                                <p class="form-row form-row-last">
                                    <button type="button" class="button" name="apply_coupon" value="<?php echo esc_attr($apply_button_text);?>"><?php echo esc_html($apply_button_text) ?></button>
                                </p>
                            <?php endif; ?>

                            <div class="clear"></div>
                        </div>

                        <?php if($style == '2' && $settings['desc']): ?>
                            <p class="woolentor-info"><?php echo wp_kses_post($settings['desc']);?></p>
                        <?php endif; ?>

                    </div>

                <?php

            }
        }
    }

}