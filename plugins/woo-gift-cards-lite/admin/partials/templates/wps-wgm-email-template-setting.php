<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_WGC_DIRPATH . 'admin/partials/templates/wps_wgm_settings/wps-wgm-mail-template-settings-array.php';
$flag        = false;
$current_tab = 'wps_wgm_mail_setting';
if ( isset( $_POST['wps_wgm_save_mail'] ) ) {
	if ( isset( $_REQUEST['wps-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-wgc-nonce'] ) ), 'wps-wgc-nonce' ) ) {
		unset( $_POST['wps_wgm_save_mail'] );
		if ( in_array( 'giftware/giftware.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active( 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' ) ) {
			$postdata = wp_unslash( $_POST );
		} else {
			$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		}
		if ( 'wps_wgm_mail_setting' == $current_tab ) {
			$mail_settings_array = array();
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$mail_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $mail_settings_array ) && ! empty( $mail_settings_array ) ) {
				update_option( 'wps_wgm_mail_settings', $mail_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->wps_wgm_settings_saved();
}
?>
<?php $mail_settings = get_option( 'wps_wgm_mail_settings', array() ); ?>
<h3 class="wps_wgm_overview_heading"><?php esc_html_e( 'Email Settings', 'woo-gift-cards-lite' ); ?></h3>
<div class="wps_wgm_table_wrapper">	
	<div class="wps_table">
		<table class="form-table wps_wgm_general_setting">
			<tbody>
				<?php
				$settings_obj->wps_wgm_generate_common_settings( $wps_wgm_mail_template_settings['top'], $mail_settings );
				?>
			</tbody>
		</table>
	</div>
</div>
<h3 id="wps_wgm_mail_setting" class="wps_wgm_mail_setting_tab"><?php esc_html_e( 'Mail Settings', 'woo-gift-cards-lite' ); ?></h3>
<div id="wps_wgm_mail_setting_wrapper" class="wps_wgm_table_wrapper">
	<table class="form-table wps_wgm_general_setting">	
		<tbody>
			<?php
				$settings_obj->wps_wgm_generate_common_settings( $wps_wgm_mail_template_settings['middle'], $mail_settings );
			?>
		</tbody>
	</table>
</div>
<?php
do_action( 'wps_wgm_addtional_mail_settings', $wps_wgm_mail_template_settings, $mail_settings );
$settings_obj->wps_wgm_save_button_html( 'wps_wgm_save_mail' );
?>
<div class="clear"></div>
	
