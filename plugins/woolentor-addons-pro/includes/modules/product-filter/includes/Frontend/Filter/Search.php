<?php
/**
 * Search.
 */

namespace WLPF\Frontend\Filter;

/**
 * Class.
 */
class Search {

    /**
     * Label.
     */
    protected $label;

    /**
     * Active label.
     */
    protected $active_label;

    /**
     * Placeholder.
     */
    protected $placeholder;

    /**
     * Apply.
     */
    protected $apply;

    /**
     * Apply button text.
     */
    protected $apply_button_txt;

    /**
     * Clear.
     */
    protected $clear;

    /**
     * Clear button text.
     */
    protected $clear_button_txt;

    /**
     * Collapsible.
     */
    protected $collapsible;

    /**
     * Collapsed by default.
     */
    protected $collapsed_by_default;

    /**
     * Unique ID.
     */
    protected $unique_id;

    /**
     * Settings.
     */
    protected $settings;

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
    public function __construct( $settings = array(), $show_label = true, $group_item = false, $group_apply = '', $group_clear = '' ) {
        $settings    = wlpf_cast( $settings, 'array' );
        $show_label  = wlpf_cast( $show_label, 'bool' );
        $group_item  = wlpf_cast( $group_item, 'bool' );
        $group_apply = wlpf_cast( $group_apply, 'key' );
        $group_clear = wlpf_cast( $group_clear, 'key' );

        $label                = ( isset( $settings['filter_label'] ) ? wlpf_cast( $settings['filter_label'], 'text' ) : '' );
        $active_label         = ( isset( $settings['filter_active_label'] ) ? wlpf_cast( $settings['filter_active_label'], 'text' ) : '' );
        $placeholder          = ( isset( $settings['filter_search_placeholder'] ) ? wlpf_cast( $settings['filter_search_placeholder'], 'text' ) : '' );
        $apply                = ( isset( $settings['filter_apply_action'] ) ? wlpf_cast( $settings['filter_apply_action'], 'key' ) : '' );
        $apply_button_txt     = ( isset( $settings['filter_apply_action_button_txt'] ) ? wlpf_cast( $settings['filter_apply_action_button_txt'], 'text' ) : '' );
        $clear                = ( isset( $settings['filter_clear_action'] ) ? wlpf_cast( $settings['filter_clear_action'], 'key' ) : '' );
        $clear_button_txt     = ( isset( $settings['filter_clear_action_button_txt'] ) ? wlpf_cast( $settings['filter_clear_action_button_txt'], 'text' ) : '' );
        $collapsible          = ( isset( $settings['filter_collapsible'] ) ? wlpf_cast( $settings['filter_collapsible'], 'selectbool' ) : true );
        $collapsed_by_default = ( isset( $settings['filter_collapsed_by_default'] ) ? wlpf_cast( $settings['filter_collapsed_by_default'], 'selectbool' ) : false );
        $unique_id            = ( isset( $settings['filter_unique_id'] ) ? wlpf_cast( $settings['filter_unique_id'], 'absint' ) : 0 );

        $apply            = ( ( 'auto' === $apply ) ? 'auto' : 'button' );
        $apply_button_txt = ( ( 0 < strlen( $apply_button_txt ) ) ? $apply_button_txt : esc_html__( 'Apply', 'woolentor-pro' ) );
        $clear            = ( ( 'none' === $clear ) ? 'none' : 'button' );
        $clear_button_txt = ( ( 0 < strlen( $clear_button_txt ) ) ? $clear_button_txt : esc_html__( 'Apply', 'woolentor-pro' ) );
        $group_apply      = ( ( 'auto' === $group_apply ) ? 'auto' : ( ( 'individual' === $group_apply ) ? 'individual' : 'button' ) );
        $group_clear      = ( ( 'none' === $group_clear ) ? 'none' : ( ( 'individual' === $group_clear ) ? 'individual' : 'button' ) );

        if ( empty( $unique_id ) ) {
            return;
        }

        $this->label                = $label;
        $this->active_label         = $active_label;
        $this->placeholder          = $placeholder;
        $this->apply                = $apply;
        $this->apply_button_txt     = $apply_button_txt;
        $this->clear                = $clear;
        $this->clear_button_txt     = $clear_button_txt;
        $this->collapsible          = $collapsible;
        $this->collapsed_by_default = $collapsed_by_default;
        $this->unique_id            = $unique_id;
        $this->settings             = $settings;
        $this->show_label           = $show_label;
        $this->group_item           = $group_item;
        $this->group_apply          = $group_apply;
        $this->group_clear          = $group_clear;

        $this->prepare_content();
    }

    /**
     * Get classes.
     */
    protected function get_classes() {
        $classes = '';

        $classes .= ' wlpf-filter-wrap wlpf-search-filter';

        if ( ( true === $this->collapsible ) && ( true === $this->collapsed_by_default ) ) {
            $classes .= ' wlpf-filter-collapsed';
        }

        $classes .= ' wlpf-filter-' . $this->unique_id;

        return trim( $classes );
    }

    /**
     * Get attributes.
     */
    protected function get_attributes( $selected_data ) {
        $attributes = '';

        $attributes .= ' data-wlpf-active-label="' . esc_attr( $this->active_label ) . '"';
        $attributes .= ' data-inserted-keyword="' . esc_attr( $selected_data ) . '"';

        if ( true === $this->group_item ) {
            $attributes .= ' data-wlpf-group-item="1"';
        } else {
            $attributes .= ' data-wlpf-group-item="0"';
        }

        $attributes .= ' data-wlpf-apply-action="' . esc_attr( $this->apply ) . '"';
        $attributes .= ' data-wlpf-clear-action="' . esc_attr( $this->clear ) . '"';

        if ( true === $this->group_item ) {
            $attributes .= ' data-wlpf-group-apply-action="' . esc_attr( $this->group_apply ) . '"';
            $attributes .= ' data-wlpf-group-clear-action="' . esc_attr( $this->group_clear ) . '"';
        } else {
            $attributes .= ' data-wlpf-group-apply-action=""';
            $attributes .= ' data-wlpf-group-clear-action=""';
        }

        $attributes .= ' data-wlpf-apply-action-taken="1"';
        $attributes .= ' data-wlpf-clear-action-taken="1"';
        $attributes .= ' data-wlpf-fixed-filter-args="' . htmlspecialchars( wp_json_encode( wlpf_get_fixed_filter_args() ) ) . '"';

        return trim( $attributes );
    }

    /**
     * Get content attributes.
     */
    protected function get_content_attributes() {
        $attributes = '';

        $attributes .= ( ( true === $this->collapsed_by_default ) ? ' style="display: none;"' : ' style="display: block;"' );

        return trim( $attributes );
    }

    /**
     * Get selected data.
     */
    protected function get_selected_data() {
        $data = \WLPF\Frontend\Selected::get_data( 'search' );
        $search = ( isset( $data['search'] ) ? wlpf_cast( $data['search'], 'text' ) : '' );

        return $search;
    }

    /**
     * Prepare content.
     */
    protected function prepare_content() {
        $content = '';

        $selected_data = $this->get_selected_data();

        $classes = $this->get_classes();
        $attributes = $this->get_attributes( $selected_data );

        $content_attributes = $this->get_content_attributes();

        $apply_button = ( ( 'button' === $this->apply ) ? true : false );
        $apply_button = ( ( true === $this->group_item ) ? ( ( 'individual' === $this->group_apply ) ? $apply_button : false ) : $apply_button );

        $clear_button = ( ( 'button' === $this->clear ) ? true : false );
        $clear_button = ( ( true === $this->group_item ) ? ( ( 'individual' === $this->group_clear ) ? $clear_button : false ) : $clear_button );

        $content .= '<div class="' . $classes . '" ' . $attributes . '>';

        if ( ( ( true === $this->show_label ) && ( 0 < strlen( $this->label ) ) ) || ( true === $this->collapsible ) ) {
            $content .= '<div class="wlpf-filter-header">';

            if ( ( true === $this->show_label ) && ( 0 < strlen( $this->label ) ) ) {
                $content .= '<div class="wlpf-filter-label">';
                $content .= '<h2 class="wlpf-filter-label-text">' . $this->label . '</h2>';
                $content .= '</div>';
            }

            if ( true === $this->collapsible ) {
                $content .= '<div class="wlpf-filter-collapse">';
                $content .= '<button class="wlpf-filter-collapse-button"><i class="wlpf-icon"></i></button>';
                $content .= '</div>';
            }

            $content .= '</div>';
        }

        $content .= '<div class="wlpf-filter-content" ' . $content_attributes . '>';
        $content .= '<div class="wlpf-search-input">';
        $content .= '<input type="text" class="wlpf-search-field" name="wlpf-search-field" value="' . $selected_data . '" placeholder="' . $this->placeholder . '">';
        $content .= '<div class="wlpf-search-icon"><i class="wlpf-icon wlpf-icon-search"></i></div>';
        $content .= '</div>';

        if ( ( true === $apply_button ) || ( true === $clear_button ) ) {
            $content .= '<div class="wlpf-filter-action wlpf-filter-action-bottom">';

            if ( true === $apply_button ) {
                $content .= '<div class="wlpf-filter-action-item wlpf-filter-apply-action">';
                $content .= '<button class="wlpf-filter-apply-action-button">' . $this->apply_button_txt . '</button>';
                $content .= '</div>';
            }

            if ( true === $clear_button ) {
                $content .= '<div class="wlpf-filter-action-item wlpf-filter-clear-action">';
                $content .= '<button class="wlpf-filter-clear-action-button">' . $this->clear_button_txt . '</button>';
                $content .= '</div>';
            }

            $content .= '</div>';
        }

        $content .= '</div>';
        $content .= '</div>';

        $this->content = $content;
    }

    /**
     * Get content.
     */
    public static function get_content( $settings = array(), $show_label = true, $group_item = false, $group_apply = '', $group_clear = '' ) {
        $instance = new self( $settings, $show_label, $group_item, $group_apply, $group_clear );

        $content = $instance->content;
        $content = ( is_string( $content ) ? $content : '' );

        return $content;
    }

}