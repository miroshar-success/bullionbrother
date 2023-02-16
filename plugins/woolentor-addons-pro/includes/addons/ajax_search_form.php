<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Ajax_Search_Form_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-ajax-search-form';
    }
    
    public function get_title() {
        return __( 'WL: Ajax Product Search Form', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-site-search';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-ajax-search',
        ];
    }

    public function get_script_depends(){
        return [
            'woolentor-ajax-search',
        ];
    }

    public function get_keywords(){
        return ['search','search form','product search','live search','ajax search','ajax search form','product ajax search'];
    }

    protected function register_controls() {

        // Content Start
        $this->start_controls_section(
            'woolentor-ajax-search-form',
            [
                'label' => esc_html__( 'Search Form', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'limit',
                [
                    'label' => __( 'Show Number of Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                    'default' => 10,
                ]
            );

            $this->add_control(
                'placeholder_text',
                [
                    'label'     => __( 'Placeholder Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'Search Products', 'woolentor-pro' ),
                    'label_block'=>true,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'show_category',
                [
                    'label' => esc_html__( 'Show Category Dropdown', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'all_category_text',
                [
                    'label'     => __( 'All Category Text', 'woolentor-pro' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => __( 'All Categories', 'woolentor-pro' ),
                    'label_block' => true,
                    'condition'=>[
                        'show_category'=>'yes'
                    ]
                ]
            );

        $this->end_controls_section();
        // Content end

        $this->start_controls_section(
            'search_form_area',
            [
                'label' => __( 'Form Area', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'search_form_area_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_widget_psa_field_area',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'search_form_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa_field_area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'search_form_input',
            [
                'label' => __( 'Input Box', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'search_form_input_text_color',
                [
                    'label'     => __( 'Text Color', 'woolentor-pro' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa input[type="search"]'   => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'search_form_input_placeholder_color',
                [
                    'label'     => __( 'Placeholder Color', 'woolentor-pro' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa input[type*="search"]::-webkit-input-placeholder' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woolentor_widget_psa input[type*="search"]::-moz-placeholder'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woolentor_widget_psa input[type*="search"]:-ms-input-placeholder'  => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'search_form_input_typography',
                    'selector' => '{{WRAPPER}} .woolentor_widget_psa input[type="search"]',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'search_form_input_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor_widget_psa input[type="search"]',
                ]
            );

            $this->add_responsive_control(
                'search_form_input_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'search_form_input_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa input[type="search"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'search_form_input_height',
                [
                    'label' => __( 'Height', 'woolentor-pro' ),
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
                        'size' => 40,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa input[type="search"]' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'search_form_input_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_widget_psa input[type="search"]',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'search_form_input_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa input[type="search"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Category Dropdown Style tab section
        $this->start_controls_section(
            'search_form_category_dropdown',
            [
                'label' => __( 'Category Dropdown', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_category'=>'yes'
                ]
            ]
        );

            $this->add_control(
                'category_dropdown_text_color',
                [
                    'label'     => __( 'Color', 'woolentor-pro' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa .woolentor_widget_psa_category select' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'category_dropdown_typography',
                    'selector' => '{{WRAPPER}} .woolentor_widget_psa .woolentor_widget_psa_category select',
                ]
            );

            $this->add_control(
                'category_dropdown_right_border_color',
                [
                    'label'     => __( 'Right Border Color', 'woolentor-pro' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_widget_psa_category::after' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Submit Button
        $this->start_controls_section(
            'search_form_style_submit_button',
            [
                'label' => __( 'Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            // Button Tabs Start
            $this->start_controls_tabs('search_form_style_submit_tabs');

                // Start Normal Submit button tab
                $this->start_controls_tab(
                    'search_form_style_submit_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'search_form_submitbutton_text_color',
                        [
                            'label'     => __( 'Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_widget_psa button'   => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'search_form_submitbutton_typography',
                            'selector' => '{{WRAPPER}} .woolentor_widget_psa button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_form_submitbutton_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woolentor_widget_psa button',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_form_submitbutton_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_widget_psa button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'search_form_submitbutton_height',
                        [
                            'label' => __( 'Height', 'woolentor-pro' ),
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
                                'size' => 40,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_widget_psa button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' =>'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_form_submitbutton_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_widget_psa button',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_form_submitbutton_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_widget_psa button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Normal submit Button tab end

                // Start Hover Submit button tab
                $this->start_controls_tab(
                    'search_form_style_submit_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'search_form_submitbutton_hover_text_color',
                        [
                            'label'     => __( 'Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_widget_psa button:hover'   => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'search_form_submitbutton_hover_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woolentor_widget_psa button:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'search_form_submitbutton_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_widget_psa button:hover',
                            'separator' =>'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'search_form_submitbutton_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_widget_psa button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Hover Submit Button tab End

            $this->end_controls_tabs(); // Button Tabs End

        $this->end_controls_section();

        // Search results Style section
        $this->start_controls_section(
            'search_form_style_results',
            [
                'label' => esc_html__( 'Search Results', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'area_heading',
                [
                    'label' => esc_html__( 'Area', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'area_height',
                [
                    'label' => esc_html__( 'Height', 'woolentor-pro' ),
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
                        '{{WRAPPER}} .woolentor_psa_inner_wrapper' => 'max-height: {{SIZE}}{{UNIT}};',
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
                        '{{WRAPPER}} .woolentor_psa_inner_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'area_border',
                    'label' => esc_html__( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_psa_inner_wrapper',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'area_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_psa_inner_wrapper',
                ]
            );

            $this->add_control(
                'result_item_heading',
                [
                    'label' => esc_html__( 'Result item', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'space_between',
                [
                    'label' => esc_html__( 'Space between', 'woolentor-pro' ),
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
                        '{{WRAPPER}} .woolentor_single_psa a' => 'padding-bottom: {{SIZE}}{{UNIT}};margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_border_color',
                [
                    'label' => esc_html__( 'Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_single_psa a' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'item_title_color',
                [
                    'label' => esc_html__( 'Title Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_psa_content h3' => 'color: {{VALUE}}',
                    ],
                    'separator'=>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'item_title_typography',
                    'label' => esc_html__( 'Title Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_psa_content h3',
                ]
            );

            $this->add_responsive_control(
                'item_title_margin',
                [
                    'label' => esc_html__( 'Title Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_psa_content h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'item_price_color',
                [
                    'label' => esc_html__( 'Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_psa_content .woolentor_psa_price' => 'color: {{VALUE}}',
                    ],
                    'separator'=>'before',
                ]
            );

            $this->add_control(
                'item_old_price_color',
                [
                    'label' => esc_html__( 'Old Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} #woolentor_psa_results_wrapper del' => 'color: {{VALUE}}',
                        '{{WRAPPER}} #woolentor_psa_results_wrapper del .amount' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'item_price_typography',
                    'label' => esc_html__( 'Price Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} #woolentor_psa_results_wrapper .woolentor_psa_content .woolentor_psa_price .amount',
                ]
            );

            $this->add_responsive_control(
                'item_image_border_radius',
                [
                    'label' => esc_html__( 'Image Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} #woolentor_psa_results_wrapper .woolentor_psa_image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator'=>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_image_border',
                    'label' => esc_html__( 'Image Border', 'woolentor-pro' ),
                    'fields_options'=>[
                        'border'=>[
                            'label' => esc_html__( 'Image Border Type', 'woolentor-pro' )
                        ]
                    ],
                    'selector' => '{{WRAPPER}} #woolentor_psa_results_wrapper .woolentor_psa_image img',
                ]
            );

        $this->end_controls_section();


    }

    protected function render() {

        $settings  = $this->get_settings_for_display();
        $shortcode_atts = [
            'limit'         => $settings[ 'limit' ],
            'placeholder'   => $settings[ 'placeholder_text' ],
            'show_category' => ( 'yes' === $settings['show_category'] ),
        ];
        if( 'yes' === $settings['show_category'] ){
            $shortcode_atts['all_category_text'] = $settings['all_category_text'];
        }
        echo woolentor_do_shortcode( 'woolentorsearch', $shortcode_atts );

    }

}