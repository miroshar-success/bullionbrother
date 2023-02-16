<?php
/**
 * Plugin Name: Bravo - WooCommerce Points and Rewards
 * Plugin URI: https://wpwebelite.com/
 * Description: With Points and Rewards Extension, you can reward customers for purchases and other actions with points which can be redeemed for discounts.
 * Version: 2.3.0
 * Author: WPWeb
 * Author URI: https://wpwebelite.com
 * Text Domain: woopoints
 * Domain Path: languages
 * 
 * WC tested up to: 5.5.2
 * Tested up to: 6.0
 *
 * @package WooCommerce - Points and Rewards
 * @category Core
 * @author WPWeb
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Basic plugin definitions 
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
if (!defined('WOO_PR_DIR')) {
    define('WOO_PR_DIR', dirname(__FILE__));      // Plugin dir
}
if (!defined('WOO_PR_VERSION')) {
    define('WOO_PR_VERSION', '2.3.0');      // Plugin Version
}
if (!defined('WOO_PR_URL')) {
    define('WOO_PR_URL', plugin_dir_url(__FILE__));   // Plugin url
}
if (!defined('WOO_PR_INC_DIR')) {
    define('WOO_PR_INC_DIR', WOO_PR_DIR . '/includes');   // Plugin include dir
}
if (!defined('WOO_PR_INC_URL')) {
    define('WOO_PR_INC_URL', WOO_PR_URL . 'includes');    // Plugin include url
}
if (!defined('WOO_PR_ADMIN_DIR')) {
    define('WOO_PR_ADMIN_DIR', WOO_PR_INC_DIR . '/admin');  // Plugin admin dir
}
if (!defined('WOO_PR_PREFIX')) {
    define('WOO_PR_PREFIX', 'woo_pr'); // Plugin Prefix
}
if (!defined('WOO_PR_VAR_PREFIX')) {
    define('WOO_PR_VAR_PREFIX', 'woo_pr'); // Variable Prefix
}
if( !defined( 'WOO_PR_META_PREFIX' ) ) {
    define( 'WOO_PR_META_PREFIX', '_woo_pr_' ); // meta box prefix
}
if (!defined('WOO_POINTS_IMG_URL')) {
    define('WOO_POINTS_IMG_URL', WOO_PR_URL . 'includes/images'); // plugin image url
}
if (!defined('WOO_POINTS_BASENAME')) {
    define('WOO_POINTS_BASENAME', basename(WOO_PR_DIR)); //points and rewards basename
}
if (!defined('WOO_POINTS_LOG_POST_TYPE')) {
    define('WOO_POINTS_LOG_POST_TYPE', 'woopointslog'); //post type for points log
}
if (!defined('WOO_POINTS_PLUGIN_KEY')) {
	define('WOO_POINTS_PLUGIN_KEY', 'woopar');
}
// Required Wpweb updater functions file
if ( ! function_exists( 'wpweb_updater_install' ) ) {
	require_once( 'includes/wpweb-upd-functions.php' );
}


/**
 * Admin notices
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
*/
function woo_pr_activation_admin_notices() {
    
    if ( ! class_exists( 'Woocommerce' ) ) {
        
		echo '<div class="error">';
		echo "<p><strong>" . esc_html__( 'Woocommerce needs to be activated to be able to use the Points and Rewards.', 'woopoints' ) . "</strong></p>";
		echo '</div>';
    }
}

/**
 * Check Woocommerce Plugin
 *
 * Handles to check Woocommerce plugin
 * if not activated then deactivate our plugin
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
function woo_pr_woocommerce_check_activation() {
    
    if ( ! class_exists( 'Woocommerce' ) ) {
        // is this plugin active?
        if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
            // deactivate the plugin
            deactivate_plugins( plugin_basename( __FILE__ ) );
            // unset activation notice
            unset( $_GET[ 'activate' ] );
            // display notice
            add_action( 'admin_notices', 'woo_pr_activation_admin_notices' );
        }
    }
}
//Check Woocommerce plugin is Activated or not
add_action( 'admin_init', 'woo_pr_woocommerce_check_activation' );

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
function woo_pr_load_text_domain() {

    // Set filter for plugin's languages directory
    $woo_pr_lang_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
    $woo_pr_lang_dir = apply_filters('woo_pr_languages_directory', $woo_pr_lang_dir);

    // Traditional WordPress plugin locale filter
    $locale = apply_filters('plugin_locale', get_locale(), 'woopoints');
    $mofile = sprintf('%1$s-%2$s.mo', 'woopoints', $locale);

    // Setup paths to current locale file
    $mofile_local = $woo_pr_lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/' . WOO_POINTS_BASENAME . '/' . $mofile;

    if (file_exists($mofile_global)) { // Look in global /wp-content/languages/woocommerce-points-and-rewards folder
        load_textdomain('woopoints', $mofile_global);
    } elseif (file_exists($mofile_local)) { // Look in local /wp-content/plugins/woocommerce-points-and-rewards/languages/ folder
        load_textdomain('woopoints', $mofile_local);
    } else { // Load the default language files
        load_plugin_textdomain('woopoints', false, $woo_pr_lang_dir);
    }
}



// loads the admin functions file
require_once ( WOO_PR_INC_DIR . '/admin/woo-pr-admin-function.php' );
// Registring Post type functionality
require_once( WOO_PR_INC_DIR . '/woo-pr-post-type.php' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
register_activation_hook(__FILE__, 'woo_pr_install');

function woo_pr_install() {
    
    //register post type
    woo_pr_register_post_types();
    
    //IMP Call of Function
    //Need to call when custom post type is being used in plugin
    flush_rewrite_rules();
    
    // Flush Cron Jobs
    wp_clear_scheduled_hook( 'woo_pr_expiration_points' );
    
    // Schedule Cron
    if ( !wp_next_scheduled('woo_pr_expiration_points') ) {
        $utc_timestamp = time();
        $local_time = current_time( 'timestamp' ); // to get current local time
        
        // Schedule CRON events starting at user defined hour and periodically thereafter
        $schedule_time  = mktime( '1', '0', '0', date('m', $local_time), date('d', $local_time), date('Y', $local_time) );
        
        // get difference 
        $diff       = ( $schedule_time - $local_time );
        $utc_timestamp  = $utc_timestamp + $diff;

        wp_schedule_event( $utc_timestamp, 'twicedaily', 'woo_pr_expiration_points' );
    }

    //get option for when plugin is activating first time
    $woo_pr_set_option = get_option( 'woo_pr_set_option' );
    
    if( empty( $woo_pr_set_option ) ) { //check plugin version option
        
        //update default options
        woo_pr_default_settings();
        
        //update plugin version to option
        update_option( 'woo_pr_set_option', '1.0' );
        update_option( 'woo_pr_plugin_version', WOO_PR_VERSION );
    }

    // added code since 1.1.2
    $woo_pr_set_option = get_option( 'woo_pr_set_option' );
    
    if( $woo_pr_set_option == '1.0' ) {

        // set message to show on top of the user points history page
        $woo_pr_user_history_points_message = get_option( 'woo_pr_user_history_points_message' );
        
        if( empty( $woo_pr_user_history_points_message ) ) {
            
            update_option( 'woo_pr_user_history_points_message', esc_html__('You have {points} {points_label}, which are worth a discount of {points_amount} amount.', 'woopoints') );
        }

        update_option( 'woo_pr_set_option', '1.1.0' );
    }
	
	
	$woo_pr_set_option = get_option( 'woo_pr_set_option' );
    
    if( $woo_pr_set_option == '1.1.0' ) {
		
        // set message to show on top of the user points history page
        $woo_pr_enable_expire_point_email = get_option( 'woo_pr_enable_expire_point_email' );
        $woo_pr_expire_point_email_before_day = get_option( 'woo_pr_expire_point_email_before_day' );
        $woo_pr_expire_point_email_subject = get_option( 'woo_pr_expire_point_email_subject' );
        $woo_pr_expire_point_email_content = get_option( 'woo_pr_expire_point_email_content' );
        $woo_pr_enable_earn_points_email = get_option( 'woo_pr_enable_earn_points_email' );
        $woo_pr_earn_point_subject = get_option( 'woo_pr_earn_point_subject' );
        $woo_pr_earn_point_email_content = get_option( 'woo_pr_earn_point_email_content' );
        $woo_pr_enable_redeem_email = get_option( 'woo_pr_enable_redeem_email' );
        $woo_pr_redeem_point_email_subject = get_option( 'woo_pr_redeem_point_email_subject' );
        $woo_pr_redeem_point_email_content = get_option( 'woo_pr_redeem_point_email_content' );
        
		
        if( empty( $woo_pr_enable_expire_point_email ) ) {            
            update_option( 'woo_pr_enable_expire_point_email','no');
		}
		if( empty( $woo_pr_expire_point_email_before_day ) ) {            
            update_option( 'woo_pr_expire_point_email_before_day',1);
		}
		if( empty( $woo_pr_expire_point_email_subject ) ) {            
            update_option( 'woo_pr_expire_point_email_subject',esc_html__('Your Points on {site_url} Are About to Expire','woopoints'));
		}
		
		if( empty( $woo_pr_enable_earn_points_email ) ) {            
            update_option( 'woo_pr_enable_earn_points_email','no');
		}
		if( empty( $woo_pr_earn_point_subject ) ) {            
            update_option( 'woo_pr_earn_point_subject',esc_html__("Congratulations! You've Earned Points","woopoints"));
		}
		if( empty( $woo_pr_earn_point_email_content ) ) {            
            update_option( 'woo_pr_earn_point_email_content',__(sprintf(' %1$s Hi {username}, %2$s
%1$s Below  you can find  latest updates about your {point_label} on {site_url} %2$s
%1$s You have earned {earned_point} {point_label} for {latest_update}  %2$s
%1$s Your current balance is {total_point} %2$s','<p>','</p>'),'woopoints'));
		}
		if( empty( $woo_pr_enable_redeem_email ) ) {            
            update_option( 'woo_pr_enable_redeem_email','no');
		}
		if( empty( $woo_pr_redeem_point_email_subject ) ) {            
            update_option( 'woo_pr_redeem_point_email_subject',esc_html__('Rewards Redemption','woopoints'));
		}
		if( empty( $woo_pr_redeem_point_email_content ) ) {            
            update_option( 'woo_pr_redeem_point_email_content',__(sprintf(' %1$s Hi {username}, %2$s 
%1$s Below  you can find  latest updates about your {point_label} on {site_url} %2$s
%1$s You have redeemed {redeemed_point} {point_label} for {latest_update} %2$s
%1$s Your current balance is {total_point} %2$s','<p>','</p>'),'woopoints'));
		}

        update_option( 'woo_pr_set_option', '1.2.0' );
    }

    $woo_pr_set_option = get_option( 'woo_pr_set_option' );
    
    if( $woo_pr_set_option == '1.2.0' ) {

        if( empty( get_option('woo_pr_minimum_cart_total_earn_error_msg') ) ){
            update_option( 'woo_pr_minimum_cart_total_earn_error_msg', sprintf(esc_html__('You need Minimum of {carttotal} cart total to Earn {points_label}!', 'woopoints'), '{carttotal} {points_label}') );
        }

        if( empty( get_option('woo_pr_minimum_cart_total_redeem_err_msg') ) ){
            update_option( 'woo_pr_minimum_cart_total_redeem_err_msg', sprintf(esc_html__('You need minimum cart Total of {carttotal} in order to Redeem {point_label}!', 'woopoints'), '{carttotal} {points_label}') );
        }

        update_option( 'woo_pr_set_option', '1.2.1' );   
	}
	
    $woo_pr_set_option = get_option( 'woo_pr_set_option' );
    
    if( $woo_pr_set_option == '1.2.1' ) {

        if( empty( get_option('woo_pr_redd_on_status') ) ){
            update_option( 'woo_pr_redd_on_status', '' );
        }

        update_option( 'woo_pr_set_option', '1.2.2' );   
    }

    $woo_pr_set_option = get_option( 'woo_pr_set_option' );
    
    if( $woo_pr_set_option == '1.2.2' ) {

        if( empty( get_option('woo_pr_user_first_purchase_points_message') ) ){
            update_option( 'woo_pr_user_first_purchase_points_message', sprintf(esc_html__('Purchase this product now and earn %s for first purchase!', 'woopoints'), '<strong>{points}</strong> {points_label}') );
        }

        if( empty( get_option('woo_pr_user_first_purchase_points_cart_message') ) ){
            update_option( 'woo_pr_user_first_purchase_points_cart_message', sprintf(esc_html__('Complete your first purchase to earn %s!', 'woopoints'), '<strong>{points}</strong> {points_label}') );
        }


        if( empty( get_option('woo_pr_expire_point_email_subject') ) ) {            
            update_option( 'woo_pr_expire_point_email_subject',esc_html__('Your Points on {site_title} Are About to Expire','woopoints'));
        }

        update_option( 'woo_pr_set_option', '1.2.3' );   
    }

     $woo_pr_set_option = get_option( 'woo_pr_set_option' );
    
    if( $woo_pr_set_option == '1.2.3' ) {       
        
        if( empty( get_option('woo_pr_expire_point_email_content') ) ) {            
            update_option( 'woo_pr_expire_point_email_content',__(sprintf('%1$s Hi {username}, %2$s
            
%1$s This email is to remind you that you have {expiring_points} on {site_url} that are about to expire in next {expiry_days} days. %2$s%3$s {expire_points_details}','<p>','</p>', '<br>'),'woopoints'));
        }     
      

        update_option( 'woo_pr_set_option', '1.2.4' );   
    }

    $woo_pr_set_option = get_option( 'woo_pr_set_option' );

    if( $woo_pr_set_option == '1.2.4' ) {       

      	$woo_pr_enable_account_signup = get_option( 'woo_pr_enable_account_signup' );
	    $woo_pr_enable_post_creation_points = get_option( 'woo_pr_enable_post_creation_points' );
	    $woo_pr_enable_product_creation_points = get_option( 'woo_pr_enable_product_creation_points' );
	    $woo_pr_enable_daily_login_points = get_option( 'woo_pr_enable_daily_login_points' );
	    $woo_pr_post_creation_points = get_option( 'woo_pr_post_creation_points' );
	    $woo_pr_product_creation_points = get_option( 'woo_pr_product_creation_points' );
	    $woo_pr_daily_login_points = get_option( 'woo_pr_daily_login_points' );

	    if( empty( $woo_pr_enable_account_signup ) ){
	    	update_option('woo_pr_enable_account_signup','no');
	    }

		if( empty( $woo_pr_enable_post_creation_points ) ){
			update_option('woo_pr_enable_post_creation_points','no');
		}

		if( empty( $woo_pr_enable_product_creation_points ) ){
			update_option('woo_pr_enable_product_creation_points','no');
		}

		if( empty( $woo_pr_enable_daily_login_points ) ){
			update_option('woo_pr_enable_daily_login_points','no');
		}

		if( empty( $woo_pr_post_creation_points ) ){
			update_option('woo_pr_post_creation_points','');
		}

		if( empty( $woo_pr_product_creation_points ) ){
			update_option('woo_pr_product_creation_points','');
		}

		if( empty( $woo_pr_daily_login_points ) ){
			update_option('woo_pr_daily_login_points','');
		}
      
        update_option( 'woo_pr_set_option', '1.2.5' );

    }

    $woo_pr_set_option = get_option( 'woo_pr_set_option' );

    if( $woo_pr_set_option == '1.2.5' ) {    

        $woo_pr_by_points_single_product_message = get_option( 'woo_pr_by_points_single_product_message' );
        $woo_pr_enable_earn_email_actions = get_option( 'woo_pr_enable_earn_email_actions' );
        $woo_pr_enable_earn_email_actions_arr = array(
            'woo_pr_enable_earn_points_email_for_purchase_product' => 'yes',
            'woo_pr_enable_earn_points_email_for_seller' => 'yes',
            'woo_pr_enable_earn_points_email_for_signup' => 'yes',
            'woo_pr_enable_earn_points_email_for_rate_product' => 'yes',
            'woo_pr_enable_earn_points_email_for_review_status_change' => 'yes',
            'woo_pr_enable_earn_points_email_for_post_creation' => 'yes',
            'woo_pr_enable_earn_points_email_for_product_creation' => 'yes',
            'woo_pr_enable_earn_points_email_for_daily_login' => 'yes',
        );

        if( empty( $woo_pr_by_points_single_product_message ) ){
            update_option('woo_pr_by_points_single_product_message','Purchase this product to fund {points} {points_label} into your account.');
        }

        if( empty( $woo_pr_enable_earn_email_actions ) ){
            update_option('woo_pr_enable_earn_email_actions', $woo_pr_enable_earn_email_actions_arr);
        }

        update_option( 'woo_pr_set_option', '1.2.6' );

    }
	

    //Update option for exluded role
    $woo_pr_set_option = get_option( 'woo_pr_set_option' );     
    if( $woo_pr_set_option == '2.1.4' ) {

        $exclude_roles_points = get_option( 'woo_pr_exclude_roles_points');
        $exclude_roles_points = !empty($exclude_roles_points)?$exclude_roles_points: array();
        update_option('woo_pr_exclude_roles_redeem_points',$exclude_roles_points);
        update_option( 'woo_pr_set_option', '1.2.6' );

    }
    
}


/**
 * Default Settings
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
function woo_pr_default_settings() {

    //Items need to set 
    $options = array(
        'woo_pr_ratio_settings_points_monetary_value'   => '1',
        'woo_pr_ratio_settings_points'                  => '1',
        'woo_pr_redeem_points_monetary_value'           => '1',
        'woo_pr_redeem_points'                          => '100',
        'woo_pr_buy_points_monetary_value'              => '1',
        'woo_pr_buy_points'                             => '100',
        'woo_pr_lables_points'                          => esc_html__( 'Point', 'woopoints' ),
        'woo_pr_lables_points_monetary_value'           => esc_html__( 'Points', 'woopoints' ),
        'woo_pr_selling_points_monetary_value'          => '1',
        'woo_pr_selling_points'                         => '1',
        'woo_pr_cart_max_discount'                      => '',
        'woo_pr_per_product_max_discount'               => '',
        'woo_pr_single_product_message'                 => sprintf(esc_html__('Purchase this product now and earn %s!', 'woopoints'), '<strong>{points}</strong> {points_label}'),
        'woo_pr_earn_points_cart_message'               => sprintf(esc_html__('Complete your order and earn %s for a discount on a future purchase', 'woopoints'), '<strong>{points}</strong> {points_label}'),
        'woo_pr_redeem_points_cart_message'             => sprintf(esc_html__('Use %s for a %s discount on this order!', 'woopoints'), '<strong>{points}</strong> {points_label}', '<strong>{points_value}</strong>'),
        'woo_pr_guest_checkout_page_message'            => sprintf(esc_html__('You need to register an account in order to earn %s', 'woopoints'), ' <strong>{points}</strong> {points_label}'),
        'woo_pr_guest_checkout_page_buy_message'        =>  sprintf(esc_html__('You need to register an account in order to fund %s into your account.', 'woopoints'), ' <strong>{points}</strong> {points_label}'),
        'woo_pr_guest_user_history_message'             => sprintf(esc_html__('Sorry, You have not earned any %s yet.', 'woopoints'), '<strong>{points_label}</strong>'),
        'woo_pr_user_history_points_message'             => esc_html__('You have {points} {points_label}, which are worth a discount of {points_amount} amount.', 'woopoints'),
        'woo_pr_earn_for_account_signup'                => '500',
        'woo_pr_revert_points_refund_enabled'           => 'no',
        'woo_pr_delete_options'                         => 'no',
        'woo_pr_enable_decimal_points'                  => 'no',
        'woo_pr_number_decimal'                         => 2,
		// Start from here
		'woo_pr_enable_expire_point_email'              => 'no',
        'woo_pr_expire_point_email_before_day'          => 1,
        'woo_pr_expire_point_email_subject'          => esc_html__('Your Points on {site_url} Are About to Expire','woopoints'),
        'woo_pr_expire_point_email_content'          => __(sprintf('%1$s Hi {username}, %2$s
            
%1$s This email is to remind you that you have {expiring_points} on {site_url} that are about to expire in next {expiry_days} days. %2$s%3$s {expire_points_details}','<p>','</p>', '<br>'),'woopoints'),

	'woo_pr_enable_earn_points_email'				=> 'no',
	'woo_pr_earn_point_subject'				=> esc_html__("Congratulations! You've Earned Points","woopoints"),
	'woo_pr_earn_point_email_content'				=> sprintf(__('%1$s Hi {username}, %2$s
%1$s Below  you can find  latest updates about your {point_label} on {site_url} %2$s
%1$s You have earned {earned_point} {point_label} for {latest_update}  %2$s
%1$s Your current balance is {total_point} %2$s ','woopoints'),'<p>','</p>'), 
	'woo_pr_enable_redeem_email'		=> 'no',
	'woo_pr_redeem_point_email_subject'		=> esc_html__('Rewards Redemption','woopoints'),
	'woo_pr_redeem_point_email_content'		=> sprintf(__('%1$s Hi {username}, %2$s
%1$s Below  you can find  latest updates about your {point_label} on {site_url} %2$s
%1$s You have redeemed {redeemed_point} {point_label} for {latest_update} %2$s
%1$s Your current balance is {total_point} %2$s','woopoints'),'<p>','</p>'),
    'woo_pr_minimum_cart_total_earn_error_msg' => sprintf(esc_html__('You need Minimum of {carttotal} cart total to Earn {points_label}!', 'woopoints'), '{carttotal} {points_label}'),
    'woo_pr_minimum_cart_total_redeem_err_msg' => sprintf(esc_html__('You need minimum cart Total of {carttotal} in order to Redeem {point_label}!', 'woopoints'), '{carttotal} {points_label}'),
        'woo_pr_redd_on_status' => '',
	'woo_pr_enable_first_purchase_points' => 'no',
    'woo_pr_first_purchase_earn_points' => '',
    'woo_pr_user_first_purchase_points_message' => sprintf(esc_html__('Purchase this product now and earn %s for first purchase!', 'woopoints'), '<strong>{points}</strong> {points_label}'),
    'woo_pr_user_first_purchase_points_cart_message' => sprintf(esc_html__('Complete your first purchase to earn %s!', 'woopoints'), '<strong>{points}</strong> {points_label}'),
    'woo_pr_enable_never_points_expiration_purchased_points' => '',
    'woo_pr_enable_never_points_expiration_sell_points' => '',
    'woo_pr_enable_account_signup' => 'no',
    'woo_pr_enable_post_creation_points' => 'no',
    'woo_pr_enable_product_creation_points' => 'no',
    'woo_pr_enable_daily_login_points' => 'no',
    'woo_pr_post_creation_points' => '',
    'woo_pr_product_creation_points' => '',
    'woo_pr_daily_login_points' => '',
    'woo_pr_by_points_single_product_message' => 'Purchase this product to fund {points} {points_label} into your account.',
    'woo_pr_enable_earn_email_actions' => array(
        'woo_pr_enable_earn_points_email_for_purchase_product' => 'yes',
        'woo_pr_enable_earn_points_email_for_seller' => 'yes',
        'woo_pr_enable_earn_points_email_for_signup' => 'yes',
        'woo_pr_enable_earn_points_email_for_rate_product' => 'yes',
        'woo_pr_enable_earn_points_email_for_review_status_change' => 'yes',
        'woo_pr_enable_earn_points_email_for_post_creation' => 'yes',
        'woo_pr_enable_earn_points_email_for_product_creation' => 'yes',
        'woo_pr_enable_earn_points_email_for_daily_login' => 'yes',
    ),
    );
    foreach ($options as $key => $value) {
        update_option( $key, $value );
    }
}

/**
 * Deactivation Hook
 *
 * Register plugin deactivation hook.
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
register_deactivation_hook(__FILE__, 'woo_pr_uninstall');

function woo_pr_uninstall() {
    global $wpdb;

    // Get prefix
    $prefix = WOO_PR_META_PREFIX;

   // Flush Cron Jobs
   wp_clear_scheduled_hook( 'woo_pr_expiration_points' );

    // Getting delete option
    $woo_pr_delete_options = get_option('woo_pr_delete_options');

    // If option is set
    if (isset($woo_pr_delete_options) && !empty($woo_pr_delete_options) && $woo_pr_delete_options == 'yes') {

        //delete custom main post data
        $queryargs = array( 'post_type' => WOO_POINTS_LOG_POST_TYPE, 'post_status' => 'any' , 'numberposts' => '-1' );
        $queryargsdata = get_posts( $queryargs );
        
        //delete all points log posts
        foreach ($queryargsdata as $post) {
            wp_delete_post($post->ID,true);
        }
        
        //get all user which meta key $prefix.'userpoints' not equal to empty
        $all_user = get_users( array( 'meta_key' => $prefix.'userpoints', 'meta_value' => '', 'meta_compare' => '!=' ) );
        
        foreach ( $all_user as $key => $value ){
            delete_user_meta( $value->ID, $prefix.'userpoints' );
        }

        //Items need to delete
        $options = array(
            'woo_pr_ratio_settings_points_monetary_value',
            'woo_pr_ratio_settings_points',
            'woo_pr_redeem_points_monetary_value',
            'woo_pr_redeem_points',
            'woo_pr_buy_points_monetary_value',
            'woo_pr_buy_points',
            'woo_pr_selling_points_monetary_value',
            'woo_pr_selling_points',
            'woo_pr_cart_max_discount',
            'woo_pr_per_product_max_discount',
            'woo_pr_lables_points_monetary_value',
            'woo_pr_lables_points',
            'woo_pr_single_product_message',
            'woo_pr_earn_points_cart_message',
            'woo_pr_redeem_points_cart_message',
            'woo_pr_guest_checkout_page_message',
            'woo_pr_guest_checkout_page_buy_message',
            'woo_pr_guest_user_history_message',
            'woo_pr_earn_for_account_signup',
            'woo_pr_apply_points_to_previous_orders',
            'woo_pr_revert_points_refund_enabled',
            'woo_pr_delete_options',
            'woo_pr_set_option',
            'woo_pr_plugin_version',
            'woo_pr_enable_reviews',
            'woo_pr_review_points',
            'woo_pr_enable_decimal_points',
            'woo_pr_number_decimal',
            'woo_pr_redd_on_status',
            'woo_pr_enable_never_points_expiration_purchased_points',
            'woo_pr_enable_never_points_expiration_sell_points'
        );

        // Delete all options
        foreach ($options as $option) {
            delete_option($option);
        }
    } // End of if
}

/**
 * Check role exclude by user id
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.1.0
 * */
function woo_pr_check_exclude_role( $userid = '', $action = 'earn', $is_signup = false ) {

    $role_valid = true;
    $userdata = array();
    
    if( ( is_user_logged_in() || $is_signup ) && $action == 'earn' ){
        
        $exclude_roles = get_option('woo_pr_exclude_roles_points');
        $exclude_roles = !empty( $exclude_roles ) ? $exclude_roles : array();

        //check userid is empty then use current user
        if (empty($userid)) {
            $userdata = wp_get_current_user();
        } else {
            $userdata = get_userdata( $userid );
        }

        if( !empty($exclude_roles) && !empty($userdata) ){
            $mach_role = array_intersect( $exclude_roles, $userdata->roles );
            if( !empty($mach_role) ){
                $role_valid = false;
            }
        }
    }
    elseif(  is_user_logged_in()  && $action == 'redeem' ){
        $exclude_roles = get_option('woo_pr_exclude_roles_redeem_points');
        $exclude_roles = !empty( $exclude_roles ) ? $exclude_roles : array();

        //check userid is empty then use current user
        if (empty($userid)) {
            $userdata = wp_get_current_user();
        } else {
            $userdata = get_userdata( $userid );
        }

        if( !empty($exclude_roles) && !empty($userdata) ){
            $mach_role = array_intersect( $exclude_roles, $userdata->roles );
            if( !empty($mach_role) ){
                $role_valid = false;
            }
        }
    }
    

    return apply_filters( 'woo_pr_check_exclude_role', $role_valid, $userdata );
}

//add action to load plugin
add_action('plugins_loaded', 'woo_pr_plugin_loaded');

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
function woo_pr_plugin_loaded() {

    $user_id = get_current_user_id();
    $exclude_role = woo_pr_check_exclude_role( $user_id );

    //check Woocommerce is activated or not
    if ( class_exists('Woocommerce')) {

        /**
         * Add plugin action links
         *
         * Adds a Settings, Support and Docs link to the plugin list.
         *
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        function woo_pr_add_plugin_links( $links ) {
            $plugin_links = array(
                '<a href="admin.php?page=wc-settings&tab=woopr-settings">' . esc_html__( 'Settings', 'woopoints' ) . '</a>',
                '<a href="https://support.wpwebelite.com/">' . esc_html__( 'Support', 'woopoints' ) . '</a>',
                '<a href="https://docs.wpwebelite.com/bravo-woocommerce-points-and-rewards/">' . esc_html__( 'Docs', 'woopoints' ) . '</a>'
            );

            return array_merge( $plugin_links, $links );
        }
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_pr_add_plugin_links' );

        // load first plugin text domain
        woo_pr_load_text_domain();

        // Global variables
        global $woo_pr_scripts, $woo_pr_model, $woo_pr_admin, $woo_pr_log, $woo_pr_public, $woo_pr_polylang;

        // loads the Misc Functions file
        require_once ( WOO_PR_DIR . '/includes/woo-pr-misc-functions.php' );
        // loads the Pagination Class file
        require_once ( WOO_PR_DIR . '/includes/class-woo-pr-pagination-public.php' );
        // Script class handles most of script functionalities of plugin
        include_once( WOO_PR_INC_DIR . '/class-woo-pr-scripts.php' );
        $woo_pr_scripts = new Woo_Pr_Scripts();
        $woo_pr_scripts->add_hooks();

        // Model class handles most of model functionalities of plugin
        include_once( WOO_PR_INC_DIR . '/class-woo-pr-model.php' );
        $woo_pr_model = new Woo_Pr_Model();

        //Insert logs for points functionality.
        require_once( WOO_PR_DIR . '/includes/class-woo-pr-points-log.php');
        $woo_pr_log = new Woo_Pr_Logging();

        //Public Class for public functionlities
        require_once( WOO_PR_DIR . '/includes/class-woo-pr-public.php' );
        $woo_pr_public = new Woo_Pr_Public();
        $woo_pr_public->add_hooks();

        include_once( WOO_PR_ADMIN_DIR . '/class-woo-pr-admin.php' );
        $woo_pr_admin = new Woo_Pr_Admin();
        $woo_pr_admin->add_hooks();

        // Registering our custom product class
        require( WOO_PR_INC_DIR . '/class-woo-pr-product-type-points.php' );

        // check Polylang & Polylang for WooCommerce plugin is activated
        if( defined( 'POLYLANG_VERSION' ) && defined( 'PLLWC_VERSION' ) ) {
            require_once( WOO_PR_DIR . '/includes/compatibility/class-polylang.php' );
            $woo_pr_polylang = new Woo_Pr_Polylang();
            $woo_pr_polylang->add_hooks();            
        }
        // check Polylang & Polylang for WooCommerce plugin is activated
        if( defined( 'OPENPOS_DIR' ) ) {
            require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-pr-openpos.php' );
            $woo_pr_Openpos = new Woo_Pr_Openpos();
            $woo_pr_Openpos->add_hooks();            
        }

        // check WooCommerce bundles plugin is activated
        if( class_exists( 'WC_Bundles' ) ) {
            require_once( WOO_PR_DIR . '/includes/compatibility/class-woo-bundle-product.php' );
            $Woo_pr_bundle = new Woo_Pr_bundle();
            $Woo_pr_bundle->add_hooks();            
        }
    }

    if( class_exists( 'Wpweb_Upd_Admin' ) ) { //Check WPWEB Updater is activated
    
        // Plugin updates
        wpweb_queue_update( plugin_basename( __FILE__ ), WOO_POINTS_PLUGIN_KEY );
        
        /**
         * Include Auto Updating Files
         * 
         * @package WooCommerce - Points and Rewards
         * @since 1.0.0
         */
        require_once( WPWEB_UPD_DIR . '/updates/class-plugin-update-checker.php' ); // auto updating
        
        $WpwebWooPARUpdateChecker = new WpwebPluginUpdateChecker (
            WPWEB_UPD_DOMAIN . '/Updates/WOOPR/license-info.php',
            __FILE__,
            WOO_POINTS_PLUGIN_KEY
        );
        
        
        $WpwebWooPARUpdateChecker->addQueryArgFilter( 'woo_par_add_secret_key' );
    } // end check WPWeb Updater is activated
}

/**
 * Auto Update
 * 
 * Get the license key and add it to the update checker.
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
function woo_par_add_secret_key( $query ) {
    
    $plugin_key = WOO_POINTS_PLUGIN_KEY;
    
    $query['lickey'] = wpweb_get_plugin_purchase_code( $plugin_key );
    return $query;
}