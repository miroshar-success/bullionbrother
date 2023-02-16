<?php
namespace EverCompare;
/**
 * Assets handlers class
 */
class Assets {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [$suffix]
     * @var [string]
     */
    public $suffix = '';

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Class constructor
     */
    private function __construct() {
        $this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {

        $script_list = [
            'evercompare-admin' => [
                'src'     => EVERCOMPARE_ASSETS . '/js/admin.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'evercompare-frontend' => [
                'src'     => EVERCOMPARE_ASSETS . '/js/frontend'.$this->suffix.'.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
        ];

        return $script_list;

    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {

        $style_list = [
            'evercompare-admin' => [
                'src'     => EVERCOMPARE_ASSETS . '/css/admin.css',
                'version' => WOOLENTOR_VERSION,
            ],
            'evercompare-frontend' => [
                'src'     => EVERCOMPARE_ASSETS . '/css/frontend'.$this->suffix.'.css',
                'version' => WOOLENTOR_VERSION,
            ],
        ];

        return $style_list;

    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        // Inline CSS
        wp_add_inline_style( 'evercompare-frontend', $this->inline_style() );

        // Frontend Localize data
        $option_data = array();
        $localize_data = array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'popup'         => ( woolentor_get_option( 'open_popup', 'ever_compare_settings_tabs', 'on' ) === 'on' ) ? 'yes' : 'no',
            'option_data'   => $option_data,
        );

        // Admin Localize data
        $setting_page = 0;
        if( isset( $_GET['page'] ) && $_GET['page'] == 'evercompare' ){
            $setting_page = 1;
        }
        $admin_option_data = array(
            'shop_btn_position'     => woolentor_get_option( 'shop_btn_position','ever_compare_settings_tabs','after_cart_btn' ),
            'product_btn_position'  => woolentor_get_option( 'product_btn_position','ever_compare_settings_tabs','after_cart_btn' ),
            'button_icon_type'      => woolentor_get_option( 'button_icon_type','ever_compare_settings_tabs','none' ),
            'added_button_icon_type'=> woolentor_get_option( 'added_button_icon_type','ever_compare_settings_tabs','none' ),
            'enable_shareable_link' => woolentor_get_option( 'enable_shareable_link','ever_compare_table_settings_tabs','off' ),
            'button_style'          => woolentor_get_option( 'button_style','ever_compare_style_tabs','theme' ),
            'table_style'           => woolentor_get_option( 'table_style','ever_compare_style_tabs','default' ),
        );
        $admin_localize_data = array(
            'is_settings'=> $setting_page,
            'option_data'=> $admin_option_data,
        );
        wp_localize_script( 'evercompare-frontend', 'evercompare', $localize_data );
        wp_localize_script( 'evercompare-admin', 'evercompare', $admin_localize_data );
        
    }

    /**
     * [inline_style]
     * @return [CSS String]
     */
    public function inline_style(){

        $inline_css = '';

        // Button Custom Style
        if( 'custom' === woolentor_get_option( 'button_style', 'ever_compare_style_tabs', 'theme' ) ){

            $btn_padding        = ever_compare_dimensions( 'button_custom_padding','ever_compare_style_tabs','padding' );
            $btn_margin         = ever_compare_dimensions( 'button_custom_margin','ever_compare_style_tabs','margin' );
            $btn_border_radius  = ever_compare_dimensions( 'button_custom_border_radius','ever_compare_style_tabs','border-radius' );
            $btn_border_width   = ever_compare_dimensions( 'button_custom_border','ever_compare_style_tabs','border-width' );
            $btn_border_style   = !empty( $btn_border_width ) ? 'border-style:solid;' : '';

            $btn_border_color = ever_compare_generate_css('button_custom_border_color','ever_compare_style_tabs','border-color');
            $btn_color        = ever_compare_generate_css('button_color','ever_compare_style_tabs','color');
            $btn_bg_color     = ever_compare_generate_css('background_color','ever_compare_style_tabs','background-color');

            // Hover
            $btn_hover_color    = ever_compare_generate_css('button_hover_color','ever_compare_style_tabs','color');
            $btn_hover_bg_color = ever_compare_generate_css('hover_background_color','ever_compare_style_tabs','background-color');

            $inline_css .= "
                .htcompare-btn{
                    {$btn_padding}
                    {$btn_margin}
                    {$btn_color}
                    {$btn_bg_color}
                    {$btn_border_width}
                    {$btn_border_style}
                    {$btn_border_color}
                    {$btn_border_radius}
                }
                .htcompare-btn:hover{
                    {$btn_hover_color}
                    {$btn_hover_bg_color}
                }
            ";

        }

        // Table style
        if( 'custom' === woolentor_get_option( 'table_style', 'ever_compare_style_tabs', 'default' ) ){

            $border_color   = ever_compare_generate_css('table_border_color','ever_compare_style_tabs','border-color');
            $column_padding = ever_compare_dimensions( 'table_column_padding','ever_compare_style_tabs','padding' );

            $event_bg_color = ever_compare_generate_css('table_event_color','ever_compare_style_tabs','background-color');
            $odd_bg_color = ever_compare_generate_css('table_odd_color','ever_compare_style_tabs','background-color');

            $event_heading_color = ever_compare_generate_css('table_heading_event_color','ever_compare_style_tabs','color');
            $odd_heading_color = ever_compare_generate_css('table_heading_odd_color','ever_compare_style_tabs','color');

            $event_content_color = ever_compare_generate_css('table_content_event_color','ever_compare_style_tabs','color');
            $odd_content_color = ever_compare_generate_css('table_content_odd_color','ever_compare_style_tabs','color');

            $link_color = ever_compare_generate_css('table_content_link_color','ever_compare_style_tabs','color');
            $link_hover_color = ever_compare_generate_css('table_content_link_hover_color','ever_compare_style_tabs','color');

            $inline_css .= "
                .htcompare-col,.compare-data-primary .htcolumn-value{
                    {$border_color}
                }
                .htcompare-col{
                    {$column_padding}
                }

                .htcompare-row:nth-child(2n) .htcompare-col{
                    {$event_bg_color}
                }
                .htcompare-row:nth-child(2n+1) .htcompare-col{
                    {$odd_bg_color}
                }

                .htcompare-row:nth-child(2n) .htcompare-col.htcolumn-field-name{
                    {$event_heading_color}
                }
                .htcompare-row:nth-child(2n+1) .htcompare-col.htcolumn-field-name{
                    {$odd_heading_color}
                }

                .htcompare-row:nth-child(2n) .htcompare-col.htcolumn-value{
                    {$event_content_color}
                }
                .htcompare-row:nth-child(2n+1) .htcompare-col.htcolumn-value{
                    {$odd_content_color}
                }

                .htcompare-row .htcompare-col.htcolumn-value a{
                    {$link_color}
                }
                .htcompare-row .htcompare-col.htcolumn-value a:hover{
                    {$link_hover_color}
                }

            ";
            
        }

        return $inline_css;

    }


}
