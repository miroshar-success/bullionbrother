<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Title_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-single-product-title';
    }

    public function get_title() {
        return __( 'WL: Product title', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-title';
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
        return ['product title','product','title'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_title_content',
            [
                'label' => __( 'Product Title', 'woolentor' ),
            ]
        );
            $this->add_control(
                'product_title_html_tag',
                [
                    'label'   => __( 'Title HTML Tag', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => woolentor_html_tag_lists(),
                    'default' => 'h2',
                ]
            );

        $this->end_controls_section();

        // Product Style
        $this->start_controls_section(
            'product_style_section',
            array(
                'label' => __( 'Product Title', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'product_title_color',
                [
                    'label'     => __( 'Title Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .product_title' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'product_title_typography',
                    'label'     => __( 'Typography', 'woolentor' ),
                    'selector'  => '{{WRAPPER}} .product_title',
                )
            );

            $this->add_responsive_control(
                'product_title_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .product_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'product_title_align',
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
                    'prefix_class' => 'elementor-align-%s',
                    'default'      => 'left',
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $title_html_tag = woolentor_validate_html_tag( $settings['product_title_html_tag'] );

        if( woolentor_is_preview_mode() ){
            $title = get_the_title( woolentor_get_last_product_id() );
            echo sprintf( "<%s class='product_title entry-title'>%s</%s>", $title_html_tag, $title, $title_html_tag );
        }else{
            echo sprintf( "<%s class='product_title entry-title'>%s</%s>", $title_html_tag, get_the_title(), $title_html_tag  );
        }

    }

}
