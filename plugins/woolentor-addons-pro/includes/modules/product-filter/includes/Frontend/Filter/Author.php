<?php
/**
 * Author.
 */

namespace WLPF\Frontend\Filter;

/**
 * Class.
 */
class Author {

    /**
     * Label.
     */
    protected $label;

    /**
     * Active label.
     */
    protected $active_label;

    /**
     * Authors include.
     */
    protected $authors_include;

    /**
     * Authors exclude.
     */
    protected $authors_exclude;

    /**
     * Orderby.
     */
    protected $orderby;

    /**
     * Order.
     */
    protected $order;

    /**
     * Field type.
     */
    protected $field_type;

    /**
     * Authors count.
     */
    protected $authors_count;

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
        $authors_include      = ( isset( $settings['filter_authors_include'] ) ? wlpf_cast( $settings['filter_authors_include'], 'text' ) : '' );
        $authors_exclude      = ( isset( $settings['filter_authors_exclude'] ) ? wlpf_cast( $settings['filter_authors_exclude'], 'text' ) : '' );
        $orderby              = ( isset( $settings['filter_author_orderby'] ) ? wlpf_cast( $settings['filter_author_orderby'], 'key' ) : '' );
        $order                = ( isset( $settings['filter_order'] ) ? wlpf_cast( $settings['filter_order'], 'key' ) : '' );
        $field_type           = ( isset( $settings['filter_field_type'] ) ? wlpf_cast( $settings['filter_field_type'], 'key' ) : 'checkbox' );
        $authors_count        = ( isset( $settings['filter_authors_count'] ) ? wlpf_cast( $settings['filter_authors_count'], 'selectbool' ) : false );
        $placeholder          = ( isset( $settings['filter_select_placeholder'] ) ? wlpf_cast( $settings['filter_select_placeholder'], 'text' ) : '' );
        $apply                = ( isset( $settings['filter_apply_action'] ) ? wlpf_cast( $settings['filter_apply_action'], 'key' ) : '' );
        $apply_button_txt     = ( isset( $settings['filter_apply_action_button_txt'] ) ? wlpf_cast( $settings['filter_apply_action_button_txt'], 'text' ) : '' );
        $clear                = ( isset( $settings['filter_clear_action'] ) ? wlpf_cast( $settings['filter_clear_action'], 'key' ) : '' );
        $clear_button_txt     = ( isset( $settings['filter_clear_action_button_txt'] ) ? wlpf_cast( $settings['filter_clear_action_button_txt'], 'text' ) : '' );
        $max_height           = ( isset( $settings['filter_max_height'] ) ? wlpf_cast( $settings['filter_max_height'], 'absint' ) : 0 );
        $collapsible          = ( isset( $settings['filter_collapsible'] ) ? wlpf_cast( $settings['filter_collapsible'], 'selectbool' ) : true );
        $collapsed_by_default = ( isset( $settings['filter_collapsed_by_default'] ) ? wlpf_cast( $settings['filter_collapsed_by_default'], 'selectbool' ) : false );
        $unique_id            = ( isset( $settings['filter_unique_id'] ) ? wlpf_cast( $settings['filter_unique_id'], 'absint' ) : 0 );

        $authors_include = wlpf_string_to_array_of_id( $authors_include );
        $authors_exclude = wlpf_string_to_array_of_id( $authors_exclude );

        $orderby          = ( ( ! empty( $orderby ) ) ? $orderby : 'login' );
        $order            = ( ( ! empty( $order ) ) ? $order : 'asc' );
        $field_type       = ( ( ! empty( $field_type ) ) ? $field_type : 'checkbox' );
        $placeholder      = ( ( 0 < strlen( $placeholder ) ) ? $placeholder : esc_html__( 'Choose an option', 'woolentor-pro' ) );
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
        $this->authors_include      = $authors_include;
        $this->authors_exclude      = $authors_exclude;
        $this->orderby              = $orderby;
        $this->order                = $order;
        $this->field_type           = $field_type;
        $this->authors_count        = $authors_count;
        $this->placeholder          = $placeholder;
        $this->apply                = $apply;
        $this->apply_button_txt     = $apply_button_txt;
        $this->clear                = $clear;
        $this->clear_button_txt     = $clear_button_txt;
        $this->max_height           = $max_height;
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

        $classes .= ' wlpf-filter-wrap wlpf-author-filter';
        $classes .= ' wlpf-filter-field-type-' . $this->field_type;
        $classes .= ' wlpf-filter-terms-name-yes';

        $classes .= ' wlpf-filter-terms-count-' . ( ( true === $this->authors_count ) ? 'yes' : 'no' );

        if ( ( true === $this->collapsible ) ) {
            $classes .= ' wlpf-filter-collapsible';

            if ( true === $this->collapsed_by_default ) {
                $classes .= ' wlpf-filter-collapsed';
            }
        }

        $classes .= ' wlpf-filter-' . $this->unique_id;

        return trim( $classes );
    }

    /**
     * Get attributes.
     */
    protected function get_attributes( $terms_data, $selected_data ) {
        $attributes = '';

        $attributes .= ' data-wlpf-active-label="' . esc_attr( $this->active_label ) . '"';
        $attributes .= ' data-wlpf-available-terms="' . htmlspecialchars( wp_json_encode( $terms_data ) ) . '"';
        $attributes .= ' data-wlpf-selected-terms="' . htmlspecialchars( wp_json_encode( $selected_data ) ) . '"';

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
     * Get terms data.
     */
    protected function get_terms_data() {
        $terms_data = array();

        $args = array(
            'orderby' => $this->orderby,
            'order'   => $this->order,
            'include' => $this->authors_include,
            'exclude' => $this->authors_exclude,
        );

        $users = get_users( $args );

        if ( is_array( $users ) && ! empty( $users ) ) {
            foreach ( $users as $user ) {
                if ( is_object( $user ) ) {
                    $term_data = array();

                    $id       = ( isset( $user->ID ) ? wlpf_cast( $user->ID, 'absint' ) : 0 );
                    $name     = ( isset( $user->display_name ) ? wlpf_cast( $user->display_name, 'text' ) : '' );
                    $username = ( isset( $user->user_login ) ? wlpf_cast( $user->user_login, 'text' ) : '' );
                    $count    = count_user_posts( $id, 'product' );

                    $term_data = array(
                        'id'       => $id,
                        'name'     => $name,
                        'username' => $username,
                        'count'    => $count,
                    );

                    $terms_data[ $id ] = $term_data;
                }
            }
        }

        return $terms_data;
    }

    /**
     * Get selected data.
     */
    protected function get_selected_data() {
        $data = \WLPF\Frontend\Selected::get_data( 'author' );
        $author = ( isset( $data['vendor'] ) ? wlpf_cast( $data['vendor'], 'array' ) : array() );

        $terms = ( isset( $author['terms'] ) ? wlpf_cast( $author['terms'], 'array' ) : array() );
        $terms_info = ( isset( $author['terms_info'] ) ? wlpf_cast( $author['terms_info'], 'array' ) : array() );

        if ( 'checkbox' !== $this->field_type ) {
            $term = ( isset( $terms[0] ) ? $terms[0] : 0 );
            $term_info = ( isset( $terms_info[ $term ] ) ? $terms_info[ $term ] : array() );

            if ( ! empty( $term ) && ! empty( $term_info ) ) {
                $terms = array( $term );
                $terms_info = array( $term => $term_info );
            }
        }

        return $terms_info;
    }

    /**
     * Get list content.
     */
    protected function get_list_content( $terms_data = array(), $selected_data = array() ) {
        $list_content = '';

        if ( is_array( $terms_data ) && ! empty( $terms_data ) ) {
            foreach ( $terms_data as $term_data ) {
                $id       = ( isset( $term_data['id'] ) ? wlpf_cast( $term_data['id'], 'absint' ) : 0 );
                $name     = ( isset( $term_data['name'] ) ? wlpf_cast( $term_data['name'], 'text' ) : '' );
                $username = ( isset( $term_data['username'] ) ? wlpf_cast( $term_data['username'], 'text' ) : '' );
                $count    = ( isset( $term_data['count'] ) ? wlpf_cast( $term_data['count'], 'absint' ) : 0 );

                $list_content .= '<li class="wlpf-term-item">';
                $list_content .= '<span class="wlpf-term-content">';
                $list_content .= '<label class="wlpf-term-label">';
                $list_content .= '<span class="wlpf-term-input">';

                if ( isset( $selected_data[ $id ] ) ) {
                    $list_content .= '<input class="wlpf-term-field" type="' . $this->field_type . '" name="wlpf-author" value="' . $id . '" checked="checked">';
                } else {
                    $list_content .= '<input class="wlpf-term-field" type="' . $this->field_type . '" name="wlpf-author" value="' . $id . '">';
                }

                $list_content .= '<span class="wlpf-term-box"></span>';
                $list_content .= '</span>';

                $list_content .= '<span class="wlpf-term-info">';
                $list_content .= '<span class="wlpf-term-name">' . $name . '</span>';

                if ( true === $this->authors_count ) {
                    $list_content .= '<span class="wlpf-term-count">' . sprintf( '(%1$s)', $count ) . '</span>';
                }

                $list_content .= '</span>';

                $list_content .= '</label>';
                $list_content .= '</span>';
                $list_content .= '</li>';
            }
        }

        return $list_content;
    }

    /**
     * Get select content.
     */
    protected function get_select_content( $terms_data = array(), $selected_data = array() ) {
        $select_content = '';

        if ( is_array( $terms_data ) && ! empty( $terms_data ) ) {
            foreach ( $terms_data as $term_data ) {
                $id       = ( isset( $term_data['id'] ) ? wlpf_cast( $term_data['id'], 'absint' ) : 0 );
                $name     = ( isset( $term_data['name'] ) ? wlpf_cast( $term_data['name'], 'text' ) : '' );
                $username = ( isset( $term_data['username'] ) ? wlpf_cast( $term_data['username'], 'text' ) : '' );
                $count    = ( isset( $term_data['count'] ) ? wlpf_cast( $term_data['count'], 'absint' ) : 0 );

                $option = '';

                if ( true === $this->authors_count ) {
                    $option .= sprintf( '%1$s (%2$s)', $name, $count );
                } else {
                    $option .= $name;
                }

                if ( isset( $selected_data[ $id ] ) ) {
                    $select_content .= '<option class="wlpf-term-item" value="' . $id . '" selected="selected">' . $option . '</option>';
                } else {
                    $select_content .= '<option class="wlpf-term-item" value="' . $id . '">' . $option . '</option>';
                }
            }
        }

        return $select_content;
    }

    /**
     * Prepare content.
     */
    protected function prepare_content() {
        $content = '';

        $list_content = '';
        $select_content = '';

        $terms_data = $this->get_terms_data();
        $selected_data = $this->get_selected_data( $terms_data );

        $classes = $this->get_classes();
        $attributes = $this->get_attributes( $terms_data, $selected_data );

        $content_attributes = $this->get_content_attributes();

        if ( ( 'checkbox' === $this->field_type ) || ( 'radio' === $this->field_type ) ) {
            $list_content = $this->get_list_content( $terms_data, $selected_data );
        } elseif ( 'select' === $this->field_type ) {
            $select_content = $this->get_select_content( $terms_data, $selected_data );

            if ( ! empty( $select_content ) ) {
                $static_content = '<option class="wlpf-term-item" value="0">' . $this->placeholder . '</option>';
                $select_content = $static_content . $select_content;
            }
        }

        $apply_button = ( ( 'button' === $this->apply ) ? true : false );
        $apply_button = ( ( true === $this->group_item ) ? ( ( 'individual' === $this->group_apply ) ? $apply_button : false ) : $apply_button );

        $clear_button = ( ( 'button' === $this->clear ) ? true : false );
        $clear_button = ( ( true === $this->group_item ) ? ( ( 'individual' === $this->group_clear ) ? $clear_button : false ) : $clear_button );

        if ( ! empty( $list_content ) || ! empty( $select_content ) ) {
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

            if ( ! empty( $list_content ) ) {
                $list_attributes = '';

                if ( 0 < $this->max_height ) {
                    $list_attributes = ' style="max-height: ' . esc_attr( $this->max_height ) . 'px"';
                }

                $content .= '<ul class="wlpf-terms-list"' . $list_attributes . '>' . $list_content . '</ul>';
            } elseif ( ! empty( $select_content ) ) {
                $content .= '<select class="wlpf-terms-select">' . $select_content . '</select>';
            }

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
        }

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