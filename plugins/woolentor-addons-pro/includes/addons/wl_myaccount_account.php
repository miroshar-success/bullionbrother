<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Myaccount_Account_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-myaccount-account';
    }

    public function get_title() {
        return __( 'WL: My Account', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-elementor';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'woolentor-widgets-pro',
        ];
    }

    public function get_keywords(){
        return ['my account page','account page','my account navigation','custom layout'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'myaccount_content_setting',
            [
                'label' => esc_html__( 'Settings', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'user_info_show',
                [
                    'label' => esc_html__( 'User Info', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'thumbnail_image',
                [
                    'label' => __( 'Custom thumbnail', 'woolentor-pro' ),
                    'type' => Controls_Manager::MEDIA,
                    'condition'=>[
                        'user_info_show'=>'yes'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'thumbnailsize',
                    'default' => 'thumbnai',
                    'separator' => 'none',
                    'condition'=>[
                        'user_info_show'=>'yes'
                    ]
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'menu_items',
                [
                    'label' => esc_html__( 'Menu Items', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'dashboard',
                    'options' => function_exists('wc_get_account_menu_items') ? ( wc_get_account_menu_items() + ['customadd' => esc_html__( 'Custom', 'woolentor-pro' )] ) : [
                        'dashboard' => esc_html__( 'Dashboard', 'woolentor-pro' ),
                        'orders' => esc_html__( 'Orders', 'woolentor-pro' ),
                        'downloads' => esc_html__( 'Downloads', 'woolentor-pro' ),
                        'edit-address' => esc_html__( 'Addresses', 'woolentor-pro' ),
                        'edit-account' => esc_html__( 'Account details', 'woolentor-pro' ),
                        'customer-logout' => esc_html__( 'Logout', 'woolentor-pro' ),
                        'customadd' => esc_html__( 'Custom', 'woolentor-pro' ),
                    ],
                ]
            );

            $repeater->add_control(
                'menu_title', 
                [
                    'label' => esc_html__( 'Menu Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'New Menu Item' , 'woolentor-pro' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'menu_key', 
                [
                    'label' => esc_html__( 'Menu Key', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'New Menuitem' , 'woolentor-pro' ),
                    'label_block' => true,
                    'condition'=>[
                        'menu_items'=>'customadd',
                    ],
                ]
            );

            $repeater->add_control(
                'content_source', 
                [
                    'label'   => __( 'Select Content Source', 'woolentor-pro' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default'   => __( 'Default', 'woolentor-pro' ),
                        'custom'    => __( 'Custom', 'woolentor-pro' ),
                        'elementor' => __( 'Elementor Template', 'woolentor-pro' ),
                    ],
                    'label_block' => true,
                    'condition'=>[
                        'menu_items!'=>'customadd',
                    ],
                ]
            );

            $repeater->add_control(
                'remove_default_content',
                [
                    'label' => __( 'Remove Default Content', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                    'condition'   => [
                        'content_source!' =>'default',
                     ],
                ]
            );

            $repeater->add_control(
                'custom_content', 
                [
                    'label'       => __( 'Content', 'woolentor-pro' ),
                    'type'        => Controls_Manager::WYSIWYG,
                    'condition'   => [
                        'content_source' =>'custom',
                    ],
                    'default' => '',
                    'placeholder' => __( 'Enter your custom content here', 'woolentor-pro' ),
                ]
            );
            
            $repeater->add_control(
                'content_tmp',
                [
                    'label' => esc_html__( 'Content Template', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => woolentor_elementor_template(),
                    'condition'=>[
                        'content_source'=>'elementor',
                    ],
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'menu_url', 
                [
                    'label' => esc_html__( 'Menu URL', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( '#' , 'woolentor-pro' ),
                    'label_block' => true,
                    'condition'=>[
                        'menu_items'=>'customadd',
                    ],
                ]
            );

            $this->add_control(
                'navigation_list',
                [
                    'label' => __( 'Navigation List', 'woolentor-pro' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'menu_items' => 'dashboard',
                            'menu_title' => esc_html__( 'Dashboard', 'woolentor-pro' ),
                        ],
                        [
                            'menu_items' => 'orders',
                            'menu_title' => esc_html__( 'Orders', 'woolentor-pro' ),
                        ],
                        [
                            'menu_items' => 'downloads',
                            'menu_title' => esc_html__( 'Downloads', 'woolentor-pro' ),
                        ],
                        [
                            'menu_items' => 'edit-address',
                            'menu_title' => esc_html__( 'Addresses', 'woolentor-pro' ),
                        ],
                        [
                            'menu_items' => 'edit-account',
                            'menu_title' => esc_html__( 'Account details', 'woolentor-pro' ),
                        ],
                        [
                            'menu_items' => 'customer-logout',
                            'menu_title' => esc_html__( 'Logout', 'woolentor-pro' ),
                        ],
                    ],
                    'title_field' => '{{{ menu_title }}}',
                ]
            );

        $this->end_controls_section();

        // My Account User Info Style
        $this->start_controls_section(
            'myaccount_user_info_style',
            array(
                'label' => __( 'User Info', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'user_info_show'=>'yes'
                ]
            )
        );
                    
            $this->add_control(
                'myaccount_usermeta_text_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woolentor-user-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'myaccount_usermeta_link_color',
                [
                    'label' => __( 'Logout Link', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woolentor-logout a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'myaccount_usermeta_link_hover_color',
                [
                    'label' => __( 'Logout Link Hover', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woolentor-logout a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'myaccount_usermeta_name_typography',
                    'label' => __( 'Name Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_myaccount_page .woolentor-username',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'myaccount_usermeta_logout_typography',
                    'label' => __( 'Logout Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_myaccount_page .woolentor-logout',
                ]
            );

            $this->add_responsive_control(
                'myaccount_usermeta_image_border_radius',
                [
                    'label' => __( 'Image Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woolentor-user-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'myaccount_usermeta_alignment',
                [
                    'label' => __( 'Alignment', 'woolentor-pro' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woolentor-user-area' => 'justify-content: {{VALUE}}',
                    ],
                ]
            );


        $this->end_controls_section();


        // My Account Menu Style
        $this->start_controls_section(
            'myaccount_menu_style',
            array(
                'label' => __( 'Menu', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'myaccount_menu_type',
                [
                    'label'   => __( 'Menu Type', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'hleft' => [
                            'title' => __( 'Horizontal Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'hright' => [
                            'title' => __( 'Horizontal Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'vtop' => [
                            'title' => __( 'Vertical Top', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-top',
                        ],
                        'vbottom' => [
                            'title' => __( 'Vertical Bottom', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => is_rtl() ? 'hright' : 'hleft',
                    'toggle'      => false,
                ]
            );

            $this->add_responsive_control(
                'myaccount_menu_area_margin',
                [
                    'label' => __( 'Menu Area Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'myaccount_menu_alignment',
                [
                    'label' => __( 'Alignment', 'woolentor-pro' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'myaccount_menu_area_width',
                [
                    'label' => __( 'Menu Area Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 30,
                    ],
                    'condition'=>[
                        'myaccount_menu_type' => array( 'hleft','hright' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('myaccount_menu_style_tabs');

                // Menu Normal Color
                $this->start_controls_tab(
                    'myaccount_menu_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'myaccount_menu_text_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation ul li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'myaccount_menu_text_typography',
                            'label' => __( 'Typography', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation ul li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'myaccount_menu_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'myaccount_menu_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'myaccount_menu_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation ul li',
                        ]
                    );

                $this->end_controls_tab();

                // Menu Hover
                $this->start_controls_tab(
                    'myaccount_menu_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'myaccount_menu_text_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation ul li a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-navigation ul li.is-active a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'myaccount_content_style',
            array(
                'label' => __( 'Content', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_responsive_control(
                'myaccount_content_area_width',
                [
                    'label' => __( 'Content Area Width', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 68,
                    ],
                    'condition'=>[
                        'myaccount_menu_type' => array( 'hleft','hright' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-content' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'myaccount_text_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-content' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_control(
                'myaccount_link_color',
                [
                    'label' => __( 'Link Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-content a' => 'color: {{VALUE}}',
                    ],
                ]
            );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'myaccount_text_typography',
                    'selector' => '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-content',
                ]
            );

            $this->add_responsive_control(
                'myaccount_content_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'myaccount_content_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'myaccount_alignment',
                [
                    'label' => __( 'Alignment', 'woolentor-pro' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_myaccount_page .woocommerce-MyAccount-content' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $size = $settings['thumbnailsize_size'];
        $image_size = Null;
        if( $size === 'custom' ){
            $image_size = [
                $settings['thumbnailsize_custom_dimension']['width'],
                $settings['thumbnailsize_custom_dimension']['height']
            ];
        }else{
            $image_size = $size;
        }
        $thumbnail = isset( $settings['thumbnail_image']['id'] ) ? wp_get_attachment_image( $settings['thumbnail_image']['id'], $image_size ) : false;

        $userinfo = array(
            'status' => $settings['user_info_show'],
            'image' => $thumbnail
        );

        if ( !Plugin::instance()->editor->is_edit_mode() && ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }
        $this->my_account_content( $settings['navigation_list'], $userinfo, $settings['myaccount_menu_type'] );

    }

    public function my_account_content( $settings, $userinfo, $menutype ){
        $items       = array();
        $menu_list   = array();
        if( isset( $settings ) ){
            foreach ( $settings as $key => $navigation ) {

                $item_key = ( 'customadd' === $navigation['menu_items'] ) ? $navigation['menu_key'] : $navigation['menu_items'];
                $menu_list[$item_key] = array(
                    'title'          => $navigation['menu_title'],
                    'type'           => $navigation['menu_items'],
                    'content_source' => $navigation['content_source']
                );

                if( 'elementor' === $navigation['content_source'] ){
                    $menu_list[$item_key]['content'] = $navigation['content_tmp'];
                    $menu_list[$item_key]['remove_content'] = $navigation['remove_default_content'];
                }elseif( 'custom' === $navigation['content_source'] ){
                    $menu_list[$item_key]['content'] = $navigation['custom_content'];
                    $menu_list[$item_key]['remove_content'] = $navigation['remove_default_content'];
                }else{
                    $menu_list[$item_key]['content'] = '';
                    $menu_list[$item_key]['remove_content'] = 'no';
                }

                if( 'customadd' === $navigation['menu_items'] ){
                    $menu_list[$item_key]['url'] = $navigation['menu_url'];
                }

            }
        }else{
            $items = [
                'dashboard'       => esc_html__( 'Dashboard', 'woolentor-pro' ),
                'orders'          => esc_html__( 'Orders', 'woolentor-pro' ),
                'downloads'       => esc_html__( 'Downloads', 'woolentor-pro' ),
                'edit-address'    => esc_html__( 'Addresses', 'woolentor-pro' ),
                'edit-account'    => esc_html__( 'Account details', 'woolentor-pro' ),
                'customer-logout' => esc_html__( 'Logout', 'woolentor-pro' ),
            ];
        }
        
        new \WooLentor_MyAccount( $menu_list, $userinfo );

        echo '<div class="woolentor_myaccount_page woolentor_myaccount_menu_pos_'.$menutype.'">';
            if( $menutype === 'vtop' || $menutype === 'hleft' ){ do_action( 'woocommerce_account_navigation' );}
            echo '<div class="woocommerce-MyAccount-content">';
                    do_action( 'woocommerce_account_content' );
            echo '</div>';
            if( $menutype === 'vbottom' || $menutype === 'hright' ){ do_action( 'woocommerce_account_navigation' ); }
        echo '</div>';

    }

}