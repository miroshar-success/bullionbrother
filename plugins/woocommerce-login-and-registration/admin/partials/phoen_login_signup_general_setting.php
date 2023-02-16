<?php if( isset( $_POST['submit_1'] ) ){
	
	$nonce_check = sanitize_text_field( $_POST['_wpnonce_login_signup_setting'] );

	if ( ! wp_verify_nonce( $nonce_check, 'login_signup_setting' ) ) {
		
		die(  'Security check failed'  ); 
	}

	$enable_tncond = isset($_POST['phoen_login_signup_terms_and_condition']) ? sanitize_text_field($_POST['phoen_login_signup_terms_and_condition']):'off';

	$auto_load_popupp = isset($_POST['phoen_login_signup_auto_load_home_page']) ? sanitize_text_field($_POST['phoen_login_signup_auto_load_home_page']):'off';


	($auto_load_popupp == 'on') ? update_option('_lsphe_auto_load_popup', 'on' ) : update_option('_lsphe_auto_load_popup', 'off' );

	($enable_tncond == 'on') ? update_option('_lsphe_enable_tncond', 'on' ) : update_option('_lsphe_enable_tncond', 'off' ); 

	?><div class="updated notice is-dismissible below-h2" id="message"><p>Successfully Saved Data.</p></div><?php
} ?>

<div class="wrap" id="profile-page" style="background:white;padding:10px;">

	<!-- <h3 style="color:#0c5777;border-bottom: 1px solid #dc9999; margin: 0;font-size: 20px; text-transform: uppercase;padding: 5px;"><?= _e('General Setting')?></h3> -->
	
	<form action="" id="form7" method="post">

		<?php $nonce = wp_create_nonce( 'login_signup_setting' ); ?>

		<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_login_signup_setting" id="_wpnonce_login_signup_setting" />

		<table class="form-table">

			<tbody>		

				<tr class="user-nickname-wrap">
					<th><?= _e('Enable Terms & Conditions :')?></th>
					<?php $enable_tncond 	  = get_option( '_lsphe_enable_tncond' ); ?>
					<?php $check_enable_tncond = ($enable_tncond == 'on') ? 'checked' :''; ?>
					<td>
						<input type="checkbox" id="popup2" name="phoen_login_signup_terms_and_condition" <?php _e($check_enable_tncond)?>>
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Enable Auto load popup on Home page :'))?></th>
					<?php $auto_load_popupp   = get_option( '_lsphe_auto_load_popup' );?>
					<?php $check_auto_load_popupp = ($auto_load_popupp == 'on') ? 'checked' :''; ?>
					<td>
						<input type="checkbox" id="popup3" name="phoen_login_signup_auto_load_home_page" <?php _e($check_auto_load_popupp)?>>
					</td>
				</tr>

				
		
	   		</tbody>

		</table>

		<br/><input type="submit" class="button button-primary" id="submit1" name="submit_1" value="Save" />

	</form>

</div>