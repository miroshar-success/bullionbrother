<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Product_Tabs_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-product-tab';
    }
    
    public function get_title() {
        return __( 'WL: Product Tab', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-cart-medium';
    }
    
    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'htflexboxgrid',
            'font-awesome',
            'simple-line-icons',
            'slick',
            'woolentor-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'woolentor-widgets-scripts',
            'slick',
        ];
    }

    public function get_keywords(){
        return ['product tab','tab','tabs','tab with product','product'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'woolentor-products',
            [
                'label' => esc_html__( 'Product Settings', 'woolentor' ),
            ]
        );
        
            $this->add_control(
                'woolentor_product_style',
                [
                    'label' => esc_html__( 'Product Style', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => esc_html__( 'Style One', 'woolentor' ),
                        '2' => esc_html__( 'Style Two', 'woolentor' ),
                        '3' => esc_html__( 'Style Three', 'woolentor' ),
                    ],
                ]
            );

            $this->add_control(
                'woolentor_product_grid_product_filter',
                [
                    'label' => esc_html__( 'Filter By', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'recent',
                    'options' => [
                        'recent' => esc_html__( 'Recent Products', 'woolentor' ),
                        'featured' => esc_html__( 'Featured Products', 'woolentor' ),
                        'best_selling' => esc_html__( 'Best Selling Products', 'woolentor' ),
                        'sale' => esc_html__( 'Sale Products', 'woolentor' ),
                        'top_rated' => esc_html__( 'Top Rated Products', 'woolentor' ),
                        'mixed_order' => esc_html__( 'Mixed order Products', 'woolentor' ),
                    ],
                ]
            );
            
            $this->add_control(
                'woolentor_product_grid_column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '1' => esc_html__( '1', 'woolentor' ),
                        '2' => esc_html__( '2', 'woolentor' ),
                        '3' => esc_html__( '3', 'woolentor' ),
                        '4' => esc_html__( '4', 'woolentor' ),
                        '5' => esc_html__( '5', 'woolentor' ),
                        '6' => esc_html__( '6', 'woolentor' ),
                    ],
                    'condition' => [
                        'proslider!' => 'yes',
                    ]
                ]
            );

            $this->add_control(
              'woolentor_product_grid_row',
              [
                 'label'   => __( 'Rows', 'woolentor' ),
                 'type'    => Controls_Manager::NUMBER,
                 'default' => 1,
                 'min'     => 1,
                 'max'     => 20,
                 'step'    => 1,
              ]
            );

            $this->add_control(
              'woolentor_product_grid_products_count',
              [
                 'label'   => __( 'Number of Products', 'woolentor' ),
                 'type'    => Controls_Manager::NUMBER,
                 'default' => 4,
                 'min'     => 1,
                 'step'    => 1,
              ]
            );

            $this->add_control(
                'woolentor_product_grid_categories',
                [
                    'label' => esc_html__( 'Product Categories', 'woolentor' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_taxonomy_list(),
                ]
            );

            $this->add_control(
                'custom_order',
                [
                    'label' => esc_html__( 'Custom Order', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__( 'Order by', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => esc_html__('None','woolentor'),
                        'ID'            => esc_html__('ID','woolentor'),
                        'date'          => esc_html__('Date','woolentor'),
                        'name'          => esc_html__('Name','woolentor'),
                        'title'         => esc_html__('Title','woolentor'),
                        'comment_count' => esc_html__('Comment count','woolentor'),
                        'rand'          => esc_html__('Random','woolentor'),
                    ],
                    'condition' => [
                        'custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => esc_html__( 'Order', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','woolentor'),
                        'ASC'   => esc_html__('Ascending','woolentor'),
                    ],
                    'condition' => [
                        'custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'producttab',
                [
                    'label' => esc_html__( 'Product Tab', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'proslider!' => 'yes',
                    ]
                ]
            );
            
            $this->add_control(
                'proslider',
                [
                    'label' => esc_html__( 'Product Slider', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'description' => esc_html__('When the product tab is off, Then working slider.','woolentor'),
                ]
            );

        $this->end_controls_section();

        // Product Tab menu setting
        $this->start_controls_section(
            'woolentor-products-tab-menu',
            [
                'label' => esc_html__( 'Tab Menu Style', 'woolentor' ),
                'condition' => [
                    'producttab' => 'yes',
                    'proslider!' => 'yes',
                ]
            ]
        );

            $this->add_responsive_control(
                'woolentor-tab-menu-align',
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
                        '{{WRAPPER}} .product-tab-list.ht-text-center' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'after',
                ]
            );
            
            $this->start_controls_tabs(
                'product_tab_style_tabs'
            );

                // Tab menu style Normal
                $this->start_controls_tab(
                    'product_tab_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'tabmenutypography',
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a',
                        ]
                    );

                    $this->add_control(
                        'tab_menu_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#23252a',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_tab_menu_bg_color',
                        [
                            'label' => __( 'Product tab menu background', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabmenu_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'tabmenu_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_tab_menu_padding',
                        [
                            'label' => __( 'Tab Menu padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_tab_menu_margin',
                        [
                            'label' => __( 'Tab Menu margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                $this->end_controls_tab();// Normal tab menu style end

                // Tab menu style Hover
                $this->start_controls_tab(
                    'product_tab_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );


                    $this->add_control(
                        'tab_menu_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#23252a',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ht-tab-menus li a.htactive' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_tab_menu_hover_bg_color',
                        [
                            'label' => __( 'Product tab menu background', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a:hover' => 'background-color: {{VALUE}} !important;',
                                '{{WRAPPER}} .ht-tab-menus li a.htactive' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabmenu_hover_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a:hover',
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a.htactive',
                        ]
                    );

                    $this->add_responsive_control(
                        'tabmenu_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .ht-tab-menus li a.htactive' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();// Hover tab menu style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

        // Product slider setting
        $this->start_controls_section(
            'woolentor-products-slider',
            [
                'label' => esc_html__( 'Slider Option', 'woolentor' ),
                'condition' => [
                    'proslider' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 4,
                    'condition' => [
                        'proslider' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'proslider' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'proslider' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'woolentor'),
                    'label_on' => __('Yes', 'woolentor'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'woolentor'),
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider autoplay', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no',
                    'condition' => [
                        'proslider' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 3,
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'woolentor'),
                    'description' => __('The resolution to the tablet.', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'woolentor'),
                    'description' => __('The resolution to mobile.', 'woolentor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                ]
            );

            $this->end_controls_section(); // Slider Option end

            // Slider Button stle
            $this->start_controls_section(
                'products-slider-controller-style',
                [
                    'label' => esc_html__( 'Slider Controller Style', 'woolentor' ),
                    'condition' => [
                        'proslider' => 'yes',
                    ]
                ]
            );

                $this->start_controls_tabs(
                    'product_sliderbtn_style_tabs'
                );

                    // Slider Button style Normal
                    $this->start_controls_tab(
                        'product_sliderbtn_style_normal_tab',
                        [
                            'label' => __( 'Normal', 'woolentor' ),
                        ]
                    );

                        $this->add_control(
                            'button_style_heading',
                            [
                                'label' => __( 'Navigation Arrow', 'woolentor' ),
                                'type' => Controls_Manager::HEADING,
                            ]
                        );

                        $this->add_control(
                            'button_color',
                            [
                                'label' => __( 'Color', 'woolentor' ),
                                'type' => Controls_Manager::COLOR,
                                'default' =>'#dddddd',
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-arrow' => 'color: {{VALUE}};',
                                ],
                            ]
                        );

                        $this->add_control(
                            'button_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor' ),
                                'type' => Controls_Manager::COLOR,
                                'default' =>'#ffffff',
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-arrow' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'button_border',
                                'label' => __( 'Border', 'woolentor' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-arrow',
                            ]
                        );

                        $this->add_responsive_control(
                            'button_border_radius',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                        $this->add_responsive_control(
                            'button_padding',
                            [
                                'label' => __( 'Padding', 'woolentor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%', 'em' ],
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                                ],
                            ]
                        );

                        $this->add_control(
                            'button_style_dots_heading',
                            [
                                'label' => __( 'Navigation Dots', 'woolentor' ),
                                'type' => Controls_Manager::HEADING,
                            ]
                        );

                            $this->add_control(
                                'dots_bg_color',
                                [
                                    'label' => __( 'Background Color', 'woolentor' ),
                                    'type' => Controls_Manager::COLOR,
                                    'default' =>'#ffffff',
                                    'selectors' => [
                                        '{{WRAPPER}} .product-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                                    ],
                                ]
                            );

                            $this->add_group_control(
                                Group_Control_Border::get_type(),
                                [
                                    'name' => 'dots_border',
                                    'label' => __( 'Border', 'woolentor' ),
                                    'selector' => '{{WRAPPER}} .product-slider .slick-dots li button',
                                ]
                            );

                            $this->add_responsive_control(
                                'dots_border_radius',
                                [
                                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                                    'type' => Controls_Manager::DIMENSIONS,
                                    'selectors' => [
                                        '{{WRAPPER}} .product-slider .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                    ],
                                ]
                            );

                    $this->end_controls_tab();// Normal button style end

                    // Button style Hover
                    $this->start_controls_tab(
                        'product_sliderbtn_style_hover_tab',
                        [
                            'label' => __( 'Hover', 'woolentor' ),
                        ]
                    );


                        $this->add_control(
                            'button_style_arrow_heading',
                            [
                                'label' => __( 'Navigation', 'woolentor' ),
                                'type' => Controls_Manager::HEADING,
                            ]
                        );

                        $this->add_control(
                            'button_hover_color',
                            [
                                'label' => __( 'Color', 'woolentor' ),
                                'type' => Controls_Manager::COLOR,
                                'default' =>'#23252a',
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'color: {{VALUE}};',
                                ],
                            ]
                        );

                        $this->add_control(
                            'button_hover_bg_color',
                            [
                                'label' => __( 'Background', 'woolentor' ),
                                'type' => Controls_Manager::COLOR,
                                'default' =>'#ffffff',
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'button_hover_border',
                                'label' => __( 'Border', 'woolentor' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-arrow:hover',
                            ]
                        );

                        $this->add_responsive_control(
                            'button_hover_border_radius',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );


                        $this->add_control(
                            'button_style_dotshov_heading',
                            [
                                'label' => __( 'Navigation Dots', 'woolentor' ),
                                'type' => Controls_Manager::HEADING,
                            ]
                        );

                            $this->add_control(
                                'dots_hover_bg_color',
                                [
                                    'label' => __( 'Background Color', 'woolentor' ),
                                    'type' => Controls_Manager::COLOR,
                                    'default' =>'#282828',
                                    'selectors' => [
                                        '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'background-color: {{VALUE}} !important;',
                                        '{{WRAPPER}} .product-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                                    ],
                                ]
                            );

                            $this->add_group_control(
                                Group_Control_Border::get_type(),
                                [
                                    'name' => 'dots_border_hover',
                                    'label' => __( 'Border', 'woolentor' ),
                                    'selector' => '{{WRAPPER}} .product-slider .slick-dots li button:hover',
                                ]
                            );

                            $this->add_responsive_control(
                                'dots_border_radius_hover',
                                [
                                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                                    'type' => Controls_Manager::DIMENSIONS,
                                    'selectors' => [
                                        '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                    ],
                                ]
                            );

                    $this->end_controls_tab();// Hover button style end

                $this->end_controls_tabs();

            $this->end_controls_section(); // Tab option end

            // Style tab section
            $this->start_controls_section(
                'product_style',
                [
                    'label' => __( 'Style', 'woolentor' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->start_controls_tabs(
                'style_tabs'
            );

                // Normal style tab
                $this->start_controls_tab(
                    'style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'product_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .product-item .product-inner',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_image_padding',
                        [
                            'label' => __( 'Product Image Area Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .image-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_image_bg_color',
                        [
                            'label' => __( 'Product Image Background', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .image-wrap' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_content_padding',
                        [
                            'label' => __( 'Product Content Area Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_content_bg_color',
                        [
                            'label' => __( 'Product Content Background', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .content' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'woolentor_product_title_heading',
                        [
                            'label' => __( 'Title', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'aligntitle',
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
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .content .title' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'typography',
                            'selector' => '{{WRAPPER}} .product-item .product-inner .content .title',
                        ]
                    );

                    $this->add_control(
                        'title_color',
                        [
                            'label' => __( 'Title color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#444444',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .content .title a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'woolentor_product_price_heading',
                        [
                            'label' => __( 'Product Price', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'price_color',
                        [
                            'label' => __( 'Price color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#444444',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .content .price ' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .product-item .product-inner .content .price .amount' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'pricetypography',
                            'selector' => '{{WRAPPER}} .product-item .product-inner .content .price ',
                        ]
                    );

                    $this->add_responsive_control(
                        'alignprice',
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
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .content' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );


                $this->end_controls_tab();

                // Hover Style tab
                $this->start_controls_tab(
                    'style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'title_hovercolor',
                        [
                            'label' => __( 'Title color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#dc9a0e',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .content .title a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_hoverbg_color',
                        [
                            'label' => __( 'Product content background', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .product_information_area .content' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_hover_content_padding',
                        [
                            'label' => __( 'Product hover content area padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-item .product-inner .product_information_area .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Action Button Style
        $this->start_controls_section(
            'product_action_button_style',
            [
                'label' => __( 'Action Button', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('action_button_style_tabs');

                // Normal style tab
                $this->start_controls_tab(
                    'action_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'action_button_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#444444',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .actions a, {{WRAPPER}} .product-item .woocommerce.compare-button a.button, {{WRAPPER}} .product-item .actions a::before' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'action_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .actions' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'action_button_font_size',
                        [
                            'label' => __( 'Font Size', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 5,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 16,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .product-item .actions a::before,{{WRAPPER}} .product-item .actions a' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover style tab
                $this->start_controls_tab(
                    'action_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );
                    $this->add_control(
                        'action_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#dc9a0e',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .actions a:hover, {{WRAPPER}} .product-item .woocommerce.compare-button a.button:hover, {{WRAPPER}} .product-item .actions a:hover::before' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'action_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .product-item .actions:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $product_type       = $this->get_settings_for_display('woolentor_product_grid_product_filter');
        $per_page           = $this->get_settings_for_display('woolentor_product_grid_products_count');
        $custom_order_ck    = $this->get_settings_for_display('custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $order              = $this->get_settings_for_display('order');
        $columns            = $this->get_settings_for_display('woolentor_product_grid_column');
        $rows               = $this->get_settings_for_display('woolentor_product_grid_row');
        $tabuniqid          = $this->get_id();
        $proslider          = $this->get_settings_for_display('proslider');
        $producttab          = $this->get_settings_for_display('producttab');

        $is_rtl = is_rtl();
        $direction = $is_rtl ? 'rtl' : 'ltr';
        $slider_settings = [
            'arrows' => ('yes' === $settings['slarrows']),
            'dots' => ('yes' === $settings['sldots']),
            'autoplay' => ('yes' === $settings['slautolay']),
            'autoplay_speed' => absint($settings['slautoplay_speed']),
            'animation_speed' => absint($settings['slanimation_speed']),
            'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
            'rtl' => $is_rtl,
        ];

        $slider_responsive_settings = [
            'product_items' => $settings['slitems'],
            'scroll_columns' => $settings['slscroll_columns'],
            'tablet_width' => $settings['sltablet_width'],
            'tablet_display_columns' => $settings['sltablet_display_columns'],
            'tablet_scroll_columns' => $settings['sltablet_scroll_columns'],
            'mobile_width' => $settings['slmobile_width'],
            'mobile_display_columns' => $settings['slmobile_display_columns'],
            'mobile_scroll_columns' => $settings['slmobile_scroll_columns'],

        ];

        $slider_settings = array_merge($slider_settings, $slider_responsive_settings);

        // WooCommerce Category
        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => $per_page,
        );

        switch( $product_type ){

            case 'sale':
                $args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
            break;

            case 'featured':
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                );
            break;

            case 'best_selling':
                $args['meta_key']   = 'total_sales';
                $args['orderby']    = 'meta_value_num';
                $args['order']      = 'desc';
            break;

            case 'top_rated': 
                $args['meta_key']   = '_wc_average_rating';
                $args['orderby']    = 'meta_value_num';
                $args['order']      = 'desc';          
            break;

            case 'mixed_order':
                $args['orderby']    = 'rand';
            break;

            default: /* Recent */
                $args['orderby']    = 'date';
                $args['order']      = 'desc';
            break;
        }

        // Custom Order
        if( $custom_order_ck == 'yes' ){
            $args['orderby'] = $orderby;
            $args['order'] = $order;
        }

        $get_product_categories = $settings['woolentor_product_grid_categories']; // get custom field value
        $product_cats = str_replace(' ', '', $get_product_categories);

        if ( "0" != $get_product_categories) {
            if( is_array($product_cats) && count($product_cats) > 0 ){
                $field_name = is_numeric($product_cats[0])?'term_id':'slug';
                $args['tax_query'][] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms' => $product_cats,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }
        }

        $products = new \WP_Query( $args );

        $tabmenu = 'yes';

        if( ($proslider == 'yes') && ( $producttab != 'yes' ) ){
            $collumval = 'slide-item ht-col-xs-12';
        }else{
            $collumval = 'ht-col-lg-3 ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-50';
            if( $columns !='' ){
                if( $columns == 5){
                    $collumval = 'cus-col-5 ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-50';
                }else{
                    $colwidth = round(12/$columns);
                    $collumval = 'ht-col-lg-'.$colwidth.' ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-50';
                }
            }
        }

        ?>
          
        <div class="product-style">

            <?php if ( $producttab == 'yes' ) { ?>
                <div class="product-tab-list ht-text-center">
                    <ul class="ht-tab-menus">
                        <?php
                            $m=0;
                            if( is_array( $product_cats ) && count( $product_cats ) > 0 ){

                                // Category retrive
                                $catargs = array(
                                    'orderby'    => 'name',
                                    'order'      => 'ASC',
                                    'hide_empty' => true,
                                    'slug'       => $product_cats,
                                );
                                $prod_categories = get_terms( 'product_cat', $catargs);

                                foreach( $prod_categories as $prod_cats ){
                                    $m++;
                                    $field_name = is_numeric( $product_cats[0] ) ? 'term_id' : 'slug';
                                    $args['tax_query'] = array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'terms' => $prod_cats,
                                            'field' => $field_name,
                                            'include_children' => false
                                        ),
                                    );
                                    if( 'featured' == $product_type ){
                                        $args['tax_query'][] = array(
                                            'taxonomy' => 'product_visibility',
                                            'field'    => 'name',
                                            'terms'    => 'featured',
                                            'operator' => 'IN',
                                        );
                                    }
                                    $fetchproduct = new \WP_Query( $args );

                                    if( $fetchproduct->have_posts() ){
                                        ?>
                                            <li><a class="<?php if($m==1){ echo 'htactive';}?>" href="#woolentortab<?php echo $tabuniqid.esc_attr($m);?>">
                                                <?php echo esc_attr( $prod_cats->name,'woolentor' );?>
                                            </a></li>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
            <?php }; ?>

            <?php if( is_array( $product_cats ) && (count( $product_cats ) > 0) && ( $producttab == 'yes' ) ): ?>
                
            <?php
                $j=0;
                $tabcatargs = array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => true,
                    'slug'       => $product_cats,
                );
                $tabcat_fach = get_terms( 'product_cat', $tabcatargs );
                foreach( $tabcat_fach as $cats ):
                    $j++;
                    $field_name = is_numeric($product_cats[0])?'term_id':'slug';
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_cat',
                            'terms' => $cats,
                            'field' => $field_name,
                            'include_children' => false
                        )
                    );
                    if( 'featured' == $product_type ){
                        $args['tax_query'][] = array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN',
                        );
                    }
                    $products = new \WP_Query( $args );

                    if( $products->have_posts() ):
                    ?>
                    <div class="ht-tab-pane <?php if($j==1){echo 'htactive';} ?>" id="<?php echo 'woolentortab'.$tabuniqid.$j;?>">
                        
                        <div class="ht-row">
                            <div class="<?php echo esc_attr( $collumval );?>">
                                <?php
                                    $k=1;
                                    while( $products->have_posts() ): $products->the_post();
                                ?>

                                <div class="product-item <?php if ( $rows > 1 && ($k % $rows != 0)){ echo 'mb-30 ';} if( $settings['woolentor_product_style'] == 3){echo 'product_style_three'; }?> ">

                                    <div class="product-inner">
                                        <div class="image-wrap">
                                            <a href="<?php the_permalink();?>" class="image">
                                                <?php 
                                                    woocommerce_show_product_loop_sale_flash();
                                                    woocommerce_template_loop_product_thumbnail();
                                                ?>
                                            </a>
                                            <?php
                                                if( $settings['woolentor_product_style'] == 1){
                                                    if( true === woolentor_has_wishlist_plugin() ){
                                                        echo woolentor_add_to_wishlist_button();
                                                    }
                                                }
                                            ?>
                                            <?php if( $settings['woolentor_product_style'] == 3):?>
                                                <div class="product_information_area">

                                                    <?php
                                                        global $product; 
                                                        $attributes = $product->get_attributes();
                                                        if($attributes):
                                                            echo '<div class="product_attribute">';
                                                            foreach ( $attributes as $attribute ) :
                                                                $name = $attribute->get_name();
                                                            ?>
                                                            <ul>
                                                                <?php
                                                                    echo '<li class="attribute_label">'.wc_attribute_label( $attribute->get_name() ).esc_html__(':','woolentor').'</li>';
                                                                    if ( $attribute->is_taxonomy() ) {
                                                                        global $wc_product_attributes;
                                                                        $product_terms = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'all' ) );
                                                                        foreach ( $product_terms as $product_term ) {
                                                                            $product_term_name = esc_html( $product_term->name );
                                                                            $link = get_term_link( $product_term->term_id, $name );
                                                                            $color = get_term_meta( $product_term->term_id, 'color', true );
                                                                            if ( ! empty ( $wc_product_attributes[ $name ]->attribute_public ) ) {
                                                                                echo '<li><a href="' . esc_url( $link  ) . '" rel="tag">' . $product_term_name . '</a></li>';
                                                                            } else {
                                                                                if(!empty($color)){
                                                                                    echo '<li class="color_attribute" style="background-color: '.$color.';">&nbsp;</li>';
                                                                                }else{
                                                                                    echo '<li>' . $product_term_name . '</li>';
                                                                                }
                                                                                
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </ul>
                                                    <?php endforeach; echo '</div>'; endif;?>

                                                    <div class="actions style_two">
                                                        <?php
                                                            woocommerce_template_loop_add_to_cart();
                                                            if( true === woolentor_has_wishlist_plugin() ){
                                                                echo woolentor_add_to_wishlist_button();
                                                            }
                                                        ?>
                                                    </div>

                                                    <div class="content">
                                                        <h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
                                                        <?php woocommerce_template_loop_price();?>
                                                        <?php do_action( 'woolentor_addon_after_price' ); ?>
                                                    </div>

                                                </div>

                                            <?php else:?>
                                                <div class="actions <?php if( $settings['woolentor_product_style'] == 2){ echo 'style_two'; }?>">
                                                    <?php
                                                        if( $settings['woolentor_product_style'] == 2){
                                                            woocommerce_template_loop_add_to_cart();
                                                            if( true === woolentor_has_wishlist_plugin() ){
                                                                echo woolentor_add_to_wishlist_button();
                                                            }
                                                        }else{
                                                            woocommerce_template_loop_add_to_cart(); 

                                                            if( function_exists('woolentor_compare_button') && !Plugin::instance()->editor->is_edit_mode() && true === woolentor_exist_compare_plugin() ){
                                                                woolentor_compare_button();
                                                            }

                                                        }
                                                    ?>
                                                </div>
                                            <?php endif;?>

                                            
                                        </div>
                                        
                                        <div class="content">
                                            <h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
                                            <?php woocommerce_template_loop_price();?>
                                            <?php do_action( 'woolentor_addon_after_price' ); ?>
                                        </div>
                                    </div>

                                </div>

                           <?php if ($k % $rows == 0 && ($products->post_count != $k)) { ?>
                            </div>
                            <div class="<?php echo esc_attr($collumval);?>">
                                <?php } $k++; endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>

                    </div>
                <?php endif; endforeach;?>

            <?php else:?>
                <div class="ht-row">
                    <?php
                        $slider_main_div_style = '';
                        if( $proslider == 'yes' ){
                            $slider_main_div_style = "style='display:none'";
                            echo '<div id="product-slider-' . uniqid() . '" dir="'.$direction.'" class="product-slider" '.$slider_main_div_style.' data-settings=\'' . wp_json_encode($slider_settings) . '\'>';
                        }
                    ?>

                        <div class="<?php echo esc_attr( $collumval );?>">
                            <?php
                                $k=1;
                                if( $products->have_posts() ):
                                    while( $products->have_posts() ): $products->the_post();
                            ?>

                                <div class="product-item <?php if ( $rows > 1 && ($k % $rows != 0)){ echo 'mb-30';} if( $settings['woolentor_product_style'] == 3){echo 'product_style_three'; }?> ">

                                    <div class="product-inner">
                                        <div class="image-wrap">
                                            <a href="<?php the_permalink();?>" class="image">
                                                <?php 
                                                    woocommerce_show_product_loop_sale_flash();
                                                    woocommerce_template_loop_product_thumbnail();
                                                ?>
                                            </a>
                                            <?php
                                                if( $settings['woolentor_product_style'] == 1 ){
                                                    if( true === woolentor_has_wishlist_plugin() ){
                                                        echo woolentor_add_to_wishlist_button();
                                                    }
                                                }
                                            ?>
                                            <?php if( $settings['woolentor_product_style'] == 3):?>
                                                <div class="product_information_area">

                                                    <?php
                                                        global $product; 
                                                        $attributes = $product->get_attributes();
                                                        if($attributes):
                                                            echo '<div class="product_attribute">';
                                                            foreach ( $attributes as $attribute ) :
                                                                $name = $attribute->get_name();
                                                            ?>
                                                            <ul>
                                                                <?php
                                                                    echo '<li class="attribute_label">'.wc_attribute_label( $attribute->get_name() ).esc_html__(':','woolentor').'</li>';
                                                                    if ( $attribute->is_taxonomy() ) {
                                                                        global $wc_product_attributes;
                                                                        $product_terms = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'all' ) );
                                                                        foreach ( $product_terms as $product_term ) {
                                                                            $product_term_name = esc_html( $product_term->name );
                                                                            $link = get_term_link( $product_term->term_id, $name );
                                                                            $color = get_term_meta( $product_term->term_id, 'color', true );
                                                                            if ( ! empty ( $wc_product_attributes[ $name ]->attribute_public ) ) {
                                                                                echo '<li><a href="' . esc_url( $link  ) . '" rel="tag">' . $product_term_name . '</a></li>';
                                                                            } else {
                                                                                if(!empty($color)){
                                                                                    echo '<li class="color_attribute" style="background-color: '.$color.';">&nbsp;</li>';
                                                                                }else{
                                                                                    echo '<li>' . $product_term_name . '</li>';
                                                                                }
                                                                                
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </ul>
                                                    <?php endforeach; echo '</div>'; endif;?>

                                                    <div class="actions style_two">
                                                        <?php
                                                            woocommerce_template_loop_add_to_cart();
                                                            if( true === woolentor_has_wishlist_plugin() ){
                                                                echo woolentor_add_to_wishlist_button();
                                                            }
                                                        ?>
                                                    </div>

                                                    <div class="content">
                                                        <h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
                                                        <?php woocommerce_template_loop_price();?>
                                                        <?php do_action( 'woolentor_addon_after_price' ); ?>
                                                    </div>

                                                </div>

                                            <?php else:?>
                                                <div class="actions <?php if( $settings['woolentor_product_style'] == 2){ echo 'style_two'; }?>">
                                                    <?php
                                                        if( $settings['woolentor_product_style'] == 2){
                                                            woocommerce_template_loop_add_to_cart();
                                                            if( true === woolentor_has_wishlist_plugin() ){
                                                                echo woolentor_add_to_wishlist_button();
                                                            }
                                                        }else{
                                                            woocommerce_template_loop_add_to_cart(); 
                                                            if( function_exists('woolentor_compare_button') && !Plugin::instance()->editor->is_edit_mode() && true === woolentor_exist_compare_plugin() ){
                                                                woolentor_compare_button();
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            <?php endif;?>

                                            
                                        </div>
                                        
                                        <div class="content">
                                            <h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
                                            <?php woocommerce_template_loop_price();?>
                                            <?php do_action( 'woolentor_addon_after_price' ); ?>
                                        </div>
                                    </div>

                                </div>

                           <?php if ($k % $rows == 0 && ($products->post_count != $k)) { ?>
                            </div>
                            <div class="<?php echo esc_attr($collumval);?>">
                                <?php } $k++; endwhile; wp_reset_query(); wp_reset_postdata();  endif; ?>
                            </div>
                    <?php if( $proslider == 'yes' ){ echo '</div>';} ?>
                </div>
            <?php endif;?>

        </div>  


        <?php

    }


}

