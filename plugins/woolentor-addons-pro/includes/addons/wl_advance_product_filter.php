<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Advance_Product_Filter_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-advance-product-filter';
    }

    public function get_title() {
        return __( 'WL: Advance Product Filter', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-user-preferences';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [];
    }

    public function get_script_depends(){
        return [];
    }

    public function get_keywords(){
        return ['filter','product filter','advance product filter'];
    }

    protected function register_controls() {

        // Content
        $this->start_controls_section(
            'advance_product_filter_content',
            [
                'label' => __( 'Advance Product Filter', 'woolentor-pro' ),
            ]
        );
            $this->add_control(
                'type',
                [
                    'label'     => __( 'Type', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => array(
                        'filter' => __( 'Filter', 'woolentor-pro' ),
                        'group'  => __( 'Group', 'woolentor-pro' ),
                    ),
                    'default'   => 'filter',
                ]
            );
            $this->add_control(
                'filter_id',
                [
                    'label'     => __( 'Filter', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => $this->wlpf_get_filters_list(),
                    'condition' => [ 'type' => 'filter' ],
                    'default'   => '0',
                ]
            );
            $this->add_control(
                'group_id',
                [
                    'label'     => __( 'Group', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => $this->wlpf_get_groups_list(),
                    'condition' => [ 'type' => 'group' ],
                    'default'   => '0',
                ]
            );
            $this->add_control(
                'show_label',
                [
                    'label'     => __( 'Show label', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'default'   => 'yes',
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'terms' => [
                                    ['name' => 'type', 'operator' => '===', 'value' => 'filter'],
                                    ['name' => 'filter_id', 'operator' => '!==', 'value' => '0'],
                                ],
                            ],
                            [
                                'terms' => [
                                    ['name' => 'type', 'operator' => '===', 'value' => 'group'],
                                    ['name' => 'group_id', 'operator' => '!==', 'value' => '0'],
                                ],
                            ],
                        ],
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function wlpf_get_filters_list() {
        $filters_list = ( function_exists( 'wlpf_get_filters_list' ) ? wlpf_get_filters_list() : array() );
        $filters_list = array( '0' => esc_html__( 'Select Filter', 'woolentor-pro' ) ) + $filters_list;

        return $filters_list;
    }

    protected function wlpf_get_groups_list() {
        $groups_list = ( function_exists( 'wlpf_get_groups_list' ) ? wlpf_get_groups_list() : array() );
        $groups_list = array( '0' => esc_html__( 'Select Group', 'woolentor-pro' ) ) + $groups_list;

        return $groups_list;
    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();

        $type       = ( isset( $settings['type'] ) ? sanitize_key( $settings['type'] )    : '' );
        $filter_id  = ( isset( $settings['filter_id'] ) ? absint( $settings['filter_id'] ): 0 );
        $group_id   = ( isset( $settings['group_id'] ) ? absint( $settings['group_id'] )  : 0 );
        $show_label = ( isset( $settings['show_label'] ) ? rest_sanitize_boolean( $settings['show_label'] )  :  true );

        $shortcode_tag = '';
        $shortcode_atts = [];

        if ( ( 'filter' === $type ) && ! empty( $filter_id ) ) {
            $shortcode_tag = 'wlpf_filter';
            $shortcode_atts['id'] = $filter_id;
        } elseif ( ( 'group' === $type ) && ! empty( $group_id ) ) {
            $shortcode_tag = 'wlpf_group';
            $shortcode_atts['id'] = $group_id;
        }

        $shortcode_atts['show_label'] = $show_label;

        if ( ! empty( $shortcode_tag ) && ! empty( $shortcode_atts ) ) {
            echo woolentor_do_shortcode( $shortcode_tag, $shortcode_atts );
        }
    }

}