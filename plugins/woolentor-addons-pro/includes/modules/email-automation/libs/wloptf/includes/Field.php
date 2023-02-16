<?php
/**
 * Field.
 */

namespace WLOPTF;

/**
 * Class.
 */
class Field {

    /**
     * ID.
     */
    protected $id;

    /**
     * Type.
     */
    protected $type;

    /**
     * Title.
     */
    protected $title;

    /**
     * Subtitle.
     */
    protected $subtitle;

    /**
     * Desc.
     */
    protected $desc;

    /**
     * Args.
     */
    protected $args;

    /**
     * Constructor.
     */
    public function __construct( $args = array(), $store = true, $complex = true, $wrapper = true ) {
        if ( ! is_array( $args ) ) {
            return;
        }

        $args = wp_parse_args( $args, array(
            'id'       => '',
            'type'     => '',
            'title'    => '',
            'subtitle' => '',
            'desc'     => '',
        ) );

        $id       = ( isset( $args['id'] ) ? wloptf_cast( $args['id'], 'key' ) : '' );
        $type     = ( isset( $args['type'] ) ? wloptf_cast( $args['type'], 'key' ) : '' );
        $title    = ( isset( $args['title'] ) ? wloptf_cast( $args['title'], 'text' ) : '' );
        $subtitle = ( isset( $args['subtitle'] ) ? wloptf_cast( $args['subtitle'], 'text' ) : '' );
        $desc     = ( isset( $args['desc'] ) ? wloptf_cast( $args['desc'], 'textarea' ) : '' );

        $store   = wloptf_cast( $store, 'bool' );
        $complex = wloptf_cast( $complex, 'bool' );
        $wrapper = wloptf_cast( $wrapper, 'bool' );

        if ( empty( $id ) || empty( $type ) || ( false === $complex && ( 'group' === $type || 'rules' === $type ) ) ) {
            return;
        }

        $this->id       = $id;
        $this->type     = $type;
        $this->title    = $title;
        $this->subtitle = $subtitle;
        $this->desc     = $desc;
        $this->args     = $args;
        $this->store    = $store;
        $this->complex  = $complex;
        $this->wrapper  = $wrapper;

        if ( true === $wrapper ) {
            $this->render_field();
        } else {
            $this->render_fieldset();
        }
    }

    /**
     * Render field.
     */
    protected function render_field() {
        $class = 'wloptf-field wloptf-field-' . $this->type;
        ?>
        <div class="<?php echo esc_attr( $class ); ?>">
            <?php
            if ( ! empty( $this->title ) || ! empty( $this->subtitle ) ) {
                ?>
                <div class="wloptf-field-title">
                    <?php
                    if ( ! empty( $this->title ) ) {
                        ?>
                        <h4><?php echo esc_html( $this->title ); ?></h4>
                        <?php
                    }

                    if ( ! empty( $this->subtitle ) ) {
                        ?>
                        <div class="wloptf-field-subtitle"><?php echo esc_html( $this->subtitle ); ?></div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="wloptf-field-content">
                <div class="wloptf-field-element"><?php $this->render_fieldset(); ?></div>
                <?php
                if ( ! empty( $this->desc ) ) {
                    ?>
                    <div class="wloptf-field-desc"><?php echo esc_html( $this->desc ); ?></div>
                    <?php
                }
                ?>
            </div>
            <div class="wloptf-clearfix"></div>
        </div>
        <?php
    }

    /**
     * Render fieldset.
     */
    protected function render_fieldset() {
        $args = $this->args;

        switch ( $this->type ) {
            case 'text':
                \WLOPTF\Field\Text::instance( $args, $this->store );
                break;

            case 'textarea':
                \WLOPTF\Field\Textarea::instance( $args, $this->store );
                break;

            case 'checkbox':
                \WLOPTF\Field\Checkbox::instance( $args, $this->store );
                break;

            case 'number':
                \WLOPTF\Field\Number::instance( $args, $this->store );
                break;

            case 'date':
                \WLOPTF\Field\Date::instance( $args, $this->store );
                break;

            case 'select':
                \WLOPTF\Field\Select::instance( $args, $this->store );
                break;

            case 'schedule':
                \WLOPTF\Field\Schedule::instance( $args, $this->store );
                break;

            case 'group':
                \WLOPTF\Field\Group::instance( $args, $this->store );
                break;

            case 'rules':
                \WLOPTF\Field\Rules::instance( $args, $this->store );
                break;

            default:
                $this->default_field();
                break;
        }
    }

    /**
     * Default field.
     */
    protected function default_field() {
        esc_html_e( 'Invalid field type.', 'woolentor-pro' );
    }

    /**
     * Instance.
     */
    public static function instance( $args = array(), $store = true, $complex = true, $wrapper = true ) {
        new self( $args, $store, $complex, $wrapper );
    }

}