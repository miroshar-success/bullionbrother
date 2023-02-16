<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $product = wc_get_product( get_the_ID() );

    // Add to cart Button Classes
    $btn_class = 'woolentor-product-addtocart product_type_' . $product->get_type();

    $btn_class .= $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

    $btn_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';

    // Add to Cart Button
    $cart_btn = $button_icon = '';
    if( !empty( $args['button_icon']['value'] ) ){
        $btn_class .= ' woolentor-button-icon-'.$args['button_icon_align'];
        $button_icon = woolentor_render_icon( $args, 'button_icon', 'buttonicon' );
    }
    $button_text  = ! empty( $args['add_to_cart_text'] ) ? $args['add_to_cart_text'] : '';

    if( $args['button_icon_align'] === 'right' ){
        $cart_btn = $button_text.$button_icon;
    }else{
        $cart_btn = $button_icon.$button_text;
    }

    $image_size = 'full';

    // Generate Category Name list
    $category_name_list = array();
    $terms = get_the_terms( $product->get_id(), 'product_cat' );
    if ( ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $term = sanitize_term( $term, 'product_cat' );
            $category_name_list[ $product->get_id() ][ 'product_cat' ][] = $term->name;
        }
    } else {
        $category_name_list[ $product->get_id() ][ 'product_cat' ][] = '-';
    }

    // Link attributes
    $link_attributes = array(
        'aria-label' => $product->add_to_cart_description(),
        'rel'        => 'nofollow',
    );

?>
<div class="woolentor-grid__item">
    <div class="grid__product">
        <div class="product__bg"></div>
        <?php echo $product->get_image( $image_size, array( 'class'=>'product__img', 'alt'=>$product->get_slug() ) ); ?>
        <h2 class="product__title"><?php the_title(); ?></h2>
        <h3 class="product__subtitle"><?php echo implode( ', ', $category_name_list[ $product->get_id() ][ 'product_cat' ] ); ?></h3>
        <p class="product__description"><?php echo get_the_excerpt(); ?></p>
        <div class="product__price"><?php echo $product->get_price_html(); ?></div>
        <div class="product__price_after" style="display: none;"><?php do_action( 'woolentor_addon_after_price' ); ?></div>
        <div class="product__addtocart" style="display: none;">
            <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>" <?php echo wc_implode_html_attributes( $link_attributes ); ?>><?php echo __( $cart_btn, 'woolentor-pro' );?></a>
        </div>
    </div>
</div>