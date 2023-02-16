<?php
namespace Woolentor\Modules\Swatchly;
use  Woolentor\Modules\Swatchly\Helper as Helper;

/**
 * Frontend class.
 */
class Frontend {
    public $sp_enable_swatches;
    public $pl_enable_swatches;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->sp_enable_swatches = Helper::get_option('sp_enable_swatches');
        $this->pl_enable_swatches = Helper::get_option('pl_enable_swatches');

        // frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );

        // generate blank notice wrapper for ajax add to cart
        if( $this->pl_enable_swatches ){
            add_action('wp_footer', array( $this, 'popup_notice_blank_div') );
        }

        // add body class
        add_filter( 'body_class', array( $this, 'custom_body_class') );
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Thickbox.
        add_thickbox();

        wp_register_style( 'swatchly-frontend', MODULE_ASSETS . '/css/frontend.css', array(), WOOLENTOR_VERSION );
        wp_register_script( 'swatchly-add-to-cart-variation', MODULE_ASSETS . '/js/add-to-cart-variation.js', array('jquery', 'wp-util', 'jquery-blockui' ), WOOLENTOR_VERSION, true );
        wp_register_script( 'swatchly-frontend', MODULE_ASSETS . '/js/frontend.js', array('jquery'), WOOLENTOR_VERSION );

        $enable_swatches = $this->pl_enable_swatches;

        if( is_product() ){
            $enable_swatches = $this->sp_enable_swatches;
        }

        if( $enable_swatches ){
            wp_enqueue_style( 'swatchly-frontend' );
            wp_enqueue_script( 'swatchly-frontend' );

            $swatch_width_height = Helper::get_option('swatch_width_height');
            $swatch_width        = !empty($swatch_width_height['width']) ? $swatch_width_height['width'] : '';
            $swatch_height       = !empty($swatch_width_height['height']) ? $swatch_width_height['height'] : '';
            $swatch_unit         = !empty($swatch_width_height['unit']) ? $swatch_width_height['unit'] : 'px';

            $tooltip_width_height = Helper::get_option('tooltip_width_height');
            $tooltip_width        = !empty($tooltip_width_height['width']) ? $tooltip_width_height['width'] : '';
            $tooltip_unit         = !empty($tooltip_width_height['unit']) ? $tooltip_width_height['unit'] : 'px';

            $custom_css = array();
            $custom_css[] = Helper::add_inline_css(
                array(
                    'id' => '',
                    'properties' => 'min-width',
                    'unit'     => $swatch_unit,
                    'selectors' => array(
                        '.swatchly-swatch',
                    ),
                    'value' => $swatch_width
                )
            );

            $custom_css[] = Helper::add_inline_css(
                array(
                    'id' => '',
                    'properties' => 'min-height',
                    'unit'     => $swatch_unit, 
                    'selectors' => array(
                        '.swatchly-swatch',
                    ),
                    'value' => $swatch_height
                )
            );

            $custom_css[] = Helper::add_inline_css(
                array(
                    'id' => '',
                    'properties' => 'max-width',
                    'unit'     => $tooltip_unit, 
                    'selectors' => array(
                        '.swatchly-swatch .swatchly-tooltip',
                    ),
                    'value' => $tooltip_width
                )
            );

            wp_add_inline_style( 'swatchly-frontend', implode('', $custom_css) );
        }

        $auto_convert_dropdowns_to_label = Helper::get_option('auto_convert_dropdowns_to_label', 1);
        $tooltip                         = Helper::get_option('tooltip', 1);
        $deselect_on_click               = Helper::get_option('deselect_on_click', 0);
        $show_selected_attribute_name    = Helper::get_option('show_selected_attribute_name', 1);
        $variation_label_separator       = Helper::get_option('variation_label_separator', ' : ');
        $product_thumbnail_selector      = Helper::get_option('pl_product_thumbnail_selector');
        $hide_wc_forward_button          = Helper::get_option('pl_hide_wc_forward_button', 1);
        $enable_cart_popup_notice        = Helper::get_option('pl_enable_cart_popup_notice', 1);
        $enable_catalog_mode             = Helper::get_option('pl_enable_catalog_mode', 0);

        $localize_vars = array(
            'is_product'                      => is_product(),
            'enable_swatches'                 => $enable_swatches,
            'auto_convert_dropdowns_to_label' => $auto_convert_dropdowns_to_label,
            'tooltip'                         => $tooltip,
            'deselect_on_click'               => $deselect_on_click,
            'show_selected_attribute_name'    => $show_selected_attribute_name,
            'variation_label_separator'       => $variation_label_separator,
            'product_thumbnail_selector'      => $product_thumbnail_selector,
            'hide_wc_forward_button'          => $hide_wc_forward_button,
            'enable_cart_popup_notice'        => $enable_cart_popup_notice,
            'enable_catalog_mode'             => $enable_catalog_mode
        );
        wp_localize_script( 'swatchly-frontend', 'swatchly_params', $localize_vars );

        // Localize for WC Variation js
        wc_get_template( 'single-product/add-to-cart/variation.php' );

        $params = array(
            'wc_ajax_url'                      => \WC_AJAX::get_endpoint( '%%endpoint%%' ),
            'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woolentor' ),
            'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'woolentor' ),
            'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woolentor' ),
        );
        wp_localize_script( 'swatchly-add-to-cart-variation', 'swatchly_add_to_cart_variation_params', $params );
    }

     /**
      * Add custom body class
      */   
    function custom_body_class( $classes ) {
        $show_swatches_label = Helper::get_option('pl_show_swatches_label');
        $show_clear_link     = Helper::get_option('pl_show_clear_link');
        if(!is_product() && $show_swatches_label){
            $classes[] = 'swatchly_pl_show_swatches_label_'. $show_swatches_label;
        }

        if(!is_product() && $show_clear_link){
            $classes[] = 'swatchly_pl_show_clear_link_'. $show_clear_link;
        }

        return $classes;
    }

    /**
     * Ajax add to cart notice div
     */
    public function popup_notice_blank_div(){
        ?>
        <div id="swatchly_notice_popup" style="display:none;"></div>
        <?php
    }
}