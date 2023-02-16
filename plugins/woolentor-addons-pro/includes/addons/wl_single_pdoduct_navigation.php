<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Single_Pdoduct_Navigation_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-single-product-navigation';
    }

    public function get_title() {
        return __( 'WL:Single Product Navigation', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-post-navigation';
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
        return ['single product navigation','navigation'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_thumbnails_content',
            array(
                'label' => __( 'Navigation', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );
            $this->add_control(
                'pgination_icon_position',
                [
                    'label'   => __( 'Icon Position', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-left',
                        ],

                        'space-between' => [
                            'title' => __( 'Space between', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-stretch',
                        ],

                        'flex-end' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                    ],
                    'default'     => 'left',
                    'toggle'      => false,
                    'selectors' => [
                        '{{WRAPPER}} .wl-single-product-navigation' => 'justify-content: {{VALUE}} ;',
                    ],
                ]
            );

            $this->add_control(
                'in_same_category',
                [
                    'label' => __( 'In Same Category', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'product_icon_right',
                [
                    'label'       => esc_html__( 'Icon Next', 'woolentor-pro' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-long-arrow-alt-right',
                        'library' => 'solid',
                    ],
                    'label_block' => true,
                    'fa4compatibility' => 'buttonicon'
                ]
            );

            $this->add_control(
                'product_icon_left',
                [
                    'label'       => esc_html__( 'Icon Prev', 'woolentor-pro' ),
                    'type'        => Controls_Manager::ICONS,
                    'default' => [
                        'value'=>'fas fa-long-arrow-alt-left',
                        'library' => 'solid',
                    ],
                    'label_block' => true,
                    'fa4compatibility' => 'buttonicon'
                ]
            );

        $this->end_controls_section();

        // Product Style
        $this->start_controls_section(
            'product_pagination_style_section',
            array(
                'label' => __( 'Pagination Icon', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs('tabslider_dots_style_tabs');
 
                $this->start_controls_tab(
                    'tabslider_dots_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'product_pagination__color',
                        [
                            'label'     => __( 'Icon Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a i' => 'color: {{VALUE}} ;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_background_color',
                        [
                            'label'     => __( 'Icon Background Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a' => 'background: {{VALUE}} ;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'porduct_pagination_height',
                        [
                            'label' => esc_html__( 'Height', 'woolentor-pro' ),
                            'type' => Controls_Manager::NUMBER,
                            'min' => 20,
                            'max' => 100,
                            'step' => 1,
                            'default' => 35,
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a' => 'height: {{VALUE}}px ;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'porduct_pagination_width',
                        [
                            'label' => esc_html__( 'Width', 'woolentor-pro' ),
                            'type' => Controls_Manager::NUMBER,
                            'min' => 20,
                            'max' => 100,
                            'step' => 1,
                            'default' => 35,
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a' => 'width: {{VALUE}}px ;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                         Group_Control_Typography::get_type(),
                        [
                            'name' => 'navigation_icon_typography',
                            'selector' => '{{WRAPPER}} .wl-single-product-navigation a i',
                        ]
                    );

                    $this->add_responsive_control(
                        'porduct_pagination_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Active Style
                $this->start_controls_tab(
                    'tabslider_dots_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'product_pagination_hover_color',
                        [
                            'label'     => __( 'Icon Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a:hover > i' => 'color: {{VALUE}} ;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_pagination_background_hover_color',
                        [
                            'label'     => __( 'Icon Background Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a:hover' => 'background: {{VALUE}} ;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'porduct_pagination_border_radius_hover',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wl-single-product-navigation a:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
    }


    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();

        if( Plugin::instance()->editor->is_edit_mode() ){
            $product = wc_get_product( woolentor_get_last_product_id() );
        } else{
            global $product, $post;
        }

        if ( empty( $product ) ) { return; }

        $next_icon = !empty( $settings['product_icon_right']['value'] ) ? woolentor_render_icon( $settings, 'product_icon_right', 'buttonicon' ) : '<i class="fas fa-long-arrow-alt-right"></i>';
        $prev_icon = !empty( $settings['product_icon_left']['value'] ) ? woolentor_render_icon( $settings, 'product_icon_left', 'buttonicon' ) : '<i class="fas fa-long-arrow-alt-left"></i>';

        if( Plugin::instance()->editor->is_edit_mode() ){
            ?>
                <div class="wl-single-product-navigation">
                    <a href="#"><?php echo $prev_icon; ?></a>
                    <a href="#"><?php echo $next_icon; ?></a>
                </div>
            <?php
        }else{
            if('yes' === $settings['in_same_category']){
                $previous = get_adjacent_post( true, '', true, 'product_cat' );
                $next     = get_adjacent_post( true, '', false, 'product_cat' );
            }else{
                $previous = get_adjacent_post( false, '', true );
                $next     = get_adjacent_post( false, '', false );
            }

            if ( $previous) {
                $previous_post = wc_get_product( $previous->ID );
                if ( $previous_post && $previous_post->is_visible() ) {
                    $previous_product = $previous_post->get_permalink();
                }else{
                    $previous_product = woolentor_get_previous_next_product(true);
                }
            }else{
                $previous_product = '';
            }

            if($next){
                $next_post = wc_get_product( $next->ID );
                if ( $next_post && $next_post->is_visible() ) {
                    $next_product = $next_post->get_permalink();
                }else{
                    $next_product = woolentor_get_previous_next_product();
                }
            }else{
                $next_product = '';
            }

            ?>
            <div class="wl-single-product-navigation">
                <?php if($next_product): ?>
                    <a href="<?php echo esc_url( $next_product ); ?>"><?php echo $prev_icon; ?></a>
                <?php endif; ?>
                <?php if($previous_product): ?>
                    <a href="<?php echo esc_url( $previous_product ); ?>"><?php echo $next_icon; ?></a>
                <?php endif; ?>
            </div>
            <?php
        }
    }

}