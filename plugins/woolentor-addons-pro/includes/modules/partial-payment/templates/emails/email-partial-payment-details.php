<?php
/**
 * Email Order details Summary
 *
 * This template displays a summary of partial payments
 *
 * @package woolentor\Templates
 * @version 3.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $payment_schedule && !empty( array_column( $payment_schedule['installment'], 'id' ) ) ) {
    ?>
        <h2><?php esc_html_e('Partial payment details', 'woolentor-pro') ?></h2>

        <table class="td" cellspacing="0" cellpadding="6" style="border:1px solid #e5e5e5; vertical-align:middle; width: 100%; margin-bottom: 40px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">

            <thead>
                <tr>
                    <th style="border: 1px solid #e5e5e5;"><?php esc_html_e( 'Payment', 'woolentor-pro' ); ?> </th>
                    <th style="border: 1px solid #e5e5e5;"><?php esc_html_e( 'Payment method', 'woolentor-pro' ); ?> </th>
                    <th style="border: 1px solid #e5e5e5;"><?php esc_html_e( 'Status', 'woolentor-pro' ); ?> </th>
                    <th style="border: 1px solid #e5e5e5;"><?php esc_html_e( 'Amount', 'woolentor-pro' ); ?> </th>
                    <th style="border: 1px solid #e5e5e5;"><?php esc_html_e( 'Action', 'woolentor-pro' ); ?> </th>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ( $payment_schedule['installment'] as $payment ) {
                        
                        $payment_order = false;
                        if (isset( $payment['id'] ) && !empty( $payment['id'] ) ) $payment_order = wc_get_order( $payment['id'] );
                        if ( !$payment_order ) continue;
            
                        $payment_method = $payment_order ? $payment_order->get_payment_method_title() : '-';
                        $status = $payment_order ? wc_get_order_status_name( $payment_order->get_status() ) : '-';

                        ?>
                            <tr>
                                <td style="border: 1px solid #e5e5e5;">
                                    <?php 
                                        foreach ( $payment_order->get_fees() as $fee ) {
                                            echo esc_html( $fee['name'] );
                                        }
                                    ?>
                                </td>
                                <td style="border: 1px solid #e5e5e5;"><?php echo ( $payment_order->get_status() !== 'pending' ) ? $payment_method : ''; ?></td>
                                <td style="border: 1px solid #e5e5e5;"><?php echo $status; ?></td>
                                <td style="border: 1px solid #e5e5e5;"><?php echo wc_price( $payment_order->get_total(), ['currency' => $payment_order->get_currency()] ); ?></td>
                                <td style="border: 1px solid #e5e5e5;">
                                    <?php 
                                        if ( $payment_order->get_status() == 'pending' ) {
                                            ?>
                                                <a href="<?php echo esc_url( $payment_order->get_checkout_payment_url() ); ?>" class="due-payment-button button pay"><?php esc_html_e( 'Pay Due Payment', 'woolentor-pro' ); ?></a>
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

    <?php
}
