<?php

	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login']) && sanitize_text_field( $_POST['login'] ) ):

		global $wpdb;

		$get_nonce_value = isset($_POST['_wpnonce_phoe_login_form']) ? sanitize_text_field($_POST['_wpnonce_phoe_login_form']) : '';


		$check_nonce = wp_verify_nonce($get_nonce_value,'phoe_login_form');

		if(!$check_nonce):

			die('Security check failed');
		
		else:

			$username = isset($_POST['username'])?sanitize_text_field($_POST['username']):'';
			
			$password = isset($_POST['password'])?$_POST['password']:'';

			$checkbox_terms = isset($_POST['checkbox_terms']) ? sanitize_text_field($_POST['checkbox_terms']):'';
			
			$remember = isset($_POST['rememberme'])?sanitize_text_field($_POST['rememberme']):'';
			
			$remember = ( $remember ) ? 'true' : 'false';

			if(empty($username) || $username == 'null'):

				echo $this->phoen_login_signup_error_message('<strong>Error : </strong> Username is Required Field');

			elseif(empty($password) || $password == 'null'):

				echo $this->phoen_login_signup_error_message('<strong>Error : </strong> Password is Required Field');

			elseif($checkbox_terms  != 'on' && get_option('_lsphe_enable_tncond') =='on'):

				echo $this->phoen_login_signup_error_message('<strong>Error : </strong> Terms & Condition is Required Field');

			else:

				if(is_email($username)):

					$user = get_user_by('email',$username);

					if($user):

						if(wp_check_password($password,$user->user_pass)):

							wp_set_current_user( $user->ID, $user->user_login );
							
							wp_set_auth_cookie( $user->ID );
							
							do_action( 'wp_login', $user->user_login ,$user);
							
							$location = home_url()."/my-account/";
							
							wp_redirect( $location );
						
							exit;

						else:

							$print = "<strong>Error : </strong> The password you have entered for <strong>".$user->user_login." </strong> is incorrect.";

							echo $this->phoen_login_signup_error_message($print);

						endif;

					else:

						echo $this->phoen_login_signup_error_message('<strong>Error : </strong> A user could not be found with this email address.');

					endif;

				else:

					$login_data = array();

					$login_data['user_login'] = $username;

					$login_data['user_password'] = $password;

					$login_data['remember'] = $remember;
	
					$user_verify = wp_signon($login_data,false);

					if(is_wp_error($user_verify)):

						echo $this->phoen_login_signup_error_message($user_verify->get_error_message()); 

					else:

						wp_set_current_user( $user_verify->ID, $user_verify->user_login );
					
						wp_set_auth_cookie( $user_verify->ID );
						
						do_action( 'wp_login', $user_verify->user_login, $user_verify);
						
						$location = home_url();  
						
						wp_redirect( $location );
						
						exit;

					endif;

				endif;

			endif;

		endif;

	endif;


		$lsphe_login_styling_setting 		= get_option( '_lsphe_login_styling_setting');
		$login_username_field_label  		= get_option( '_lsphe_un_lbl');
		$login_password_field_label  		= get_option( '_lsphe_pswd_lbl');
?>

<div class="woocommerce">

	<div class="col-set" id="customer_login">
				
		<div class="col">
		
			<h2><?= (!empty($lsphe_login_styling_setting['lsphe_sign_in_text']))?_e($lsphe_login_styling_setting['lsphe_sign_in_text']):'Login'?></h2>
			
			<form method="post" class="login">
			
				<?php $nonce = wp_create_nonce( 'phoe_login_form' ); ?>
					
				<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_phoe_login_form" id="_wpnonce_phoe_login_form" />

				<p class="form-row form-row-wide">

					<label for="username"><?php (!empty($login_username_field_label))?_e($login_username_field_label):_e('Username or email address ')?><span class="required">*</span></label>

					<input type="text" class="input-text" name="username" id="username" value="<?php echo isset( $username ) ? $username: '' ; ?>" required>
				</p>

				<p class="form-row form-row-wide">

					<label for="password"> <?php (!empty($login_password_field_label))?_e($login_password_field_label):_e('Password')?> <span class="required">*</span></label>

					<input class="input-text" type="password" name="password" id="password" required>
				</p>

				<?php if(get_option('_lsphe_enable_tncond') == 'on'): ?>

                    <p class="form-row" style="display:inline-flex;">
                        <input style="margin-top: 3px;" type="checkbox" id="checkbox1" name="checkbox_terms" class="input-checkbox">
                        <label for="remember-me">By creating an account you agree to our 
                        <a  href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('terms')) ); ?>" target="_blank"><?php _e( 'Terms & Conditions', 'phoen_login_signup' ); ?></a></label>
                    </p>
                <?php endif; ?>

				<p class="form-row">
					<input type="hidden" id="_wpnonce" name="_wpnonce" value="fd684f83cf">
					
					<input type="hidden" name="_wp_http_referer" value="<?php echo get_site_url(); ?>/my-account/">				
					
					<input type="submit" class="button" id="login" name="login" value="<?= (!empty($lsphe_login_styling_setting['lsphe_sign_in_label']))?_e($lsphe_login_styling_setting['lsphe_sign_in_label']):'Login'?>">
				</p>



				<p class="lost_password">

					<a style="color:<?= (!empty($lsphe_login_styling_setting['lsphe_sheading_color']))?_e($lsphe_login_styling_setting['lsphe_sheading_color']):'black';?>" href="<?php echo get_site_url(); ?>/my-account/lost-password/"><?php (!empty($lsphe_login_styling_setting['lsphe_pas_lnk_label']))?_e($lsphe_login_styling_setting['lsphe_pas_lnk_label']): _e('Lost your password ?')?></a>
				</p>

			</form>

		</div>
	
	</div>

</div>