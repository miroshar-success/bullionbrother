<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * WooCommerce bundle products Plugin Compability Class
 *
 * @package WooCommerce - Points and Rewards
 * @since 2.2.9
 */

class Woo_Pr_bundle {

    public function __construct() {
	}

    /**
     * Enqueue Public Scripts
     * 
     * Handles to enqueue scripts for public side
     * 
     * @package Woocommerce - Points and Rewards
     * @since 2.2.9
     */
    public function woo_bundle_pr_public_scripts() {
        
        global $post;
        
        wp_register_script( 'woo-bundle-pr-public-script', WOO_PR_INC_URL . '/js/woo-bundle-pr-public.js', array( 'jquery' ), null );
   
        wp_enqueue_script( 'woo-bundle-pr-public-script' );
    }

	/**
     * Calculate Points via Disocunted Amount
     * 
     * Handles to calculate points
     * via discounted amount
     * 
     * @package WooCommerce - Points and Rewards Addon
     * @since 2.2.9
     * */

	public function woo_bundle_pr_calculate_earn_round_points_from_price ($pricevalue,$points,$rate) {
        
        $bundle_id = isset($_POST['bundle_id']) ? $_POST['bundle_id'] : '';
        $variation_id = isset($_POST['variation_id']) ? $_POST['variation_id'] : '';

        if( !empty( $bundle_id ) ) {
            
            $product = wc_get_product( $bundle_id );

            $amount = $product->get_price();

            if( isset( $product) && $product->get_type() == 'bundle' && !empty( $variation_id ) ){

                // Get decimal points option
                $enable_decimal_points = get_option('woo_pr_enable_decimal_points');
                $woo_pr_number_decimal = get_option('woo_pr_number_decimal');

                $product_variation = new WC_Product_Variation($variation_id);

                if (empty($points) || empty($rate)) {
                    $amount = 0;
                }

                // Apply decimal if enabled
                if( $enable_decimal_points=='yes' && !empty($woo_pr_number_decimal) ){
                    $pricevalue = round($amount * ( $points / $rate ), $woo_pr_number_decimal );
                } else {
                    $pricevalue = round($amount * ( $points / $rate ));
                }

                $pricevalue = $amount + $product_variation->price;

            }
        }
    
        return $pricevalue;
        
	}


    /**
     * Callback function to work with individual price option with bundle product
     * 
     * Handles to calculate points based on individual price
     * 
     * @package WooCommerce - Points and Rewards Addon
     * @since 2.2.9
     * */
    public function woo_bundle_pr_calculate_init_earn_points( $earningpoints, $postid ) {

        $product = wc_get_product( $postid );

        if( isset( $product) && $product->get_type() == 'bundle') {

            $bundle = new WC_Product_Bundle( $product );
            $bundle_sells = $product->get_meta( '_bundle_data', true );
            if( !empty( $bundle->min_raw_price) && !empty( $bundle->max_raw_price) ) {
                $earningpoints = array($bundle->min_raw_price, $bundle->max_raw_price);
            }
        }

        return $earningpoints;
    }


    /**
     * Adding Hooks
     *
     * @package WooCommerce - Points and Rewards
     * @since 2.2.9
     */

    public function add_hooks() {
        // added filter for apply discount on cart total
		add_filter('woo_pr_calculate_earn_points_from_price',array($this,'woo_bundle_pr_calculate_earn_round_points_from_price'),10,3);

        //add script to front side for woocommerce bundle product
        add_action( 'wp_enqueue_scripts', array( $this, 'woo_bundle_pr_public_scripts' ) );

        add_filter('woo_pr_single_page_earning_points', array($this,'woo_bundle_pr_calculate_init_earn_points'),10,2);
    }
}