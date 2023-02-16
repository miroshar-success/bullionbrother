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

$screen = get_current_screen();
$is_valid = in_array( $screen->id, apply_filters( 'wps_helper_valid_frontend_screens', array() ) );
if ( ! $is_valid ) {
	return false;
}

$form_fields = apply_filters( 'wps_on_boarding_form_fields', array() );
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
				<h3 class="wps-on-boarding-heading"><?php esc_html_e( 'Welcome to WP Swings', 'woo-gift-cards-lite' ); ?></h3>
				<p class="wps-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'woo-gift-cards-lite' ); ?></p>
				<form action="#" method="post" class="wps-on-boarding-form">
					<?php foreach ( $form_fields as $key => $field_attr ) : ?>
						<?php $this->render_field_html( $field_attr ); ?>
					<?php endforeach; ?>
					<div class="wps-on-boarding-form-btn__wrapper">
						<div class="wps-on-boarding-form-submit wps-on-boarding-form-verify ">
						<input type="submit" class="wps-on-boarding-submit wps-on-boarding-verify " value="Send Us">
					</div>
					<div class="wps-on-boarding-form-no_thanks">
						<a href="javascript:void(0);" class="wps-on-boarding-no_thanks"><?php esc_html_e( 'Skip For Now', 'woo-gift-cards-lite' ); ?></a>
					</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>
