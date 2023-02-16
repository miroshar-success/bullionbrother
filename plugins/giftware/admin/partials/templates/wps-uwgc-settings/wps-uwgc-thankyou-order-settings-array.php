<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
$wps_uwgc_thankyou_order_settings = array(
	array(
		'title' => __( 'Want to give ThankYou Gift coupon to your customers ?', 'giftware' ),
		'id' => 'wps_wgm_thankyouorder_enable',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to enable gift coupon for those customers who had placed orders in your site', 'giftware' ),
		'desc' => __( 'Enable ThankYou Gift Coupon to Customers.', 'giftware' ),
	),
	array(
		'title' => __( 'Select the Order Status', 'giftware' ),
		'id' => 'wps_wgm_thankyouorder_time',
		'class' => 'wps_wgm_new_woo_ver_style_select',
		'type' => 'singleSelectDropDownWithKeyvalue',
		'desc_tip' => __( 'Select the status when the ThankYou Gift Coupon would be send', 'giftware' ),
		'custom_attribute' => array(
			array(
				'id' => 'wps_wgm_order_creation',
				'name' => 'Order Creation',
			),
			array(
				'id' => 'wps_wgm_order_processing',
				'name' => 'Order is in Processing',
			),
			array(
				'id' => 'wps_wgm_order_completed',
				'name' => 'Order is in Complete',
			),
		),
	),
	array(
		'title' => __( 'Number of Orders, after which the thankyou gift card would be sent', 'giftware' ),
		'id' => 'wps_wgm_thankyouorder_number',
		'type' => 'number',
		'default' => 1,
		'custom_attribute' => array( 'min' => '"1"' ),
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'desc_tip' => __( 'Enter the number of orders, after that, you want to give a thank you Gift Card to your customers', 'giftware' ),
	),
	array(
		'title' => __( 'ThankYou Coupon Expiry', 'giftware' ),
		'id' => 'wps_wgm_thnku_giftcard_expiry',
		'type' => 'number',
		'default' => 0,
		'custom_attribute' => array( 'min' => '"0"' ),
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'desc_tip' => __(
			'Enter number of days for Coupon Expiry,  Keep value "1" for one-day expiry 
after generating coupon, Keep value "0" for no expiry.',
			'giftware'
		),
	),
	array(
		'title' => __( 'Select ThankYou Gift Coupon Type', 'giftware' ),
		'id' => 'wps_wgm_thankyouorder_type',
		'class' => 'wps_wgm_new_woo_ver_style_select',
		'type' => 'singleSelectDropDownWithKeyvalue',
		'desc_tip' => __( 'Choose the ThankYou Gift Coupon Type for Customers', 'giftware' ),
		'custom_attribute' => array(
			array(
				'id' => 'wps_wgm_fixed_thankyou',
				'name' => 'Fixed',
			),
			array(
				'id' => 'wps_wgm_percentage_thankyou',
				'name' => 'Percentage',
			),
		),
	),
	array(
		'title' => __( 'Enter a Thankyou Message for your customers', 'giftware' ),
		'id' => 'wps_wgm_thankyou_message',
		'class' => 'input-text',
		'type' => 'textarea',
		'custom_attribute' => array(
			'rows' => '"3"',
		),
		'default' => __( 'You have received a coupon [COUPONCODE], having the amount of [COUPONAMOUNT] with the expiration date of [COUPONEXPIRY]', 'giftware' ),
		'desc_tip' => __( 'This message will print inside the Thankyou Gift coupon Template', 'giftware' ),
		'additional_info' => __( 'You may use shortcodes [COUPONCODE], [COUPONAMOUNT] and [COUPONEXPIRY]', 'giftware' ),
	),
	array(
		'type' => 'thankyouBox',
	),

);
$wps_uwgc_thankyou_order_settings = apply_filters( 'wps_uwgc_thankyou_order_settings', $wps_uwgc_thankyou_order_settings );
