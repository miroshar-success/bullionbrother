<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email heading widget.
 */
class Woolentor_Wl_Email_Heading_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-heading';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Heading', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-t-letter';
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
        return [ 'email', 'heading', 'title', 'text' ];
    }

    /**
     * Register heading widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_heading',
            [
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'Enter your heading', 'woolentor-pro' ),
                'default' => esc_html__( 'Add Your Heading Text Here', 'woolentor-pro' ),
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
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => esc_html__( 'HTML Tag', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
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
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-heading-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();
        $this->controls_for_placeholders();

        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Text Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-heading, {{WRAPPER}} .woolentor-email-heading a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading',
                'global' => [
                    'active' => false,
                ],
                'exclude' => [ 'font_family', 'word_spacing' ],
                'fields_options' => [
                    'font_size' => [
                        'label' => esc_html__( 'Size (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                        'default' => [
                            'unit' => 'px',
                        ],
                    ],
                    'line_height' => [
                        'label' => esc_html__( 'Line-Height (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                        'default' => [
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'label' => esc_html__( 'Letter Spacing (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                    ],
                ],
                'selector' => '{{WRAPPER}} .woolentor-email-heading',
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
                'selector' => '{{WRAPPER}} .woolentor-email-heading-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-heading-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-heading-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-heading-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-heading-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
     * Controls for placeholders.
     */
    public function controls_for_placeholders() {
        $contexts = array();
        $email_type = woolentor_email_get_type();

        if ( ( 'customer_new_account' === $email_type ) || ( 'customer_reset_password' === $email_type ) ) {
            $contexts = array( 'common', 'user', 'woocommerce' );
        }

        $this->start_controls_section(
            'section_placeholders',
            [
                'label' => esc_html__( 'Placeholders', 'woolentor-pro' ),
            ]
        );

        $this->control_for_no_order_found_notice( 2 );

        $this->add_control(
            'placeholders_html',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => woolentor_email_get_placeholders_list_as_html( $contexts, $email_type ),
                'content_classes' => 'woolentor-email-placeholder',
                'label_block' => true,
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
     * Render heading widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! woolentor_email_widget_conditions( $settings ) ) {
            return;
        }

        $heading = isset( $settings['heading'] ) ? $settings['heading'] : '';

        if ( 0 === strlen( $heading ) ) {
            return;
        }

        $link = isset( $settings['link'] ) ? $settings['link'] : array();
        $link_url = isset( $link['url'] ) ? $link['url'] : '';

        $html_tag = isset( $settings['html_tag'] ) ? $settings['html_tag'] : '';
        $html_tag = ! empty( $html_tag ) ? $html_tag : 'h2';

        if ( ! empty( $link_url ) ) {
            $this->add_link_attributes( 'link_atts', $link );
        }

        $link_atts = $this->get_render_attribute_string( 'link_atts' );
        $link_atts .= ! empty( $link_atts ) ? ' data-elementor-open-lightbox="no"' : '';

        $output = '';

        if ( ! empty( $link_atts ) ) {
            $output = sprintf( '<%3$s class="woolentor-email-heading"><a %2$s>%1$s</a></%3$s>', $heading, $link_atts, $html_tag );
        } else {
            $output = sprintf( '<%2$s class="woolentor-email-heading">%1$s</%2$s>', $heading, $html_tag );
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-heading-wrapper">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }
}
