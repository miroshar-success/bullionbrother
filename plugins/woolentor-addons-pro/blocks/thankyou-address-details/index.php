<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_thankyou_address_details' );
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
    $show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
    if ( $show_customer_details ) {
        wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
    }

echo '</div>';