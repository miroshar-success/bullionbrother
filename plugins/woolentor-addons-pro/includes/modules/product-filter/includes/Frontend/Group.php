<?php
/**
 * Group.
 */

namespace WLPF\Frontend;

/**
 * Class.
 */
class Group {

	/**
     * ID.
     */
    protected $id;

    /**
     * Show label.
     */
    protected $show_label;

    /**
     * Content.
     */
    protected $content;

    /**
     * Constructor.
     */
    public function __construct( $id = 0, $show_label = true ) {
        $id = wlpf_cast( $id, 'absint' );
        $show_label = wlpf_cast( $show_label, 'bool' );

        $this->id = $id;
        $this->show_label = $show_label;

        $this->prepare_content();
    }

    /**
     * Prepare content.
     */
    protected function prepare_content() {
        $content = '';

        $groups = wlpf_get_groups();

        if ( ! is_array( $groups ) || empty( $groups ) ) {
            return $content;
        }

        $group = array();

        foreach ( $groups as $item ) {
            $unique_id = ( isset( $item['group_unique_id'] ) ? wlpf_cast( $item['group_unique_id'], 'absint' ) : 0 );

            if ( $this->id === $unique_id ) {
                $group = $item;
                break;
            }
        }

        if ( ! is_array( $group ) || empty( $group ) ) {
            return $content;
        }

        $content = \WLPF\Frontend\Group\Base::get_content( $group, $this->show_label );

        $this->content = $content;
    }

    /**
     * Get content.
     */
    public static function get_content( $id = 0, $show_label = true ) {
        $instance = new self( $id, $show_label );

        $content = $instance->content;
        $content = ( is_string( $content ) ? $content : '' );

        return $content;
    }

}