<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wl_Thankyou_Order_Details_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-thankyou-order-details';
    }

    public function get_title() {
        return __( 'WL: Order Details', 'woolentor-pro' );
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
        return ['thankyou','thank you order','order','order details'];
    }

    protected function register_controls() {
        
        // Heading
        $this->start_controls_section(
            'order_details_heading_style',
            array(
                'label' => __( 'Heading', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_control(
                'order_details_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'order_details_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title',
                )
            );

            $this->add_responsive_control(
                'order_details_heading_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'order_details_heading_align',
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
                        '{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Table Content
        $this->start_controls_section(
            'order_details_table_content_style',
            array(
                'label' => __( 'Table Content', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'order_details_table_heading',
                [
                    'label' => __( 'Table Heading', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'order_details_table_heading_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .order_details th' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-order-details .order_details tfoot td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'order_details_table_heading_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-order-details .order_details th, {{WRAPPER}} .woocommerce-order-details .order_details tfoot td',
                )
            );

            $this->add_responsive_control(
                'order_details_table_heading_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .order_details th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} {{WRAPPER}} .woocommerce-order-details .order_details tfoot td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'order_details_table_content_heading',
                [
                    'label' => __( 'Table Content', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'order_details_table_content_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .order_details td' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'order_details_table_content_typography',
                    'label'     => __( 'Typography', 'woolentor-pro' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-order-details .order_details td',
                )
            );

            $this->add_control(
                'order_details_table_content_link_color',
                [
                    'label' => __( 'Link Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .order_details td a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-order-details .order_details td strong' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'order_details_table_content_link_hover_color',
                [
                    'label' => __( 'Link Hover Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .order_details td a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'order_details_table_content_border_color',
                [
                    'label' => __( 'Border Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-order-details .order_details' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-order-details .order_details td' => 'border-color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-order-details .order_details th' => 'border-color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {

        global $wp;
        
        if( isset( $wp->query_vars['order-received'] ) ){
            $received_order_id = $wp->query_vars['order-received'];
        }else{
           $received_order_id = woolentor_get_last_order_id();
        }

        if( !$received_order_id ){ return; }
        
        $order = wc_get_order( $received_order_id );
        $order_id = $order->get_id();
        
        
        if ( ! $order = wc_get_order( $order_id ) ) { return; }
        
        $order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
        $show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
        $show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
        $downloads             = $order->get_downloadable_items();
        $show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();
        
        if ( $show_downloads ) {
            wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
        }
        
        ?>
        <section class="woocommerce-order-details">
            <?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>
        
            <h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Order details', 'woolentor-pro' ); ?></h2>
        
            <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
        
                <thead>
                    <tr>
                        <th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'woolentor-pro' ); ?></th>
                        <th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woolentor-pro' ); ?></th>
                    </tr>
                </thead>
        
                <tbody>
                    <?php
                    do_action( 'woocommerce_order_details_before_order_table_items', $order );
        
                    foreach ( $order_items as $item_id => $item ) {
                        $product = $item->get_product();
                        wc_get_template( 'order/order-details-item.php', array(
                            'order'              => $order,
                            'item_id'            => $item_id,
                            'item'               => $item,
                            'show_purchase_note' => $show_purchase_note,
                            'purchase_note'      => $product ? $product->get_purchase_note() : '',
                            'product'            => $product,
                        ) );
                    }
        
                    do_action( 'woocommerce_order_details_after_order_table_items', $order );
                    ?>
                </tbody>
        
                <tfoot>
                    <?php
                        foreach ( $order->get_order_item_totals() as $key => $total ) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $total['label']; ?></th>
                                <td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : $total['value']; ?></td>
                            </tr>
                            <?php
                        }
                    ?>
                    <?php if ( $order->get_customer_note() ) : ?>
                        <tr>
                            <th><?php esc_html_e( 'Note:', 'woolentor-pro' ); ?></th>
                            <td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
                        </tr>
                    <?php endif; ?>
                </tfoot>
            </table>
        
            <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
        </section>
        
        <?php
    }

}