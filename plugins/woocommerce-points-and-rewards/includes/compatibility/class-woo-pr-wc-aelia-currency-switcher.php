<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WC Aelia Currency Switcher plugin Compability Class
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.1.1
 */

class Woo_Pr_Aelia_Currencyswitcher {
	/**
	 * Convert based on base currency at the time of redeem
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.1.1
	 * */
	public function woo_pr_wcm_aelia_currency_convert( $amount, $current_currency = '' ){
		
		//Get instance and details
        $CSWitcher = WC_Aelia_CurrencySwitcher::instance();
        $base_currency = $CSWitcher->settings_controller()->base_currency();
        $selected_currency = $CSWitcher->get_selected_currency();

        //Check if currency not switched
        if( ! $selected_currency || $selected_currency == $base_currency ) {
            return $amount;
        }

        //Convert to selected currency
        $amount = $CSWitcher->convert( $amount, $base_currency, $selected_currency );

        return $amount;
	}

	/**
	 * Convert based on base currency
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.1.1
	 * */
	public function woo_pr_wcm_aelia_currency_convert_original( $amount, $current_currency = '', $order_id = '') {

		//Get instance and details
        $CSWitcher = WC_Aelia_CurrencySwitcher::instance();
        $base_currency = $CSWitcher->settings_controller()->base_currency();
        
        if( !empty( $current_currency ) ){
            $selected_currency = $current_currency;
        } else{
            if( is_admin() && ! wp_doing_ajax() ){
                $selected_currency = get_option( 'woocommerce_currency' );
                if( !empty( $order_id)){
                    $order = wc_get_order($order_id);
                    $status = $order->get_status();
                    if( $status == 'processing' && !isset($_POST['order_status'])){
                        $selected_currency = $CSWitcher->get_selected_currency();  
                    }
                } 
            } else{
                $selected_currency = $CSWitcher->get_selected_currency();
            }
        }

        //Check if currency not switched
        if( ! $selected_currency || $selected_currency == $base_currency ) {
            return $amount;
        }

        //Convert to base currency
        $amount = $CSWitcher->convert( $amount, $selected_currency, $base_currency );

        return $amount;
	}
}