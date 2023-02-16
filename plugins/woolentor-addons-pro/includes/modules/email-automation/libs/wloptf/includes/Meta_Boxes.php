<?php
/**
 * Meta Boxe.
 */

namespace WLOPTF;

/**
 * Class.
 */
class Meta_Boxes {

	/**
     * ID.
     */
    protected $id;

    /**
     * Title.
     */
    protected $title;

    /**
     * Screen.
     */
    protected $screen;

    /**
     * Context.
     */
    protected $context;

    /**
     * Priority.
     */
    protected $priority;

    /**
     * Fields.
     */
    protected $fields;

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
            'id'       => '',
            'title'    => '',
            'screen'   => '',
            'context'  => 'advanced',
            'priority' => 'default',
            'fields'   => array(),
        ) );

        $id       = ( isset( $args['id'] ) ? wloptf_cast( $args['id'], 'key' ) : '' );
        $title    = ( isset( $args['title'] ) ? wloptf_cast( $args['title'], 'text' ) : '' );
        $screen   = ( isset( $args['screen'] ) ? wloptf_cast( $args['screen'], 'key' ) : '' );
        $context  = ( isset( $args['context'] ) ? wloptf_cast( $args['context'], 'key' ) : '' );
        $priority = ( isset( $args['priority'] ) ? wloptf_cast( $args['priority'], 'key' ) : '' );
        $fields   = ( isset( $args['fields'] ) ? wloptf_cast( $args['fields'], 'array' ) : array() );

        $title    = ( ! empty( $title ) ? $title : esc_html__( 'Unnamed', 'woolentor-pro' ) );
        $context  = ( in_array( $context, array( 'normal', 'side', 'advanced' ) ) ? $context : 'advanced' );
        $priority = ( in_array( $priority, array( 'high', 'core', 'default', 'low' ) ) ? $priority : 'default' );

        if ( empty( $id ) || empty( $screen ) || empty( $fields ) ) {
            return;
        }

        $this->id       = $id;
        $this->title    = $title;
        $this->screen   = $screen;
        $this->context  = $context;
        $this->priority = $priority;
        $this->fields   = $fields;
        $this->args     = $args;

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_metabox' ), 10, 3 );
    }

    /**
     * Add meta box.
     */
    public function add_meta_box( $post_type ) {
        if ( $this->screen !== $post_type ) {
            return;
        }

        add_meta_box( $this->id, $this->title, array( $this, 'render_meta_box' ), $this->screen, $this->context, $this->priority );

        add_action( 'admin_enqueue_scripts', function () {
            wp_enqueue_style( 'wloptf' );
            wp_enqueue_script( 'wloptf' );
        } );
    }

    /**
     * Render meta box.
     */
    public function render_meta_box( $post ) {
        $post_id = ( ( is_object( $post ) && isset( $post->ID ) ) ? absint( $post->ID ) : 0 );
        $meta_data = wloptf_cast( get_post_meta( $post_id, $this->id, true ), 'array', false );
        ?>
        <div class="wloptf-opt-meta-box wloptf-border-box-r">
            <?php
            foreach ( $this->fields as $field ) {
                $args = array_merge( $field, array(
                    'base_name' => $this->id,
                    'base_data' => $meta_data,
                ) );

                \WLOPTF\Field::instance( $args );
            }

            wp_nonce_field( 'wloptf_opt_nonce_action', '_wloptf_opt_nonce' );
            ?>
        </div>
        <?php
    }

    /**
     * Save meta box.
     */
    public function save_metabox( $post_id, $post, $update ) {
        $nonce_name   = isset( $_POST['_wloptf_opt_nonce'] ) ? $_POST['_wloptf_opt_nonce'] : '';
        $nonce_action = 'wloptf_opt_nonce_action';

        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        $meta_key = $this->id;

        $meta_data = ( ( isset( $_POST[ $meta_key ] ) && is_array( $_POST[ $meta_key ] ) ) ? $_POST[ $meta_key ] : array() );
        $meta_data = apply_filters( 'wloptf_meta_box_' . $meta_key . '_data_save', $meta_data, $post_id, $post, $update );

        update_post_meta( $post_id, $meta_key, $meta_data );

        do_action( 'wloptf_meta_box_' . $meta_key . '_saved', $meta_data, $post_id, $post, $update );
        do_action( 'wloptf_meta_box_' . $meta_key . '_save_after', $meta_data, $post_id, $post, $update );
    }

    /**
     * Instance.
     */
    public static function instance( $args = array() ) {
        new self( $args );
    }

}