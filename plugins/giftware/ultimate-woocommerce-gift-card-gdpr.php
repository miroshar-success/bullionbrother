<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the default suggested privacy policy content.
 *
 * @return string The default policy content.
 */
function wps_pro_plugin_get_default_privacy_content() {
	return '<h2>' . __( 'Store Recipient Details for sending Gift Card', 'giftware' ) . '</h2>' .
	'<p>' . __( "We store your recipient's email address, recipient's name, gift message, your name so that we can send them again your Gift Card with all proper details have been filled by you at the time of purchasing Gift Card Product if they have arrived", 'giftware' ) . '</p>';
}

/**
 * Add the suggested privacy policy text to the policy postbox.
 */
function wps_pro_plugin_add_suggested_privacy_content() {
	$content = wps_pro_plugin_get_default_privacy_content();
	wp_add_privacy_policy_content( __( 'Ultimate Woocommerce Gift Card', 'giftware' ), $content );
}

add_action( 'admin_init', 'wps_pro_plugin_add_suggested_privacy_content', 20 );

/**
 * Register exporter for Plugin user data.
 *
 * @see https://github.com/allendav/wp-privacy-requests/blob/master/EXPORT.md
 * @param mixed $exporters Exporters.
 * @return array
 */
function wps_pro_plugin_register_exporters( $exporters ) {
	$exporters[] = array(
		'exporter_friendly_name' => __( 'Recipient Details', 'giftware' ),
		'callback'               => 'wps_pro_plugin_user_data_exporter',
	);
	return $exporters;
}

add_filter( 'wp_privacy_personal_data_exporters', 'wps_pro_plugin_register_exporters' );

/**
 * Exporter for Plugin user data.
 *
 * @see https://github.com/allendav/wp-privacy-requests/blob/master/EXPORT.md
 * @param mixed $email_address Email address.
 * @param int   $page Page.
 * @return array
 */
function wps_pro_plugin_user_data_exporter( $email_address, $page = 1 ) {
	$export_items = array();
	$user = get_user_by( 'email', $email_address );
	if ( $user && $user->ID ) {

		$item_id = "wps-wgm-recipient-details-{$user->ID}";

		$group_id = 'wps-wgm-recipient-details';

		$group_label = __( 'Gift Card Details', 'giftware' );

		// Plugins can add as many items in the item data array as they want.
		$data = array();

		// Add the user's recipient's details, and along with user itself.

		// Get all customer orders.
		$customer_orders = get_posts(
			array(
				'numberposts' => -1,
				'meta_key'    => '_customer_user',
				'meta_value'  => $user->ID,
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_order_statuses() ),
			)
		);
		if ( isset( $customer_orders ) && ! empty( $customer_orders ) ) {
			foreach ( $customer_orders as $order_key => $orders ) {
				$order_id = $orders->ID;
				if ( isset( $order_id ) && ! empty( $order_id ) ) {
					$order = wc_get_order( $order_id );
					foreach ( $order->get_items() as $item_id => $item ) {
						$item_meta_data = $item->get_meta_data();
						$to = '';
						$to_name = '';
						$from = '';
						$gift_msg = '';
						$gift_img_name = '';
						if ( ! empty( $item_meta_data ) ) {
							foreach ( $item_meta_data as $key => $value ) {
								if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
									$to = $value->value;
								}
								if ( isset( $value->key ) && 'To Name' == $value->key && ! empty( $value->value ) ) {
									$to_name = $value->value;
								}
								if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
									$from = $value->value;
								}
								if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
									$gift_msg = $value->value;
								}
								if ( isset( $value->key ) && 'Image' == $value->key && ! empty( $value->value ) ) {
									$gift_img_name = $value->value;
								}
							}
							// Add these data into $data.
							if ( ! empty( $to ) ) {
								$data[] = array(
									'name'  => __( 'Recipient Name/Email', 'giftware' ),
									'value' => $to,
								);
							}
							if ( ! empty( $to_name ) ) {
								  $data[] = array(
									  'name'  => __( 'Recipient Name', 'giftware' ),
									  'value' => $to_name,
								  );
							}
							if ( ! empty( $from ) ) {
								$data[] = array(
									'name'  => __( 'Buyer Name/Email', 'giftware' ),
									'value' => $from,
								);
							}
							if ( ! empty( $gift_msg ) ) {
								$data[] = array(
									'name'  => __( 'Gift Message', 'giftware' ),
									'value' => $gift_msg,
								);
							}
							if ( ! empty( $gift_img_name ) ) {
								$data[] = array(
									'name'  => __( 'Uploaded Image', 'giftware' ),
									'value' => $gift_img_name,
								);
							}
						}
						// Add $data to $export items.
						// Add this group of items to the exporters data array.
						$export_items[] = array(
							'group_id'    => $group_id,
							'group_label' => $group_label,
							'item_id'     => $item_id,
							'data'        => $data,
						);
					}
				}
			}
		}
	}
	// Returns an array of exported items for this pass, but also a boolean whether this exporter is finished.
	// If not it will be called again with $page increased by 1.
	return array(
		'data' => $export_items,
		'done' => true,
	);
}

/**
 * Register eraser for Plugin user data.
 *
 * @param array $erasers erasers.
 * @return array
 */
function wps_pro_plugin_register_erasers( $erasers = array() ) {
	$erasers[] = array(
		'eraser_friendly_name' => __( 'Recipient Details', 'giftware' ),
		'callback'               => 'wps_pro_plugin_user_data_eraser',
	);
	return $erasers;
}

add_filter( 'wp_privacy_personal_data_erasers', 'wps_pro_plugin_register_erasers' );

/**
 * Eraser for Plugin user data.
 *
 * @param mixed $email_address Email Address.
 * @param int   $page Page.
 * @return array
 */
function wps_pro_plugin_user_data_eraser( $email_address, $page = 1 ) {
	if ( empty( $email_address ) ) {
		return array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);
	}
	$user = get_user_by( 'email', $email_address );
	$messages = array();
	$items_removed  = false;
	$items_retained = false;
	if ( $user && $user->ID ) {
		// Delete their order meta keys.

		$customer_orders = get_posts(
			array(
				'numberposts' => -1,
				'meta_key'    => '_customer_user',
				'meta_value'  => $user->ID,
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_order_statuses() ),
			)
		);
		if ( isset( $customer_orders ) && ! empty( $customer_orders ) ) {
			foreach ( $customer_orders as $order_key => $orders ) {
				$order_id = $orders->ID;
				if ( isset( $order_id ) && ! empty( $order_id ) ) {
					$order = wc_get_order( $order_id );
					foreach ( $order->get_items() as $item_id => $item ) {
						$item_meta_data = $item->get_meta_data();
						$to = '';
						$to_name = '';
						$from = '';
						$gift_msg = '';
						$gift_img_name = '';
						if ( ! empty( $item_meta_data ) ) {
							foreach ( $item_meta_data as $key => $value ) {
								if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
									if ( $status ) {
										$items_removed  = true;
									} else {
										$messages[] = __( 'Removed key "TO"', 'giftware' );
										$items_retained = true;
									}
								}
								if ( isset( $value->key ) && 'To Name' == $value->key && ! empty( $value->value ) ) {
									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
									if ( $status ) {
										$items_removed  = true;
									} else {
										$messages[] = __( 'Removed key "TO Name"', 'giftware' );
										$items_retained = true;
									}
								}
								if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
									if ( $status ) {
										$items_removed  = true;
									} else {
										$messages[] = __( 'Removed key "From"', 'giftware' );
										$items_retained = true;
									}
								}
								if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
									if ( $status ) {
										$items_removed  = true;
									} else {
										$messages[] = __( 'Removed key "Message"', 'giftware' );
										$items_retained = true;
									}
								}
								if ( isset( $value->key ) && 'Image' == $value->key && ! empty( $value->value ) ) {
									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
									if ( $status ) {
										$items_removed  = true;
									} else {
										$messages[] = __( 'Removed key "Image"', 'giftware' );
										$items_retained = true;
									}
								}
							}
						} else {
							$items_removed  = true;
						}
					}
				}
			}
		}
	}
	// Returns an array of exported items for this pass, but also a boolean whether this exporter is finished.
	// If not it will be called again with $page increased by 1.
	return array(
		'items_removed'  => $items_removed,
		'items_retained' => $items_retained,
		'messages'       => $messages,
		'done'           => true,
	);
}
