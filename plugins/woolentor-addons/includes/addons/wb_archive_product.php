<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Archive_Product_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-product-archive-addons';
    }
    
    public function get_title() {
        return __( 'WL: Product Archive Layout (Default)', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
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
        return ['archive','shop','product archive','default archive'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product-archive-conent',
            [
                'label' => __( 'Archive Product', 'woolentor' ),
            ]
        );
            
            $this->add_responsive_control(
                'columns',
                [
                    'label' => __( 'Columns', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'prefix_class' => 'woolentorducts-columns%s-',
                    'min' => 1,
                    'max' => 6,
                    'default' => 4,
                    'required' => true,
                    'device_args' => [
                        Controls_Stack::RESPONSIVE_TABLET => [
                            'required' => false,
                        ],
                        Controls_Stack::RESPONSIVE_MOBILE => [
                            'required' => false,
                        ],
                    ],
                    'min_affected_device' => [
                        Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
                        Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
                    ],
                ]
            );

            $this->add_control(
                'rows',
                [
                    'label' => __( 'Rows', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 4,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'max' => 20,
                        ],
                    ],
                ]
            );

            $this->add_control(
                'paginate',
                [
                    'label' => __( 'Pagination', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                ]
            );

            $this->add_control(
                'allow_order',
                [
                    'label' => __( 'Allow Order', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'show_result_count',
                [
                    'label' => __( 'Show Result Count', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => __( 'Order by', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date' => __( 'Date', 'woolentor' ),
                        'title' => __( 'Title', 'woolentor' ),
                        'price' => __( 'Price', 'woolentor' ),
                        'popularity' => __( 'Popularity', 'woolentor' ),
                        'rating' => __( 'Rating', 'woolentor' ),
                        'rand' => __( 'Random', 'woolentor' ),
                        'menu_order' => __( 'Menu Order', 'woolentor' ),
                    ],
                    'condition' => [
                        'paginate!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => __( 'Order', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'desc',
                    'options' => [
                        'asc' => __( 'ASC', 'woolentor' ),
                        'desc' => __( 'DESC', 'woolentor' ),
                    ],
                    'condition' => [
                        'paginate!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'query_post_type',
                [
                    'type' => 'hidden',
                    'default' => 'current_query',
                ]
            );

        $this->end_controls_section();

        // Item Style Section
        $this->start_controls_section(
            'product-item-section',
            [
                'label' => esc_html__( 'Item', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product',
                ]
            );

            $this->add_responsive_control(
                'item_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_box_shadow',
                    'label' => __( 'Box Shadow', 'woolentor' ),
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product',
                ]
            );

            $this->add_responsive_control(
                'item_alignment',
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
                    'prefix_class' => 'woolentor-product-loop-item-align-',
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // image Style Section
        $this->start_controls_section(
            'product-image-section',
            [
                'label' => esc_html__( 'Image', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .attachment-woocommerce_thumbnail',
                ]
            );

            $this->add_responsive_control(
                'image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .attachment-woocommerce_thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .attachment-woocommerce_thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section(
            'product-title-section',
            [
                'label' => esc_html__( 'Title', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs('product_title_style_tabs');

                // Title Normal Style
                $this->start_controls_tab(
                    'product_title_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                $this->add_control(
                        'product_title_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .woocommerce-loop-product__title' => 'color: {{VALUE}} !important',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .title a' => 'color: {{VALUE}} !important',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'product_title_typography',
                            'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .woocommerce-loop-product__title,{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .woocommerce-loop-product__title,{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .title a',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_title_padding',
                        [
                            'label' => __( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .woocommerce-loop-product__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .woocommerce-loop-product__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_title_margin',
                        [
                            'label' => __( 'Margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Title Hover Style
                $this->start_controls_tab(
                    'product_title_style_hover_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_title_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .woocommerce-loop-product__title:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .woocommerce-loop-product__title:hover' => 'color: {{VALUE}} !important',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .title a:hover' => 'color: {{VALUE}} !important',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Price Style Section
        $this->start_controls_section(
            'product-price-section',
            [
                'label' => esc_html__( 'Price', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'sell_price_heading',
                [
                    'label' => __( 'Sale Price', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_price_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .price' => 'color: {{VALUE}} !important',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price ins' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .price ins' => 'color: {{VALUE}} !important',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .price ins .amount' => 'color: {{VALUE}} !important',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price .amount' => 'color: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_price_typography',
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price,{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .price',
                ]
            );

            // Regular Price
            $this->add_control(
                'regular_price_heading',
                [
                    'label' => __( 'Regular Price', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_regular_price_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price del' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .price del' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price del .amount' => 'color: {{VALUE}} !important',                        
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .price del .amount' => 'color: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_regular_price_typography',
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price del .amount, {{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .price del, {{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .price del',
                ]
            );

        $this->end_controls_section();

        // Rating Style Section
        $this->start_controls_section(
            'product-rating-section',
            [
                'label' => esc_html__( 'Rating', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'product_rating_color',
                [
                    'label' => __( 'Rating Star Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .star-rating' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .star-rating' => 'color: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_control(
                'product_empty_rating_color',
                [
                    'label' => __( 'Empty Rating Star Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .star-rating::before' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .star-rating::before' => 'color: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_control(
                'product_rating_star_size',
                [
                    'label' => __( 'Star Size', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .star-rating' => 'font-size: {{SIZE}}{{UNIT}} !important',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_rating_start_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                    ],
                ]
            );

        $this->end_controls_section();

        // Add to Cart Button Style Section
        $this->start_controls_section(
            'product-addtocartbutton-section',
            [
                'label' => esc_html__( 'Add To Cart Button', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs('product_addtocartbutton_style_tabs');

                // Add to cart normal style
                $this->start_controls_tab(
                    'product_addtocartbutton_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                    $this->add_control(
                        'atc_button_text_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button' => 'color: {{VALUE}};',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'atc_button_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'atc_button_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button,{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button',
                        ]
                    );

                    $this->add_responsive_control(
                        'atc_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'atc_button_typography',
                            'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button,{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button',
                        ]
                    );

                    $this->add_responsive_control(
                        'atc_button_margin',
                        [
                            'label' => __( 'Margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'atc_button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Add to cart hover style
                $this->start_controls_tab(
                    'product_addtocartbutton_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );
                    $this->add_control(
                        'atc_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button:hover' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'atc_button_hover_background_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'atc_button_hover_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product .button:hover,{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons .button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Pagination Style Section
        $this->start_controls_section(
            'product-pagination-section',
            [
                'label' => esc_html__( 'Pagination', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );
            $this->start_controls_tabs('product_pagination_style_tabs');

                // Pagination normal style
                $this->start_controls_tab(
                    'product_pagination_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_pagination_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul' => 'border-color: {{VALUE}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_pagination_padding',
                        [
                            'label' => __( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li a, {{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_link_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_link_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Pagination Active style
                $this->start_controls_tab(
                    'product_pagination_style_active_tab',
                    [
                        'label' => __( 'Active', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_pagination_link_color_hover',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_link_bg_color_hover',
                        [
                            'label' => __( 'Background Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

         // Sale Flash Style Section
        $this->start_controls_section(
            'product-saleflash-style-section',
            [
                'label' => esc_html__( 'Sale Tag', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'product_show_onsale_flash',
                [
                    'label' => __( 'Sale Flash', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __( 'Hide', 'woolentor' ),
                    'label_on' => __( 'Show', 'woolentor' ),
                    'separator' => 'before',
                    'default' => 'yes',
                    'return_value' => 'yes',
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product span.onsale' => 'display: block',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons span.onsale' => 'display: block !important',
                    ],
                    'prefix_class' => 'woolentor-sale-status-',
                ]
            );

            $this->add_control(
                'product_onsale_text_color',
                [
                    'label' => __( 'Text Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product span.onsale' => 'color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons span.onsale' => 'color: {{VALUE}} !important',
                    ],
                    'condition' => [
                        'product_show_onsale_flash' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'product_onsale_background_color',
                [
                    'label' => __( 'Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product span.onsale' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons span.onsale' => 'background-color: {{VALUE}} !important',
                    ],
                    'condition' => [
                        'product_show_onsale_flash' => 'yes',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_onsale_typography',
                    'selector' => '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product span.onsale,{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons span.onsale',
                    'condition' => [
                        'product_show_onsale_flash' => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_onsale_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                    ],
                    'condition' => [
                        'product_show_onsale_flash' => 'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_onsale_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
                    ],
                    'condition' => [
                        'product_show_onsale_flash' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'product_onsale_position',
                [
                    'label' => __( 'Position', 'woolentor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => false,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons ul.products li.product span.onsale' => '{{VALUE}}',
                        '{{WRAPPER}}.elementor-widget-woolentor-product-archive-addons span.onsale' => '{{VALUE}} !important',
                    ],
                    'selectors_dictionary' => [
                        'left' => 'right: auto; left: 0',
                        'right' => 'left: auto; right: 0',
                    ],
                    'condition' => [
                        'product_show_onsale_flash' => 'yes',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    public function woolentor_custom_product_limit( $limit = 3 ) {
        $limit = ( $this->get_settings_for_display('columns')*$this->get_settings_for_display('row') );
        return $limit;
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        if ( WC()->session && function_exists('wc_print_notices') ) {
            wc_print_notices();
        }

        if ( ! isset( $GLOBALS['post'] ) ) {
            $GLOBALS['post'] = null;
        }

        $filterable = ( isset( $settings['filterable'] ) ? rest_sanitize_boolean( $settings['filterable'] ) : true );

        $settings = $this->get_settings();
        $settings['editor_mode'] = Plugin::instance()->editor->is_edit_mode();
        add_filter( 'product_custom_limit', array( $this, 'woolentor_custom_product_limit' ) );
        $shortcode = new \Archive_Products_Render( $settings, 'products', $filterable );
        $content = $shortcode->get_content();
        $not_found_content = woolentor_products_not_found_content();

        if ( true === $filterable ) {
            $wrap_class = 'wl-filterable-products-wrap';
            $content_class = 'wl-filterable-products-content';
            $wrap_attributes = 'data-wl-widget-name="woolentor-product-archive-addons"';
            $wrap_attributes .= ' data-wl-widget-settings="' . esc_attr( htmlspecialchars( wp_json_encode( $settings ) ) ) . '"';
            ?>
            <div class="<?php echo esc_attr( $wrap_class ); ?>"<?php echo $wrap_attributes; ?>>
                <div class="<?php echo esc_attr( $content_class ); ?>">
                    <?php
                    if ( strip_tags( trim( $content ) ) ) {
                        echo $content;
                    } else{
                        echo $not_found_content;
                    }
                    ?>
                </div>
            </div>
            <?php
        } else {
            if ( strip_tags( trim( $content ) ) ) {
                echo $content;
            } else{
                echo $not_found_content;
            }
        }

    }

}

