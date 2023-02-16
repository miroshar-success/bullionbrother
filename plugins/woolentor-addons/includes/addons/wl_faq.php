<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Faq_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-faq';
    }

    public function get_title() {
        return __( 'WL: FAQ', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-accordion';
    }

    public function get_categories() {
        return [ 'woolentor-addons' ];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [ 'woolentor-faq' ];
    }

    public function get_script_depends(){
        return [ 'woolentor-accordion-min','woolentor-widgets-scripts' ];
    }

    public function get_keywords(){
        return ['faq','question','answer'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'woolentor' ),
            ]
        );

            $repeater = new Repeater();

            $repeater->add_control(
                'content_source',
                [
                    'label'   => esc_html__( 'Select Content Source', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'    => esc_html__( 'Custom', 'woolentor' ),
                        "elementor" => esc_html__( 'Elementor Template', 'woolentor' ),
                    ],
                    'label_block'=>true,
                ]
            );

            $repeater->add_control(
                'title',
                [
                    'label' => esc_html__( 'Title', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'FAQ Title', 'woolentor' ),
                    'placeholder' => esc_html__( 'Type your faq title here', 'woolentor' ),
                    'label_block'=>true,
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $repeater->add_control(
                'content',
                [
                    'label' => esc_html__( 'Content', 'woolentor' ),
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris niesi ut aliquip ex ea commodo consequat.sed do eiusmod tempor incididunt ut quis labore et doliore magna aliqua.</p>',
                    'condition' => [
                        'content_source' =>'custom',
                    ],
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $repeater->add_control(
                'template_id',
                [
                    'label'   => esc_html__( 'Select Template', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => woolentor_elementor_template(),
                    'condition' => [
                        'content_source' =>'elementor',
                    ],
                    'label_block'=>true,
                ]
            );

            $repeater->add_control(
                'individual_icon',
                [
                    'label' => esc_html__( 'Do you want to individual icon ?', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor' ),
                    'label_off' => esc_html__( 'No', 'woolentor' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $repeater->add_control(
                'indopen_icon',
                [
                    'label'       => esc_html__( 'Open Icon', 'woolentor' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'indopenicon',
                    'condition'=>[
                        'individual_icon'=>'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'indclose_icon',
                [
                    'label'       => esc_html__( 'Close Icon', 'woolentor' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'indcloseicon',
                    'condition'=>[
                        'individual_icon'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'accordion_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'title' => esc_html__( 'Words To Live By', 'woolentor' ),
                            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris niesi ut aliquip ex ea commodo consequat.sed do eiusmod tempor incididunt ut quis labore et doliore magna aliqua.</p>',
                            'content_source'=>'custom',
                        ],
                        [
                            'title' => esc_html__( 'Producing Perfume From Home', 'woolentor' ),
                            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris niesi ut aliquip ex ea commodo consequat.sed do eiusmod tempor incididunt ut quis labore et doliore magna aliqua.</p>',
                            'content_source'=>'custom',
                        ],
                        [
                            'title' => esc_html__( 'The Basics Of Western Astrology Explained', 'woolentor' ),
                            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris niesi ut aliquip ex ea commodo consequat.sed do eiusmod tempor incididunt ut quis labore et doliore magna aliqua.</p>',
                            'content_source'=>'custom',
                        ],
                        [
                            'title' => esc_html__( 'What Curling Irons Are The Best Ones', 'woolentor' ),
                            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris niesi ut aliquip ex ea commodo consequat.sed do eiusmod tempor incididunt ut quis labore et doliore magna aliqua.</p>',
                            'content_source'=>'custom',
                        ]
                    ],
                    'title_field' => '{{{ title }}}',
                ]
            );

        $this->end_controls_section();

        // Additional Options area Start
        $this->start_controls_section(
            'aditional_options',
            [
                'label' => esc_html__( 'Additional Options', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'show_item',
                [
                    'label' => esc_html__( 'Show First Item', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor' ),
                    'label_off' => esc_html__( 'No', 'woolentor' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'separator'=>'after',
                ]
            );

            $this->add_control(
                'custom_icon',
                [
                    'label' => esc_html__( 'Custom Icon', 'woolentor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor' ),
                    'label_off' => esc_html__( 'No', 'woolentor' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'open_icon',
                [
                    'label'       => esc_html__( 'Open Icon', 'woolentor' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'openicon',
                    'condition'=>[
                        'custom_icon'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'close_icon',
                [
                    'label'       => esc_html__( 'Close Icon', 'woolentor' ),
                    'type'        => Controls_Manager::ICONS,
                    'label_block' => true,
                    'fa4compatibility' => 'closeicon',
                    'condition'=>[
                        'custom_icon'=>'yes',
                    ],
                ]
            );

            $this->add_control(
                'icon_position',
                [
                    'label'   => esc_html__( 'Icon Position', 'woolentor' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'before',
                    'options' => [
                        'before'=> esc_html__( 'Before Title', 'woolentor' ),
                        'after' => esc_html__( 'After Title', 'woolentor' ),
                    ],
                    'label_block'=>true,
                    'separator'=>'after',
                ]
            );

        $this->end_controls_section();

        // Accordion item style tab section
        $this->start_controls_section(
            'accordion_item_style',
            [
                'label' => esc_html__( 'Item', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_responsive_control(
                'accordion_item_spacing',
                [
                    'label' => esc_html__( 'Item Spacing', 'woolentor' ),
                    'type'  => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 150,
                        ],
                    ],
                    'default' => [
                        'size' => 12,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card + .htwoolentor-faq-card' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'accordion_item_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card',
                ]
            );

            $this->add_responsive_control(
                'accordion_item_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'accordion_item_background',
                    'label' => esc_html__( 'Background', 'woolentor' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'accordion_item_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card',
                ]
            );

            $this->add_responsive_control(
                'accordion_item_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Title style tab start
        $this->start_controls_section(
            'accordion_title_style',
            [
                'label'     => esc_html__( 'Title', 'woolentor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'title_align',
                [
                    'label'   => esc_html__( 'Alignment', 'woolentor' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'start'    => [
                            'title' => esc_html__( 'Left', 'woolentor' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'woolentor' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'end' => [
                            'title' => esc_html__( 'Right', 'woolentor' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card .htwoolentor-faq-head' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );

            $this->start_controls_tabs('accordion_title_style_tabs');

                // Accordion Title Normal tab Start
                $this->start_controls_tab(
                    'accordion_title_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'title_normal_background',
                            'label' => esc_html__( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_title_border',
                            'label' => esc_html__( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'title_box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head',
                            'separator' => 'before',
                        ]
                    );

                    $this->add_control(
                        'accordion_title_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'title_typography',
                            'label' => esc_html__( 'Typography', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card .htwoolentor-faq-head',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // Accordion Title Normal tab End

                // Accordion Title Active tab Start
                $this->start_controls_tab(
                    'accordion_title_style_active_tab',
                    [
                        'label' => esc_html__( 'Active', 'woolentor' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'activebackground',
                            'label' => esc_html__( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head',
                        ]
                    );

                    $this->add_control(
                        'accordion_title_active_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_title_active_border',
                            'label' => esc_html__( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_active_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_title_active_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'active_title_box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head',
                            'separator' => 'before',
                        ]
                    );

                $this->end_controls_tab(); // FAQ Title Active tab End

            $this->end_controls_tabs();

        $this->end_controls_section();


        // Content style tab start
        $this->start_controls_section(
            'accordion_content_style',
            [
                'label'     => esc_html__( 'Content', 'woolentor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'accordion_content_color',
                [
                    'label'     => esc_html__( 'Color', 'woolentor' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card .htwoolentor-faq-content' => 'color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => esc_html__( 'Typography', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card .htwoolentor-faq-content',
                ]
            );

            $this->add_responsive_control(
                'accordion_content_align',
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
                        '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card .htwoolentor-faq-content' => 'text-align: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'accordion_content_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card .htwoolentor-faq-content' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );

        $this->end_controls_section();

        // Icon style tab start
        $this->start_controls_section(
            'accordion_icon_style',
            [
                'label'     => esc_html__( 'Icon', 'woolentor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
            // FAQ Icon tabs Start
            $this->start_controls_tabs('woolentor_faq_icon_style_tabs');

                // FAQ Icon normal tab Start
                $this->start_controls_tab(
                    'accordion_icon_style_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'accordion_icon_indecator_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-indicator::before' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-indicator::after' => 'background-color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon!'=>'yes',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'iconbackground',
                            'label' => esc_html__( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'accordion_icon_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon svg *' => 'stroke: {{VALUE}};fill:{{VALUE}};',
                            ],
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_icon_border',
                            'label' => esc_html__( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_icon_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'icon_box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon',
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_icon_lineheight',
                        [
                            'label' => esc_html__( 'Icon Line Height', 'woolentor' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 150,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_icon_width',
                        [
                            'label' => esc_html__( 'Icon Width', 'woolentor' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card:not(.is-active) .htwoolentor-faq-head .htwoolentor-faq-head-icon' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Accordion Icon normal tab End

                // Accordion Icon Active tab Start
                $this->start_controls_tab(
                    'accordion_active_icon_style_tab',
                    [
                        'label' => esc_html__( 'Active', 'woolentor' ),
                    ]
                );

                    $this->add_control(
                        'accordion_icon_active_indecator_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-indicator::before' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-indicator::after' => 'background-color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon!'=>'yes',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'iconactivebackground',
                            'label' => esc_html__( 'Background', 'woolentor' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-icon',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'accordion_active_icon_color',
                        [
                            'label'     => esc_html__( 'Color', 'woolentor' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-icon' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-icon svg *' => 'stroke: {{VALUE}};fill:{{VALUE}};',
                            ],
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'accordion_active_icon_border',
                            'label' => esc_html__( 'Border', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-icon',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'accordion_active_icon_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-icon' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'icon_active_box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'woolentor' ),
                            'selector' => '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-icon',
                            'separator' => 'before',
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                    $this->add_control(
                        'accordion_active_icon_lineheight',
                        [
                            'label' => esc_html__( 'Icon Line Height', 'woolentor' ),
                            'type'  => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 150,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .htwoolentor-faq .htwoolentor-faq-card.is-active .htwoolentor-faq-head .htwoolentor-faq-head-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition'=>[
                                'custom_icon'=>'yes',
                            ],
                        ]
                    );

                $this->end_controls_tab(); // Accordion Icon Active tab End

            $this->end_controls_tabs();

        $this->end_controls_section(); // Icon style tabs end


    }

    protected function render( $instance = [] ) {
        $settings       = $this->get_settings_for_display();
        $accordion_list = $this->get_settings_for_display('accordion_list');
        $id             = $this->get_id();

        $this->add_render_attribute( 'area_attr', 'class', 'htwoolentor-faq htmove-icon-pos-'.$settings['icon_position'] );
        $this->add_render_attribute( 'area_attr', 'id', 'htwoolentor-faq-'.$id );


        $accordion_settings = [
            'showitem' => ( 'yes' === $settings['show_item'] ),
        ];
        $this->add_render_attribute( 'area_attr', 'data-settings', wp_json_encode( $accordion_settings ) );

        // Icon
        $open_icon = ( !empty( $settings['open_icon']['value'] ) ? '<span class="htwoolentor-faq-head-icon htwoolentor-faq-open-icon">'.woolentor_render_icon( $settings,'open_icon', 'openicon' ).'</span>' : ''  );

        $close_icon = ( !empty( $settings['close_icon']['value'] ) ? '<span class="htwoolentor-faq-head-icon htwoolentor-faq-close-icon">'.woolentor_render_icon( $settings,'close_icon', 'closeicon' ).'</span>' : '' );

        $icon = '<span class="htwoolentor-faq-head-indicator"></span>';
        if( !empty( $settings['open_icon']['value'] ) || !empty( $settings['close_icon']['value'] )){
            $icon = $open_icon.$close_icon;
        }

        ?>                
            <div <?php echo $this->get_render_attribute_string( 'area_attr' ); ?> >
                <?php
                    if( is_array( $accordion_list ) ){
                        foreach ( $accordion_list as  $accordion ){

                            $title = ( !empty( $accordion['title'] ) ? '<span class="htwoolentor-faq-head-text">'.$accordion['title'].'</span>' : '' );

                            if( $accordion['individual_icon'] == 'yes' ){
                                $ind_open_icon = ( !empty( $accordion['indopen_icon']['value'] ) ? '<span class="htwoolentor-faq-head-icon htwoolentor-faq-open-icon">'.woolentor_render_icon( $accordion,'indopen_icon', 'indopenicon' ).'</span>' : ''  );

                                $ind_close_icon = ( !empty( $accordion['indclose_icon']['value'] ) ? '<span class="htwoolentor-faq-head-icon htwoolentor-faq-close-icon">'.woolentor_render_icon( $accordion,'indclose_icon', 'indcloseicon' ).'</span>' : '' );

                                $open_close_icon = $ind_open_icon.$ind_close_icon;

                            }else{
                                $open_close_icon = $icon;
                            }

                            ?>
                            <div class="htwoolentor-faq-card">
                                <?php
                                    if( $settings['icon_position'] == 'after'){
                                        echo sprintf( '<div class="htwoolentor-faq-head">%2$s %1$s</div>',$open_close_icon, $title );
                                    }else{
                                        echo sprintf( '<div class="htwoolentor-faq-head">%1$s %2$s</div>',$open_close_icon, $title );
                                    }
                                ?>
                                <div class="htwoolentor-faq-body">
                                    <div class="htwoolentor-faq-content">
                                    <?php 
                                        if ( $accordion['content_source'] == 'custom' && !empty( $accordion['content'] ) ) {
                                            echo wp_kses_post( $accordion['content'] );
                                        } elseif ( $accordion['content_source'] == "elementor" && !empty( $accordion['template_id'] )) {
                                            echo Plugin::instance()->frontend->get_builder_content_for_display( $accordion['template_id'] );
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        <?php

    }

}