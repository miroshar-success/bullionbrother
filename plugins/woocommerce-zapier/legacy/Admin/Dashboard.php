<?php

namespace OM4\Zapier\Admin;

use OM4\Zapier\Admin\FeedUI;
use OM4\Zapier\Admin\SystemStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Administration (dashboard) functionality
 *
 * @deprecated 2.0.0
 */
class Dashboard {

	/**
	 * Constructor
	 */
	public function __construct() {
		new FeedUI();
		new SystemStatus();
	}
}
