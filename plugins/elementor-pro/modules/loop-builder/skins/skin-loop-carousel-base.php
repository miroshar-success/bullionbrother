<?php
namespace ElementorPro\Modules\LoopBuilder\Skins;

use Elementor\Icons_Manager;
use ElementorPro\Modules\LoopBuilder\Documents\Loop as LoopDocument;
use ElementorPro\Modules\LoopBuilder\Module;
use ElementorPro\Modules\Posts\Skins\Skin_Base;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Loop Carousel Base
 *
 * Base Skin for Loop widgets.
 */
class Skin_Loop_Carousel_Base extends Skin_Loop_Base {

	public function get_id() {
		return Module::LOOP_CAROUSEL_BASE_SKIN_ID;
	}

	protected function get_loop_header_widget_classes() {
		$classes = parent::get_loop_header_widget_classes();

		$classes[] = 'swiper-container';

		return $classes;
	}

	protected function render_loop_header() {
		parent::render_loop_header();

		?>
		<div class="swiper-wrapper">
		<?php
	}

	protected function render_loop_footer() {
		$settings = $this->parent->get_settings_for_display();
		?>
			</div>
			<div class="swiper-pagination"></div>

			<?php if ( 'yes' === $settings['arrows'] ) { ?>
				<div class="elementor-swiper-button elementor-swiper-button-prev">
					<?php $this->render_swiper_button( 'previous' ); ?>
					<span class="elementor-screen-only"><?php echo esc_html__( 'Previous', 'elementor-pro' ); ?></span>
				</div>
				<div class="elementor-swiper-button elementor-swiper-button-next">
					<?php $this->render_swiper_button( 'next' ); ?>
					<span class="elementor-screen-only"><?php echo esc_html__( 'Next', 'elementor-pro' ); ?></span>
				</div>
			<?php } ?>

			<div class="swiper-scrollbar"></div>
		</div>
		<?php
	}

	private function render_swiper_button( $type ) {
		$icon_settings = $this->parent->get_settings_for_display( 'navigation_' . $type . '_icon' );

		if ( empty( $icon_settings['value'] ) ) {
			return;
		}

		Icons_Manager::render_icon( $icon_settings, [ 'aria-hidden' => 'true' ] );
	}

	public function add_swiper_slide_class_to_loop_item( $attributes, $document ) {
		if ( LoopDocument::DOCUMENT_TYPE === $document::get_type() ) {
			$attributes['class'] .= ' swiper-slide';
		}

		return $attributes;
	}

	public function add_loop_header_attributes( $render_attributes ) {
		$settings = $this->parent->get_settings_for_display();

		if ( ! empty( $settings['direction'] ) ) {
			$render_attributes['dir'] = $settings['direction'];
		}

		return $render_attributes;
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();
		$is_edit_mode = Plugin::elementor()->editor->is_edit_mode();

		if ( ! empty( $settings['template_id'] ) ) {
			add_filter( 'elementor/document/wrapper_attributes', [ $this, 'add_swiper_slide_class_to_loop_item' ], 10, 2 );
			add_filter( 'elementor/skin/loop_header_attributes', [ $this, 'add_loop_header_attributes' ], 10, 1 );

			Skin_Base::render();

			remove_filter( 'elementor/document/wrapper_attributes', [ $this, 'add_swiper_slide_class_to_loop_item' ] );
			remove_filter( 'elementor/skin/loop_header_attributes', [ $this, 'add_loop_header_attributes' ] );
		} else if ( $is_edit_mode ) {
			$this->render_empty_view();
		}
	}
}
