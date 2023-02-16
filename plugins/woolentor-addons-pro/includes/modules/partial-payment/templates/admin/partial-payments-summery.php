<?php
/**
 * Order details Summary
 *
 * This template displays a summary of partial payments
 *
 * @package woolentor\Templates
 * @version 3.2.6
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $order && $order->get_type() !== 'woolentor_pp_payment' ) {

    $partial_schedule = $order->get_meta( '_woolentor_payment_schedule', true );
    if ( !is_array( $partial_schedule ) || empty( $partial_schedule ) ){
        echo '<div><h4>'.esc_html__('Partial payment not found.', 'woolentor-pro').'</h4></div>';
    }else{
        ?>
        <table style="width:100%; text-align:left;">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Payment', 'woolentor-pro' ); ?> </th>
                    <th><?php esc_html_e( 'Payment ID', 'woolentor-pro' ); ?> </th>
                    <th><?php esc_html_e( 'Payment method', 'woolentor-pro' ); ?> </th>
                    <th><?php esc_html_e( 'Status', 'woolentor-pro' ); ?> </th>
                    <th><?php esc_html_e( 'Amount', 'woolentor-pro' ); ?> </th>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ( $partial_schedule['installment'] as $payment ) {
                        
                        $payment_order = false;
                        if (isset( $payment['id'] ) && !empty( $payment['id'] ) ) $payment_order = wc_get_order( $payment['id'] );
                        if ( !$payment_order ) continue;
            
                        $payment_method = $payment_order ? $payment_order->get_payment_method_title() : '-';
                        $status = $payment_order ? wc_get_order_status_name( $payment_order->get_status() ) : '-';

                        ?>
                            <tr>
                                <td>
                                    <?php 
                                        foreach ( $payment_order->get_fees() as $fee ) {
                                            echo esc_html( $fee['name'] );
                                        }
                                    ?>
                                </td>
                                <td><a href="<?php echo esc_url( $payment_order->get_edit_order_url() ); ?>"><?php echo $payment_order->get_id(); ?></a></td>
                                <td><?php echo ( $payment_order->get_status() !== 'pending' ) ? $payment_method : ''; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo wc_price( $payment_order->get_total(), ['currency' => $payment_order->get_currency()] ); ?></td>
                            </tr>
                        <?php
            
                    }

                ?>
            </tbody>

        </table>
        <?php

    }
}