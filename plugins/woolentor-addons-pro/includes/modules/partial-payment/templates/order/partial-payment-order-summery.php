<?php
/**
 * Order details Summary
 *
 * This template displays a summary of partial payments
 *
 * @package woolentor\Templates
 * @version 3.2.6
 */


if ( !defined('ABSPATH') ) {
    exit;
}

?> 
<h2 class="woocommerce-column__title"> <?php esc_html_e( 'Partial payments summary', 'woolentor-pro' ); ?></h2>
<table class="woocommerce-table  woocommerce_partial_payment_parent_order_summary">

    <thead>
        <tr>
            <th ><?php esc_html_e('Payment', 'woolentor-pro'); ?> </th>
            <th ><?php esc_html_e('Payment ID', 'woolentor-pro'); ?> </th>
            <th><?php esc_html_e('Status', 'woolentor-pro'); ?> </th>
            <th><?php esc_html_e('Amount', 'woolentor-pro'); ?> </th>
            <th><?php esc_html_e('Action', 'woolentor-pro'); ?> </th>
        </tr>
    </thead>

    <tbody>
    <?php
        foreach ( $orders as $order ){
            ?>
                <tr>
                    <td>
                        <?php 
                            foreach ( $order->get_fees() as $fee ) {
                                echo esc_html( $fee['name'] );
                            }
                        ?>
                    </td>
                    <td><?php echo $order->get_id(); ?></td>
                    <td><?php echo $order->get_status(); ?></td>
                    <td><?php echo wc_price( $order->get_total(), ['currency' => $order->get_currency()] ) ?></td>
                    <td>
                    <?php 
                        if ( $order->get_status() == 'pending' ) {
                            ?>
                                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="due-payment-button button pay"><?php esc_html_e( 'Pay Due Payment', 'woolentor-pro' ); ?></a>
                            <?php
                        }
                    ?>
                    </td>
                </tr>
            <?php
        }
    ?>
    </tbody>

</table>
