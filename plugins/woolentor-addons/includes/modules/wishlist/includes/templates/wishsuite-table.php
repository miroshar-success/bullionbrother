<div class="wishsuite-table-content">
    <table class="wishsuite_table">
        <thead>
            <?php
                $cell_count = 1;
                if( !empty( $fields ) ){
                    $cell_count = count( $fields );
                    echo '<tr>';
                        foreach ( $fields as $field_id => $field ){
                            $name = $wishsuite->field_name( $field_id );
                            if( array_key_exists( $field_id, $heading_txt ) && !empty( $heading_txt[$field_id] ) ){
                                $name = $wishsuite->field_name( $heading_txt[$field_id], true );
                            }
                            echo '<th>'.$name.'</th>';
                        }
                    echo '</tr>';
                }
            ?>
        </thead>
        <tbody>
            <?php 
                if( !empty( $products ) ):
                    foreach ( $products as $product_id => $product ):
            ?>
                    <tr>
                        <?php foreach ( $fields as $field_id => $field ) : 
                            $data_label = $wishsuite->field_name( $field_id );
                            if( array_key_exists( $field_id, $heading_txt ) && !empty( $heading_txt[$field_id] ) ){
                                $data_label = $wishsuite->field_name( $heading_txt[$field_id], true );
                            }
                        ?>
                            <td class="wishsuite-product-<?php echo esc_attr( $field_id ); ?>" data-label="<?php echo esc_attr( $data_label ); ?>">
                                <?php $wishsuite->display_field( $field_id, $product ); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>

            <?php endforeach; ?>
                <tr class="wishsuite-empty-tr" style="display: none;">
                    <td class="wishsuite-emplty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
                        <?php if( !empty( $empty_text ) ){ echo wp_kses_post( $empty_text ); } ?>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="wishsuite-emplty-text" colspan="<?php echo esc_attr( $cell_count ); ?>">
                        <?php if( !empty( $empty_text ) ){ echo wp_kses_post( $empty_text ); } ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php $wishsuite->social_share(); ?>

    <div class="wishsuite-table-content-loader"></div>
</div>