<?php
/**
 * Exit if accessed directly
 *
 * @package Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
?>
	<?php $wps_total_balance = wps_uwgc_total_balance(); ?>
	<div class="wps-uwgc-balance-summary">
		<table>
			<tr>
				<th><?php esc_html_e( 'Outstanding Balance', 'giftware' ); ?></th>
				<th><?php esc_html_e( 'Expired Balance', 'giftware' ); ?></th>
			</tr>
			<tr>
				<?php
				if ( isset( $wps_total_balance ) && ! empty( $wps_total_balance ) && is_array( $wps_total_balance ) ) {
					foreach ( $wps_total_balance as $key => $value ) {
						?>
						<td><?php echo wp_kses_post( wc_price( $value ) ); ?></td>
						<?php
					}
				}
				?>
			</tr>
		</table>
	</div>
<?php

/**
 * Giftcard Coupon Report
 *
 * @author     WP Swings <webmaster@wpswings.com>
 * @package    Ultimate Woocommerce Gift Cards
 * @version    2.2.1
 */
class Wps_UWGC_Giftcard_Report_List extends WP_List_Table {
	/**
	 * Eample_data
	 *
	 * @var [type]
	 */
	public $example_data;

	/**
	 * Get column value.
	 *
	 * @param mixed  $item item.
	 * @param string $column_name column.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'giftcard_code':
				$html = '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $item['coupon_id'] ) ) . '&action=edit' ) . '">' . $item[ $column_name ] . '</a>';
				return $html;
			case 'order_id':
				$html = '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $item['order_id'] ) ) . '&action=edit' ) . '">' . $item[ $column_name ] . '</a>';
				return $html;
			case 'coupon_amount':
				return $item[ $column_name ];
			case 'expiry_date':
				return $item[ $column_name ];
			case 'buyer_email':
				$html = '<a href="mailto:' . $item[ $column_name ] . '">' . $item[ $column_name ] . '</a>';
				return $html;
			case 'action':
				$text = __( 'View Details', 'giftware' );
				$html = '<input type="button" value="' . $text . '" data-coupon-id="' . $item['coupon_id'] . '" data-order-id="' . $item['order_id'] . '" class="button wps_uwgc_gift_report_view">';
				return $html;
			default:
				return false;
		}
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'giftcard_code' => __( 'Gift Card Code', 'giftware' ),
			'order_id'  => __( 'Order Id', 'giftware' ),
			'coupon_amount' => __( 'Balance', 'giftware' ),
			'expiry_date'     => __( 'Expiry Date', 'giftware' ),
			'buyer_email'   => __( 'Buyer Email', 'giftware' ),
			'action' => __( 'Action', 'giftware' ),
		);
		return $columns;
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'order_id'    => array( 'order_id', false ),
		);
		return $sortable_columns;
	}

	/**
	 * Column cb.
	 *
	 * @param  array $item Key data.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="wps_coupon_ids[]" value="%s" />',
			$item['coupon_id']
		);
	}

	/**
	 * Process bulk actions.
	 */
	public function process_bulk_action() {
		if ( 'bulk-delete' === $this->current_action() ) {
			if ( isset( $_POST['wps_coupon_ids'] ) && ! empty( $_POST['wps_coupon_ids'] ) ) {
				$coupon_ids = map_deep( wp_unslash( $_POST['wps_coupon_ids'] ), 'sanitize_text_field' );
				global $wpdb;
				if ( isset( $coupon_ids ) && ! empty( $coupon_ids ) && is_array( $coupon_ids ) ) {
					foreach ( $coupon_ids as $key => $value ) {
						wp_delete_post( $value );
					}
				}
			}
			?>
			<div class="notice notice-success is-dismissible"> 
				<p><strong><?php esc_html_e( 'Gift Card Deleted', 'giftware' ); ?></strong></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'giftware' ); ?></span>
				</button>
			</div>
			<?php
		}
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Delete', 'giftware' ),
		);
		return $actions;
	}


	/**
	 * Prepare table list items.
	 */
	public function prepare_items() {
		global $wpdb;
		$per_page = 10;
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();
		$this->example_data = wps_uwgc_giftcard_report_data();
		$data = $this->example_data;
		usort( $data, array( $this, 'wps_uwgc_usort_reorder_report' ) );
		$current_page = $this->get_pagenum();
		$total_items = count( $data );
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);

	}

	/**
	 * Search box.
	 *
	 * @param  array $cloumna Column A.
	 * @param  array $cloumnb Column B.
	 */
	public function wps_uwgc_usort_reorder_report( $cloumna, $cloumnb ) {
		$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'order_id';
		$order = ( ! empty( $_REQUEST['order'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'dsc';
		$result = strcmp( $cloumna[ $orderby ], $cloumnb[ $orderby ] );
		return ( 'asc' === $order ) ? $result : -$result;
	}
}
?>
<form method="post">
	<input type="hidden" name="page" value="<?php echo esc_attr( isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '' ); ?>">
	<?php
	$wps_report_list = new Wps_UWGC_Giftcard_Report_List();
	$wps_report_list->prepare_items();
	$wps_report_list->search_box( __( 'Search Gift Cards', 'giftware' ), 'giftcard_code' );
	$wps_report_list->display();

	?>
</form>
<?php

/**
 * Function is used to show giftcard coupons.
 */
function wps_uwgc_giftcard_report_data() {

	$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'shop_coupon',
		'post_status'      => 'publish',
	);
	$coupons = get_posts( $args );

	$coupon_codes = array();
	if ( isset( $coupons ) && is_array( $coupons ) && ! empty( $coupons ) ) {
		foreach ( $coupons as $coupon ) {
			$couponcontent = $coupon->post_content;
			if ( strpos( $couponcontent, 'GIFTCARD ORDER #' ) !== false ) {
				$coupon_name = strtolower( $coupon->post_title );
				array_push( $coupon_codes, $coupon_name );
			}
		}
	}

	$wps_uwgc_data = array();

	if ( is_array( $coupon_codes ) && ! empty( $coupon_codes ) && count( $coupon_codes ) ) {

		foreach ( $coupon_codes as $key => $value ) {
			$coupon_obj = new WC_Coupon( $value );
			$order_id = get_post_meta( $coupon_obj->get_id(), 'wps_wgm_giftcard_coupon', true );
			if ( isset( $order_id ) && ! empty( $order_id ) ) {
				$order = wc_get_order( $order_id );
				if ( ! empty( $order ) ) {
					$user_email = $order->get_billing_email();
					$coupon_amount = get_post_meta( $coupon_obj->get_id(), 'coupon_amount', true );
					$expiry_date = $coupon_obj->get_date_expires();
					$expiry_date = isset( $expiry_date ) ? $expiry_date->date_i18n( 'F j, Y' ) : esc_html__( 'No Expiry', 'giftware' );

					$wps_uwgc_data[] = array(
						'coupon_id' => $coupon_obj->get_id(),
						'giftcard_code' => $value,
						'order_id'  => $order_id,
						'coupon_amount' => $coupon_amount,
						'expiry_date' => $expiry_date,
						'buyer_email' => $user_email,
					);
				}
			}
		}

		$wps_uwgc_data = wps_uwgc_search_option( $wps_uwgc_data );
	}
	return $wps_uwgc_data;
}

/**
 * Function is used to search gift cards.
 *
 * @param array $wps_uwgc_data Array of data.
 */
function wps_uwgc_search_option( $wps_uwgc_data ) {
	$wps_uwgc_search_arr = array();
	if ( isset( $_REQUEST['s'] ) && ! empty( $_REQUEST['s'] ) ) {
		$search_coupon = strtolower( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) );

		if ( isset( $wps_uwgc_data ) && ! empty( $wps_uwgc_data ) && is_array( $wps_uwgc_data ) ) {
			foreach ( $wps_uwgc_data as $key => $value ) {
				if ( in_array( $search_coupon, $value ) ) {
					array_push( $wps_uwgc_search_arr, $value );
				}
			}
		}
		return $wps_uwgc_search_arr;
	} else {
		return $wps_uwgc_data;
	}
}

/**
 * Function is used to check expiry date of coupon.
 *
 * @param array $coupon_obj Object of coupon.
 */
function wps_uwgc_validate_expiry( $coupon_obj ) {

	if ( $coupon_obj->get_date_expires() && time() > $coupon_obj->get_date_expires()->getTimestamp() ) {
		return false;
	} else {
		return true;
	}
}

/**
 * This function is used to get total balance.
 */
function wps_uwgc_total_balance() {
	$total_balance = 0;
	$expire_giftcard = 0;
	$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'shop_coupon',
		'post_status'      => 'publish',
	);
	$coupons = get_posts( $args );
	if ( isset( $coupons ) && is_array( $coupons ) && ! empty( $coupons ) ) {
		foreach ( $coupons as $coupon ) {
			$couponcontent = $coupon->post_content;
			if ( strpos( $couponcontent, 'GIFTCARD ORDER #' ) !== false ) {

				$coupon_id = $coupon->ID;
				$coupon_obj = new WC_Coupon( $coupon_id );

				if ( $coupon_obj->get_usage_limit() == 0 && wps_uwgc_validate_expiry( $coupon_obj ) ) {

					$coupon_amount = get_post_meta( $coupon_obj->get_id(), 'coupon_amount', true );
					$total_balance = $total_balance + $coupon_amount;

				} else if ( $coupon_obj->get_usage_limit() > 0 && $coupon_obj->get_usage_limit() > $coupon_obj->get_usage_count() && wps_uwgc_validate_expiry( $coupon_obj ) ) {

					$coupon_amount = get_post_meta( $coupon_obj->get_id(), 'coupon_amount', true );
					$total_balance = $total_balance + $coupon_amount;
				} else {
						$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
					if ( isset( $order_id ) && ! empty( $order_id ) ) {
						$coupon_amount = get_post_meta( $coupon_id, 'coupon_amount', true );
						$expiry_date = get_post_meta( $coupon_id, 'date_expires', true );

						if ( isset( $expiry_date ) && ! empty( $expiry_date ) ) {

							$now_date = current_time( 'timestamp' );
							$diff = $expiry_date - $now_date;
							if ( $diff < 0 ) {
								$expire_giftcard = $expire_giftcard + $coupon_amount;
							}
						}
					}
				}
			}
		}
	}

	$wps_common_array = array(
		'total_balance' => $total_balance,
		'expire_giftcard' => $expire_giftcard,
	);
	return $wps_common_array;
}

