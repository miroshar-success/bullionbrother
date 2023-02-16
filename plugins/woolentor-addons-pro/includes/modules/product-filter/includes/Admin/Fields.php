<?php
/**
 * Fields.
 */

namespace WLPF\Admin;

/**
 * Class.
 */
class Fields {

    /**
     * Constructor.
     */
    public function __construct() {
        add_filter( 'woolentor_pro_product_filter_fields', array( $this, 'setting_fields' ) );
    }

    /**
     * Setting fields.
     */
    public function setting_fields() {
        $fields = array(
            array(
                'name'    => 'enable',
                'label'   => esc_html__( 'Enable / Disable', 'woolentor-pro' ),
                'desc'    => esc_html__( 'You can enable / disable product filter from here.', 'woolentor-pro' ),
                'type'    => 'checkbox',
                'default' => 'off',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'   => 'filters',
                'label'  => esc_html__( 'Filters', 'woolentor-pro' ),
                'type'   => 'repeater',
                'fields' => $this->filter_fields(),
            ),
            array(
                'name'   => 'groups',
                'label'  => esc_html__( 'Groups', 'woolentor-pro' ),
                'type'   => 'repeater',
                'fields' => $this->group_fields(),
            ),
            array(
                'name'      => 'general_settings_title',
                'headding'  => esc_html__( 'General Settings', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
            ),
            array(
                'name'    => 'ajax_filter',
                'label'   => esc_html__( 'Ajax filter', 'woolentor-pro' ),
                'type'    => 'checkbox',
                'default' => 'on',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'add_ajax_query_args_to_url',
                'label'     => esc_html__( 'Add ajax query arguments to URL', 'woolentor-pro' ),
                'type'      => 'checkbox',
                'default'   => 'on',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'ajax_filter', '==', '1' ),
            ),
            array(
                'name'      => 'time_to_take_ajax_action',
                'label'     => esc_html__( 'Time to take ajax action (ms)', 'woolentor-pro' ),
                'type'      => 'number',
                'default'   => '500',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'ajax_filter', '==', '1' ),
            ),
            array(
                'name'      => 'time_to_take_none_ajax_action',
                'label'     => esc_html__( 'Time to take none ajax action (ms)', 'woolentor-pro' ),
                'type'      => 'number',
                'default'   => '1000',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'ajax_filter', '==', '0' ),
            ),
            array(
                'name'    => 'show_filter_arguments',
                'label'   => esc_html__( 'Show filter arguments', 'woolentor-pro' ),
                'type'    => 'checkbox',
                'default' => 'off',
                'class'   => 'woolentor-action-field-left wlpf-show-filter-arguments',
            ),
            array(
                'name'        => 'query_args_prefix',
                'label'       => esc_html__( 'Query arguments prefix', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => 'wlpf_',
                'default'     => 'wlpf_',
                'class'       => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'default_shop_and_product_archive_title',
                'headding'  => esc_html__( 'Default Shop & Product Archive', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
            ),
            array(
                'name'        => 'products_wrapper_selector',
                'label'       => esc_html__( 'Products wrapper selector', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => '.wlpf-products-wrap',
                'default'     => '.wlpf-products-wrap',
                'class'       => 'woolentor-action-field-left',
            ),
        );

        return $fields;
    }

    /**
     * Filter fields.
     */
    public function filter_fields() {
        $fields = array(
            array(
                'name'  => 'filter_shortcode',
                'label' => esc_html__( 'Shortcode', 'woolentor-pro' ),
                'type'  => 'text',
                'class' => 'woolentor-action-field-left wlpf-filter-shortcode wlpf-dynamic-shortcode',
            ),
            array(
                'name'  => 'filter_label',
                'label' => esc_html__( 'Label', 'woolentor-pro' ),
                'type'  => 'text',
                'class' => 'woolentor-action-field-left wlpf-filter-label wlpf-dynamic-label',
            ),
            array(
                'name'    => 'filter_element',
                'label'   => esc_html__( 'Element', 'woolentor-pro' ),
                'type'    => 'select',
                'options' => array(
                    'taxonomy'  => esc_html__( 'Taxonomy', 'woolentor-pro' ),
                    'attribute' => esc_html__( 'Attribute', 'woolentor-pro' ),
                    'author'    => esc_html__( 'Author (vendor)', 'woolentor-pro' ),
                    'price'     => esc_html__( 'Price range', 'woolentor-pro' ),
                    'search'    => esc_html__( 'Search input', 'woolentor-pro' ),
                    'sorting'   => esc_html__( 'Sorting', 'woolentor-pro' ),
                ),
                'default' => 'taxonomy',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'filter_taxonomy_options',
                'headding'  => esc_html__( 'Taxonomy options', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
                'condition' => array( 'filter_element', '==', 'taxonomy' ),
            ),
            array(
                'name'      => 'filter_attribute_options',
                'headding'  => esc_html__( 'Attribute options', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
                'condition' => array( 'filter_element', '==', 'attribute' ),
            ),
            array(
                'name'      => 'filter_author_options',
                'headding'  => esc_html__( 'Author (vendor) options', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
                'condition' => array( 'filter_element', '==', 'author' ),
            ),
            array(
                'name'      => 'filter_price_options',
                'headding'  => esc_html__( 'Price options', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
                'condition' => array( 'filter_element', '==', 'price' ),
            ),
            array(
                'name'      => 'filter_search_options',
                'headding'  => esc_html__( 'Search options', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
                'condition' => array( 'filter_element', '==', 'search' ),
            ),
            array(
                'name'      => 'filter_sorting_options',
                'headding'  => esc_html__( 'Sorting options', 'woolentor-pro' ),
                'type'      => 'title',
                'size'      => 'margin_0 regular',
                'class'     => 'element_section_title_area',
                'condition' => array( 'filter_element', '==', 'sorting' ),
            ),
            array(
                'name'      => 'filter_taxonomy',
                'label'     => esc_html__( 'Taxonomy', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => wlpf_get_product_taxonomies( 'product', true ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'taxonomy' ),
            ),
            array(
                'name'        => 'filter_taxonomy_terms_include',
                'label'       => esc_html__( 'Terms inlcudes', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Comma separated IDs', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element', '==', 'taxonomy' ),
            ),
            array(
                'name'        => 'filter_taxonomy_terms_exclude',
                'label'       => esc_html__( 'Terms exlcudes', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Comma separated IDs', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element', '==', 'taxonomy' ),
            ),
            array(
                'name'       => 'filter_attribute',
                'label'      => esc_html__( 'Attribute', 'woolentor-pro' ),
                'type'       => 'select',
                'options'    => wlpf_get_product_attributes(),
                'class'      => 'woolentor-action-field-left',
                'condition'  => array( 'filter_element', '==', 'attribute' ),
            ),
            array(
                'name'        => 'filter_attribute_terms_include',
                'label'       => esc_html__( 'Terms inlcudes', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Comma separated IDs', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element', '==', 'attribute' ),
            ),
            array(
                'name'        => 'filter_attribute_terms_exclude',
                'label'       => esc_html__( 'Terms exlcudes', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Comma separated IDs', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element', '==', 'attribute' ),
            ),
            array(
                'name'      => 'filter_terms_operator',
                'label'     => esc_html__( 'Terms operator', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'in'     => esc_html__( 'IN', 'woolentor-pro' ),
                    'not_in' => esc_html__( 'NOT IN', 'woolentor-pro' ),
                    'and'    => esc_html__( 'AND', 'woolentor-pro' ),
                ),
                'default'   => 'in',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', 'any', 'taxonomy,attribute' ),
            ),
            array(
                'name'        => 'filter_authors_include',
                'label'       => esc_html__( 'Author inlcudes', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Comma separated IDs', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element', '==', 'author' ),
            ),
            array(
                'name'        => 'filter_authors_exclude',
                'label'       => esc_html__( 'Author exlcudes', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Comma separated IDs', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element', '==', 'author' ),
            ),
            array(
                'name'      => 'filter_sortings_include',
                'label'     => esc_html__( 'Sortings inlcudes', 'woolentor-pro' ),
                'type'      => 'multiselect',
                'options'   => wlpf_get_sorting_options(),
                'default'   => wlpf_get_sorting_options( 'key' ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'sorting' ),
            ),
            array(
                'name'      => 'filter_orderby',
                'label'     => esc_html__( 'Orderby', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => wlpf_get_terms_orderby_options(),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', 'any', 'taxonomy,attribute' ),
            ),
            array(
                'name'      => 'filter_author_orderby',
                'label'     => esc_html__( 'Orderby', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => wlpf_get_author_orderby_options(),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'author' ),
            ),
            array(
                'name'      => 'filter_order',
                'label'     => esc_html__( 'Order', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'asc'  => esc_html__( 'Ascending', 'woolentor-pro' ),
                    'desc' => esc_html__( 'Descending', 'woolentor-pro' ),
                ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', 'any', 'taxonomy,attribute,author' ),
            ),
            array(
                'name'      => 'filter_children_terms',
                'label'     => esc_html__( 'Show children terms', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'on',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'taxonomy' ),
            ),
            array(
                'name'      => 'filter_terms_hierarchy',
                'label'     => esc_html__( 'Terms hierarchy', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'on',
                'class'     => 'woolentor-action-field-left',
                'condition' => array(
                    array( 'filter_element', '==', 'taxonomy' ),
                    array( 'filter_children_terms', '==', 'on' ),
                ),
            ),
            array(
                'name'      => 'filter_hide_empty_terms',
                'label'     => esc_html__( 'Hide empty terms', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'on',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', 'any', 'taxonomy,attribute' ),
            ),
            array(
                'name'      => 'filter_with_children_terms',
                'label'     => esc_html__( 'Filter with children terms', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'on',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'taxonomy' ),
            ),
            array(
                'name'      => 'filter_field_type',
                'label'     => esc_html__( 'Field type', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'checkbox' => esc_html__( 'Checkbox', 'woolentor-pro' ),
                    'radio'    => esc_html__( 'Radio', 'woolentor-pro' ),
                    'select'   => esc_html__( 'Select', 'woolentor-pro' ),
                ),
                'default'   => 'checkbox',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', 'any', 'taxonomy,attribute,author' ),
            ),
            array(
                'name'      => 'filter_sorting_field_type',
                'label'     => esc_html__( 'Field type', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'radio'  => esc_html__( 'Radio', 'woolentor-pro' ),
                    'select' => esc_html__( 'Select', 'woolentor-pro' ),
                ),
                'default'   => 'radio',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'sorting' ),
            ),
            array(
                'name'        => 'filter_select_placeholder',
                'label'       => esc_html__( 'Select placeholder', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Choose an option', 'woolentor-pro' ),
                'default'     => esc_html__( 'Choose an option', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element|filter_field_type', 'any|==', 'taxonomy,attribute,author|select' ),
            ),
            array(
                'name'        => 'filter_sorting_select_placeholder',
                'label'       => esc_html__( 'Select placeholder', 'woolentor-pro' ),
                'type'        => 'text',
                'placeholder' => esc_html__( 'Choose an option', 'woolentor-pro' ),
                'default'     => esc_html__( 'Choose an option', 'woolentor-pro' ),
                'class'       => 'woolentor-action-field-left',
                'condition'   => array( 'filter_element|filter_sorting_field_type', 'any|==', 'sorting|select' ),
            ),
            array(
                'name'      => 'filter_terms_name',
                'label'     => esc_html__( 'Show terms name', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'on',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element|filter_field_type', 'any|any', 'taxonomy,attribute|checkbox,radio' ),
            ),
            array(
                'name'      => 'filter_terms_count',
                'label'     => esc_html__( 'Show products count with terms name', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'off',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', 'any', 'taxonomy,attribute' ),
            ),
            array(
                'name'      => 'filter_authors_count',
                'label'     => esc_html__( 'Show products count with authors name', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'off',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'author' ),
            ),
            array(
                'name'      => 'filter_search_placeholder',
                'label'     => esc_html__( 'Placeholder', 'woolentor-pro' ),
                'type'      => 'text',
                'default'   => esc_html__( 'Search keyword', 'woolentor-pro' ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element', '==', 'search' ),
            ),
            array(
                'name'    => 'filter_apply_action',
                'label'   => esc_html__( 'Apply', 'woolentor-pro' ),
                'type'    => 'select',
                'options' => array(
                    'auto'   => esc_html__( 'Auto', 'woolentor-pro' ),
                    'button' => esc_html__( 'Button click', 'woolentor-pro' ),
                ),
                'default' => 'auto',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'filter_apply_action_button_txt',
                'label'     => esc_html__( 'Apply button text', 'woolentor-pro' ),
                'type'      => 'text',
                'default'   => esc_html__( 'Apply', 'woolentor-pro' ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_apply_action', '==', 'button' ),
            ),
            array(
                'name'    => 'filter_clear_action',
                'label'   => esc_html__( 'Clear', 'woolentor-pro' ),
                'type'    => 'select',
                'options' => array(
                    'none'   => esc_html__( 'Default', 'woolentor-pro' ),
                    'button' => esc_html__( 'Button click', 'woolentor-pro' ),
                ),
                'default' => 'auto',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'filter_clear_action_button_txt',
                'label'     => esc_html__( 'Clear button text', 'woolentor-pro' ),
                'type'      => 'text',
                'default'   => esc_html__( 'Clear', 'woolentor-pro' ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_clear_action', '==', 'button' ),
            ),
            array(
                'name'      => 'filter_max_height',
                'label'     => esc_html__( 'Maximum height (px)', 'woolentor-pro' ),
                'type'      => 'number',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element|filter_field_type', 'any|any', 'taxonomy,attribute,author|checkbox,radio' ),
            ),
            array(
                'name'      => 'filter_sorting_max_height',
                'label'     => esc_html__( 'Maximum height (px)', 'woolentor-pro' ),
                'type'      => 'number',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_element|filter_sorting_field_type', 'any|==', 'sorting|radio' ),
            ),
            array(
                'name'    => 'filter_collapsible',
                'label'   => esc_html__( 'Collapsible', 'woolentor-pro' ),
                'type'    => 'select',
                'options' => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default' => 'on',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'filter_collapsed_by_default',
                'label'     => esc_html__( 'Collapsed by default', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'off',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'filter_collapsible', '==', 'on' ),
            ),
            array(
                'name'      => 'filter_terms_type',
                'label'     => esc_html__( 'Terms type', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    ''      => esc_html__( 'Default', 'woolentor-pro' ),
                    'color' => esc_html__( 'Color', 'woolentor-pro' ),
                    'image' => esc_html__( 'Image', 'woolentor-pro' ),
                ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array(
                    array( 'filter_element', 'any', 'taxonomy,attribute' ),
                    array( 'filter_field_type', 'any', 'checkbox,radio' ),
                ),
            ),
        );

        $fields = $this->add_filter_terms_fields( $fields );

        $fields[] = array(
            'name'  => 'filter_unique_id',
            'label' => esc_html__( 'Unique ID', 'woolentor-pro' ),
            'type'  => 'text',
            'class' => 'woolentor-action-field-left wlpf-filter-unique-id wlpf-dynamic-unique-id',
        );

        return $fields;
    }

    /**
     * Group fields.
     */
    public function group_fields() {
        $fields = array(
            array(
                'name'  => 'group_shortcode',
                'label' => esc_html__( 'Shortcode', 'woolentor-pro' ),
                'type'  => 'text',
                'class' => 'woolentor-action-field-left wlpf-group-shortcode wlpf-dynamic-shortcode',
            ),
            array(
                'name'  => 'group_label',
                'label' => esc_html__( 'Label', 'woolentor-pro' ),
                'type'  => 'text',
                'class' => 'woolentor-action-field-left wlpf-group-label wlpf-dynamic-label',
            ),
            array(
                'name'    => 'group_filters',
                'label'   => esc_html__( 'Filters', 'woolentor-pro' ),
                'type'    => 'multiselect',
                'options' => wlpf_get_filters_list(),
                'class'   => 'woolentor-action-field-left wlpf-group-filters',
            ),
            array(
                'name'    => 'group_filters_label',
                'label'   => esc_html__( 'Filters label', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'on',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'    => 'group_apply_action',
                'label'   => esc_html__( 'Apply', 'woolentor-pro' ),
                'type'    => 'select',
                'options' => array(
                    'auto'       => esc_html__( 'Auto', 'woolentor-pro' ),
                    'button'     => esc_html__( 'Button click', 'woolentor-pro' ),
                    'individual' => esc_html__( 'Individual', 'woolentor-pro' ),
                ),
                'default' => 'button',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'group_apply_action_button_txt',
                'label'     => esc_html__( 'Apply button text', 'woolentor-pro' ),
                'type'      => 'text',
                'default'   => esc_html__( 'Apply All', 'woolentor-pro' ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'group_apply_action', '==', 'button' ),
            ),
            array(
                'name'      => 'group_apply_action_button_pos',
                'label'     => esc_html__( 'Apply button position', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'top'    => esc_html__( 'Top', 'woolentor-pro' ),
                    'bottom' => esc_html__( 'Bottom', 'woolentor-pro' ),
                    'both'   => esc_html__( 'Top & Bottom', 'woolentor-pro' ),
                ),
                'default'   => 'bottom',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'group_apply_action', '==', 'button' ),
            ),
            array(
                'name'    => 'group_clear_action',
                'label'   => esc_html__( 'Clear', 'woolentor-pro' ),
                'type'    => 'select',
                'options' => array(
                    'none'       => esc_html__( 'Default', 'woolentor-pro' ),
                    'button'     => esc_html__( 'Button click', 'woolentor-pro' ),
                    'individual' => esc_html__( 'Individual', 'woolentor-pro' ),
                ),
                'default' => 'button',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'group_clear_action_button_txt',
                'label'     => esc_html__( 'Clear button text', 'woolentor-pro' ),
                'type'      => 'text',
                'default'   => esc_html__( 'Clear All', 'woolentor-pro' ),
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'group_clear_action', '==', 'button' ),
            ),
            array(
                'name'      => 'group_clear_action_button_pos',
                'label'     => esc_html__( 'Clear button position', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'top'    => esc_html__( 'Top', 'woolentor-pro' ),
                    'bottom' => esc_html__( 'Bottom', 'woolentor-pro' ),
                    'both'   => esc_html__( 'Top & Bottom', 'woolentor-pro' ),
                ),
                'default'   => 'bottom',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'group_clear_action', '==', 'button' ),
            ),
            array(
                'name'  => 'group_max_height',
                'label' => esc_html__( 'Maximum height (px)', 'woolentor-pro' ),
                'type'  => 'number',
                'class' => 'woolentor-action-field-left',
            ),
            array(
                'name'    => 'group_collapsible',
                'label'   => esc_html__( 'Collapsible', 'woolentor-pro' ),
                'type'    => 'select',
                'options' => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default' => 'on',
                'class'   => 'woolentor-action-field-left',
            ),
            array(
                'name'      => 'group_collapsed_by_default',
                'label'     => esc_html__( 'Collapsed by default', 'woolentor-pro' ),
                'type'      => 'select',
                'options'   => array(
                    'on'  => esc_html__( 'Yes', 'woolentor-pro' ),
                    'off' => esc_html__( 'No', 'woolentor-pro' ),
                ),
                'default'   => 'off',
                'class'     => 'woolentor-action-field-left',
                'condition' => array( 'group_collapsible', '==', 'on' ),
            ),
            array(
                'name'  => 'group_unique_id',
                'label' => esc_html__( 'Unique ID', 'woolentor-pro' ),
                'type'  => 'text',
                'class' => 'woolentor-action-field-left wlpf-group-unique-id wlpf-dynamic-unique-id',
            ),
        );

        return $fields;
    }

    /**
     * Filter terms fields.
     */
    public function add_filter_terms_fields( $fields = array() ) {
        $taxonomies = wlpf_get_product_global_taxonomies_with_terms();

        if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy => $terms ) {
                $element = ( ( 'pa_' === substr( $taxonomy, 0, 3 ) ) ? 'attribute' : 'taxonomy' );

                if ( is_array( $terms ) && ! empty( $terms ) ) {
                    $fields[] = array(
                        'name'      => 'filter_' . $taxonomy . '_terms',
                        'headding'  => esc_html__( 'Configure terms', 'woolentor-pro' ),
                        'type'      => 'title',
                        'size'      => 'margin_0 regular',
                        'class'     => 'element_section_title_area',
                        'condition' => array(
                            array( 'filter_element', '==', $element ),
                            array( 'filter_field_type', 'any', 'checkbox,radio' ),
                            array( 'filter_terms_type', 'any', 'color,image' ),
                            array( 'filter_' . $element, '==', $taxonomy ),
                        ),
                    );

                    foreach ( $terms as $term_id => $term_name ) {
                        $fields[] = array(
                            'name'      => 'filter_' . $taxonomy . '_term_' . $term_id . '_color',
                            'label'     => $term_name,
                            'type'      => 'color',
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array(
                                array( 'filter_element', '==', $element ),
                                array( 'filter_field_type', 'any', 'checkbox,radio' ),
                                array( 'filter_terms_type', '==', 'color' ),
                                array( 'filter_' . $element, '==', $taxonomy ),
                            ),
                        );

                        $fields[] = array(
                            'name'      => 'filter_' . $taxonomy . '_term_' . $term_id . '_image',
                            'label'     => $term_name,
                            'type'      => 'image_upload',
                            'class'     => 'woolentor-action-field-left',
                            'condition' => array(
                                array( 'filter_element', '==', $element ),
                                array( 'filter_field_type', 'any', 'checkbox,radio' ),
                                array( 'filter_terms_type', '==', 'image' ),
                                array( 'filter_' . $element, '==', $taxonomy ),
                            ),
                        );
                    }
                }
            }
        }

        return $fields;
    }

}