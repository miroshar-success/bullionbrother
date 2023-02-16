<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpswings.com/
 * @since             1.0.0
 * @package           Ultimate Woocommerce Gift Cards
 *
 * @wordpress-plugin
 * Plugin Name:       Gift Cards For WooCommerce Pro
 * Plugin URI:        https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-pro-backend&utm_campaign=giftcards-pro
 * Description:        <code><strong>Gift Cards for WooCommerce Pro</strong></code> plugin allows merchants to sell, and create gift cards on the WordPress website by increasing sales and revenue. <a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-giftcards-shop&utm_medium=giftcards-pro-backend&utm_campaign=shop-page" target="_blank"> Elevate your e-commerce store by exploring more on <strong> WP Swings </strong></a>.
 * Version:           3.5.7
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-giftcards-official&utm_medium=giftcards-pro-backend&utm_campaign=official
 * Requires at least: 4.5
 * Tested up to:      6.1.1
 * WC tested up to:   7.1.0
 * License:           WP Swings License
 * License URI:       https://wpswings.com/license-agreement.txt
 * Text Domain:       giftware
 * Domain Path:       /languages
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$wps_wgm_old_org_present = false;
$plugs           = get_plugins();

if ( isset( $plugs['woo-gift-cards-lite/woocommerce_gift_cards_lite.php'] ) ) {
	if ( version_compare( $plugs['woo-gift-cards-lite/woocommerce_gift_cards_lite.php']['Version'], '2.4.0', '<' ) ) {
		$wps_wgm_old_org_present = true;
	}
}
$activated = false;
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active( 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' ) ) {
		$activated = true;
	}
} else {
	if ( in_array( 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		$activated = true;
	}
}
define( 'WPS_UWGC_PLUGIN_VERSION', '3.5.7' );

register_activation_hook( __FILE__, 'activate_ultimate_woocommerce_gift_card' );
register_deactivation_hook( __FILE__, 'deactivate_ultimate_woocommerce_gift_card' );

/*Check If Ultimate Gift card lite is Active*/
if ( $activated && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

	if ( true === $wps_wgm_old_org_present ) {

		add_action( 'admin_notices', 'wps_wgm_upgrade_old_plugin' );
		/**
		 * Try org update to minimum.
		 */
		function wps_wgm_upgrade_old_plugin() {
			require_once 'wps-wgm-auto-download-free.php';
			wps_wgm_org_replace_plugin();
		}
		return;
	}

	$wp_upload     = wp_upload_dir();
	$wp_upload_dir = $wp_upload['basedir'];
	$wp_upload_url = $wp_upload['baseurl'];
	define( 'WPS_UWGC_UPLOAD_DIR', $wp_upload_dir );
	define( 'WPS_UWGC_UPLOAD_URL', $wp_upload_url );
	define( 'WPS_UWGC_DIRPATH', plugin_dir_path( __FILE__ ) );
	define( 'WPS_UWGC_URL', plugin_dir_url( __FILE__ ) );
	define( 'WPS_UWGC_ADMIN_URL', admin_url() );
	define( 'WPS_UWGC_SPECIAL_SECRET_KEY', '59f32ad2f20102.74284991' );
	define( 'WPS_UWGC_SERVER_URL', 'https://wpswings.com' );
	define( 'WPS_UWGC_ITEM_REFERENCE', 'giftware' );
	define( 'WPS_TEMPLATE_URL', 'https://demo.wpswings.com/client-notification/' );
	global $wp_version;
	if ( $wp_version >= '4.9.6' ) {
		include_once WPS_UWGC_DIRPATH . 'ultimate-woocommerce-gift-card-gdpr.php';
	}
	add_filter( 'plugin_action_links', 'wps_uwgc_admin_settings', 10, 2 );

	/**
	 * Register eraser for Plugin user data.
	 *
	 * @param array $actions Actions.
	 * @param array $plugin_file Plugin file.
	 * @return array
	 */
	function wps_uwgc_admin_settings( $actions, $plugin_file ) {
		static $plugin;
		if ( ! isset( $plugin ) ) {
			$plugin = plugin_basename( __FILE__ );
		}
		if ( $plugin == $plugin_file ) {
			$settings     = array(
				'settings' => '<a href="' . admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=general_setting' ) . '">' . __( 'Settings', 'giftware' ) . '</a>',
			);
			$callname_lic = Ultimate_Woocommerce_Gift_Card::$lic_callback_function;
			if ( ! Ultimate_Woocommerce_Gift_Card::$callname_lic() ) {
				$settings['license'] = '<a href="' . admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=validate_license' ) . '">' . __( 'Activate License', 'giftware' ) . '</a>';
			}
			$actions = array_merge( $settings, $actions );
		}
		return $actions;
	}

	add_action( 'admin_init', 'wps_uwgc_create_template_for_product_as_a_gift' );

	/**
	 * Function to create giftcard template for product as a gift.
	 */
	function wps_uwgc_create_template_for_product_as_a_gift() {

		if ( empty( get_option( 'wps_wgm_purchase_as_a_gift_template', '' ) ) ) {

			update_option( 'wps_wgm_purchase_as_a_gift_template', true );
			$wps_wgm_purchase_as_a_gift_template = '<table class="email-container" style="margin: auto; width: 100%!important;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center"> <tbody> <tr> <td style="padding: 20px 10px; text-align: left;">[LOGO]</td> <td style="padding: 20px 10; text-align: right;"><span style="font-size: 35px; font-family: arial; font-weight: bold; display: block;">[AMOUNT]</span><span style="font-size: 16px; font-family: arial; font-weight: bold; display: block;">(Ed: [EXPIRYDATE])</span></td> </tr> </tbody> </table> <table class="email-container" style="margin: auto; width: 100%!important;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center"> <tbody> <tr> <td style="padding: 40px 0px;" align="center"><span style="font-size: 25px;">Coupon Code</span> <p style="background-color: #e91e63; color: #fff; padding: 10px 10px; font-size: 26px; font-family: arial; margin: 10px 0px 10px 0px; letter-spacing: 10px; word-wrap: break-word; word-break: break-all;">[COUPON]</p> </td> </tr> <tr> <td style="padding: 10px;" align="center" valign="top"> <table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"> <tbody> <tr> <td class="stack-column-center" style="vertical-align: top; width: 50%;"> <table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"> <tbody> <tr> <td class="stack-column-center" style="vertical-align: top; width: 50%;"> <table role="presentation" border="0" cellspacing="0" cellpadding="0" align="center"> <tbody> <tr> <td style="padding: 10px; text-align: center;">[DEFAULTEVENT]</td> </tr> <tr> <td style="padding: 10px; text-align: center;">[PRODUCTNAME]</td> </tr> <tr> <td style="padding: 10px; text-align: center;">[MESSAGE]</td> </tr> </tbody> </table> </td> </tr> <tr> <td class="center-on-narrow" style="text-align: left; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555; padding: 0 10px 10px;"> <p style="text-align: center; font-size: 14px; font-family: sans-serif; margin: 0; font-weight: bold;">From :[FROM]</p> <p style="text-align: center; font-size: 14px; font-family: sans-serif; margin: 0; font-weight: bold;">To:[TO]</p> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <table class="email-container" style="margin: auto; width: 100%!important;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#333333"> <tbody> <tr> <td style="padding: 10px 10px; width: 100%; font-size: 14px; font-weight: bold; font-family: sans-serif; mso-height-rule: exactly; line-height: 18px; text-align: center; color: #ffffff;" bgcolor="#333333">[DISCLAIMER]</td> </tr> </tbody> </table> &nbsp; <style type="text/css">.stack-column-center img{max-width: 300px; width: 100%;}@media screen and (max-width: 480px){.stack-column,.stack-column-center{display: block !important;width: 100% !important;max-width: 100% !important;direction: ltr !important;}.stack-column-center{text-align: center !important;}}</style>';

			$gifttemplate_new = array(
				'post_title'   => __( 'Purchase as a Gift', 'giftware' ),
				'post_content' => $wps_wgm_purchase_as_a_gift_template,
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_type'    => 'giftcard',
			);
			$parent_post_id   = wp_insert_post( $gifttemplate_new );
		}
	}

	add_action( 'admin_init', 'wps_uwgc_create_giftcard_template' );

	/**
	 * Function to create giftcard template.
	 */
	function wps_uwgc_create_giftcard_template() {
		// create folder for qrcode/barcode.
		$upload_dir_path = WPS_UWGC_UPLOAD_DIR . '/qrcode_barcode';
		if ( ! is_dir( $upload_dir_path ) ) {
			wp_mkdir_p( $upload_dir_path );
			chmod( $upload_dir_path, 0775 );
		}

		/* ===== ====== Create the Check Gift Card Page ====== ======*/
		if ( ! get_option( 'check_balance_page_created', false ) ) {

			$balance_content = '[wps_check_your_gift_card_balance]';

			$check_balance = array(
				'post_author'  => get_current_user_id(),
				'post_name'    => __( 'Gift Card Balance', 'giftware' ),
				'post_title'   => __( 'Gift Card Balance', 'giftware' ),
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => $balance_content,
			);
			$page_id       = wp_insert_post( $check_balance );
			update_option( 'check_balance_page_created', true );
			/* ===== ====== End of Create the Gift Card Page ====== ======*/
		}
		if ( ! get_option( 'giftcard_balance' ) ) {
			$mypost = get_page_by_path( 'gift-card-balance', '', 'page' );
			if ( isset( $mypost ) ) {
				update_option( 'giftcard_balance', $mypost->ID );
			}
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-ultimate-woocommerce-gift-card-activator.php
	 */
	function activate_ultimate_woocommerce_gift_card() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-woocommerce-gift-card-activator.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-woocommerce-gift-cards-activation.php';
		$restore_data = new Ultimate_Woocommerce_Gift_Cards_Activation();
		$restore_data->wps_wgm_restore_data_pro();

		Ultimate_Woocommerce_Gift_Card_Activator::activate();
		set_transient( 'wps-uwgc-admin-activation-notice', true, 5 );
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-ultimate-woocommerce-gift-card-deactivator.php
	 */
	function deactivate_ultimate_woocommerce_gift_card() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-woocommerce-gift-card-deactivator.php';
		Ultimate_Woocommerce_Gift_Card_Deactivator::deactivate();
	}

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-woocommerce-gift-card.php';
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_ultimate_woocommerce_gift_card() {
		$plugin = new Ultimate_Woocommerce_Gift_Card();
		$plugin->run();
	}
	run_ultimate_woocommerce_gift_card();

	add_action( 'admin_init', 'wps_uwgc_giftcard_scheduling_cron' );

	/**
	 * Schedule Giftcard.
	 */
	function wps_uwgc_giftcard_scheduling_cron() {

		$offset = get_option( 'gmt_offset' );
		$time   = time() + $offset * 60 * 60;
		if ( ! wp_next_scheduled( 'wps_gw_giftcard_cron_schedule' ) ) {
			wp_schedule_event( $time, 'hourly', 'wps_gw_giftcard_cron_schedule' );
		}
		if ( ! wp_next_scheduled( 'wps_gw_giftcard_cron_delete_images' ) ) {
			wp_schedule_event( $time, 'daily', 'wps_gw_giftcard_cron_delete_images' );
		}
	}

	register_deactivation_hook( __FILE__, 'wps_uwgc_remove_cron_delete_images' );

	add_action( 'admin_init', 'wps_uwgc_offline_giftcard_table' );
	/**
	 * Schedule Giftcard.
	 */
	function wps_uwgc_offline_giftcard_table() {

		$offline_giftcard = get_option( 'wps_wgm_offline_giftcard', false );
		if ( empty( $offline_giftcard ) ) {
			global $wpdb;
			$table_name      = $wpdb->prefix . 'offline_giftcard';
			$charset_collate = '';
			if ( ! empty( $wpdb->charset ) ) {
				$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
			}

			if ( ! empty( $wpdb->collate ) ) {
				$charset_collate .= " COLLATE {$wpdb->collate}";
			}
			$create_tbl = "
			CREATE TABLE IF NOT EXISTS `$table_name` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`to` text,
				`from` text,
				`message` text,
				`amount` text,
				`coupon` text,
				`template` text,
				`mail` text,	
				`date` datetime,
				`schedule` date,
				PRIMARY KEY (`id`)
			);";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $create_tbl );
			$add_schedule = get_option( 'wps_wgm_add_schedule', false );

			if ( false == $add_schedule ) {
				update_option( 'wps_wgm_add_schedule', true );
				if ( ! empty( $wpdb->query ) ) {
					$wpdb->query( "ALTER TABLE $table_name ADD COLUMN `schedule` DATE" );
				}
			}
			update_option( 'wps_wgm_offline_giftcard', true );
		}
	}

	register_deactivation_hook( __FILE__, 'wps_uwgc_remove_cron_schedule' );
	/**
	 * This function is used to remove the cron schedule
	 *
	 * @name wps_uwgc_remove_cron_schedule
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	function wps_uwgc_remove_cron_schedule() {
		wp_clear_scheduled_hook( 'wps_gw_giftcard_cron_schedule' );
	}

	/**
	 * This function is used to remove the cron schedule for deleting images
	 *
	 * @name wps_uwgc_remove_cron_delete_image.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	function wps_uwgc_remove_cron_delete_images() {
		wp_clear_scheduled_hook( 'wps_gw_giftcard_cron_delete_images' );
	}
	add_action( 'admin_notices', 'wps_uwgc_license_notice_on_activation' );
	/**
	 * Wps_uwgc_license_notice_on_activation.
	 *
	 * @return void
	 */
	function wps_uwgc_license_notice_on_activation() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
		}
		if ( isset( $pagescreen ) && 'plugins' === $pagescreen ) {
			/* Check transient, if available display notice */
			if ( get_transient( 'wps-uwgc-admin-activation-notice' ) ) {
				?>
				<div class="updated notice is-dismissible" class="wps-wgm-is-dismissible">
				<p class="wps_wgm_plugin_active_para"><strong><?php esc_html_e( 'Welcome to Ultimate WooCommerce Gift Cards ', 'giftware' ); ?></strong><span style="background: black;color: #fff;padding: 2px 5px;margin-right: 3px;"><?php esc_html_e( ' Premium ', 'giftware' ); ?></span><?php esc_html_e( ' -Create and sell multiple Gift Cards with ease.', 'giftware' ); ?></p>
				<p class="wps_show_setting_on_activation">
					<?php
					$general_settings               = get_option( 'wps_wgm_general_settings', array() );
					$wps_obj                        = new Woocommerce_Gift_Cards_Common_Function();
					$wps_wgm_general_setting_enable = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable' );
					if ( 'on' !== $wps_wgm_general_setting_enable ) {
						?>
						<a class="wps_wgm_plugin_activation_msg" href="<?php echo esc_url( admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=general_setting' ) ); ?>"><?php echo esc_html__( 'Enable Gift Cards', 'giftware' ); ?></a>
						<?php
					}
					$callname_lic = Ultimate_Woocommerce_Gift_Card::$lic_callback_function;
					if ( ! Ultimate_Woocommerce_Gift_Card::$callname_lic() ) {
						?>
						<a class="wps_wgm_plugin_activation_msg" href="<?php echo esc_url( admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=validate_license' ) ); ?>"><?php echo esc_html__( 'Activate License', 'giftware' ); ?></a>
						<?php
					}
					?>
				</p>		
				</div>
				<?php
				/* Delete transient, only display this notice once. */
				delete_transient( 'wps-uwgc-admin-activation-notice' );
			}
		}
	}

	// Multisite Compatibilty for new site creation.
	add_action( 'wps_wgm_standard_plugin_on_create_blog', 'wps_wgm_standard_plugin_on_create_blog', 10, 3 );
	/**
	 * Compatibilty with multisite.
	 *
	 * @param string $wps_lcns_status status.
	 * @param string $wps_license_key license code.
	 * @param string $timestamp       timestamp.
	 * @return void
	 */
	function wps_wgm_standard_plugin_on_create_blog( $wps_lcns_status, $wps_license_key, $timestamp ) {
		update_option( 'wps_gw_lcns_key', $wps_license_key );
		update_option( 'wps_gw_lcns_status', $wps_lcns_status );
		update_option( 'wps_gw_lcns_thirty_days', $timestamp );
	}
} else {
	add_action( 'admin_enqueue_scripts', 'wps_uwgc_enqueue_activation_script' );
	add_action( 'admin_init', 'wps_uwgc_plugin_error_notice' );
	add_action( 'wp_ajax_wps_uwgc_activate_lite_plugin', 'wps_uwgc_activate_lite_plugin' );
	add_action( 'wp_ajax_wps_uwgc_dismiss_plugin_notice', 'wps_uwgc_dismiss_plugin_notice' );

	/**
	 * Plugin error notice.
	 */
	function wps_uwgc_plugin_error_notice() {
		// to hide Plugin activated notice.
		unset( $_GET['activate'] );
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'WooCommerce is not activated, Please activate WooCommerce first to activate Ultimate WooCommerce Gift Cards-Pro.', 'giftware' ); ?></p>
			</div>

			<?php
		} elseif ( ! is_plugin_active( 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' ) ) {
			?>

			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'Ultimate Gift Cards For WooCommerce is not activated, Please activate ', 'giftware' ); ?>
				<a href="<?php echo esc_url( 'https://wordpress.org/plugins/woo-gift-cards-lite/?utm_source=wps-org-plugin&utm_medium=org-plugin&utm_campaign=free-plugin' ); ?>"><?php esc_html_e( ' Ultimate Gift Cards For WooCommerce ', 'giftware' ); ?></a>
				<?php esc_html_e( 'first to activate Ultimate WooCommerce Gift Cards-Pro.', 'giftware' ); ?>
				</p>
				<?php
				$wps_lite_plugin = 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php';
				if ( file_exists( WP_PLUGIN_DIR . '/' . $wps_lite_plugin ) && ! is_plugin_active( 'woo-gift-cards-lite' ) ) {
					?>

						<p>
							<a class="button button-primary" href="<?php echo esc_attr( wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $wps_lite_plugin . '&amp;plugin_status=all&amp;paged=1&amp;s=', 'activate-plugin_' . $wps_lite_plugin ) ); ?>"><?php esc_html_e( 'Activate', 'giftware' ); ?></a>
						</p>
					<?php
				} else {
					?>
							<p>
								<a href = "#" id="wps-uwgc-install-lite" class="button button-primary"><?php esc_html_e( 'Install', 'giftware' ); ?></a>
								<span style="display: none;" class="wps_loader_style" id="wps_notice_loader">
									<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>assets/images/loading.gif">
								</span>
							</p>
						<?php
				}
				?>
			</div>

			<?php
		}

	}

	/**
	 * Enqueue js and css.
	 */
	function wps_uwgc_enqueue_activation_script() {
		$wps_uwgc_params = array(
			'ajax_url'       => admin_url( 'admin-ajax.php' ),
			'wps_uwgc_nonce' => wp_create_nonce( 'wps-uwgc-activation-nonce' ),
		);
		wp_enqueue_script( 'wps-uwgc-activation-js', plugin_dir_url( __FILE__ ) . 'admin/js/wps-uwgc-activation.js', array( 'jquery' ), '1.0.0', false );
		wp_localize_script( 'wps-uwgc-activation-js', 'wps_uwgc_activation', $wps_uwgc_params );
		wp_enqueue_style( 'wps-uwgc-activation-css', plugin_dir_url( __FILE__ ) . 'admin/css/wps-uwgc-activation.css', array(), '1.0.0' );
	}

	/**
	 * Install lite plugin on ajax request.
	 */
	function wps_uwgc_activate_lite_plugin() {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$wps_plugin_name = 'woo-gift-cards-lite';
		$wps_plugin_api  = plugins_api(
			'plugin_information',
			array(
				'slug'   => $wps_plugin_name,
				'fields' => array( 'sections' => false ),
			)
		);

		if ( isset( $wps_plugin_api->download_link ) ) {
			$wps_ajax_obj = new WP_Ajax_Upgrader_Skin();
			$wps_obj      = new Plugin_Upgrader( $wps_ajax_obj );
			$wps_install  = $wps_obj->install( $wps_plugin_api->download_link );
			activate_plugin( 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' );
		}
		echo 'success';
		wp_die();
	}

	/**
	 * Dismiss the notice.
	 */
	function wps_uwgc_dismiss_plugin_notice() {
		check_ajax_referer( 'wps-uwgc-activation-nonce', 'wps_nonce' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		echo 'success';
		wp_die();
	}
}


$wps_uwgc_license_key_pre = get_option( 'wps_gw_lcns_key', '' );
$wps_uwgc_server_host     = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
$wps_uwgc_license_key     = get_option( 'wps_gw_lcns_key' . $wps_uwgc_server_host, $wps_uwgc_license_key_pre );
define( 'WPS_UWGC_LICENSE_KEY', $wps_uwgc_license_key );
define( 'WPS_UWGC_FILE', __FILE__ );
$wps_uwgc_update_check = 'https://wpswings.com/pluginupdates/giftware/update.php';
require_once 'class-wps-uwgc-update.php';

?>
