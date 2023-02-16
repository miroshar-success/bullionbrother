<?php
/**
 * The Template for displaying option to remove and the order bump.
 *
 * This template can be overridden by copying it to yourtheme/wl-woo-templates/order-bump/order-bump-action.php
 *
 * It is callded in the following places:
 * includes/modules/order-bump/templates/order-bump.php
 * 
 * Passed in $args:
 * $args['order_bump_id']
 * $args['product']
 * $args['cart_item_key']
 * $args['checked']
 * $args['style']
 */
?>
<div class="woolentor-order-bump-action">
    <?php if( $product->is_type('variable') ): ?>
        <a href="javascript:void(0);" class="woolentor-order-bump-select-options" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
            <?php echo esc_html__('Select Options', 'woolentor-pro') ?>
        </a>
    <?php else: ?>
        <div class="wl-checkbox-wrapper">
            <input type="checkbox" class="woolentor-order-bump-checkbox" id="<?php echo esc_attr( $order_bump_id ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-order_bump_id="<?php echo esc_attr( $order_bump_id ); ?>" data-cart_item_key="<?php echo esc_attr($cart_item_key) ?>" <?php checked( 1, $checked ); ?>>
            <label for="<?php echo esc_attr( $order_bump_id ); ?>"><?php echo wp_kses_post($label_grab_this_offer) ?></label>
        </div>
    <?php endif; ?>

    <?php if( $style == '5' ): ?>
    <div class="wl-price">
        <?php if ( $price_html = $product->get_price_html() ) : ?>
            <span class="price"><?php echo wp_kses_post($price_html); ?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>