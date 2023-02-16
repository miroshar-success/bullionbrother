<?php
/**
 * Filter.
 */

namespace WLPF\Frontend;

/**
 * Class.
 */
class Filter {

    /**
     * ID.
     */
    protected $id;

    /**
     * Show label.
     */
    protected $show_label;

    /**
     * Group item.
     */
    protected $group_item;

    /**
     * Group apply.
     */
    protected $group_apply;

    /**
     * Group clear.
     */
    protected $group_clear;

    /**
     * Content.
     */
    protected $content;

    /**
     * Constructor.
     */
    public function __construct( $id = 0, $show_label = true, $group_item = false, $group_apply = '', $group_clear = '' ) {
        $id = wlpf_cast( $id, 'absint' );
        $show_label = wlpf_cast( $show_label, 'bool' );
        $group_item = wlpf_cast( $group_item, 'bool' );
        $group_apply = wlpf_cast( $group_apply, 'key' );
        $group_clear = wlpf_cast( $group_clear, 'key' );

        $this->id = $id;
        $this->show_label = $show_label;
        $this->group_item = $group_item;
        $this->group_apply = $group_apply;
        $this->group_clear = $group_clear;

        $this->prepare_content();
    }

    /**
     * Prepare content.
     */
    protected function prepare_content() {
        $content = '';

        $filters = wlpf_get_filters();

        if ( ! is_array( $filters ) || empty( $filters ) ) {
            return $content;
        }

        $filter = array();

        foreach ( $filters as $item ) {
            $unique_id = ( isset( $item['filter_unique_id'] ) ? wlpf_cast( $item['filter_unique_id'], 'absint' ) : 0 );

            if ( $this->id === $unique_id ) {
                $filter = $item;
                break;
            }
        }

        if ( ! is_array( $filter ) || empty( $filter ) ) {
            return $content;
        }

        $element = ( isset( $filter['filter_element'] ) ? wlpf_cast( $filter['filter_element'], 'key' ) : '' );

        if ( empty( $element ) ) {
            return $content;
        }

        switch ( $element ) {
            case 'taxonomy':
                $content = \WLPF\Frontend\Filter\Taxonomy::get_content( $filter, $this->show_label, $this->group_item, $this->group_apply, $this->group_clear );
                break;

            case 'attribute':
                $content = \WLPF\Frontend\Filter\Attribute::get_content( $filter, $this->show_label, $this->group_item, $this->group_apply, $this->group_clear );
                break;

            case 'author':
                $content = \WLPF\Frontend\Filter\Author::get_content( $filter, $this->show_label, $this->group_item, $this->group_apply, $this->group_clear );
                break;

            case 'price':
                $content = \WLPF\Frontend\Filter\Price::get_content( $filter, $this->show_label, $this->group_item, $this->group_apply, $this->group_clear );
                break;

            case 'sorting':
                $content = \WLPF\Frontend\Filter\Sorting::get_content( $filter, $this->show_label, $this->group_item, $this->group_apply, $this->group_clear );
                break;

            case 'search':
                $content = \WLPF\Frontend\Filter\Search::get_content( $filter, $this->show_label, $this->group_item, $this->group_apply, $this->group_clear );
                break;

            default:
                $content = '';
                break;
        }

        $this->content = $content;
    }

    /**
     * Get content.
     */
    public static function get_content( $id = 0, $show_label = true, $group_item = false, $group_apply = '', $group_clear = '' ) {
        $instance = new self( $id, $show_label, $group_item, $group_apply, $group_clear );

        $content = $instance->content;
        $content = ( is_string( $content ) ? $content : '' );

        return $content;
    }

}