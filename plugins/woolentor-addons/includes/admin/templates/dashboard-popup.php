<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="woolentor-admin-pro-popup" class="woolentor-admin-popup">
    <div class="woolentor-admin-popup-inner">
        <button class="woolentor-admin-popup-close">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.08366 1.73916L8.26116 0.916656L5.00033 4.17749L1.73949 0.916656L0.916992 1.73916L4.17783 4.99999L0.916992 8.26082L1.73949 9.08332L5.00033 5.82249L8.26116 9.08332L9.08366 8.26082L5.82283 4.99999L9.08366 1.73916Z" fill="currentColor" />
            </svg>
        </button>
        <div class="woolentor-admin-popup-icon"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>includes/admin/assets/images/icons/pro-badge.png" alt="pro"></div>
        <h2 class="woolentor-admin-popup-title"><?php echo esc_html__('BUY PRO','woolentor'); ?></h2>
        <p class="woolentor-admin-popup-text"><?php echo esc_html__('Our free version is great, but it doesn\'t have all our advanced features. The best way to unlock all of the features in our plugin is by purchasing the pro version.','woolentor'); ?></p>
        <a href="https://woolentor.com/?utm_source=admin&utm_medium=notice&utm_campaign=free" class="woolentor-admin-btn woolentor-admin-btn-primary" target="_blank"><?php echo esc_html__('Buy Now','woolentor'); ?></a>
    </div>
</div>