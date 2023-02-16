<?php

namespace OM4\WooCommerceZapier;

use OM4\WooCommerceZapier\Logger;
use OM4\Zapier\Plugin as LegacyPlugin;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Zapier Installer.
 * Responsible for installing the plugin when first activated,
 * and also responsible for managing database upgrades that occur
 * when users update the plugin to a new version.
 *
 * @since 2.0.0
 */
class Installer {

	/**
	 * Database version (used for install/upgrade tasks if required).
	 */
	const DB_VERSION = 14;

	/**
	 * Name of the wp_option record that stores the installed version number.
	 */
	const DB_VERSION_OPTION_NAME = 'wc_zapier_version';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Installer constructor.
	 *
	 * @param Logger $logger The Logger.
	 */
	public function __construct( Logger $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Instructs the installer to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action( 'admin_init', array( $this, 'install_or_update' ) );
		register_deactivation_hook( \WC_ZAPIER_PLUGIN_FILE, array( $this, 'deactivate' ) );
	}

	/**
	 * Get the currently installed database version number.
	 *
	 * @return int
	 */
	public function get_db_version() {
		return intval( get_option( self::DB_VERSION_OPTION_NAME ) );
	}

	/**
	 * Set the installed database version number to the specified version number.
	 *
	 * @param int $version Optional database version number, defaults to the newest version number if not specified.
	 *
	 * @return void
	 */
	public function set_db_version( $version = self::DB_VERSION ) {
		update_option( self::DB_VERSION_OPTION_NAME, $version );
		$this->logger->info( 'Database version set to %d.', array( $this->get_db_version() ) );
	}

	/**
	 * Whether or not the installed database version is up to date.
	 *
	 * @return bool
	 */
	public function is_up_to_date() {
		return self::DB_VERSION === $this->get_db_version();
	}

	/**
	 * Install the plugin if required, and perform database upgrade routines if required.
	 * Executed on every admin/dashboard page load (via the `admin_init` hook).
	 * As per http://core.trac.wordpress.org/ticket/14170 it's far better to use
	 * an upgrade routine fired on `admin_init`.
	 *
	 * @return void
	 */
	public function install_or_update() {
		$installed_version = $this->get_db_version();
		if ( self::DB_VERSION === $installed_version ) {
			// Database version already up-to-date -> nothing to do.
			return;
		}

		if ( 0 === $installed_version ) {
			// Initial plugin installation/activation, or a user has deactivated and reactivated the plugin.
			$installed_version = 3;
			$this->logger->info( 'New installation or reactivation. Database version set to 3.' );
		}

		$this->logger->info(
			'Database upgrade from version %d to %d starting...',
			array( $installed_version, self::DB_VERSION )
		);

		// Database upgrade routines for existing users.
		if ( 1 === $installed_version ) {
			// v1.1.0
			// Send sample data to all active feeds to ensure they have the latest field definitions.
			LegacyPlugin::resend_sample_data_async();
			$this->set_db_version( $installed_version++ );
		}
		if ( 2 === $installed_version ) {
			// v1.7.0
			// Automatically deactivate the Synchronous Send plugin (its no longer required).
			if ( function_exists( 'deactivate_plugins' ) ) {
				if ( is_plugin_active( 'woocommerce-zapier-synchronous/woocommerce-zapier-synchronous.php' ) ) {
					deactivate_plugins( 'woocommerce-zapier-synchronous/woocommerce-zapier-synchronous.php' );
				}
			}
			$this->set_db_version( $installed_version++ );
		}
		foreach ( range( 3, self::DB_VERSION - 1 ) as $start ) {
			if ( $start === $installed_version ) {
				$next = $start + 1;
				/**
				 * Perform Database Upgrade Tasks to migrate to the next DB version.
				 *
				 * @internal
				 * @since 2.0.0
				 */
				do_action( "wc_zapier_db_upgrade_v_{$start}_to_{$next}" );
				$installed_version++;
				$this->set_db_version( $installed_version );
			}
		}

		// Database upgrade routines complete. Update installed version.
		$this->logger->info( 'Database upgrade completed.' );
	}

	/**
	 * Plugin deactivation tasks.
	 * Executed whenever the plugin is deactivated.
	 * NOTE: deactivation is not the same as deletion or uninstall, as a user may temporarily deactivate
	 * the plugin and then activate it again so no data should be deleted here.
	 *
	 * @return void
	 */
	public function deactivate() {
		$this->logger->info( 'Plugin deactivation started.' );

		// Re-set the database version so that if the user reactivates the plugin, then our installer is run again.
		$this->set_db_version( 0 );

		/**
		 * Perform tasks when the plugin is deactivated.
		 *
		 * @internal
		 * @since 2.0.0
		 */
		do_action( 'wc_zapier_plugin_deactivate' );
		$this->logger->info( 'Plugin deactivation completed.' );
	}
}
