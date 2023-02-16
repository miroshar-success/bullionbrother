<?php
namespace ElementorPro\Modules\LoopBuilder\Skins;

use Elementor\Icons_Manager;
use ElementorPro\Modules\LoopBuilder\Documents\Loop as LoopDocument;
use ElementorPro\Modules\LoopBuilder\Module;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Loop Carousel Base
 *
 * Base Skin for Loop widgets.
 */
class Skin_Loop_Carousel_Post extends Skin_Loop_Carousel_Base {

	public function get_id() {
		return Module::LOOP_CAROUSEL_POST_SKIN_ID;
	}

	public function get_title() {
		return esc_html__( 'Posts', 'elementor-pro' );
	}
}
