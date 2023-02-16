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


do_action( 'woocommerce_before_cart' );
?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="wl_cart_table <?php echo $cartopt['cart_layout_sytle']; ?> shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<?php
					foreach ( $cartitem as $itemvalue ) {
						if( $itemvalue['table_items'] == 'customadd' ){
							echo '<th class="product-'.esc_attr( uniqid('wlcustomitem_') ).' elementor-repeater-item-'.$itemvalue['_id'].'">'.esc_html( $itemvalue['table_heading_title'] ).'</th>';
						}else{
							echo '<th class="product-'.esc_attr( $itemvalue['table_items'] ).' elementor-repeater-item-'.$itemvalue['_id'].'">'.esc_html( $itemvalue['table_heading_title'] ).'</th>';
						}
					}
				?>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<?php
							foreach ( $cartitem as $itemvalue ) {

								switch ( $itemvalue['table_items'] ) {

									case 'remove':
										echo '<td class="product-remove elementor-repeater-item-'.$itemvalue['_id'].'">';
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove woolentor-cart-product-remove %s %s" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_attr( $cartopt['remove_icon_style'] ),
												esc_attr( $cartopt['remove_icon_position'] ),
												esc_html__( 'Remove this item', 'woolentor-pro' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() ),
												woolentor_render_icon( $cartopt, 'remove_icon', 'removeicon' )
											), $cart_item_key );
										echo '</td>';
										break;

									case 'thumbnail':
										echo '<td class="product-thumbnail elementor-repeater-item-'.$itemvalue['_id'].'">';
											$content_alignment = $cartopt['thumbnail_content_alignment'] ? 'inline' : '';
											echo '<div class="woolentor-cart-product '.$content_alignment.' '.'">';
												echo '<div class="woolentor-cart-img">';
													$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
													if ( ! $product_permalink ) {
														echo ( $thumbnail ); // PHPCS: XSS ok.
													} else {
														printf( '<a class="woolentor-cart-product-thumb" href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
													}

													if(!$cartopt['thumbnail_remove_icon']){
														echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
															'<a href="%s" class="remove woolentor-cart-product-remove %s %s" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
															esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
															esc_attr( $cartopt['remove_icon_style'] ),
															esc_attr( $cartopt['remove_icon_position'] ),
															esc_html__( 'Remove this item', 'woolentor-pro' ),
															esc_attr( $product_id ),
															esc_attr( $_product->get_sku() ),
															woolentor_render_icon( $cartopt, 'remove_icon', 'removeicon' )
														), $cart_item_key );
													}
												echo '</div>';
												echo '<div class="woolentor-cart-product-content">';
													echo '<h4 class="woolentor-cart-product-title">';
														if ( ! $product_permalink || $cartopt['extra_options']['remove_link'] === 'yes' ) {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
														} else {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
														}
													echo '</h4>';

													if( $cartopt['extra_options']['show_category'] === 'yes' ){
														$product_item = $cart_item['data'];
											        // make sure to get parent product if variation
											        if ( $product_item->is_type( 'variation' ) ) {
											            $product_item = wc_get_product( $product_item->get_parent_id() );
											        }
											        $cat_ids = $product_item->get_category_ids();
											        // if product has categories, concatenate cart item name with them
											        echo  ( $cat_ids ? '<div class="woolentor-cart-cats">' . wc_get_product_category_list( $product_item->get_id(), ', ',
											                '<span class="posted_in">' . _n( '<label>Category</label>:', '<label>Categories</label>:', count( $cat_ids ), 'woolentor-pro') . ' ',
											            '</span></div>'
											        ) : '' ) ;
													}

													if( $cartopt['extra_options']['show_sku'] === 'yes' ){
														if($_product->get_sku()){
															echo '<div class="woolentor-cart-product-id-wraper">';
															echo '<label>SKU: </label>';
															echo '<p class="woolentor-cart-product-id">'.$_product->get_sku().'</p>';
															echo '</div>';
														}
													}

													if( $cartopt['extra_options']['show_variation'] === 'yes' ){
														if ( woolentor_is_preview_mode() ){
											            echo WooLentor_Cart_Page::wl_get_formatted_cart_item_data($cart_item);
														}else{
															echo wc_get_formatted_cart_item_data( $cart_item );
														}
													}

													if( $cartopt['extra_options']['show_stock'] === 'yes' ){
														woolentor_cart_page_stock( $cart_item, $cart_item_key );
													}

													// Backorder notification.
													if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woolentor-pro' ) . '</p>', $product_id ) );
													}

												echo '</div>';
											echo '</div>';
										echo '</td>';
										break;

									case 'name':
										echo '<td class="product-name elementor-repeater-item-'.$itemvalue['_id'].'" data-title="'.$itemvalue['table_heading_title'].'">';
											if ( ! $product_permalink || $cartopt['extra_options']['remove_link'] === 'yes' ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
											} else {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}

											if( $cartopt['extra_options']['show_category'] === 'yes' ){
												woolentor_cart_page_categories( $cart_item, $cart_item_key );
											}

											if( $cartopt['extra_options']['show_stock'] === 'yes' ){
												woolentor_cart_page_stock( $cart_item, $cart_item_key );
											}

											do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

											// Meta data.
											echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

											// Backorder notification.
											if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woolentor-pro' ) . '</p>', $product_id ) );
											}
										echo '</td>';
										break;

									case 'price':
										echo '<td class="product-price elementor-repeater-item-'.$itemvalue['_id'].'" data-title="'.$itemvalue['table_heading_title'].'">';
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										echo '</td>';
										break;

									case 'quantity':
										echo '<td class="product-quantity elementor-repeater-item-'.$itemvalue['_id'].'" data-title="'.$itemvalue['table_heading_title'].'">';

											if( $cartopt['extra_options']['disable_qtn'] === 'yes'){
												$product_quantity = sprintf( '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', esc_html( $cart_item_key ), esc_html( $cart_item['quantity'] ) );
												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
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

												if($cartopt['qty_layout_sytle']){
												   $plus_icon  = woolentor_render_icon( $cartopt, 'quantity_plus_icon', 'quantiytplusicon' );
													$minus_icon = woolentor_render_icon( $cartopt, 'quantity_minus_icon', 'quantiytminusicon' );
													

													?>
														<div class="woolentor-cart-product-quantity <?php echo $cartopt['qty_layout_sytle']; ?>">
															<button class="woolentor-cart-product-quantity-btn minus">
																<?php echo $minus_icon; ?>
															</button>	
													<?php
															echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
													?>
															<button class="woolentor-cart-product-quantity-btn plus">
																<?php echo $plus_icon; ?>
															</button>	
														</div>
													<?php
												}else{
													echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
												}

											}
										echo '</td>';
										break;

									case 'subtotal':
										echo '<td class="product-subtotal elementor-repeater-item-'.$itemvalue['_id'].'" data-title="'.$itemvalue['table_heading_title'].'">';
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										echo '</td>';
										break;

									case 'customadd':
										echo '<td class="product-wlcustomdata elementor-repeater-item-'.$itemvalue['_id'].'">';
											$cart_custom_data = get_post_meta( $product_id, 'woolentor_cart_custom_content', true );
											echo ( isset( $cart_custom_data ) ? esc_html( $cart_custom_data ) : '' );
										echo '</td>';
										break;

									default:
										break;
								}

							}
						?>

					</tr>
					<?php
				}
			}
			?>

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

					<?php wp_nonce_field( 'woocommerce-cart' ); ?>
					
				</td>
			</tr>
			
		</tbody>
	</table>
</form>
<script type="text/javascript">
    ;jQuery(document).ready(function($){ 
        $('.woolentor-cart-product-quantity').on( 'click', '.woolentor-cart-product-quantity-btn.minus, .woolentor-cart-product-quantity-btn.plus', function(e) {

        	e.preventDefault();
            
            // Get current quantity values
            var qty = $( this ).siblings( '.quantity' ).find( '.qty' );
            var val = parseFloat(qty.val());
            var min_val = 1;
            
            var max  = parseFloat(qty.attr( 'max' ));
            var min  = parseFloat(qty.attr( 'min' ));
            var step = parseFloat(qty.attr( 'step' ));
 
            // Change the value if plus or minus
            if ( $( this ).is( '.plus' ) ) {
               if ( max && ( max <= val ) ) {
                  qty.val( max );
               } 
               else{
                   qty.val( val + step );
                }
            } 
            else {
               if ( min && ( min >= val ) ) {
                  qty.val( min );
               } 
               else if ( val > min_val ) {
                  qty.val( val - step );
               }
            }

            $(".wl_update_cart_shop input[type=submit]").prop('disabled', false);
             
        });
    });        
</script>

<?php do_action( 'woocommerce_after_cart' ); ?>