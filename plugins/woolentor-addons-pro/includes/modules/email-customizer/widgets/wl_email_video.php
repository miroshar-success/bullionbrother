<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email video widget.
 */
class Woolentor_Wl_Email_Video_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-video';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Video', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-youtube';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return [ 'woolentor-addons-pro' ];
    }

    /**
     * Get help URL.
     */
    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return [ 'email', 'video', 'player', 'embed', 'youtube', 'vimeo', 'dailymotion' ];
    }

    /**
     * Register video widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_video',
            [
                'label' => esc_html__( 'Video', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'video_type',
            [
                'label' => esc_html__( 'Source', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => esc_html__( 'YouTube', 'elementor' ),
                    'vimeo' => esc_html__( 'Vimeo', 'elementor' ),
                    'dailymotion' => esc_html__( 'Dailymotion', 'elementor' ),
                    'hosted' => esc_html__( 'Self Hosted', 'elementor' ),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'youtube_link',
            [
                'label' => esc_html__( 'Link', 'woolentor-pro' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'Enter your URL', 'elementor' ) . ' (YouTube)',
                'default' => [
                    'url' => 'https://www.youtube.com/watch?v=XHOmBV4js_E'
                ],
                'condition' => [
                    'video_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'vimeo_link',
            [
                'label' => esc_html__( 'Link', 'woolentor-pro' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'Enter your URL', 'elementor' ) . ' (Vimeo)',
                'default' => [
                    'url' => 'https://vimeo.com/235215203'
                ],
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->add_control(
            'dailymotion_link',
            [
                'label' => esc_html__( 'Link', 'woolentor-pro' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'Enter your URL', 'elementor' ) . ' (Vimeo)',
                'default' => [
                    'url' => 'https://www.dailymotion.com/video/x6tqhqb'
                ],
                'condition' => [
                    'video_type' => 'dailymotion',
                ],
            ]
        );

        $this->add_control(
            'hosted_link',
            [
                'label' => esc_html__( 'Link', 'woolentor-pro' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'Enter your URL', 'elementor' ),
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->add_control(
            'hosted_poster',
            [
                'label' => esc_html__( 'Poster', 'woolentor-pro' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => false,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->add_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'woolentor-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video-wrapper, {{WRAPPER}} .woolentor-email-video' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();

        $this->start_controls_section(
            'section_style_video',
            [
                'label' => esc_html__( 'Video', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'width',
            [
                'label' => esc_html__( 'Width (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => esc_html__( 'Height (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'opacity',
            [
                'label' => esc_html__( 'Opacity', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'fields_options' => [
                    'width' => [
                        'responsive' => false,
                    ],
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .woolentor-email-video',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_wrapper_style',
            [
                'label' => esc_html__( 'Wrapper', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'fields_options' => [
                    'width' => [
                        'responsive' => false,
                    ],
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .woolentor-email-video-wrapper',
            ]
        );

        $this->add_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'woolentor-pro' ),
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'color_b' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'image' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'position' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'attachment' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'repeat' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'size' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'bg_width' => [
                        'label' => esc_html__( 'Width (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px', '%' ],
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .woolentor-email-video-wrapper',
            ]
        );

        $this->add_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-video-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Controls for conditions.
     */
    public function controls_for_conditions() {
        $this->start_controls_section(
            'section_conditions',
            [
                'label' => esc_html__( 'Conditions', 'woolentor-pro' ),
            ]
        );

        $this->control_for_no_order_found_notice( 1 );

        $this->add_control(
            'conditions_order_status',
            [
                'label' => esc_html__( 'Order Status', 'woolentor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'woolentor-pro' ),
                'label_on' => esc_html__( 'On', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'conditions_order_statuses',
            [
                'label' => esc_html__( 'Order Statuses', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => woolentor_email_get_conditions_order_statuses(),
                'condition' => [
                    'conditions_order_status' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'conditions_payment_status',
            [
                'label' => esc_html__( 'Payment Status', 'woolentor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'woolentor-pro' ),
                'label_on' => esc_html__( 'On', 'woolentor-pro' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'conditions_payment_statuses',
            [
                'label' => esc_html__( 'Payment Statuses', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => woolentor_email_get_conditions_payment_statuses(),
                'condition' => [
                    'conditions_payment_status' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * No order found notice control.
     */
    public function control_for_no_order_found_notice( $serial = 1 ) {
        $order = woolentor_email_get_order();

        if ( ! is_object( $order ) || empty( $order ) ) {
            $this->add_control(
                'no_order_found_notice_html_' . $serial,
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => woolentor_email_no_order_found_notice_html(),
                    'content_classes' => 'woolentor-email-no-order-found-notice',
                    'separator' => 'after',
                ]
            );
        }
    }

    /**
     * Render image widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! woolentor_email_widget_conditions( $settings ) ) {
            return;
        }

        $video_type = ( ( $settings['video_type'] != '' ) ? $settings['video_type'] : 'youtube' );

        if ( empty( $video_type ) ) {
            return;
        }

        $video_link_url = '';
        $video_link_atts = '';
        $video_thumbnail = '';
        $video_play_icon = '';

        if ( 'youtube' === $video_type ) {
            $youtube_link = isset( $settings['youtube_link'] ) ? $settings['youtube_link'] : '';
            $youtube_link_url = isset( $youtube_link['url'] ) ? $youtube_link['url'] : '';

            if ( ! empty( $youtube_link_url ) ) {
                parse_str( parse_url( $youtube_link_url, PHP_URL_QUERY ), $youtube_link_url_params );

                $video_thumbnail = ( isset( $youtube_link_url_params['v'] ) ? 'https://img.youtube.com/vi/' . $youtube_link_url_params['v'] . '/hqdefault.jpg' : 'https://img.youtube.com/vi/XHOmBV4js_E/hqdefault.jpg' );
                $video_play_icon = WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/images/youtube-play-icon.png';
                $video_link_atts = $this->set_link_atts( $youtube_link );
                $video_link_url = $youtube_link_url;
            }
        } elseif ( 'vimeo' === $video_type ) {
            $vimeo_link = isset( $settings['vimeo_link'] ) ? $settings['vimeo_link'] : '';
            $vimeo_link_url = isset( $vimeo_link['url'] ) ? $vimeo_link['url'] : '';

            if ( ! empty( $vimeo_link_url ) ) {
                $vimeo_id = substr( $vimeo_link_url, 18, 9 );
                $vimeo_id = ( strlen( $vimeo_id ) == 9 ) ? $vimeo_id : '235215203';
                $vimeo_hash = @file_get_contents( 'http://vimeo.com/api/v2/video/' . $vimeo_id . '.php' );

                if ( $vimeo_hash ) {
                    $vimeo_hash = unserialize( $vimeo_hash );
                    $video_thumbnail = isset( $vimeo_hash[0]['thumbnail_large'] ) ? $vimeo_hash[0]['thumbnail_large'] : $vimeo_hash[0]['thumbnail'];
                    $video_play_icon = WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/images/vimeo-play-icon.png';
                    $video_link_atts = $this->set_link_atts( $vimeo_link );
                    $video_link_url = $vimeo_link_url;
                }
            }
        } elseif ( 'dailymotion' === $video_type ) {
            $dailymotion_link = isset( $settings['dailymotion_link'] ) ? $settings['dailymotion_link'] : '';
            $dailymotion_link_url = isset( $dailymotion_link['url'] ) ? $dailymotion_link['url'] : '';

            if ( ! empty( $dailymotion_link_url ) ) {
                $video_thumbnail = str_replace( 'https://www.dailymotion.com/video', 'https://www.dailymotion.com/thumbnail/video', $dailymotion_link_url );
                $video_play_icon = WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/images/dailymotion-play-icon.png';
                $video_link_atts = $this->set_link_atts( $dailymotion_link );
                $video_link_url = $dailymotion_link_url;
            }
        } elseif ( 'hosted' === $video_type ) {
            $hosted_link = isset( $settings['hosted_link'] ) ? $settings['hosted_link'] : '';
            $hosted_link_url = isset( $hosted_link['url'] ) ? $hosted_link['url'] : '';

            if ( ! empty( $hosted_link_url ) ) {
                $hosted_poster = isset( $settings['hosted_poster'] ) ? $settings['hosted_poster'] : '';
                $hosted_poster_id = isset( $hosted_poster['id'] ) ? $hosted_poster['id'] : 0;
                $hosted_poster_url = isset( $hosted_poster['url'] ) ? $hosted_poster['url'] : '';

                $video_thumbnail = ! empty( $hosted_poster_url ) ? $hosted_poster_url : '';
                $video_play_icon = WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/images/hosted-play-icon.png';
                $video_link_atts = $this->set_link_atts( $hosted_link );
                $video_link_url = $hosted_link_url;
            }
        }

        if ( empty( $video_link_url ) ) {
            return;
        }

        $video_thumbnail = esc_url( $video_thumbnail );
        $video_play_icon = esc_url( $video_play_icon );

        $output = sprintf( '<div class="woolentor-email-video" style="background-image:url(%3$s);"><a %2$s><img src="%1$s" alt=""></a></div>', $video_play_icon, $video_link_atts, $video_thumbnail );

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-video-wrapper">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }

    /**
     * Set link attributes.
     */
    protected function set_link_atts( $video_link = array() ) {
        $this->add_link_attributes( 'link_atts', $video_link );

        $link_atts = $this->get_render_attribute_string( 'link_atts' );
        $link_atts .= ! empty( $link_atts ) ? ' data-elementor-open-lightbox="no"' : '';

        return $link_atts;
    }
}
