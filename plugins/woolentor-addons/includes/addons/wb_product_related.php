<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Related_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-related';
    }

    public function get_title() {
        return __( 'WL: Related Product', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-related';
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
        return ['related','product','related product'];
    }

    protected function register_controls() {


        // Related Product Content
        $this->start_controls_section(
            'product_related_content',
            [
                'label' => __( 'Related Product', 'woolentor' ),
            ]
        );
            $this->add_control(
                'posts_per_page',
                [
                    'label' => __( 'Products Per Page', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 4,
                    'range' => [
                        'px' => [
                            'max' => 20,
                        ],
                    ],
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
                        'date' => __( 'Date', 'woolentor' ),
                        'title' => __( 'Title', 'woolentor' ),
                        'price' => __( 'Price', 'woolentor' ),
                        'popularity' => __( 'Popularity', 'woolentor' ),
                        'rating' => __( 'Rating', 'woolentor' ),
                        'rand' => __( 'Random', 'woolentor' ),
                        'menu_order' => __( 'Menu Order', 'woolentor' ),
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
                        'asc' => __( 'ASC', 'woolentor' ),
                        'desc' => __( 'DESC', 'woolentor' ),
                    ],
                ]
            );

            $this->add_control(
                'show_heading',
                [
                    'label' => __( 'Heading', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __( 'Hide', 'woolentor' ),
                    'label_on' => __( 'Show', 'woolentor' ),
                    'default' => 'yes',
                    'return_value' => 'yes',
                    'prefix_class' => 'wlshow-heading-',
                ]
            );

        $this->end_controls_section();

        // Product Style
        $this->start_controls_section(
            'related_heading_style_section',
            array(
                'label' => __( 'Heading', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'related_heading_color',
                [
                    'label'     => __( 'Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-wl-product-related .products > h2' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'related_heading_typography',
                    'label'     => __( 'Typography', 'woolentor' ),
                    'selector'  => '{{WRAPPER}}.elementor-widget-wl-product-related .products > h2',
                )
            );

            $this->add_responsive_control(
                'related_heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-wl-product-related .products > h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'related_heading_align',
                [
                    'label'        => __( 'Alignment', 'woolentor' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}}.elementor-widget-wl-product-related .products > h2'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();

        global $product;
        $product = wc_get_product();

        if( woolentor_is_preview_mode() ){
            echo \WooLentor_Default_Data::instance()->default( $this->get_name(), $settings );
        } else{
            if ( ! $product ) { return; }
            $args = [
                'posts_per_page' => 4,
                'columns' => 4,
                'orderby' => $settings['orderby'],
                'order' => $settings['order'],
            ];
            if ( ! empty( $settings['posts_per_page'] ) ) {
                $args['posts_per_page'] = $settings['posts_per_page'];
            }
            if ( ! empty( $settings['columns'] ) ) {
                $args['columns'] = $settings['columns'];
            }

            // Get related Product
            $args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), 
                $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
            $args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

            wc_get_template( 'single-product/related.php', $args );
        }

    }

}
