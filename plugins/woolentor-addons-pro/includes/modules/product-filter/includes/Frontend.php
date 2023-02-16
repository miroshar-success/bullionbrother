<?php
/**
 * Frontend.
 */

namespace WLPF;

/**
 * Class.
 */
class Frontend {

	/**
     * Constructor.
     */
    public function __construct() {
        new Frontend\Hooks();
        new Frontend\Shortcode();
        new Frontend\Query();
        new Frontend\Ajax();
    }

}