<?php

namespace OM4\Zapier\Feed;

use OM4\Zapier\Feed\Feed;
use OM4\Zapier\Trigger\Base;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * This class is responsible for retrieving OM4\Zapier\Feed\Feed objects.
 *
 * @deprecated 2.0.0
 */
class FeedFactory {

	/**
	 * Obtain the (active and valid) Zapier Feeds that are configured for the specified trigger.
	 * The oldest feeds are first.
	 *
	 * Note: multiple calls to this function will simply return the cached result.
	 *
	 * @param Base $trigger The trigger to get Feeds for.
	 *
	 * @return Feed[] Array of \OM4\Zapier\Feed\Feed objects
	 */
	public static function get_feeds_for_trigger( Base $trigger ) {

		$feeds = array();
		// Strangely, WP_Query doesn't let us search by post_content so we need to do it manually.
		$enabled_feeds = self::get_enabled_feeds();
		foreach ( $enabled_feeds as $feed ) {
			if ( get_class( $feed->trigger() ) === get_class( $trigger ) ) {
				$feeds[] = $feed;
			}
		}
		return $feeds;
	}

	/**
	 * Obtain the number of existing Zapier Feeds that have the specified webhook URL and trigger.
	 * This is used to help ensure that two Zapier Feeds can't exist with the same webhook URL and trigger combination.
	 *
	 * @param string        $webhook_url     Zapier Search for this Webhook URL.
	 * @param Base          $trigger Trigger Search for this Trigger.
	 * @param null|int|Feed $feed_to_exclude Feed not to include in the search.
	 *
	 * @return integer
	 */
	public static function get_number_of_feeds_with_webhook_url_and_trigger( $webhook_url, Base $trigger, $feed_to_exclude = null ) {

		$post_id_to_exclude = is_null( $feed_to_exclude ) ? 0 : $feed_to_exclude->id();

		$query       = array(
			'post_type'    => 'wc_zapier_feed',
			'nopaging'     => true,
			'post_status'  => 'publish',
			'post__not_in' => array( $post_id_to_exclude ),
		);
		$feeds_query = new WP_Query( $query );
		$posts       = $feeds_query->get_posts();

		// Strangely, WP_Query doesn't let us search by post_content or post_excerpt so we need to do it manually.
		foreach ( $posts as $index => $post ) {
			$feed = new Feed( $post );

			if ( get_class( $feed->trigger() ) !== get_class( $trigger ) || $feed->webhook_url() !== $webhook_url ) {
				unset( $posts[ $index ] );
			}
		}
		wp_reset_postdata();
		return count( $posts );
	}

	/**
	 * Obtain the number of existing Zapier Feeds that have the specified title.
	 * This is used to help ensure that two Zapier Feeds can't exist with the same title.
	 *
	 * @param string   $title           Zapier Feed Title.
	 * @param int|Feed $feed_to_exclude Optional feed not to include in the search.
	 *
	 * @return integer
	 */
	public static function get_number_of_feeds_with_title( $title, $feed_to_exclude = null ) {

		$post_id_to_exclude = is_null( $feed_to_exclude ) ? 0 : $feed_to_exclude->id();

		$query       = array(
			'post_type'    => 'wc_zapier_feed',
			'nopaging'     => true,
			'post_status'  => 'publish',
			'post__not_in' => array( $post_id_to_exclude ),
		);
		$feeds_query = new WP_Query( $query );
		$posts       = $feeds_query->get_posts();

		// Strangely, WP_Query doesn't let us search by post_title so we need to do it manually.
		foreach ( $posts as $index => $post ) {
			$feed = new Feed( $post );
			if ( $feed->title() !== $title ) {
				unset( $posts[ $index ] );
			}
		}
		wp_reset_postdata();
		return count( $posts );
	}

	/**
	 * Obtain the number of configured Zapier feeds.
	 * This only includes published (active) ones.
	 *
	 * @return integer
	 */
	public static function get_number_of_enabled_feeds() {
		return count( self::get_enabled_feeds() );
	}

	/**
	 * Obtain all of the configured active and valid Zapier feeds.
	 * This only includes published (active) ones.
	 *
	 * @return array
	 */
	public static function get_enabled_feeds() {
		$query       = array(
			'post_type'   => 'wc_zapier_feed',
			'nopaging'    => true,
			'post_status' => 'publish',
			'orderby'     => 'date',
			'order'       => 'ASC',
		);
		$feeds_query = new WP_Query( $query );
		$feeds       = array();
		$posts       = $feeds_query->get_posts();

		foreach ( $posts as $post ) {
			$feed = new Feed( $post );
			// Ensure the active feed's trigger is valid.
			if ( $feed->is_valid_trigger() ) {
				$feeds[] = $feed;
			}
		}
		wp_reset_postdata();
		return $feeds;
	}
}
