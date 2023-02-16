<?php
/**
 * Workflow.
 */

namespace WLEA;

/**
 * Class.
 */
class Workflow {

	/**
     * Constructor.
     */
    public function __construct() {
        new Workflow\Events();
        new Workflow\Tasks();
        new Workflow\Schedule();
        new Workflow\Handler();
    }

}