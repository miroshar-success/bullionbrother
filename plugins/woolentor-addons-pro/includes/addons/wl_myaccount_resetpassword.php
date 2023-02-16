<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Myaccount_Resetpassword_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-myaccount-resetpassword-form';
    }

    public function get_title() {
        return __( 'WL: My Account Reset Password Form', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
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
        return ['my account page','account page','account reset','reset form','reset password form'];
    }

    protected function register_controls() {
        
        $this->start_controls_section(
            'content_setting',
            [
                'label' => esc_html__( 'Settings', 'woolentor-pro' ),
            ]
        );

            $this->add_control(
                'new_password_box_label',
                [
                    'label' => __( 'New Password Box Label', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'New Password', 'woolentor-pro' ),
                    'placeholder' => __( 'New Password', 'woolentor-pro' ),
                    'label_block' => true
                ]
            );

            $this->add_control(
                'renew_password_box_label',
                [
                    'label' => __( 'Re-Enter New Password Box Label', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Re-enter new password', 'woolentor-pro' ),
                    'placeholder' => __( 'Re-enter new password', 'woolentor-pro' ),
                    'label_block' => true
                ]
            );

            $this->add_control(
                'button_label',
                [
                    'label' => __( 'Button Label', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Save', 'woolentor-pro' ),
                    'placeholder' => __( 'Save', 'woolentor-pro' ),
                    'label_block' => true
                ]
            );

        $this->end_controls_section();

        // Form label
        $this->start_controls_section(
            'form_label_style',
            array(
                'label' => __( 'Label', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_label_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword .form-row label',
                )
            );

            $this->add_control(
                'form_label_color',
                [
                    'label' => __( 'Label Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword .form-row label' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_label_required_color',
                [
                    'label' => __( 'Required Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword .form-row label span.required' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_label_padding',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword .form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'form_label_align',
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
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword .form-row label' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Input box
        $this->start_controls_section(
            'form_input_box_style',
            array(
                'label' => esc_html__( 'Input Box', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_control(
                'layout_style',
                [
                    'label' => esc_html__( 'Layout', 'woolentor-pro' ),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'inline',
                    'options' => [
                        'block' => [
                            'title' => esc_html__( 'Block', 'woolentor-pro' ),
                            'icon' => 'eicon-editor-list-ul',
                        ],
                        'inline' => [
                            'title' => esc_html__( 'Inline', 'woolentor-pro' ),
                            'icon' => 'eicon-ellipsis-h',
                        ],
                    ]
                ]
            );

            $this->add_control(
                'form_input_box_text_color',
                [
                    'label' => __( 'Text Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword input.input-text' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_input_box_typography',
                    'label'     => esc_html__( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword input.input-text',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_input_box_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword input.input-text',
                ]
            );

            $this->add_responsive_control(
                'form_input_box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'form_input_box_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            
            $this->add_responsive_control(
                'form_input_box_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Button
        $this->start_controls_section(
            'form_button_style',
            array(
                'label' => esc_html__( 'Button', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->start_controls_tabs('form_button_style_tabs');
                
                $this->start_controls_tab(
                    'form_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'form_button_typography',
                            'label'     => __( 'Typography', 'woolentor-pro' ),
                            'selector'  => '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button',
                        )
                    );

                    $this->add_control(
                        'form_button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'form_button_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_button_margin',
                        [
                            'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();
                
                // Hover
                $this->start_controls_tab(
                    'form_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'form_button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button:hover' => 'color: {{VALUE}}; transition:0.4s;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor-myaccount-form-lostpassword button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Form area
        $this->start_controls_section(
            'form_area_style',
            array(
                'label' => esc_html__( 'Form Area', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_area_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword',
                ]
            );

            $this->add_responsive_control(
                'form_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_area_margin',
                [
                    'label' => esc_html__( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator'=>'before',
                ]
            );

            $this->add_responsive_control(
                'form_area_padding',
                [
                    'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor-myaccount-form-lostpassword form.woocommerce-ResetPassword' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],                
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {

        $settings  = $this->get_settings_for_display();
        $new_password_box_label = !empty( $settings['new_password_box_label'] ) ? $settings['new_password_box_label'] : '';
        $renew_password_box_label = !empty( $settings['renew_password_box_label'] ) ? $settings['renew_password_box_label'] : '';
        $button_label    = !empty( $settings['button_label'] ) ? $settings['button_label'] : esc_html__('Save','woolentor-pro');

        $layout_style = $settings['layout_style'];

        if ( Plugin::instance()->editor->is_edit_mode() ) {
            ?>
                <div class="woolentor-myaccount-form-lostpassword">
                    <?php do_action( 'woocommerce_before_reset_password_form' ); ?>
                    <form method="post" class="woocommerce-ResetPassword lost_reset_password">
                        <p class="woocommerce-form-row woocommerce-form-row--first form-row <?php echo ( $layout_style == 'inline' ? 'form-row-first': '' ); ?>">
                            <?php
                                if( !empty( $new_password_box_label ) ){
                                    echo '<label for="password_1">'.esc_html__( $new_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                                }
                            ?>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" />
                        </p>
                        <p class="woocommerce-form-row woocommerce-form-row--last form-row <?php echo ( $layout_style == 'inline' ? 'form-row-last': '' ); ?>">
                            <?php
                                if( !empty( $renew_password_box_label ) ){
                                    echo '<label for="password_2">'.esc_html__( $renew_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                                }
                            ?>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
                        </p>

                        <div class="clear"></div>

                        <?php do_action( 'woocommerce_resetpassword_form' ); ?>

                        <p class="woocommerce-form-row form-row">
                            <input type="hidden" name="wc_reset_password" value="true" />
                            <button type="submit" class="woocommerce-Button button" value="<?php echo esc_attr( $button_label ); ?>"><?php echo esc_html( $button_label ); ?></button>
                        </p>

                        <?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
                    </form>
                    <?php do_action( 'woocommerce_after_reset_password_form' ); ?>
                </div>
            <?php
        }else{
            if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {
                list( $reset_password_id, $reset_password_key ) = array_map( 'wc_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) );
                $userdata               = get_userdata( absint( $reset_password_id ) );
				$reset_password_login   = $userdata ? $userdata->user_login : '';
                ?>
                <div class="woolentor-myaccount-form-lostpassword">
                    <?php do_action( 'woocommerce_before_reset_password_form' ); ?>
                    <form method="post" class="woocommerce-ResetPassword lost_reset_password">
                        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                            <?php
                                if( !empty( $new_password_box_label ) ){
                                    echo '<label for="password_1">'.esc_html__( $new_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                                }
                            ?>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" />
                        </p>
                        <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                            <?php
                                if( !empty( $renew_password_box_label ) ){
                                    echo '<label for="password_2">'.esc_html__( $renew_password_box_label, 'woolentor-pro' ).'&nbsp;<span class="required">*</span></label>';
                                }
                            ?>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
                        </p>

                        <input type="hidden" name="reset_key" value="<?php echo esc_attr( $reset_password_key ); ?>" />
                        <input type="hidden" name="reset_login" value="<?php echo esc_attr( $reset_password_login ); ?>" />

                        <div class="clear"></div>

                        <?php do_action( 'woocommerce_resetpassword_form' ); ?>

                        <p class="woocommerce-form-row form-row">
                            <input type="hidden" name="wc_reset_password" value="true" />
                            <button type="submit" class="woocommerce-Button button" value="<?php echo esc_attr( $button_label ); ?>"><?php echo esc_html( $button_label ); ?></button>
                        </p>

                        <?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
                    </form>
                    <?php do_action( 'woocommerce_after_reset_password_form' ); ?>
                </div>
                <?php
            }
        }
    }

}