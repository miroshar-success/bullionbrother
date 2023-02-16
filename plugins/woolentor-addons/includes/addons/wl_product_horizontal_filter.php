<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Product_Horizontal_Filter_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-horizontal-filter';
    }

    public function get_title() {
        return __( 'WL: Product Horizontal Filter', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-filter';
    }

    public function get_categories() {
        return ['woolentor-addons'];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-select2','woolentor-widgets'];
    }

    public function get_script_depends() {
        return ['select2-min'];
    }

    public function get_keywords(){
        return ['woolentor','shop','filter','product filter','horizontal'];
    }

    protected function register_controls() {

        $filter_by = [
            'price_by'     => esc_html__( 'Price', 'woolentor' ),
            'sort_by'      => esc_html__( 'Sort By', 'woolentor' ),
            'order_by'     => esc_html__( 'Order By', 'woolentor' )
        ];
        $prices = function_exists('woolentor_minmax_price_limit') ? woolentor_minmax_price_limit() : array('min' => 10,'max' => 20);

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Filter', 'woolentor' ),
            ]
        );
            
            $repeater = new Repeater();

            $repeater->add_control(
                'wl_filter_title', 
                [
                    'label' => esc_html__( 'Filter Title', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'wl_filter_placeholder', 
                [
                    'label' => esc_html__( 'Filter Placeholder', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'wl_filter_type',
                [
                    'label'     => esc_html__( 'Filter Type', 'woolentor' ),
                    'type'      => Controls_Manager::SELECT2,
                    'options'   => $filter_by + woolentor_get_taxonomies(),
                    'label_block' => true,
                ]
            );

            $repeater->add_responsive_control(
                'wl_filter_width',
                [
                    'label' => esc_html__( 'Max Width', 'woolentor' ),
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
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-single-item{{CURRENT_ITEM}} .select2-container .select2-search--inline .select2-search__field' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'wl_filter_list',
                [
                    'label' => esc_html__( 'Filter List', 'woolentor' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'wl_filter_title' => esc_html__( 'Sort By', 'woolentor' ),
                            'wl_filter_placeholder' => esc_html__( 'Sort By', 'woolentor' ),
                            'wl_filter_type' => 'sort_by',
                        ],
                        [
                            'wl_filter_title' => esc_html__( 'Order By', 'woolentor' ),
                            'wl_filter_placeholder' => esc_html__( 'Order By', 'woolentor' ),
                            'wl_filter_type' => 'order_by',
                        ],
                        [
                            'wl_filter_title' => esc_html__( 'Pricing', 'woolentor' ),
                            'wl_filter_placeholder' => esc_html__( 'Pricing', 'woolentor' ),
                            'wl_filter_type' => 'price_by',
                        ],
                    ],
                    'title_field' => '{{{ wl_filter_title }}}',
                ]
            );

            $price_range = new Repeater();

            $price_range->add_control(
                'min_price', 
                [
                    'label'         => esc_html__( 'Min Price', 'woolentor' ),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => floor( $prices['min'] ),
                ]
            );

            $price_range->add_control(
                'max_price', 
                [
                    'label'         => esc_html__( 'Max Price', 'woolentor' ),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => ceil( $prices['max'] ),
                ]
            );

            $price_range->add_control(
                'price_seprator', 
                [
                    'label' => esc_html__( 'Filter Placeholder', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default'=> esc_html__( 'to', 'woolentor' ),
                ]
            );

            $this->add_control(
                'price_range_list',
                [
                    'label'         => esc_html__( 'Price Range', 'woolentor' ),
                    'type'          => Controls_Manager::REPEATER,
                    'fields'        => $price_range->get_controls(),
                    'separator'     => 'before',
                    'default'       => [
                        [
                            'min_price' => floor( $prices['min'] ),
                            'max_price' => ceil( $prices['max'] ),
                            'price_seprator' => esc_html__( 'to', 'woolentor' ),
                        ],
                    ],
                    'title_field'   => esc_html__( 'Price: {{{ min_price }}} {{{ price_seprator }}} {{{ max_price }}}', 'woolentor' ),
                ]
            );

        $this->end_controls_section();

        // Additional Option
        $this->start_controls_section(
            'section_additional_option',
            [
                'label' => esc_html__( 'Additional Options', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'wl_filter_area_title',
                [
                    'label' => esc_html__( 'Title', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'default'=>esc_html__( 'Filter', 'woolentor' ),
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'show_search_form',
                [
                    'label' => esc_html__( 'Search Form', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default'=>'yes',
                ]
            );

            $this->add_control(
                'redirect_form_url',
                [
                    'label'     => esc_html__( 'Redirect Custom URL', 'woolentor' ),
                    'type'      => Controls_Manager::TEXT,
                    'placeholder' => get_home_url( null, 'custom-search-page' ),
                    'label_block'=>true,
                    'condition' => [
                        'show_search_form' => 'yes'
                    ],
                ]
            );

            $this->add_control(
                'show_filter_label',
                [
                    'label' => esc_html__( 'Show Filter Label', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'show_filter_btton',
                [
                    'label' => esc_html__( 'Show Filter Button', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default'=>'yes',
                ]
            );

            $this->add_control(
                'form_field_placeholder',
                [
                    'label' => esc_html__( 'Search Form Placeholder', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'default'=>esc_html__( 'Search Products...', 'woolentor' ),
                    'separator'=>'before',
                    'label_block' => true,
                    'condition'=>[
                        'show_search_form'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'form_submit_button_icon',
                [
                    'label' => esc_html__( 'Search Button Icon', 'woolentor' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fa fa-search',
                        'library' => 'solid',
                    ],
                    'fa4compatibility' => 'formsubmitbuttonicon',
                    'condition'=>[
                        'show_search_form'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'filter_button_icon',
                [
                    'label' => esc_html__( 'Filter Button Icon', 'woolentor' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-filter',
                        'library' => 'solid',
                    ],
                    'fa4compatibility' => 'filterbuttonicon',
                    'condition'=>[
                        'show_filter_btton'=>'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Area Style Section
        $this->start_controls_section(
            'wlproduct_filter_area_style',
            [
                'label' => esc_html__( 'Area', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'area_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-heaer-box-area',
                ]
            );

            $this->add_responsive_control(
                'area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-heaer-box-area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'area_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-heaer-box-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'area_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-heaer-box-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'area_background',
                    'label' => esc_html__( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-heaer-box-area',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'area_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-heaer-box-area',
                ]
            );

        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section(
            'wlproduct_filter_title_style',
            [
                'label' => esc_html__( 'Title', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'wl_filter_area_title!'=>''
                ]
            ]
        );
            
            $this->add_control(
                'title_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} h2.wl_hoz_filter_title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => esc_html__( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} h2.wl_hoz_filter_title',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'title_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} h2.wl_hoz_filter_title',
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} h2.wl_hoz_filter_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} h2.wl_hoz_filter_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Filter Label Style Section
        $this->start_controls_section(
            'wlproduct_filter_label_style',
            [
                'label' => esc_html__( 'Label', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_filter_label'=>'yes'
                ]
            ]
        );
            
            $this->add_control(
                'filter_label_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-field-wrap .woolentor-filter-single-item label' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'filter_label_typography',
                    'label' => esc_html__( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-field-wrap .woolentor-filter-single-item label',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'filter_label_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-field-wrap .woolentor-filter-single-item label',
                ]
            );

            $this->add_responsive_control(
                'filter_label_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-field-wrap .woolentor-filter-single-item label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'filter_label_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-field-wrap .woolentor-filter-single-item label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Search Form Style Section
        $this->start_controls_section(
            'wlproduct_filter_search_form_style',
            [
                'label' => esc_html__( 'Search Form', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_search_form'=>'yes'
                ]
            ]
        );

            $this->add_control(
                'form_inputbox',
                [
                    'label' => esc_html__( 'Input Box', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'inputbox_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-box' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'inputbox_background',
                    'label' => esc_html__( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-box',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'inputbox_typography',
                    'label' => esc_html__( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-box',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'inputbox_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-box',
                ]
            );

            $this->add_responsive_control(
                'inputbox_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'inputbox_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'form_submit_button',
                [
                    'label' => esc_html__( 'Submit Button', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->start_controls_tabs('submit_button_style_tabs');

                // Button Normal Style
                $this->start_controls_tab(
                    'submit_button_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'submit_button_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-inner-btn' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'submit_button_icon_size',
                        [
                            'label' => esc_html__( 'Font Size', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-inner-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Button Hover Style
                $this->start_controls_tab(
                    'submit_button_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'woolentor' ),
                    ]
                );
                    $this->add_control(
                        'submit_button_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-header-top-area .woolentor-search-input-box .input-inner-btn:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Filter Menu
        $this->start_controls_section(
            'wlproduct_filter_menu_style',
            [
                'label' => esc_html__( 'Filter Menu', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'menu_label_placeholder_color',
                [
                    'label' => esc_html__( 'Placeholder Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-filter-single-item .select2-container--default .select2-selection--single .select2-selection__placeholder' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-filter-single-item .select2-container .select2-search--inline .select2-search__field::-webkit-input-placeholder' => 'color: {{VALUE}};opacity:1;',
                        '{{WRAPPER}} .woolentor-filter-single-item .select2-container .select2-search--inline .select2-search__field::-moz-placeholder' => 'color: {{VALUE}};opacity:1;',
                        '{{WRAPPER}} .woolentor-filter-single-item .select2-container .select2-search--inline .select2-search__field:-ms-input-placeholder' => 'color: {{VALUE}};opacity:1;',
                    ],
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'menu_label_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .select2-container .select2-selection--single,{{WRAPPER}} .woolentor-horizontal-filter-wrap .select2-container .select2-selection--multiple',
                ]
            );

            $this->add_responsive_control(
                'menu_label_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .select2-container .select2-selection--single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .select2-container .select2-selection--multiple' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'menu_label_color',
                [
                    'label' => esc_html__( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-select-drop .select2-results__option' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woolentor-select-drop .select2-results__option::before' => 'border-color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'menu_label_hover_color',
                [
                    'label' => esc_html__( 'Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-select-drop .select2-container--default .select2-results__option--highlighted[aria-selected="true"]' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'menu_label_hover_background',
                    'label' => esc_html__( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'fields_options'=>[
                        'background'=>[
                            'label' => esc_html__( 'Hover Background', 'woolentor' ),
                        ],
                    ],
                    'exclude'=>['image'],
                    'selector' => '{{WRAPPER}} .woolentor-select-drop .select2-container--default .select2-results__option--highlighted[aria-selected="true"]',
                ]
            );

            $this->add_responsive_control(
                'menu_alignment',
                [
                    'label' => esc_html__( 'Alignment', 'woolentor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Left', 'woolentor' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'woolentor' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Right', 'woolentor' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-filter-field-wrap' => 'justify-content: {{VALUE}};',
                    ],
                    'default' => 'center',
                ]
            );

        $this->end_controls_section();

        // Filter Button Style Section
        $this->start_controls_section(
            'wlproduct_filter_button_style',
            [
                'label' => esc_html__( 'Filter Button', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_filter_btton'=>'yes'
                ]
            ]
        );
            $this->start_controls_tabs('filter_button_style_tabs');

                // Button Normal Style
                $this->start_controls_tab(
                    'filter_button_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'filter_button_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-search-filter-custom .filter-icon' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'filter_button_icon_size',
                        [
                            'label' => esc_html__( 'Font Size', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-search-filter-custom .filter-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                            'name' => 'filter_button_background',
                            'label' => esc_html__( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'exclude'=>['image'],
                            'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-search-filter-custom .filter-icon',
                        ]
                    );

                $this->end_controls_tab();

                // Button Hover Style
                $this->start_controls_tab(
                    'filter_button_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'woolentor' ),
                    ]
                );
                    $this->add_control(
                        'filter_button_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-search-filter-custom .filter-icon:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                            'name' => 'filter_button_hover_background',
                            'label' => esc_html__( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'exclude'=>['image'],
                            'selector' => '{{WRAPPER}} .woolentor-horizontal-filter-wrap .woolentor-search-filter-custom .filter-icon:hover',
                        ]
                    );


                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();


    }


    protected function render( $instance = [] ) {
        $settings  = $this->get_settings_for_display();
        $id              = $this->get_id();
        $currency_symbol = get_woocommerce_currency_symbol();

        $filter_list = $settings['wl_filter_list'];

        
        global $wp;
        if ( '' == get_option('permalink_structure' ) ) {
            $current_url = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
        } else {
            $current_url = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
        }

        if( !empty( $settings['form_submit_button_icon']['value'] ) ){
            $submit_btton_icon = woolentor_render_icon( $settings, 'form_submit_button_icon', 'formsubmitbuttonicon' );
        }else{
            $submit_btton_icon = '<i class="fa fa-search"></i>';
        }

        if( !empty( $settings['filter_button_icon']['value'] ) ){
            $filter_btton_icon = woolentor_render_icon( $settings, 'filter_button_icon', 'filterbuttonicon' );
        }else{
            $filter_btton_icon = '<i class="fas fa-filter"></i>';
        }

        ?>
            <div class="woolentor-horizontal-filter-wrap">
                <!-- Heaer Box Area Start -->
                <div class="woolentor-heaer-box-area">

                    <div class="woolentor-filter-header-top-area">
                        <div class="woolentor-header-left-side">
                            <?php
                                if( !empty( $settings['wl_filter_area_title'] ) ){
                                    echo '<h2 class="wl_hoz_filter_title">'.$settings['wl_filter_area_title'].'</h2>';
                                }
                            ?>
                        </div>
                        <div class="woolentor-header-right-side">
                            <?php 
                            if( $settings['show_search_form'] === 'yes' ):

                                if ( isset( $_GET['q'] ) || isset( $_GET['s'] ) ) {
                                    $s = !empty( $_GET['s'] ) ? $_GET['s'] : '';
                                    $q = !empty( $_GET['q'] ) ? $_GET['q'] : '';
                                    $search_value = !empty( $q ) ? $q : $s;
                                }else{
                                    $search_value = '';
                                }

                                if( !empty( $settings['redirect_form_url'] ) ){
                                    $form_action = $settings['redirect_form_url'];
                                }else{
                                    $form_action = $current_url;
                                }

                            ?>
                                <form class="woolentor-header-search-form" role="search" method="get" action="<?php echo esc_url( $form_action ); ?>">
                                    <div class="woolentor-search-input-box">
                                        <input class="input-box" type="search" placeholder="<?php echo esc_attr_x( $settings['form_field_placeholder'], 'placeholder', 'woolentor' ); ?>" value="<?php echo esc_attr( $search_value ); ?>" name="q" title="<?php echo esc_attr_x( 'Search for:', 'label', 'woolentor' ); ?>" />
                                        <button class="input-inner-btn" type="submit"><?php echo $submit_btton_icon; ?></button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if( $settings['show_filter_btton'] == 'yes' ): ?>
                                <div class="woolentor-search-filter-custom">
                                    <a href="#" id="filter-toggle-<?php echo $id; ?>" class="filter-icon"><?php echo $filter_btton_icon; ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="filter-item-<?php echo $id; ?>" class="filter-item">
                        <div class="woolentor-filter-field-area">
                            <div class="woolentor-filter-field-wrap">
                                <?php 
                                    if( isset( $filter_list ) ){
                                        foreach ( $filter_list as $filter_key => $filter_item ) {

                                            $filter_label = '';
                                            if( 'yes' === $settings['show_filter_label'] ){
                                                $filter_label = '<label for="woolentor-field-for-'.$filter_item['_id'].'">'.$filter_item['wl_filter_title'].'</label>';
                                            }

                                            if( 'sort_by' === $filter_item['wl_filter_type'] ){
                                                $wlsort = ( isset( $_GET['wlsort'] ) && !empty( $_GET['wlsort'] ) ) ? $_GET['wlsort'] : '';
                                            ?>
                                                <div class="woolentor-filter-single-item woolentor-states-input-auto elementor-repeater-item-<?php echo $filter_item['_id']; ?>">
                                                    <?php echo $filter_label; ?>
                                                    <select name="wl_sort" id="woolentor-field-for-<?php echo $filter_item['_id']; ?>" class="woolentor-onchange-single-item woolentor-single-select-<?php echo $id; ?>" data-minimum-results-for-search="Infinity" data-placeholder="<?php echo $filter_item['wl_filter_placeholder']; ?>">
                                                        <?php
                                                            if( !empty( $filter_item['wl_filter_placeholder'] ) ){echo '<option></option>';}
                                                        ?>
                                                        <option value="&wlsort=ASC" <?php selected( 'ASC', $wlsort, true ); ?> ><?php echo esc_html__( 'ASC', 'woolentor' ); ?></option>
                                                        <option value="&wlsort=DESC" <?php selected( 'DESC', $wlsort, true ); ?> ><?php echo esc_html__( 'DESC', 'woolentor' ); ?></option>
                                                    </select>
                                                </div>
                                            <?php

                                            }elseif( 'order_by' === $filter_item['wl_filter_type'] ){
                                                $wlorder_by = ( isset( $_GET['wlorder_by'] ) && !empty( $_GET['wlorder_by'] ) ) ? $_GET['wlorder_by'] : '';
                                                ?>
                                                <div class="woolentor-filter-single-item woolentor-states-input-auto elementor-repeater-item-<?php echo $filter_item['_id']; ?>">
                                                    <?php echo $filter_label; ?>
                                                    <select name="wl_order_by_sort" id="woolentor-field-for-<?php echo $filter_item['_id']; ?>" class="woolentor-onchange-single-item woolentor-single-select-<?php echo $id; ?>" data-minimum-results-for-search="Infinity" data-placeholder="<?php echo $filter_item['wl_filter_placeholder']; ?>">
                                                        <?php
                                                            if( !empty( $filter_item['wl_filter_placeholder'] ) ){echo '<option></option>';}
                                                        
                                                            foreach ( woolentor_order_by_opts() as $key => $opt_data ) {
                                                                echo '<option value="&wlorder_by='.esc_attr( $key ).'" '.selected( $key, $wlorder_by, false ).'>'.esc_html__( $opt_data, 'woolentor' ).'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php

                                            }elseif( 'price_by' === $filter_item['wl_filter_type'] ){

                                                $woocommerce_currency_pos = get_option( 'woocommerce_currency_pos' );
                                                $currency_pos_left = false;
                                                $currency_pos_space = false;
                                                if( $woocommerce_currency_pos == 'left' || $woocommerce_currency_pos == 'left_space' ){
                                                    $currency_pos_left = true;
                                                }

                                                if( strstr( $woocommerce_currency_pos, 'space' ) ){
                                                    $currency_pos_space = true;
                                                }
                        
                                                if( $currency_pos_space == true && $currency_pos_left == true){
                                                    // left space
                                                    $final_currency_symbol = $currency_symbol.' ';
                                                }else if( $currency_pos_space == true && $currency_pos_left == false ){
                                                    // right space
                                                    $final_currency_symbol = ' '.$currency_symbol;
                                                }else{
                                                    $final_currency_symbol = $currency_symbol;
                                                }

                                                $cmin_price = ( isset( $_GET['min_price'] ) && !empty( $_GET['min_price'] ) ) ? $_GET['min_price'] : '';
                                                $cmax_price = ( isset( $_GET['max_price'] ) && !empty( $_GET['max_price'] ) ) ? $_GET['max_price'] : '';

                                                $current_price = [ $cmin_price, $cmax_price ];

                                                $psl_placeholder = '';
                                                if( empty( $cmin_price ) ){
                                                    $psl_placeholder = 'data-placeholder="'.esc_attr( !empty( $filter_item['wl_filter_placeholder'] ) ? $filter_item['wl_filter_placeholder'] : '' ).'"';
                                                }

                                                $price_range_list = $settings['price_range_list'];
                                                
                                                if( isset( $price_range_list ) ):
                                                    ?>
                                                    <div class="woolentor-filter-single-item woolentor-states-input-auto elementor-repeater-item-<?php echo $filter_item['_id']; ?>">
                                                        <?php echo $filter_label; ?>
                                                        <select id="woolentor-field-for-<?php echo $filter_item['_id']; ?>" class="woolentor-onchange-single-item woolentor-price-filter woolentor-single-select-<?php echo $id; ?>" data-minimum-results-for-search="Infinity" <?php echo $psl_placeholder; ?> >
                                                            <?php
                                                                if( !empty( $filter_item['wl_filter_placeholder'] ) && empty( $cmin_price ) ){echo '<option></option>';}

                                                                foreach ( $price_range_list as $key => $price_range ) {

                                                                    $individual = [$price_range['min_price'], $price_range['max_price'] ];
                                                                    $diff = array_diff( $individual, $current_price );

                                                                    $pselected = 0;
                                                                    if( count( $diff ) == 0 ) {
                                                                        $pselected = 1;
                                                                    }

                                                                    if( $currency_pos_left ){
                                                                        $generate_price = sprintf('%s%s %s %s%s',$final_currency_symbol,$price_range['min_price'], $price_range['price_seprator'],$final_currency_symbol,$price_range['max_price'] );
                                                                    }else{
                                                                        $generate_price = sprintf('%s%s %s %s%s',$price_range['min_price'], $final_currency_symbol, $price_range['price_seprator'],$price_range['max_price'], $final_currency_symbol );
                                                                    }

                                                                    echo sprintf("<option value='%s' data-min_price='&min_price=%s' data-max_price='&max_price=%s' %s>%s</option>", $key, $price_range['min_price'], $price_range['max_price'], selected( $pselected, 1, false ), $generate_price );
                                                                }

                                                            ?>
                                                        </select>
                                                    </div>
                                                    <?php
                                                endif;

                                            }else{
                                                $terms = get_terms( $filter_item['wl_filter_type'] );
                                                if ( !empty( $terms ) && !is_wp_error( $terms )){

                                                    $taxonomy_data = get_taxonomy( $filter_item['wl_filter_type'] );

                                                    $filter_name = $filter_item['wl_filter_type'];
                                                    $str = substr( $filter_item['wl_filter_type'], 0, 3 );
                                                    if( 'pa_' === $str ){
                                                        $filter_name = 'filter_' . wc_attribute_taxonomy_slug( $filter_item['wl_filter_type'] );
                                                    }

                                                    if( $filter_name === 'product_cat' || $filter_name === 'product_tag' ){
                                                        $filter_name = 'woolentor_'.$filter_name;
                                                    }

                                                    $selected_taxonomies = ( isset( $_GET[$filter_name] ) && !empty( $_GET[$filter_name] ) ) ? explode( ',', $_GET[$filter_name] ) : array();

                                                    $sl_placeholder = '';
                                                    if( count( $selected_taxonomies ) != 1 ){
                                                        $sl_placeholder = 'data-placeholder="'.esc_attr( !empty( $filter_item['wl_filter_placeholder'] ) ? $filter_item['wl_filter_placeholder'] : $taxonomy_data->labels->singular_name ).'"';
                                                    }

                                                    echo '<div class="woolentor-filter-single-item woolentor-states-input-auto elementor-repeater-item-'.$filter_item['_id'].'">';
                                                    echo $filter_label;
                                                    echo '<select name="wltaxonomies['.$filter_item['wl_filter_type'].'][]" class="woolentor-onchange-multiple-item woolentor-multiple-select-'.$id.'" '.$sl_placeholder.' multiple="multiple">';

                                                        foreach ( $terms as $term ){
                                                            $link = $this->generate_term_link( $filter_item['wl_filter_type'], $term, null );

                                                            $selected = 0;
                                                            if( in_array( $term->slug, $selected_taxonomies ) ) {
                                                                $selected = 1;
                                                            }

                                                            echo sprintf('<option value="%1$s" %3$s>%2$s</option>', $link['link'], $term->name, selected( $selected, 1, false ) );
                                                        }

                                                    echo '</select></div>';

                                                }

                                            }

                                        }
                                    }
                                ?>
                            </div>
                            <div class="woolentor-select-drop woolentor-single-select-drop-<?php echo $id; ?>"></div>
                            <div class="woolentor-select-drop woolentor-multiple-select-drop-<?php echo $id; ?>"></div>
                        </div>
                    </div>
                </div>
                <!-- Heaer Box Area End -->
            </div>

            <script type="text/javascript">
                ;jQuery(document).ready(function($) {
                    'use strict';

                    var id = '<?php echo $id; ?>',
                        isEditorMode = '<?php echo woolentor_is_preview_mode(); ?>';

                    // Localize Text
                    var selectTxt = '<?php echo esc_html__( 'select', 'woolentor' ); ?>',
                        ofTxt = '<?php echo esc_html__( 'of', 'woolentor' ); ?>';

                    // Filter Toggle
                    $('#filter-toggle-'+id).on('click', function(e){
                        e.preventDefault()
                        $('#filter-item-'+id).slideToggle()
                    })


                    $('.woolentor-single-select-'+id).select2({
                        dropdownParent: $('.woolentor-single-select-drop-'+id),
                    });
                    $('.woolentor-multiple-select-'+id).select2({
                        // closeOnSelect : false,
                        allowHtml: true,
                        allowClear: true,
                        dropdownParent: $('.woolentor-multiple-select-drop-'+id),
                    });

                    $('.woolentor-filter-single-item select').on('change', function (e) {
                        var output = $(this).siblings('span.select2').find('ul');
                        var total = e.currentTarget.length;
                        var count = output.find('li').length - 0;
                        if(count >= 3) {
                            output.html("<li>"+count+" "+ofTxt+" "+total+" "+selectTxt+"</li>")
                        } 
                    });

                    // Filter product
                    var current_url = '<?php echo $current_url.'?wlfilter=1'; ?>';
                    $('.woolentor-filter-single-item select.woolentor-onchange-single-item').on('change', function () {
                        var sort_key = $(this).val();
                        if ( sort_key && ( isEditorMode != true ) ) {
                            window.location = current_url + sort_key;
                        }
                        return false;
                    });

                    // Price Filter
                    $('.woolentor-filter-single-item select.woolentor-price-filter').on( 'change', function(){
                        var selected = $(this).find('option:selected'),
                            min_price = selected.data('min_price'),
                            max_price = selected.data('max_price'),
                            location  = min_price + max_price;

                        if ( location && ( isEditorMode != true ) ) {
                            window.location = current_url + location;
                        }

                    });

                    // Texanomies Filter
                    var previouslySelected = [];
                    $('.woolentor-filter-single-item select.woolentor-onchange-multiple-item').on('change', function () {
                        // Get newly selected elements
                        var currentlySelected = $(this).val();
                        if( currentlySelected != null ){

                             if( currentlySelected.length == 0 && ( isEditorMode != true ) ){
                                window.location = current_url;
                            }else{
                                var newSelections = currentlySelected.filter(function (element) {
                                    return previouslySelected.indexOf(element) == -1;
                                });
                                previouslySelected = currentlySelected;
                                if (newSelections.length) {
                                    // If there are multiple new selections, we'll take the last in the list
                                    var lastSelected = newSelections.reverse()[0];
                                }
                                if ( lastSelected && ( isEditorMode != true ) ) {
                                    window.location = lastSelected;
                                }
                            }
                            
                        }else{
                            if(isEditorMode != true){
                                window.location = current_url;
                            }
                        }
                        return false;
                    });


                });
            </script>
        <?php
    }

    protected function generate_term_link( $filter_type, $term, $current_url ) {

        $filter_name = $filter_type;
        $str = substr( $filter_type, 0, 3 );
        if( 'pa_' === $str ){
            $filter_name = 'filter_' . wc_attribute_taxonomy_slug( $filter_type );
        }

        if( $filter_name === 'product_cat' || $filter_name === 'product_tag' ){
            $filter_name = 'woolentor_'.$filter_name;
        }

        $current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array();
        $option_is_set  = in_array( $term->slug, $current_filter, true );

        // Generate choosen Class
        if( in_array( $term->slug, $current_filter ) ){
            $active_class = 'wlchosen';
        }else{
            $active_class = '';
        }

        // Term Link
        $current_filter = array_map( 'sanitize_title', $current_filter );
        if ( ! in_array( $term->slug, $current_filter, true ) ) {
            $current_filter[] = $term->slug;
        }
        $link = remove_query_arg( $filter_name, $current_url );

        foreach ( $current_filter as $key => $value ) {
            if ( $option_is_set && $value === $term->slug ) {
                unset( $current_filter[ $key ] );
            }
        }

        if ( ! empty( $current_filter ) ) {
            asort( $current_filter );
            $link = add_query_arg( 'wlfilter', '1', $link );
            $link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

            $link = str_replace( '%2C', ',', $link );
        }
        return [
            'link'  => $link,
            'class' => $active_class,
        ];

    }


}