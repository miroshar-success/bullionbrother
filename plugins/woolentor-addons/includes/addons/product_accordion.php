<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Product_Accordion_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-accordion-product';
    }
    
    public function get_title() {
        return __( 'WL: Product Accordion', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-cart-light';
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
            'woolentor-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'woolentor-widgets-scripts',
        ];
    }

    public function get_keywords(){
        return ['slider','product','universal','universal product','universal layout'];
    }

    protected function register_controls() {

        // Product Content
        $this->start_controls_section(
            'woolentor-products-layout-setting',
            [
                'label' => esc_html__( 'Layout Settings', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'product_content_style',
                [
                    'label'   => __( 'Background Type', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '2',
                    'options' => [
                        '1'   => __( 'Background Color', 'woolentor' ),
                        '2'   => __( 'Gradient Color', 'woolentor' ),
                    ]
                ]
            );

        $this->end_controls_section();
        // Product Query
        $this->start_controls_section(
            'woolentor-products',
            [
                'label' => esc_html__( 'Query Settings', 'woolentor' ),
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
                        'mixed_order' => esc_html__( 'Random Products', 'woolentor' ),
                        'show_byid' => esc_html__( 'Show By Id', 'woolentor' ),
                        'show_byid_manually' => esc_html__( 'Add ID Manually', 'woolentor' ),
                    ],
                ]
            );

            $this->add_control(
                'woolentor_product_id',
                [
                    'label' => __( 'Select Product', 'woolentor' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_post_name( 'product' ),
                    'condition' => [
                        'woolentor_product_grid_product_filter' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
                'woolentor_product_ids_manually',
                [
                    'label' => __( 'Product IDs', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition' => [
                        'woolentor_product_grid_product_filter' => 'show_byid_manually',
                    ]
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
                    'condition' => [
                        'woolentor_product_grid_product_filter!' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
              'woolentor_product_grid_products_count',
                [
                    'label'   => __( 'Product Limit', 'woolentor' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 4,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'woolentor_custom_order',
                [
                    'label' => esc_html__( 'Custom order', 'woolentor' ),
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
                        'woolentor_custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => esc_html__( 'order', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','woolentor'),
                        'ASC'   => esc_html__('Ascending','woolentor'),
                    ],
                    'condition' => [
                        'woolentor_custom_order' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Product Content
        $this->start_controls_section(
            'woolentor-products-content-setting',
            [
                'label' => esc_html__( 'Content Settings', 'woolentor' ),
            ]
        );  

            $this->add_control(
                'add_to_cart_text',
                [
                    'label'         => esc_html__( 'Add to Cart Button Text', 'woolentor' ),
                    'description'   => esc_html__( 'This text effect only for simple product.', 'woolentor' ),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => esc_html__( 'Buy', 'woolentor' ),
                    'placeholder'   => esc_html__( 'Type your cart button text', 'woolentor' ),
                    'label_block'   => true,
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label'       => esc_html__( 'Add to Cart Button Icon', 'woolentor' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'buttonicon',
                    'default'=>[
                        'value'  => 'fa fa-shopping-cart',
                        'library'=> 'solid',
                    ]
                ]
            );

            $this->add_responsive_control(
                'icon_specing',
                [
                    'label' => esc_html__( 'Icon Spacing', 'woolentor' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 7,
                    ],
                    'condition' => [
                        'button_icon[value]!' => '',
                    ],
                    'selectors' => [ 
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a.action-item i'  => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a.action-item i'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );            

            $this->add_group_control(
                \Elementor\Group_Control_Image_Size::get_type(),
                [
                    'name' => 'thumbnailsize',
                    'default' => 'large',
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'content_showing_heading',
                [
                    'label' => esc_html__( 'Content Display', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
                'hide_product_content',
                [
                    'label'     => __( 'Hide Content', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-content-top p' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
              'content_count',
                [
                    'label'   => __( 'Content Limit', 'woolentor' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 15,
                    'step'    => 1,
                    'condition'=>[
                        'hide_product_content'=> ''
                    ]
                ]
            );

            $this->add_control(
                'hide_product_price',
                [
                    'label'     => __( 'Hide Price', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .product-price' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_ratting',
                [
                    'label'     => __( 'Hide Rating', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-content-top .reading' => 'display: none !important;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style  section
        $this->start_controls_section(
            'universal_product_style_section',
            [
                'label' => __( 'Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'product_inner_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type'  => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .wl_product-accordion-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_inner_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type'  => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .wl_product-accordion-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'product1_background_color',
                    'label' => __( 'Background', 'woolentor' ),
                    'condition' => [
                        'product_content_style' => '1'
                    ],
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wl_product-accordion.wl_product-accordion-two .wl_product-accordion-card ',
                    
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'product2_background_color',
                    'label' => __( 'Background', 'woolentor' ),
                    'condition' => [
                        'product_content_style' => '2'
                    ],
                    'types' => [ 'gradient' ],
                   'selector' => '{{WRAPPER}} .wl_product-accordion .wl_product-accordion-card',
                    
                ]
            );

            //title area
            $this->add_control(
                'product_title_area_heading',
                [
                    'label' => __( 'Title Area', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );


            $this->add_control(
                'product_border_title_color',
                [
                    'label' => __( 'Border Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-content-top' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_title_typography',
                    'selector' => '{{WRAPPER}} .wl_product-accordion .wl_product-accordion-card .wl_product-accordion-head',                    
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .wl_product-accordion-card .wl_product-accordion-head' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'product_collapse_icon_color',
                [
                    'label' => __( 'Collapse Icon Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                   'default' => '#3951E1',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .wl_product-accordion-card.active .wl_product-accordion-head-indicator i' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'product_collapse_bg_color',
                [
                    'label' => __( 'Collapse Background', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .wl_product-accordion-card .wl_product-accordion-head-indicator' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            //product content
            $this->add_control(
                'product_content_area_heading',
                [
                    'label' => __( 'Content Area', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_content_typography',
                    'selector' => '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-content-top p',                    
                ]
            );

            $this->add_control(
                'product_content_color',
                [
                    'label' => __( 'Content Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-content-top p' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'product_content_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-content-top p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            // Product Price
            $this->add_control(
                'product_price_heading',
                [
                    'label' => __( 'Product Price', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_sale_price_color',
                [
                    'label' => __( 'Sale Price Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                   'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .product-price' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_sale_price_typography',
                    'selector' => '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .product-price',
                ]
            );

            $this->add_control(
                'product_regular_price_color',
                [
                    'label' => __( 'Regular Price Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .product-price del' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_regular_price_typography',
                    'selector' => '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .product-price del',
                ]
            );

            $this->add_responsive_control(
                'product_price_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Rating
            $this->add_control(
                'product_rating_heading',
                [
                    'label' => __( 'Product Rating', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_rating_color',
                [
                    'label' => __( 'Empty Rating Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .product-content .reading .star-rating' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_rating_give_color',
                [
                    'label' => __( 'Rating Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                   'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .product-content .reading .star-rating span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_rating_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_product-accordion .product-content .reading .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Style End

        // Style Action Button tab section
        $this->start_controls_section(
            'universal_product_action_button_style_section',
            [
                'label' => __( 'Action Button Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs('product_action_button_style_tabs');

                // Normal
                $this->start_controls_tab(
                    'product_action_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_action_button_normal_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#333333',
                            'selectors' => [
                                '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a' => 'color: {{VALUE}};',
                                '{{WRAPPER}}  .wl_product-accordion .card-body .product-content .product-acontent-bottom .action .wishlist a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_product-accordion .action .woocommerce.product.compare-button a:before' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a.added_to_cart.wc-forward:after' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_normal_background_color',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a, {{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action .wishlist a, {{WRAPPER}} .wl_product-accordion .action .woocommerce.product.compare-button a:before, {{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a.added_to_cart.wc-forward:after',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a, {{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action .wishlist a, {{WRAPPER}} .wl_product-accordion .action .woocommerce.product.compare-button a:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover
                $this->start_controls_tab(
                    'product_action_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_action_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#333333',
                            'selectors' => [
                                '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}}  .wl_product-accordion .card-body .product-content .product-acontent-bottom .action .wishlist a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_product-accordion .action .woocommerce.product.compare-button a:hover::before' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a.added_to_cart.wc-forward:after' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_hover_background_color',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a:hover, {{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action .wishlist a:hover, {{WRAPPER}} .wl_product-accordion .action .woocommerce.product.compare-button a:hover::before, {{WRAPPER}} .wl_product-accordion .card-body .product-content .product-acontent-bottom .action a.added_to_cart.wc-forward:hover:after',
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
        $custom_order_ck    = $this->get_settings_for_display('woolentor_custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $order              = $this->get_settings_for_display('order');
        $tabuniqid          = $this->get_id();
       
        // Query Argument
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

            case 'show_byid':
                $args['post__in'] = $settings['woolentor_product_id'];
            break;

            case 'show_byid_manually':
                $args['post__in'] = explode( ',', $settings['woolentor_product_ids_manually'] );
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


        // Thumbanail Image size
        $image_size = 'woocommerce_thumbnail';
        $size = $settings['thumbnailsize_size'];
        if( $size === 'custom' ){
            $image_size = [
                (int)$settings['thumbnailsize_custom_dimension']['width'],
                (int)$settings['thumbnailsize_custom_dimension']['height']
            ];
        }else{
            $image_size = $size;
        }

        // Add to Cart Button
        $cart_btn = $button_icon = '';
        if( !empty( $settings['button_icon']['value'] ) ){
            
            $button_icon = woolentor_render_icon( $settings, 'button_icon', 'buttonicon' );
        }
        $button_text  = ! empty( $settings['add_to_cart_text'] ) ? $settings['add_to_cart_text'] : '';

        $cart_btn_content = $button_icon.$button_text;

        $products = new \WP_Query( $args );

    
        ?>    
        <div class=" ht-row ht-products woocommerce product">
            <div class="wl_product-accordion <?php if($settings['product_content_style']=='1'){echo esc_attr('wl_product-accordion-two'); } ?>">
                
                <?php
                    if( $products->have_posts() ):

                        $i=0;
                        while( $products->have_posts() ): $products->the_post();
                            $i++;
                             //Gallery Image
                           global $product;

                           $btn_class = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

                            $btn_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';

                            if( $product->get_type() !== 'simple' ){
                                $cart_btn = $button_icon.$product->add_to_cart_text();
                            }else{
                                $cart_btn = $cart_btn_content;
                            }
                       
                            $content_count = wp_trim_words(get_the_content(),$settings['content_count'],'');
                ?>                           

                            <div class="wl_product-accordion-card <?php if( $i ==1){echo esc_attr('active'); } ?>">
                                <div class="wl_product-accordion-head <?php echo $tabuniqid; ?>">
                                    <span class="wl_product-accordion-head-text"><?php the_title(); ?></span>
                                    <span class="wl_product-accordion-head-indicator"><i class="fa fa-caret-down"></i><i class="fa fa-caret-up"></i></span>
                                </div>
                                <div class="wl_product-accordion-body <?php echo $tabuniqid; ?> ">
                                    <div class="wl_product-accordion-content">
                                        <div class="card-body">
                                            <div class="product-thumbnail">
                                                <a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_image($image_size); ?></a>
                                            </div>
                                            <div class="product-content">
                                                <div class="product-content-top">
                                                    <p><?php echo $content_count; ?></p>
                                                    <div class="reading">
                                                        <?php woocommerce_template_loop_rating(); ?>
                                                    </div>
                                                </div>
                                                <div class="product-acontent-bottom">
                                                    <div class="product-price">
                                                        <span class="new-price"><?php woocommerce_template_loop_price();?></span>
                                                    </div>
                                                    <ul class="action">
                                                        <li class="btn_cart">
                                                            <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="action-item <?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor' );?></a>
                                                        </li>
                                                        <?php
                                                            if( true === woolentor_has_wishlist_plugin() ){
                                                                echo '<li>'.woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>').'</li>';
                                                            }
                                                        
                                                            if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() && !Plugin::instance()->editor->is_edit_mode() ){
                                                                echo '<li>';
                                                                    woolentor_compare_button( 
                                                                        array( 
                                                                            'btn_text'=>'<i class="fas fa-exchange-alt"></i>',
                                                                            'btn_added_txt'=>'<i class="fas fa-exchange-alt"></i>' 
                                                                        )
                                                                    );
                                                               echo '</li>';
                                                            }
                                                        ?> 
                                                    </ul>
                                                </div>
                                                <?php do_action( 'woolentor_addon_after_price' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <!--Product End-->
                <?php endwhile; wp_reset_query(); wp_reset_postdata(); endif; ?>
                
            </div>
        </div>

        <script>
                ;jQuery(document).ready(function($) {
                    'use strict';
                     (function HTProductAccordionFunction() {
                        var HTProductAccordionHead = $('.wl_product-accordion-head.<?php echo $tabuniqid; ?>'),
                            HTProductAccordionBody = $('.wl_product-accordion-body.<?php echo $tabuniqid; ?>');
                        HTProductAccordionBody.hide()
                        $('.wl_product-accordion-card.active').find('.wl_product-accordion-body.<?php echo $tabuniqid; ?>').slideDown();
                        HTProductAccordionHead.on('click', function(e) {
                            e.preventDefault();
                            var $this = $(this);

                            if ($this.parent('.wl_product-accordion-card').hasClass('active')) {
                                $this.parent('.wl_product-accordion-card').removeClass('active').find('.wl_product-accordion-body.<?php echo $tabuniqid; ?>').slideUp();
                            } else {
                                $this.parent('.wl_product-accordion-card').addClass('active').find('.wl_product-accordion-body.<?php echo $tabuniqid; ?>').slideDown();
                                $this.parent().siblings('.wl_product-accordion-card').removeClass('active').find('.wl_product-accordion-body.<?php echo $tabuniqid; ?>').slideUp();
                            }
                        })
                     })();

                });
            </script>
               
        <?php

    }

}