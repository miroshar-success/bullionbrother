<?php
/**
 * Base.
 */

namespace WLPF\Frontend\Group;

/**
 * Class.
 */
class Base {

    /**
     * Label.
     */
    protected $label;

    /**
     * Filters.
     */
    protected $filters;

    /**
     * Filters label.
     */
    protected $filters_label;

    /**
     * Apply.
     */
    protected $apply;

    /**
     * Apply button text.
     */
    protected $apply_button_txt;

    /**
     * Apply button position.
     */
    protected $apply_button_pos;

    /**
     * Clear.
     */
    protected $clear;

    /**
     * Clear button text.
     */
    protected $clear_button_txt;

    /**
     * Clear button position.
     */
    protected $clear_button_pos;

    /**
     * Max height.
     */
    protected $max_height;

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
     * Content.
     */
    protected $content;

    /**
     * Constructor.
     */
    public function __construct( $settings = array(), $show_label = true ) {
        $label                = ( isset( $settings['group_label'] ) ? wlpf_cast( $settings['group_label'], 'text' ) : '' );
        $filters              = ( isset( $settings['group_filters'] ) ? wlpf_cast( $settings['group_filters'], 'array' ) : array() );
        $filters_label        = ( isset( $settings['group_filters_label'] ) ? wlpf_cast( $settings['group_filters_label'], 'selectbool' ) : true );
        $apply                = ( isset( $settings['group_apply_action'] ) ? wlpf_cast( $settings['group_apply_action'], 'key' ) : '' );
        $apply_button_txt     = ( isset( $settings['group_apply_action_button_txt'] ) ? wlpf_cast( $settings['group_apply_action_button_txt'], 'text' ) : '' );
        $apply_button_pos     = ( isset( $settings['group_apply_action_button_pos'] ) ? wlpf_cast( $settings['group_apply_action_button_pos'], 'key' ) : '' );
        $clear                = ( isset( $settings['group_clear_action'] ) ? wlpf_cast( $settings['group_clear_action'], 'key' ) : '' );
        $clear_button_txt     = ( isset( $settings['group_clear_action_button_txt'] ) ? wlpf_cast( $settings['group_clear_action_button_txt'], 'text' ) : '' );
        $clear_button_pos     = ( isset( $settings['group_clear_action_button_pos'] ) ? wlpf_cast( $settings['group_clear_action_button_pos'], 'key' ) : '' );
        $max_height           = ( isset( $settings['group_max_height'] ) ? wlpf_cast( $settings['group_max_height'], 'absint' ) : 0 );
        $collapsible          = ( isset( $settings['group_collapsible'] ) ? wlpf_cast( $settings['group_collapsible'], 'selectbool' ) : true );
        $collapsed_by_default = ( isset( $settings['group_collapsed_by_default'] ) ? wlpf_cast( $settings['group_collapsed_by_default'], 'selectbool' ) : false );
        $unique_id            = ( isset( $settings['group_unique_id'] ) ? wlpf_cast( $settings['group_unique_id'], 'absint' ) : 0 );

        $apply            = ( ( 'auto' === $apply ) ? 'auto' : ( ( 'individual' === $apply ) ? 'individual' : 'button' ) );
        $apply_button_txt = ( ( 0 < strlen( $apply_button_txt ) ) ? $apply_button_txt : esc_html__( 'Apply', 'woolentor-pro' ) );
        $apply_button_pos = ( ( 'top' === $apply_button_pos ) ? 'top' : ( ( 'both' === $apply_button_pos ) ? 'both' : 'bottom' ) );
        $clear            = ( ( 'none' === $clear ) ? 'none' : ( ( 'individual' === $clear ) ? 'individual' : 'button' ) );
        $clear_button_txt = ( ( 0 < strlen( $clear_button_txt ) ) ? $clear_button_txt : esc_html__( 'Clear', 'woolentor-pro' ) );
        $clear_button_pos = ( ( 'top' === $clear_button_pos ) ? 'top' : ( ( 'both' === $clear_button_pos ) ? 'both' : 'bottom' ) );

        if ( empty( $filters ) || empty( $unique_id ) ) {
            return;
        }

        $this->label                = $label;
        $this->filters              = $filters;
        $this->filters_label        = $filters_label;
        $this->apply                = $apply;
        $this->apply_button_txt     = $apply_button_txt;
        $this->apply_button_pos     = $apply_button_pos;
        $this->clear                = $clear;
        $this->clear_button_txt     = $clear_button_txt;
        $this->clear_button_pos     = $clear_button_pos;
        $this->max_height           = $max_height;
        $this->collapsible          = $collapsible;
        $this->collapsed_by_default = $collapsed_by_default;
        $this->unique_id            = $unique_id;
        $this->settings             = $settings;
        $this->show_label           = $show_label;

        $this->prepare_content();
    }

    /**
     * Get classes.
     */
    protected function get_classes() {
        $classes = '';

        $classes .= ' wlpf-group-wrap';

        if ( ( true === $this->collapsible ) ) {
            $classes .= ' wlpf-group-collapsible';

            if ( true === $this->collapsed_by_default ) {
                $classes .= ' wlpf-group-collapsed';
            }
        }

        $classes .= ' wlpf-group-' . $this->unique_id;

        return trim( $classes );
    }

    /**
     * Get attributes.
     */
    protected function get_attributes() {
        $attributes = '';

        $attributes .= ' data-wlpf-apply-action="' . esc_attr( $this->apply ) . '"';
        $attributes .= ' data-wlpf-clear-action="' . esc_attr( $this->clear ) . '"';
        $attributes .= ' data-wlpf-apply-action-taken="1"';
        $attributes .= ' data-wlpf-clear-action-taken="1"';

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
     * Get filters content.
     */
    protected function get_filters_content() {
        $filters_content = '';

        if ( is_array( $this->filters ) && ! empty( $this->filters ) ) {
            foreach ( $this->filters as $filter_id ) {
                $filters_content .= \WLPF\Frontend\Filter::get_content( $filter_id, $this->filters_label, true, $this->apply, $this->clear );
            }
        }

        return $filters_content;
    }

    /**
     * Prepare content.
     */
    protected function prepare_content() {
        $content = '';

        $classes = $this->get_classes();
        $attributes = $this->get_attributes();
        $content_attributes = $this->get_content_attributes();
        $filters_content = $this->get_filters_content();

        $filters_list_attributes = '';

        if ( 0 < $this->max_height ) {
            $filters_list_attributes = ' style="max-height: ' . esc_attr( $this->max_height ) . 'px"';
        }

        $top_apply_condition = ( ( ( 'button' === $this->apply ) && ( ( 'top' === $this->apply_button_pos ) || ( 'both' === $this->apply_button_pos ) ) ) ? true : false );
        $top_clear_condition = ( ( ( 'button' === $this->clear ) && ( ( 'top' === $this->clear_button_pos ) || ( 'both' === $this->clear_button_pos ) ) ) ? true : false );

        $bottom_apply_condition = ( ( ( 'button' === $this->apply ) && ( ( 'bottom' === $this->apply_button_pos ) || ( 'both' === $this->apply_button_pos ) ) ) ? true : false );
        $bottom_clear_condition = ( ( ( 'button' === $this->clear ) && ( ( 'bottom' === $this->clear_button_pos ) || ( 'both' === $this->clear_button_pos ) ) ) ? true : false );

        if ( ! empty( $filters_content ) ) {
            $content .= '<div class="' . $classes . '" ' . $attributes . '>';

            if ( ( ( true === $this->show_label ) && ( 0 < strlen( $this->label ) ) ) || ( true === $this->collapsible ) ) {
                $content .= '<div class="wlpf-group-header">';

                if ( ( true === $this->show_label ) && ( 0 < strlen( $this->label ) ) ) {
                    $content .= '<div class="wlpf-group-label">';
                    $content .= '<h2 class="wlpf-group-label-text">' . $this->label . '</h2>';
                    $content .= '</div>';
                }

                if ( true === $this->collapsible ) {
                    $content .= '<div class="wlpf-group-collapse">';
                    $content .= '<button class="wlpf-group-collapse-button"><i class="wlpf-icon"></i></button>';
                    $content .= '</div>';
                }

                $content .= '</div>';
            }

            $content .= '<div class="wlpf-group-content" ' . $content_attributes . '>';
            $content .= '<div class="wlpf-group-filters-list" ' . $filters_list_attributes . '>';

            if ( ( true === $top_apply_condition ) || ( true === $top_clear_condition ) ) {
                $content .= '<div class="wlpf-group-action wlpf-group-action-top">';

                if ( true === $top_apply_condition ) {
                    $content .= '<div class="wlpf-group-action-item wlpf-group-apply-action">';
                    $content .= '<button class="wlpf-group-apply-action-button">' . $this->apply_button_txt . '</button>';
                    $content .= '</div>';
                }

                if ( true === $top_clear_condition ) {
                    $content .= '<div class="wlpf-group-action-item wlpf-group-clear-action">';
                    $content .= '<button class="wlpf-group-clear-action-button">' . $this->clear_button_txt . '</button>';
                    $content .= '</div>';
                }

                $content .= '</div>';
            }

            $content .= $filters_content;

            if ( ( true === $bottom_apply_condition ) || ( true === $bottom_clear_condition ) ) {
                $content .= '<div class="wlpf-group-action wlpf-group-action-bottom">';

                if ( true === $bottom_apply_condition ) {
                    $content .= '<div class="wlpf-group-action-item wlpf-group-apply-action">';
                    $content .= '<button class="wlpf-group-apply-action-button">' . $this->apply_button_txt . '</button>';
                    $content .= '</div>';
                }

                if ( true === $bottom_clear_condition ) {
                    $content .= '<div class="wlpf-group-action-item wlpf-group-clear-action">';
                    $content .= '<button class="wlpf-group-clear-action-button">' . $this->clear_button_txt . '</button>';
                    $content .= '</div>';
                }

                $content .= '</div>';
            }

            $content .= '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }

        $this->content = $content;
    }

    /**
     * Get content.
     */
    public static function get_content( $settings = array(), $show_label = true ) {
        $instance = new self( $settings, $show_label );

        $content = $instance->content;
        $content = ( is_string( $content ) ? $content : '' );

        return $content;
    }

}