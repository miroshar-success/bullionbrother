<div class="htcompare-table">
    <?php
        do_action( 'ever_compare_before_table' );

        if ( ! empty( $products ) ) {
            array_unshift( $products, array() );
            foreach ( $fields as $field_id => $field ) {
                if ( ! $evercompare->is_products_have_field( $field_id, $products ) ) {
                    continue;
                }

                // Generate Filed name
                $name = $evercompare->field_name( $field );
                if( array_key_exists( $field_id, $heading_txt ) && !empty( $heading_txt[$field_id] ) ){
                    $name = $evercompare->field_name( $heading_txt[$field_id], true );
                }

                ?>
                    <div class="htcompare-row compare-data-<?php echo esc_attr( $field_id ); ?>">
                        <?php foreach ( $products as $product_id => $product ) : ?>
                            <?php if ( ! empty( $product ) ) : ?>
                                <div class="htcompare-col htcolumn-value" data-title="<?php echo esc_attr( $name ); ?>">
                                    <?php $evercompare->compare_display_field( $field_id, $product ); ?>
                                </div>
                            <?php else: ?>
                                <div class="htcompare-col htcolumn-field-name">
                                    <?php echo esc_html( $name ); ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach ?>
                    </div>
                <?php
            }
            echo '<div class="htcompare-table-loader"></div>'; 
        } else {
            if ( $empty_compare_text ){
                echo '<div class="htcompare-empty-page-text">'.wp_kses_post( $empty_compare_text ).'</div>';
            }

            if( $return_shop_button ){
                echo '<div class="htcompare-return-to-shop"><a href="'.esc_url( wc_get_page_permalink( 'shop' ) ).'" class="button">'.esc_html__( $return_shop_button, 'ever-compare' ).'</a></div>';
            }
        }

        do_action( 'ever_compare_after_table' );
    ?>
</div>