<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Breadcrumbs_Widget extends Widget_Base {

    public function get_name() {
        return 'woolentor-breadcrumbs-addons';
    }
    
    public function get_title() {
        return __( 'WL: Breadcrumbs', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-product-breadcrumbs';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
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
        return [ 'Breadcrumbs' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'breadcrumbs-conent',
            [
                'label' => __( 'Breadcrumbs', 'woolentor' ),
            ]
        );
        
            $this->add_control(
                'breadcrumbs_icon',
                [
                    'label'   => esc_html__('Separator Icon', 'woolentor'),
                    'type'    => Controls_Manager::ICONS,
                    'fa4compatibility' => 'breadcrumbsicon',
                    'default' => [
                        'value'   => 'fas fa-angle-right',
                        'library' => 'fa-solid',
                    ],
                ]
            );

        $this->end_controls_section();

        // Slider Button stle
        $this->start_controls_section(
            'breadcrumbs-style-section',
            [
                'label' => esc_html__( 'Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'breadcrumbs_align',
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
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-breadcrumb' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => esc_html__( 'Text Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-breadcrumb .woocommerce-breadcrumb' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'link_color',
                [
                    'label' => esc_html__( 'Link Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-breadcrumb .woocommerce-breadcrumb a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'link_hover_color',
                [
                    'label' => esc_html__( 'Link Hover Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-breadcrumb .woocommerce-breadcrumb a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_spacing',
                [
                    'label' => esc_html__( 'Space', 'woolentor' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'default' => [
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-breadcrumb span.breadcrumb-separator' => 'margin:0 {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'icon_color',
                [
                    'label' => esc_html__( 'Icon Color', 'woolentor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-breadcrumb .woocommerce-breadcrumb span.breadcrumb-separator i' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'icon_size',
                [
                    'label' => esc_html__( 'Icon Size', 'woolentor' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                        ],
                    ],
                    'default' => [
                        'size' => 16,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-breadcrumb .woocommerce-breadcrumb span.breadcrumb-separator' => 'font-size:{{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor-breadcrumb .woocommerce-breadcrumb span.breadcrumb-separator svg' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Tab option end

    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();
        
        $args = [
            'delimiter'   => !empty( $settings['breadcrumbs_icon']['value'] ) ? '<span class="breadcrumb-separator">'.woolentor_render_icon( $settings, 'breadcrumbs_icon', 'breadcrumbsicon' ).'</span>' : '<span class="breadcrumb-separator">&nbsp;&#47;&nbsp;</span>',
            'wrap_before' => '<nav class="woocommerce-breadcrumb">',
            'wrap_after'  => '</nav>'
        ];

        echo '<div class="woolentor-breadcrumb">';
            woocommerce_breadcrumb( $args );
        echo '</div>';

    }

}