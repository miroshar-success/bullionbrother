<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_thankyou_order_details' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';
    
    global $wp;
            
    if( isset( $wp->query_vars['order-received'] ) ){
        $received_order_id = $wp->query_vars['order-received'];
    }else{
    $received_order_id = woolentorBlocks_get_last_order_id();
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

echo '</div>';