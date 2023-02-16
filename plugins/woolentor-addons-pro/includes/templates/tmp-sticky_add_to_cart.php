<div class="woolentor-add-to-cart-sticky">
    <div class="ht-container">

        <?php
            $woolentor_sticky_cart_css = '';

            $btn_color    = woolentor_generate_css_pro('sps_add_to_cart_color','woolentor_others_tabs','color','',' !important');
            $btn_bg_color = woolentor_generate_css_pro('sps_add_to_cart_bg_color','woolentor_others_tabs','background-color','',' !important');

            $btn_padding        = woolentor_dimensions_pro( 'sps_add_to_cart_padding','woolentor_others_tabs','padding',' !important' );
            $btn_border_radius  = woolentor_dimensions_pro( 'sps_add_to_cart_border_radius','woolentor_others_tabs','border-radius',' !important' );

            // Hover
            $btn_hover_color    = woolentor_generate_css_pro('sps_add_to_cart_hover_color','woolentor_others_tabs','color','',' !important');
            $btn_hover_bg_color = woolentor_generate_css_pro('sps_add_to_cart_bg_hover_color','woolentor_others_tabs','background-color','',' !important');

            $woolentor_sticky_cart_css .= "
                .woolentor-sticky-btn-area .single_add_to_cart_button,.woolentor-sticky-btn-area .woolentor-sticky-add-to-cart{
                    {$btn_color}
                    {$btn_bg_color}
                    {$btn_padding}
                    {$btn_border_radius}
                }
                .woolentor-sticky-btn-area .single_add_to_cart_button:hover,.woolentor-sticky-btn-area .woolentor-sticky-add-to-cart:hover{
                    {$btn_hover_color}
                    {$btn_hover_bg_color}
                }
            ";
        ?>

        <?php if( !empty( $woolentor_sticky_cart_css ) ): ?>
            <style type="text/css">
                <?php echo $woolentor_sticky_cart_css; ?>
            </style>
        <?php endif; ?>

        <div class="ht-row">
            <div class="ht-col-lg-6 ht-col-md-6 ht-col-sm-6 ht-col-xs-12">
                <div class="woolentor-addtocart-content">
                    <div class="woolentor-sticky-thumbnail">
                        <?php echo woocommerce_get_product_thumbnail(); ?>  
                    </div>
                    <div class="woolentor-sticky-product-info">
                        <h4 class="title"><?php the_title(); ?></h4>
                        <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>     
                    </div>
                </div>
            </div>
            <div class="ht-col-lg-6 ht-col-md-6 ht-col-sm-6 ht-col-xs-12">
                <div class="woolentor-sticky-btn-area">
                    <?php 
                        if ( $product->is_type( 'simple' ) ){ 
                            woocommerce_simple_add_to_cart();
                        }else{
                            echo '<a href="'.esc_url( $product->add_to_cart_url() ).'" class="woolentor-sticky-add-to-cart button alt">'.( true == $product->is_type( 'variable' ) ? esc_html__( 'Select Options', 'woolentor-pro' ) : $product->single_add_to_cart_text() ).'</a>';
                        }
                        if( true === woolentor_has_wishlist_plugin() ){
                            echo '<div class="woolentor-sticky-wishlist">'.woolentor_add_to_wishlist_button().'</div>';
                        }
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>