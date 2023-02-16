<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();
?>
<div class="woocommerce woolentor-elementor-empty-cart">
    <?php
        do_action( 'woolentor_cartempty_content_build' );
        echo '<div style="display:none;">';
            do_action( 'woocommerce_cart_is_empty' );
        echo '</div>';
    ?>
</div>