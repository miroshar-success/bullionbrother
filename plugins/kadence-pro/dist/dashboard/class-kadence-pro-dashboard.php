<?php
/**
 * Displays an inactive message if the API License Key has not yet been activated
 *
 * @package Kadence Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for API getting started page.
 *
 * @category class
 */
class Kadence_Pro_API_Manager {
	/**
	 * This is where we make api calls.
	 *
	 * @var api url
	 */
	public $upgrade_url = 'https://www.kadencewp.com/';

	/**
	 * This is fall back for where we make api calls.
	 *
	 * @var url
	 */
	private $fallback_api_url = 'https://api.kadencewp.com/';

	/**
	 * This is the link to the account page.
	 *
	 * @var url
	 */
	private $renewal_url = 'https://www.kadencewp.com/my-account/';

	/**
	 * This is the link to the account page.
	 *
	 * @var url
	 */
	private $kt_license_link;

	/**
	 * This is the current theme version.
	 *
	 * @var number
	 */
	public $version;

	/**
	 * This is the theme name for the kadence theme.
	 *
	 * @var theme name
	 */
	private $kt_product_name;

	/**
	 * This is the current theme data object.
	 *
	 * @var theme data
	 */
	private $my_theme;

	/**
	 * This is the data key for database.
	 *
	 * @var string
	 */
	public $kt_data_key = 'ktp_api_manager';

	/**
	 * This is the settings key for api key.
	 *
	 * @var string
	 */
	public $kt_api_key = 'ktp_api_key';

	/**
	 * This is the settings key for api email.
	 *
	 * @var string
	 */
	public $kt_activation_email = 'activation_email';

	/**
	 * This is the product ID key.
	 *
	 * @var string
	 */
	public $kt_product_id_key;

	/**
	 * This is the api instance key.
	 *
	 * @var string
	 */
	public $kt_instance_key;

	/**
	 * This is the api activated key.
	 *
	 * @var string
	 */
	public $kt_activated_key;

	/**
	 * This is the settings key for api checkbox.
	 *
	 * @var string
	 */
	public $kt_deactivate_checkbox = 'kt_deactivate_example_checkbox';

	/**
	 * This is the settings key for api activate tab.
	 *
	 * @var string
	 */
	public $kt_activation_tab_key = 'kt_api_manager_dashboard';

	/**
	 * This is the settings key for api deactive tab.
	 *
	 * @var string
	 */
	public $kt_deactivation_tab_key = 'kt_api_manager_dashboard_deactivation';
	/**
	 * This is the page menu title.
	 *
	 * @var string
	 */
	public $kt_settings_menu_title;
	/**
	 * This is the page title.
	 *
	 * @var string
	 */
	public $kt_settings_title;
	/**
	 * This is the activation title.
	 *
	 * @var string
	 */
	public $kt_menu_tab_activation_title;
	/**
	 * This is the deactivation title.
	 *
	 * @var string
	 */
	public $kt_menu_tab_deactivation_title;
	/**
	 * This is options array.
	 *
	 * @var array
	 */
	public $kt_options;
	/**
	 * This is the product ID.
	 *
	 * @var string
	 */
	public $kt_product_id;
	/**
	 * This is the instance ID.
	 *
	 * @var string
	 */
	public $kt_instance_id;
	/**
	 * This is the site domain.
	 *
	 * @var string
	 */
	public $kt_domain;

	/**
	 * This is if it's a multisite.
	 *
	 * @var bool
	 */
	public static $multisite = false;

	/**
	 * Instance Control.
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Instance Control.
	 *
	 * @param string $kt_product_id_key product ID key.
	 * @param string $kt_instance_key product instance key.
	 * @param string $kt_activated_key product activated key.
	 * @param string $kt_product_id the product ID.
	 * @param string $kt_product_name the product name.
	 */
	public static function instance( $kt_product_id_key, $kt_instance_key, $kt_activated_key, $kt_product_id, $kt_product_name ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $kt_product_id_key, $kt_instance_key, $kt_activated_key, $kt_product_id, $kt_product_name );
		}
		return self::$_instance;
	}
	/**
	 * Constructor function.
	 *
	 * @param string $kt_product_id_key product ID key.
	 * @param string $kt_instance_key product instance key.
	 * @param string $kt_activated_key product activated key.
	 * @param string $kt_product_id the product ID.
	 * @param string $kt_product_name the product name.
	 */
	public function __construct( $kt_product_id_key, $kt_instance_key, $kt_activated_key, $kt_product_id, $kt_product_name ) {
		// Only run in the admin.
		if ( is_admin() ) {
			if ( is_multisite() ) {
				$show_local_activation = apply_filters( 'kadence_activation_individual_multisites', false );
				if ( $show_local_activation ) {
					self::$multisite = false;
				} else {
					self::$multisite = true;
				}
			}
			add_action( 'wp_loaded', array( $this, 'hide_inactive_notice' ) );
			add_action( 'admin_notices', array( $this, 'check_external_blocking' ) );
			add_action( 'admin_init', array( $this, 'activation' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'kadence_theme_dash_side_panel', array( $this, 'render_license_form' ) );
			add_action( 'wp_ajax_kadence_add_elementor', array( $this, 'add_elementor_ajax_callback' ) );

			// Repeat Check license.
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'status_check' ) );

			$this->my_theme = wp_get_theme(); // Get theme data.
			$this->version  = $this->my_theme->get( 'Version' );

			/**
			 * Set all data defaults here
			 */
			$this->kt_product_name                = apply_filters( 'kadence_whitelabel_theme_name', $kt_product_name );
			$this->kt_license_link                = apply_filters( 'kadence_whitelabel_license_link', $this->renewal_url );
			$this->kt_product_id_key              = $kt_product_id_key;
			$this->kt_instance_key                = $kt_instance_key;
			$this->kt_activated_key               = $kt_activated_key;
			$this->kt_settings_menu_title         = __( 'Getting Started', 'kadence-pro' );
			$this->kt_menu_tab_activation_title   = __( 'API License Activation', 'kadence-pro' );
			$this->kt_menu_tab_deactivation_title = __( 'Deactivation', 'kadence-pro' );
			$this->kt_options                     = $this->get_setting_option( $this->kt_data_key );
			$this->kt_product_id                  = $kt_product_id; // Software ID.
			$this->kt_instance_id                 = $this->get_setting_option( $this->kt_instance_key ); // Instance ID (unique to each blog activation).
			if ( empty( $this->kt_instance_id ) ) {
				$this->update_setting_option( $this->kt_instance_key, wp_generate_password( 12, false ) );
			}
			$this->kt_domain                      = str_ireplace( array( 'http://', 'https://' ), '', home_url() );
			add_action( 'init', array( $this, 'options_update' ), 1 );
			add_action( 'admin_notices' , array( $this, 'admin_interface_notices' ) );
			if ( 'Activated' !== $this->get_setting_option( $this->kt_activated_key ) ) {
				add_action( 'admin_notices', array( $this, 'inactive_notice' ) );
			}
		}
		add_action( 'init', array( $this, 'load_api_settings' ) );
	}
	/**
	 * On options save.
	 */
	public function options_update() {
		if ( isset( $_POST['kadence_theme_license_form_submit'] ) ) {
			$this->license_form_submit();
		}
	}
	/**
	 * Save Add Elements to Elementor.
	 */
	public function add_elementor_ajax_callback() {
		if ( ! check_ajax_referer( 'kadence-ajax-verification', 'security', false ) ) {
			wp_send_json_error( __( 'Security Error, please reload the page.', 'kadence-pro' ) );
		}
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$cpt_support = get_option( 'elementor_cpt_support' );
			if ( ! $cpt_support ) {
				$cpt_support = array( 'page', 'post', 'kadence_element' );
				update_option( 'elementor_cpt_support', $cpt_support );
			} else if ( ! in_array( 'kadence_element', $cpt_support ) ) {
				$cpt_support[] = 'kadence_element';
				update_option( 'elementor_cpt_support', $cpt_support );
			}
		}
		wp_send_json_success();
	}
	/**
	 * On options save validate the license.
	 */
	public function license_form_submit() {

		//check for de-activation.
		if ( isset( $_POST['kadence_theme_license_form_submit'] ) && isset( $_POST['kadence_theme_license_deactivate'] ) && wp_verify_nonce( $_POST['kadence_theme_license_nonce'], 'kadence_theme_license') ) {
			$this->validate_deactivation_options();
		}

		if ( isset( $_POST['kadence_theme_license_form_submit'] ) && isset( $_POST['kadence_theme_license_activate'] ) && wp_verify_nonce( $_POST['kadence_theme_license_nonce'], 'kadence_theme_license' ) ) {
			$this->validate_activation_options();
		}
	}
	/**
	 * Register settings
	 */
	public function load_api_settings() {
		register_setting(
			'kadence_pro_theme_config',
			'kadence_pro_theme_config',
			array(
				'type'              => 'string',
				'description'       => __( 'Config Kadence Pro Modules', 'kadence-blocks' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => '',
			)
		);
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
	 * Activation function to set defaults.
	 */
	public function activation() {
		if ( false === $this->get_setting_option( $this->kt_data_key ) || false === $this->get_setting_option( $this->kt_instance_key ) ) {
			$global_options = array(
				$this->kt_api_key          => '',
				$this->kt_activation_email => '',
			);
			$this->update_setting_option( $this->kt_data_key, $global_options );
			$single_options = array(
				$this->kt_product_id_key      => $this->kt_product_id,
				$this->kt_instance_key        => wp_generate_password( 12, false ),
				$this->kt_deactivate_checkbox => 'on',
				$this->kt_activated_key       => 'Deactivated',
			);
			foreach ( $single_options as $key => $value ) {
				$this->update_setting_option( $key, $value );
			}
		}
	}
	/**
	 * Check the license status.
	 *
	 * @param string $transient_value filter to pass along.
	 */
	public function status_check( $transient_value = null ) {
		$status = get_transient( 'kt_api_status_check' );
		if ( false === $status ) {
			if ( $this->get_setting_option( $this->kt_activated_key ) === 'Activated' ) {
				$data    = $this->get_setting_option( $this->kt_data_key );
				$args = array(
					'email'         => ( isset( $data[ $this->kt_activation_email ] ) ? $data[ $this->kt_activation_email ] : '' ),
					'licence_key'   => ( isset( $data[ $this->kt_api_key ] ) ? $data[ $this->kt_api_key ] : '' ),
					'version'       => KTP_VERSION,
					'ithemes_key'   => ( isset( $data['ithemes_key'] ) ? $data['ithemes_key'] : '' ),
				);
				if ( isset( $data['ithemes'] ) && $data['ithemes'] ) {
					$status_results = json_decode( $this->status_ithemes( $args ), true );
				} else {
					$status_results = json_decode( $this->status( $args ), true );
				}
				if ( 'failed' === $status_results ) {
					// do nothing.
				} elseif ( isset( $status_results['activated'] ) && 'inactive' === $status_results['activated'] ) {
					$this->uninstall();
					$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
				} elseif ( isset( $status_results['status_check'] ) && 'inactive' === $status_results['status_check'] ) {
					$this->uninstall();
					$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
				} elseif ( isset( $status_results['error'] ) && ( '101' == $status_results['code'] || '104' == $status_results['code'] ) ) {
					$this->uninstall();
					$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
				}
			}
			set_transient( 'kt_api_status_check', 1, 1200 );
		}
		return $transient_value;
	}
	/**
	 * Uninstall the product license.
	 */
	public function uninstall() {

		$this->license_key_deactivation();
		foreach ( array(
			$this->kt_data_key,
			$this->kt_product_id_key,
			$this->kt_instance_key,
			$this->kt_deactivate_checkbox,
			$this->kt_activated_key,
		) as $option ) {
			$this->delete_setting_option( $option );
		}
	}

	/**
	 * Deactivates the license on the API server
	 */
	public function license_key_deactivation() {
		$activation_status = $this->get_setting_option( $this->kt_activated_key );

		$api_email = $this->kt_options[ $this->kt_activation_email ];
		$api_key   = $this->kt_options[ $this->kt_api_key ];

		$args = array(
			'email'       => $api_email,
			'licence_key' => $api_key,
		);

		if ( 'Activated' === $activation_status && ! empty( $api_key ) && ! empty( $api_email ) ) {
			$this->deactivate( $args ); // reset license key activation.
		}
	}
	/**
	 * Hide Notice
	 */
	public function hide_inactive_notice() {
		if ( isset( $_GET['kadence-wp-activation-notice'] ) && isset( $_GET['_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( wp_unslash( sanitize_key( $_GET['_notice_nonce'] ) ), 'kadence_wp_hide_activation_notice' ) ) {
				wp_die( esc_html__( 'Authorization failed. Please refresh the page and try again.', 'kadence-pro' ) );
			}
			update_option( $this->kt_product_id_key . '_activation_notice', true );
		}
	}
	/**
	 * Displays an inactive notice when the software is inactive.
	 */
	public function inactive_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_GET['page'] ) && 'kadence' === $_GET['page'] ) {
			return;
		}
		if ( get_option( $this->kt_product_id_key . '_activation_notice' ) ) {
			return;
		}
		?>
		<div id="message" class="error" style="position: relative;">
			<p><?php /* translators: %1$s and %2$s refer to an internal link markup */ printf( __( 'The Pro Kadence Theme has not been activated! %1$sClick here%2$s to activate the license key.', 'kadence-pro' ), '<a href="' . esc_url( admin_url( 'themes.php?page=kadence' ) ) . '">', '</a>' ); ?></p>
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'kadence-wp-activation-notice', 'install' ), 'kadence_wp_hide_activation_notice', '_notice_nonce' ) ); ?>" style="text-decoration:none" class="notice-dismiss kt-close-theme-notice"><span class="screen-reader-text"><?php esc_html_e( 'hide', 'kadence-pro' ); ?></span></a>
		</div>
		<?php
	}

	/**
	 * Check for external blocking contstant
	 */
	public function check_external_blocking() {
		// show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant.
		if ( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {

			// check if our API endpoint is in the allowed hosts.
			$host = parse_url( $this->upgrade_url, PHP_URL_HOST );

			if ( ! defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
				?>
				<div class="error">
				<p><?php /* translators: %1$s  refers to product name. %2$s refer to host information and %3$s an internal link markup */ printf( esc_html__( 'Warning! You\'re blocking external requests which means you won\'t be able to get %1$s updates. Please add %2$s to %3$s.', 'kadence-pro' ), $this->kt_product_id, '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>' ); ?></p>
				</div>
				<?php
			}

		}
	}
	/**
	 * Register settings
	 */
	public function admin_interface_notices() {
		global $kadence_theme_license_interface_messages;

		if ( isset( $_GET['page'] ) && 'kadence' !== $_GET['page'] ) {
			return;
		}

		if ( ! is_array( $kadence_theme_license_interface_messages ) ) {
			return;
		}

		if ( count( $kadence_theme_license_interface_messages ) > 0 ) {
			foreach ( $kadence_theme_license_interface_messages as $message ) {
				echo '<div class="' . esc_attr( $message['type'] ) . ' fade"><p>' . esc_html( $message['text'] ) . '</p></div>';
			}
		}
	}
	/**
	 * Register settings
	 */
	public function render_license_form() {
		?>
		<div class="license-section sidebar-section components-panel">
			<div class="components-panel__body is-opened">
				<?php
				if ( 'Activated' !== $this->get_setting_option( $this->kt_activated_key ) ) {
					$this->license_form();
				} else {
					$this->license_deactivate_form();
				}
				?>
			</div>
		</div>
		<?php
	}
	/**
	 * Register settings
	 */
	public function license_form() {
		$data = $this->get_setting_option( $this->kt_data_key );
		$is_ithemes = ( isset( $data['ithemes'] ) && $data['ithemes'] ? true : false );
		if ( isset( $data[ $this->kt_api_key ] ) && ! empty( $data[ $this->kt_api_key ] ) ) {
			$input_string = $data[ $this->kt_api_key ];
		} else {
			$input_string = '';
		}
		if ( isset( $data['username'] ) && ! empty( $data['username'] ) ) {
			$user_input_string = $data['username'];
		} else {
			$user_input_string = '';
		}
		if ( isset( $data[ $this->kt_activation_email ] ) && ! empty( $data[ $this->kt_activation_email ] ) ) {
			$email_string = $data[ $this->kt_activation_email ];
		} else {
			$email_string = '';
		}
		?>
		<form name="form" method="post" id="kadence-pro-license-activate">
			<input type="hidden" name="kadence_theme_license_form_submit" value="true" />
			<input type="hidden" name="kadence_theme_license_activate" value="true" />
			<?php wp_nonce_field( 'kadence_theme_license', 'kadence_theme_license_nonce' ); ?>
			<h2>Kadence Pro<span class="kt-license-status k-inactive">Inactive</span></h2>
			<div class="toggle-ithemes" style="font-size: 11px;margin-bottom: 10px;">
				<input onChange="document.getElementById('kadence-pro-license-activate').submit()" <?php echo checked( $is_ithemes, true ); ?> type="checkbox" id="kadence_toggle_plateform" name="<?php echo esc_attr( $this->kt_data_key . '[ithemes]' ); ?>" value="on">
				<label for="kadence_toggle_plateform"><?php echo __( 'Use iThemes Toolkit/Agency License', 'kadence-pro' ); ?></label>
			</div>
			Please activate your license to get updates. If you need your api key you will find it by <a href="https://www.kadencewp.com/my-account/" target="_blank">logging into your account</a>.
			<?php
			if ( $is_ithemes ) {
				?>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">iThemes Username</th>
							<td>
								<input id="username" name="<?php echo esc_attr( $this->kt_data_key . '[username]' ); ?>" size="25" type="text" value="<?php echo esc_attr( $user_input_string ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">iThemes Password</th>
							<td>
								<input id="password" name="<?php echo esc_attr( $this->kt_data_key . '[password]' ); ?>" size="25" type="password" value="">
							</td>
						</tr>
					</tbody>
				</table>
				<?php
			} else {
				?>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">License Key</th>
							<td>
								<input id="api_key" name="<?php echo esc_attr( $this->kt_data_key . '[' . $this->kt_api_key . ']' ); ?>" size="25" type="text" value="<?php echo esc_attr( $input_string ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">License Email</th>
							<td>
								<input id="activation_email" name="<?php echo esc_attr( $this->kt_data_key . '[' . $this->kt_activation_email . ']' ); ?>" size="25" type="text" value="<?php echo esc_attr( $email_string ); ?>">
							</td>
						</tr>
					</tbody>
				</table>
			<?php } ?>
			<p class="submit">
				<input type="submit" name="bnt-submit" id="btn-submit" class="button button-primary" value="<?php echo esc_attr__( 'Activate', 'kadence-pro' ); ?>">
			</p>
		</form>
		<?php
	}
	/**
	 * Register settings
	 */
	public function license_deactivate_form() {
		$options = $this->get_setting_option( $this->kt_data_key );
		$is_ithemes = ( isset( $options['ithemes'] ) && $options['ithemes'] ? true : false );
		if ( isset( $options['username'] ) && ! empty( $options['username'] ) ) {
			$input_user_string = $options['username'];
		} else {
			$input_user_string = '';
		}
		if ( isset( $options[ $this->kt_api_key ] ) && ! empty( $options[ $this->kt_api_key ] ) ) {
			$start = 3;
			$length = mb_strlen( $options[ $this->kt_api_key ] ) - $start - 3;
			$mask_string = preg_replace( '/\S/', 'X', $options[ $this->kt_api_key ] );
			$mask_string = mb_substr( $mask_string, $start, $length );
			$input_string = substr_replace( $options[ $this->kt_api_key ], $mask_string, $start, $length );
		} else {
			$input_string = '';
		}
		if ( isset( $options[ $this->kt_activation_email ] ) && ! empty( $options[ $this->kt_activation_email ] ) ) {
			$start = 3;
			$length = mb_strlen( $options[ $this->kt_activation_email ] ) - $start;
			$email_mask_string = preg_replace( '/\S/', 'X', $options[ $this->kt_activation_email ] );
			$email_mask_string = mb_substr( $email_mask_string, $start, $length );
			$email_input_string = substr_replace( $options[ $this->kt_activation_email ], $mask_string, $start, $length );
		} else {
			$email_input_string = '';
		}
		?>
		<form id="form_data" name="form" method="post">
			<input type="hidden" name="kadence_theme_license_form_submit" value="true" />
			<input type="hidden" name="kadence_theme_license_deactivate" value="true" />
			<?php wp_nonce_field( 'kadence_theme_license', 'kadence_theme_license_nonce' ); ?>
			<h2>Kadence Pro<span class="kt-license-status k-active">Active</span></h2>
			<?php
			if ( $is_ithemes ) {
				?>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">iThemes Username</th>
							<td>
								<input id="username" name="<?php echo esc_attr( $this->kt_data_key . '[username]' ); ?>" size="25" type="text" disabled value="<?php echo esc_attr( $input_user_string ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">iThemes Password</th>
							<td>
								<input id="password" name="<?php echo esc_attr( $this->kt_data_key . '[password]' ); ?>" size="25" type="password" value="">
							</td>
						</tr>
					</tbody>
				</table>
				<?php
			} else {
				?>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row">License Key</th>
							<td>
								<input id="api_key" name="<?php echo esc_attr( $this->kt_data_key . '[' . $this->kt_api_key . ']' ); ?>" disabled size="25" type="text" value="<?php echo esc_attr( $input_string ); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row">License Email</th>
							<td>
								<input id="activation_email" name="<?php echo esc_attr( $this->kt_data_key . '[' . $this->kt_activation_email . ']' ); ?>" disabled size="25" type="text" value="<?php echo esc_attr( $email_input_string ); ?>">
							</td>
						</tr>
					</tbody>
				</table>
			<?php } ?>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr__( 'Deactivate', 'kadence-pro' ); ?>">
			</p>
		</form>
		<?php
	}

	/**
	 * Sanitizes and validates all input and output for Dashboard
	 */
	public function validate_activation_options() {
		global $kadence_theme_license_interface_messages;
		// Load existing options, validate, and update with changes from input before returning.
		$options                                = $this->kt_options;
		$form_data                              =  wp_unslash( $_POST[ $this->kt_data_key ] );
		$is_ithemes                             = ( isset( $form_data['ithemes'] ) ? trim( $form_data['ithemes'] ) : '' );
		$current_api_key                        = ( !empty( $options ) && isset( $options[ $this->kt_api_key ] ) ? $options[ $this->kt_api_key ] : '' );
		$options[ $this->kt_api_key ]           = ( isset( $form_data[ $this->kt_api_key ] ) ? trim( $form_data[ $this->kt_api_key ] ) : '' );
		$options[ $this->kt_activation_email ]  = ( isset( $form_data[ $this->kt_activation_email ] ) ? trim( $form_data[ $this->kt_activation_email ] ) : '' );
		$options['username']                    = ( isset( $form_data['username'] ) ? trim( $form_data['username'] ) : '' );
		$api_email                              = ( isset( $form_data[ $this->kt_activation_email ] ) ? trim( $form_data[ $this->kt_activation_email ] ) : '' );
		$api_key                                = ( isset( $form_data[ $this->kt_api_key ] ) ? trim( $form_data[ $this->kt_api_key ] ) : '' );
		$password                               = ( isset( $form_data['password'] ) ? trim( $form_data['password'] ) : '' );
		$activation_status                      = $this->get_setting_option( $this->kt_activated_key );
		$checkbox_status                        = $this->get_setting_option( $this->kt_deactivate_checkbox );
		$clear_options = array(
			$this->kt_api_key          => '',
			$this->kt_activation_email => '',
		);
		$settings = $options;
		if ( $is_ithemes && 'on' === $is_ithemes ) {
			$settings['ithemes'] = true;
		} else {
			$settings['ithemes'] = false;
		}
		if ( ( isset( $options['ithemes'] ) && $options['ithemes'] !== $settings['ithemes'] ) || ( ! isset( $options['ithemes'] ) && $settings['ithemes'] ) ) {
			$this->update_setting_option( $this->kt_data_key, $settings );
			return;
		}
		if ( $activation_status == 'Deactivated' || $activation_status == '' || $api_key == '' || $api_email == '' || $checkbox_status == 'on' || $current_api_key != $api_key ) {
			if ( isset( $current_api_key ) && ! empty( $current_api_key ) ) {
				if ( $current_api_key != $api_key ) {
					$this->replace_license_key( $current_api_key );
				}
			}

			$args = array(
				'email'         => $api_email,
				'licence_key'   => $api_key,
				'version'       => KTP_VERSION,
				'username'    => $options['username'],
				'password'    => $password,
			);
			if ( $settings['ithemes'] ) {
				$activate_results = json_decode( $this->activate_ithemes( $args ), true );
			} else {
				$activate_results = json_decode( $this->activate( $args ), true );
			}
			// if ( isset( $activate_results['code'] ) && '100' ===  $activate_results['code'] ) {
			// 	$reset = $this->deactivate( $args ); // reset license key activation.
			// 	if ( true == $reset ) {
			// 		$activate_results = json_decode( $this->activate( $args ), true );
			// 	}
			// }
			if ( isset( $activate_results['activated'] ) && $activate_results['activated'] === true ) {
				$kadence_theme_license_interface_messages[] = array(
					'type' => 'updated',
					'text' => __( 'Pro activated.', 'kadence-pro' ),
				);
				if ( isset( $activate_results['key'] ) && ! empty( $activate_results['key'] ) ) {
					$options['ithemes_key'] = $activate_results['key'];
				}
				$this->update_setting_option( $this->kt_data_key, $options );
				$this->update_setting_option( $this->kt_activated_key, 'Activated' );
			}

			if ( $activate_results == false ) {
				$kadence_theme_license_interface_messages[] = array(
					'type' => 'error',
					'text' => __( 'Connection failed to the License Key API server. Make sure your host servers php version has the curl module installed and enabled.', 'kadence-pro' ),
				);
				$this->update_setting_option( $this->kt_data_key, $clear_options );
				$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
			}
			if ( isset( $activate_results['code'] ) ) {
				switch ( $activate_results['code'] ) {
					case '100':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $clear_options );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
						break;
					case '101':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $clear_options );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
						break;
					case '102':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $clear_options );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
						break;
					case '103':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $clear_options );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
						break;
					case '104':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $clear_options );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
						break;
					case '105':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $clear_options );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
						break;
					case '106':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $clear_options );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
						break;
				}

			}
		}

		return;
	}

	/**
	 * Deactivate the current license key before activating the new license key.
	 *
	 * @param string $current_api_key the license key.
	 */
	public function replace_license_key( $current_api_key ) {
		global $kadence_theme_license_interface_messages;
		$args = array(
			'email'       => $this->kt_options[ $this->kt_activation_email ],
			'licence_key' => $current_api_key,
		);

		$reset = $this->deactivate( $args ); // reset license key activation.

		if ( true == $reset ) {
			return true;
		}
		$kadence_theme_license_interface_messages[] = array(
			'type' => 'error',
			'text' => __( 'The license could not be deactivated. Please try again.', 'kadence-pro' ),
		);

		return false;
	}

	/**
	 * Deactivates the license key to allow key to be used on another blog.
	 */
	public function validate_deactivation_options() {
		global $kadence_theme_license_interface_messages;
		$activation_status = $this->get_setting_option( $this->kt_activated_key );
		$form_data         = wp_unslash( $_POST[ $this->kt_data_key ] );
		$is_ithemes = ( isset( $options['ithemes'] ) && $options['ithemes'] ? true : false );
		if ( ! is_array( $this->kt_options ) ) {
			$this->kt_options = array(
				$this->kt_api_key          => '',
				$this->kt_activation_email => '',
			);
		}
		$args = array(
			'email'       => ( isset( $this->kt_options[ $this->kt_activation_email ] ) ? $this->kt_options[ $this->kt_activation_email ] : '' ),
			'licence_key' => ( isset( $this->kt_options[ $this->kt_api_key ] ) ? $this->kt_options[ $this->kt_api_key ] : '' ),
			'version'       => KTP_VERSION,
			'username'    => ( isset( $this->kt_options['username'] ) ? $this->kt_options['username'] : '' ),
			'ithemes_key' => ( isset( $this->kt_options['ithemes_key'] ) ? $this->kt_options['ithemes_key'] : '' ),
			'password'    => ( isset( $form_data['password'] ) ? trim( $form_data['password'] ) : '' ),
		);
		$update_cleared = array(
			$this->kt_api_key          => '',
			$this->kt_activation_email => '',
		);
		if ( $activation_status != 'Activated' ) {
			$this->update_setting_option( $this->kt_instance_key, wp_generate_password( 12, false ) );
			$this->update_setting_option( $this->kt_data_key, $update_cleared );
		}

		if ( $activation_status === 'Activated' && $this->kt_options[ $this->kt_api_key ] != '' && $this->kt_options[ $this->kt_activation_email ] != '' ) {
			if ( $is_ithemes ) {
				$activate_results = json_decode( $this->deactivate_ithemes( $args ), true );
			} else {
				$activate_results = json_decode( $this->deactivate( $args ), true );
			}

			if ( $activate_results['deactivated'] == true ) {
				if ( ! empty( $this->kt_activated_key ) ) {
					$this->update_setting_option( $this->kt_data_key, $update_cleared );
					$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					$kadence_theme_license_interface_messages[] = array(
						'type' => 'updated',
						'text' => __( 'Pro license deactivated. ', 'kadence-pro' ),
					);
				}

				return;
			}

			if ( isset( $activate_results['code'] ) ) {

				switch ( $activate_results['code'] ) {
					case '100':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $update_cleared );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					break;
					case '101':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $update_cleared );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					break;
					case '102':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $update_cleared );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					break;
					case '103':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $update_cleared );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					break;
					case '104':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $update_cleared );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					break;
					case '105':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $update_cleared );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					break;
					case '106':
						$additional_info = ! empty( $activate_results['additional info'] ) ? esc_attr( $activate_results['additional info'] ) : '';
						$kadence_theme_license_interface_messages[] = array(
							'type' => 'error',
							'text' => "{$activate_results['error']}. {$additional_info}",
						);
						$this->update_setting_option( $this->kt_data_key, $update_cleared );
						$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
					break;
				}

			}
		} elseif ( 'Activated' === $activation_status && ( '' === $this->kt_options[ $this->kt_api_key ] || '' === $this->kt_options[ $this->kt_activation_email ] ) ) {
			$update = array(
				$this->kt_api_key => '',
				$this->kt_activation_email => ''
			);

			$merge_options = array_merge( $this->kt_options, $update );
			if ( ! empty( $this->kt_activated_key ) ) {
				$this->update_setting_option( $this->kt_data_key, $merge_options );
				$this->update_setting_option( $this->kt_activated_key, 'Deactivated' );
				$kadence_theme_license_interface_messages[] = array(
					'type' => 'updated',
					'text' => __( 'Pro license deactivated. ', 'kadence-pro' ),
				);
			}

			return;
		}
		return;
	}

	/**
	 * Loads admin style sheets and scripts
	 */
	public function scripts() {
		if ( ! isset( $_GET['page'] ) || 'kadence' !== $_GET['page'] ) {
			return;
		}
		wp_enqueue_script( 'kadence-pro-dashboard', KTP_URL . 'build/dashboard.js', array( 'wp-i18n', 'wp-element', 'wp-plugins', 'wp-components', 'wp-api', 'wp-hooks', 'wp-edit-post', 'lodash', 'wp-block-library', 'wp-block-editor', 'wp-editor', 'jquery' ), KTP_VERSION, true );
		wp_localize_script(
			'kadence-pro-dashboard',
			'kadenceProDashboardParams',
			array(
				'adminURL'   => admin_url(),
				'settings'   => get_option( 'kadence_pro_theme_config' ),
				'activated'  => $this->get_setting_option( $this->kt_activated_key ),
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'kadence-ajax-verification' ),
			)
		);
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'kadence-pro-dashboard', 'kadence-pro' );
		}
	}
	/**
	 * Create Software API URL
	 *
	 * @param array $args for the url.
	 */
	public function create_software_api_url( $args ) {

		$api_url = add_query_arg( $args, $this->upgrade_url );

		return $api_url;
	}
	/**
	 * Activate the domain
	 *
	 * @param array $args for the activation.
	 */
	public function activate_ithemes( $args ) {
		// Ithemes Add.
		if ( is_callable( 'network_home_url' ) ) {
			$site_url = network_home_url( '', 'http' );
		} else {
			$site_url = get_bloginfo( 'url' );
		}
		$site_url = preg_replace( '/^https/', 'http', $site_url );
		$site_url = preg_replace( '|/$|', '', $site_url );
		$username = $args['username'];
		$password = $args['password'];
		$query = array(
			'user' => $username,
		);
		$default_query = array(
			'wp'           => $GLOBALS['wp_version'],
			'site'         => $site_url,
			'timestamp'    => time(),
			'auth_version' => '2',
		);
		$data = array(
			'auth_token' => $this->get_password_hash( $username, $password, $site_url ),
			'packages'   => array(
				'kadence-pro' => array(
					'ver' => $args['version'],
					'key' => '',
					'active' => false,
				),
			),
		);
		if ( isset( $data['auth_token'] ) ) {
			$data['iterations'] = 8;
		}
		$query = array_merge( $default_query, $query );
		$post_data = array(
			'request' => json_encode( $data ),
		);
		$remote_post_args = array(
			'timeout' => 10,
			'body'    => $post_data,
		);
		$request = "/package-activate/?" . http_build_query( $query, '', '&' );
		$response = wp_remote_post( 'https://api.ithemes.com/updater' . $request, $remote_post_args );
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 ) {
			return false;
		}
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! isset( $response['packages'] ) ) {
			return false;
		}
		if ( empty( $response['packages'] ) ) {
			return false;
		}
		if ( ! is_array( $response['packages'] ) ) {
			return false;
		}
		$success = array();
		$data_key = '';
		foreach ( $response['packages'] as $package => $data ) {
			if ( preg_match( '/ \|\|\| \d+$/', $package ) ) {
				continue;
			}
			if ( ! empty( $data['key'] ) && 'active' === $data['status'] ) {
				$success[] = 'kadence-pro';
				$data_key = $data['key'];
			}
		}
		if ( ! empty( $success ) ) {
			return '{"activated":true,"key":"' . $data_key . '"}';
		} else {
			return false;
		}
	}
	/**
	 * Activate the domain
	 *
	 * @param array $args for the activation.
	 */
	public function deactivate_ithemes( $args ) {
		// Ithemes Add.
		if ( is_callable( 'network_home_url' ) ) {
			$site_url = network_home_url( '', 'http' );
		} else {
			$site_url = get_bloginfo( 'url' );
		}
		$site_url = preg_replace( '/^https/', 'http', $site_url );
		$site_url = preg_replace( '|/$|', '', $site_url );
		$username = $args['username'];
		$password = $args['password'];
		$query = array(
			'user' => $username,
		);
		$default_query = array(
			'wp'           => $GLOBALS['wp_version'],
			'site'         => $site_url,
			'timestamp'    => time(),
			'auth_version' => '2',
		);
		$data = array(
			'auth_token' => $this->get_password_hash( $username, $password, $site_url ),
			'packages'   => array(
				'kadence-pro' => array(
					'ver' => $args['version'],
					'key' => $args['ithemes_key'],
					'active' => true,
				),
			),
		);
		if ( isset( $data['auth_token'] ) ) {
			$data['iterations'] = 8;
		}
		$query = array_merge( $default_query, $query );
		$post_data = array(
			'request' => json_encode( $data ),
		);
		$remote_post_args = array(
			'timeout' => 10,
			'body'    => $post_data,
		);
		$request = "/package-deactivate/?" . http_build_query( $query, '', '&' );
		$response = wp_remote_post( 'https://api.ithemes.com/updater' . $request, $remote_post_args );
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 ) {
			return false;
		}
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! isset( $response['packages'] ) ) {
			return false;
		}
		if ( empty( $response['packages'] ) ) {
			return false;
		}
		if ( ! is_array( $response['packages'] ) ) {
			return false;
		}
		$success = array();
		$data_key = '';
		foreach ( $response['packages'] as $package => $data ) {
			if ( preg_match( '/ \|\|\| \d+$/', $package ) ) {
				continue;
			}
			if ( isset( $data['status'] ) && 'inactive' === $data['status'] ) {
				$success[] = 'kadence-pro';
			}
		}
		if ( ! empty( $success ) ) {
			return '{"deactivated":true}';
		} else {
			return false;
		}
	}
	/**
	 * Checks if the software is activated or deactivated
	 *
	 * @param array $args the product args.
	 */
	public function status_ithemes( $args ) {
		// Ithemes Add.
		if ( is_callable( 'network_home_url' ) ) {
			$site_url = network_home_url( '', 'http' );
		} else {
			$site_url = get_bloginfo( 'url' );
		}
		$site_url = preg_replace( '/^https/', 'http', $site_url );
		$site_url = preg_replace( '|/$|', '', $site_url );
		$query = array();
		$default_query = array(
			'wp'           => $GLOBALS['wp_version'],
			'site'         => $site_url,
			'timestamp'    => time(),
			'auth_version' => '2',
		);
		$data = array(
			'packages'   => array(
				'kadence-pro' => array(
					'ver' => $args['version'],
					'key' => $args['ithemes_key'],
					'active' => true,
				),
			),
		);
		$query = array_merge( $default_query, $query );
		$post_data = array(
			'request' => json_encode( $data ),
		);
		$remote_post_args = array(
			'timeout' => 10,
			'body'    => $post_data,
		);
		$request = "/package-details/?" . http_build_query( $query, '', '&' );
		$response = wp_remote_post( 'https://api.ithemes.com/updater' . $request, $remote_post_args );
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 ) {
			return false;
		}
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! isset( $response['packages'] ) ) {
			return '{"status_check":"inactive"}';
		}
		if ( empty( $response['packages'] ) ) {
			return '{"status_check":"inactive"}';
		}
		if ( ! is_array( $response['packages'] ) ) {
			return '{"status_check":"inactive"}';
		}
		$success = array();
		foreach ( $response['packages'] as $package => $data ) {
			if ( preg_match( '/ \|\|\| \d+$/', $package ) ) {
				continue;
			}
			if ( isset( $data['status'] ) && 'active' === $data['status'] ) {
				$success[] = 'kadence-pro';
			}
		}
		if ( ! empty( $success ) ) {
			return '{"status_check":"active"}';
		} else {
			return '{"status_check":"inactive"}';
		}
	}
	public function get_password_hash( $username, $password, $site_url ) {
		$password = $this->get_pbkdf2( $password, $username );
		$salted_password = $password . $username . $site_url . $GLOBALS['wp_version'];
		$salted_password = substr( $salted_password, 0, max( strlen( $password ), 512 ) );
		$auth_token = wp_hash_password( $salted_password );
		return $auth_token;
	}
	public function get_salt( $username ) {
		return strtolower( trim( $username ) ) . 'wdHVwU&HcYcWnllo%kTUUnxpScy4%ICM29';
	}
	public function get_pbkdf2( $password, $username ) {
		
		return $this->pbkdf2(
			'sha256', 
			$password, 
			$this->get_salt( $username ), 
			131072, 
			64 / 2, 
			false
		);
		
	}
	
	//-----------------------------------------------------------------------------        
	
	/*
		* PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
		* $algorithm - The hash algorithm to use. Recommended: SHA256
		* $password - The password.
		* $salt - A salt that is unique to the password.
		* $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
		* $key_length - The length of the derived key in bytes.
		* $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
		* Returns: A $key_length-byte key derived from the password and salt.
		*
		* Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
		*
		* This implementation of PBKDF2 was originally created by https://defuse.ca
		* With improvements by http://www.variations-of-shadow.com
		*/
	private function pbkdf2( $algorithm, $password, $salt, $count, $key_length, $raw_output = false ) {
		
		$algorithm = strtolower($algorithm);
		
		if(!in_array($algorithm, hash_algos(), true))
			trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
		
		if($count <= 0 || $key_length <= 0)
			trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
				
	
		$hash_length = strlen(hash($algorithm, '', true));
		$block_count = ceil($key_length / $hash_length);
	
		$output = '';
		
		for($i = 1; $i <= $block_count; $i++) 
		{
			
			// $i encoded as 4 bytes, big endian.
			$last = $salt . pack("N", $i);
			
			// first iteration
			$last = $xorsum = hash_hmac($algorithm, $last, $password, true);
			
			// perform the other $count - 1 iterations
			for ($j = 1; $j < $count; $j++) 
			{
				$xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
			}
			
			$output .= $xorsum;
			
		}
	
		if($raw_output)
			return substr($output, 0, $key_length);
		else
			return bin2hex(substr($output, 0, $key_length));
			
	}
	/**
	 * Activate the domain
	 *
	 * @param array $args for the activation.
	 */
	public function activate( $args ) {
		$license = substr( $args['licence_key'], 0, 3 );
		if ( 'ktp' === $license ) {
			$productid = 'ktpl';
		} elseif ( 'ktm' === $license ) {
			$productid = 'ktm';
		} elseif ( 'ktl' === $license ) {
			$productid = 'ktl';
		} elseif ( 'esn' === $license ) {
			$productid = 'esn';
		} else {
			$productid = $this->kt_product_id;
		}
		$defaults = array(
			'wc-api'           => 'am-software-api',
			'request'          => 'activation',
			'product_id'       => $productid,
			'instance'         => $this->kt_instance_id,
			'platform'         => $this->kt_domain,
			'software_version' => $this->version,
		);
		$args = wp_parse_args( $defaults, $args );

		$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

		$request = wp_safe_remote_get( $target_url, array( 'sslverify' => false ) );
		if ( is_wp_error( $request ) ) {
			// Lets try api address.
			$new_target_url = esc_url_raw( add_query_arg( $args, $this->fallback_api_url ) );
			$request        = wp_safe_remote_get( $new_target_url, array( 'sslverify' => false ) );
			if ( is_wp_error( $request ) || 200 !== wp_remote_retrieve_response_code( $request ) ) {
				return false;
			}
		} elseif ( 200 !== wp_remote_retrieve_response_code( $request ) ) {

			return false;
		}
		$response = wp_remote_retrieve_body( $request );

		return $response;
	}
	/**
	 * Deactivate the domain
	 *
	 * @param array $args for the deactivation.
	 */
	public function deactivate( $args ) {
		$license = substr( $args['licence_key'], 0, 3 );
		if ( 'ktp' === $license ) {
			$productid = 'ktpl';
		} elseif ( 'ktm' === $license ) {
			$productid = 'ktm';
		} elseif ( 'ktl' === $license ) {
			$productid = 'ktl';
		} elseif ( 'esn' === $license ) {
			$productid = 'esn';
		} else {
			$productid = $this->kt_product_id;
		}
		$defaults = array(
			'wc-api'     => 'am-software-api',
			'request'    => 'deactivation',
			'product_id' => $productid,
			'instance'   => $this->kt_instance_id,
			'platform'   => $this->kt_domain,
		);

		$args = wp_parse_args( $defaults, $args );

		$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

		$request = wp_safe_remote_get( $target_url, array( 'sslverify'  => false ) );
		if ( is_wp_error( $request ) ) {
			// Lets try api address.
			$new_target_url = esc_url_raw( add_query_arg( $args, $this->fallback_api_url ) );
			$request        = wp_safe_remote_get( $new_target_url, array( 'sslverify' => false ) );
			if ( is_wp_error( $request ) || 200 !== wp_remote_retrieve_response_code( $request ) ) {
				return false;
			}
		} elseif ( 200 !== wp_remote_retrieve_response_code( $request ) ) {
			return false;
		}
		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	/**
	 * Checks if the software is activated or deactivated
	 *
	 * @param array $args for the status check.
	 */
	public function status( $args ) {
		$license = substr( $args['licence_key'], 0, 3 );
		if ( 'ktp' === $license ) {
			$productid = 'ktpl';
		} elseif ( 'ktm' === $license ) {
			$productid = 'ktm';
		} elseif ( 'ktl' === $license ) {
			$productid = 'ktl';
		} elseif ( 'esn' === $license ) {
			$productid = 'esn';
		} else {
			$productid = $this->kt_product_id;
		}
		$defaults = array(
			'wc-api'     => 'am-software-api',
			'request'    => 'status',
			'product_id' => $productid,
			'instance'   => $this->kt_instance_id,
			'platform'   => $this->kt_domain,
		);

		$args = wp_parse_args( $defaults, $args );

		$target_url = esc_url_raw( $this->create_software_api_url( $args ) );

		$request = wp_safe_remote_get( $target_url, array( 'sslverify'  => false ) );

		if ( is_wp_error( $request ) ) {
			// Lets try api address.
			$new_target_url = esc_url_raw( add_query_arg( $args, $this->fallback_api_url ) );
			$request        = wp_safe_remote_get( $new_target_url, array( 'sslverify' => false ) );
			if ( is_wp_error( $request ) || 200 !== wp_remote_retrieve_response_code( $request ) ) {
				return 'failed';
			}
		} elseif ( 200 !== wp_remote_retrieve_response_code( $request ) ) {
			return 'failed';
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

}
Kadence_Pro_API_Manager::instance( 'kadence_pro_activation', 'kadence_pro_api_manager_instance', 'kadence_pro_api_manager_activated', 'kadence_pro', 'Kadence Pro' );
