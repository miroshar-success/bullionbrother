<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Checkout_Login_Form_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-login-form';
    }
    
    public function get_title() {
        return __( 'WL: Checkout Login Form', 'woolentor-pro' );
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
        ];
    }

    public function get_keywords(){
        return ['checkout form','login form','checkout login form','checkout'];
    }

    protected function register_controls() {
        // Content
        $this->start_controls_section(
            'form_area_content',
            [
                'label' => __( 'Login Form', 'woolentor-pro' ),
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
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        ''  => __( 'Default', 'woolentor-pro' ),
                    ]
                ]
            );

            $this->add_control(
                'field_label_display',
                [
                    'label'   => __( 'Field Label Display', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'on_top',
                    'options' => [
                        'on_top' => __( 'Float On Top', 'woolentor-pro' ),
                        'inside' => __( 'Float Inside', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'style' => '1',
                    ],
                ]
            );

            $this->add_control(
                'checkbox_style',
                [
                    'label'   => __( 'Checkbox Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => __( 'Default', 'woolentor-pro' ),
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Heading
        $this->start_controls_section(
            'form_area_style',
            [
                'label' => __( 'Message Info', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'hide_icon',
                [
                    'label'     => __( 'Hide Icon?', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info::before' => 'display: none;',
                        '{{WRAPPER}} .woocommerce-message::before' => 'display: none;',
                    ],
                    'condition' => [
                        'style' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'form_area_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-info',
                ]
            );

            $this->add_control(
                'form_area_text_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_color',
                [
                    'label' => __( 'Link Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'lost_password_link_color',
                [
                    'label' => __( 'Lost Psssword Link Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .lost_password a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_hover_color',
                [
                    'label' => __( 'Link Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_icon_color',
                [
                    'label' => __( 'Left Icon Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info::before' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_area_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woocommerce-info',
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
                        '{{WRAPPER}} .woocommerce-info' => 'border-top-color: {{VALUE}}',
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
                        '{{WRAPPER}} .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'form_area_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woocommerce-info',
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
                        '{{WRAPPER}} .woocommerce-info' => 'text-align: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-info::before' => 'position: static;margin-right:10px;',
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
            ]
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'form_box_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .login.woocommerce-form-login p',
                ]
            );

            $this->add_control(
                'form_box_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .login.woocommerce-form-login p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'form_box_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .login.woocommerce-form-login',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_box_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .login.woocommerce-form-login',
                ]
            );

            $this->add_responsive_control(
                'form_box_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .login.woocommerce-form-login' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .login.woocommerce-form-login' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .login.woocommerce-form-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $this->start_controls_tabs('form_input_box_style_tabs');
            
            $this->start_controls_tab(
                'form_input_box_normal_tab',
                [
                    'label' => __( 'Normal', 'woolentor-pro' ),
                ]
            );
                $this->add_control(
                    'form_input_box_label_bg',
                    [
                        'label' => __( 'Label BG Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} .woolentor-fields-1 .form-row:not(.has-value,.focused) label:not(.checkbox,.woocommerce-form-login__rememberme)' => 'background-color: {{VALUE}}',
                            '.woocommerce {{WRAPPER}} .form-row label:not(.checkbox,.woocommerce-form-login__rememberme)' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'form_input_box_label_text',
                    [
                        'label' => __( 'Label Text Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} .woolentor-fields-1 .form-row:not(.has-value,.focused) label:not(.checkbox,.woocommerce-form-login__rememberme)' => 'color: {{VALUE}}',
                            '.woocommerce {{WRAPPER}} .form-row label:not(.checkbox,.woocommerce-form-login__rememberme)' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'form_input_box_text_color',
                    [
                        'label' => __( 'Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'color: {{VALUE}}',
                        ]
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'form_input_box_text_background',
                        'label' => __( 'Background', 'woolentor-pro' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .login.woocommerce-form-login input.input-text',
                    ]
                );

                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name'      => 'form_input_box_typography',
                        'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                        'selector'  => '{{WRAPPER}} .login.woocommerce-form-login input.input-text',
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'form_input_box_border',
                        'label' => __( 'Border', 'woolentor-pro' ),
                        'selector' => '{{WRAPPER}} .login.woocommerce-form-login input.input-text',
                    ]
                );

                $this->add_responsive_control(
                    'form_input_box_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%'],
                        'selectors' => [
                            '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                            '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                            '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' => 'after',
                    ]
                );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'form_input_box_focus_tab',
                [
                    'label' => __( 'Focus', 'woolentor-pro' ),
                ]
            );
                $this->add_control(
                    'form_input_box_label_bg_focus',
                    [
                        'label' => __( 'Label BG Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} .woolentor-fields-1.wl_on_top .has-value label' => 'background-color: {{VALUE}}',
                            '.woocommerce {{WRAPPER}} .woolentor-fields-1.wl_on_top .focused label' => 'background-color: {{VALUE}}',
                        ],
                        'condition' => [
                            'style!' => '',
                        ],
                    ]
                );

                $this->add_control(
                    'form_input_box_label_text_focus',
                    [
                        'label' => __( 'Label Text Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} .woolentor-fields-1.wl_on_top .has-value label' => 'color: {{VALUE}}',
                            '.woocommerce {{WRAPPER}} .woolentor-fields-1.wl_on_top .focused label' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'style!' => '',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'form_input_box_border_focus',
                        'label' => __( 'Border', 'woolentor-pro' ),
                        'selector' => '{{WRAPPER}} .login.woocommerce-form-login input.input-text:focus',
                    ]
                );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section(); 

        // Checkbox
        $this->start_controls_section(
            'input_checkbox_section',
            [
                'label' => esc_html__( 'Input Checkbox', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'checkbox_style!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'checkbox_border',
                'label'     => esc_html__( 'Border', 'woolentor-pro' ),
                'selector'  => '{{WRAPPER}} .woocommerce-form-login.login input[type=checkbox] ~ span::before',
                'exclude'   => array('width')
            ]
        );

        $this->add_responsive_control(
            'checkbox_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wl_cb_style_1 input[type=checkbox] ~ span::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wl_cb_style_1 input[type=checkbox] ~ span::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'checkbox_style' => '1',
                ],
            ]
        );
        
        $this->add_control(
            'checkbox_bg_color',
            [
                'label' => __( 'Checkbox BG Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-form-login.login input[type=checkbox] ~ span::before,{{WRAPPER}} .woocommerce-form-login.login input[type=checkbox] ~ span::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_checked',
            [
                'label' => __( 'Checked', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'checkbox_checked_border',
                'label'     => esc_html__( 'Checked Border', 'woolentor-pro' ),
                'selector'  => '{{WRAPPER}} .wl_cb_style_1 input[type=checkbox]:checked ~ span::before,{{WRAPPER}} .wl_cb_style_2 input[type=checkbox]:checked ~ span::before',
                'exclude'   => array('width'),
            ]
        );

        $this->add_control(
            'checkbox_checked_color',
            [
                'label' => __( 'Checked Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wl_cb_style_1 input[type=checkbox]:checked ~ span::after,{{WRAPPER}} .wl_cb_style_2 input[type=checkbox]:checked ~ span::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wl_cb_style_1 input[type=checkbox]:checked ~ span::before,{{WRAPPER}} .wl_cb_style_2 input[type=checkbox]:checked ~ span::before' => 'border-color: {{VALUE}}',
                ],
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

            $this->add_control(
                'inline_remember_me',
                [
                    'label'         => __( 'Inline Remember Me?', 'woolentor-pro' ),
                    'description'   => __( 'Show the "Remember me" checkbox after the button.', 'woolentor-pro' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on'      => __( 'Yes', 'woolentor' ),
                    'label_off'     => __( 'No', 'woolentor' ),
                    'return_value'  => 'yes',
                    'default'       => 'no',
                ]
            );
            
            $this->start_controls_tabs('submit_button_style_tabs');
                
                $this->start_controls_tab(
                    'submit_button_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'form_submit_button_text_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'form_submit_button_typography',
                            'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .login.woocommerce-form-login button.button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .login.woocommerce-form-login button.button',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_submit_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .login.woocommerce-form-login button.button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_hover_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .login.woocommerce-form-login button.button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .login.woocommerce-form-login button.button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs(); //tabs

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
        $settings    = $this->get_settings_for_display();

        $wrapper_classes = array();
        if( !empty($settings['style']) ){
            $wrapper_classes[] = 'woolentor-fields-1 woolentor-form-login-1';
            $wrapper_classes[] = 'wl_'. $settings['field_label_display'];
           
        }

        if( $settings['inline_remember_me'] == 'yes' ){
            $wrapper_classes[] = 'woolentor-inline-remember-me';
        }

        if( !empty($settings['checkbox_style']) ){
            $wrapper_classes[] = 'wl_cb_style_'. $settings['checkbox_style'];
        }

        if ( Plugin::instance()->editor->is_edit_mode() ) {
            echo '<div class="'. esc_attr(implode(' ', $wrapper_classes)) .'">';
                ?>
                    <div class="woocommerce-form-login-toggle">
                        <?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'woolentor-pro' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'woolentor-pro' ) . '</a>', 'notice' ); ?>
                    </div>
                <?php
                ob_start();
                    woocommerce_login_form(
                        array(
                            'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woolentor-pro' ),
                            'redirect' => wc_get_checkout_url(),
                            'hidden'   => true,
                        )
                    );
                $html = ob_get_clean();
                $html = str_replace( ['<form','</form'],['<div','</div'], $html );
            echo '</div>';
            echo $html;

        }else{
            if( is_checkout() ){
                ob_start();

                echo '<div class="'. esc_attr(implode(' ', $wrapper_classes)) .'">';
                woocommerce_checkout_login_form();
                echo '</div>';
                $html = ob_get_clean();
                $html = str_replace( ['<form','</form'],['<div','</div'], $html );
                echo $html;
            }

        }
        
    }

}