<?php
/*
Plugin Name: WooCommerce Authorize.Net Gateway
Plugin URI: https://pledgedplugins.com/products/authorize-net-payment-gateway-woocommerce/
Description: A payment gateway for Authorize.Net. An Authorize.Net account and a server with cURL, SSL support, and a valid SSL certificate is required (for security reasons) for this gateway to function. Requires WC 3.3+
Version: 5.2.4
Author: Pledged Plugins
Author URI: https://pledgedplugins.com
Text Domain: wc-authnet
Domain Path: /languages
WC requires at least: 3.3
WC tested up to: 7.3

	Copyright: © Pledged Plugins.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WC_AUTHNET_VERSION', '5.2.4' );
define( 'WC_AUTHNET_MIN_PHP_VER', '5.6.0' );
define( 'WC_AUTHNET_MIN_WC_VER', '3.3' );
define( 'WC_AUTHNET_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_AUTHNET_MAIN_FILE', __FILE__ );

if ( function_exists( 'wc_authnet_fs' ) ) {
    wc_authnet_fs()->set_basename( false, __FILE__ );
    return;
}

if ( ! function_exists( 'wc_authnet_fs' ) ) {

	// Create a helper function for easy SDK access.
    function wc_authnet_fs() {
        global $wc_authnet_fs;

        if ( ! isset( $wc_authnet_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';

            $wc_authnet_fs = fs_dynamic_init( array(
                'id'             => '3348',
                'slug'           => 'woo-authorize-net-gateway-aim',
                'premium_slug'   => 'woo-authorize-net-gateway-enterprise',
                'type'           => 'plugin',
                'public_key'     => 'pk_bbbcfbaa9049689829ae3f0c2021c',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
					'slug'		=> 'authnet',
					'support' 	=> false,
					'parent'	=> array(
						'slug' 	=> 'woocommerce',
					),
				),
                'is_live'        => true,
            ) );
        }

        return $wc_authnet_fs;
    }

    // Init Freemius.
    wc_authnet_fs();

    // Signal that SDK was initiated.
    do_action( 'wc_authnet_fs_loaded' );
}

/**
 * Main Authorize.Net class which sets the gateway up for us
 */
class WC_Authnet {

	/**
     * @var WC_Authnet Singleton The reference the *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return WC_Authnet Singleton The *Singleton* instance.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Flag to indicate whether or not we need to load code for / support subscriptions.
     *
     * @var bool
     */
    private $subscription_support_enabled = false;

    /**
     * Flag to indicate whether or not we need to load support for pre-orders.
     *
     * @since 3.0.3
     *
     * @var bool
     */
    private $pre_order_enabled = false;

	/**
     * Notices (array)
     * @var array
     */
    public $notices = array();

    /**
     * Constructor
     */
    public function __construct() {
        // Actions
        add_action( 'admin_init', array( $this, 'check_environment' ) );
        add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        wc_authnet_fs()->add_filter( 'templates/checkout.php', array( $this, 'checkout_notice' ) );
        wc_authnet_fs()->add_filter( 'templates/pricing.php', array( $this, 'checkout_notice' ) );
    }

    public function submenu_setup() {
        add_submenu_page(
            'woocommerce',
            'WooCommerce Authorize.Net Gateway',
            'Authorize.Net',
            'manage_options',
            'authnet',
            array( $this, 'submenu_page' )
        );
    }

    public function submenu_page() {
        ?>
        <div class="wrap">
            <h1>WooCommerce Authorize.Net Gateway</h1>
            <h3><?php _e( 'About Authorize.Net', 'wc-authnet' ); ?></h3>
            <p><?php printf( __( 'As a leading payment gateway, %sAuthorize.Net%s is trusted by more than 430,000 merchants, handling more than 1 billion transactions and $149 billion in payments every year. Authorize.Net has been working with merchants and small businesses since 1996 and will offer you a credit card payment solution that works for your business and lets you focus on what you love best.', 'wc-authnet' ), '<a href="https://reseller.authorize.net/application/?resellerId=100678" target="_blank">', '</a>' ); ?></p>
			<h3><?php _e( 'About this WooCommerce Extension', 'wc-authnet' ); ?></h3>
			<p><?php _e( 'This extension enables you to use the Authorize.Net payment gateway to accept payments via credit cards directly on checkout on your WooCommerce powered WordPress e-commerce website without redirecting customers away to the gateway website.', 'wc-authnet' ); ?></p>
			<p>
				<a class="button" href="<?php echo $this->settings_url(); ?>">
					<?php _e( 'Settings', 'wc-authnet' ); ?>
				</a>
				<a class="button" href="<?php echo wc_authnet_fs()->contact_url(); ?>">
					<?php _e( 'Support', 'wc-authnet' ); ?>
				</a>
			</p>
			<h3><?php _e( 'License', 'wc-authnet' ); ?></h3>
			<p><?php printf( __( 'You are using our %1$sFREE PRO%2$s version of the extension. Here are the features you will get access to if you upgrade to the %1$sENTERPRISE%2$s version:', 'wc-authnet' ), '<strong>', '</strong>' ); ?></p>
			<ol>
				<li><strong><?php _e( 'Process Subscriptions:', 'wc-authnet' );	?></strong>
					<?php printf( __( 'Use with %1$sWooCommerce Subscriptions%2$s extension to %3$screate and manage products with recurring payments%4$s — payments that will give you residual revenue you can track and count on.', 'wc-authnet' ), '<a href="https://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">', '</a>', '<strong>', '</strong>' ); ?>
				</li>
				<li><strong><?php _e( 'Setup Pre-Orders:', 'wc-authnet' ); ?></strong>
					<?php printf( __( 'Use with %1$sWooCommerce Pre-Orders%2$s extension&nbsp;so customers can order products before they’re available by submitting their card details. The&nbsp;card is then&nbsp;automatically charged when the pre-order is available.', 'wc-authnet' ), '<a href="https://woocommerce.com/products/woocommerce-pre-orders/" target="_blank">', '</a>' ); ?>
				</li>
				<li><strong><?php _e( 'Pay via Saved Cards:', 'wc-authnet' ); ?></strong>
					<?php _e( 'Enable option to use saved card details on the gateway servers for quicker checkout. No sensitive card data is stored on the website!', 'wc-authnet' ); ?>
				</li>
			</ol>
			<?php
			$upgrade_label = __( 'Upgrade to Enterprise!', 'wc-authnet' );
			$show_account = false;
			?>
			<p><a class="button button-primary" href="<?php echo wc_authnet_fs()->get_upgrade_url(); ?>"><strong><?php echo $upgrade_label;	?></strong></a>
				<?php
				if ( $show_account ) { ?>
					<a class="button" href="<?php echo wc_authnet_fs()->get_account_url(); ?>"><?php _e( 'Account', 'wc-authnet' ); ?></a>
					<?php
				} ?>
			</p>
        </div>
		<?php
    }

    public function settings_url() {
        return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=authnet' );
    }

    /**
     * Add relevant links to plugins page
     * @param  array $links
     * @return array
     */
    public function plugin_action_links( $links ) {
        $plugin_links = array( '<a href="' . $this->settings_url() . '">' . __( 'Settings', 'wc-authnet' ) . '</a>', '<a href="' . wc_authnet_fs()->contact_url() . '">' . __( 'Support', 'wc-authnet' ) . '</a>', '<a href="' . admin_url( 'admin.php?page=authnet ' ) . '">' . __( 'About', 'wc-authnet' ) . '</a>' );
        return array_merge( $plugin_links, $links );
    }

    /**
     * Init localisations and files
     */
    public function init() {
        // Don't hook anything else in the plugin if we're in an incompatible environment
        if ( self::get_environment_warning() ) {
            return;
        }

        // Init the gateway itself
        $this->init_gateways();

        // required files
        require_once dirname( __FILE__ ) . '/includes/class-wc-gateway-authnet-logger.php';

        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ), 11 );

        add_action( 'admin_menu', array( $this, 'submenu_setup' ), 80 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'capture_payment' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'capture_payment' ) );
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'cancel_payment' ) );
		add_action( 'woocommerce_order_status_refunded', array( $this, 'cancel_payment' ) );
    }

    /**
     * Allow this class and other classes to add slug keyed notices (to avoid duplication)
     */
    public function add_admin_notice( $slug, $class, $message ) {
        $this->notices[$slug] = array(
            'class'   => $class,
            'message' => $message,
        );
    }

    /**
     * The backup sanity check, in case the plugin is activated in a weird way,
     * or the environment changes after activation. Also handles upgrade routines.
     */
    public function check_environment() {

        if ( !defined( 'IFRAME_REQUEST' ) && WC_AUTHNET_VERSION !== get_option( 'wc_authnet_version', '4.0.4' ) ) {
            $this->install();
            do_action( 'woocommerce_authnet_updated' );
        }

        $environment_warning = self::get_environment_warning();
        if ( $environment_warning && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
            $this->add_admin_notice( 'bad_environment', 'error', $environment_warning );
        }

        // Check if secret key present. Otherwise prompt, via notice, to go to setting.
		$options = get_option( 'woocommerce_authnet_settings' );
		$secret = isset( $options['transaction_key'] ) ? $options['transaction_key'] : '';

        if ( empty( $secret ) && !( isset( $_GET['page'], $_GET['section'] ) && 'wc-settings' === $_GET['page'] && 'authnet' === $_GET['section'] ) ) {
            $setting_link = $this->settings_url();
            $this->add_admin_notice( 'prompt_connect', 'notice notice-warning', sprintf( __( 'Authorize.Net is almost ready. To get started, <a href="%s">set your Authorize.Net account keys</a>.', 'wc-authnet' ), $setting_link ) );
        }

	    if ( class_exists( 'WC_Subscriptions' ) && function_exists( 'wcs_create_renewal_order' ) ) {
            $this->subscription_support_enabled = true;
        }
        if ( class_exists( 'WC_Pre_Orders_Order' ) ) {
            $this->pre_order_enabled = true;
        }

    }

    /**
     * Updates the plugin version in db
     *
     * @since 3.1.0
     * @version 3.1.0
     * @return bool
     */
    private static function _update_plugin_version() {
        delete_option( 'wc_authnet_version' );
        update_option( 'wc_authnet_version', WC_AUTHNET_VERSION );
        return true;
    }

    /**
     * Handles upgrade routines.
     *
     * @since 3.1.0
     * @version 3.1.0
     */
    public function install() {
        if ( !defined( 'WC_AUTHNET_INSTALLING' ) ) {
            define( 'WC_AUTHNET_INSTALLING', true );
        }
        $this->_update_plugin_version();
    }

    /**
     * Checks the environment for compatibility problems.  Returns a string with the first incompatibility
     * found or false if the environment has no problems.
     */
    static function get_environment_warning() {

        if ( version_compare( phpversion(), WC_AUTHNET_MIN_PHP_VER, '<' ) ) {
            $message = __( 'WooCommerce Authorize.Net - The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'wc-authnet' );
            return sprintf( $message, WC_AUTHNET_MIN_PHP_VER, phpversion() );
        }

        if ( !defined( 'WC_VERSION' ) ) {
            return __( 'WooCommerce Authorize.Net requires WooCommerce to be activated to work.', 'wc-authnet' );
        }

        if ( version_compare( WC_VERSION, WC_AUTHNET_MIN_WC_VER, '<' ) ) {
            $message = __( 'WooCommerce Authorize.Net - The minimum WooCommerce version required for this plugin is %1$s. You are running %2$s.', 'wc-authnet' );
            return sprintf( $message, WC_AUTHNET_MIN_WC_VER, WC_VERSION );
        }

        if ( !function_exists( 'curl_init' ) ) {
            return __( 'WooCommerce Authorize.Net - cURL is not installed.', 'wc-authnet' );
        }
        return false;
    }

    /**
     * Display any notices we've collected thus far (e.g. for connection, disconnection)
     */
    public function admin_notices() {
        foreach ( (array) $this->notices as $notice_key => $notice ) {
            echo  "<div class='" . esc_attr( $notice['class'] ) . "'><p>" ;
            echo  wp_kses( $notice['message'], array(
                'a' => array(
                'href' => array(),
            ),
            ) ) ;
            echo  '</p></div>' ;
        }
    }

    public function checkout_notice( $html ) {
        $notices = array();
        $notice_html = '';
        if( ! $this->subscription_support_enabled ) {
           $notices[] = __( 'To process subscription payments using Authorize.Net you will need the <a target="_blank" href="https://woocommerce.com/products/woocommerce-subscriptions/">WooCommerce Subscriptions</a> extension installed and running. Please continue with your purchase if you are not setting up subscriptions or will install WooCommerce Subscriptions later.', 'wc-authnet' );
        }
        if( !empty( $notices ) ) {
            $notice_html =  "<div class='notice notice-warning' style='margin:50px 0 -30px;'>" ;
            if( ! $this->subscription_support_enabled ) {
                $notice_html .= __( '<h3>WooCommerce Subscriptions Not Detected!</h3>', 'wc-authnet' );
            }
            foreach( $notices as $notice ) {
                $notice_html .= wpautop( $notice );
            }
            $notice_html .=  '</div>' ;
        }
        return $notice_html . $html;
    }

    /**
     * Initialize the gateway. Called very early - in the context of the plugins_loaded action
     *
     * @since 1.0.0
     */
    public function init_gateways() {
        if ( !class_exists( 'WC_Payment_Gateway' ) ) {
            return;
        }
        // Includes
        if ( is_admin() ) {
            require_once dirname( __FILE__ ) . '/includes/class-wc-authnet-privacy.php';
        }
        include_once dirname( __FILE__ ) . '/includes/class-wc-gateway-authnet.php';
        include_once dirname( __FILE__ ) . '/includes/class-wc-gateway-authnet-paperCheck.php';
        include_once dirname( __FILE__ ) . '/includes/class-wc-gateway-authnet-bankWire.php';
        load_plugin_textdomain( 'wc-authnet', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
        add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateways' ) );
    }

    /**
     * Add the gateways to WooCommerce
     *
     * @since 1.0.0
     */
    public function add_gateways( $methods ) {
        array_push($methods, 'WC_Gateway_Authnet', 'WC_Gateway_Authnet_BankWire', 'WC_Gateway_Authnet_PaperCheck');
        return $methods;
    }

	/**
	 * Capture payment when the order is changed from on-hold to complete or processing
	 *
	 * @param  int $order_id
	 */
	public function capture_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		$gateway = new WC_Gateway_Authnet();

		if ( $order->get_payment_method() == 'authnet' ) {
			$charge   = $order->get_meta( '_authnet_charge_id' );
			$captured = $order->get_meta( '_authnet_charge_captured' );

			if ( $charge && $captured == 'no' ) {

				$gateway->log( "Info: Beginning capture payment for order $order_id for the amount of {$order->get_total()}" );

				$order_total = $order->get_total();

				if ( 0 < $order->get_total_refunded() ) {
					$order_total = $order_total - $order->get_total_refunded();
				}

				$args = array(
					'x_amount'		=> $order_total,
					'x_trans_id'	=> $order->get_transaction_id(),
					'x_type' 		=> 'PRIOR_AUTH_CAPTURE',
				);
				$args = apply_filters( 'wc_authnet_request_args', $args, $order );

				$response = $gateway->authnet_request( $args );

				if ( is_wp_error( $response ) ) {
					$order->add_order_note( __( 'Unable to capture charge!', 'wc-authnet' ) . ' ' . $response->get_error_message() );
				} else {
					if( ! $gateway->capture && $order->get_meta( '_authnet_fds_hold' ) == 'yes' ) {
	                    $order->update_meta_data( '_authnet_fds_hold', 'no' );
						$order->save();
                        self::capture_payment( $order_id );
                        return;
                    }

					$complete_message = sprintf( __( "Authorize.Net charge complete (Charge ID: %s)", 'wc-authnet' ), $response['transaction_id'] );
					$order->add_order_note( $complete_message );
					$gateway->log( "Success: $complete_message" );

					$order->update_meta_data( '_authnet_charge_captured', 'yes' );
					$order->update_meta_data( 'Authorize.Net Payment ID', $response['transaction_id'] );

					$order->set_transaction_id( $response['transaction_id'] );
					$order->save();
				}

			}
		}
	}

	/**
	 * Cancel pre-auth on refund/cancellation
	 *
	 * @param  int $order_id
	 */
	public function cancel_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		$gateway = new WC_Gateway_Authnet();

		if ( $order->get_payment_method() == 'authnet' ) {
			$charge = $order->get_meta( '_authnet_charge_id' );
			$charge_captured = $order->get_meta( '_authnet_charge_captured' );

			if ( $charge ) {

				$gateway->log( "Info: Beginning cancel payment for order $order_id for the amount of {$order->get_total()}" );

				$args = array(
					'x_amount'		=> $order->get_total(),
					'x_trans_id'	=> $order->get_transaction_id(),
					'x_type' 		=> 'VOID',
				);
				$args = apply_filters( 'wc_authnet_request_args', $args, $order );

				$response = $gateway->authnet_request( $args );

				if ( is_wp_error( $response ) ) {
					$order->update_meta_data( '_authnet_void', 'failed' );
					if( $charge_captured == 'no' ) {
						$order->add_order_note( __( 'Unable to refund charge!', 'wc-authnet' ) . ' ' . $response->get_error_message() );
					}
				} else {
					$cancel_message = sprintf( __( "Authorize.Net charge refunded (Charge ID: %s)", 'wc-authnet' ), $response['transaction_id'] );
					$order->add_order_note( $cancel_message );
					$gateway->log( "Success: $cancel_message" );

					$order->delete_meta_data( '_authnet_charge_captured' );
					$order->delete_meta_data( '_authnet_charge_id' );
				}
				$order->save();

			}

		}
	}

}
$GLOBALS['wc_authnet'] = WC_Authnet::get_instance();