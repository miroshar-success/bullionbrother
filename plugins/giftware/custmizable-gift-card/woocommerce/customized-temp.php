<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );
do_action( 'wps_cgc_before_title' );
the_title( '<h1 class="product_title entry-title wps_cgw_title">', '</h1>' );
do_action( 'wps_cgc_after_title' );
global $post ,$product;
$product = wc_get_product( $post->ID );
$product_id = $post->ID;
$product_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
$price = 0;
$price_type = '';
$price_html = '';
$wps_custmize_obj = new Wps_Uwgc_Custmizable_Gift_Card_Product();
if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {
	$price_type = $wps_custmize_obj->wps_uwgc_get_custmizable_price_type( $product_pricing );
	$price_html = $wps_custmize_obj->wps_uwgc_get_custmizable_price_html( $product_pricing );
}
$checkout_url = wc_get_page_permalink( 'checkout' ) . '?add-to-cart=' . $post->ID;

if ( ! empty( $product_pricing['type'] ) && isset( $product_pricing['type'] ) ) {
	if ( 'wps_wgm_range_price' == $product_pricing['type'] ) {
		$price = $product_pricing['from'];
		if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
			if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
				$price = wcpbc_the_zone()->get_exchange_rate_price( $price );
			} else {
				$price = $product_pricing['from'];
			}
		} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
			$price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
		}
	} elseif ( 'wps_wgm_default_price' == $product_pricing['type'] ) {
		$price = $product_pricing['default_price'];

		if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
			if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
				$price = wcpbc_the_zone()->get_exchange_rate_price( $price );
			} else {
				$price = $product_pricing['default_price'];
			}
		} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
			$price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
		}
	} elseif ( 'wps_wgm_selected_price' == $product_pricing['type'] ) {
		$price = $product_pricing['default_price'];

		if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
			if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
				$price = wcpbc_the_zone()->get_exchange_rate_price( $price );
			} else {
				$price = $product_pricing['default_price'];
			}
		} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
			$price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
		}
	} elseif ( 'wps_wgm_user_price' == $product_pricing['type'] ) {
		$price = $product_pricing['default_price'];
		if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
			if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
				$price = wcpbc_the_zone()->get_exchange_rate_price( $price );
			} else {
				$price = $product_pricing['default_price'];
			}
		} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
			$price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
		}
	}
}
$custmizable_giftcard_settings      = get_option( 'wps_wgm_customizable_settings', array() );
$wps_public_obj                     = new Woocommerce_Gift_Cards_Common_Function();
$mail_settings                      = get_option( 'wps_wgm_mail_settings', array() );
$default_giftcard_message           = $wps_public_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_default_message' );
$wps_wgm_customize_default_giftcard = $wps_public_obj->wps_wgm_get_template_data( $custmizable_giftcard_settings, 'wps_wgm_customize_default_giftcard' );

if ( isset( $wps_wgm_customize_default_giftcard ) && ! empty( $wps_wgm_customize_default_giftcard ) ) {
	$backimage = $wps_wgm_customize_default_giftcard;
} else {
	$backimage = WPS_UWGC_URL . 'custmizable-gift-card/images/gift card-1.jpg';
}
?>
<!-- wps-cgw-main-container -->
<div style="display: none;" class="loading-style-bg" id="wps_wgm_loader">
	<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
</div>
<div class="wps-cgw-main-container">
	<?php do_action( 'wps_cgc_before_main_content' ); ?>
	<form class="cart" action="<?php echo esc_attr( $checkout_url ); ?>" method="post" enctype='multipart/form-data'>
		<?php wp_nonce_field( 'wps_wgm_single_nonce', 'wps_wgm_single_nonce_field' ); ?>
		<!-- wps-cgw-wrapper -->
		<div class="wps-cgw-wrapper">
			<!-- wps-cgw-wrapper-row -->
			<div class="wps-cgw-wrapper-row clearfix">
				<!-- wps-cgw-product-image -->
				<div class="wps-cgw-product-image wps-cgw-column">
					<div class="wps-cgw-product-image-wrapper">
						<h4 class="wps-cgw-heading"><?php esc_html_e( 'Gift Card Design', 'giftware' ); ?></h4>
						<ul>
							<?php
							$wps_wugc_image_enable = $wps_public_obj->wps_wgm_get_template_data( $custmizable_giftcard_settings, 'wps_wgm_image_enable' );

							if ( isset( $wps_wugc_image_enable ) && ! empty( $wps_wugc_image_enable ) && 'default_img' == $wps_wugc_image_enable ) {
								list_default_images();
							}
							if ( isset( $wps_wugc_image_enable ) && ! empty( $wps_wugc_image_enable ) && 'upload_img' == $wps_wugc_image_enable ) {
								list_all_uploaded_images();
							}
							if ( isset( $wps_wugc_image_enable ) && ! empty( $wps_wugc_image_enable ) && 'upload_and_default_img' == $wps_wugc_image_enable ) {
								list_uploaded_images();
								list_default_images();
							}
							?>
						</ul>
					</div>
				</div><!-- wps-cgw-product-image -->
				<!-- wps-cgw-preview -->
				<div class="wps-cgw-column wps-cgw-preview">
					<div class="wps-cgw-preview-wrapper">
						<h4 class="wps-cgw-heading"><?php esc_html_e( 'Preview', 'giftware' ); ?></h4>
						<div class="wps-cgw-preview-image"><img class="wps_cgw_preview_image" src="<?php echo esc_attr( $backimage ); ?>?>" alt="image"></div>

						<div class="wps-cgw-coupon-code-preview" style="background-color: <?php echo isset( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_bg_color'] ) ? esc_html( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_bg_color'] ) : ''; ?>;">
							<p class="wps-cgw-coupon"><span><?php echo esc_html( $wps_custmize_obj->wps_uwgc_get_custmizable_coupon_prefix() ) . 'XXXXX'; ?></span></p>
							<div class="clearfix">
								<p class="wps-cgw-expiry"><label><?php esc_html_e( 'ED:', 'giftware' ); ?> </label><?php echo esc_html( $wps_custmize_obj->wps_uwgc_get_custmizable_expiry_date_format() ); ?></p>
								<p class="wps-cgw-coupon-price"><label class="wps-cgw-pro-price"><?php echo wp_kses_post( wc_price( $price ) ); ?></label></p>
							</div>
						</div>
						<div class="wps-cgw-from-name" style="background-color: <?php echo isset( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_middle_color'] ) ? esc_html( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_middle_color'] ) : ''; ?>;">
							<em><p class="wps-cgw-gift-content"><?php esc_html_e( 'A gift is waiting for you!', 'giftware' ); ?></p></em>
							<div class="wps-cgw-name"><label><?php esc_html_e( 'From : ', 'giftware' ); ?></label><span class="wps_wgm_from_name"><?php esc_html_e( 'Your Name', 'giftware' ); ?></span></div>
						</div>
						<div class="wps-cgw-desclaimer" style="background-color: <?php echo isset( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_desclaimer_color'] ) ? esc_html( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_desclaimer_color'] ) : ''; ?>;"><?php echo esc_html( $wps_custmize_obj->wps_uwgc_get_custmizable_disclaimer() ); ?>
					</div>
					<?php
						do_action( 'wps_cgc_desclaimer' );
					?>
				</div>
			</div><!-- wps-cgw-preview -->
			<!-- wps-cgw-gift-details -->
			<div class="wps-cgw-column wps-cgw-gift-details">
				<div class="wps-cgw-gift-details-wrapper">
					<h4 class="wps-cgw-heading"><?php esc_html_e( 'Gift Card Details', 'giftware' ); ?></h4>
					<p class="wps-cgw-price">
						<span><?php echo $price_type; ?></span>
					</p>
					<p class="wps-cgw-price">
						<?php echo $price_html; ?>
					</p>
					<?php if ( $wps_custmize_obj->wps_uwgc_check_custmizable_schedule_date_enable() ) { ?>
						<p class="wps_cgw_heading"><?php esc_html_e( 'Send Date', 'giftware' ); ?></p>
						<p>
							<input type="text"  name="wps_uwgc_send_date" id="wps_uwgc_send_date" class="wps_uwgc_send_date">
						</p>
					<?php } ?>
					<p>
						<p class="wps_cgw_heading"><?php esc_html_e( 'Gift Message ', 'giftware' ); ?></p>
						<textarea id="wps_wgm_message" name="wps_wgm_message" cols="30" rows="3" class="wps-cgw-text" placeholder="<?php esc_html_e( 'Gift Message', 'giftware' ); ?>"><?php echo esc_html( $default_giftcard_message ); ?></textarea>
					</p>
					<p class="wps_cgw_heading"><?php esc_html_e( 'Delivery Method', 'giftware' ); ?></p>
					<?php do_action( 'wps_cgc_delivery_methods', $product_id ); ?>
					<p><label><?php esc_html_e( 'From: ', 'giftware' ); ?></label> <input type="text"  id="wps_wgm_from_name" class="wps-cgw-text" name="wps_wgm_from_name" placeholder="<?php esc_attr_e( 'Buyer\'s Name: ', 'giftware' ); ?>"></p>
					<?php
					$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
					$notification_settings = get_option( 'wps_wgm_notification_settings', array() );
					$wps_uwgc_sms_notification = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_enable_sms_notification' );
					if ( ! $wps_uwgc_sms_notification || '' === $wps_uwgc_sms_notification ) {
						$wps_uwgc_sms_notification = 'off';
					}
					$delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
					$wps_uwgc_method_enable = $wps_public_obj->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_send_giftcard' );
					if ( isset( $wps_uwgc_method_enable ) && 'shipping' !== $wps_uwgc_method_enable ) {
						if ( 'off' !== $wps_uwgc_sms_notification ) {
							?>
							<p class="wps_wgm_section wps_notification" id="wps_notification_div">
								<label class="wps_wgc_label"><?php esc_html_e( 'Share Giftcard over SMS : ', 'giftware' ); ?></label>	
								<input type="tel" name="wps_whatsapp_contact" id="wps_whatsapp_contact" class="wps_uwgc_from_name">
								<span class= "wps_uwgc_msg_info"><?php esc_html_e( 'Enter contact number with country code. Ex : 1XXXXXXX987 ( "+" not allowed)', 'giftware' ); ?></span>
								<span class= "wps_uwgc_msg_info"><?php esc_html_e( 'NOTE : No special characters & spaces are allowed.', 'giftware' ); ?></span>
							</p>
							<?php
						}
					}
					?>
				</div>
				<div class="wps-cgw-gift-details-wrapper wps-cgw-button-wrapper">
					<input type="hidden" value="<?php echo esc_attr( $post->ID ); ?>" name="product_id">
					<input type="hidden" value="1" name="quantity">
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $post->ID ); ?>" class="single_add_to_cart_button button alt support_cart" name="support_cart"><?php esc_html_e( 'Buy Now', 'giftware' ); ?></button>
				</div>
			</div><!-- wps-cgw-gift-details -->
		</div>	<!-- wps-cgw-wrapper-row -->
	</div><!-- wps-cgw-wrapper -->
</form>
</div><!-- wps-cgw-main-container -->
<?php
get_footer( 'shop' );

/**
 * Upload default images.
 *
 * @since    1.0.0
 */
function list_default_images() {
	?>
	<li>
		<a href="javascript:;" class="wps-cgw-image"><img class="wps_cgw_choose_img" src="<?php echo esc_url( WPS_UWGC_URL ) . 'custmizable-gift-card/images/christmas.jpg'; ?>" data-img="Christmas" alt="image"></a>
	</li>
	<li>
		<a href="javascript:;" class="wps-cgw-image"><img  class="wps_cgw_choose_img" src="<?php echo esc_url( WPS_UWGC_URL ) . 'custmizable-gift-card/images/new year.jpg'; ?>" data-img="Newyear" alt="image"></a>
	</li>
	<li>
		<a href="javascript:;" class="wps-cgw-image"><img class="wps_cgw_choose_img" src="<?php echo esc_url( WPS_UWGC_URL ) . 'custmizable-gift-card/images/anniversary.jpg'; ?>" data-img="Anniversary" alt="image"></a>
	</li>
	<li>
		<a href="javascript:;" class="wps-cgw-image"><img class="wps_cgw_choose_img" src="<?php echo esc_url( WPS_UWGC_URL ) . 'custmizable-gift-card/images/happy birthday.jpg'; ?>" data-img="Birthday" alt="image"></a>
	</li>
	<li>
		<a href="javascript:;" class="wps-cgw-image"><img class="wps_cgw_choose_img" src="<?php echo esc_url( WPS_UWGC_URL ) . 'custmizable-gift-card/images/gift card-1.jpg'; ?>" data-img="Giftcard" alt="image"></a>
	</li>
	<li class="wps_cgw_choose_img" data-img="Custom">
		<input type="file" value="Upload Image" class="wps-cgw-text-upload" id="wps_cgw_upload_img" name="wps_cgc_custom_img" >
		<input type="hidden" name="upload_path" id="upload_path" value="<?php echo esc_attr( wp_upload_dir()['baseurl'] ); ?>">
		<?php
		$custmizable_giftcard_settings = get_option( 'wps_wgm_customizable_settings', array() );
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_wgm_customize_default_giftcard = $wps_public_obj->wps_wgm_get_template_data( $custmizable_giftcard_settings, 'wps_wgm_customize_default_giftcard' );
		if ( isset( $wps_wgm_customize_default_giftcard ) && ! empty( $wps_wgm_customize_default_giftcard ) ) {
			$backimage = $wps_wgm_customize_default_giftcard;
			echo ( '<input type="hidden" name="selected_image" id="selected_image" value="' . esc_html( $backimage ) . '">' );
		} else {
			echo ( '<input type="hidden" name="selected_image" id="selected_image" value="Giftcard">' );
		}
		?>
		<div class="wps-cgw-image-upload"><span>&#10010;</span><?php esc_html_e( 'Upload Image', 'giftware' ); ?></div>
		<span class="wps-cgw-description"><?php esc_html_e( '(Suggested Dimension: 600*400)', 'giftware' ); ?></span>
	</li>
	<?php
}

/**
 * List all uploaded images.
 *
 * @since    1.0.0
 */
function list_all_uploaded_images() {
	list_uploaded_images();
	?>
	<li class="wps_cgw_choose_img" data-img="Custom">
		<input type="file" value="Upload Image" class="wps-cgw-text-upload" id="wps_cgw_upload_img" name="wps_cgc_custom_img" >
		<input type="hidden" name="upload_path" id="upload_path" value="<?php echo esc_attr( wp_upload_dir()['baseurl'] ); ?>">
		<?php
		$custmizable_giftcard_settings = get_option( 'wps_wgm_customizable_settings', array() );
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_wgm_customize_default_giftcard = $wps_public_obj->wps_wgm_get_template_data( $custmizable_giftcard_settings, 'wps_wgm_customize_default_giftcard' );
		if ( isset( $wps_wgm_customize_default_giftcard ) && ! empty( $wps_wgm_customize_default_giftcard ) ) {
			$backimage = $wps_wgm_customize_default_giftcard;
			echo ( '<input type="hidden" name="selected_image" id="selected_image" value="' . esc_html( $backimage ) . '">' );
		} else {
			echo ( '<input type="hidden" name="selected_image" id="selected_image" value="Giftcard">' );
		}
		?>
		<input type="hidden" name="uploaded_image_value" id="uploaded_image_value" value="Giftcard">
		<div class="wps-cgw-image-upload"><span>&#10010;</span>Upload Image</div>
	</li>
	<?php
}

/**
 * List all uploaded images.
 *
 * @since    1.0.0
 */
function list_uploaded_images() {
	$custmizable_giftcard_settings = get_option( 'wps_wgm_customizable_settings', array() );

	$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
	$imageurl = $wps_public_obj->wps_wgm_get_template_data( $custmizable_giftcard_settings, 'wps_wgm_customize_email_template_image' );

	if ( isset( $imageurl ) && ! empty( $imageurl ) && is_array( $imageurl ) ) {
		foreach ( $imageurl as $value ) {
			if ( ! empty( $value ) ) {
				?>
				<li>
					<a href="javascript:;" class="wps-cgw-image"><img class="wps_cgw_choose_img" data-img="<?php echo esc_attr( $value ); ?>" data-value="<?php echo esc_attr( $value ); ?>" src="<?php echo esc_attr( $value ); ?>"  alt="image" ></a>
				</li>
				<?php
			}
		}
	}
}
?>
