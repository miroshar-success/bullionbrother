<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Category_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-category-grid';
    }

    public function get_title() {
        return __( 'WL: Category Grid', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-categories';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['slick','woolentor-category-grid','woolentor-widgets'];
    }

    public function get_script_depends() {
        return ['slick','woolentor-widgets-scripts'];
    }

    public function get_keywords(){
        return ['category','product category','category grid','categorise'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Category Grid', 'woolentor' ),
            ]
        );

            $this->add_control(
                'layout',
                [
                    'label' => esc_html__( 'Select Style', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1' => esc_html__('Style One','woolentor'),
                        '2' => esc_html__('Style Two','woolentor'),
                        '3' => esc_html__('Style Three','woolentor'),
                        '4' => esc_html__('Style Four','woolentor'),
                        '5' => esc_html__('Style Five','woolentor'),
                    ],
                    'label_block' => true,
                    'separator'=>'after',
                ]
            );

            $this->add_control(
                'category_display_type',
                [
                    'label' => esc_html__( 'Category Display Type', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'all_cat',
                    'options' => [
                        'single_cat' => esc_html__('Single Category','woolentor'),
                        'multiple_cat'=> esc_html__('Multiple Categories','woolentor'),
                        'all_cat'=> esc_html__('All Categories','woolentor'),
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'product_categories',
                [
                    'label' => esc_html__( 'Select categories', 'woolentor' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_taxonomy_list(),
                    'condition' => [
                        'category_display_type' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'multi_categories',
                [
                    'label' => esc_html__( 'Select categories', 'woolentor' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => woolentor_taxonomy_list(),
                    'condition' => [
                        'category_display_type' => 'multiple_cat',
                    ]
                ]
            );

            $this->add_control(
                'catorder',
                [
                    'label' => esc_html__( 'Order', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'ASC',
                    'options' => [
                        'ASC'   => esc_html__('Ascending','woolentor'),
                        'DESC'  => esc_html__('Descending','woolentor'),
                    ],
                    'condition' => [
                        'category_display_type!' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'catorderby',
                [
                    'label' => esc_html__( 'Orderby', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'name',
                    'options' => [
                        'ID'    => esc_html__('ID','woolentor'),
                        'name'  => esc_html__('Name','woolentor'),
                        'slug'  => esc_html__('Slug','woolentor'),
                        'parent' => esc_html__('Parent','woolentor'),
                        'menu_order' => esc_html__('Menu Order','woolentor'),
                    ],
                    'condition' => [
                        'category_display_type!' => 'single_cat',
                    ]
                ]
            );

            $this->add_control(
                'limitcount',
                [
                    'label' => esc_html__( 'Show items', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 5,
                    'condition' => [
                        'category_display_type' => 'all_cat',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'thumbnailsize',
                    'default' => 'full',
                    'separator' => 'none',
                ]
            );

            $this->add_control(
                'show_count',
                [
                    'label' => __( 'Show Count', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'woolentor' ),
                    'label_off' => __( 'No', 'woolentor' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'layout'=>['1','4']
                    ]
                ]
            );

            $this->add_control(
                'slider_on',
                [
                    'label' => __( 'Slider On', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'=>'before',
                ]
            );

        $this->end_controls_section();

        // Column Option
        $this->start_controls_section(
            'section_column_option',
            [
                'label' => esc_html__( 'Columns', 'woolentor' ),
                'condition'=>[
                    'slider_on!'=>'yes',
                ]
            ]
        );
            
            $this->add_responsive_control(
                'category_grid_column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1' => esc_html__( 'One', 'woolentor' ),
                        '2' => esc_html__( 'Two', 'woolentor' ),
                        '3' => esc_html__( 'Three', 'woolentor' ),
                        '4' => esc_html__( 'Four', 'woolentor' ),
                        '5' => esc_html__( 'Five', 'woolentor' ),
                        '6' => esc_html__( 'Six', 'woolentor' ),
                        '7' => esc_html__( 'Seven', 'woolentor' ),
                        '8' => esc_html__( 'Eight', 'woolentor' ),
                        '9' => esc_html__( 'Nine', 'woolentor' ),
                        '10'=> esc_html__( 'Ten', 'woolentor' ),
                    ],
                    'label_block' => true,
                    'prefix_class' => 'wl-columns%s-',
                ]
            );

            $this->add_control(
                'no_gutters',
                [
                    'label' => esc_html__( 'No Gutters', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor' ),
                    'label_off' => esc_html__( 'No', 'woolentor' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_responsive_control(
                'item_space',
                [
                    'label' => esc_html__( 'Space', 'woolentor' ),
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
                    'default' => [
                        'unit' => 'px',
                        'size' => 15,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'padding: 0  {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Slider Option
        $this->start_controls_section(
            'section_slider_option',
            [
                'label' => esc_html__( 'Slider Option', 'woolentor' ),
                'condition'=>[
                    'slider_on'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 3
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no'
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
                    'default' => 'no'
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

        $this->end_controls_section();

        // Area Style Section
        $this->start_controls_section(
            'category_area_style_section',
            [
                'label' => esc_html__( 'Area', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'area_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} [class*="ht-category-wrap"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'area_box_shadow',
                    'label' => __( 'Box Shadow', 'woolentor' ),
                    'selector' => '{{WRAPPER}} [class*="ht-category-wrap"]',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'area_box_background',
                    'label' => __( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} [class*="ht-category-wrap"]',
                ]
            );

        $this->end_controls_section();

        // Image Style Section
        $this->start_controls_section(
            'category_image_style_section',
            [
                'label' => esc_html__( 'Image', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'image_box_color',
                [
                    'label' => __( 'Box Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap .ht-category-image a.ht-category-border::before' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .ht-category-wrap-2:hover::before' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .ht-category-wrap .ht-category-image a.ht-category-border-2::before' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap .ht-category-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} [class*="ht-category-wrap"] [class*="ht-category-image-"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .ht-category-wrap .ht-category-image,{{WRAPPER}} [class*="ht-category-wrap"] [class*="ht-category-image-"]',
                ]
            );
            
            $this->add_responsive_control(
                'image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap .ht-category-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .ht-category-wrap .ht-category-image a.ht-category-border::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} [class*="ht-category-wrap"] [class*="ht-category-image-"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section(
            'category_title_style',
            [
                'label' => esc_html__( 'Title', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap .ht-category-content h3 a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} [class*="ht-category-wrap"] [class*="ht-category-content-"] h3 a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'title_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap .ht-category-content h3 a:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                        '{{WRAPPER}} [class*="ht-category-wrap"] [class*="ht-category-content-"] h3 a:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'title_after_color',
                [
                    'label' => __( 'After Border Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap-2 .ht-category-content-2 h3::before' => 'background-color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'layout'=>['2'],
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .ht-category-wrap .ht-category-content h3 a,{{WRAPPER}} [class*="ht-category-wrap"] [class*="ht-category-content-"] h3 a',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap .ht-category-content h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} [class*="ht-category-wrap"] [class*="ht-category-content-"] h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Count Style Section
        $this->start_controls_section(
            'category_count_style',
            [
                'label' => esc_html__( 'Count', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'count_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap [class*="ht-category-content"] span' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'count_before_color',
                [
                    'label' => __( 'Before Border Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-category-wrap [class*="ht-category-content"] span::before' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'count_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .ht-category-wrap [class*="ht-category-content"] span',
                ]
            );

        $this->end_controls_section();

        // Slider Button style
        $this->start_controls_section(
            'products-slider-controller-style',
            [
                'label' => esc_html__( 'Slider Controller Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->start_controls_tabs('product_sliderbtn_style_tabs');

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

                    $this->add_responsive_control(
                        'nvigation_position',
                        [
                            'label' => __( 'Position', 'woolentor' ),
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
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
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

                        $this->add_responsive_control(
                            'dots_position',
                            [
                                'label' => __( 'Position', 'woolentor' ),
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
                                    '{{WRAPPER}} .product-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}};',
                                ],
                            ]
                        );

                        $this->add_control(
                            'dots_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor' ),
                                'type' => Controls_Manager::COLOR,
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

    }


    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $display_type = $settings['category_display_type'];
        $order = ! empty( $settings['catorder'] ) ? $settings['catorder'] : '';
        $orderby = ! empty( $settings['catorderby'] ) ? $settings['catorderby'] : 'name';

        $column         = $settings['category_grid_column'];
        $layout         = $settings['layout'];

        $collumval = 'wl-col-1';
        if( $column !='' ){
            $collumval = 'wl-col-'.$column;
        }

        $catargs = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => true,
        );

        if( $display_type == 'single_cat' ){
            $product_categories = $settings['product_categories'];
            $product_cats = str_replace( ' ', '', $product_categories );
            $catargs['slug'] = $product_cats;
        }
        elseif( $display_type == 'multiple_cat' ){
            $product_categories = $settings['multi_categories'];
            $product_cats = str_replace(' ', '', $product_categories);
            $catargs['slug'] = $product_cats;
        }else{
            $catargs['slug'] = '';
        }
        $prod_categories = get_terms( 'product_cat', $catargs );

        if( $display_type == 'all_cat' ){
            $limitcount = $settings['limitcount'];
        }else{
            $limitcount = -1;
        }

        $size = $settings['thumbnailsize_size'];
        $image_size = Null;
        if( $size === 'custom' ){
            $image_size = [
                $settings['thumbnailsize_custom_dimension']['width'],
                $settings['thumbnailsize_custom_dimension']['height']
            ];
        }else{
            $image_size = $size;
        }


        // Slider Options
        $slider_main_div_style = '';
        if( $settings['slider_on'] === 'yes' ){

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
            $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );
            $slider_main_div_style = "style='display:none'";
        }else{
            $slider_settings = '';
        }

        $counter = $bgc = 0;
        $thumbnails = '';

        $placeholder_image = sprintf( '<img src="%s" alt="%s" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting category image', 'woolentor' ) );

        echo '<div class="wl-row '.( $settings['no_gutters'] === 'yes' ? 'wlno-gutters' : '' ).' '.( $settings['slider_on'] === 'yes' ? 'product-slider' : '' ).' " data-settings='.wp_json_encode( $slider_settings ).' '.$slider_main_div_style.'>';
            foreach ( $prod_categories as $key => $prod_cat ):
                $counter++;
                $bgc++;

                $cat_thumb_id = get_term_meta( $prod_cat->term_id, 'thumbnail_id', true );

                $cat_thumb = wp_get_attachment_image( $cat_thumb_id, $image_size );

                $term_link = get_term_link( $prod_cat, 'product_cat' );

                $thumbnails = $cat_thumb;

                ?>
                <div class="<?php echo esc_attr( $collumval ); ?>">

                    <?php if( '1' === $layout ): ?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ): ?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a class="ht-category-border" href="<?php echo esc_url( $term_link ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <div class="ht-category-content">
                                <h3><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html__( $prod_cat->name, 'woolentor' ); ?></a></h3>
                                <?php 
                                    if( $settings['show_count'] === 'yes' ){
                                        echo '<span>'.esc_html__( $prod_cat->count, 'woolentor' ).'</span>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php elseif( '2' === $layout ):?>
                        <div class="ht-category-wrap-2">
                            <div class="ht-category-content-2">
                                <h3><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html__( $prod_cat->name, 'woolentor' ); ?></a></h3>
                            </div>
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image-2">
                                <a href="<?php echo esc_url( $term_link ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>

                    <?php elseif( '3' === $layout ):?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ): ?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a class="ht-category-border-2" href="<?php echo esc_url( $term_link ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php else: ?>
                                <div class="ht-category-image ht-category-image-zoom">
                                    <a class="ht-category-border-2" href="<?php echo esc_url( $term_link ); ?>">
                                        <?php echo $placeholder_image; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="ht-category-content-3 ht-category-content-3-bg<?php echo $bgc; ?>">
                                <h3><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html__( $prod_cat->name, 'woolentor' ); ?></a></h3>
                            </div>
                        </div>

                    <?php elseif( '4' === $layout ):?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a href="<?php echo esc_url( $term_link ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="ht-category-content-4">
                                <h3>
                                    <a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html__( $prod_cat->name, 'woolentor' ); ?></a>
                                    <?php 
                                        if( $settings['show_count'] === 'yes' ){
                                            echo '<span>('.esc_html__( $prod_cat->count, 'woolentor' ).')</span>';
                                        }
                                    ?>
                                </h3>
                            </div>
                        </div>
                    <?php else:?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image-3 ht-category-image-zoom">
                                <a href="<?php echo esc_url( $term_link ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="ht-category-content-5">
                                <h3><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html__( $prod_cat->name, 'woolentor' ); ?></a></h3>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
                <?php
                if( $bgc == 4 ){ $bgc = 0; }
                if( $counter == $limitcount ) { break; }
            endforeach;
        echo '</div>';
    }

}