<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email downloads widget.
 */
class Woolentor_Wl_Email_Downloads_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-downloads';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Downloads', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-download-kit';
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
        return [ 'email', 'order', 'note', 'comments' ];
    }

    /**
     * Register downloads widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_downloads',
            [
                'label' => esc_html__( 'Downloads', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'woolentor-pro' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => '1',
            ]
        );

        $this->control_for_no_order_found_notice( 1 );

        $this->add_control(
            'downloads_heading',
            [
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'Downloads', 'woolentor-pro' ),
                'default' => esc_html__( 'Downloads', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'downloads_alt_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'When Downloads is not available', 'woolentor-pro' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'downloads_alt',
            [
                'label' => esc_html__( 'Show', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none'   => esc_html__( 'Nothing', 'woolentor-pro' ),
                    'custom' => esc_html__( 'Custom Text', 'woolentor-pro' ),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'downloads_custom',
            [
                'label' => esc_html__( 'Custom Text', 'woolentor-pro' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => false,
                ],
                'condition' => [
                    'downloads_alt' => 'custom',
                ],
                'placeholder' => esc_html__( 'Enter your custom text', 'woolentor-pro' ),
                'default' => esc_html__( 'Nothing to download!', 'woolentor-pro' ),
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();

        $this->start_controls_section(
            'section_downloads_heading_style',
            [
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->control_for_no_order_found_notice( 2 );

        $this->add_control(
            'downloads_heading_color',
            [
                'label' => esc_html__( 'Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'downloads_heading',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads-heading',
            ]
        );

        $this->add_control(
            'downloads_heading_align',
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
                    '{{WRAPPER}} .woolentor-email-downloads-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'downloads_heading_border',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads-heading',
            ]
        );

        $this->add_control(
            'downloads_heading_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'downloads_heading_background',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads-heading',
            ]
        );

        $this->add_control(
            'downloads_heading_margin',
            [
                'label' => esc_html__( 'Margin (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_heading_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_downloads_content_style',
            [
                'label' => esc_html__( 'Content', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->control_for_no_order_found_notice( 3 );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'downloads_content_border',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads-content',
            ]
        );

        $this->add_control(
            'downloads_content_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'downloads_content_background',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads-content',
            ]
        );

        $this->add_control(
            'downloads_content_margin',
            [
                'label' => esc_html__( 'Margin (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_content_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->controls_for_layout_1();

        $this->start_controls_section(
            'section_wrapper_style',
            [
                'label' => esc_html__( 'Wrapper', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->control_for_no_order_found_notice( 4 );

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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-downloads-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-downloads-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-downloads-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Controls for layout 1.
     */
    public function controls_for_layout_1() {
        $this->start_controls_section(
            'section_downloads_l1_table_style',
            [
                'label' => esc_html__( 'Table', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => '1',
                ],
            ]
        );

        $this->control_for_no_order_found_notice( 5 );

        $this->add_control(
            'downloads_l1_table_color',
            [
                'label' => esc_html__( 'Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'downloads_l1_table',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td',
            ]
        );

        $this->add_control(
            'downloads_l1_table_align',
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
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_l1_table_horizontal_padding',
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
                        'max' => 100,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_l1_table_vertical_padding',
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
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_l1_table_border_type',
            [
                'label' => esc_html__( 'Border Type', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'woolentor-pro' ),
                    'solid' => esc_html__( 'Solid', 'woolentor-pro' ),
                    'dotted' => esc_html__( 'Dotted', 'woolentor-pro' ),
                    'dashed' => esc_html__( 'Dashed', 'woolentor-pro' ),
                ],
                'separator' => 'before',
                'default' => 'solid',
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th + th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td + th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th + td, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td + td' => 'border-left-style: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody td' => 'border-top-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_l1_table_border_color',
            [
                'label' => esc_html__( 'Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th + th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td + th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th + td, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td + td' => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody td' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_l1_table_border_width',
            [
                'label' => esc_html__( 'Border Width (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th + th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td + th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table th + td, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table td + td' => 'border-left-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody td' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'downloads_l1_table_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'downloads_l1_table_background',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_downloads_l1_table_header_style',
            [
                'label' => esc_html__( 'Table Header', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => '1',
                ],
            ]
        );

        $this->control_for_no_order_found_notice( 6 );

        $this->add_control(
            'downloads_l1_table_header_color',
            [
                'label' => esc_html__( 'Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'downloads_l1_table_header',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead td',
            ]
        );

        $this->add_control(
            'downloads_l1_table_header_align',
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
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead td' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'downloads_l1_table_header_background',
                'label' => esc_html__( 'Background', 'woolentor-pro' ),
                'types' => [ 'classic' ],
                'exclude' => [ 'image' ],
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table thead td',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_downloads_l1_table_body_style',
            [
                'label' => esc_html__( 'Table Body', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout' => '1',
                ],
            ]
        );

        $this->control_for_no_order_found_notice( 7 );

        $this->add_control(
            'downloads_l1_table_body_color',
            [
                'label' => esc_html__( 'Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'downloads_l1_table_body',
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody td',
            ]
        );

        $this->add_control(
            'downloads_l1_table_body_align',
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
                    '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody td' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'downloads_l1_table_body_background',
                'label' => esc_html__( 'Background', 'woolentor-pro' ),
                'types' => [ 'classic' ],
                'exclude' => [ 'image' ],
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
                'selector' => '{{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody th, {{WRAPPER}} .woolentor-email-downloads.layout-1 .downloads-table tbody td',
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

        $this->control_for_no_order_found_notice( 8 );

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
     * Render downloads widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! woolentor_email_widget_conditions( $settings ) ) {
            return;
        }

        $layout = isset( $settings['layout'] ) ? sanitize_text_field( $settings['layout'] ) : '1';
        $layout = ! empty( $layout ) ? $layout : '1';

        $heading = isset( $settings['downloads_heading'] ) ? sanitize_text_field( $settings['downloads_heading'] ) : '';
        $alt = isset( $settings['downloads_alt'] ) ? sanitize_text_field( $settings['downloads_alt'] ) : '';
        $custom = isset( $settings['downloads_custom'] ) ? sanitize_textarea_field( $settings['downloads_custom'] ) : '';

        $order = woolentor_email_get_order();
        $email = woolentor_email_get_email();

        if ( ! is_object( $order ) || empty( $order ) || ! is_object( $email ) || empty( $email ) ) {
            return;
        }

        $sent_to_admin = woolentor_email_is_sent_to_admin();
        $plain_text = woolentor_email_is_plain_text();

        $downloads = $order->get_downloadable_items();

        $content = '';

        if ( ! is_array( $downloads ) || empty( $downloads ) ) {
            if ( 'custom' === $alt ) {
                $content = $custom;
            }
        } else {
            ob_start();
            include( WOOLENTOR_EMAIL_CUSTOMIZER_RENDER_PATH . '/downloads/' . $layout . '.php' );
            $content = ob_get_clean();
        }

        if ( empty( $content ) ) {
            return;
        }

        $output = '';

        if ( ! empty( $heading ) ) {
            $output .= '<div class="woolentor-email-downloads-heading">' . $heading . '</div>';
        }

        if ( ! empty( $content ) ) {
            $output .= '<div class="woolentor-email-downloads-content">' . $content . '</div>';
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-downloads layout-' . $layout . '">' . $output . '</div>';
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-downloads-wrapper layout-' . $layout . '">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }
}