<?php
/**
 * Hooks.
 */

namespace WLPF\Frontend;

/**
 * Class.
 */
class Hooks {

	/**
     * Constructor.
     */
    public function __construct() {
        add_filter( 'paginate_links', 'wlpf_hooked_paginate_links' );

        add_action( 'woocommerce_before_shop_loop', 'wlpf_hooked_before_shop_loop', -10000 );
        add_action( 'woocommerce_after_shop_loop', 'wlpf_hooked_after_shop_loop', 10000 );

        add_action( 'woocommerce_no_products_found', 'wlpf_hooked_before_shop_loop', -10000 );
        add_action( 'woocommerce_no_products_found', 'wlpf_hooked_after_shop_loop', 10000 );
    }

}