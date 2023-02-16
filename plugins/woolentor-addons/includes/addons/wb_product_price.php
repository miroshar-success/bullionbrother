<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Price_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-single-product-price';
    }

    public function get_title() {
        return __( 'WL: Product Price', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-price';
    }

    public function get_categories() {
        return array( 'woolentor-addons' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets',
        ];
    }

    public function get_keywords(){
        return ['price','product price'];
    }

    protected function register_controls() {

        // Product Price Style
        $this->start_controls_section(
            'product_price_regular_style_section',
            array(
                'label' => __( 'Price', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'product_price_color',
                [
                    'label'     => __( 'Price Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .price' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'product_price_typography',
                    'label'     => __( 'Typography', 'woolentor' ),
                    'selector'  => '{{WRAPPER}} .price .amount',
                ]
            );

            $this->add_control(
                'price_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_price_sale_style_section',
            [
                'label' => __( 'Old Price', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'product_sale_price_color',
                [
                    'label'     => __( 'Price Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .price del' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'product_sale_price_typography',
                    'label'     => __( 'Typography', 'woolentor' ),
                    'selector'  => '{{WRAPPER}} .price del, {{WRAPPER}} .price del .amount',
                )
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        global $product;
        $product = wc_get_product();

        if( woolentor_is_preview_mode() ){
            echo \WooLentor_Default_Data::instance()->default( $this->get_name() );
        }else{
            if ( empty( $product ) ) { return; }
            woocommerce_template_single_price();
        }

    }

}
