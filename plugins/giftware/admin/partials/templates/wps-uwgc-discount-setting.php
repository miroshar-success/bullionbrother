<?php
/**
 * Exit if accessed directly
 *
 * @package  Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-settings/wps-uwgc-discount-settings-array.php';
$flag = false;
$current_tab = 'wps_wgm_discount_setting';

if ( isset( $_POST['wps_uwgc_save_discount'] ) || isset( $_POST['wps_uwgc_save_discount_js'] ) ) {
	if ( isset( $_REQUEST['wps-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-wgc-nonce'] ) ), 'wps-wgc-nonce' ) ) { // WPCS: input var ok, sanitization ok.
		unset( $_POST['wps_uwgc_save_discount'] );
		$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		$discount_settings_array = array();
		if ( 'wps_wgm_discount_setting' == $current_tab ) {
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$discount_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $discount_settings_array ) && ! empty( $discount_settings_array ) ) {
				update_option( 'wps_wgm_discount_settings', $discount_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->wps_wgm_settings_saved( 'Settings saved' );
}
?>
<?php $discount_settings = get_option( 'wps_wgm_discount_settings', true ); ?>
<?php
if ( ! is_array( $discount_settings ) ) :
	$discount_settings = array();
endif;
?>
<h3 class="wps_wgm_overview_heading"><?php esc_html_e( 'Discount Settings', 'giftware' ); ?></h3>
<div class="wps_wgm_table_wrapper">	
	<div class="wps_table">
		<table class="form-table wps_wgm_general_setting">
			<tbody>	
				<?php
				$settings_obj->wps_wgm_generate_common_settings( $wps_wgm_discount_settings, $discount_settings );
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
$settings_obj->wps_wgm_save_button_html( 'wps_uwgc_save_discount' );
?>
<div class="clear"></div>
