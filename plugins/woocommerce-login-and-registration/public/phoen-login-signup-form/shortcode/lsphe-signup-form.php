<?php

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register']) && sanitize_text_field( $_POST['register'] ) ){

	$get_nonce_value = isset($_POST['_wpnonce_phoe_register_form'])?sanitize_text_field( $_POST['_wpnonce_phoe_register_form'] ):'';

	$check_nonce =  wp_verify_nonce( $get_nonce_value, 'phoe_register_form' );

	if ( ! $check_nonce ) {
		
		die(   'Security check failed'  ); 
	}
	
	$registrated_email = isset($_POST['email'])?sanitize_email($_POST['email']):'';
	
	$registrated_password = isset($_POST['password'])? sanitize_text_field($_POST['password']):'';
	
	$registrated_first_name = isset($_POST['first_name'])? sanitize_text_field($_POST['first_name']):'';

	$registrated_last_name = isset($_POST['last_name'])? sanitize_text_field($_POST['last_name']):'';
	
	$get_user_name = explode("@",$registrated_email);  
	
	$temp = $get_user_name[0];
	
	$user = get_user_by( 'email',$registrated_email );			   
    
	if($registrated_email == ''){
		
		echo $this->phoen_login_signup_error_message('<strong>Error :</strong> Please provide a valid email address.');
	}
	
	else if($registrated_password == ''){
	
		echo $this->phoen_login_signup_error_message('<strong>Error :</strong> Please enter an account password.');
    }
	else{
		
		if(is_email($registrated_email)){ 	
			
			if(is_object($user) && $user->user_email == $registrated_email){
			
				echo $this->phoen_login_signup_error_message('<strong>Error :</strong> An account is already registered with your email address. Please login.');
			}
		    else{             
				
				if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && empty( $registrated_password ) ) {
					
						$registrated_password = wp_generate_password();
						
						$password_generated = true;

					} elseif ( empty( $registrated_password ) ) {
						
						return new WP_Error( 'registration-error-missing-password', __( 'Please enter an account password.', 'woocommerce' ) );

					} else {
						
						$password_generated = false;
						
					}
					
				$userdata=array(

								"role"=>"customer",
				
								"user_email"=>$registrated_email,
								
								"user_login"=>$temp,
								
								"user_pass"=>$registrated_password,

								"user_first_name"=>$registrated_first_name,

								"user_last_name"=>$registrated_last_name
							);
				
				if($user_id = wp_insert_user( $userdata )){ 
					
					do_action('woocommerce_created_customer', $user_id, $userdata, $password_generated);
					
					$user1 = get_user_by('id',$user_id);
				    
					wp_set_current_user( $user1->ID, $user1->user_login );
								   
				    wp_set_auth_cookie( $user1->ID );
				   
				    do_action( 'wp_login', $user1->user_login,$user1 );
				   
				    $location = home_url()."/my-account/"; 
					wp_redirect($location);
				    exit;												 
				}
				
			}
			
		}
		else{
			
			echo $this->phoen_login_signup_error_message('<strong>Error :</strong> Please provide a valid email address.');	
		} 
	}
}
	$lsphe_registration_styling_setting = get_option( '_lsphe_registration_setting_style');
?>

<div class="woocommerce">	

	<div class="col-set" id="customer_login">

		<div class="col">
			
			<h2><?= (!empty($lsphe_registration_styling_setting['lsphe_reg_in_text']))?_e($lsphe_registration_styling_setting['lsphe_reg_in_text']):_e('Register')?></h2>
			
			<form method="post" class="register">	

				<?php $nonce_register = wp_create_nonce( 'phoe_register_form' ); ?>
					
				<input type="hidden" value="<?php echo $nonce_register; ?>" name="_wpnonce_phoe_register_form" id="_wpnonce_phoe_register_form" />

				<?php if($lsphe_registration_styling_setting['lsphe_show_first_name_label'] == '1'): ?>

				<p class="form-row form-row-wide">
					<label for="registrated_first_name"><?= (!empty($lsphe_registration_styling_setting['lsphe_first_name_label'])) ? $lsphe_registration_styling_setting['lsphe_first_name_label'] :_e('First Name'); ?><span class="required">*</span></label>
					<input type="text" class="input-text" required name="first_name" id="registrated_first_name" value="" >
				</p>

				<?php endif; ?>
				<?php if($lsphe_registration_styling_setting['lsphe_show_last_name_label'] == '1'): ?>

				<p class="form-row form-row-wide">
					<label for="registrated_last_name"><?= (!empty($lsphe_registration_styling_setting['lsphe_last_name_label'])) ? $lsphe_registration_styling_setting['lsphe_last_name_label'] :_e('Last Name'); ?><span class="required">*</span></label>
					<input type="text" class="input-text" required name="last_name" id="registrated_last_name" value="" >
				</p>

				<?php endif; ?>
			
				<p class="form-row form-row-wide">
					<label for="registrated_email"><?= _e('Email Address','phoen-login-signup')?><span class="required">*</span></label>
					<input type="email" class="input-text" required name="email" id="registrated_email" value="<?php echo isset( $registrated_email ) ? $registrated_email: '' ; ?>" >
				</p>	

				<p class="form-row form-row-wide">
					<label for="registrated_password"><?= _e('Password','phoen-login-signup')?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password" id="registrated_password " >
				</p>	

				<div style="left: -999em; position: absolute;"><label for="trap"><?= _e('Anti-spam','phoen-login-signup')?></label><input type="text" name="email_2" id="trap" tabindex="-1"></div>						
				
				<p class="form-row">
					<input type="hidden" id="_wpnonce" name="_wpnonce" value="70c2c9e9dd"><input type="hidden" name="_wp_http_referer" value="<?php echo get_site_url(); ?>/my-account/">				
					
					<input type="submit" class="button" name="register" value="<?= (!empty($lsphe_registration_styling_setting['lsphe_reg_in_label']))?_e($lsphe_registration_styling_setting['lsphe_reg_in_label']):_e('Register')?>">
				</p>
				
			</form>

		</div>

	</div>

</div>