<?php
/**
 * Rules.
 */

namespace WLOPTF\Field;

/**
 * Class.
 */
class Rules {

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
     * Settings.
     */
    protected $settings;

    /**
     * Control.
     */
    protected $control;

    /**
     * Control name.
     */
    protected $control_name;

    /**
     * Control event.
     */
    protected $control_event;

    /**
     * Control value.
     */
    protected $control_value;

    /**
     * Value JSON.
     */
    protected $value_json;

    /**
     * Fields JSON.
     */
    protected $fields_json;

    /**
     * Settings JSON.
     */
    protected $settings_json;

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
        $settings   = ( isset( $args['settings'] ) ? wloptf_cast( $args['settings'], 'array' ) : array() );
        $control    = ( isset( $args['control'] ) ? wloptf_cast( $args['control'], 'array' ) : array() );
        $button     = ( isset( $args['button'] ) ? wloptf_cast( $args['button'], 'text' ) : '' );
        $default    = ( isset( $args['default'] ) ? wloptf_cast( $args['default'], 'array' ) : array() );
        $class      = ( isset( $args['class'] ) ? wloptf_cast( $args['class'], 'text' ) : '' );
        $attributes = ( isset( $args['attributes'] ) ? wloptf_cast( $args['attributes'], 'array' ) : array() );
        $base_name  = ( isset( $args['base_name'] ) ? wloptf_cast( $args['base_name'], 'text' ) : '' );
        $base_data  = ( isset( $args['base_data'] ) ? wloptf_cast( $args['base_data'], 'array' ) : array() );

        $button = ( ( 0 < strlen( $button ) ) ? $button : esc_html__( 'Add New Group', 'woolentor-pro' ) );

        if ( empty( $id ) ) {
            return;
        }

        $this->id         = $id;
        $this->settings   = $settings;
        $this->control    = $control;
        $this->button     = $button;
        $this->default    = $default;
        $this->class      = $class;
        $this->attributes = $attributes;
        $this->base_name  = $base_name;
        $this->base_data  = $base_data;
        $this->args       = $args;

        $this->prepare_name();
        $this->prepare_value();
        $this->prepare_settings();
        $this->prepare_control();
        $this->prepare_items();
        $this->prepare_sample();
        $this->prepare_value_json();
        $this->prepare_fields_json();
        $this->prepare_settings_json();

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
     * Prepare settings.
     */
    protected function prepare_settings() {
        $settings_args = array();

        $settings = $this->settings;
        $base_name = sprintf( '%1$s[%2$s][%3$s]', $this->name, 'WLOPTF8888', 'WLOPTF9999' );

        foreach ( $settings as $control_key => $control_args ) {
            $control_key  = wloptf_cast( $control_key, 'key' );
            $control_args = wloptf_cast( $control_args, 'array' );

            if ( empty( $control_key ) || empty( $control_args ) ) {
                continue;
            }

            $base_opts = array();

            foreach ( $control_args as $key => $args ) {
                $key  = wloptf_cast( $key, 'key' );
                $args = wloptf_cast( $args, 'array' );

                if ( empty( $key ) || empty( $args ) ) {
                    continue;
                }

                $title    = ( isset( $args['title'] ) ? wloptf_cast( $args['title'], 'text' ) : '' );
                $operator = ( isset( $args['operator'] ) ? wloptf_cast( $args['operator'], 'array' ) : array() );
                $value    = ( isset( $args['value'] ) ? wloptf_cast( $args['value'], 'array' ) : array() );

                if ( empty( $operator ) || empty( $value ) ) {
                    continue;
                }

                $title = ( 0 < strlen( $title ) ? $title : $key );

                $operator = array_merge( $operator, array(
                    'id'         => 'operator',
                    'base_name'  => $base_name,
                    'tbase_name' => $base_name,
                ) );

                $value = array_merge( $value, array(
                    'id'         => 'value',
                    'base_name'  => $base_name,
                    'tbase_name' => $base_name,
                ) );

                unset( $operator['ajax'] );
                unset( $operator['multiple'] );
                unset( $operator['placeholder'] );

                $base_opts[ $key ] = $title;

                $settings_args[ $control_key ]['deps'][ $key ] = array(
                    'operator' => $operator,
                    'value'    => $value,
                );
            }

            if ( ! empty( $base_opts ) ) {
                $base = array(
                    'id'         => 'base',
                    'type'       => 'select',
                    'options'    => $base_opts,
                    'base_name'  => $base_name,
                    'tbase_name' => $base_name,
                );

                $settings_args[ $control_key ]['base'] = $base;
            }
        }

        $this->settings = $settings_args;
    }

    /**
     * Prepare control.
     */
    protected function prepare_control() {
        $this->control_name  = ( isset( $this->control['name'] ) ? sanitize_text_field( $this->control['name'] ) : '' );
        $this->control_event = ( isset( $this->control['event'] ) ? sanitize_text_field( $this->control['event'] ) : '' );
        $this->control_value = ( isset( $this->control['value'] ) ? sanitize_text_field( $this->control['value'] ) : '' );
    }

    /**
     * Prepare items.
     */
    protected function prepare_items() {
        $items = '';

        $groups = ( is_array( $this->value ) ? $this->value : array() );

        $settings = ( is_array( $this->settings ) ? $this->settings : array() );
        $settings = ( ( isset( $settings[ $this->control_value ] ) && is_array( $settings[ $this->control_value ] ) ) ? $settings[ $this->control_value ] : array() );

        if ( ! empty( $groups ) ) {
            $g_count = 0;

            $tbase_name = sprintf( '%1$s[%2$s][%3$s]', $this->name, 'WLOPTF8888', 'WLOPTF9999' );

            foreach ( $groups as $group ) {
                $g_items = ( is_array( $group ) ? $group : array() );

                if ( ! empty( $g_items ) ) {
                    ob_start();
                    ?>
                    <div class="wloptf-rules-group">
                        <div class="wloptf-rules-group-devider"><span><?php esc_html_e( 'OR', 'woolentor-pro' ); ?></span></div>
                        <div class="wloptf-rules-group-content">
                            <div class="wloptf-rules-items">
                                <?php
                                $i_count = 0;

                                foreach ( $g_items as $g_item ) {
                                    $g_item = ( is_array( $g_item ) ? $g_item : array() );

                                    if ( ! empty( $g_item ) ) {
                                        $base_name = sprintf( '%1$s[%2$s][%3$s]', $this->name, $g_count, $i_count );

                                        $base_default = ( isset( $g_item['base'] ) ? wloptf_cast( $g_item['base'], 'text' ) : '' );
                                        $operator_default = ( isset( $g_item['operator'] ) ? wloptf_cast( $g_item['operator'], 'text' ) : '' );

                                        $value_default = ( isset( $g_item['value'] ) ? $g_item['value'] : '' );
                                        $value_default = ( is_array( $value_default ) ? wloptf_cast( $value_default, 'array' ) : wloptf_cast( $value_default, 'text' ) );

                                        $deps = ( isset( $settings['deps'] ) ? wloptf_cast( $settings['deps'], 'array' ) : array() );
                                        $args = ( isset( $deps[ $base_default ] ) ? wloptf_cast( $deps[ $base_default ], 'array' ) : array() );

                                        $base_args     = ( isset( $settings['base'] ) ? wloptf_cast( $settings['base'], 'array' ) : array() );
                                        $operator_args = ( isset( $args['operator'] ) ? wloptf_cast( $args['operator'], 'array' ) : array() );
                                        $value_args    = ( isset( $args['value'] ) ? wloptf_cast( $args['value'], 'array' ) : array() );

                                        $base_args = array_merge( $base_args, array(
                                            'default'    => $base_default,
                                            'base_name'  => $base_name,
                                            'tbase_name' => $tbase_name,
                                        ) );

                                        $operator_args = array_merge( $operator_args, array(
                                            'default'    => $operator_default,
                                            'base_name'  => $base_name,
                                            'tbase_name' => $tbase_name,
                                        ) );

                                        $value_args = array_merge( $value_args, array(
                                            'default'    => $value_default,
                                            'base_name'  => $base_name,
                                            'tbase_name' => $tbase_name,
                                        ) );
                                        ?>
                                        <div class="wloptf-rules-item">
                                            <div class="wloptf-rules-item-devider"><span><?php esc_html_e( 'AND', 'woolentor-pro' ); ?></span></div>
                                            <div class="wloptf-rules-item-content">
                                                <div class="wloptf-rules-item-fields">
                                                    <div class="wloptf-rules-item-base"><?php \WLOPTF\Field::instance( $base_args, true, false, false ); ?></div>
                                                    <div class="wloptf-rules-item-operator"><?php \WLOPTF\Field::instance( $operator_args, true, false, false ); ?></div>
                                                    <div class="wloptf-rules-item-value"><?php \WLOPTF\Field::instance( $value_args, true, false, false ); ?></div>
                                                </div>
                                                <div class="wloptf-rules-item-controls">
                                                    <button class="button wloptf-add"><span class="wloptf-icon wloptf-icon-insert"></span></button>
                                                    <button class="button wloptf-remove"><span class="wloptf-icon wloptf-icon-remove"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                    $i_count++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $item = ob_get_clean();
                    $item = wloptf_clean_html( $item );

                    $items .= $item;
                }

                $g_count++;
            }
        }

        $this->items = $items;
    }

    /**
     * Prepare sample.
     */
    protected function prepare_sample() {
        $sample = '';

        $settings = ( is_array( $this->settings ) ? $this->settings : array() );
        $settings = ( ( isset( $settings[ $this->control_value ] ) && is_array( $settings[ $this->control_value ] ) ) ? $settings[ $this->control_value ] : array() );

        $deps = ( isset( $settings['deps'] ) ? wloptf_cast( $settings['deps'], 'array' ) : array() );
        $deps = array_values( $deps );

        $args = ( isset( $deps[0] ) ? wloptf_cast( $deps[0], 'array' ) : array() );

        $base_args     = ( isset( $settings['base'] ) ? wloptf_cast( $settings['base'], 'array' ) : array() );
        $operator_args = ( isset( $args['operator'] ) ? wloptf_cast( $args['operator'], 'array' ) : array() );
        $value_args    = ( isset( $args['value'] ) ? wloptf_cast( $args['value'], 'array' ) : array() );

        if ( ! empty( $base_args ) && ! empty( $operator_args ) && ! empty( $value_args ) ) {
            ob_start();
            ?>
            <div class="wloptf-rules-group">
                <div class="wloptf-rules-group-devider"><span><?php esc_html_e( 'OR', 'woolentor-pro' ); ?></span></div>
                <div class="wloptf-rules-group-content">
                    <div class="wloptf-rules-items">
                        <div class="wloptf-rules-item">
                            <div class="wloptf-rules-item-devider"><span><?php esc_html_e( 'AND', 'woolentor-pro' ); ?></span></div>
                            <div class="wloptf-rules-item-content">
                                <div class="wloptf-rules-item-fields">
                                    <div class="wloptf-rules-item-base"><?php \WLOPTF\Field::instance( $base_args, false, false, false ); ?></div>
                                    <div class="wloptf-rules-item-operator"><?php \WLOPTF\Field::instance( $operator_args, false, false, false ); ?></div>
                                    <div class="wloptf-rules-item-value"><?php \WLOPTF\Field::instance( $value_args, false, false, false ); ?></div>
                                </div>
                                <div class="wloptf-rules-item-controls">
                                    <button class="button wloptf-add"><span class="wloptf-icon wloptf-icon-insert"></span></button>
                                    <button class="button wloptf-remove"><span class="wloptf-icon wloptf-icon-remove"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $sample = ob_get_clean();
            $sample = wloptf_clean_html( $sample );
        }

        $this->sample = $sample;
    }

    /**
     * Prepare value JSON.
     */
    protected function prepare_value_json() {
        $value = array();
        $ivalue = array();

        $groups = $this->value;

        if ( is_array( $groups ) && ! empty( $groups ) ) {
            foreach ( $groups as $iog => $items ) {
                $iog = (string) $iog;

                if ( is_array( $items ) && ! empty( $items ) ) {
                    foreach ( $items as $ioi => $item ) {
                        $ioi = (string) $ioi;

                        $item_base     = ( isset( $item['base'] ) ? wloptf_cast( $item['base'], 'text' ) : '' );
                        $item_operator = ( isset( $item['operator'] ) ? wloptf_cast( $item['operator'], 'text' ) : '' );

                        $item_value = ( isset( $item['value'] ) ? $item['value'] : '' );
                        $item_value = ( is_array( $item_value ) ? wloptf_cast( $item_value, 'array' ) : wloptf_cast( $item_value, 'text' ) );

                        $value[ $iog ][ $ioi ][ $item_base ] = array(
                            'operator' => $item_operator,
                            'value'    => $item_value,
                        );

                        $ivalue[ $iog ][ $ioi ][ $item_base ] = array(
                            'operator' => $item_operator,
                            'value'    => $item_value,
                        );
                    }
                }
            }
        }

        $value = ( ! empty( $value ) ? array( $this->control_value => $value ) : $value );
        $ivalue = ( ! empty( $ivalue ) ? array( $this->control_value => $ivalue ) : $ivalue );

        $this->value_json = ( is_array( $value ) ? htmlspecialchars( wp_json_encode( $value, JSON_FORCE_OBJECT ) ) : '' );
        $this->ivalue_json = ( is_array( $ivalue ) ? htmlspecialchars( wp_json_encode( $ivalue, JSON_FORCE_OBJECT ) ) : '' );
    }

    /**
     * Prepare fields JSON.
     */
    protected function prepare_fields_json() {
        $fields = array();

        $settings = ( is_array( $this->settings ) ? $this->settings : array() );
        $settings = ( ( isset( $settings[ $this->control_value ] ) && is_array( $settings[ $this->control_value ] ) ) ? $settings[ $this->control_value ] : array() );

        $base_args = ( isset( $settings['base'] ) ? wloptf_cast( $settings['base'], 'array' ) : array() );
        $deps      = ( isset( $settings['deps'] ) ? wloptf_cast( $settings['deps'], 'array' ) : array() );

        if ( ! empty( $deps ) ) {
            foreach ( $deps as $key => $args ) {
                $operator_args = ( isset( $args['operator'] ) ? wloptf_cast( $args['operator'], 'array' ) : array() );
                $value_args    = ( isset( $args['value'] ) ? wloptf_cast( $args['value'], 'array' ) : array() );

                ob_start();
                \WLOPTF\Field::instance( $operator_args, false, false, false );
                $operator_fields = ob_get_clean();
                $operator_fields = wloptf_clean_html( $operator_fields );

                ob_start();
                \WLOPTF\Field::instance( $value_args, false, false, false );
                $value_fields = ob_get_clean();
                $value_fields = wloptf_clean_html( $value_fields );

                $fields['deps'][ $key ] = array(
                    'operator' => $operator_fields,
                    'value'    => $value_fields,
                );
            }
        }

        if ( ! empty( $base_args ) ) {
            ob_start();
            \WLOPTF\Field::instance( $base_args, false, false, false );
            $base_fields = ob_get_clean();
            $base_fields = wloptf_clean_html( $base_fields );

            $fields['base'] = $base_fields;
        }

        $this->fields_json = ( is_array( $fields ) ? htmlspecialchars( wp_json_encode( $fields ) ) : '' );
    }

    /**
     * Prepare settings JSON.
     */
    protected function prepare_settings_json() {
        $this->settings_json = ( is_array( $this->settings ) ? htmlspecialchars( wp_json_encode( $this->settings ) ) : '' );
    }

    /**
     * Get attributes.
     */
    protected function get_attributes() {
        $atts = '';

        $class = $this->class;
        $attrs = $this->attributes;

        $class = ( ( 0 < strlen( $class ) ) ? ( 'wloptf-rules-wrapper ' . $class ) : 'wloptf-rules-wrapper' );
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

        $add_attr .= ' data-wloptf-rules-field-name="' . esc_attr( $this->name ) . '"';
        $add_attr .= ' data-wloptf-rules-value-json="' . esc_attr( $this->value_json ) . '"';
        $add_attr .= ' data-wloptf-rules-ivalue-json="' . esc_attr( $this->ivalue_json ) . '"';
        $add_attr .= ' data-wloptf-rules-fields-json="' . esc_attr( $this->fields_json ) . '"';
        $add_attr .= ' data-wloptf-rules-settings-json="' . esc_attr( $this->settings_json ) . '"';
        $add_attr .= ' data-wloptf-rules-control-name="' . esc_attr( $this->control_name ) . '"';
        $add_attr .= ' data-wloptf-rules-control-event="' . esc_attr( $this->control_event ) . '"';
        $add_attr .= ' data-wloptf-rules-control-value="' . esc_attr( $this->control_value ) . '"';
        ?>
        <div <?php echo wp_kses_post( $add_attr ); ?>>
            <div class="wloptf-rules-sample"><?php echo $this->sample; ?></div>
            <div class="wloptf-rules-groups">
                <div class="wloptf-rules-groups-content"><?php echo $this->items; ?></div>
                <div class="wloptf-rules-groups-controls">
                    <button class="button wloptf-add"><span class="wloptf-icon wloptf-icon-plus"></span><?php echo esc_html( $this->button ); ?></button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Instance.
     */
    public static function instance( $args = array(), $store = true ) {
        new self( $args, $store );
    }

}