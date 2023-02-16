<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Stock_Progress_Bar_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-stock-progressbar';
    }
    
    public function get_title() {
        return __( 'WL: Available Stock Progressbar', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-skill-bar';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets-pro',
        ];
    }

    public function get_keywords(){
        return ['progressbar','product progressbar','stock progressbar','available stock progressbar'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_stock_progressbar',
            [
                'label' => __( 'Stock Progressbar', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            
            $this->add_control(
                'hide_order_counter',
                [
                    'label'     => __( 'Hide Order Counter', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wltotal-sold' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_available_counter',
                [
                    'label'     => __( 'Hide Available Counter', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wlcurrent-stock' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'order_custom_text',
                [
                    'label' => __( 'Ordered Custom Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Ordered', 'woolentor-pro' ),
                    'condition' => [
                        'hide_order_counter!' => 'yes',
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'available_custom_text',
                [
                    'label' => __( 'Available Custom Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Items available', 'woolentor-pro' ),
                    'condition' => [
                        'hide_available_counter!' => 'yes',
                    ],
                    'label_block' => true,
                ]
            );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'section_stock_progressbar_style',
            [
                'label' => __( 'Stock Progressbar', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'progressbar_height',
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
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlprogress-area' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlprogress-area' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_active_bg_color',
                [
                    'label' => __( 'Sell Progress Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlprogress-bar' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_area',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Order and Ability Style
        $this->start_controls_section(
            'section_stock_order_ability_style',
            [
                'label' => __( 'Order & Ability Counter', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'order_ability_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-stock-progress-bar .wlstock-info',
                ]
            );

            $this->add_control(
                'order_ability_color',
                [
                    'label' => __( 'Label Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlstock-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'counter_number_color',
                [
                    'label' => __( 'Counter Number Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-stock-progress-bar .wlstock-info span' => 'color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $order_text     = $settings['order_custom_text'] ? $settings['order_custom_text'] : 'Ordered:';
        $available_text = $settings['available_custom_text'] ? $settings['available_custom_text'] : 'Items available:';

        if( woolentor_is_preview_mode() ){
            $product_id = woolentor_get_last_product_id();
        } else{
            $product_id = get_the_ID();
        }
        $this->manage_stock_status( $order_text, $available_text, $product_id );

    }

    protected function manage_stock_status( $order_text, $available_text, $product_id ){

        $product_id  = $product_id;
        if ( get_post_meta( $product_id, '_manage_stock', true ) == 'yes' ) {

            $total_stock = get_post_meta( $product_id, 'woolentor_total_stock_quantity', true );

            if ( ! $total_stock ) { echo '<div class="stock-management-progressbar">'.__( 'Set the initial stock amount from', 'woolentor-pro' ).' <a href="'.get_edit_post_link( $product_id ).'" target="_blank">'.__( 'here', 'woolentor-pro' ).'</a></div>'; return; }

            $current_stock = round( get_post_meta( $product_id, '_stock', true ) );

            $total_sold = $total_stock > $current_stock ? $total_stock - $current_stock : 0;
            $percentage = $total_sold > 0 ? round( $total_sold / $total_stock * 100 ) : 0;

            if ( $current_stock >= 0 ) {
                echo '<div class="woolentor-stock-progress-bar">';
                    echo '<div class="wlstock-info">';
                        echo '<div class="wltotal-sold">' . __( $order_text, 'woolentor-pro' ) . '<span>' . esc_html( $total_sold ) . '</span></div>';
                        echo '<div class="wlcurrent-stock">' . __( $available_text, 'woolentor-pro' ) . '<span>' . esc_html( $current_stock ) . '</span></div>';
                    echo '</div>';
                    echo '<div class="wlprogress-area" title="' . __( 'Sold', 'woolentor-pro' ) . ' ' . esc_attr( $percentage ) . '%">';
                        echo '<div class="wlprogress-bar"style="width:' . esc_attr( $percentage ) . '%;"></div>';
                    echo '</div>';
                echo '</div>';
            }

        }

    }

}