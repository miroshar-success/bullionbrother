<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Wc_Multicurrency_Widget extends Widget_Base {

    public function get_name() {
        return 'wb-multi-currency';
    }

    public function get_title() {
        return __( 'WL: Multi Currency', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
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

    public function get_script_depends(){
        return [];
    }

    public function get_keywords(){
        return ['multi currency','currency','woocommerce currency'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'multi_currency_content',
            [
                'label' => __( 'MultiCurrency', 'woolentor' ),
            ]
        );
            $this->add_control(
                'multi_currency_style',
                [
                    'label' => __( 'Style', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'all',
                    'options' => [
                        'all'      => esc_html__( 'All', 'woolentor' ),
                        'flagonly' => esc_html__( 'Flag only', 'woolentor' ),
                    ],
                ]
            );

        $this->end_controls_section();

        // Heading Style
        $this->start_controls_section(
            'heading_style_section',
            [
                'label' => __( 'Heading', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'heading_color',
                [
                    'label' => __( 'Heading Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-mcs-sidebar-widget-list ul li a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .ht-mcs-sidebar-widget-list ul li a',
                ]
            );
            
        $this->end_controls_section();


    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $short_code_attributes = [
            'style' => $settings['multi_currency_style'],
        ];
        echo woolentor_do_shortcode( 'WCMC', $short_code_attributes );
    }

}