<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Product_Expanding_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-expanding-grid';
    }

    public function get_title() {
        return __( 'WL: Product Expanding Grid', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-product-expanding-grid'];
    }

    public function get_script_depends() {
        return ['wlexpanding-scripts'];
    }

    public function get_keywords(){
        return ['woolentor','product','grid','Custom product','WooCommerce','expanding'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_grid_content',
            [
                'label' => __( 'Product Grid', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'product_layout',
                [
                    'label'   => esc_html__('Product Layout', 'woolentor-pro'),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'expanding_grid',
                    'options' => [
                        'content'        => esc_html__('Current Theme Style', 'woolentor-pro'),
                        'expanding_grid' => esc_html__('Grid Style', 'woolentor-pro'),
                    ],
                    'label_block'=>true,
                ]
            );

            $this->add_control(
                'filterable',
                [
                    'label' => __( 'Filterable', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Product Query Settings
        $this->start_controls_section(
            'woolentor-products',
            [
                'label' => esc_html__( 'Query Settings', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'product_type',
                [
                    'label' => esc_html__( 'Product Type', 'woolentor-pro' ),
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
                'product_id',
                [
                    'label' => esc_html__( 'Select Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_post_name( 'product' ),
                    'condition' => [
                        'product_type' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
                'product_ids_manually',
                [
                    'label' => esc_html__( 'Product IDs', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition' => [
                        'product_type' => 'show_byid_manually',
                    ]
                ]
            );

            $this->add_control(
              'product_limit',
                [
                    'label'   => esc_html__( 'Product Limit', 'woolentor-pro' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 3,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'categories',
                [
                    'label' => esc_html__( 'Product Categories', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_taxonomy_list(),
                    'condition' => [
                        'product_type!' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
                'cat_operator',
                [
                    'label'     => esc_html__('Category Operator', 'woolentor-pro'),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'IN',
                    'options'   => [
                        'AND'    => esc_html__('AND', 'woolentor-pro'),
                        'IN'     => esc_html__('IN', 'woolentor-pro'),
                        'NOT IN' => esc_html__('NOT IN', 'woolentor-pro'),
                    ],
                    'condition' => [
                        'categories!' => ''
                    ],
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
                        'menu_order'    => esc_html__('Menu Order', 'woolentor-pro'),
                        'comment_count' => esc_html__('Comment count','woolentor-pro'),
                        'rand'          => esc_html__('Random','woolentor-pro'),
                    ],
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
                ]
            );

            $this->add_control(
                'paginate',
                [
                    'label' => esc_html__( 'Pagination', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'allow_order',
                [
                    'label' => esc_html__( 'Allow Order', 'woolentor-pro' ),
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
                    'label' => esc_html__( 'Show Result Count', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'paginate' => 'yes',
                    ],
                ]
            );

        $this->end_controls_section();

        // Additional Options
        $this->start_controls_section(
            'section_additional_option',
            [
                'label' => esc_html__( 'Additional Options', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'content_add_to_cart_settings',
                [
                    'label' => esc_html__( 'Cart Button Settings', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'product_layout!'=>'content'
                    ],
                ]
            );

            $this->add_control(
                'add_to_cart_text',
                [
                    'label' => esc_html__( 'Add to Cart Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Add To Cart', 'woolentor-pro' ),
                    'placeholder' => esc_html__( 'Type your cart button text', 'woolentor-pro' ),
                    'label_block' => true,
                    'condition'=>[
                        'product_layout!'=>'content'
                    ]
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
                        'value'  => 'fas fa-plus',
                        'library'=> 'solid',
                    ],
                    'condition' => [
                        'product_layout!'=>'content'
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
                        'product_layout!'=>'content'
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_responsive_control(
                'icon_specing',
                [
                    'label' => esc_html__( 'Icon Spacing', 'woolentor-pro' ),
                    'type'  => Controls_Manager::SLIDER,
                    'condition' => [
                        'button_icon[value]!' => '',
                        'product_layout!'=>'content'
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .details__addtocart a.woolentor-button-icon-right i'  => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .details__addtocart a.woolentor-button-icon-left i'   => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Item Style tab section
        $this->start_controls_section(
            'grid_style_section',
            [
                'label' => esc_html__( 'Grid Item Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .product__bg',
                    'exclude'=>['image'],
                ]
            );

            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .grid__product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_title_heading',
                [
                    'label' => __( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce h2.product__title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woocommerce h2.product__title',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce h2.product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_category_heading',
                [
                    'label' => __( 'Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'category_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product__subtitle' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'category_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .product__subtitle',
                ]
            );

            $this->add_responsive_control(
                'category_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .product__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Item Details Style tab section
        $this->start_controls_section(
            'item_details_style_section',
            [
                'label' => esc_html__( 'Item Details Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_details_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .details__bg--up',
                    'exclude'=>['image'],
                    'fields_options'=>[
                        'background'=>[
                            'label'=> __( 'Upper Background', 'woolentor-pro' ),
                        ]
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'item_details_down_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .details__bg--down',
                    'exclude'=>['image'],
                    'fields_options'=>[
                        'background'=>[
                            'label'=> __( 'Down Background', 'woolentor-pro' ),
                        ]
                    ]
                ]
            );

            $this->add_responsive_control(
                'item_detail_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_detail_title_heading',
                [
                    'label' => __( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'detail_title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce h2.details__title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'detail_title_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woocommerce h2.details__title',
                ]
            );

            $this->add_responsive_control(
                'detail_title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce h2.details__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_detail_category_heading',
                [
                    'label' => __( 'Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'detail_category_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .details__subtitle' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'detail_category_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .details__subtitle',
                ]
            );

            $this->add_responsive_control(
                'detail_category_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .details__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_detail_price_heading',
                [
                    'label' => __( 'Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'detail_price_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .details__price' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'detail_price_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .details__price',
                ]
            );

            $this->add_responsive_control(
                'detail_price_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .details__price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_detail_content_heading',
                [
                    'label' => __( 'Content', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'detail_content_color',
                [
                    'label' => __( 'Content', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .details__description' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'detail_content_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .details__description',
                ]
            );

            $this->add_responsive_control(
                'detail_content_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .details__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_detail_addtocart_heading',
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
                    'selector' => '{{WRAPPER}} .details__addtocart a',
                ]
            );

            $this->add_responsive_control(
                'cart_btn_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .details__addtocart a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .details__addtocart a i' => 'font-size: {{SIZE}}{{UNIT}};',
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
                                '{{WRAPPER}} .details__addtocart a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_btn_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .details__addtocart a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'cart_btn_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .details__addtocart a',
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
                                '{{WRAPPER}} .details__addtocart a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_btn_hover_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .details__addtocart a:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'cart_btn_hover_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .details__addtocart a:hover',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_control(
                'item_detail_zoom_heading',
                [
                    'label' => __( 'Zoom Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'zoom_btn_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .details__magnifier' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs( 'zoom_button_style_tabs' );
                
                $this->start_controls_tab(
                    'zoom_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'zoom_btn_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .details__magnifier' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'zoom_btn_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .details__magnifier',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'zoom_btn_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .details__magnifier',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();
                
                $this->start_controls_tab(
                    'zoom_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'zoom_btn_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .details__magnifier:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'zoom_btn_hover_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .details__magnifier:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'zoom_btn_hover_background',
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .details__magnifier:hover',
                            'exclude'=>['image'],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();

        $type = 'recent_products';
        $type = $this->parse_product_type( $settings['product_type'] );

        $filterable = ( 'yes' === $settings['filterable'] ? rest_sanitize_boolean( $settings['filterable'] ) : false );

        $shortcode = new \WooLentor_WC_Shortcode_Products( $settings, $type, $filterable );
        $content = $shortcode->get_content( $settings['product_layout'] );
        $not_found_content = woolentor_pro_products_not_found_content();

        if ( true === $filterable ) {
            $wrap_class = 'wl-filterable-products-wrap';
            $content_class = 'wl-filterable-products-content';
            $wrap_attributes = 'data-wl-widget-name="wl-product-expanding-grid"';
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

    protected function parse_product_type( $type ) {
        switch ( $type ) {

            case 'recent':
                $product_type = 'recent_products';
                break;

            case 'sale':
                $product_type = 'sale_products';
                break;

            case 'best_selling':
                $product_type = 'best_selling_products';
                break;

            case 'top_rated':
                $product_type = 'top_rated_products';
                break;

            case 'featured':
                $product_type = 'featured';
                break;

            default:
                $product_type = 'products';
                break;
        }
        return $product_type;
    }


}