<?php
/**
 * Class file to check for active license
 * Displays an inactive message if the API License Key has not yet been activated
 *
 * @package Kadence Plugins
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kadence_Plugin_API_Manager' ) ) {
	/**
	 * Class to check for license
	 *
	 * @category Class
	 */
	class Kadence_Plugin_API_Manager {
		private $api_url             = 'https://www.kadencewp.com/';
		private $api_data_key        = 'kt_plugin_api_manager';
		/**
		 * This is fall back for where we make api calls.
		 *
		 * @var url
		 */
		private $fallback_api_url    = 'https://www.kadencethemes.com/';
		private $renewal_url         = 'https://www.kadencewp.com/my-account/';
		private $admin_page_id       = 'kadence_plugin_activation';
		private $admin_page_name     = 'KT Plugin Activation';
		private $admin_page_title    = 'Kadence License Activation';
		private $version             = '1.1.5';
		public static $multisite     = false;
		public static $current_theme = null;
		public static $instance_id   = null;
		public static $domain        = null;
		public static $memberkey     = null;
		public static $memberemail   = null;
		public static $memberactive  = null;
		public static $products      = array();

		/**
		 * Settings Control.
		 *
		 * @var settings array
		 */
		public static $settings = array();

		/**
		 * Instance Control.
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Instance Control
		 */
		public static function get_instance() {
			if ( is_null(  self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public static function add_product( $product_key = '', $product_data_key = '', $product_id = '', $product_name = '' ) {
			// Lets make sure it's not added to the array
			if ( ! isset( self::$products[ $product_id ] ) ) {
				// add to the products array
				self::$products[ $product_id ] = array(
					'product_key'      => $product_key,
					'product_data_key' => $product_data_key,
					'product_id'       => $product_id,
					'product_name'     => $product_name,
				);
			}
		}
		/**
		 * Construct function.
		 */
		public function __construct() {

			if ( is_admin() ) {
				// Add notices.
				add_action( 'init', array( $this, 'on_init' ), 1 );

				// Add notices.
				add_action( 'admin_init', array( $this, 'on_admin_init' ), 20 );

				// Repeat Check license.
				add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'status_check' ) );

				// register settings.
				add_action( 'admin_init', array( $this, 'load_settings' ) );
				// Save Network.
				add_action( 'network_admin_edit_kt_activate_update_network_options', array( $this, 'update_network_options' ) );
				// deactivate Network.
				add_action( 'network_admin_edit_kt_deactivate_update_network_options', array( $this, 'deactivate_network_options' ) );

			}
		}

		/**
		 * Get Things started
		 */
		public function on_init() {
			if ( is_multisite() ) {
				$show_local_activation = apply_filters( 'kadence_activation_individual_multisites', false );
				if ( $show_local_activation ) {
					self::$multisite = false;
					add_action( 'admin_menu', array( $this, 'add_menu' ) );
				} else {
					self::$multisite = true;
					add_action( 'network_admin_menu', array( $this, 'add_network_menu' ), 10);
				}
			} else {
				add_action( 'admin_menu', array( $this, 'add_menu' ) );
			}
			self::$current_theme = wp_get_theme();
			self::$instance_id   = $this->get_setting_option( 'kt_plugin_api_manager_instance_id', 'needs_instance' ); // Instance ID (unique to each blog activation).
			if ( 'needs_instance' == self::$instance_id || '' == self::$instance_id ) {
				self::$instance_id = wp_generate_password( 12, false );
				$this->update_setting_option( 'kt_plugin_api_manager_instance_id', self::$instance_id );
			}
			self::$domain = str_ireplace( array( 'http://', 'https://' ), '', home_url() );
		}

		/**
		 * Add Notices
		 */
		public function on_admin_init() {

			add_action( 'admin_notices', array( $this, 'check_external_blocking' ) );
			add_action( 'admin_notices', array( $this, 'inactive_notice' ) );
		}

		/**
		 * Displays an inactive notice when the software is inactive.
		 */
		public function inactive_notice() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( isset( $_GET['page'] ) && $this->admin_page_id == $_GET['page'] ) {
				return;
			}
			$inactive_plugins = array();
			if ( $this->is_membership_activated() ) {
				foreach ( self::$products as $key => $value ) {
					if ( $this->get_setting_option( $value['product_key'], 'Deactivated' ) != 'Activated' ) {
						$this->update_setting_option( $value['product_key'], 'Activated' );
					}
				}
			} else {
				foreach ( self::$products as $key => $value ) {
					if ( $this->get_setting_option( $value['product_key'], 'Deactivated' ) != 'Activated' ) {
						$inactive_plugins[] = $value['product_name'];
					}
				}
			}
			if ( ! empty( $inactive_plugins ) ) {
				if ( self::$multisite && is_multisite() ) {
					if ( current_user_can( 'manage_network_options' ) ) {
						echo '<div class="error">';
						echo '<p>' . __( 'The following plugins have not been activated: ', 'kadence-plugin-api-manager' ) . implode( ', ', $inactive_plugins ) . '. <a href="' . esc_url( network_admin_url( 'settings.php?page=' . esc_attr( $this->admin_page_id ) ) ) . '">' . __( 'Click here to activate.', 'kadence-plugin-api-manager' ) . '</a></p>';
						echo '</div>';
					}

				} else {
					echo '<div class="error">';
					echo '<p>' . __( 'The following plugins have not been activated: ', 'kadence-plugin-api-manager' ) . implode( ', ', $inactive_plugins ) . '. <a href="' . esc_url( admin_url( 'options-general.php?page=' . esc_attr( $this->admin_page_id ) ) ) . '">' . __( 'Click here to activate.', 'kadence-plugin-api-manager' ) . '</a></p>';
					echo '</div>';
				}
			}
		}

		/**
		 * Checks if membership activated
		 * @return bool
		 */
		public function is_membership_activated() {
			if ( is_null( self::$memberactive ) ) {
				$current_theme_name = self::$current_theme->get( 'Name' );
				$current_theme_template = self::$current_theme->get( 'Template' );
				$membership = false;
				if ( 'Pinnacle Premium' == $current_theme_name || 'pinnacle_premium' == $current_theme_template ) {
					// Check if activated
					if ( get_option( 'kt_api_manager_pinnacle_premium_activated' ) == 'Activated' ) {
						// Check if membership
						$data     = get_option( 'kt_api_manager' );
						$license  = substr( $data[ 'kt_api_key' ], 0, 3 );
						if ( 'ktm' == $license || 'ktl' == $license ) {
							$membership = true;
							self::$memberkey = $data[ 'kt_api_key' ];
							self::$memberemail = $data[ 'activation_email' ];
						}
					}
				} else if ( 'Ascend - Premium' == $current_theme_name || 'ascend_premium' == $current_theme_template ) {
					// Check if activated
					if ( get_option( 'kt_api_manager_ascend_premium_activated' ) == 'Activated' ) {
						// Check if membership
						$data     = get_option( 'kt_api_manager' );
						$license  = substr( $data[ 'kt_api_key' ], 0, 3 );
						if ( 'ktm' == $license || 'ktl' == $license ) {
							$membership = true;
							self::$memberkey = $data[ 'kt_api_key' ];
							self::$memberemail = $data[ 'activation_email' ];
						}
					}
				} else if( 'Virtue - Premium' == $current_theme_name || 'virtue_premium' == $current_theme_template ) {
					if( get_option( 'kt_api_manager_virtue_premium_activated' ) == 'Activated' ) {
						// Check if membership
						$data     = get_option( 'kt_api_manager' );
						$license  = substr( $data[ 'kt_api_key' ], 0, 3 );
						if ( 'ktm' == $license || 'ktl' == $license ) {
							$membership = true;
							self::$memberkey = $data[ 'kt_api_key' ];
							self::$memberemail = $data[ 'activation_email' ];
						}
					}
				}
				self::$memberactive = $membership;
			}
			return self::$memberactive;
		}

		/**
		 * Adds The admin Menu
		 */
		public function add_menu() {
			$page = add_options_page( $this->admin_page_name, $this->admin_page_title, 'manage_options', $this->admin_page_id, array( $this, 'config_page' ) );
			add_action( 'admin_print_styles-' . $page, array( $this, 'load_scripts' ) );
		}

		/**
		 * Adds The admin Menu
		 */
		public function add_network_menu() {
			$page = add_submenu_page( 'settings.php', $this->admin_page_name, $this->admin_page_title, 'manage_network_options', $this->admin_page_id, array( $this, 'config_page' ) );
			add_action( 'admin_print_styles-' . $page, array( $this, 'load_scripts' ) );
		}


		/**
		 * Loads Admin Scripts
		 */
		public function load_scripts() {
			wp_enqueue_style( $this->admin_page_id . '-css', plugin_dir_url(__FILE__) . '/kadence-api-manage.css', array(), $this->version, 'all' );
		}

		/**
		 * Checks if license has expired
		 */
		public function status_check( $transient_value = null ) {
			if ( ! $this->is_membership_activated() ) {
				$status = get_transient( 'kt_plugin_api_status_check' );
				if ( false === $status ) {
						foreach ( self::$products as $p_key => $p_values ) {
							if ( $this->get_setting_option( $p_values['product_key'], 'Deactivated' ) == 'Activated' ) {
								$data = $this->get_setting_option( $p_values['product_data_key'], array('api_email' => '', 'api_key' => '' ) );
								if ( empty( $data[ 'api_email' ] ) || empty( $data[ 'api_key' ] ) ) {
									$this->update_setting_option( $p_values['product_key'], 'Deactivated' );
								}
								$args = array(
									'email'         => $data[ 'api_email' ],
									'licence_key'   => $data[ 'api_key' ],
									'product_id'    => $p_values['product_id'],
								);
								$status_results = json_decode( $this->status( $args ), true );
								if ( $status_results == 'failed' ) {
									// do nothing, could be timeout
								} else if ( isset( $status_results['status_check'] ) && $status_results['status_check'] == 'inactive' ) {
									$this->uninstall( $p_key );
									$this->update_setting_option( $p_values['product_key'], 'Deactivated' );
								} else if( isset( $status_results['error'] ) && isset( $status_results['code'] ) && ( '101' == $status_results['code'] || '104' == $status_results['code'] ) ) {
									$this->uninstall( $p_key );
									$this->update_setting_option( $p_values['product_key'], 'Deactivated' );
								}
							}
						}
					set_transient( 'kt_plugin_api_status_check', 1, WEEK_IN_SECONDS );
				}
			}
			return $transient_value;
		}

		/**
		 * Uninstalls a plugin activation
		 */
		public function uninstall( $p_key ) {
			global $blog_id;

			$this->license_key_deactivation( $p_key );

			// Remove options
			if ( is_multisite() ) {

				switch_to_blog( $blog_id );

				foreach ( array(
						self::$products[$p_key ]['product_data_key'],
						self::$products[$p_key ]['product_data_key'] . '_deactivation',
						) as $option) {

					delete_option( $option );

				}

				restore_current_blog();

			} else {

				foreach ( array(
					self::$products[$p_key ]['product_data_key'],
					self::$products[$p_key ]['product_data_key'] . '_deactivation',
				) as $option ) {

					$this->delete_setting_option( $option );

				}

			}

		}

		/**
		 * Deactivates the license on the API server.
		 *
		 * @return void
		 * @param string $p_key the product key.
		 */
		public function license_key_deactivation( $p_key ) {
			$data = $this->get_data_options( self::$products[ $p_key ]['product_data_key'] );
			$args = array(
				'email'       => ( isset( $data['api_email'] ) ? $data['api_email'] : '' ),
				'licence_key' => ( isset( $data['api_key'] ) ? $data['api_key'] : '' ),
				'product_id'  => self::$products[ $p_key ]['product_id'],
			);

			if ( $this->get_setting_option( self::$products[ $p_key ]['product_key'], 'Deactivated' ) == 'Activated' && $args['email'] != '' && $args['licence_key'] != '' ) {
				$this->deactivate( $args ); // reset license key activation.
			}
		}

		/**
		 * Check for external blocking contstant
		 */
		public function check_external_blocking() {
			// show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant.
			if ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {

				// check if our API endpoint is in the allowed hosts
				$host = parse_url( $this->$api_url, PHP_URL_HOST );

				if ( ! defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
					?>
					<div class="error">
						<p><?php printf( __( '<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get certain plugin updates. Please add %s to %s.', 'kadence-plugin-api-manager' ), '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>' ); ?></p>
					</div>
					<?php
				}
			}
		}

		/**
		 * Get data by key
		 *
		 * @return string
		 * @param string $key the product key.
		 */
		public function get_data_options( $key ) {
			if ( ! isset( self::$settings[ $key ] ) ) {
				self::$settings[ $key ] = $this->get_setting_option( $key, array( 'api_email' => '', 'api_key' => '' ) );
			}
			return self::$settings[ $key ];
		}
		/**
		 * Build activation page.
		 */
		public function config_page() {
			?>
			<div class="wrap kt_theme_license">
				<h2 class="notices"></h2>
				<div class="kt_title_area">
					<h1>
						<?php echo __( 'Kadence Plugin Activation.', 'kadence-plugin-api-manager' ); ?>
					</h1>
					<h5>
					<?php printf( __( 'Activating your license allows for plugin updates. If you need your api key you will find it by logging in to your %s Kadence WP account%s.', 'kadence-plugin-api-manager' ), '<a href="https://www.kadencewp.com/my-account/" target="_blank">', '</a>' );
					?>
					</h5>
				</div>
				<?php if ( isset( $_GET['kt_updated'] ) && 'true' === $_GET['kt_updated'] ) : ?>
					<div id="message" class="updated notice is-dismissible"><p><?php _e( 'Activated', 'kadence-plugin-api-manager' ); ?></p></div>
				<?php elseif ( isset( $_GET['kt_updated'] ) && 'false' === $_GET['kt_updated'] ) : ?>
					<div id="message" class="updated error"><p><?php _e( 'Could Not Activate', 'kadence-plugin-api-manager'); ?></p></div>
				<?php elseif ( isset( $_GET['kt_deactivate'] ) && 'true' === $_GET['kt_deactivate'] ) : ?>
					<div id="message" class="updated notice is-dismissible"><p><?php _e( 'Deactivated', 'kadence-plugin-api-manager' ); ?></p></div>
				<?php elseif ( isset( $_GET['kt_deactivate'] ) && 'false' === $_GET['kt_deactivate'] ) : ?>
					<div id="message" class="updated error"><p><?php _e( 'Could Not Deactivate, Try again.', 'kadence-plugin-api-manager'); ?></p></div>
				<?php endif; ?>

				<div class="kad-panel-contain">
					<div class="content kt-admin-clearfix">
						<div class="kt-main">
							<?php
							$membership_active = $this->is_membership_activated();
							foreach ( self::$products as $p_key => $p_values ) {
								$data = $this->get_data_options( self::$products[$p_key]['product_data_key'] );
								echo '<div class="kt-product-container">';
								echo '<h4>' . esc_html( $p_values['product_name'] ) .'</h4>';
								if ( $membership_active ) {
									echo '<h2>' . __( 'Kadence Membership activated through theme', 'kadence-plugin-api-manager' ) . '</h2>';
									echo '<h3 class="kt-primary-color">' . __( ' Status: Active', 'kadence-plugin-api-manager' ) . '</h3>';
								} else {
									if ( self::$multisite && is_multisite() ) {
										echo '<form action="edit.php?action=kt_activate_update_network_options" method="post">';
											settings_fields( $p_values['product_data_key'] );
											do_settings_sections( $p_values['product_id'] );
											if ( empty( $data['api_key'] ) ) {
												submit_button( __( 'Activate', 'kadence-plugin-api-manager' ) );
											}
										echo '</form>';
									} else {
										echo '<form action="options.php" method="post">';
											settings_fields( $p_values['product_data_key'] );
											do_settings_sections( $p_values['product_id'] );
											if ( empty( $data['api_key'] ) ) {
												submit_button( __( 'Activate', 'kadence-plugin-api-manager' ) );
											}
										echo '</form>';
									}
									if ( ! empty( $data['api_key'] ) ) {
										if ( self::$multisite && is_multisite() ) {
											echo '<form action="edit.php?action=kt_deactivate_update_network_options" method="post" class="kt-deactivation-form">';
												settings_fields( $p_values['product_data_key'] . '_deactivation' );
												do_settings_sections( $p_values['product_id'] . '_deactivation' );
												submit_button( __( 'Deactivate', 'kadence-plugin-api-manager' ), 'kt-deactivation-submit' );
											echo '</form>';
										} else {
											echo '<form action="options.php" method="post" class="kt-deactivation-form">';
												settings_fields( $p_values['product_data_key'] . '_deactivation' );
												do_settings_sections( $p_values['product_id'] . '_deactivation' );
												submit_button( __( 'Deactivate', 'kadence-plugin-api-manager' ), 'kt-deactivation-submit' );
											echo '</form>';
										}
									}
									echo '</div>';
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * This function here is hooked up to a special action and necessary to process
		 * the saving of the options. This is the big difference with a normal options
		 * page.
		 */
		public function update_network_options() {
			$options_id = $_REQUEST['option_page'];

			// Make sure we are posting from our options page.
			check_admin_referer( $options_id . '-options' );

			$settings = $this->validate_options( $_POST[ $options_id ] );
			if ( isset( $settings['api_email'] ) && ! empty( $settings['api_email'] ) ) {
				$updated = 'true';
				$this->update_setting_option( $options_id, $settings );
			} else {
				$updated = 'false';
				$this->update_setting_option( $options_id, array() );
			}

			// At last we redirect back to our options page.
			wp_redirect( add_query_arg( array( 'page' => $this->admin_page_id, 'kt_updated' => $updated ), network_admin_url( 'settings.php' ) ) );
			exit;
		}
		/**
		 * This function here is hooked up to a special action and necessary to process
		 * the saving of the options. This is the big difference with a normal options
		 * page.
		 */
		public function deactivate_network_options() {

			$options_id = $_REQUEST['option_page'];

			// Make sure we are posting from our options page.
			check_admin_referer( $options_id . '-options' );

			$settings = $this->key_deactivation( $_POST[ $options_id ] );
			if ( false === $settings ) {
				$updated = 'false';
			} else {
				$updated = 'true';
				$this->update_setting_option( $options_id, array() );
			}

			// At last we redirect back to our options page.
			wp_redirect( add_query_arg( array( 'page' => $this->admin_page_id, 'kt_deactivate' => $updated ), network_admin_url( 'settings.php' ) ) );
			exit;
		}
		/**
		 * Register settings.
		 */
		public function load_settings() {
			foreach ( self::$products as $p_key => $p_values ) {
				$args = array(
					'sanitize_callback' => array( $this, 'validate_options' ),
				);
				register_setting( $p_values['product_data_key'], $p_values['product_data_key'], $args );

				// API Activation settings
				add_settings_section( $p_values['product_key'], __( 'API License Activation', 'kadence-plugin-api-manager' ), array( $this, 'api_key_text' ), $p_values['product_id'] );

				add_settings_field( $p_values['product_key'], __( 'API License Key', 'kadence-plugin-api-manager' ), array( $this, 'api_key_field' ), $p_values['product_id'], $p_values['product_key'], array( 'p_key' => $p_key ) );
				add_settings_field( 'activation_email', __( 'API License Email', 'kadence-plugin-api-manager' ), array( $this, 'api_email_field' ), $p_values['product_id'], $p_values['product_key'], array( 'p_key' => $p_key ) );

				// Deactivation settings
				register_setting( $p_values['product_data_key'] . '_deactivation', $p_values['product_data_key'] . '_deactivation', array( $this, 'key_deactivation' ) );
				add_settings_section( 'deactivate_button', '', array( $this, 'deactivate_text' ), $p_values['product_id'] . '_deactivation' );
				add_settings_field( 'deactivate_button', __( 'Check box to deactivate API License key', 'kadence-plugin-api-manager' ), array( $this, 'deactivate_inputs' ), $p_values['product_id'] . '_deactivation', 'deactivate_button', array( 'p_key' => $p_key, 'class' => 'kt-plugin-deactivation' ));
			}

		}
		/**
		 * Provides text for api key section
		 */
		public function api_key_text() {

		}

		/**
		 * Outputs API License text field
		 */
		public function api_key_field( $args ) {
			$data = $this->get_data_options( self::$products[$args['p_key']]['product_data_key'] );

			if ( isset( $data['api_key'] ) && ! empty( $data['api_key'] ) ) {
				$start = 3;
				$length = mb_strlen( $data['api_key'] ) - $start - 3;
				$mask_string = preg_replace( '/\S/', 'X', $data['api_key'] );
				$mask_string = mb_substr( $mask_string, $start, $length );
				$input_string = substr_replace( $data['api_key'], $mask_string, $start, $length );
				$input_disabled = 'disabled';
			} else {
				$input_string = '';
				$input_disabled = '';
			}

			echo '<input id="' . self::$products[$args['p_key']]['product_id'] . '_api_key" name="' . esc_attr( self::$products[$args['p_key']]['product_data_key'] ) . '[api_key]" ' . esc_attr( $input_disabled ) . ' size="25" type="text" value="' . esc_attr( $input_string ) . '" />';
			echo '<input id="' . self::$products[$args['p_key']]['product_id'] . '_product_id" name="' . esc_attr( self::$products[$args['p_key']]['product_data_key'] ) . '[product_id]" type="hidden" value="' . esc_attr( $args['p_key'] ) . '" />';

			if ( isset( $data['api_key'] ) && ! empty( $data['api_key'] ) ) {
				echo '<span class="ktap-icon-pos"><i class="dashicons dashicons-yes" style="font-size: 32px; color:green;"></i></span>';
			} else {
				echo '<span class="ktap-icon-pos"><i class="dashicons dashicons-warning" style="font-size: 32px; color:orange;"></a></span>';
			}
		}

		/**
		 *  Outputs API License email text field
		 */
		public function api_email_field( $args ) {
			$data = $this->get_data_options( self::$products[$args['p_key']]['product_data_key'] );
			if ( isset( $data['api_email'] ) && ! empty( $data['api_email'] ) ) {
				$input_disabled = 'disabled';
				$input_string = $data['api_email'];
			} else {
				$input_disabled = '';
				$input_string = '';
			}
			echo '<input id="' . self::$products[$args['p_key']]['product_id'] . '_activation_email" name="' . self::$products[$args['p_key']]['product_data_key'] . '[api_email]" ' . esc_attr( $input_disabled ) . ' size="25" type="text" value="' . esc_attr( $input_string ) . '" />';
			if ( isset( $data['api_email'] ) && ! empty( $data['api_email'] ) ) {
				echo '<span class="ktap-icon-pos"><i class="dashicons dashicons-yes" style="font-size: 32px; color:green;"></i></span>';
			} else {
				echo '<span class="ktap-icon-pos"><i class="dashicons dashicons-warning" style="font-size: 32px; color:orange;"></a></span>';
			}
		}

		/**
		 *  Outputs deactivation field
		 */
		public function deactivate_inputs( $args ) {

			$data = $this->get_data_options( self::$products[$args['p_key']]['product_data_key'] );

			echo '<input type="checkbox" id="' . self::$products[$args['p_key']]['product_id'] . '_deactivation_checkbox" name="' . esc_attr( self::$products[$args['p_key']]['product_data_key']) . '_deactivation[deactivation_checkbox]" value="on"';
			echo checked( false, 'on' );
			echo '/>';
			echo '<input id="'.self::$products[$args['p_key']]['product_id'].'_product_id" name="' . esc_attr( self::$products[$args['p_key']]['product_data_key'] ). '_deactivation[product_id]" type="hidden" value="' . esc_attr( $args['p_key'] ) . '" />';
		}

		/**
		 * Provides text for deactivation section
		 */
		public function deactivate_text() {

		}
		/**
		 * Updates Settings.
		 *
		 * @param string $key the setting Key.
		 * @param mixed  $option the setting value.
		 */
		public function update_setting_option( $key, $option ) {
			if ( self::$multisite && is_multisite() ) {
				update_site_option( $key, $option );
			} else {
				update_option( $key, $option );
			}
		}
		/**
		 * Retrives Settings.
		 *
		 * @param string $key the setting Key.
		 * @param mixed  $default the setting default value.
		 */
		public function get_setting_option( $key, $default = null ) {
			if ( self::$multisite && is_multisite() ) {
				return get_site_option( $key, $default );
			} else {
				return get_option( $key, $default );
			}
		}
		/**
		 * Delete Settings.
		 *
		 * @param string $key the setting Key.
		 */
		public function delete_setting_option( $key ) {
			if ( self::$multisite && is_multisite() ) {
				delete_site_option( $key );
			} else {
				delete_option( $key );
			}
		}
		/**
		 *  Sanitizes and validates all input and output for Dashboard
		 *
		 * @param mixed $input the settings value.
		 */
		public function validate_options( $input ) {
			$current_key = trim( $input['api_key'] );
			$current_email = trim( $input['api_email'] );
			$current_product_id = trim( $input['product_id'] );
			$settings = array();

			// Should match the settings_fields() value.
			if ( $_REQUEST['option_page'] == self::$products[ $current_product_id ]['product_data_key'] ) {
				if ( empty( $current_key ) ) {
					add_settings_error( 'api_error_missing', 'api_key_error',  __( 'Missing API Key, PLease add. ', 'kadence-plugin-api-manager' ), 'error' );
					return false;
				}
				if ( empty( $current_email ) ) {
					add_settings_error( 'api_error_missing', 'api_email_error',  __( 'Missing API Email, PLease add. ', 'kadence-plugin-api-manager' ), 'error' );
					return false;
				}
				$args = array(
					'email'       => $current_email,
					'licence_key' => $current_key,
					'product_id'  => $current_product_id,
				);

				$activate_results = json_decode( $this->activate( $args ), true );
				if ( isset( $activate_results['activated'] ) && $activate_results['activated'] === true ) {

					add_settings_error( 'activate_text', 'activate_msg', __( 'Plugin activated.', 'kadence-plugin-api-manager' ), 'updated' );
					$settings['api_key']    = $current_key;
					$settings['api_email']  = $current_email;
					$settings['product_id'] = $current_product_id;
					$this->update_setting_option( self::$products[ $current_product_id ]['product_key'], 'Activated' );

					return $settings;
				}

				if ( $activate_results == false ) {
					add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Make sure your host servers php version has the curl module installed and enabled.', 'kadence-plugin-api-manager' ), 'error' );
					$this->update_setting_option( self::$products[ $current_product_id ]['product_key'], 'Deactivated' );

					return false;
				}
				if ( isset( $activate_results['code'] ) ) {

					switch ( $activate_results['code'] ) {
						case '100':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '101':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '102':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '103':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '104':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '105':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$$additional_info}", 'error' );
						break;
						case '106':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
					}

					$this->update_setting_option( self::$products[ $current_product_id ]['product_key'], 'Deactivated' );
					return false;
				}
			}

			return $settings;
		}

		/*
		 * Deactivates the license key to allow key to be used on another blog
		 */
		public function key_deactivation( $input ) {

			$current_product_id = trim( $input['product_id'] );
			$current_checkbox = trim( $input['deactivation_checkbox'] );

			// Should match the settings_fields() value
			if ( $_REQUEST['option_page'] == self::$products[$current_product_id]['product_data_key'] . '_deactivation' ) {

				if ( ! isset( $current_checkbox ) || empty( $current_checkbox ) ) {
					add_settings_error( 'kadence_deactivate_needs_check', 'deactivate_msg', __( 'Please check box to deactivate. ', 'kadence-plugin-api-manager' ), 'error' );
					return false;
				}

				$data = $this->get_data_options( self::$products[$current_product_id]['product_data_key'] );
				$args = array(
					'email'         => $data['api_email'],
					'licence_key'   => $data['api_key'],
					'product_id'    => $current_product_id,
				);

				$activate_results = json_decode( $this->deactivate( $args ), true );

				if ( isset( $activate_results['deactivated'] ) && $activate_results['deactivated'] == true ) {

					$this->update_setting_option( self::$products[$current_product_id]['product_key'], 'Deactivated' );
					$this->update_setting_option( self::$products[$current_product_id]['product_data_key'], array('api_email' => '', 'api_key' => '' ) );
					if ( isset( self::$settings[$current_product_id]['product_data_key'] ) ) {
						self::$settings[$current_product_id]['product_data_key'] = null;
					}
					add_settings_error( 'kadence_deactivate_text', 'deactivate_msg', __( 'License deactivated.', 'kadence-plugin-api-manager' ), 'updated' );

					return array( 'product_id' => $current_product_id );
				}

				if ( $activate_results == false ) {
					add_settings_error( 'api_key_check_text', 'api_key_check_error', __( 'Connection failed to the License Key API server. Make sure your host servers php version has the curl module installed and enabled.', 'kadence-plugin-api-manager' ), 'error' );

					return array( 'product_id' => $current_product_id );
				}

				if ( isset( $activate_results['code'] ) ) {

					switch ( $activate_results['code'] ) {
						case '100':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_email_text', 'api_email_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '101':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_text', 'api_key_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '102':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_purchase_incomplete_text', 'api_key_purchase_incomplete_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '103':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_exceeded_text', 'api_key_exceeded_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '104':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_not_activated_text', 'api_key_not_activated_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '105':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'api_key_invalid_text', 'api_key_invalid_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
						case '106':
							$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
							add_settings_error( 'sub_not_active_text', 'sub_not_active_error', "{$activate_results['error']}. {$additional_info}", 'error' );
						break;
					}

					$this->update_setting_option( self::$products[$current_product_id]['product_key'], 'Deactivated' );
					$this->update_setting_option( self::$products[$current_product_id]['product_data_key'], array('api_email' => '', 'api_key' => '' ) );
					if ( isset( self::$settings[$current_product_id]['product_data_key'] ) ) {
							self::$settings[$current_product_id]['product_data_key'] = null;
						}
					add_settings_error( 'kadence_deactivate_text', 'deactivate_msg', __( 'License deactivated.', 'kadence-plugin-api-manager' ), 'updated' );

					return array( 'product_id' => $current_product_id );
				}
			}

			return false;

		}

		/*
		 * API URl
		 */
		public function create_software_api_url( $args ) {

			$api_url = add_query_arg( $args, $this->api_url );

			return $api_url;
		}

		/**
		 * API Activation
		 *
		 * @param array $args the product args.
		 */
		public function activate( $args ) {
			$license = substr( $args['licence_key'], 0, 3 );
			if ( 'ktm' == $license ) {
				$args['product_id'] = 'ktm';
			} elseif ( 'ktl' === $license ) {
				$args['product_id'] = 'ktl';
			}
			if ( 'kadence_gutenberg_pro' === $args['product_id'] ) {
				if ( 'vps' == $license ) {
					$args['product_id'] = 'vps';
				} else if ( 'pps' == $license ) {
					$args['product_id'] = 'pps';
				} else if ( 'aps' == $license ) {
					$args['product_id'] = 'aps';
				}
			}
			$defaults = array(
				'wc-api'           => 'am-software-api',
				'request'          => 'activation',
				'instance'         => self::$instance_id,
				'platform'         => self::$domain,
				'software_version' => $this->version,
			);
			$args = wp_parse_args( $defaults, $args );

			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

			$request = wp_safe_remote_get( $target_url, array( 'sslverify'  => false ) );

			if ( is_wp_error( $request ) ) {
				// Lets try api address for some server types.
				$new_target_url = esc_url_raw( add_query_arg( $args, $this->fallback_api_url ) );

				$request = wp_safe_remote_get( $new_target_url, array( 'sslverify'  => false ) );

				if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
					return false;
				}

			} else if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}

			$response = wp_remote_retrieve_body( $request );

			return $response;
		}

		/**
		 * Deactivates software
		 *
		 * @param array $args the product args.
		 */
		public function deactivate( $args ) {
			$license = substr( $args['licence_key'], 0, 3 );
			if ( 'ktm' == $license ) {
				$args['product_id'] = 'ktm';
			} elseif ( 'ktl' === $license ) {
				$args['product_id'] = 'ktl';
			}
			if ( 'kadence_gutenberg_pro' === $args['product_id'] ) {
				if ( 'vps' == $license ) {
					$args['product_id'] = 'vps';
				} else if ( 'pps' == $license ) {
					$args['product_id'] = 'pps';
				} else if ( 'aps' == $license ) {
					$args['product_id'] = 'aps';
				}
			}
			$defaults = array(
				'wc-api'   => 'am-software-api',
				'request'  => 'deactivation',
				'instance' => self::$instance_id,
				'platform' => self::$domain,
			);

			$args = wp_parse_args( $defaults, $args );

			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

			$request = wp_safe_remote_get( $target_url, array( 'sslverify'  => false ) );

			if ( is_wp_error( $request ) ) {
				// Lets try api address for some server types.
				$new_target_url = esc_url_raw( add_query_arg( $args, $this->fallback_api_url ) );

				$request = wp_safe_remote_get( $new_target_url, array( 'sslverify'  => false ) );

				if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
					return false;
				}
			} else if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}

			$response = wp_remote_retrieve_body( $request );

			return $response;
		}

		/**
		 * Checks if the software is activated or deactivated
		 *
		 * @param array $args the product args.
		 */
		public function status( $args ) {
			$license = substr( $args['licence_key'], 0, 3 );
			if ( 'ktm' == $license ) {
				$args['product_id'] = 'ktm';
			} elseif ( 'ktl' === $license ) {
				$args['product_id'] = 'ktl';
			}
			if ( 'kadence_gutenberg_pro' === $args['product_id'] ) {
				if ( 'vps' == $license ) {
					$args['product_id'] = 'vps';
				} else if ( 'pps' == $license ) {
					$args['product_id'] = 'pps';
				} else if ( 'aps' == $license ) {
					$args['product_id'] = 'aps';
				}
			}
			$defaults = array(
				'wc-api'   => 'am-software-api',
				'request'  => 'status',
				'instance' => self::$instance_id,
				'platform' => self::$domain,
			);

			$args = wp_parse_args( $defaults, $args );

			$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

			$request = wp_safe_remote_get( $target_url, array( 'sslverify'  => false ) );

			if ( is_wp_error( $request ) ) {
				// Lets try api address for some server types.
				$new_target_url = esc_url_raw( add_query_arg( $args, $this->fallback_api_url ) );

				$request = wp_safe_remote_get( $new_target_url, array( 'sslverify'  => false ) );

				if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
					return false;
				}
			} else if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}

			$response = wp_remote_retrieve_body( $request );

			return $response;
		}

	}
}

$kt_plugin_api = Kadence_Plugin_API_Manager::get_instance();
$kt_plugin_api->add_product( 'kadence_pro_activation', 'kt_api_manager_kadence_pro_data', 'kadence_pro', 'Kadence Pro' );
