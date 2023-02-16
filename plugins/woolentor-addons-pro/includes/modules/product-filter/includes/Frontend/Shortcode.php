<?php
/**
 * Shortcode.
 */

namespace WLPF\Frontend;

/**
 * Class.
 */
class Shortcode {

	/**
     * Constructor.
     */
    public function __construct() {
        add_shortcode( 'wlpf_filter', array( $this, 'filter' ) );
        add_shortcode( 'wlpf_group', array( $this, 'group' ) );
    }

    /**
     * Filter.
     */
    public function filter( $atts = array() ) {
        $atts = shortcode_atts(
            array(
                'id'         => 0,
                'show_label' => true,
            ),
            $atts,
            'wlpf_filter'
        );

        $id         = ( isset( $atts['id'] ) ? wlpf_cast( $atts['id'], 'absint' ) : 0 );
        $show_label = ( isset( $atts['show_label'] ) ? wlpf_cast( $atts['show_label'], 'bool' ) : true );

        $content = \WLPF\Frontend\Filter::get_content( $id, $show_label );
        $content = ( is_string( $content ) ? $content : '' );

        return $content;
    }

    /**
     * Group.
     */
    public function group( $atts = array() ) {
        $atts = shortcode_atts(
            array(
                'id'         => 0,
                'show_label' => true,
            ),
            $atts,
            'wlpf_group'
        );

        $id         = ( isset( $atts['id'] ) ? wlpf_cast( $atts['id'], 'absint' ) : 0 );
        $show_label = ( isset( $atts['show_label'] ) ? wlpf_cast( $atts['show_label'], 'bool' ) : true );

        $content = \WLPF\Frontend\Group::get_content( $id, $show_label );
        $content = ( is_string( $content ) ? $content : '' );

        return $content;
    }

}