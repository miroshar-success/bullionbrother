<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Social_Shere_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-product-social-share';
    }

    public function get_title() {
        return __( 'WL: Product Social Share', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-social-icons';
    }

    public function get_categories() {
        return array( 'woolentor-addons-pro' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return ['elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid','woolentor-widgets-pro'];
    }

    public function get_keywords(){
        return ['social share','product share','product social share','share'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'product_social_share',
            array(
                'label' => __( 'Social Share', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            )
        );
            $this->add_control(
                'social_share_layout_style',
                [
                    'label' => __( 'Layout', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'style-1',
                    'options' => [
                        'style-1' => __( 'Layout One', 'woolentor-pro' ),
                        'style-2' => __( 'Layout Two', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'product_social_title',
                [
                    'label' => __( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Social Share', 'woolentor-pro' ),
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'social_name',
                [
                    'label' => __( 'Layout', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'facebook' => __( 'facebook', 'woolentor-pro' ),
                        'Twitter' => __( 'Twitter', 'woolentor-pro' ),
                        'Pinterest' => __( 'Pinterest', 'woolentor-pro' ),
                        'Google+' => __( 'Google+', 'woolentor-pro' ),
                        'Reddit' => __( 'Reddit', 'woolentor-pro' ),
                        'Linkedin' => __( 'Linkedin', 'woolentor-pro' ),
                        'Skype' => __( 'Skype', 'woolentor-pro' ),
                    ],
                ]
            );

            $repeater->add_control(
                'social_share_text',
                [
                    'label' => __( 'Social Share Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                ]
            );

            $repeater->add_control(
                'social_share_style_icon_heading',
                [
                    'label' => __( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            
            $repeater->start_controls_tabs(
                'style_tabs'
            );

                // Normal Style Tab
                $repeater->start_controls_tab(
                    'style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $repeater->add_control(
                        'product_social_icon_color',
                        [
                            'label'     => __( 'Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share {{CURRENT_ITEM}} a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'product_social_icon_background',
                        [
                            'label'     => __( 'Backgornd Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share {{CURRENT_ITEM}} a' => 'background: {{VALUE}};',
                            ],
                        ]
                    );

                $repeater->end_controls_tab();

                // Hover Style Tab
                $repeater->start_controls_tab(
                    'filter_menu_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );

                    $repeater->add_control(
                        'product_social_icon_color_hover',
                        [
                            'label'     => __( 'Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share {{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $repeater->add_control(
                        'product_social_icon_background_hover',
                        [
                            'label'     => __( 'Background Color', 'woolentor-pro' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share {{CURRENT_ITEM}} a:hover' => 'background: {{VALUE}};',
                            ],
                        ]
                    );

                $repeater->end_controls_tab();
            $repeater->end_controls_tabs();

            $this->add_control(
                'product_social_share_list',
                [
                    'label'   => esc_html__( 'Social Share List', 'woolentor-pro' ),
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'social_name'         => 'facebook',
                            'social_share_text'   => 'Like'
                        ],
                        [
                            'social_name'         => 'Twitter',
                            'social_share_text'   => 'Tweet'
                        ],
                        [
                            'social_name'         => 'Pinterest',
                            'social_share_text'   => 'Save'
                        ],
                        [
                            'social_name'         => 'Google+',
                            'social_share_text'   => 'Share'
                        ],
                        [
                            'social_name'         => 'Reddit',
                            'social_share_text'   => 'Reddit'
                        ],
                    ],
                    'title_field' => '{{{social_name}}}',
                    'condition'=>[
                        'social_share_layout_style' => 'style-2',
                    ],
                ]
            );

        $this->end_controls_section();

        // Social Share Style
        $this->start_controls_section(
            'product_social_area_style',
            array(
                'label' => __( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_responsive_control(
                'product_social_area_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_social_share' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_social_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_social_share' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_social_area_title',
                [
                    'label' => __( 'Title', 'plugin-name' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_social_area_title_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_social_share h2' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'product_social_area_title_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woolentor_product_social_share h2',
                )
            );

            $this->add_responsive_control(
                'product_social_area_title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_product_social_share h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Social Share icon
        $this->start_controls_section(
            'product_social_icon_style',
            array(
                'label' => __( 'Share Icon', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs('product_social_icon_style_tabs');

                $this->start_controls_tab(
                    'product_social_icon_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'product_social_icon_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_social_icon_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_social_icon_size',
                        [
                            'label' => __( 'Font Size', 'woolentor-pro' ),
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
                                'size' => 14,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'product_social_icon_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_product_social_share ul li a',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'product_social_icon_border_radius',
                        [
                            'label' => __( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator'=>'after',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_social_icon_padding',
                        [
                            'label' => __( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_social_icon_width',
                        [
                            'label' => __( 'Width', 'woolentor-pro' ),
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
                                'unit' => 'px',
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_social_icon_margin',
                        [
                            'label' => __( 'Margin', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover Tab
                $this->start_controls_tab(
                    'product_social_icon_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_social_icon_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_social_icon_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_product_social_share ul li a:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'product_social_icon_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_product_social_share ul li a:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        if( woolentor_is_preview_mode() ){
            $product = wc_get_product( woolentor_get_last_product_id() );
        } else{
            global $product;
            $product = wc_get_product();
        }

        if ( empty( $product ) ) {return; }

        $product_title  = get_the_title();
        $product_url    = get_permalink();
        $product_img    = wp_get_attachment_url( get_post_thumbnail_id() );

        if( 'style-1' == $settings['social_share_layout_style']){

            $facebook_url   = 'https://www.facebook.com/sharer/sharer.php?u=' . $product_url;
            $twitter_url    = 'http://twitter.com/intent/tweet?status=' . rawurlencode( $product_title ) . '+' . $product_url;
            $pinterest_url  = 'http://pinterest.com/pin/create/bookmarklet/?media=' . $product_img . '&url=' . $product_url . '&is_video=false&description=' . rawurlencode( $product_title );
            $gplus_url      = 'https://plus.google.com/share?url='. $product_url;
            $reddit_url     = 'https://reddit.com/submit?url={'.  $product_url .'}&title={'. $product_title .'}';

            ?>
                <div class="woolentor_product_social_share <?php echo $settings['social_share_layout_style']; ?>">
                    <?php
                        if( !empty( $settings['product_social_title'] ) ){
                            echo '<h2>'.esc_html( $settings['product_social_title'] ).'</h2>';
                        }
                    ?>
                    <ul>
                        <li><a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="<?php echo esc_url( $gplus_url ); ?>" target="_blank"><i class="fab fa-google-plus-g"></i></a></li>
                        <li><a href="<?php echo esc_url( $pinterest_url ); ?>" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
                        <li><a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="<?php echo esc_url( $reddit_url ); ?>" target="_blank"><i class="fab fa-reddit-alien"></i></a></li>
                    </ul>
                </div>
            <?php
        }else{
            $shocial_share = [
                'facebook' =>[
                    'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $product_url,
                    'icon'=> 'fab fa-facebook-f'
                ],
                'Twitter' => [
                    'url' => 'http://twitter.com/intent/tweet?status=' . rawurlencode( $product_title ) . '+' . $product_url,
                    'icon'=> 'fab fa-twitter'
                ],
                'Pinterest' => [
                    'url' => 'http://pinterest.com/pin/create/bookmarklet/?media=' . $product_img . '&url=' . $product_url . '&is_video=false&description=' . rawurlencode( $product_title ),
                    'icon'=> 'fab fa-pinterest-p'
                ], 
                'Google+' => [
                    'url' => 'https://plus.google.com/share?url='. $product_url,
                    'icon'=> 'fab fa-google-plus-g'
                ],
                'Reddit' => [
                    'url' => 'https://reddit.com/submit?url={'. $product_url .'}&title={'. $product_title .'}',
                    'icon'=> 'fab fa-reddit-alien'
                ],
                'Linkedin' => [
                    'url' => 'http://www.linkedin.com/shareArticle?url='. esc_url($product_url) .'&amp;title='. $product_title,
                    'icon'=> 'fab fa-linkedin-in'
                ],
                'Skype' => [
                    'url' => 'https://web.skype.com/share?url='.urlencode( esc_url($product_url) ),
                    'icon'=> 'fab fa-skype'
                ]
            ];

            ?>
                <div class="woolentor_product_social_share <?php echo $settings['social_share_layout_style']; ?>">
                    <?php
                        if( !empty( $settings['product_social_title'] ) ){
                            echo '<h2>'.esc_html( $settings['product_social_title'] ).'</h2>';
                        }
                    ?>
                    <ul>
                        <?php foreach($settings['product_social_share_list'] as $share_item):
                            $share_item_info = $shocial_share[$share_item['social_name']];
                        ?>
                            <li class="elementor-repeater-item-<?php echo esc_attr($share_item['_id']); ?>">
                                <a href="<?php echo esc_url( $share_item_info['url'] ); ?>" target="_blank">
                                    <i class="<?php echo esc_attr($share_item_info['icon']); ?>"></i>
                                    <?php if(!empty($share_item['social_share_text'])): ?>
                                        <span><?php echo $share_item['social_share_text'];  ?></span>
                                    <?php endif; ?>
                                </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php
        }
    }

}