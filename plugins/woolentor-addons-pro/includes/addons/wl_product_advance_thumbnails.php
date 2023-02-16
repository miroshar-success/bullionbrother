<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Product_Advance_Thumbnails_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-thumbnails-image';
    }

    public function get_title() {
        return __( 'WL: Advance Product Image', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-product-images';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'iconicons',
            'lightgallery-style',
            'woolentor-widgets-pro',
        ];
    }
    
    public function get_script_depends() {
        return [
            'slick',
            'jquery-zoom',
            'tippy',
            'lightgallery',
            'woolentor-widgets-scripts-pro',
        ];
    }

    public function get_keywords(){
        return ['image','product image','thumbnail','custom thumbnail layout'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_thumbnails_content',
            array(
                'label' => __( 'Product Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );
            $this->add_control(
                'layout_style',
                [
                    'label' => __( 'Layout', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'tab',
                    'options' => [
                        'tab'       => __( 'Tab', 'woolentor-pro' ),
                        'tabslider' => __( 'Tab With Slider', 'woolentor-pro' ),
                        'gallery'   => __( 'Gallery', 'woolentor-pro' ),
                        'slider'    => __( 'Slider', 'woolentor-pro' ),
                        'single'    => __( 'Single Thumbnails', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'tab_thumbnails_position',
                [
                    'label'   => __( 'Thumbnails Position', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'top' => [
                            'title' => __( 'Top', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => 'bottom',
                    'toggle'      => false,
                    'condition'=>[
                        'layout_style' => ['tab','tabslider']
                    ],
                ]
            );

            $this->add_control(
                'hide_sale_badge',
                [
                    'label'     => __( 'Sale Badge Hide', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} span.onsale' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'hide_custom_badge',
                [
                    'label'     => __( 'Custom Badge Hide', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-product-gallery__image .ht-product-label.ht-product-label-left' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'enable_image_zoom',
                [
                    'label' => __( 'Zoom', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'layout_style' => 'tabslider',
                    ]
                ]
            );

            $this->add_control(
                'enable_light_gallery',
                [
                    'label' => __( 'Light Gallery', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'layout_style' => 'tabslider',
                    ]
                ]
            );

            $this->add_control(
                'enable_wishlist',
                [
                    'label' => __( 'Wishlist', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition' => [
                        'layout_style' => 'tabslider',
                    ]
                ]
            );

        $this->end_controls_section();

        // Tab Slider setting
        $this->start_controls_section(
            'woolentor-tabslider-opt',
            [
                'label' => __( 'Slider Option', 'woolentor-pro' ),
                'condition' => [
                    'layout_style' => 'tabslider',
                ]
            ]
        );
            
            $this->add_control(
                'tabslitems',
                [
                    'label' => __( 'Slider Thumbnails Items', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 20,
                    'step' => 1,
                    'default' => 4
                ]
            );

            $this->add_control(
                'tabsldots',
                [
                    'label' => __( 'Slider Pagination', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'tabslarrows',
                [
                    'label' => __( 'Slider Image Navigation', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'tabslthumbarrows',
                [
                    'label' => __( 'Slider Thumbnails Navigation', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'tabslthumbmod',
                [
                    'label' => __( 'Slider Thumbnails Center Mode', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

        $this->end_controls_section();

        // Product slider setting
        $this->start_controls_section(
            'woolentor-thumbnails-slider',
            [
                'label' => __( 'Slider Option', 'woolentor-pro' ),
                'condition' => [
                    'layout_style' => 'slider',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label' => __( 'Slider Items', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 3
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => __( 'Slider Arrow', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => __( 'Slider dots', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'woolentor-pro'),
                    'label_on' => __('Yes', 'woolentor-pro'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => __( 'Slider autoplay', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'woolentor-pro'),
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
                    'label' => __('Autoplay animation speed', 'woolentor-pro'),
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
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
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
                    'label' => __( 'Tablet', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
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
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
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
                    'label' => __('Tablet Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to the tablet.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
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
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
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
                    'label' => __('Mobile Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to mobile.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                ]
            );

        $this->end_controls_section(); // Slider Option end
        
        // Product Main Image Style
        $this->start_controls_section(
            'product_image_style_section',
            [
                'label' => __( 'Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'layout_style' => 'tab',
                ],
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_image_border',
                    'label' => __( 'Product image border', 'woolentor-pro' ),
                    'selector' => '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .woocommerce-product-gallery__image,.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .woocommerce-product-gallery__image img',
                ]
            );

            $this->add_responsive_control(
                'product_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .woocommerce-product-gallery__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .woocommerce-product-gallery__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .woocommerce-product-gallery__image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Main Image Style
        $this->start_controls_section(
            'product_tabslider_image_style_section',
            [
                'label' => __( 'Product Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'layout_style' => 'tabslider',
                ],
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_tabslider_image_border',
                    'label' => __( 'Product image border', 'woolentor-pro' ),
                    'selector' => '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .woocommerce-product-gallery__image img',
                ]
            );

            $this->add_responsive_control(
                'product_tabslider_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .woocommerce-product-gallery__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .woocommerce-product-gallery__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_tabslider_thumbnais_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails ul.woolentor-thumbanis-image li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .wl-single-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wl-thumbnails-slider .wl-single-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .woocommerce-product-gallery__image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'tabslider_gallery_elalrge_color',
                [
                    'label' => __( 'Gallery Enlarge Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider #tabslide-enlarge-image i' => 'color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'layout_style' => 'tabslider',
                    ],
                ]
            );

            $this->add_control(
                'tabslider_wishlist_color',
                [
                    'label' => __( 'Wishlist Color', 'woolentor-pro' ),
                    'type'  => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider #tabslide-wishlist a.wishsuite-button, {{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider #tabslide-wishlist a i' => 'color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'layout_style' => 'tabslider',
                    ],
                ]
            );

        $this->end_controls_section();

        //product image dots
        $this->start_controls_section(
            'product_tabslider_image_style_dots',
            [
                'label' => __( 'Product Image Slider Dots', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'layout_style' => 'tabslider',
                ],
            ]
        );

            $this->start_controls_tabs('tabslider_dots_style_tabs');

                $this->start_controls_tab(
                    'tabslider_dots_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'tabslider_dots_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-dots li button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabslider_dots_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-dots li button',
                        ]
                    );

                    $this->add_control(
                        'tabslider_dots_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-dots li button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Active Style
                $this->start_controls_tab(
                    'tabslider_dots_style_hover_tab',
                    [
                        'label' => __( 'Active', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'tabslider_dots_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-dots li.slick-active button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabslider_dots_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-dots li.slick-active button',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        //product image arrows
        $this->start_controls_section(
            'tabslider_image_slider_style_arrow',
            [
                'label' => __( 'Product Image Slider Arrow', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'layout_style' => 'tabslider',
                ],
            ]
        );
            $this->start_controls_tabs('tabslider_slider_arrow_style_tabs');

                $this->start_controls_tab(
                    'tabslider_slider_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                
                    $this->add_control(
                        'tabslider_slider_arrow_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .woolentor-learg-img .slick-arrow' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tabslider_slider_arrow_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-arrow' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabslider_slider_arrow_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-arrow',
                        ]
                    );

                    $this->add_control(
                        'tabslider_slider_arrow_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style
                $this->start_controls_tab(
                    'tabslider_slider_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'tabslider_slider_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-arrow:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tabslider_slider_arrow_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-arrow:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabslider_slider_arrow_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-thumbnails .slick-arrow:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Product Badge Style
        $this->start_controls_section(
            'product_badge_style_section',
            [
                'label' => __( 'Product Badge', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_badge_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '.woocommerce {{WRAPPER}} span.onsale,{{WRAPPER}} .woocommerce-product-gallery__image .ht-product-label.ht-product-label-left',
                ]
            );

            $this->add_control(
                'product_badge_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} span.onsale' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woocommerce-product-gallery__image .ht-product-label.ht-product-label-left' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_badge_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} span.onsale' => 'background-color: {{VALUE}} !important;',
                        '{{WRAPPER}} .woocommerce-product-gallery__image .ht-product-label.ht-product-label-left' => 'background-color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_badge_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}} .woocommerce-product-gallery__image .ht-product-label.ht-product-label-left' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_badge_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '{{WRAPPER}} .woocommerce-product-gallery__image .ht-product-label.ht-product-label-left' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();
        
        // Product Thumbnails Image Style
        $this->start_controls_section(
            'product_image_thumbnails_style_section',
            [
                'label' => __( 'Thumbnails Image', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_thumbnais_image_border',
                    'label' => __( 'Product image border', 'woolentor-pro' ),
                    'selector' => '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails ul.woolentor-thumbanis-image li img, .woocommerce {{WRAPPER}} .wlpro-product-thumbnails .wl-single-gallery img, .woocommerce {{WRAPPER}} .wl-thumbnails-slider .wl-single-slider img,.woocommerce {{WRAPPER}} .woocommerce-product-gallery__image img,.woocommerce {{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-list .woolentor-thumb-single img',
                ]
            );

            $this->add_responsive_control(
                'product_thumbnais_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails ul.woolentor-thumbanis-image li img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .wl-single-gallery img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wl-thumbnails-slider .wl-single-slider img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .woocommerce-product-gallery__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .woolentor-thumbnails .slick-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_product_thumbnais_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails ul.woolentor-thumbanis-image li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails .wl-single-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .wl-thumbnails-slider .wl-single-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        '.woocommerce {{WRAPPER}} .woocommerce-product-gallery__image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->start_controls_tabs(
                'tabslider_thumbnail_active_style_tabs',
                [
                    'condition' => [
                        'layout_style' => 'tabslider',
                    ]
                ]
            );

                $this->start_controls_tab(
                    'tabslider_thumbnail_style_active_tab',
                    [
                        'label' => __( 'Active', 'woolentor-pro' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabslider_thumbnail_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '.woocommerce {{WRAPPER}} .wlpro-product-thumbnails.thumbnails-layout-tabslider .slick-list .woolentor-thumb-single.slick-current img',
                        ]
                    );
                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Tab With Slider Arrow style
        $this->start_controls_section(
            'tabwithslider_arrow_style',
            [
                'label' => __( 'Thumbnails Slider Arrow', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_style' => 'tabslider',
                ]
            ]
        );

            $this->add_control(
                'tabwithslider_navigation_style',
                [
                    'label' => __( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''                     => __( 'Style One', 'woolentor-pro' ),
                        'wl-thumb-nav-style-1' => __( 'Style Two', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->start_controls_tabs('tabslider_arrow_style_tabs');

                $this->start_controls_tab(
                    'tabslider_arrow_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'tabslider_arrow_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-thumbnails .slick-arrow' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tabslider_arrow_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-thumbnails .slick-arrow' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabslider_arrow_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-thumbnails .slick-arrow',
                        ]
                    );

                    $this->add_control(
                        'tabslider_arrow_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-thumbnails .slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Style
                $this->start_controls_tab(
                    'tabslider_arrow_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'tabslider_arrow_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-thumbnails .slick-arrow:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'tabslider_arrow_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-thumbnails .slick-arrow:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabslider_arrow_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-thumbnails .slick-arrow:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Slider Button style
        $this->start_controls_section(
            'products-slider-controller-style',
            [
                'label' => __( 'Slider Controller Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout_style' => 'slider',
                ]
            ]
        );

            $this->start_controls_tabs('product_sliderbtn_style_tabs');

                // Slider Button style Normal
                $this->start_controls_tab(
                    'product_sliderbtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'button_style_heading',
                        [
                            'label' => __( 'Navigation Arrow', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'navigation_arroe_boxshadow',
                            'label' => __( 'Box Shadow', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow',
                        ]
                    );

                    $this->add_control(
                        'button_style_dots_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_control(
                            'dots_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .wl-thumbnails-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border',
                                'label' => __( 'Border', 'woolentor-pro' ),
                                'selector' => '{{WRAPPER}} .wl-thumbnails-slider .slick-dots li button',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius',
                            [
                                'label' => __( 'Border Radius', 'woolentor-pro' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .wl-thumbnails-slider .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Normal button style end

                // Button style Hover
                $this->start_controls_tab(
                    'product_sliderbtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'button_style_arrow_heading',
                        [
                            'label' => __( 'Navigation', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_bg_color',
                        [
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'navigation_arrow_hover_boxshadow',
                            'label' => __( 'Box Shadow', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .wl-thumbnails-slider .slick-arrow:hover',
                        ]
                    );

                    $this->add_control(
                        'button_style_dotshov_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_control(
                            'dots_hover_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .wl-thumbnails-slider .slick-dots li button:hover' => 'background-color: {{VALUE}} !important;',
                                    '{{WRAPPER}} .wl-thumbnails-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border_hover',
                                'label' => __( 'Border', 'woolentor-pro' ),
                                'selector' => '{{WRAPPER}} .wl-thumbnails-slider .slick-dots li button:hover',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius_hover',
                            [
                                'label' => __( 'Border Radius', 'woolentor-pro' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .wl-thumbnails-slider .slick-dots li button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );
                $this->end_controls_tab();// Hover button style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

    }

    protected function render() {
        $settings  = $this->get_settings_for_display();

        $this->add_render_attribute( 'wl_product_thumbnails_attr', 'class', 'wlpro-product-thumbnails images thumbnails-tab-position-'.$settings['tab_thumbnails_position'] );
        $this->add_render_attribute( 'wl_product_thumbnails_attr', 'class', 'thumbnails-layout-'.$settings['layout_style'] );

        //tab slider option
        $tab_slider_settings = [
            'thumbarrows' => ('yes' === $settings['tabslthumbarrows']) ? 1 : 0,
            'imagearrows' => ('yes' === $settings['tabslarrows']) ? 1 : 0,
            'centermod' => ('yes' === $settings['tabslthumbmod']) ? 1 : 0,
            'dots' => ('yes' === $settings['tabsldots']) ? 1 : 0,
        ];

        // Slider Options
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

        if( woolentor_is_preview_mode() ){
            $product = wc_get_product( woolentor_get_last_product_id() );
            $post = '';
        } else{
            global $product, $post;
        }
        if ( empty( $product ) ) { return; }

        $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
        if ( $product->get_image_id() ){
            $gallery_images_ids = array( 'wlthumbnails_id' => $product->get_image_id() ) + $gallery_images_ids;
        }

        // Placeholder image set
        if( empty( $gallery_images_ids ) ){
            $gallery_images_ids = array( 'wlthumbnails_id' => get_option( 'woocommerce_placeholder_image', 0 ) );
        }

        ?>

        <?php if( woolentor_is_preview_mode() ){ echo '<div class="product">'; } ?>
        <div <?php echo $this->get_render_attribute_string( 'wl_product_thumbnails_attr' ); ?>>
            <div class="wl-thumbnails-image-area">
                <?php if( $settings['layout_style'] == 'tab' ): ?>

                    <?php if( $settings['tab_thumbnails_position'] == 'left' || $settings['tab_thumbnails_position'] == 'top' ): ?>
                        <ul class="woolentor-thumbanis-image">
                            <?php
                                foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
                                    echo '<li data-wlimage="'.wp_get_attachment_image_url( $gallery_attachment_id, 'woocommerce_single' ).'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</li>';
                                }
                            ?>
                        </ul>
                    <?php endif; ?>
                    <div class="woocommerce-product-gallery__image">
                        <?php
                            if( woolentor_is_preview_mode() ){
                                if ( $product->is_on_sale() ) { 
                                    echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
                                }
                            }else{
                                woolentor_show_product_sale_flash();
                            }

                            if(function_exists('woolentor_custom_product_badge')){
                                woolentor_custom_product_badge();
                            }
                            echo wp_get_attachment_image( reset( $gallery_images_ids ), 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) );
                        ?>
                    </div>
                    <?php if( $settings['tab_thumbnails_position'] == 'right' || $settings['tab_thumbnails_position'] == 'bottom' ): ?>
                        <ul class="woolentor-thumbanis-image">
                            <?php
                                foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                    echo '<li data-wlimage="'.wp_get_attachment_image_url( $gallery_attachment_id, 'woocommerce_single' ).'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</li>';
                                }
                            ?>
                        </ul>
                    <?php endif; ?>
                <?php 
                elseif( $settings['layout_style'] == 'tabslider' ):
                    if( woolentor_is_preview_mode()){
                        if ( $product->is_on_sale() ) { 
                            echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
                        }
                    }else{
                        woolentor_show_product_sale_flash();
                    }
                    if( $settings['tab_thumbnails_position'] == 'left' || $settings['tab_thumbnails_position'] == 'top' ){
                        echo '<div class="woolentor-thumbnails '.$settings['tabwithslider_navigation_style'].'" style="display:none;">';
                            foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                echo '<div class="woolentor-thumb-single">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</div>';
                            }
                        echo '</div>';
                    }
                    woolentor_show_product_sale_flash();
                    ?>
                        <div class="single-product-media-action">
                            <?php if('yes' === $settings['enable_wishlist']): ?>
                                <?php if( true === woolentor_has_wishlist_plugin() ): ?>
                                    <?php if( woolentor_is_preview_mode()): ?>
                                        <button id="tabslide-wishlist" class="single-product-media-action-btn" data-tippy="Wishlist" data-tippy-placement="left" data-tippy-inertia="true" data-tippy-animation="shift-away" data-tippy-delay="50" data-tippy-arrow="true" data-tippy-theme="sharpborder">
                                            <a href="#"><i class="sli sli-heart"></i></a>
                                        </button>
                                    <?php else: ?>
                                        <button id="tabslide-wishlist" class="single-product-media-action-btn" data-tippy="" data-tippy-placement="left" data-tippy-inertia="true" data-tippy-animation="shift-away" data-tippy-placement="left" data-tippy-inertia="true" data-tippy-animation="shift-away" data-tippy-delay="50" data-tippy-arrow="true" data-tippy-theme="sharpborder">
                                            <?php echo woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'no') ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if('yes' === $settings['enable_light_gallery']): ?>
                                <button id="tabslide-enlarge-image" class="single-product-media-action-btn btn-zoom-popup" data-tippy-placement="left" data-tippy-inertia="true" data-tippy-animation="shift-away" data-tippy-delay="50" data-tippy-arrow="true" data-tippy-theme="sharpborder"><i class="sli sli-frame"></i></button>
                            <?php endif; ?>
                        </div>
                    <?php
                    echo '<div class="woolentor-learg-img woocommerce-product-gallery__image" style="display:none;">';
                        $i = 0;
                        foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                            $i++;
                            if( $i == 1 ){
                                echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) ).'</div>';
                            }else{
                                echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' ).'</div>';
                            }
                        }
                    echo '</div>';
                    if( $settings['tab_thumbnails_position'] == 'right' || $settings['tab_thumbnails_position'] == 'bottom' ){
                        echo '<div class="woolentor-thumbnails '.$settings['tabwithslider_navigation_style'].'" style="display:none;">';
                            foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                echo '<div class="woolentor-thumb-single">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</div>';
                            }
                        echo '</div>';
                    }
                ?>

                <?php elseif( $settings['layout_style'] == 'gallery' ): ?>
                    <div class="woocommerce-product-gallery__image wl-single-gallery">
                        <?php
                            if( woolentor_is_preview_mode() ){
                                if ( $product->is_on_sale() ) { 
                                    echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
                                }
                            }else{
                                woolentor_show_product_sale_flash();
                            }
                            if( function_exists('woolentor_custom_product_badge') ){
                                woolentor_custom_product_badge();
                            }
                            echo wp_get_attachment_image( reset( $gallery_images_ids ), 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) );
                        ?>
                    </div>
                    <?php
                        $imagecount = 1;
                        foreach ( $gallery_images_ids as $thkey => $gallery_attachment_id ) {
                            if( $imagecount == 1 ){
                                $imagecount++;
                                continue;
                            }else{
                                echo '<div class="wl-single-gallery">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' ).'</div>';
                            }
                        }
                    ?>
                <?php elseif( $settings['layout_style'] == 'slider' ): ?>
                    <div class="wl-thumbnails-slider woocommerce-product-gallery__image" data-settings='<?php echo wp_json_encode( $slider_settings );  ?>' style="display:none;">
                        <?php
                            $j = 0;
                            foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                $j++;
                                if( $j == 1 ){
                                    echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) ).'</div>';
                                }else{
                                    echo '<div class="wl-single-slider">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_single' ).'</div>';
                                }
                            }
                        ?>
                    </div>
                <?php else:?>
                    <div class="woocommerce-product-gallery__image">
                        <?php
                            if( woolentor_is_preview_mode() ){
                                if ( $product->is_on_sale() ) { 
                                    echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woolentor-pro' ) . '</span>', $post, $product ); 
                                }
                            }else{
                                woolentor_show_product_sale_flash();
                            }
                            if(function_exists('woolentor_custom_product_badge')){
                                woolentor_custom_product_badge();
                            }
                            echo wp_get_attachment_image( reset( $gallery_images_ids ), 'woocommerce_single', '', array( 'class' => 'wp-post-image' ) );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if( woolentor_is_preview_mode() ){ echo '</div>'; } //tab_thumbnails_position ?>

        <?php if( $settings['layout_style'] == 'tabslider' ): 
            if( $settings['tab_thumbnails_position'] == 'right' || $settings['tab_thumbnails_position'] == 'left' ){ $slider_type = 'true'; } else{ $slider_type = 'false'; }
        ?>
            <script>
                ;jQuery(document).ready(function($) {
                    'use strict';

                    if( $('.woolentor-learg-img').length > 0 ){
                        $('.woolentor-learg-img')[0].style.display='inline-block';
                    }
                    if( $('.woolentor-thumbnails').length > 0 ){
                        $('.woolentor-thumbnails')[0].style.display='block';
                    }

                    $('.woolentor-learg-img').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: Boolean(<?php echo $tab_slider_settings['dots']; ?>),
                        arrows: Boolean(<?php echo $tab_slider_settings['imagearrows'] ?>),
                        fade: false,
                        asNavFor: '.woolentor-thumbnails',
                        prevArrow: '<button class="woolentor-slick-large-prev"><i class="sli sli-arrow-left"></i></button>',
                        nextArrow: '<button class="woolentor-slick-large-next"><i class="sli sli-arrow-right"></i></button>'
                    });
                    $('.woolentor-thumbnails').slick({
                        slidesToShow: <?php echo $settings['tabslitems']; ?>,
                        slidesToScroll: 1,
                        asNavFor: '.woolentor-learg-img',
                        centerMode: Boolean(<?php echo $tab_slider_settings['centermod']; ?>),
                        dots: false,
                        arrows: Boolean(<?php echo $tab_slider_settings['thumbarrows'] ?>),
                        vertical: <?php echo $slider_type; ?>,
                        focusOnSelect: true,
                        prevArrow: '<button class="woolentor-slick-prev"><i class="sli sli-arrow-left"></i></button>',
                        nextArrow: '<button class="woolentor-slick-next"><i class="sli sli-arrow-right"></i></button>',
                    });

                    <?php if( 'yes' === $settings['enable_image_zoom']): ?>
                        $(".thumbnails-layout-tabslider .woolentor-learg-img .slick-slide").zoom();
                    <?php else: ?>
                        $('.thumbnails-layout-tabslider .woolentor-learg-img .slick-slide').trigger('zoom.destroy');
                    <?php endif; ?>

                    //lightgallery
                    var productThumb = $(".thumbnails-layout-tabslider .woolentor-learg-img .slick-slide:not(.slick-active) img"),
                        imageSrcLength = productThumb.length,
                        images = [];
                    for (var i = 0; i < imageSrcLength; i++) {
                        images[i] = { src: productThumb[i].src };
                    }

                    $(".thumbnails-layout-tabslider .wl-thumbnails-image-area .btn-zoom-popup").on("click", function () {
                        images.unshift( { src: $(".thumbnails-layout-tabslider .woolentor-learg-img .slick-slide.slick-active img")[0].src } );
                        $(this).lightGallery({
                          thumbnail: false,
                          dynamic: true,
                          autoplayControls: false,
                          download: false,
                          actualSize: false,
                          share: false,
                          hash: false,
                          index: 0,
                          dynamicEl: images
                        });
                    });

                    function getWishListText(){
                        var whishlistText = $(".thumbnails-layout-tabslider #tabslide-wishlist .wishsuite-btn-text").text();
                        return whishlistText;
                    }

                    const wishListTippye = tippy('.thumbnails-layout-tabslider #tabslide-wishlist', {
                        content: getWishListText() ? getWishListText() : '<?php echo esc_html__('Wishlist','woolentor-pro'); ?>', 
                    });

                    const imageEnlargeTippye = tippy('.thumbnails-layout-tabslider #tabslide-enlarge-image', {
                        content: '<?php echo esc_html__('Click to enlarge','woolentor-pro'); ?>',
                    });

                    $(".thumbnails-layout-tabslider #tabslide-wishlist").on("click", function(){
                        wishListTippye.destroyAll();
                        tippy($(this)[0], {
                          content: getWishListText(),
                        });
                    });

                });
            </script>
        <?php endif; ?>

        <?php if( $settings['layout_style'] == 'tabslider' || $settings['layout_style'] == 'slider' ): ?>
            <script>
                ;jQuery(document).ready(function($) {
                    'use strict';
                    $( '.single_variation_wrap' ).on( 'show_variation', function ( event, variation ) {
                        $('.wlpro-product-thumbnails').find('.woolentor-learg-img').slick('slickGoTo', 0);
                    });

                });
            </script>
        <?php endif; ?>

        <?php
    }

}