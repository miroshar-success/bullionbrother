<?php
/**
 * Query.
 */

namespace WLPF\Frontend;

/**
 * Class.
 */
class Query {

	/**
     * Constructor.
     */
    public function __construct() {
        new Query\Base();
        new Query\Shortcode();
    }

}