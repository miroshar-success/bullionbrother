<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] && defined( "WOOLENTOR_ADDONS_PL_PATH_PRO" ) && file_exists( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.my_account.php' ) ){
    include_once( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.my_account.php' );
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';
!empty( $settings['menuPosition'] ) ? $areaClasses[] = 'woolentor_myaccount_menu_pos_'.$settings['menuPosition'] : '';

if ( $block['is_editor'] !== 'yes' && ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }

echo '<div class="'.implode(' ', $areaClasses ).'">';

    $size = $settings['imageSize'];
    $image_size = Null;
    if( $size === 'custom' ){
        $image_size = [
            $settings['thumbnailsize_custom_dimension']['width'],
            $settings['thumbnailsize_custom_dimension']['height']
        ];
    }else{
        $image_size = $size;
    }
    $thumbnail = !empty( $settings['userCustomImage']['id'] ) ? wp_get_attachment_image( $settings['userCustomImage']['id'], $image_size ) : false;

    $userinfo = array(
        'status' => $settings['userInfo'] === true ? 'yes' : 'no',
        'image' => $thumbnail
    );

    $menu_list = array();
    if( isset( $settings['navigationItemList'] ) ){
        foreach ( $settings['navigationItemList'] as $key => $navigation ) {

            $item_key = ( 'customadd' === $navigation['menuKey'] ) ? $navigation['menuCusKey'] : $navigation['menuKey'];
            $menu_list[$item_key] = array(
                'title'          => $navigation['menuTitle'],
                'type'           => $navigation['menuKey'],
                'content_source' => $navigation['contentSource']
            );

            if( 'custom' === $navigation['contentSource'] ){
                $menu_list[$item_key]['content'] = $navigation['customContent'];
                $menu_list[$item_key]['remove_content'] = $navigation['contentRemove'] === true ? 'yes' : 'no';
            }else{
                $menu_list[$item_key]['content'] = '';
                $menu_list[$item_key]['remove_content'] = 'no';
            }

            if( 'customadd' === $navigation['menuKey'] ){
                $menu_list[$item_key]['url'] = $navigation['menuUrl'];
            }

        }
    }

    new \WooLentor_MyAccount( $menu_list, $userinfo );
    
    if( $settings['menuPosition'] === 'vtop' || $settings['menuPosition'] === 'hleft' ){ woocommerce_account_navigation();}
    echo '<div class="woocommerce-MyAccount-content">';
        woocommerce_account_content();
    echo '</div>';
    if( $settings['menuPosition'] === 'vbottom' || $settings['menuPosition'] === 'hright' ){ woocommerce_account_navigation(); }

    
echo '</div>';