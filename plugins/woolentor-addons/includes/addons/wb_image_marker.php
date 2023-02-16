<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Image_Marker_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-image-marker';
    }

    public function get_title() {
        return __( 'WL: Image Marker', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-post';
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
        return ['image marker','marker','product indicator'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'image_marker_image_section',
            [
                'label' => __( 'Image', 'woolentor' ),
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'marker_bg_background',
                    'label' => __( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient', 'video' ],
                    'selector' => '{{WRAPPER}} .wlb-marker-wrapper',
                ]
            );

            $this->add_control(
                'marker_bg_opacity_color',
                [
                    'label' => __( 'Opacity Color', 'woolentor' ),
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper:before' => 'background-color: {{VALUE}}',
                    ],
                    'condition'=>[
                        'marker_bg_background_image[id]!'=>'',
                    ]
                ]
            );

        $this->end_controls_section(); // Marker Image Content section

        // Marker Content section
        $this->start_controls_section(
            'image_marker_content_section',
            [
                'label' => __( 'Marker', 'woolentor' ),
            ]
        );
            $this->add_control(
                'marker_style',
                [
                    'label'   => __( 'Style', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'woolentor' ),
                        '2'   => __( 'Style Two', 'woolentor' ),
                        '3'   => __( 'Style Three', 'woolentor' ),
                        '4'   => __( 'Style Four', 'woolentor' ),
                        '5'   => __( 'Style Five', 'woolentor' ),
                    ],
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'marker_title',
                [
                    'label'   => __( 'Marker Title', 'woolentor' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Marker #1', 'woolentor' ),
                ]
            );

            $repeater->add_control(
                'marker_content',
                [
                    'label'   => __( 'Marker Content', 'woolentor' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __( 'Lorem ipsum pisaci volupt atem accusa saes ntisdumtiu loperm asaerks.', 'woolentor' ),
                ]
            );

            $repeater->add_control(
                'marker_x_position',
                [
                    'label' => __( 'X Position', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 66,
                        'unit' => '%',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $repeater->add_control(
                'marker_y_position',
                [
                    'label' => __( 'Y Position', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 15,
                        'unit' => '%',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'image_marker_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'marker_title' => __( 'Marker #1', 'woolentor' ),
                            'marker_content' => __( 'Lorem ipsum pisaci volupt atem accusa saes ntisdumtiu loperm asaerks.','woolentor' ),
                            'marker_x_position' => [
                                'size' => 66,
                                'unit' => '%',
                            ],
                            'marker_y_position' => [
                                'size' => 15,
                                'unit' => '%',
                            ]
                        ]
                    ],
                    'title_field' => '{{{ marker_title }}}',
                ]
            );

        $this->end_controls_section();

        // Style Marker tab section
        $this->start_controls_section(
            'image_marker_style_section',
            [
                'label' => __( 'Marker', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'image_marker_color',
                [
                    'label'     => __( 'Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer::before' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_marker_background',
                    'label' => __( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_marker_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer',
                ]
            );

            $this->add_responsive_control(
                'image_marker_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_marker_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // End Marker style tab

        // Style Marker tab section
        $this->start_controls_section(
            'image_marker_content_style_section',
            [
                'label' => __( 'Content', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'image_marker_content_area_background',
                    'label' => __( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_marker_content_area_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box',
                ]
            );

            $this->add_responsive_control(
                'image_marker_content_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'image_marker_content_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('image_marker_content_style_tabs');
                
                // Style Title Tab start
                $this->start_controls_tab(
                    'style_title_tab',
                    [
                        'label' => __( 'Title', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'image_marker_title_color',
                        [
                            'label'     => __( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box h4' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'image_marker_title_typography',
                            'selector' => '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box h4',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'image_marker_title_border',
                            'label' => __( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box h4',
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_title_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box h4' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_title_margin',
                        [
                            'label' => __( 'Margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Style Title Tab end
                
                // Style Description Tab start
                $this->start_controls_tab(
                    'style_description_tab',
                    [
                        'label' => __( 'Description', 'woolentor' ),
                    ]
                );
                    
                    $this->add_control(
                        'image_marker_description_color',
                        [
                            'label'     => __( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box p' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'image_marker_description_typography',
                            'selector' => '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box p',
                        ]
                    );

                    $this->add_responsive_control(
                        'image_marker_description_margin',
                        [
                            'label' => __( 'Margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .wlb-marker-wrapper .wlb_image_pointer .wlb_pointer_box p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Style Description Tab end

            $this->end_controls_tabs();

        $this->end_controls_section(); // End Content style tab

    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $this->add_render_attribute( 'wl_image_marker_attr', 'class', 'wlb-marker-wrapper' );
        $this->add_render_attribute( 'wl_image_marker_attr', 'class', 'wlb-marker-style-'.$settings['marker_style'] );
       
        ?>
            <div <?php echo $this->get_render_attribute_string('wl_image_marker_attr'); ?> >

                <?php
                    foreach ( $settings['image_marker_list'] as $item ):
                    ?>
                        <div class="wlb_image_pointer elementor-repeater-item-<?php echo esc_attr( $item['_id'] );?>">
                            <div class="wlb_pointer_box">
                                <?php
                                    if( !empty( $item['marker_title'] ) ){
                                        echo '<h4>'.esc_html__( $item['marker_title'], 'woolentor' ).'</h4>';
                                    }
                                    if( !empty( $item['marker_content'] ) ){
                                        echo '<p>'.esc_html__( $item['marker_content'], 'woolentor' ).'</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                ?>
                    
            </div>
        <?php

    }

}