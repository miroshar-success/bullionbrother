<?php
/**
 * Cart Page
 *
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Default labels
if( empty($config['label_sku']) ){
	$config['label_sku'] = __('SKU', 'woolentor-pro') ;
}

if( empty($config['label_qty']) ){
	$config['label_qty'] = __('Qty: ', 'woolentor-pro') ;
}

if( empty($config['label_add_to_wishlist']) ){
	$config['label_add_to_wishlist'] = __('Add to wishlist', 'woolentor-pro') ;
}

if( empty($config['label_added_to_wishlist']) ){
	$config['label_added_to_wishlist'] = __('Added to wishlist', 'woolentor-pro') ;
}

if( empty($config['label_add_to_compare']) ){
	$config['label_add_to_compare'] = __('Add to compare', 'woolentor-pro') ;
}

if( empty($config['label_added_to_compare']) ){
	$config['label_added_to_compare'] = __('Product Added', 'woolentor-pro') ;
}

if( empty($config['label_delete']) ){
	$config['label_delete'] = __('Delete', 'woolentor-pro') ;
}

$orders_arr_1 = array(
	'sku'                 => $config['order_sku'],
	'meta'                => $config['order_meta_data'],
	'qty'                 => $config['order_qty'],
	'price'               => $config['order_price'],
	'stock_availability'  => $config['order_stock_availability'],
);

asort($orders_arr_1);

$orders_arr_2 = array(
	'remove_button'  => $config['order_remove_action'],
	'compare_button' => $config['order_compare_action'],
	'wishlist_button' => $config['order_wishlist_action']
);

asort($orders_arr_2);

$form_class_arr   = array('woocommerce-cart-form woolentor-cart woolentor-cart-list');
$form_class_arr[] = 'woolentor-cart-list woolentor-cart-'. $config['style'];
$form_class_arr[] = 'wl-qty-placement--'. $config['qty_input_placement'];
$form_class_arr[] = 'wl-stock-placement--'. $config['stock_availability_placement'];
?>

<div class="woocommerce">
<?php do_action( 'woocommerce_before_cart' ); ?>

	<form class="<?php echo esc_attr(implode(' ', $form_class_arr)) ?>" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="wl_cart_table shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

						// Discount
						$discount_amount_percent = '';
						$cart_product_raw_price  = $_product->get_price();
						$product_raw_price       = $_product->get_regular_price();

						if( $config['show_discount_percent'] === 'yes' &&
							$config['discount_percent_label']	&&
							$cart_product_raw_price != $product_raw_price && 
							$product_raw_price > $cart_product_raw_price ){

							$discount_amount_percent = WooLentor_Cart_Page::get_discount_percent($product_raw_price, $cart_product_raw_price);
							$discount_percent_label  = str_replace( '{discounted_amount}', (string) $discount_amount_percent, $config['discount_percent_label'] );
						}

						// Stock availability
						$availability = $_product->get_availability();

						// Compare
						if( !function_exists('woolentor_compare_button') && true != woolentor_exist_compare_plugin() ){
							$config['show_compare_action'] = '';
						}

						// Wishlist
						if( !woolentor_has_wishlist_plugin() ){
							$config['show_wishlist_action'] = '';
						}
						?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<td>
								<div class="woolentor-cart-product">
									<?php
									// Remove icon
									$remove_link = '';
									if( $config['show_thumbnail_remove_icon'] == 'yes' ){
										$remove_link =  apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove woolentor-cart-product-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1.69824 8.30167L8.30158 1.69833" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
												<path d="M8.30158 8.30167L1.69824 1.69833" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
											</svg></a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'woolentor-pro' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
									}
									?>
									<?php
										// Thumbanail
										if( $cartopt['extra_options']['remove_link'] == 'yes' ){
											$product_permalink = '';
										}
										
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
										if ( ! $product_permalink ) {
											printf( '<div class="product-thumbnail">%s %s</div>', $thumbnail, $remove_link ); // PHPCS: XSS ok.
										} else {
											printf( '<div class="product-thumbnail"><a href="%s">%s</a> %s</div>', esc_url( $product_permalink ), $thumbnail, $remove_link ); // PHPCS: XSS ok.
										}
									?>

									<div class="woolentor-cart-product-content">
										<div class="woolentor-cart-product-content-left">
											<div class="product-name">
												<?php
													if ( ! $product_permalink ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<div class="woolentor-product-name">%s</div>', $_product->get_name() ), $cart_item, $cart_item_key ) . '&nbsp;' );
													} else {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="woolentor-product-name">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
													}
												?>
													
												<?php
													do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
												?>
											</div>

											<?php if( $config['qty_input_placement'] === 'below_title' ): ?>

											<?php endif; ?>

											<?php
												foreach( $orders_arr_1 as $key => $item ){
													// Stock availability
													if( $key == 'stock_availability' && $config['show_product_stock'] === 'yes' && !empty($availability['availability']) && !empty($availability['class']) ){
														printf('<span class="woolentor-cart-product-stock %s">%s</span>',
															$availability['class'],
															$availability['availability']
														);
													}

													// Qty
													if( $key == 'qty' && $config['qty_input_placement'] === 'left' ){
													?>
														<div class="product-quantity woolentor-product-quantity">
															<?php if( !empty($config['label_qty']) ){ 
																echo '<span>'. $config['label_qty'] .' </span>';
															} ?>

															<?php
																if( $cartopt['extra_options']['disable_qtn'] === 'yes'){
																	$product_quantity = sprintf( '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', esc_html( $cart_item_key ), esc_html( $cart_item['quantity'] ) );
																}else{

																	if ( $_product->is_sold_individually() ) {
																		$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
																	} else {
																		$product_quantity = woocommerce_quantity_input( array(
																			'input_name'  => "cart[{$cart_item_key}][qty]",
																			'input_value' => $cart_item['quantity'],
																			'max_value'   => $_product->get_max_purchase_quantity(),
																			'min_value'   => '0',
																		), $_product, false );
																	}

																}

																echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
															?>
														</div>
													<?php
													}

													if( $key === 'meta' ){
														echo '<div class="woolentor-cart-product-meta-wrapper">';
													}

													// SKU
													if( $key == 'meta' && $config['show_sku'] === 'yes' && $_product->get_sku() ){
														wc_get_template( 'cart/cart-item-data.php', array(
															'type' 		=> 'sku',
															'item_data' => array(
																array(
																	'key'   => trim($config['label_sku']),
																	'display' => $_product->get_sku(),
																)
															)
														));
													}

													// Meta data. (variations)
													if( $key == 'meta' && $config['show_meta_data'] === 'yes' ){
														echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
													}

													if( $key === 'meta' ){
														echo '</div><!-- .woolentor-cart-product-meta-wrapper -->';
													}
													
													// Price
													if( $key == 'price' ){
													?>
													<div class="woolentor-product-price">
														<div class="product-price woolentor-product-price-new">
															<?php
																echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_subtotal( $_product, 1 ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
															?>
														</div>

														<?php
														if( $cart_product_raw_price != $product_raw_price ): ?>
														<div class="woolentor-product-price-old">
															<?php
															$regular_price = $_product->get_regular_price();

															if ( $_product->is_taxable() ) {
																if ( WC()->cart->display_prices_including_tax() ) {
																	$row_price	= wc_get_price_including_tax( $_product, array( 'price' => $regular_price, 'qty' => 1 ) );
																	$regular_price = wc_price( $row_price );

																	if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
																		$regular_price .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
																	}
																} else {
																	$row_price  = wc_get_price_excluding_tax( $_product, array( 'price' => $regular_price, 'qty' => 1 ) );
																	$regular_price = wc_price( $row_price );
													
																	if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
																		$regular_price .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
																	}
																}
															} else {
																$regular_price = wc_price( $regular_price );
															}

															// Showing the old price
															printf('<div class="%s">%s</div>',
																$config['line_through_old_price'] === 'yes' ? 'wl-line-through' : '',
																wp_kses_post($regular_price)
															);

															// Showing the discount amount.
															if( $discount_amount_percent ){
																printf('<div class="woolentor-cart-product-sale">%s</div>',
																	$discount_percent_label
																);
															}
															?>
														</div>
														<?php endif; ?>
													</div>	
													<?php
													} // Price									
												}
											?>
										</div>

										<div class="woolentor-cart-product-content-right">

											<!-- Action buttons -->
											<?php if( $config['show_remove_action'] === 'yes' || $config['show_compare_action'] === 'yes' || $config['show_wishlist_action'] === 'yes' ): ?>
											<div class="woolentor-cart-product-actions wl-style--<?php echo esc_attr($config['action_button_layout']) ?>">
												<?php
												foreach( $orders_arr_2 as $key => $item ){
													// Wishlist
													if( $key == 'wishlist_button' && $config['show_wishlist_action'] === 'yes' ){
														echo '<div class="woolentor-cart-product-actions-btn">'.Woolentor_Cart_Page::add_to_wishlist_button(
																'<i class="sli sli-heart"></i>',
																'<i class="sli sli-heart"></i>',
																'yes',
																array(
																	'product_id'	=> $_product->is_type('variation') ? $_product->get_parent_id() : $_product->get_id(),
																	'config'		=> $config
																)
															).'</div>';
													}

													// Compare action
													if( $key == 'compare_button' && $config['show_compare_action'] === 'yes' ){

														echo '<div class="woolentor-cart-product-actions-btn" data-wl_order="'. $config['order_compare_action'] .'">';
															Woolentor_Cart_Page::compare_button(
																array(
																	'product_id'    => $_product->is_type('variation') ? $_product->get_parent_id() : $_product->get_id(),
																	'config' 		=> $config
																)
															);
														echo '</div>';
													}

													// Remove Action
													if( $key == 'remove_button' && $config['show_remove_action'] === 'yes' ){
														if( $config['style'] == '2' && $config['delete_action_icon'] ){
															ob_start();
															\Elementor\Icons_Manager::render_icon( $config['delete_action_icon'], [ 'aria-hidden' => 'true' ]);
															$config['label_delete'] = ob_get_clean();
														}
														printf(
															'<div class="woolentor-cart-product-actions-btn"><a href="%s" class="woolentor-cart-product-actions-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-wl_order="%s">%s</a></div>',
															esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
															esc_html__( 'Remove this item', 'woolentor-pro' ),
															esc_attr( $product_id ),
															esc_attr( $_product->get_sku() ),
															$config['order_remove_action'],
															$config['label_delete']
														); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													}
												}
												?>
											</div>
											<?php endif; // action buttons ?>

											<div class="product-quantity woolentor-product-quantity">
												<?php if( !empty($config['label_qty']) ){ 
													echo '<span>'. $config['label_qty'] .' </span>';
												} ?>

												<?php
													if( $cartopt['extra_options']['disable_qtn'] === 'yes'){
														$product_quantity = sprintf( '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', esc_html( $cart_item_key ), esc_html( $cart_item['quantity'] ) );
													}else{

														if ( $_product->is_sold_individually() ) {
															$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
														} else {
															$product_quantity = woocommerce_quantity_input( array(
																'input_name'  => "cart[{$cart_item_key}][qty]",
																'input_value' => $cart_item['quantity'],
																'max_value'   => $_product->get_max_purchase_quantity(),
																'min_value'   => '0',
															), $_product, false );
														}

													}

													echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
												?>
											</div>
										</div>
									</div><!-- .woolentor-cart-product-content -->
								</div>
								<?php wp_nonce_field( 'woocommerce-cart' ); ?>
							</td>
						</tr>
						<?php
					}
				}
				?>

				
				<?php if( $config['show_continue_button'] === 'yes' || 
					$config['show_coupon_form'] === 'yes' || 
					$config['show_update_button'] === 'yes' 
					
				): ?>
				<tr>
					<td colspan="<?php echo count( $cartitem ); ?>" class="actions">
						
						<?php if ( wc_coupons_enabled() && $cartopt['coupon_form']['enable'] === 'yes' ) { ?>
							<div class="coupon">
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( $cartopt['coupon_form']['placeholder'], 'woolentor-pro' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( $cartopt['coupon_form']['button_txt'], 'woolentor-pro' ); ?>" />
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>

						<div class="wl_update_cart_shop">
							<?php if( $cartopt['continue_shop_button']['enable'] === 'yes' ){ ?>
								<a class="wlbutton-continue-shopping"  href="<?php echo esc_url( apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
									<?php echo ( is_rtl() ? '&#8594;' : '&#8592;' ) . '&nbsp;' . esc_html__( $cartopt['continue_shop_button']['button_txt'], 'woolentor-pro' ); ?>
								</a>
							<?php } ?>

							<?php
								if( $cartopt['update_cart_button']['enable'] === 'yes' ){
									echo '<input type="submit" class="button" name="update_cart" value="'.esc_attr( $cartopt['update_cart_button']['button_txt'] ).'" />';
								}
							?>
						</div>
						
					</td>
				</tr>
				<?php endif; ?>
				
			</tbody>
		</table>
	</form>

<?php do_action( 'woocommerce_after_cart' ); ?>
</div>