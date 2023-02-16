<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_Settings();

/*
 * Redeem Settings Template
 */
if ( isset( $_POST['wcgm_generate_offine_redeem_url'] ) ) {
	if ( isset( $_REQUEST['wps-redeem-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-redeem-nonce'] ) ), 'wps-redeem-nonce' ) ) {
		global $woocommerce;
		$client_name = isset( $_POST['wcgm_offine_redeem_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wcgm_offine_redeem_name'] ) ) : '';
		$client_email = isset( $_POST['wcgm_offine_redeem_email'] ) ? sanitize_text_field( wp_unslash( $_POST['wcgm_offine_redeem_email'] ) ) : '';
		$enable = isset( $_POST['wcgm_offine_redeem_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['wcgm_offine_redeem_enable'] ) ) : '';
		$client_license_code = get_option( 'wps_gw_lcns_key', '' );
		$client_domain = home_url();
		$currency = get_option( 'woocommerce_currency' );
		$client_currency = get_woocommerce_currency_symbol();
		$curl_data = array(
			'user_name' => $client_name,
			'email' => $client_email,
			'license' => $client_license_code,
			'domain' => $client_domain,
			'currency' => $client_currency,
		);
		$redeem_data = get_option( 'giftcard_offline_redeem_link', true );
		$url = 'https://gifting.wpswings.com/api/generate';
		$response = wp_remote_post(
			$url,
			array(
				'method'     => 'POST',
				'timeout'    => 50,
				'user-agent' => '',
				'sslverify'  => false,
				'body'       => $curl_data,
			)
		);
		if ( is_array( $response ) && ! empty( $response ) ) {
			$response = $response['body'];
			$response = json_decode( $response );
			if ( 'error' == $response->status ) {
				$wps_wgm_error_message = $response->message;
			} else {
				if ( isset( $response->status ) && 'success' == $response->status ) {
					$wps_redeem_link['shop_url'] = $response->shop_url;
					$wps_redeem_link['embed_url'] = $response->embed_url;
					$wps_redeem_link['user_id'] = $response->user_id;
					update_option( 'giftcard_offline_redeem_link', $wps_redeem_link );
				}
			}
		}
		update_option( 'giftcard_offline_redeem_settings', $curl_data );
	}
} else if ( isset( $_POST['remove_giftcard_redeem_details'] ) ) {
	if ( isset( $_REQUEST['wps-remove-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-remove-nonce'] ) ), 'wps-remove-nonce' ) ) {
		global $woocommerce;
		$offine_giftcard_redeem_details = get_option( 'giftcard_offline_redeem_link' );
		$userid = $offine_giftcard_redeem_details['user_id'];
		$client_domain = home_url();
		$url = 'https://gifting.wpswings.com/api/generate/remove';

		$curl_data = array(
			'user_id' => $userid,
			'domain' => $client_domain,
		);

		$response = wp_remote_post(
			$url,
			array(
				'timeout' => 50,
				'user-agent' => '',
				'sslverify' => false,
				'body' => $curl_data,
			)
		);

		if ( is_array( $response ) && ! empty( $response ) ) {
			$response = $response['body'];
			$response = json_decode( $response );
			if ( 'error' == $response->status ) {
				$wps_wgm_error_message = $response->message;
			} else {
				if ( isset( $response->status ) && 'success' == $response->status ) {
					delete_option( 'giftcard_offline_redeem_link' );
					delete_option( 'giftcard_offline_redeem_settings' );
				}
			}
		}
	}
} else if ( isset( $_POST['update_giftcard_redeem_details'] ) ) {
	$offine_giftcard_redeem_details = get_option( 'giftcard_offline_redeem_link' );
	$offine_giftcard_redeem_settings = get_option( 'giftcard_offline_redeem_settings' );
	$userid = $offine_giftcard_redeem_details['user_id'];
	$client_domain = home_url();
	$url = 'https://gifting.wpswings.com/api/generate/update';
	$client_license_code = get_option( 'wps_gw_lcns_key', '' );
	if ( ( isset( $offine_giftcard_redeem_settings['license'] ) && '' === $offine_giftcard_redeem_settings['license'] ) && ( isset( $offine_giftcard_redeem_settings['domain'] ) && home_url() !== $offine_giftcard_redeem_settings['domain'] ) ) {
		$request_type = 'both';
	} elseif ( isset( $offine_giftcard_redeem_settings['domain'] ) && home_url() !== $offine_giftcard_redeem_settings['domain'] ) {
		$request_type = 'domainupdate';
	} elseif ( isset( $offine_giftcard_redeem_settings['license'] ) && '' === $offine_giftcard_redeem_settings['license'] ) {
		$request_type = 'licenseupdate';
	}

	if ( '' !== $client_license_code ) {
		$curl_data = array(
			'user_id' => $userid,
			'domain' => $client_domain,
			'license' => $client_license_code,
			'request_type' => $request_type,
		);

		$response = wp_remote_post(
			$url,
			array(
				'timeout' => 50,
				'user-agent' => '',
				'sslverify' => false,
				'body' => $curl_data,
			)
		);
		if ( is_array( $response ) && ! empty( $response ) ) {
			$response = $response['body'];
			$response = json_decode( $response );
			if ( 'error' == $response->status ) {
				$wps_wgm_error_message = $response->message;
			} else {
				if ( isset( $response->status ) && 'success' == $response->status ) {
					$offine_giftcard_redeem_settings['license'] = $client_license_code;
					$offine_giftcard_redeem_settings['domain']  = $client_domain;
					update_option( 'giftcard_offline_redeem_settings', $offine_giftcard_redeem_settings );
				}
			}
		}
	}
}
$wps_current_user = wp_get_current_user();
$offine_giftcard_redeem_link = get_option( 'giftcard_offline_redeem_link', true );
$offine_giftcard_redeem_settings = get_option( 'giftcard_offline_redeem_settings', true );
if ( isset( $wps_wgm_error_message ) && null !== $wps_wgm_error_message ) {
	?>
<div class="notice notice-success is-dismissible"> 
	<p><strong>
	<?php
	echo wp_kses_post( $wps_wgm_error_message );
	?>
	</strong></p>
	<button type="button" class="notice-dismiss">
		<span class="screen-reader-text"><?php echo wp_kses_post( 'Dismiss this notice', 'woo-gift-cards-lite' ); ?></span>
	</button>
</div>
	<?php
}
?>
<h3 class="wps_wgm_overview_heading text-center"><?php esc_html_e( 'Gift Card  Redeem / Recharge ', 'woo-gift-cards-lite' ); ?></h3>
<div class="wps_table">
	<div style="display: none;" class="loading-style-bg" id="wps_wgm_loader">
		<img src="<?php echo esc_url( WPS_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<div class="wps_redeem_div_wrapper">
		<?php if ( ! isset( $offine_giftcard_redeem_link ['shop_url'] ) || '' == $offine_giftcard_redeem_link['shop_url'] ) { ?>
			<div>
				<div class="wps-giftware-reddem-image text-center">

					<img src="<?php echo esc_url( WPS_WGC_URL . 'assets/images/giftware-redeem-image.png' ); ?>" alt="GiftWare">
					<div class="wps_giftware_reddem_link_wrapper">
						<a href="#" class="generate_link"><i class="fas fa-link"></i><?php esc_html_e( 'Get me My FREE redeem Link', 'woo-gift-cards-lite' ); ?> </a>
						<span><?php esc_html_e( '(you can delete your redeem link anytime)', 'woo-gift-cards-lite' ); ?></span>
					</div>
				</div>

				<div class="wps_redeem_main_content">
					<h3 class="text-left"><?php esc_html_e( 'Hello Dear', 'woo-gift-cards-lite' ); ?></h3>	
					<p><?php esc_html_e( 'We are thrilled to announce that we have launched a FREE service to simplify the problem of redeeming gift cards at a retail store', 'woo-gift-cards-lite' ); ?></p>

					<p><?php esc_html_e( 'We have made this just on your demand so we would love your suggestion to improve it.', 'woo-gift-cards-lite' ); ?></p>
				</div>

				
				<h3 class="text-center"><?php esc_html_e( 'What it Contains', 'woo-gift-cards-lite' ); ?></h3>	
				<ul class="wps_redeem_listing">	
					<li class="wps_redeem_item scan"> <div class="wps_redeem_content"><?php esc_html_e( 'Scan', 'woo-gift-cards-lite' ); ?></div> <div class="wps_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>	
					<li class="wps_redeem_item redeem"> <div class="wps_redeem_content"><?php esc_html_e( 'Redeem', 'woo-gift-cards-lite' ); ?></div> <div class="wps_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>
					<li class="wps_redeem_item recharge"> <div class="wps_redeem_content"><?php esc_html_e( 'Recharge', 'woo-gift-cards-lite' ); ?></div> <div class="wps_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>
					<li class="wps_redeem_item reports"> <div class="wps_redeem_content"><?php esc_html_e( 'Reports', 'woo-gift-cards-lite' ); ?></div></li>
				</ul>
			</div>	
		<?php } else { ?>
			
			<div>

				<table class="wps_redeem_details">

					<thead>
						<tr>
							<th colspan="2"><?php esc_html_e( 'Your Gift Card Redeem Details', 'woo-gift-cards-lite' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wcgw_plugin_enable"><?php esc_html_e( 'Gift Card Redeem Link', 'woo-gift-cards-lite' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$allowed_tags = $settings_obj->wps_wgm_allowed_html_for_tool_tip();
								$attribute_description = __( 'Please open the link to redeem the gift card', 'woo-gift-cards-lite' );
								echo wp_kses( wc_help_tip( $attribute_description ), $allowed_tags );
								?>
								<label for="wcgw_plugin_enable">
									<input type="text" name="wcgm_offine_redeem_link" id="wcgm_offine_redeem_link" class="input-text" value="
									<?php
									if ( isset( $offine_giftcard_redeem_link ['shop_url'] ) && '' !== $offine_giftcard_redeem_link['shop_url'] ) {
										echo esc_html( $offine_giftcard_redeem_link['shop_url'] );  }
									?>
									">
									<div class="wps-giftware-copy-icon" >
										<button  class="wps_link_copy wps_redeem_copy" data-clipboard-target="#wcgm_offine_redeem_link" title="copy">
											<i class="far fa-copy" ></i>
										</button>

									</div>	
								</label>

							</td>
						</tr>

						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wcgw_plugin_enable"><?php esc_html_e( 'Embedded Link', 'woo-gift-cards-lite' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$allowed_tags = $settings_obj->wps_wgm_allowed_html_for_tool_tip();
								$attribute_description = __( 'Enter this code to add the redeem page in your site', 'woo-gift-cards-lite' );
								echo wp_kses( wc_help_tip( $attribute_description ), $allowed_tags );
								?>
									<textarea cols="20" rows="3" id="wps_gw_embeded_input_text">
									<?php
									if ( isset( $offine_giftcard_redeem_link ['embed_url'] ) && '' !== $offine_giftcard_redeem_link['embed_url'] ) {
										echo esc_html( $offine_giftcard_redeem_link['embed_url'] );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.
									}
									?>
									</textarea>
									<div class="wps-giftware-copy-icon">									
										<button  class="wps_embeded_copy wps_redeem_copy" data-clipboard-target="#wps_gw_embeded_input_text" title="copy">
											<i class="far fa-copy" ></i>
										</button>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<td colspan="2">
									<input type="submit" name="remove_giftcard_redeem_details" class="remove_giftcard_redeem_details"  class="input-text" value = 'Remove Details' >
									<?php wp_nonce_field( 'wps-remove-nonce', 'wps-remove-nonce' ); ?>
									<a target="_blank" href="
									<?php
									if ( isset( $offine_giftcard_redeem_link ['shop_url'] ) && '' !== $offine_giftcard_redeem_link['shop_url'] ) {
										echo esc_attr( $offine_giftcard_redeem_link['shop_url'] );  }
									?>
										" class= "wps_gw_open_redeem_link"><?php esc_html_e( 'Open Shop', 'woo-gift-cards-lite' ); ?></a>
									<?php if ( ( isset( $offine_giftcard_redeem_settings['license'] ) && '' === $offine_giftcard_redeem_settings['license'] ) || ( isset( $offine_giftcard_redeem_settings['domain'] ) && home_url() !== $offine_giftcard_redeem_settings['domain'] ) ) { ?>
										<input type="submit" name="update_giftcard_redeem_details" class="update_giftcard_redeem_details"  class="input-text" value ='Update Authorization' >
									<?php } ?>
								</td>
							</tr>
								<?php if ( isset( $offine_giftcard_redeem_link['license'] ) && '' == $offine_giftcard_redeem_link['license'] ) { ?>
									<tr>
										<td colspan="2">
											<?php esc_html_e( 'This is your limited  account so please purchase the pro and update the details .', 'woo-gift-cards-lite' ); ?>								
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<p><b>
							<?php esc_html_e( 'To use the redeem link as it is, follow the steps below', 'woo-gift-cards-lite' ); ?></b></p>
							<ol>
								<li><?php esc_html_e( 'Click on the Open Shop button and log in using the credentials provided in the received email', 'woo-gift-cards-lite' ); ?></li>
								<li><?php esc_html_e( 'Start Scan/Fetch and Redeem/Recharge', 'woo-gift-cards-lite' ); ?></li>
							</ol>

							<p><b><?php esc_html_e( 'To use the redeem link on the web-store follow the steps below', 'woo-gift-cards-lite' ); ?></b></p>
							<ol>
								<li><?php esc_html_e( 'Create a page', 'woo-gift-cards-lite' ); ?></li>
								<li><?php esc_html_e( 'Copy the embed link and paste it in the created page', 'woo-gift-cards-lite' ); ?></li>
								<li><?php esc_html_e( 'Login using the credentials given in the received email', 'woo-gift-cards-lite' ); ?></li>
								<li><?php esc_html_e( 'Start Scan/Fetch and Redeem/Recharge', 'woo-gift-cards-lite' ); ?></li>
							</ol>

							<p><b><?php esc_html_e( 'To use the redeem link on this POS system, follow the steps below', 'woo-gift-cards-lite' ); ?></b></p>
							<ol>
								<li><?php esc_html_e( 'Copy the embed link and paste it on any page at POS', 'woo-gift-cards-lite' ); ?></li>
								<li><?php esc_html_e( 'Login using the credentials given in the received email', 'woo-gift-cards-lite' ); ?></li>
								<li><?php esc_html_e( 'Start Scan/Fetch and Redeem/Recharge', 'woo-gift-cards-lite' ); ?></li>
							</ol>

						</div>
					<?php	} ?>

					<div class="wps_wgm_video_wrapper">
						<h3><?php esc_html_e( 'See it in Action', 'woo-gift-cards-lite' ); ?></h3>
						<iframe height="411" src="https://www.youtube.com/embed/H1cYF4F5JA8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
		
	</div>


	<div class="wps_redeem_registraion_div" style="display:none;">
		<div class=" wps_gw_general_setting">
			<table class="form-table">

				<tbody>			
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wcgw_plugin_enable"><?php esc_html_e( 'Email', 'woo-gift-cards-lite' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$allowed_tags = $settings_obj->wps_wgm_allowed_html_for_tool_tip();
							$attribute_description = __( 'Enter the email for account creation', 'woo-gift-cards-lite' );
							echo wp_kses( wc_help_tip( $attribute_description ), $allowed_tags );
							?>
							<label for="wcgw_plugin_enable">
								<input type="email" name="wcgm_offine_redeem_email" id="wcgm_offine_redeem_email" class="input-text" value="<?php echo esc_attr( $wps_current_user->user_email ); ?> ">
							</label>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wcgw_plugin_enable"><?php esc_html_e( 'Name', 'woo-gift-cards-lite' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$allowed_tags = $settings_obj->wps_wgm_allowed_html_for_tool_tip();
							$attribute_description = __( 'Enter the name for account creation', 'woo-gift-cards-lite' );
							echo wp_kses( wc_help_tip( $attribute_description ), $allowed_tags );
							?>
							<label for="wcgw_plugin_enable">
								<input type="text" name="wcgm_offine_redeem_name" id="wcgm_offine_redeem_name" class="input-text" value="<?php echo esc_attr( $wps_current_user->display_name ); ?> ">
							</label>						
						</td>
					</tr>			

					<tr valign="top">
						
						<td class="forminp forminp-text text-center" colspan="2">

							<label for="wcgw_plugin_enable">
								<input type="submit" name="wcgm_generate_offine_redeem_url" id="wcgm_generate_offine_redeem_url" class="input-text" value = 'Generate Link'>
								<?php wp_nonce_field( 'wps-redeem-nonce', 'wps-redeem-nonce' ); ?>
							</label>						
						</td>
					</tr>				
				</tbody>				
			</table>
			<span class="wps-redeem-pop-close"><i class="fas fa-times"></i></span>
		</div>
	</div>			
</div>
