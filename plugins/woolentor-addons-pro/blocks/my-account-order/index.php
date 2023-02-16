<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_order' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

if ( $block['is_editor'] !== 'yes' && ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }

echo '<div class="'.implode(' ', $areaClasses ).'">';

    global $wp;
    if( isset( $wp->query_vars['orders'] ) ){
        $value = $wp->query_vars['orders'];
        woocommerce_account_orders( $value );
            
    }elseif( isset( $wp->query_vars['view-order'] ) ){
        $myaccount_url = get_permalink();
        $value = $wp->query_vars['view-order'];
        woocommerce_account_view_order( $value );
        
    }else{
        $value = '';
        woocommerce_account_orders( $value );
    }
    
echo '</div>';