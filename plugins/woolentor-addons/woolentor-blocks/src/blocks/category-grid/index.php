<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    $uniqClass   = 'woolentorblock-'.$settings['blockUniqId'];
    $areaClasses = array( $uniqClass, );
    $rowClasses  = $settings['sliderOn'] === true ? array( 'woolentor-grid-slider' ) : array( 'woolentor-grid' );

    !empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';
    !empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-grid-columns-'.$settings['columns']['desktop'] : 'woolentor-grid-columns-4';
    !empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-grid-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-grid-columns-laptop-3';
    !empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-grid-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-grid-columns-tablet-2';
    !empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-grid-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-grid-columns-mobile-1';

    !empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';

    $settings['noGutter'] === true ? $rowClasses[] = 'woolentor-no-gutters' : '';

    $display_type = $settings['displayType'];
    $order = ! empty( $settings['order'] ) ? $settings['order'] : '';

    $layout  = $settings['style'];
    $column = !empty( $settings['columns']['desktop'] ) ? $settings['columns']['desktop'] : '4';

    $collumval = 'woolentor-grid-column';

    $catargs = array(
        'orderby'    => 'name',
        'order'      => $order,
        'hide_empty' => true,
    );

    if( $display_type == 'singleCat' ){
        $product_category = $settings['productCategory'];
        $catargs['slug'] = $product_category;
    }
    elseif( $display_type == 'multipleCat' ){
        $product_categories = !empty( $settings['productCategories'] ) ? $settings['productCategories'] : array();
        if( is_array( $product_categories ) && count( $product_categories ) > 0 ){
            $catargs['slug'] = $product_categories;
        }
    }else{
        $catargs['slug'] = '';
    }

    if( $display_type == 'allCat' ){
        $catargs['number'] = $settings['displayLimit'];
    }else{
        $catargs['number'] = 20;
    }

    $prod_categories = woolentorBlocks_taxnomy_data( $catargs['slug'], $catargs['number'], $catargs['order'] );

    $image_size = $settings['imageSize'] ? $settings['imageSize'] : 'full';

    // Slider Options
    $slider_settings = array();
    $sliderOptions = $direction = '';
    if( $settings['sliderOn'] === true ){

        $rowClasses[] = 'product-slider';

        $direction = $settings['slIsrtl'] ? 'dir=rtl' : 'dir=ltr';
        $slider_settings = [
            'arrows' => (true === $settings['slarrows']),
            'dots' => (true === $settings['sldots']),
            'autoplay' => (true === $settings['slautolay']),
            'autoplay_speed' => absint($settings['slautoplaySpeed']),
            'animation_speed' => absint($settings['slanimationSpeed']),
            'pause_on_hover' => ('yes' === $settings['slpauseOnHover']),
            'rtl' => ( true === $settings['slIsrtl'] ),
        ];

        $slider_responsive_settings = [
            'product_items' => $settings['slitems'],
            'scroll_columns' => $settings['slscrollItem'],
            'tablet_width' => $settings['sltabletWidth'],
            'tablet_display_columns' => $settings['sltabletDisplayColumns'],
            'tablet_scroll_columns' => $settings['sltabletScrollColumns'],
            'mobile_width' => $settings['slMobileWidth'],
            'mobile_display_columns' => $settings['slMobileDisplayColumns'],
            'mobile_scroll_columns' => $settings['slMobileScrollColumns'],

        ];
        $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );
        $sliderOptions = 'data-settings='.wp_json_encode( $slider_settings );
    }else{
        $sliderOptions = '';
        $direction = '';
    }

    $counter = $bgc = 0;

?>
<div class="<?php echo implode(' ', $areaClasses ); ?>">
    <div class="<?php echo implode(' ', $rowClasses ); ?>" <?php echo $sliderOptions; ?> <?php echo esc_attr( $direction ); ?>>
        <?php
            $topSpace = '';
            foreach ( $prod_categories as $key => $prod_cat ):
                $bgc++;
                $counter++;

                $cat_thumb_id = $prod_cat['thumbnail_id'];
                $thumbnails = $cat_thumb_id ? wp_get_attachment_image( $cat_thumb_id, $image_size ) : '';

                ?>
                <div class="<?php echo esc_attr( $collumval ); echo esc_attr( $topSpace ); ?>">

                    <?php if( '1' === $layout ): ?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a class="ht-category-border" href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <div class="ht-category-content">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'woolentor' ); ?></a></h3>
                                <?php 
                                    if( $settings['showCount'] === true ){
                                        echo '<span>'.esc_html__( $prod_cat['count'], 'woolentor' ).'</span>';
                                    }
                                ?>
                            </div>
                        </div>

                    <?php elseif( '2' === $layout ):?>
                        <div class="ht-category-wrap-2">
                            <div class="ht-category-content-2">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'woolentor' ); ?></a></h3>
                            </div>
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image-2">
                                <a href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>

                    <?php elseif( '3' === $layout ):?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ): ?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a class="ht-category-border-2" href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php else: ?>
                                <div class="ht-category-image ht-category-image-zoom">
                                    <a class="ht-category-border-2" href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                        <img src="<?php echo esc_url( $prod_cat['image']['placeholderImg'] ) ?>">
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="ht-category-content-3 ht-category-content-3-bg<?php echo $bgc; ?>">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'woolentor' ); ?></a></h3>
                            </div>
                        </div>

                    <?php elseif( '4' === $layout ):?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image ht-category-image-zoom">
                                <a href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="ht-category-content-4">
                                <h3>
                                    <a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'woolentor' ); ?></a>
                                    <?php 
                                        if( $settings['showCount'] === true ){
                                            echo '<span>('.esc_html__( $prod_cat['count'], 'woolentor' ).')</span>';
                                        }
                                    ?>
                                </h3>
                            </div>
                        </div>

                    <?php else:?>
                        <div class="ht-category-wrap">
                            <?php if( !empty( $thumbnails ) ):?>
                            <div class="ht-category-image-3 ht-category-image-zoom">
                                <a href="<?php echo esc_url( $prod_cat['link'] ); ?>">
                                    <?php echo $thumbnails; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="ht-category-content-5">
                                <h3><a href="<?php echo esc_url( $prod_cat['link'] ); ?>"><?php echo esc_html__( $prod_cat['name'], 'woolentor' ); ?></a></h3>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
                <?php
                if( $bgc == 4 ){ $bgc = 0; }
                if( $counter >= $column ){
                    $topSpace = ' woolentor_margin_top';
                }
            endforeach;
        ?>
    </div>
</div>