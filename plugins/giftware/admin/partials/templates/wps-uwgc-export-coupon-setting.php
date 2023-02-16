<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( isset( $_POST['wps_wgm_csv_custom_giftcoupon_import'] ) && ! empty( $_POST['wps_wgm_csv_custom_giftcoupon_import'] ) ) {
	$coupon_imported = false;
	if ( ! empty( $_FILES['giftcoupon_csv_import']['tmp_name'] ) ) {
			$csv_mimetypes = array(
				'text/csv',
				'application/csv',
				'text/comma-separated-values',
				'application/excel',
				'application/vnd.ms-excel',
				'application/vnd.msexcel',
				'application/octet-stream',
			);
			$wps_file_type = isset( $_FILES['giftcoupon_csv_import']['type'] ) ? sanitize_text_field( wp_unslash( $_FILES['giftcoupon_csv_import']['type'] ) ) : '';
			if ( in_array( $wps_file_type, $csv_mimetypes ) ) {
				$file = isset( $_FILES['giftcoupon_csv_import']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['giftcoupon_csv_import']['tmp_name'] ) ) : '';
				if ( file_exists( $file ) ) {
					$row = 1;
					ini_set( 'auto_detect_line_endings', true );
					$handle = fopen( $file, 'r' );
					$csv_data = array();
					if ( $handle ) {
						while ( false !== ( $data = fgetcsv( $handle, 1000, ',' ) ) ) {
							$num_of_col = count( $data );
							if ( 1 == $row ) {
								$row++;
								continue;
							}
							if ( 3 == $num_of_col && isset( $data ) && ! empty( $data ) ) {
								$coupon_code = sanitize_text_field( $data[0] );
								$coupon_exp = sanitize_text_field( $data[1] );
								$usage_limit = sanitize_text_field( $data[2] );
								if ( wps_wgm_generate_coupon_via_csv( $coupon_code, $coupon_exp, $usage_limit ) ) {
									$coupon_imported = true;
								} else {
									$coupon_imported = false;
								}
							} else {
								$coupon_imported = false;
							}
						}
					} else {
						$coupon_imported = false;
					}
				} else {
					$coupon_imported = false;
				}
			} else {
				$coupon_imported = false;
			}
	}
	if ( $coupon_imported ) {
		?>
			<div class="notice notice-success is-dismissible"> 
				<p><strong><?php esc_html_e( 'Coupons Imported Successfully!', 'giftware' ); ?></strong></p>
			</div>
		<?php
	} elseif ( ! $coupon_imported ) {
		?>
		<div class="notice notice-error is-dismissible"> 
				<p><strong><?php esc_html_e( 'Fail Due To Some Error', 'giftware' ); ?></strong></p>
			</div>
		<?php
	}
}
if ( isset( $_POST['wps_wgm_csv_custom_giftproduct_import'] ) && ! empty( $_POST['wps_wgm_csv_custom_giftproduct_import'] ) ) {
	$product_imported = false;
	$message = __( 'Fail due to some error', 'giftware' );
	if ( ! empty( $_FILES['giftprod_csv_import']['tmp_name'] ) ) {
			$csv_mimetypes = array(
				'text/csv',
				'application/csv',
				'text/comma-separated-values',
				'application/excel',
				'application/vnd.ms-excel',
				'application/vnd.msexcel',
				'application/octet-stream',
			);
			if ( isset( $_FILES['giftprod_csv_import']['type'] ) && in_array( $_FILES['giftprod_csv_import']['type'], $csv_mimetypes ) ) {
				$file = sanitize_text_field( wp_unslash( $_FILES['giftprod_csv_import']['tmp_name'] ) );
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
							if ( 6 == $num_of_col && isset( $data ) && ! empty( $data ) ) {
								$pro_img_url = sanitize_text_field( $data[0] );
								$pro_name = sanitize_text_field( $data[1] );
								$pro_price = sanitize_text_field( $data[2] );
								$giftcard_code = sanitize_text_field( $data[3] );
								$giftcard_expiry = sanitize_text_field( $data[4] );
								$giftcard_template = sanitize_text_field( $data[5] );
								if ( wps_wgm_generate_product_via_csv( $pro_img_url, $pro_name, $pro_price, $giftcard_code, $giftcard_expiry, $giftcard_template ) ) {
									$product_imported = true;
								} else {
									$product_imported = false;
									$message = __( 'Fail due to some error', 'giftware' );
								}
							} else {
								$product_imported = false;
								$message = __( 'Columns are not appropriate.', 'giftware' );
							}
						}
					} else {
							// file cannot be open.
							$product_imported = false;
							$message = __( 'File Can not be opened', 'giftware' );
					}
				} else {
						// file does not exist.
						$product_imported = false;
						$message = __( 'File does not exist', 'giftware' );
				}
			} else {
				$product_imported = false;
				$message = __( 'Fail due to some error', 'giftware' );
			}
	}
	if ( $product_imported ) {
		?>
			<div class="notice notice-success is-dismissible"> 
				<p><strong><?php esc_html_e( 'Gift Card Products Imported Successfully!', 'giftware' ); ?></strong></p>
			</div>
		<?php
	} elseif ( ! $product_imported ) {
		?>
		<div class="notice notice-error is-dismissible"> 
				<p><strong><?php echo esc_html( $message ); ?></strong></p>
			</div>
		<?php
	}
}
?>
<h3 class="wps_wgm_overview_heading"><?php esc_html_e( 'Export Coupons', 'giftware' ); ?></h3>
<div class="wps_wgm_import_giftcoupons wps_wgm_export_giftcoupons">
<table class="form-table wps_wgm_general_setting">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wps_wugc_export_coupon"><?php esc_html_e( 'Export Online Coupons Details', 'giftware' ); ?></label>
			</th>
			<td class="forminp forminp-text">
				<?php
				$attribute_description = __( 'You can export CSV report of all the generated coupons from the orders.', 'giftware' );
				echo wp_kses_post( wc_help_tip( $attribute_description ) );
				?>
				<a href="admin.php?page=wps-wgc-setting-lite&wps_wugc_export_csv=wps_woo_gift_card_report" class="wps_wgm_small_button" target="_blank"><?php esc_html_e( 'Export CSV', 'giftware' ); ?> </a>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="wps_wugc_export_coupon"><?php esc_html_e( 'Export Offline Coupons Details', 'giftware' ); ?></label>
			</th>
			<td class="forminp forminp-text">
				<?php
				$attribute_description = __( 'You can export all the offline generated coupons from the orders.', 'giftware' );
				echo wp_kses_post( wc_help_tip( $attribute_description ) );
				?>
				<a href="admin.php?page=wps-wgc-setting-lite&wps_wugc_export_csv=wps_woo_offline_gift_card_report" class="wps_wgm_small_button" target="_blank"><?php esc_html_e( 'Export CSV', 'giftware' ); ?> </a>	
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="wps_wgm_import_giftcoupons">
<h3 class="wps_wgm_heading"><?php esc_html_e( 'Import Gift Coupons', 'giftware' ); ?></h3>
<table class="form-table wps_wgm_general_setting">
	<tbody>
		<tr valign="top">
			<td colspan="3" class="wps_wgm_instructions_tabledata">	
				<h3><?php esc_html_e( 'Instructions', 'giftware' ); ?></h3>
				<p> 1- <?php esc_html_e( 'Import Gift Coupons for sending your pre-defined codes rather than system generated one, we do not provide the Price field here as it will take the value of Gift card on purchasing. You need to choose a CSV file and click Import', 'giftware' ); ?></p>
				<p>2- <?php esc_html_e( 'CSV for Gift Coupons must have 3 columns in this order ( Coupon Code, Expiry Date, Usage Limit. Also first row must be the respective headings. )', 'giftware' ); ?> </p>
				<p>3- <?php esc_html_e( 'You may leave the Expiry Date field empty if you want to set your gift coupons with "No Expiry". The Expiry Date should be in days, which will be calculated after it will get assigned to a particular product..', 'giftware' ); ?> </p>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Choose a CSV file:', 'giftware' ); ?>
			</th>
			<td>
				<input class="wps_wgm_csv_custom_giftcoupon_import" name="giftcoupon_csv_import" id="giftcoupon_csv_import" type="file" size="25" value="" aria-required="true" /> 

				<input type="hidden" value="134217728" name="max_file_size">
				<small><?php esc_html_e( 'Maximum size:128 MB', 'giftware' ); ?></small>
			</td>
			<td>
				<a href="<?php echo esc_url( WPS_UWGC_URL ) . 'admin/uploads/wps_wgm_custom_gift_sample.csv'; ?>"><?php esc_html_e( 'Export Demo CSV', 'giftware' ); ?>
					<span class="wps_sample_export"><img src="<?php echo esc_url( WPS_UWGC_URL ) . 'assets/images/download.png'; ?>"></span>
				</a>
			</td>
		</tr>
		<tr>
			<td>
				<p><input name="wps_wgm_csv_custom_giftcoupon_import" id = "wps_wgm_csv_custom_giftcoupon_import" class="wps_wgm_small_button" type="submit" value="<?php esc_attr_e( 'Import', 'giftware' ); ?>"/></p>
			</td><td></td><td></td>								
		</tr>
	</tbody>
</table>
</div>
<div class="wps_wgm_import_giftproducts">
<h3 class="wps_wgm_heading"><?php esc_html_e( 'Import Gift Products', 'giftware' ); ?></h3>
<table class="form-table wps_wgm_general_setting">
	<tbody>
		<tr valign="top">
			<td colspan="3" class="wps_wgm_instructions_tabledata">	
				<h3><?php esc_html_e( 'Instructions', 'giftware' ); ?></h3>
				<p> 1- <?php esc_html_e( 'Import Gift Products along with your own custom codes. You need to choose a CSV file and click Import', 'giftware' ); ?></p>
				<p>2- <?php esc_html_e( 'CSV for Gift Products must have 6 columns in this order (Product Image URL, Giftcard Name, Giftcard Price, Giftcard Codes, Giftcard Expiry, Giftcard Template Name. Also first row must have the respective headings. )', 'giftware' ); ?> </p>
				<p>3- <?php esc_html_e( 'You may leave Giftcard Expiry field empty if you want to set your "Giftcard Expiry" with "No Expiry" & also you may leave blank "Giftcard Codes" if you want to assign that product with system generated code.', 'giftware' ); ?> </p>
				<p>4- <?php esc_html_e( 'Gift card Expiry should be in Days(i.e 2 or 3). It will be calculated after purchasing', 'giftware' ); ?> </p>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Choose a CSV file:', 'giftware' ); ?>
			</th>
			<td>
				<input class="wps_wgm_csv_custom_giftproduct_import" name="giftprod_csv_import" id="giftprod_csv_import" type="file" size="25" value="" aria-required="true" /> 

				<input type="hidden" value="134217728" name="max_file_size">
				<small><?php esc_html_e( 'Maximum size:128 MB', 'giftware' ); ?></small>
			</td>
			<td>
				<a href="<?php echo esc_url( WPS_UWGC_URL ) . 'admin/uploads/wps_wgm_giftproduct_sample.csv'; ?>"><?php esc_html_e( 'Export Demo CSV', 'giftware' ); ?>
					<span class="wps_sample_export"><img src="<?php echo esc_url( WPS_UWGC_URL ) . 'assets/images/download.png'; ?>"></span>
				</a>
			</td>
		</tr>
		<tr>
			<td>
				<p><input name="wps_wgm_csv_custom_giftproduct_import" id = "wps_wgm_csv_custom_giftproduct_import" class="wps_wgm_small_button" type="submit" value="<?php esc_attr_e( 'Import', 'giftware' ); ?>"/></p>
			</td><td></td><td></td>								
		</tr>
	</tbody>
</table>
</div>
<?php
/**
 * Generate coupon vis CSV.
 *
 * @param string $coupon_code Code.
 * @param string $coupon_exp Coupon Expiry.
 * @param string $usage_limit usage Limit.
 */
function wps_wgm_generate_coupon_via_csv( $coupon_code, $coupon_exp, $usage_limit ) {
	$the_coupon = new WC_Coupon( $coupon_code );
	$woo_ver = WC()->version;
	if ( $woo_ver < '3.0.0' ) {
		$coupon_id = $the_coupon->id;
	} else {
		$coupon_id = $the_coupon->get_id();
	}
	if ( isset( $coupon_id ) && 0 == $coupon_id ) {

		$coupon_description = 'Imported Coupon';
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
		update_post_meta( $new_coupon_id, 'coupon_amount', 0 );

		$woo_ver = WC()->version;

		if ( $woo_ver < '3.6.0' ) {
			update_post_meta( $new_coupon_id, 'expiry_date', '' );
		} else {
			update_post_meta( $new_coupon_id, 'date_expires', '' );
		}
		update_post_meta( $new_coupon_id, 'wps_wgm_expiry_date', $coupon_exp );
		update_post_meta( $new_coupon_id, 'wps_wgm_imported_coupon', 'yes' );
		update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
		return true;
	} else {
		return false;
	}
}
/**
 * Create giftcard product via CSV.
 *
 * @param mixed  $pro_img_url Url.
 * @param string $pro_name name.
 * @param mixed  $pro_price price.
 * @param mixed  $giftcard_code code.
 * @param mixed  $giftcard_expiry Expiry.
 * @param mixed  $giftcard_template template.
 */
function wps_wgm_generate_product_via_csv( $pro_img_url, $pro_name, $pro_price, $giftcard_code, $giftcard_expiry, $giftcard_template ) {
	if ( empty( $pro_name ) ) {
		$pro_name = 'Gift Card';
	}
	if ( empty( $pro_price ) ) {
		$pro_price = 10;
	}
	if ( empty( $giftcard_template ) ) {
		$giftcard_template = 'Template';
	}
	if ( ! empty( $pro_name ) && ! empty( $pro_price ) && ! empty( $giftcard_template ) ) {
		if ( ! empty( $pro_img_url ) ) {
			$filename = array( $pro_img_url );
			foreach ( $filename as $key => $value ) {

				if ( isset( $value ) && '' == $value ) {
					$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
				}

				if ( isset( $upload_file ) && ! $upload_file['error'] ) {
					$filename = $upload_file['file'];
					// The ID of the post this attachment is for.

					$parent_post_id = 0;

					// Check the type of file. We'll use this as the 'post_mime_type'.
					$filetype = wp_check_filetype( basename( $filename ), null );

					// Get the path to the upload directory.
					$wp_upload_dir = wp_upload_dir();

					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

						'post_status'    => 'inherit',
					);
					// Insert the attachment.
					$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					// Generate the metadata for the attachment, and update the database record.
					$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

					wp_update_attachment_metadata( $attach_id, $attach_data );
				}
			}
		}
		$page_object = get_page_by_title( $giftcard_template, OBJECT, 'giftcard' );
		$default_temp_id = isset( $page_object ) ? $page_object->ID : '';
		$template_id = array();
		array_push( $template_id, $default_temp_id );
		$pro_description = 'Imported Product';
		$wps_wgm_pricing = array();
		$product_array = array(
			'post_title' => $pro_name,
			'post_content' => '',
			'post_excerpt' => '',
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
			'post_type'     => 'product',
		);
		$product_id = wp_insert_post( $product_array );
		$term = __( 'Gift Card', 'giftware' );
		$taxonomy = 'product_cat';
		$term_exist = term_exists( $term, $taxonomy );
		if ( 0 == $term_exist || null == $term_exist ) {
			$args['slug'] = 'wps_wgm_giftcard';
			$term_exist = wp_insert_term( $term, $taxonomy, $args );
		}
		$wps_wgm_pricing['type'] = 'wps_wgm_default_price';
		$wps_wgm_pricing['default_price'] = $pro_price;
		$wps_wgm_pricing['template'] = $template_id;
		$wps_wgm_pricing['by_default_tem'] = $default_temp_id;
		wp_set_object_terms( $product_id, 'wgm_gift_card', 'product_type' );
		wp_set_post_terms( $product_id, $term_exist, $taxonomy );
		update_post_meta( $product_id, '_regular_price', $pro_price );
		update_post_meta( $product_id, '_price', $pro_price );
		update_post_meta( $product_id, 'wps_wgm_pricing', $wps_wgm_pricing );
		update_post_meta( $product_id, 'is_imported', 'yes' );
		update_post_meta( $product_id, 'coupon_code', $giftcard_code );
		update_post_meta( $product_id, 'expiry_after_days', $giftcard_expiry );
		if ( ! empty( $attach_id ) ) {
			set_post_thumbnail( $product_id, $attach_id );
		}
		return true;
	} else {
		return false;
	}
}
?>
