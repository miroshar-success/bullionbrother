<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( $block['is_editor'] ){
    \WC()->frontend_includes();
    if ( is_null( \WC()->cart ) ) {
        \WC()->session = new \WC_Session_Handler();
        \WC()->session->init();
        \WC()->cart     = new \WC_Cart();
        \WC()->customer = new \WC_Customer(get_current_user_id(), true);
    }
    \WooLentorBlocks\Sample_Data::instance()->add_product_for_empty_cart();
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_cart_table' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

// Collumn
$table_item_list = ( isset( $settings['tableItemList'] ) ? $settings['tableItemList'] : array() );

// Cart Option
$cart_table_opt = array(
    'update_cart_button' => array(
        'enable'    => $settings['show_update_button'] === true ? 'yes' : 'no',
        'button_txt'=> $settings['update_cart_button_txt']
    ),
    'continue_shop_button'=> array(
        'enable'    => $settings['show_continue_button'] === true ? 'yes' : 'no',
        'button_txt'=> $settings['continue_button_txt']
    ),
    'coupon_form' => array(
        'enable'        => $settings['show_coupon_form'] === true ? 'yes' : 'no',
        'button_txt'    => $settings['coupon_form_button_txt'],
        'placeholder'   => $settings['coupon_form_pl_txt']
    ),
    'extra_options' => array(
        'disable_qtn'   => $settings['disable_user_adj_qtn'] === true ? 'yes' : 'no',
        'remove_link'   => $settings['remove_product_link'] === true ? 'yes' : 'no',
        'show_category' => $settings['show_product_category'] === true ? 'yes' : 'no',
        'show_stock'    => $settings['show_product_stock'] === true ? 'yes' : 'no'
    ),
);

echo '<div class="'.implode(' ', $areaClasses ).'">';
    if ( empty( \WC()->cart->cart_contents ) ) {
        wc_get_template( 'cart/cart-empty.php');
    }else{
        wc_get_template( '/cart.php', ['cartitem' => $table_item_list, 'cartopt' => $cart_table_opt ], __DIR__, __DIR__ );
    }
echo '</div>';