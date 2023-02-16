<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Polylang Compability Class
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.3
 */
class Woo_Pr_Polylang {	

	/**
	 * Returns options to translate
	 * Don't call before 'init' to avoid loading WooCommerce translations sooner than WooCommerce
	 *
	 * @since 1.0.3
	 *
	 * @return array
	 */
	static protected function get_options() {
		return array(
			'woo_pr_single_product_message'               			=> array( 'name' => esc_html__( 'Single Product Page Message', 'woopoints' ), 'multiline' => true ),
			'woo_pr_earn_points_cart_message'             			=> array( 'name' => esc_html__( 'Earn Points Cart / Checkout Page Message', 'woopoints' ), 'multiline' => true ),
			'woo_pr_redeem_points_cart_message'             		=> array( 'name' => esc_html__( 'Redeem Points Cart / Checkout Page Message', 'woopoints' ), 'multiline' => true ),
			'woo_pr_guest_checkout_page_message'             		=> array( 'name' => esc_html__( 'Guest User Cart / Checkout Page Message', 'woopoints' ), 'multiline' => true ),
			'woo_pr_guest_checkout_page_buy_message'             	=> array( 'name' => esc_html__( 'Guest User Cart / Checkout Page Buy Message', 'woopoints' ), 'multiline' => true ),
			'woo_pr_guest_user_history_message'						=> array( 'name' => esc_html__( 'Guest User Points History Message', 'woopoints' ), 'multiline' => true ),

		);
	}

	/**
	 * Translate string in frontend
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.3
	 */
	public function woo_pr_translate_strings() {
    	// Options
		foreach ( array_keys( self::get_options() ) as $string ) {
			add_filter( 'option_' . $string, 'pll__' );
		}    	
	}
	
	/**
	 * register settings in backend
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.3
	 */	
	public function woo_pr_register_strings() {
		// Strings as single option
		foreach ( self::get_options() as $string => $arr ) {
			if ( $option = get_option( $string ) ) {
				pll_register_string( $arr['name'], $option, 'Bravo - WooCommerce Points and Rewards', ! empty( $arr['multiline'] ) );
			}
		}              
    }

	/**
	 * Adding Hooks		 
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.3
	 */
	public function add_hooks() {

		if ( PLL() instanceof PLL_Frontend ) {
		    // Translate strings on frontend
		    add_action( 'init', array( $this, 'woo_pr_translate_strings' ), 99 );
		} else {
		    if ( PLL() instanceof PLL_Settings ) {
		        add_action( 'init', array( $this, 'woo_pr_register_strings' ), 99 );		        
		    }   
		}		
	}

}