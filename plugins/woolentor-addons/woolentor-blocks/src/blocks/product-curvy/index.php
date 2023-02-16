<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass     = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses   = array( $uniqClass, 'woocommerce', 'woolentor-product-curvy' );

!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-grid-columns-'.$settings['columns']['desktop'] : 'woolentor-grid-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-grid-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-grid-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-grid-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-grid-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-grid-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-grid-columns-mobile-1';

$queryArgs = [
	'perPage'	=> $settings['perPage'],
	'filterBy'	=> $settings['productFilterType']
];
if( $settings['customOrder'] ){
	$queryArgs['orderBy'] = $settings['orderBy'];
	$queryArgs['order'] = $settings['order'];
}
if( is_array( $settings['selectedCategories'] ) && count( $settings['selectedCategories'] ) > 0 ){
	$queryArgs['categories'] = $settings['selectedCategories'];
}
$products = new \WP_Query( woolentorBlocks_Product_Query( $queryArgs ) );


$content_style = '';
if( isset( $settings['layout'] ) ){
	if ( $settings['layout'] == '2' ) {
		$content_style = 'wl_left-item';
	}elseif ( $settings['layout']=='3' ) {
		$content_style = 'wl_dark-item';
	}else{
		$content_style = '';
	}
}

?>
<div class="<?php echo implode(' ', $areaClasses ); ?>">

	<?php if( $products->have_posts() ): ?>

		<div class="woolentor-grid <?php echo ( $settings['noGutter'] === true ? 'woolentor-no-gutters' : '' ); ?>">
			<?php
				while( $products->have_posts() ) {
					$products->the_post();

					$product = wc_get_product( get_the_ID() );

					$btn_class = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

					$btn_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';
					$description = wp_trim_words ( get_the_content(), $settings['contentLimit'], '' );

					?>
						<div class="woolentor-grid-column">
							<div class="wl_single-product-item <?php echo $content_style; ?>">

								<a href="<?php the_permalink(); ?>" class="product-thumbnail">
									<div class="images">
										<?php echo $product->get_image( 'full' ); //woocommerce_template_loop_product_thumbnail(); ?>
									</div>
								</a>

								<div class="product-content">
									<div class="product-content-top">

										<?php if( $settings['showTitle'] === true ): ?>
											<h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
										<?php endif; ?>

										<?php if( $settings['showPrice'] === true ): ?>
											<div class="product-price">
												<span class="new-price"><?php woocommerce_template_loop_price();?></span>
											</div>
										<?php endif; ?>

										<?php
											if( $settings['showContent'] === true ){
												echo '<p>'.$description.'</p>';
											}
										?>

										<?php if( $settings['showRating'] === true ): ?>
											<div class="reading">
												<?php woocommerce_template_loop_rating(); ?>
											</div>
										<?php endif; ?>

									</div>
									<ul class="action">
										<li class="wl_cart">
											<a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="action-item <?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( '<i class="fa fa-shopping-cart"></i>', 'woolentor' );?></a>
										</li>
										<?php
											if( true === woolentor_has_wishlist_plugin() ){
												echo '<li>'.woolentor_add_to_wishlist_button('<i class="fa fa-heart-o"></i>','<i class="fa fa-heart"></i>').'</li>';
											}
										?>                                    
										<?php
											if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() ){
												echo '<li>';
													woolentor_compare_button(
														array(
															'style' => 2,
															'btn_text' => '<i class="fa fa-exchange"></i>',
															'btn_added_txt' => '<i class="fa fa-exchange"></i>' 
														)
													);
												echo '</li>';
											}
										?>
									</ul>
								</div>

							</div>
						</div>
					<?php
				}
			?>
		</div>

	<?php wp_reset_postdata(); endif; ?>
	
</div>