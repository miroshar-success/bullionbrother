<?php
/**
 * The template for displaying product content in the quickview-product.php template
 *
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
            <div class="ht-quick-view-learg-img">
                <?php 
                    if ( $attachment_ids ) {
                        $i = 0;
                        foreach ( $attachment_ids as $attachment_id ) {
                            $i++;

                            $html = wc_get_gallery_image_html( $attachment_id, true );

                            if( $i == 1 ){
                                echo '<div class="ht-quick-view-single wl-quickview-first-image">'.apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ).'</div>';
                            }else{
                                echo '<div class="ht-quick-view-single">'.apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id ).'</div>';
                            }

                        }
                    }
                ?>
            </div>

            <div class="ht-quick-view-thumbnails">
                <?php
                    if ( $attachment_ids && $product->get_image_id() ) {
                        foreach ( $attachment_ids as $attachment_id ) {
                            ?>
                                <div class="ht-quick-thumb-single">
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
        <div class="ht-qwick-view-right">
            <div class="qwick-view-content">
                <?php do_action( 'woolentor_quickview_before_summary' ); ?>
    			<div class="content-woolentorquickview entry-summary">
    				<?php
                        add_action( 'woolentor_quickview_content', 'woocommerce_template_single_title', 5 );
                        add_action( 'woolentor_quickview_content', 'woocommerce_template_single_rating', 10 );
                        add_action( 'woolentor_quickview_content', 'woocommerce_template_single_price', 10 );
                        add_action( 'woolentor_quickview_content', 'woocommerce_template_single_excerpt', 20 );
                        add_action( 'woolentor_quickview_content', 'woocommerce_template_single_add_to_cart', 30 );
                        add_action( 'woolentor_quickview_content', 'woocommerce_template_single_meta', 40 );
                        add_action( 'woolentor_quickview_content', 'woocommerce_template_single_sharing', 50 );

                        // Render Content
                        do_action( 'woolentor_quickview_content' );
    				?>
    			</div><!-- .summary -->
    			<?php do_action( 'woolentor_quickview_after_summary' ); ?>
            </div>
        </div>
    </div>

</div>	