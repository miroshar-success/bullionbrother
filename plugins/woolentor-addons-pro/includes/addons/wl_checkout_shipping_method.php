<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_WL_Checkout_Shipping_Method_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-shipping-method';
    }

    public function get_title() {
        return __( 'WL: Checkout Shipping Method', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-editor-list-ul';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets-pro',
            'woolentor-checkout',
        ];
    }

    public function get_keywords(){
        return ['shipping','checkout','method', 'shipping method'];
    }

    protected function register_controls() {

        // Content section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Shipping Methods', 'woolentor-pro' ),
            ]
        );
        
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'woolentor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Shipping Method', 'woolentor-pro' ),
                'placeholder' => esc_html__( 'Type your title here', 'woolentor-pro' ),
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'style',
            [
                'label'   => __( 'Shipping Methods Style', 'woolentor-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => __( 'Style 1', 'woolentor-pro' ),
                    '2' => __( 'Style 2', 'woolentor-pro' ),
                ],
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'important_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div>After editing these fields, update this template, reload this template and check your real checkout page from your website.</div>',
                'content_classes' => 'elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning',
            ]
        );

        $this->add_control(
            'free_shipping_desc',
            [
                'label'     => esc_html__( 'Free Shipping Description', 'woolentor-pro' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Free shipping for the US Pasific Zone', 'woolentor-pro' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'flat_rate_desc',
            [
                'label'     => esc_html__( 'Flat Rate Description', 'woolentor-pro' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Delivered between 10 and 15 Business Days', 'woolentor-pro' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'local_pickup_desc',
            [
                'label'     => esc_html__( 'Local Pickup Description', 'woolentor-pro' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Local pickup option description', 'woolentor-pro' ),
                'label_block' => true,
            ]
        );

        $this->end_controls_section(); //Content section
        
        // Style section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'title_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-title',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_align',
                [
                    'label'        => __( 'Alignment', 'woolentor-pro' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default'   => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-title' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section(); //Style section
        
        // Method item style
        $this->start_controls_section(
            'method_item_style_section',
            [
                'label' => __( 'Method Item', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );  

            $this->add_control(
                'method_item',
                [
                    'label' => __( 'Name', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'method_name_text_color',
                [
                    'label' => __( 'Text Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} ul#shipping_method li label' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'method_name_text_typo',
                    'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li label',
                    
                ]
            );

            $this->add_control(
                'method_desc',
                [
                    'label' => __( 'Desc', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'method_item_desc_text_color',
                [
                    'label' => __( 'Desc Text Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} ul#shipping_method li .woolentor-desc' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'method_name_desc_text_typo',
                    'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li .woolentor-desc',
                ]
            );

            $this->add_control(
                'method_price',
                [
                    'label' => __( 'Price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'method_item_price_color',
                [
                    'label' => __( 'Price Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} ul#shipping_method li .woocommerce-Price-amount' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'method_item_2',
                [
                    'label' => __( 'Item Wrapper', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'method_item_wrapper_bg',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} ul#shipping_method li' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'method_item_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '.woocommerce {{WRAPPER}} ul#shipping_method li',
                ]
            );
            $this->add_responsive_control(
                'method_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} ul#shipping_method li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Margin
            $this->add_responsive_control(
                'method_item_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} ul#shipping_method li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            // Padding
            $this->add_responsive_control(
                'method_item_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} ul#shipping_method li label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            // Separator border
            $this->add_control(
                'heading_separator',
                [
                    'label' => __( 'Separator', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'condition'=>[
                        'style'=>'2',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'method_item_separator_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '.woocommerce {{WRAPPER}} .woolentor-shipping-method-1 ul#shipping_method li:not(:last-child)::after',
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .woolentor-shipping-method-1 ul#shipping_method li:not(:last-child)::after',
                    ],
                    'exclude'   => array('width'),
                    'condition'=>[
                        'style'=>'2',
                    ],
                ]
            );
        $this->end_controls_section(); //Method item style section

        // Radio button
        $this->start_controls_section(
            'radio_button_style_section',
            [
                'label' => esc_html__( 'Radio Button', 'woolentor-pros' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'radio_button_border',
                'label'     => esc_html__( 'Border', 'woolentor-pro' ),
                'selector'  => '{{WRAPPER}} .woolentor-shipping-method-1 ul#shipping_method input[type=radio] ~ label::before',
                'exclude'   => array('width'),
            ]
        );

        $this->add_control(
            'heading_selected',
            [
                'label' => __( 'Selected', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'radio_selected_border',
                'label'     => esc_html__( 'Selected Border', 'woolentor-pro' ),
                'selector'  => '{{WRAPPER}} .woolentor-shipping-method-1 ul#shipping_method input[type=radio]:checked ~ label::before',
                'exclude'   => array('width'),
            ]
        );

        $this->add_control(
            'radio_selected_color',
            [
                'label' => __( 'Selected Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .woolentor-shipping-method-1 ul#shipping_method input[type=radio] ~ label::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section(); //Checkbox

        /* Adding a background color to the message box. */
        // Message box style
        $this->start_controls_section(
            'message_box_style_section',
            [
                'label' => __( 'Message Box', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );  

            $this->add_control(
                'important_note_message_box',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div>When there is no shipping methods are available there is a notice displayed. Use the options below to customize that notice.</div>',
                    'content_classes' => 'elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning',
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label'=> __( 'Text Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'message_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert',
                ]
            );

            $this->add_control(
                'message_box_bg_color',
                [
                    'label' => __( 'Background Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'message_box_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert',
                ]
            );
            $this->add_responsive_control(
                'message_box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Margin
            $this->add_responsive_control(
                'message_box_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-checkout__shipping-method' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            // Padding
            $this->add_responsive_control(
                'message_box_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            // Icon
            $this->add_control(
                'heading_icon',
                [
                    'label' => __( 'Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_responsive_control(
                'icon_font_size',
                [
                    'label' => __( 'Icon Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert i' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_control(
                'icon_normal_color',
                [
                    'label'=> __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-shipping-method-1 .woolentor-shipping-alert i' => 'color: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section(); //Method item style section

        $this->start_controls_section(
            'global_font_typography_section',
            [
                'label' => __('Global Font Family', 'woolentor-pro'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'global_font_typography',
                [
                    'label'       => __( 'Font Family', 'woolentor-pro' ),
                    'description' => __('Set a specific font family for this widget.', 'woolentor-pro'),
                    'type'        => Controls_Manager::FONT,
                    'default'     => '',
                    'selectors' => [
                        '{{WRAPPER}} *:not(i)' => 'font-family: {{VALUE}}',
                    ],
                ]
            );
        $this->end_controls_section();
    }

    protected function render() {
        $settings           = $this->get_settings_for_display();
        $shipping_status    = WC()->cart->needs_shipping() && WC()->cart->show_shipping() ? true : false;

        if( !$shipping_status ){
            return;
        }

        $wrapper_classes = array('woolentor-shipping-method-1');
        if( !empty($settings['style']) ){
            $wrapper_classes[] = 'wl_style_'. $settings['style'];
        }
        ?>
        <div class="<?php echo esc_attr( implode(' ', $wrapper_classes) ); ?>">

            <?php if(!empty($settings['title'])): ?>
            <h3 class="woolentor-title"><?php echo wp_kses_post($settings['title']) ?></h3>
            <?php endif; ?>

            <div class="woolentor-checkout__shipping-method">
                <table>
                    <tbody>
                    <?php
                    if ( $shipping_status ) : ?>

                        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

                        <?php wc_cart_totals_shipping_html(); ?>

                        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

                    <?php endif; ?> 
                    </tbody>
                </table>
            </div>
        </div>
        <?php 
    }
}