<?php
 /**
  * Exit if accessed directly
  *
  * @package    woo-gift-cards-lite
  */

if ( ! defined( 'ABSPATH' ) ) {
	 exit();
}

require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
$wps_wgm_product_settings = array(
	array(
		'title' => esc_html__( 'Exclude Sale Items', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_product_setting_giftcard_ex_sale',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => esc_html__( 'Check this box if the Giftcard Coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.', 'woo-gift-cards-lite' ),
		'desc' => esc_html__( 'Enable to exclude Sale Items', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Exclude Products', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_product_setting_exclude_product',
		'type' => 'search&select',
		'multiple' => 'multiple',
		'custom_attribute' => array(
			'style' => '"width:50%;"',
			'class' => '"wc-product-search"',
			'data-action' => '"woocommerce_json_search_products_and_variations"',
			'data-placeholder' => esc_html__( 'Search for a product', 'woo-gift-cards-lite' ),
		),
		'desc_tip' => esc_html__( 'Products which must not be in the cart to use Gift card coupon or, for "Product Discounts", which products are not discounted.', 'woo-gift-cards-lite' ),
		'options' => $settings_obj->wps_wgm_get_product( 'wps_wgm_product_setting_exclude_product', 'wps_wgm_product_settings' ),
	),
	array(
		'title' => esc_html__( 'Exclude Product Category', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_product_setting_exclude_category',
		'type' => 'search&select',
		'multiple' => 'multiple',
		'desc_tip' => esc_html__( 'Product must not be in this category for the Gift Card coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted.', 'woo-gift-cards-lite' ),
		'options' => $settings_obj->wps_wgm_get_category(),
	),
);
 $wps_wgm_product_settings = apply_filters( 'wps_wgm_product_settings', $wps_wgm_product_settings );
