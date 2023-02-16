<?php

namespace OM4\WooCommerceZapier\NewUser;

use OM4\WooCommerceZapier\Auth\KeyDataStore;
use OM4\WooCommerceZapier\NewUser\NewUserWelcomeNotice;
use OM4\WooCommerceZapier\Settings;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;

defined( 'ABSPATH' ) || exit;

/**
 * Detects if a user is using the extension/plugin for the first time.
 *
 * @since 2.0.0
 */
class NewUser {

	/**
	 * Settings instance.
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $task_data_store;

	/**
	 * KeyDataStore instance.
	 *
	 * @var KeyDataStore
	 */
	protected $key_data_store;

	/**
	 * NewUserWelcomeNotice instance.
	 *
	 * @var NewUserWelcomeNotice
	 */
	protected $welcome_notice;

	/**
	 * Constructor.
	 *
	 * @param Settings             $settings Settings instance.
	 * @param TaskDataStore        $task_data_store TaskDataStore instance.
	 * @param KeyDataStore         $key_data_store KeyDataStore instance.
	 * @param NewUserWelcomeNotice $welcome_notice NewUserWelcomeNotice instance.
	 */
	public function __construct(
		Settings $settings,
		TaskDataStore $task_data_store,
		KeyDataStore $key_data_store,
		NewUserWelcomeNotice $welcome_notice
	) {
		$this->settings        = $settings;
		$this->task_data_store = $task_data_store;
		$this->key_data_store  = $key_data_store;
		$this->welcome_notice  = $welcome_notice;
	}

	/**
	 * Initialise the functionality by hooking into the relevant hooks.
	 *
	 * @return void
	 */
	public function initialise() {
		// Initialise Notice.
		$this->welcome_notice->initialise();

		add_action( 'wc_zapier_db_upgrade_v_11_to_12', array( $this, 'maybe_enable_new_user_notice' ) );
	}

	/**
	 * During the database upgrade routine, enable the new user welcome notice if the installation is new.
	 *
	 * Executed during the `wc_zapier_db_upgrade_v_11_to_12` hook.
	 *
	 * @return void
	 */
	public function maybe_enable_new_user_notice() {
		if ( $this->is_new_user() ) {
			$this->welcome_notice->enable();
		}
	}

	/**
	 * Whether or not the installation is a "new" user, which is someone with all of the following:
	 * - legacy mode disabled
	 * - 0 task history records
	 * - 0 authentication keys
	 *
	 * @return bool
	 */
	public function is_new_user() {
		if ( $this->settings->is_legacy_mode_enabled() ) {
			return false;
		}
		if ( 0 !== $this->task_data_store->get_tasks_count() ) {
			return false;
		}
		if ( 0 !== $this->key_data_store->count() ) {
			return false;
		}
		return true;
	}
}
