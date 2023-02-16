<?php
/**
 * Select.
 */

namespace WLOPTF\Field;

/**
 * Class.
 */
class Select {

    /**
     * ID.
     */
    protected $id;

    /**
     * Placeholder.
     */
    protected $placeholder;

    /**
     * Ajax.
     */
    protected $ajax;

    /**
     * Multiple.
     */
    protected $multiple;

    /**
     * Options.
     */
    protected $options;

    /**
     * Query type.
     */
    protected $query_type;

    /**
     * Query args.
     */
    protected $query_args;

    /**
     * Name.
     */
    protected $name;

    /**
     * Temporary name.
     */
    protected $tname;

    /**
     * Value.
     */
    protected $value;

    /**
     * Default.
     */
    protected $default;

    /**
     * Class.
     */
    protected $class;

    /**
     * Attributes.
     */
    protected $attributes;

    /**
     * Base name.
     */
    protected $base_name;

    /**
     * Base data.
     */
    protected $base_data;

    /**
     * Args.
     */
    protected $args;

    /**
     * Store.
     */
    protected $store;

    /**
     * Constructor.
     */
    public function __construct( $args = array(), $store = true ) {
        if ( ! is_array( $args ) ) {
            return;
        }

        $args = wp_parse_args( $args, array(
            'id'          => '',
            'placeholder' => '',
            'ajax'        => '',
            'multiple'    => '',
            'options'     => array(),
            'query_type'  => '',
            'query_args'  => array(),
            'default'     => '',
            'class'       => '',
            'attributes'  => '',
            'base_name'   => '',
            'base_data'   => array(),
            'tbase_name'  => '',
        ) );

        $id          = ( isset( $args['id'] ) ? wloptf_cast( $args['id'], 'key' ) : '' );
        $placeholder = ( isset( $args['placeholder'] ) ? wloptf_cast( $args['placeholder'], 'text' ) : '' );
        $ajax        = ( isset( $args['ajax'] ) ? wloptf_cast( $args['ajax'], 'bool' ) : false );
        $multiple    = ( isset( $args['multiple'] ) ? wloptf_cast( $args['multiple'], 'bool' ) : false );
        $options     = ( isset( $args['options'] ) ? wloptf_cast( $args['options'], 'array' ) : array() );
        $query_type  = ( isset( $args['query_type'] ) ? wloptf_cast( $args['query_type'], 'key' ) : '' );
        $query_args  = ( isset( $args['query_args'] ) ? wloptf_cast( $args['query_args'], 'array' ) : array() );
        $class       = ( isset( $args['class'] ) ? wloptf_cast( $args['class'], 'text' ) : '' );
        $attributes  = ( isset( $args['attributes'] ) ? wloptf_cast( $args['attributes'], 'array' ) : array() );
        $base_name   = ( isset( $args['base_name'] ) ? wloptf_cast( $args['base_name'], 'text' ) : '' );
        $base_data   = ( isset( $args['base_data'] ) ? wloptf_cast( $args['base_data'], 'array' ) : array() );
        $tbase_name  = ( isset( $args['tbase_name'] ) ? wloptf_cast( $args['tbase_name'], 'text' ) : '' );

        $default = ( isset( $args['default'] ) ? $args['default'] : '' );
        $default = ( ( true === $multiple ) ? wloptf_cast( $default, 'array' ) : wloptf_cast( $default, 'text' ) );

        if ( ( 1 > strlen( $placeholder ) ) && ( true === $ajax ) ) {
            $placeholder = ( ( true === $multiple ) ? esc_html__( 'Select options', 'woolentor-pro' ) : esc_html__( 'Select an option', 'woolentor-pro' ) );
        }

        $store = wloptf_cast( $store, 'bool' );

        if ( empty( $id ) ) {
            return;
        }

        $this->id          = $id;
        $this->placeholder = $placeholder;
        $this->ajax        = $ajax;
        $this->multiple    = $multiple;
        $this->options     = $options;
        $this->query_type  = $query_type;
        $this->query_args  = $query_args;
        $this->default     = $default;
        $this->class       = $class;
        $this->attributes  = $attributes;
        $this->base_name   = $base_name;
        $this->base_data   = $base_data;
        $this->tbase_name  = $tbase_name;
        $this->args        = $args;
        $this->store       = $store;

        $this->prepare_name();
        $this->prepare_value();
        $this->prepare_options();

        if ( ( false === $this->ajax ) && empty( $this->options ) ) {
            $this->invalid_options();
        } else {
            $this->render_field();
        }
    }

    /**
     * Prepare name.
     */
    protected function prepare_name() {
        if ( true === $this->store ) {
            if ( true === $this->multiple ) {
                $this->name = sprintf( '%1$s[%2$s][]', $this->base_name, $this->id );
            } else {
                $this->name = sprintf( '%1$s[%2$s]', $this->base_name, $this->id );
            }
        }

        if ( ! empty( $this->tbase_name ) ) {
            if ( true === $this->multiple ) {
                $this->tname = sprintf( '%1$s[%2$s][]', $this->tbase_name, $this->id );
            } else {
                $this->tname = sprintf( '%1$s[%2$s]', $this->tbase_name, $this->id );
            }
        }
    }

    /**
     * Prepare value.
     */
    protected function prepare_value() {
        $value = ( isset( $this->base_data[ $this->id ] ) ? $this->base_data[ $this->id ] : $this->default );
        $value = ( ( true === $this->multiple ) ? wloptf_cast( $value, 'array' ) : wloptf_cast( $value, 'text' ) );

        $this->value = $value;
    }

    /**
     * Prepare options.
     */
    protected function prepare_options() {
        $options = array();

        if ( ! empty( $this->query_args ) ) {
            if ( ( false === $this->ajax ) || ( ! empty( $this->value ) ) ) {
                if ( 'taxonomy_term' === $this->query_type ) {
                    $query_args = wp_parse_args( $this->query_args, array(
                        'taxonomy' => 'category',
                        'hide_empty' => false,
                    ) );

                    if ( ( true === $this->ajax ) && ! empty( $this->value ) ) {
                        $query_args['include'] = ( is_array( $this->value ) ? $this->value : array( $this->value ) );
                    }

                    $terms = get_terms( $query_args );

                    if ( is_array( $terms ) && ! empty( $terms ) ) {
                        foreach ( $terms as $term ) {
                            if ( ! is_object( $term ) || empty( $term ) ) {
                                continue;
                            }

                            $id = ( isset( $term->term_id ) ? absint( $term->term_id ) : 0 );

                            $title = ( isset( $term->name ) ? sanitize_text_field( $term->name ) : '' );
                            $title = ( ( 0 < strlen( $title ) ) ? $title : esc_html__( 'Unnamed', 'woolentor-pro' ) );

                            if ( ! empty( $id ) ) {
                                $options[ $id ] = $title;
                            }
                        }
                    }
                } else {
                    $query_args = wp_parse_args( $this->query_args, array(
                        'post_type' => 'post',
                    ) );

                    if ( ( true === $this->ajax ) && ! empty( $this->value ) ) {
                        $query_args['post__in'] = ( is_array( $this->value ) ? $this->value : array( $this->value ) );
                    }

                    $query_args['posts_per_page'] = -1;

                    $posts = get_posts( $query_args );

                    if ( is_array( $posts ) && ! empty( $posts ) ) {
                        foreach ( $posts as $post ) {
                            if ( ! is_object( $post ) || empty( $post ) ) {
                                continue;
                            }

                            $id = ( isset( $post->ID ) ? absint( $post->ID ) : 0 );

                            $title = ( isset( $post->post_title ) ? sanitize_text_field( $post->post_title ) : '' );
                            $title = ( ( 0 < strlen( $title ) ) ? $title : esc_html__( 'Unnamed', 'woolentor-pro' ) );

                            if ( ! empty( $id ) ) {
                                $options[ $id ] = $title;
                            }
                        }
                    }
                }
            }
        } else {
            $options = $this->options;
        }

        if ( ( 0 < strlen( $this->placeholder ) ) && ( false === $this->ajax ) && ( false === $this->multiple ) ) {
            $options = array_merge( array( '' => $this->placeholder ), $options );
        }

        $this->options = $options;
    }

    /**
     * Get attributes.
     */
    protected function get_attributes() {
        $atts = '';

        $class = $this->class;
        $attrs = $this->attributes;

        if ( ( true === $this->ajax ) || ( true === $this->multiple ) ) {
            if ( true === $this->ajax ) {
                $attrs['data-wloptf-ajax'] = '1';
            }

            if ( true === $this->multiple ) {
                $attrs['multiple'] = '1';
                $attrs['data-wloptf-multiple'] = '1';
            }

            $attrs['data-wloptf-select2'] = '1';

            $class = trim( $class . ' ' . 'wloptf-select2' );
        }

        $attrs = array_merge( $attrs, array(
            'data-wloptf-placeholder' => $this->placeholder,
            'data-wloptf-options'     => htmlspecialchars( wp_json_encode( $this->options ) ),
            'data-wloptf-query-type'  => htmlspecialchars( $this->query_type ),
            'data-wloptf-query-args'  => htmlspecialchars( wp_json_encode( $this->query_args ) ),
        ) );

        $attrs['data-wloptf-value'] = ( is_array( $this->value ) ? htmlspecialchars( wp_json_encode( $this->value, JSON_FORCE_OBJECT ) ) : $this->value );

        $atts .= ( ( 0 < strlen( $class ) && ! isset( $attrs['class'] ) ) ? sprintf( 'class="%1$s"', $class ) : '' );

        foreach ( $attrs as $attr_key => $attr_value ) {
            $attr_key = wloptf_cast( $attr_key, 'text' );
            $attr_value = wloptf_cast( $attr_value, 'text' );

            if ( 'class' === $attr_key && 0 < strlen( $class ) ) {
                $attr_value = ( ( 0 < strlen( $attr_value ) ) ? ( $class . ' ' . $attr_value ) : $class );
            }

            $attr = sprintf( '%1$s="%2$s"', $attr_key, $attr_value );

            $atts .= ( ( 0 < strlen( $atts ) ) ? ( ' ' . $attr ) : $attr );
        }

        return $atts;
    }

    /**
     * Render field.
     */
    protected function render_field() {
        $attrs = $this->get_attributes();

        $add_attr = ( ! empty( $this->name ) ? ( ' name="' . esc_attr( $this->name ) . '"' ) : '' );
        $add_attr .= ( ! empty( $this->tname ) ? ( ' data-wloptf-tname="' . esc_attr( $this->tname ) . '"' ) : '' );
        $add_attr .= ( ! empty( $attrs ) ? ( ' ' . $attrs ) : '' );
        ?>
        <select <?php echo wp_kses_data( trim( $add_attr ) ); ?>>
            <?php
            foreach ( $this->options as $option_key => $option_title ) {
                $option_key   = ( isset( $option_key ) ? wloptf_cast( $option_key, 'text' ) : '' );

                $option_title = ( isset( $option_title ) ? wloptf_cast( $option_title, 'text' ) : '' );
                $option_title = ( 0 < strlen( $option_title ) ? $option_title : esc_html__( 'Unnamed', 'woolentor-pro' ) );

                if ( ( ( true === $this->multiple ) && in_array( $option_key, $this->value, true ) ) || ( $this->value === $option_key ) ) {
                    ?>
                    <option value="<?php echo esc_attr( $option_key ); ?>" selected="selected"><?php echo esc_html( $option_title ); ?></option>
                    <?php
                } else {
                    ?>
                    <option value="<?php echo esc_attr( $option_key ); ?>"><?php echo esc_html( $option_title ); ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }

    /**
     * Invalid options.
     */
    protected function invalid_options() {
        esc_html_e( 'Invalid options.', 'woolentor-pro' );
    }

    /**
     * Instance.
     */
    public static function instance( $args = array(), $store = true ) {
        new self( $args, $store );
    }

}