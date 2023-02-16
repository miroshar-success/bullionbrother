<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WooCommerce Multi Currency Premium by VillaTheme Compability Class
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.1.1
 */

class Woo_Pr_Woomulti_Currency {

	/**
	 * Convert based on base currency at the time of redeem
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.1.1
	 * */
	public function woo_pr_wcm_woo_multi_currency_convert( $amount, $current_currency = '' ){

		$wcm_currency_setting = new WOOMULTI_CURRENCY_Data();

        /*Check currency*/
        $selected_currencies = $wcm_currency_setting->get_list_currencies();

        if( empty($current_currency) || !isset($selected_currencies[$current_currency]) ){
            $current_currency    = $wcm_currency_setting->get_current_currency();
        }

        if ( ! $current_currency ) {

            return $amount;
        }

        if ( $amount ) {

            if ( $current_currency && isset( $selected_currencies[$current_currency] ) ) {
                $amount = $amount * $selected_currencies[$current_currency]['rate'];
            }

        }

        return $amount;
	}

    /**
     * Convert based on base currency
     * 
     * @package WooCommerce - Points and Rewards
     * @since 1.1.1
     * */
    public function woo_pr_wcm_woo_multi_currency_convert_original( $amount, $current_currency = '') {

        $wcm_currency_setting = new WOOMULTI_CURRENCY_Data();
        /*Check currency*/
        $selected_currencies = $wcm_currency_setting->get_list_currencies();
        $default_currency = $wcm_currency_setting->get_default_currency();

        if( empty($current_currency) || !isset($selected_currencies[$current_currency]) ){
            $current_currency    = $wcm_currency_setting->get_current_currency();
        }

        if ( ! $current_currency ) {

            return $amount;
        }
        if ( $current_currency == $default_currency ) {

            return $amount;
        }

        if ( $amount ) {

            if ( $current_currency && isset( $selected_currencies[$current_currency] ) ) {
                $amount = $amount / $selected_currencies[$current_currency]['rate'];
            }

        }

        return $amount;
    }
}