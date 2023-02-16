<?php

if( !empty( $_POST['submit_1'] ) && sanitize_text_field( $_POST['submit_1'] ) && current_user_can( 'manage_options' )  ){
	
	$nonce_check = sanitize_text_field( $_POST['_wpnonce_common_style'] );

	if ( ! wp_verify_nonce( $nonce_check, 'common_style' ) ) {
		
		die(  'Security check failed'  ); 
	}

	$lsphe_common_setting_style =	array(

		'lsphe_bag_color'             => sanitize_text_field( $_POST['_lsphe_bag_color'] ),
		'lsphe_border_color'          => sanitize_text_field( $_POST['_lsphe_border_color'] ),
		'lsphe_border_size'           => sanitize_text_field( $_POST['_lsphe_border_size'] ),
		'lsphe_border_style'          => sanitize_text_field( $_POST['_lsphe_border_style'] ),
		'lsphe_heading_color'         => sanitize_text_field( $_POST['_lsphe_heading_color'] ),
		'lsphe_lheading_fsz'  	      => sanitize_text_field( $_POST['_lsphe_lheading_fsz'] ),

	);

	update_option( '_lsphe_common_setting_style', $lsphe_common_setting_style );

	?><div class="updated notice is-dismissible below-h2" id="message"><p>Successfully Saved Data.</p></div><?php	
}

	$lsphe_setting_style1 =  get_option( '_lsphe_common_setting_style');

	(!empty($lsphe_setting_style1)) ? extract($lsphe_setting_style1) : '';

?>

<div class="wrap" id="profile-page" style="background:white;padding:10px;">
	
	<form action="" id="form7" method="post">

		<?php $nonce = wp_create_nonce( 'common_style' ); ?>

		<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_common_style" id="_wpnonce_common_style" />

		<table class="form-table">

			<tbody>	

				<tr class="user-user-login-wrap">

					<th><label for="_lsphe_bag_color"><?php _e("Background color",'phoen_login_signup'); ?>:</label></th>

					<td><input type="text" class="my-color-field" value="<?php echo (isset($lsphe_bag_color))?$lsphe_bag_color:''; ?>" id="_lsphe_bag_color" name="_lsphe_bag_color" /></td>

				</tr>

				<tr class="user-user-login-wrap">

					<th><label for="_lsphe_border_color"><?php _e("Border color",'phoen_login_signup'); ?>:</label></th>

					<td><input type="text" class="my-color-field" value="<?php echo (isset($lsphe_border_color))?$lsphe_border_color:''; ?>" id="_lsphe_border_color" name="_lsphe_border_color" /></td>

				</tr>
				
				<tr class="user-user-login-wrap">

					<th><label for="_lsphe_border_size"><?php _e("Border size",'phoen_login_signup'); ?>:</label></th>

					<td><input type="number" min="1" class="regular-text" value="<?php echo (isset($lsphe_border_size))?$lsphe_border_size:''; ?>" id="_lsphe_border_size" name="_lsphe_border_size" /><?php _e('px','phoen_login_signup'); ?> </td>

				</tr>
				
				<tr class="user-user-login-wrap">

					<th><label for="_lsphe_border_style"><?php _e("Border style",'phoen_login_signup'); ?>:</label></th>

					<td>
						<label for="_lsphe_border_style1"><input type="radio" name="_lsphe_border_style" value="none" <?php echo ((isset($lsphe_border_style)) && ($lsphe_border_style == 'none'))? "checked": '' ; ?> id="_lsphe_border_style1"><?php _e('none','phoen_login_signup'); ?></label>
						<label for="_lsphe_border_style2"><input type="radio" name="_lsphe_border_style" value="solid" <?php echo ((isset($lsphe_border_style)) && ($lsphe_border_style == 'solid'))? "checked": '' ; ?> id="_lsphe_border_style2"><?php _e('solid','phoen_login_signup'); ?></label>
						<label for="_lsphe_border_style3"><input type="radio" name="_lsphe_border_style" value="dashed" <?php echo ((isset($lsphe_border_style)) && ($lsphe_border_style == 'dashed'))? "checked": '' ; ?> id="_lsphe_border_style3"><?php _e('dashed','phoen_login_signup'); ?></label>
						<label for="_lsphe_border_style4"><input type="radio" name="_lsphe_border_style" value="dotted" <?php echo ((isset($lsphe_border_style)) && ($lsphe_border_style == 'dotted'))? "checked": '' ; ?> id="_lsphe_border_style4"><?php _e('dotted','phoen_login_signup'); ?></label>
					</td>

				</tr>


				<tr class="user-user-login-wrap">

					<th><label for="_lsphe_heading_color"><?php _e("Popup heading Color",'phoen_login_signup'); ?>:</label></th>

					<td><input type="text" class="my-color-field" value="<?php echo (isset($lsphe_heading_color))?$lsphe_heading_color:''; ?>" id="_lsphe_heading_color" name="_lsphe_heading_color" /></td>

				</tr>
				
				<tr class="user-user-login-wrap">

					<th><label for="_lsphe_lheading_fsz"><?php _e("Popup heading font size",'phoen_login_signup'); ?>:</label></th>

					<td><input type="number" min="1" class="regular-text" value="<?php echo (isset($lsphe_lheading_fsz))?$lsphe_lheading_fsz:''; ?>" id="_lsphe_lheading_fsz" name="_lsphe_lheading_fsz" /> <?php _e('px','phoen_login_signup'); ?></td>

				</tr>
		
	   		</tbody>

		</table>

		<br/><input type="submit" class="button button-primary" id="submit1" name="submit_1" value="Save" />

	</form>
</div>