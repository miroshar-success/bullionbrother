<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_return_to_shop' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

echo '<div class="'.implode(' ', $areaClasses ).'">';
    $button_text = !empty( $settings['buttonText'] ) ? $settings['buttonText'] : __('Return to shop','woolentor-pro');
    if ( wc_get_page_id( 'shop' ) > 0 ) {
        $buttonlink = !empty( $settings['buttonLink'] ) ? $settings['buttonLink'] : wc_get_page_permalink( 'shop' );
        ?>
            <p class="return-to-shop" style="margin:0">
                <a class="button wc-backward" href="<?php echo esc_url( $buttonlink ); ?>">
                    <?php echo esc_html( $button_text ); ?>
                </a>
            </p>
        <?php
    }
echo '</div>';