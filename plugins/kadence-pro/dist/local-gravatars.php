<?php
/**
 * All Props go to Ari Stathopoulos. This is just a copy of his plugin: https://github.com/aristath/local-gravatars
 *
 * @package Kadence
 */

namespace Kadence_Pro;

add_filter(
	'get_avatar',
	/**
	 * Filters the HTML for a user's avatar.
	 *
	 * @param string $avatar HTML for the user's avatar.
	 * @return string
	 */
	function( $avatar ) {
		preg_match_all( '/srcset=["\']?((?:.(?!["\']?\s+(?:\S+)=|\s*\/?[>"\']))+.)["\']?/', $avatar, $srcset );
		if ( isset( $srcset[1] ) && isset( $srcset[1][0] ) ) {
			$url             = explode( ' ', $srcset[1][0] )[0];
			$local_gravatars = new Local_Gravatars( $url );
			$avatar          = str_replace( $url, $local_gravatars->get_gravatar(), $avatar );
		}

		preg_match_all( '/src=["\']?((?:.(?!["\']?\s+(?:\S+)=|\s*\/?[>"\']))+.)["\']?/', $avatar, $src );
		if ( isset( $src[1] ) && isset( $src[1][0] ) ) {
			$url             = explode( ' ', $src[1][0] )[0];
			$local_gravatars = new Local_Gravatars( $url );
			$avatar          = str_replace( $url, $local_gravatars->get_gravatar(), $avatar );
		}
		return $avatar;
	}
);

/**
 * Local Gravatars Class.
 */
class Local_Gravatars {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * The remote URL.
	 *
	 * @access protected
	 * @since 1.1.0
	 * @var string
	 */
	protected $remote_url;

	/**
	 * Base path.
	 *
	 * @access protected
	 * @since 1.1.0
	 * @var string
	 */
	protected $base_path;

	/**
	 * Subfolder name.
	 *
	 * @access protected
	 * @since 1.1.0
	 * @var string
	 */
	protected $subfolder_name;

	/**
	 * Base URL.
	 *
	 * @access protected
	 * @since 1.1.0
	 * @var string
	 */
	protected $base_url;

	/**
	 * The gravatars folder.
	 *
	 * @access protected
	 * @since 1.1.0
	 * @var string
	 */
	protected $gravatars_folder;

	/**
	 * Cleanup routine frequency.
	 */
	const CLEANUP_FREQUENCY = 'weekly';

	/**
	 * Maxiumum process seconds.
	 *
	 * @since 1.0
	 */
	const MAX_PROCESS_TIME = 5;

	/**
	 * Start tim of all processes.
	 *
	 * @static
	 * 
	 * @access private
	 * 
	 * @since 1.0.1
	 *
	 * @var int
	 */
	private static $start_time;

	/**
	 * Set to true if we want to stop processing.
	 * 
	 * @static
	 * 
	 * @access private
	 * 
	 * @since 1.0.1
	 * 
	 * @var bool
	 */
	private static $has_stopped = false;

	/**
	 * Constructor.
	 *
	 * Get a new instance of the object for a new URL.
	 *
	 * @access public
	 * @since 1.1.0
	 * @param string $url The remote URL.
	 */
	public function __construct( $url = '' ) {
		$this->remote_url = $url;

		// Add a cleanup routine.
		$this->schedule_cleanup();
		add_action( 'delete_gravatars_folder', array( $this, 'delete_gravatars_folder' ) );
	}

	/**
	 * Get the local file's URL.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return string
	 */
	public function get_gravatar() {

		// Early exit if we don't want to process.
		if ( ! $this->should_process() ) {
			return $this->get_fallback_url();
		}

		// If the gravatars folder doesn't exist, create it.
		if ( ! file_exists( $this->get_base_path() ) ) {
			$this->get_filesystem()->mkdir( $this->get_base_path(), FS_CHMOD_DIR );
		}

		// Get the filename.
		$filename = basename( wp_parse_url( $this->remote_url, PHP_URL_PATH ) );

		$path = $this->get_base_path() . '/' . $filename;

		// Check if the file already exists.
		if ( ! file_exists( $path ) ) {

			// require file.php if the download_url function doesn't exist.
			if ( ! function_exists( 'download_url' ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			}

			// Download file to temporary location.
			$tmp_path = download_url( $this->remote_url );

			// Make sure there were no errors.
			if ( ! is_wp_error( $tmp_path ) ) {
				// Move temp file to final destination.
				$success = $this->get_filesystem()->move( $tmp_path, $path, true );
				if ( ! $success ) {
					return $this->get_fallback_url();
				}
			}
		}
		return $this->get_base_url() . '/' . $filename;
	}

	/**
	 * Get the base path.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return string
	 */
	public function get_base_path() {
		if ( ! $this->base_path ) {
			$this->base_path = apply_filters(
				'get_local_gravatars_base_path',
				$this->get_filesystem()->wp_content_dir() . '/gravatars'
			);
		}
		return $this->base_path;
	}

	/**
	 * Get the base URL.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return string
	 */
	public function get_base_url() {
		if ( ! $this->base_url ) {
			$this->base_url = apply_filters(
				'get_local_gravatars_base_url',
				content_url() . '/gravatars'
			);
		}
		return $this->base_url;
	}

	/**
	 * Schedule a cleanup.
	 *
	 * Deletes the gravatars files on a regular basis.
	 * This way gravatars will get updated regularly,
	 * and we avoid edge cases where unused files remain in the server.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function schedule_cleanup() {
		if ( ! is_multisite() || ( is_multisite() && is_main_site() ) ) {
			if ( ! wp_next_scheduled( 'delete_gravatars_folder' ) && ! wp_installing() ) {
				wp_schedule_event(
					time(),
					apply_filters( 'get_local_gravatars_cleanup_frequency', self::CLEANUP_FREQUENCY ),
					'delete_gravatars_folder'
				);
			}
		}
	}

	/**
	 * Delete the gravatars folder.
	 *
	 * This runs as part of a cleanup routine.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return bool
	 */
	public function delete_gravatars_folder() {
		return $this->get_filesystem()->delete( $this->get_base_path(), true );
	}

	/**
	 * Get the filesystem.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @return WP_Filesystem
	 */
	protected function get_filesystem() {
		global $wp_filesystem;

		// If the filesystem has not been instantiated yet, do it here.
		if ( ! $wp_filesystem ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			}
			\WP_Filesystem();
		}
		return $wp_filesystem;
	}

	/**
	 * Should we process or not?
	 * 
	 * @access public
	 * 
	 * @since 1.0.1
	 * 
	 * @return bool
	 */
	public function should_process() {

		// Early exit if we've already determined we want to stop.
		if ( self::$has_stopped ) {
			return false;
		}

		// Set the start time.
		if ( ! self::$start_time ) {
			self::$start_time = time();
		}

		// Return false if we've got over the max time limit.
		if ( time() > self::$start_time + $this->get_max_process_time() ) {
			self::$has_stopped = true;
			return false;
		}

		// Falback to true.
		return true;
	}

	/**
	 * Get maximum process time in seconds.
	 * 
	 * @access public
	 * 
	 * @since 1.0.1
	 * 
	 * @return int
	 */
	public function get_max_process_time() {
		return apply_filters( 'get_local_gravatars_max_process_time', self::MAX_PROCESS_TIME );
	}

	/**
	 * Get fallback image
	 * 
	 * @access public
	 * 
	 * @since 1.0.1
	 * 
	 * @return string
	 */
	public function get_fallback_url() {
		return apply_filters( 'get_local_gravatars_fallback_url', '', $this->remote_url );
	}
}
