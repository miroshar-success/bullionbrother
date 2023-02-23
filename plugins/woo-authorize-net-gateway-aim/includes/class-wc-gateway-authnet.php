<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_Authnet class.
 *
 * @extends WC_Payment_Gateway_CC
 */
class WC_Gateway_Authnet extends WC_Payment_Gateway_CC {

	public $capture;
	public $statement_descriptor;
	public $login_id;
	public $transaction_key;
	public $client_key;
	public $testmode;
	public $logging;
	public $debugging;
	public $line_items;
	public $allowed_card_types;
	public $customer_receipt;
	public $free_api_method;

	const  ACCEPT_JS_URL_LIVE = 'https://js.authorize.net/v1/Accept.js';
	const  ACCEPT_JS_URL_TEST = 'https://jstest.authorize.net/v1/Accept.js';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                 = 'authnet';
		$this->method_title       = __( 'Authorize.Net', 'wc-authnet' );
		$this->method_description = sprintf( esc_html__( 'Live merchant accounts cannot be used in a sandbox environment, so to test the plugin, please make sure you are using a separate sandbox account. If you do not have a sandbox account, you can sign up for one from %shere%s.', 'wc-authnet' ), '<a href="https://developer.authorize.net/hello_world/sandbox.html" target="_blank">', '</a>' );
		$this->has_fields         = true;

		$this->method_description .= '<h3>' . __( 'Upgrade to Enterprise', 'wc-authnet' ) . '</h3>' . sprintf( esc_html__( 'Enterprise version is a full blown plugin that provides full support for processing subscriptions, pre-orders and payments via saved cards. The credit card information is saved in your Authorize.Net account and is reused to charge future orders, recurring payments or pre-orders at a later time. %sClick here%s to upgrade to Enterprise version or to know more about it.', 'wc-authnet' ), '<a href="' . wc_authnet_fs()->get_upgrade_url() . '" target="_blank">', '</a>' );
		$this->supports           = array( 'products', 'refunds' );

		// Load the form fields
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Get setting values.
		$this->title                = $this->get_option( 'title' );
		$this->description          = $this->get_option( 'description' );
		$this->enabled              = $this->get_option( 'enabled' );
		$this->testmode             = $this->get_option( 'testmode' ) === 'yes';
		$this->capture              = $this->get_option( 'capture', 'yes' ) === 'yes';
		$this->statement_descriptor = $this->get_option( 'statement_descriptor', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
		$this->login_id             = $this->get_option( 'login_id' );
		$this->transaction_key      = $this->get_option( 'transaction_key' );
		$this->client_key           = $this->get_option( 'client_key' );
		$this->logging              = $this->get_option( 'logging' ) === 'yes';
		$this->debugging            = $this->get_option( 'debugging' ) === 'yes';
		$this->allowed_card_types   = $this->get_option( 'allowed_card_types', array() );
		$this->customer_receipt     = $this->get_option( 'customer_receipt' ) === 'yes';
		$this->free_api_method      = $this->get_option( 'free_api_method' );

		if ( $this->testmode ) {
			$this->description .= "\n\n<strong>" . __( 'TEST MODE ENABLED', 'wc-authnet' ) . "</strong>\n";
			$this->description .= sprintf( __( 'In test mode, you can use the card number 4111111111111111 with any CVC and a valid expiration date or check the %sAuthorize.Net Testing Guide%s for more card numbers and generate various test scenarios before going live.', 'wc-authnet' ), '<a href="https://developer.authorize.net/hello_world/testing_guide/" target="_blank">', '</a>' );
		}

		if ( $this->client_key ) {
			$this->supports[] = 'tokenization';
		}

		WC_Authnet_API::set_login_id( $this->login_id );
		WC_Authnet_API::set_transaction_key( $this->transaction_key );
		WC_Authnet_API::set_testmode( $this->testmode );
		WC_Authnet_API::set_logging( $this->logging );
		WC_Authnet_API::set_debugging( $this->debugging );
		WC_Authnet_API::set_statement_descriptor( $this->statement_descriptor );

		// Hooks
		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
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
		$icon = '';
		if ( in_array( 'visa', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/visa.svg' ) . '" alt="Visa" width="32" />';
		}
		if ( in_array( 'mastercard', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/mastercard.svg' ) . '" alt="Mastercard" width="32" />';
		}
		if ( in_array( 'amex', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/amex.svg' ) . '" alt="Amex" width="32" />';
		}
		if ( in_array( 'discover', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/discover.svg' ) . '" alt="Discover" width="32" />';
		}
		if ( in_array( 'jcb', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/jcb.svg' ) . '" alt="JCB" width="32" />';
		}
		if ( in_array( 'diners-club', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/diners.svg' ) . '" alt="Diners Club" width="32" />';
		}

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
		if ( ! $this->login_id ) {
			echo '<div class="error"><p>' . sprintf( __( 'Gateway error: Please enter your API Login ID <a href="%s">here</a>', 'wc-authnet' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=authnet' ) ) . '</p></div>';

			return;

		} elseif ( ! $this->transaction_key ) {
			echo '<div class="error"><p>' . sprintf( __( 'Gateway error: Please enter your Transaction Key <a href="%s">here</a>', 'wc-authnet' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=authnet' ) ) . '</p></div>';

			return;
		}

		// Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
		if ( ! wc_checkout_is_https() ) {
			echo '<div class="notice notice-warning"><p>' . sprintf( __( 'Authorize.Net is enabled, but an SSL certificate is not detected. Your checkout may not be secure! Please ensure your server has a valid <a href="%1$s" target="_blank">SSL certificate</a>', 'wc-authnet' ), 'https://en.wikipedia.org/wiki/Transport_Layer_Security' ) . '</p></div>';
		}
	}

	/**
	 * Check if this gateway is enabled
	 */
	public function is_available() {

		if ( $this->enabled == "yes" ) {
			// Required fields check
			if ( ! $this->login_id || ! $this->transaction_key ) {
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
			'enabled'              => array(
				'title'       => __( 'Enable/Disable', 'wc-authnet' ),
				'label'       => __( 'Enable Authorize.Net', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'title'                => array(
				'title'       => __( 'Title', 'wc-authnet' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wc-authnet' ),
				'default'     => __( 'Credit card (Authorize.Net)', 'wc-authnet' ),
			),
			'description'          => array(
				'title'       => __( 'Description', 'wc-authnet' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'wc-authnet' ),
				'default'     => sprintf( __( 'Pay with your credit card via %s.', 'wc-authnet' ), $this->method_title ),
			),
			'testmode'             => array(
				'title'       => __( 'Sandbox mode', 'wc-authnet' ),
				'label'       => __( 'Enable Sandbox Mode', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => sprintf( esc_html__( 'Check the Authorize.Net testing guide %shere%s. This will display "sandbox mode" warning on checkout.', 'wc-authnet' ), '<a href="https://developer.authorize.net/hello_world/testing_guide/" target="_blank">', '</a>' ),
				'default'     => 'yes',
			),
			'login_id'             => array(
				'title'       => __( 'API Login ID', 'wc-authnet' ),
				'type'        => 'text',
				'description' => esc_html__( 'Get it from Account → Security Settings → API Credentials & Keys page in your Authorize.Net account.', 'wc-authnet' ),
				'default'     => '',
			),
			'transaction_key'      => array(
				'title'       => __( 'Transaction Key', 'wc-authnet' ),
				'type'        => 'password',
				'description' => esc_html__( 'Get it from Account → Security Settings → API Credentials & Keys page in your Authorize.Net account. For security reasons, you cannot view your Transaction Key, but you will be able to generate a new one.', 'wc-authnet' ),
				'default'     => '',
			),
			'client_key'           => array(
				'title'       => __( 'Public Client Key', 'wc-authnet' ),
				'type'        => 'text',
				'description' => esc_html__( 'Get it from Account → Security Settings → Manage Public Client Key page in your Authorize.Net account.', 'wc-authnet' ),
				'default'     => '',
			),
			'statement_descriptor' => array(
				'title'       => __( 'Statement Descriptor', 'wc-authnet' ),
				'type'        => 'text',
				'description' => __( 'Extra information about a charge. This will appear in your order description. Defaults to site name.', 'wc-authnet' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'capture'              => array(
				'title'       => __( 'Capture', 'wc-authnet' ),
				'label'       => __( 'Capture charge immediately', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => __( 'Whether or not to immediately capture the charge. When unchecked, the charge issues an authorization and will need to be captured later.', 'wc-authnet' ),
				'default'     => 'yes',
			),
			'logging'              => array(
				'title'       => __( 'Logging', 'wc-authnet' ),
				'label'       => __( 'Log debug messages', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => sprintf( __( 'Save debug messages to the WooCommerce System Status log file <code>%s</code>.', 'wc-authnet' ), WC_Log_Handler_File::get_log_file_path( 'woocommerce-gateway-authnet' ) ),
				'default'     => 'no',
			),
			'debugging'            => array(
				'title'       => __( 'Gateway Debug', 'wc-authnet' ),
				'label'       => __( 'Log gateway requests and response to the WooCommerce System Status log.', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => __( '<strong>CAUTION! Enabling this option will write gateway requests possibly including card numbers and CVV to the logs.</strong> Do not turn this on unless you have a problem processing credit cards. You must only ever enable it temporarily for troubleshooting or to send requested information to the plugin author. It must be disabled straight away after the issues are resolved and the plugin logs should be deleted.', 'wc-authnet' ) . ' ' . sprintf( __( '<a href="%s">Click here</a> to check and delete the full log file.', 'wc-authnet' ), admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . WC_Log_Handler_File::get_log_file_name( 'woocommerce-gateway-authnet' ) ) ),
				'default'     => 'no',
			),
			'allowed_card_types'   => array(
				'title'       => __( 'Allowed Card types', 'wc-authnet' ),
				'class'       => 'wc-enhanced-select',
				'type'        => 'multiselect',
				'description' => __( 'Select the card types you want to allow payments from.', 'wc-authnet' ),
				'default'     => array( 'visa', 'mastercard', 'discover', 'amex' ),
				'options'     => array(
					'visa'        => __( 'Visa', 'wc-authnet' ),
					'mastercard'  => __( 'MasterCard', 'wc-authnet' ),
					'discover'    => __( 'Discover', 'wc-authnet' ),
					'amex'        => __( 'American Express', 'wc-authnet' ),
					'jcb'         => __( 'JCB', 'wc-authnet' ),
					'diners-club' => __( 'Diners Club', 'wc-authnet' ),
				),
			),
			'customer_receipt'     => array(
				'title'       => __( 'Receipt', 'wc-authnet' ),
				'label'       => __( 'Send Gateway Receipt', 'wc-authnet' ),
				'type'        => 'checkbox',
				'description' => __( 'If enabled, the customer will be sent an email receipt from Authorize.Net.', 'wc-authnet' ),
				'default'     => 'no',
			),
			'free_api_method'      => array(
				'title'       => __( 'Processing API', 'wc-authnet' ),
				'type'        => 'select',
				'description' => __( 'Always use "Authorize.Net API" unless you are using the AIM emulator.', 'wc-authnet' ),
				'options'     => array(
					'api' => __( 'Authorize.Net API', 'wc-authnet' ),
					'aim' => __( 'Legacy AIM', 'wc-authnet' ),
				),
				'default'     => 'aim',
				'css'         => 'min-width:100px;',
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
	 * Payment_scripts function.
	 *
	 * Outputs scripts used for authnet payment
	 *
	 * @since 3.1.0
	 * @version 4.0.0
	 */
	public function payment_scripts() {
		if ( ! $this->client_key || ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) && ! is_add_payment_method_page() ) {
			return;
		}

		$js_url = ( $this->testmode ? self::ACCEPT_JS_URL_TEST : self::ACCEPT_JS_URL_LIVE );
		wp_enqueue_script( 'authnet-accept', $js_url, '', null, true );
		wp_enqueue_script( 'woocommerce_authnet', plugins_url( 'assets/js/authnet.js', WC_AUTHNET_MAIN_FILE ), array( 'jquery-payment', 'authnet-accept' ), WC_AUTHNET_VERSION, true );

		$authnet_params = array(
			'login_id'              => $this->login_id,
			'client_key'            => $this->client_key,
			'allowed_card_types'    => $this->allowed_card_types,
			'i18n_terms'            => __( 'Please accept the terms and conditions first', 'wc-authnet' ),
			'i18n_required_fields'  => __( 'Please fill in required checkout fields first', 'wc-authnet' ),
			'no_cvv_error'          => __( 'CVC code is required.', 'wc-authnet' ),
			'card_disallowed_error' => __( 'Card Type Not Accepted.', 'wc-authnet' ),
		);

		// If we're on the pay page we need to pass authnet.js the address of the order.
		if ( isset( $_GET['pay_for_order'] ) && 'true' === $_GET['pay_for_order'] ) {
			$order_id                             = wc_get_order_id_by_order_key( urldecode( $_GET['key'] ) );
			$order                                = wc_get_order( $order_id );
			$authnet_params['billing_first_name'] = $order->get_billing_first_name();
			$authnet_params['billing_last_name']  = $order->get_billing_last_name();
		}

		wp_localize_script( 'woocommerce_authnet', 'wc_authnet_params', apply_filters( 'wc_authnet_params', $authnet_params ) );
	}

	/**
	 * Generate the request for the payment.
	 *
	 * @param WC_Order $order
	 * @param object $source
	 *
	 * @return array()
	 */
	protected function generate_payment_request_args( $order, $source, $recurring_description = 'Credit Card' ) {

		$source_args = array();

		if ( $source->source['source_type'] == 'card' ) {
			// Create the payment data for a credit card
			$expiry      = explode( '/', wc_clean( $source->source['expiry'] ) );
			$expiry[1]   = '20' . substr( trim( $expiry[1] ), -2 );
			$source_args = array(
				'creditCard' => array(
					'cardNumber'     => wc_clean( $source->source['card_number'] ),
					'expirationDate' => $expiry[1] . '-' . trim( $expiry[0] ),
					'cardCode'       => wc_clean( $source->source['cvc'] ),
				),
			);
		} elseif ( $source->source['source_type'] == 'nonce' ) {
			// Create the payment data for a payment nonce
			$source_args = array(
				'opaqueData' => array(
					'dataDescriptor' => wc_clean( $source->source['descriptor'] ),
					'dataValue'      => wc_clean( $source->source['nonce'] ),
				),
			);
		}

		// Set the customer's Bill To address
		$billing_address = array(
			'firstName'   => substr( $order->get_billing_first_name(), 0, 50 ),
			'lastName'    => substr( $order->get_billing_last_name(), 0, 50 ),
			'company'     => substr( $order->get_billing_company(), 0, 50 ),
			'address'     => substr( trim( $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() ), 0, 60 ),
			'city'        => substr( $order->get_billing_city(), 0, 40 ),
			'state'       => substr( $order->get_billing_state(), 0, 40 ),
			'zip'         => substr( $order->get_billing_postcode(), 0, 20 ),
			'country'     => substr( $order->get_billing_country(), 0, 60 ),
			'phoneNumber' => substr( $order->get_billing_phone(), 0, 25 ),
		);

		// Set the customer's Ship To address
		$shipping_address = array(
			'firstName' => substr( $order->get_shipping_first_name(), 0, 50 ),
			'lastName'  => substr( $order->get_shipping_last_name(), 0, 50 ),
			'company'   => substr( $order->get_shipping_company(), 0, 50 ),
			'address'   => substr( trim( $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2() ), 0, 60 ),
			'city'      => substr( $order->get_shipping_city(), 0, 40 ),
			'state'     => substr( $order->get_shipping_state(), 0, 40 ),
			'zip'       => substr( $order->get_shipping_postcode(), 0, 20 ),
			'country'   => substr( $order->get_shipping_country(), 0, 60 ),
		);

		// Add values for transaction settings
		$transaction_settings = array(
			array(
				'settingName'  => 'duplicateWindow',
				'settingValue' => '60',
			),
			array(
				'settingName'  => 'emailCustomer',
				'settingValue' => $this->customer_receipt,
			)
		);

		// Add basic custom fields
		$custom_fields = array(
			array(
				'name'  => 'Customer Name',
				'value' => sanitize_text_field( $order->get_billing_first_name() ) . ' ' . sanitize_text_field( $order->get_billing_last_name() ),
			),
			array(
				'name'  => 'Customer Email',
				'value' => sanitize_email( $order->get_billing_email() ),
			)
		);

		// Add values for line items
		$line_items = array();
		foreach ( $order->get_items() as $id => $item ) {
			$product = $item->get_product();
			if ( ! is_object( $product ) ) {
				continue;
			}
			$line_item['itemId']      = ( $product->get_sku() ? substr( $product->get_sku(), 0, 31 ) : substr( $product->get_id(), 0, 31 ) );
			$line_item['name']        = substr( $this->format_line_item( $item->get_name() ), 0, 31 );
			$line_item['quantity']    = $item->get_quantity();
			$line_item['unitPrice']   = ( isset( $item['recurring_line_total'] ) ? $item['recurring_line_total'] : $order->get_item_total( $item ) );
			$line_item['taxable']     = $product->is_taxable();
			$line_items['lineItem'][] = $line_item;
			if ( count( $line_items ) >= 30 ) {
				break;
			}
		}

		$customer_id = ( is_user_logged_in() ? get_current_user_id() : 'guest_' . time() );
		$description = trim( sprintf( __( '%1$s - Order %2$s %3$s', 'wc-authnet' ), $this->statement_descriptor, $order->get_order_number(), $recurring_description ) );

		// Create complete request args (strictly follow ordering of request arguments)

		$request_args = array(
			'refId'              => $order->get_id(),
			'transactionRequest' => array(
				'transactionType'     => ( $this->capture ? 'authCaptureTransaction' : 'authOnlyTransaction' ),
				'amount'              => wc_clean( $order->get_total()),
				'currencyCode'        => $this->get_payment_currency( $order->get_id() ),
				'payment'             => $source_args,
				'order'               => array(
					'invoiceNumber' => $order->get_order_number(),
					'description'   => substr( $description, 0, 255 ),
				),
				'lineItems'           => $line_items,
				'tax'                 => array(
					'amount' => $order->get_total_tax(),
				),
				'shipping'            => array(
					'amount' => $order->get_shipping_total(),
				),
				'customer'            => array(
					'id'    => $customer_id,
					'email' => substr( $order->get_billing_email(), 0, 255 ),
				),
				'billTo'              => $billing_address,
				'shipTo'              => $shipping_address,
				'customerIP'          => WC_Geolocation::get_ip_address(),
				'transactionSettings' => array(
					'setting' => $transaction_settings,
				),
				'userFields'          => array(
					'userField' => $custom_fields,
				),
			),
		);

		return apply_filters( 'wc_authnet_generate_payment_request_args', $request_args, $order, $source );
	}

	/**
	 * Get payment source. This can be a new token or existing card.
	 *
	 * @param string $user_id
	 * @param bool $force_customer Should we force customer creation.
	 *
	 * @return object
	 * @throws Exception When card was not added or for and invalid card.
	 */
	protected function get_source( $user_id, $force_customer = false ) {

		$authnet_source   = false;
		$token_id         = false;
		$authnet_customer = false;

		WC_Authnet_API::log( "Info: Getting payment source with new card details." );

		// New CC info was entered and we have a new token to process
		if ( isset( $_POST['authnet_nonce'] ) && isset( $_POST['authnet_data_descriptor'] ) ) {
			$authnet_source_args = array(
				'nonce'      => wc_clean( $_POST['authnet_nonce'] ),
				'descriptor' => wc_clean( $_POST['authnet_data_descriptor'] ),
			);
			$new_source          = $authnet_source_args['source_type'] = 'nonce';
		} elseif ( isset( $_POST['authnet-card-number'] ) && ! empty( $_POST['authnet-card-number'] ) && isset( $_POST['authnet-card-expiry'] ) && isset( $_POST['authnet-card-cvc'] ) ) {
			$authnet_source_args = array(
				'card_number' => str_replace( ' ', '', wc_clean( $_POST['authnet-card-number'] ) ),
				'expiry'      => wc_clean( $_POST['authnet-card-expiry'] ),
				'cvc'         => wc_clean( $_POST['authnet-card-cvc'] ),
			);

			// Check for card type supported or not
			if ( ! in_array( $this->get_card_type( $authnet_source_args['card_number'], 'pattern', 'name' ), $this->allowed_card_types ) ) {
				WC_Authnet_API::log( sprintf( __( 'Card type being used is not one of supported types in plugin settings: %s', 'wc-authnet' ), $this->get_card_type( $authnet_source_args['card_number'], 'pattern', 'name' ) ) );
				WC_Authnet_API::log( "Error: Card Type Not Accepted." );
				throw new Exception( __( 'Card Type Not Accepted.', 'wc-authnet' ) );
			}
			if ( empty( $authnet_source_args['cvc'] ) ) {
				WC_Authnet_API::log( "Error: CVC code is empty." );
				throw new Exception( __( 'CVC code is required.', 'wc-authnet' ) );
			}

			$new_source = $authnet_source_args['source_type'] = 'card';
		}

		if ( isset( $new_source ) ) {
			// Not saving token, so don't define customer either.
			$authnet_source = $authnet_source_args;
		}

		return (object) array(
			'token_id' => $token_id,
			'customer' => ( $authnet_customer ? $authnet_customer->get_id() : false ),
			'source'   => $authnet_source,
		);
	}

	/**
	 * Process the payment
	 *
	 * @param int $order_id Reference.
	 * @param bool $retry Should we retry on fail.
	 * @param bool $force_customer Force user creation.
	 *
	 * @return array|void
	 * @throws Exception If payment will not be accepted.
	 *
	 */
	public function process_payment( $order_id, $retry = true, $force_customer = false ) {

		$order    = wc_get_order( $order_id );
		$response = false;

		try {

			WC_Authnet_API::log( "Info: Begin processing payment for order {$order_id} for the amount of {$order->get_total()}" );

			$source = $this->get_source( get_current_user_id(), $force_customer );

			if ( empty( $source->source ) && empty( $source->customer ) ) {
				WC_Authnet_API::log( "Error: Payment source could not be found." );
				$error_msg = __( 'Please enter your card details to make a payment.', 'wc-authnet' );
				//$error_msg .= ' ' . __( 'Developers: Please make sure that you are including jQuery and there are no JavaScript errors on the page.', 'wc-authnet' );
				throw new Exception( $error_msg );
			}

			// Handle payment.
			if ( $order->get_total() > 0 ) {

				// Make the request.
				$payment_args = $this->generate_payment_request_args( $order, $source );

				$response = WC_Authnet_API::execute( 'createTransactionRequest', $payment_args );

				if ( is_wp_error( $response ) ) {
					throw new Exception( $response->get_error_message() );
				}
				// Process valid response.
				$this->process_response( $response['transactionResponse'], $order );

			} else {
				$order->payment_complete();
			}

			// Remove cart.
			WC()->cart->empty_cart();
			do_action( 'wc_gateway_authnet_process_payment', $response, $order );

			// Return thank you page redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);

		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
			WC_Authnet_API::log( sprintf( __( 'Error: %s', 'wc-authnet' ), $e->getMessage() ) );

			if ( is_wp_error( $response ) ) {
				$message = sprintf( __( 'Authorize.Net failure reason: %s', 'wc-authnet' ), $response->get_error_code() . ' - ' . $response->get_error_message() );
				if ( $trx_response = $response->get_error_data() ) {
					$message = sprintf( __( "Authorize.Net failure reason: %s \n\nAVS Response: %s \n\nCVV2 Response: %s", 'wc-authnet' ), $response->get_error_code() . ' - ' . $response->get_error_message(), self::get_avs_message( $trx_response['avsResultCode'] ), self::get_cvv_message( $trx_response['cvvResultCode'] ) );
				}
				$order->add_order_note( $message );
			}

			do_action( 'wc_gateway_authnet_process_payment_error', $e, $order );

			if ( ! isset( $_GET['change_payment_method'] ) ) {
				$order->update_status( 'failed' );
			} else {
				$order->set_payment_method( $order->get_meta( '_old_payment_method' ) );
				$order->set_payment_method_title( $order->get_meta( '_old_payment_method_title' ) );
				$order->add_order_note( sprintf( __( 'Payment method changed back to "%1$s" since the new card was not accepted.', 'wc-authnet' ), $order->get_meta( '_old_payment_method_title' ) ) );
				$order->save();
			}

			return array(
				'result'   => 'fail',
				'redirect' => '',
			);
		}
	}

	/**
	 * Store extra meta data for an order from an Authorize.Net Response.
	 */
	public function process_response( $response, $order ) {
		$order_id = $order->get_id();

		// Store charge data
		$order->update_meta_data( '_authnet_charge_id', $response['transId'] );
		$order->update_meta_data( '_authnet_cc_last4', substr( $response['accountNumber'], -4 ) );
		$order->update_meta_data( '_authnet_authorization_code', $response['authCode'] );

		$order->set_transaction_id( $response['transId'] );

		if ( $this->capture && $response['responseCode'] != 4 ) {
			$order->update_meta_data( '_authnet_charge_captured', 'yes' );
			$order->update_meta_data( 'Authorize.Net Payment ID', $response['transId'] );
			$order->payment_complete( $response['transId'] );

			$complete_message = sprintf( __( "Authorize.Net charge complete (Charge ID: %s) \n\nAVS Response: %s \n\nCVV2 Response: %s", 'wc-authnet' ), $response['transId'], self::get_avs_message( $response['avsResultCode'] ), self::get_cvv_message( $response['cvvResultCode'] ) );
			$order->add_order_note( $complete_message );
			WC_Authnet_API::log( 'Success: ' . $complete_message );

		} else {
			$order->update_meta_data( '_authnet_charge_captured', 'no' );

			if ( $response['responseCode'] == 4 ) {
				$order->update_meta_data( '_authnet_fds_hold', 'yes' );
			}

			if ( $order->has_status( array( 'pending', 'failed' ) ) ) {
				wc_reduce_stock_levels( $order_id );
			}

			$authorized_message = sprintf( __( "Authorize.Net charge authorized (Charge ID: %s). Process order to take payment, or cancel to remove the pre-authorization.\n\nAVS Response: %s \n\nCVV2 Response: %s \n\n", 'wc-authnet' ), $response['transId'], self::get_avs_message( $response['avsResultCode'] ), self::get_cvv_message( $response['cvvResultCode'] ) );
			$order->update_status( 'on-hold', $authorized_message . "\n" );
			WC_Authnet_API::log( "Success: " . $authorized_message );
		}

		$order->save();

		do_action( 'wc_gateway_authnet_process_response', $response, $order );

		return $response;
	}

	/**
	 * Refund a charge
	 *
	 * @param int $order_id
	 * @param float $amount
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {

		$order = wc_get_order( $order_id );

		if ( ! $order || ! $order->get_transaction_id() || $amount <= 0 ) {
			return false;
		}

		$charge_captured = $order->get_meta( '_authnet_charge_captured' );

		if ( $amount == $order->get_total() ) {
			$order->update_meta_data( '_authnet_charge_captured', 'no' );
			$order->save();

			$instance = new WC_Authnet();
			$instance->cancel_payment( $order_id );

			$order       = wc_get_order( $order_id );
			$void_status = $order->get_meta( '_authnet_void' );
		} else {
			$void_status = 'failed';
		}

		if ( $order->get_meta( '_authnet_charge_captured' ) != $charge_captured ) {
			$order->update_meta_data( '_authnet_charge_captured', $charge_captured );
			$order->save();
		}

		if ( $void_status == 'failed' ) {

			WC_Authnet_API::log( "Info: Beginning refund for order {$order_id} for the amount of {$amount}" );

			// Create complete request args
			$args = array(
				'refId'              => $order->get_id(),
				'transactionRequest' => array(
					'transactionType' => 'refundTransaction',
					'amount'          => $amount,
					'currencyCode'    => $this->get_payment_currency( $order_id ),
					'payment'         => array(
						'creditCard' => array(
							'cardNumber'     => $order->get_meta( '_authnet_cc_last4' ),
							'expirationDate' => 'XXXX',
						),
					),
					'refTransId'      => $order->get_transaction_id(),
				),
			);
			$args = apply_filters( 'wc_authnet_refund_request_args', $args, $order );

			$response = WC_Authnet_API::execute( 'createTransactionRequest', $args );

			if ( is_wp_error( $response ) ) {
				$order->add_order_note( __( 'Gateway Error: ', 'wc-authnet' ) . $response->get_error_message() );

				return false;
			} else {
				$trx_response   = $response['transactionResponse'];

				$refund_message = sprintf( __( 'Refunded %s - Refund ID: %s - Reason: %s', 'wc-authnet' ), $amount, $trx_response['transId'], $reason );
				$order->add_order_note( $refund_message );
				$order->save();

				WC_Authnet_API::log( "Success: " . html_entity_decode( strip_tags( $refund_message ) ) );
			}

		}

		return true;
	}

	/**
	 * Taken from https://gist.github.com/jaywilliams/119517
	 *
	 * @param $string
	 *
	 * @return string
	 */
	protected function format_line_item( $string ) {

		// Replace Single Curly Quotes
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 152 );
		$replace[] = "'";
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 153 );
		$replace[] = "'";

		// Replace Smart Double Curly Quotes
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 156 );
		$replace[] = '"';
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 157 );
		$replace[] = '"';

		// Replace En Dash
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 147 );
		$replace[] = '--';

		// Replace Em Dash
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 148 );
		$replace[] = '---';

		// Replace Bullet
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 162 );
		$replace[] = '*';

		// Replace Middle Dot
		$search[]  = chr( 194 ) . chr( 183 );
		$replace[] = '*';

		// Replace Ellipsis with three consecutive dots
		$search[]  = chr( 226 ) . chr( 128 ) . chr( 166 );
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

	function get_card_type( $value, $field = 'pattern', $return = 'label' ) {
		$card_types = array(
			array(
				'label'        => 'American Express',
				'name'         => 'amex',
				'pattern'      => '/^3[47]/',
				'valid_length' => '[15]',
			),
			array(
				'label'        => 'JCB',
				'name'         => 'jcb',
				'pattern'      => '/^35(2[89]|[3-8][0-9])/',
				'valid_length' => '[16]',
			),
			array(
				'label'        => 'Discover',
				'name'         => 'discover',
				'pattern'      => '/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/',
				'valid_length' => '[16]',
			),
			array(
				'label'        => 'MasterCard',
				'name'         => 'mastercard',
				'pattern'      => '/^5[1-5]/',
				'valid_length' => '[16]',
			),
			array(
				'label'        => 'Visa',
				'name'         => 'visa',
				'pattern'      => '/^4/',
				'valid_length' => '[16]',
			),
			array(
				'label'        => 'Maestro',
				'name'         => 'maestro',
				'pattern'      => '/^(5018|5020|5038|6304|6759|676[1-3])/',
				'valid_length' => '[12, 13, 14, 15, 16, 17, 18, 19]',
			),
			array(
				'label'        => 'Diners Club',
				'name'         => 'diners-club',
				'pattern'      => '/^3[0689]/',
				'valid_length' => '[14]',
			)
		);

		foreach ( $card_types as $type ) {
			$compare = $type[ $field ];
			if ( $field == 'pattern' && preg_match( $compare, $value, $match ) || $compare == $value ) {
				return $type[ $return ];
			}
		}

	}

	/**
	 * Returns the order_id if on the checkout pay page
	 *
	 * @return int order identifier
	 * @since 3.3
	 */
	public function get_checkout_pay_page_order_id() {
		global $wp;

		return ( isset( $wp->query_vars['order-pay'] ) ? absint( $wp->query_vars['order-pay'] ) : 0 );
	}

	function get_payment_currency( $order_id = false ) {
		$currency = get_woocommerce_currency();
		$order_id = ( ! $order_id ? $this->get_checkout_pay_page_order_id() : $order_id );

		// Gets currency for the current order, that is about to be paid for
		if ( $order_id ) {
			$order    = wc_get_order( $order_id );
			$currency = $order->get_currency();
		}

		return $currency;
	}

	/**
	 * get_avs_message function.
	 *
	 * @access public
	 *
	 * @param string $code
	 *
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
			'S' => __( 'AVS Not Supported by Card Issuing Bank', 'wc-authnet' ),
			'U' => __( 'Address Information For This Cardholder Is Unavailable', 'wc-authnet' ),
			'W' => __( 'Street Address: No Match -- All 9 Digits of ZIP: Match', 'wc-authnet' ),
			'X' => __( 'Street Address: Match -- All 9 Digits of ZIP: Match', 'wc-authnet' ),
			'Y' => __( 'Street Address: Match - First 5 Digits of ZIP: Match', 'wc-authnet' ),
			'Z' => __( 'Street Address: No Match - First 5 Digits of ZIP: Match', 'wc-authnet' ),
		);

		if ( array_key_exists( $code, $avs_messages ) ) {
			return $code . ' - ' . $avs_messages[ $code ];
		} else {
			return $code;
		}
	}

	/**
	 * get_cvv_message function.
	 *
	 * @access public
	 *
	 * @param string $code
	 *
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
			return $code . ' - ' . $cvv_messages[ $code ];
		} else {
			return $code;
		}
	}

}