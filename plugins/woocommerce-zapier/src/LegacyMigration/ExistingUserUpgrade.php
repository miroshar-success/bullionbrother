<?php

namespace OM4\WooCommerceZapier\LegacyMigration;

use OM4\WooCommerceZapier\Helper\WordPressDB;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Settings;
use WC_DateTime;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Detects users upgrading from 1.9.x to 2.0+, and if active Zapier Feeds
 * are found then enable Legacy Mode (which lets the user manage Zapier Feeds
 * and deactivate them as they migrate to REST API Based Zaps).
 *
 * @since 2.0.0
 */
class ExistingUserUpgrade {

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Settings instance.
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * WordPress DB (WordPressDB) instance.
	 *
	 * @var WordPressDB
	 */
	protected $wp_db;

	/**
	 * UpgradedToVersion2Notice instance.
	 *
	 * @var UpgradedToVersion2Notice
	 */
	protected $upgraded_to_v2_notice;

	/**
	 * LegacyFeedsDeletedNotice instance.
	 *
	 * @var LegacyFeedsDeletedNotice
	 */
	protected $legacy_feeds_deleted_notice;

	/**
	 * The URL to the migration guide documentation that explains how users migrate to the new REST API based Zaps.
	 */
	const MIGRATION_GUIDE_URL = 'https://docs.om4.io/woocommerce-zapier/migration/';

	/**
	 * The deadline of the migration, users have to migrate to the new REST API before this.
	 * To access this date, use the `get_migration_deadline()` method which provides a localisation as well.
	 */
	const MIGRATION_DEADLINE = '30 April 2022';

	/**
	 * Constructor.
	 *
	 * @param Logger                   $logger Logger instance.
	 * @param Settings                 $settings Settings instance.
	 * @param WordPressDB              $wp_db WordPressDB instance.
	 * @param UpgradedToVersion2Notice $upgraded_to_v2_notice UpgradedToVersion2Notice instance.
	 * @param LegacyFeedsDeletedNotice $legacy_feeds_deleted_notice LegacyFeedsDeletedNotice instance.
	 */
	public function __construct(
		Logger $logger,
		Settings $settings,
		WordPressDB $wp_db,
		UpgradedToVersion2Notice $upgraded_to_v2_notice,
		LegacyFeedsDeletedNotice $legacy_feeds_deleted_notice
	) {
		$this->logger                      = $logger;
		$this->settings                    = $settings;
		$this->wp_db                       = $wp_db;
		$this->upgraded_to_v2_notice       = $upgraded_to_v2_notice;
		$this->legacy_feeds_deleted_notice = $legacy_feeds_deleted_notice;
	}

	/**
	 * Get the date that users must migrate their Zaps to the new REST API based Zap setup.
	 *
	 * @return string Human-readable date (localised in the user's date format).
	 */
	public static function get_migration_deadline() {
		$deadline = new WC_DateTime( self::MIGRATION_DEADLINE );
		return $deadline->date_i18n( get_option( 'date_format' ) );
	}

	/**
	 * Initialise the functionality by hooking into the relevant hooks.
	 *
	 * @return void
	 */
	public function initialise() {
		// Enable legacy mode when upgrading from a previous WC Zapier version.
		add_action( 'wc_zapier_db_upgrade_v_6_to_7', array( $this, 'enable_legacy_mode_if_required' ) );

		// Initialise Notices.
		$this->upgraded_to_v2_notice->initialise();
		$this->legacy_feeds_deleted_notice->initialise();

		if ( true === $this->settings->is_legacy_mode_enabled() ) {
			// Legacy Mode is enabled.
			// Add listeners to automatically disable Legacy mode when all Legacy Feeds are deleted.
			add_action( 'before_delete_post', array( $this, 'detect_legacy_feed_deactivation' ) );
		}
	}

	/**
	 * If the user has one or more active Zapier Feeds, then enable Legacy Mode.
	 *
	 * @return void
	 */
	public function enable_legacy_mode_if_required() {
		$num = $this->get_number_of_active_legacy_feeds();
		if ( 0 !== $num ) {
			$this->settings->set_legacy_mode_enabled();
			$this->logger->notice( 'Found %s active Zapier Feeds. Legacy Mode Enabled.', (string) $num );

			// Enable and display the user upgrade notice.
			$this->upgraded_to_v2_notice->enable();
		}
	}

	/**
	 * Get the number of active Zapier Feeds.
	 *
	 * @return int
	 */
	protected function get_number_of_active_legacy_feeds() {
		// We can't use wp_count_posts() because the post type isn't registered yet, so a direct database query is required.
		return absint( $this->wp_db->get_var( "SELECT COUNT( * ) FROM {$this->wp_db->posts} WHERE post_type = 'wc_zapier_feed' AND post_status = 'publish'" ) );
	}

	/**
	 * Whenever a post is about to be permanently deleted,
	 * if it is a Zapier Feed then check if Legacy Mode can be deactivated.
	 * Executed during the `before_delete_post` hook.
	 *
	 * @param int $post_id The Post ID being permanently deleted.
	 *
	 * @return void
	 */
	public function detect_legacy_feed_deactivation( $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post instanceof WP_Post ) {
			return;
		}
		if ( 'wc_zapier_feed' !== $post->post_type ) {
			return;
		}

		// The feed hasn't (quite) been deleted yet, so if there is 1 (or less) in the database
		// then inform the user that they have deleted all legacy feeds.
		if ( $this->get_number_of_active_legacy_feeds() <= 1 ) {
			// Enable the "Congratulations" notice which explains that legacy mode is being disabled.
			$this->legacy_feeds_deleted_notice->enable();
			// Disable the "Migrate your Zaps" notice (just in case the user hasn't already dismissed it).
			$this->upgraded_to_v2_notice->disable();
		}
	}
}
