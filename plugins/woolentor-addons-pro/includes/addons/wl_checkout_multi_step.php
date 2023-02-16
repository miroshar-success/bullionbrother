<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Checkout_Multi_Step_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-multi-step-form';
    }
    
    public function get_title() {
        return __( 'WL: Multi Step Checkout', 'woolentor-pro' );
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
            'woolentor-checkout',
            'woolentor-widgets-pro',
        ];
    }

    public function get_script_depends(){
        return [
            'woolentor-multi-steps-checkout',
        ];
    }

    public function get_keywords(){
        return ['checkout form','multistep checkout','multi step','checkout'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_tabs_content',
            [
                'label' => esc_html__( 'Steps', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'steps_custom_title_heading',
                [
                    'label' => esc_html__( 'Custom Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'login_step_custom_title',
                [
                    'label' => esc_html__( 'Login', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Login', 'woolentor-pro' ),
                ]
            );

            $this->add_control(
                'billing_step_custom_title',
                [
                    'label' => esc_html__( 'Billing', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Billing', 'woolentor-pro' ),
                ]
            );

            $this->add_control(
                'shipping_step_custom_title',
                [
                    'label' => esc_html__( 'Shipping', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Shipping', 'woolentor-pro' ),
                ]
            );

            $this->add_control(
                'order_step_custom_title',
                [
                    'label' => esc_html__( 'Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Order', 'woolentor-pro' ),
                ]
            );

            $this->add_control(
                'payment_step_custom_title',
                [
                    'label' => esc_html__( 'Payment', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Payment', 'woolentor-pro' ),
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_buttons_content',
            [
                'label' => esc_html__( 'Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'show_cart_btn',
                [
                    'label' => esc_html__( 'Back To Cart Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'Hide', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'back_to_cart_btn_text',
                [
                    'label' => esc_html__( 'Back To Cart Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Back to cart', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Back to cart', 'woolentor-pro' ),
                    'condition'=>[
                        'show_cart_btn'=>'yes',
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'next_btn_text',
                [
                    'label' => esc_html__( 'Next Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Next', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Next', 'woolentor-pro' ),
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'prev_btn_text',
                [
                    'label' => esc_html__( 'Previous Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Previous', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Previous', 'woolentor-pro' ),
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'login_skip_btn_text',
                [
                    'label' => esc_html__( 'Login Skip Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'I do not have an account', 'woolentor-pro' ),
                    'default'=> esc_html__( 'I do not have an account', 'woolentor-pro' ),
                    'label_block'=>true,
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_forms_content',
            [
                'label' => esc_html__( 'Forms', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'form_require_field_message',
                [
                    'label' => esc_html__( 'Require Field Message', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'This field is required.', 'woolentor-pro' ),
                    'default'=> esc_html__( 'This field is required.', 'woolentor-pro' ),
                    'label_block' => true,
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
                    'placeholder' => esc_html__( 'Billing &amp; Shipping', 'woolentor-pro' ),
                    'default'=> esc_html__( 'Billing &amp; Shipping', 'woolentor-pro' ),
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
                'order_overview_heading',
                [
                    'label' => esc_html__( 'Order Overview', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'order_overview_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'All Order', 'woolentor-pro' ),
                    'default'=> esc_html__( 'All Order', 'woolentor-pro' ),
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

        $this->end_controls_section();

        // Tabs Style Section
        $this->start_controls_section(
            'section_tabs_menu_style',
            [
                'label' => esc_html__( 'Tabs', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'area_margin',
                [
                    'label' => esc_html__( 'Area Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-tabs-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tabs_menu_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item .woolentor-tab-number-text',
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

                    $this->add_control(
                        'tabs_menu_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item:not(.current) span' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tabs_menu_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item:not(.current) .woolentor-tab-number-text' => 'background: {{VALUE}}',
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item:not(.current) .woolentor-tab-number-text:before' => 'border-top-color: {{VALUE}}; border-bottom-color:{{VALUE}}',
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item:not(.current) .woolentor-tab-number-text:after' => 'border-left-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();
                
                // Current tabs style
                $this->start_controls_tab(
                    'tabs_menu_style_current_tab',
                    [
                        'label' => esc_html__( 'Current', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'tabs_menu_current_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item.current span' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tabs_menu_current_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item.current .woolentor-tab-number-text' => 'background: {{VALUE}}',
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item.current .woolentor-tab-number-text:before' => 'border-top-color: {{VALUE}}; border-bottom-color:{{VALUE}}',
                                '{{WRAPPER}} .woolentor-msc-tabs-menu ul li.woolentor-msc-tab-item.current .woolentor-tab-number-text:after' => 'border-left-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Forms Style Section
        $this->start_controls_section(
            'section_forms_menu_style',
            [
                'label' => esc_html__( 'Forms', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'forms_heading_section',
                [
                    'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'forms_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .wlb-msc-forms-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'forms_heading_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-msc-steps-wrapper .wlb-msc-forms-title',
                ]
            );

            $this->add_responsive_control(
                'forms_heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .wlb-msc-forms-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'forms_heading_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .wlb-msc-forms-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Fields Label Style Section
            $this->add_control(
                'forms_label_section',
                [
                    'label' => esc_html__( 'Fields label', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'forms_fields_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper form .form-row label' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'forms_fields_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-msc-steps-wrapper form .form-row label',
                ]
            );

            $this->add_responsive_control(
                'forms_fields_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper form .form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'forms_fields_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper form .form-row label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'forms_fields_required_color',
                [
                    'label' => esc_html__( 'Required Indicator Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper form .form-row label .required' => 'color: {{VALUE}}',
                    ],
                ]
            );

            // Input Box Style Section
            $this->add_control(
                'forms_input_box_section',
                [
                    'label' => esc_html__( 'Input Box', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'forms_inputbox_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper input.input-text' => 'color: {{VALUE}} !important',
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .input-text' => 'color: {{VALUE}} !important',
                        '{{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection, {{WRAPPER}} .form-row .select2-container .select2-selection .select2-selection__rendered' => 'color: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'forms_inputbox_text_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-msc-steps-wrapper input.input-text, {{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection,  {{WRAPPER}} .form-row .select2-container .select2-selection .select2-selection__rendered, {{WRAPPER}} .woolentor-msc-steps-wrapper .input-text',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'forms_inputbox_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-msc-steps-wrapper input.input-text, {{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection, {{WRAPPER}} .woolentor-msc-steps-wrapper .input-text',
                ]
            );

            $this->add_responsive_control(
                'forms_inputbox_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'forms_inputbox_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .form-row select, {{WRAPPER}} .form-row .select2-container .select2-selection' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing: content-box;',
                        '{{WRAPPER}} .form-row .select2-container .select2-selection .select2-selection__arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0; box-sizing: content-box;',
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0; box-sizing: content-box;',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'forms_inputbox_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Order review Style Section
            $this->add_control(
                'forms_order_review_section',
                [
                    'label' => esc_html__( 'Order Review Table', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'forms_order_review_border_color',
                [
                    'label' => __( 'Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table td,{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table th' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-checkout .woolentor-msc-step-item #order_review_heading' => 'border-color: {{VALUE}}',
                        'separator' => 'after',
                    ],
                ]
            );

            $this->add_control(
                'forms_order_review_table_heading_color',
                [
                    'label' => __( 'Heading Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'forms_order_review_table_heading_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table th',
                ]
            );

            $this->add_control(
                'forms_order_review_content_color',
                [
                    'label' => __( 'Content Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'forms_order_review_table_content_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table,{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table td',
                ]
            );

            $this->add_control(
                'forms_order_review_price_color',
                [
                    'label' => __( 'Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-steps-wrapper .woolentor-msc-step-review table.shop_table .woocommerce-Price-amount' => 'color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Buttons Style Section
        $this->start_controls_section(
            'section_buttons_style',
            [
                'label' => esc_html__( 'Buttons', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'buttons_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button',
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'buttons_normal_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'buttons_normal_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button',
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
                                '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'buttons_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'buttons_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-msc-nav-wrapper button.woolentor-msc-nav-button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Payment Place Order Button
        $this->start_controls_section(
            'checkout_payment_place_order_style',
            [
                'label' => __( 'Place Order Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('checkout_payment_place_order_style_tabs');
                
                // Plece order button normal
                $this->start_controls_tab(
                    'checkout_payment_place_order_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'checkout_payment_place_order_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} #payment #place_order',
                        ]
                    );

                    $this->add_control(
                        'checkout_payment_place_order_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} #payment #place_order' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'checkout_payment_place_order_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} #payment #place_order' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'checkout_payment_place_order_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} #payment #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'checkout_payment_place_order_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} #payment #place_order',
                        ]
                    );

                    $this->add_responsive_control(
                        'checkout_payment_place_order_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} #payment #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Plece order button hover
                $this->start_controls_tab(
                    'checkout_payment_place_order_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'checkout_payment_place_order_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} #payment #place_order:hover' => 'color: {{VALUE}}; transition:0.4s;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'checkout_payment_place_order_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} #payment #place_order:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'checkout_payment_place_order_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} #payment #place_order:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        $settings   = $this->get_settings_for_display();

        $checkout = wc()->checkout();

        // Check WooCommerce options
        $is_registration_enabled = $checkout->is_registration_enabled();

        $show_login_step = ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) ? false : true;

        $stop_at_login = ( ! $is_registration_enabled && $checkout->is_registration_required() && ! is_user_logged_in() ) ? true : false;

        $checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );
        $cart_url     = wc_get_cart_url();

        /* Steps Custom Title */
        $steps_title = [
            'login' => ( !empty( $settings['login_step_custom_title'] ) ? $settings['login_step_custom_title'] : __( 'Login', 'woolentor-pro' ) ),
            'billing' => ( !empty( $settings['billing_step_custom_title'] ) ? $settings['billing_step_custom_title'] : __( 'Billing', 'woolentor-pro' ) ),
            'shipping' => ( !empty( $settings['shipping_step_custom_title'] ) ? $settings['shipping_step_custom_title'] : __( 'Shipping', 'woolentor-pro' ) ),
            'review' => ( !empty( $settings['order_step_custom_title'] ) ? $settings['order_step_custom_title'] : __( 'Order', 'woolentor-pro' ) ),
            'payment' => ( !empty( $settings['payment_step_custom_title'] ) ? $settings['payment_step_custom_title'] : __( 'Payment', 'woolentor-pro' ) ),
        ];


        $all_steps = array(
            'billing'=> array(
                'title'    => $steps_title['billing'],
                'position' => 10,
            ),
            'shipping' => array(
                'title'    => $steps_title['shipping'],
                'position' => 20,
            ),
            'review' => array(
                'title'    => $steps_title['review'],
                'position' => 30,
            ),
            'payment'  => array(
                'title'    => $steps_title['payment'],
                'position' => 40,
            )
        );

        $all_steps = apply_filters( 'woolentor_multistep_allsteps', $all_steps );

        if ( $show_login_step ){
            $login_step = array(
                'login' => array(
                    'title'    => $steps_title['login'],
                    'position' => 10,
                )
            );
            $all_steps = $login_step + $all_steps;
        }

        ?>
        
        <div class="woolentor-msc-checkout" data-message="<?php echo esc_html__( $settings['form_require_field_message'], 'woolentor-pro' );?>" >
            
            <div class="woolentor-msc-tabs-menu">
                <ul class="woolentor-msc-tabs-<?php echo count( $all_steps ); ?>">
                    <?php
                        $i = 0;
                        foreach ( $all_steps as $step_key => $step ) {
                            $i++;
                            $menu_class = 'woolentor-msc-step-'.$step_key.( ( $i == 1 ) ? ' current first' : '' );

                            if( $i == count( $all_steps ) ){
                                $menu_class .= ' last';
                            }
                            ?>
                                <li class="woolentor-msc-tab-item <?php echo $menu_class; ?>" id="<?php echo 'woolentor-step-'.$i;?>">
                                    <span class="woolentor-tab-number-text">
                                        <span class="woolentor-step-number"><?php echo $i; ?></span>
                                        <span class="woolentor-step-text"><?php echo esc_html__( $step['title'],'woolentor-pro' ); ?></span>
                                    </span>
                                </li>
                            <?php
                        }
                    ?>
                </ul>
            </div>

            <!-- <div style="clear: both;"></div> -->

            <div class="woolentor-msc-steps-wrapper">

                <?php if( function_exists('wc_print_notices') ){  wc_print_notices(); } ?>

                <div id="checkout_coupon" class="woocommerce_checkout_coupon" style="display: none;">
                    <div class="woolentor-checkout-coupon-form">
                        <?php
                            ob_start();
                            woocommerce_checkout_coupon_form();
                            $coupon_form = ob_get_clean();
                            $coupon_form = str_replace( ['<form','</form'], ['<div','</div'], $coupon_form );
                            echo $coupon_form;
                        ?>
                    </div>
                </div>

                <div id="woocommerce_before_checkout_form" class="woocommerce_before_checkout_form" data-step="<?php echo apply_filters('woocommerce_before_checkout_form_step', 'step-review'); ?>" style="display: none;">
                    <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
                </div>

                <!-- Step: Login -->
                <?php
                    $step_ids = [
                        'woolentor-msc-step-item-1',
                        'woolentor-msc-step-item-2',
                        'woolentor-msc-step-item-3',
                        'woolentor-msc-step-item-4',
                    ];

                    if ( $show_login_step ) {

                        $step_ids = [
                            'woolentor-msc-step-item-2',
                            'woolentor-msc-step-item-3',
                            'woolentor-msc-step-item-4',
                            'woolentor-msc-step-item-5',
                        ];

                        echo '<div id="woolentor-msc-step-item-1" class="woolentor-msc-step-item woolentor-msc-step-login current">';
                            $this->login_form( $checkout, $stop_at_login );
                        echo '</div>';
                    }

                    if ( $stop_at_login ) { 
                        echo '</div>'; // closes the "woolentor-msc-steps-wrapper" div 
                        return false; 
                    } 

                ?>
                
                <?php if ( Plugin::instance()->editor->is_edit_mode() ): ?>
                    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data">
                <?php endif; ?>

                    <div id="<?php echo $step_ids[0];?>" class="woolentor-msc-step-item woolentor-msc-step-billing <?php if ( ! $show_login_step ) { echo 'current'; }?>">
                        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                        <?php $this->billing_form( $checkout ); ?>
                    </div>

                    <div id="<?php echo $step_ids[1];?>" class="woolentor-msc-step-item woolentor-msc-step-shipping">
                        <?php $this->shipping_form( $checkout ); ?>
                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                    </div>

                    <div id="<?php echo $step_ids[2];?>" class="woolentor-msc-step-item woolentor-msc-step-review">
                        <?php do_action( 'woolentor_before_checkout_order' ); ?>
                        <?php
                            if( !empty( $settings['order_overview_title'] ) ){
                                echo '<h3 id="order_review_heading">'.esc_html__( $settings['order_overview_title'], 'woolentor-pro' ).'</h3>';
                            }
                            woocommerce_order_review();
                            do_action( 'woolentor_after_checkout_order' );
                        ?>
                    </div>

                    <div id="<?php echo $step_ids[3];?>" class="woolentor-msc-step-item woolentor-msc-step-payment"><?php $this->payment(); ?></div>

                <?php if ( Plugin::instance()->editor->is_edit_mode() ){ echo '</form>'; } ?>
                
                <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

            </div>

            <div class="woolentor-msc-nav-wrapper">

                <?php if( $settings['show_cart_btn'] === 'yes' ):?>
                    <button data-url="<?php echo esc_url( $cart_url ); ?>" id="woolentor-msc-back-to-cart" class="woolentor-msc-nav-button" type="button"><?php echo esc_html__( $settings['back_to_cart_btn_text'], 'woolentor-pro' ); ?></button>
                <?php endif; ?>
                    
                <button id="woolentor-msc-prev" class="woolentor-msc-nav-button current" type="button"><?php echo esc_html__( $settings['prev_btn_text'], 'woolentor-pro' ); ?></button>
                <button id="woolentor-msc-next" class="woolentor-msc-nav-button <?php if ( !$show_login_step ){ echo 'current'; } ?>" type="button"><?php echo esc_html__( $settings['next_btn_text'], 'woolentor-pro' ); ?></button>
                
                <?php if ( $show_login_step ){ ?>
                    <button id="woolentor-msc-skip-login" class="woolentor-msc-nav-button current" type="button"><?php echo esc_html__( $settings['login_skip_btn_text'], 'woolentor-pro' ); ?></button>
                <?php } ?>
            </div>

        </div>

        <?php

    }

    /* Billing Form */
    public function billing_form( $checkout ){
        $settings   = $this->get_settings_for_display();

        ?>
            <div class="woocommerce-billing-fields__field-wrapper">

                <?php
                    if( !empty( $settings['billing_form_title'] ) ){
                        echo '<h3 class="wlbilling_form_title wlb-msc-forms-title">'.esc_html__( $settings['billing_form_title'], 'woolentor-pro' ).'</h3>';
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

                <h3 id="ship-to-different-address" class="wlb-msc-forms-title">
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
                        echo '<h3 class="wlshipping_form_title wlb-msc-forms-title">'.esc_html__( $settings['shipping_form_additional_info'], 'woolentor-pro' ).'</h3>';
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
            <div class="woo-checkout-payment">
                <?php
                    if( !empty( $settings['payment_form_title'] ) ){
                        echo '<h3 class="wlpayment_form_title wlb-msc-forms-title">'.esc_html__( $settings['payment_form_title'], 'woolentor-pro' ).'</h3>';
                    }
                ?>
                <?php woocommerce_checkout_payment(); ?>
            </div>
        <?php 
    }

    /* Login form */
    public function login_form( $checkout, $stop_at_login ){
        ob_start();
        ?>
        <div id="checkout_login" class="woocommerce_checkout_login">
            <?php
                woocommerce_login_form(
                    [
                        'message'  => apply_filters( 'woocommerce_checkout_logged_in_message', __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'woolentor-pro' ) ),
                        'redirect' => wc_get_page_permalink( 'checkout' ),
                        'hidden'   => false,
                    ]
                );
            ?>
        </div>
        <?php
        $login_form_html = ob_get_clean();
        $login_form_html = str_replace( ['<form','</form'], ['<div','</div'], $login_form_html );
        echo $login_form_html;
        if ( $stop_at_login ) {
            echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

}