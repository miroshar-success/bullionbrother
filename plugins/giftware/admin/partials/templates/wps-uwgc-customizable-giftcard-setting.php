<?php
/**
 * Exit if accessed directly
 *
 * @package Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-settings/wps-uwgc-customizable-giftcard-settings-array.php';
$flag = false;
$current_tab = 'wps_uwgc_customizable_setting';
if ( isset( $_POST['wps_uwgc_save_customizable'] ) ) {
	if ( isset( $_REQUEST['wps-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-wgc-nonce'] ) ), 'wps-wgc-nonce' ) ) { // WPCS: input var ok, sanitization ok.
		unset( $_POST['wps_uwgc_save_customizable'] );
		$postdata = $settings_obj->wps_wgm_sanitize_settings_data( $_POST, $wps_uwgc_customizable_settings );

		$customizable_settings_array = array();
		if ( 'wps_uwgc_customizable_setting' == $current_tab ) {
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$customizable_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $customizable_settings_array ) && ! empty( $customizable_settings_array ) ) {
				update_option( 'wps_wgm_customizable_settings', $customizable_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->wps_wgm_settings_saved( 'Settings saved' );
}
?>
<?php $customizable_settings = get_option( 'wps_wgm_customizable_settings', true ); ?>
<?php
if ( ! is_array( $customizable_settings ) ) :
	$customizable_settings = array();
endif;
?>
<h3 class="wps_wgm_overview_heading"><?php esc_html_e( 'Customizable Gift Cards', 'giftware' ); ?></h3>
<div class="wps_wgm_table_wrapper">	
	<div class="wps_table">
		<div style="display: none;" class="loading-style-bg" id="wps_uwgc_loader">
			<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
		</div>
		<table class="form-table wps_wgm_general_setting wp-list-table striped">
			<tbody>	
				<?php
					$settings_obj->wps_wgm_generate_common_settings( $wps_uwgc_customizable_settings, $customizable_settings );
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
$settings_obj->wps_wgm_save_button_html( 'wps_uwgc_save_customizable' );
?>
<div class="clear"></div>
