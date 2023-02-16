<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Brand_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-brand-logo';
    }

    public function get_title() {
        return __( 'WL: Brand Logo', 'woolentor' );
    }

    public function get_icon() {
        return 'eicon-logo';
    }

    public function get_categories() {
        return ['woolentor-addons'];
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'slick',
            'woolentor-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'slick',
            'woolentor-widgets-scripts',
        ];
    }

    public function get_keywords(){
        return ['brand','brand logo','logo','custom brand','custom logo'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Brand Logo', 'woolentor' ),
            )
        );
            
            $this->add_control(
                'layout',
                [
                    'label' => esc_html__( 'Select Layout', 'woolentor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__('Default','woolentor'),
                    ],
                    'label_block' => true,
                    'description' => sprintf( __( 'Slider layouts are available in the pro version. <a href="%s" target="_blank">Get Pro</a>', 'woolentor' ), esc_url( 'https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/?fd' ) ),
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'brand_title',
                [
                    'label' => esc_html__( 'Brand Title', 'woolentor' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Default title', 'woolentor' ),
                    'placeholder' => esc_html__( 'Type your title here', 'woolentor' ),
                ]
            );

            $repeater->add_control(
                'brand_logo',
                [
                    'label' => esc_html__( 'Choose Image', 'woolentor' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => WOOLENTOR_ADDONS_PL_URL.'assets/images/brand.png',
                    ],
                ]
            );

            $repeater->add_control(
                'brand_link',
                [
                    'label' => esc_html__( 'Brand Link', 'woolentor' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => esc_html__( 'https://your-link.com', 'woolentor' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '',
                        'is_external' => true,
                        'nofollow' => true,
                    ],
                ]
            );

            $this->add_control(
                'brand_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'brand_title' => esc_html__( 'Brand Title', 'woolentor' ),
                            'brand_link' => '',
                            'brand_logo' => WOOLENTOR_ADDONS_PL_URL.'assets/images/brand.png',
                        ]
                    ],
                    'title_field' => '{{{ brand_title }}}',
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'brandsize',
                    'default' => 'thumbnail',
                    'separator' => 'none',
                ]
            );

        $this->end_controls_section();

        /* Brand Options */
        $this->start_controls_section(
            'brand_option',
            array(
                'label' => esc_html__( 'Brand Option', 'woolentor' ),
            )
        );
            $this->add_responsive_control(
                'column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '6',
                    'options' => [
                        '1' => esc_html__( 'One', 'woolentor-pro' ),
                        '2' => esc_html__( 'Two', 'woolentor-pro' ),
                        '3' => esc_html__( 'Three', 'woolentor-pro' ),
                        '4' => esc_html__( 'Four', 'woolentor-pro' ),
                        '5' => esc_html__( 'Five', 'woolentor-pro' ),
                        '6' => esc_html__( 'Six', 'woolentor-pro' ),
                        '7' => esc_html__( 'Seven', 'woolentor-pro' ),
                        '8' => esc_html__( 'Eight', 'woolentor-pro' ),
                        '9' => esc_html__( 'Nine', 'woolentor-pro' ),
                        '10'=> esc_html__( 'Ten', 'woolentor-pro' ),
                    ],
                    'label_block' => true,
                    'prefix_class' => 'wl-columns%s-',
                ]
            );

            $this->add_control(
                'no_gutters',
                [
                    'label' => esc_html__( 'No Gutters', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_responsive_control(
                'item_space',
                [
                    'label' => esc_html__( 'Space', 'woolentor-pro' ),
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
                        'size' => 15,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'padding: 0  {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Slider setting
        $this->start_controls_section(
            'brand_slider',
            [
                'label' => esc_html__( 'Slider Option', 'woolentor' ),
            ]
        );
            
            $this->add_control(
                'slider_option_pro',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div class="elementor-nerd-box">' .
                            '<i class="elementor-nerd-box-icon eicon-hypster"></i>
                            <div class="elementor-nerd-box-title">' .
                                __( 'Slider Option', 'woolentor' ) .
                            '</div>
                            <div class="elementor-nerd-box-message">' .
                                __( 'Purchase our premium version to unlock these pro features!', 'woolentor' ) .
                            '</div>
                            <a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="' . esc_url( 'https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/?fd' ) . '" target="_blank">' .
                                __( 'Go Pro', 'woolentor' ) .
                            '</a>
                            </div>',
                ]
            );

        $this->end_controls_section(); // Slider Option end

        // Brand Style Section
        $this->start_controls_section(
            'brand_style',
            [
                'label' => esc_html__( 'Brand', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'brand_border',
                    'label' => __( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wl-single-brand',
                ]
            );

            $this->add_control(
                'brand_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-single-brand' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'brand_padding',
                [
                    'label' => __( 'Padding', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-single-brand' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'brand_margin',
                [
                    'label' => __( 'Margin', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-single-brand' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'brand_align',
                [
                    'label'   => __( 'Alignment', 'woolentor' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'    => [
                            'title' => __( 'Left', 'woolentor' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-single-brand'   => 'text-align: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Image Style Section
        $this->start_controls_section(
            'brand_image_style',
            [
                'label' => esc_html__( 'Brand Image', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'brand_img_border',
                    'label' => esc_html__( 'Border', 'woolentor' ),
                    'selector' => '{{WRAPPER}} .wl-single-brand img',
                ]
            );

            $this->add_control(
                'brand_img_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-single-brand img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Slider Button style
        $this->start_controls_section(
            'slider_controller_style',
            [
                'label' => esc_html__( 'Slider Controller Style', 'woolentor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
            'slider_controller_style_pro',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-nerd-box">' .
                        '<i class="elementor-nerd-box-icon eicon-hypster"></i>
                        <div class="elementor-nerd-box-title">' .
                            __( 'Slider Controller Style', 'woolentor' ) .
                        '</div>
                        <div class="elementor-nerd-box-message">' .
                            __( 'Purchase our premium version to unlock these pro features!', 'woolentor' ) .
                        '</div>
                        <a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="' . esc_url( 'https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/?fd' ) . '" target="_blank">' .
                            __( 'Go Pro', 'woolentor' ) .
                        '</a>
                        </div>',
            ]

        );
        $this->end_controls_section(); // Tab option end

    }


    protected function render( $instance = [] ) {
        $settings  = $this->get_settings_for_display();
        $column    = $this->get_settings_for_display('column');
        $brands    = $this->get_settings_for_display('brand_list');

        $collumval = 'wl-col-6';
        if( $column !='' ){
            $collumval = 'wl-col-'.$column;
        }

        $size = $settings['brandsize_size'];
        $image_size = Null;
        if( $size === 'custom' ){
            $image_size = [
                $settings['brandsize_custom_dimension']['width'],
                $settings['brandsize_custom_dimension']['height']
            ];
        }else{
            $image_size = $size;
        }
        $default_img = '<img src="'.WOOLENTOR_ADDONS_PL_URL.'assets/images/brand.png'.'" alt="">';

        if( is_array( $brands ) ){
            echo '<div class="wl-row '.( $settings['no_gutters'] === 'yes' ? 'wlno-gutters' : '' ).'">';
            foreach ( $brands as $key => $brand ) {
                if( !empty( $brand['brand_link']['url'] ) ){
                    $target = $brand['brand_link']['is_external'] ? ' target="_blank"' : '';
                    $nofollow = $brand['brand_link']['nofollow'] ? ' rel="nofollow"' : '';
                    $link = '<a href="'.esc_url( $brand['brand_link']['url'] ).'" '.$target.$nofollow.'>';
                }
                if( !empty( $brand['brand_logo']['id'] ) ){
                    $logo = wp_get_attachment_image( $brand['brand_logo']['id'], $image_size );
                }else{
                    $logo = $default_img;
                }
                ?>
                <div class="<?php echo esc_attr( esc_attr( $collumval ) ); ?>">
                    <?php if( !empty( $brand['brand_link']['url'] ) ) echo $link; ?>
                    <div class="wl-single-brand">
                        <?php echo $logo; ?>
                    </div>
                    <?php if( !empty( $brand['brand_link']['url'] ) ) echo '</a>'; ?>
                </div>
                <?php
            }
            echo '</div>';
        }

    }

}
