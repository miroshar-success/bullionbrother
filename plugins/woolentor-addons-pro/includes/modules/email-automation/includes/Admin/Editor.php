<?php
/**
 * Editor.
 */

namespace WLEA\Admin;

/**
 * Class.
 */
class Editor {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'buttons' ) );
    }

    /**
     * Buttons.
     */
    public function buttons() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        add_filter( 'mce_buttons', array( $this, 'register_buttons' ) );
        add_filter( 'mce_external_plugins', array( $this, 'register_external_plugins' ) );
    }

    /**
     * Register buttons.
     */
    public function register_buttons( $buttons ) {
        $buttons[] = 'wlea_placeholders';

        return $buttons;
    }

    /**
     * Register external plugins.
     */
    public function register_external_plugins( $plugins ) {
        $plugins['wlea_placeholders'] = WLEA_ASSETS . '/js/admin-editor.js';

        return $plugins;
    }
}