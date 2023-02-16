<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Sku_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-single-product-sku';
    }

    public function get_title() {
        return __( 'WL: Product SKU', 'woolentor' );
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
        return ['product info','product sku','sku info'];
    }

    protected function register_controls() {

        // Style
        $this->start_controls_section(
            'product_sku_style_section',
            array(
                'label' => __( 'SKU', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'sku_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woolentor_product_sku_info span',
                ]
            );

            $this->add_responsive_control(
                'sku_align',
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

            $this->add_control(
                'hide_product_sku_title',
                [
                    'label'     => __( 'Hide Title', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_sku_info .sku-title' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'sku_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_product_sku_info .sku-title' => 'color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'hide_product_sku_title!'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'sku_value_color',
                [
                    'label' => __( 'Value Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_product_sku_info .sku' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'sku_spacing',
                [
                    'label' => esc_html__( 'Spacing', 'woolentor' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 5,
                        ],
                    ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_product_sku_info .sku' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'sku_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_sku_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        global $product;
        $product = wc_get_product();
        
        if( woolentor_is_preview_mode() ){
            echo \WooLentor_Default_Data::instance()->default( $this->get_name() );
        } else{
            if ( empty( $product ) ) { return; }

            echo '<div class="woolentor_product_sku_info">';
	            do_action( 'woocommerce_product_meta_start' );
                if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
                    <span class="sku-title"><?php esc_html_e('SKU:', 'woolentor'); ?></span>
                    <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woolentor' ); ?></span>      
                <?php endif;
            echo '</div>';

        }
        

    }

}
