<?php

if( !empty( $_POST['submit_1'] ) && sanitize_text_field( $_POST['submit_1'] ) && current_user_can( 'manage_options' )  ){
	
	$nonce_check = sanitize_text_field( $_POST['_wpnonce_login'] );

	if ( ! wp_verify_nonce( $nonce_check, 'login' ) ) {
		
		die(  'Security check failed'  ); 
	}


	$login_username_field_label = isset($_POST['phoen_login_signup_username_field_label']) ? sanitize_text_field( $_POST['phoen_login_signup_username_field_label'] ):'';

	$login_password_field_label = isset($_POST['phoen_login_signup_password_field_label']) ? sanitize_text_field( $_POST['phoen_login_signup_password_field_label'] ):'';

	$login_forget_password_link_label = isset($_POST['phoen_login_signup_forget_password_link_label']) ? sanitize_text_field( $_POST['phoen_login_signup_forget_password_link_label'] ):'';

	$login_forget_password_label_color = isset($_POST['phoen_login_signup_forget_password_label_color']) ? sanitize_text_field( $_POST['phoen_login_signup_forget_password_label_color'] ):'';

	$login_register_link_label = isset($_POST['phoen_login_signup_register_link_label']) ? sanitize_text_field( $_POST['phoen_login_signup_register_link_label'] ):'';

	$login_register_link_label_color = isset($_POST['phoen_login_signup_register_link_label_color']) ? sanitize_text_field( $_POST['phoen_login_signup_register_link_label_color'] ):'';

	$login_popup_text_title = isset($_POST['phoen_login_signup_login_popup_text_title']) ? sanitize_text_field( $_POST['phoen_login_signup_login_popup_text_title'] ):'';

	$login_button_label = isset($_POST['phoen_login_signup_login_button_label']) ? sanitize_text_field( $_POST['phoen_login_signup_login_button_label'] ):'';

	/* Save Login Styling */

	$lsphe_login_styling_setting =	array(

		'lsphe_pas_lnk_label'       => $login_forget_password_link_label,
		'lsphe_sheading_color'      => $login_forget_password_label_color,
		'lsphe_sn_lnk_label'     	=> $login_register_link_label,
		'lsphe_sn_lnk_labelcolor'   => $login_register_link_label_color,
		'lsphe_sign_in_text'		=> $login_popup_text_title,
		'lsphe_sign_in_label'       => $login_button_label,
	);

	update_option( '_lsphe_login_styling_setting', $lsphe_login_styling_setting );
	update_option( '_lsphe_un_lbl', $login_username_field_label );
	update_option( '_lsphe_pswd_lbl', $login_password_field_label );

	?><div class="updated notice is-dismissible below-h2" id="message"><p>Successfully Saved Data.</p></div><?php
}

	$lsphe_login_styling_setting 		= get_option( '_lsphe_login_styling_setting');
	$login_username_field_label  		= get_option( '_lsphe_un_lbl');
	$login_password_field_label  		= get_option( '_lsphe_pswd_lbl');

?>
<div class="wrap" id="profile-page" style="background:white;padding:10px;">
	
	<form action="" id="form7" method="post">

		<?php $nonce = wp_create_nonce( 'login' ); ?>

		<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_login" id="_wpnonce_login" />

		<table class="form-table">

			<tbody>	

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Username field label :'))?></th>
					<td>
						<?php $get_username_field_data = (isset($login_username_field_label))?$login_username_field_label:""; ?>
						<input type="text" value="<?= _e($get_username_field_data)?>" name="phoen_login_signup_username_field_label" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Password field label :'))?></th>
					<td>
						<?php $get_password_field_data = (isset($login_password_field_label))?$login_password_field_label:""; ?>
						<input type="text" value="<?= _e($get_password_field_data)?>" name="phoen_login_signup_password_field_label" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Forget password link label :'))?></th>
					<td>

						<?php $forget_password_link_label = (isset($lsphe_login_styling_setting['lsphe_pas_lnk_label']))?$lsphe_login_styling_setting['lsphe_pas_lnk_label']:""; ?>
						<input type="text" value="<?= _e($forget_password_link_label)?>" name="phoen_login_signup_forget_password_link_label" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Forget password label color :'))?></th>
					<td>
						<?php $forget_password_label_color = (isset($lsphe_login_styling_setting['lsphe_sheading_color']))?$lsphe_login_styling_setting['lsphe_sheading_color']:""; ?>
						<input type="text" value="<?= _e($forget_password_label_color)?>" class="my-color-field" name="phoen_login_signup_forget_password_label_color" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Register link label :'))?></th>
					<td>
						<?php $register_link_label = (isset($lsphe_login_styling_setting['lsphe_sn_lnk_label']))?$lsphe_login_styling_setting['lsphe_sn_lnk_label']:""; ?>
						<input type="text" value="<?= _e($register_link_label)?>" name="phoen_login_signup_register_link_label" />
					</td>
				</tr>
				
				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Register link label color :'))?></th>
					<td>
						<?php $register_link_label_color = (isset($lsphe_login_styling_setting['lsphe_sn_lnk_labelcolor']))?$lsphe_login_styling_setting['lsphe_sn_lnk_labelcolor']:""; ?>
						<input type="text" value="<?= _e($register_link_label_color)?>" class="my-color-field" name="phoen_login_signup_register_link_label_color" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Login popup title text :'))?></th>
					<td>
						<?php $popup_text_title = (isset($lsphe_login_styling_setting['lsphe_sign_in_text']))?$lsphe_login_styling_setting['lsphe_sign_in_text']:""; ?>
						<input type="text" value="<?= _e($popup_text_title)?>" name="phoen_login_signup_login_popup_text_title" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Login button label :'))?></th>
					<td>
						<?php $login_button_label = (isset($lsphe_login_styling_setting['lsphe_sign_in_label']))?$lsphe_login_styling_setting['lsphe_sign_in_label']:""; ?>
						<input type="text" value="<?= _e($login_button_label)?>" name="phoen_login_signup_login_button_label" />
					</td>
				</tr>
		
	   		</tbody>

		</table>

		<br/><input type="submit" class="button button-primary" id="submit1" name="submit_1" value="Save" />

	</form>

</div>