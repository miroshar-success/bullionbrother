<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_logout' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

if ( $block['is_editor'] !== 'yes' && ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }

echo '<div class="'.implode(' ', $areaClasses ).'">';
    $label = !empty( $settings['buttonLabel'] ) ? $settings['buttonLabel'] : esc_html( 'Logout','woolentor-pro' );
    ?>
        <div class="woolentor-customer-logout">
            <a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
        </div>
    <?php
    
echo '</div>';