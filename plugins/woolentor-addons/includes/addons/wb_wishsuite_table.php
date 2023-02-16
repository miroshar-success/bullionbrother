<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Wishsuite_Table_Widget extends Widget_Base {

    public function get_name() {
        return 'wb-wishsuite-table';
    }

    public function get_title() {
        return __( 'WL: WishSuite Table', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-table';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'wishsuite-frontend',
            'woolentor-widgets',
        ];
    }

    public function get_script_depends(){
        return ['wishsuite-frontend'];
    }

    public function get_keywords(){
        return ['wishlist','product wishlist','wishsuite'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'wishsuite_content',
            [
                'label' => __( 'WishSuite', 'woolentor' ),
            ]
        );

            $this->add_control(
                'empty_table_text',
                [
                    'label' => __( 'Empty table text', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block'=>true,
                ]
            );

        $this->end_controls_section();

        // Table Heading Style
        $this->start_controls_section(
            'table_heading_style_section',
            [
                'label' => __( 'Table Heading', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'heading_color',
                [
                    'label' => __( 'Heading Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content table thead > tr th' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'heading_background',
                    'label' => __( 'Heading Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wishsuite-table-content table thead > tr th',
                    'exclude' =>['image'],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'heading_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-table-content table thead > tr',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-table-content table thead > tr th',
                ]
            );
            
        $this->end_controls_section();

        // Table Content Style
        $this->start_controls_section(
            'table_content_style_section',
            [
                'label' => __( 'Table Body', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'table_body_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-table-content table,.wishsuite-table-content table tbody > tr',
                ]
            );

            $this->add_control(
                'table_body_title',
                [
                    'label' => __( 'Title', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_title_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-table-content table tbody > tr td.wishsuite-product-title',
                ]
            );

            $this->add_control(
                'table_body_title_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content table tbody > tr td.wishsuite-product-title a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_title_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content table tbody > tr td.wishsuite-product-title a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_price',
                [
                    'label' => __( 'Price', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_price_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-table-content table tbody > tr td.wishsuite-product-price',
                ]
            );

            $this->add_control(
                'table_body_price_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content table tbody > tr td.wishsuite-product-price' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wishsuite-table-content table tbody > tr td.wishsuite-product-price .woocommerce-Price-amount' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_quantity_field',
                [
                    'label' => __( 'Quantity', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'table_body_quantity_field_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content .quantity .qty' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_quantity_field_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-table-content .quantity .qty',
                ]
            );
            $this->add_control(
                'table_body_quantity_field_background_color',
                [
                    'label' => __( 'Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content .quantity .qty' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'table_body_quantity_field_border_color',
                [
                    'label' => __( 'Border Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content .quantity .qty' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_remove_icon',
                [
                    'label' => __( 'Remove Icon', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'table_body_remove_icon_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-remove::before' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wishsuite-remove::after' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'table_body_remove_icon_hover_color',
                [
                    'label' => __( 'Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-remove:hover::before' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wishsuite-remove:hover::after' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'table_body_social_share',
                [
                    'label' => __( 'Social Share', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );
            $this->add_control(
                'table_body_social_share_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-social-share .wishsuite-social-title' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_body_social_share_title_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-social-share .wishsuite-social-title',
                ]
            );

            $this->add_control(
                'table_body_social_share_color',
                [
                    'label' => __( 'Icon Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-social-share ul li a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'table_body_social_share_hover_color',
                [
                    'label' => __( 'Icon Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-social-share ul li a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'table_body_social_share_size',
                [
                    'label' => esc_html__( 'Size', 'woolentor' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-social-share ul li a .wishsuite-social-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

         // Table Add to cart button Style
         $this->start_controls_section(
            'table_content_add_to_style_section',
            [
                'label' => __( 'Add To Cart', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'table_add_to_cart_button_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-table-content table .wishsuite-addtocart',
                ]
            );

            $this->add_responsive_control(
                'table_add_to_cart_button_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-table-content table .wishsuite-addtocart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('table_add_to_cart_button_style_tabs');

                // Normal
                $this->start_controls_tab(
                    'table_add_to_cart_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'table_cart_button_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wishsuite-table-content table .wishsuite-addtocart' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
        
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'table_cart_button_bg_color',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wishsuite-table-content table .wishsuite-addtocart',
                            'exclude' =>['image'],
                        ]
                    );

                $this->end_controls_tab();

                // Hover
                $this->start_controls_tab(
                    'table_add_to_cart_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'table_cart_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wishsuite-table-content table .wishsuite-addtocart:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
        
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'table_cart_button_hover_bg_color',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .wishsuite-table-content table .wishsuite-addtocart:hover',
                            'exclude' =>['image'],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $short_code_attributes = [
            'empty_text' => $settings['empty_table_text'],
        ];
        echo woolentor_do_shortcode( 'wishsuite_table', $short_code_attributes );

    }

}
