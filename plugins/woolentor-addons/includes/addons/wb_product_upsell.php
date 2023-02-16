<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Upsell_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-single-product-upsell';
    }

    public function get_title() {
        return __( 'WL: Product Upsell', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-upsell';
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_categories() {
        return array( 'woolentor-addons' );
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets',
        ];
    }

    public function get_keywords(){
        return ['product','upsell','upsell product'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_upsell_content',
            [
                'label' => __( 'Upsells', 'woolentor' ),
            ]
        );

            $this->add_responsive_control(
                'columns',
                [
                    'label' => __( 'Columns', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'prefix_class' => 'woolentorducts-columns%s-',
                    'default' => 4,
                    'min' => 1,
                    'max' => 6,
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => __( 'Order By', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date'          => __( 'Date', 'woolentor' ),
                        'title'         => __( 'Title', 'woolentor' ),
                        'price'         => __( 'Price', 'woolentor' ),
                        'popularity'    => __( 'Popularity', 'woolentor' ),
                        'rating'        => __( 'Rating', 'woolentor' ),
                        'rand'          => __( 'Random', 'woolentor' ),
                        'menu_order'    => __( 'Menu Order', 'woolentor' ),
                    ],
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => __( 'Order', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'desc',
                    'options' => [
                        'asc'   => __( 'ASC', 'woolentor' ),
                        'desc'  => __( 'DESC', 'woolentor' ),
                    ],
                ]
            );

            $this->add_control(
                'wl_show_heading',
                [
                    'label' => __( 'Heading', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'woolentor' ),
                    'label_off' => __( 'Hide', 'woolentor' ),
                    'render_type' => 'ui',
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'prefix_class' => 'wl-show-heading-',
                ]
            );

        $this->end_controls_section();

        // Heading Style
        $this->start_controls_section(
            'heading_style_section',
            array(
                'label' => __( 'Heading', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'heading_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .up-sells > h2' => 'color: {{VALUE}} !important',
                    ],
                    'condition' => [
                        'wl_show_heading!' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .up-sells > h2',
                    'condition' => [
                        'wl_show_heading!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .up-sells > h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                    'condition' => [
                        'wl_show_heading!' => '',
                    ],
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        $product_per_page   = '-1';
        $columns            = 4;
        $orderby            = 'rand';
        $order              = 'desc';
        if ( ! empty( $settings['columns'] ) ) {
            $columns = $settings['columns'];
        }
        if ( ! empty( $settings['orderby'] ) ) {
            $orderby = $settings['orderby'];
        }
        if ( ! empty( $settings['order'] ) ) {
            $order = $settings['order'];
        }

        if( woolentor_is_preview_mode() ){
            echo \WooLentor_Default_Data::instance()->default( $this->get_name(), $settings );
        }else{
            woocommerce_upsell_display( $product_per_page, $columns, $orderby, $order );
        }

    }

}
