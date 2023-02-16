<?php

namespace OM4\Zapier\Feed;

use Exception;
use OM4\Zapier\Feed\FeedFactory;
use OM4\Zapier\Logger;
use OM4\Zapier\Plugin;
use OM4\Zapier\Trigger\Base;
use OM4\Zapier\Trigger\TriggerFactory;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Represents a single Zapier Feed.
 * One or more Zapier Feeds are configured via the user and stored in the WordPress installation.
 * They define which Zapier webhook URL should be contacted once the specified Zapier Trigger occurs.
 *
 * @deprecated 2.0.0
 */
class Feed {

	/**
	 * The Post ID of this Zapier Feed.
	 *
	 * @var int
	 */
	private $id;

	/**
	 * WP_Post instance.
	 *
	 * @var WP_Post
	 */
	private $post;

	/**
	 * The title/name of this Zapier Feed.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * The Zapier Trigger that this Zapier Feed applies to.
	 *
	 * @var Base
	 */
	private $trigger;

	/**
	 * The Webhook URL.
	 *
	 * @var string
	 */
	private $webhook_url;

	/**
	 * Whether or not this Zapier Feed is active
	 *
	 * @var bool
	 */
	private $is_active = false;

	/**
	 * Regular Expression used to validate a Webhook URL.
	 *
	 * Valid webhook examples:
	 * - https://zapier.com/hooks/catch/n/abcdef/ (For Zaps created before April 2014)
	 * - https://zapier.com/hooks/catch/fvc2n/ (For Zaps created between April 2014 and March 2016)
	 * - https://zapier.com/hooks/catch/12345/abcdef/ (For Zaps created from March 2016 onwards)
	 */
	const WEBHOOK_URL_REGEXP = '#^https://(hooks\.)?zapier\.com\/hooks\/catch(\/n)?\/([a-z0-9]+)\/([a-z0-9]+\/)?\z#';

	/**
	 * Example Zapier Webhook URL. Displayed in the Dashboard.
	 */
	const WEBHOOK_URL_EXAMPLE = 'https://hooks.zapier.com/hooks/catch/12345/abcdef/';

	/**
	 * Loads an existing Zapier Feed if its ID is specified
	 *
	 * @param null|int|object $id Post ID or post object.
	 */
	public function __construct( $id = null ) {
		if ( ! empty( $id ) ) {
			$this->load( $id );
		}
	}

	/**
	 * Load the feed from the WordPress database
	 *
	 * @param int|object $id Post ID or post object.
	 *
	 * @return void
	 */
	public function load( $id ) {
		$post = get_post( $id );
		if ( is_null( $post ) || 'wc_zapier_feed' !== $post->post_type ) {
			return;
		}

		$this->post = $post;
		$this->populate_from_post_object();
	}

	/**
	 * Populate the class properties based on the WordPress WP_Post object.
	 *
	 * @return void
	 */
	private function populate_from_post_object() {
		try {
			$this->id = $this->post->ID;
			$this->set_title( $this->post->post_title );
			$this->set_webhook_url( $this->post->post_excerpt, false );
			$this->set_active( isset( $this->post->post_status ) && 'publish' === $this->post->post_status );
			// Do this last in case the trigger is invalid.
			if ( ! empty( $this->post->post_content ) ) {
				// Attempt to sanitize post_content if other plugin modified it.
				// See Issue #204.
				preg_match( '/^wc\.[a-z_]+/', $this->post->post_content, $matches );
				if ( $this->post->post_content !== $matches[0] ) {
					$msg = sprintf(
						'Altered trigger key: "%s" in Feed "%s" (%s)',
						$this->post->post_content,
						$this->post->post_title,
						$this->post->ID
					);
					( new Logger() )->notice( $msg );
					$this->post->post_content = $matches[0];
				}
				$this->set_trigger( TriggerFactory::get_trigger_with_key( $this->post->post_content ) );
			}
		} catch ( Exception $ex ) {
			$msg = sprintf(
				'No trigger for key: "%s" found in Feed "%s" (%s)',
				$this->post->post_content,
				$this->post->post_title,
				$this->post->ID
			);
			( new Logger() )->debug( $msg );
		}
	}

	/**
	 * Obtain an array representing this feed's properties.
	 * Used when inserting/updating the Feed details into the WordPress database.
	 *
	 * @return array
	 */
	private function get_array_from_properties() {
		$data = array(
			'post_title'   => $this->title(),
			'post_content' => empty( $this->trigger ) ? '' : $this->trigger->get_trigger_key(),
			'post_excerpt' => $this->webhook_url(),
			'post_status'  => $this->is_active() ? 'publish' : 'draft',
			'post_type'    => 'wc_zapier_feed',
		);

		if ( $this->id ) {
			$data['ID'] = $this->id;
		}

		return $data;
	}

	/**
	 * The ID of this Zapier Feed.
	 *
	 * @return int
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * The Title/Name of this Zapier Feed.
	 *
	 * @return string
	 */
	public function title() {
		return $this->title;
	}

	/**
	 * The Trigger for this Zapier Feed.
	 *
	 * @return Base
	 */
	public function trigger() {
		return $this->trigger;
	}

	/**
	 * The webhook URL of this Zapier Feed.
	 *
	 * @return string
	 */
	public function webhook_url() {
		return $this->webhook_url;
	}

	/**
	 * Whether or not this Zapier Feed is active.
	 *
	 * @return bool
	 */
	public function is_active() {
		return $this->is_active;
	}

	/**
	 * Set the Title/Name of this Zapier Feed.
	 *
	 * @param string $title Name to set.
	 *
	 * @return bool
	 */
	public function set_title( $title ) {
		$this->title = trim( (string) $title );
		return true;
	}

	/**
	 * Set the Trigger for this Zapier Feed.
	 *
	 * @param Base $trigger Name to set.
	 *
	 * @return bool
	 */
	public function set_trigger( Base $trigger ) {
		$this->trigger = $trigger;
		return true;
	}

	/**
	 * Set the Trigger for this Zapier Feed using a trigger key (rather than a Trigger object)
	 *
	 * @param string $trigger_key The trigger key.
	 *
	 * @return boolean
	 * @throws Exception If trigger not found.
	 */
	public function set_trigger_with_key( $trigger_key ) {
		if ( TriggerFactory::trigger_exists( $trigger_key ) ) {
			$this->trigger = TriggerFactory::get_trigger_with_key( $trigger_key );
			return true;
		}
		$this->trigger = null;
		return false;
	}

	/**
	 * Set the webhook URL for this Zapier Feed.
	 *
	 * Validation occurs to make sure only a valid Webhook URL can be specified.
	 *
	 * @param string  $webhook_url The URL of the webhook.
	 * @param boolean $validate Whether or not to validate the URL. Defaults to true.
	 *
	 * @return boolean
	 */
	public function set_webhook_url( $webhook_url, $validate = true ) {
		$webhook_url = trim( (string) $webhook_url );
		if ( $validate ) {
			// Ensure the specified webhook URL matches our expected format.
			if ( self::is_valid_webhook_url( $webhook_url ) ) {
				$this->webhook_url = $webhook_url;
				return true;
			} else {
				$this->webhook_url = '';
			}
		} else {
			// No need to validate/check the webhook URL.
			$this->webhook_url = $webhook_url;
			return true;
		}
		return false;
	}

	/**
	 * Set this Zapier Feed as active or inactive.
	 *
	 * @param boolean $active Whether this feed is active or not.
	 *
	 * @return void
	 */
	public function set_active( $active = true ) {
		$this->is_active = (bool) $active;
	}

	/**
	 * Ensure that this feed is valid.
	 *
	 * If it isn't valid, ensure it cannot be published.
	 *
	 * @return boolean|array True if valid, array of validation errors/warnings on failure
	 */
	public function validate() {

		$validation = array(
			'errors'   => array(),
			'warnings' => array(),
		);

		// If the Feed's title is empty, automatically set the Feed's title to match the name of the chosen trigger.
		if ( empty( $this->title ) && ! is_null( $this->trigger ) ) {
			$this->title = $this->trigger()->get_trigger_title();
		}

		if ( empty( $this->title ) ) {
			$validation['errors'][] = __( '<strong>Title:</strong> A title is required.', 'woocommerce-zapier' );
		}
		// Ensure unique title.
		if ( FeedFactory::get_number_of_feeds_with_title( $this->title, $this ) ) {
			$validation['errors'][] = __( '<strong>Title:</strong> Another Zapier Feed with this title already exists. Please choose a unique Title.', 'woocommerce-zapier' );
		}

		if ( ! $this->is_valid_trigger() ) {
			$validation['errors'][] = __( 'Invalid Trigger.', 'woocommerce-zapier' );
		}

		if ( empty( $this->webhook_url ) ) {
			// Translators: %s: URL of the example WebbHook.
			$validation['errors'][] = sprintf( __( '<strong>Webhook URL:</strong> Invalid Webhook URL. Zapier Webhook URLs should be in the following format: <code>%s</code>', 'woocommerce-zapier' ), self::WEBHOOK_URL_EXAMPLE );
		}

		if ( $this->webhook_url && ! is_null( $this->trigger ) ) {
			// Ensure unique trigger/webhook_url combination.
			if ( FeedFactory::get_number_of_feeds_with_webhook_url_and_trigger( $this->webhook_url, $this->trigger, $this ) ) {
				$validation['errors'][] = __( 'Another Zapier Feed with this Trigger and Webhook URL already exists.', 'woocommerce-zapier' );
			}
		}

		if ( empty( $validation['errors'] ) && empty( $validation['warnings'] ) ) {
			// The Feed is valid (configured correctly)
			// Send sample data to Zapier so that Zapier know what the data structure is like.
			$result = $this->trigger->send_sample_data_payload( $this );
			if ( true !== $result ) {
				// Translators: %1$s: URL of the Zapier WebbHook. %2$s: Incoming error message. %3$s: URL of the WooCommerce Zapier.
				$validation['errors'][] = sprintf( __( 'There was an error communicating with your Zapier Webhook (%1$s).<br />Error Message: %2$s<br />Please try again, and see <a href="%3$s">here for troubleshooting steps</a>.', 'woocommerce-zapier' ), '<code>' . esc_url( $this->webhook_url() ) . '</code>', '<code>' . esc_html( $result ) . '</code>', esc_url( Plugin::DOCUMENTATION_URL . '#troubleshooting' ) );

			}
		}

		if ( ! empty( $validation['errors'] ) ) {
			// Ensure the feed is in draft status.
			$this->set_active( false );
		} else {
			// Feed is error free.
			return true;
		}

		return $validation;
	}

	/**
	 * Test whether or not the specified Webhook URL is valid.
	 *
	 * @param string $webhook_url The webhook URL to check.
	 *
	 * @return boolean
	 */
	public static function is_valid_webhook_url( $webhook_url ) {
		if ( preg_match( self::WEBHOOK_URL_REGEXP, $webhook_url ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Whether or not this feed has a valid trigger assigned to it.
	 * For example, the feed could contain an invalid trigger if WC
	 * Subscriptions has been deactivated since creating the Feed.
	 *
	 * @return bool
	 */
	public function is_valid_trigger() {
		return ! is_null( $this->trigger );
	}

	/**
	 * The URL that of the Edit Feed screen.
	 *
	 * @return string
	 */
	public function edit_url() {
		// Unfortunately we can't use get_edit_post_link() because it doesn't
		// work during cron because no user is logged in.
		return admin_url( "post.php?post={$this->id}&action=edit" );
	}

	/**
	 * Save this Zapier feed to the database.
	 *
	 * @return bool
	 */
	public function save() {
		$result = null;

		if ( is_a( $this->post, 'WP_Post' ) ) {
			// The post could be an auto-draft (for a newly created Zapier Feed), or
			// The post could be an existing Zapier Feed.
			$result = wp_update_post( $this->get_array_from_properties(), true );
		} else {
			// No post yet. Unlikely, but possible.
			$result = wp_insert_post( $this->get_array_from_properties(), true );
		}

		if ( is_wp_error( $result ) || ! $result ) {
			( new Logger() )->error( $result->get_error_message() );
			return false;
		} else {
			// Success
			// Re-load the new feed data in case it has somehow changed.
			$this->load( $result );
			return true;
		}
	}
}
