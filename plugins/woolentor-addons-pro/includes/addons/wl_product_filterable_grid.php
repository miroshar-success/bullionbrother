<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Product_Filterable_grid_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-filtarable-grid-product';
    }
    
    public function get_title() {
        return __( 'WL: Filterable Product Grid', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-products';
    }
    
    public function get_categories() {
        return [ 'woolentor-addons-pro' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-filtarable-grid'];
    }

    public function get_script_depends() {
        return ['masonry','wlisotope','woolentor-widgets-scripts-pro'];
    }

    public function get_keywords(){
        return ['woolentor','product','filter','gallery','grid'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'woolentor-products',
            [
                'label' => esc_html__( 'Query Settings', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'woolentor_product_grid_product_filter',
                [
                    'label' => esc_html__( 'Filter By', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'recent',
                    'options' => [
                        'recent' => esc_html__( 'Recent Products', 'woolentor-pro' ),
                        'featured' => esc_html__( 'Featured Products', 'woolentor-pro' ),
                        'best_selling' => esc_html__( 'Best Selling Products', 'woolentor-pro' ),
                        'sale' => esc_html__( 'Sale Products', 'woolentor-pro' ),
                        'top_rated' => esc_html__( 'Top Rated Products', 'woolentor-pro' ),
                        'mixed_order' => esc_html__( 'Random Products', 'woolentor-pro' ),
                        'show_byid' => esc_html__( 'Show By ID', 'woolentor-pro' ),
                        'show_byid_manually' => esc_html__( 'Add ID Manually', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'woolentor_product_id',
                [
                    'label' => __( 'Select Product', 'woolentor-pro' ),
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
                    'label' => __( 'Product IDs', 'woolentor-pro' ),
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
                    'label'   => __( 'Product Limit', 'woolentor-pro' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 3,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'woolentor_product_grid_categories',
                [
                    'label' => esc_html__( 'Product Categories', 'woolentor-pro' ),
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
                    'label' => esc_html__( 'Custom Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__( 'Order by', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => esc_html__('None','woolentor-pro'),
                        'ID'            => esc_html__('ID','woolentor-pro'),
                        'date'          => esc_html__('Date','woolentor-pro'),
                        'name'          => esc_html__('Name','woolentor-pro'),
                        'title'         => esc_html__('Title','woolentor-pro'),
                        'comment_count' => esc_html__('Comment count','woolentor-pro'),
                        'rand'          => esc_html__('Random','woolentor-pro'),
                    ],
                    'condition' => [
                        'woolentor_custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => esc_html__( 'Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','woolentor-pro'),
                        'ASC'   => esc_html__('Ascending','woolentor-pro'),
                    ],
                    'condition' => [
                        'woolentor_custom_order' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Product Content
        $this->start_controls_section(
            'additional-setting',
            [
                'label' => esc_html__( 'Additional Settings', 'woolentor-pro' ),
            ]
        );
            $this->add_control(
                'show_filter_menu',
                [
                    'label' => __( 'Show Filter Menu', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'title_length',
                [
                    'label' => __( 'Title Length', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 2,
                ]
            );

            $this->add_responsive_control(
                'small_width',
                [
                    'label' => __( 'Small Column Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1300,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_filter_grid__item' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'large_width',
                [
                    'label' => __( 'Large Column Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1300,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 40,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .grid__item--size-a' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'content_add_to_cart_settings',
                [
                    'label' => esc_html__( 'Cart Button Settings', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'add_to_cart_text',
                [
                    'label' => esc_html__( 'Add to Cart Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Type your cart button text', 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'button_icon',
                [
                    'label'       => esc_html__( 'Add to Cart Button Icon', 'woolentor-pro' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'buttonicon',
                    'default'=>[
                        'value'  => 'fas fa-shopping-cart',
                        'library'=> 'solid',
                    ],
                ]
            );

            $this->add_control(
                'button_icon_align',
                [
                    'label'   => esc_html__( 'Add to Cart Icon Position', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left'   => esc_html__( 'Left', 'woolentor-pro' ),
                        'right'  => esc_html__( 'Right', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'button_icon[value]!' => '',
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_responsive_control(
                'icon_specing',
                [
                    'label' => esc_html__( 'Icon Spacing', 'woolentor-pro' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 5,
                    ],
                    'condition' => [
                        'button_icon[value]!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item a.woolentor-button-icon-right i'  => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item a.woolentor-button-icon-left i'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_sale_badge',
                [
                    'label'     => __( 'Hide Sale Badge', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_filter_grid__item .ht-product-label' => 'display: none !important;',
                    ],
                ]
            );
            $this->add_control(
                'product_sale_badge_type',
                [
                    'label'     => __( 'Sale Badge Type', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'default',
                    'options'   => [
                        'default'       => __( 'Default', 'woolentor-pro' ),
                        'custom'        => __( 'Custom', 'woolentor-pro' ),
                        'dis_percent'   => __( 'Percentage', 'woolentor-pro' ),
                        'dis_price'     => __( 'Discount Amount', 'woolentor-pro' ),
                    ],                    
                    'condition' => [
                        'hide_product_sale_badge!' => 'yes'
                    ]
                ]
            );
            $this->add_control(
                'product_sale_badge_custom',
                [
                    'label'     => __( 'Custom Badge Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => 'Sale!',
                    'condition' => [
                        'product_sale_badge_type' =>'custom'
                    ]
                ]
            );
            $this->add_control(
                'product_sale_percent_position',
                [
                    'label'     => __( 'Additional Text Position', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'before',
                    'options'   => [
                        'after' => __( 'After', 'woolentor-pro' ),
                        'before'=> __( 'Before', 'woolentor-pro' ),
                    ],
                    'condition' => [
                        'product_sale_badge_type' => array('dis_percent','dis_price'),
                    ]
                ]
            );
            $this->add_control(
                'product_after_badge_percent',
                [
                    'label'     => __( 'After Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'condition' => [
                        'product_sale_percent_position' =>'after',
                        'product_sale_badge_type' => array('dis_percent','dis_price'),
                    ]
                ]
            );
            $this->add_control(
                'product_before_badge_percent',
                [
                    'label'     => __( 'Before Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'condition' => [
                        'product_sale_percent_position' =>'before',
                        'product_sale_badge_type' => array('dis_percent','dis_price'),
                    ]
                ]
            );

        $this->end_controls_section();

        // Menu Style tab section
        $this->start_controls_section(
            'menu_style_section',
            [
                'label' => esc_html__( 'Menu', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_filter_menu'=>'yes',
                ]
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'area_border',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-filter-menu',
                ]
            );

            $this->add_responsive_control(
                'area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'area_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'area_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'area_background',
                    'label' => esc_html__( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-filter-menu',
                ]
            );

            $this->add_responsive_control(
                'area_alignment',
                [
                    'label'   => esc_html__( 'Alignment', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start'    => [
                            'title' => esc_html__( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-menu > ul'   => 'justify-content: {{VALUE}};',
                    ],
                    'prefix_class'=>'woolentor-menu-align-%s',
                ]
            );

            $this->add_control(
                'menu_item_heading',
                [
                    'label' => esc_html__( 'Menu Item', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'menu_item_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-filter-menu > ul > li',
                ]
            );

            $this->add_responsive_control(
                'menu_item_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-menu > ul > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_space',
                [
                    'label' => esc_html__( 'Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-menu > ul > li' => 'margin: 0 {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('menu_style_tabs');
                
                $this->start_controls_tab(
                    'menu_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_normal_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-filter-menu > ul > li' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'menu_style_active_tab',
                    [
                        'label' => esc_html__( 'Active', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_active_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-filter-menu > ul > li.active' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-filter-menu > ul > li:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-filter-menu > ul > li::before' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'menu_active_bg_before_color',
                        [
                            'label'     => esc_html__( 'Active Border Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-filter-menu > ul > li::before' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Item Style tab section
        $this->start_controls_section(
            'item_style_section',
            [
                'label' => esc_html__( 'Item Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_filter_grid__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_margin',
                [
                    'label' => esc_html__( 'margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_filter_grid__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_border',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_filter_grid__item',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'exclude'=>['image'],
                    'selector' => '{{WRAPPER}} .woolentor_filter_grid__item',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_filter_grid__item',
                ]
            );

        $this->end_controls_section();

        // Content Style tab section
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__( 'Content Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'content_image_heading',
                [
                    'label' => esc_html__( 'Image', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'image_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .image__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .image__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'image_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .image__item',
                ]
            );

            $this->add_responsive_control(
                'image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .image__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'image_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .image__item',
                    'exclude'=>['image'],
                ]
            );

            $this->add_control(
                'content_title_heading',
                [
                    'label' => esc_html__( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__title',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'content_category_heading',
                [
                    'label' => esc_html__( 'Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'category_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__brand a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'category_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__brand a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'category_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__brand a',
                ]
            );

            $this->add_responsive_control(
                'category_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__brand' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'content_price_heading',
                [
                    'label' => esc_html__( 'Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'price_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__price span' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__price' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'price_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__price span',
                ]
            );

            $this->add_responsive_control(
                'price_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-grid .woolentor_filter_grid__item .meta__price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_addtocart_heading',
                [
                    'label' => __( 'Cart Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cart_btn_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} a.woolentor_cart_action',
                ]
            );

            $this->add_responsive_control(
                'cart_btn_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} a.woolentor_cart_action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_btn_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} a.woolentor_cart_action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_btn_icon_size',
                [
                    'label' => esc_html__( 'Icon Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} a.woolentor_cart_action i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs( 'button_style_tabs' );
                
                $this->start_controls_tab(
                    'button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'cart_btn_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} a.woolentor_cart_action' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_btn_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} a.woolentor_cart_action',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'cart_btn_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} a.woolentor_cart_action',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();
                
                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'cart_btn_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} a.woolentor_cart_action:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_btn_hover_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} a.woolentor_cart_action:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'cart_btn_hover_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} a.woolentor_cart_action:hover',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'product_badge_heading',
                [
                    'label' => __( 'Product Badge', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_badge_color',
                [
                    'label' => __( 'Badge Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_filter_grid__item span.ht-product-label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_badge_bg_color',
                [
                    'label' => __( 'Badge Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_filter_grid__item span.ht-product-label' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_badge_typography',
                    'selector' => '{{WRAPPER}} .woolentor_filter_grid__item span.ht-product-label',
                ]
            );

            $this->add_responsive_control(
                'product_badge_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_filter_grid__item span.ht-product-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $product_type       = $this->get_settings_for_display('woolentor_product_grid_product_filter');
        $per_page           = $this->get_settings_for_display('woolentor_product_grid_products_count');
        $custom_order_ck    = $this->get_settings_for_display('woolentor_custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $order              = $this->get_settings_for_display('order');
        $id                 = $this->get_id();

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

        $product_cats = $settings['woolentor_product_grid_categories'];
        $filter_menu = '';

        if( is_array( $product_cats ) && count( $product_cats ) > 0 ){
            $args['tax_query'][] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'terms' => $product_cats,
                    'field' => 'slug',
                    'include_children' => false
                )
            );

            $filter_menu = $product_cats;
            $filter_menu = array_merge( array( 'allfi' => 'All' ), $filter_menu );
        }

        $products = new \WP_Query( $args );

        ?>

        <!-- Filter Menu Start -->
        <?php if ( $settings['show_filter_menu'] == 'yes' && is_array( $filter_menu ) && count( $filter_menu ) > 0 ): ?>
            <div class="woolentor-filter-menu">
                <ul data-target="#filterable-grid-<?php echo $id; ?>">
                    <?php
                        foreach ( $filter_menu as $menukey => $menu ) {
                            $menu_name = get_term_by( 'slug', $menu, 'product_cat' );
                            if( $menukey === 'allfi' ){
                                echo '<li class="active" data-filter="*">'.esc_html( $filter_menu[$menukey] ).'</li>';
                            }else{
                                echo '<li data-filter=".'.esc_attr( $menu ).'">'.esc_html( $menu_name->name ).'</li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        <?php endif; ?>
        <!-- Filter Menu End -->

        <div class="woolentor-filter-grid" id="filterable-grid-<?php echo $id; ?>" data-active-item="*">
            <div class="woolentor_filter_grid__item__sizer"></div>

            <?php
                $i = 0;
                $big_class = '';

                while( $products->have_posts() ): $products->the_post(); 
                    $product = wc_get_product( get_the_ID() );

                    $i++;

                    // Add to cart Button Classes
                    $btn_class = 'woolentor_cart_action product_type_' . $product->get_type();

                    $btn_class .= $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

                    $btn_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';

                    $image_size = 'woocommerce_thumbnail';

                    // Add to Cart Button
                    $cart_btn = $button_icon = '';
                    if( !empty( $settings['button_icon']['value'] ) ){
                        $btn_class .= ' woolentor-button-icon-'.$settings['button_icon_align'];
                        $button_icon = woolentor_render_icon( $settings, 'button_icon', 'buttonicon' );
                    }
                    $button_text  = ! empty( $settings['add_to_cart_text'] ) ? $settings['add_to_cart_text'] : '';

                    if($settings['button_icon_align'] == 'right'){
                        $cart_btn = $button_text.$button_icon;
                    }else{  
                        $cart_btn = $button_icon.$button_text;
                    }


                    $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
                    if ( has_post_thumbnail() ){
                        array_unshift( $gallery_images_ids, $product->get_image_id() );
                    }

                    if( $i == 4 || $i == 6 || $i == 15 || $i == 23 || $i == 33 ){
                        $big_class = 'grid__item--size-a';
                    }else{
                        $big_class = '';
                    }

                    $item_class = array_merge( array( 'big_item' => $big_class ), $this->category_slug_list() );

            ?>
                <div class="woolentor_filter_grid__item <?php echo implode( ' ', $item_class ); ?>">
                    <div class="image__item">
                        <a href="<?php the_permalink(); ?>"><?php echo $product->get_image( $image_size ); ?></a>
                        <?php
                            if( class_exists('WooCommerce') ){ 
                                woolentor_custom_product_badge(); 
                                Woolentor_Control_Sale_Badge( $settings, get_the_ID() );
                            }
                        ?>
                    </div>
                    <div class="woolentor__meta">
                        <div class="meta__content">
                            <h3 class="meta__title"><?php echo wp_trim_words( get_the_title(), $settings['title_length'] ); ?></h3>
                            <span class="meta__brand"><?php woolentor_get_product_category_list(); ?></span>
                        </div>
                        <span class="meta__price"><?php echo $product->get_price_html(); ?></span>
                    </div>
                    <?php do_action( 'woolentor_addon_after_price' ); ?>
                    <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor-pro' ); ?></a>
                </div>
                
            <?php endwhile; ?>

        </div>
        <?php

    }

    public function category_slug_list( $id = null, $taxonomy = 'product_cat' ) {
        $terms = get_the_terms( $id, $taxonomy );
        $slug_list = [];
        if ( is_wp_error( $terms ) )
            return $terms;

        if ( empty( $terms ) )
            return false;

        foreach ( $terms as $term ) {
            $slug_list[] = $term->slug;
        }
        return $slug_list;
        
    }

}