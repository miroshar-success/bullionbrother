<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wps_obj;
$wps_obj = new Woocommerce_Gift_Cards_Common_Function();
require_once WPS_UWGC_DIRPATH . 'includes/class-wps-uwgc-giftcard-common-function.php';

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * WPS_UWGC_LIST_TABLE
 */
class WPS_UWGC_LIST_TABLE extends WP_List_Table {

	/**
	 * Example_data
	 *
	 * @var [type]
	 */
	public $example_data;

	/**
	 * Column_default
	 *
	 * @param array  $item item.
	 * @param string $column_name columnname.
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'id':
				return $item[ $column_name ];
			case 'date':
				return $item[ $column_name ];
			case 'coupon':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'to':
				return $item[ $column_name ];
			case 'from':
				return $item[ $column_name ];
			case 'message':
				return stripcslashes( $item[ $column_name ] );
			case 'schedule':
				if ( isset( $item[ $column_name ] ) && null != $item[ $column_name ] ) {
					return $item[ $column_name ];
				} else {
					return __( 'Not Scheduled', 'giftware' );
				}

			case 'amount':
				return '<b>' . wc_price( $item[ $column_name ] ) . '</b>';
			case 'resend':
				$text = __( 'RESEND', 'giftware' );

				$html = '<input type="button" value="' . $text . '" data-id="' . $item['id'] . '" class="button button-primary button-large wps_wgm_offline_resend_mail"><p class="resendmail"></p>';

				return $html;
			default:
				return false;
		}
	}

	/**
	 * Get_columns
	 */
	public function get_columns() {
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'id'    => __( 'ID', 'giftware' ),
			'date'  => __( 'Order Date', 'giftware' ),
			'to'     => __( 'To', 'giftware' ),
			'from'   => __( 'From', 'giftware' ),
			'message' => __( 'Message', 'giftware' ),
			'amount' => __( 'Price', 'giftware' ),
			'coupon' => __( 'Gift card Coupon', 'giftware' ),
			'schedule' => __( 'Schedule Date', 'giftware' ),
			'resend' => __( 'Resend', 'giftware' ),
		);
		return $columns;
	}
	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item item.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="wps_offline_ids[]" value="%s" />',
			$item['id']
		);
	}
	/**
	 * Returns an associative array containing the bulk action
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
	 * Get_sortable_columns
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'id'    => array( 'id', false ),
			'date'  => array( 'date', false ),
		);
		return $sortable_columns;
	}
	/**
	 * Process_bulk_action
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( 'bulk-delete' === $this->current_action() ) {
			if ( isset( $_POST['wps_offline_ids'] ) && ! empty( $_POST['wps_offline_ids'] ) ) {
				$offline_ids = map_deep( wp_unslash( $_POST['wps_offline_ids'] ), 'sanitize_text_field' );
				global $wpdb;
				$table_name = $wpdb->prefix . 'offline_giftcard';
				foreach ( $offline_ids as $key => $value ) {
					$wpdb->delete( $table_name, array( 'id' => $value ) );
				}
			}
			?>
			<div class="notice notice-success is-dismissible"> 
				<p><strong><?php esc_html_e( 'Offline Gift Card Deleted', 'giftware' ); ?></strong></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'giftware' ); ?></span>
				</button>
				</div>
				<?php
		}
	}

	/**
	 * Prepare_items
	 *
	 * @return void
	 */
	public function prepare_items() {
		global $wpdb; // This is used only if making any database queries.
		$per_page = 10;
		$columns = $this->get_columns();

		$hidden = array();

		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$table_name = $wpdb->prefix . 'offline_giftcard';

		$query = "SELECT * FROM $table_name";

		$giftresults = $wpdb->get_results( $query, ARRAY_A );

		$this->example_data = $giftresults;
		$data = $this->example_data;

		usort( $data, array( $this, 'wps_wgm_usort_reorder' ) );

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
	 * Wps_wgm_usort_reorder
	 *
	 * @param array $cloumna columna.
	 * @param array $cloumnb columnb.
	 */
	public function wps_wgm_usort_reorder( $cloumna, $cloumnb ) {
		$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'id';
		$order = ( ! empty( $_REQUEST['order'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'dsc';
		$result = strcmp( $cloumna[ $orderby ], $cloumnb[ $orderby ] );
		return ( 'asc' === $order ) ? $result : -$result;
	}
}

$display_message = array();
global $display_message;
if ( isset( $_POST['wps_wgm_csv_custom_import'] ) && ! empty( $_POST['wps_wgm_csv_custom_import'] ) ) {
	if ( isset( $_POST['wps_wgm_offline_gift_template'] ) && null != $_POST['wps_wgm_offline_gift_template'] ) {
		if ( ! empty( $_FILES['csv_import']['tmp_name'] ) ) {
			$csv_mimetypes = array(
				'text/csv',
				'application/csv',
				'text/comma-separated-values',
				'application/excel',
				'application/vnd.ms-excel',
				'application/vnd.msexcel',
				'application/octet-stream',
			);

			if ( isset( $_FILES['csv_import']['type'] ) && in_array( $_FILES['csv_import']['type'], $csv_mimetypes ) ) {
				$file = sanitize_text_field( wp_unslash( $_FILES['csv_import']['tmp_name'] ) );
				if ( file_exists( $file ) ) {
					$row = 1;
					ini_set( 'auto_detect_line_endings', true );
					$handle = fopen( $file, 'r' );
					if ( $handle ) {
						$count = 0;
						$posted_values = array();

						while ( ( $data = fgetcsv( $handle, 1000 ) ) !== false ) {
							if ( 1 == $row ) {
								$row++;
								continue;
							}
							if ( isset( $data ) && ! empty( $data ) && count( $data ) == 5 ) {
								$posted_values[ $count ]['to'] = $data[0];
								$posted_values[ $count ]['from'] = $data[1];
								$posted_values[ $count ]['message'] = sanitize_text_field( $data[2] );
								$posted_values[ $count ]['amount'] = sanitize_text_field( $data[3] );
								$posted_values[ $count ]['template'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_template'] ) );
								if ( isset( $data[4] ) && '' != $data[4] && null != $data[4] ) {
									$posted_values[ $count ]['schedule'] = $data[4];
								} else {
									$posted_values[ $count ]['schedule'] = null;
								}
							} else {
								$display_message['class'] = 'notice-error';
								$display_message['message'] = "<b style='color:red;'>" . __( 'File not imported due to some error.', 'giftware' ) . '</b>';

							}
							$count++;
						}

						fclose( $handle );
					} else {
						$display_message['class'] = 'notice-error';
						$display_message['message'] = "<b style='color:red;'>" . __( 'File not imported due to some error.', 'giftware' ) . '</b>';
					}
					add_offline_data_to_table( $posted_values );
				} else {
					$display_message['class'] = 'notice-error';
					$display_message['message'] = "<b style='color:red;'>" . __( 'File not imported due to some error.', 'giftware' ) . '</b>';
				}
			} else {
				$display_message['class'] = 'notice-error';
				$display_message['message'] = "<b style='color:red;'>" . __( 'File not imported due to some error.', 'giftware' ) . '</b>';
			}
		} else {
			$display_message['class'] = 'notice-error';
			$display_message['message'] = __( 'File not imported due to some error.', 'giftware' );

		}
	} else {
		$display_message['class'] = 'notice-error';
		$display_message['message'] = __( 'Please create Gift Card Product First.', 'giftware' );
	}
}

/**
 * Add_offline_data_to_table
 *
 * @param array $posted_values post.
 * @return void
 */
function add_offline_data_to_table( $posted_values ) {

	global $wpdb,$display_message,$wps_obj;
	$table_name = $wpdb->prefix . 'offline_giftcard';
	if ( isset( $posted_values ) && ! empty( $posted_values ) ) {
		foreach ( $posted_values as $key => $value ) {
			if ( isset( $value['template'] ) && null != $value['template'] && '' != $value['template'] ) {
				$general_settings = get_option( 'wps_wgm_general_settings', array() );
				$giftcard_coupon_length = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );
				if ( empty( $giftcard_coupon_length ) ) {
					$giftcard_coupon_length = 5;
				}
				$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );
				$value['coupon'] = $gift_couponnumber;
				$value['date'] = date_i18n( 'Y-m-d h:i:s' );
				$value['mail'] = false;
				$insert_id = $wpdb->insert( $table_name, $value );

			}
		}
		$display_message['class'] = 'notice-success';
		$display_message['message'] = __( 'File imported successfully.', 'giftware' );
	}

}

$args = array(
	'post_type' => 'product',
	'posts_per_page' => -1,
	'meta_key' => 'wps_wgm_pricing',
);

$gift_products = array();
$loop = new WP_Query( $args );
if ( $loop->have_posts() ) :
	while ( $loop->have_posts() ) :
		$loop->the_post();
		global $product;
		$product_id = $loop->post->ID;
		$product_title = $loop->post->post_title;
		$product_types = wp_get_object_terms( $product_id, 'product_type' );
		if ( isset( $product_types[0] ) ) {
			$product_type = $product_types[0]->slug;
			if ( 'wgm_gift_card' == $product_type ) {
				$gift_products[ $product_id ] = $product_title;
			}
		}
	endwhile;
endif;
if ( isset( $_GET['action'] ) ) {
	if ( 'add' == $_GET['action'] ) {
		global $wpdb;
		global $wps_obj;
		$table_name = $wpdb->prefix . 'offline_giftcard';

		// save new offline giftcard order.

		if ( isset( $_POST['wps_wgm_offline_gift_save'] ) ) {
			if ( isset( $_POST['wps_wgm_offline_gift_template'] ) && null != $_POST['wps_wgm_offline_gift_template'] ) {

				$wps_common_function = new WPS_UWGC_Giftcard_Common_Function();
				$general_settings = get_option( 'wps_wgm_general_settings', array() );
				$giftcard_pdf_prefix = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_pdf_prefix' );
				$giftcard_coupon_length = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );
				if ( empty( $giftcard_coupon_length ) ) {
					$giftcard_coupon_length = 5;
				}
				$selected_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );

				$gift_manual_code = isset( $_POST['wps_wgm_offline_gift_coupon_manual'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_coupon_manual'] ) ) : '';
				if ( isset( $gift_manual_code ) && ! empty( $gift_manual_code ) ) {
					$gift_couponnumber = $gift_manual_code;
				} else {
					$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );
				}
				$data['to'] = isset( $_POST['wps_wgm_offline_gift_to'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_to'] ) ) : '';
				$data['from'] = isset( $_POST['wps_wgm_offline_gift_from'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_from'] ) ) : '';
				$data['amount'] = isset( $_POST['wps_wgm_offline_gift_amount'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_amount'] ) ) : '';
				$data['message'] = isset( $_POST['wps_wgm_offline_gift_message'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_message'] ) ) : '';
				$data['template'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_template'] ) );
				$data['coupon'] = $gift_couponnumber;
				$data['date'] = date_i18n( 'Y-m-d h:i:s' );
				$data['schedule'] = null;
				$schedule_date = isset( $_POST['wps_wgm_offline_gift_schedule'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_schedule'] ) ) : '';

				$counter_date = 'wps_default';
				$counter_schedule = true;

				if ( isset( $schedule_date ) && '' != $schedule_date && null != $schedule_date ) {
					$counter_schedule = false;
					if ( is_string( $schedule_date ) ) {
						if ( isset( $selected_date ) && null != $selected_date && '' != $selected_date ) {
							if ( 'd/m/Y' == $selected_date ) {
								$schedule_date = str_replace( '/', '-', $schedule_date );
							}
						}
						$senddatetime = strtotime( $schedule_date );
					}
					$senddatetime = strtotime( $schedule_date );
					$senddate = date_i18n( 'Y-m-d', $senddatetime );
					$todaytime = time();
					$todaydate = date_i18n( 'Y-m-d', $todaytime );
					$senddatetime = strtotime( "$senddate" );
					$todaytime = strtotime( "$todaydate" );
					$giftdiff = $senddatetime - $todaytime;
					if ( $giftdiff > 0 ) {
						$counter_date = 'wps_schedule';
					} else {
						$counter_date = 'wps_schedule_today';
					}
					$data['schedule'] = isset( $_POST['wps_wgm_offline_gift_schedule'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_offline_gift_schedule'] ) ) : '';
					if ( is_string( $data['schedule'] ) ) {
						if ( isset( $selected_date ) && null != $selected_date && '' != $selected_date ) {
							if ( 'd/m/Y' == $selected_date ) {
								$data['schedule'] = str_replace( '/', '-', $data['schedule'] );
							}
						}
						$data['schedule'] = strtotime( $data['schedule'] );
					}

					$data['schedule'] = date_i18n( 'Y-m-d', $data['schedule'] );
				}
				$send_mail = true;
				if ( 'wps_schedule' == $counter_date && ! $counter_schedule ) {
					$send_mail = false;
				}

				$data['mail'] = false;
				if ( $send_mail ) {
					$to = $data['to'];
					$from = $data['from'];
					$mail_settings = get_option( 'wps_wgm_mail_settings', array() );
					$subject = $wps_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_subject' );
					$bloginfo = get_bloginfo();
					if ( empty( $subject ) || ! isset( $subject ) ) {

						$subject = $bloginfo . __( ' : Hurry!!! Gift Card is Received', 'giftware' );
					}
					$subject = str_replace( '[SITENAME]', $bloginfo, $subject );
					$subject = str_replace( '[FROM]', $from, $subject );
					$subject = stripcslashes( $subject );
					$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );
					$todaydate = date_i18n( 'Y-m-d' );
					$expiry_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );

					if ( $expiry_date > 0 || 0 === $expiry_date ) {
						$expirydate = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
						$expirydate_format = date_create( $expirydate );
						$selected_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );
						if ( isset( $selected_date ) && null != $selected_date && '' != $selected_date ) {
							$selected_date = $wps_common_function->wps_uwgc_selected_date_format( $selected_date );
							$expirydate_format = date_i18n( $selected_date, strtotime( "$todaydate +$expiry_date day" ) );
						} else {
							$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
						}
					} else {
						$expirydate_format = __( 'No Expiry', 'giftware' );
					}
					$product_id = $data['template'];
					$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
					$templateid = $wps_wgm_pricing['template'];
					if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
						$temp = $templateid[0];
					} else {
						$temp = $templateid;
					}
					$args['from'] = $data['from'];
					$args['to'] = $data['to'];
					$args['message'] = stripcslashes( $data['message'] );
					$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $gift_couponnumber );
					$args['expirydate'] = $expirydate_format;
					$args['amount'] = wc_price( $data['amount'] );
					$args['templateid'] = $temp;
					$args['product_id'] = $product_id;
					$args['send_date']  = $schedule_date;

					$message = $wps_obj->wps_wgm_create_gift_template( $args );
					$other_settings = get_option( 'wps_wgm_other_settings', array() );
					$wps_uwgc_pdf_enable = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_pdf_enable' );

					if ( isset( $wps_uwgc_pdf_enable ) && 'on' == $wps_uwgc_pdf_enable ) {
						$site_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
						$time = time();
						$wps_common_function->wps_uwgc_attached_pdf( $message, $site_name, $time, '', $gift_couponnumber );
						if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
							$attachments = array( wp_upload_dir()['basedir'] . '/giftcard_pdf/' . $giftcard_pdf_prefix . $gift_couponnumber . '.pdf' );
						} else {
							$attachments = array( wp_upload_dir()['basedir'] . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
						}
					} else {
						$attachments = array();
					}
					// send mail to receiver.
					$wps_wgc_bcc_enable = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_bcc_option_enable' );
					if ( isset( $wps_wgc_bcc_enable ) && 'on' == $wps_wgc_bcc_enable ) {
						$headers[] = 'Bcc:' . $from;
						wc_mail( $to, $subject, $message, $headers, $attachments );
						if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
							unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/' . $giftcard_pdf_prefix . $gift_couponnumber . '.pdf' );
						} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
							unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
						}
					} else {

						$headers = array( 'Content-Type: text/html; charset=UTF-8' );
						wc_mail( $to, $subject, $message, $headers, $attachments );
						if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
							unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/' . $giftcard_pdf_prefix . $gift_couponnumber . '.pdf' );
						} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
							unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
						}
					}

					$data['mail'] = true;
					$insert_id = $wpdb->insert( $table_name, $data );
					$insert_id = $wpdb->insert_id;

					// coupon is created.

					$couponcreated = $wps_common_function->wps_uwgc_create_offline_gift_coupon( $gift_couponnumber, $data['amount'], $insert_id, $product_id, $to );

					$subject = $wps_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_subject' );

					$message = $wps_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_receive_message' );
					if ( empty( $subject ) || ! isset( $subject ) ) {

						$subject = $bloginfo . __( ' : Giftcard is Send Successfully', 'giftware' );
					}

					if ( empty( $message ) || ! isset( $message ) ) {

						$message = $bloginfo . __( ' : Giftcard is Send Successfully', 'giftware' );
					}

					$message = stripcslashes( $message );
					$subject = stripcslashes( $subject );
					$wps_wgm_disable_buyer_notification = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_disable_buyer_notification' );
					if ( 'on' !== $wps_wgm_disable_buyer_notification ) {
						wc_mail( $from, $subject, $message );
					}
					// send acknowledge mail to sender.

					?>
					<div class="updated notice notice-success is-dismissible" id="message">
						<p>
							<?php esc_html_e( 'Gift card Created and Mail is sent to Sender and Receiver. Code is', 'giftware' ); ?> : <a href="javascript:void(0);"><?php echo wp_kses_post( $gift_couponnumber ); ?></a>
						</p>
					</div>
					<?php
				} else {
					$insert_id = $wpdb->insert( $table_name, $data );
					?>
					<div class="updated notice notice-success is-dismissible" id="message">
						<p>
							<?php esc_html_e( 'Gift card Created and Mail will be sent on the scheduled date.', 'giftware' ); ?>
						</p>
					</div>
					<?php
				}
			} else {
				?>
				<div class="updated notice notice-success is-dismissible" id="message">
					<p>
						<?php esc_html_e( 'Please create Gift Card Product First.', 'giftware' ); ?>
					</p>
				</div>
				<?php
			}
		}
		?>
		<form method="post" action="">
			<table class="form-table wps_wgm_offline_gift_to">
				<tbody>
					<tr>
						<th id="wps_wgm_add_offline" colspan="2">
							<h3 class="wp-heading-inline" ><?php esc_html_e( 'Add New Gift Card', 'giftware' ); ?></h3>
							<a  href="<?php echo esc_url( WPS_UWGC_ADMIN_URL ); ?>edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=offline-giftcard" class="wps_wgm_small_button"><?php esc_html_e( 'VIEW LIST', 'giftware' ); ?>
						</th>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_schedule"><?php esc_html_e( 'Schedule Date', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribute_description = __( 'Select the scheduled date for sending the Gift Card on the specified date.', 'giftware' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<label for="wps_wgm_offline_gift_schedule">
								<input type="text" name="wps_wgm_offline_gift_schedule" id="wps_wgm_offline_gift_schedule" class="input-text wps_wgm_new_woo_ver_style_text">
								<p class="description wps_ml-35"><?php esc_html_e( 'Leave this field empty if you want to send the Gift Card right now.', 'giftware' ); ?></p>
							</label>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_to"><?php esc_html_e( 'To', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribute_description = __( 'Enter the email id of the recipient.', 'giftware' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<label for="wps_wgm_offline_gift_to">
								<input type="email" name="wps_wgm_offline_gift_to" id="wps_wgm_offline_gift_to" class="input-text wps_wgm_new_woo_ver_style_text" placeholder="<?php esc_html_e( 'to@example.com', 'giftware' ); ?>">
							</label>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_from"><?php esc_html_e( 'From', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribute_description = __( 'Enter the email id of the sender.', 'giftware' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<label for="wps_wgm_offline_gift_from">
								<input type="email" name="wps_wgm_offline_gift_from" id="wps_wgm_offline_gift_from" class="input-text wps_wgm_new_woo_ver_style_text" placeholder="<?php esc_html_e( 'from@example.com', 'giftware' ); ?>">
							</label>						
						</td>
					</tr>

					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_amount"><?php esc_html_e( 'Amount', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribute_description = __( 'Enter the Gift Card amount.', 'giftware' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<label for="wps_wgm_offline_gift_amount">
								<input type="number" name="wps_wgm_offline_gift_amount" id="wps_wgm_offline_gift_amount" class="input-text wps_wgm_new_woo_ver_style_text" min="0">
							</label>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_coupon_manual"><?php esc_html_e( 'Custom Coupon Code', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribute_description = __( 'Enter the Gift Coupon Manual, Leave blank if you need system generated code', 'giftware' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<label for="wps_wgm_offline_gift_coupon_manual">
								<input type="text" name="wps_wgm_offline_gift_coupon_manual" id="wps_wgm_offline_gift_coupon_manual" class="input-text wps_wgm_new_woo_ver_style_text">
								<div id="wps_wgm_invalid_code_notice" style="display: inline;"></div>
							</label>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_message"><?php esc_html_e( 'Message', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribute_description = __( 'Enter the Gift Card message.', 'giftware' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<label for="wps_wgm_offline_gift_message">
								<textarea name="wps_wgm_offline_gift_message" id="wps_wgm_offline_gift_message" class="input-text" rows="2" cols="3"></textarea>
							</label>						
						</td>
					</tr>

					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_template"><?php esc_html_e( 'Gift Card', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribute_description = __( 'Select the Gift card product.', 'giftware' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<label for="wps_wgm_offline_gift_template">
								<?php
								if ( isset( $gift_products ) && ! empty( $gift_products ) ) {
									?>
										<select name="wps_wgm_offline_gift_template" id="wps_wgm_offline_gift_template" class="input-text wps_wgm_new_woo_ver_style_select">
											<?php
											foreach ( $gift_products as $ids => $gift_product ) {
												?>
													<option value="<?php echo esc_attr( $ids ); ?>"><?php echo esc_attr( $gift_product ); ?></option>										
													<?php
											}
											?>
											</select>
											<?php
								} else {
									echo '<p style=color:red>' . esc_html_e( 'No Gift Card Product Present, Please Add Gift Card Product first', 'giftware' ) . '</p>';
								}
								?>
									</select>
								</label>						
							</td>
						</tr>
						
						<tr valign="top">
							<th></th>
							<td scope="row" class="titledesc">
								<label for="wps_wgm_offline_gift_preview" class="wps_ml-35"><a id="wps_wgm_offline_gift_preview" href="javascript:void(0);"><?php esc_html_e( 'Preview', 'giftware' ); ?></a></label>
							</td>
						</tr>
						
					</tbody>
				</table>	
				<p class="submit">
					<input type="submit" name="wps_wgm_offline_gift_save" id="wps_wgm_offline_gift_save"  class="wps_wgm_small_button" value="<?php esc_html_e( 'Save & Send', 'giftware' ); ?>">
				</p>	
			</form>
			<?php
	}
} else {
	global $display_message;

	?>
		<h3 class="wps_wgm_heading"><?php esc_html_e( 'Import Offline Coupons', 'giftware' ); ?></h3>
		<div class="wps_wgm_import_giftcoupons">
			<table class="form-table wps_wgm_general_setting">
				<tbody>
					<tr valign="top">
						<td colspan="3" class="wps_wgm_instructions_tabledata">	
							<h3><?php esc_html_e( 'Instructions', 'giftware' ); ?></h3>
							<p>1- <?php esc_html_e( 'It just provides you the way from where you can import your coupons in bulk and can provide them Manually to your Customers. You need to choose a CSV file and click Import', 'giftware' ); ?></p>
							<p>2- <?php esc_html_e( 'CSV for Offline Coupons must have 4 columns in this order ( Coupon Code, Expiry Date, Usage Limit, Price. Also first row must be the respective headings. )', 'giftware' ); ?> </p>
							<p>3- <?php esc_html_e( 'You may leave the Expiry Date field empty if you want to set your gift coupons with "No Expiration". The Expiry Date format must be in (YYYY-MM-DD), also may leave Usage Limit for setting this for "No Usage Limit".', 'giftware' ); ?> </p>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Choose a CSV file:', 'giftware' ); ?>
					</th>
					<td>
						<input class="wps_wgm_csv_offlinecoupon_import" name="offlinecoupon_csv_import" id="offlinecoupon_csv_import" type="file" size="25" value="" aria-required="true" /> 

						<input type="hidden" value="134217728" name="max_file_size">
						<small><?php esc_html_e( 'Maximum size:128 MB', 'giftware' ); ?></small>
					</td>
					<td>
						<a href="<?php echo esc_url( WPS_UWGC_URL . '/admin/uploads/wps_wgm_offline_coupon_import.csv' ); ?>"><?php esc_html_e( 'Export Demo CSV', 'giftware' ); ?>
						<span class="wps_sample_export"><img src="<?php echo esc_url( WPS_UWGC_URL . 'assets/images/download.png' ); ?>"></span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<p><input name="wps_wgm_csv_offlinecoupon_import" id = "wps_wgm_csv_offlinecoupon_import" class="button-primary woocommerce-save-button" type="submit" value="<?php esc_html_e( 'Import', 'giftware' ); ?>"/></p>
				</td><td></td><td></td>								
			</tr>
		</tbody>
	</table>
</div>
	<?php
	if ( isset( $_POST['wps_wgm_csv_offlinecoupon_import'] ) && ! empty( $_POST['wps_wgm_csv_offlinecoupon_import'] ) ) {

		if ( ! empty( $_FILES['offlinecoupon_csv_import']['tmp_name'] ) ) {
			$csv_mimetypes = array(
				'text/csv',
				'application/csv',
				'text/comma-separated-values',
				'application/excel',
				'application/vnd.ms-excel',
				'application/vnd.msexcel',
				'application/octet-stream',
			);
			if ( isset( $_FILES['offlinecoupon_csv_import']['type'] ) && in_array( $_FILES['offlinecoupon_csv_import']['type'], $csv_mimetypes ) ) {
				$coupon_imported = false;
				$file = sanitize_text_field( wp_unslash( $_FILES['offlinecoupon_csv_import']['tmp_name'] ) );
				if ( file_exists( $file ) ) {
					$row = 1;
					ini_set( 'auto_detect_line_endings', true );
					$handle = fopen( $file, 'r' );
					$csv_data = array();
					if ( $handle ) {
						while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
							$num_of_col = count( $data );
							if ( 1 == $row ) {
								$row++;
								continue;
							}
							if ( 4 == $num_of_col && isset( $data ) && ! empty( $data ) ) {
								$coupon_code = sanitize_text_field( $data[0] );
								$coupon_exp = sanitize_text_field( $data[1] );
								$usage_limit = sanitize_text_field( $data[2] );
								$coupon_amount = sanitize_text_field( $data[3] );
								if ( wps_wgm_generate_coupon_via_csv( $coupon_code, $coupon_exp, $usage_limit, $coupon_amount ) ) {
									$display_message['class'] = 'notice-success';
									$display_message['message'] = "<b style='color:green;'>" . __( 'Coupons imported successfully.', 'giftware' ) . '</b>';
								} else {
									$display_message['class'] = 'notice-error';
									$display_message['message'] = "<b style='color:red;'>" . __( 'Fail due to some error', 'giftware' ) . '</b>';
								}
							} else {
								$display_message['class'] = 'notice-error';
								$display_message['message'] = "<b style='color:red;'>" . __( 'Columns are not appropriate.', 'giftware' ) . '</b>';
							}
						}
					} else {
						$display_message['class'] = 'notice-error';
						$display_message['message'] = "<b style='color:red;'>" . __( 'File cannot be opened.', 'giftware' ) . '</b>';
					}
				} else {
					$display_message['class'] = 'notice-error';
					$display_message['message'] = "<b style='color:red;'>" . __( 'File does not exist.', 'giftware' ) . '</b>';
				}
			} else {
				$display_message['class'] = 'notice-error';
				$display_message['message'] = "<b style='color:red;'>" . __( 'File type not supported.', 'giftware' ) . '</b>';
			}
		} else {
			$display_message['class'] = 'notice-error';
			$display_message['message'] = "<b style='color:red;'>" . __( 'Please choose a valid file', 'giftware' ) . '</b>';
		}
	}
	?>
	<?php
	if ( isset( $display_message ) && null != $display_message ) {

		?>
	<div class="notice <?php echo wp_kses_post( $display_message['class'] ); ?> is-dismissible"> 
		<p><strong><?php echo wp_kses_post( $display_message['message'] ); ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'giftware' ); ?></span>
		</button>
	</div>
		<?php
	}

	?>
<h3 class="wps_wgm_heading" id="wps_wgm_add_new_card_heading"><?php esc_html_e( 'Offline Gift Card List', 'giftware' ); ?></h3><br>
<table class="form-table">
	<tr valign="top">
		<td class="forminput">

			<h3><?php esc_html_e( 'Instructions', 'giftware' ); ?></h3>
			<p>1- <?php esc_html_e( 'Import Offline Gift Card CSV for sending offline Gift Cards. You need to choose a CSV file and click Upload.', 'giftware' ); ?></p>
			<p>2- <?php esc_html_e( 'CSV for Offline Gift Card must have 5 columns in this order ( To, From, Message, Price, Schedule Date. Also first row must be the respective headings. )', 'giftware' ); ?> </p>
			<p>3- <?php esc_html_e( 'You may leave Schedule Date field empty if you want to send Gift Card today. The Schedule Date format must be in (YYYY-MM-DD).', 'giftware' ); ?> </p>
		</td>
	</tr>
	<tr>
		<td>
			<table class="widefat">
				<tbody>
					<tr>
						<th scope="row" class="titledesc">
							<label for="wps_wgm_offline_gift_template"><?php esc_html_e( 'Gift card', 'giftware' ); ?></label>
						</th>
						<td class="forminp forminp-text">
						<?php
						$attribute_description = __( 'Select the Gift card product.', 'giftware' );
						echo wp_kses_post( wc_help_tip( $attribute_description ) );
						?>
							<label for="wps_wgm_offline_gift_template">
							<?php
							if ( isset( $gift_products ) && ! empty( $gift_products ) ) {
								?>
										<select name="wps_wgm_offline_gift_template" id="wps_wgm_offline_gift_template" class="input-text wps_wgm_new_woo_ver_style_select">
										<?php
										foreach ( $gift_products as $ids => $gift_product ) {
											?>
													<option value="<?php echo esc_attr( $ids ); ?>"><?php echo esc_attr( $gift_product ); ?></option>										
													<?php
										}
										?>
											</select>
											<?php
							} else {
								esc_html_e( 'No Gift Card Product Present', 'giftware' );
							}
							?>
									</select>
								</label>						
							</td>
						</tr>
						<tr>
							<th><?php echo esc_html_e( 'Choose a CSV file:', 'giftware' ); ?>
						</th>
						<td>
							<input class="wps_wgm_csv_custom_import" name="csv_import" id="csv_import" type="file" size="25" value="" aria-required="true" /> 

							<input type="hidden" value="134217728" name="max_file_size">
							<small><?php esc_html_e( 'Maximum size:128 MB', 'giftware' ); ?></small>
						</td>
						<td>
							<a href="<?php echo esc_url( WPS_UWGC_URL . '/admin/uploads/wps_wgm_gift_card_sample.csv' ); ?>"><?php esc_html_e( 'Export Demo CSV', 'giftware' ); ?>
							<span class="wps_sample_export"><img src="<?php echo esc_url( WPS_UWGC_URL . 'assets/images/download.png' ); ?>"></img></span>

						</a>
					</td>
				</tr>
				<tr>
					<td>
						<p><input name="wps_wgm_csv_custom_import" id = "wps_wgm_import_button" class="button-primary woocommerce-save-button" type="submit" value="<?php esc_html_e( 'Import', 'giftware' ); ?>" name="wps_wgm_import_button"/></p>
					</td>								
				</tr>
			</tbody>
		</table>
	</td>
</tr>
</table>
<a id="wps_wgm_add_new_card_button" class="page-title-action button button-primary button-large" href="<?php echo esc_url( WPS_UWGC_ADMIN_URL ); ?>edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=offline-giftcard&action=add"><?php esc_html_e( 'Add New', 'giftware' ); ?></a>
<form method="post">
	<input type="hidden" name="page" value="ttest_list_table">
	<?php
	$my_list_table = new WPS_UWGC_LIST_TABLE();
	$my_list_table->prepare_items();
	$my_list_table->display();
	?>
</form>
	<?php
}

/**
 * Wps_wgm_generate_coupon_via_csv
 *
 * @param string $coupon_code code.
 * @param string $coupon_exp expiry.
 * @param int    $usage_limit limit.
 * @param int    $coupon_amount amount.
 */
function wps_wgm_generate_coupon_via_csv( $coupon_code, $coupon_exp, $usage_limit, $coupon_amount ) {
	$the_coupon = new WC_Coupon( $coupon_code );
	$woo_ver = WC()->version;
	if ( $woo_ver < '3.0.0' ) {
		$coupon_id = $the_coupon->id;
	} else {
		$coupon_id = $the_coupon->get_id();
	}

	if ( isset( $coupon_id ) && 0 == $coupon_id ) {

		$coupon_description = 'Imported Offline Coupon';
		$coupon = array(
			'post_title' => $coupon_code,
			'post_content' => $coupon_description,
			'post_excerpt' => $coupon_description,
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
			'post_type'     => 'shop_coupon',
		);
		$new_coupon_id = wp_insert_post( $coupon );
		update_post_meta( $new_coupon_id, 'discount_type', 'fixed_cart' );
		update_post_meta( $new_coupon_id, 'coupon_amount', $coupon_amount );
		update_post_meta( $new_coupon_id, 'wps_wgm_coupon_amount', $coupon_amount );

		$woo_ver = WC()->version;

		if ( $woo_ver < '3.6.0' ) {
			update_post_meta( $new_coupon_id, 'expiry_date', $coupon_exp );
		} else {

			$expirydate = strtotime( $coupon_exp );
			update_post_meta( $new_coupon_id, 'date_expires', $expirydate );
		}
		update_post_meta( $new_coupon_id, 'wps_wgm_imported_offline', 'yes' );
		update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
		return true;
	} else {
		return false;
	}
}
?>
