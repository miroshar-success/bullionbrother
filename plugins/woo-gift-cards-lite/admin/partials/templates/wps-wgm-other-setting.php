<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * General Settings Template
 */
require_once WPS_WGC_DIRPATH . 'admin/partials/templates/wps_wgm_settings/wps-wgm-other-settings-array.php';
$flag = false;
$current_tab = 'wps_wgm_other_setting';
if ( isset( $_POST['wps_wgm_save_other'] ) ) {
	if ( isset( $_REQUEST['wps-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-wgc-nonce'] ) ), 'wps-wgc-nonce' ) ) {
		unset( $_POST['wps_wgm_save_other'] );
		if ( 'wps_wgm_other_setting' == $current_tab ) {
			$other_settings_array = array();
			$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$other_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $other_settings_array ) && ! empty( $other_settings_array ) ) {
				update_option( 'wps_wgm_other_settings', $other_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->wps_wgm_settings_saved();
}
?>
<?php $other_settings = get_option( 'wps_wgm_other_settings', array() ); ?>
<h3 class="wps_wgm_overview_heading"><?php esc_html_e( 'Other Settings', 'woo-gift-cards-lite' ); ?></h3>
<div class="wps_wgm_table_wrapper">	
	<div class="wps_table">
		<table class="form-table wps_wgm_general_setting">
			<tbody>
				<?php
				$settings_obj->wps_wgm_generate_common_settings( $wps_wgm_other_setting, $other_settings );
				?>
			</tbody>
		</table>
	</div>	
</div>
<?php
$settings_obj->wps_wgm_save_button_html( 'wps_wgm_save_other' );
?>
<div class="clear"></div>
