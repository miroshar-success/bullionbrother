<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    $product = wc_get_product( get_the_ID() );
    $settings = $args;
    $columns = $settings['columns']['desktop'];
    $tabuniqid = $settings['blockUniqId'];

    // Calculate Column
    $collumval = ( $settings['layoutStyle'] == 'slider' ) ? 'ht-product mb-30 product woolentor-slider-item' : 'ht-product mb-30 product';
    
    // Action Button Style
    if( $settings['actionButtonStyle'] == 2 ){
        $collumval .= ' ht-product-action-style-2';
    }elseif( $settings['actionButtonStyle'] == 3 ){
        $collumval .= ' ht-product-action-style-2 ht-product-action-round';
    }else{
        $collumval = $collumval;
    }
    // Position Action Button
    if( $settings['actionButtonPosition'] == 'right' ){
        $collumval .= ' ht-product-action-right';
    }elseif( $settings['actionButtonPosition'] == 'bottom' ){
        $collumval .= ' ht-product-action-bottom';
    }elseif( $settings['actionButtonPosition'] == 'middle' ){
        $collumval .= ' ht-product-action-middle';
    }elseif( $settings['actionButtonPosition'] == 'contentbottom' ){
        $collumval .= ' ht-product-action-bottom-content';
    }else{
        $collumval = $collumval;
    }

    // Show Action
    if( $settings['actionButtonShowOn'] == 'hover' ){
        $collumval .= ' ht-product-action-on-hover';
    }

    // Content Style
    if( $settings['contentStyle'] == 2 ){
        $collumval .= ' ht-product-category-right-bottom';
    }elseif( $settings['contentStyle'] == 3 ){
        $collumval .= ' ht-product-ratting-top-right';
    }elseif( $settings['contentStyle'] == 4 ){
        $collumval .= ' ht-product-content-allcenter';
    }else{
        $collumval = $collumval;
    }

    // Position countdown
    if( $settings['countdownPosition'] == 'left' ){
        $collumval .= ' ht-product-countdown-left';
    }elseif( $settings['countdownPosition'] == 'right' ){
        $collumval .= ' ht-product-countdown-right';
    }elseif( $settings['countdownPosition'] == 'middle' ){
        $collumval .= ' ht-product-countdown-middle';
    }elseif( $settings['countdownPosition'] == 'bottom' ){
        $collumval .= ' ht-product-countdown-bottom';
    }elseif( $settings['countdownPosition'] == 'contentbottom' ){
        $collumval .= ' ht-product-countdown-content-bottom';
    }else{
        $collumval = $collumval;
    }

    // Countdown Gutter 
    if( $settings['showCountdownGutter'] != true ){
       $collumval .= ' ht-product-countdown-fill'; 
    }

    // Countdown Custom Label
    if( $settings['showCountdown'] == true ){
        $data_customlavel = [];
        $data_customlavel['daytxt'] = $settings['showCountdownCustomLabel'] == true && ! empty( $settings['CountdownCustomLabel']['days'] ) ? $settings['CountdownCustomLabel']['days'] : 'Days';
        $data_customlavel['hourtxt'] = $settings['showCountdownCustomLabel'] == true && ! empty( $settings['CountdownCustomLabel']['hour'] ) ? $settings['CountdownCustomLabel']['hour'] : 'Hours';
        $data_customlavel['minutestxt'] = $settings['showCountdownCustomLabel'] == true && ! empty( $settings['CountdownCustomLabel']['minutes'] ) ? $settings['CountdownCustomLabel']['minutes'] : 'Min';
        $data_customlavel['secondstxt'] = $settings['showCountdownCustomLabel'] == true && ! empty( $settings['CountdownCustomLabel']['seconds'] ) ? $settings['CountdownCustomLabel']['seconds'] : 'Sec';
    }

    $title_html_tag = woolentor_validate_html_tag( $settings['titleHtmlTag'] );

    // Sale Schedule
    $offer_start_date_timestamp = get_post_meta( get_the_ID(), '_sale_price_dates_from', true );
    $offer_start_date = $offer_start_date_timestamp ? date_i18n( 'Y/m/d', $offer_start_date_timestamp ) : '';
    $offer_end_date_timestamp = get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
    $offer_end_date = $offer_end_date_timestamp ? date_i18n( 'Y/m/d', $offer_end_date_timestamp ) : '';

    // Gallery Image
    $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
    if ( has_post_thumbnail() ){
        array_unshift( $gallery_images_ids, $product->get_image_id() );
    }

    ?>
        <!--Product Start-->
        <div class="<?php echo $collumval; ?>">
            <div class="ht-product-inner">

                <div class="ht-product-image-wrap">
                    <?php
                        if( class_exists('WooCommerce') ){
                            woolentor_custom_product_badge(); 
                            woolentor_sale_flash();
                        }
                    ?>
                    <div class="ht-product-image">
                        <?php  if( $settings['thumbnailsStyle'] == 2 && $gallery_images_ids ): ?>
                            <div class="ht-product-image-slider ht-product-image-thumbnaisl-<?php echo $tabuniqid; ?>" data-slick='{"rtl":<?php if( is_rtl() ){ echo 'true'; }else{ echo 'false'; } ?> }'>
                                <?php
                                    foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                        echo '<a href="'.esc_url( get_the_permalink() ).'" class="item">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_thumbnail' ).'</a>';
                                    }
                                ?>
                            </div>

                        <?php elseif( $settings['thumbnailsStyle'] == 3 && $gallery_images_ids ) : $tabactive = ''; ?>
                            <div class="ht-product-cus-tab">
                                <?php
                                    $i = 0;
                                    foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                        $i++;
                                        if( $i == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                        echo '<div class="ht-product-cus-tab-pane '.$tabactive.'" id="image-'.$i.get_the_ID().'"><a href="#">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_thumbnail' ).'</a></div>';
                                    }
                                ?>
                            </div>
                            <ul class="ht-product-cus-tab-links">
                                <?php
                                    $j = 0;
                                    foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                        $j++;
                                        if( $j == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                        echo '<li><a href="#image-'.$j.get_the_ID().'" class="'.$tabactive.'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</a></li>';
                                    }
                                ?>
                            </ul>

                        <?php else: ?>
                            <a href="<?php the_permalink();?>"> 
                                <?php woocommerce_template_loop_product_thumbnail(); ?> 
                            </a>
                        <?php endif; ?>

                    </div>

                    <?php if( $settings['showCountdown'] == true && $settings['countdownPosition'] != 'contentbottom' && $offer_end_date != '' ):

                        if( $offer_start_date_timestamp && $offer_end_date_timestamp && current_time( 'timestamp' ) > $offer_start_date_timestamp && current_time( 'timestamp' ) < $offer_end_date_timestamp
                        ): 
                    ?>
                        <div class="ht-product-countdown-wrap">
                            <div class="ht-product-countdown" data-countdown="<?php echo esc_attr( $offer_end_date ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                        </div>
                    <?php endif; endif; ?>

                    <?php if( $settings['showActionButton'] == true ){ if( $settings['actionButtonPosition'] != 'contentbottom' ): ?>
                        <div class="ht-product-action">
                            <ul class="woolentor-action-btn-area">
                                <li>
                                    <a href="#" class="woolentorquickview" data-quick-id="<?php the_ID();?>" >
                                        <i class="sli sli-magnifier"></i>
                                        <span class="ht-product-action-tooltip"><?php esc_html_e('Quick View','woolentor'); ?></span>
                                    </a>
                                </li>
                                <?php
                                    if( true === woolentor_has_wishlist_plugin() ){
                                        echo '<li>'.woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes').'</li>';
                                    }
                                ?>
                                <?php
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
                                <li class="woolentor-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                            </ul>
                        </div>
                    <?php endif; }?>

                </div>

                <div class="ht-product-content">
                    <div class="ht-product-content-inner">
                        <?php if ( $settings['hideCategory'] != true ) : ?>
                            <div class="ht-product-categories <?php if ( $settings['hideCategoryBeforeBorder'] == true ) {echo 'hide-category-before';} ?>"><?php woolentor_get_product_category_list(); ?></div>
                        <?php endif; ?>
                        <?php if ( $settings['hideTitle'] != true ) { echo sprintf( "<%s class='ht-product-title'><a href='%s'>%s</a></%s>", $title_html_tag, get_the_permalink(), get_the_title(), $title_html_tag ); } ?>
                        <?php if ( $settings['hidePrice'] != true ) : ?>
                            <div class="ht-product-price"><?php woocommerce_template_loop_price();?></div>
                        <?php endif; ?>
                        <?php if ( $settings['hideRating'] != true ) : ?>
                            <div class="ht-product-ratting-wrap"><?php echo woolentor_wc_get_rating_html('yes'); ?></div>
                        <?php endif; ?>

                        <?php if( $settings['showActionButton'] == true ){ if( $settings['actionButtonPosition'] == 'contentbottom' ): ?>
                            <div class="ht-product-action">
                                <ul class="woolentor-action-btn-area">
                                    <li>
                                        <a href="#" class="woolentorquickview" data-quick-id="<?php the_ID();?>" >
                                            <i class="sli sli-magnifier"></i>
                                            <span class="ht-product-action-tooltip"><?php esc_html_e('Quick View','woolentor'); ?></span>
                                        </a>
                                    </li>
                                    <?php
                                        if( true === woolentor_has_wishlist_plugin() ){
                                            echo '<li>'.woolentor_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes').'</li>';
                                        }
                                    ?>
                                    <?php
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
                                    <li class="woolentor-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                                </ul>
                            </div>
                        <?php endif; } ?>
                    </div>
                    <?php 
                        if( $settings['showCountdown'] == true && $settings['countdownPosition'] == 'contentbottom' && $offer_end_date != ''  ):

                            if( $offer_start_date_timestamp && $offer_end_date_timestamp && current_time( 'timestamp' ) > $offer_start_date_timestamp && current_time( 'timestamp' ) < $offer_end_date_timestamp
                            ):
                    ?>
                        <div class="ht-product-countdown-wrap">
                            <div class="ht-product-countdown" data-countdown="<?php echo esc_attr( $offer_end_date ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                        </div>
                    <?php endif; endif; ?>
                </div>

            </div>
        </div>
        <!--Product End-->
    <?php