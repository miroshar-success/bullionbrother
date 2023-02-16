<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes/extra-templates
 */

global $pagenow;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}

$form_fields = apply_filters( 'wps_deactivation_form_fields', array() );
if ( ! empty( $form_fields ) ) : ?>
	<div style="display: none;" class="loading-style-bg" id="wps_wgm_loader">
		<img src="<?php echo esc_url( WPS_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<div class="wps-onboarding-section">
		<div class="wps-on-boarding-wrapper-background">
		<div class="wps-on-boarding-wrapper">
			<div class="wps-on-boarding-close-btn">
				<a href="javascript:void(0);">
					<span class="close-form">x</span>
				</a>
			</div>
			<h3 class="wps-on-boarding-heading"></h3>
			<p class="wps-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'woo-gift-cards-lite' ); ?></p>
			<form action="#" method="post" class="wps-on-boarding-form">
				<?php foreach ( $form_fields as $key => $field_attr ) : ?>
					<?php $this->render_field_html( $field_attr, 'deactivating' ); ?>
				<?php endforeach; ?>
				<div class="wps-on-boarding-form-btn__wrapper">
					<div class="wps-on-boarding-form-submit wps-on-boarding-form-verify ">
					<input type="submit" class="wps-on-boarding-submit wps-on-boarding-verify " value="SUBMIT AND DEACTIVATE">
				</div>
				<div class="wps-on-boarding-form-no_thanks">
					<a href="javascript:void(0);" class="wps-deactivation-no_thanks"><?php esc_html_e( 'Skip and Deactivate Now', 'woo-gift-cards-lite' ); ?></a>
				</div>
				</div>
			</form>
		</div>
	</div>
	</div>
<?php endif; ?>
