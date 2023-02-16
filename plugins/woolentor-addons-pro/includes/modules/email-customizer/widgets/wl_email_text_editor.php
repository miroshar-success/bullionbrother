<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email text editor widget.
 */
class Woolentor_Wl_Email_Text_Editor_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-text-editor';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Text Editor', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-text';
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
        return [ 'email', 'text', 'editor' ];
    }

    /**
     * Register heading widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_text_editor',
            [
                'label' => esc_html__( 'Text Editor', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'editor',
            [
                'label' => '',
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => false,
                ],
                'default' => '<p>' . esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ) . '</p>',
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();
        $this->controls_for_placeholders();

        $this->start_controls_section(
            'section_text_editor_style',
            [
                'label' => esc_html__( 'Text Editor', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .woolentor-email-text-editor-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_editor_color',
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
                    '{{WRAPPER}} .woolentor-email-text-editor' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor h1' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor h2' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor h3' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor h4' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor h5' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor h6' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor pre' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor ul' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-text-editor ol' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_editor',
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
                'selector' => '{{WRAPPER}} .woolentor-email-text-editor, {{WRAPPER}} .woolentor-email-text-editor p, {{WRAPPER}} .woolentor-email-text-editor ul, {{WRAPPER}} .woolentor-email-text-editor ol',
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
                'selector' => '{{WRAPPER}} .woolentor-email-text-editor-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-text-editor-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-text-editor-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-text-editor-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-text-editor-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $editor = isset( $settings['editor'] ) ? $settings['editor'] : '';

        if ( 0 === strlen( $editor ) ) {
            return;
        }

        $output = sprintf( '<div class="woolentor-email-text-editor">%1$s</div>', $editor );

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-text-editor-wrapper">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }
}
