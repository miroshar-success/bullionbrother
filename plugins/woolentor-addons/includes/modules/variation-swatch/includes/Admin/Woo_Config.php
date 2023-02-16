<?php
namespace Woolentor\Modules\Swatchly\Admin;
use Woolentor\Modules\Swatchly\Helper as Helper;
/**
 * Woo_Config class
 */
class Woo_Config {

    /**
     * Constructor
     */
    public function __construct() {
        // Add swatch types
        add_filter( 'product_attributes_type_selector', array( $this, 'add_swatch_types'), 10, 1 );

        // Swatch type preview column
        $taxonomy = isset($_GET['taxonomy']) ? sanitize_title( $_GET['taxonomy'] ) : '';
        add_filter( 'manage_edit-'. $taxonomy .'_columns', array( $this, 'add_new_attribute_column') );
        add_filter( 'manage_'. $taxonomy .'_custom_column', array( $this, 'add_swatch_preview_markup' ), 10, 3 );
    }

    /**
     * Add swatch types
     */
    public function add_swatch_types( $fields ){
        $current_screen = get_current_screen();

        if(isset($current_screen->base) && $current_screen->base == 'product_page_product_attributes'){
            $new_fields           = array();
            $new_fields['select'] = esc_html__( 'Select', 'woolentor' );
            $new_fields['label']  = esc_html__( 'WL Label', 'woolentor' );
            $new_fields['color']  = esc_html__( 'WL Color', 'woolentor' );
            $new_fields['image']  = esc_html__( 'WL Image', 'woolentor' );
            $fields               = array_merge( $new_fields, $fields );
        }

        return $fields;
    }

    /**
     * Add new column for swatch preview
     */
    public function add_new_attribute_column( $columns ){
        global $taxnow;
        $request_taxonomy = isset($_GET['taxonomy']) ? sanitize_title($_GET['taxonomy']) : '';
        if($request_taxonomy !== $taxnow){
            return $columns;
        }

        $swatch_type = Helper::get_swatch_type( $request_taxonomy );
        if( !in_array($swatch_type, array('color', 'image')) ){
            return $columns;
        }

        $new_columns          = array();
        $new_columns['cb']    = isset($columns['cb']) ? $columns['cb'] : '';
        $new_columns['thumb'] = '';
        unset( $columns['cb'] );

        $new_columns = array_merge( $new_columns, $columns );

        return $new_columns;
    }

    /**
     * Add swatch preview markup
     */
    public function add_swatch_preview_markup( $columns, $column, $term_id ){
        global $taxnow;
        $request_taxonomy = isset($_GET['taxonomy']) ? sanitize_title($_GET['taxonomy']) : '';
        if( ($request_taxonomy !== $taxnow) || ('thumb' !== $column)){
            return $columns;
        }

        $swatch_type = Helper::get_swatch_type( $request_taxonomy );
        if( !in_array($swatch_type, array('color', 'image')) ){
            return $columns;
        }

        switch ( $swatch_type ) {
            case 'color':
                $color              = get_term_meta( $term_id, 'swatchly_color', true );
                $enable_multi_color = get_term_meta( $term_id, 'swatchly_enable_multi_color', true );
                $color_2            = get_term_meta( $term_id, 'swatchly_color_2', true );

                if($enable_multi_color){
                    echo '<div class="swatchly_preview" style="background: linear-gradient( -50deg, '. esc_attr($color) .' 50%, '. esc_attr($color_2) .' 50% );"></div>';
                } else{
                    printf( '<div class="swatchly_preview" style="background-color:%s;"></div>', esc_attr( $color ) );
                }
                break;

            case 'image':
                $image_arr = get_term_meta( $term_id, 'swatchly_image', true );
                $image_url    = (is_array($image_arr) && $image_arr['thumbnail']) ? $image_arr['thumbnail'] : wc_placeholder_img_src();
                $image_url    = str_replace( ' ', '%20', $image_url );
                printf( '<img class="swatchly_preview" src="%s" width="44px" height="44px">', esc_url( $image_url ) );
                break;

            case 'label':
                echo '<div class="swatchly_preview"></div>';
                break;
        }
    }
}