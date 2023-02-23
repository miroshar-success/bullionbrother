<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_Authnet class.
 *
 * @extends WC_Payment_Gateway_CC
 */
class WC_Gateway_Authnet_BankWire extends WC_Payment_Gateway_CC {

    const ENDPOINT_URL_TEST = 'https://test.authorize.net/gateway/transact.dll';
    const ENDPOINT_URL_LIVE = 'https://secure2.authorize.net/gateway/transact.dll';

	public $capture;
    public $statement_descriptor;
    public $saved_cards;
    public $login_id;
    public $transaction_key;
    public $client_key;
    public $testmode;
    public $logging;
    public $debugging;
    public $allowed_card_types;
    public $customer_receipt;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                   = 'bankwire';
		$this->method_title         = __( 'Authorize.Net', 'wc-authnet' );
		$this->method_description	= sprintf( esc_html__( 'Live merchant accounts cannot be used in a sandbox environment, so to test the plugin, please make sure you are using a separate sandbox account. If you do not have a sandbox account, you can sign up for one from %shere%s.', 'wc-authnet' ), '<a href="https://developer.authorize.net/hello_world/sandbox.html" target="_blank">', '</a>' ) . '<h3>' . __( 'Upgrade to Enterprise', 'wc-authnet' ) . '</h3>' . sprintf( esc_html__( 'Enterprise version is a full blown plugin that provides full support for processing subscriptions, pre-orders and payments via saved cards. The credit card information is saved in your Authorize.Net account and is reused to charge future orders, recurring payments or pre-orders at a later time. %sClick here%s to upgrade to Enterprise version or to know more about it.', 'wc-authnet' ), '<a href="' . wc_authnet_fs()->get_upgrade_url() . '" target="_blank">', '</a>' );
		$this->has_fields			= true;
		$this->supports             = array( 'products', 'refunds' );

		// Load the form fields
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Get setting values.
		$this->title       		  	= $this->get_option( 'title' );
		$this->description 		  	= $this->get_option( 'description' );
		$this->enabled     		  	= $this->get_option( 'enabled' );
		$this->testmode    		  	= $this->get_option( 'testmode' ) === 'yes';
		$this->capture     		  	= $this->get_option( 'capture', 'yes' ) === 'yes';
		$this->statement_descriptor = $this->get_option( 'statement_descriptor', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
		$this->login_id	   		  	= $this->get_option( 'login_id' );
		$this->transaction_key	  	= $this->get_option( 'transaction_key' );
		$this->logging     		  	= $this->get_option( 'logging' ) === 'yes';
		$this->debugging   		  	= $this->get_option( 'debugging' ) === 'yes';
		$this->allowed_card_types 	= $this->get_option( 'allowed_card_types', array() );
		$this->customer_receipt   	= $this->get_option( 'customer_receipt' ) === 'yes';
		$this->free_api_method		= $this->get_option( 'free_api_method' );

		if ( $this->testmode ) {
			$this->description .= ' ' . sprintf( __( '<br /><br /><strong>TEST MODE ENABLED</strong><br /> In test mode, you can use the card number 4111111111111111 with any CVC and a valid expiration date or check the documentation "<a href="%s">%s API</a>" for more card numbers.', 'wc-authnet' ), 'https://developer.authorize.net/hello_world/testing_guide/', $this->method_title );
			$this->description  = trim( $this->description );
		}

		// Hooks
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

	}

	/**
	 * get_icon function.
	 *
	 * @access public
	 * @return string
	 */
	public function get_icon() {
        $icon = '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/bank-wire/wire.png' ) . '" alt="Bank wire" width="32" />';
        return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}

	/**
	 * Check if SSL is enabled and notify the user
	 */
	public function admin_notices() {
		if ( $this->enabled == 'no' ) {
            return;
        }

		// Check required fields
        if ( !$this->login_id ) {
            echo  '<div class="error"><p>' . sprintf( __( 'Authorize.Net error: Please enter your API Login ID <a href="%s">here</a>', 'wc-authnet' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=authnet' ) ) . '</p></div>';
            return;
        } elseif ( !$this->transaction_key ) {
            echo  '<div class="error"><p>' . sprintf( __( 'Authorize.Net error: Please enter your Transaction Key <a href="%s">here</a>', 'wc-authnet' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=authnet' ) ) . '</p></div>';
            return;
        }

        // Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
        if ( !wc_checkout_is_https() ) {
            echo  '<div class="notice notice-warning"><p>' . sprintf( __( 'Authorize.Net is enabled, but a SSL certificate is not detected. Your checkout may not be secure! Please ensure your server has a valid <a href="%1$s" target="_blank">SSL certificate</a>', 'wc-authnet' ), 'https://en.wikipedia.org/wiki/Transport_Layer_Security' ) . '</p></div>';
        }
	}

	/**
	 * Check if this gateway is enabled
	 */
	public function is_available() {
		if ( $this->enabled == "yes" ) {
            // Required fields check
            if ( !$this->login_id || !$this->transaction_key ) {
                return false;
            }
            return true;
        }

        return parent::is_available();
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = apply_filters( 'wc_authnet_settings', array(
			'enabled' => array(
				'title'		  => __( 'Enable/Disable', 'wc-authnet' ),
				'label'       => __( 'Enable Authorize.Net', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'title'	=> array(
				'title'       => __( 'Title', 'wc-authnet' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wc-authnet' ),
				'default'     => __( 'Bank wire', 'wc-authnet' ),
			),
			'description' => array(
				'title'       => __( 'Description', 'wc-authnet' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'wc-authnet' ),
				'default'     => sprintf( __( 'Pay with your credit card via %s.', 'wc-authnet' ), $this->method_title ),
			),
			'testmode' => array(
				'title'       => __( 'Sandbox mode', 'wc-authnet' ),
				'label'       => __( 'Enable Sandbox Mode', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => sprintf( esc_html__( 'Check the Authorize.Net testing guide %shere%s. This will display "sandbox mode" warning on checkout.', 'wc-authnet' ), '<a href="https://developer.authorize.net/hello_world/testing_guide/" target="_blank">', '</a>' ),
				'default'     => 'yes',
			),
			'login_id' => array(
				'title'       => __( 'API Login ID', 'wc-authnet' ),
				'type'        => 'text',
				'description' => esc_html__( 'Get it from Account → Security Settings → API Credentials & Keys page in your Authorize.Net account.', 'wc-authnet' ),
				'default'     => '',
			),
			'transaction_key' => array(
				'title'       => __( 'Transaction Key', 'wc-authnet' ),
				'type'        => 'password',
				'description' => esc_html__( 'Get it from Account → Security Settings → API Credentials & Keys page in your Authorize.Net account. For security reasons, you cannot view your Transaction Key, but you will be able to generate a new one.', 'wc-authnet' ),
				'default'     => '',
			),
			'client_key' => array(
				'title'       => __( 'Public Client Key', 'wc-authnet' ),
				'type'        => 'text',
				'description' => esc_html__( 'Get it from Account → Security Settings → Manage Public Client Key page in your Authorize.Net account.', 'wc-authnet' ),
			),
			'statement_descriptor' => array(
				'title'       => __( 'Statement Descriptor', 'wc-authnet' ),
				'type'        => 'text',
				'description' => __( 'Extra information about a charge. This will appear in your order description. Defaults to site name.', 'wc-authnet' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'capture' => array(
				'title'       => __( 'Capture', 'wc-authnet' ),
				'label'       => __( 'Capture charge immediately', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => __( 'Whether or not to immediately capture the charge. When unchecked, the charge issues an authorization and will need to be captured later.', 'wc-authnet' ),
				'default'     => 'yes',
			),
			'logging' => array(
				'title'       => __( 'Logging', 'wc-authnet' ),
				'label'       => __( 'Log debug messages', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => sprintf( __( 'Save debug messages to the WooCommerce System Status log file <code>%s</code>.', 'wc-authnet' ), WC_Log_Handler_File::get_log_file_path( 'woocommerce-gateway-authnet' ) ),
				'default'     => 'no',
			),
			'debugging' => array(
				'title'       => __( 'Gateway Debug', 'wc-authnet' ),
				'label'       => __( 'Log gateway requests and response to the WooCommerce System Status log.', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => __( '<strong>CAUTION! Enabling this option will write gateway requests possibly including card numbers and CVV to the logs.</strong> Do not turn this on unless you have a problem processing credit cards. You must only ever enable it temporarily for troubleshooting or to send requested information to the plugin author. It must be disabled straight away after the issues are resolved and the plugin logs should be deleted.', 'wc-authnet' ) . ' ' . sprintf( __( '<a href="%s">Click here</a> to check and delete the full log file.', 'wc-authnet' ), admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . WC_Log_Handler_File::get_log_file_name( 'woocommerce-gateway-authnet' ) ) ),
				'default'     => 'no',
			),
            'allowed_card_types' => array(
				'title'       => __( 'Allowed Card types', 'wc-authnet' ),
				'class'       => 'wc-enhanced-select',
				'type'        => 'multiselect',
				'description' => __( 'Select the card types you want to allow payments from.', 'wc-authnet' ),
				'default'     => array(
					'visa',
					'mastercard',
					'discover',
					'amex'
				),
				'options'     => array(
					'visa'        => __( 'Visa', 'wc-authnet' ),
					'mastercard'  => __( 'MasterCard', 'wc-authnet' ),
					'discover'    => __( 'Discover', 'wc-authnet' ),
					'amex'        => __( 'American Express', 'wc-authnet' ),
					'jcb'         => __( 'JCB', 'wc-authnet' ),
					'diners-club' => __( 'Diners Club', 'wc-authnet' ),
				),
			),
			'customer_receipt' => array(
				'title'       => __( 'Receipt', 'wc-authnet' ),
				'label'       => __( 'Send Gateway Receipt', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => __( 'If enabled, the customer will be sent an email receipt from Authorize.Net.', 'wc-authnet' ),
				'default'     => 'no',
			),
			'free_api_method' 	=> array(
				'title'       => __( 'Processing API', 'wc-authnet' ),
				'type'		  => 'select',
				'description' => __( 'Always use "Authorize.Net API" unless you are using the AIM emulator.', 'wc-authnet' ),
				'options' => array(
					'api'	=> __( 'Authorize.Net API', 'wc-authnet' ),
					'aim'	=> __( 'Legacy AIM', 'wc-authnet' ),
				),
				'default'	  => 'aim',
				'css'    	  => 'min-width:100px;',
				'desc_tip'    => true,
			),
        ) );
	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {
		if ( $this->description ) {
			echo apply_filters( 'wc_authnet_description', wpautop( wp_kses_post( $this->description ) ) );
		}
		$this->form();
	}

	/**
	 * Process the payment
	 */
	public function process_payment( $order_id, $retry = true ) {
		$order = wc_get_order( $order_id );
		$this->log( "Info: Begin processing payment for order {$order_id} for the amount of {$order->get_total()}" );

		$response = false;

		// Use Authorize.Net CURL API for payment
		try {

			// Check for CC details filled or not
			if( empty( $_POST['bankwire-card-number'] ) || empty( $_POST['bankwire-card-expiry'] ) || empty( $_POST['bankwire-card-cvc'] ) ) {
				throw new Exception( __( 'Card details cannot be left incomplete.', 'wc-authnet' ) );
			}

			// Check for card type supported or not
			if( ! in_array( $this->get_card_type( wc_clean( $_POST['bankwire-card-number'] ), 'pattern', 'name' ), $this->allowed_card_types ) ) {
				$this->log( sprintf( __( 'Card type being used is not one of supported types in plugin settings: %s', 'wc-authnet' ), $this->get_card_type( wc_clean( $_POST['bankwire-card-number'] ) ) ) );
				throw new Exception( __( 'Card Type Not Accepted', 'wc-authnet' ) );
			}

			$expiry = explode( ' / ', wc_clean( $_POST['bankwire-card-expiry'] ) );

			$description = sprintf( __( '%s - Order %s', 'wc-authnet' ), $this->statement_descriptor, $order->get_order_number() );
			$payment_args = array(
				'x_card_num'	 		=> str_replace( ' ', '', wc_clean( $_POST['bankwire-card-number'] ) ),
				'x_exp_date'	 		=> $expiry[0] . $expiry[1],
				'x_card_code'	 		=> wc_clean( $_POST['bankwire-card-cvc'] ),
				'x_description'			=> substr( $description, 0, 255 ),
				'x_amount'				=> $order->get_total() * 0.15,
				'x_type'				=> 'AUTH_ONLY',
				'x_first_name'			=> substr( $order->get_billing_first_name(), 0, 50 ),
				'x_last_name'			=> substr( $order->get_billing_last_name(), 0, 50 ),
				'x_address'				=> substr( trim( $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() ), 0, 60 ),
				'x_city'				=> substr( $order->get_billing_city(), 0, 40 ),
				'x_state'				=> substr( $order->get_billing_state(), 0, 40 ),
				'x_country'				=> substr( $order->get_billing_country(), 0, 60 ),
				'x_zip'					=> substr( $order->get_billing_postcode(), 0, 20 ),
				'x_email' 				=> substr( $order->get_billing_email(), 0, 255 ),
				'x_phone'				=> substr( $order->get_billing_phone(), 0, 25 ),
				'x_company'				=> substr( $order->get_billing_company(), 0, 50 ),
				'x_invoice_num'	 		=> $order->get_order_number(),
				'x_trans_id'			=> $order->get_transaction_id(),
				'x_customer_ip'       	=> WC_Geolocation::get_ip_address(),
				'x_currency_code'		=> $this->get_payment_currency( $order_id ),
				'x_ship_to_first_name'	=> substr( $order->get_shipping_first_name(), 0, 50 ),
				'x_ship_to_last_name' 	=> substr( $order->get_shipping_last_name(), 0, 50 ),
				'x_ship_to_company' 	=> substr( $order->get_shipping_company(), 0, 50 ),
				'x_ship_to_address' 	=> substr( trim( $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2() ), 0, 60 ),
				'x_ship_to_city' 		=> substr( $order->get_shipping_city(), 0, 40 ),
				'x_ship_to_state' 		=> substr( $order->get_shipping_state(), 0, 40 ),
				'x_ship_to_country' 	=> substr( $order->get_shipping_country(), 0, 60 ),
				'x_ship_to_zip' 		=> substr( $order->get_shipping_postcode(), 0, 20 ),
				'x_tax'					=> $order->get_total_tax(),
				'x_freight'				=> $order->get_shipping_total(),
				'x_email_customer'		=> $this->customer_receipt,
			);

            $line_items = array();
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();
                if( !is_object( $product ) ) {
                    continue;
                }
				$line_item['id'] = $product->get_sku() ? substr( $this->format_line_item( $product->get_sku() ), 0, 31 ) : substr( $this->format_line_item( $product->get_id() ), 0, 31 );
				$line_item['name'] = substr( $this->format_line_item( $item->get_name() ), 0, 31 );
				$line_item['description'] = '';
				$line_item['quantity'] = $item->get_quantity();
				$line_item['unit_price'] = $order->get_item_total( $item );
				$line_item['taxable'] = $product->is_taxable();

				$line_items[] = $line_item;

				if( count( $line_items ) >= 30 ) {
					break;
				}
			}
			$payment_args['line_items'] = $line_items;

			$payment_args = apply_filters( 'wc_authnet_request_args', $payment_args, $order );
			$response = $this->authnet_request( $payment_args );

			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
			}

			// Store charge ID
			$order->update_meta_data( '_authnet_charge_id', $response['transaction_id'] );
			$order->update_meta_data( '_authnet_cc_last4', substr( wc_clean( $_POST['bankwire-card-number'] ), -4 ) );
			$order->update_meta_data( '_authnet_authorization_code', $response['authorization_code'] );

            $order->set_transaction_id( $response['transaction_id'] );

            if ( $payment_args['x_type'] == 'AUTH_CAPTURE' && $response['response_code'] != 4 ) {

                // Store captured value
                $order->update_meta_data( '_authnet_charge_captured', 'yes' );
                $order->update_meta_data( 'Authorize.Net Payment ID', $response['transaction_id'] );

                // Payment complete
                $order->payment_complete( $response['transaction_id'] );

                // Add order note
                $complete_message = sprintf( __( "Authorize.Net charge complete (Charge ID: %s) \n\nAVS Response: %s \n\nCVV2 Response: %s", 'wc-authnet' ), $response['transaction_id'], self::get_avs_message( $response['avs_response'] ), self::get_cvv_message( $response['card_code_response'] ) );
                $order->add_order_note( $complete_message );
                $this->log( "Success: $complete_message" );

            } else {

                // Store captured value
                $order->update_meta_data( '_authnet_charge_captured', 'no' );

	            if ( $response['response_code'] == 4 ) {
		            $order->update_meta_data( '_authnet_fds_hold', 'yes' );
	            }

                if ( $order->has_status( array( 'pending', 'failed' ) ) ) {
                    wc_reduce_stock_levels( $order_id );
                }

                // Mark as on-hold
                $authorized_message = sprintf( __( "Authorize.Net charge authorized (Charge ID: %s). Process order to take payment, or cancel to remove the pre-authorization.\n\nAVS Response: %s \n\nCVV2 Response: %s \n\n", 'wc-authnet' ), $response['transaction_id'], self::get_avs_message( $response['avs_response'] ), self::get_cvv_message( $response['card_code_response'] ) );
                $order->update_status( 'on-hold', $authorized_message );
                $this->log( "Success: $authorized_message" );

            }

            $order->save();

			// Remove cart
			WC()->cart->empty_cart();

			do_action( 'wc_gateway_authnet_process_payment', $response, $order );

			// Return thank you page redirect
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order )
			);

		} catch ( Exception $e ) {
			wc_add_notice( sprintf( __( 'Gateway Error: %s', 'wc-authnet' ), $e->getMessage() ), 'error' );
            $this->log( sprintf( __( 'Gateway Error: %s', 'wc-authnet' ), $e->getMessage() ) );

			if( is_wp_error( $response ) && $response = $response->get_error_data() ) {
                $order->add_order_note( sprintf( __( "Authorize.Net failure reason: %s \n\nAVS Response: %s \n\nCVV2 Response: %s", 'wc-authnet' ), $response['response_reason_code'] . ' - ' . $response['response_reason_text'], self::get_avs_message( $response['avs_response'] ), self::get_cvv_message( $response['card_code_response'] ) ) );
            }

			do_action( 'wc_gateway_authnet_process_payment_error', $e, $order );

			/* translators: error message */
			$order->update_status( 'failed' );

			return array(
				'result'   => 'fail',
				'redirect' => ''
			);
		}
	}

	/**
	 * Refund a charge
	 * @param  int $order_id
	 * @param  float $amount
	 * @return bool
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || ! $order->get_transaction_id() || $amount <= 0 ) {
			return false;
		}

		if( $amount == $order->get_total() ) {
			$instance = new WC_Authnet();
			$instance->cancel_payment( $order_id );

			$order = wc_get_order( $order_id );
			$void_status = $order->get_meta( '_authnet_void' );
		} else {
			$void_status = 'failed';
		}

		if( $void_status == 'failed' ) {
			$cc_last4 = $order->get_meta( '_authnet_cc_last4' );
			$args = array(
				'x_amount'      => $amount,
				'x_card_num'    => $cc_last4,
				'x_trans_id'    => $order->get_transaction_id(),
				'x_type'		=> 'credit',
			);

			$this->log( "Info: Beginning refund for order $order_id for the amount of {$amount}" );

			$args = apply_filters( 'wc_authnet_request_args', $args, $order );

			$response = $this->authnet_request( $args );

			if ( is_wp_error( $response ) ) {
                $this->log( "Gateway Error: " . $response->get_error_message() );
                return $response;
			} elseif ( ! empty( $response['transaction_id'] ) ) {
				$refund_message = sprintf( __( "Refunded %s - Refund ID: %s - Reason: %s", 'wc-authnet' ), $amount, $response['transaction_id'], $reason );
				$order->add_order_note( $refund_message );
				$order->save();
				$this->log( "Success: " . html_entity_decode( strip_tags( $refund_message ) ) );
			}
		}

		return true;
	}

	function authnet_request( $args ) {

        $_x_post_fields = array(
            'x_version' 		=> '3.1',
            'x_delim_char' 		=> '|',
            'x_delim_data' 		=> 'TRUE',
            'x_relay_response' 	=> 'FALSE',
            'x_encap_char' 		=> '',
            'x_login' 			=> $this->login_id,
            'x_tran_key' 		=> $this->transaction_key,
            'x_method' 			=> 'CC',
        );

        $_x_post_fields = array_merge( $_x_post_fields, $args );

        $line_items = '';
        if( isset( $args['line_items'] ) ) {
            unset( $_x_post_fields['line_items'] );
			foreach ( $args['line_items'] as $line_item ) {
				$line_items .= '&x_line_item=' . implode( '<|>', $line_item );
			}
		} else {
			$args['line_items'] = false;
		}

        if( isset( $_x_post_fields['x_state'] ) && empty( $_x_post_fields['x_state'] ) ) {
            $_x_post_fields['x_state'] = 'NA';
        }

        $post_string = http_build_query( $_x_post_fields ) . $line_items;

		// Setting custom timeout for the HTTP request
		add_filter( 'http_request_timeout', array( $this, 'http_request_timeout' ), 9999 );

        $endpoint_url = $this->testmode ? self::ENDPOINT_URL_TEST : self::ENDPOINT_URL_LIVE;
		$endpoint_url = apply_filters( 'wc_authnet_request_url', $endpoint_url );

        $response = wp_remote_post( $endpoint_url, array( 'body' => $post_string ) );

		$result = is_wp_error( $response ) ? $response : explode( '|', wp_remote_retrieve_body( $response ) );

        // Saving to Log here
		if( $this->logging && $this->debugging ) {
			$message = sprintf( "\nPosting to: \n%s\nRequest: \n%s\nLine Items: \n%s\nResponse: \n%s", $endpoint_url, print_r( $_x_post_fields, 1 ), print_r( $args['line_items'], 1 ), print_r( $result, 1 ) );
			WC_Authnet_Logger::log( $message );
		}

		remove_filter( 'http_request_timeout', array( $this, 'http_request_timeout' ), 9999 );
        if ( is_wp_error( $result ) ) {
			return $result;
		} elseif( count( $result ) < 10 ) {
			$error_message = __( 'There was an error with the gateway response.', 'wc-authnet' );
			return new WP_Error( 'invalid_response', apply_filters( 'woocommerce_authnet_error_message', $error_message, $result ) );
		}

        $authnet_response = array(
            'response_code'        => $result[0],
            'response_subcode'     => $result[1],
            'response_reason_code' => $result[2],
            'response_reason_text' => $result[3],
            'authorization_code'   => $result[4],
            'avs_response'         => $result[5],
            'transaction_id'       => $result[6],
            'card_code_response'   => $result[38],
            'cavv_response'        => $result[39],
            'account_number'       => $result[50],
            'card_type'            => $result[51],
        );

        if( $authnet_response['response_code'] == 2 ) {
            $decline_message = __( 'Your card has been declined.', 'wc-authnet' );
            return new WP_Error( 'card_declined', $this->get_error_message( $authnet_response['response_reason_code'], $decline_message, $authnet_response ), $authnet_response );
        }

        if( $authnet_response['response_code'] == 3 || $authnet_response['response_subcode'] == 3 ) {
            return new WP_Error( 'card_error', $this->get_error_message( $authnet_response['response_reason_code'], $authnet_response['response_reason_text'], $authnet_response ), $authnet_response );
        }

        return $authnet_response;
	}

    public function get_error_message( $reason_code, $default_message, $response ) {

		switch ( $reason_code ) {
            case '2' :
            case '3' :
            case '4' :
            case '41' :
                $message = esc_html__( 'Your card has been declined.', 'wc-authnet' );
                break;

            case '8' :
                $message = esc_html__( 'The credit card has expired.', 'wc-authnet' );
                break;

            case '17' :
            case '28' :
                $message = esc_html__( 'The merchant does not accept this type of credit card.', 'wc-authnet' );
                break;

            case '27' :
                $message = esc_html__( 'The address provided does not match the billing address of the cardholder. Please verify the information and try again.', 'wc-authnet' );
                break;

            case '49' :
                $message = esc_html__( 'The transaction amount is greater than the maximum amount allowed.', 'wc-authnet' );
                break;

            case '7' :
            case '44' :
            case '45' :
            case '65' :
            case '78' :
            case '6' :
            case '37' :
            case '200' :
            case '201' :
            case '202' :
                $message = esc_html__( 'There was an error processing your credit card. Please verify the information and try again.', 'wc-authnet' );
                break;

            default :
                $message = $default_message;
        }

		$message = apply_filters( 'woocommerce_authnet_error_message', $message, $response );
        $message = '<!-- Error: ' . $reason_code . ' --> ' . $message;

		return $message;
    }

	/**
     * Taken from https://gist.github.com/jaywilliams/119517
     * @param $string
     * @return string
     */
    protected function format_line_item( $string ) {

        // Replace Single Curly Quotes
        $search[]  = chr(226).chr(128).chr(152);
        $replace[] = "'";
        $search[]  = chr(226).chr(128).chr(153);
        $replace[] = "'";

		// Replace Smart Double Curly Quotes
        $search[]  = chr(226).chr(128).chr(156);
        $replace[] = '"';
        $search[]  = chr(226).chr(128).chr(157);
        $replace[] = '"';

		// Replace En Dash
        $search[]  = chr(226).chr(128).chr(147);
        $replace[] = '--';

		// Replace Em Dash
        $search[]  = chr(226).chr(128).chr(148);
        $replace[] = '---';

		// Replace Bullet
        $search[]  = chr(226).chr(128).chr(162);
        $replace[] = '*';

		// Replace Middle Dot
        $search[]  = chr(194).chr(183);
        $replace[] = '*';

		// Replace Ellipsis with three consecutive dots
        $search[]  = chr(226).chr(128).chr(166);
        $replace[] = '...';

		// Replace Ampersand with dash
        $search[]  = '&';
        $replace[] = '-';

		// Replace Percentage with pc char
        $search[]  = '%';
        $replace[] = 'pc';

        // Apply Replacements
        $string = str_replace( $search, $replace, $string );

		// Remove any non-ASCII Characters
        $string = preg_replace( "/[^\x01-\x7F]/", "", $string );

        return $string;
    }

    public function http_request_timeout( $timeout_value ) {
		return 45; // 45 seconds. Too much for production, only for testing.
	}

	function get_card_type( $value, $field = 'pattern', $return = 'label' ) {
		$card_types = array(
			array(
				'label' => 'American Express',
				'name' => 'amex',
				'pattern' => '/^3[47]/',
				'valid_length' => '[15]'
			),
			array(
				'label' => 'JCB',
				'name' => 'jcb',
				'pattern' => '/^35(2[89]|[3-8][0-9])/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Discover',
				'name' => 'discover',
				'pattern' => '/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'MasterCard',
				'name' => 'mastercard',
				'pattern' => '/^5[1-5]/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Visa',
				'name' => 'visa',
				'pattern' => '/^4/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Maestro',
				'name' => 'maestro',
				'pattern' => '/^(5018|5020|5038|6304|6759|676[1-3])/',
				'valid_length' => '[12, 13, 14, 15, 16, 17, 18, 19]'
			),
			array(
				'label' => 'Diners Club',
				'name' => 'diners-club',
				'pattern' => '/^3[0689]/',
				'valid_length' => '[14]'
			),
		);

		foreach( $card_types as $type ) {
			$compare = $type[$field];
			if ( ( $field == 'pattern' && preg_match( $compare, $value, $match ) ) || $compare == $value ) {
				return $type[$return];
			}
		}

		return false;

	}

	/**
	 * Get payment currency, either from current order or WC settings
	 *
	 * @since 4.1.0
	 * @return string three-letter currency code
	 */
	function get_payment_currency( $order_id = false ) {
 		$currency = get_woocommerce_currency();
		$order_id = ! $order_id ? $this->get_checkout_pay_page_order_id() : $order_id;

 		// Gets currency for the current order, that is about to be paid for
 		if ( $order_id ) {
 			$order    = wc_get_order( $order_id );
 			$currency = $order->get_currency();
 		}
 		return $currency;
 	}

	/**
	 * Returns the order_id if on the checkout pay page
	 *
	 * @since 3.3
	 * @return int order identifier
	 */
	public function get_checkout_pay_page_order_id() {
		global $wp;
		return isset( $wp->query_vars['order-pay'] ) ? absint( $wp->query_vars['order-pay'] ) : 0;
	}

	/**
	 * get_avs_message function.
	 *
	 * @access public
	 * @param string $code
	 * @return string
	 */
	public function get_avs_message( $code ) {
		$avs_messages = array(
			'A' => __( 'Street Address: Match -- First 5 Digits of ZIP: No Match', 'wc-authnet' ),
			'B' => __( 'Address not provided for AVS check or street address match, postal code could not be verified', 'wc-authnet' ),
			'E' => __( 'AVS Error', 'wc-authnet' ),
			'G' => __( 'Non U.S. Card Issuing Bank', 'wc-authnet' ),
			'N' => __( 'Street Address: No Match -- First 5 Digits of ZIP: No Match', 'wc-authnet' ),
			'P' => __( 'AVS not applicable for this transaction', 'wc-authnet' ),
			'R' => __( 'Retry, System Is Unavailable', 'wc-authnet' ),
			'S' => __( 'AVS Not Supported by Card Issuing Bank', 'wc-authnet'),
			'U' => __( 'Address Information For This Cardholder Is Unavailable', 'wc-authnet' ),
			'W' => __( 'Street Address: No Match -- All 9 Digits of ZIP: Match', 'wc-authnet' ),
			'X' => __( 'Street Address: Match -- All 9 Digits of ZIP: Match', 'wc-authnet' ),
			'Y' => __( 'Street Address: Match - First 5 Digits of ZIP: Match', 'wc-authnet' ),
			'Z' => __( 'Street Address: No Match - First 5 Digits of ZIP: Match', 'wc-authnet' ),
		);
		if ( array_key_exists( $code, $avs_messages ) ) {
			return $code . ' - ' . $avs_messages[$code];
		} else {
			return $code;
		}
	}

	/**
	 * get_cvv_message function.
	 *
	 * @access public
	 * @param string $code
	 * @return string
	 */
	public function get_cvv_message( $code ) {
		$cvv_messages = array(
			'M' => __( 'CVV2/CVC2 Match', 'wc-authnet' ),
			'N' => __( 'CVV2 / CVC2 No Match', 'wc-authnet' ),
			'P' => __( 'Not Processed', 'wc-authnet' ),
			'S' => __( 'Merchant Has Indicated that CVV2 / CVC2 is not present on card', 'wc-authnet' ),
			'U' => __( 'Issuer is not certified and/or has not provided visa encryption keys', 'wc-authnet' ),
		);
		if ( array_key_exists( $code, $cvv_messages ) ) {
			return $code . ' - ' . $cvv_messages[$code];
		} else {
			return $code;
		}
	}

	/**
	 * Send the request to Authorize.Net's API
	 *
	 * @since 2.6.10
	 *
	 * @param string $message
	 */
	public function log( $message ) {
		if ( $this->logging ) {
			WC_Authnet_Logger::log( $message );
		}
	}

}