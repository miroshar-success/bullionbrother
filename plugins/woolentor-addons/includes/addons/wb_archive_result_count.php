<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Archive_Result_Count_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-archive-result-count';
    }

    public function get_title() {
        return __( 'WL: Archive Result Count', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-counter';
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
        return ['archive result count','resutl count','count'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'result_count_content',
            [
                'label' => __( 'Result Count', 'woolentor' ),
            ]
        );

            $this->add_control(
                'product_per_page',
                [
                    'label' => __( 'Product Per Page', 'woolentor' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 16,
                    'separator' => 'after'
                ]
            );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'archive_result_count_style_section',
            array(
                'label' => __( 'Result Count', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'result_count_typography',
                    'label' => __( 'Typography', 'woolentor' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woolentor_archive_result_count .woocommerce-result-count',
                ]
            );

            $this->add_responsive_control(
                'result_count_align',
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
                'result_count_color',
                [
                    'label' => __( 'Value Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor_archive_result_count .woocommerce-result-count' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'result_count_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_archive_result_count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        if( woolentor_is_preview_mode() ){
            echo '<div class="woolentor_archive_result_count">';
                $args = array(
                    'total'    => wp_count_posts( 'product' )->publish,
                    'per_page' => $settings['product_per_page'],
                    'current'  => 1,
                );
                wc_get_template( 'loop/result-count.php', $args );
            echo '</div>';
        } else{
            $total    = wc_get_loop_prop( 'total' );
            $par_page = !empty( $settings['product_per_page'] ) ? $settings['product_per_page'] : wc_get_loop_prop('per_page');
            echo '<div class="woolentor_archive_result_count">';
                $page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );
                woolentor_product_result_count( $total, $par_page, $page );
            echo '</div>';
        }
        

    }

}