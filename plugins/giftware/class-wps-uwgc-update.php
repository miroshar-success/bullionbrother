<?php
/**
 * Exit if accessed directly
 *
 * @package     Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wps_Uwgc_Update' ) ) {
	/**
	 * The update-specific functionality of the plugin.
	 *
	 * @package    Ultimate Woocommerce Gift Cards
	 * @subpackage Ultimate Woocommerce Gift Cards/admin
	 * @author     WP Swings <webmaster@wpswings.com>
	 */
	class Wps_Uwgc_Update {

		/**
		 * Constructor.
		 */
		public function __construct() {
			register_activation_hook( WPS_UWGC_FILE, array( $this, 'wps_uwgc_activation' ) );
			add_action( 'wps_gw_check_event', array( $this, 'wps_uwgc_check_update' ) );
			add_filter( 'http_request_args', array( $this, 'wps_uwgc_updates_exclude' ), 5, 2 );
			add_action( 'install_plugins_pre_plugin-information', array( $this, 'wps_plugin_details' ) );
			register_deactivation_hook( WPS_UWGC_FILE, array( $this, 'wps_uwgc_check_deactivation' ) );
			add_action( 'in_plugin_update_message-giftware/giftware.php', array( $this, 'wps_uwgc_update_message' ), 10, 2 );
		}

		/**
		 * Wps_uwgc_update_message function
		 *
		 * @param array  $args args.
		 * @param string $response response.
		 * @return void
		 */
		public function wps_uwgc_update_message( $args, $response ) {
			$upgrade_notice     = '';
			$get_plugin_updates = $this->wps_uwgc_get_plugin_update_notice();
			if ( $args['new_version'] > $args['Version'] ) {
				if ( isset( $get_plugin_updates ) && '' !== $get_plugin_updates ) {
					$upgrade_notice .= '</p><p class="giftware-plugin-upgrade-notice" style="padding: 14px 10px !important;background: #1a4251 !important;color: #fff !important;">';
					$upgrade_notice .= $get_plugin_updates;
				}
			}
			echo wp_kses_post( $upgrade_notice );
		}

		/**
		 * Wps_uwgc_get_plugin_update_notice
		 *
		 * @return void
		 */
		public function wps_uwgc_get_plugin_update_notice() {
			$wps_notice = '';
			$url        = 'https://wpswings.com/pluginupdates/giftware/update.php';
			$postdata   = array(
				'action'       => 'check_update',
				'license_code' => WPS_UWGC_LICENSE_KEY,
			);
			$args       = array(
				'method' => 'POST',
				'body'   => $postdata,
			);
			$data       = wp_remote_post( $url, $args );
			if ( is_wp_error( $data ) ) {
				return;
			}
			if ( isset( $data['body'] ) ) {
				$all_data = json_decode( $data['body'], true );
				if ( is_array( $all_data ) && ! empty( $all_data ) ) {
					if ( isset( $all_data['upgrade_notice'] ) ) {
						$wps_notice = $all_data['upgrade_notice'];
					}
				}
			}
			return $wps_notice;
		}

		/**
		 * Function run on activation.
		 */
		public function wps_uwgc_activation() {
			if ( ! wp_next_scheduled( 'wps_gw_check_event' ) ) {
				wp_schedule_event( time(), 'daily', 'wps_gw_check_event' );
			}
		}

		/**
		 * Function to check activation.
		 */
		public function wps_uwgc_check_deactivation() {
			wp_clear_scheduled_hook( 'wps_gw_check_event' );
		}

		/**
		 * Function to check updates.
		 */
		public function wps_uwgc_check_update() {
			global $wp_version;
			global $wps_uwgc_update_check;
			$plugin_folder = plugin_basename( dirname( WPS_UWGC_FILE ) );
			$plugin_file   = basename( ( WPS_UWGC_FILE ) );
			if ( defined( 'WP_INSTALLING' ) ) {
				return false;
			}
			$postdata = array(
				'action'          => 'check_update',
				'current_version' => WPS_UWGC_PLUGIN_VERSION,
				'license_key'     => WPS_UWGC_LICENSE_KEY,
			);

			$args = array(
				'method' => 'POST',
				'body'   => $postdata,
			);

			$response = wp_remote_post( $wps_uwgc_update_check, $args );

			if ( empty( $response['response']['code'] ) || 200 !== (int) $response['response']['code'] ) {

				$plugin_transient = get_site_transient( 'update_plugins' );
				unset( $plugin_transient->response[ $plugin_folder . '/' . $plugin_file ] );
				set_site_transient( 'update_plugins', $plugin_transient );
				return;
			}

			if ( is_wp_error( $response ) ) {
				return false;
			} else {
				if ( empty( $response['body'] ) ) {
					return false;
				} else {
					list($version, $url) = explode( '~', $response['body'] );
					if ( $this->wps_plugin_get( 'Version' ) >= $version ) {
						return false;
					}

					$plugin_transient = get_site_transient( 'update_plugins' );
					$a                = array(
						'slug'        => $plugin_folder,
						'new_version' => $version,
						'url'         => $this->wps_plugin_get( 'AuthorURI' ),
						'package'     => $url,
					);
					$o                = (object) $a;
					$plugin_transient->response[ $plugin_folder . '/' . $plugin_file ] = $o;
					set_site_transient( 'update_plugins', $plugin_transient );
				}
			}
		}

		/**
		 * Exclude updates.
		 *
		 * @param array  $r Array value.
		 * @param string $url Url.
		 */
		public function wps_uwgc_updates_exclude( $r, $url ) {
			if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) ) {
				return $r;
			}
			if ( isset( $r ) && is_array( $r ) && ! empty( $r ) ) {
				if ( isset( $r['body'] ) && ! empty( $r['body'] ) ) {
					if ( isset( $r['body']['plugins'] ) && ! empty( $r['body']['plugins'] ) ) {
						$plugins = unserialize( $r['body']['plugins'] );
						unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
						unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
						$r['body']['plugins'] = serialize( $plugins );
						return $r;
					}
				}
			}
		}

		/**
		 * Return current plugin info.
		 *
		 * @param array $i value.
		 */
		public function wps_plugin_get( $i ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugin_folder = get_plugins( '/' . plugin_basename( dirname( WPS_UWGC_FILE ) ) );
			$plugin_file   = basename( ( WPS_UWGC_FILE ) );
			return $plugin_folder[ $plugin_file ][ $i ];
		}

		/**
		 * Return plugin details.
		 */
		public function wps_plugin_details() {
			global $tab;
			$current_plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
			if ( 'plugin-information' == $tab && 'giftware' == $current_plugin ) {

				$url = 'https://wpswings.com/pluginupdates/giftware/update.php';

				$postdata = array(
					'action'       => 'check_update',
					'license_code' => WPS_UWGC_LICENSE_KEY,
				);

				$args = array(
					'method' => 'POST',
					'body'   => $postdata,
				);

				$data = wp_remote_post( $url, $args );

				if ( is_wp_error( $data ) ) {
					return;
				}

				if ( isset( $data['body'] ) ) {
					$all_data = json_decode( $data['body'], true );

					if ( is_array( $all_data ) && ! empty( $all_data ) ) {
						$this->create_html_data( $all_data );
						wp_die();
					}
				}
			}
		}

		/**
		 * Create html data.
		 *
		 * @param array $all_data Data.
		 */
		public function create_html_data( $all_data ) {
			?>
			<style>
				#TB_window{
					top : 4% !important;
				}
				.wps_plugin_banner > img {
					height: 55%;
					width: 100%;
					border: 1px solid;
					border-radius: 7px;
				}
				.wps_plugin_description > h4 {
					background-color: #3779B5;
					padding: 5px;
					color: #ffffff;
					border-radius: 5px;
				}
				.wps_plugin_requirement > h4 {
					background-color: #3779B5;
					padding: 5px;
					color: #ffffff;
					border-radius: 5px;
				}
				#error-page > p {
					display: none;
				}
			</style>
			<div class="wps_plugin_details_wrapper">
				<div class="wps_plugin_banner">
					<img src="<?php echo esc_url( $all_data['banners']['low'] ); ?>">	
				</div>
				<div class="wps_plugin_description">
					<h4><?php esc_html_e( 'Plugin Description', 'giftware' ); ?></h4>
					<span><?php echo esc_html( $all_data['sections']['description'] ); ?></span>
				</div>
				<div class="wps_plugin_requirement">
					<h4><?php esc_html_e( 'Plugin Change Log', 'giftware' ); ?></h4>
					<span><?php echo wp_kses_post( $all_data['sections']['changelog'] ); ?></span>
				</div> 
			</div>
			<?php
		}
	}
	new WPS_UWGC_Update();
}
?>
