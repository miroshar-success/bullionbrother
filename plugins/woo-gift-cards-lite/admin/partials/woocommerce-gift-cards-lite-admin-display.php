<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Makewebbetter_Onboarding_Helper' ) ) {
	$this->onboard = new Makewebbetter_Onboarding_Helper();

}
/*  create the settings tabs*/
$wps_wgm_setting_tab = array(
	'overview_setting' => array(
		'title' => esc_html__( 'OverView', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-overview-setting.php',
	),
	'general_setting' => array(
		'title' => esc_html__( 'General', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-general-setting.php',
	),
	'product_setting' => array(
		'title' => esc_html__( 'Product', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-product-setting.php',
	),
	'email_setting' => array(
		'title' => esc_html__( 'Email Template', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-email-template-setting.php',
	),
	'delivery_method' => array(
		'title' => esc_html__( 'Delivery Method', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-delivery-setting.php',
	),
	'other_setting' => array(
		'title' => esc_html__( 'Other Settings', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-other-setting.php',
	),
);
$wps_wgm_setting_tab = apply_filters( 'wps_wgm_add_gift_card_setting_tab_before', $wps_wgm_setting_tab );
$wps_wgm_setting_tab['redeem_tab'] = array(
	'title' => esc_html__( 'Gift Card Redeem', 'woo-gift-cards-lite' ),
	'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/redeem-giftcard-settings.php',
);
if ( ! wps_uwgc_pro_active() ) {
	$wps_wgm_setting_tab['premium_plugin'] = array(
		'title' => esc_html__( 'Premium Features', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-premium-features.php',
	);
}
$wps_wgm_setting_tab = apply_filters( 'wps_wgm_add_gift_card_setting_tab_after', $wps_wgm_setting_tab );
do_action( 'wps_uwgc_show_notice' );
?>
<div class="wrap woocommerce" id="wps_wgm_setting_wrapper">
	<input type="hidden" class="treat-button">
	<div style="display: none;" class="loading-style-bg" id="wps_wgm_loader">
		<img src="<?php echo esc_url( WPS_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<form enctype="multipart/form-data" action="" id="mainform" method="post">
		<div class="wps_wgm_header">
			<div class="wps_wgm_header_content_left">
				<div>
					<h3 class="wps_wgm_setting_title"><?php esc_html_e( 'Gift Card Settings', 'woo-gift-cards-lite' ); ?></h3>
				</div>
			</div>
			<div class="wps_wgm_header_content_right">
				<ul>
					<?php
					if ( wps_uwgc_pro_active() ) {
						?>
						<li class="wps_wgm_header_menu_button"><a href="https://wpswings.com/contact-us/?utm_source=wpswings-giftcards-contact&utm_medium=giftcards-pro-backend&utm_campaign=giftcards-contact" target="_blank">
							<span class="dashicons dashicons-phone"></span>
							<span class="wps-wgn-icon-text"><?php esc_html_e( 'CONTACT US', 'woo-gift-cards-lite' ); ?></span>
						</a>
						</li>
						<li class="wps_wgm_header_menu_button"><a href="https://docs.wpswings.com/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-pro-backend&utm_campaign=documentation" target="_blank">
							<span class="dashicons dashicons-media-document"></span>
							<span class="wps-wgn-icon-text"><?php esc_html_e( 'DOC', 'woo-gift-cards-lite' ); ?></span>
						</a>
						</li>	
						<?php
					} else {
						?>
						<li class="wps_wgm_header_menu_button"><a href="https://wpswings.com/contact-us/?utm_source=wpswings-giftcards-contact&utm_medium=giftcards-org-backend&utm_campaign=contact" target="_blank">
							<span class="dashicons dashicons-phone"></span>
							<span class="wps-wgn-icon-text"><?php esc_html_e( 'CONTACT US', 'woo-gift-cards-lite' ); ?></span>
						</a>
						</li>
						<li class="wps_wgm_header_menu_button"><a href="https://docs.wpswings.com/woo-gift-cards-lite/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-org-backend&utm_campaign=documentation" target="_blank">
							<span class="dashicons dashicons-media-document"></span>
							<span class="wps-wgn-icon-text"><?php esc_html_e( 'DOC', 'woo-gift-cards-lite' ); ?></span>
						</a>
						</li>
						<li class="wps_wgm_header_menu_button">
							<a  href="https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro" class="wps-wgn-icon-text" title="" target="_blank"><?php esc_html_e( 'GO PRO NOW', 'woo-gift-cards-lite' ); ?></a>
						</li>	
						<?php
					}
					?>
				</ul>
			</div>
		</div>
		<?php
		wp_nonce_field( 'wps-wgc-nonce', 'wps-wgc-nonce' );
		$plugin_admin = new Woocommerce_Gift_Cards_Lite_Admin( WPS_WGC_ONBOARD_PLUGIN_NAME, WPS_WGC_VERSION );
		$count        = $plugin_admin->wps_wgm_get_count( 'orders' );
		if ( ! empty( $count ) ) {
			$global_custom_css = 'const triggerError = () => {
				swal({
					title: "Attention Required!",
					text: "Please Migrate Your Database keys first by click on the below button then you can access the dashboard page.",
					icon: "error",
					button: "Click to Import",
					closeOnClickOutside: false,
				}).then(function() {
					jQuery( ".treat-button" ).click();
				});
			}
			triggerError();';
			wp_register_script( 'wps_wgm_incompatible_css', false, array(), WPS_WGC_VERSION, 'all' );
			wp_enqueue_script( 'wps_wgm_incompatible_css' );
			wp_add_inline_script( 'wps_wgm_incompatible_css', $global_custom_css );
		}
		?>
		<div class="wps_wgm_main_template">
			<div class="wps_wgm_body_template">
				<div class="wps_wgm_mobile_nav">
					<span class="dashicons dashicons-menu"></span>
				</div>
				<div class="wps_wgm_navigator_template">
					<div class="wps_wgm-navigations">
						<?php
						if ( isset( $wps_wgm_setting_tab ) && ! empty( $wps_wgm_setting_tab ) && is_array( $wps_wgm_setting_tab ) ) {
							foreach ( $wps_wgm_setting_tab as $key => $wps_tab ) {
								if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {
									?>
									<div class="wps_wgm_tabs">
										<a class="wps_wgm_nav_tab nav-tab nav-tab-active" href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?></a>
									</div>
									<?php
								} else {
									if ( ! isset( $_GET['tab'] ) && 'overview_setting' == $key ) {
										?>
										<div class="wps_wgm_tabs">
											<a class="wps_wgm_nav_tab nav-tab nav-tab-active" href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?></a>
										</div>
										<?php
									} else {
										?>
													
										<div class="wps_wgm_tabs">
											<a class="wps_wgm_nav_tab nav-tab" href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?></a>
										</div>
										<?php
									}
								}
							}
						}
						?>
							
					</div>
				</div>
				<?php
				if ( isset( $wps_wgm_setting_tab ) && ! empty( $wps_wgm_setting_tab ) && is_array( $wps_wgm_setting_tab ) ) {
					foreach ( $wps_wgm_setting_tab as $key => $wps_file ) {
						if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {
							$include_tab = isset( $wps_file['file_path'] ) ? $wps_file['file_path'] : '';
							?>
							<div class="wps_wgm_content_template">
								<?php include_once $include_tab; ?>
							</div>
							<?php
						} elseif ( ! isset( $_GET['tab'] ) && 'overview_setting' == $key ) {
							$include_tab = isset( $wps_file['file_path'] ) ? $wps_file['file_path'] : '';
							?>
							<div class="wps_wgm_content_template">
								<?php include_once $include_tab; ?>
							</div>
							<?php
							break;
						}
					}
				}
				?>
			</div>
		</div>
	</form>
</div>
