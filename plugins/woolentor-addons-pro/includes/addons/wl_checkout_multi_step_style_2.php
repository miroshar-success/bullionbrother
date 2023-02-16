<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_WL_Checkout_Multi_Step_Style_2_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-multi-step-form-style-2';
    }
    
    public function get_title() {
        return __( 'WL: Checkout Multi Step Style 2', 'woolentor-pro' );
    }

    public function get_icon() {
        return ' eicon-form-horizontal';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_style_depends(){
        return [
            'woolentor-checkout',
            'woolentor-widgets-pro',
        ];
    }

    public function get_script_depends(){
        return [
            'woolentor-multi-steps-checkout',
            'woolentor-widgets-scripts-pro',
        ];
    }

    public function get_keywords(){
        return ['checkout form','multistep checkout','multi step','checkout'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );  
            // Style
            $this->add_control(
                'style',
                [
                    'label'   => __( 'Checkout Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ]
                ]
            );

            // Step Style
            $this->add_control(
                'step_indicator_style',
                [
                    'label'   => __( 'Step Navigation Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '2',
                    'options' => [
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                        '3' => __( 'Style 3', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_control(
                'navigation_placement',
                [
                    'label' => esc_html__( 'Navigation Placement', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''                           => esc_html__('Default','woolentor-pro'),
                        'separate_addon'             => esc_html__('Use Separate Addon','woolentor-pro'),
                    ],
                    'condition' => [
                        'style!' => '2',
                    ]
                ]
            );

            $this->add_control(
                'important_note',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div style="line-height:18px;">Use this option if you would like to display the "Step Navigation" outside of this addon. To do so, use the addon called <b>"WL: Checkout Multi Step Style 2 Navigation"</b> where you want to display the navigation.</div>',
                    'content_classes' => 'wlnotice-imp elementor-panel-alert elementor-panel-alert-info',
                    'condition' => [
                        'style!' => '2',
                        'navigation_placement' => 'separate_addon'
                    ]
                ]
            );

            // Shipping method style
            $this->add_control(
                'shipping_method_style',
                [
                    'label'   => __( 'Shipping Method Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'style!' => '2',
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
                    'label_block' => false,
                ]
            );

            $this->add_control(
                'checkbox_style',
                [
                    'label'   => __( 'Checkbox Field Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '' =>  __( 'Default', 'woolentor-pro' ),
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ],
                    'label_block' => false,
                ]
            );
            
        $this->end_controls_section();

        $this->start_controls_section(
            'section_tabs_content',
            [
                'label' => esc_html__( 'Tabs Label', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'steps_custom_title_heading',
                [
                    'label' => esc_html__( 'Custom Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'information',
                [
                    'label' => __( 'Information', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Information', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'shipping',
                [
                    'label' => __( 'Shipping', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Shipping', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'payment',
                [
                    'label' => __( 'Payment', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Payment', 'woolentor-pro'),
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_buttons_content',
            [
                'label' => esc_html__( 'Buttons Label', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'buttons_custom_title_heading',
                [
                    'label' => esc_html__( 'Custom Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'return_to_informations',
                [
                    'label' => __( 'Return To Informations', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Return To Informations', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'return_to_shipping',
                [
                    'label' => __( 'Return To Shipping', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Return To Shipping', 'woolentor-pro'),
                ]
            );
            
            $this->add_control(
                'continue_to_shipping',
                [
                    'label' => __( 'Continue To Shipping', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Continue To Shipping', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'continue_to_payment',
                [
                    'label' => __( 'Continue To Payment', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Continue To Payment', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'place_order',
                [
                    'label' => __( 'Place Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Place Order', 'woolentor-pro'),
                ]
            );
            

        $this->end_controls_section();

        $this->start_controls_section(
            'section_forms_content',
            [
                'label' => esc_html__( 'Form Labels', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'billing_form_heading',
                [
                    'label' => esc_html__( 'Billing Form', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'billing_form_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Billing Informations', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Billing Informations', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'billing_form_create_an_title',
                [
                    'label' => esc_html__( 'Create an account Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Create an account?', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Create an account?', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'shipping_form_heading',
                [
                    'label' => esc_html__( 'Shipping Form', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'shipping_form_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Ship to a different address?', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Ship to a different address?', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'shipping_form_additional_info',
                [
                    'label' => esc_html__( 'Additional Info Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Additional Information', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Additional Information', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'shipping_method_title',
                [
                    'label' => esc_html__( 'Shipping Method Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Shipping Method', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Shipping Method', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'payment_form_heading',
                [
                    'label' => esc_html__( 'Payment', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'payment_form_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Payment Methods', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Payment Methods', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            // Valdation message
            $this->add_control(
                'required_message',
                [
                    'label' => __( 'Required field validation message', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('This field is required!', 'woolentor-pro'),
                ]
            );

        $this->end_controls_section();

        // Tabs Style Section
        $this->start_controls_section(
            'section_tabs_menu_style',
            [
                'label' => esc_html__( 'Steps - Tab/Accordion', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navigation_placement' => '',
                ],
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'tabs_background_color',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-step-nav,{{WRAPPER}} .woolentor-step-nav-3 ul li .woolentor-step-nav-number',
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'area_margin',
                [
                    'label' => esc_html__( 'Area Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'area_padding',
                [
                    'label' => esc_html__( 'Area Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_control(
                'heading_tabs_name',
                [
                    'label' => __( 'Tab Heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tabs_menu_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-step-nav-text, {{WRAPPER}} .woolentor-step-nav-number,.wl_msc_style_2 .woolentor-block-heading .woolentor-block-heading-title',
                ]
            );

            $this->add_control(
                'heading_tabs_number',
                [
                    'label' => __( 'Number', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'condition' => [
                        'step_indicator_style' => '2',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tabs_number_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-step-nav-number',
                    'condition' => [
                        'step_indicator_style' => '2',
                    ],
                ]
            );

            $this->start_controls_tabs('tabs_menu_style_tabs');
                // Normal tabs style
                $this->start_controls_tab(
                    'tabs_menu_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    // Text color
                    $this->add_control(
                        'normal_text_color',
                        [
                            'label' => __( 'Step Name Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav ul li .woolentor-step-nav-text' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_msc_style_2 .woolentor-block-heading-title' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Number color
                    $this->add_control(
                        'step_number_color',
                        [
                            'label' => __( 'Step Number Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav-2 ul li .woolentor-step-nav-number' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style!' => '2',
                                'step_indicator_style' => '2',
                            ],
                        ]
                    );

                    // Color
                    $this->add_control(
                        'normal_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) ul li .woolentor-step-nav-number' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style!' => '2',
                                'step_indicator_style!' => '1',
                            ],
                        ]
                    );
                    
                    $this->add_control(
                        'border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) .woolentor-step-nav-bar' => 'background-color: {{VALUE}}', // border
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) ul li .woolentor-step-nav-number' => 'border-color:{{VALUE}}',
                                '{{WRAPPER}} .wl_msc_style_2 .woolentor-step' => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'step_indicator_style!' => '1'
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Current tabs style
                $this->start_controls_tab(
                    'tabs_menu_style_current_tab',
                    [
                        'label' => esc_html__( 'Current / Complete', 'woolentor-pro' ),
                    ]
                );
                    // Text color
                    $this->add_control(
                        'active_text_color',
                        [
                            'label' => __( 'Step Name Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-text' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                     // Number color
                     $this->add_control(
                        'active_step_number_color',
                        [
                            'label' => __( 'Step Number Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav-2 ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-number' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style!' => '2',
                                'step_indicator_style' => '2',
                            ],
                        ]
                    );

                    // Color
                    $this->add_control(
                        'active_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav-2 ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-number' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-step-nav-3 ul li .woolentor-step-nav-number::after' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-step-nav-3 ul .woolentor-step-nav-bar-active' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-step-nav-3 ul li:is(.woolentor-active) .woolentor-step-nav-number' => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'step_indicator_style!' => '1',
                            ],
                        ]
                    );

                    $this->add_control(
                        'active_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) div.woolentor-step-nav-bar-active' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-number' => 'border-color:{{VALUE}}',
                            ],
                            'condition' => [
                                'step_indicator_style!' => '1',
                            ],
                        ]
                    );

                $this->end_controls_tab();
                
            $this->end_controls_tabs();
        
            $this->add_responsive_control(
                'step_name_margin',
                [
                    'label' => __( 'Spacing', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-nav' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl_msc_style_2 .woolentor-step:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'step_heading_padding',
                [
                    'label' => esc_html__( 'Heading Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_msc_style_2 .woolentor-step:not(.woolentor-active)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style' => '2',
                    ],
                ]
            );

        $this->end_controls_section();

        // Steps Content Section
        $this->start_controls_section(
            'section_step_content_style',
            [
                'label' => esc_html__( 'Steps - Content', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            // Title
            $this->add_control(
                'heading_steps_title',
                [
                    'label' => __( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            // Title color
            $this->add_control(
                'step_content_title_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'heading_steps_title_typography',
                    'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector'  => '.woocommerce {{WRAPPER}} .woolentor-title',
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'step_content_title_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'step_content_title_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'paddng: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );
            

            // Content
            $this->add_control(
                'heading_steps_content',
                [
                    'label' => __( 'Content', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_responsive_control(
                'step_content_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-billing-fields__field-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-shipping-fields__field-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-shipping-method-1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-shipping-method-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woocommerce-checkout-payment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'step_content_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-billing-fields__field-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-shipping-fields__field-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-shipping-method-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-shipping-method-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woocommerce-checkout-payment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl_msc_style_2 .woolentor-step.woolentor-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'step_content_section_spacing',
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
                    'selectors' => [
                        '{{WRAPPER}} .wl_msc_style_1 .woolentor-shipping-fields__field-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl_msc_style_2 .woolentor-step:is(.woolentor-active)' => 'padding: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );  

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'step_content_bg',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} :not(.wl_msc_style_2) .woocommerce-billing-fields__field-wrapper,{{WRAPPER}} .woolentor-shipping-fields__field-wrapper, {{WRAPPER}} .woocommerce-checkout-payment#payment,{{WRAPPER}} .wl_msc_style_2 .woolentor-step.woolentor-active,{{WRAPPER}} .woolentor-shipping-method-1,{{WRAPPER}} .woolentor-shipping-method-2',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'step_content_box_shadow',
                    'label' => __( 'Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wl_msc_style_2 .woolentor-step:not(.woolentor-active)',
                    'condition' => [
                        'style' => '2',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'step_content_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wl_msc_style_2 .woolentor-step.woolentor-active',
                    'condition' => [
                        'style' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'step_content_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} :not(.wl_msc_style_2) .woocommerce-billing-fields__field-wrapper,{{WRAPPER}} .woolentor-shipping-fields__field-wrapper, {{WRAPPER}} .woocommerce-checkout-payment#payment,{{WRAPPER}} .wl_msc_style_2 .woolentor-step.woolentor-active,{{WRAPPER}} .woolentor-shipping-method-1,{{WRAPPER}} .woolentor-shipping-method-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Input box
        $this->start_controls_section(
            'form_input_box_style',
            [
                'label' => esc_html__( 'Forms Input Box', 'woolentor-pro' ),
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
                        'form_input_box_bg',
                        [
                            'label' => __( 'Input BG Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout input.input-text, {{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection, {{WRAPPER}} .woolentor-msc2-checkout .input-text' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_input_box_label_bg',
                        [
                            'label' => __( 'Label BG Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woolentor-fields-1 .form-row label:not(.checkbox,[for="order_comments"])' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_input_box_label_text',
                        [
                            'label' => __( 'Label Text Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woolentor-fields-1 .form-row label:not(.checkbox,[for="order_comments"])' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_input_box_text_color',
                        [
                            'label' => __( 'Text Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout input.input-text' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout .input-text' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout textarea' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout select' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout .select2-container .select2-selection' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout .select2-container--default .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout .woocommerce-input-wrapper strong' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'form_input_box_typography',
                            'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                            'selector'  => '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout input.input-text, .woocommerce {{WRAPPER}} .form-row select, .woocommerce {{WRAPPER}} .form-row .select2-container .select2-selection,  .woocommerce {{WRAPPER}} .form-row .select2-container .select2-selection .select2-selection__rendered, .woocommerce {{WRAPPER}} .woolentor-msc2-checkout .input-text',
                        ]
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
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields__field-wrapper,.woocommerce-shipping-fields) .form-row:not(.create-account),.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields__field-wrapper,.woocommerce-shipping-fields) input.input-text,.woocommerce {{WRAPPER}} :is(.woocommerce-account-fields,.woocommerce-billing-fields__field-wrapper,.woocommerce-shipping-fields) select' => 'height: {{SIZE}}{{UNIT}};',
                                '.woocommerce {{WRAPPER}} .select2-container--default .select2-selection--single,.woocommerce {{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow ' => 'height: {{SIZE}}{{UNIT}};display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;',
                                '.woocommerce {{WRAPPER}} .select2-container--default .select2-selection--single[aria-expanded="true"]' => 'outline: 1px solid #005fcc; border: 1px solid transparent;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_input_box_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout input.input-text, .woocommerce {{WRAPPER}} .form-row select, .woocommerce {{WRAPPER}} .form-row .select2-container .select2-selection, .woocommerce {{WRAPPER}} .woolentor-msc2-checkout .input-text',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_input_box_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '.woocommerce {{WRAPPER}} .form-row select, .woocommerce {{WRAPPER}} .form-row .select2-container .select2-selection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '.woocommerce {{WRAPPER}} .form-row select, .woocommerce {{WRAPPER}} .form-row .select2-container .select2-selection' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '.woocommerce {{WRAPPER}} .form-row .select2-container .select2-selection .select2-selection__arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
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
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '.woocommerce {{WRAPPER}} .woolentor-msc2-checkout .input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                            ],
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_input_box_border_focus',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woocommerce-billing-fields input.input-text:focus, {{WRAPPER}} .form-row select:focus, {{WRAPPER}} .woocommerce-billing-fields .input-text:focus',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs(); //tabs

        $this->end_controls_section(); //input box

        // Checkbox
        $this->start_controls_section(
            'checkbox_style_section',
            [
                'label' => esc_html__( 'Input Checkbox', 'woolentor-pro' ),
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
                'selector'  => '{{WRAPPER}} input[type=checkbox]:checked ~ span::before',
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
                    '{{WRAPPER}} input[type=checkbox] ~ span::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} input[type=checkbox]:checked ~ span::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section(); //checkbox

        // Radio button
        $this->start_controls_section(
            'radio_button_style_section',
            [
                'label' => esc_html__( 'Radio Button', 'woolentor-pros' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'radio_button_border',
                'label'     => esc_html__( 'Border', 'woolentor-pro' ),
                'selector'  => '.woocommerce {{WRAPPER}} .woolentor-msc2-step input[type=radio] ~ label::before,.wl_msc_style_2 .woolentor-block-heading:before',
                'exclude'   => array('width'),
            ]
        );

        $this->add_control(
            'heading_selected',
            [
                'label' => __( 'Selected', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'radio_selected_border',
                'label'     => esc_html__( 'Selected Border', 'woolentor-pro' ),
                'selector'  => '.woocommerce {{WRAPPER}} .woolentor-msc2-step input[type=radio]:checked ~ label::before,.wl_msc_style_2 .woolentor-step.woolentor-active .woolentor-block-heading:before',
                'exclude'   => array('width'),
            ]
        );

        $this->add_control(
            'radio_selected_color',
            [
                'label' => __( 'Selected Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .woolentor-msc2-step input[type=radio] ~ label::after' => 'background-color: {{VALUE}}',
                    '.woocommerce {{WRAPPER}} .wl_msc_style_2 .woolentor-step.woolentor-active .woolentor-block-heading:before' => 'border-color: {{VALUE}}',
                    '.woocommerce {{WRAPPER}} .wl_msc_style_2 .woolentor-step.woolentor-active .woolentor-block-heading:after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section(); //Checkbox

        // Method item style
        $this->start_controls_section(
            'method_item_style_section',
            [
                'label' => __( 'Shipping Method', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );  
            $this->add_control(
                'important_note_message_box_shipping_method_item',
                [
                    'label' => __( 'Method Item Options', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
                $this->add_control(
                    'method_item_bg_color',
                    [
                        'label' => __( 'Background Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li',
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} ul#shipping_method li' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

                // Border
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'method_item_border',
                        'label' => __( 'Border', 'woolentor-pro' ),
                        'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li',
                    ]
                );
                $this->add_responsive_control(
                    'method_item_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%'],
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} ul#shipping_method li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                // Margin
                $this->add_responsive_control(
                    'method_item_margin',
                    [
                        'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%'],
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} .woolentor-shipping-method-1 ul#shipping_method li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                        ],   
                    ]
                );

                // Padding  
                $this->add_responsive_control(
                    'method_item_padding',
                    [
                        'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%'],
                        'selectors' => [
                            '.woocommerce {{WRAPPER}} ul#shipping_method li label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

            $this->add_control(
                'important_note_message_box_shipping_method_message_box',
                [
                    'label' => __( 'Message Box', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );
                $this->add_control(
                    'text_color',
                    [
                        'label'=> __( 'Text Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name'      => 'message_typography',
                        'label'     => __( 'Typography', 'woolentor-pro' ),
                        'selector'  => '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert',
                    ]
                );

                $this->add_control(
                    'message_box_bg_color',
                    [
                        'label' => __( 'Background Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'message_box_margin',
                    [
                        'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'message_box_padding',
                    [
                        'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
        $this->end_controls_section(); //Shipping Method style section

        $this->start_controls_section(
            'payment_method_style_section',
            [
                'label' => __( 'Payment Method', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'important_note_message_box_payment_method_general',
                [
                    'label' => __( 'General Options', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name'      => 'checkout_payment_typography',
                        'label'     => __( 'Typography', 'woolentor-pro' ),
                        'selector'  => '{{WRAPPER}} #payment',
                    ]
                );

                $this->add_control(
                    'checkout_payment_color',
                    [
                        'label' => __( 'Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} #payment' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .woolentor-payment-method-1 .woocommerce-privacy-policy-text' => 'color: {{VALUE}}',
                            '.woocommerce {{WRAPPER}} .woolentor-payment-method-1 .woocommerce-form__label-for-checkbox' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'checkout_payment_link_color',
                    [
                        'label' => __( 'Link Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .woolentor-payment-method-1 .woocommerce-privacy-policy-text a' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .woolentor-payment-method-1 .woocommerce-form__label-for-checkbox .woocommerce-terms-and-conditions-checkbox-text a' => 'color: {{VALUE}}',
                        ],
                        'condition' => [
                            'style!' => '',
                        ],
                    ]
                );

                $this->add_control(
                    'important_note_message_box_payment_method_heading',
                    [
                        'label' => __( 'Heading', 'woolentor-pro' ),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before'
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name'      => 'checkout_payment_heading_typography',
                        'label'     => __( 'Typography', 'woolentor-pro' ),
                        'selector'  => '{{WRAPPER}} #payment .wc_payment_method label',
                    ]
                );

                $this->add_control(
                    'checkout_payment_heading_color',
                    [
                        'label' => __( 'Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} #payment .wc_payment_method label' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'checkout_payment_heading_border',
                        'label' => __( 'Border', 'woolentor-pro' ),
                        'selector' => '{{WRAPPER}} #payment ul.payment_methods.methods li',
                    ]
                );

                $this->add_responsive_control(
                    'checkout_payment_heading_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%'],
                        'selectors' => [
                            '{{WRAPPER}} #payment ul.payment_methods.methods li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'checkout_payment_heading_padding',
                    [
                        'label' => __( 'Padding', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} #payment ul.payment_methods.methods li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}} .woolentor-payment-method-1 #payment ul.payment_methods.methods li label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'checkout_payment_heading_margin',
                    [
                        'label' => __( 'Margin', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} #payment ul.payment_methods.methods li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            '{{WRAPPER}} #payment .wc_payment_method label' => 'margin: 0;',
                        ],
                        'condition'=>[
                            'style'=>'',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'checkout_payment_heading_align',
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
                            '{{WRAPPER}} #payment ul.payment_methods.methods li' => 'text-align: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_control(
                    'checkout_payment_heading_background_color',
                    [
                        'label' => __( 'Background Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .#payment ul.payment_methods.methods li' => 'background-color: {{VALUE}}',
                            '{{WRAPPER}} .woolentor-payment-method-1 #payment ul.payment_methods.methods li label' => 'background-color: {{VALUE}}',
                        ],
                    ]
                );

            $this->add_control(
                'important_note_message_box_payment_method_content',
                [
                    'label' => __( 'Content', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );
                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name'      => 'checkout_payment_content_typography',
                        'label'     => __( 'Typography', 'woolentor-pro' ),
                        'selector'  => '{{WRAPPER}} #payment .payment_box',
                    ]
                );

                $this->add_control(
                    'checkout_payment_content_color',
                    [
                        'label' => __( 'Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} #payment .payment_box' => 'color: {{VALUE}}',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'checkout_payment_content_padding',
                    [
                        'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} #payment .payment_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'checkout_payment_content_margin',
                    [
                        'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}} #payment .payment_box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]   
                );

                $this->add_control(
                    'checkout_payment_content_bg_color',
                    [
                        'label' => __( 'Background Color', 'woolentor-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} #payment .payment_box' => 'background-color: {{VALUE}}',
                            '{{WRAPPER}} #payment div.payment_box::before, {{WRAPPER}} #payment div.payment_box::before' => 'border-color:transparent transparent {{VALUE}}',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'checkout_payment_content_border',
                        'label' => __( 'Border', 'woolentor-pro' ),
                        'selector' => '{{WRAPPER}} #payment .payment_box',
                    ]
                );

                $this->add_responsive_control(
                    'checkout_payment_content_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%'],
                        'selectors' => [
                            '{{WRAPPER}} #payment .payment_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

        $this->end_controls_section(); //Payment method style section

        // Buttons Style Section
        $this->start_controls_section(
            'section_buttons_style',
            [
                'label' => esc_html__( 'Continue Buttons', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'buttons_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text)',
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->start_controls_tabs('buttons_menu_style_tabs');

                // Normal buttons style
                $this->start_controls_tab(
                    'buttons_style_current_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'buttons_normal_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text)' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'buttons_normal_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text)' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'buttons_normal_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text)',
                        ]
                    );

                $this->end_controls_tab();

                // Hover buttons style
                $this->start_controls_tab(
                    'buttons_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'buttons_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text):hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'buttons_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text):hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'buttons_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-step-footer .woolentor-btn:not(.woolentor-btn-text):hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
        
        // Return buttons
        $this->start_controls_section(
            'section_return_buttons_style',
            [
                'label' => esc_html__( 'Back Buttons', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'section_return_buttons_icon',
                [
                    'label' => __( 'Button Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::ICONS,
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'return_buttons_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text',
                ]
            );

            $this->add_responsive_control(
                'return_button_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'return_button_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->start_controls_tabs('return_buttons_menu_style_tabs');

                // Normal buttons style
                $this->start_controls_tab(
                    'return_buttons_style_current_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'return_buttons_normal_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'return_buttons_normal_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'return_buttons_normal_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text',
                        ]
                    );

                $this->end_controls_tab();

                // Hover buttons style
                $this->start_controls_tab(
                    'return_buttons_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'return_buttons_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'return_buttons_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'return_buttons_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-step-footer .woolentor-btn.woolentor-btn-text:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section(); // Back button section

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
        $settings       = $this->get_settings_for_display();
        $checkout       = wc()->checkout();
        $checkout_url   = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );

        $wrapper_classes    = array('woolentor-msc2-checkout');
        $wrapper_classes[]  = 'wl_cb_style_'. $settings['checkbox_style'];
        if( !empty($settings['style']) ){
            $wrapper_classes[] = 'wl_msc_style_' . $settings['style'];
            $wrapper_classes[] = 'woolentor-fields-1';
            $wrapper_classes[] = 'wl_'. $settings['field_label_display'];
        }

        // Skip shipping tab
        $shipping_method_step = $settings['style'] == '2' ? 'shipping_address_step' : 'shipping_method_step';
        $steps = array(
            'information' => array(
                'next_step_label'   => $settings['continue_to_shipping'],
                'next_step'         => $shipping_method_step,
                'prev_step'         => '',
                'step_number'       => __('1', 'woolentor-pro')
            ),
            'shipping'    => array(
                'next_step'         => 'payment_method_step',
                'next_step_label'   => $settings['continue_to_payment'],
                'prev_step'         => 'information_step',
                'prev_step_label'   => $settings['return_to_informations'],
                'step_number'       => __('2', 'woolentor-pro')
            ),
            'payment'     => array(
                'next_step'         => '',
                'prev_step'         => $shipping_method_step,
                'prev_step_label'   => $settings['return_to_shipping'],
                'step_number'       => __('3', 'woolentor-pro')
            )
        );

        if( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ){
            $hide_shipping_step = false;
        } else{
            $hide_shipping_step = true;

            $steps['information']['next_step']          = 'payment_method_step';
            $steps['information']['next_step_label']    = $steps['shipping']['next_step_label'];
            $steps['payment']['prev_step']              = 'information_step';
            $steps['payment']['prev_step_label']        = $steps['shipping']['prev_step_label'];
            $steps['payment']['step_number']            = __('2', 'woolentor-pro');
        }
        ?>

        <?php if ( Plugin::instance()->editor->is_edit_mode() ): ?>
            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data">
        <?php endif; ?>
        
        <?php if( $settings['style'] == 1 ): ?>

        <?php if( empty($settings['navigation_placement']) ): ?>
        <!-- Step Nav Start -->
        <div class="woolentor-step-nav woolentor-step-nav-<?php echo esc_attr($settings['step_indicator_style']) ?>">
            <?php
                if(
                    $settings['step_indicator_style'] == 2 || 
                    $settings['step_indicator_style'] == 3
                ):
            ?>
            <div class="woolentor-step-nav-bar">bar</div>
            <div class="woolentor-step-nav-bar woolentor-step-nav-bar-active">bar active</div>
            <?php endif; ?>

            <ul>
                <li data-step-target="#information_step" class="woolentor-active" data-step-number="<?php echo esc_attr($steps['information']['step_number']) ?>">
                    <span class="woolentor-step-nav-number"><?php echo esc_html($steps['information']['step_number']) ?></span>
                    <span class="woolentor-step-nav-text"><?php echo esc_html($settings['information']) ?></span>
                </li>

                <?php if(!$hide_shipping_step): ?>
                <li data-step-target="#shipping_method_step" data-step-number="<?php echo esc_attr($steps['shipping']['step_number']) ?>" class="">
                    <span class="woolentor-step-nav-number"><?php echo esc_html($steps['shipping']['step_number']) ?></span>
                    <span class="woolentor-step-nav-text"><?php echo esc_html($settings['shipping']) ?></span>
                </li>
                <?php endif; ?>

                <li data-step-target="#payment_method_step" data-step-number="<?php echo esc_attr($steps['payment']['step_number']) ?>" class="">
                    <span class="woolentor-step-nav-number"><?php echo esc_html($steps['payment']['step_number']) ?></span>
                    <span class="woolentor-step-nav-text"><?php echo esc_html($settings['payment']) ?></span>
                </li>
            </ul>
        </div><!-- Step Nav End -->
        <?php endif; // navigation_placement ?>


        <div class="<?php echo esc_attr( implode(' ', $wrapper_classes) ); ?>" data-required-message="<?php echo esc_attr($settings['required_message']); ?>">

            <div id="information_step" class="woolentor-msc2-step woolentor-msc2-step-1 woolentor-active">
                <div class="woolentor-step-body">
                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                    <?php $this->billing_form( $checkout ); ?>
                </div>
                <div class="woolentor-step-footer">
                    <button class="woolentor-btn woolentor-btn-primary-5" data-step-target="#<?php echo esc_attr($steps['information']['next_step']) ?>" type="button"><?php echo esc_html($steps['information']['next_step_label']) ?></button>
                </div>
            </div>

            <?php if( !$hide_shipping_step ): ?>
            <div id="shipping_method_step" class="woolentor-msc2-step woolentor-msc2-step-2">
                <div class="woolentor-step-body">
                    <div class="woolentor-shipping-fields__field-wrapper">
                    <?php
                        $this->shipping_form( $checkout );
                        do_action( 'woocommerce_checkout_after_customer_details' );
                    ?>
                    </div>
                    <?php
                        $this->shipping_method();
                    ?>
                </div>
                <div class="woolentor-step-footer">
                    <button class="woolentor-btn woolentor-btn-text" data-step-target="#<?php echo esc_attr($steps['shipping']['prev_step']) ?>" type="button">
                        <?php if(empty($settings['section_return_buttons_icon']['value'])): ?>    
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.37998 3.95312L2.33331 7.99979L6.37998 12.0465" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.6667 8H2.44672" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        <?php else: ?>
                            <i class="<?php echo $settings['section_return_buttons_icon']['value'] ?>"></i>
                        <?php endif; ?>

                        <?php echo esc_html($steps['shipping']['prev_step_label']) ?>
                    </button>
                    <button class="woolentor-btn" data-step-target="#<?php echo esc_attr($steps['shipping']['next_step']) ?>" type="button"><?php echo esc_html($steps['shipping']['next_step_label']) ?></button>
                </div>
            </div>
            <?php endif; ?>

            <div id="payment_method_step" class="woolentor-msc2-step woolentor-msc2-step-3">
                <div class="woolentor-step-body">
                    <?php $this->payment(); ?>
                </div>
                <div class="woolentor-step-footer">
                    <button class="woolentor-btn woolentor-btn-text" data-step-target="#<?php echo esc_attr($steps['payment']['prev_step']) ?>" type="button">
                        <?php if(empty($settings['section_return_buttons_icon']['value'])): ?>    
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.37998 3.95312L2.33331 7.99979L6.37998 12.0465" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.6667 8H2.44672" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        <?php else: ?>
                            <i class="<?php echo $settings['section_return_buttons_icon']['value'] ?>"></i>
                        <?php endif; ?>
                        
                        <?php echo esc_html($steps['payment']['prev_step_label']) ?>
                    </button>
                    <?php
                        do_action( 'woocommerce_review_order_before_submit' );
                        
                        $order_button_text  = $settings['place_order'];
                        
                        echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="woolentor-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine

                        do_action( 'woocommerce_review_order_after_submit' );
                    ?>
                </div>
            </div>

        </div> <!-- .woolentor-step-wrapper -->
        
        <?php elseif($settings['style'] == 2): ?>
        <!-- Checkout Content Left Start -->
        <div class="<?php echo esc_attr( implode(' ', $wrapper_classes) ); ?>" data-required-message="<?php echo esc_attr($settings['required_message']); ?>">
            <div class="woolentor-step-wrapper woolentor-step-wrapper-9">

                <div id="information_step" data-step-toggle="slide" class="woolentor-msc2-step woolentor-step woolentor-active">
                    <div class="woolentor-step-body">
                        <div class="woolentor-block">
                            <div class="woolentor-block-heading">
                                <h4 class="woolentor-block-heading-title"><?php echo esc_html($settings['information']) ?></h4>
                            </div>
                            <div class="woolentor-block-inner">
                                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                                <?php $this->billing_form( $checkout ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="woolentor-step-footer">
                        <button class="woolentor-btn woolentor-btn-primary-9" data-step-target="#<?php echo esc_attr($steps['information']['next_step']) ?>" type="button"><?php echo esc_html($steps['information']['next_step_label']) ?></button>
                    </div>
                </div>

                <?php if( !$hide_shipping_step ): ?>
                <div id="shipping_address_step" data-step-toggle="slide" class="woolentor-msc2-step woolentor-step">
                    <div class="woolentor-step-body">
                        <div class="woolentor-block">
                            <div class="woolentor-block-heading">
                                <h4 class="woolentor-block-heading-title"><?php echo esc_html($settings['shipping']) ?></h4>
                            </div>
                            <div class="woolentor-block-inner">
                                <?php
                                    $this->shipping_form( $checkout );
                                    $this->shipping_method();
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="woolentor-step-footer">

                        <button class="woolentor-btn woolentor-btn-text" data-step-target="#<?php echo esc_attr($steps['shipping']['prev_step']) ?>" type="button">
                            <?php if(empty($settings['section_return_buttons_icon']['value'])): ?>    
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.37998 3.95312L2.33331 7.99979L6.37998 12.0465" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.6667 8H2.44672" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            <?php else: ?>
                                <i class="<?php echo $settings['section_return_buttons_icon']['value'] ?>"></i>
                            <?php endif; ?>
                            <?php echo esc_html($steps['shipping']['prev_step_label']) ?>
                        </button>

                        <button class="woolentor-btn woolentor-btn-primary-9" data-step-target="#<?php echo esc_attr($steps['shipping']['next_step']) ?>" type="button"><?php echo esc_html($steps['shipping']['next_step_label']) ?></button>
                    </div>
                </div>
                <?php endif; ?>

                <div id="payment_method_step" data-step-toggle="slide" class="woolentor-msc2-step woolentor-step">
                    <div class="woolentor-step-body">
                        <div class="woolentor-block">
                            <div class="woolentor-block-heading">
                                <h4 class="woolentor-block-heading-title"><?php echo esc_html($settings['payment']) ?></h4>
                            </div>
                            <div class="woolentor-block-inner">
                                <?php $this->payment(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="woolentor-step-footer">

                        <button class="woolentor-btn woolentor-btn-text" data-step-target="#<?php echo esc_attr($steps['payment']['prev_step']) ?>" type="button">
                            <?php if(empty($settings['section_return_buttons_icon']['value'])): ?>    
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.37998 3.95312L2.33331 7.99979L6.37998 12.0465" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.6667 8H2.44672" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            <?php else: ?>
                                <i class="<?php echo $settings['section_return_buttons_icon']['value'] ?>"></i>
                            <?php endif; ?>
                            <?php echo esc_html($steps['payment']['prev_step_label']) ?>
                        </button>

                        <?php
                        do_action( 'woocommerce_review_order_before_submit' );

                        $order_button_text  = $settings['place_order'];
                        
                        echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="woolentor-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine

                        do_action( 'woocommerce_review_order_after_submit' );
                    ?>
                    </div>
                </div>

            </div>

        </div>
        <!-- Checkout Content Left End -->
        <?php endif; ?>

        <?php
        if ( Plugin::instance()->editor->is_edit_mode() ){ echo '</form>'; }
    }

    /* Billing Form */
    public function billing_form( $checkout ){
        $settings   = $this->get_settings_for_display();

        ?>
            <div class="">

                <?php
                    if( !empty( $settings['billing_form_title'] ) ){
                        echo '<h3 class="woolentor-title">'.esc_html__( $settings['billing_form_title'], 'woolentor-pro' ).'</h3>';
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
                <div class="woocommerce-account-fields">
                    <?php if ( ! $checkout->is_registration_required() ) : ?>

                        <p class="form-row form-row-wide create-account">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( $settings['billing_form_create_an_title'], 'woolentor-pro' ); ?></span>
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


    /* Shipping Form */
    public function shipping_form( $checkout ) {
        $settings   = $this->get_settings_for_display();
        ?>
        <div class="woocommerce-shipping-fields">
            <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

                <h3 id="ship-to-different-address" class="woolentor-title">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                        <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php echo esc_html__( $settings['shipping_form_title'], 'woolentor-pro' ); ?></span>
                    </label>
                </h3>

                <div class="shipping_address">
                    <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
                    <div class="woocommerce-shipping-fields__field-wrapper">
                        <?php
                            $fields = $checkout->get_checkout_fields( 'shipping' );
                            foreach ( $fields as $key => $field ) {
                                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                            }
                        ?>
                    </div>
                    <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
                </div>

            <?php endif; ?>
        </div>
        <div class="woocommerce-additional-fields">
            <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

            <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
                <?php
                    if( !empty( $settings['shipping_form_additional_info'] ) ){
                        echo '<h3 class="woolentor-title">'.esc_html__( $settings['shipping_form_additional_info'], 'woolentor-pro' ).'</h3>';
                    }
                ?>
                <div class="woocommerce-additional-fields__field-wrapper">
                    <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                        <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
        </div>
    <?php }

    /* Payment */
    public function payment() {
        $settings   = $this->get_settings_for_display();
        ?>
            <div class="woo-checkout-payment woolentor-payment-method-1">
                <?php
                    if( !empty( $settings['payment_form_title'] ) ){
                        echo '<h3 class="woolentor-title">'.esc_html__( $settings['payment_form_title'], 'woolentor-pro' ).'</h3>';
                    }
                ?>
                <?php woocommerce_checkout_payment(); ?>
            </div>
        <?php 
    }

    /* Shipping method */
    public function shipping_method(){
        $settings   = $this->get_settings_for_display();

        $wrapper_classes = array('woolentor-shipping-method-1');
        if( !empty($settings['shipping_method_style']) ){
            $wrapper_classes[] = 'wl_style_'. $settings['shipping_method_style'];
        }
        ?>
        <div class="<?php echo esc_attr( implode(' ', $wrapper_classes) ); ?>">
            <?php
                if( !empty( $settings['shipping_method_title'] ) ){
                    echo '<h3 class="woolentor-title">'.esc_html__( $settings['shipping_method_title'], 'woolentor-pro' ).'</h3>';
                }
            ?>
            <div class="woolentor-checkout__shipping-method">
                <table>
                    <tbody>
                    <?php
                    if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

                        <?php wc_cart_totals_shipping_html(); ?>

                        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

                    <?php endif; ?> 
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}