<?php

namespace OM4\Zapier\Payload;

use OM4\Zapier\Payload\Base\Item;
use OM4\Zapier\Payload\Collection\DownloadableFiles;
use OM4\Zapier\Payload\Collection\LineItems;
use OM4\Zapier\Payload\Collection\Notes;
use OM4\Zapier\Payload\Item\BillingTrait;
use OM4\Zapier\Payload\Item\MetaData;
use OM4\Zapier\Payload\Item\ShippingTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Implement base structure requirements for Basic Order Object.
 *
 * @deprecated 2.0.0
 */
class Order extends Item {

	/**
	 * Holds the type information for validate
	 *
	 * @var array
	 */
	protected static $property_types = array(
		'id'                    => 'int',
		'number'                => 'string',
		'date'                  => 'string',
		'status'                => 'string',
		'status_previous'       => 'string',
		'payment_method'        => 'string',
		'transaction_id'        => 'string',
		'view_url'              => 'string',
		'user_id'               => 'int',
		'billing_first_name'    => 'string',
		'billing_last_name'     => 'string',
		'billing_company'       => 'string',
		'billing_email'         => 'string',
		'billing_phone'         => 'string',
		'billing_address'       => 'string',
		'billing_address_1'     => 'string',
		'billing_address_2'     => 'string',
		'billing_city'          => 'string',
		'billing_state'         => 'string',
		'billing_state_name'    => 'string',
		'billing_postcode'      => 'string',
		'billing_country'       => 'string',
		'billing_country_name'  => 'string',
		'shipping_first_name'   => 'string',
		'shipping_last_name'    => 'string',
		'shipping_company'      => 'string',
		'shipping_address'      => 'string',
		'shipping_address_1'    => 'string',
		'shipping_address_2'    => 'string',
		'shipping_city'         => 'string',
		'shipping_state'        => 'string',
		'shipping_state_name'   => 'string',
		'shipping_postcode'     => 'string',
		'shipping_country'      => 'string',
		'shipping_country_name' => 'string',
		'currency'              => 'string',
		'currency_symbol'       => 'string',
		'item_count'            => 'int|double|string',
		'line_items'            => '\\OM4\\Zapier\\Payload\\Collection\\LineItems',
		'prices_include_tax'    => 'bool',
		'total'                 => 'string',
		'subtotal'              => 'string',
		'tax_total'             => 'string',
		'cart_discount'         => 'string',
		'discount_total'        => 'string',
		'coupons'               => 'string',
		'shipping_total'        => 'string',
		'shipping_tax'          => 'string',
		'shipping_method'       => 'string',
		'has_downloadable_item' => 'bool',
		'downloadable_files'    => '\\OM4\\Zapier\\Payload\\Collection\\DownloadableFiles',
		'customer_note'         => 'string',
		'notes'                 => '\\OM4\\Zapier\\Payload\\Collection\\Notes',
		'meta_data'             => '\\OM4\\Zapier\\Payload\\Item\\MetaData',
	);

	/**
	 * Order ID.
	 *
	 * @var  int
	 */
	protected $id;

	/**
	 * Order Number. Normally the same as the Order ID, unless using an extension such as
	 *   the Sequential Order Numbers Pro plugin, which lets you customize the order number format.
	 *
	 * @since 1.1
	 * @var  string
	 */
	protected $number;

	/**
	 * Order Date. Order date/time (in W3C format)
	 *
	 * @var  string
	 */
	protected $date;

	/**
	 * Order Status. Order status. Valid values for this field are: pending, failed, on-hold,
	 *   processing, completed, refunded, cancelled
	 *
	 * @var  string
	 */
	protected $status;

	/**
	 * Previous Order Status. The Order's previous status. Valid values for this field are: pending,
	 *   failed, on-hold, processing, completed, refunded, cancelled
	 *
	 * @var  string
	 */
	protected $status_previous;

	/**
	 * Payment Method Title. The name of the payment method used for this order.
	 *
	 * @var  string
	 */
	protected $payment_method;

	/**
	 * Transaction ID. The order's transaction ID. May be empty, depending on the status of the
	 *   order.
	 *
	 * @since  1.6.4
	 * @var  string
	 */
	protected $transaction_id;

	/**
	 * View Order URL. The URL to view the order from the my account page.
	 *
	 * @var  string
	 */
	protected $view_url;

	/**
	 * User ID. Gets the user/customer ID associated with the order. Guests are 0.
	 *
	 * @var  int
	 */
	protected $user_id;

	use BillingTrait;

	use ShippingTrait;

	/**
	 * Currency Code. 3-character currency code. Should match the currency configured in
	 *   Dashboard, WooCommerce, Settings, General Options, Currency.
	 *
	 * @var  string
	 */
	protected $currency;

	/**
	 * Currency Symbol. Currency symbol. Should match the currency configured in Dashboard,
	 *   WooCommerce, Settings, General Options, Currency. Typically $ or € or ¥
	 *   etc.
	 *
	 * @var  string
	 */
	protected $currency_symbol;

	/**
	 * Item Count. The number of items in this order
	 *
	 * @var  int|double|string
	 */
	protected $item_count;

	/**
	 * Order Line Items. List of line items contained in this order.
	 *
	 * @since  1.2.0
	 * @var  \OM4\Zapier\Payload\Collection\LineItems
	 */
	protected $line_items;

	/**
	 * Do Prices Include Tax? Whether or not the prices in this order are inclusive of tax
	 *
	 * @var  bool
	 */
	protected $prices_include_tax;

	/**
	 * Order Total. Total Order Amount (includes tax if applicable)
	 *
	 * @var  string
	 */
	protected $total;

	/**
	 * Order Subtotal. Subtotal - The total of all line items, pre tax and excluding shipping.
	 *   (requires WooCommerce v2.2 or later)
	 *
	 * @since  1.4.0
	 * @var  string
	 */
	protected $subtotal;

	/**
	 * Tax Total. Total Tax Amount of the cart (exclude shipping)
	 *
	 * @var  string
	 */
	protected $tax_total;

	/**
	 * Discount Cart Amount. Total (product) discount amount - these are applied before tax.
	 *
	 * @var  string
	 */
	protected $cart_discount;

	/**
	 * Discount Total Amount. Total discount amount
	 *
	 * @deprecated  1.5.0 WooCommerce v2.3 does not have a concept of an after tax discount
	 * @see https://woocommerce.wordpress.com/2014/12/12/upcoming-coupon-changes-in-woocommerce-2-3/
	 * @var  string
	 */
	protected $discount_total;

	/**
	 * Coupons Codes. A (comma-separated) list of coupon codes used with this Order.
	 *
	 * @since  1.7.2
	 * @var  string
	 */
	protected $coupons;

	/**
	 * Shipping Total Amount. Shipping total/cost amount
	 *
	 * @var  string
	 */
	protected $shipping_total;

	/**
	 * Shipping Tax Amount. Shipping tax amount
	 *
	 * @var  string
	 */
	protected $shipping_tax;

	/**
	 * Shipping Method Title. The name of the shipping method used or this order.
	 *
	 * @var  string
	 */
	protected $shipping_method;

	/**
	 * Has Downloadable Item?. Whether or not this order contains a downloadable file/item
	 *
	 * @var  bool
	 */
	protected $has_downloadable_item;

	/**
	 * List of downloadable files/items. Only included once the customer has permission to download the files
	 *   (typically when the order status is Processing or Completed). See
	 *   http://bit.ly/LseaXx for more details. Each line item has the following
	 *   format: filename: File Name download_url: URL to download the downloadable
	 *   file from.
	 *
	 * @var  \OM4\Zapier\Payload\Collection\DownloadableFiles
	 */
	protected $downloadable_files;

	/**
	 * Customer Note. The note/comment that is added by the customer during
	 *   checkout. This is the data field that is called "Customer Note" on the
	 *   edit order dashboard screen.
	 *
	 * @var  string
	 */
	protected $customer_note;

	/**
	 * List of order notes. List of order notes (private notes aren't included).
	 *   These are the purple line item has the following format: note:
	 *   Note/Comment date: Note/Comment Date author: Comment Author
	 *   author_email: Comment Author's Email Address
	 *
	 * @var  \OM4\Zapier\Payload\Collection\Notes
	 */
	protected $notes;

	/**
	 * Order Meta Data. Order custom fields (order metadata).
	 *
	 * @since  1.7.1
	 * @var  \OM4\Zapier\Payload\Item\MetaData
	 */
	protected $meta_data;
}
