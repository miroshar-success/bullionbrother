<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Product_Tags_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-single-product-tags';
    }

    public function get_title() {
        return __( 'WL: Product Tags', 'woolentor' );
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
        return ['product info','product tag','tags'];
    }

    protected function register_controls() {

        // Style
        $this->start_controls_section(
            'product_tags_style_section',
            array(
                'label' => __( 'Tags', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tags_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woolentor_product_tags_info',
                ]
            );

            $this->add_responsive_control(
                'tags_align',
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
                'hide_product_tags_title',
                [
                    'label'     => __( 'Hide Title', 'woolentor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_tags_info .tags-title' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'tags_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_product_tags_info .tags-title' => 'color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'hide_product_tags_title!'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'tags_value_color',
                [
                    'label' => __( 'Value Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_product_tags_info .tagged_as' => 'color: {{VALUE}}',
                        '.woocommerce {{WRAPPER}} .woolentor_product_tags_info .tagged_as a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'tags_value_hover_color',
                [
                    'label' => __( 'Value Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_product_tags_info .tagged_as a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'tag_spacing',
                [
                    'label' => esc_html__( 'Spacing', 'woolentor' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 5,
                        ],
                    ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_product_tags_info .tagged_as a:first-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'tags_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_tags_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

            if( has_term( '', 'product_tag', $product->get_id() ) ) {
                echo '<div class="woolentor_product_tags_info">';
                    ?>
                        <span class="tags-title"><?php echo sprintf( _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woolentor' ) ); ?></span>
                        <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">', '</span>' ); ?>
                    <?php
                    do_action( 'woocommerce_product_meta_end' );
                echo '</div>';
            }
        }
        

    }

}