<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Cross_Sell_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-cross-sell';
    }
    
    public function get_title() {
        return __( 'WL: Cross Sell', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
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
        return ['cross sell','cross sell product','cross'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_cross_sells',
            [
                'label' => __( 'Cross Sells', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'style',
                [
                    'label' => __( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        '' => __( 'Default', 'woolentor-pro' ),
                        '2' => __( 'Style 2', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'heading',
                [
                    'label' => __( 'Heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::TEXTAREA,
                    'placeholder' => __( 'You may be interested in…', 'woolentor-pro' ),
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );
        
            $this->add_control(
                'limit',
                [
                    'label' => __( 'Limit', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 2,
                    'min' => 1,
                    'max' => 16,
                ]
            );
            
            $this->add_responsive_control(
                'columns',
                [
                    'label' => __( 'Columns', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'prefix_class' => 'woolentorducts-columns%s-',
                    'default' => 2,
                    'min' => 1,
                    'max' => 6,
                    'condition' => [
                        'style' => '',
                    ],
                ]
            );
            
            $this->add_control(
                'orderby',
                [
                    'label' => __( 'Order by', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'rand',
                    'options' => [
                        'rand' => __( 'Random', 'woolentor-pro' ),
                        'date' => __( 'Publish Date', 'woolentor-pro' ),
                        'modified' => __( 'Modified Date', 'woolentor-pro' ),
                        'title' => __( 'Alphabetic', 'woolentor-pro' ),
                        'popularity' => __( 'Popularity', 'woolentor-pro' ),
                        'rating' => __( 'Rate', 'woolentor-pro' ),
                        'price' => __( 'Price', 'woolentor-pro' ),
                    ],
                ]
            );
            
            $this->add_control(
                'order',
                [
                    'label' => __( 'Order', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'desc',
                    'options' => [
                        'desc' => __( 'DESC', 'woolentor-pro' ),
                        'asc' => __( 'ASC', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'hide_product_ratting',
                [
                    'label'     => __( 'Hide Rating', 'woolentor-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wl-cart-cross-sell-2 .wl-product-rating' => 'display: none !important;',
                    ],
                    'condition' => [
                        'style!' => '',
                    ],
                ]
            );
        
        $this->end_controls_section();

        // Heading
        $this->start_controls_section(
            'cross_sell_heading_style',
            array(
                'label' => __( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'cross_sell_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .cross-sells > h2, {{WRAPPER}} .wl-cart-cross-sell-2 > h2',
                )
            );

            $this->add_control(
                'cross_sell_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .cross-sells > h2' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wl-cart-cross-sell-2 > h2' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cross_sell_heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .cross-sells > h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .wl-cart-cross-sell-2 > h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cross_sell_heading_align',
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
                        '{{WRAPPER}} .cross-sells > h2' => 'text-align: {{VALUE}}',
                    ],
                    'condition' => [
                        'style' => '',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'cross_sell_product_heading',
            array(
                'label' => __( 'Product', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
        // Product Title
        $this->add_control(
            'product_title_heading',
            [
                'label' => __( 'Title', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'product_title_typography',
                'selector' => '{{WRAPPER}} .wl-products .wl-product-title a',
            ]
        );

        $this->add_control(
            'product_title_color',
            [
                'label' => __( 'Title Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wl-products .wl-product-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_title_hover_color',
            [
                'label' => __( 'Title Hover Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wl-products .wl-product-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_title_margin',
            [
                'label' => __( 'Margin', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wl-products .wl-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Product Price
        $this->add_control(
            'product_price_heading',
            [
                'label' => __( 'Regular Price', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_regular_price_color',
            [
                'label' => __( 'Regular Price Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wl-products .wl-product-price > span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wl-products .wl-product-price del span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_regular_price_typography',
                'selector' => '{{WRAPPER}} .wl-products .wl-product-price > span,{{WRAPPER}} .wl-products .wl-product-price del span',
            ]
        );

        $this->add_control(
            'heading_tablet',
                [
                'label' => __( 'Sale Price', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'product_sale_price_color',
            [
                'label' => __( 'Sale Price Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wl-products .wl-product-price del span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'product_sale_price_typography',
                'selector' => '{{WRAPPER}} .wl-products .wl-product-price del span',
            ]
        );

        $this->add_responsive_control(
            'product_price_margin',
            [
                'label' => __( 'Margin', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wl-products .wl-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Product Rating
        $this->add_control(
            'product_rating_heading',
            [
                'label' => __( 'Product Rating', 'woolentor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'product_rating_color',
            [
                'label' => __( 'Empty Rating Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wl-products .star-rating' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'product_rating_give_color',
            [
                'label' => __( 'Rating Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wl-products .star-rating span:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_rating_margin',
            [
                'label' => __( 'Margin', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wl-products .wl-product-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

    $this->end_controls_section(); // Style Default End

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $cross_sell = \WC()->cart->get_cross_sells();
        if ( !$cross_sell && Plugin::instance()->editor->is_edit_mode() ) {
            echo '<p>'.esc_html__( 'No cross-sale products are available.','woolentor-pro' ).'</p>';
        }else{
            if( $settings['style'] == '2' ){
                $this->woocommerce_cross_sell_display( $settings['limit'], $settings['columns'], $settings['orderby'], $settings['order'], $settings );
            } else {
                woocommerce_cross_sell_display( $settings['limit'], $settings['columns'], $settings['orderby'], $settings['order'] );
            }
        }
    }

    // Custom woocommerce_cross_sell_display function
    protected function woocommerce_cross_sell_display( $limit = 2, $columns = 2, $orderby = 'rand', $order = 'desc', $config = array() ) {
		if ( is_checkout() ) {
			return;
		}
		// Get visible cross sells then sort them at random.
		$cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );

		wc_set_loop_prop( 'name', 'cross-sells' );
		wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_cross_sells_columns', $columns ) );

		// Handle orderby and limit results.
		$orderby     = apply_filters( 'woocommerce_cross_sells_orderby', $orderby );
		$order       = apply_filters( 'woocommerce_cross_sells_order', $order );
		$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
		$limit       = apply_filters( 'woocommerce_cross_sells_total', $limit );
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;

        wc_get_template( 
            'cart/cross-sells-two.php',
            array(
                'cross_sells'    => $cross_sells,
                'config'         => $config,

				// Not used now, but used in previous version of up-sells.php.
				'posts_per_page' => $limit,
				'orderby'        => $orderby,
				'columns'        => $columns,
            ),
            'wl-woo-templates',
            WOOLENTOR_ADDONS_PL_PATH_PRO. '/wl-woo-templates/'
        );
	}
}