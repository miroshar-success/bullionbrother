<?php

if( !empty( $_POST['submit_1'] ) && sanitize_text_field( $_POST['submit_1'] ) && current_user_can( 'manage_options' )  ){
	
	$nonce_check = sanitize_text_field( $_POST['_wpnonce_registration'] );

	if ( ! wp_verify_nonce( $nonce_check, 'registration' ) ) {
		
		die(  'Security check failed'  ); 
	}

	/* Save Registration styling */

	$shwo_first_name_label = isset($_POST['_lsphe_show_first_name_label']) ? sanitize_text_field( $_POST['_lsphe_show_first_name_label'] ):'';

	$shwo_last_name_label = isset($_POST['_lsphe_show_last_name_label']) ? sanitize_text_field( $_POST['_lsphe_show_last_name_label'] ):'';

	$first_name_label = isset($_POST['phoen_login_signup_first_name_label']) ? sanitize_text_field( $_POST['phoen_login_signup_first_name_label'] ):'';

	$last_name_label = isset($_POST['phoen_login_signup_last_name_label']) ? sanitize_text_field( $_POST['phoen_login_signup_last_name_label'] ):'';

	$login_link_label = isset($_POST['phoen_login_signup_login_link_label']) ? sanitize_text_field( $_POST['phoen_login_signup_login_link_label'] ):'';

	$link_label_color = isset($_POST['phoen_login_signup_login_link_label_color']) ? sanitize_text_field( $_POST['phoen_login_signup_login_link_label_color'] ):'';

	$popup_title_text = isset($_POST['phoen_login_signup_register_popup_title_text']) ? sanitize_text_field( $_POST['phoen_login_signup_register_popup_title_text'] ):'';

	$register_button_label = isset($_POST['phoen_login_signup_register_button_label']) ? sanitize_text_field( $_POST['phoen_login_signup_register_button_label'] ):'';

	$lsphe_registration_setting_style =	array(

		'lsphe_show_first_name_label'   => $shwo_first_name_label,
		'lsphe_show_last_name_label'    => $shwo_last_name_label,
		'lsphe_first_name_label' 		=> $first_name_label,
		'lsphe_last_name_label'  		=> $last_name_label,
		'lsphe_lg_lnk_label'			=> $login_link_label,
		'lsphe_lg_lnk_labelcolor'		=> $link_label_color,
		'lsphe_reg_in_text'				=> $popup_title_text,
		'lsphe_reg_in_label'			=> $register_button_label

	);

	update_option( '_lsphe_registration_setting_style', $lsphe_registration_setting_style );

	?><div class="updated notice is-dismissible below-h2" id="message"><p>Successfully Saved Data.</p></div><?php
}

	$lsphe_registration_styling_setting = get_option( '_lsphe_registration_setting_style');

?>

<div class="wrap" id="profile-page" style="background:white;padding:10px;">
	
	<form action="" id="form7" method="post">

		<?php $nonce = wp_create_nonce( 'registration' ); ?>

		<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_registration" id="_wpnonce_registration" />

		<table class="form-table">

			<tbody>		

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Show first name field label :'))?></th>
				
					<td>
						<?php 

							$show_first_name_label = $lsphe_registration_styling_setting['lsphe_show_first_name_label'] ;

							$show_last_name_label = $lsphe_registration_styling_setting['lsphe_show_last_name_label'] ;
						?>

						<label for="_lsphe_show_first_name_label2">
						
							<input type="radio" name="_lsphe_show_first_name_label" value="1" id="_lsphe_show_first_name_label2" <?php echo ($show_first_name_label == '1') ? "checked" : "" ;  ?> ><?php _e('Yes','phoen_login_signup');?>
					
						</label>

						<label for="_lsphe_show_first_name_label1">

							<input type="radio" name="_lsphe_show_first_name_label" value="0" id="_lsphe_show_first_name_label1" <?php echo ($show_first_name_label == '0')?'checked':'';?> > <?php _e('No','phoen_login_signup'); ?>

						</label>
					</td>

				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Show last name field label :'))?></th>
				
					<td>
						<label for="_lsphe_show_last_name_label2">

							<input type="radio" name="_lsphe_show_last_name_label" value="1" id="_lsphe_show_last_name_label2" <?php echo ($show_last_name_label == '1') ? "checked" : "" ;  ?> ><?php _e('Yes','phoen_login_signup'); ?>

						</label>

						<label for="_lsphe_show_last_name_label1">

							<input type="radio" name="_lsphe_show_last_name_label" value="0" id="_lsphe_show_last_name_label1" <?php echo ($show_last_name_label == '0') ? "checked" : "" ;  ?> ><?php _e('No','phoen_login_signup'); ?>

						</label>
					</td>

				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('First name field label :'))?></th>
					<td>
						<?php $first_name_label = (!empty($lsphe_registration_styling_setting['lsphe_first_name_label']))?$lsphe_registration_styling_setting['lsphe_first_name_label']:''; ?>
						<input type="text" value="<?= _e($first_name_label)?>" name="phoen_login_signup_first_name_label" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('last name field label :'))?></th>
					<td>
						<?php $last_name_label = (!empty($lsphe_registration_styling_setting['lsphe_last_name_label']))?$lsphe_registration_styling_setting['lsphe_last_name_label']:''; ?>
						<input type="text" value="<?= _e($last_name_label)?>" name="phoen_login_signup_last_name_label" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Login link label :'))?></th>
					<td>
						<?php $login_link_label = (!empty($lsphe_registration_styling_setting['lsphe_lg_lnk_label']))?$lsphe_registration_styling_setting['lsphe_lg_lnk_label']:''; ?>
						<input type="text" value="<?= _e($login_link_label)?>" name="phoen_login_signup_login_link_label" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Login link label color :'))?></th>
					<td>
						<?php $login_link_label_color = (!empty($lsphe_registration_styling_setting['lsphe_lg_lnk_labelcolor']))?$lsphe_registration_styling_setting['lsphe_lg_lnk_labelcolor']:''; ?>
						<input type="text" value="<?= _e($login_link_label_color)?>" class="my-color-field" name="phoen_login_signup_login_link_label_color" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Register popup title text :'))?></th>
					<td>
						<?php $regsiter_popup_title_text = (!empty($lsphe_registration_styling_setting['lsphe_reg_in_text']))?$lsphe_registration_styling_setting['lsphe_reg_in_text']:''; ?>
						<input type="text" value="<?= _e($regsiter_popup_title_text)?>" name="phoen_login_signup_register_popup_title_text" />
					</td>
				</tr>

				<tr class="user-nickname-wrap">
					<th><?= _e(ucwords('Register button label :'))?></th>
					<td>
						<?php $regsiter_button_label = (!empty($lsphe_registration_styling_setting['lsphe_reg_in_label']))?$lsphe_registration_styling_setting['lsphe_reg_in_label']:''; ?>
						<input type="text" value="<?= _e($regsiter_button_label)?>" name="phoen_login_signup_register_button_label" />
					</td>
				</tr>
		
	   		</tbody>

		</table>

		<br/><input type="submit" class="button button-primary" id="submit1" name="submit_1" value="Save" />

	</form>

</div>