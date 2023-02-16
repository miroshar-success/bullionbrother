<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Points List Page
 * 
 * The html markup for the Points list
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */

// declare the product class
class WC_Product_woo_pr_points extends WC_Product {

    /**
	 * Initialize simple product.
	 *
	 * @param mixed $product
	 */
	public function __construct( $product = 0 ) {
		$this->supports[]   = 'ajax_add_to_cart';
		parent::__construct( $product );
	}

    /**
     * Get internal type.
     * Needed for WooCommerce 3.0 Compatibility
     * @return string
     */
    public function get_type() {
        return 'woo_pr_points';
    }

	/**
	 * Get the add to cart button text description - used in aria tags.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function add_to_cart_description() {
		/* translators: %s: Product title */
		$text = $this->is_purchasable() && $this->is_in_stock() ? esc_html__( 'Add &ldquo;%s&rdquo; to your cart', 'woopoints' ) : esc_html__( 'Read more about &ldquo;%s&rdquo;', 'woocommerce' );

		return apply_filters( 'woo_pr_product_add_to_cart_description', sprintf( $text, $this->get_name() ), $this );
	}
}