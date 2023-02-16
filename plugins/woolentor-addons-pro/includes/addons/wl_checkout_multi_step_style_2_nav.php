<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_WL_Checkout_Multi_Step_Style_2_Nav_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-checkout-multi-step-form-style-2-nav';
    }
    
    public function get_title() {
        return __( 'WL: Checkout Multi Step Style 2 Navigation', 'woolentor-pro' );
    }

    public function get_icon() {
        return ' eicon-form-horizontal';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_style_depends(){
        return [
            'woolentor-checkout',
            'woolentor-widgets-pro',
        ];
    }

    public function get_script_depends(){
        return [
            'woolentor-multi-steps-checkout',
            'woolentor-widgets-scripts-pro',
        ];
    }

    public function get_keywords(){
        return ['checkout form','multistep checkout','multi step','checkout', 'nav', 'navigation'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        ); 
            $this->add_control(
                'important_note',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => '<div style="line-height:18px;">The Addon is only compatible with the <b>"WL: Checkout Multi Step Style 2"</b> addon.</div>',
                    'content_classes' => 'wlnotice-imp elementor-panel-alert elementor-panel-alert-info',
                ]
            ); 

            // Step Style
            $this->add_control(
                'step_indicator_style',
                [
                    'label'   => __( 'Step Navigation Style', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '2',
                    'options' => [
                        '1' => __( 'Style 1', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                        '3' => __( 'Style 3', 'woolentor-pro' ),
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tabs_content',
            [
                'label' => esc_html__( 'Tabs Label', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'steps_custom_title_heading',
                [
                    'label' => esc_html__( 'Custom Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                ]
            );

            $this->add_control(
                'information',
                [
                    'label' => __( 'Information', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Information', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'shipping',
                [
                    'label' => __( 'Shipping', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Shipping', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'payment',
                [
                    'label' => __( 'Payment', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => __('Payment', 'woolentor-pro'),
                ]
            );

        $this->end_controls_section();

        // Tabs Style Section
        $this->start_controls_section(
            'section_tabs_menu_style',
            [
                'label' => esc_html__( 'Steps - Tab/Accordion', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'tabs_background_color',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor-step-nav,{{WRAPPER}} .woolentor-step-nav-3 ul li .woolentor-step-nav-number',
                ]
            );
            
            $this->add_responsive_control(
                'area_margin',
                [
                    'label' => esc_html__( 'Area Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'area_padding',
                [
                    'label' => esc_html__( 'Area Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'heading_tabs_name',
                    [
                    'label' => __( 'Tab Heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tabs_menu_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-step-nav-text, {{WRAPPER}} .woolentor-step-nav-number,.wl_msc_style_2 .woolentor-block-heading .woolentor-block-heading-title',
                ]
            );

            $this->add_control(
                'heading_tabs_number',
                [
                    'label' => __( 'Number', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'condition' => [
                        'step_indicator_style' => '2',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tabs_number_typography',
                    'label' => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-step-nav-number',
                    'condition' => [
                        'step_indicator_style' => '2',
                    ],
                ]
            );

            $this->start_controls_tabs('tabs_menu_style_tabs');
                // Normal tabs style
                $this->start_controls_tab(
                    'tabs_menu_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    // Text color
                    $this->add_control(
                        'normal_text_color',
                        [
                            'label' => __( 'Step Name Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav ul li .woolentor-step-nav-text' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .wl_msc_style_2 .woolentor-block-heading-title' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Number color
                    $this->add_control(
                        'step_number_color',
                        [
                            'label' => __( 'Step Number Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav-2 ul li .woolentor-step-nav-number' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'step_indicator_style' => '2',
                            ],
                        ]
                    );

                    // Color
                    $this->add_control(
                        'normal_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) ul li .woolentor-step-nav-number' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'step_indicator_style!' => '1',
                            ],
                        ]
                    );
                    
                    $this->add_control(
                        'border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) .woolentor-step-nav-bar' => 'background-color: {{VALUE}}', // border
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) ul li .woolentor-step-nav-number' => 'border-color:{{VALUE}}',
                                '{{WRAPPER}} .wl_msc_style_2 .woolentor-step' => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'step_indicator_style!' => '1'
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Current tabs style
                $this->start_controls_tab(
                    'tabs_menu_style_current_tab',
                    [
                        'label' => esc_html__( 'Current / Complete', 'woolentor-pro' ),
                    ]
                );
                    // Text color
                    $this->add_control(
                        'active_text_color',
                        [
                            'label' => __( 'Step Name Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-text' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                        // Number color
                        $this->add_control(
                            'active_step_number_color',
                            [
                                'label' => __( 'Step Number Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .woolentor-step-nav-2 ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-number' => 'color: {{VALUE}};',
                                ],
                                'condition' => [
                                    'step_indicator_style' => '2',
                                ],
                            ]
                        );

                    // Color
                    $this->add_control(
                        'active_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-step-nav-2 ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-number' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-step-nav-3 ul li .woolentor-step-nav-number::after' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-step-nav-3 ul .woolentor-step-nav-bar-active' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .woolentor-step-nav-3 ul li:is(.woolentor-active) .woolentor-step-nav-number' => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'step_indicator_style!' => '1',
                            ],
                        ]
                    );

                    $this->add_control(
                        'active_border_color',
                        [
                            'label' => __( 'Border Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) div.woolentor-step-nav-bar-active' => 'background-color: {{VALUE}}',
                                '{{WRAPPER}} :is(.woolentor-step-nav-2,.woolentor-step-nav-3) ul li:is(.woolentor-active,.woolentor-complete) .woolentor-step-nav-number' => 'border-color:{{VALUE}}',
                            ],
                            'condition' => [
                                'step_indicator_style!' => '1',
                            ],
                        ]
                    );

                $this->end_controls_tab();
                
            $this->end_controls_tabs();
        
            $this->add_responsive_control(
                'step_name_margin',
                [
                    'label' => __( 'Spacing', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-step-nav' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wl_msc_style_2 .woolentor-step:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style!' => '2',
                    ],
                ]
            );

            $this->add_responsive_control(
                'step_heading_padding',
                [
                    'label' => esc_html__( 'Heading Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_msc_style_2 .woolentor-step:not(.woolentor-active)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'style' => '2',
                    ],
                ]
            );

        $this->end_controls_section();

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
        $settings   = $this->get_settings_for_display();

        // Skip shipping tab
        $shipping_method_step = 'shipping_method_step';
        $steps = array(
            'information' => array(
                'step_number'       => __('1', 'woolentor-pro')
            ),
            'shipping'    => array(
                'step_number'       => __('2', 'woolentor-pro')
            ),
            'payment'     => array(
                'step_number'       => __('3', 'woolentor-pro')
            )
        );

        // wc_ship_to_billing_address_only()
        if( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ){
            $hide_shipping_step = false;
        } else{
            $hide_shipping_step = true;

            $steps['payment']['step_number']            = __('2', 'woolentor-pro');
        }
        ?>
                    <!-- Step Nav Start -->
        <div class="woolentor-step-nav woolentor-step-nav-<?php echo esc_attr($settings['step_indicator_style']) ?>">
            <?php
                if(
                    $settings['step_indicator_style'] == 2 || 
                    $settings['step_indicator_style'] == 3
                ):
            ?>
            <div class="woolentor-step-nav-bar">bar</div>
            <div class="woolentor-step-nav-bar woolentor-step-nav-bar-active">bar active</div>
            <?php endif; ?>

            <ul>
                <li data-step-target="#information_step" class="woolentor-active" data-step-number="<?php echo esc_attr($steps['information']['step_number']) ?>">
                    <span class="woolentor-step-nav-number"><?php echo esc_html($steps['information']['step_number']) ?></span>
                    <span class="woolentor-step-nav-text"><?php echo esc_html($settings['information']) ?></span>
                </li>

                <?php if(!$hide_shipping_step): ?>
                <li data-step-target="#shipping_method_step" data-step-number="<?php echo esc_attr($steps['shipping']['step_number']) ?>" class="">
                    <span class="woolentor-step-nav-number"><?php echo esc_html($steps['shipping']['step_number']) ?></span>
                    <span class="woolentor-step-nav-text"><?php echo esc_html($settings['shipping']) ?></span>
                </li>
                <?php endif; ?>

                <li data-step-target="#payment_method_step" data-step-number="<?php echo esc_attr($steps['payment']['step_number']) ?>" class="">
                    <span class="woolentor-step-nav-number"><?php echo esc_html($steps['payment']['step_number']) ?></span>
                    <span class="woolentor-step-nav-text"><?php echo esc_html($settings['payment']) ?></span>
                </li>
            </ul>
        </div><!-- Step Nav End -->

        <?php
    }
}