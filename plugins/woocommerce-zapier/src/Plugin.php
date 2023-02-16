<?php

namespace OM4\WooCommerceZapier;

use OM4\WooCommerceZapier\AdminUI;
use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Auth\AuthKeyRotator;
use OM4\WooCommerceZapier\Auth\SessionAuthenticate;
use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Installer;
use OM4\WooCommerceZapier\LegacyMigration\ExistingUserUpgrade;
use OM4\WooCommerceZapier\NewUser\NewUser;
use OM4\WooCommerceZapier\Plugin\Bookings\Plugin as BookingsPlugin;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Plugin as SubscriptionsPlugin;
use OM4\WooCommerceZapier\TaskHistory\Installer as TaskHistoryInstaller;
use OM4\WooCommerceZapier\TaskHistory\Listener\TriggerListener;
use OM4\WooCommerceZapier\Uninstall;
use OM4\WooCommerceZapier\Webhook\DeliveryFilter as WebhookDeliveryFilter;
use OM4\WooCommerceZapier\Webhook\Installer as WebhookInstaller;
use OM4\WooCommerceZapier\Webhook\Resources as WebhookResources;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;
use OM4\Zapier\Plugin as LegacyPlugin;

defined( 'ABSPATH' ) || exit;

/**
 * Main WooCommerce Zapier 2.0 Plugin class.
 * Bootstraps the plugin, with things starting during the `plugins_loaded` hook,
 * after all WordPress plugins have loaded and WooCommerce has initialised.
 *
 * @since 2.0.0
 */
class Plugin {

	/** The minimum WooCommerce version that this plugin supports. */
	const MINIMUM_SUPPORTED_WOOCOMMERCE_VERSION = '4.2.0';

	/** URL to the documentation for this plugin. */
	const DOCUMENTATION_URL = 'https://docs.om4.io/woocommerce-zapier/';

	/**
	 * ContainerService instance.
	 *
	 * @var ContainerService
	 */
	protected $container;

	/**
	 * Plugin constructor.
	 *
	 * @param ContainerService $container The Container.
	 */
	public function __construct( ContainerService $container ) {
		$this->container = $container;
	}

	/**
	 * Executed during the 'plugins_loaded' WordPress hook.
	 * - Checks that we're running the correct WooCommerce Version
	 * - Sets up various hooks
	 * - Load Supported Zapier Triggers
	 * - Loads the admin/dashboard interface if required
	 *
	 * @return void
	 */
	public function plugins_loaded() {

		load_plugin_textdomain( 'woocommerce-zapier', false, dirname( plugin_basename( WC_ZAPIER_PLUGIN_FILE ) ) . '/languages' );

		if ( ! $this->container->get( FeatureChecker::class )->class_exists( 'WooCommerce' ) ) {
			// WooCommerce plugin not installed.
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_woocommerce' ) );
			return;
		}

		if ( version_compare( WC_VERSION, self::MINIMUM_SUPPORTED_WOOCOMMERCE_VERSION, '<' ) ) {
			// WooCommerce plugin is older than our minimum required version.
			add_action( 'admin_notices', array( $this, 'admin_notice_unsupported_woocommerce_version' ) );
			return;
		}

		// Our minimum requirements are all met, let's get started!

		add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );
		add_action( 'woocommerce_init', array( $this, 'woocommerce_init' ) );
		add_action( 'init', array( $this, 'initialise' ), 9 );

		$this->legacy_mode_check();

		add_filter( 'plugin_action_links_' . plugin_basename( WC_ZAPIER_PLUGIN_FILE ), array( $this, 'action_links' ) );
		register_uninstall_hook( WC_ZAPIER_PLUGIN_FILE, array( Uninstall::class, 'run' ) );
	}

	/**
	 * Enable Legacy Zapier Feed Mode if required.
	 *
	 * @return void
	 */
	protected function legacy_mode_check() {
		$this->container->get( ExistingUserUpgrade::class )->initialise();
		if ( true === $this->container->get( Settings::class )->is_legacy_mode_enabled() ) {
			// Legacy Mode is enabled, so start up the Legacy Zapier Feeds functionality.
			LegacyPlugin::instance()->plugins_loaded();
		}
	}

	/**
	 * Initialise our functionality that needs to be initialised before WooCommerce loads and enqueues
	 * its active webhooks.
	 * Executed during the `before_woocommerce_init` hook to ensure it loads *before* WooCommerce
	 * loads all active webhooks (which occurs during `init` ie before `woocommerce_init`).
	 *
	 * @return void
	 */
	public function before_woocommerce_init() {
		$this->third_party_plugin_compatibility();

		$this->container->get( ResourceManager::class )->initialise();
		$this->container->get( WebhookDeliveryFilter::class )->initialise();
		$this->container->get( TriggerListener::class )->initialise();
		$this->container->get( WebhookResources::class )->initialise();
	}

	/**
	 * Initialise compatibility functionality for third party plugins (such as WooCommerce Subscriptions).
	 * Executed early during `before_woocommerce_init`.
	 *
	 * @return void
	 */
	protected function third_party_plugin_compatibility() {
		$this->container->get( BookingsPlugin::class )->initialise();
		$this->container->get( SubscriptionsPlugin::class )->initialise();
	}

	/**
	 * Initialise the plugin's functionality.
	 * Executed during the `woocommerce_init` hook.
	 *
	 * @return void
	 */
	public function woocommerce_init() {
		$this->container->get( API::class )->initialise();

	}

	/**
	 * Functionality that needs to be instantiated during `init`.
	 * Includes SessionAuthenticate (rewrite rule additions) because they need
	 * to be included before rewrite rules are flushed by WordPress on the
	 * Settings, Permalinks screen.
	 * Executed during the `init` hook.
	 *
	 * @return void
	 */
	public function initialise() {
		$this->container->get( SessionAuthenticate::class )->initialise();
		$this->container->get( AuthKeyRotator::class )->initialise();
		$this->container->get( NewUser::class )->initialise();
		$this->container->get( Installer::class )->initialise();
		$this->container->get( TaskHistoryInstaller::class )->initialise();
		$this->container->get( WebhookInstaller::class )->initialise();

		if ( is_admin() ) {
			$this->admin();
		}
	}

	/**
	 * Override the WooCommerce Zapier plugin's action links.
	 * Displayed beside the activate/deactivate links on WordPress' Plugins screen.
	 *
	 * @param array $links Array of plugin action links.
	 *
	 * @return array
	 */
	public function action_links( $links ) {

		$plugin_links = array(
			'<a href="' . $this->container->get( Settings::class )->get_settings_page_url() . '">' . __( 'Settings', 'woocommerce-zapier' ) . '</a>',
			'<a href="' . $this->container->get( AdminUI::class )->get_url() . '">' . __( 'Task History', 'woocommerce-zapier' ) . '</a>',
			'<a href="' . self::DOCUMENTATION_URL . '">' . __( 'Docs', 'woocommerce-zapier' ) . '</a>',
			'<a href="' . self::DOCUMENTATION_URL . 'support/">' . __( 'Support', 'woocommerce-zapier' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Administration/Dashboard functionality,
	 * executed if the user is in the Admin/Dashboard.
	 *
	 * @return void
	 */
	public function admin() {
		$this->container->get( AdminUI::class )->initialise();
		$this->container->get( Privacy::class );
	}

	/**
	 * Displays a message if WooCommerce not active.
	 *
	 * @return void
	 */
	public function admin_notice_missing_woocommerce() {
		$class   = 'notice notice-error';
		$message = __( 'WooCommerce Zapier requires WooCommerce. Please install and activate WooCommerce and try again.', 'woocommerce-zapier' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	/**
	 * Displays a message if the user isn't using a supported version of WooCommerce.
	 *
	 * @return void
	 */
	public function admin_notice_unsupported_woocommerce_version() {
		?>
		<div id="message" class="error">
			<p>
				<?php
				echo esc_html(
					sprintf(
						// Translators: %s: WooCommerce Version.
						__( 'WooCommerce Zapier requires WooCommerce version %s or later. Please update WooCommerce.', 'woocommerce-zapier' ),
						self::MINIMUM_SUPPORTED_WOOCOMMERCE_VERSION
					)
				);
				?>
			</p>
		</div>
		<?php
	}
}
