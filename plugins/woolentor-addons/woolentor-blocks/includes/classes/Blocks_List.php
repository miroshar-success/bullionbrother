<?php
namespace WooLentorBlocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage Blocks
 */
class Blocks_List {
    
    /**
     * Block List
     *
     * @return Array
     */
    public static function get_block_list(){

        $blockList = [

            'brand_logo' => [
                'label'  => __('Brand Logo','woolentor'),
                'name'   => 'woolentor/brand-logo',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'category_grid' => [
                'label'  => __('Category Grid','woolentor'),
                'name'   => 'woolentor/category-grid',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'slick'
            ],
            'image_marker' => [
                'label'  => __('Image Marker','woolentor'),
                'name'   => 'woolentor/image-marker',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'special_day_offer' => [
                'label'  => __('Special Day Offer','woolentor'),
                'name'   => 'woolentor/special-day-offer',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'store_feature' => [
                'label'  => __('Store Feature','woolentor'),
                'name'   => 'woolentor/store-feature',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'product_tab' => [
                'label'  => __('Product tab','woolentor'),
                'name'   => 'woolentor/product-tab',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'slick',
            ],
            'promo_banner' => [
                'label'  => __('Promo Banner','woolentor'),
                'name'   => 'woolentor/promo-banner',
                'type'   => 'common',
                'active' => true,
            ],
            'faq' => [
                'label'  => __('FAQ','woolentor'),
                'name'   => 'woolentor/faq',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
                'script' => 'woolentor-accordion-min',
            ],
            'product_curvy' => [
                'label'  => __('Product Curvy','woolentor'),
                'name'   => 'woolentor/product-curvy',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'archive_title' => [
                'label'  => __('Archive Title','woolentor'),
                'name'   => 'woolentor/archive-title',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],
            'breadcrumbs' => [
                'label'  => __('Breadcrumbs','woolentor'),
                'name'   => 'woolentor/breadcrumbs',
                'server_side_render' => true,
                'type'   => 'common',
                'active' => true,
            ],

            'product_title' => [
                'label'  => __('Product Title','woolentor'),
                'name'   => 'woolentor/product-title',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_price' => [
                'label'  => __('Product Price','woolentor'),
                'name'   => 'woolentor/product-price',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_addtocart' => [
                'label'  => __('Product Add To Cart','woolentor'),
                'name'   => 'woolentor/product-addtocart',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_short_description' => [
                'label'  => __('Product Short Description','woolentor'),
                'name'   => 'woolentor/product-short-description',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_description' => [
                'label'  => __('Product Description','woolentor'),
                'name'   => 'woolentor/product-description',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_rating' => [
                'label'  => __('Product Rating','woolentor'),
                'name'   => 'woolentor/product-rating',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_image' => [
                'label'  => __('Product Image','woolentor'),
                'name'   => 'woolentor/product-image',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_meta' => [
                'label'  => __('Product Meta','woolentor'),
                'name'   => 'woolentor/product-meta',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_additional_info' => [
                'label'  => __('Product Additional Info','woolentor'),
                'name'   => 'woolentor/product-additional-info',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_tabs' => [
                'label'  => __('Product Tabs','woolentor'),
                'name'   => 'woolentor/product-tabs',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_stock' => [
                'label'  => __('Product Stock','woolentor'),
                'name'   => 'woolentor/product-stock',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_qrcode' => [
                'label'  => __('Product QR Code','woolentor'),
                'name'   => 'woolentor/product-qrcode',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_related' => [
                'label'  => __('Product Related','woolentor'),
                'name'   => 'woolentor/product-related',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],
            'product_upsell' => [
                'label'  => __('Product Upsell','woolentor'),
                'name'   => 'woolentor/product-upsell',
                'server_side_render' => true,
                'type'   => 'single',
                'active' => true,
            ],

            'shop_archive_product' => [
                'title'  => __('Archive Layout Default','woolentor'),
                'name'   => 'woolentor/shop-archive-default',
                'server_side_render' => true,
                'type'   => 'shop',
                'active' => true,
            ]
            
        ];

        return apply_filters( 'woolentor_block_list', $blockList );
        
    }


}
