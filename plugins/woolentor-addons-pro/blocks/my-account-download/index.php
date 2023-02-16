<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] ){
    \WC()->frontend_includes();
    \WC()->customer = new \WC_Customer(get_current_user_id(), true);
    
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_download' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

if ( $block['is_editor'] !== 'yes' && ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }

echo '<div class="'.implode(' ', $areaClasses ).'">';

    if ( is_account_page() || $block['is_editor'] == 'yes') {
        woocommerce_account_downloads();
    }
    
echo '</div>';