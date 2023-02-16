<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $navs = Woolentor_Admin_Fields::instance()->field_sections();

?>

<!-- Nav Start -->
<div class="woolentor-admin-main-nav">
    <ul class="woolentor-admin-main-nav-ul">
        <?php
            foreach( $navs as $key => $nav ){
            ?>
                <li class="woolentor-admin-main-nav-li">
                    <a href="#<?php echo esc_attr( $nav['id'] ); ?>" class="woolentor-admin-main-nav-btn">
                        <svg class="left" width="8" height="8" viewBox="0 0 8 8" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 8H0L0.152774 7.97004C4.16091 7.18414 7.27643 4.01985 8 0V8Z" fill="currentColor" />
                        </svg>
                        <svg class="right" width="8" height="8" viewBox="0 0 8 8" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 8H8L7.84723 7.97004C3.83909 7.18414 0.723573 4.01985 0 0V8Z" fill="currentColor" />
                        </svg>
                        <?php 
                            if ( strstr( $nav['icon'], 'dashicons' ) ){
                                echo sprintf('<i class="dashicons %s"></i>%s', $nav['icon'], esc_html__( $nav['title'],'woolentor') );
                            }else{
                                echo sprintf('<i class="wli %s"></i>%s', $nav['icon'], esc_html__( $nav['title'],'woolentor') );
                            }
                        ?>
                    </a>
                </li>
            <?php
            }
        ?>
    </ul>
</div>
<!-- Nav End -->