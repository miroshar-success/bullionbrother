<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/admin
 * @author     phoeniixx <contact@phoeniixx.com>
 */
class Phoen_login_signup_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		/* action for admin menu */
		add_action("admin_menu",array($this,"phoen_login_signup_add_menu"),99);

		/* End Add Admin script for media library */
		add_action('admin_enqueue_scripts', array($this,'phoen_my_media_lib_uploader_enqueue'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phoen_login_signup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phoen_login_signup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/phoen_login_signup-admin.css', array(), $this->version, 'all' );


		wp_enqueue_style( 'wp-color-picker');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Phoen_login_signup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Phoen_login_signup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/phoen_login_signup-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'wp-color-picker');

	}

	
	public function phoen_my_media_lib_uploader_enqueue() {
		
		wp_enqueue_media();
	 
		wp_enqueue_script( 'media-lib-uploader-js' );
	}

	public function phoen_login_signup_add_menu(){

		$page_title='Login/Signup Setting';
		
		$menu_title='Login/Signup';
		
		$capability='manage_options';
		
		$menu_slug='login_signup_settings';
		
		$function='settings_wp_login_signup';

		if ( empty ( $GLOBALS['admin_page_hooks']['phoeniixx'] ) ){
			add_menu_page( 'phoeniixx', __( 'Phoeniixx', 'phe' ), 'nosuchcapability', 'phoeniixx', NULL, PLUGINlSPDIRURL.'image/logo-wp.png', 57 );
        }

		add_submenu_page( 'phoeniixx', $page_title, $menu_title, $capability, $menu_slug, array($this,$function) );

	}


	public function settings_wp_login_signup(){ 

		(isset($_GET['tab'] ) && !empty($_GET['tab'] )) ? $tab = sanitize_text_field( $_GET['tab'] ) : $tab = ''; ?>
		
		<h2 style="text-transform: uppercase;color: #0c5777;font-size: 22px;font-weight: 700;text-align: left;display: inline-block;box-sizing: border-box;">  <?php _e('Login & Signup For Woocommerce','phoen-login-signup'); ?>
		</h2>

		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
				
			<a style="<?= ($tab == 'general' || $tab == '')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'general' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=login_signup_settings&amp;tab=general"><?= _e('General')?></a>

			<a style="<?= ($tab == 'styling-pop-up')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'styling-pop-up'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=login_signup_settings&amp;tab=styling-pop-up"><?= _e('Styling pop-up')?></a>

			<a style="<?= ($tab == 'login')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'login'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=login_signup_settings&amp;tab=login"><?= _e('Login')?></a>

			<a style="<?= ($tab == 'registration')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'registration'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=login_signup_settings&amp;tab=registration"><?= _e('Registration')?></a>

			<a style="<?= ($tab == 'shortcode')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'shortcode'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=login_signup_settings&amp;tab=shortcode"><?= _e('Shortcode / Classes')?></a>

			<a style="<?= ($tab == 'premium')?_e('background:#336374;color:white'):_e('background:white;color:black;')?>" class="nav-tab <?php if($tab == 'premium'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=login_signup_settings&amp;tab=premium"><?= _e('Premium Version')?></a>
			
		</h2> <?php $this->phoen_login_sign_up_admin_setting_tab($tab);
	}

	private function phoen_login_sign_up_admin_setting_tab($tab){

		switch ($tab) {
			case 'general':
				$this->phoen_login_sign_up_general_setting();
				break;

			case 'login':
				$this->phoen_login();
				break;

			case 'registration':
				$this->phoen_regsitartion();
				break;

			case 'styling-pop-up':
				$this->phoen_styling_pop_up();
				break;
			
			case 'shortcode':
				$this->phoen_login_sign_up_shortcode();
				break;

			case 'premium':
				$this->phoen_login_sign_up_premium_version();
				break;

			default:
				$this->phoen_login_sign_up_general_setting();
				break;
		}
	}

	private function phoen_login_sign_up_general_setting(){

		ob_start();
			include_once(PLUGINlSPDIRPATH.'admin/partials/phoen_login_signup_general_setting.php');
		$content = ob_get_contents();
		ob_end_clean();
	   		echo $content;
	}

	private function phoen_login_sign_up_shortcode(){

		ob_start();
			include_once(PLUGINlSPDIRPATH.'admin/partials/phoen_login_signup_shortcode.php');
		$content = ob_get_contents();
		ob_end_clean();
	   		echo $content;
	}

	private function phoen_login(){

		ob_start();
			include_once(PLUGINlSPDIRPATH.'admin/partials/phoen_login.php');
		$content = ob_get_contents();
		ob_end_clean();
	   		echo $content;
	}

	private function phoen_regsitartion(){

		ob_start();
			include_once(PLUGINlSPDIRPATH.'admin/partials/phoen_registration.php');
		$content = ob_get_contents();
		ob_end_clean();
	   		echo $content;
	}

	private function phoen_styling_pop_up(){

		ob_start();
			include_once(PLUGINlSPDIRPATH.'admin/partials/phoen_styling_popup.php');
		$content = ob_get_contents();
		ob_end_clean();
	   		echo $content;
		
	}

	private function phoen_login_sign_up_premium_version(){

		ob_start();
			include_once(PLUGINlSPDIRPATH.'admin/partials/phoen_login_signup_premium_version.php');
		$content = ob_get_contents();
		ob_end_clean();
	   		echo $content;
	}

}