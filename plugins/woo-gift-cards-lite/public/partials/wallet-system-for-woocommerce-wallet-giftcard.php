<?php
/**
 * Exit if accessed directly
 *
 * @package woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class='content active'>
	<form method="post" action="" id="wps_wallet_transfer_form">
		<p class="wps-wallet-field-container form-row form-row-wide">
			<label for="wps_giftcard_code"><?php echo esc_html__( 'Enter Gift Card Code : ', 'woo-gift-cards-lite' ); ?></label>
			<input type="text" id="wps_giftcard_code" name="wps_giftcard_code" required>
		</p>
		<p class="error"></p>
		<p class="success"></p>
		<p class="wps-wallet-field-container form-row">
			<input type="button" class="wps-btn__filled button" id="wps_recharge_wallet_giftcard" name="wps_recharge_wallet_giftcard" value="<?php esc_html_e( 'Proceed', 'woo-gift-cards-lite' ); ?>">
		</p>
	</form>
</div>
<?php
$wps_wgm = array(
	'ajaxurl'       => admin_url( 'admin-ajax.php' ),
	'wps_wgm_nonce' => wp_create_nonce( 'wps-wgc-verify-nonce' ),
	'wps_currency'  => get_woocommerce_currency_symbol(),
);
wp_enqueue_script( 'wps-wallet-giftcard', plugin_dir_url( __FILE__ ) . '../js/woocommerce_gift_cards_lite-public.js', array( 'jquery' ), $this->version, true );
wp_localize_script( 'wps-wallet-giftcard', 'wps_wgm', $wps_wgm );
?>
