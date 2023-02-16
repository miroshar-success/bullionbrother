<?php
/**
 * Exit if accessed directly
 *
 * @package Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-settings/wps-uwgc-qrcode-settings-array.php';
$reset = false;
$flag = false;
if ( isset( $_POST['wps_uwgc_qrcode_reset_save'] ) ) {
	unset( $_POST['wps_uwgc_qrcode_reset_save'] );
	delete_option( 'wps_wgm_qrcode_settings' );
	unset( $_POST );
	$reset = true;
}
$current_tab = 'wps_uwgc_qrcode_setting';
if ( isset( $_POST['wps_uwgc_save_qrcode'] ) ) {
	if ( isset( $_REQUEST['wps-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-wgc-nonce'] ) ), 'wps-wgc-nonce' ) ) { // WPCS: input var ok, sanitization ok.
		unset( $_POST['wps_uwgc_save_qrcode'] );
		$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		$qrcode_settings_array = array();
		if ( 'wps_uwgc_qrcode_setting' == $current_tab ) {
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$qrcode_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $qrcode_settings_array ) && ! empty( $qrcode_settings_array ) ) {
				update_option( 'wps_wgm_qrcode_settings', $qrcode_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->wps_wgm_settings_saved( 'Settings saved' );
}
if ( $reset ) {
	$settings_obj->wps_wgm_settings_saved( 'Settings are Reset' );
}
?>
<?php $qrcode_settings = get_option( 'wps_wgm_qrcode_settings', true ); ?>
<?php
if ( ! is_array( $qrcode_settings ) ) :
	$qrcode_settings = array();
endif;
?>
<h3 class="wps_wgm_overview_heading"><?php esc_html_e( 'QR/BAR Code Settings', 'giftware' ); ?></h3>
<div class="wps_wgm_table_wrapper">	
	<div class="wps_table">
		<table class="form-table wps_wgm_general_setting">
			<tbody>	
				<?php
					$settings_obj->wps_wgm_generate_common_settings( $wps_uwgc_qrcode_settings, $qrcode_settings );
				?>
			</tbody>
		</table>
	</div>
</div>
<div class="wps_wgm_button_wrapper">
	<?php
	$settings_obj->wps_wgm_save_button_html( 'wps_uwgc_save_qrcode' );
	?>
	<input type="submit" value="<?php esc_html_e( 'Reset', 'giftware' ); ?>" class="button-primary woocommerce-reset-button" name="wps_uwgc_qrcode_reset_save" id="wps_uwgc_qrcode_setting_save" >
</div>
<div class="clear"></div>
