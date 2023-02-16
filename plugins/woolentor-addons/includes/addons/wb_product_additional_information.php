<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Additional_Information_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-additional-information';
    }

    public function get_title() {
        return __( 'WL: Product Additional Information', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-info';
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
        return ['additional','information','attributes'];
    }

    protected function register_controls() {


        // Slider Button stle
        $this->start_controls_section(
            'addition_info_content',
            [
                'label' => __( 'Heading', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'wl_show_heading',
                [
                    'label' => __( 'Heading', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'woolentor' ),
                    'label_off' => __( 'Hide', 'woolentor' ),
                    'render_type' => 'ui',
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'prefix_class' => 'wl-show-heading-',
                ]
            );

        $this->end_controls_section();

        // Heading Style
        $this->start_controls_section(
            'heading_style_section',
            array(
                'label' => __( 'Heading', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'heading_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} h2' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'wl_show_heading!' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} h2',
                    'condition' => [
                        'wl_show_heading!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'content_style_section',
            array(
                'label' => __( 'Content', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'content_color',
                [
                    'label' => __( 'Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .shop_attributes' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .shop_attributes',
                ]
            ); 

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        if( woolentor_is_preview_mode() ){
            echo \WooLentor_Default_Data::instance()->default( $this->get_name() );
        } else{
            global $product;
            $product = wc_get_product();
            if ( empty( $product ) ) {
                return;
            }
            wc_get_template( 'single-product/tabs/additional-information.php' );
        }

    }

}
