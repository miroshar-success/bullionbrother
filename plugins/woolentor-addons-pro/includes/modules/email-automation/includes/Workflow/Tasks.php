<?php
/**
 * Tasks.
 */

namespace WLEA\Workflow;

/**
 * Class.
 */
class Tasks {

	/**
     * Constructor.
     */
    public function __construct() {
        new Tasks\WC_Order();
        new Tasks\WC_Customer();
    }

}