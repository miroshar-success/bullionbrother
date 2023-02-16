<?php
/**
 * Cross sells style 2
 *
 * This template can be overridden by copying it to yourtheme/wl-woo-templates/cart/cross-sells-style-two.php.
 */
defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>
	<div class="wl-products wl-cart-cross-sell-2">
	<?php
		$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'woocommerce' ) );
		if( !empty($config['heading']) ){
			$heading = $config['heading'];
		}

		if ( $heading ) :
			?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>

				<?php
					$post_object = get_post( $cross_sell->get_id() );
					$product     = wc_get_product($cross_sell->get_id());

					$rating_count = $cross_sell->get_rating_count();
					$review_count = $cross_sell->get_review_count();
					$average      = $cross_sell->get_average_rating();

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
					?>
						<div class="wl-product">
							<a href="<?php echo esc_url($cross_sell->get_permalink()) ?>" class="wl-product-thumb">
								<?php echo wp_kses_post($cross_sell->get_image()) ?>
							</a>
							
							<div class="wl-product-content">
								<h2 class="wl-product-title">
									<a href="<?php echo esc_url($cross_sell->get_permalink()) ?>"><?php echo wp_kses_post($cross_sell->get_title()) ?></a>
								</h2>

								<?php if( $review_count > 0 ): ?>
								<div class="wl-product-rating">
									<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
									<span class="wl-product-rating-number">(<?php echo esc_html($review_count) ?>)</span>
								</div>
								<?php endif; ?>
								
								<div class="wl-product-price">
									<?php echo wp_kses_post( $cross_sell->get_price_html() ) ?>
								</div>
							</div>
						</div>
					<?php
				?>

			<?php endforeach; ?>

	</div><!-- .wl-cart-cross-sell-2 -->
	<?php
endif;

wp_reset_postdata();