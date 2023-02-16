<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email image widget.
 */
class Woolentor_Wl_Email_Image_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-image';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Image', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-image';
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
        return [ 'email', 'image', 'photo', 'visual' ];
    }

    /**
     * Register image widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_image',
            [
                'label' => esc_html__( 'Image', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'woolentor-pro' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => false,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
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
                    '{{WRAPPER}} .woolentor-email-image-wrapper, {{WRAPPER}} .woolentor-email-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => esc_html__( 'Link', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'woolentor-pro' ),
                    'file' => esc_html__( 'Media File', 'woolentor-pro' ),
                    'custom' => esc_html__( 'Custom URL', 'woolentor-pro' ),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'woolentor-pro' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'woolentor-pro' ),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Image', 'woolentor-pro' ),
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
                    '{{WRAPPER}} .woolentor-email-image img' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-image img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-image img' => 'opacity: {{SIZE}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-image',
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
                    '{{WRAPPER}} .woolentor-email-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-image-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-image-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-image-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $image = isset( $settings['image'] ) ? $settings['image'] : array();
        $image_url = isset( $image['url'] ) ? $image['url'] : '';

        if ( empty( $image_url ) ) {
            return;
        }

        $link = isset( $settings['link'] ) ? $settings['link'] : array();
        $link_to = isset( $settings['link_to'] ) ? $settings['link_to'] : '';

        $link_url = isset( $link['url'] ) ? $link['url'] : '';
        $link_url = ( ( 'file' === $link_to ) ? $image_url : $link_url );

        if ( ! empty( $link_url ) ) {
            if ( 'custom' === $link_to ) {
                $this->add_link_attributes( 'link_atts', $link );
            } else {
                $this->add_link_attributes( 'link_atts', array( 'url' => $link_url ) );
            }
        }

        $link_atts = $this->get_render_attribute_string( 'link_atts' );
        $link_atts .= ! empty( $link_atts ) ? ' data-elementor-open-lightbox="no"' : '';

        $output = '';

        if ( ! empty( $link_atts ) ) {
            $output = sprintf( '<div class="woolentor-email-image"><a %2$s><img src="%1$s" alt=""></a></div>', $image_url, $link_atts );
        } else {
            $output = sprintf( '<div class="woolentor-email-image"><img src="%1$s" alt=""></div>', $image_url );
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-image-wrapper">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }
}
