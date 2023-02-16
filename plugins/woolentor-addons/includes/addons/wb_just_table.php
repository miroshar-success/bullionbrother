<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Just_Table_Widget extends Widget_Base {

    public function get_name() {
        return 'wb-just-table';
    }

    public function get_title() {
        return __( 'WL: JustTable', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-table';
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
        return ['table','product table','justtable'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'justtable_content',
            [
                'label' => __( 'JustTable', 'woolentor' ),
            ]
        );
            $this->add_control(
                'table_id',
                [
                    'label' => __( 'Select Table', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => woolentor_post_name('jt-product-table'),
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
                        '{{WRAPPER}} .jtpt-product-table th.jtpt-head-data' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .jtpt-product-table th.jtpt-head-data',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'heading_background',
                    'label' => __( 'Heading Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .jtpt-product-table th.jtpt-head-data',
                    'exclude' =>['image'],
                    'fields_options'=>[
                        'background'=>[
                            'label'=>__( 'Heading Background', 'woolentor' )
                        ]
                    ]
                ]
            );
            
        $this->end_controls_section();

        // Wrapper Style
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __( 'Table Content', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'table_wrapper_background',
                    'label' => __( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .jtpt-product-table-wrapper',
                    'exclude' =>['image'],
                    'fields_options'=>[
                        'background'=>[
                            'label'=>__( 'Wrapper Background', 'woolentor' )
                        ]
                    ]
                ]
            );

            $this->add_control(
                'table_td_border_color',
                [
                    'label' => __( 'Item Gap Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jtpt-product-table .jtpt-body-data' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {
        $settings   = $this->get_settings_for_display();

        $short_code_attributes = [
            'id' => $settings['table_id'],
        ];
        echo woolentor_do_shortcode( 'JT_Product_Table', $short_code_attributes );
    }

}
