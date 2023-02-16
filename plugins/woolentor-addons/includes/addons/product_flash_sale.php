<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Product_Flash_Sale_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-flash-sale-product';
    }
    
    public function get_title() {
        return __( 'WL: Product Flash Sale', 'woolentor' );
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
            'woolentor-flash-sale-module',
        ];
    }

    public function get_script_depends() {
        return [
            'woolentor-flash-sale-module',
        ];
    }

    public function get_keywords(){
        return ['flash','product','sale','flash sale'];
    }

    public function get_deal_options(){
        $deals = woolentor_get_option('deals', 'woolentor_flash_sale_settings');
        $deal_options = [];
        if( isset( $deals ) && is_array( $deals ) ){
            $deal_options = array_map( function( $item ){
                return !empty( $item['title'] ) ? $item['title'] : __( 'Unnamed Deal', 'woolentor' );
            }, $deals );
        }
        return $deal_options;
    }

    protected function register_controls() {
        // General settings
        $this->start_controls_section(
            'woolentor-products-general-setting',
            [
                'label' => esc_html__( 'General Settings', 'woolentor' ),
            ]
        );

            if( empty( $this->get_deal_options() ) ){
                $this->add_control(
                    'important_note',
                    [
                        'type' => Controls_Manager::RAW_HTML,
                        'raw' => '<div style="line-height:18px;">No events have been created, You can create an events from here. <strong>WooLentor > Settings > Modules > Flash Sale Countdown.<strong> '.sprintf( __( '<a href="%s" target="_blank">Create Event</a>', 'woolentor-pro' ), admin_url( 'admin.php?page=woolentor' ) ).'</div>',
                        'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    ]
                );
            }else{
                $this->add_control(
                    'deal',
                    [
                        'label' => esc_html__( 'Select Deal', 'woolentor' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => $this->get_deal_options(),
                        'default' => '0',
                    ]
                );
            }

            $this->add_control(
              'woolentor_product_grid_products_count',
                [
                    'label'   => __( 'Product Limit', 'woolentor' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 9,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'woolentor_product_grid_column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1' => esc_html__( '1', 'woolentor' ),
                        '2' => esc_html__( '2', 'woolentor' ),
                        '3' => esc_html__( '3', 'woolentor' ),
                        '4' => esc_html__( '4', 'woolentor' ),
                        '5' => esc_html__( '5', 'woolentor' ),
                        '6' => esc_html__( '6', 'woolentor' ),
                    ],
                ]
            );

            $this->add_control(
                'woolentor_custom_order',
                [
                    'label' => esc_html__( 'Custom Order', 'woolentor' ),
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
                    'label' => esc_html__( 'Order', 'woolentor' ),
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

        // Countdown settings
        $this->start_controls_section(
            'woolentor-products-countdown-setting',
            [
                'label' => esc_html__( 'Countdown', 'woolentor' ),
            ]
        );
            $this->add_control(
                'show_countdown',
                [
                    'label'        => __( 'Show Countdown Timer', 'woolentor' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __( 'Show', 'woolentor' ),
                    'label_off'    => __( 'Hide', 'woolentor' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_control(
                'countdown_style',
                [
                    'label'   => __( 'Countdown Style', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'  => __( 'Style One', 'woolentor' ),
                        '2'  => __( 'Style Two', 'woolentor' ),
                    ],
                    'condition' =>[
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'countdown_position',
                [
                    'label'   => __( 'Position', 'woolentor' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'content_top' => [
                            'title' => __( 'Content Top', 'woolentor' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'content_bottom' => [
                            'title' => __( 'Content Bottom', 'woolentor' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                        'left' => [
                            'title' => __( 'Thumbnail Left', 'woolentor' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Thumbnail Right', 'woolentor' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'bottom' => [
                            'title' => __( 'Thumbnail Bottom', 'woolentor' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => 'content_top',
                    'toggle'      => false,
                    'label_block' => true,
                    'condition'   =>[
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'countdown_title',
                [
                    'label'       => __( 'Countdown Title', 'woolentor' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'description' => __( 'HTML tags are allowed.', 'woolentor' ),
                    'default'     => woolentor_get_option('countdown_timer_title', 'woolentor_flash_sale_settings'),
                    'condition'   => [
                        'show_countdown' => 'yes',
                        'countdown_position' => array('content_top', 'content_bottom')
                    ]
                ]
            );

            $this->add_control(
                'custom_labels',
                [
                    'label'        => __( 'Custom Label', 'woolentor' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'condition'    => [
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_days',
                [
                    'label'       => __( 'Days', 'woolentor' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Days', 'woolentor' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_hours',
                [
                    'label'       => __( 'Hours', 'woolentor' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Hours', 'woolentor' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_minutes',
                [
                    'label'       => __( 'Minutes', 'woolentor' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Minutes', 'woolentor' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_seconds',
                [
                    'label'       => __( 'Seconds', 'woolentor' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Seconds', 'woolentor' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );
        $this->end_controls_section();

        // Stock Progress Settings
        $this->start_controls_section(
            'woolentor-products-stockprogress-setting',
            [
                'label' => esc_html__( 'Stock Progress Bar', 'woolentor' ),
            ]
        );

            $this->add_control(
                'show_stock_progress',
                [
                    'label'        => __( 'Show Stock Progress Bar', 'woolentor' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __( 'Show', 'woolentor' ),
                    'label_off'    => __( 'Hide', 'woolentor' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ]
            );

            $this->add_control(
                'show_stock_progress_notice',
                [
                    'raw'             => esc_html__( 'Product must have both "Manage stock" and "Initial number in stock" set from the "Inventory" tab to display the stock progress indicator."' , 'woolentor' ),
                    'type'            => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor elementor-panel-alert elementor-panel-alert-info',
                    'condition'       => [
                        'show_stock_progress' => 'yes',
                    ]
                ],
            );

            $this->add_control(
                'sold_custom_text',
                [
                    'label'       => __( 'Sold Custom Text', 'woolentor' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Sold:', 'woolentor' ),
                    'label_block' => true,
                    'condition'   => [
                        'show_stock_progress' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'available_custom_text',
                [
                    'label'       => __( 'Available Custom Text', 'woolentor' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Available:', 'woolentor' ),
                    'label_block' => true,
                    'condition'   => [
                        'show_stock_progress' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Styling
        $this->start_controls_section(
            'styling_section_tab',
            [
                'label' => __( 'General Style', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'product_inner_margin',
                [
                    'label'      => __( 'Margin', 'woolentor' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .woolentor-flash-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_inner_padding',
                [
                    'label'      => __( 'Padding', 'woolentor' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .woolentor-flash-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Title
            $this->add_control(
                'product_title_heading',
                [
                    'label'     => __( 'Product Title', 'woolentor' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label'     => __( 'Title Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product .woolentor-flash-product-title a' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'product_title_typography',
                    'selector' => '{{WRAPPER}} .woolentor-flash-product .woolentor-flash-product-title a',                    
                ]
            );

            $this->add_control(
                'product_title_hover_color',
                [
                    'label'     => __( 'Title Hover Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#0A3ACA',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product .woolentor-flash-product-title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_title_margin',
                [
                    'label'      => __( 'Margin', 'woolentor' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .woolentor-flash-product .woolentor-flash-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Price
            $this->add_control(
                'product_price_heading',
                [
                    'label'     => __( 'Product Price', 'woolentor' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'product_price_margin',
                [
                    'label'      => __( 'Margin', 'woolentor' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'separator'  => 'before',
                    'selectors'  => [
                        '{{WRAPPER}} .woolentor-flash-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_sale_price_color',
                [
                    'label'     => __( 'Sale Price Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#000',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-price,{{WRAPPER}} .woolentor-flash-product-price ins' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'product_sale_price_typography',
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-price,{{WRAPPER}} .woolentor-flash-product-price ins',
                ]
            );

            $this->add_control(
                'product_regular_price_color',
                [
                    'label'     => __( 'Regular Price Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#666666',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-price del' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'product_regular_price_typography',
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-price del',
                ]
            );

            // Rating
            $this->add_control(
                'product_rating_heading',
                [
                    'label'     => __( 'Product Rating', 'woolentor' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_rating_icon_color',
                [
                    'label'     => __( 'Icon Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default'   => '#ecb804',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-rating i' => 'color: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_control(
                'product_rating_icon_size',
                [
                    'label'      => __( 'Icon Size', 'woolentor' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range'      => [
                        'px' => [
                            'min' => 10,
                            'max' => 100,
                            'step' => 5,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 16,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_rating_number_color',
                [
                    'label'     => __( 'Number Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-rating span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'product_rating_number_typography',
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-rating span',
                ]
            );

            $this->add_control(
                'product_info_others_heading',
                [
                    'label'     => __( 'Others', 'woolentor' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_info_separator_color',
                [
                    'label'     => __( 'Separator Border Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-offer-timer,{{WRAPPER}} .woolentor-flash-product-offer-pos-c-bottom .woolentor-flash-product-offer-timer' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_info_out_of_stock_color',
                [
                    'label'     => __( '"Out of stock" Text Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-image span' => 'color: {{VALUE}};',
                    ],
                    'default' => '#ffffff'
                ]
            );

            $this->add_control(
                'product_info_out_of_stock_bg_color',
                [
                    'label'     => __( '"Out of stock" BG Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-image span' => 'background-color: {{VALUE}};',
                    ],
                    'default' => '#f05b64'
                ]
            );


        $this->end_controls_section(); // General

        // Style Action Button tab section
        $this->start_controls_section(
            'product_action_button_style_section',
            [
                'label' => __( 'Action Button Style', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
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
                        'product_action_button_normal_bg_color',
                        [
                            'label' => esc_html__( 'Button Background', 'woolentor' ),
                            'type'  => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'product_action_button_normal_background_color',
                            'label'    => __( 'Button Background', 'woolentor' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woolentor-flash-product-action a',
                        ]
                    );

                    $this->add_control(
                        'product_action_button_normal_icon_color',
                        [
                            'label' => esc_html__( 'Button Icon Color', 'woolentor' ),
                            'type'  => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'product_action_button_normal_color',
                        [
                            'label'     => __( 'Button Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-flash-product-action i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-flash-product-action svg' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_action_button_border',
                        [
                            'label' => esc_html__( 'Button Border', 'woolentor' ),
                            'type'  => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_border_radius',
                        [
                            'label'      => __( 'Border Radius', 'woolentor' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .woolentor-flash-product-action a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                            'label'     => __( 'Button Hover Icon Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '#ffffff' ,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-flash-product-action a:hover i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-flash-product-action a:hover svg' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                   
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'product_action_button_hover_background_color',
                            'label'    => __( 'Button Hover Background Color', 'woolentor' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'default'  => '#DC9A0E',
                            'selector' => '{{WRAPPER}} .woolentor-flash-product-action a:hover',
                        ]
                    );

                $this->end_controls_tab(); // Hover tab

            $this->end_controls_tabs(); // Normal and Hover tabs

        $this->end_controls_section(); // Action buttons

        // Style Countdown tab section
        $this->start_controls_section(
            'product_counter_style_section',
            [
                'label' => __( 'Counter Style', 'woolentor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_countdown' => 'yes',
                ]
            ]
        );
            // Countdown title
            $this->add_control(
                'product_counter_heading',
                [
                    'label'     => __( 'Title', 'woolentor' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'product_counter_title_typography',
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-offer-timer-text',                    
                ]
            );

            $this->add_control(
                'product_ccounter_title_color',
                [
                    'label'     => __( 'Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-offer-timer-text' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_ccounter_title_margin',
                [
                    'label'      => __( 'Margin', 'woolentor' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .woolentor-flash-product-offer-timer-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Counter items
            $this->add_control(
                'product_counter_items_heading',
                [
                    'label'     => __( 'Counter Item', 'woolentor' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_counter_number_color',
                [
                    'label'     => __( 'Number Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-offer-timer .woolentor-count' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',    
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'product_counter_number_typography',
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-offer-timer .woolentor-count',                    
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'product_counter_background_color',
                    'label'    => __( 'Counter Background', 'woolentor' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-offer-timer .woolentor-count',
                ]
            );

            // Counter Label
            $this->add_control(
                'product_counter_label_heading',
                [
                    'label'     => __( 'Counter Label', 'woolentor' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_counter_label_color',
                [
                    'label'     => __( 'Label Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-offer-timer .woolentor-label' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',    
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'product_counter_label_typography',
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-offer-timer .woolentor-label',                    
                ]
            );

        $this->end_controls_section(); // Counter

        $this->start_controls_section(
            'product_stock_progress_style_section',
            [
                'label'     => __( 'Stock Progress Style', 'woolentor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_stock_progress' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'product_stock_progress_bar_color',
                [
                    'label'     => __( 'Color', 'woolentor' ),
                    'separator' => 'before',
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-progress-sold' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'product_stock_progress_bar_bg_color',
                    'label'    => __( 'Background', 'woolentor' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-flash-product-progress-total',
                ]
            );

            $this->add_control(
                'product_stock_progress_bar_sold_label_color',
                [
                    'label'     => __( 'Sold Label Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-progress-label span:first-child' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_stock_progress_bar_Available_label_color',
                [
                    'label'     => __( 'Available Number Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-progress-label span:last-child' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_stock_progress_bar_height',
                [
                    'label'      => __( 'Height', 'woolentor' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'range' => [
                        'px' => [
                            'min' => 5,
                            'max' => 100,
                            'step' => 5,
                        ],
                        'em' => [
                            'min' => 1,
                            'max' => 5,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-progress-total' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_stock_progress_bar_border_radius',
                [
                    'label'      => __( 'Border Radius', 'woolentor' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 5,
                        ],
                        'em' => [
                            'min' => 0,
                            'max' => 5,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-flash-product-progress-total,{{WRAPPER}} .woolentor-flash-product-progress-sold' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Stock progress
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();

        // Calculate Column
        $columns   = $settings['woolentor_product_grid_column'];
        $collumval = 'ht-product ht-col-lg-4 ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-30 product';
        if( $columns !='' ){
            if( $columns == 5 ){
                $collumval = 'ht-product cus-col-5 ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-30 product';
            }else{
                $colwidth = round( 12 / $columns );
                $collumval = 'ht-product ht-col-lg-'.$colwidth.' ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-30 product';
            }
        }

        // Countdown
        $show_countdown     = $settings['show_countdown'];
        $countdown_style    = $settings['countdown_style'];
        $countdown_style    = $countdown_style == '2' ? 'flip' : 'default';
        $countdown_position = $settings['countdown_position'] ? $settings['countdown_position'] : 'content_top';
        $countdown_position_class = array(
            'left'           => 'woolentor-flash-product-offer-pos-t-left',
            'right'          => 'woolentor-flash-product-offer-pos-t-right',
            'bottom'         => 'woolentor-flash-product-offer-pos-t-bottom',
            'content_top'    => '',
            'content_bottom' => 'woolentor-flash-product-offer-pos-c-bottom',
        );
        $countdown_title    = $settings['countdown_title'];
        $data_customlavel   = [];
        if( $show_countdown == 'yes' ){
            $data_customlavel['daytxt']     = ! empty( $settings['customlabel_days'] ) ? $settings['customlabel_days'] : __('Days', 'woolentor');
            $data_customlavel['hourtxt']    = ! empty( $settings['customlabel_hours'] ) ? $settings['customlabel_hours'] : __('Hours', 'woolentor');
            $data_customlavel['minutestxt'] = ! empty( $settings['customlabel_minutes'] ) ? $settings['customlabel_minutes'] : __('Min', 'woolentor');
            $data_customlavel['secondstxt'] = ! empty( $settings['customlabel_seconds'] ) ? $settings['customlabel_seconds'] : __('Sec', 'woolentor');
        }

        // Stock Progress bar
        $show_stock_progress   = $settings['show_stock_progress'];
        $sold_custom_text      = $settings['sold_custom_text'] ? $settings['sold_custom_text'] : __('Sold:', 'woolentor');
        $available_custom_text = $settings['available_custom_text'] ? $settings['available_custom_text'] : __('Available:', 'woolentor');

        // Get deal
        $selected_deal = $settings['deal'];
        $deals         = woolentor_get_option('deals', 'woolentor_flash_sale_settings');
        $deal          = !empty($deals[$selected_deal]) ? $deals[$selected_deal] : array();

        // Query Argument
        $per_page           = $settings['woolentor_product_grid_products_count'];
        $custom_order_ck    = $settings['woolentor_custom_order'];
        $orderby            = $settings['orderby'];
        $order              = $settings['order'];

        $query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $per_page,
        );

        // Custom Order
        if( $custom_order_ck == 'yes' ){
            $query_args['orderby'] = $orderby;
            $query_args['order'] = $order;
        }

        $apply_on_all_products = !empty($deal['apply_on_all_products']) ? $deal['apply_on_all_products'] : 'off';
        $include_categories    = !empty($deal['categories']) ? $deal['categories'] : array();
        $include_products      = !empty($deal['products']) ? $deal['products'] : array();
        $exclude_products      = !empty($deal['exclude_products']) ? $deal['exclude_products'] : array();
        $product_ids           = array();

        // Prepare product ids
        if( $apply_on_all_products != 'on' ){

            if( $include_categories ){
                $query_1 = new \WP_Query( array(
                    'post_type'   => 'product',
                    'post_status' => 'publish',
                    'fields'      => 'ids',
                    'tax_query'   => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    =>  $include_categories,
                        ),
                        // grouped and variable product is not supported right now
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'product_type',
                            'field'    => 'slug',
                            'terms'    => array('simple', 'external'),
                        ),
                    ),

                ));

                $product_ids = $query_1->posts;
            }

            if( $include_products ){
                $product_ids = array_merge($product_ids, $include_products);
            }

            if( $exclude_products ){
                $product_ids = array_intersect($product_ids, $exclude_products);
            }

        } elseif( $exclude_products ){
            $query_args['post__not_in'] = $exclude_products;
        }

        $found_products = false;
        $deal_status = !empty($deal['status']) ? $deal['status'] : 'off';
        if( $deal_status == 'on' && \Woolentor_Flash_Sale::user_validity($deal) && \Woolentor_Flash_Sale::datetime_validity($deal) ){
            if( $apply_on_all_products == 'on' ){
                $found_products = true;
            } elseif( $product_ids ){
                $found_products = true;
                $query_args['post__in'] = $product_ids;
            }
        }

        if( $found_products ):
        ?>
            <div class="ht-products woocommerce ht-row">

                <?php
                    $products = new \WP_Query( $query_args );
                    if( $products->have_posts() ):

                        // Countdown remaining time
                        $remaining_time = \Woolentor_Flash_Sale::get_remaining_time($deal);

                        while( $products->have_posts() ): $products->the_post();
                            global $product;
                            $product_id = $product->get_id();
                            $ajax_add_to_cart_class  = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';
                            $ajax_add_to_cart_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';
                ?>

                <!--Product Start-->
                <div class="<?php echo esc_attr($collumval); ?>">
                    <div class="woolentor-flash-product <?php echo esc_attr($countdown_position_class[$countdown_position]) ?>">

                        <div class="woolentor-flash-product-thumb">
                            <a href="<?php the_permalink(); ?>">
                                <div class="woolentor-flash-product-image">
                                    <?php 
                                        woolentor_sale_flash();
                                        woocommerce_template_loop_product_thumbnail();
                                    ?>
                                </div>
                            </a>

                            <?php if($show_countdown == 'yes'): ?>
                                <div class="woolentor-countdown woolentor-countdown-<?php echo esc_attr($countdown_style); ?>" data-countdown="<?php echo esc_attr( $remaining_time ) ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                            <?php endif; ?>


                            <ul class="woolentor-flash-product-action">
                                <li><a href="<?php echo $product->add_to_cart_url() ?>" data-quantity="1" class="woolentor-flash-product-action-btn <?php echo esc_attr($ajax_add_to_cart_class); ?>" data-product_id="<?php echo esc_attr($product_id); ?>"><i class="fa fa-shopping-cart"></i></a></li>

                                <?php
                                    if( true === woolentor_has_wishlist_plugin() ){
                                        echo '<li>'.woolentor_add_to_wishlist_button('<i class="fa fa-heart"></i>','<i class="fa fa-heart"></i>').'</li>';
                                    }
                                ?>   

                                <?php
                                    if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() ){
                                        echo '<li>';
                                            woolentor_compare_button(
                                                array(
                                                    'style'=>2,
                                                    'btn_text'=>'<i class="fas fa-exchange-alt"></i>',
                                                    'btn_added_txt'=>'<i class="fas fa-exchange-alt"></i>' 
                                                )
                                            );
                                       echo '</li>';
                                    }
                                ?>

                                <li>
                                    <a href="#" class="woolentor-flash-product-action-btn woolentorquickview" data-quick-id="<?php echo esc_attr($product_id);?>" >
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="woolentor-flash-product-content">

                            <?php if($show_countdown == 'yes'): ?>
                                <div class="woolentor-flash-product-offer-timer">
                                    
                                    <?php if($countdown_title): ?>
                                    <p class="woolentor-flash-product-offer-timer-text"><?php echo wp_kses_post($countdown_title) ?></p>
                                    <?php endif; ?>

                                    
                                    <div class="woolentor-countdown woolentor-countdown-<?php echo esc_attr($countdown_style); ?>" data-countdown="<?php echo esc_attr( $remaining_time ) ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                                </div>
                            <?php endif; ?>

                            <?php
                                $manage_stock  = get_post_meta( $product_id, '_manage_stock', true );
                                $initial_stock = get_post_meta( $product_id, 'woolentor_total_stock_quantity', true );

                                if($show_stock_progress == 'yes' && $manage_stock == 'yes' && $initial_stock):
                                    $current_stock = get_post_meta( $product_id, '_stock', true );
                                    $total_sold    = $initial_stock > $current_stock ? $initial_stock - $current_stock : 0;
                                    $percentage    = $total_sold > 0 ? round( $total_sold / $initial_stock * 100 ) : 0;

                                    if($current_stock >= 0):
                                ?>
                                <div class="woolentor-flash-product-progress">
                                    <div class="woolentor-flash-product-progress-total">
                                        <div class="woolentor-flash-product-progress-sold" style="width: <?php echo esc_attr($percentage) ?>%;"></div>
                                    </div>
                                    <div class="woolentor-flash-product-progress-label"><span><?php echo esc_html($sold_custom_text) ?> <?php echo esc_html($total_sold) ?></span><span><?php echo esc_html($available_custom_text) ?> <?php echo esc_html($current_stock); ?></span></div>
                                </div>
                                <?php endif; ?>

                            <?php elseif($show_stock_progress && $manage_stock == 'yes' && !$initial_stock): ?>
                                <div class="woolentor-flash-product-progress woolentor-stock-message">
                                    <span><?php echo esc_html__( 'To show the stock progress bar. Set the initial stock amount from', 'woolentor' ) ?></span> <a href="<?php echo esc_url(get_edit_post_link( $product_id )) ?>" target="_blank"><b> <?php echo esc_html__( 'Here', 'woolentor' ); ?></b></a>
                                </div>
                            <?php endif; ?>

                            <h3 class="woolentor-flash-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                            <?php
                                $has_rating_class = $product->get_average_rating() ? '' : 'woolentor-product-has-no-rating';
                            ?>
                            <div class="woolentor-flash-product-price-rating <?php echo esc_attr($has_rating_class) ?>">
                                <div class="woolentor-flash-product-price">
                                    <?php
                                        if( $product->get_type() != 'variable' ){
                                            
                                            echo '<div class="price">' .wc_format_sale_price( wc_get_price_to_display( $product ), \Woolentor_Flash_Sale::get_calculated_price($product_id, $deal) ) . $product->get_price_suffix() . '</div>';

                                        } elseif($product->get_type() == 'variable') {
                                            $price_min_o        = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'min' ) ] );
                                            $price_min        = \Woolentor_Flash_Sale::get_calculated_price($product_id, $deal, $price_min_o);
                                            $price_max_o        = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'max' ) ] );
                                            $price_max        = \Woolentor_Flash_Sale::get_calculated_price($product_id, $deal, $price_max_o);
                                            $price_html       = wc_format_price_range( $price_min, $price_max );

                                            if($price_min == $price_max){
                                                echo '<div class="price">' .wc_format_sale_price( $price_max_o, $price_max) . $product->get_price_suffix() . '</div>';
                                            } else{
                                                echo '<div class="price">' .wp_kses_post($price_html) . '</div>';
                                            }
                                        }
                                    ?>
                                </div>

                                <?php if($product->get_average_rating()): ?>
                                <div class="woolentor-flash-product-rating"><i class="eicon-star"></i> <span><?php echo esc_html($product->get_average_rating()); ?></span></div>
                                <?php endif; ?>
                            </div>

                        </div>

                    </div>
                </div> <!-- /.product -->
                <?php endwhile; wp_reset_query(); wp_reset_postdata(); endif; ?>

            </div> <!-- /.ht-products -->
        <?php
        else:
            echo '<strong>' . __( 'Unfortunately, no products were found in the deal you selected.', 'woolentor' ) . '</strong>';
        endif;
    }

}