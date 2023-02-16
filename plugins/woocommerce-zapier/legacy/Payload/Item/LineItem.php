<?php

namespace OM4\Zapier\Payload\Item;

use OM4\Zapier\Payload\Base\Item;
use OM4\Zapier\Payload\Item\MetaData;

defined( 'ABSPATH' ) || exit;

/**
 * Implement base structure requirements for Line Item Object.
 *
 * @deprecated 2.0.0
 */
class LineItem extends Item {

	/**
	 * Holds the type information for validate
	 *
	 * @var array
	 */
	protected static $property_types = array(
		'name'              => 'string',
		'quantity'          => 'int|double',
		'product_id'        => 'int',
		'variation_id'      => 'int',
		'sku'               => 'string',
		'categories'        => 'string',
		'tags'              => 'string',
		'type'              => 'string',
		'unit_price'        => 'string',
		'line_subtotal'     => 'string',
		'line_total'        => 'string',
		'line_tax'          => 'string',
		'line_subtotal_tax' => 'string',
		'tax_class'         => 'string',
		'item_meta'         => '\\OM4\\Zapier\\Payload\\Item\\MetaData',
	);

	/**
	 * Product Name.
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $name;

	/**
	 * Product Quantity.
	 *
	 * @since  1.2.0
	 * @var  int|double
	 */
	protected $quantity;

	/**
	 * Product ID.
	 *
	 * @since  1.2.0
	 * @var  int
	 */
	protected $product_id;

	/**
	 * Variation ID. (if the product is a variable product)
	 *
	 * @since  1.2.0
	 * @var  int
	 */
	protected $variation_id;

	/**
	 * Product SKU.
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $sku;

	/**
	 * Product Categories. (comma-separated)
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $categories;

	/**
	 * Product Tags. (comma-separated)
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $tags;

	/**
	 * Product Type. (simple, variation)
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $type;

	/**
	 * Line item unit price. (item cost) excluding tax
	 *
	 * @since  1.7.0
	 * @var  string
	 */
	protected $unit_price;

	/**
	 * Line Subtotal Amount Excluding Tax (before discounts)
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $line_subtotal;

	/**
	 * Line Total Amount Excluding Tax (after discounts)
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $line_total;

	/**
	 * Line Tax Amount (after discounts).
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $line_tax;

	/**
	 * Line Subtotal Tax Amount (before discounts).
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $line_subtotal_tax;

	/**
	 * Product Tax Class.
	 *
	 * @since  1.2.0
	 * @var  string
	 */
	protected $tax_class;

	/**
	 * Array of order line item meta data. Typically empty unless using a plugin/extension that adds custom order item
	 *   meta data. Product Add-Ons and Gravity Forms Product Add-Ons data is
	 *   included here.
	 *
	 * @since  1.2.0
	 * @var  \OM4\Zapier\Payload\Item\MetaData
	 */
	protected $item_meta;
}
