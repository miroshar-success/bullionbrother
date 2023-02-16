<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Redeem api work...

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'gifting',
			'/redeem-giftcard',
			array(

				'methods'  => 'POST',
				'callback' => 'wps_redeem_giftcard_offline',
				'permission_callback' => 'wps_permission_check',

			)
		);
		register_rest_route(
			'gifting',
			'/get-giftcard',
			array(

				'methods'  => 'POST',
				'callback' => 'wps_get_giftcard_details',
				'permission_callback' => 'wps_permission_check',

			)
		);
		register_rest_route(
			'gifting',
			'/recharge-giftcard',
			array(

				'methods'  => 'POST',
				'callback' => 'wps_recharge_giftcard_offine',
				'permission_callback' => 'wps_permission_check',

			)
		);
	}
);

/**
 * Get_giftcard_details
 *
 * @since      1.0.0
 * @name wps_get_giftcard_details
 * @param mixed $request Request.
 * @author WP Swings <webmaster@wpswings.com>
 * @link https://www.wpswings.com/
 */
function wps_get_giftcard_details( $request ) {

	global $woocommerce;
	$request_params = $request->get_params();

	$coupon_code = $request_params['coupon_code'];
	$coupon_code = strtolower( $coupon_code );

	$coupon_details = new WC_Coupon( $coupon_code );
	$response['code'] = 'success';
	$response['message'] = 'There is Giftcard Details ';
	$coupon_id = $coupon_details->get_id();
	if ( '' !== $coupon_id && 0 !== $coupon_id ) {

		$woo_ver = WC()->version;
		if ( $woo_ver < '3.6.0' ) {

			$coupon_expiry = get_post_meta( $coupon_id, 'expiry_date', true );

		} else {
			$coupon_expiry = get_post_meta( $coupon_id, 'date_expires', true );
		}

		$response['code'] = 'success';
		$response['message'] = 'There is Giftcard Details ';

		$data = array(
			'status' => 200,
			'remaining_amount' => $coupon_details->amount,
			'discount_type' => $coupon_details->discount_type,
			'usage_count' => $coupon_details->usage_count,
			'usage_limit' => $coupon_details->usage_limit,
			'description' => $coupon_details->description,
			'coupon_expiry' => $coupon_expiry,

		);
		$response['data'] = $data;
		$response = new WP_REST_Response( $response );
	} else {

		$response['code'] = 'error';
		$response['message'] = 'Coupon is not valid  Giftcard Coupon';

		$data = array(
			'status' => 404,

		);
		$response['data'] = $data;
		$response = new WP_REST_Response( $response );

	}
	return $response;
}

/**
 * Redeem Giftcard Offline
 *
 * @since      1.0.0
 * @name wps_redeem_giftcard_offline
 * @param mixed $request Request.
 * @author WP Swings <webmaster@wpswings.com>
 * @link https://www.wpswings.com/
 */
function wps_redeem_giftcard_offline( $request ) {

	global $woocommerce;

	$request_params = $request->get_params();

	$coupon_code = $request_params['coupon_code'];
	$redeem_amount = $request_params['redeem_amount'];
	$coupon_code = strtolower( $coupon_code );

	$the_coupon = new WC_Coupon( $coupon_code );
	$coupon_id = $the_coupon->get_id();
	if ( '' !== $coupon_id && 0 !== $coupon_id ) {
		$coupon_amount = get_post_meta( $coupon_id, 'coupon_amount', true );
		$coupon_usage_count = get_post_meta( $coupon_id, 'usage_count', true );
		$coupon_usage_limit = get_post_meta( $coupon_id, 'usage_limit', true );

		if ( 0 == $coupon_usage_limit || $coupon_usage_limit > $coupon_usage_count ) {

			$woo_ver = WC()->version;

			$coupon_expiry = '';
			if ( $woo_ver < '3.6.0' ) {

				$coupon_expiry = get_post_meta( $coupon_id, 'expiry_date', true );

			} else {
				$coupon_expiry = get_post_meta( $coupon_id, 'date_expires', true );
			}

			$giftcardcoupon_order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );

			if ( '' == $coupon_expiry || $coupon_expiry > current_time( 'timestamp' ) ) {

				if ( $coupon_amount >= $redeem_amount ) {

					$remaining_amount = $coupon_amount - $redeem_amount;

					update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
					$coupon_usage_count = ++$coupon_usage_count;
					update_post_meta( $coupon_id, 'usage_count', $coupon_usage_count );

					$response['code'] = 'success';
					$response['message'] = 'Coupon is successfully Redeemed';

					$data = array(
						'status' => 200,
						'remaining_amount' => $remaining_amount,
						'discount_type' => $the_coupon->discount_type,
						'usage_count' => $coupon_usage_count,
						'usage_limit' => $the_coupon->usage_limit,
						'description' => $the_coupon->description,
						'coupon_expiry' => $coupon_expiry,
					);
					$response['data'] = $data;

					$response = new WP_REST_Response( $response );

				} else {

					$response['code'] = 'error';
					$response['message'] = 'Redeem amount is greater than Coupon amount';

					$data = array(
						'status' => 404,

					);
					$response['data'] = $data;
					$response = new WP_REST_Response( $response );
				}
			} else {

				$response['code'] = 'error';
				$response['message'] = 'Coupon is expired';

				$data = array(
					'status' => 404,

				);
				$response['data'] = $data;
				$response = new WP_REST_Response( $response );

			}
		} else {

			$response['code'] = 'error';
			$response['message'] = 'Coupon is already used';

			$data = array(
				'status' => 404,

			);
			$response['data'] = $data;
			$response = new WP_REST_Response( $response );

		}
	} else {
		$response['code'] = 'error';
		$response['message'] = 'Coupon is not valid Giftcard Coupon';

		$data = array(
			'status' => 404,

		);
		$response['data'] = $data;
		$response = new WP_REST_Response( $response );

	}
	return $response;

}

/**
 * Recharge Giftcard Offline
 *
 * @since      1.0.0
 * @name wps_recharge_giftcard_offine
 * @param mixed $request request.
 * @author WP Swings <webmaster@wpswings.com>
 * @link https://www.wpswings.com/
 */
function wps_recharge_giftcard_offine( $request ) {
	global $woocommerce;
	$request_params = $request->get_params();
	$coupon_code = $request_params['coupon_code'];
	$recharge_amount = $request_params['recharge_amount'];
	$coupon_expirys = ( '' !== $request_params['coupon_expiry'] ) ? $request_params['coupon_expiry'] : null;
	$usage_limit = ( '' !== $request_params['usage_limit'] ) ? $request_params['usage_limit'] : 0;
	$coupon_code = strtolower( $coupon_code );

	$the_coupon = new WC_Coupon( $coupon_code );
	$coupon_id = $the_coupon->get_id();

	if ( '' !== $coupon_id && 0 !== $coupon_id ) {
		$coupon_amount = get_post_meta( $coupon_id, 'coupon_amount', true );

		$coupon_expiry = '';
		$woo_ver = WC()->version;

		if ( $woo_ver < '3.6.0' ) {

			$coupon_expiry = get_post_meta( $coupon_id, 'expiry_date', true );

		} else {
			$coupon_expiry = get_post_meta( $coupon_id, 'date_expires', true );
		}

		$giftcardcoupon_order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );

		if ( '' == $coupon_expiry || $coupon_expiry > current_time( 'timestamp' ) ) {

			$updated_amount = $coupon_amount + $recharge_amount;

			update_post_meta( $coupon_id, 'coupon_amount', $updated_amount );

			update_post_meta( $coupon_id, 'usage_limit', $usage_limit );
			update_post_meta( $coupon_id, 'usage_count', 0 );

			if ( $woo_ver < '3.6.0' ) {
				if ( $coupon_expirys > time() ) {
					update_post_meta( $coupon_id, 'expiry_date', $coupon_expirys );
					$coupon_expiry = $coupon_expirys;
				}
			} else {
				if ( $coupon_expirys > time() ) {
					update_post_meta( $coupon_id, 'date_expires', $coupon_expirys );
					$coupon_expiry = $coupon_expirys;
				}
			}

			$response['code'] = 'success';
			$response['message'] = 'Coupon is successfully Recharged';

			$data = array(
				'status' => 200,
				'remaining_amount' => $updated_amount,
				'discount_type' => $the_coupon->discount_type,
				'usage_count' => 0,
				'usage_limit' => $usage_limit,
				'description' => $the_coupon->description,
				'coupon_expiry' => $coupon_expiry,
			);
			$response['data'] = $data;
			$response = new WP_REST_Response( $response );

		} else {

			$response['code'] = 'error';
			$response['message'] = 'Coupon is expired';

			$data = array(
				'status' => 404,

			);
			$response['data'] = $data;
			$response = new WP_REST_Response( $response );
		}
	} else {
		$response['code'] = 'error';
		$response['message'] = 'Coupon is not valid  Giftcard Coupon';

		$data = array(
			'status' => 404,

		);
		$response['data'] = $data;
		$response = new WP_REST_Response( $response );
	}
	return $response;
}

/**
 * Check permission
 *
 * @since      1.0.0
 * @name wps_permission_check
 * @param mixed $request Request.
 * @author WP Swings <webmaster@wpswings.com>
 * @link https://www.wpswings.com/
 */
function wps_permission_check( $request ) {
	$license             = $request->get_header( 'licensecode' );
	$client_license_code = get_option( 'wps_gw_lcns_key', '' );
	if ( '' == $license ) {
		return true;
	} elseif ( trim( $client_license_code ) === trim( $license ) ) {
		return true;
	} else {
		return false;
	}
}
