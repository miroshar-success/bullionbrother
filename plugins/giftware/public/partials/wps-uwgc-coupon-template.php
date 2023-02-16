<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<center style="width: 100%;">
	<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px; background-color:#0467A2;">
		<tr>
			<td style="padding: 20px 0; text-align: center">
				<p style="font-size: 20px; color: #fff; font-family: sans-serif; text-align: center;">[SITENAME]</p>
			</td>
		</tr>
	</table>
	<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
		<tr>
			<td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;">
				<p style="font-size: 18px; color: #575757; text-align: center; font-family: sans-serif;"><?php esc_html_e( 'Hello, This is the notification for your coupon amount.', 'giftware' ); ?><br/><?php esc_html_e( 'You have left with amount of ', 'giftware' ); ?>[COUPONAMOUNT]  <?php esc_html_e( 'with coupon code.', 'giftware' ); ?> [COUPONCODE]</p>
				<span style="font-size: 16px; color: #575757; text-align: center; font-family: sans-serif;"><?php esc_html_e( 'Thank You', 'giftware' ); ?></span>
			</td>
		</tr>
	</table>
	<!-- Email Header : END -->
	<!-- Email Footer : BEGIN -->
	<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px; background-color: #FCD347;">
		<tr>
			<td style="padding: 10px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;">
				<p style="font-size: 14px; font-family: sans-serif; color: #fff; text-align: center;">[DISCLAIMER]</p>
			</td>
		</tr>
	</table>
</div>
</center>
