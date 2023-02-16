<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Wishsuite_Counter_Widget extends Widget_Base {

    public function get_name() {
        return 'wb-wishsuite-counter';
    }

    public function get_title() {
        return __( 'WL: WishSuite Counter', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-counter-circle';
    }

    public function get_categories() {
        return array( 'woolentor-addons' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_keywords(){
        return ['wishlist counter','product counter','wishsuite counter'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'wishsuite_content',
            [
                'label' => __( 'WishSuite Counter', 'woolentor' ),
            ]
        );

            $this->add_control(
                'counter_after_text',
                [
                    'label' => __( 'Text after "Wishlist" icon', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block'=>true,
                ]
            );

        $this->end_controls_section();

        // Counter Style
        $this->start_controls_section(
            'counter_style_section',
            [
                'label' => __( 'Styles', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'woolentor-tab-menu-align',
                [
                    'label' => __( 'Alignment', 'woolentor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor' ),
                            'icon' => 'eicon-text-align-right',
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    ],
                    'prefix_class'=> 'wishsuite-align-%s',
                    'default' => 'left',
                ]
            );

            $this->add_control(
                'counter_style_hedding',
                [
                    'label' => esc_html__( 'Counter', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'counter_color',
                [
                    'label' => __( 'Counter Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-counter-area span.wishsuite-counter' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'counter_background',
                    'label' => __( 'Counter Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wishsuite-counter-area span.wishsuite-counter',
                    'exclude' =>['image'],
                ]
            );

            $this->add_control(
                'counter_icon_style_hedding',
                [
                    'label' => esc_html__( 'Counter Icon', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'counter_icon_color',
                [
                    'label' => __( 'Counter Icon Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-counter-area span.wishsuite-counter-icon' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'counter_text_style_hedding',
                [
                    'label' => esc_html__( 'Text', 'woolentor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition'=>[
                        'counter_after_text!'=>''
                    ]
                ]
            );
            $this->add_control(
                'counter_text_color',
                [
                    'label' => __( 'Counter Text Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wishsuite-counter-area.wishsuite-has-text' => 'color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'counter_after_text!'=>''
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'counter_text_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wishsuite-counter-area.wishsuite-has-text',
                    'condition'=>[
                        'counter_after_text!'=>''
                    ]
                ]
            );
            
        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $short_code_attributes = [
            'text' => $settings['counter_after_text'],
        ];
        echo woolentor_do_shortcode( 'wishsuite_counter', $short_code_attributes );

    }

}