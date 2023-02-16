<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email products widget.
 */
class Woolentor_Wl_Email_Products_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-products';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Products', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-products';
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
        return [ 'email', 'products', 'woocommerce', 'wc' ];
    }

    /**
     * Register image widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_products',
            [
                'label' => esc_html__( 'Products', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 12,
                    ],
                ],
            ]
        );

        $this->add_control(
            'number_of_products',
            [
                'label' => esc_html__( 'Number of products', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
            ]
        );

        $this->add_control(
            'source',
            [
                'label' => esc_html__( 'Source', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => [
                    'all' => esc_html__( 'All', 'woolentor-pro' ),
                    'latest' => esc_html__( 'Latest', 'woolentor-pro' ),
                    'sale' => esc_html__( 'On sale', 'woolentor-pro' ),
                    'featured' => esc_html__( 'Featured', 'woolentor-pro' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'include_ids',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__( 'Include By IDs', 'woolentor-pro' ),
                'description' => esc_html__( 'Comma separated IDs.', 'woolentor-pro' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'exclude_ids',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__( 'Exclude By IDs', 'woolentor-pro' ),
                'description' => esc_html__( 'Comma separated IDs.', 'woolentor-pro' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order By', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => esc_html__( 'Date', 'woolentor-pro' ),
                    'title' => esc_html__( 'Title', 'woolentor-pro' ),
                    'price' => esc_html__( 'Price', 'woolentor-pro' ),
                    'popularity' => esc_html__( 'Popularity', 'woolentor-pro' ),
                    'rating' => esc_html__( 'Rating', 'woolentor-pro' ),
                    'rand' => esc_html__( 'Random', 'woolentor-pro' ),
                    'menu_order' => esc_html__( 'Menu Order', 'woolentor-pro' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Order', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'asc',
                'options' => [
                    'asc' => esc_html__( 'ASC', 'woolentor-pro' ),
                    'desc' => esc_html__( 'DESC', 'woolentor-pro' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();

        $this->start_controls_section(
            'section_style_products',
            [
                'label' => esc_html__( 'Products', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'columns_gap',
            [
                'label' => esc_html__( 'Columns Gap (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ]
        );

        $this->add_control(
            'rows_gap',
            [
                'label' => esc_html__( 'Rows Gap (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
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
                    '{{WRAPPER}} .woolentor-email-product' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Image', 'woolentor-pro' ),
                'separator' => 'before',
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
                'selector' => '{{WRAPPER}} .woolentor-email-product-image',
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
                    '{{WRAPPER}} .woolentor-email-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_spacing',
            [
                'label' => esc_html__( 'Spacing (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-product-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Title', 'woolentor-pro' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
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
                    '{{WRAPPER}} .woolentor-email-product-title, {{WRAPPER}} .woolentor-email-product-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
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
                'selector' => '{{WRAPPER}} .woolentor-email-product-title',
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label' => esc_html__( 'Spacing (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-product-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'price_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Price', 'woolentor-pro' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
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
                    '{{WRAPPER}} .woolentor-email-product-price, {{WRAPPER}} .woolentor-email-product-price del, {{WRAPPER}} .woolentor-email-product-price ins, {{WRAPPER}} .woolentor-email-product-price bdi, {{WRAPPER}} .woolentor-email-product-price span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price',
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
                'selector' => '{{WRAPPER}} .woolentor-email-product-price',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_style',
            [
                'label' => esc_html__( 'Product Box', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'product_border',
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
                'selector' => '{{WRAPPER}} .woolentor-email-product',
            ]
        );

        $this->add_control(
            'product_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'product_background',
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
                'selector' => '{{WRAPPER}} .woolentor-email-product',
            ]
        );

        $this->add_control(
            'product_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
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
                'selector' => '{{WRAPPER}} .woolentor-email-products-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-products-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .woolentor-email-products-wrapper',
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
                    '{{WRAPPER}} .woolentor-email-products-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woolentor-email-products-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $source = isset( $settings['source'] ) ? sanitize_key( $settings['source'] ) : 'all';
        $include_ids = isset( $settings['include_ids'] ) ? sanitize_text_field( $settings['include_ids'] ) : '';
        $exclude_ids = isset( $settings['exclude_ids'] ) ? sanitize_text_field( $settings['exclude_ids'] ) : '';
        $orderby = isset( $settings['orderby'] ) ? sanitize_key( $settings['orderby'] ) : '';
        $order = isset( $settings['order'] ) ? sanitize_key( $settings['order'] ) : '';
        $number_of_products = isset( $settings['number_of_products'] ) ? $settings['number_of_products'] : '';
        $columns = isset( $settings['columns'] ) ? $settings['columns'] : '';

        $columns_gap = isset( $settings['columns_gap'] ) ? $settings['columns_gap'] : '';
        $rows_gap = isset( $settings['rows_gap'] ) ? $settings['rows_gap'] : '';

        $include_ids = woolentor_email_string_to_array_of_id( $include_ids );
        $exclude_ids = woolentor_email_string_to_array_of_id( $exclude_ids );

        $number_of_products = isset( $number_of_products['size'] ) ? sanitize_text_field( $number_of_products['size'] ) : 4;
        $number_of_products = is_numeric( $number_of_products ) ? absint( $number_of_products ) : 4;
        $number_of_products = ( ! empty( $number_of_products ) ? ( 11 > $number_of_products ? $number_of_products : 20 ) : 4 );

        $columns = isset( $columns['size'] ) ? sanitize_text_field( $columns['size'] ) : 2;
        $columns = is_numeric( $columns ) ? absint( $columns ) : 2;
        $columns = ( ! empty( $columns ) ? ( 7 > $columns ? $columns : 6 ) : 2 );

        $columns_gap = isset( $columns_gap['size'] ) ? sanitize_text_field( $columns_gap['size'] ) : 20;
        $columns_gap = is_numeric( $columns_gap ) ? absint( $columns_gap ) : 20;

        $rows_gap = isset( $rows_gap['size'] ) ? sanitize_text_field( $rows_gap['size'] ) : 20;
        $rows_gap = is_numeric( $rows_gap ) ? absint( $rows_gap ) : 20;

        $columns_padding = ( 1 < $columns ? ( ( $columns_gap * ( $columns - 1 ) ) / $columns ) : 0 );
        $columns_padding = is_float( $columns_padding ) ? number_format( $columns_padding, 6 ) : $columns_padding;

        $columns_half_padding = ( 0 < $columns_padding ? ( $columns_padding / 2 ) : 0 );
        $columns_half_padding = is_float( $columns_half_padding ) ? number_format( $columns_half_padding, 6 ) : $columns_half_padding;

        $columns_padding = ( 0 < $columns_padding ? $columns_padding . 'px' : 0 );
        $columns_half_padding = ( 0 < $columns_half_padding ? $columns_half_padding . 'px' : 0 );
        $row_padding = ( 0 < $rows_gap ? $rows_gap . 'px' : 0 );

        $products = wc_get_products( array(
            'status'  => array( 'publish' ),
            'limit'   => $number_of_products,
            'include' => $include_ids,
            'exclude' => $exclude_ids,
            'orderby' => $orderby,
            'order'   => $order,
        ) );

        $output = '';

        if ( is_array( $products ) && ! empty( $products ) ) {
            $row_count = 1;
            $item_count = 1;
            $total_items = count( $products );

            foreach ( $products as $product ) {
                if ( ! is_object( $product ) || empty( $product ) ) {
                    continue;
                }

                $product_id = $product->get_id();
                $product_title = $product->get_title();
                $product_image = $product->get_image( 'full' );
                $product_permalink = $product->get_permalink();
                $product_price_html = $product->get_price_html();

                $column_position = '';

                if ( 1 < $total_items ) {
                    if ( $columns < $item_count ) {
                        if ( 0 === ( ( $item_count - 1 ) % $columns ) ) {
                            $column_position = 'first';
                        } elseif ( 0 === ( $item_count % $columns ) ) {
                            $column_position = 'last';
                        } else {
                            $column_position = 'middle';
                        }
                    } else {
                        if ( 1 === $item_count ) {
                            $column_position = 'first';
                        } elseif ( $columns === $item_count ) {
                            $column_position = 'last';
                        } else {
                            $column_position = 'middle';
                        }
                    }
                }

                $top_padding = ( 1 < $item_count ? $row_padding : 0 );
                $right_padding = ( 'first' === $column_position ? $columns_padding : ( 'middle' === $column_position ? $columns_half_padding : 0 ) );
                $left_padding = ( 'last' === $column_position ? $columns_padding : ( 'middle' === $column_position ? $columns_half_padding : 0 ) );

                $item_padding = sprintf( '0 %1$s 0 %2$s', $right_padding, $left_padding );
                $group_padding = sprintf( '%1$s 0 0 0', $top_padding );

                if ( ( 1 === $item_count ) || ( ( $columns < $item_count ) && ( 0 === ( ( $item_count - 1 ) % $columns ) ) ) ) {
                    $output .= '<div class="woolentor-email-products-row woolentor-email-products-row-' . $row_count . '" style="padding: ' . $group_padding . ';">';
                }

                $output .= '<div class="woolentor-email-product-col woolentor-email-product-col-' . $item_count . '">';
                $output .= '<div class="woolentor-email-product-wrap" style="padding: ' . $item_padding . ';">';
                $output .= '<div class="woolentor-email-product">';
                $output .= '<div class="woolentor-email-product-image"><a target="_blank" href="' . $product_permalink . '" rel="noopener">' . $product_image . '</a></div>';
                $output .= '<div class="woolentor-email-product-title"><a target="_blank" href="' . $product_permalink . '" rel="noopener">' . $product_title . '</a></div>';
                $output .= '<div class="woolentor-email-product-price">' . $product_price_html . '</div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';

                if ( ( $total_items === $item_count ) || ( 0 === ( $item_count % $columns ) ) ) {
                    $output .= '</div>';

                    $row_count++;
                }

                $item_count++;
            }
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-products woolentor-email-products-columns-' . $columns . '">' . $output . '</div>';
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-products-wrapper">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }
}