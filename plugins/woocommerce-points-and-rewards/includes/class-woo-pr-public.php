<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
	exit;

/**
 * Public Pages Class
 *
 * Handles all the different features and functions
 * for the front end pages.
 *
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
class Woo_Pr_Public {

	var $model, $logs;

	public function __construct() {

		global $woo_pr_model, $woo_pr_log,$pagenow;

		$this->logs = $woo_pr_log;
		$this->model = $woo_pr_model;

		if( $pagenow != 'site-health.php' && ( !isset($_GET['page'] ) || $_GET['page'] != 'health-check' ) ) { // added condition to fix WP site health and plugin rest api error issue 
			
			if( $this->woo_pr_should_start_session() ){ // function to check the url baypass, to fix sitehealth loopback request issue

				if (!session_id() && !headers_sent()){
					session_start();
				}
			}
		}
	}

	/**
	 * Retrieve the URI blacklist
	 *
	 * These are the URIs where we never start sessions
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since  2.0.2
	 */
	function woo_pr_should_start_session() {

		$start_session = true;

		if( !empty($_SERVER['REQUEST_URI']) ) {

			$blacklist = $this->woo_pr_get_blacklist();
			$uri       = ltrim( $_SERVER[ 'REQUEST_URI' ], '/' );
			$uri       = untrailingslashit( $uri );

			if( in_array( $uri, $blacklist ) ) {
				$start_session = false;
			}

			if( false !== strpos( $uri, 'feed=' ) ) {
				$start_session = false;
			}

			if( is_admin() && false !== strpos( $uri, 'wp-admin/admin-ajax.php' ) ) {
				// We do not want to start sessions in the admin unless we're processing an ajax request
				$start_session = false;
			}

			if( false !== strpos( $uri, 'wp_scrape_key' ) ) {
				// Starting sessions while saving the file editor can break the save process, so don't start
				$start_session = false;
			}

			if( false !== strpos( $uri, 'wp-json/wp-site-health/' ) ) {
				// Starting sessions while site health page gives error, so don't start
				$start_session = false;
			}

		}

		return apply_filters( 'woo_pr_baypass_start_session', $start_session );

	}

	/**
	 * return the URI blacklist
	 *
	 * These are the URIs where we never start sessions
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since  2.0.2
	 */
	public function woo_pr_get_blacklist() {

		$blacklist = array(
			'feed',
			'feed/rss',
			'feed/rss2',
			'feed/rdf',
			'feed/atom',
			'comments/feed'
		);

		// Look to see if WordPress is in a sub folder or this is a network site that uses sub folders
		$folder = str_replace( network_home_url(), '', get_site_url() );

		if( ! empty( $folder ) ) {
			foreach( $blacklist as $path ) {
				$blacklist[] = $folder . '/' . $path;
			}
		}

		return $blacklist;
	}

	/**
	 * Show Message for cart/redeemed product point
	 * 
	 * Handles to show message on cart
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function woo_pr_cart_checkout_message_content() {
	
		global $woocommerce;

		$woo_pr_msg 	= get_option('woo_pr_earn_points_cart_message');		
		$cart_data 		= $woocommerce->cart->get_cart();
				
		$prevent_coupon = !empty(get_option('woo_pr_prevent_coupon_usag'))?get_option('woo_pr_prevent_coupon_usag'):'no';				
		$totalpoints_earned = $this->model->woo_pr_get_user_checkout_points($cart_data);		
		$points_label       = $this->model->woo_pr_get_points_label($totalpoints_earned);
		if( $totalpoints_earned === 'user_product' ) {
			// Owner User Message
			echo $this->model->woo_pr_owner_product_message( 'cart' );
		} else {
			// Replace code into message
			$points_replace = array( "{points}", "{points_label}" );
			$replace_message = array( $totalpoints_earned, $points_label);
			$message = $this->model->woo_pr_replace_array($points_replace, $replace_message, $woo_pr_msg);
			$cart_total = $woocommerce->cart->cart_contents_total;
			 
			if (!empty($message) && !empty($totalpoints_earned)) {
				// Check if cart total <=  Minimum Cart Total to Earn Points
				if( woo_pr_check_min_cart_total_to_earn_points($cart_total) ){					
					// wrap with info div
					$message = '<div class="woocommerce-info woo-pr-earn-points-message">' . $message . '</div>';
					echo apply_filters( 'woo_pr_earn_points_message', $message, $totalpoints_earned );					
				}
				else{
					$error_msg = get_option('woo_pr_minimum_cart_total_earn_error_msg');
					if(!empty($error_msg)){						
						// Replace code into message
						$min_require_cart_total =  get_option('woo_pr_minimum_cart_total_earn')?get_option('woo_pr_minimum_cart_total_earn'):0;	
						$min_require_cart_total_with_currency = get_woocommerce_currency_symbol().$min_require_cart_total;
						$points_replace = array( "{carttotal}", "{points_label}" );
						$replace_message = array( $min_require_cart_total_with_currency, $points_label);
						$error_msg = $this->model->woo_pr_replace_array($points_replace, $replace_message, $error_msg);
						$error_msg = '<div class="woocommerce-error woo-pr-earn-points-message">'.$error_msg.'</div>';	
						echo apply_filters( 'woo_pr_minimum_cart_total_earn_error_msg', $error_msg, $min_require_cart_total_with_currency );									
					}
					
				}
				
			}

			$enable_first_purchase_points = get_option('woo_pr_enable_first_purchase_points');
			$first_purchase_points = get_option('woo_pr_first_purchase_earn_points');
			$woo_pr_first_purchase_cart_checkout_message    = get_option( 'woo_pr_user_first_purchase_points_cart_message' );
			
			if ( $enable_first_purchase_points === 'yes' && !empty( $first_purchase_points ) ) {

				if ( is_user_logged_in() ) {
				   
					$user_id = get_current_user_id();

					$customer_orders = wc_get_orders( array(
						'meta_key' => '_customer_user',
						'meta_value' => $user_id,
						'numberposts' => -1
					) );

					if ( empty( $customer_orders ) ) {

						//points label
						$points_label = $this->model->woo_pr_get_points_label( $first_purchase_points );
						
						$points_replace     = array( "{points}","{points_label}" );
						$replace_message    = array( $first_purchase_points , $points_label );
						$message            = $this->model->woo_pr_replace_array( $points_replace, $replace_message, $woo_pr_first_purchase_cart_checkout_message );

						if( !empty( $message ) ){

							$message = '<div class="woocommerce-info woo-pr-first-purchas-eearn-points-message">' . $message . '</div>';
							echo apply_filters( 'woo_pr_first_purchase_earn_points_cart_checkout_message', $message, $first_purchase_points );

						}
					}
				}

			}
		}
	}

	/**
	 * Show Message for cart/redeemed product point
	 * 
	 * Handles to show message on cart
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function woo_pr_guest_cart_checkout_message_content() {

		global $woocommerce;

		if( !is_user_logged_in() ) {
			
			// Get message options
			$guest_checkout_msg     = get_option('woo_pr_guest_checkout_page_message');
			$guest_checkout_buy_msg = get_option('woo_pr_guest_checkout_page_buy_message');
			$signup_points          = get_option('woo_pr_earn_for_account_signup');

			$cart_data  = $woocommerce->cart->get_cart();

			$totalpoints_earned = $this->model->woo_pr_get_user_checkout_points($cart_data);
			$points_label       = $this->model->woo_pr_get_points_label($totalpoints_earned);

			// Replace code into message
			$points_replace     = array("{points}", "{points_label}", "{signup_points}");
			$replace_message    = array( $totalpoints_earned, $points_label, $signup_points );
			$signup_message     = $this->model->woo_pr_replace_array($points_replace, $replace_message, $guest_checkout_msg);

			//check user is not logged in and total earned points is not empty
			if (!empty($signup_message) && !empty($totalpoints_earned) ) {

				// wrap with info div
				$signup_message = '<div class="woocommerce-info woo-pr-signup-message">' . $signup_message . '</div>';

				echo apply_filters( 'woo_pr_earn_points_guest_checkout_message', $signup_message, $totalpoints_earned, $signup_points );
			}

			// If cart_data not empty
			if (!empty($cart_data)) {
				$total_buy_points = 0;
				foreach ($cart_data as $cart_item) {

					$product_id     = $cart_item['product_id'];
					$quantity       = $cart_item['quantity'];
					$_product       = wc_get_product( $product_id );
					$pro_type       = is_object( $_product ) ? $_product->get_type() : '';

					if( !empty( $pro_type ) && $pro_type == 'woo_pr_points' ) {

						$total_buy_points += $this->model->woo_pr_get_product_buy_points($product_id, $quantity);
					}
				}

				// Replace code into message
				$points_replace     = array( "{points}", "{points_label}" );
				$replace_message    = array( $total_buy_points, $points_label );
				$guest_buy_msg      = $this->model->woo_pr_replace_array( $points_replace, $replace_message, $guest_checkout_buy_msg);

				if( !empty($guest_buy_msg) &&  !empty($total_buy_points) ){

					// wrap with info div
					$guest_buy_msg = '<div class="woocommerce-info woo-pr-buy-points-message">' . $guest_buy_msg . '</div>';

					echo apply_filters( 'woo_pr_buy_points_guest_checkout_message', $guest_buy_msg, $total_buy_points );

				}
			}
		}
	}

	/**
	 * Redeem Points Markup
	 * 
	 * Handles to show redeem points markup
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 * */
	public function woo_pr_redeem_point_markup() {
		
		
		global $current_user, $woocommerce, $wp;

		$gotdiscount = false;
		$woo_fees = $woocommerce->cart->get_fees();
		$min_cart_total = !empty(get_option('woo_pr_minimum_cart_total_redeem'))?get_option('woo_pr_minimum_cart_total_redeem'):0;
		//get user total points
		$userpoints = woo_pr_get_user_points();

		// get Minumum points required settings
		$minimum_points = get_option('woo_pr_minimum_points');
		$minimum_points_message = get_option('woo_pr_minimum_points_required_message');
		
		if( is_user_logged_in() && !empty($minimum_points) ){

			if( $minimum_points > $userpoints ){				
		
				if( !empty($minimum_points_message) ) {
					//get points label to show to user
					$points_label       = $this->model->woo_pr_get_points_label($minimum_points);
						
					$points_replace     = array("{minimum_points}", "{points_label}");
					$replace_message    = array( $minimum_points, $points_label );
					$message = $this->model->woo_pr_replace_array($points_replace, $replace_message, $minimum_points_message);

					ob_start();
					?>
					<div class="woo-points-redeem-points-wrap">
						<form method="POST" action="" >

							<div class="woo-points-redeem-message"><p><?php echo $message; ?></p></div><!--.woo-pr-points-checkout-message-->
						</form>
					</div>
					<?php
					$message_content = ob_get_clean();

					// wrap with info div
					$message_content = '<div class="woocommerce-info woo-pr-redeem-earn-points">' . $message_content . '</div>';

					echo apply_filters( 'woo_pr_minimum_points_message', $message_content, $minimum_points );
				}

				return false;
			}
		}

		//points plural label
		$plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__( 'Points', 'woopoints' );
		$woo_pr_fee_name = $plurallable.esc_html__( ' Discount', 'woopoints' );
		$woo_pr_fee_name = str_replace( ' ', '-', strtolower( $woo_pr_fee_name ) );
		$woo_pr_fee_name = sanitize_title( $woo_pr_fee_name );

		foreach ($woo_fees as $woo_fee_key => $woo_fee_val) {

			if (strpos($woo_fee_key, $woo_pr_fee_name) !== false) {

				$gotdiscount = true;
				break;
			}
		}

		$current_uri = home_url($wp->request);

		// get message from settings
		$redemptionmessage = get_option('woo_pr_redeem_points_cart_message');

		//  calculate discount towards points
		$available_discount_value = $this->model->woo_pr_get_discount_for_redeeming_points();

		$points = intval(get_option('woo_pr_redeem_points'));

		// Get redemption ration from settings page
		$rate = intval(get_option('woo_pr_redeem_points_monetary_value'));

		if (empty($points) || empty($rate)) {
			return 0;
		}

		$available_discount = $available_discount_value * ( $rate / $points );

		if ( !empty($available_discount) ) {

			//get discounte price from points
			$discountedpoints   = $this->model->woo_pr_calculate_points($available_discount);
	
			// Conver the amount
			$available_discount = woo_pr_wcm_currency_convert( $available_discount );

			//get points label to show to user
			$points_label       = $this->model->woo_pr_get_points_label($discountedpoints);
			
			//Get cart total
			$cart_total = WC()->cart->cart_contents_total;
			$is_apply_on_cart = get_option('woo_pr_discount_on_carttotal');
			if( $is_apply_on_cart === 'yes' && wc_tax_enabled() ) {

				$cart_total = round(WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() );
			}

			$points_replace     = array("{points}", "{points_label}", "{points_value}");
			$points_value       = apply_filters( 'woo_pr_cart_points_value', strip_tags( wc_price( round( $available_discount, 2 ) ) ), $available_discount );
		
			$replace_message    = array( $discountedpoints, $points_label, $points_value );
		   
			if( !empty( $redemptionmessage ) ){

				$message = $this->model->woo_pr_replace_array($points_replace, $replace_message, $redemptionmessage);

				// add 'Apply Discount' button
				if (!empty($message) && $gotdiscount == false) {
					ob_start();
					?>
					<div class="woo-points-redeem-points-wrap">
						<form method="POST" action="" >
							<input type="submit" id="woo_pr_apply_discount" name="woo_pr_apply_discount" class="button woo-points-apply-discount-button" value="<?php esc_html_e('Apply Discount', 'woopoints'); ?>" />
							<input type="hidden" name="add_discount_value" value="<?php echo $available_discount; ?>">    

							<div class="woo-points-redeem-message"><p><?php echo $message; ?></p></div><!--.woo-pr-points-checkout-message-->
						</form>
					</div>
					<?php
					$message_content = ob_get_clean();
				}
			}
		}
		
		//Automatic Rewords Redeeming in Cart Page
		$is_automatic_redeem =  get_option('woo_pr_enable_automatic_redeem_point_cart_page');
		if (!empty($gotdiscount) && $is_automatic_redeem != 'yes' ) {

			$removfeesurl = add_query_arg(array('woo_pr_remove_discount' => 'remove'), $current_uri);
			ob_start();
			?>
			<fieldset class="woo-pr-points-checkout-message">

				<a href="<?php echo esc_url($removfeesurl); ?>" class="button woo-point-remove-discount-link woo-pr-points-float-right"><?php esc_html_e('Remove', 'woopoints'); ?></a>
				<div class="woo-pr-points-remove-disocunt-message"><?php printf(esc_html__('Remove %s Discount', 'woopoints'), $points_label); ?></div><!--.woo-pr-points-checkout-message-->
			</fieldset><!--.woo-pr-points-redeem-points-wrap--> 
			<?php
			$message_content = ob_get_clean();
		}
	
		
		if( !empty($available_discount) && !empty($message_content) ){
			
			if(woo_pr_check_min_cart_total_to_redeem_points($cart_total)){				
				// wrap with info div
				$message_content = '<div class="woocommerce-info woo-pr-redeem-earn-points">' . $message_content . '</div>';
				echo apply_filters( 'woo_pr_redeem_points_message', $message_content, $available_discount );
			}
			else{
				$redeem_error_msg = !empty(get_option('woo_pr_minimum_cart_total_redeem_err_msg'))?get_option('woo_pr_minimum_cart_total_redeem_err_msg'):'';
				if(!empty($redeem_error_msg)){
					$min_require_cart_total =  get_option('woo_pr_minimum_cart_total_redeem')?get_option('woo_pr_minimum_cart_total_redeem'):0;	
					$min_require_cart_total_with_currency = get_woocommerce_currency_symbol().$min_require_cart_total;						
					
					$points_replace     = array("{carttotal}", "{point_label}");
					$replace_message    = array( $min_require_cart_total_with_currency, $points_label );
					$redeem_err_msg = $this->model->woo_pr_replace_array($points_replace, $replace_message, $redeem_error_msg);				
					$message_content = '<div class="woocommerce-error woo-pr-redeem-earn-points">'.$redeem_err_msg.'</div>';
					echo apply_filters( 'woo_pr_redeem_points_message', $message_content, $min_require_cart_total_with_currency );
				}
			}
		}
	}

	/**
	 * Manage points on order processing or completed
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	function woo_pr_order_processing_completed_update_points($order_id) {
		
		$prefix     = WOO_PR_META_PREFIX;
		$order      = new WC_Order($order_id);
		$user_id    = $order->get_user_id();

		if( empty( $user_id) ) { // if guest user
			return false;
		}

		$get_order = wc_get_order( $order_id );
		$get_items = $get_order->get_items();

		if ( !empty( $get_items ) ) {
		   
			foreach ( $get_items as $item ) {

				$product_id = $item['product_id'];

				if ( empty($product_id) ) {
					continue;
				}
		  
				$_product = wc_get_product( $product_id );

				if( $_product->is_type( 'woo_pr_points' ) ) {
					
					$product_type_log_meta = 'woo_pr_points';

				}
				else{
					$product_type_log_meta = '';

				}
			}
		}

		$args = array(
		   'post_type' => 'woopointslog',
		   'post_status'       => 'publish',
		   'meta_query' => array(
			'relation' => 'AND',
			   array(
				   'key' => '_woo_log_order_id',
				   'value' => $order_id,
				   'type'    => 'numeric',
				   'compare' => '=',
			   ),
			   array(
				   'key' => '_woo_log_events',
				   'value' => 'earned_purchase',
				   'compare' => 'LIKE',
			   ),
		   )
		);

		$earning_log = get_posts($args);

		$userdata 	= get_userdata($user_id);
		$cart_data  = $order->get_items();
		$totalpoints_earned = 0;
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
		// Check user role exclude and return       
		
		$enable_first_purchase_points = get_option('woo_pr_enable_first_purchase_points');
		$first_purchase_points = get_option('woo_pr_first_purchase_earn_points');

		// Get total earned points from this order
		if ( !empty($user_id) && $enable_first_purchase_points==='yes' && !empty( $first_purchase_points ) ) {

			$user_exclude_role = woo_pr_check_exclude_role( $user_id,'earn' );
		
			if( !$user_exclude_role ){
				return false;
			}
	
				
			$args = array(
			   'post_type' => 'woopointslog',
			   'post_status' => 'publish',
			   'meta_query' => array(
				'relation' => 'AND',
				   array(
					   'key' => '_woo_log_user_id',
					   'value' => $user_id,
					   'type'    => 'numeric',
					   'compare' => '=',
				   ),
				   array(
					   'key' => '_woo_log_events',
					   'value' => 'earned_first_purchase',
					   'compare' => 'LIKE',
				   ),
			   )
			);

			$first_earning_log = get_posts($args);

			$customer_orders = wc_get_orders( array(
				'meta_key' => '_customer_user',
				'meta_value' => $user_id,
				'numberposts' => -1
			) );

			$total_orders = sizeof($customer_orders);

			if ( empty( $first_earning_log ) && $total_orders <= 1 ) {
				
				//points plural label
				$pointslabel = $this->model->woo_pr_get_points_label( $first_purchase_points );

			   //record data logs for for first purchase
				$post_data = array(
					'post_title'   => sprintf(esc_html__('%s earned for first purchase', 'woopoints'), $pointslabel ),
					'post_content' => sprintf(esc_html__('%s earned for first purchase', 'woopoints'), $pointslabel ),
					'post_author'  => $user_id,
					'post_parent'  => $order_id,
				);
				//log meta array
				$log_meta = array(
					'order_id'  => $order_id,
					'user_id'  => $user_id,
					'userpoint' => $first_purchase_points,
					'events' => 'earned_first_purchase',
					'operation' => 'add'//add or minus
				);
		
				if ( !empty( $product_type_log_meta ) ) {
					
				  $log_meta['product_type'] = $product_type_log_meta; 

				}

				//insert entry in log
				$this->logs->woo_pr_insert_logs($post_data, $log_meta);

				$earn_email_actions = get_option('woo_pr_enable_earn_email_actions');
				
				if ( isset($earn_email_actions['woo_pr_enable_earn_points_email_for_first_product_purchase']) && $earn_email_actions['woo_pr_enable_earn_points_email_for_first_product_purchase'] == 'yes') {

					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";    

					$earn_point_subject         = get_option('woo_pr_earn_point_subject');
					$earn_point_email_content   = get_option('woo_pr_earn_point_email_content');

					$latest_update  = esc_html__("First product purchase","woopoints");
					$site_url_text  = site_url();
					$site_title     = get_option('blogname');

					$find_subject = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
					$replace_subject = array($latest_update,$site_url_text,$site_title,$first_purchase_points);
					
					$email_subject  = str_replace($find_subject,$replace_subject,$earn_point_subject);

					$total_point    = woo_pr_get_user_points($order->get_customer_id()); 
					$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );

					$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));

					$customer_data  = get_userdata($order->get_customer_id());              
					$username       = $customer_data->user_login;

					$pointslabel = $this->model->woo_pr_get_points_label( $first_purchase_points );

					$tags_arr       = array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
					$replace_arr    = array($username,$first_purchase_points,$pointslabel,$latest_update,$total_amount_message,$site_url_text,$site_title); 
					$email_content  = str_replace($tags_arr,$replace_arr,$earn_point_email_content);
					
					$to_email       = $order->get_billing_email();
					$mail_status = wp_mail($to_email,$email_subject,$email_content,$headers);                    
				}

				// Add points from user points log
				woo_pr_add_points_to_user($first_purchase_points, $user_id);

				$order->add_order_note(sprintf(esc_html__('%1$d %2$s earned for first purchase.', 'woopoints'), $first_purchase_points, $pointslabel));

				// Update points redeemed meta
				update_post_meta( $order_id, $prefix.'first_purchase_points_order_earned', $first_purchase_points );
			}
		}

		// Get points redeemed meta 
		$old_points_order_earned = get_post_meta( $order_id, $prefix.'points_order_earned', true );
		// Get decimal points option
		$enable_decimal_points = get_option('woo_pr_enable_decimal_points');
		$woo_pr_number_decimal = get_option('woo_pr_number_decimal');        

		if( !empty( $cart_data ) && empty($old_points_order_earned) && woo_pr_check_min_cart_total_to_earn_points($order->get_subtotal()) && empty($earning_log) ) {

			$totalpoints_earned = $this->model->woo_pr_get_user_checkout_points( $cart_data, $user_id, $order_id );

			$order_currency = get_post_meta( $order_id, '_order_currency', true );

			if( $totalpoints_earned  != 'user_product' && $totalpoints_earned != 0 ){

				//points plural label
				$pointslabel = $this->model->woo_pr_get_points_label( $totalpoints_earned );
		
				//record data logs for redeem for purchase
				$post_data = array(
					'post_title'   => sprintf(esc_html__('%s earned for purchase', 'woopoints'), $pointslabel ),
					'post_content' => sprintf(esc_html__('%s earned for purchase', 'woopoints'), $pointslabel ),
					'post_author'  => $user_id,
					'post_parent'  => $order_id,
				);
				//log meta array
				$log_meta = array(
					'order_id'  => $order_id,
					'userpoint' => $totalpoints_earned,
					'events' => 'earned_purchase',
					'operation' => 'add'//add or minus
				);

				if ( !empty( $product_type_log_meta ) ) {                    
				  $log_meta['product_type'] = $product_type_log_meta; 
				}

				
				$user_exclude_role = woo_pr_check_exclude_role( $user_id,'earn' );
				
				if( $user_exclude_role ){                   
			  
				   //insert entry in log
				   $this->logs->woo_pr_insert_logs($post_data, $log_meta);
		
					// Add points from user points log
					woo_pr_add_points_to_user($totalpoints_earned, $user_id);
				
				   $order->add_order_note(sprintf(esc_html__('%1$d %2$s earned for purchase.', 'woopoints'), $totalpoints_earned, $pointslabel));
							
				}
								
				$is_enable_email= get_option('woo_pr_enable_earn_points_email');
				$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
				$is_enable_email_for_purchase_product = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_purchase_product'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_purchase_product'] : '';
				$email_subject 	= get_option('woo_pr_earn_point_subject');
				$email_content  = get_option('woo_pr_earn_point_email_content');
				if($is_enable_email == 'yes' && $is_enable_email_for_purchase_product == 'yes' && !empty($email_subject) && !empty($email_content) && $user_exclude_role ){ // If enable & not empty email subject & content			
					
					$to_email 		= $order->get_billing_email();					
					$customer_data  = get_userdata($order->get_customer_id()); 					
					$username 		= $customer_data->user_login;
					$total_point 	= woo_pr_get_user_points($order->get_customer_id()); 
					$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );	
					$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
					$site_url_text = site_url();
					$site_title = get_option('blogname');
					$latest_update  = esc_html__("product purchase","woopoints");
					
					$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
					$tags_arr 		= array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
					$replace_arr	= array($username,$totalpoints_earned,$pointslabel,$latest_update,$total_amount_message,$site_url,$site_title); 
					$email_content 	= str_replace($tags_arr,$replace_arr,$email_content);					
					
					$find_subject = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
					$replace_subject = array($latest_update,$site_url_text,$site_title,$totalpoints_earned);
					
					$email_subject 	= str_replace($find_subject,$replace_subject,$email_subject);
					wp_mail($to_email,$email_subject,$email_content,$headers);											
				}			
				
			}

			// Get selling points from settings page
			$seller_points 	= !empty(get_option('woo_pr_selling_points')) ? get_option('woo_pr_selling_points') : '';
	
			// Get selling ratio from settings page
			$seller_rate 	= !empty(get_option('woo_pr_selling_points_monetary_value')) ? get_option('woo_pr_selling_points_monetary_value') : '';            

			// Add points for product sale
			foreach ( $cart_data as $item_key => $item_data ) {

				// If product is variable product take variation id else product id
				$data_id = ( !empty( $item_data['variation_id'] ) ) ? $item_data['variation_id'] : $item_data['product_id'];
				$variation_id = ( isset( $item_data['variation_id'] ) && !empty( $item_data['variation_id'] ) ) ? $item_data['variation_id'] : '';

				$post 		= get_post( $item_data['product_id'] );
				$_product 	= wc_get_product( $data_id );
				
				$product_id     = $item_data['product_id'];
				$quantity       = $item_data['quantity'];

				if( empty($product_id) ) {
					continue;
				}
				
				$is_allowed = $this->model->wpp_pr_check_product_allow_for_points_earned( $item_data['product_id'],$variation_id );

				if( $is_allowed == false ){
					continue;
				}

				// If user is not product author then add selling points.
				if( !empty($post) && ($user_id != $post->post_author)){
					
					$user_exclude_role = woo_pr_check_exclude_role( $post->post_author,'earn' );
		
					if( !$user_exclude_role ){
						return false;
					}    

					$_pro_price = $_product->get_price();

					$_pro_price = woo_pr_wcm_currency_convert_original( $_pro_price, '',$order_id );
					
					// Check Selling Points Conversion.
					if(!empty($seller_points) && !empty($seller_rate)){ 
					
						// Calculate seller points
						$seller_earned_points = ( $seller_points * ( $_pro_price * $item_data->get_quantity() ) ) / $seller_rate;

						// Apply decimal if enabled
						if( $enable_decimal_points=='yes' && !empty($woo_pr_number_decimal) ){
							$seller_earned_points = round( $seller_earned_points, $woo_pr_number_decimal );
						} else {
							$seller_earned_points = round( $seller_earned_points );
						}

						//points plural label
						$pointslabel = $this->model->woo_pr_get_points_label( $seller_earned_points );
				
						$post_data = array(
							'post_title'	=> sprintf( esc_html__('%s earned for selling the downloads.','woopoints'), $pointslabel ),
							'post_content'	=> sprintf( esc_html__('Get %s for selling the downloads.','woopoints'), $pointslabel ),
							'post_author'	=> $post->post_author,
							'post_parent'   => $order_id,
						);
						$log_meta = array(
							'order_id'      => $order_id,
							'userpoint'		=>	$seller_earned_points,
							'events'		=>	'earned_sell',
							'operation'		=>	'add'//add or minus
						);
								
						//insert entry in log	
						$this->logs->woo_pr_insert_logs( $post_data, $log_meta );
				
						//update user points
						woo_pr_add_points_to_user( $seller_earned_points, $post->post_author );								
						
						$is_enable_author_email	= get_option('woo_pr_enable_earn_points_email');
						$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
						$is_enable_email_for_seller = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_seller'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_seller'] : '';
						$author_email_subject 		= get_option('woo_pr_earn_point_subject');
						$author_email_content 	 = get_option('woo_pr_earn_point_email_content');
		
						if($is_enable_author_email == 'yes' && $is_enable_email_for_seller == 'yes' && !empty($author_email_subject) && !empty($author_email_content) ){ // If enable & not empty email subject & content
						
							$author_data 		= get_userdata($post->post_author);
							$to_email 	 	= $author_data->user_email;									
							$user_login 	= $author_data->user_login;																
							
							$total_point 	= woo_pr_get_user_points($post->post_author); 
							$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );	
			
							$latest_update  = esc_html__("product selling ","woopoints");
							$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
							
							$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
							$site_url_text = site_url();
							$site_title = get_option('blogname');
							
							$author_find_subject = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
							$author_replace_subject = array($latest_update,$site_url_text,$site_title,$seller_earned_points);
							
							$author_email_subject = str_replace($author_find_subject,$author_replace_subject,$author_email_subject);
															
							$tags_arr 		= array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
							$replace_arr	= array($user_login,$seller_earned_points,$pointslabel,$latest_update,$total_amount_message,$site_url,$site_title);
							$author_email_content 	= str_replace($tags_arr,$replace_arr,$author_email_content);						
						
							wp_mail($to_email,$author_email_subject,$author_email_content,$headers);
						}
					}
				}
			}
		}
		// Update points redeemed meta
		update_post_meta( $order_id, $prefix.'points_order_earned', $totalpoints_earned );
	}


	 /**
	 * Handle to remove all applied coupon while apply points discount
	 * 
	 * Handles to calculate the discount towards points
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.1.4
	 * */
	public function woo_pr_redeem_points_add_remove_prevent_coupon(){
		global $woocommerce;
		$prevent_coupon = !empty(get_option('woo_pr_prevent_coupon_usag'))?get_option('woo_pr_prevent_coupon_usag'):'no';

		$user_id = get_current_user_id();
		$discount_val = get_transient( 'woo_pr_add_discount_value_' . $user_id );

		// If apply discount submit or discout in session
		if( (isset($_POST['woo_pr_apply_discount']) && !empty($_POST['woo_pr_apply_discount']) )
		|| ( ! empty($discount_val) )
		) {
			if( $prevent_coupon == 'yes' && !empty($woocommerce->cart) && !empty($woocommerce->cart->applied_coupons) ){
				WC()->cart->remove_coupons();
			}
		}

		if( $prevent_coupon == 'yes' ){

			$woo_fees = $woocommerce->cart->get_fees();
			if( !empty($woo_fees) ) {
				//points plural label
				$plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__( 'Points', 'woopoints' );
				$woo_pr_fee_name = $plurallable.esc_html__( ' Discount', 'woopoints' );
				$woo_pr_fee_name_low = str_replace( ' ', '-', strtolower( $woo_pr_fee_name ) );

				foreach( $woo_fees as $woo_fee_key => $woo_fee_val ) {
					if( strpos($woo_fee_key, $woo_pr_fee_name_low) !== false && !empty($woocommerce->cart->applied_coupons) ) {
						WC()->cart->remove_coupons();
					}

				}
			}
		}
	}
	/**
	 * Calculate Discount towards Points
	 * 
	 * Handles to calculate the discount towards points
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 * */
	public function woo_pr_redeem_points_add_remove_discount() {

		global $woocommerce;
		$cartdata = $woocommerce->cart->get_cart();	
		
		//Automatic Rewords Redeeming in Cart Page
		$is_automatic_redeem =  get_option('woo_pr_enable_automatic_redeem_point_cart_page');
		
		//points plural label
		$plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__( 'Points', 'woopoints' );
		
		$woo_pr_fee_name = $plurallable.esc_html__( ' Discount', 'woopoints' );
		$woo_pr_fee_name_low = str_replace( ' ', '-', strtolower( $woo_pr_fee_name ) );
		$woo_pr_fee_name_low = sanitize_title( $woo_pr_fee_name_low );
		
		if( isset($_GET['woo_pr_remove_discount']) && !empty($_GET['woo_pr_remove_discount']) && $_GET['woo_pr_remove_discount'] == 'remove' ) {

			$woo_fees = $woocommerce->cart->get_fees();
			if (!empty($woo_fees)) {

				foreach ($woo_fees as $woo_fee_key => $woo_fee_val) {

					if (strpos($woo_fee_key, $woo_pr_fee_name_low ) !== false) {

						//remove fees towards fees
						$woocommerce->cart->remove_fee($woo_pr_fee_name);
					}
				}
			}
		}
		$cart_total = WC()->cart->cart_contents_total;
		$is_apply_on_cart = get_option('woo_pr_discount_on_carttotal');
		if( $is_apply_on_cart === 'yes' && wc_tax_enabled() ) {

			$cart_total = round(WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() );
		}


		$user_id = get_current_user_id();
		$discount_val = get_transient( 'woo_pr_add_discount_value_' . $user_id );
		
		// If apply discount submit or discout in session
		if ( (isset($_POST['woo_pr_apply_discount']) && !empty($_POST['woo_pr_apply_discount']) )
		|| ( !empty($discount_val) )
		|| ($is_automatic_redeem == 'yes')) {			
			
			if( !empty($_REQUEST['add_discount_value']) ){
				set_transient( 'woo_pr_add_discount_value_' . $user_id, esc_attr($_REQUEST['add_discount_value']) );
			}

			// check cartdata not empty
			if (!empty($cartdata) && woo_pr_check_min_cart_total_to_redeem_points($cart_total)) {
				
				// Get max discount points from cart
				$cart_max_discount_points = $this->model->woo_pr_get_discount_for_redeeming_points_from_cart( $woocommerce->cart );

				if( !empty($cart_max_discount_points) && $cart_max_discount_points > 0 ){
					
					// Calculate discount amount from discount points
					$cart_max_discount_amount = $this->model->woo_pr_calculate_discount_amount( $cart_max_discount_points );					
					// Conver the amount
					$cart_max_discount_amount = woo_pr_wcm_currency_convert( $cart_max_discount_amount );

					$cart_max_discount_amount *= -1;

					// Add points discount in cart
					$woocommerce->cart->add_fee( $woo_pr_fee_name, $cart_max_discount_amount, true, 'standard');
				}
			}
		}
	}
	
	 /**
	 * Code for not allowing discount on tax with inclusive tax option
	 * 
	 * Handles to not allowing discount on tax with inclusive tax
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.1.3
	 * */
	public function woo_pr_redeem_points_tax_calc( $tax_arr , $fee ){
		
		if( !empty( $fee ) && !empty( $fee->object) ){
			$plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__( 'Points', 'woopoints' );
			$woo_pr_fee_name = $plurallable.esc_html__( ' Discount', 'woopoints' );
			$woo_pr_fee_name_low = str_replace( ' ', '-', strtolower( $woo_pr_fee_name ) );
			
			if( wc_tax_enabled() && wc_prices_include_tax() ){
				if( $fee->object->id == $woo_pr_fee_name_low ){
					return array( 0 ); 
				}
			}
		}
		return $tax_arr;
	}
	
	

	/**
	 * Remove redeem points from session
	 * 
	 * Handles to remove redeem points from session if requested.
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 * */
	public function woo_pr_handle_redeem_points_add_remove_session() {

		if( isset($_GET['woo_pr_remove_discount']) && !empty($_GET['woo_pr_remove_discount']) && $_GET['woo_pr_remove_discount'] == 'remove' ) {

			$redirecturl = remove_query_arg('woo_pr_remove_discount', get_permalink());

			$user_id = get_current_user_id();
			delete_transient( 'woo_pr_add_discount_value_' . $user_id );

			//redirect to current page
			wp_redirect($redirecturl);
			exit;
		}
	}

	/**
	 * Add points to customer for creating an account
	 *
	 * @since 1.0
	 * @Package WooCommerce - Points and Rewards
	 */
	public function woo_pr_create_log_account_signup($user_id) {
		
		$enable_account_signup = get_option('woo_pr_enable_account_signup');
		$points = get_option('woo_pr_earn_for_account_signup');
		
		// Check user role exclude
		$user_exclude_role = woo_pr_check_exclude_role( $user_id,'earn', true );
		if( $user_exclude_role && ( !empty( $enable_account_signup ) && $enable_account_signup == 'yes' ) && !empty( $points ) ){

			if (!empty($points)) {
				woo_pr_add_points_to_user($points, $user_id);
			}
			$pointslable = $this->model->woo_pr_get_points_label($points);

			$post_data = array(
				'post_title' => sprintf(esc_html__('%s for Signup', 'woopoints'), $pointslable),
				'post_content' => sprintf(esc_html__('Get %s for signing up new account', 'woopoints'), $pointslable),
				'post_author' => $user_id
			);
			$log_meta = array(
				'userpoint' => $points,
				'events' => 'signup',
				'operation' => 'add' //add or minus
			);

			$this->logs->woo_pr_insert_logs($post_data, $log_meta);
			
			$is_enable_email	= get_option('woo_pr_enable_earn_points_email');
			$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
			$is_enable_email_for_signup = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_signup'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_signup'] : '';
			$email_subject 		= get_option('woo_pr_earn_point_subject');
			$email_content 		= get_option('woo_pr_earn_point_email_content');

			$is_enable_email    = apply_filters( 'woo_pr_enable_signup_earn_email_notification', $is_enable_email );
				
			if($is_enable_email == 'yes' && $is_enable_email_for_signup == 'yes' && !empty($email_subject) && !empty($email_subject) ){ 
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$userdata 	= get_userdata($user_id);
				$user_email = $userdata->user_email;
				$username 	= $userdata->user_login;
				
				$total_point 	= woo_pr_get_user_points($user_id); 
				
				$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );	
				$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
				$site_text = site_url();
				$site_title = get_option('blogname');
				
				$latest_update  = esc_html__("signing up","woopoints");
				
				$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
				$tags_arr 		= array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}',); 
				$replace_arr	= array($username,$points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
				$email_content 	= str_replace($tags_arr,$replace_arr,$email_content);
				
				$find_subject = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
				$replace_subject = array($latest_update,$site_text,$site_title,$points);
				$email_subject 	= str_replace($find_subject,$replace_subject,$email_subject);
				
				wp_mail($user_email,$email_subject,$email_content,$headers);
			}			
		}
	}

	/**
	 * Add points to user for creating blog
	 *
	 * @since 1.0
	 * @Package WooCommerce - Points and Rewards
	 */
	public function woo_pr_create_log_post_creation( $ID, $post ) {

		$prefix = WOO_PR_META_PREFIX;
		$enable_post_creation = get_option('woo_pr_enable_post_creation_points');
		$points = get_option('woo_pr_post_creation_points');
		$user_id = $post->post_author;
		$earned_points = get_post_meta($ID, $prefix.'post_creation_points', true);

		// Check user role exclude
		$user_exclude_role = woo_pr_check_exclude_role( $user_id,'earn' );
		if( $user_exclude_role && ( !empty( $enable_post_creation ) && $enable_post_creation == 'yes' ) && !empty( $points ) && empty( $earned_points ) ){

			if (!empty($points)) {
				woo_pr_add_points_to_user($points, $user_id);
			}

			update_post_meta($ID, $prefix.'post_creation_points', $points);

			$pointslable = $this->model->woo_pr_get_points_label($points);

			$post_data = array(
				'post_title' => sprintf(esc_html__('%s for Post Creation', 'woopoints'), $pointslable),
				'post_content' => sprintf(esc_html__('Get %s for new post creation', 'woopoints'), $pointslable),
				'post_author' => $user_id
			);
			$log_meta = array(
				'userpoint' => $points,
				'post_id' => $ID,
				'events' => 'post_creation',
				'operation' => 'add' //add or minus
			);

			$this->logs->woo_pr_insert_logs($post_data, $log_meta);
			
			$is_enable_email    = get_option('woo_pr_enable_earn_points_email');
			$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
			$is_enable_email_for_post_creation = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_post_creation'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_post_creation'] : '';
			$email_subject      = get_option('woo_pr_earn_point_subject');
			$email_content      = get_option('woo_pr_earn_point_email_content');
				
			if($is_enable_email == 'yes' && $is_enable_email_for_post_creation == 'yes' && !empty($email_subject) && !empty($email_content) ){ 
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$userdata   = get_userdata($user_id);
				$user_email = $userdata->user_email;
				$username   = $userdata->user_login;
				
				$total_point    = woo_pr_get_user_points($user_id); 
				
				$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );  
				$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
				$site_text = site_url();
				$site_title = get_option('blogname');
				
				$latest_update  = esc_html__("post creation","woopoints");
				
				$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
				$tags_arr       = array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}',); 
				$replace_arr    = array($username,$points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
				$email_content  = str_replace($tags_arr,$replace_arr,$email_content);               
				
				$find_subject = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
				$replace_subject = array($latest_update,$site_text,$site_title,$points);
				$email_subject  = str_replace($find_subject,$replace_subject,$email_subject);
				
				wp_mail($user_email,$email_subject,$email_content,$headers);
			}           
		}
	}

	/**
	 * Add points to user for creating product
	 *
	 * @since 1.0
	 * @Package WooCommerce - Points and Rewards
	 */
	public function woo_pr_create_log_product_creation( $ID, $post ) {

		$prefix = WOO_PR_META_PREFIX;
		$enable_product_creation = get_option('woo_pr_enable_product_creation_points');
		$points = get_option('woo_pr_product_creation_points');
		$user_id = $post->post_author;
		$earned_points = get_post_meta($ID, $prefix.'product_creation_points', true);

		//Check user role exclude
		$user_exclude_role = woo_pr_check_exclude_role( $user_id,'earn' );
		if( $user_exclude_role && ( !empty( $enable_product_creation ) && $enable_product_creation == 'yes' ) && !empty( $points ) && empty( $earned_points ) ){

			if (!empty($points)) {
				woo_pr_add_points_to_user($points, $user_id);
			}

			update_post_meta($ID, $prefix.'product_creation_points', $points);

			$pointslable = $this->model->woo_pr_get_points_label($points);

			$post_data = array(
				'post_title' => sprintf(esc_html__('%s for Product Creation', 'woopoints'), $pointslable),
				'post_content' => sprintf(esc_html__('Get %s for new product creation', 'woopoints'), $pointslable),
				'post_author' => $user_id
			);
			$log_meta = array(
				'userpoint' => $points,
				'post_id' => $ID,
				'events' => 'product_creation',
				'operation' => 'add' //add or minus
			);

			$this->logs->woo_pr_insert_logs($post_data, $log_meta);
			
			$is_enable_email    = get_option('woo_pr_enable_earn_points_email');
			$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
			$is_enable_email_for_product_creation = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_product_creation'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_product_creation'] : '';
			$email_subject      = get_option('woo_pr_earn_point_subject');
			$email_content      = get_option('woo_pr_earn_point_email_content');
				
			if($is_enable_email == 'yes' && $is_enable_email_for_product_creation == 'yes' && !empty($email_subject) && !empty($email_content) ){ 
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$userdata   = get_userdata($user_id);
				$user_email = $userdata->user_email;
				$username   = $userdata->user_login;
				
				$total_point    = woo_pr_get_user_points($user_id); 
				
				$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );  
				$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
				$site_text = site_url();
				$site_title = get_option('blogname');
				
				$latest_update  = esc_html__("product creation","woopoints");
				
				$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
				$tags_arr       = array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}',); 
				$replace_arr    = array($username,$points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
				$email_content  = str_replace($tags_arr,$replace_arr,$email_content);               
				
				$find_subject = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
				$replace_subject = array($latest_update,$site_text,$site_title,$points);
				$email_subject  = str_replace($find_subject,$replace_subject,$email_subject);
				
				wp_mail($user_email,$email_subject,$email_content,$headers);
			}           
		}
	}

	/**
	 * Add points to user for daily login
	 *
	 * @since 1.0
	 * @Package WooCommerce - Points and Rewards
	 */
	public function woo_pr_create_log_daily_login( $username ) {

		$prefix = WOO_PR_META_PREFIX;
		$enable_daily_login = get_option('woo_pr_enable_daily_login_points');
		$points = get_option('woo_pr_daily_login_points');
		$current_user = get_user_by('login', $username);
		$user_id = $current_user->ID;
		$daily_login_time = get_user_meta($user_id, $prefix.'daily_login_time', true);
		$current_time = time();
		$timediff = 0;
		$seconds_in_day = 60 * 60 * 24;

		//Check user role exclude
		$user_exclude_role = woo_pr_check_exclude_role( $user_id,'earn' );
		if( $user_exclude_role && ( !empty( $enable_daily_login ) && $enable_daily_login == 'yes' ) && !empty( $points ) ){

			if ( !empty( $daily_login_time ) ) {
				
				$timediff = $current_time - $daily_login_time;

			}

			if ( empty( $daily_login_time ) || $timediff > $seconds_in_day ) {
				
				update_user_meta($user_id, $prefix.'daily_login_time', time() );
				update_user_meta($user_id, $prefix.'daily_login_counter', 0);

			}

			$counter = get_user_meta($user_id, $prefix.'daily_login_counter', true);
			$counter = ( empty( $counter ) && $counter == '' ) ? 0 : $counter;
			$counter = intval($counter) + 1;

			update_user_meta($user_id, $prefix.'daily_login_counter', $counter);

			if ( $counter === 1 ) {

				if (!empty($points)) {
					woo_pr_add_points_to_user($points, $user_id);
				}

				$pointslable = $this->model->woo_pr_get_points_label($points);

				$post_data = array(
					'post_title' => sprintf(esc_html__('%s for Daily Login', 'woopoints'), $pointslable),
					'post_content' => sprintf(esc_html__('Get %s for new daily login', 'woopoints'), $pointslable),
					'post_author' => $user_id
				);
				
				$log_meta = array(
					'userpoint' => $points,
					'events' => 'daily_login',
					'operation' => 'add' //add or minus
				);

				$this->logs->woo_pr_insert_logs($post_data, $log_meta);
				
				$is_enable_email    = get_option('woo_pr_enable_earn_points_email');
				$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
				$is_enable_email_for_daily_login = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_daily_login'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_daily_login'] : '';
				$email_subject      = get_option('woo_pr_earn_point_subject');
				$email_content      = get_option('woo_pr_earn_point_email_content');
					
				if($is_enable_email == 'yes' && $is_enable_email_for_daily_login == 'yes' && !empty($email_subject) && !empty($email_content) ){ 
					
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

					$userdata   = get_userdata($user_id);
					$user_email = $userdata->user_email;
					$username   = $userdata->user_login;
					
					$total_point    = woo_pr_get_user_points($user_id); 
					
					$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );  
					$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
					$site_text = site_url();
					$site_title = get_option('blogname');
					
					$latest_update  = esc_html__("daily login","woopoints");
					
					$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
					$tags_arr       = array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}',); 
					$replace_arr    = array($username,$points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
					$email_content  = str_replace($tags_arr,$replace_arr,$email_content);               
					
					$find_subject = array('{latest_update}','{site_url}','{site_title}','{earned_point}');
					$replace_subject = array($latest_update,$site_text,$site_title,$points);
					$email_subject  = str_replace($find_subject,$replace_subject,$email_subject);
					
					wp_mail($user_email,$email_subject,$email_content,$headers);
				}
			}           
		}
	}

	/**
	 * Handle an order that is cancelled or refunded by:
	 * 1) Removing any points earned for the order
	 *
	 * 2) Crediting points redeemed for a discount back to the customer's account if the order that they redeemed the points
	 * for a discount on is cancelled or refunded
	 *
	 * @Package WooCommerce - Points and Rewards
	 * @since 1.0
	 * @param int $order_id the WC_Order ID
	 */
	public function woo_pr_handle_cancelled_refunded_order($order_id) {


		$prefix = WOO_PR_META_PREFIX;

		$order = wc_get_order($order_id);
		$order_id = $order->get_id();
		$order_user_id = $order->get_user_id();
		$args = array(
		   'post_type' => 'woopointslog',
		   'post_status'       => 'publish',
		   'posts_per_page'    => 1,
		   'meta_query' => array(
			'relation' => 'AND',
			   array(
				   'key' => '_woo_log_order_id',
				   'value' => $order_id,
				   'type'    => 'numeric',
				   'compare' => '=',
			   ),
			   array(
				   'key' => '_woo_log_events',
				   'value' => 'redeemed_purchase',
				   'compare' => 'LIKE',
			   ),
		   )
		);
		$redeem_log = get_posts($args);

		// bail for guest user
		if (!$order_user_id) {
			return;
		}

		// Get settings to revert points when purchase is refunded
		$woo_pr_revert_points_refund = !empty(get_option('woo_pr_revert_points_refund_enabled')) ? get_option('woo_pr_revert_points_refund_enabled') : '';

		// If payment id is not empty and payment status needs to revert points
		if (!empty($woo_pr_revert_points_refund) && $woo_pr_revert_points_refund == 'yes' && !empty($order_id)) {

			// Get earned points and redeemed points
			$points_earned = get_post_meta( $order_id, $prefix.'points_order_earned', true);
			$points_redeemed = get_post_meta( $order_id, $prefix.'redeem_order', true);
			$check_sell_debited = get_post_meta( $order_id, $prefix.'sell_debited', true);

			// If points earned is not empty
			if (!empty($points_earned)) {

				//points label
				$pointslable = $this->model->woo_pr_get_points_label($points_earned);

				//record data logs for redeem for purchase
				$post_data = array(
					'post_title'    => sprintf(esc_html__(' %s debited for refunded Payment %d', 'woopoints'), $pointslable, $points_earned),
					'post_content'  => sprintf(esc_html__('%s debited for refunded Payment %d', 'woopoints'), $pointslable, $points_earned),
					'post_author'   => $order_user_id,
					'post_parent'   => $order_id,
				);
				//log meta array
				$log_meta = array(
					'order_id'  => $order_id,
					'userpoint' => $points_earned,
					'events' => 'refunded_purchase_debited',
					'operation' => 'minus'//add or minus
				);

				//insert entry in log
				$this->logs->woo_pr_insert_logs($post_data, $log_meta);

				// Deduct points from user points log
				woo_pr_minus_points_from_user($points_earned, $order_user_id);

				$order->add_order_note(sprintf(esc_html__('%1$d %2$s debited discount towards to customer.', 'woopoints'), $points_earned, $pointslable));

				// Delete points earned meta
				delete_post_meta($order_id, $prefix.'points_order_earned');
			}

			// If points redeemed is not empty
			if (!empty($points_redeemed) && !empty($redeem_log)) {

				//points label
				$pointslable = $this->model->woo_pr_get_points_label($points_redeemed);

				//record data logs for redeem for purchase
				$post_data = array(
					'post_title'    => sprintf(esc_html__(' %s credited for refunded Payment %d', 'woopoints'), $pointslable, $order_id),
					'post_content'  => sprintf(esc_html__('%s credited for refunded Payment %d', 'woopoints'), $pointslable, $order_id),
					'post_author'   => $order_user_id,
					'post_parent'   => $order_id,
				);
				//log meta array
				$log_meta = array(
					'order_id'  => $order_id,
					'userpoint' => $points_redeemed,
					'events' => 'refunded_purchase_credited',
					'operation' => 'add'//add or minus
				);

				//insert entry in log
				$this->logs->woo_pr_insert_logs($post_data, $log_meta);

				// Add points from user points log
				woo_pr_add_points_to_user($points_redeemed, $order_user_id);

				// Add order note for Points and Rewards
				$order->add_order_note(sprintf(esc_html__('%1$d %2$s credited back to customer.', 'woopoints'), $points_redeemed, $pointslable));

				// Delete points redeemed meta
				delete_post_meta($order_id, $prefix.'redeem_order');
			}

			// Refunded Sell Debited
			$order_points_logs_args = array( 
				'post_parent'   => $order_id,
				'meta_query'    => array(
									array(
										'key'     => '_woo_log_events',
										'value'   => 'earned_sell',
									),
								)
			);
			//get order logs data
			$order_points_logs = $this->model->woo_pr_get_points( $order_points_logs_args );

			if( !empty( $order_points_logs ) ) { //check user log in not empty
			
				foreach ( $order_points_logs as $key => $value ){
					
					$logspointid    = $value['ID'];
					$post_author    = $value['post_author'];
					$event          = get_post_meta( $logspointid, '_woo_log_events', true );
					$event_data     = $this->model->woo_pr_get_events( $event );
					$sellpoints     = get_post_meta( $logspointid, '_woo_log_userpoint', true );
					$sellpoints     = str_replace("+", "", $sellpoints );
					$sellpoints     = str_replace("-", "", $sellpoints );
					
					//check event is earned sell and points earned is not empty
					if( !empty($sellpoints) && ($event == 'earned_sell') ) {

						//points label
						$pointslable = $this->model->woo_pr_get_points_label($sellpoints);

						//record data logs for redeem for purchase
						$post_data = array(
							'post_title'    => sprintf(esc_html__(' %s debited for refunded Payment %d', 'woopoints'), $pointslable, $sellpoints),
							'post_content'  => sprintf(esc_html__('%s debited for refunded Payment %d', 'woopoints'), $pointslable, $sellpoints),
							'post_author'   => $post_author,
							'post_parent'   => $order_id,
						);
						//log meta array
						$log_meta = array(
							'order_id'  => $order_id,
							'userpoint' => $sellpoints,
							'events' => 'refunded_sell_debited',
							'operation' => 'minus'//add or minus
						);

						//insert entry in log
						$this->logs->woo_pr_insert_logs($post_data, $log_meta);

						// Deduct points from user points log
						woo_pr_minus_points_from_user($sellpoints, $post_author);
					}
				} //end foreach loop

				// Update sell debited
				update_post_meta( $order_id, $prefix.'sell_debited', 'yes' );
			}// End Refunded Sell Debited
			

			 $args = array(
			   'post_type' => 'woopointslog',
			   'post_status'       => 'publish',
			   'meta_query' => array(
				'relation' => 'AND',
				   array(
					   'key' => '_woo_log_user_id',
					   'value' => $order_user_id,
					   'type'    => 'numeric',
					   'compare' => '=',
				   ),
				   array(
					   'key' => '_woo_log_order_id',
					   'value' => $order_id,
					   'type'    => 'numeric',
					   'compare' => '=',
				   ),
				   array(
					   'key' => '_woo_log_events',
					   'value' => 'earned_first_purchase',
					   'compare' => 'LIKE',
				   ),
			   )
			);

			$first_earning_log = get_posts($args);

			$first_purchase_points_earned = get_post_meta( $order_id, $prefix.'first_purchase_points_order_earned', true);

			if ( !empty($first_purchase_points_earned) && !empty($first_earning_log) ) {

				//points label
				$pointslable = $this->model->woo_pr_get_points_label($first_purchase_points_earned);

				//record data logs refund first purchase
				$post_data = array(
					'post_title'    => sprintf(esc_html__(' %s of first purchase debited for refunded Payment %d', 'woopoints'), $pointslable, $order_id),
					'post_content'  => sprintf(esc_html__('%s of first purchase debited for refunded Payment %d', 'woopoints'), $pointslable, $order_id),
					'post_author'   => $order_user_id,
					'post_parent'   => $order_id,
				);
				//log meta array
				$log_meta = array(
					'order_id'  => $order_id,
					'userpoint' => $first_purchase_points_earned,
					'events' => 'refunded_first_purchase_debited',
					'operation' => 'minus'//add or minus
				);

				//insert entry in log
				$this->logs->woo_pr_insert_logs($post_data, $log_meta);

				// Minus points from user points log
				woo_pr_minus_points_from_user($first_purchase_points_earned, $order_user_id);

				// Add order note for Points and Rewards
				$order->add_order_note(sprintf(esc_html__('%1$d %2$s of first purchase debited towards to customer.', 'woopoints'), $first_purchase_points_earned, $pointslable));

				// Delete first purchase points meta
				delete_post_meta($order_id, $prefix.'first_purchase_points_order_earned');
			}

		}
	}
	
	/**
	 * Show Message for puchase points
	 * 
	 * Handles to show message for purchasing on 
	 * download view page
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function woo_pr_points_message_before_add_to_cart_button( ) {

		global $post, $current_user;
		$postid = $post->ID;
		
		$_product = wc_get_product( $postid );

		if( $_product->is_type( 'woo_pr_points' ) ) {
		
			$woo_pr_single_product_message    = get_option( 'woo_pr_by_points_single_product_message' );

		} else {
		
			$woo_pr_single_product_message    = get_option( 'woo_pr_single_product_message' );

		}
		
		//get earning points for downloads
		$earningpoints = $this->model->woo_pr_get_earning_points( $postid );
		$earningpoints = apply_filters( 'woo_pr_single_page_earning_points', $earningpoints, $postid );
		$rewardsearningpoints = $this->model->woo_pr_get_product_earn_points($postid);
		
		$is_allowed = $this->model->wpp_pr_check_product_allow_for_points_earned( $postid );

		if( $is_allowed == false ){
			return '';
		}

		// Don't show message if login user is the owner of product
		if(  $post->post_author == $current_user->ID ) {
			// Owner User Message
			$message = $this->model->woo_pr_owner_product_message( 'product' );
			echo "<p class='woopr-product-message'>".$message."</p>";

		} else if( !empty( $earningpoints ) ) { //check earning points should not empty
			
			// Formatting point amount
			if(is_array($earningpoints)) { //if product is variable then earningpoints contains array of lowest and highest price
			   $earningpoints =  array_unique($earningpoints);
				$earning_points = '';
				foreach ($earningpoints as $key => $value) {
					
					$earning_points .= $value . ' - ';
				}
				
				$earningpoints = trim($earning_points,' - ');
				
			} else {
				$earningpoints = $earningpoints;
			}
			
			//points label
			$points_label = $this->model->woo_pr_get_points_label( $earningpoints );
			
			$points_replace     = array( "{points}","{points_label}" );
			$replace_message    = array( $earningpoints , $points_label );
			$message            = $this->model->woo_pr_replace_array( $points_replace, $replace_message, $woo_pr_single_product_message );

			if( !empty( $message ) ){
				echo "<p class='woopr-product-message'>".$message."</p>";
			}

		} //end if to check earning points should not empty
		
		$enable_first_purchase_points = get_option('woo_pr_enable_first_purchase_points');
		$first_purchase_points = get_option('woo_pr_first_purchase_earn_points');
		$woo_pr_first_purchase_single_product_message    = get_option( 'woo_pr_user_first_purchase_points_message' );

		if ( $enable_first_purchase_points==='yes' && !empty( $first_purchase_points ) ) {

			if ( is_user_logged_in() ) {
			   
				$user_id = get_current_user_id();

				$customer_orders = wc_get_orders( array(
					'meta_key' => '_customer_user',
					'meta_value' => $user_id,
					'numberposts' => -1
				) );

				if ( empty( $customer_orders ) ) {

					//points label
					$points_label = $this->model->woo_pr_get_points_label( $first_purchase_points );
					
					$points_replace     = array( "{points}","{points_label}" );
					$replace_message    = array( $first_purchase_points , $points_label );
					$message            = $this->model->woo_pr_replace_array( $points_replace, $replace_message, $woo_pr_first_purchase_single_product_message );

					if( !empty( $message ) ){
						echo "<p class='woopr-product-message'>".$message."</p>";
					}
				}
			}

		}

	}

	/**
	 * Redeem used points
	 * 
	 * Handles to Points redeemed towards purchase
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function woo_pr_woocommerce_checkout_process( $order_id, $data ){       

		global $current_user;
		$prefix = WOO_PR_META_PREFIX;
		$order = wc_get_order($order_id);
	
		$order_status = $order->get_status();
		
		$order_status = 'wc-'.$order_status;
		
		$point_discount_amount = 0;
		$get_order_status = get_option('woo_pr_redd_on_status');

	
		//points plural label
		$plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__( 'Points', 'woopoints' );
		$woo_pr_fee_name = $plurallable.esc_html__( ' Discount', 'woopoints' );

		// Iterating through order fee items ONLY
		foreach( $order->get_items('fee') as $item_id => $item_fee ){
			// The fee name
			$fee_name = $item_fee->get_name();
			if( strpos($fee_name, $woo_pr_fee_name) !== false ){
				// The fee total amount
				$point_discount_amount += woo_pr_wcm_currency_convert_original( $item_fee->get_total() );
			}
		 }

		// remove redeemed points
		$user_id = get_current_user_id();
		
		delete_transient( 'woo_pr_add_discount_value_' . $user_id );
		
		if( !empty( $point_discount_amount ) && $point_discount_amount < 0 ) {

			$user_id        = $current_user->ID;
			$current_points = $this->model->woo_pr_calculate_points( $point_discount_amount );
			$points_label   = $this->model->woo_pr_get_points_label( $current_points );
			$log_title      = $points_label.esc_html__( ' redeemed towards purchase', 'woopoints' );

			//check number contains minus sign or not
			if (strpos($current_points, '-') !== false) {
				$current_points = str_replace('-', '', $current_points);
			} 

			// Update points redeemed
			update_post_meta( $order_id, $prefix.'redeem_order', $current_points);

			if ( empty( $get_order_status ) || in_array($order_status, $get_order_status) ) {

			 
				// Update user points to user account
				woo_pr_minus_points_from_user($current_points, $user_id);

				$post_data = array(
					'post_title'    => $log_title,
					'post_content'  => $log_title,
					'post_author'   => $user_id
				);

				$log_meta = array(
					'order_id'  => $order_id,
					'userpoint' => abs($current_points),
					'events'    => 'redeemed_purchase',
					'operation' => 'minus' //add or minus
				);

				$this->logs->woo_pr_insert_logs($post_data, $log_meta);

				// Add order note for Points and Rewards
				$order->add_order_note(sprintf(esc_html__('%1$d %2$s debited discount towards to customer.', 'woopoints'), $current_points, $plurallable));
				
				
				$enable_redeem_email 	= get_option('woo_pr_enable_redeem_email');
				$redeem_email_subject 	= get_option('woo_pr_redeem_point_email_subject');
				$redeem_email_content 	= get_option('woo_pr_redeem_point_email_content');
				
				if($enable_redeem_email == 'yes' && !empty($redeem_email_subject)  && !empty($redeem_email_content) ){
					
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						
						$to_email 		= $order->get_billing_email();					
						$customer_data  = get_userdata($order->get_customer_id()); 					
						$username 		= $customer_data->user_login;
						$total_point 	= woo_pr_get_user_points($order->get_customer_id()); 
						$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );	
						
						$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
						$site_url_text = site_url();
						$site_title = get_option('blogname');
						
						$latest_update  = esc_html__("product purchase","woopoints");
						
						$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
						$tags_arr 		= array('{username}','{redeemed_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
						$replace_arr	= array($username,$current_points,$points_label,$latest_update,$total_amount_message,$site_url,$site_title); 
						$email_content 	= str_replace($tags_arr,$replace_arr,$redeem_email_content);						
						
					
						$find_subject = array('{latest_update}','{site_url}','{site_title}','{redeemed_point}');
						$replace_subject = array($latest_update,$site_url_text,$site_title,$current_points);
						$email_subject 	= str_replace($find_subject,$replace_subject,$redeem_email_subject);
						
						wp_mail($to_email,$email_subject,$email_content,$headers);				
				}			
			}
		}

	}
	
	/**
	 * Show All Points and Rewards Buttons
	 * 
	 * Handles to show all Points and Rewards buttons on the viewing page
	 * whereever user put shortcode
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 **/
	public function woo_pr_points_history( $content ) {
		
		//check user is logged in or not
		if( is_user_logged_in() ) {
			//show user logs list
			$content .= $this->logs->woo_pr_user_log_list();
		} else {
			
			//points lable
			$content = $this->model->woo_pr_points_guest_points_history_message();
		}
		return $content;
	}

	public function woo_pr_woocommerce_locate_template( $template, $template_name, $template_path ){

		$_template = $template;
	
		if ( ! $template_path ) {
			$template_path = WC()->template_path();
		}
		
		$plugin_path = WOO_PR_DIR . '/includes/templates/';

		// Look within passed path within the theme � this is priority    
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
		
		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}
	
		// Use default template
		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found
		return $template;
	}

	/**
	 * Awarded Points on User Rated on product.
	 *
	 * Awarded points rated on product.
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.1
	 */
	public function woo_pr_rate_on_product( $comment_ID, $comment ) {
		
		
		global $woo_pr_log;
		$prefix = WOO_PR_META_PREFIX;

		$comment_product_id = $comment->comment_post_ID;
		$excludeed_products = get_option('woo_pr_exclude_products_points');
		$excludeed_products = !empty( $excludeed_products ) ? $excludeed_products : array();
		
		// get options 
		$woo_pr_enable_reviews = get_option('woo_pr_enable_reviews');
		$woo_pr_review_points = get_option('woo_pr_review_points');
	
		
		//Check if review need to do
		if( !empty( $woo_pr_enable_reviews ) && ($woo_pr_enable_reviews=='yes') && !empty( $comment->user_id )
			&& isset( $comment->comment_type ) && ( $comment->comment_approved==1 ) && (!in_array($comment_product_id,$excludeed_products)) ) {
			
			//Get details
			$rating        = ( isset( $_POST['rating'] ) ) ? trim( $_POST['rating'] ) : null;
			$rating        = wp_filter_nohtml_kses( $rating );			
		
			// Get comment review points
			$comment_review_points = get_comment_meta( $comment->comment_ID, $prefix.'review_points', true );

			//Get points
			$product_review_points = get_post_meta( $comment->comment_post_ID, $prefix."review_points", true );
			$review_points = !empty( $product_review_points[$rating] ) ? $product_review_points[$rating] : '';
			if( empty( $review_points ) ) {

				//Get global points if not at product level
				$review_points = !empty( $woo_pr_review_points[$rating] ) ? $woo_pr_review_points[$rating] : '';
			}

			if( !empty( $review_points ) && empty($comment_review_points) ) {
				

				// Add points to post author 
				woo_pr_add_points_to_user( $review_points , $comment->user_id );

				// insert add point log
				$post_data = array(
					'post_title'    => esc_html__( 'Points earned for review on product.', 'woopoints' ),
					'post_content'  => esc_html__( 'Points earned for review on product.', 'woopoints' ),
					'post_author'   =>  $comment->user_id
				);

				$log_meta = array(
					'userpoint'     =>  $review_points,
					'events'        =>  'earned_product_review',
					'operation'     =>  'add'//add or minus
				);

				//insert entry in log   
				$points_log_id = $woo_pr_log->woo_pr_insert_logs( $post_data, $log_meta );
				// Set review points in comment meta
				update_comment_meta( $comment->comment_ID, $prefix.'review_points', $review_points );

				$is_enable_email	= get_option('woo_pr_enable_earn_points_email');
				$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
				$is_enable_email_for_rate_product = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_rate_product'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_rate_product'] : '';
				$email_subject 		= get_option('woo_pr_earn_point_subject');
				$email_content 		= get_option('woo_pr_earn_point_email_content');
				
				if($is_enable_email == 'yes' && $is_enable_email_for_rate_product == 'yes' && !empty($email_subject) && !empty($email_subject) ){ 				

					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

					$userdata 	= get_userdata($comment->user_id);
					$user_email = $userdata->user_email;
					$username 	= $userdata->user_login;
					
					$total_point 	= woo_pr_get_user_points($comment->user_id); 
					$pointslable 	= $this->model->woo_pr_get_points_label($total_point);
					$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );	
					
					$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
					$site_url_text = site_url();
					$site_title = get_option('blogname');
					
					$latest_update  = esc_html__("reviewing the product ","woopoints");
					
					$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
					$tags_arr 		= array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
					$replace_arr	= array($username,$review_points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
					$email_content 	= str_replace($tags_arr,$replace_arr,$email_content);				
				
					$find_subject 		= array('{latest_update}','{site_url}','{site_title}','{earned_point}');
					$replace_subject 	= array($latest_update,$site_url_text,$site_title,$review_points);
					
					$email_subject 	= str_replace($find_subject,$replace_subject,$email_subject);		
					
					wp_mail($user_email,$email_subject,$email_content,$headers);
				}
				
			}
			
		}
	}

	/**
	 * Review status change 
	 *
	 * Awarded points on product review status make approve.
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.1
	 */
	public function woo_pr_rate_status_change( $comment_ID, $comment_status ) {

		global $woo_pr_log;
		$prefix = WOO_PR_META_PREFIX;
	
		
		
		// get options 
		$woo_pr_enable_reviews = get_option('woo_pr_enable_reviews');
		$woo_pr_review_points = get_option('woo_pr_review_points');

		// Get comment
		$comment = get_comment( $comment_ID );

		$comment_product_id = $comment->comment_post_ID;
		
		$excludeed_products = get_option('woo_pr_exclude_products_points');
		$excludeed_products = !empty( $excludeed_products ) ? $excludeed_products : array();
		
		//Check if review need to do
		if( !empty( $woo_pr_enable_reviews ) && ($woo_pr_enable_reviews=='yes') && !empty( $comment->user_id )
			&& isset( $comment->comment_type ) && ( $comment->comment_approved==1 ) &&(!in_array($comment_product_id,$excludeed_products)) ) {

			//Get details
			$rating     = get_comment_meta( $comment->comment_ID, 'rating', true );
			$rating     = ( isset( $rating ) ) ? trim( $rating ) : null;
			$rating     = wp_filter_nohtml_kses( $rating );
			// Get comment review points
			$comment_review_points = get_comment_meta( $comment->comment_ID, $prefix.'review_points', true );

			//Get points
			$product_review_points = get_post_meta( $comment->comment_post_ID, $prefix."review_points", true );
			$review_points = !empty( $product_review_points[$rating] ) ? $product_review_points[$rating] : '';
			if( empty( $review_points ) ) {

				//Get global points if not at product level
				$review_points = !empty( $woo_pr_review_points[$rating] ) ? $woo_pr_review_points[$rating] : '';
			}

			// Check user role exclude
			$user_exclude_role = woo_pr_check_exclude_role( $comment->user_id,'earn' );

			if( $user_exclude_role && !empty( $review_points ) && empty($comment_review_points) ) {

				// Add points to post author 
				woo_pr_add_points_to_user( $review_points , $comment->user_id );

				// insert add point log
				$post_data = array(
					'post_title'    => esc_html__( 'Points earned for review on product.', 'woopoints' ),
					'post_content'  => esc_html__( 'Points earned for review on product.', 'woopoints' ),
					'post_author'   =>  $comment->user_id
				);

				$log_meta = array(
									'userpoint'     =>  $review_points,
									'events'        =>  'earned_product_review',
									'operation'     =>  'add'//add or minus
								);

				//insert entry in log   
				$points_log_id = $woo_pr_log->woo_pr_insert_logs( $post_data, $log_meta );
				// Set review points in comment meta
				update_comment_meta( $comment->comment_ID, $prefix.'review_points', $review_points );
				
				$is_enable_email	= get_option('woo_pr_enable_earn_points_email');
				$is_enable_email_actions = get_option('woo_pr_enable_earn_email_actions');
				$is_enable_email_for_review_status_change = ( isset( $is_enable_email_actions['woo_pr_enable_earn_points_email_for_review_status_change'] ) ) ? $is_enable_email_actions['woo_pr_enable_earn_points_email_for_review_status_change'] : '';
				$email_subject 		= get_option('woo_pr_earn_point_subject');
				$email_content 		= get_option('woo_pr_earn_point_email_content');
				
				if($is_enable_email == 'yes' && $is_enable_email_for_review_status_change == 'yes' && !empty($email_subject) && !empty($email_subject) ){ 				
	
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

					$userdata 	= get_userdata($comment->user_id);
					$user_email = $userdata->user_email;
					$username 	= $userdata->user_login;
					
					$site_title = get_option('blogname');
					$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
					$site_url_text 	= site_url();
					$total_point 	= woo_pr_get_user_points($comment->user_id); 
					$pointslable 	= $this->model->woo_pr_get_points_label($total_point);
					$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );	
					
					$latest_update  = esc_html__("reviewing the product ","woopoints");
					
					$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
					$tags_arr 		= array('{username}','{earned_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
					$replace_arr	= array($username,$review_points,$pointslable,$latest_update,$total_amount_message,$site_url,$site_title); 
					$email_content 	= str_replace($tags_arr,$replace_arr,$email_content);	
					
					$find_subject 		= array('{latest_update}','{site_url}','{site_title}','{earned_point}');
					$replace_subject 	= array($latest_update,$site_url_text,$site_title,$review_points);
					
					$email_subject 	= str_replace($find_subject,$replace_subject,$email_subject);	
					
					wp_mail($user_email,$email_subject,$email_content,$headers);
				}

			}
		}
	}

	/**
	 * Added total calculated points to checkout
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.2
	 */
	public function woo_pr_review_order_after_cart_contents() {

		//Check if enable tax points and woocommerce tax
		$enable_tax_points     = get_option('woo_pr_enable_tax_points');
		if ( $enable_tax_points == 'yes' && wc_tax_enabled() ) {
 
			global $woocommerce;
			$cart_data  = $woocommerce->cart->get_cart();
			$totalpoints = $this->model->woo_pr_get_user_checkout_points( $cart_data );
			echo '<input id="woo_pr_total_points_will_earn" type="hidden" value="'. $totalpoints .'">';
		}
	}

	/**
	 * Get added and minus points by userid and date
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	public function woo_pr_expiration_points_log_data( $user_id, $expiry_date = '', $notice_date = '' ) {

		$prefix = WOO_PR_META_PREFIX;
		$add_points_data = $minus_points_data = $points_log_data = array();

		if( empty($expiry_date) ){
			$expiry_date = date( 'Y-m-d', current_time( 'timestamp') );
		}

		// Get all points ids for which are earn points
		$expiry_points_args = array(
			'fields'        => 'ids',
			'post_type'     => WOO_POINTS_LOG_POST_TYPE,
			'post_status'   => 'publish' ,
			'posts_per_page'=> '-1',
			'author'        => $user_id,
			'meta_query'    => array(
				array(
					'key'     => $prefix.'expiry_processed',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => '_woo_log_operation',
					'value'   => 'add'
				),
			),
			"orderby" => 'meta_value_num',
			"meta_key" => $prefix.'expiry_date',
			"order" => 'ASC'
			
		);

		// set meta query for points expiry date is single date or date range
		if( !empty($notice_date) && $expiry_date != $notice_date ){

			$expiry_points_args['meta_query'][] = array(
					'key'     => $prefix.'expiry_date',
					'value'   => $expiry_date,
					'type'    => 'DATE',
					'compare' => '>=',
				);
			$expiry_points_args['meta_query'][] = array(
					'key'     => $prefix.'expiry_date',
					'value'   => $notice_date,
					'type'    => 'DATE',
					'compare' => '<=',
				);

		} else {
			$expiry_points_args['meta_query'][] = array(
					'key'     => $prefix.'expiry_date',
					'value'   => $expiry_date,
					'type'    => 'DATE',
					'compare' => '<',
				);
		}

		// Get all order ids for which our meta is not set
		$points_query    = new WP_Query( $expiry_points_args );
		$points_ids      = !empty( $points_query->posts ) ? $points_query->posts : array();

		// otherwise go through the results and get the points
		if ( !empty( $points_ids ) && is_array( $points_ids ) ) {

			foreach( $points_ids as $point_id ) {

				// get points meta
				$point_date = get_the_date( 'Y-m-d H:i:s', $point_id );
				$point_expiry_date = get_post_meta( $point_id, $prefix.'expiry_date', true );
				$log_userpoint = (float)get_post_meta( $point_id, '_woo_log_userpoint', true );

				$add_points_data[$point_id] = $log_userpoint;

				$points_log_data[] = array(
					'point_id' => $point_id,
					'points' => $log_userpoint,
					'point_expiry_date' => $point_expiry_date,
				);

				// Get all points ids for which are debited points between the points earn date and expiry date
				$minus_points_args = array(
					'fields'        => 'ids',
					'post_type'     => WOO_POINTS_LOG_POST_TYPE,
					'post_status'   => 'publish' ,
					'posts_per_page'=> '-1',
					'author'        => $user_id,
					'date_query' => array(
						array(
							'after'     => $point_date,
							'before'    => $point_expiry_date,
							'inclusive' => true,
						),
					),
					'meta_query'    => array(
						array(
							'key'     => '_woo_log_operation',
							'value'   => 'minus'
						),
						array(
							'key'     => '_woo_log_events',
							'value'   => 'expiration',
							'compare' => '!='
						),
					),
				);

				$minus_points_query    = new WP_Query( $minus_points_args );
				$minus_points_ids      = !empty( $minus_points_query->posts ) ? $minus_points_query->posts : array();

				// otherwise go through the results and get the points
				if ( !empty( $minus_points_ids ) && is_array( $minus_points_ids ) ) {

					foreach( $minus_points_ids as $minus_points_id ) {

						$minus_log_userpoint = (float)get_post_meta( $minus_points_id, '_woo_log_userpoint', true );
						if( $minus_log_userpoint < 0 ){
							$minus_log_userpoint *= -1 ;
						}

						$minus_points_data[$minus_points_id] = $minus_log_userpoint;

					}
				}

			} //end foreach loop
		} //end if check retrive payment ids are array

		return array( 'add_points' => $add_points_data, 'minus_points' => $minus_points_data, 'points_log_data' => $points_log_data );
	}

	/**
	 * Process for expiration points of current date
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	public function woo_pr_expiration_points_process() {

		$prefix = WOO_PR_META_PREFIX;
		$today_date = date( 'Y-m-d', current_time( 'timestamp'));

		// Check all user
		$all_user_ids = get_users( array( 'fields' => 'ids' ) );

		if( !empty($all_user_ids) ){

			foreach ($all_user_ids as $user_id) {

				// get user points by user and date
				$today_points_data = $this->woo_pr_expiration_points_log_data($user_id, $today_date);

				if( !empty($today_points_data['add_points']) ){

					$add_points_data = !empty($today_points_data['add_points']) ? $today_points_data['add_points'] : '' ;
					$minus_points_data = !empty($today_points_data['minus_points']) ? $today_points_data['minus_points'] : '' ;

					$add_points_total = (is_array($add_points_data)) ? round(array_sum($add_points_data), 2 ) : 0;
					$minus_points_total = (is_array($minus_points_data)) ? round(array_sum($minus_points_data), 2 ) : 0;

					$remaining_points = $add_points_total - $minus_points_total;

					// If remaining points greater than zero then add point log for expiration points
					if( $remaining_points > 0 ){

						//points label
						$pointslable = $this->model->woo_pr_get_points_label($remaining_points);

						$post_data = array(
							'post_title' => sprintf(esc_html__('%s Expiration', 'woopoints'), $pointslable),
							'post_content' => sprintf(esc_html__('Earned %s debited towards expiration', 'woopoints'), $pointslable),
							'post_author' => $user_id
						);
						$log_meta = array(
							'userpoint' => $remaining_points,
							'events' => 'expiration',
							'operation' => 'minus' //add or minus
						);

						$this->logs->woo_pr_insert_logs($post_data, $log_meta);

						// Deduct points from user points log
						woo_pr_minus_points_from_user($remaining_points, $user_id);

					}
						// Add meta for expiry points logs
						if( !empty($add_points_data) ){

							foreach ($add_points_data as $log_id => $log_points) {
								update_post_meta( $log_id, $prefix.'expiry_processed', 1 );
							}
						}

					}
			}

		}

	}

	/**
	 * Show notice next expiration points in woocommerce dashboard page
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	public function woo_pr_expiration_points_notice(){

		// get options 
		$enable_notice_expiration = get_option('woo_pr_enable_notice_points_expiration');
		$notice_days_expiry = get_option('woo_pr_expiration_notice_days');
		$wc_dashboard_notice = get_option('woo_pr_expiration_notice_message');
		$enable_expiration_points = get_option('woo_pr_enable_points_expiration');

		// If current user is login and expiration points, expiry notice is enable
		if( is_user_logged_in() && !empty($enable_expiration_points) && !empty($enable_notice_expiration) && !empty($notice_days_expiry) && !empty($wc_dashboard_notice) && $enable_expiration_points == 'yes' && $enable_notice_expiration == 'yes' ){
		
			$user_id = get_current_user_id();
			$today_date = date( 'Y-m-d', current_time( 'timestamp') );
			$notice_date = date( 'Y-m-d', strtotime(" +$notice_days_expiry days", current_time( 'timestamp')) );
			$date_format = get_option( 'date_format' );

			// get user points by user and date
			$today_points_data = $this->woo_pr_expiration_points_log_data( $user_id, $today_date, $notice_date );

			if( !empty($today_points_data['add_points']) ){
				
				$add_points_data = !empty($today_points_data['add_points']) ? $today_points_data['add_points'] : '' ;
				$minus_points_data = !empty($today_points_data['minus_points']) ? $today_points_data['minus_points'] : '' ;

				$add_points_total = (is_array($add_points_data)) ? round(array_sum($add_points_data), 2 ) : 0;
				$minus_points_total = (is_array($minus_points_data)) ? round(array_sum($minus_points_data), 2 ) : 0;
				$total_used_points   = $minus_points_total;

				//remaining points and points label
				$remaining_points = $add_points_total - $minus_points_total;
				$pointslable = $this->model->woo_pr_get_points_label($remaining_points);
				$expire_point_table = '';
			
				if( $remaining_points > 0 ){
					
					$is_exp_tble = false;

					if( isset( $today_points_data['points_log_data'] ) && !empty( $today_points_data['points_log_data'])){

						$expire_point_table = '<table class="woopr-expiry-points-table"width="80%" cellspacing="0" cellpadding="5" align="center">';
						$expire_point_table .= '<tr class="woopr-head"><th>'.esc_html__('S.No','woopoints').'</th><th>'.esc_html__('Points','woopoints').'</th><th>'.esc_html__('Expiry Date','woopoints').'</th></tr>'; 
						$expire_points = $today_points_data['points_log_data'];
						
						$srno = 1;

						foreach ( $expire_points as $key => $expire_points ) {

							if( !empty( $expire_points['point_expiry_date'] ) ){
								
								$p_expiry_date = date_i18n( $date_format,strtotime($expire_points['point_expiry_date']));

								if( $total_used_points > 0 ){

									if( $expire_points['points'] <= $total_used_points ){
										$total_used_points = $total_used_points - $expire_points['points'];
										continue;
									}

									$deducted_points = $expire_points['points'] - $total_used_points;
									$total_used_points = 0;
									$expire_point_table .= '<tr><td>'.$srno
									.'</td><td>'.$deducted_points.'</td><td>'.$p_expiry_date.'</td></tr>';
									$is_exp_tble = true;                                            
								} else{

									$expire_point_table .= '<tr><td>'.$srno
									.'</td><td>'.$expire_points['points'].'</td><td>'.$p_expiry_date.'</td></tr>'; 
									$is_exp_tble = true;
								}

								$srno++;
							}
						}

						$expire_point_table .= '</table>';
					}

					if( !$is_exp_tble ){
						$expire_point_table = '';
					}           

					$find = array( '{points}', '{points_label}', '{expiry_days}');
					$replace = array( $remaining_points, $pointslable, $notice_days_expiry );
					$wc_dashboard_notice = str_replace( $find, $replace, $wc_dashboard_notice );

					// wrap with info div
					$wc_dashboard_notice = '<div class="woocommerce-info woo-pr-earn-points-message">' . $wc_dashboard_notice . '</div>';

					echo apply_filters( 'woo_pr_expiration_notice_message', $wc_dashboard_notice.$expire_point_table, $remaining_points, $notice_days_expiry );

				}

			}
		}

	}
	
	
	/**
	 * Show message on variation change
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	public function woo_pr_change_points_meesage_variation_wise(){
		global $post, $current_user;
		
		$variation_id 	=	$_POST['variation_id'];
		$proudct_id 	=	$_POST['proudct_id'];
				 
		$product = get_post( $proudct_id );
		$current_user_id =   $current_user->ID;	
		$product_author = $product->post_author;
	
		$responce = "";		
		$prefix     = WOO_PR_META_PREFIX;	
		
		
		$woo_pr_single_product_message = get_option( 'woo_pr_single_product_message' );		
		
		$data_id = !empty( $variation_id) ? $variation_id : $proudct_id;

		$earning_product_points = $this->model->woo_get_product_earn_point($data_id, 1);
		
		
		if( isset($_POST['variation_id']) && isset( $_POST['proudct_id'] ) ){
						
			
			if($current_user_id == $product_author ){
				if( empty( $excluded_product ) || ( !in_array($proudct_id,$excluded_product) && !in_array($_POST['variation_id'],$excluded_product) ) ){
					$responce = $this->model->woo_pr_owner_product_message( 'product' );
				}
			}
			else{	

				$is_allowed = $this->model->wpp_pr_check_product_allow_for_points_earned( $proudct_id, $variation_id );

				if( $is_allowed == true ){

					$variation_points 	= get_post_meta($variation_id,$prefix.'points_earned',true);	
					$product_points 	= get_post_meta($proudct_id,$prefix.'rewards_earn_point',true);	
						
						if(!empty($variation_points)){					
							$earningpoints = $variation_points;	
						}
						else{
							$earningpoints = $this->model->woo_pr_get_earning_points($proudct_id);
							
							if( is_array( $earningpoints ) ){
							 $earningpoints = array_unique($earningpoints);
							 $earningpoints = implode('-',$earningpoints);			
							}
						}		
								
						$points_label = $this->model->woo_pr_get_points_label( $earningpoints );			
						$points_replace     = array( "{points}","{points_label}" );
						$replace_message    = array( $earningpoints , $points_label );
						$message            = $this->model->woo_pr_replace_array( $points_replace, $replace_message, $woo_pr_single_product_message );
							if(!empty($message)){
								 $responce =  $message;		
							}
				}
			}
		} //Check excluded_product
		echo $responce;
	   wp_die();	
	}
	
	
	/**
	 * Add Tabs to my account page.
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	 
	public function woo_pr_add_point_tab_myaccount_page($menu_links ){
		
		$show_points_tab = !empty(get_option('woo_pr_show_my_points_tab')) ? get_option('woo_pr_show_my_points_tab') : '';
		if($show_points_tab != "yes" ){
			
			$plural_label = get_option('woo_pr_lables_points_monetary_value');
			$pointslabel = isset( $plural_label ) && !empty( $plural_label )? ucfirst( $plural_label ) : esc_html__( 'Points', 'woopoints' );								
			
			$point_histry_tab = array( 				
				'woo-pr-point-history' =>  sprintf(esc_html__('My %s ', 'woopoints'), $pointslabel)			
			);
			
			$menu_links = array_slice( $menu_links, 0, 5, true ) 
			+ $point_histry_tab 
			+ array_slice( $menu_links, 1, NULL, true );
		}
 
		return $menu_links;
	}
	
	/**
	 * Set permalink for the My point tab
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	public function woo_pr_add_my_account_endpoint(){
		$show_points_tab = !empty(get_option('woo_pr_show_my_points_tab')) ? get_option('woo_pr_show_my_points_tab') : '';
		if($show_points_tab != "yes" ){
			add_rewrite_endpoint( 'woo-pr-point-history', EP_PAGES );
			flush_rewrite_rules();
		}
	}
	
	/**
	 *  Add content for the my point tab.
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	 
	public function woo_pr_information_endpoint_content(){
		$show_points_tab = !empty(get_option('woo_pr_show_my_points_tab')) ? get_option('woo_pr_show_my_points_tab') : '';
		if($show_points_tab != "yes" ){
			echo do_shortcode('[woopr_points_history]');
		}
		else{
			esc_html_e("Sorry, you are not allowed to access","woopoints");
		}
	}
	
	
	
	/**
	 * Get added and minus points by userid and date
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	public function woo_pr_expiration_points_email_log_data( $user_id, $expiry_date = '', $notice_date = '' ) {

		$prefix = WOO_PR_META_PREFIX;
		$add_points_data = $minus_points_data = array();

		if( empty($expiry_date) ){
			$expiry_date = date( 'Y-m-d', current_time( 'timestamp') );
		}

		// Get all points ids for which are earn points
		$expiry_points_args = array(
			'fields'        => 'ids',
			'post_type'     => WOO_POINTS_LOG_POST_TYPE,
			'post_status'   => 'publish' ,
			'posts_per_page'=> '-1',
			'author'        => $user_id,
			'meta_query'    => array(
				'relation' => 'AND',
				array(
					'key'     => $prefix.'expiry_email_notification',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => '_woo_log_operation',
					'value'   => 'add',
				),
			),
		  "orderby" => 'meta_value_num',
		  "meta_key" => $prefix.'expiry_date',
		  "order" => 'ASC'
			
		);

		// set meta query for points expiry date is single date or date range
		if( !empty($notice_date) && $expiry_date != $notice_date ){

			$expiry_points_args['meta_query'][] = array(
					'key'     => $prefix.'expiry_date',
					'value'   => $expiry_date,
					'type'    => 'DATE',
					'compare' => '>=',
				);
			$expiry_points_args['meta_query'][] = array(
					'key'     => $prefix.'expiry_date',
					'value'   => $notice_date,
					'type'    => 'DATE',
					'compare' => '<=',
				);

		} else {
			$expiry_points_args['meta_query'][] = array(
					'key'     => $prefix.'expiry_date',
					'value'   => $expiry_date,
					'type'    => 'DATE',
					'compare' => '<',
				);
		}

		// Get all order ids for which our meta is not set
		$points_query    = new WP_Query( $expiry_points_args );
		$points_ids      = !empty( $points_query->posts ) ? $points_query->posts : array();

		$points_log_data = array();

		// otherwise go through the results and get the points
		if ( !empty( $points_ids ) && is_array( $points_ids ) ) {

			foreach( $points_ids as $point_id ) {

				// get points meta
				$point_date = get_the_date( 'Y-m-d', $point_id );
				$point_expiry_date = get_post_meta( $point_id, $prefix.'expiry_date', true );
				$log_userpoint = (float)get_post_meta( $point_id, '_woo_log_userpoint', true );

				$add_points_data[$point_id] = $log_userpoint;
				$points_log_data[] = array(
						'point_id' => $point_id,
						'points' => $log_userpoint,
						'point_expiry_date' => $point_expiry_date,
					);


				// Get all points ids for which are debited points between the points earn date and expiry date
				$minus_points_args = array(

			'fields'        => 'ids',
			'post_type'     => WOO_POINTS_LOG_POST_TYPE,
			'post_status'   => 'publish',
			'posts_per_page'=> '-1',
			'author'        => $user_id,
			'date_query' => array(
				array(
					'after'     => $point_date,
					'before'    => $point_expiry_date,
					'inclusive' => true,
				),
			),
			'meta_query'    => array(
				'relation' => 'AND',
				array(
					'key'     => '_woo_log_operation',
					'value'   => 'minus',
				),
				array(
					'key'     => '_woo_log_events',
					'value'   => 'expiration',
					'compare' => '!='
				),
			),
		);

		$minus_points_query    = new WP_Query( $minus_points_args );

		$minus_points_ids      = !empty( $minus_points_query->posts ) ? $minus_points_query->posts : array();

		// otherwise go through the results and get the points
		if ( !empty( $minus_points_ids ) && is_array( $minus_points_ids ) ) {

			foreach( $minus_points_ids as $minus_points_id ) {

						$minus_log_userpoint = (float) get_post_meta( $minus_points_id, '_woo_log_userpoint', true );
						if( $minus_log_userpoint < 0 ){
							$minus_log_userpoint *= -1 ;
						}

						$minus_points_data[$minus_points_id] = $minus_log_userpoint;

					}
				}

			} //end foreach loop
		} //end if check retrive payment ids are array


		return array( 'add_points' => $add_points_data, 'minus_points' => $minus_points_data, 'points_log_data' => $points_log_data );
	}


	
	/**
	 *  Scheduled Action Hook for expiration email notification
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */
	
	function expiration_mail_cron_callback( ) {	
		$prefix = WOO_PR_META_PREFIX;
		
		$enable_expire_email 	= get_option('woo_pr_enable_expire_point_email');
		$expire_email_subject 	= get_option('woo_pr_expire_point_email_subject');
		$expire_email_content	= get_option('woo_pr_expire_point_email_content ');		
		
		if($enable_expire_email = 'yes'  && !empty($expire_email_subject) && !empty($expire_email_content) ){
			
			$notice_days_expiry 	= !empty(get_option('woo_pr_expire_point_email_before_day'))?get_option('woo_pr_expire_point_email_before_day'):'1';
			
			$today_date 	= date( 'Y-m-d', current_time( 'timestamp') );
			$notice_date 	= date( 'Y-m-d', strtotime(" +$notice_days_expiry days", current_time( 'timestamp')) );		
			$all_user_ids 	= get_users( array( 'fields' => 'ids' ) );
			$date_format = get_option( 'date_format' );

			if( !empty($all_user_ids) ){

				foreach ($all_user_ids as $user_id) {

					$today_points_data = $this->woo_pr_expiration_points_email_log_data( $user_id, $today_date, $notice_date );

					if( !empty($today_points_data['add_points']) ){
						 
						$add_points_data 	 = !empty($today_points_data['add_points']) ? $today_points_data['add_points'] : '' ;
						$minus_points_data 	 = !empty($today_points_data['minus_points']) ? $today_points_data['minus_points'] : '' ;

						$add_points_total 	 = (is_array($add_points_data)) ? round(array_sum($add_points_data), 2 ) : 0;
						$minus_points_total  = (is_array($minus_points_data)) ? round(array_sum($minus_points_data), 2 ) : 0;
						$total_used_points   = $minus_points_total;
					
						$remaining_points 	 = $add_points_total - $minus_points_total;
						$pointslable		 = $this->model->woo_pr_get_points_label($remaining_points);
						
						if( $remaining_points > 0 ){
							
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
							$userdata  		        = get_userdata($user_id); 								
							$username 		        = $userdata->user_login;
							$useremail 		        = $userdata->user_email;
							$site_url_text          = site_url();
							$site_title             = get_option('blogname');
							$expire_point_table     = '';

							//$expiry_date = date('Y-m-d', strtotime($notice_date. ' - 1 days'));
							$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $remaining_points );	
							$total_points_amount_currency = woo_pr_wcm_currency_convert($total_points_amount);
							$point_amount =  wc_price($total_points_amount_currency);
							
							$point_amount = sprintf(esc_html__("%s %s which are worth an amount of %s","woopoints"), $remaining_points,$pointslable,$point_amount);
							$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';

							if( isset( $today_points_data['points_log_data'] ) && !empty( $today_points_data['points_log_data'])){

								$expire_point_table = '<table style="border: 1px solid #5e5e5e;" width="80%" cellspacing="0" cellpadding="5" align="center">';
								$expire_point_table .= '<tr style="background:black;color:white;text-align: center;"><th>'.esc_html__('S.No','woopoints').'</th><th>'.esc_html__('Points','woopoints').'</th><th>'.esc_html__('Expiry Date','woopoints').'</th></tr>'; 
								$expire_points = $today_points_data['points_log_data'];
								
								$srno = 1;
								$is_exp_tble = false;

								foreach ( $expire_points as $key => $expire_points ) {

									if( !empty( $expire_points['point_expiry_date'] ) ){
										
										$p_expiry_date = date_i18n( $date_format,strtotime($expire_points['point_expiry_date']));

										if( $total_used_points > 0 ){

											if( $expire_points['points'] <= $total_used_points ){
												$total_used_points = $total_used_points - $expire_points['points'];
												continue;
											}

											$deducted_points = $expire_points['points'] - $total_used_points;
											$total_used_points = 0;
											$expire_point_table .= '<tr style="text-align:center;"><td>'.$srno
											.'</td><td>'.$deducted_points.'</td><td>'.$p_expiry_date.'</td></tr>';
											$is_exp_tble = true;                                            
										} else{

											$expire_point_table .= '<tr style="text-align:center;"><td>'.$srno
											.'</td><td>'.$expire_points['points'].'</td><td>'.$p_expiry_date.'</td></tr>'; 
											$is_exp_tble = true;
										}

										$srno++;
									}
								}

								$expire_point_table .= '<table>';
							}

							if( !$is_exp_tble ){
								$expire_point_table = '';
							}

							$find 		= array('{username}','{expiring_points}','{point_label}','{expiring_date}','{site_url}','{site_title}', '{expire_points_details}','{expiry_days}');

							$replace 	= array($username,$point_amount,$pointslable,$notice_date,$site_url,$site_title,$expire_point_table,$notice_days_expiry);

							$new_email_content = str_replace($find,$replace,$expire_email_content);

							
							$find_subject 		= array('{site_url}','{site_title}');
							$replace_subject 	= array($site_url_text,$site_title);
							$expire_email_subject = str_replace($find_subject,$replace_subject,$expire_email_subject);	
							
							wp_mail($useremail,$expire_email_subject,$new_email_content,$headers);							
							
							foreach($today_points_data['add_points'] as $log_id=>$total_points){
								update_post_meta($log_id,$prefix.'expiry_email_notification',1);								
							}	
						}
					}
				}				
			}
		}		
	}
	
	
	/**
	 *  Schedule Cron Job Event for expiration email notification
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */	
	function woo_pr_expiration_mail_cron() {
		if ( ! wp_next_scheduled( 'expiration_mail_cron_callback' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'daily', 'expiration_mail_cron_callback' );
		}
	}
	
	
	/**
	 *  Prevent coupon code if point applied
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.11
	 */	
	 
	public function woo_pr_prevent_coupon_code_point_redeemed($enabled){
		global $woocommerce;
		
		if( !empty( $woocommerce->cart ) ){

			$woo_fees = $woocommerce->cart->get_fees();
			$prevent_coupon = !empty(get_option('woo_pr_prevent_coupon_usag'))?get_option('woo_pr_prevent_coupon_usag'):'no';
			
			//points plural label
			$plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__( 'Points', 'woopoints' );
			$woo_pr_fee_name = $plurallable.esc_html__( ' Discount', 'woopoints' );
			$woo_pr_fee_name = str_replace( ' ', '-', strtolower( $woo_pr_fee_name ) );
			
			if(!empty($woo_fees) && $prevent_coupon == 'yes' ){			
					
				foreach ($woo_fees as $woo_fee_key => $woo_fee_val) {

					if (strpos($woo_fee_key, $woo_pr_fee_name) !== false) {
						$enabled = false;					
					}
				}
			}
		}
		return $enabled;
	}

	 /**
	 * Redeem used points
	 * 
	 * Handles to Points redeemed towards purchase on change status
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function woo_pr_redeem_points_on_status_change( $this_get_id,  $this_status_transition_from,  $this_status_transition_to,  $instance){

		$order_id = $this_get_id;
		$args = array(
		   'post_type' => 'woopointslog',
		   'post_status'       => 'publish',
		   'posts_per_page'    => 1,
		   'meta_query' => array(
			'relation' => 'AND',
			   array(
				   'key' => '_woo_log_order_id',
				   'value' => $order_id,
				   'type'    => 'numeric',
				   'compare' => '=',
			   ),
			   array(
				   'key' => '_woo_log_events',
				   'value' => 'redeemed_purchase',
				   'compare' => 'LIKE',
			   ),
		   )
		);
		$redeem_log = get_posts($args);
	 
		//global $current_user;
		$prefix = WOO_PR_META_PREFIX;
		$order = wc_get_order($order_id);
		// $order_data = $order->data;

		$order_status = 'wc-'.$this_status_transition_to;
		$redeem_point_amount = get_post_meta($order_id,$prefix.'redeem_order',true);
		$get_order_status = get_option('woo_pr_redd_on_status');

	
		//points plural label
		$plurallable = !empty(get_option('woo_pr_lables_points_monetary_value')) ? get_option('woo_pr_lables_points_monetary_value') : esc_html__( 'Points', 'woopoints' );
		
		if ( !empty( $redeem_point_amount ) && empty( $redeem_log ) ) {

			$user_id        = $order->get_customer_id();
			$current_points = $redeem_point_amount;
			$points_label   = $this->model->woo_pr_get_points_label( $current_points );
			$log_title      = $points_label.esc_html__( ' redeemed towards purchase', 'woopoints' );

			//check number contains minus sign or not
			if (strpos($current_points, '-') !== false) {
				$current_points = str_replace('-', '', $current_points);
			} 

			if ( in_array($order_status, $get_order_status) || empty( $get_order_status ) ) {

				// Update user points to user account
				woo_pr_minus_points_from_user($current_points, $user_id);

				$post_data = array(
					'post_title'    => $log_title,
					'post_content'  => $log_title,
					'post_author'   => $user_id
				);

				$log_meta = array(
					'order_id'  => $order_id,
					'userpoint' => abs($current_points),
					'events'    => 'redeemed_purchase',
					'operation' => 'minus' //add or minus
				);

				$this->logs->woo_pr_insert_logs($post_data, $log_meta);

				// Add order note for Points and Rewards
				$order->add_order_note(sprintf(esc_html__('%1$d %2$s debited discount towards to customer.', 'woopoints'), $current_points, $plurallable));
				
				
				$enable_redeem_email    = get_option('woo_pr_enable_redeem_email');
				$redeem_email_subject   = get_option('woo_pr_redeem_point_email_subject');
				$redeem_email_content   = get_option('woo_pr_redeem_point_email_content');
				
				if($enable_redeem_email == 'yes' && !empty($redeem_email_subject)  && !empty($redeem_email_content) ){
					
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						
						$to_email       = $order->get_billing_email();                  
						$customer_data  = get_userdata($order->get_customer_id());                  
						$username       = $customer_data->user_login;
						$total_point    = woo_pr_get_user_points($order->get_customer_id()); 
						$total_points_amount = $this->model->woo_pr_calculate_discount_amount( $total_point );  
						
						$site_url = '<a href="'.site_url().'">'.site_url() .'</a>';
						$site_url_text = site_url();
						$site_title = get_option('blogname');
						
						$latest_update  = esc_html__("product purchase","woopoints");
						
						$total_amount_message  = sprintf(esc_html__("%s which are worth an amount of %s","woopoints"),$total_point,wc_price(woo_pr_wcm_currency_convert($total_points_amount)));
						$tags_arr       = array('{username}','{redeemed_point}','{point_label}','{latest_update}','{total_point}','{site_url}','{site_title}'); 
						$replace_arr    = array($username,$current_points,$points_label,$latest_update,$total_amount_message,$site_url,$site_title); 
						$email_content  = str_replace($tags_arr,$replace_arr,$redeem_email_content);                        
						
					
						$find_subject = array('{latest_update}','{site_url}','{site_title}','{redeemed_point}');
						$replace_subject = array($latest_update,$site_url_text,$site_title,$current_points);
						$email_subject  = str_replace($find_subject,$replace_subject,$redeem_email_subject);
						
						wp_mail($to_email,$email_subject,$email_content,$headers);              
				}           
			}
		}


	}

	/**
	 * Show points with total discounts including tax
	 *
	 * Front-end functionality to show total points with discounts including date
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 2.2.1
	*/  

	public function woo_pr_tax_show_full_points( $item_total , $item_tax ){
		
		$is_apply_on_cart = get_option('woo_pr_discount_on_carttotal');

		if( empty( $is_apply_on_cart) || $is_apply_on_cart !== 'yes' ){
			return $item_total;             
		}

		if ( $item_tax != 0 ) {
				$item_total = $item_total + $item_tax;
		}

		return $item_total; 

	}



	/**
	 * Re-calculate with full points
	 *
	 * Custom code to re-calculate points with amount if sufficient balance is available
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 2.2.1
	*/  


	public function woo_pr_tax_re_calculate_full_discount_points( $taxes, $fee, $cart ){

		global $woo_pr_model,$woocommerce;

		$is_apply_on_cart = get_option('woo_pr_discount_on_carttotal');

		if( empty( $is_apply_on_cart) || $is_apply_on_cart !== 'yes' ){
			return $taxes;             
		}

		$cart_max_discount_points = $woo_pr_model->woo_pr_get_discount_for_redeeming_points_from_cart( $woocommerce->cart );
		$orignal_fee = $fee;
		$cart_max_discount_amount = $total_amount = 0;
		if( !empty($cart_max_discount_points) && $cart_max_discount_points > 0 ){

			$cart_max_discount_amount = $woo_pr_model->woo_pr_calculate_discount_amount( $cart_max_discount_points );                   

			// Conver the amount - converting points into amounts
			$cart_max_discount_amount = woo_pr_wcm_currency_convert( $cart_max_discount_amount );

			if ( ! WC()->cart->prices_include_tax ) {
				$total_amount = WC()->cart->cart_contents_total;
			} else {
				$total_amount = WC()->cart->cart_contents_total + WC()->cart->tax_total;
			}
			$total_amount = number_format((float)$total_amount, 2, '.', '');

			if( $cart_max_discount_amount ==  $total_amount ) {

				$cart_max_discount_amount = -1 * ( $cart_max_discount_amount * 100);    

				$fee->total = $cart_max_discount_amount;
				

			} 

		}       

		return $taxes;

	}

	/**
	 * Show order total with single tax amount
	 *
	 * After applying points with full discount it was showing order total with double tax
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 2.2.1
	*/

	public function woo_pr_update_tax_to_zero_cart_checkout( $html ){

		$is_apply_on_cart = get_option('woo_pr_discount_on_carttotal');

		if( empty( $is_apply_on_cart) || $is_apply_on_cart !== 'yes' ){
			return $html;             
		}

	  	global $woocommerce;  

	  	if( $woocommerce->cart->total == "0.00" ) {

		  $html = '';   
		  $formatted_total = wc_price( $woocommerce->cart->total );
		  $html .= $formatted_total;

	  	} else {


		   if( isset( $_POST['woo_pr_apply_discount'] ) && $_POST['woo_pr_apply_discount'] == 'Apply Discount') {

				$_SESSION['applied_pr_discount'] = 'yes';

		   }

		   if( !empty($_SESSION) && $_SESSION['applied_pr_discount'] == 'yes' ) {
		   		$value = '';
		   		$tax_string_array = array();
		   		$cart_tax_totals  = WC()->cart->get_tax_totals();
		   		$rates = @reset( WC_Tax::get_rates() )['rate'];

		   		$applicable_tax_on_discount = round($woocommerce->cart->total * ( round( $rates ) / 100 ),2);

		   		if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) {
					foreach ( $cart_tax_totals as $code => $tax ) {
						$tax_string_array[] = sprintf( '%s %s', wc_price($applicable_tax_on_discount), $tax->label );
					}
				} elseif ( ! empty( $cart_tax_totals ) ) {
					$tax_string_array[] = sprintf( '%s %s', wc_price( $applicable_tax_on_discount ), WC()->countries->tax_or_vat() );
				}

				if ( ! empty( $tax_string_array ) ) {
					$taxable_address = WC()->customer->get_taxable_address();
					if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
						$country = WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ];
						/* translators: 1: tax amount 2: country name */
						$tax_text = wp_kses_post( sprintf( __( '(includes %1$s estimated for %2$s)', 'woocommerce' ), implode( ', ', $tax_string_array ), $country ) );
					} else {
						/* translators: %s: tax amount */
						$tax_text = wp_kses_post( sprintf( __( '(includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) );
					}

					$value .= '<small class="includes_tax">' . $tax_text . '</small>';
				}

				$formatted_total = $value; 
				$html = wc_price($woocommerce->cart->total); 
				$html .= $formatted_total;
			
		   }
	  	}

	  return $html;

	}    


	/**
	 * Show order total with single tax amount - remove order total without tax if zero after placing order.
	 *
	 * After applying points with full discount it was showing order total with double tax
	 * 
	 * @package WooCommerce - Points and Rewards
	 * @since 2.2.1
	*/

	public function woo_pr_update_tax_to_zero_after_placing_order( $html, $order, $tax_display, $display_refunded  ) {

		$is_apply_on_cart = get_option('woo_pr_discount_on_carttotal');

		if( empty( $is_apply_on_cart) || $is_apply_on_cart != 'yes' ){
			return $html;             
		}

		if( is_admin() ) {
			return $html;
		}

		$total_order_amount =  $order->get_total();

		if( $total_order_amount == "0.00" ) {

		  $html = '';   
		  $formatted_total = wc_price( $total_order_amount );
		  $html .= $formatted_total;

	   } else {

			if( isset( $_SESSION['applied_pr_discount'] ) && $_SESSION['applied_pr_discount'] == 'yes' ) {

				$applicable_tax_on_discount = round($total_order_amount * ( round( reset( WC_Tax::get_rates() )['rate'] ) / 100 ),2);
				$tax_totals = $order->get_tax_totals();
				$tax_string_array = array();

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
					foreach ( $tax_totals as $code => $tax ) {
						$tax_amount         = wc_price( $applicable_tax_on_discount, array( 'currency' => $order->get_currency() ) );
						$tax_string_array[] = sprintf( '%s %s', $tax_amount, $tax->label );
					}
				} elseif ( ! empty( $tax_totals ) ) {
					$tax_amount         = $applicable_tax_on_discount;
					$tax_string_array[] = sprintf( '%s %s', wc_price( $tax_amount, array( 'currency' => $order->get_currency() ) ), WC()->countries->tax_or_vat() );
				}

				if ( ! empty( $tax_string_array ) ) {
					/* translators: %s: taxes */
					$tax_string = ' <small class="includes_tax">' . sprintf( __( '(includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) . '</small>';
				}

				$formatted_total = $tax_string;
				$html = wc_price($total_order_amount); 
				$html .= $formatted_total;
			
		   	}    

	   }

	   return $html;

	}
	
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hoocks for the public pages.
	 *
	 * @package WooCommerce - Points and Rewards
	 * @since 1.0.0
	 */
	public function add_hooks() {
		$user_id = get_current_user_id();

		$earn_user_exclude_role = '';
		$redeem_user_exclude_role = '';

		if( !empty( $user_id ) ){
			$earn_user_exclude_role = woo_pr_check_exclude_role( $user_id,'earn' );
		}

		if( !empty( $user_id ) ){
			$redeem_user_exclude_role = woo_pr_check_exclude_role( $user_id,'redeem' );
		}

		// Add action to create log when new user register.
		add_action('user_register', array($this, 'woo_pr_create_log_account_signup'));

		// Add action to create log when new post or blog created.
		add_action(  'publish_post',  array($this, 'woo_pr_create_log_post_creation'), 10, 2 );

		// Add action to create log when new product created.
		add_action(  'publish_product',  array($this, 'woo_pr_create_log_product_creation'), 10, 2 );

		// Add action to create log when user loggedin.
		add_action("wp_login", array($this, 'woo_pr_create_log_daily_login'));

		// Add action for order cancelled or refunded
		add_action('woocommerce_order_status_refunded', array($this, 'woo_pr_handle_cancelled_refunded_order'));
		add_action('woocommerce_order_status_cancelled', array($this, 'woo_pr_handle_cancelled_refunded_order'));
		add_action('woocommerce_order_status_failed', array($this, 'woo_pr_handle_cancelled_refunded_order'));

		// Add action to add/remove points discount.
		add_action('wp', array($this, 'woo_pr_handle_redeem_points_add_remove_session'), 1);


		// add action when order status goes to complete or processing
		add_action('woocommerce_order_status_completed', array($this, 'woo_pr_order_processing_completed_update_points'));
		add_action('woocommerce_order_status_processing', array($this, 'woo_pr_order_processing_completed_update_points'));

		if( $earn_user_exclude_role !== false ){

			// Add action to show message for puchase points before cart button
			add_action('woocommerce_before_add_to_cart_button', array($this, 'woo_pr_points_message_before_add_to_cart_button' ));

			// add earn points points message above cart
			add_action( 'woocommerce_before_cart', array( $this,'woo_pr_cart_checkout_message_content'),15);

			add_action( 'woocommerce_before_checkout_form', array( $this, 'woo_pr_cart_checkout_message_content' ), 5 );

			// Add action to add filter for add to cart button
			add_action( 'woocommerce_woo_pr_points_add_to_cart', 'woocommerce_woo_pr_points_add_to_cart' );

			//Action to rate on product
			add_action( 'wp_insert_comment', array( $this, 'woo_pr_rate_on_product' ), 10, 2 );
			add_action( 'wp_set_comment_status', array( $this, 'woo_pr_rate_status_change' ), 10, 2 );

			//Action to added total calculated points to checkout
			add_action( 'woocommerce_review_order_after_cart_contents', array( $this, 'woo_pr_review_order_after_cart_contents' ) );

			// Process for points expiration
			add_action( 'woo_pr_expiration_points', array( $this, 'woo_pr_expiration_points_process' ) );

			// Show next expiration points notice
			add_action( 'woocommerce_account_dashboard', array( $this, 'woo_pr_expiration_points_notice' ) );

			add_action( 'wp_ajax_woo_pr_change_points_meesage_variation_wise',array($this,'woo_pr_change_points_meesage_variation_wise') );
			add_action( 'wp_ajax_nopriv_woo_pr_change_points_meesage_variation_wise',array($this,'woo_pr_change_points_meesage_variation_wise') );
		}

		if( $redeem_user_exclude_role !== false ){

			add_action('woocommerce_cart_calculate_fees', array($this, 'woo_pr_redeem_points_add_remove_discount'));
			add_action('woocommerce_cart_updated', array($this, 'woo_pr_redeem_points_add_remove_prevent_coupon'));
		
			add_action('woocommerce_before_calculate_totals', array($this, 'woo_pr_redeem_points_add_remove_prevent_coupon'));

			// Add action to remove points from customer
			add_action('woocommerce_checkout_update_order_meta', array($this, 'woo_pr_woocommerce_checkout_process'), 15, 2 );

			// add earn points/redeem points message above cart / checkout
			add_action( 'woocommerce_before_cart', array( $this, 'woo_pr_redeem_point_markup' ), 16 );
			add_action( 'woocommerce_before_checkout_form', array( $this, 'woo_pr_redeem_point_markup' ), 6 );

			// Handles to not allowing discount on tax with inclusive tax
			add_filter('woocommerce_cart_totals_get_fees_from_cart_taxes', array($this, 'woo_pr_redeem_points_tax_calc'), 10, 2);
			add_filter( 'woocommerce_coupons_enabled', array($this,'woo_pr_prevent_coupon_code_point_redeemed') );
		}

		if( $redeem_user_exclude_role !== false || $earn_user_exclude_role !== false ){
			add_action( 'woocommerce_account_woo-pr-point-history_endpoint', array($this,'woo_pr_information_endpoint_content'));
			
			// add Points histry tab to woocommer my account page
			add_filter ( 'woocommerce_account_menu_items', array($this,'woo_pr_add_point_tab_myaccount_page'),10,1);    
			add_action( 'init', array($this,'woo_pr_add_my_account_endpoint'));
		}


		add_action( 'woocommerce_before_cart', array( $this, 'woo_pr_guest_cart_checkout_message_content' ), 17 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'woo_pr_guest_cart_checkout_message_content' ), 7 );
		
		//add shortcode to show all Points and Rewards buttons
		add_shortcode( 'woopr_points_history', array( $this, 'woo_pr_points_history' ) );

		// Added woocommerce template filter to override templates from plugin
		add_filter('woocommerce_locate_template', array( $this, 'woo_pr_woocommerce_locate_template' ), 10, 3 );


		//AJAX Call for paging for points log
		add_action( 'wp_ajax_woo_pr_next_page', array( $this->logs, 'woo_pr_user_log_list' ) );
		add_action( 'wp_ajax_nopriv_woo_pr_next_page', array( $this->logs, 'woo_pr_user_log_list' ) );

		
		add_action( 'expiration_mail_cron_callback',array($this,'expiration_mail_cron_callback'));

		add_action( 'admin_init', array($this,'woo_pr_expiration_mail_cron'));
		
		add_action('woocommerce_order_status_changed', array($this,'woo_pr_redeem_points_on_status_change'),10,4);

		// added filter for apply discount on cart total
		add_filter('woo_pr_add_tax_to_reedemed_points_label',array($this,'woo_pr_tax_show_full_points'),10,2);
		add_filter('woocommerce_cart_totals_get_fees_from_cart_taxes',array($this,'woo_pr_tax_re_calculate_full_discount_points'), 10 ,3);	
		add_filter('woocommerce_cart_totals_order_total_html',array($this,'woo_pr_update_tax_to_zero_cart_checkout'),99,1);
		add_filter('woocommerce_get_formatted_order_total',array($this,'woo_pr_update_tax_to_zero_after_placing_order'),999,4);
	  
	}
}