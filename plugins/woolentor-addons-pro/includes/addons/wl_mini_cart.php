<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Mini_Cart_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-mini-cart';
    }

    public function get_title() {
        return __( 'WL: Mini Cart', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-cart-light';
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
        return ['woolentor','mini cart','cart','side cart'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'mini_cart_header_content',
            [
                'label' => __( 'Mini Cart', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            
            $this->add_control(
                'content_type',
                [
                    'label' => __( 'Content Type', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'header',
                    'options' => [
                        'header'      => __( 'Header', 'woolentor-pro' ),
                        'bodycontent' => __( 'Body Content', 'woolentor-pro' ),
                        'footer'      => __( 'Footer', 'woolentor-pro' ),
                    ],
                    'label_block'=>true,
                ]
            );

            // Header
            $this->add_control(
                'header_title',
                [
                    'label' => __( 'Header Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Your Cart', 'woolentor-pro' ),
                    'condition'=>[
                        'content_type'  =>'header',
                    ],
                    'label_block' =>true,
                ]
            );

            $this->add_control(
                'cross_icon',
                [
                    'label' => __( 'Cross Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-times',
                        'library' => 'solid',
                    ],
                    'fa4compatibility' => 'crossicon',
                    'condition'=>[
                        'content_type'  =>'header',
                    ],
                ]
            );

            // Footer
            $this->add_control(
                'footer_sub_total',
                [
                    'label' => __( 'Sub Total Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Sub Total', 'woolentor-pro' ),
                    'condition'=>[
                        'content_type'  =>'footer',
                    ],
                    'label_block' =>true,
                ]
            );

            $this->add_control(
                'cart_btn_txt',
                [
                    'label' => __( 'Cart Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'View Cart', 'woolentor-pro' ),
                    'condition'=>[
                        'content_type'  =>'footer',
                    ],
                    'label_block' =>true,
                ]
            );

            $this->add_control(
                'checkout_btn_txt',
                [
                    'label' => __( 'Checkout Button Text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Checkout', 'woolentor-pro' ),
                    'condition'=>[
                        'content_type'  =>'footer',
                    ],
                    'label_block' =>true,
                ]
            );

            // Body
            $this->add_control(
                'footer_empty_text',
                [
                    'label' => __( 'Empty Cart text', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Your Cart Is Empty', 'woolentor-pro' ),
                    'condition'=>[
                        'content_type'  =>'bodycontent',
                    ],
                    'label_block' =>true,
                ]
            );

        $this->end_controls_section();


        // Header Style Section
        $this->start_controls_section(
            'header_style_section',
            [
                'label' => __( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'content_type'  =>'header',
                ],
            ]
        );
            
            $this->add_control(
                'title_heading',
                [
                    'label' => __( 'Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label' => __( 'Title Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_header h2' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_header h2',
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_header h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cross_heading',
                [
                    'label' => __( 'Cross Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'cross_icon[value]!' => '',
                    ],
                ]
            );

            $this->add_control(
                'cross_icon_color',
                [
                    'label' => __( 'Icon Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_close i' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'cross_icon[value]!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cross_icon_size',
                [
                    'label' => __( 'Icon Size', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_close i' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .woolentor_mini_cart_close svg *' => 'width: {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'cross_icon[value]!' => '',
                    ],
                ]
            );

            $this->add_control(
                'title_area_heading',
                [
                    'label' => __( 'Title Area', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'title_area_padding',
                [
                    'label' => __( 'Area Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'title_area_border',
                    'label' => __( 'Title Area Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_header',
                ]
            );

        $this->end_controls_section();

        // Footer Style Section
        $this->start_controls_section(
            'footer_style_section',
            [
                'label' => __( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'content_type'  =>'footer',
                ],
            ]
        );

            $this->add_control(
                'footer_area_heading',
                [
                    'label' => __( 'Footer Area', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'footer_area_padding',
                [
                    'label' => __( 'Area Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'footer_area_border',
                    'label' => __( 'Area Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_footer',
                ]
            );

            $this->add_control(
                'footer_subtotal_heading',
                [
                    'label' => __( 'Sub Total', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'subtotal_color',
                [
                    'label' => __( 'Sub Total Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_sub_total' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'subtotal_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_sub_total',
                ]
            );

            $this->add_responsive_control(
                'subtotal_alignment',
                [
                    'label'   => __( 'Alignment', 'woolentor-pro' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left'=> [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'default'     => 'right',
                    'toggle'      => false,
                    'prefix_class' => 'woolentor-alignment%s-',
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_sub_total'=>'text-align: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_control(
                'footer_button_heading',
                [
                    'label' => __( 'Button', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_button_area a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_button_area a.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_button_area a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('button_style_tabs');

                $this->start_controls_tab(
                    'button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'woolentor-pro' ),
                    ]
                );
                    
                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_button_area a.button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woolentor_button_area a.button,.woocommerce {{WRAPPER}} .woolentor_button_area a.button',
                            'exclude'=>['image'],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_button_area a.button',
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'woolentor-pro' ),
                    ]
                );
                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => __( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .woolentor_button_area a.button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'button_hover_background',
                            'label' => __( 'Background', 'woolentor-pro' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .woolentor_button_area a.button:hover,.woocommerce {{WRAPPER}} .woolentor_button_area a.button:hover,{{WRAPPER}} .woolentor_button_area a::before',
                            'exclude'=>['image'],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .woolentor_button_area a.button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Content Style Section
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __( 'Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'content_type'  =>'bodycontent',
                ],
            ]
        );
            
            $this->add_control(
                'product_title_heading',
                [
                    'label' => __( 'Product Title', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label' => __( 'Title Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_content ul li a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'product_title_hover_color',
                [
                    'label' => __( 'Title Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_content ul li a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_title_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_mini_title h3',
                ]
            );

            $this->add_responsive_control(
                'product_title_margin',
                [
                    'label' => __( 'Title Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_mini_title h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_qtn_price_heading',
                [
                    'label' => __( 'Product quantity/price', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_qtn_price_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_mini_title span' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_qtn_price_typography',
                    'label' => __( 'Typography', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_mini_title span',
                ]
            );

            $this->add_control(
                'product_image_heading',
                [
                    'label' => __( 'Image', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_image_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_mini_cart_img img',
                ]
            );

            $this->add_responsive_control(
                'product_image_margin',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_mini_cart_img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_cross_heading',
                [
                    'label' => __( 'Cross Icon', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_cross_icon_color',
                [
                    'label' => __( 'Icon Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_del' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'product_cross_icon_hover_color',
                [
                    'label' => __( 'Icon Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_del:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'product_cross_bg',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_del',
                    'exclude'=>['image'],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'product_cross_bg_hover',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woolentor_mini_cart_content ul li .woolentor_del:hover',
                    'exclude'=>['image'],
                    'fields_options'=>[
                        'background'=>[
                            'label'=> __( 'Hover Background', 'woolentor-pro' ),
                        ]
                    ]
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        if( 'header' === $settings['content_type'] ){
            $title = !empty( $settings['header_title'] ) ? sprintf( "<h2>%s</h2>", $settings['header_title'] ) : '';
            $icon = !empty( $settings['cross_icon']['value'] ) ? woolentor_render_icon( $settings, 'cross_icon', 'crossicon' ) : '&#10006;';
            $close_icon = sprintf( "<span class='woolentor_mini_cart_close'>%s</span>", $icon );
            echo sprintf('<div class="woolentor_mini_cart_header">%1$s %2$s</div>',$title, $close_icon );
        }elseif( 'footer' === $settings['content_type'] ){

            $subtotal_txt = !empty( $settings['footer_sub_total'] ) ? $settings['footer_sub_total'] : '';
            $viewcart_btn = !empty( $settings['cart_btn_txt'] ) ? $settings['cart_btn_txt'] : '';
            $checkout_btn = !empty( $settings['checkout_btn_txt'] ) ? $settings['checkout_btn_txt'] : '';

            ?>
                <div class="woolentor_mini_cart_footer">
                    <?php if( !\WC()->cart->is_empty() ):?>
                        <span class="woolentor_sub_total">
                            <span><?php esc_html_e( $subtotal_txt, 'woolentor-pro' ); ?></span>
                            <?php echo \WC()->cart->get_cart_subtotal(); ?>
                        </span>
                    <?php endif; ?>
                    <div class="woolentor_button_area">
                        <a href="<?php echo wc_get_cart_url(); ?>" class="button btn woolentor_cart">
                            <?php esc_html_e( $viewcart_btn, 'woolentor-pro' ); ?>
                        </a>
                        <a  href="<?php echo wc_get_checkout_url(); ?>" class="button btn woolentor_checkout">
                            <?php esc_html_e( $checkout_btn, 'woolentor-pro' ); ?>
                        </a>
                    </div>
                </div>
            <?php
        }else{
            $empty_cart_body = !empty( $settings['footer_empty_text'] ) ? $settings['footer_empty_text'] : '';
            ?>
            <div class="woolentor_mini_cart_content">
                <?php if( \WC()->cart->is_empty() ): ?>
                    <p class="woolentor_empty_cart_body"><?php esc_html_e( $empty_cart_body, 'woolentor-pro' ); ?></p>
                <?php else:?>
                    <ul>
                    <?php
                        foreach ( \WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                            $product_name =  apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
                            
                            $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

                            $product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                    ?>
                        <li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?> woolentor_mini_cart_product-wrap">

                            <?php if ( ! $_product->is_visible() ) : ?>
                            <div class="woolentor_mini_cart_img">
                                <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                                <?php
                                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                        '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&#10005;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        __( 'Remove this item', 'woolentor-pro' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $cart_item_key ),
                                        esc_attr( $_product->get_sku() )
                                    ), $cart_item_key );
                                ?>
                            </div>
                            <?php else : ?>
                                <div class="woolentor_mini_cart_img">
                                    <a href="<?php echo esc_url( $product_permalink ); ?>">
                                        <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                                    </a>
                                    <?php
                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                            '<a href="%s" class="woolentor_del remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&#10005;</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            __( 'Remove this item', 'woolentor-pro' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $cart_item_key ),
                                            esc_attr( $_product->get_sku() )
                                        ), $cart_item_key );
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div class="woolentor_cart_single_content">
                                <div class="woolentor_mini_title">
                                    <h3><?php echo $product_name;?></h3>
                                    <span><?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?></span>
                                </div>
                            </div>
                            
                        </li>
                        <?php } } ?>

                    </ul>
                <?php endif;?>
            </div>
            <?php
        }

    }

}