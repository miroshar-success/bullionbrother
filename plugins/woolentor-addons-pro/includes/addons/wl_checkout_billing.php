<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Checkout_Billing_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-billing-form';
    }
    
    public function get_title() {
        return __( 'WL: Checkout Billing Form', 'woolentor-pro' );
    }

    public function get_icon() {
        return ' eicon-form-horizontal';
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets-pro',
            'woolentor-checkout'
        ];
    }

    public function get_script_depends(){
        return [
            'woolentor-checkout',
            'woolentor-widgets-scripts-pro'
        ];
    }

    public function get_keywords(){
        return ['checkout form','billing form','billing field','checkout'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_billing_content',
            [
                'label' => esc_html__( 'Billing Form', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'form_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Billing details', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Type your title here', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'form_createfield_label_title',
                [
                    'label' => esc_html__( 'Create an account label', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Create an account?', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Type your title here', 'woolentor-pro' ),
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
                    ],
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
                    'condition'=>[
                        'style'=>'1',
                    ]
                ]
            );

            $this->add_control(
                'checkbox_style',
                [
                    'label'   => __( 'Checkbox Field Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        '' =>  __( 'Default', 'woolentor-pro' ),
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_billing_fields',
            [
                'label' => esc_html__( 'Manage Field', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'important_note',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div style="line-height:18px;">To keep things tidy and user-friendly, field settings have now been moved to <strong>WooLentor > Settings > Modules > Checkout Fields Manager.<strong> '.sprintf( __( '<a href="%s" target="_blank">Field Settings</a>', 'woolentor-pro' ), admin_url( 'admin.php?page=woolentor' ) ).'</div>',
                    'content_classes' => 'wlnotice-imp elementor-panel-alert elementor-panel-alert-info',
                ]
            );

        $this->end_controls_section();

        // Heading
        $this->start_controls_section(
            'form_heading_style',
            array(
                'label' => __( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-billing-fields > h3,{{WRAPPER}} .woocommerce-billing-fields .woolentor-field-heading *',
                )
            );

            $this->add_control(
                'form_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-billing-fields > h3' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-billing-fields .woolentor-field-heading *' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-billing-fields .woolentor-field-heading' => 'border-color: {{VALUE}}',
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
                        '{{WRAPPER}} .woocommerce-billing-fields > h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woocommerce-billing-fields .woolentor-field-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
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
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-billing-fields > h3' => 'text-align: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-billing-fields .woolentor-field-heading' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Form label
        $this->start_controls_section(
            'form_label_style',
            array(
                'label' => __( 'Label', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_label_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .form-row label',
                )
            );

            $this->add_control(
                'form_label_color',
                [
                    'label' => __( 'Label Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .form-row label' => 'color: {{VALUE}}',
                        '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .form-row:not(.has-value,.focused) label:not(.checkbox,[for="order_comments"])' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_input_box_label_bg',
                [
                    'label' => __( 'Label BG Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .form-row:not(.has-value,.focused) label:not(.checkbox,[for="order_comments"])' => 'background-color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'style'=>'1',
                    ]
                ]
            );

            $this->add_control(
                'form_label_required_color',
                [
                    'label' => __( 'Required Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .form-row label abbr' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_label_padding',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'form_label_align',
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
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .form-row label' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Input box
        $this->start_controls_section(
            'form_input_box_style',
            array(
                'label' => esc_html__( 'Input Box', 'woolentor-pros' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->start_controls_tabs('form_input_box_style_tabs');
                
                $this->start_controls_tab(
                    'form_input_box_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'form_input_box_bg',
                        [
                            'label' => __( 'Input BG Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text' => 'background-color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text' => 'background-color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) textarea' => 'background-color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) select' => 'background-color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .select2-container .select2-selection' => 'background-color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .select2-container--default .select2-selection--single .select2-selection__rendered' => 'background-color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .woocommerce-input-wrapper strong' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_input_box_text_color',
                        [
                            'label' => __( 'Text Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) textarea' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) select' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .select2-container .select2-selection' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .select2-container--default .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .woocommerce-input-wrapper strong' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woolentor-fields-1 .select2-container--default .select2-selection--single .select2-selection__arrow > b::before' => 'border-color: {{VALUE}}; opacity: .8;',
                                '.woocommerce {{WRAPPER}} .woolentor-fields-1 .select2-container--default .select2-selection--single .select2-selection__arrow > b::after' => 'border-color: {{VALUE}}; opacity: .8;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'form_input_box_typography',
                            'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text, {{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection,  {{WRAPPER}} .form-row .select2-container .select2-selection .select2-selection__rendered, {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text',
                        )
                    );

                    $this->add_responsive_control(
                        'form_input_box_height',
                        [
                            'label' => __( 'Field Height', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px'  ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                            ],
                            'default' => [],
                            'selectors' => [
                                '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text,{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) select' => 'height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .select2-container--default .select2-selection--single,{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow ' => 'height: {{SIZE}}{{UNIT}};display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;',
                                '{{WRAPPER}} .select2-container--default .select2-selection--single[aria-expanded="true"]' => 'outline: 1px solid #005fcc; border: 1px solid transparent;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_input_box_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text, {{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection, {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_input_box_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'form_input_box_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .form-row .select2-container .select2-selection .select2-selection__arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
                                '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'form_input_box_margin',
                        [
                            'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
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
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_input_box_border_focus',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input.input-text:focus, {{WRAPPER}} .form-row select:focus, {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) .input-text:focus',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs(); //tabs

        $this->end_controls_section(); //input box

        // Checkbox
        $this->start_controls_section(
            'checkbox_style_section',
            [
                'label' => esc_html__( 'Input Checkbox', 'woolentor-pros' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'checkbox_style!'=>'',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'checkbox_border',
                'label'     => esc_html__( 'Border', 'woolentor-pro' ),
                'selector'  => '{{WRAPPER}} input[type=checkbox] ~ span::before,{{WRAPPER}} .woolentor-fields-1 input[type=checkbox] ~ span::before',
                'exclude'   => array('width'),
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
                    '{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input[type=checkbox] ~ span::before,{{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input[type=checkbox] ~ span::after' => 'background-color: {{VALUE}}',
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
                'selector'  => '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input[type=checkbox]:checked ~ span::before',
                'exclude'   => array('width', 'color'),
                'condition'=>[
                    'checkbox_style'=>'2',
                ],
            ]
        );

        $this->add_control(
            'checkbox_checked_color',
            [
                'label' => __( 'Checked Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input[type=checkbox] ~ span::after' => 'background-color: {{VALUE}}',
                    '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields) input[type=checkbox]:checked ~ span::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section(); //checkbox

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
        $settings   = $this->get_settings_for_display();

        $wrapper_classes = array();
        if( !empty($settings['style']) ){
            $wrapper_classes[] = 'woolentor-fields-1 woolentor-form-billing-1';
            $wrapper_classes[] = 'wl_'. $settings['field_label_display'];
        }

        $wrapper_classes[] = 'wl_cb_style_'. $settings['checkbox_style'];
        
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            $checkout = wc()->checkout();
            if( sizeof( $checkout->checkout_fields ) > 0 ){

                ?>
                    <form class="<?php echo esc_attr( implode(' ', $wrapper_classes) ); ?>">
                        <div class="woocommerce-billing-fields">

                            <?php
                                if( !empty( $settings['form_title'] ) ){
                                    echo '<h3>'.esc_html__( $settings['form_title'], 'woolentor-pro' ).'</h3>';
                                }
                            ?>

                            <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

                            <div class="woocommerce-billing-fields__field-wrapper">
                                <?php
                                    $fields = $checkout->get_checkout_fields( 'billing' );
                                    foreach ( $fields as $key => $field ) {
                                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                    }
                                ?>
                            </div>

                            <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
                        </div>
                    </form>

                <?php

            }
        }else{
            if( is_checkout() ){
                $checkout = wc()->checkout();
                if( sizeof( $checkout->checkout_fields ) > 0 ){
                    
                    ?>
                        <div class="woocommerce-billing-fields <?php echo esc_attr( implode(' ', $wrapper_classes) ); ?>">

                            <?php
                                if( !empty( $settings['form_title'] ) ){
                                    echo '<h3>'.esc_html__( $settings['form_title'], 'woolentor-pro' ).'</h3>';
                                }
                            ?>

                            <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

                            <div class="woocommerce-billing-fields__field-wrapper">
                                <?php
                                    $fields = $checkout->get_checkout_fields( 'billing' );
                                    foreach ( $fields as $key => $field ) {
                                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                    }
                                ?>
                            </div>

                            <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
                        </div>

                        <?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
                            <div class="woocommerce-account-fields <?php echo esc_attr( implode(' ', $wrapper_classes) ); ?>">
                                <?php if ( ! $checkout->is_registration_required() ) : ?>

                                    <p class="form-row form-row-wide create-account">
                                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                            <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( $settings['form_createfield_label_title'], 'woolentor-pro' ); ?></span>
                                        </label>
                                    </p>

                                <?php endif; ?>

                                <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

                                <?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

                                    <div class="create-account">
                                        <?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
                                            <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                                        <?php endforeach; ?>
                                        <div class="clear"></div>
                                    </div>

                                <?php endif; ?>

                                <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
                            </div>
                        <?php endif; ?>

                    <?php
                }
            }
        }
    }

}