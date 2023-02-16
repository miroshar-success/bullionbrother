<?php

namespace OM4\WooCommerceZapier;

use OM4\WooCommerceZapier\SystemStatus\UI as SystemStatusUI;
use OM4\WooCommerceZapier\TaskHistory\UI as TaskHistoryUI;

defined( 'ABSPATH' ) || exit;

/**
 * Administration / Dashboard (wp-admin) UI functionality:
 * - Adds the Dashboard, WooCommerce, Zapier menu item.
 * - Initialises our various WC Zapier Admin screens/UI's:
 *      - Task History Screen
 *      - Settings Screen
 *      - System Status Information
 * - Displays necessary Admin Notices to users browsing wp-admin, including necessary CSS/JS rules.
 *
 * @since 2.0.0
 */
class AdminUI {

	/**
	 * TaskHistoryUI instance.
	 *
	 * @var TaskHistoryUI
	 */
	protected $history_ui;

	/**
	 * Settings instance.
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * SystemStatusUI instance.
	 *
	 * @var SystemStatusUI
	 */
	protected $system_status_ui;

	/**
	 * Whether or not the site is using the WooCommerce Admin (wc-admin) interface,
	 * which was introduced in WooCommerce 4.0.
	 *
	 * @var bool
	 */
	protected $is_wc_admin;

	/**
	 * The full file name to the plugin's main Admin UI screen.
	 * Use self::get_url() to get the full URL.
	 *
	 * @var string
	 */
	const ADMIN_PAGE = 'admin.php?page=wc_zapier';

	/**
	 * Constructor
	 *
	 * @param TaskHistoryUI  $history_ui TaskHistory UI instance.
	 * @param Settings       $settings Settings instance.
	 * @param SystemStatusUI $system_status_ui SystemStatusUI instance.
	 */
	public function __construct( TaskHistoryUI $history_ui, Settings $settings, SystemStatusUI $system_status_ui ) {
		$this->history_ui       = $history_ui;
		$this->settings         = $settings;
		$this->system_status_ui = $system_status_ui;
		$this->is_wc_admin      = function_exists( 'wc_admin_connect_page' );
	}

	/**
	 * Instructs the Admin UI functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// After WC core calls WC_Admin_Notices::add_notices() is executed.
		add_action( 'admin_print_styles', array( $this, 'detect_if_notice_being_displayed' ), 11 );

		// Display notices on the Main Zapier screen.
		add_filter( 'woocommerce_screen_ids', array( $this, 'woocommerce_screen_ids' ) );

		// Initialise our UI's.
		$this->history_ui->initialise();
		$this->settings->initialise();
		$this->system_status_ui->initialise();

		if ( $this->is_wc_admin ) {
			// WC Admin (WooCommerce 4.0+): Add the WC Admin header/breadcrumb to our WC Zapier screen.
			wc_admin_connect_page(
				array(
					'id'        => 'woocommerce-zapier',
					'screen_id' => 'woocommerce_page_wc_zapier',
					'title'     => array(
						__( 'Zapier', 'woocommerce-zapier' ),
					),
					'path'      => 'admin.php?page=wc_zapier',
				)
			);
		} else {
			// WC 3.9 or earlier (ie not running WC Admin).
			add_action( 'admin_notices', array( $this, 'output_header' ), 9999 );
			add_action( 'admin_notices', array( $this, 'output_tabs' ), 9999 );
			add_action( 'admin_footer', array( $this, 'output_footer' ) );
		}
	}

	/**
	 * If WooCommerce is displaying a custom HTML notice (which could be one of ours),
	 * enqueue the output our CSS/JS.
	 * Executed during the `admin_print_styles` hook at priority 11.
	 *
	 * @return void
	 */
	public function detect_if_notice_being_displayed() {
		add_action( 'admin_print_styles', array( $this, 'output_css' ), 12 );
		if ( has_action( 'admin_notices', 'WC_Admin_Notices::output_custom_notices' ) ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'output_js' ), 12 );
		}
	}

	/**
	 * Output the CSS rules required for our Admin UI.
	 *
	 * @return void
	 */
	public function output_css() {
		$zapier_primary_colour = '#FF4A00';
		$zapier_hover_colour   = '#CC3A00';

		echo wp_kses(
			<<<EOD
<style type="text/css">
div.woocommerce-message.wc-zapier-notice-wrapper { border-left: 4px $zapier_primary_colour solid !important; }
div.wc-zapier-notice a.button.button-primary { background-color: $zapier_primary_colour; border-color: $zapier_primary_colour; box-shadow: none; text-shadow: none; }
div.wc-zapier-notice a.button.button-primary:hover, div.wc-zapier-notice a.button.button-primary:active { background-color: $zapier_hover_colour; border-color: $zapier_hover_colour;  }
div.wc-zapier-notice ul { list-style-type: disc; padding-left: 2em; }
</style>
EOD
			,
			array(
				'style' => array(
					'type' => true,
				),
			)
		);
	}

	/**
	 * Output our JS rules required for our admin UI.
	 * For each Zapier notice on the page:
	 * - Add a class to the parent (wrapper) div so we can style the overall notice.
	 * - If the notice content contains a dismiss button, when it is clicked then trigger WC's dismiss to handle the dismiss request.
	 *
	 * @return void
	 */
	public function output_js() {
		echo wp_kses(
			<<<EOD
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.wc-zapier-notice').each( function( index, element ) {
		jQuery(element).closest('.woocommerce-message').addClass('wc-zapier-notice-wrapper');
		jQuery(element).find('.button.dismiss-notice').on('click', function(event){
			event.preventDefault();
			window.location.href = jQuery(this).closest( '.wc-zapier-notice-wrapper' ).find('.woocommerce-message-close.notice-dismiss' ).attr('href');
		});
	});
});
</script>
EOD
			,
			array(
				'script' => array(
					'type' => true,
				),
			)
		);

	}

	/**
	 * Add "Zapier" to the WooCommerce Dashboard menu.
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_submenu_page( 'woocommerce', __( 'WooCommerce Zapier', 'woocommerce-zapier' ), __( 'Zapier', 'woocommerce-zapier' ), 'manage_woocommerce', 'wc_zapier', array( $this, 'output' ) );
	}

	/**
	 * If the current wp-admin page load is a Zapier-related page,
	 * output our standard heading.
	 * Executed during the `admin_notices` hook on every wp-admin page load.
	 *
	 * @return void
	 */
	public function output_header() {
		if ( $this->is_zapier_dashboard_screen() ) {
			echo '<div class="wrap woocommerce">';
			// Only output our H1 if wc-admin isn't active.
			if ( ! $this->is_wc_admin ) {
				echo '<h1 class="wp-heading-inline">' . esc_html__( 'WooCommerce Zapier', 'woocommerce-zapier' ) . '</h1>';
			}
		}
	}

	/**
	 * If the current wp-admin page load is a Zapier-related page,
	 * output our standard footer.
	 * Executed during the `admin_footer` hook on every wp-admin page load.
	 *
	 * @return void
	 */
	public function output_footer() {
		if ( $this->is_zapier_dashboard_screen() ) {
			// Closing tag for div.wrap.woocommerce which is output in output_header().
			echo '</div>';
		}
	}

	/**
	 * Whether or not the currently loading wp-admin screen is a Zapier-related screen.
	 *
	 * @return bool
	 */
	public function is_zapier_dashboard_screen() {
		foreach ( $this->get_admin_tabs() as $tab_id => $tab ) {
			if ( true === $tab['current_tab'] ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the full URL to the plugin's main Admin UI screen.
	 * This is the Task History screen.
	 *
	 * @return string
	 */
	public function get_url() {
		return admin_url( self::ADMIN_PAGE );
	}

	/**
	 * Get the list of WooCommerce Zapier Admin Tabs.
	 *
	 * @return array
	 */
	public function get_admin_tabs() {

		$admin_tabs['task_history'] = array(
			'label'       => __( 'Task History', 'woocommerce-zapier' ),
			'url'         => 'admin.php?page=wc_zapier',
			'current_tab' => false,
		);

		// Detect which tab is being current/active (if any).
		$current_screen = get_current_screen();
		if ( ! is_null( $current_screen ) && 'woocommerce_page_wc_zapier' === $current_screen->base ) {
			$admin_tabs['task_history']['current_tab'] = true;
		}

		/**
		 * Override the tabs that are displayed on the Dashboard, WooCommerce, Zapier screens.
		 *
		 * @internal
		 * @since 2.0.0
		 *
		 * @param array $rows The tab definitions.
		 */
		$admin_tabs = apply_filters( 'wc_zapier_admin_tabs', $admin_tabs );

		return $admin_tabs;
	}

	/**
	 * Output the tabs that are displayed on the WC Zapier wp-admin screens.
	 *
	 * @return void
	 */
	public function output_tabs() {
		if ( $this->is_zapier_dashboard_screen() ) {
			// The markup used here is the same as wp-content/plugins/woocommerce/includes/admin/views/html-admin-settings.php
			// so that we inherit the styling/CSS of those tabs.
			// Our own .wc-zapier-nav-tab-wrapper class is added.
			echo '<nav class="nav-tab-wrapper woo-nav-tab-wrapper wc-zapier-nav-tab-wrapper">';
			foreach ( $this->get_admin_tabs() as $tab ) {
				echo '<a href="' . esc_attr( admin_url( $tab['url'] ) ) . '" class="nav-tab ' . ( $tab['current_tab'] ? 'nav-tab-active' : '' ) . '">' . esc_html( $tab['label'] ) . '</a>';
			}
			echo '</nav>';
		}
	}

	/**
	 * Output the WooCommerce -> Zapier dashboard screen.
	 *
	 * @return void
	 */
	public function output() {
		if ( $this->is_wc_admin ) {
			$this->output_header();
			$this->output_tabs();
		}
		$this->history_ui->output_screen();
		if ( $this->is_wc_admin ) {
			$this->output_footer();
		}
	}

	/**
	 * Extend WooCommerce's list of wp-admin screen IDs, so our Main Dashboard -> WooCommerce -> Zapier screen
	 * is considered a WooCommerce screen.
	 * This ensures WooCommerce's admin.css is loaded when our screen is loaded.
	 * Executed by the `woocommerce_screen_ids` filter.
	 *
	 * @param string[] $screen_ids WooCommerce Screen IDs.
	 *
	 * @return string[]
	 */
	public function woocommerce_screen_ids( $screen_ids ) {
		// Main Dashboard -> WooCommerce -> Zapier screen (/wp-admin/admin.php?page=wc_zapier).
		$screen_ids[] = 'woocommerce_page_wc_zapier';
		return $screen_ids;
	}
}
