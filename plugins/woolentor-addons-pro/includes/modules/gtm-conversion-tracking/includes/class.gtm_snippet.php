<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooLentor_GTM_Snippet handler class
 */
class WooLentor_GTM_Snippet extends Woolentor_GTM_Conversion_Tracking {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [WooLentor_GTM_Snippet]
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * [__construct] Class construct
     */
    function __construct() {

        $enable = $this->get_saved_data( 'enable', 'on' );
        if( 'on' == $enable ){
            add_action( 'wp_head', [ $this, 'wp_header' ], 10, 0 );
            add_action( 'wp_head', [ $this, 'wp_header_top' ], 1, 0 );
            $this->gtm_tag_load_position();
        }

    }

    /**
     * [get_the_gtm_tag] get Google tag manager noscript
     * @return [string]
     */
    public function get_the_gtm_tag(){

        $gtm_tag = '
            <!-- Google Tag Manager (noscript) -->';

        $enable = $this->get_saved_data( 'enable', 'on' );
        $gtm_id = $this->get_saved_data( 'gtm_id', '' );

        if ( ( !empty( $gtm_id ) ) && ( 'on' == $enable ) ) {
            
            $gtm_tag = '
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $gtm_id . '"height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';

            $gtm_tag .= '
            <!-- End Google Tag Manager (noscript) -->';

            $gtm_tag = apply_filters( 'woolentor_get_the_gtm_tag', $gtm_tag );

        }

        return $gtm_tag;

    }

    /**
     * [the_gtm_tag] Print Google tag manager noscript
     * @return [void]
     */
    public function the_gtm_tag() {
        echo $this->get_the_gtm_tag();
    }

    /**
     * [gtm_tag_load_position] Google tag manager noscript position
     * @return [void]
     */
    public function gtm_tag_load_position(){

        $position = 'open_body';

        switch ( $position ) {
            case 'footer':
                add_action( 'wp_footer', [ $this, 'the_gtm_tag' ] );
                break;
            
            default:
                add_action( 'wp_body_open', [ $this, 'the_gtm_tag' ] );
                break;
        }


    }

    /**
     * [wp_header_top] Google tag manager data global variable
     * @return [void]
     */
    public function wp_header_top() {
        
        $html5_support = current_theme_supports( 'html5' );

        $gtm_top_content = '
        <!-- Google Tag Manager for WooCommerce -->
        <script data-cfasync="false" data-pagespeed-no-defer' . ( $html5_support ? ' type="text/javascript"' : '' ) . '>//<![CDATA[

            var dataLayer = dataLayer || [];';

            $gtm_top_content .= '
        //]]>
        </script>
        <!-- End Google Tag Manager for WooCommerce -->';

        echo $gtm_top_content;
        
    }

    /**
     * [wp_header] Google tag manager data layer content
     * @param  boolean $echo
     * @return [string]
     */
    public static function wp_header( $echo = true ){
        $html5_support = current_theme_supports( 'html5' );

        $datalayer_data = array();
        $datalayer_data = (array) apply_filters( WOOLENTOR_GTM_DATALAYER, $datalayer_data );

        if ( version_compare( PHP_VERSION, '5.4.0' ) >= 0 ) {
            $datalayer_json = json_encode( $datalayer_data, JSON_UNESCAPED_UNICODE );
        } else {
            $datalayer_json = json_encode( $datalayer_data );
        }

        $header_content = '
        <!-- Start Google Tag Manager for WooCommerce -->
        <script data-cfasync="false" data-pagespeed-no-defer' . ( $html5_support ? ' type="text/javascript"' : '' ) . '>//<![CDATA[';

            $header_content .= '
            var dataLayer_content = ' . $datalayer_json . ';';

            $header_content .= 'dataLayer.push( dataLayer_content );';

            $header_content .= '//]]>

        </script>';

        $header_content .= apply_filters( 'woolentor_after_datalayer', '' );


        $enable = Woolentor_GTM_Conversion_Tracking::get_instance()->get_saved_data( 'enable', 'on' );
        $gtm_id = Woolentor_GTM_Conversion_Tracking::get_instance()->get_saved_data( 'gtm_id', '' );

        if ( ( !empty( $gtm_id ) ) && ( 'on' == $enable ) ) {

            $gtm_tag = '<!-- Start Google Tag Manager for WooCommerce-->';

            $gtm_tag .= '<script data-cfasync="false">//<![CDATA[
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':
            new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=
            \'//www.googletagmanager.com/gtm.\'' . '+\'js?id=\'+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,\'script\',\'dataLayer\',\''.$gtm_id.'\');//]]>
            </script>';

            $gtm_tag = apply_filters( 'woolentor_get_the_gtm_tag', $gtm_tag );
            $header_content .= $gtm_tag;
        }

        $header_content .= '<!-- End Google Tag Manager for WooCommerce -->';

        if ( $echo ) {
            echo $header_content;
        } else {
            return $header_content;
        }

    }


}