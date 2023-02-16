<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Myaccount_Logout_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-myaccount-logout';
    }

    public function get_title() {
        return __( 'WL: My Account Logout', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-sign-out';
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
        return ['my account page','account page','account logout','logout button','my account logout'];
    }

    protected function register_controls() {
        
        // Style
        $this->start_controls_section(
            'logout_content_style',
            array(
                'label' => __( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->start_controls_tabs('logout_style_tabs');

                $this->start_controls_tab(
                    'logout_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'logout_content_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-customer-logout a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'logout_content_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-customer-logout a' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'logout_content_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .woolentor-customer-logout a',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'logout_content_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-customer-logout a',
                        ]
                    );

                    $this->add_responsive_control(
                        'logout_content_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-customer-logout a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'logout_content_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-customer-logout a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display:inline-block;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'alignment',
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
                            'prefix_class' => 'elementor%s-align-',
                            'default'      => 'left',
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-customer-logout' => 'text-align: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Logout Hover
                $this->start_controls_tab(
                    'logout_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'logout_content_text_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-customer-logout a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'logout_content_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-customer-logout a:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'logout_content_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-customer-logout a:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();
        

    }

    protected function render() {
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
                if( $endpoint == 'customer-logout' ):
                    ?>
                        <div class="woolentor-customer-logout">
                            <a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
                        </div>
                    <?php
                endif;
            endforeach;
        }else{
            if ( ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }
            foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
                if( $endpoint == 'customer-logout' ):
                    ?>
                        <div class="woolentor-customer-logout">
                            <a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
                        </div>
                    <?php
                endif;
            endforeach;
        }
    }

}