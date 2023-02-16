<?php
namespace ElementorPro\Modules\LoopBuilder\Widgets;

use ElementorPro\Modules\LoopBuilder\Skins\Skin_Loop_Carousel_Post;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Loop_Carousel extends Base {

	public function get_name() {
		return 'loop-carousel';
	}

	public function get_title() {
		return esc_html__( 'Loop Carousel', 'elementor-pro' );
	}

	public function get_keywords() {
		return [ 'loop', 'carousel', 'dynamic', 'listing', 'archive', 'blog', 'repeater', 'grid', 'products', 'posts', 'portfolio', 'cpt ', 'query', 'custom post type' ];
	}

	public function get_icon() {
		return 'eicon-loop-builder';
	}

	protected function register_skins() {
		$this->add_skin( new Skin_Loop_Carousel_Post( $this ) );
	}

	public function register_pagination_section_controls() {}

	public function register_settings_section_controls() {
		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => esc_html__( 'Settings', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'On', 'elementor-pro' ),
					'no' => esc_html__( 'Off', 'elementor-pro' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Scroll Speed (ms)', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on hover', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'On', 'elementor-pro' ),
					'no' => esc_html__( 'Off', 'elementor-pro' ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => esc_html__( 'Pause on interaction', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'On', 'elementor-pro' ),
					'no' => esc_html__( 'Off', 'elementor-pro' ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		// Loop requires a re-render so no 'render_type = none'
		$this->add_control(
			'infinite',
			[
				'label' => esc_html__( 'Infinite scroll', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'On', 'elementor-pro' ),
					'no' => esc_html__( 'Off', 'elementor-pro' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Transition Duration (ms)', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => [
					'ltr' => esc_html__( 'Left', 'elementor-pro' ),
					'rtl' => esc_html__( 'Right', 'elementor-pro' ),
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_navigation_section_controls() {
		$this->start_controls_section(
			'section_navigation_settings',
			[
				'label' => esc_html__( 'Navigation', 'elementor-pro' ),
				'condition' => [
					'template_id!' => '',
				],
			]
		);

		$this->add_control(
			'arrows',
			[
				'label' => esc_html__( 'Arrows', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
				'label_on' => esc_html__( 'Show', 'elementor-pro' ),
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'navigation_previous_icon',
			[
				'label' => esc_html__( 'Previous Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'skin_settings' => [
					'inline' => [
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'arrow-alt-circle-left',
						'caret-square-left',
					],
					'fa-solid' => [
						'angle-double-left',
						'angle-left',
						'arrow-alt-circle-left',
						'arrow-circle-left',
						'arrow-left',
						'caret-left',
						'caret-square-left',
						'chevron-circle-left',
						'chevron-left',
						'long-arrow-alt-left',
					],
				],
				'condition' => [
					'arrows' => 'yes',
				],
				'default' => [
					'value' => 'eicon-chevron-left',
					'library' => 'eicons',
				],
			]
		);

		$this->add_control(
			'navigation_next_icon',
			[
				'label' => esc_html__( 'Next Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'skin_settings' => [
					'inline' => [
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'arrow-alt-circle-right',
						'caret-square-right',
					],
					'fa-solid' => [
						'angle-double-right',
						'angle-right',
						'arrow-alt-circle-right',
						'arrow-circle-right',
						'arrow-right',
						'caret-right',
						'caret-square-right',
						'chevron-circle-right',
						'chevron-right',
						'long-arrow-alt-right',
					],
				],
				'condition' => [
					'arrows' => 'yes',
				],
				'default' => [
					'value' => 'eicon-chevron-right',
					'library' => 'eicons',
				],
			]
		);

		$this->end_controls_section();
	}
}
