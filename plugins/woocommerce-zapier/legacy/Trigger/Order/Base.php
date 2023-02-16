<?php

namespace OM4\Zapier\Trigger\Order;

use OM4\Zapier\Feed\Feed;
use OM4\Zapier\Logger;
use OM4\Zapier\Payload\Collection\DownloadableFiles;
use OM4\Zapier\Payload\Collection\LineItems;
use OM4\Zapier\Payload\Collection\Notes;
use OM4\Zapier\Payload\Item\DownloadableFile;
use OM4\Zapier\Payload\Item\LineItem;
use OM4\Zapier\Payload\Item\MetaData;
use OM4\Zapier\Payload\Item\Note;
use OM4\Zapier\Payload\Order as Payload;
use OM4\Zapier\Plugin;
use OM4\Zapier\Trigger\Base as TriggerBase;
use WC_Order_Item_Product;
use WC_Order;

defined( 'ABSPATH' ) || exit;

/**
 * Base (abstract) class of Order related Triggers
 *
 * @deprecated 2.0.0
 */
abstract class Base extends TriggerBase {

	/**
	 * WC_Order instance.
	 *
	 * @var WC_Order
	 */
	protected $wc_order;

	/**
	 * The slug/key for the order status.
	 * Must correspond to a valid WooCommerce order status
	 *
	 * @var string
	 */
	protected $status_slug = '';

	/**
	 * Downloadable files collected from every line item
	 *
	 * @var DownloadableFiles
	 */
	protected $downloadable_files;

	/**
	 * Optional text that is added to the end of this Trigger's title inside brackets.
	 *
	 * @var string|null
	 */
	protected $title_suffix;

	/**
	 * Holds the Logger class.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->downloadable_files = new DownloadableFiles();
		$this->logger             = new Logger();
		parent::__construct();
	}

	/**
	 * The sample WooCommerce order data that is sent to Zapier as sample data.
	 * Used if the store doesn't have any existing order data
	 *
	 * @return array
	 */
	protected function get_sample_data() {
		// Use a random order ID so the "Pick a Sample to Set Up Your Zap"
		// screen shows the most recent sample data.
		$order = Payload::from_sample();
		// phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
		$order->id = rand( 1000, 100000 );
		return $order->to_array();
	}

	/**
	 * Collect downloadable files for order line items
	 * Only included once the customer has permission to download the files
	 * (typically when the order status is Processing or Completed).
	 *
	 * @see https://docs.woocommerce.com/document/digitaldownloadable-product-handling/#section-3
	 * @param array $downloadable_files_data Data of file associated with Line Item.
	 *
	 * @return void Writes to the $downloadable_files property.
	 */
	protected function assemble_downloadable_files( $downloadable_files_data ) {
		// TODO: also include WC 2.1+ downloadable file name.
		foreach ( $downloadable_files_data as $download_id => $download_details ) {
			$file                       = new DownloadableFile();
			$file->filename             = wc_get_filename_from_url( $download_details['file'] );
			$file->download_url         = $download_details['download_url'];
			$this->downloadable_files[] = $file;
		}
	}

	/**
	 * Collect meta information for order.
	 *
	 * @param WC_Meta_Data[] $item_meta Order meta data.
	 *
	 * @return MetaData
	 */
	protected function assemble_meta_data_for_order( $item_meta ) {
		$meta_data = new MetaData();
		foreach ( $item_meta as $meta ) {
			$meta_key             = Plugin::decode( $meta->key );
			$meta_data->$meta_key = Plugin::decode( $meta->value );
		}
		return $meta_data;
	}

	/**
	 * Collect meta information for line item.
	 *
	 * @param array $item_meta Line item meta data.
	 *
	 * @return MetaData
	 */
	protected function assemble_meta_data_for_line_item( $item_meta ) {
		$meta_data = new MetaData();
		foreach ( $item_meta as $meta_key => $meta_value ) {
			$meta_key             = Plugin::decode( $meta_key );
			$meta_data->$meta_key = Plugin::decode( $meta_value );
		}
		return $meta_data;
	}

	/**
	 * Collect notes
	 *
	 * @param array $notes_array Output of WC_Order::get_customer_order_notes().
	 *
	 * @return Notes
	 */
	protected function assemble_notes( $notes_array ) {
		$collection = new Notes();
		foreach ( $notes_array as $note_data ) {
			$note               = new Note();
			$note->note         = $note_data->comment_content;
			$note->date         = Plugin::format_date( $note_data->comment_date );
			$note->author       = $note_data->comment_author;
			$note->author_email = $note_data->comment_author_email;
			$collection[]       = $note;
		}
		return $collection;
	}

	/**
	 * Collect all data for one line item for order
	 *
	 * @param WC_Order_Item_Product $line_item_data Class with all the line item data.
	 *
	 * @return LineItem
	 */
	protected function assemble_one_line_item( WC_Order_Item_Product $line_item_data ) {
		$product = $line_item_data->get_product();

		$line_item               = new LineItem();
		$line_item->name         = $line_item_data->get_name();
		$line_item->quantity     = $line_item_data->get_quantity();
		$line_item->product_id   = $line_item_data->get_product_id();
		$line_item->variation_id = $line_item_data->get_variation_id();
		$line_item->sku          = $product ? $product->get_sku() : '';
		$line_item->type         = ( false !== $product ) ? $product->get_type() : '';

		/* Getting the categories and tags */

		// If the product is variation we getting the parent product.
		if ( false !== $product && $product->is_type( 'variation' ) ) {
			$product = wc_get_product( $product->get_parent_id() );
		}

		/*
		Also allow for the case where the order/subscription is for a product
		that no longer exists. In this case, $product will be false,
		and category/tags/type will be sent as empty.
		*/

		// Product Categories.
		$line_item->categories = '';
		if ( false !== $product ) {
			$categories = wc_get_product_category_list( $product->get_id() );
			if ( is_string( $categories ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
				$line_item->categories = strip_tags( $categories );
			}
		}

		// Product Tags.
		$line_item->tags = '';
		if ( false !== $product ) {
			$tags = wc_get_product_tag_list( $product->get_id() );
			if ( is_string( $tags ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags
				$line_item->tags = strip_tags( $tags );
			}
		}

		// Line Item Data.
		$line_item->unit_price        = Plugin::format_price( $this->wc_order->get_item_total( $line_item_data, false, true ) );
		$line_item->line_subtotal     = Plugin::format_price( $line_item_data->get_subtotal() );
		$line_item->line_total        = Plugin::format_price( $line_item_data->get_total() );
		$line_item->line_tax          = Plugin::format_price( $line_item_data->get_total_tax() );
		$line_item->line_subtotal_tax = Plugin::format_price( $line_item_data->get_subtotal_tax() );
		$line_item->tax_class         = $line_item_data->get_tax_class();
		$line_item->item_meta         = $this->assemble_meta_data_for_line_item( $line_item_data['item_meta'] );

		// Collect downloadable files data from every line item.
		$this->assemble_downloadable_files( $line_item_data->get_item_downloads() );

		return $line_item;
	}

	/**
	 * Collect every line item for order
	 * NOTE: WC_Order_Item::get_product() without arguments returns only array
	 * of WC_Order_Item_Product::class.
	 *
	 * @return LineItems
	 */
	protected function assemble_line_items() {
		$collection = new LineItems();
		foreach ( $this->wc_order->get_items() as $line_item_data ) {
			$collection[] = $this->assemble_one_line_item( $line_item_data );
		}
		return $collection;
	}

	/**
	 * Collect assign and convert data to assemble data for Zapier
	 *
	 * @param  array  $args        Array of ID, new_status & previous_status (if applicable).
	 * @param  string $action_name Name of WP action that changed the order state.
	 *
	 * @return array
	 */
	public function assemble_data( $args, $action_name ) {

		// The webhook/trigger is being tested. Send the store's most recent
		// order, or if that doesn't exist then send the static hard-coded
		// sample order data.
		if ( $this->is_sample() ) {
			$orders = wc_get_orders(
				array(
					'type'    => 'shop_order',
					'limit'   => 1,
					'orderby' => 'date',
					'order'   => 'DESC',
					'return'  => 'ids',
				)
			);

			// No existing orders found, so send static hard-coded order
			// sample data.
			if ( ! $orders || ! isset( $orders[0] ) ) {
				return $this->get_sample_data();
			}

			$args[0] = $orders[0];
		}

		/* Using real live data from now */

		$order_id = intval( $args[0] );
		if ( ! $order_id ) {
			return false;
		}

		$this->wc_order = new WC_Order( $order_id );

		// Check and prepare current and previous states. If we don't know the
		// previous status, nothing special required here.
		// NOTE: order statuses can be a-z characters or a hyphen.
		if ( 'woocommerce_order_status_changed' === $action_name ) {
			$previous_status = $args[1];
			$new_status      = $args[2];
		} elseif (
			preg_match(
				'/^woocommerce_order_status_([a-z-]+)_to_([a-z-]+)$/i',
				$action_name,
				$matches
			)
		) {
			$previous_status = $matches[1];
			$new_status      = $matches[2];
		} else {
			$new_status      = $this->wc_order->get_status();
			$previous_status = '';
		}

		/* NOTE: this could fire for any order statuses (including pending/unpaid). */

		// Compile the order details/data that will be sent to Zapier.
		$order = new Payload();

		// Getting similarly named fields from WC.
		$similar_fields = array(
			'id',
			'user_id',
			'currency',
			'transaction_id',
			'billing_first_name',
			'billing_last_name',
			'billing_company',
			'billing_email',
			'billing_phone',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_postcode',
			'billing_state',
			'billing_country',
			'shipping_first_name',
			'shipping_last_name',
			'shipping_company',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_city',
			'shipping_postcode',
			'shipping_state',
			'shipping_country',
			'shipping_method',
			'prices_include_tax',
			'customer_note',
		);

		foreach ( $similar_fields as $property ) {
			$method           = 'get_' . $property;
			$order->$property = $this->wc_order->$method();
		}

		// Getting similarly named fields from WC which all need to be price
		// formatted.
		$similar_priced_fields = array(
			'total',
			'subtotal',
			'shipping_total',
			'shipping_tax',
		);

		foreach ( $similar_priced_fields as $property ) {
			$method           = 'get_' . $property;
			$order->$property = Plugin::format_price( $this->wc_order->$method() );
		}

		// Getting customized details.
		$order->number           = $this->wc_order->get_order_number();
		$order->status           = $new_status;
		$order->status_previous  = $previous_status;
		$order->date             = Plugin::format_date( $this->wc_order->get_date_created() );
		$order->currency_symbol  = Plugin::decode( get_woocommerce_currency_symbol( $order->currency ) );
		$order->view_url         = $this->wc_order->get_view_order_url();
		$order->billing_address  = Plugin::decode( $this->wc_order->get_formatted_billing_address() );
		$order->shipping_address = Plugin::decode( $this->wc_order->get_formatted_shipping_address() );
		$order->payment_method   = $this->wc_order->get_payment_method_title();
		$order->discount_total   = '';
		$order->cart_discount    = Plugin::format_price( $this->wc_order->get_total_discount() );
		$order->tax_total        = Plugin::format_price( $this->wc_order->get_cart_tax() );

		/* Country & state names */

		// Filling country names.
		if ( ! empty( $order->billing_country ) && isset( WC()->countries->countries[ $order->billing_country ] ) ) {
			$order->billing_country_name = WC()->countries->countries[ $order->billing_country ];
		} else {
			$order->billing_country_name = '';
		}
		if ( ! empty( $order->shipping_country ) && isset( WC()->countries->countries[ $order->shipping_country ] ) ) {
			$order->shipping_country_name = WC()->countries->countries[ $order->shipping_country ];
		} else {
			$order->shipping_country_name = '';
		}

		// Filling state names.
		if ( ! empty( $order->billing_state ) && isset( WC()->countries->states[ $order->billing_country ][ $order->billing_state ] ) ) {
			$order->billing_state_name = WC()->countries->states[ $order->billing_country ][ $order->billing_state ];
		} else {
			$order->billing_state_name = '';
		}
		if ( ! empty( $order->shipping_state ) && isset( WC()->countries->states[ $order->shipping_country ][ $order->shipping_state ] ) ) {
			$order->shipping_state_name = WC()->countries->states[ $order->shipping_country ][ $order->shipping_state ];
		} else {
			$order->shipping_state_name = '';
		}

		// Order Line Items.
		$order->line_items = $this->assemble_line_items();
		$order->item_count = $this->wc_order->get_item_count();

		// A comma-separated list of coupon codes that were used for this order.
		// Method renamed on WC 3.7 from get_used_coupons to get_coupon_codes.
		if ( method_exists( $this->wc_order, 'get_coupon_codes' ) ) {
			$order->coupons = implode( ', ', $this->wc_order->get_coupon_codes() );
		} else {
			$order->coupons = implode( ', ', $this->wc_order->get_used_coupons() );
		}

		// Downloadable files collected from every line item.
		$order->has_downloadable_item = $this->wc_order->has_downloadable_item();
		$order->downloadable_files    = $this->downloadable_files;

		// Customer Notes.
		$order->notes = $this->assemble_notes( $this->wc_order->get_customer_order_notes() );

		// Order Meta data.
		$order->meta_data = $this->assemble_meta_data_for_order( $this->wc_order->get_meta_data() );

		$this->logger->debug( 'Order #%s: Assembled order data.', $order->id );

		// Order data needs to be an array.
		return $order->to_array();
	}

	/**
	 * Executed every time real Order data is sent to a Zapier Feed.
	 * Not executed when sample Order data is sent.
	 *
	 * @param Feed   $feed         Feed data.
	 * @param array  $result       Response from the wp_remote_post() call.
	 * @param string $action_name  Hook/action name (needed to be able to retry failed attempts).
	 * @param array  $arguments    Hook/action arguments (needed to be able to retry failed attempts).
	 * @param int    $num_attempts The number of attempts it took to successfully send the data to Zapier.
	 */
	protected function data_sent_to_feed( Feed $feed, $result, $action_name, $arguments, $num_attempts = 0 ) {
		$note = '';

		if ( 1 === $num_attempts ) {
			// Successful on the first attempt.
			// Translators: %1$s: The URL that of the Edit Feed screen. %2$s: The Title/Name of this Zapier Feed.
			$note .= sprintf( __( 'Order sent to Zapier via the <a href="%1$s">%2$s</a> Zapier feed.', 'woocommerce-zapier' ), $feed->edit_url(), $feed->title() );
		} else {
			// It took more than 1 attempt so add that to the note.
			// Translators: %1$s: The URL that of the Edit Feed screen. %2$s: The Title/Name of this Zapier Feed.
			$note .= sprintf( __( 'Order sent to Zapier via the <a href="%1$s">%2$s</a> Zapier feed after %3$d attempts.', 'woocommerce-zapier' ), $feed->edit_url(), $feed->title(), $num_attempts );
		}

		// Translators: %1$s: The title of this trigger. %2$s: Name of this action.
		$note .= sprintf( _x( '<br ><br />Trigger:<br />%1$s<br />%2$s', 'Order trigger details.', 'woocommerce-zapier' ), $feed->trigger()->get_trigger_title(), "<small>{$action_name}</small>" );

		$note .= $this->data_sent_note_suffix( $feed, $result, $action_name, $arguments, $num_attempts );

		// Add a private note to this order.
		$this->wc_order->add_order_note( $note );

		$this->logger->debug( "Order #%s: Added note:\n%s", array( $this->wc_order->get_id(), $note ) );

		parent::data_sent_to_feed( $feed, $result, $action_name, $arguments, $num_attempts );

	}

	/**
	 * Add optional information to the end of the Order Note that is added to an Order after it is sent to Zapier.
	 *
	 * @param Feed   $feed         Feed data.
	 * @param array  $result       Response from the wp_remote_post() call.
	 * @param string $action_name  Hook/action name (needed to be able to retry failed attempts).
	 * @param array  $arguments    Hook/action arguments (needed to be able to retry failed attempts).
	 * @param int    $num_attempts The number of attempts it took to successfully send the data to Zapier.
	 *
	 * @return string
	 */
	protected function data_sent_note_suffix( Feed $feed, $result, $action_name, $arguments, $num_attempts = 0 ) {
		return '';
	}
}
