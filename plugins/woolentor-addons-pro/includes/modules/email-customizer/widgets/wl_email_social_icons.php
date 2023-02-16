<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email social icons widget.
 */
class Woolentor_Wl_Email_Social_Icons_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-social-icons';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Social Icons', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-social-icons';
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
        return [ 'email', 'social', 'icon', 'link' ];
    }

    /**
     * Register heading widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_social_icons',
            [
                'label' => esc_html__( 'Social Icons', 'woolentor-pro' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'media',
            [
                'label' => esc_html__( 'Media', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'facebook'    => esc_html__( 'Facebook', 'woolentor-pro' ),
                    'twitter'     => esc_html__( 'Twitter', 'woolentor-pro' ),
                    'linkedin'    => esc_html__( 'Linkedin', 'woolentor-pro' ),
                    'youtube'     => esc_html__( 'Youtube', 'woolentor-pro' ),
                    'instagram'   => esc_html__( 'Instagram', 'woolentor-pro' ),
                    'tumblr'      => esc_html__( 'Tumblr', 'woolentor-pro' ),
                    'dribbble'    => esc_html__( 'Dribbble', 'woolentor-pro' ),
                    'vimeo'       => esc_html__( 'Vimeo', 'woolentor-pro' ),
                    'digg'        => esc_html__( 'Digg', 'woolentor-pro' ),
                    'stumbleupon' => esc_html__( 'Stumbleupon', 'woolentor-pro' ),
                    'vk'          => esc_html__( 'VK', 'woolentor-pro' ),
                    'pinterest'   => esc_html__( 'Pinterest', 'woolentor-pro' ),
                    'whatsapp'    => esc_html__( 'Whatsapp', 'woolentor-pro' ),
                    'rss'         => esc_html__( 'RSS', 'woolentor-pro' ),
                    'link'        => esc_html__( 'Link', 'woolentor-pro' ),
                ],
                'default' => 'facebook',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'name' => 'link',
                'label' => esc_html__( 'Link', 'woolentor-pro' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'woolentor-pro' ),
            ],
        );

        $this->add_control(
            'social_icons_items',
            [
                'label' => esc_html__( 'Menu Items', 'woolentor-pro' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'media' => esc_html__( 'facebook', 'woolentor-pro' ),
                        'link' => [
                            'url' => 'https://facebook.com',
                        ],
                    ],
                    [
                        'media' => esc_html__( 'twitter', 'woolentor-pro' ),
                        'link' => [
                            'url' => 'https://twitter.com',
                        ],
                    ],
                    [
                        'media' => esc_html__( 'linkedin', 'woolentor-pro' ),
                        'link' => [
                            'url' => 'https://linkedin.com',
                        ],
                    ],
                ],
                'title_field' => '{{{ media }}}',
            ]
        );

        $this->add_control(
            'social_icons_type',
            [
                'label' => esc_html__( 'Type', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'brand' => esc_html__( 'Brand', 'woolentor-pro' ),
                    'brand-outline' => esc_html__( 'Brand - Outline', 'woolentor-pro' ),
                    'brand-solid' => esc_html__( 'Brand - Solid', 'woolentor-pro' ),
                    'black' => esc_html__( 'Black', 'woolentor-pro' ),
                    'black-outline' => esc_html__( 'Black - Outline', 'woolentor-pro' ),
                    'white' => esc_html__( 'White', 'woolentor-pro' ),
                    'white-outline' => esc_html__( 'White - Outline', 'woolentor-pro' ),
                ],
                'separator' => 'before',
                'default' => 'brand-solid',
            ]
        );

        $this->add_control(
            'social_icons_view',
            [
                'label' => esc_html__( 'View', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__( 'Horizontal', 'woolentor-pro' ),
                    'vertical' => esc_html__( 'Vertical', 'woolentor-pro' ),
                ],
                'default' => 'horizontal',
            ]
        );

        $this->add_control(
            'social_icons_align',
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
                    '{{WRAPPER}} .woolentor-email-social-icons' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();

        $this->start_controls_section(
            'section_social_icons_style',
            [
                'label' => esc_html__( 'Social Icons', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'social_icons_border',
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
                'selector' => '{{WRAPPER}} .woolentor-email-social-icons ul',
            ]
        );

        $this->add_control(
            'social_icons_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'social_icons_background',
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
                'selector' => '{{WRAPPER}} .woolentor-email-social-icons ul',
            ]
        );

        $this->add_control(
            'social_icons_margin',
            [
                'label' => esc_html__( 'Margin (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icons_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_social_icons_item_style',
            [
                'label' => esc_html__( 'Social Icons Item', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'social_icons_item_border',
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
                'selector' => '{{WRAPPER}} .woolentor-email-social-icons ul li a',
            ]
        );

        $this->add_control(
            'social_icons_item_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'social_icons_item_background',
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
                'selector' => '{{WRAPPER}} .woolentor-email-social-icons ul li a',
            ]
        );

        $this->add_control(
            'social_icons_item_icon_width',
            [
                'label' => esc_html__( 'Icon Width (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 45,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons ul li img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icons_item_horizontal_padding',
            [
                'label' => esc_html__( 'Horizontal Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons ul li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icons_item_vertical_padding',
            [
                'label' => esc_html__( 'Vertical Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons ul li' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'social_icons_item_space_between',
            [
                'label' => esc_html__( 'Space Between (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-social-icons-horizontal ul li + li' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woolentor-email-social-icons-vertical ul li + li' => 'margin-top: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-social-icons-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-social-icons-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-social-icons-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-social-icons-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-social-icons-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
     * Render social icons widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! woolentor_email_widget_conditions( $settings ) ) {
            return;
        }

        $social_icons_items = isset( $settings['social_icons_items'] ) ? $settings['social_icons_items'] : array();

        if ( ! is_array( $social_icons_items ) || empty( $social_icons_items ) ) {
            return;
        }

        $social_icons_type = isset( $settings['social_icons_type'] ) ? $settings['social_icons_type'] : '';
        $social_icons_view = isset( $settings['social_icons_view'] ) ? $settings['social_icons_view'] : '';
        $social_icons_align = isset( $settings['social_icons_align'] ) ? $settings['social_icons_align'] : '';

        $output = '';

        $item_count = 1;

        foreach ( $social_icons_items as $social_icons_item ) {
            $media = isset( $social_icons_item['media'] ) ? $social_icons_item['media'] : '';

            if ( empty( $media ) ) {
                continue;
            }

            $icon = WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/images/social-icons/' . $social_icons_type . '/' . $media . '.png';

            $link = isset( $social_icons_item['link'] ) ? $social_icons_item['link'] : array();
            $link_url = isset( $link['url'] ) ? $link['url'] : '';

            if ( ! empty( $link_url ) ) {
                $this->add_link_attributes( 'link_atts_' . $item_count, $link );
            }

            $link_atts = $this->get_render_attribute_string( 'link_atts_' . $item_count );
            $link_atts .= ! empty( $link_atts ) ? ' data-elementor-open-lightbox="no"' : '';

            if ( ! empty( $link_atts ) ) {
                $output .= sprintf( '<li><a %2$s><img src="%1$s" alt=""></a></li>', $icon, $link_atts );
            } else {
                $output .= sprintf( '<li><span><img src="%1$s" alt=""></span></li>', $icon );
            }

            $item_count++;
        }

        if ( ! empty( $output ) ) {
            $class = 'woolentor-email-social-icons';
            $class .= ! empty( $social_icons_view ) ? ' woolentor-email-social-icons-' . $social_icons_view : '';
            $class .= ! empty( $social_icons_align ) ? ' woolentor-email-social-icons-' . $social_icons_align : '';

            $output = '<div class="' . $class . '"><ul>' . $output . '</ul></div>';
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-social-icons-wrapper">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }
}
