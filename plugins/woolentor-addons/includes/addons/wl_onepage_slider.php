<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Onepage_Slider_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-onepage-slider';
    }

    public function get_title() {
        return __( 'WL: One page slider', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-slider-video';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['slick','elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-slider','woolentor-widgets'];
    }

    public function get_script_depends() {
        return ['one-page-nav','woolentor-widgets-scripts'];
    }

    public function get_keywords(){
        return ['slider','onepage slider','fullpage','fullslider'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Slider', 'woolentor' ),
            ]
        );
            
            $repeater = new Repeater();

            $repeater->add_control(
                'slider_image',
                [
                    'label' => esc_html__( 'Image', 'woolentor' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $repeater->start_controls_tabs('slider_item_tabs');
                
                $repeater->start_controls_tab(
                    'content_tab',
                    [
                        'label' => esc_html__( 'Content', 'woolentor' ),
                    ]
                );
                    
                    $repeater->add_control(
                        'slider_title',
                        [
                            'label' => esc_html__( 'Title', 'woolentor' ),
                            'type' => Controls_Manager::TEXT,
                            'placeholder' => esc_html__( 'Type your title here', 'woolentor' ),
                            'label_block'=>true,
                        ]
                    );

                    $repeater->add_control(
                        'slider_subtitle',
                        [
                            'label' => esc_html__( 'Sub Title', 'woolentor' ),
                            'type' => Controls_Manager::TEXT,
                            'placeholder' => esc_html__( 'Type your sub title here', 'woolentor' ),
                            'label_block'=>true,
                        ]
                    );

                    $repeater->add_control(
                        'slider_buttontxt',
                        [
                            'label' => esc_html__( 'Button Text', 'woolentor' ),
                            'type' => Controls_Manager::TEXT,
                            'placeholder' => esc_html__( 'Type your button text here', 'woolentor' ),
                            'label_block'=>true,
                        ]
                    );

                    $repeater->add_control(
                        'slider_buttonlink',
                        [
                            'label' => esc_html__( 'Button Link', 'woolentor' ),
                            'type' => Controls_Manager::TEXT,
                            'placeholder' => esc_html__( 'Type your button link here', 'woolentor' ),
                            'label_block'=>true,
                        ]
                    );

                    $repeater->add_control(
                        'show_video_btn',
                        [
                            'label' => esc_html__( 'Video Button', 'woolentor' ),
                            'type' => Controls_Manager::SWITCHER,
                        ]
                    );

                    $repeater->add_control(
                        'video_link',
                        [
                            'label' => esc_html__( 'Video Link', 'woolentor' ),
                            'type' => Controls_Manager::TEXT,
                            'label_block'=>true,
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'video_icon',
                        [
                            'label' => esc_html__( 'Video Icon', 'woolentor' ),
                            'type' => Controls_Manager::ICONS,
                            'default' => [
                                'value' => 'fas fa-play',
                                'library' => 'solid',
                            ],
                            'fa4compatibility' => 'videoicon',
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                $repeater->end_controls_tab();
                
                // Slider Item Style
                $repeater->start_controls_tab(
                    'style_tab',
                    [
                        'label' => esc_html__( 'Style', 'woolentor' ),
                    ]
                );
                    
                    // Area Style
                    $repeater->add_control(
                        'ind_area_heading',
                        [
                            'label' => esc_html__( 'Area', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );

                    $repeater->add_responsive_control(
                        'ind_content_align',
                        [
                            'label'   => esc_html__( 'Alignment', 'woolentor' ),
                            'type'    => Controls_Manager::CHOOSE,
                            'options' => [
                                'left'    => [
                                    'title' => esc_html__( 'Left', 'woolentor' ),
                                    'icon'  => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'woolentor' ),
                                    'icon'  => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'woolentor' ),
                                    'icon'  => 'eicon-text-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-full-slider-content' => 'text-align: {{VALUE}};',
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-video-content' => 'text-align: {{VALUE}};',
                            ],
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'int_content_background',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.ht-single-full-slider',
                            'exclude'=>['image'],
                            'fields_options'=>[
                                'background'=>[
                                    'label' => esc_html__( 'Area Background Type', 'woolentor' ),
                                ]
                            ]
                        ]
                    );

                    $repeater->add_control(
                        'ind_title_heading',
                        [
                            'label' => esc_html__( 'Title', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );

                    $repeater->add_control(
                        'ind_title_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-full-slider-content h1' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'int_title_typography',
                            'label' => esc_html__( 'Typography', 'woolentor' ),
                            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .ht-full-slider-content h1',
                        ]
                    );

                    $repeater->add_responsive_control(
                        'ind_title_margin',
                        [
                            'label' => esc_html__( 'Margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} {{WRAPPER}} {{CURRENT_ITEM}} .ht-full-slider-content h1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Sub Title
                    $repeater->add_control(
                        'ind_sub_title_heading',
                        [
                            'label' => esc_html__( 'Sub Title', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );

                    $repeater->add_control(
                        'ind_sub_title_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-full-slider-content h2' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'int_subtitle_typography',
                            'label' => esc_html__( 'Typography', 'woolentor' ),
                            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .ht-full-slider-content h2',
                        ]
                    );

                    $repeater->add_responsive_control(
                        'ind_subtitle_margin',
                        [
                            'label' => esc_html__( 'Margin', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} {{WRAPPER}} {{CURRENT_ITEM}} .ht-full-slider-content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'ind_button_heading',
                        [
                            'label' => esc_html__( 'Button', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                        ]
                    );

                    $repeater->add_responsive_control(
                        'ind_button_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-btn-style a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'ind_button_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-btn-style a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'ind_button_hover_color',
                        [
                            'label' => esc_html__( 'Hover Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-btn-style a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'int_button_typography',
                            'label' => esc_html__( 'Typography', 'woolentor' ),
                            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .ht-btn-style a',
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'int_button_background',
                            'label' => __( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .ht-btn-style a',
                            'exclude'=>['image'],
                            'fields_options'=>[
                                'background'=>[
                                    'label' => esc_html__( 'Background Type', 'woolentor' ),
                                ]
                            ]
                        ]
                    );

                    $repeater->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'int_button_hover_background',
                            'label' => __( 'Hover Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .ht-btn-style a:hover,{{WRAPPER}} {{CURRENT_ITEM}} .ht-btn-style a::after',
                            'exclude'=>['image'],
                            'fields_options'=>[
                                'background'=>[
                                    'label' => esc_html__( 'Hover Background Type', 'woolentor' ),
                                ]
                            ]
                        ]
                    );

                    $repeater->add_control(
                        'ind_play_button_heading',
                        [
                            'label' => esc_html__( 'Video Button', 'woolentor' ),
                            'type' => Controls_Manager::HEADING,
                            'separator' => 'before',
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'ind_play_button_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-video-content .ht-video-icon a' => 'color: {{VALUE}};border-color:{{VALUE}};',
                            ],
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'ind_play_button_hover_color',
                        [
                            'label' => esc_html__( 'Hover Color', 'woolentor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-video-content .ht-video-icon a:hover' => 'color: {{VALUE}};border-color:{{VALUE}};',
                            ],
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                    $repeater->add_responsive_control(
                        'ind_play_button_size',
                        [
                            'label' => esc_html__( 'Font Size', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-video-content .ht-video-icon a' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                    $repeater->add_responsive_control(
                        'ind_play_button_width',
                        [
                            'label' => esc_html__( 'Width', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-video-content .ht-video-icon a' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                    $repeater->add_responsive_control(
                        'ind_play_button_height',
                        [
                            'label' => esc_html__( 'Height', 'woolentor' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} {{CURRENT_ITEM}} .ht-video-content .ht-video-icon a' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition'=>[
                                'show_video_btn'=>'yes',
                            ],
                        ]
                    );

                $repeater->end_controls_tab();

            $repeater->end_controls_tabs();

            

            $this->add_control(
                'slider_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'slider_title' => esc_html__( 'Boho Dreams', 'woolentor' ),
                            'slider_subtitle' => esc_html__( 'Ruffled Poplin Dress', 'woolentor' ),
                            'slider_buttontxt' => esc_html__( 'Shop now', 'woolentor' ),
                            'slider_buttonlink' => esc_html__( '#', 'woolentor' ),
                        ],
                        [
                            'slider_title' => 'ready to wear<br/>clothing made for a<br/>true contemporary woman',
                            'slider_buttontxt' => esc_html__( 'Shop now', 'woolentor' ),
                            'slider_buttonlink' => esc_html__( '#', 'woolentor' ),
                        ],
                        [
                            'slider_title' => esc_html__( 'Zippers cotton jogger', 'woolentor' ),
                            'slider_buttontxt' => esc_html__( 'Shop now', 'woolentor' ),
                            'slider_buttonlink' => esc_html__( '#', 'woolentor' ),
                        ],
                    ],
                    'title_field' => '{{{ slider_title }}}',
                ]
            );

        $this->end_controls_section();

        // Pagination style tab start
        $this->start_controls_section(
            'slider_pagination_style',
            [
                'label'     => esc_html__( 'Pagination', 'woolentor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
           
            $this->add_responsive_control(
                'pagination_width',
                [
                    'label' => esc_html__( 'Width', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-full-slider-area .ht-slider-pagination ul li a' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
           
            $this->add_responsive_control(
                'pagination_height',
                [
                    'label' => esc_html__( 'Height', 'woolentor' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-full-slider-area .ht-slider-pagination ul li a' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'pagination_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .ht-full-slider-area .ht-slider-pagination ul li a',
                ]
            );

            $this->add_responsive_control(
                'pagination_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-full-slider-area .ht-slider-pagination ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pagination_background',
                    'label' => esc_html__( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-full-slider-area .ht-slider-pagination ul li:not(.current) a',
                    'exclude'=>['image'],
                    'fields_options'=>[
                        'background'=>[
                            'label' => esc_html__( 'Background Type', 'woolentor' ),
                        ]
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'pagination_hover_background',
                    'label' => esc_html__( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-full-slider-area .ht-slider-pagination ul li a:hover,{{WRAPPER}} .ht-full-slider-area .ht-slider-pagination ul li.current a',
                    'exclude'=>['image'],
                    'fields_options'=>[
                        'background'=>[
                            'label' => esc_html__( 'Hover Background Type', 'woolentor' ),
                        ]
                    ]
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        $slider_list = $this->get_settings_for_display('slider_list');
        $id = $this->get_id();

        if( is_array( $slider_list ) ){
        ?>
            <div class="ht-full-slider-area">

                <div class="ht-slider-pagination">
                    <ul id="ht-nav">
                        <?php
                            $counter = 0;
                            foreach ( $slider_list as $slider ){
                                $counter++;
                                $class = ( 1 == $counter ) ? 'current' : '';
                                echo sprintf('<li class="%2$s"><a href="#%1$s">%3$s</a></li>', $id.$slider['_id'], $class, $slider['_id'] );
                            }                
                        ?>
                    </ul>
                </div>

                <?php
                    $bg_color = 0;
                    foreach ( $slider_list as $slider ):
                        $bg_color++;
                        $image_url = !empty( $slider['slider_image']['id'] ) ? 'background-image:url('.$slider['slider_image']['url'].')' : '';

                        $bg_color = ( $bg_color > 5 ) ? 1 : $bg_color;
                ?>
                    <div id="<?php echo esc_attr( $id.$slider['_id'] ); ?>" class="ht-single-full-slider ht-full-slider-bg-color-<?php echo $bg_color; ?> ht-slider-align-items-center ht-jarallax-img ht-parallax-active elementor-repeater-item-<?php echo $slider['_id']; ?>" style="<?php echo esc_attr( $image_url ); ?>">
                        <div class="ht-container">
                            <div class="<?php echo ( 'yes' === $slider['show_video_btn'] ) ? 'ht-video-content' : 'ht-full-slider-content'; ?>">
                                <?php
                                    if( 'yes' === $slider['show_video_btn'] ){
                                        $pl_icon = !empty( $slider['video_icon']['value'] ) ? woolentor_render_icon( $slider,'video_icon', 'videoicon' ) : '<i class="fas fa-play"></i>';
                                        $pl_btn = sprintf( '<a class="ht-video-popup" href="%1$s">%2$s</a>', $slider['video_link'], $pl_icon );
                                        echo sprintf( '<div class="ht-video-icon wow fadeInUp" data-wow-delay="%1$s">%2$s</div>','0.5s', $pl_btn );

                                        echo sprintf('<h3 class="wow fadeInUp" data-wow-delay="%1$s">%2$s</h3>','.7s', $slider['slider_subtitle']);

                                    }else{
                                        echo sprintf('<h2 class="wow fadeInUp" data-wow-delay="%1$s">%2$s</h2>','.5s', $slider['slider_subtitle']);
                                        echo sprintf('<h1 class="wow fadeInUp" data-wow-delay="%1$s">%2$s</h1>','.7s', $slider['slider_title']);
                                        if( !empty( $slider['slider_buttontxt'] ) ){
                                            echo sprintf( '<div class="ht-btn-style wow fadeInUp" data-wow-delay="%1$s"><a href="%2$s">%3$s</a></div>','.9s',$slider['slider_buttonlink'], $slider['slider_buttontxt'] );
                                        }
                                    }
                                ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php
        }

    }

}