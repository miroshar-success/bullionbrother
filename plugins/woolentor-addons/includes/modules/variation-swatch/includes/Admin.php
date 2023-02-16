<?php
namespace Woolentor\Modules\Swatchly;

/**
 * Admin class.
 */
class Admin {
    /**
     * Constructor.
     */
    public function __construct() {
        new Admin\Woo_Config();
        new Admin\Attribute_Taxonomy_Metabox();
        new Admin\Product_Metabox();

        // Admin assets hook into action.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets( $hook_suffix ) {
        $current_screen = get_current_screen();

        if (
            $current_screen->post_type == 'product' ||
            $hook_suffix == 'shoplentor_page_woolentor'
        ) {
            if( $current_screen->base == 'post' ){
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'wp-color-picker-alpha',  MODULE_ASSETS . '/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), WOOLENTOR_VERSION, true );
            }

            // For conditional fields
            wp_enqueue_script('woolentor-jquery-interdependencies');
            wp_enqueue_script('woolentor-condition');
        
            wp_enqueue_style( 'swatchly-admin', MODULE_ASSETS . '/css/admin.css', array(), WOOLENTOR_VERSION );
            wp_enqueue_script( 'swatchly-admin', MODULE_ASSETS . '/js/admin.js', array('jquery'), WOOLENTOR_VERSION, true );

            $localize_vars = array();
            if(get_post_type() == 'product'){
                $localize_vars['product_id'] = get_the_id();
            } else {
                $localize_vars['product_id'] = '';
            }

            $localize_vars['nonce']                   = wp_create_nonce('swatchly_product_metabox_save_nonce');
            $localize_vars['i18n']['saving']          = esc_html__('Saving...', 'woolentor');
            $localize_vars['i18n']['choose_an_image'] = esc_html__('Choose an image', 'woolentor');
            $localize_vars['i18n']['use_image']       = esc_html__('Use image', 'woolentor');
            $localize_vars['pl_override_global']      = false;
            $localize_vars['sp_override_global']      = false;
            wp_localize_script( 'swatchly-admin', 'swatchly_params', $localize_vars );
        }
    }
}