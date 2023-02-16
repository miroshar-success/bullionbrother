<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Checkout_Payment_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-payment-method';
    }
    
    public function get_title() {
        return __( 'WL: Checkout Payment Method', 'woolentor-pro' );
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
            'woolentor-checkout',
        ];
    }

    public function get_keywords(){
        return ['checkout payment','payment method' ];
    }

    protected function register_controls() {
        // Settings
        $this->start_controls_section(
            'checkout_payment_settings',
            array(
                'label' => __( 'Settings', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
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

        $this->end_controls_section(); // Settings

        // Payment
        $this->start_controls_section(
            'checkout_payment_style',
            array(
                'label' => __( 'Payment', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'checkout_payment_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} #payment',
                )
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

        $this->end_controls_section();

        // Payment Method Heading
        $this->start_controls_section(
            'checkout_heading_style',
            array(
                'label' => __( 'Payment Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'checkout_payment_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} #payment .wc_payment_method label',
                )
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

        $this->end_controls_section();

        // Payment Content
        $this->start_controls_section(
            'checkout_payment_content_style',
            array(
                'label' => __( 'Content', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'checkout_payment_content_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} #payment .payment_box',
                )
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
                    'separator' => 'before',
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
                    'separator' => 'before',
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

        $this->end_controls_section();

        $this->start_controls_section(
            'checkout_payment_radio_button_style',
            [
                'label' => __( 'Radio Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style!' => '',
                ],
            ]
        );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'      => 'radio_button_border',
                    'label'     => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-payment-method-1 input[type=radio] ~ label::before',
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
                    'selector'  => '{{WRAPPER}} .woolentor-payment-method-1 input[type=radio]:checked ~ label::before',
                    'exclude'   => array('width'),
                ]
            );

            $this->add_control(
                'radio_selected_color',
                [
                    'label' => __( 'Selected Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-payment-method-1 input[type=radio] ~ label::after' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section();

        

        // Checkbox
        $this->start_controls_section(
            'checkbox_style_section',
            [
                'label' => esc_html__( 'Input Checkbox', 'woolentor-pros' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                'condition'=>[
                    'style!'=>'',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkbox_spacing',
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
                    '{{WRAPPER}} .woolentor-payment-method-1 .input-checkbox + span' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'=>[
                    'checkbox_style!'=>'',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkbox_position',
            [
                'label' => __( 'Position', 'woolentor-pro' ),
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
                    '{{WRAPPER}} .woolentor-payment-method-1 .input-checkbox + span' => 'top: {{SIZE}}{{UNIT}};',
                ],
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
                'condition'=>[
                    'checkbox_style!'=>'',
                ],
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
                    '{{WRAPPER}} input[type=checkbox] ~ span::before,{{WRAPPER}} .woolentor-fields-1 input[type=checkbox] ~ span::before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_checked',
            [
                'label' => __( 'Checked', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
                'condition'=>[
                    'checkbox_style!'=>'',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'checkbox_checked_border',
                'label'     => esc_html__( 'Checked Border', 'woolentor-pro' ),
                'selector'  => '{{WRAPPER}} input[type=checkbox]:checked ~ span::before',
                'exclude'   => array('width'),
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
                'condition'=>[
                    'checkbox_style!'=>'',
                ],
            ]
        );

        $this->end_controls_section(); //Checkbox

        // Payment Place Order Button
        $this->start_controls_section(
            'checkout_payment_place_order_style',
            [
                'label' => __( 'Place Order Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
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
                    '.woocommerce {{WRAPPER}} .woolentor-payment-method-1 #payment #place_order' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'style!' => '',
                ],
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
                        array(
                            'name'      => 'checkout_payment_place_order_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} #payment #place_order',
                        )
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
                        'checkout_payment_place_order_margin',
                        [
                            'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} #payment #place_order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

                    $this->add_responsive_control(
                        'checkout_payment_place_order_align',
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
                                '{{WRAPPER}} #payment #place_order' => 'float: {{VALUE}}; margin-{{VALUE}}: 0px;',
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
        if( $settings['style'] ){
            $wrapper_classes[] = 'woolentor-payment-method-1';
        }

        $wrapper_classes[] = 'wl_cb_style_'. $settings['checkbox_style'];

        echo '<div class="'. esc_attr(implode(' ', $wrapper_classes)) .'">';
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            woocommerce_checkout_payment();
        }else{
            if( is_checkout() ){
                woocommerce_checkout_payment();
            }
        }
        echo '</div>';
    }

}