<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wps_Uwgc_Qrcode_Barcode' ) ) {

	/**
	 * This is class for managing order status and other functionalities .
	 *
	 * @name    Wps_Uwgc_Qr_Barcode_Card_Product
	 * @category Class
	 * @author   WP Swings <webmaster@wpswings.com>
	 */
	class Wps_Uwgc_Qrcode_Barcode {

		/**
		 * Constructor.
		 */
		public function __construct() {
			include_once WPS_UWGC_DIRPATH . 'Qrcode/phpqrcode/qrlib.php';
			include_once WPS_UWGC_DIRPATH . 'Qrcode/php-barcode-master/barcode.php';
		}

		/**
		 * This function sets qrcode image
		 *
		 * @name getqrcode()
		 * @param mixed $coupon Coupon.
		 * @param mixed $qrcode_level Wr level.
		 * @param mixed $qrcode_size Qr size.
		 * @param mixed $qrcode_margin Qr margin.
		 * @param mixed $time_stamp Time Stamp.
		 * @param mixed $site_name Site name.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function getqrcode( $coupon, $qrcode_level, $qrcode_size, $qrcode_margin, $time_stamp, $site_name ) {

			$path = WPS_UWGC_UPLOAD_DIR . '/qrcode_barcode/wps__' . $time_stamp . $coupon . '.png';

			if ( 'L' == $qrcode_level ) {
				$qrcode_level = QR_ECLEVEL_L;
			} elseif ( 'M' == $qrcode_level ) {
				$qrcode_level = QR_ECLEVEL_M;
			} elseif ( 'Q' == $qrcode_level ) {
				$qrcode_level = QR_ECLEVEL_Q;
			} elseif ( 'H' == $qrcode_level ) {
				$qrcode_level = QR_ECLEVEL_H;
			}

			QRcode::png( $coupon, $path, $qrcode_level, $qrcode_size, $qrcode_margin );

		}
		/**
		 * This function sets barrcode image
		 *
		 * @name getbarcode()
		 * @param mixed $coupon Coupon.
		 * @param mixed $barcode_display Display.
		 * @param mixed $barcode_type Qr type.
		 * @param mixed $barcode_size Qr sixe.
		 * @param mixed $time_stamp Time Stamp.
		 * @param mixed $site_name Site name.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function getbarcode( $coupon, $barcode_display, $barcode_type, $barcode_size, $time_stamp, $site_name ) {
			$path = WPS_UWGC_UPLOAD_DIR . '/qrcode_barcode/wps__' . $time_stamp . $coupon . '.png';
			if ( 'on' == $barcode_display ) {
				$barcode_display = true;
			} else {
				$barcode_display = false;
			}
			barcode( $path, $coupon, $barcode_size, 'horizontal', $barcode_type, $barcode_display, 1 );
		}
	}
}
