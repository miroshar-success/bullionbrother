<?php
/**
 * Admin.
 */

namespace WLEA;

/**
 * Class.
 */
class Admin {

	/**
     * Constructor.
     */
    public function __construct() {
        new Admin\Post_Types();
        new Admin\Meta_Boxes();
        new Admin\Editor();
        new Admin\Menus();
        new Admin\Duplicator();
        new Admin\Notices();

        add_action( 'admin_footer', array( $this, 'footer' ) );
    }

    /**
     * Footer.
     */
    public function footer() {
        $screen = get_current_screen();
        $base = isset( $screen->base ) ? $screen->base : '';
        $post_type = isset( $screen->post_type ) ? $screen->post_type : '';

        if ( ( 'post' === $base ) && ( 'wlea-email' === $post_type ) ) {
            $placeholders = array(
                'common' => array(
                    'head' => esc_html__( 'Common', 'woolentor-pro' ),
                    'data' => wlea_get_placeholders_list( 'common' ),
                ),
                'order' => array(
                    'head' => esc_html__( 'Order', 'woolentor-pro' ),
                    'data' => wlea_get_placeholders_list( 'order' ),
                ),
                'customer' => array(
                    'head' => esc_html__( 'Customer', 'woolentor-pro' ),
                    'data' => wlea_get_placeholders_list( 'customer' ),
                ),
            );
            ?>
            <div id="wlea-admin-popup" class="wlea-admin-popup">
                <div id="wlea-admin-popup-wrapper" class="wlea-admin-popup-wrapper">
                    <div id="wlea-admin-popup-content" class="wlea-admin-popup-content">
                        <h3 class="wlea-placeholders-title"><?php esc_html_e( 'WooLentor Placeholders', 'woolentor-pro' ); ?></h3>
                        <div class="wlea-placeholders-content">
                            <?php
                            foreach ( $placeholders as $placeholder ) {
                                $head = ( isset( $placeholder['head'] ) ? wlea_cast( $placeholder['head'], 'text' ) : '' );
                                $data = ( isset( $placeholder['data'] ) ? wlea_cast( $placeholder['data'], 'array' ) : '' );
                                ?>
                                <div class="wlea-placeholders-list">
                                    <div class="wlea-placeholders-head"><?php echo esc_html( $head ); ?></div>
                                    <?php
                                    foreach ( $data as $item ) {
                                        ?>
                                        <div class="wlea-placeholder-item">
                                            <div class="wlea-placeholder-content"><?php echo esc_html( $item ); ?></div>
                                            <div class="wlea-placeholder-action">
                                                <div class="wlea-placeholder-insert">
                                                    <span class="wlea-icon wlea-icon-insert"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}