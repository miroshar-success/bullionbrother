<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Product_Image_Accordion_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-image-accordion-product';
    }
    
    public function get_title() {
        return __( 'WL: Product Image Accordion', 'woolentor' );
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
              'woolentor_product_grid_products_count',
                [
                    'label'   => __( 'Product Limit', 'woolentor' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 4,
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
                    'condition' => [
                        'woolentor_product_grid_product_filter!' => 'show_byid',
                    ]
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
            $this->add_control(
                'content_showing_heading',
                [
                    'label' => esc_html__( 'Content Display', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
                'hide_product_category',
                [
                    'label'     => __( 'Hide Category', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .sub-title' => 'display: none !important;',
                    ],
                ]
            );
            $this->add_control(
                'hide_product_title',
                [
                    'label'     => __( 'Hide Title', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .title' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_price',
                [
                    'label'     => __( 'Hide Price', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .product-price' => 'display: none !important;',
                    ],
                ]
            );


            $this->add_control(
                'hide_product_ratting',
                [
                    'label'     => __( 'Hide Rating', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .reading' => 'display: none !important;',
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
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'product_background_color',
                    'label' => __( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content',
                    
                ]
            );
            
            // Product Category
            $this->add_control(
                'product_category_heading',
                [
                    'label' => __( 'Product Category', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_cat_typography',
                    'selector' => '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .sub-title a',                    
                ]
            );

            $this->add_control(
                'product_cat_color',
                [
                    'label' => __( 'Category Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,                   
                    'default' => '#333333',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .sub-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'product_cat_hover_color',
                [
                    'label' => __( 'Category Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#0A3ACA',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .sub-title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_cat_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Title
            $this->add_control(
                'product_title_heading',
                [
                    'label' => __( 'Product Title', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_title_typography',
                    'selector' => '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .title a',                    
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#333333',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .title a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_title_hover_color',
                [
                    'label' => __( 'Title Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#0A3ACA',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_title_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'default' => '#0A3ACA',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .product-price' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_sale_price_typography',
                    'selector' => '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .product-price',
                ]
            );

            $this->add_control(
                'product_regular_price_color',
                [
                    'label' => __( 'Regular Price Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => '#0A3ACA',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .product-price del' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_regular_price_typography',
                    'selector' => '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .product-price del',
                ]
            );

            $this->add_responsive_control(
                'product_price_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .product-price del' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'default' => '#aaaaaa',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .reading' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_rating_give_color',
                [
                    'label' => __( 'Rating Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#75828E',
                    'selectors' => [
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .reading span' => 'color: {{VALUE}};',
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
                        '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .reading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action a, {{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action .yith-wcwl-add-to-wishlist a,{{WRAPPER}} .wl_horizontal-hover-accordion .action .woocommerce.product.compare-button a:before' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'action_button_normal_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#0A3ACA',
                            'selectors' => [
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action a, {{WRAPPER}}  .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action .yith-wcwl-add-to-wishlist a, {{WRAPPER}} .wl_horizontal-hover-accordion .action .woocommerce.product.compare-button a:before' => 'border-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_normal_background_color',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action a, {{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action .yith-wcwl-add-to-wishlist a, {{WRAPPER}} .wl_horizontal-hover-accordion .action .woocommerce.product.compare-button a:before',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action a, {{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action .yith-wcwl-add-to-wishlist a,{{WRAPPER}} .wl_horizontal-hover-accordion .action .woocommerce.product.compare-button a:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action .yith-wcwl-add-to-wishlist a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .action .woocommerce.product.compare-button a:hover::before' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_control(
                        'action_button_hover_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => '#0A3ACA',
                            'selectors' => [
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action a:hover' => 'border-color: {{VALUE}} !important;',
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action .yith-wcwl-add-to-wishlist a:hover' => 'border-color: {{VALUE}} !important;',
                                '{{WRAPPER}} .wl_horizontal-hover-accordion .action .woocommerce.product.compare-button a:hover::before' => 'border-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_hover_background_color',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action a:hover, {{WRAPPER}} .wl_horizontal-hover-accordion .accordion-ul li .accordion-product-content .accordion-product-item .product-content .action .yith-wcwl-add-to-wishlist a:hover,{{WRAPPER}} .wl_horizontal-hover-accordion .action .woocommerce.product.compare-button a:hover::before',
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

        // Add to Cart Button
        $cart_btn = $button_icon = '';
        if( !empty( $settings['button_icon']['value'] ) ){
            
            $button_icon = woolentor_render_icon( $settings, 'button_icon', 'buttonicon' );
        }
        $button_text  = ! empty( $settings['add_to_cart_text'] ) ? $settings['add_to_cart_text'] : '';
        $cart_btn = $button_icon.$button_text;
        $products = new \WP_Query( $args );

        ?>     
        <div class=" ht-row ht-products woocommerce product">
            <div class="wl_horizontal-hover-accordion">
                <ul class="accordion-ul">

                <?php
                    if( $products->have_posts() ):

                        while( $products->have_posts() ): $products->the_post();
                            // Gallery Image
                            global $product;
                            
                            $btna_class = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

                            $btna_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';

                            $post_thumbnail_id = $product->get_image_id();
                            if ( $post_thumbnail_id ) {
                                $image_url = wp_get_attachment_image_url( $post_thumbnail_id,'full' );
                            }else{
                                $image_url = wc_placeholder_img_src( 'full' );
                            }

                    
              ?>

        
                <li style="background-image: url('<?php echo $image_url; ?>');">
                    <div class="accordion-product-content">
                        <div class="accordion-product-item">
                            <div class="product-content">
                                <p class="sub-title"><?php woolentor_get_product_category_list(); ?></p>
                                <h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                                <div class="product-price">
                                    <span class="new-price"><?php woocommerce_template_loop_price(); ?></span>
                                </div>
                                <?php do_action( 'woolentor_addon_after_price' ); ?>
                                <div class="action">
                                        <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="action-item <?php echo $btna_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor' );?></a>
                                    <?php 
                                     
                                        if( true === woolentor_has_wishlist_plugin() ){
                                            echo woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>');
                                        }
                                    
                                        if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() && !Plugin::instance()->editor->is_edit_mode() ){
                                            woolentor_compare_button(
                                                array( 
                                                    'btn_text'=>'<i class="fas fa-exchange-alt"></i>',
                                                    'btn_added_txt'=>'<i class="fas fa-exchange-alt"></i>' 
                                                )
                                            );
                                        }
                                    ?>

                                </div>
                                <div class="reading">
                                     <?php woocommerce_template_loop_rating(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                            
                    <!--Product End-->
                <?php endwhile; wp_reset_query(); wp_reset_postdata(); endif; ?>
                </ul>
        </div>
        </div>
               
        <?php

    }

}
