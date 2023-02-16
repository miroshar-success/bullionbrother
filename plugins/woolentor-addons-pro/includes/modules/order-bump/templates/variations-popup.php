<?php
/**
 * The Template for displaying variations form into the popup.
 *
 * This template can be overridden by copying it to yourtheme/wl-woo-templates/order-bump/variations-popup.php
 *
 * It is callded in the following places:
 * render_variations_popup() in /includes/class-ajax-actions.php
 * 
 * Passed in $args:
 * $args['product']
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$attachment_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
if ( $product->get_image_id() ){
    $attachment_ids = array( 'wlquick_thumbnail_id' => $product->get_image_id() ) + $attachment_ids;
}

// Placeholder image set
if( empty( $attachment_ids ) ){
    $attachment_ids = array( 'wlquick_thumbnail_id' => get_option( 'woocommerce_placeholder_image', 0 ) );
}

?>
<div <?php wc_product_class( 'ht-row' ); ?>>

    <div class="ht-col-md-5 ht-col-sm-5 ht-col-xs-12">
    	<div class="ht-qwick-view-left">
            <div class="woolentor-order-bump-variations-popup-large-img">
                <?php 
                    if ( $attachment_ids ) {
                        $i = 0;
                        foreach ( $attachment_ids as $attachment_id ) {
                            $i++;

                            $html = wc_get_gallery_image_html( $attachment_id, true );

                            if( $i == 1 ){
                                echo '<div class="woolentor-order-bump-variations-gallery-img-single woolentor-variations-popup-gallery-first-image">'.apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ).'</div>';
                            }else{
                                echo '<div class="woolentor-order-bump-variations-gallery-img-single">'.apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ).'</div>';
                            }

                        }
                    }
                ?>
            </div>

            <div class="woolentor-order-bump-variations-popup-thumbnails">
                <?php
                    if ( $attachment_ids && $product->get_image_id() ) {
                        foreach ( $attachment_ids as $attachment_id ) {
                            ?>
                                <div class="woolentor-order-bump-variations-popup-thumb-single">
                                    <?php
                                      $thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'woocommerce_gallery_thumbnail' );
                                      echo '<img src=" '.$thumbnail_src[0].' " alt="'.get_the_title().'">';
                                    ?>
                                </div>
                            <?php
                        }
                    }
                ?>
            </div>

        </div>
    </div>

    <div class="ht-col-md-7 ht-col-sm-7 ht-col-xs-12">
        <div>
            <div>
                <?php do_action( 'woolentor_order_bump_before_summary' ); ?>
    			<div class="content-woolentor-order-bump-variations-popup entry-summary">
    				<?php
                        add_action( 'woolentor_order_bump_variations_popup_content', 'woocommerce_template_single_title', 5 );
                        add_action( 'woolentor_order_bump_variations_popup_content', 'woocommerce_template_single_price', 10 );
                        add_action( 'woolentor_order_bump_variations_popup_content', 'woocommerce_template_single_add_to_cart', 30 );

                        // Render Content
                        do_action( 'woolentor_order_bump_variations_popup_content' );
    				?>
    			</div><!-- .entry-summary -->
    			<?php do_action( 'woolentor_order_bump_after_summary' ); ?>
            </div>
        </div>
    </div>
</div> <!-- .ht-row -->