<?php

namespace OM4\WooCommerceZapier\LegacyMigration;

use OM4\WooCommerceZapier\LegacyMigration\ExistingUserUpgrade;
use OM4\WooCommerceZapier\Notice\BaseNotice;

defined( 'ABSPATH' ) || exit;


/**
 * Notice that is displayed to users upgrading from 1.9 to 2.0,
 * listing the new features and encouraging them to migrate their feeds.
 * Wording/content for this notice is stored in `templates/notices/upgraded-to-version-2.php`
 *
 * @since 2.0.0
 */
class UpgradedToVersion2Notice extends BaseNotice {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $id = 'upgraded_to_version_2';

	/**
	 * {@inheritDoc}
	 *
	 * @return array
	 */
	protected function template_variables() {
		return array(
			'migration_guide_url' => ExistingUserUpgrade::MIGRATION_GUIDE_URL,
		);
	}
}
