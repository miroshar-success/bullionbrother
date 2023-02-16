<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Data_Tab_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-data-tabs';
    }

    public function get_title() {
        return __( 'WL: Product Data Tabs', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-tabs';
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
        ];
    }

    public function get_keywords(){
        return ['product','data tab','product tabs','tabs','product info tab'];
    }

    protected function register_controls() {

        // Product Style
        $this->start_controls_section(
            'product_tabs_style_box',
            array(
                'label' => __( 'Tab Menu Box', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_tabs_style_box_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs',
                ]
            );

            $this->add_responsive_control(
                'product_tabs_style_box_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'product_tabs_style_box_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_tabs_style_section',
            array(
                'label' => __( 'Tab Menu', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->start_controls_tabs( 'data_tabs_style' );

                $this->start_controls_tab( 'normal_data_tab_style',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'tab_text_color',
                        [
                            'label' => __( 'Text Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tab_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tab_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'tab_typography',
                            'label' => __( 'Typography', 'woolentor' ),
                            'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
                        ]
                    );

                    $this->add_control(
                        'tab_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'tab_text_align',
                        [
                            'label' => __( 'Alignment', 'woolentor' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => __( 'Left', 'woolentor' ),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => __( 'Center', 'woolentor' ),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => __( 'Right', 'woolentor' ),
                                    'icon' => 'eicon-text-align-right',
                                ],
                                'justify' => [
                                    'title' => __( 'Justified', 'woolentor' ),
                                    'icon' => 'eicon-text-align-justify',
                                ],
                            ],
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs' => 'text-align: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Active Tab style
                $this->start_controls_tab( 'active_data_tab_style',
                    [
                        'label' => __( 'Active', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'active_tab_text_color',
                        [
                            'label' => __( 'Text Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'active_tab_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'background-color: {{VALUE}};border-bottom-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'active_tab_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-color: {{VALUE}} {{VALUE}} {{active_tab_bg_color.VALUE}} {{VALUE}}',
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li:not(.active)' => 'border-bottom-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'active_tab_typography',
                            'label' => __( 'Typography', 'woolentor' ),
                            'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a',
                        ]
                    );

                    $this->add_control(
                        'active_tab_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'selectors' => [
                                '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'product_data_tab_content_style',
            [
                'label' => __( 'Content', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tab_description_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
                ]
            );

            $this->add_control(
                'tab_content_description_color',
                [
                    'label' => __( 'Text Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woocommerce-Tabs-panel' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_heading_typography',
                    'label' => __( 'Heading Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel h2',
                ]
            );

            $this->add_control(
                'content_heading_color',
                [
                    'label' => __( 'Heading Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woocommerce-Tabs-panel h2' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'content_heading_margin',
                [
                    'label' => __( 'Heading Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woocommerce-Tabs-panel h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        if( woolentor_is_preview_mode() ){
            echo \WooLentor_Default_Data::instance()->default( $this->get_name() );
        }else{
            global $product;
            if ( empty( $product ) ) {
                return;
            }
            setup_postdata( $product->get_id() );
            wc_get_template( 'single-product/tabs/tabs.php' );
        }

    }

}