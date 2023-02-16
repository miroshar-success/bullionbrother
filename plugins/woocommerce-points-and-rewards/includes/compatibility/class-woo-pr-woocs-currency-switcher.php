<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WOOCS - WooCommerce Currency Switcher plugin Compability Class
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.1.1
 */

class Woo_Pr_Woocs_Currencyswitcher {

	/**
	 * Convert based on base currency at the time of redeem
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.1.1
	 * */
	public function woo_pr_wcm_woocs_currency_convert( $amount, $current_currency = '' ){

		global $WOOCS;

        $currencies = $WOOCS->get_currencies();

        if (!empty($currencies) && is_array($currencies) && isset( $currencies[$WOOCS->current_currency] ) ) {

            $selected_cur_rate = $currencies[$WOOCS->current_currency]['rate'];
            $amount = $amount * $selected_cur_rate;
        }

        return $amount;
	}

	/**
	 * Convert based on base currency
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.1.1
	 * */
	public function woo_pr_wcm_woocs_currency_convert_original( $amount, $current_currency = '', $order_id = '') {

		global $WOOCS;

        $currencies = $WOOCS->get_currencies();
        $default_currency = '';

        if (!empty($currencies) AND is_array($currencies)) {

            foreach ( $currencies as $key => $currency) {
                if ($currency['is_etalon']) {
                    $default_currency = $key;
                    break;
                }
            }
        }

        $default_currency = !empty( $default_currency ) ? $default_currency : get_woocommerce_currency();

        if( empty($current_currency) ){
            if( is_admin() && ! wp_doing_ajax() ){

                $current_currency = get_option( 'woocommerce_currency' );
                if( !empty( $order_id)){
                    $order = wc_get_order($order_id);
                    $status = $order->get_status();
                    if( $status == 'processing'){
                        $current_currency = $WOOCS->current_currency;  
                    }
                }
            } else{
                $current_currency = $WOOCS->current_currency;
            }

        }            

        if ( ! $current_currency || $current_currency == $default_currency ) {

            return $amount;
        }

        
        if( $amount && !empty( $currencies ) ) {

            if ( !empty( $current_currency ) && isset( $currencies[$current_currency] ) ) {
                $amount = $amount / $currencies[$current_currency]['rate'];
            }
        }

        return $amount;
	}
}