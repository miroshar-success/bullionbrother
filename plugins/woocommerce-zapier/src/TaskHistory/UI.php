<?php

namespace OM4\WooCommerceZapier\TaskHistory;

use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\TaskHistory\ListTable;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use OM4\WooCommerceZapier\WooCommerceResource\Definition;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Administration / Dashboard UI functionality for showing task history.
 *
 * @since 2.0.0
 */
class UI {

	/**
	 * List of resources that support custom post types.
	 *
	 * @var Definition[]
	 */
	protected $resources_with_metaboxes = array();

	/**
	 * ListTable instance.
	 *
	 * @var ListTable
	 */
	protected $list_table;

	/**
	 * ContainerService instance.
	 *
	 * @var ContainerService
	 */
	protected $container;

	/**
	 * ResourceManager instance.
	 *
	 * @var ResourceManager
	 */
	protected $resource_manager;

	/**
	 * HistoryUI constructor.
	 *
	 * @param ContainerService $container Data store.
	 * @param ResourceManager  $resource_manager ResourceManager instance.
	 */
	public function __construct( ContainerService $container, ResourceManager $resource_manager ) {
		$this->container        = $container;
		$this->resource_manager = $resource_manager;
	}

	/**
	 * Instructs the functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		foreach ( $this->resource_manager->get_enabled() as $resource ) {
			if ( ! is_null( $resource->get_metabox_screen_name() ) ) {
				$this->resources_with_metaboxes[] = $resource;
			}
		}
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 50 );
	}

	/**
	 * Add our metabox
	 * Executed during the `add_meta_boxes` hook.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {

		foreach ( $this->resources_with_metaboxes as $resource ) {
			add_meta_box(
				'woocommerce-zapier-history',
				__( 'WooCommerce Zapier History', 'woocommerce-zapier' ),
				array( $this, 'metabox_output' ),
				$resource->get_metabox_screen_name(),
				'normal',
				'default'
			);
		}
	}

	/**
	 * Output the History screen using the List Table functionality.
	 *
	 * @return void
	 */
	public function output_screen() {
		$this->list_table = $this->container->get( ListTable::class );
		$this->list_table->prepare_items();
		$this->list_table->display();
		// Translators: Days for storing Zapier Task History.
		echo '<p>' . esc_html( __( 'This screen shows a history of your WooCommerce store\'s integration with Zapier.', 'woocommerce-zapier' ) ) . '</p>';
		echo '<p>' . esc_html__( 'This includes any time that your WooCommerce store\'s data is sent to one of your Zapier Zaps, or any time that Zapier creates or updates data in your WooCommerce store via one of your Zaps.', 'woocommerce-zapier' ) . '</p>';
		// Translators: URL to Zapier Task History.
		echo '<p>' . wp_kses( sprintf( __( 'You can also <a href="%s">view your Task History at zapier.com</a>', 'woocommerce-zapier' ), 'https://zapier.com/app/history/' ), 'post' ) . '</p>';
	}

	/**
	 * Output the Task History Metabox content.
	 *
	 * Executed automatically by WordPress when the metabox needs to be displayed on a Custom Post Type edit screen.
	 *
	 * @param WP_Post $post The order/post object.
	 *
	 * @return void
	 */
	public function metabox_output( WP_Post $post ) {
		$this->list_table = $this->container->get( ListTable::class );
		foreach ( $this->resources_with_metaboxes as $resource ) {
			if ( $post->post_type === $resource->get_metabox_screen_name() ) {
				$this->list_table->enable_metabox_mode( $resource->get_key(), intval( $post->ID ) );
				$this->list_table->prepare_items();
				$this->list_table->display();
				return;
			}
		}
	}
}
