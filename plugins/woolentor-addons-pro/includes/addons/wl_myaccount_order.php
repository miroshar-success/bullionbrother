<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Myaccount_order_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-myaccount-order-list';
    }

    public function get_title() {
        return __( 'WL: My Account Order', 'woolentor-pro' );
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
        return ['my account order','account order','order','order table'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'order_heading_style',
            array(
                'label' => __( 'Headings', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_control(
                'order_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table thead th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'order_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .shop_table thead th',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'order_heading_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .shop_table thead th',
                ]
            );
            
            $this->add_responsive_control(
                'order_heading_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'order_heading_text_align',
                [
                    'label'        => __( 'Text Alignment', 'woolentor-pro' ),
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
                        '{{WRAPPER}} .shop_table thead th' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
        
        $this->end_controls_section();

        // Order Table
        $this->start_controls_section(
            'order_table_cell_style',
            [
                'label' => __( 'Table Cell', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'order_table_cell_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'order_table_cell_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'order_table_cell_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell',
                ]
            );

            $this->add_responsive_control(
                'order_table_cell_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} {{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'order_table_cell_text_align',
                [
                    'label'        => __( 'Text Alignment', 'woolentor-pro' ),
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
                        '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

         // Order Number Table
        $this->start_controls_section(
            'order_number_style',
            [
                'label' => __( 'Order Number', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('order_number_style_tabs');

                $this->start_controls_tab(
                    'order_number_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );  
                    $this->add_control(
                        'order_table_cell_order_number_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Order Number Hover
                $this->start_controls_tab(
                    'order_number_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'order_table_cell_order_number_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

         // View Button
        $this->start_controls_section(
            'order_view_button_style',
            [
                'label' => __( 'View Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('order_view_button_style_tabs');

                $this->start_controls_tab(
                    'order_view_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );  
                    
                    $this->add_control(
                        'order_table_cell_order_view_button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'order_table_cell_order_view_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'order_table_view_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button',
                        ]
                    );

                    $this->add_responsive_control(
                        'order_table_view_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'order_table_view_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Order Number Hover
                $this->start_controls_tab(
                    'order_view_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'order_table_cell_order_view_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'order_table_cell_order_number_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                     $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'order_table_view_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woocommerce-orders-table tr.woocommerce-orders-table__row td.woocommerce-orders-table__cell a.woocommerce-button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        global $wp;
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            if( isset($wp->query_vars['orders']) ){
                $value = $wp->query_vars['orders'];
                do_action( 'woocommerce_account_orders_endpoint', $value );
                    
            }elseif( isset($wp->query_vars['view-order']) ){
                $myaccount_url = get_permalink();
                $value = $wp->query_vars['view-order'];
                do_action( 'woocommerce_account_view-order_endpoint', $value );
                
            }else{
                $value = '';
                do_action( 'woocommerce_account_orders_endpoint', $value );
            }
        }else{
            if ( ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }
            if( isset($wp->query_vars['orders']) ){
                $value = $wp->query_vars['orders'];
                do_action( 'woocommerce_account_orders_endpoint', $value );
                    
            }elseif( isset($wp->query_vars['view-order']) ){
                $myaccount_url = get_permalink();
                $value = $wp->query_vars['view-order'];
                do_action( 'woocommerce_account_view-order_endpoint', $value );
                
            }else{
                $value = '';
                do_action( 'woocommerce_account_orders_endpoint', $value );
            }
        }
    }

}