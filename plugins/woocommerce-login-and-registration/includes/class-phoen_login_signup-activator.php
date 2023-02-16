<?php

/**
 * Fired during plugin activation
 *
 * @link       http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/includes
 * @author     phoeniixx <contact@phoeniixx.com>
 */
class Phoen_login_signup_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	}

    public static function phoen_add_by_default_login_data(){

        $lsphe_login_styling_setting =  array(

            'lsphe_pas_lnk_label'       => 'Lost Your Password ?',
            'lsphe_sheading_color'      => 'black',
            'lsphe_sn_lnk_label'        => 'Click For Register ?',
            'lsphe_sn_lnk_labelcolor'   => 'black',
            'lsphe_sign_in_text'        => 'Login',
            'lsphe_sign_in_label'       => 'Login',
        );

        update_option( '_lsphe_login_styling_setting', $lsphe_login_styling_setting );
        update_option( '_lsphe_un_lbl', 'Username or Email Address ' );
        update_option( '_lsphe_pswd_lbl', 'Password ' );
    }

    public static function phoen_add_by_default_register_data(){

        $lsphe_registration_setting_style = array(

            'lsphe_show_first_name_label'   => '0',
            'lsphe_show_last_name_label'    => '0',
            'lsphe_first_name_label'        => 'First Name ',
            'lsphe_last_name_label'         => 'Last Name ',
            'lsphe_lg_lnk_label'            => 'Click For login ?',
            'lsphe_lg_lnk_labelcolor'       => 'Black',
            'lsphe_reg_in_text'             => 'Register',
            'lsphe_reg_in_label'            => 'Register'

        );

        update_option( '_lsphe_registration_setting_style', $lsphe_registration_setting_style );
    }
}
