<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to 
 * different functions 
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 *
 */

/**
 * Get Current User / Passed User ID Points
 * 
 * Handles to get total points of current user / passed user id
 * and return
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 * */
function woo_pr_get_user_points($userid = '') {

    global $current_user;

    //check userid is empty then use current user id
    if (empty($userid))
        $userid = $current_user->ID;

    //get user points from user account
    $user_points = get_user_meta($userid, WOO_PR_META_PREFIX.'userpoints', true);

    //user points
    $user_points = !empty($user_points) ? $user_points : '0';

    return $user_points;
}

/**
 * Add Points to user account
 * 
 * Handles to add points to user account
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 * */
function woo_pr_add_points_to_user($points = 0, $userid = '') {

    global $current_user;

    //check userid is empty then use current user id
    if (empty($userid))
        $userid = $current_user->ID;

    //check points should not empty
    if (!empty($points)) {

        //get user current points
        $user_points = woo_pr_get_user_points($userid);

        //update users points for signup
        update_user_meta($userid, WOO_PR_META_PREFIX.'userpoints', ( $user_points + $points));
    } // end if to check points should not empty
}

/**
 * Minus / Decrease Points from user account
 * 
 * Handles to minus / decrease points from user account
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 * */
function woo_pr_minus_points_from_user($points = 0, $userid = '') {

    global $current_user;

    //check userid is empty then use current user id
    if (empty($userid))
        $userid = $current_user->ID;

    //check points should not empty
    if (!empty($points)) {

        //get user current points
        $user_points = woo_pr_get_user_points($userid);
        $user_points = $user_points - $points;
        $user_points = ( $user_points > 0 ) ? $user_points : 0;
        
        //update users points for signup
        update_user_meta($userid, WOO_PR_META_PREFIX.'userpoints', $user_points);
    } // end if to check points should not empty
}

if ( ! function_exists( 'woocommerce_woo_pr_points_add_to_cart' ) ) {

	/**
	 * Output the simple product add to cart area.
	 */
	function woocommerce_woo_pr_points_add_to_cart() {
		wc_get_template( 'single-product/add-to-cart/points.php' );
	}
}

/**
 * Convert based on base currency
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.3
 * */
function woo_pr_wcm_currency_convert_original( $amount, $current_currency = '', $order_id = '') {

    if( class_exists('WOOMULTI_CURRENCY') ){
        require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-pr-woomulti-currency.php' );
        $Woo_Pr_Woomulti_Currency = new Woo_Pr_Woomulti_Currency();
        $amount = $Woo_Pr_Woomulti_Currency->woo_pr_wcm_woo_multi_currency_convert_original( $amount, $current_currency);
    }

    //Check if Aelia Currency Switcher plugin active
    if( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {

        require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-pr-wc-aelia-currency-switcher.php' );
        $Woo_Pr_Aelia_Currencyswitcher = new Woo_Pr_Aelia_Currencyswitcher();
        $amount = $Woo_Pr_Aelia_Currencyswitcher->woo_pr_wcm_aelia_currency_convert_original( $amount, $current_currency, $order_id);
    }

    //Check if WOOCS - WooCommerce Currency Switcher plugin active
    if( class_exists('WOOCS_STARTER') ){
        require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-pr-woocs-currency-switcher.php' );
        $Woo_Pr_Woocs_Currencyswitcher = new Woo_Pr_Woocs_Currencyswitcher();
        $amount = $Woo_Pr_Woocs_Currencyswitcher->woo_pr_wcm_woocs_currency_convert_original( $amount, $current_currency, $order_id);
    }

    return $amount;
}

/**
 * Convert based on base currency at the time of redeem
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.3
 * */
function woo_pr_wcm_currency_convert( $amount, $current_currency = '') {

    if( class_exists('WOOMULTI_CURRENCY') ){
        require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-pr-woomulti-currency.php' );
        $Woo_Pr_Woomulti_Currency = new Woo_Pr_Woomulti_Currency();
        $amount = $Woo_Pr_Woomulti_Currency->woo_pr_wcm_woo_multi_currency_convert( $amount, $current_currency);
    }

    //Check if Aelia Currency Switcher plugin active
    if( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {

        require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-pr-wc-aelia-currency-switcher.php' );
        $Woo_Pr_Aelia_Currencyswitcher = new Woo_Pr_Aelia_Currencyswitcher();
        $amount = $Woo_Pr_Aelia_Currencyswitcher->woo_pr_wcm_aelia_currency_convert( $amount, $current_currency);
    }

    //Check if WOOCS - WooCommerce Currency Switcher plugin active
    if( class_exists('WOOCS_STARTER') ){
        require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-pr-woocs-currency-switcher.php' );
        $Woo_Pr_Woocs_Currencyswitcher = new Woo_Pr_Woocs_Currencyswitcher();
        $amount = $Woo_Pr_Woocs_Currencyswitcher->woo_pr_wcm_woocs_currency_convert( $amount, $current_currency);
    }

    return $amount;
}




/**
 * check minimum cart total to redeem points
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.3
 * */
function woo_pr_check_min_cart_total_to_redeem_points( $cart_total = 0) {
	
	$return 	= false;
	$min_cart_total = !empty(get_option('woo_pr_minimum_cart_total_redeem'))?get_option('woo_pr_minimum_cart_total_redeem'):0;
	
	if($cart_total >= $min_cart_total){
		$return = true;
	}
	return $return;   
}

/**
 * check minimum cart total to redeem points
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.3
 * */
function woo_pr_check_min_cart_total_to_earn_points( $cart_total = 0) {
	
	$return 	= false;
	$min_cart_total = !empty(get_option('woo_pr_minimum_cart_total_earn'))?get_option('woo_pr_minimum_cart_total_earn'):0;
	
	if($cart_total >= $min_cart_total){
		$return = true;
	}
	return $return;   
}
