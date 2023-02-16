<?php  
    $cart_position = woolentor_get_option_pro( 'mini_cart_position', 'woolentor_others_tabs', 'left' );
    $cart_icon = woolentor_get_option_pro( 'mini_cart_icon', 'woolentor_others_tabs', 'sli sli-basket-loaded' );
?>
<div class="woolentor_mini_cart_area woolentor_mini_cart_pos_<?php echo esc_attr( $cart_position ); ?>">
    <div class="woolentor_mini_cart_icon_area">
        <?php $count_item = WC()->cart->get_cart_contents_count(); ?>
        <span class="woolentor_mini_cart_counter"><?php echo $count_item; ?></span>
        <span class="woolentor_mini_cart_icon"><i class="<?php echo esc_attr( $cart_icon ); ?>"></i></span>
    </div>
    <div class="woolentor_body_opacity"></div>
    <div class="woolentor_cart_content_container">
        <?php do_action( 'woolentor_cart_content' ); ?>
    </div>
</div>