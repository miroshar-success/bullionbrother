<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Myaccount_Navigation_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-myaccount-navigation';
    }

    public function get_title() {
        return __( 'WL: My Account Navigation', 'woolentor-pro' );
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
                        '{{WRAPPER}} .woolentor-user-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'myaccount_usermeta_link_color',
                [
                    'label' => __( 'Logout Link', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-logout a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'myaccount_usermeta_link_hover_color',
                [
                    'label' => __( 'Logout Link Hover', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-logout a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'myaccount_usermeta_name_typography',
                    'label' => __( 'Name Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-username',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'myaccount_usermeta_logout_typography',
                    'label' => __( 'Logout Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-logout',
                ]
            );

            $this->add_responsive_control(
                'myaccount_usermeta_image_border_radius',
                [
                    'label' => __( 'Image Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-user-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
                        '{{WRAPPER}} .woolentor-user-area' => 'justify-content: {{VALUE}}',
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
                        'horizontal' => [
                            'title' => __( 'Horizontal', 'woolentor-pro' ),
                            'icon'  => 'eicon-h-align-center',
                        ],
                        'vertical' => [
                            'title' => __( 'Vertical', 'woolentor-pro' ),
                            'icon'  => 'eicon-v-align-middle',
                        ],
                    ],
                    'default'     => 'horizontal',
                    'toggle'      => false,
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
                        '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation' => 'text-align: {{VALUE}}',
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
                                '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'myaccount_menu_text_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'exclude' => ['image'],
                            'selector' => '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'myaccount_menu_text_typography',
                            'label' => __( 'Typography', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'myaccount_menu_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'myaccount_menu_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li',
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
                                '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li a:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li.is-active a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'myaccount_menu_text_hover_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'exclude' => ['image'],
                            'selector' => '{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li a:hover,{{WRAPPER}} .woolentor-account-navigation .woocommerce-MyAccount-navigation ul li.is-active a',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

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

        echo '<div class="woolentor-account-navigation woolentor_myaccount_menu_type_'.$settings['myaccount_menu_type'].'">';
            if( $settings['user_info_show'] === 'yes' ){
                $this->navigation_user( $userinfo );
            }
            woocommerce_account_navigation();
        echo '</div>';
        
    }

    // My Account User Info
    protected function navigation_user( $userinfo ){
        $current_user = wp_get_current_user();
        if ( $current_user->display_name ) {
            $name = $current_user->display_name;
        } else {
            $name = esc_html__( 'Welcome!', 'woolentor-pro' );
        }
        $name = apply_filters( 'woolentor_profile_name', $name );
        ?>
            <div class="woolentor-user-area">
                <div class="woolentor-user-image">
                    <?php
                        if( $userinfo['image'] ){
                            echo wp_kses_post( $userinfo['image'] );
                        }else{
                            echo get_avatar( $current_user->user_email, 125 );
                        }
                    ?>
                </div>
                <div class="woolentor-user-info">
                    <span class="woolentor-username"><?php echo esc_attr( $name ); ?></span>
                    <span class="woolentor-logout"><a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>"><?php echo esc_html__( 'Logout', 'woolentor-pro' ); ?></a></span>
                </div>
            </div>
        <?php

    }


}