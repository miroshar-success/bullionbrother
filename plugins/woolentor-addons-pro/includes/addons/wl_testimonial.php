<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Testimonial_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-testimonial';
    }

    public function get_title() {
        return __( 'WL: Testimonial', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-comments';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-testimonial','woolentor-widgets'];
    }

    public function get_script_depends() {
        return ['slick','woolentor-widgets-scripts'];
    }

    public function get_keywords(){
        return ['woolentor','review','testimonial','product review','customer review','client say'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'testimonial_content',
            [
                'label' => __( 'Testimonial', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'testimonial_layout',
                [
                    'label' => __( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'woolentor-pro' ),
                        '2'   => __( 'Style Two', 'woolentor-pro' ),
                        '3'   => __( 'Style Three', 'woolentor-pro' ),
                        '4'   => __( 'Style Four', 'woolentor-pro' ),
                        '5'   => __( 'Style Five', 'woolentor-pro' ),
                        '6'   => __( 'Style Six', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'testimonial_type',
                [
                    'label' => __( 'Review Type', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'      => __( 'Custom', 'woolentor-pro' ),
                        'allproduct'  => __( 'All Products', 'woolentor-pro' ),
                        'productwise' => __( 'Single Product', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'product_id',
                [
                    'label' => __( 'Select Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_post_name( 'product' ),
                    'condition' => [
                        'testimonial_type' => 'productwise',
                    ]
                ]
            );

            $this->add_control(
                'limit',
                [
                    'label' => __( 'Limit', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'condition' =>[
                        'testimonial_type' => 'allproduct',
                    ]
                ]
            );

            $this->add_control(
                'offset',
                [
                    'label' => __( 'Offset', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'condition' =>[
                        'testimonial_type' => 'allproduct',
                    ]
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'client_name',
                [
                    'label'   => __( 'Name', 'woolentor-pro' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Anna Miller','woolentor-pro'),
                ]
            );

            $repeater->add_control(
                'client_designation',
                [
                    'label'   => __( 'Designation', 'woolentor-pro' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Designer','woolentor-pro'),
                ]
            );

            $repeater->add_control(
                'client_rating',
                [
                    'label' => __( 'Client Rating', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                ]
            );

            $repeater->add_control(
                'client_image',
                [
                    'label' => __( 'Image', 'woolentor-pro' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'client_imagesize',
                    'default' => 'full',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'client_say',
                [
                    'label'   => __( 'Client Say', 'woolentor-pro' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __('“ Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, laboris consequat. ”','woolentor-pro'),
                ]
            );

            $this->add_control(
                'testimonial_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'condition'=>[
                        'testimonial_type' => 'custom',
                    ],
                    'fields'  => $repeater->get_controls(),
                    'default' => [

                        [
                            'client_name' => __('Anna Miller','woolentor-pro'),
                            'client_designation' => __( 'Designer','woolentor-pro' ),
                            'client_say' => __( '“ Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, laboris consequat. ”', 'woolentor-pro' ),
                        ],

                        [
                            'client_name' => __('Kevin Walker','woolentor-pro'),
                            'client_designation' => __( 'Developer','woolentor-pro' ),
                            'client_say' => __( '“ Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit ”', 'woolentor-pro' ),
                        ],

                        [
                            'client_name' => __('Ruth Pierce','woolentor-pro'),
                            'client_designation' => __( 'Customer','woolentor-pro' ),
                            'client_say' => __( '“ Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, laboris consequat. ”', 'woolentor-pro' ),
                        ],
                    ],
                    'title_field' => '{{{ client_name }}}',
                ]
            );

            $this->add_control(
                'slider_on',
                [
                    'label' => __( 'Slider On', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator'=>'before',
                ]
            );

            $this->add_control(
                'show_image',
                [
                    'label' => __( 'Show Thumbnail', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'woolentor-pro' ),
                    'label_off' => __( 'Hide', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'testimonial_type' => ['productwise','allproduct'],
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_width',
                [
                    'label' => __( 'Thumbnail Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap .ht-client-info img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'testimonial_type' => ['productwise','allproduct'],
                        'show_image'=>'yes',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_width_2',
                [
                    'label' => __( 'Thumbnail Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-testimonial-thumb' => 'flex:0 0 {{SIZE}}{{UNIT}};',
                    ],
                    'condition'=>[
                        'testimonial_type' => 'custom',
                        'testimonial_layout'=>['5','6'],
                    ],
                ]
            );

        $this->end_controls_section();

        // Column
        $this->start_controls_section(
            'section_column_option',
            [
                'label' => __( 'Columns', 'woolentor-pro' ),
                'condition'=>[
                    'slider_on!'=>'yes',
                ]
            ]
        );
            
            $this->add_responsive_control(
                'column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1' => esc_html__( 'One', 'woolentor-pro' ),
                        '2' => esc_html__( 'Two', 'woolentor-pro' ),
                        '3' => esc_html__( 'Three', 'woolentor-pro' ),
                        '4' => esc_html__( 'Four', 'woolentor-pro' ),
                        '5' => esc_html__( 'Five', 'woolentor-pro' ),
                        '6' => esc_html__( 'Six', 'woolentor-pro' ),
                        '7' => esc_html__( 'Seven', 'woolentor-pro' ),
                        '8' => esc_html__( 'Eight', 'woolentor-pro' ),
                        '9' => esc_html__( 'Nine', 'woolentor-pro' ),
                        '10'=> esc_html__( 'Ten', 'woolentor-pro' ),
                    ],
                    'label_block' => true,
                    'prefix_class' => 'wl-columns%s-',
                ]
            );

            $this->add_control(
                'no_gutters',
                [
                    'label' => esc_html__( 'No Gutters', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_responsive_control(
                'item_space',
                [
                    'label' => esc_html__( 'Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 15,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'padding: 0  {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_bottom_space',
                [
                    'label' => esc_html__( 'Bottom Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 30,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Slider Option
        $this->start_controls_section(
            'section_slider_option',
            [
                'label' => esc_html__( 'Slider Option', 'woolentor-pro' ),
                'condition'=>[
                    'slider_on'=>'yes',
                ]
            ]
        );
            
            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'woolentor-pro'),
                    'label_on' => __('Yes', 'woolentor-pro'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider autoplay', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to the tablet.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to mobile.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                ]
            );

        $this->end_controls_section();

        // Style style start
        $this->start_controls_section(
            'testimonial_area_style',
            [
                'label'     => __( 'Item', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-single-testimonial-wrap',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_border_box_shadow',
                    'label' => __( 'Box Shadow', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-single-testimonial-wrap',
                ]
            );

            $this->add_responsive_control(
                'item_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'item_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style image style start
        $this->start_controls_section(
            'testimonial_image_style',
            [
                'label'     => __( 'Image', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'testimonial_image_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] img,{{WRAPPER}} .woolentor-testimonial-thumb img',
                ]
            );

            $this->add_responsive_control(
                'testimonial_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        '{{WRAPPER}} .woolentor-testimonial-thumb img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_control(
                'testimonial_image_area_border_color',
                [
                    'label' => __( 'Image Area Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap .ht-client-info-wrap.ht-client-info-border' => 'border-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'testimonial_layout'=>'3',
                    ]
                ]
            );

        $this->end_controls_section(); // Style Testimonial image style end

        // Style Testimonial name style start
        $this->start_controls_section(
            'testimonial_name_style',
            [
                'label'     => __( 'Name', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'testimonial_name_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] h4' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"]:before' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .woolentor-testimonial-name' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'testimonial_name_typography',
                    'selector' => '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] h4,{{WRAPPER}} .woolentor-testimonial-name',
                ]
            );

            $this->add_responsive_control(
                'testimonial_name_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-testimonial-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'testimonial_name_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-testimonial-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial name style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'testimonial_designation_style',
            [
                'label'     => __( 'Designation', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
            $this->add_control(
                'testimonial_designation_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] span' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woolentor-testimonial-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'testimonial_designation_typography',
                    'selector' => '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] span, {{WRAPPER}} .woolentor-testimonial-designation',
                ]
            );

            $this->add_responsive_control(
                'testimonial_designation_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-testimonial-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'testimonial_designation_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-info"] span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-testimonial-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'testimonial_clientsay_style',
            [
                'label'     => __( 'Client say', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'testimonial_clientsay_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-content"] p' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .woolentor-testimonial-text' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'testimonial_clientsay_typography',
                    'selector' => '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-content"] p, {{WRAPPER}} .woolentor-testimonial-text',
                ]
            );

            $this->add_responsive_control(
                'testimonial_clientsay_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-content"] p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-testimonial-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'testimonial_clientsay_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap [class*="ht-client-content"] p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-testimonial-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'testimonial_clientrating_style',
            [
                'label'     => __( 'Rating', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'testimonial_clientrating_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap .ht-client-rating ul li i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'testimonial_clientrating_size',
                [
                    'label' => __( 'Font Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap .ht-client-rating ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'testimonial_clientrating_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-single-testimonial-wrap .ht-client-rating ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

        // Slider Button style
        $this->start_controls_section(
            'products-slider-controller-style',
            [
                'label' => esc_html__( 'Slider Controller Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

            $this->start_controls_tabs('product_sliderbtn_style_tabs');

                // Slider Button style Normal
                $this->start_controls_tab(
                    'product_sliderbtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'button_style_heading',
                        [
                            'label' => __( 'Navigation Arrow', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'nvigation_position',
                        [
                            'label' => __( 'Position', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_style_dots_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_responsive_control(
                            'dots_position',
                            [
                                'label' => __( 'Position', 'woolentor-pro' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px', '%' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 1000,
                                        'step' => 1,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}};',
                                ],
                            ]
                        );

                        $this->add_control(
                            'dots_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border',
                                'label' => __( 'Border', 'woolentor-pro' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Normal button style end

                // Button style Hover
                $this->start_controls_tab(
                    'product_sliderbtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'button_style_arrow_heading',
                        [
                            'label' => __( 'Navigation', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_bg_color',
                        [
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );


                    $this->add_control(
                        'button_style_dotshov_heading',
                        [
                            'label' => __( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_control(
                            'dots_hover_bg_color',
                            [
                                'label' => __( 'Background Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'background-color: {{VALUE}} !important;',
                                    '{{WRAPPER}} .product-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border_hover',
                                'label' => __( 'Border', 'woolentor-pro' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button:hover',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius_hover',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Hover button style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

        $this->start_controls_section(
            'global_font_typography_section',
            [
                'label' => __('Global Font Family', 'woolentor-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'global_font_typography',
                [
                    'label'       => __( 'Font Family', 'woolentor-pro' ),
                    'description' => __('Set a specific font family for this widget.', 'woolentor-pro'),
                    'type'        => Controls_Manager::FONT,
                    'default'     => '',
                    'selectors' => [
                        '{{WRAPPER}} :not(i)' => 'font-family: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings  = $this->get_settings_for_display();
        $column    = $this->get_settings_for_display('column');

        $collumval = 'wl-col-1';
        if( $column !='' ){
            $collumval = 'wl-col-'.$column;
        }

        // Generate review
        $testimonial_list = [];
        if( 'custom' === $settings['testimonial_type'] ){
            foreach ( $settings['testimonial_list'] as $testimonial ){
                $testimonial_list[] = array(
                    'image' => Group_Control_Image_Size::get_attachment_image_html( $testimonial, 'client_imagesize', 'client_image' ),
                    'name' => $testimonial['client_name'],
                    'designation' => $testimonial['client_designation'],
                    'ratting' => $testimonial['client_rating'],
                    'message' => $testimonial['client_say'],
                );
            }
        }else{

            if( $settings['testimonial_type'] == 'allproduct' ){
                
                $args = array(
                    'status'=> 'approve',
                    'type'  => 'review',
                );

                if( !empty( $settings['limit'] ) ){
                    $args['number'] = $settings['limit'];
                }

                if( !empty( $settings['offset'] ) ){
                    $args['offset'] = $settings['offset'];
                }

                // The Query
                $comments_query = new \WP_Comment_Query;
                $comments = $comments_query->query( $args );

            }else{
                $proid = $settings['product_id'];
                if( empty( $proid ) ){
                    echo esc_html__( 'Please select product.', 'woolentor-pro' );
                    return;
                }else{
                    $comments = get_comments( 'post_id=' . $proid );
                }
            }
            if ( !$comments ){
                echo esc_html__( 'No Review Available', 'woolentor-pro' );
                return;
            }
            foreach ( $comments as $comment ) {

                $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
                $user_id   = get_comment( $comment->comment_ID )->user_id;
                $user_info = get_userdata( $user_id );

                $testimonial_list[] = array(
                    'image' => ( $settings['show_image'] == 'yes' ? get_avatar( $comment, '150' ) : '' ),
                    'name' => get_comment_author( $comment ),
                    'designation' => ( !empty( $user_info->roles ) ? implode( ', ', $user_info->roles ): '' ) ,
                    'ratting' => $rating,
                    'message' => $comment->comment_content,
                );

            }

        }

        // Slider Options
        $slider_main_div_style = '';
        if( $settings['slider_on'] === 'yes' ){

            $is_rtl = is_rtl();
            $direction = $is_rtl ? 'rtl' : 'ltr';
            $slider_settings = [
                'arrows' => ('yes' === $settings['slarrows']),
                'dots' => ('yes' === $settings['sldots']),
                'autoplay' => ('yes' === $settings['slautolay']),
                'autoplay_speed' => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
                'rtl' => $is_rtl,
            ];

            $slider_responsive_settings = [
                'product_items' => $settings['slitems'],
                'scroll_columns' => $settings['slscroll_columns'],
                'tablet_width' => $settings['sltablet_width'],
                'tablet_display_columns' => $settings['sltablet_display_columns'],
                'tablet_scroll_columns' => $settings['sltablet_scroll_columns'],
                'mobile_width' => $settings['slmobile_width'],
                'mobile_display_columns' => $settings['slmobile_display_columns'],
                'mobile_scroll_columns' => $settings['slmobile_scroll_columns'],

            ];
            $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );
            $slider_main_div_style = "style='display:none'";
        }else{
            $slider_settings = '';
        }

        $this->add_render_attribute( 'area_attr', 'class', 'wl-row wlb-testimonial-style-'.$settings['testimonial_layout'] );

        if( $settings['no_gutters'] === 'yes' ){
            $this->add_render_attribute( 'area_attr', 'class', 'wlno-gutters' );
        }
        if( $settings['slider_on'] === 'yes' ){
            $this->add_render_attribute( 'area_attr', 'class', 'product-slider' );
            $this->add_render_attribute( 'area_attr', 'data-settings', wp_json_encode( $slider_settings ) );
        }


        echo '<div '.$this->get_render_attribute_string( 'area_attr' ).' '.$slider_main_div_style.'>';
            foreach ( $testimonial_list as $testimonial ): 
            ?>
                <div class="<?php echo esc_attr( $collumval ); ?>">
                    <div class="ht-single-testimonial-wrap">

                        <?php if( $settings['testimonial_layout'] === '1' ): ?>
                            <?php
                                if( !empty( $testimonial['message'] ) ){
                                    echo  sprintf( '<div class="ht-client-content ht-client-content-border"><p>%1$s</p>%2$s</div>', $testimonial['message'], $this->ratting( $testimonial['ratting'] ) );
                                }
                            ?>
                            <div class="ht-client-info">
                                <?php
                                    if( !empty( $testimonial['image'] ) ){
                                        echo $testimonial['image'];
                                    }

                                    if( !empty( $testimonial['name'] ) ){
                                        echo '<h4>'.$testimonial['name'].'</h4>';
                                    }

                                    if( !empty( $testimonial['designation'] ) ){
                                        echo '<span>'.$testimonial['designation'].'</span>';
                                    }
                                ?>
                            </div>

                        <?php elseif( $settings['testimonial_layout'] === '2' ): ?>
                            <div class="ht-client-info-wrap-2">
                                <?php
                                    if( !empty( $testimonial['image'] ) ){
                                        echo sprintf( '<div class="ht-client-img-2">%1$s</div>', $testimonial['image'] );
                                    }
                                ?>
                                <div class="ht-client-info-3">
                                    <?php
                                        if( !empty( $testimonial['name'] ) || !empty( $testimonial['designation'] ) ){
                                            echo sprintf('<h4>%1$s<span>%2$s</span></h4>', $testimonial['name'], $testimonial['designation'] );
                                        }
                                        if( !empty( $testimonial['ratting'] ) ){
                                            echo $this->ratting( $testimonial['ratting'] );
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                                if( !empty( $testimonial['message'] ) ){
                                    echo  sprintf( '<div class="ht-client-content"><p class="ht-width-dec">%1$s</p></div>', $testimonial['message'] );
                                }
                            ?>

                        <?php elseif( $settings['testimonial_layout'] === '3' ): ?>
                            <div class="ht-client-info-wrap ht-client-info-border">
                                <?php 
                                    if( !empty( $testimonial['image'] ) ){
                                        echo sprintf( '<div class="ht-client-img">%1$s</div>', $testimonial['image'] );
                                    }
                                ?>
                                <div class="ht-client-info-2">
                                    <?php
                                        if( !empty( $testimonial['name'] ) ){
                                            echo '<h4>'.$testimonial['name'].'</h4>';
                                        }

                                        if( !empty( $testimonial['designation'] ) ){
                                            echo '<span>'.$testimonial['designation'].'</span>';
                                        }

                                        if( !empty( $testimonial['ratting'] ) ){
                                            echo $this->ratting( $testimonial['ratting'] );
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                                if( !empty( $testimonial['message'] ) ){
                                    echo  sprintf( '<div class="ht-client-content"><p>%1$s</p></div>', $testimonial['message'] );
                                }
                            ?>

                        <?php elseif( $settings['testimonial_layout'] === '5' ): ?>
                            <div class="woolentor-testimonial-2">
                                <?php 
                                    if( !empty( $testimonial['image'] ) ){
                                        echo sprintf( '<div class="woolentor-testimonial-thumb">%1$s</div>', $testimonial['image'] );
                                    }
                                ?>
                                
                                <div class="woolentor-testimonial-content">
                                    <?php
                                        if( !empty( $testimonial['ratting'] ) ){
                                            echo $this->ratting( $testimonial['ratting'] );
                                        }

                                        if( !empty( $testimonial['message'] ) ){
                                            echo  sprintf( '<p class="woolentor-testimonial-text">%1$s</p>', $testimonial['message'] );
                                        }

                                        if( !empty( $testimonial['name'] ) ){
                                            echo '<h6 class="woolentor-testimonial-name">'.$testimonial['name'].'</h6>';
                                        }
    
                                        if( !empty( $testimonial['designation'] ) ){
                                            echo '<span class="woolentor-testimonial-designation">'.$testimonial['designation'].'</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php elseif( $settings['testimonial_layout'] === '6' ): ?>
                            <div class="woolentor-testimonial-2 <?php echo esc_attr($testimonial['image'] ? 'wl-has-thumb' : '') ?>">
                                <?php 
                                    if( !empty( $testimonial['image'] ) ){
                                        echo sprintf( '<div class="woolentor-testimonial-thumb">%1$s</div>', $testimonial['image'] );
                                    }
                                ?>
                                
                                <div class="woolentor-testimonial-content">
                                    <?php
                                        if( !empty( $testimonial['name'] ) ){
                                            echo '<h6 class="woolentor-testimonial-name">'.$testimonial['name'].'</h6>';
                                        }

                                        if( !empty( $testimonial['designation'] ) ){
                                            echo '<span class="woolentor-testimonial-designation">'.$testimonial['designation'].'</span>';
                                        }

                                        if( !empty( $testimonial['ratting'] ) ){
                                            echo $this->ratting( $testimonial['ratting'] );
                                        }

                                        if( !empty( $testimonial['message'] ) ){
                                            echo  sprintf( '<p class="woolentor-testimonial-text">%1$s</p>', $testimonial['message'] );
                                        }

                                    ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="ht-client-info-wrap-2">
                                <?php 
                                    if( !empty( $testimonial['image'] ) ){
                                        echo sprintf( '<div class="ht-client-img-2">%1$s</div>', $testimonial['image'] );
                                    }
                                ?>
                                <div class="ht-client-info-3">
                                    <?php
                                        if( !empty( $testimonial['name'] ) || !empty( $testimonial['designation'] ) ){
                                            echo sprintf('<h4>%1$s<span>%2$s</span></h4>', $testimonial['name'], $testimonial['designation'] );
                                        }

                                        if( !empty( $testimonial['ratting'] ) ){
                                            echo $this->ratting( $testimonial['ratting'] );
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                                if( !empty( $testimonial['message'] ) ){
                                    echo sprintf( '<div class="ht-client-content"><p>%1$s</p></div>', $testimonial['message'] );
                                }
                            ?>

                        <?php endif; ?>

                    </div>
                </div>
            <?php
            endforeach;
        echo '</div>';
        

    }

    public function ratting( $ratting_num ){
        if( !empty( $ratting_num ) ){
            $rating = $ratting_num;
            $rating_whole = floor( $ratting_num );
            $rating_fraction = $rating - $rating_whole;
            $ratting_html = '<div class="ht-client-rating"><ul>';
                for( $i = 1; $i <= 5; $i++ ){
                    if( $i <= $rating_whole ){
                        $ratting_html .= '<li><i class="fas fa-star"></i></li>';
                    } else {
                        if( $rating_fraction != 0 ){
                            $ratting_html .= '<li><i class="fas fa-star-half-alt"></i></li>';
                            $rating_fraction = 0;
                        } else {
                            $ratting_html .= '<li><i class="far fa-star"></i></li>';
                        }
                    }
                }
            $ratting_html .= '</ul></div>';

            return $ratting_html;
        }
    }

}