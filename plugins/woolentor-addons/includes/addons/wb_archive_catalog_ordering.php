<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Archive_Catalog_Ordering_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-archive-catalog-ordering';
    }

    public function get_title() {
        return __( 'WL: Archive Catalog Ordering', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-filter';
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
        return ['archive catalog ordering','shorting','ordering'];
    }

    protected function register_controls() {

        // Style
        $this->start_controls_section(
            'archive_ordering_style_section',
            array(
                'label' => __( 'Ordering', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'ordering_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woolentor_archive_catalog_ordering .woocommerce-ordering select',
                ]
            );

            $this->add_control(
                'ordering_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_archive_catalog_ordering .woocommerce-ordering select' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'ordering_background_color',
                [
                    'label' => __( 'Background Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_archive_catalog_ordering .woocommerce-ordering select' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'ordering_height',
                [
                    'label'     => esc_html__('Height (px)', 'woolentor'),
                    'type'      => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'default'   => [
                        'size' => 40,
                    ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_archive_catalog_ordering .woocommerce-ordering select' => 'height: {{SIZE}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'ordering_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woolentor_archive_catalog_ordering .woocommerce-ordering select',
                ]
            );

            $this->add_responsive_control(
                'ordering_order_radius',
                [
                    'label' => __( 'Order Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_archive_catalog_ordering .woocommerce-ordering select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'ordering_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_archive_catalog_ordering' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        if( woolentor_is_preview_mode() ){
            echo '<div class="woolentor_archive_catalog_ordering">';
                woolentor_product_shorting('menu_order');
            echo '</div>';
        } else{
            echo '<div class="woolentor_archive_catalog_ordering">';
                woocommerce_catalog_ordering();
            echo '</div>';
        }
        

    }

}