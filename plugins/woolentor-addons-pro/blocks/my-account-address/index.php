<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_address' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

if ( $block['is_editor'] !== 'yes' && ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }

echo '<div class="'.implode(' ', $areaClasses ).'">';

    global $wp;
    $type = '';
    if( isset( $wp->query_vars['edit-address'] ) ){
        $type = $wp->query_vars['edit-address'];
    }else{ $type = wc_edit_address_i18n( sanitize_title( $type ), true ); }
    echo '<div class="my-accouunt-form-edit-address">';
        \WC_Shortcode_My_Account::edit_address( $type );
    echo '</div>';
    
echo '</div>';