<?php
/**
 * Taxonomy.
 */

namespace WLPF\Frontend\Filter;

/**
 * Class.
 */
class Taxonomy {

    /**
     * Label.
     */
    protected $label;

    /**
     * Active label.
     */
    protected $active_label;

    /**
     * Taxonomy.
     */
    protected $taxonomy;

    /**
     * Terms include.
     */
    protected $terms_include;

    /**
     * Terms exclude.
     */
    protected $terms_exclude;

    /**
     * Orderby.
     */
    protected $orderby;

    /**
     * Order.
     */
    protected $order;

    /**
     * Children terms.
     */
    protected $children_terms;

    /**
     * Terms hierarchy.
     */
    protected $terms_hierarchy;

    /**
     * Hide empty terms.
     */
    protected $hide_empty_terms;

    /**
     * With children terms.
     */
    protected $with_children_terms;

    /**
     * Terms operator.
     */
    protected $terms_operator;

    /**
     * Field type.
     */
    protected $field_type;

    /**
     * Placeholder.
     */
    protected $placeholder;

    /**
     * Terms name.
     */
    protected $terms_name;

    /**
     * Terms count.
     */
    protected $terms_count;

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
     * Terms type.
     */
    protected $terms_type;

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
        $taxonomy             = ( isset( $settings['filter_taxonomy'] ) ? wlpf_cast( $settings['filter_taxonomy'], 'key' ) : '' );
        $terms_include        = ( isset( $settings['filter_taxonomy_terms_include'] ) ? wlpf_cast( $settings['filter_taxonomy_terms_include'], 'text' ) : '' );
        $terms_exclude        = ( isset( $settings['filter_taxonomy_terms_exclude'] ) ? wlpf_cast( $settings['filter_taxonomy_terms_exclude'], 'text' ) : '' );
        $orderby              = ( isset( $settings['filter_orderby'] ) ? wlpf_cast( $settings['filter_orderby'], 'key' ) : '' );
        $order                = ( isset( $settings['filter_order'] ) ? wlpf_cast( $settings['filter_order'], 'key' ) : '' );
        $children_terms       = ( isset( $settings['filter_children_terms'] ) ? wlpf_cast( $settings['filter_children_terms'], 'selectbool' ) : true );
        $terms_hierarchy      = ( isset( $settings['filter_terms_hierarchy'] ) ? wlpf_cast( $settings['filter_terms_hierarchy'], 'selectbool' ) : true );
        $hide_empty_terms     = ( isset( $settings['filter_hide_empty_terms'] ) ? wlpf_cast( $settings['filter_hide_empty_terms'], 'selectbool' ) : true );
        $with_children_terms  = ( isset( $settings['filter_with_children_terms'] ) ? wlpf_cast( $settings['filter_with_children_terms'], 'selectbool' ) : true );
        $terms_operator       = ( isset( $settings['filter_terms_operator'] ) ? wlpf_cast( $settings['filter_terms_operator'], 'key' ) : 'in' );
        $field_type           = ( isset( $settings['filter_field_type'] ) ? wlpf_cast( $settings['filter_field_type'], 'key' ) : 'checkbox' );
        $placeholder          = ( isset( $settings['filter_select_placeholder'] ) ? wlpf_cast( $settings['filter_select_placeholder'], 'text' ) : '' );
        $terms_name           = ( isset( $settings['filter_terms_name'] ) ? wlpf_cast( $settings['filter_terms_name'], 'selectbool' ) : true );
        $terms_count          = ( isset( $settings['filter_terms_count'] ) ? wlpf_cast( $settings['filter_terms_count'], 'selectbool' ) : false );
        $apply                = ( isset( $settings['filter_apply_action'] ) ? wlpf_cast( $settings['filter_apply_action'], 'key' ) : '' );
        $apply_button_txt     = ( isset( $settings['filter_apply_action_button_txt'] ) ? wlpf_cast( $settings['filter_apply_action_button_txt'], 'text' ) : '' );
        $clear                = ( isset( $settings['filter_clear_action'] ) ? wlpf_cast( $settings['filter_clear_action'], 'key' ) : '' );
        $clear_button_txt     = ( isset( $settings['filter_clear_action_button_txt'] ) ? wlpf_cast( $settings['filter_clear_action_button_txt'], 'text' ) : '' );
        $max_height           = ( isset( $settings['filter_max_height'] ) ? wlpf_cast( $settings['filter_max_height'], 'absint' ) : 0 );
        $collapsible          = ( isset( $settings['filter_collapsible'] ) ? wlpf_cast( $settings['filter_collapsible'], 'selectbool' ) : true );
        $collapsed_by_default = ( isset( $settings['filter_collapsed_by_default'] ) ? wlpf_cast( $settings['filter_collapsed_by_default'], 'selectbool' ) : false );
        $terms_type           = ( isset( $settings['filter_terms_type'] ) ? wlpf_cast( $settings['filter_terms_type'], 'key' ) : '' );
        $unique_id            = ( isset( $settings['filter_unique_id'] ) ? wlpf_cast( $settings['filter_unique_id'], 'absint' ) : 0 );

        $terms_include = wlpf_string_to_array_of_id( $terms_include );
        $terms_exclude = wlpf_string_to_array_of_id( $terms_exclude );

        $terms_hierarchy  = ( ( true === $children_terms ) ? $terms_hierarchy : false );
        $orderby          = ( ( ! empty( $orderby ) ) ? $orderby : 'menu_order' );
        $order            = ( ( ! empty( $order ) ) ? $order : 'asc' );
        $terms_operator   = ( ( 'and' === $terms_operator ) ? 'AND' : ( ( 'not_in' === $terms_operator ) ? 'NOT IN' : 'IN' ) );
        $field_type       = ( ( ! empty( $field_type ) ) ? $field_type : 'checkbox' );
        $placeholder      = ( ( 0 < strlen( $placeholder ) ) ? $placeholder : esc_html__( 'Choose an option', 'woolentor-pro' ) );
        $apply            = ( ( 'auto' === $apply ) ? 'auto' : 'button' );
        $apply_button_txt = ( ( 0 < strlen( $apply_button_txt ) ) ? $apply_button_txt : esc_html__( 'Apply', 'woolentor-pro' ) );
        $clear            = ( ( 'none' === $clear ) ? 'none' : 'button' );
        $clear_button_txt = ( ( 0 < strlen( $clear_button_txt ) ) ? $clear_button_txt : esc_html__( 'Apply', 'woolentor-pro' ) );
        $group_apply      = ( ( 'auto' === $group_apply ) ? 'auto' : ( ( 'individual' === $group_apply ) ? 'individual' : 'button' ) );
        $group_clear      = ( ( 'none' === $group_clear ) ? 'none' : ( ( 'individual' === $group_clear ) ? 'individual' : 'button' ) );

        if ( empty( $taxonomy ) || empty( $unique_id ) ) {
            return;
        }

        $this->label                = $label;
        $this->active_label         = $active_label;
        $this->taxonomy             = $taxonomy;
        $this->terms_include        = $terms_include;
        $this->terms_exclude        = $terms_exclude;
        $this->orderby              = $orderby;
        $this->order                = $order;
        $this->children_terms       = $children_terms;
        $this->terms_hierarchy      = $terms_hierarchy;
        $this->hide_empty_terms     = $hide_empty_terms;
        $this->with_children_terms  = $with_children_terms;
        $this->terms_operator       = $terms_operator;
        $this->field_type           = $field_type;
        $this->placeholder          = $placeholder;
        $this->terms_name           = $terms_name;
        $this->terms_count          = $terms_count;
        $this->apply                = $apply;
        $this->apply_button_txt     = $apply_button_txt;
        $this->clear                = $clear;
        $this->clear_button_txt     = $clear_button_txt;
        $this->max_height           = $max_height;
        $this->collapsible          = $collapsible;
        $this->collapsed_by_default = $collapsed_by_default;
        $this->terms_type           = $terms_type;
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

        $classes .= ' wlpf-filter-wrap wlpf-taxonomy-filter';
        $classes .= ' wlpf-filter-field-type-' . $this->field_type;
        $classes .= ' wlpf-filter-terms-type-' . $this->terms_type;

        if ( 'select' === $this->field_type ) {
            $classes .= ' wlpf-filter-terms-name-yes';
        } else {
            $classes .= ' wlpf-filter-terms-name-' . ( ( true === $this->terms_name ) ? 'yes' : 'no' );
        }

        $classes .= ' wlpf-filter-terms-count-' . ( ( true === $this->terms_count ) ? 'yes' : 'no' );

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
        $attributes .= ' data-wlpf-taxonomy="' . esc_attr( $this->taxonomy ) . '"';

        if ( true === $this->with_children_terms ) {
            $attributes .= ' data-wlpf-with-children-terms="1"';
        } else {
            $attributes .= ' data-wlpf-with-children-terms="0"';
        }

        $attributes .= ' data-wlpf-available-terms="' . htmlspecialchars( wp_json_encode( $terms_data ) ) . '"';
        $attributes .= ' data-wlpf-selected-terms="' . htmlspecialchars( wp_json_encode( $selected_data ) ) . '"';
        $attributes .= ' data-wlpf-terms-operator="' . esc_attr( $this->terms_operator ) . '"';

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
     * Get terms IDs.
     */
    protected function get_terms_ids( $parent = 0, $ids = array() ) {
        $args = array(
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => $this->hide_empty_terms,
            'parent'     => $parent,
            'fields'     => 'ids',
        );

        $terms_ids = get_terms( $args );

        if ( is_array( $terms_ids ) && ! empty( $terms_ids ) ) {
            $ids = ( ( isset( $ids[ $parent ] ) ) ? $ids[ $parent ] : $ids );

            foreach ( $terms_ids as $term_id ) {
                $ids[ $term_id ] = array();

                $child_terms_ids = get_term_children( $term_id, $this->taxonomy );

                if ( is_array( $child_terms_ids ) && ! empty( $child_terms_ids ) ) {
                    $ids[ $term_id ] = $this->get_terms_ids( $term_id, $ids );
                }
            }
        }

        return $ids;
    }

    /**
     * Clean terms IDs.
     */
    protected function clean_terms_ids( $terms_ids = array(), $ids = array(), $lap = 0 ) {
        $include = $this->terms_include;
        $exclude = $this->terms_exclude;

        $child_lap = $lap;

        if ( is_array( $terms_ids ) && ! empty( $terms_ids ) ) {
            foreach ( $terms_ids as $term_id => $child_terms_ids ) {
                if ( true === $this->children_terms ) {
                    if ( 0 === $lap ) {
                        if ( ( empty( $include ) || in_array( $term_id, $include ) ) && ( empty( $exclude ) || ! in_array( $term_id, $exclude ) ) ) {
                            $ids[] = $term_id;
                            $child_lap = ( $child_lap + 1 );
                        } else {
                            $parent_terms_ids = wlpf_get_parent_terms_ids( $term_id, $this->taxonomy );

                            if ( is_array( $parent_terms_ids ) && ! empty( $parent_terms_ids ) ) {
                                foreach ( $parent_terms_ids as $parent_term_id ) {
                                    if ( ! empty( $exclude ) && in_array( $parent_term_id, $exclude ) ) {
                                        break;
                                    } elseif ( ! empty( $include ) && in_array( $parent_term_id, $include ) ) {
                                        $ids[] = $term_id;
                                        $child_lap = ( $child_lap + 1 );
                                        break;
                                    }
                                }
                            }
                        }
                    } else {
                        if ( empty( $exclude ) || ! in_array( $term_id, $exclude ) ) {
                            $ids[] = $term_id;
                        }
                    }
                } else {
                    if ( ( 0 === $lap ) && ( empty( $include ) || in_array( $term_id, $include ) ) && ( empty( $exclude ) || ! in_array( $term_id, $exclude ) ) ) {
                        $ids[] = $term_id;
                        $child_lap = ( $child_lap + 1 );
                    }
                }

                if ( is_array( $child_terms_ids ) && ! empty( $child_terms_ids ) ) {
                    $ids = $this->clean_terms_ids( $child_terms_ids, $ids, $child_lap );
                }
            }
        }

        return $ids;
    }

    /**
     * Get terms data.
     */
    protected function get_terms_data( $terms_ids = array() ) {
        $terms_data = array();

        $args = array(
            'taxonomy'   => $this->taxonomy,
            'orderby'    => $this->orderby,
            'order'      => $this->order,
            'hide_empty' => $this->hide_empty_terms,
        );

        $terms = get_terms( $args );

        if ( is_array( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( is_object( $term ) ) {
                    $term_data = array();

                    $id = ( isset( $term->term_id ) ? wlpf_cast( $term->term_id, 'absint' ) : 0 );

                    if ( ! in_array( $id, $terms_ids, true ) ) {
                        continue;
                    }

                    $parent = ( isset( $term->parent ) ? wlpf_cast( $term->parent, 'absint' ) : 0 );
                    $slug   = ( isset( $term->slug ) ? wlpf_cast( $term->slug, 'key' ) : '' );
                    $name   = ( isset( $term->name ) ? wlpf_cast( $term->name, 'text' ) : '' );
                    $count  = ( isset( $term->count ) ? wlpf_cast( $term->count, 'absint' ) : 0 );

                    $term_data = array(
                        'id'     => $id,
                        'parent' => $parent,
                        'slug'   => $slug,
                        'name'   => $name,
                        'count'  => $count,
                    );

                    if ( 'color' === $this->terms_type ) {
                        $key = 'filter_' . $this->taxonomy . '_term_' . $id . '_color';
                        $color = ( isset( $this->settings[ $key ] ) ? wlpf_cast( $this->settings[ $key ], 'text' ) : '' );

                        $term_data['color'] = $color;
                    } elseif ( 'image' === $this->terms_type ) {
                        $key = 'filter_' . $this->taxonomy . '_term_' . $id . '_image';
                        $image = ( isset( $this->settings[ $key ] ) ? wlpf_cast( $this->settings[ $key ], 'url' ) : '' );

                        $term_data['image'] = $image;
                    }

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
        $data = \WLPF\Frontend\Selected::get_data( 'taxonomy' );
        $tax_filter = ( isset( $data['tax_filter'] ) ? wlpf_cast( $data['tax_filter'], 'array' ) : array() );
        $taxonomy = ( isset( $tax_filter[ $this->taxonomy ] ) ? wlpf_cast( $tax_filter[ $this->taxonomy ], 'array' ) : array() );

        $terms = ( isset( $taxonomy['terms'] ) ? wlpf_cast( $taxonomy['terms'], 'array' ) : array() );
        $terms_info = ( isset( $taxonomy['terms_info'] ) ? wlpf_cast( $taxonomy['terms_info'], 'array' ) : array() );

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
    protected function get_list_content( $terms_data = array(), $selected_data = array(), $deps = 0 ) {
        $list_content = '';

        if ( is_array( $terms_data ) && ! empty( $terms_data ) ) {
            $deps = absint( $deps );

            foreach ( $terms_data as $term_data ) {
                $id     = ( isset( $term_data['id'] ) ? wlpf_cast( $term_data['id'], 'absint' ) : 0 );
                $parent = ( isset( $term_data['parent'] ) ? wlpf_cast( $term_data['parent'], 'absint' ) : 0 );
                $slug   = ( isset( $term_data['slug'] ) ? wlpf_cast( $term_data['slug'], 'key' ) : '' );
                $name   = ( isset( $term_data['name'] ) ? wlpf_cast( $term_data['name'], 'text' ) : '' );
                $count  = ( isset( $term_data['count'] ) ? wlpf_cast( $term_data['count'], 'absint' ) : 0 );
                $color  = ( isset( $term_data['color'] ) ? wlpf_cast( $term_data['color'], 'text' ) : '' );
                $image  = ( isset( $term_data['image'] ) ? wlpf_cast( $term_data['image'], 'url' ) : '' );

                $box_style = '';
                $child_content = '';

                if ( 'color' === $this->terms_type ) {
                    $box_style = sprintf( 'background-color: %1$s', $color );
                } elseif ( 'image' === $this->terms_type ) {
                    $box_style = sprintf( 'background-image: url(%1$s)', $image );
                }

                if ( true === $this->terms_hierarchy ) {
                    if ( $deps !== $parent ) {
                        continue;
                    }

                    if ( true === $this->terms_hierarchy ) {
                        $child_content = $this->get_list_content( $terms_data, $selected_data, $id );
                    }
                }

                $list_content .= '<li class="wlpf-term-item">';
                $list_content .= '<span class="wlpf-term-content">';
                $list_content .= '<label class="wlpf-term-label">';
                $list_content .= '<span class="wlpf-term-input">';

                if ( isset( $selected_data[ $id ] ) ) {
                    $list_content .= '<input class="wlpf-term-field" type="' . $this->field_type . '" name="wlpf-' . $this->taxonomy . '" value="' . $id . '" checked="checked">';
                } else {
                    $list_content .= '<input class="wlpf-term-field" type="' . $this->field_type . '" name="wlpf-' . $this->taxonomy . '" value="' . $id . '">';
                }

                $list_content .= '<span class="wlpf-term-box" style="' . $box_style . '"></span>';
                $list_content .= '</span>';

                if ( true === $this->terms_name ) {
                    $list_content .= '<span class="wlpf-term-info">';
                    $list_content .= '<span class="wlpf-term-name">' . $name . '</span>';

                    if ( true === $this->terms_count ) {
                        $list_content .= '<span class="wlpf-term-count">' . sprintf( '(%1$s)', $count ) . '</span>';
                    }

                    $list_content .= '</span>';
                }

                $list_content .= '</label>';
                $list_content .= '</span>';

                if ( ! empty( $child_content ) && ( true === $this->terms_name ) ) {
                    $list_content .= '<ul class="wlpf-term-children">' . $child_content . '</ul>';
                }

                $list_content .= '</li>';

                if ( ! empty( $child_content ) && ( false === $this->terms_name ) ) {
                    $list_content .= $child_content;
                }
            }
        }

        return $list_content;
    }

    /**
     * Get select content.
     */
    protected function get_select_content( $terms_data = array(), $selected_data = array(), $deps = 0, $nbsp = 0 ) {
        $select_content = '';

        if ( is_array( $terms_data ) && ! empty( $terms_data ) ) {
            $deps = absint( $deps );

            foreach ( $terms_data as $term_data ) {
                $id     = ( isset( $term_data['id'] ) ? wlpf_cast( $term_data['id'], 'absint' ) : 0 );
                $parent = ( isset( $term_data['parent'] ) ? wlpf_cast( $term_data['parent'], 'absint' ) : 0 );
                $name   = ( isset( $term_data['name'] ) ? wlpf_cast( $term_data['name'], 'text' ) : '' );
                $count  = ( isset( $term_data['count'] ) ? wlpf_cast( $term_data['count'], 'absint' ) : 0 );

                $child_content = '';

                if ( true === $this->terms_hierarchy ) {
                    if ( $deps !== $parent ) {
                        continue;
                    }

                    if ( true === $this->terms_hierarchy ) {
                        $child_content = $this->get_select_content( $terms_data, $selected_data, $id, ( $nbsp + 1 ) );
                    }
                }

                $option = '';

                for ( $i = 0; $i < $nbsp; $i++ ) {
                    $option .= '&nbsp;';
                }

                if ( true === $this->terms_count ) {
                    $option .= sprintf( '%1$s (%2$s)', $name, $count );
                } else {
                    $option .= $name;
                }

                if ( isset( $selected_data[ $id ] ) ) {
                    $select_content .= '<option class="wlpf-term-item" value="' . $id . '" selected="selected">' . $option . '</option>';
                } else {
                    $select_content .= '<option class="wlpf-term-item" value="' . $id . '">' . $option . '</option>';
                }

                if ( ! empty( $child_content ) ) {
                    $select_content .= $child_content;
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

        $fixed_args = wlpf_get_fixed_filter_args();
        $fixed_taxonomy = ( isset( $fixed_args['taxonomy'] ) ? wlpf_cast( $fixed_args['taxonomy'], 'key' ) : '' );
        $fixed_taxonomy_term_id = ( isset( $fixed_args['taxonomy_term_id'] ) ? wlpf_cast( $fixed_args['taxonomy_term_id'], 'absint' ) : 0 );

        $parent = ( ( $fixed_taxonomy === $this->taxonomy ) ? $fixed_taxonomy_term_id : 0 );

        $terms_ids = $this->get_terms_ids( $parent );
        $terms_ids = $this->clean_terms_ids( $terms_ids );

        $terms_data = $this->get_terms_data( $terms_ids );
        $selected_data = $this->get_selected_data();

        $fixed_args = wlpf_get_fixed_filter_args();

        $classes = $this->get_classes();
        $attributes = $this->get_attributes( $terms_data, $selected_data );

        $content_attributes = $this->get_content_attributes();

        if ( ( 'checkbox' === $this->field_type ) || ( 'radio' === $this->field_type ) ) {
            $list_content = $this->get_list_content( $terms_data, $selected_data, $parent );
        } elseif ( 'select' === $this->field_type ) {
            $select_content = $this->get_select_content( $terms_data, $selected_data, $parent );

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