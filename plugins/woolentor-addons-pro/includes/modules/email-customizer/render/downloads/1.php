<?php
/**
 * Downloads - Style 1
 */

$columns = apply_filters( 'woocommerce_email_downloads_columns', array(
    'download-product' => esc_html__( 'Product', 'woolentor-pro' ),
    'download-expires' => esc_html__( 'Expires', 'woolentor-pro' ),
    'download-file'    => esc_html__( 'Download', 'woolentor-pro' ),
) );

if ( is_array( $downloads ) && ! empty( $downloads ) ) {
    ?>
    <table class="downloads-table" cellspacing="0">
        <thead>
            <tr>
                <?php foreach ( $columns as $column_id => $column_name ) : ?>
                    <th scope="col"><?php echo esc_html( $column_name ); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $downloads as $download ) : ?>
                <tr>
                    <?php foreach ( $columns as $column_id => $column_name ) : ?>
                        <td class="<?php echo esc_attr( $column_id ); ?>">
                            <?php
                            if ( has_action( 'woocommerce_email_downloads_column_' . $column_id ) ) {
                                do_action( 'woocommerce_email_downloads_column_' . $column_id, $download, $plain_text );
                            } else {
                                switch ( $column_id ) {
                                    case 'download-product':
                                        ?>
                                        <a href="<?php echo esc_url( get_permalink( $download['product_id'] ) ); ?>"><?php echo wp_kses_post( $download['product_name'] ); ?></a>
                                        <?php
                                        break;
                                    case 'download-file':
                                        ?>
                                        <a href="<?php echo esc_url( $download['download_url'] ); ?>" class="woocommerce-MyAccount-downloads-file button alt"><?php echo esc_html( $download['download_name'] ); ?></a>
                                        <?php
                                        break;
                                    case 'download-expires':
                                        if ( ! empty( $download['access_expires'] ) ) {
                                            ?>
                                            <time datetime="<?php echo esc_attr( date( 'Y-m-d', strtotime( $download['access_expires'] ) ) ); ?>" title="<?php echo esc_attr( strtotime( $download['access_expires'] ) ); ?>"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ); ?></time>
                                            <?php
                                        } else {
                                            esc_html_e( 'Never', 'woolentor-pro' );
                                        }
                                        break;
                                }
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}