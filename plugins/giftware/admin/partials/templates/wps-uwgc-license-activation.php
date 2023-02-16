<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to provide a panel for license activation verification
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/admin/partials
 */

?>
<h3><?php esc_html_e( 'License Activation', 'giftware' ); ?></h3>
<div class="wps_uwgc-license-sec">
	<p>
		<?php esc_html_e( 'This is the License Activation Panel. After purchasing extension from ', 'giftware' ); ?>
		<span>
			<a href="https://wpswings.com/" target="_blank" ><?php esc_html_e( 'WP Swings', 'giftware' ); ?></a>
		</span>&nbsp;

		<?php esc_html_e( 'you will get the purchase code of this extension. Please verify your purchase below so that you can use the features of this plugin.', 'giftware' ); ?>
	</p>
	<form id="wps_uwgc-license-form">
		<label><b><?php esc_html_e( 'Purchase Code : ', 'giftware' ); ?></b></label>
		<input type="text" id="wps_uwgc-license-key" placeholder="<?php esc_html_e( 'Enter your code here.', 'giftware' ); ?>" required="">
		<div id="wps_uwgc-ajax-loading-gif"><img src="<?php echo 'images/spinner.gif'; ?>"></div> 
		<p id="wps_uwgc-license-activation-status"></p>
		<input type='button' class="button-primary"  id="wps_uwgc-license-activate" value="<?php esc_html_e( 'Activate', 'giftware' ); ?>">
		<?php wp_nonce_field( 'wps_uwgc-license-nonce-action', 'wps_uwgc-license-nonce' ); ?>
	</form>
</div>
