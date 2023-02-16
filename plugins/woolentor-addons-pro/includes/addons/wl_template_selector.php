<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Template_Selector_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-template-selector';
    }

    public function get_title() {
        return __( 'WL: Template Selector', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-t-letter';
    }

    public function get_categories() {
        return [ 'woolentor-addons-pro' ];
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
        return ['template','template selector','selector'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'template_selector_content',
            [
                'label' => esc_html__( 'Template', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'template_id',
                [
                    'label' => __( 'Select Your template', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => woolentor_elementor_template(),
                ]
            );


        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( !empty( $settings['template_id'] )) {
            echo Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'] );
        }else{
            echo '<div class="wl_error">'.esc_html__( 'No selected template', 'woolentor-pro' ).'<div/>';
        }

    }

}