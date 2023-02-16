<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor_block_my_account_navigation' );
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';
!empty( $settings['menuPosition'] ) ? $areaClasses[] = 'woolentor_myaccount_menu_type_'.$settings['menuPosition'] : '';

if ( $block['is_editor'] !== 'yes' && ! is_user_logged_in() ) { return __('You need to logged in first', 'woolentor-pro'); }

echo '<div class="'.implode(' ', $areaClasses ).'">';

    $size = $settings['imageSize'];
    $image_size = Null;
    if( $size === 'custom' ){
        $image_size = [
            $settings['thumbnailsize_custom_dimension']['width'],
            $settings['thumbnailsize_custom_dimension']['height']
        ];
    }else{
        $image_size = $size;
    }
    $thumbnail = !empty( $settings['userCustomImage']['id'] ) ? wp_get_attachment_image( $settings['userCustomImage']['id'], $image_size ) : false;

    if( $settings['userInfo'] === true ){
        $current_user = wp_get_current_user();
        if ( $current_user->display_name ) {
            $name = $current_user->display_name;
        } else {
            $name = esc_html__( 'Welcome!', 'woolentor-pro' );
        }
        $name = apply_filters( 'woolentor_profile_name', $name );
        ?>
            <div class="woolentor-user-area">
                <div class="woolentor-user-image">
                    <?php
                        if( $thumbnail ){
                            echo wp_kses_post( $thumbnail );
                        }else{
                            echo get_avatar( $current_user->user_email, 125 );
                        }
                    ?>
                </div>
                <div class="woolentor-user-info">
                    <span class="woolentor-username"><?php echo esc_attr( $name ); ?></span>
                    <span class="woolentor-logout"><a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>"><?php echo esc_html__( 'Logout', 'woolentor-pro' ); ?></a></span>
                </div>
            </div>
        <?php
    }
    woocommerce_account_navigation();

    
echo '</div>';