<?php
/**
 * Group.
 */

namespace WLOPTF\Field;

/**
 * Class.
 */
class Group {

    /**
     * ID.
     */
    protected $id;

    /**
     * Name.
     */
    protected $name;

    /**
     * Value.
     */
    protected $value;

    /**
     * Fields.
     */
    protected $fields;

    /**
     * Serial.
     */
    protected $serial;

    /**
     * Button.
     */
    protected $button;

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
     * Items.
     */
    protected $items;

    /**
     * Sample.
     */
    protected $sample;

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
     * Constructor.
     */
    public function __construct( $args = array() ) {
        if ( ! is_array( $args ) ) {
            return;
        }

        $args = wp_parse_args( $args, array(
            'id'         => '',
            'default'    => array(),
            'class'      => '',
            'attributes' => '',
            'base_name'  => '',
            'base_data'  => array(),
        ) );

        $id         = ( isset( $args['id'] ) ? wloptf_cast( $args['id'], 'key' ) : '' );
        $fields     = ( isset( $args['fields'] ) ? wloptf_cast( $args['fields'], 'array' ) : '' );
        $serial     = ( isset( $args['serial'] ) ? wloptf_cast( $args['serial'], 'bool' ) : true );
        $button     = ( isset( $args['button'] ) ? wloptf_cast( $args['button'], 'text' ) : '' );
        $default    = ( isset( $args['default'] ) ? wloptf_cast( $args['default'], 'array' ) : '' );
        $class      = ( isset( $args['class'] ) ? wloptf_cast( $args['class'], 'text' ) : '' );
        $attributes = ( isset( $args['attributes'] ) ? wloptf_cast( $args['attributes'], 'array' ) : array() );
        $base_name  = ( isset( $args['base_name'] ) ? wloptf_cast( $args['base_name'], 'text' ) : '' );
        $base_data  = ( isset( $args['base_data'] ) ? wloptf_cast( $args['base_data'], 'array' ) : array() );

        $button = ( ( 0 < strlen( $button ) ) ? $button : esc_html__( 'Add New Item', 'woolentor-pro' ) );

        if ( empty( $id ) ) {
            return;
        }

        $this->id         = $id;
        $this->fields     = $fields;
        $this->serial     = $serial;
        $this->button     = $button;
        $this->default    = $default;
        $this->class      = $class;
        $this->attributes = $attributes;
        $this->base_name  = $base_name;
        $this->base_data  = $base_data;
        $this->args       = $args;

        $this->prepare_name();
        $this->prepare_value();
        $this->prepare_items();
        $this->prepare_sample();

        $this->render_field();
    }

    /**
     * Prepare name.
     */
    protected function prepare_name() {
        $this->name = sprintf( '%1$s[%2$s]', $this->base_name, $this->id );
    }

    /**
     * Prepare value.
     */
    protected function prepare_value() {
        $this->value = ( isset( $this->base_data[ $this->id ] ) ? wloptf_cast( $this->base_data[ $this->id ], 'array' ) : $this->default );
    }

    /**
     * Prepare items.
     */
    protected function prepare_items() {
        $items = '';

        if ( ! empty( $this->value ) ) {
            $count = 0;

            $tbase_name = sprintf( '%1$s[%2$s]', $this->name, 'WLOPTF9999' );

            foreach ( $this->value as $meta_data ) {
                $base_name = sprintf( '%1$s[%2$s]', $this->name, $count );

                ob_start();
                ?>
                <div class="wloptf-group-item">
                    <div class="wloptf-group-item-heading wloptf-sort-handle">
                        <div class="wloptf-group-item-title">
                            <span class="wloptf-group-item-title-icon wloptf-icon"></span>
                            <?php
                            if ( true === $this->serial ) {
                                ?>
                                <span class="wloptf-group-item-title-serial"><?php echo esc_html( $count + 1 ); ?>.</span>
                                <?php
                            }
                            ?>
                            <span class="wloptf-group-item-title-text"></span>
                        </div>
                        <div class="wloptf-group-item-controls">
                            <span class="wloptf-move wloptf-icon wloptf-icon-move"></span>
                            <span class="wloptf-clone wloptf-icon wloptf-icon-clone"></span>
                            <span class="wloptf-remove wloptf-icon wloptf-icon-times"></span>
                        </div>
                    </div>
                    <div class="wloptf-group-item-content">
                        <div class="wloptf-group-item-fields">
                            <?php
                            foreach ( $this->fields as $field ) {
                                $args = array_merge( $field, array(
                                    'base_name' => $base_name,
                                    'base_data' => $meta_data,
                                    'tbase_name' => $tbase_name,
                                ) );

                                \WLOPTF\Field::instance( $args, true, false );
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                $item = ob_get_clean();
                $item = wloptf_clean_html( $item );

                $items .= $item;

                $count++;
            }
        }

        $this->items = $items;
    }

    /**
     * Prepare sample.
     */
    protected function prepare_sample() {
        $sample = '';

        $base_name = sprintf( '%1$s[%2$s]', $this->name, 'WLOPTF9999' );
        $tbase_name = sprintf( '%1$s[%2$s]', $this->name, 'WLOPTF9999' );

        ob_start();
        ?>
        <div class="wloptf-group-item">
            <div class="wloptf-group-item-heading wloptf-sort-handle">
                <div class="wloptf-group-item-title">
                    <span class="wloptf-group-item-title-icon wloptf-icon"></span>
                    <?php
                    if ( true === $this->serial ) {
                        ?>
                        <span class="wloptf-group-item-title-serial"></span>
                        <?php
                    }
                    ?>
                    <span class="wloptf-group-item-title-text"></span>
                </div>
                <div class="wloptf-group-item-controls">
                    <span class="wloptf-move wloptf-icon wloptf-icon-move"></span>
                    <span class="wloptf-clone wloptf-icon wloptf-icon-clone"></span>
                    <span class="wloptf-remove wloptf-icon wloptf-icon-times"></span>
                </div>
            </div>
            <div class="wloptf-group-item-content">
                <div class="wloptf-group-item-fields">
                    <?php
                    foreach ( $this->fields as $field ) {
                        $args = array_merge( $field, array(
                            'base_name' => $base_name,
                            'base_data' => array(),
                            'tbase_name' => $tbase_name,
                        ) );

                        \WLOPTF\Field::instance( $args, false, false );
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        $sample = ob_get_clean();
        $sample = wloptf_clean_html( $sample );

        $this->sample = $sample;
    }

    /**
     * Get attributes.
     */
    protected function get_attributes() {
        $atts = '';

        $class = $this->class;
        $attrs = $this->attributes;

        $class = ( ( 0 < strlen( $class ) ) ? ( 'wloptf-group-wrapper ' . $class ) : 'wloptf-group-wrapper' );
        $atts .= ( ( 0 < strlen( $class ) && ! isset( $attrs['class'] ) ) ? sprintf( 'class="%1$s"', $class ) : '' );

        foreach ( $attrs as $attr_key => $attr_value ) {
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
        $add_attr = ( ! empty( $attrs ) ? ( ' ' . $attrs ) : '' );
        ?>
        <div <?php echo wp_kses_data( trim( $add_attr ) ); ?>>
            <div class="wloptf-group-sample"><?php echo $this->sample; ?></div>
            <div class="wloptf-group-items">
                <div class="wloptf-group-items-content wloptf-sortable"><?php echo $this->items; ?></div>
                <div class="wloptf-group-items-controls">
                    <button class="button wloptf-add"><span class="wloptf-icon wloptf-icon-plus"></span><?php echo esc_html( $this->button ); ?></button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Instance.
     */
    public static function instance( $args = array() ) {
        new self( $args );
    }

}