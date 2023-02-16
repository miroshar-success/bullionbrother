<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Myaccount_Download_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-myaccount-download';
    }

    public function get_title() {
        return __( 'WL: My Account Download', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-download-button';
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
        return ['my account page','account page','my account download','download'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            array(
                'label' => __( 'My Account Download', 'woolentor-pro' ),
            )
        );

            $this->add_control(
                'html_notice',
                array(
                    'label' => __( 'Element Information', 'woolentor-pro' ),
                    'show_label' => false,
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __( 'My Account Download', 'woolentor-pro' ),
                )
            );

        $this->end_controls_section();

    }

    protected function render() {
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            do_action('woocommerce_account_downloads_endpoint');
        }else{
            if ( is_account_page() ) {
                do_action('woocommerce_account_downloads_endpoint');
            }
        }
    }

}