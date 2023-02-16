<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_thankyou_order' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';

    global $wp;
    $order_thankyou_message = !empty( $settings['thankyoumessage'] ) ? $settings['thankyoumessage'] : '';

    if( isset($wp->query_vars['order-received']) ){
        $received_order_id = $wp->query_vars['order-received'];
    }else{
        $received_order_id = woolentorBlocks_get_last_order_id();
    }
    $order = wc_get_order( $received_order_id );

    if ( $order ) : ?>

        <?php if ( $order->has_status( 'failed' ) ) : ?>
    
            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woolentor-pro' ); ?></p>
    
            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woolentor-pro' ) ?></a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woolentor-pro' ); ?></a>
                <?php endif; ?>
            </p>
    
        <?php else : ?>

            <?php if( $order_thankyou_message ): ?>
                <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text_for_woolentor', $order_thankyou_message, $order ); ?></p>
            <?php endif; ?>
    
            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
    
                <li class="woocommerce-order-overview__order order">
                    <?php esc_html_e( 'Order number:', 'woolentor-pro' ); ?>
                    <strong><?php echo $order->get_order_number(); ?></strong>
                </li>
    
                <li class="woocommerce-order-overview__date date">
                    <?php esc_html_e( 'Date:', 'woolentor-pro' ); ?>
                    <strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
                </li>
    
                <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                    <li class="woocommerce-order-overview__email email">
                        <?php esc_html_e( 'Email:', 'woolentor-pro' ); ?>
                        <strong><?php echo $order->get_billing_email(); ?></strong>
                    </li>
                <?php endif; ?>
    
                <li class="woocommerce-order-overview__total total">
                    <?php esc_html_e( 'Total:', 'woolentor-pro' ); ?>
                    <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                </li>
    
                <?php if ( $order->get_payment_method_title() ) : ?>
                    <li class="woocommerce-order-overview__payment-method method">
                        <?php esc_html_e( 'Payment method:', 'woolentor-pro' ); ?>
                        <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                    </li>
                <?php endif; ?>
    
            </ul>

        <?php endif; ?>

        <div class="woocommerce-thankyou-order-payment-info-message">
            <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        </div>

    <?php else : ?>

        <?php if( $order_thankyou_message ): ?>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text_for_woolentor', $order_thankyou_message, null ); ?></p>
        <?php endif; ?>
    
    <?php endif;

echo '</div>';