<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $product = wc_get_product( get_the_ID() );

    // Add to cart Button Classes
    $btn_class = 'woolentor-product-addtocart product_type_' . $product->get_type();

    $btn_class .= $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

    $btn_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';

    $image_size = 'woocommerce_thumbnail';

    $collumval = 'wl-col-1';
    if( $args['column'] !='' ){
        $collumval = 'wl-col-'.$args['column'];
    }

    if( isset( $args['gridcolumn'] ) ){
        $collumval = 'woolentor-grid-column';
    }

    // Add to Cart Button
    $cart_btn = $button_icon = '';
    if( !empty( $args['button_icon']['value'] ) ){
        $btn_class .= ' woolentor-button-icon-'.$args['button_icon_align'];
        $button_icon = woolentor_render_icon( $args, 'button_icon', 'buttonicon' );
    }else if( !empty( $args['buttonIcon'] ) ){
        $btn_class .= ' woolentor-button-icon-'.$args['buttonIconAlign'];
        $button_icon = '<i class="'.$args['buttonIcon'].'"></i>';
    }else{
        $cart_btn = $button_icon = '';
    }

    $button_text  = ! empty( $args['add_to_cart_text'] ) ? $args['add_to_cart_text'] : '';

    $cart_btn = $button_icon.$button_text;

    // Gallery image
    $secondary_image = '';
    $attachment_ids = $product->get_gallery_image_ids();
    if ( is_array( $attachment_ids ) && !empty( $attachment_ids ) ) {
        $secondary_image = wp_get_attachment_image( $attachment_ids[0], $image_size, '', array( 'class'=>'ht-hover-img' ) );
    }

    // Link attributes
    $link_attributes = array(
        'aria-label' => $product->get_title(),
        'rel'        => 'nofollow',
    );
?>
<div class="product woolentor-grid-style-<?php echo $args['grid_style']; ?> <?php echo esc_attr( $collumval ); ?>">
    <div class="ht-product-2 ht-overflow-hidden">
        <div class="ht-product-image-wrap-2 ht-overflow-hidden">
            <a href="<?php the_permalink(); ?>" <?php echo wc_implode_html_attributes( $link_attributes ); ?>>
                <?php
                    if( 'secondary_img' === $args['image_layout_type'] ){
                        echo $product->get_image( $image_size, array( 'class'=>'ht-default-img', 'alt'=>$product->get_slug() ) );
                        echo $secondary_image;
                    }else{
                        echo $product->get_image( $image_size, array( 'class'=>'ht-default-img ht-product-img-zoom', 'alt'=>$product->get_slug() ) ); 
                    }
                ?>
            </a>
            <div class="ht-product-badges ht-badges-right">
                <?php
                    if( class_exists('WooCommerce') ){ 
                        woolentor_custom_product_badge(); 
                        woolentor_sale_flash();
                    }
                ?>
            </div>

            <?php if( '2' === $args['grid_style'] ): ?>
                <div class="ht-product-action-3">
                    <ul>
                        <?php
                            if( true === woolentor_has_wishlist_plugin() ){
                                echo '<li>'.woolentor_add_to_wishlist_button('<i class="far fa-heart"></i>','<i class="fas fa-heart"></i>', 'yes').'</li>';
                            }
                        ?>
                        <li class="wlgrid-cart-btn"><a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor-pro' );?></a></li>
                        <li class="wlgrid-quickview">
                            <a href="#" class="ht-product-action-icon-2 woolentorquickview" data-quick-id="<?php the_ID();?>" ><i class="sli sli-magnifier"></i></a>
                        </li>
                    </ul>
                </div>

            <?php elseif( '1' === $args['grid_style'] || '3' === $args['grid_style'] ): ?>
                <div class="ht-product-action-2">
                    <ul>
                        <li>
                            <a href="#" class="ht-product-action-icon-2 woolentorquickview" data-quick-id="<?php the_ID();?>" ><i class="sli sli-magnifier"></i></a>
                        </li>
                        <?php
                            if( true === woolentor_has_wishlist_plugin() ){
                                echo '<li>'.woolentor_add_to_wishlist_button('<i class="far fa-heart"></i>','<i class="fas fa-heart"></i>', 'yes').'</li>';
                            }

                            if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() ){
                                echo '<li>';
                                    woolentor_compare_button(
                                        array(
                                            'style'=>2,
                                            'btn_text'=>'<i class="sli sli-refresh"></i>',
                                            'btn_added_txt'=>'<i class="sli sli-check"></i>'
                                        )
                                    );
                                echo '</li>';
                            }
                        ?>
                    </ul>
                </div>
            <?php else:?>
                
            <?php endif; ?>
        </div>

        <div class="ht-product-content-2">

            <?php if( '1' == $args['grid_style'] || '2' == $args['grid_style'] || '3' == $args['grid_style'] ): if( $args['hide_category'] !== 'yes' ): ?>
                <div class="ht-product-categories-2">
                    <?php woolentor_get_product_category_list(); ?>
                </div>
            <?php endif; endif; ?>

            <?php if( '1' === $args['grid_style'] ): ?>
                <h4 class="ht-product-title-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <div class="ht-price-addtocart-wrap">
                    <div class="ht-product-price-2">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="ht-addtocart">
                        <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor-pro' );?></a>
                    </div>
                </div>
                <?php do_action( 'woolentor_addon_after_price' ); ?>

                <?php if( $args['hide_rating'] !== 'yes' ): ?>
                    <div class="ht-product-ratting-2">
                        <?php echo woolentor_wc_get_rating_html(); ?>
                    </div>
                <?php endif; ?>

            <?php elseif( '2' === $args['grid_style'] ): ?>
                <h4 class="ht-product-title-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <div class="ht-product-price-2">
                    <?php echo $product->get_price_html(); ?>
                </div>
                <?php do_action( 'woolentor_addon_after_price' ); ?>

                <?php if( $args['hide_rating'] !== 'yes' ): ?>
                    <div class="ht-product-ratting-2">
                        <?php echo woolentor_wc_get_rating_html(); ?>
                    </div>
                <?php endif; ?>

            <?php elseif( '3' === $args['grid_style'] ): ?>
                <h4 class="ht-product-title-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                
                <?php if( $args['hide_rating'] !== 'yes' ): ?>
                    <div class="ht-product-ratting-2">
                        <?php echo woolentor_wc_get_rating_html(); ?>
                    </div>
                <?php endif; ?>

                <div class="ht-price-addtocart-wrap">
                    <div class="ht-product-price-2">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="ht-addtocart-2">
                        <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor-pro' );?></a>
                    </div>
                </div>
                <?php do_action( 'woolentor_addon_after_price' ); ?>

            <?php elseif( '4' === $args['grid_style'] ): ?>
                <div class="ht-product-content-2-wrap ht-text-center">
                    <div class="ht-product-content-2 ht-product-content-2-hidden">

                        <?php if( $args['hide_category'] !== 'yes' ): ?>
                            <div class="ht-product-categories-2">
                                <?php woolentor_get_product_category_list(); ?>
                            </div>
                        <?php endif; ?>

                        <h4 class="ht-product-title-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <div class="ht-product-price-2">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                        <?php do_action( 'woolentor_addon_after_price' ); ?>

                        <?php if( $args['hide_rating'] !== 'yes' ): ?>
                            <div class="ht-product-ratting-2">
                                <?php echo woolentor_wc_get_rating_html(); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="ht-product-action-5">
                        <ul>
                            <li>
                                <a href="#" class="ht-product-action-icon-2 woolentorquickview" data-quick-id="<?php the_ID();?>" ><i class="sli sli-magnifier"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor-pro' );?></a>
                            </li>
                            <?php
                                if( true === woolentor_has_wishlist_plugin() ){
                                    echo '<li>'.woolentor_add_to_wishlist_button('<i class="far fa-heart"></i>','<i class="fas fa-heart"></i>', 'yes').'</li>';
                                }
                                if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() ){
                                    echo '<li>';
                                        woolentor_compare_button(
                                            array(
                                                'style'=>2,
                                                'btn_text'=>'<i class="sli sli-refresh"></i>',
                                                'btn_added_txt'=>'<i class="sli sli-check"></i>'
                                            )
                                        );
                                    echo '</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>

            <?php else: ?>
                <div class="ht-product-content-2-wrap ht-text-center">
                    <div class="ht-product-content-2 ht-product-content-2-up">
                        <?php if( $args['hide_category'] !== 'yes' ): ?>
                            <div class="ht-product-categories-2">
                                <?php woolentor_get_product_category_list(); ?>
                            </div>
                        <?php endif; ?>

                        <h4 class="ht-product-title-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <div class="ht-product-price-2">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                        <?php do_action( 'woolentor_addon_after_price' ); ?>
                        <?php if( $args['hide_rating'] !== 'yes' ): ?>
                            <div class="ht-product-ratting-2">
                                <?php echo woolentor_wc_get_rating_html(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="ht-product-action-6">
                        <ul>
                            <li>
                                <a href="#" class="ht-product-action-icon-2 woolentorquickview" data-quick-id="<?php the_ID();?>" ><i class="sli sli-magnifier"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="1" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'woolentor-pro' );?></a>
                            </li>
                            <?php
                                if( true === woolentor_has_wishlist_plugin() ){
                                    echo '<li>'.woolentor_add_to_wishlist_button('<i class="far fa-heart"></i>','<i class="fas fa-heart"></i>', 'yes').'</li>';
                                }
                                if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() ){
                                    echo '<li>';
                                        woolentor_compare_button(
                                            array(
                                                'style'=>2,
                                                'btn_text'=>'<i class="sli sli-refresh"></i>',
                                                'btn_added_txt'=>'<i class="sli sli-check"></i>'
                                            )
                                        );
                                    echo '</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>