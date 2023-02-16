<?php
/**
 * Functions used throughout the theme
 *
 * @package Genesis Block Theme
 */

/**
 * Display the author description on author archive
 *
 * @param string $before
 * @param string $after
 */
function the_author_archive_description( $before = '', $after = '' ) {

	$author_description = get_the_author_meta( 'description' );

	if ( ! empty( $author_description ) ) {
		/**
		 * Get the author bio.
		 */
		echo wpautop( $author_description );
	}
}


if ( ! function_exists( 'genesis_block_theme_title_logo' ) ) :
	/**
	 * Site title and logo.
	 */
	function genesis_block_theme_title_logo() { ?>
	<div class="site-title-wrap" itemscope itemtype="http://schema.org/Organization">
		<!-- Use the Site Logo feature, if supported -->
			<?php
			if ( function_exists( 'the_custom_logo' ) && the_custom_logo() ) {

				the_custom_logo();
			}
			?>

		<div class="titles-wrap 
		<?php
		if ( get_bloginfo( 'description' ) ) {
			echo 'has-description'; }
		?>
		">
			<?php if ( is_front_page() && is_home() ) { ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php } else { ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php } ?>

			<?php if ( get_bloginfo( 'description' ) ) { ?>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			<?php } ?>
		</div>
	</div><!-- .site-title-wrap -->
		<?php
	} endif;


if ( ! function_exists( 'genesis_block_theme_page_titles' ) ) :
	/**
	 * Output paeg titles, subtitles and archive descriptions.
	 */
	function genesis_block_theme_page_titles() {
		?>
	<div class="page-titles">
		<h1><?php the_archive_title(); ?></h1>

			<?php
			// Get the page excerpt or archive description for a subtitle.
			$archive_description = get_the_archive_description();

			if ( is_archive() && $archive_description ) {
				$subtitle = get_the_archive_description();
			}

			// Show the subtitle.
			if ( ! empty( $subtitle ) && ! is_singular( 'post' ) ) {
				?>
			<div class="entry-subtitle">
					<?php echo $subtitle; ?>
			</div>
			<?php } ?>

	</div>

		<?php
	} endif;


/**
 * Filter the page title for certain pages.
 *
 * @param string $title
 */
function genesis_block_theme_change_archive_title( $title ) {
	if ( is_search() ) {
		$title = sprintf( __( 'Search Results for: %s', 'genesis-block-theme' ), '<span>' . get_search_query() . '</span>' );
	} elseif ( is_404() ) {
		$title = esc_html_e( 'Page Not Found', 'genesis-block-theme' );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'genesis_block_theme_change_archive_title' );


/**
 * Custom comment output.
 *
 * @param string $comment
 * @param array  $args
 * @param int    $depth
 */
function genesis_block_theme_comment( $comment, $args, $depth ) {
	?>
<li <?php comment_class( 'clearfix' ); ?> id="li-comment-<?php comment_ID(); ?>">

	<div class="comment-block" id="comment-<?php comment_ID(); ?>">

		<div class="comment-wrap">
			<?php echo get_avatar( $comment, 75 ); ?>

			<div class="comment-info">
				<cite class="comment-cite">
					<?php comment_author_link(); ?>
				</cite>

				<a class="comment-time" href="<?php echo esc_url( get_comment_link( get_comment_ID() ) ); ?>"><?php printf( esc_html__( '%1$s at %2$s', 'genesis-block-theme' ), get_comment_date(), get_comment_time() ); ?></a><?php edit_comment_link( esc_html__( '(Edit)', 'genesis-block-theme' ), '&nbsp;', '' ); ?>
			</div>

			<div class="comment-content">
				<?php comment_text(); ?>
				<p class="reply">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
							)
						)
					);
					?>
				</p>
			</div>

			<?php if ( $comment->comment_approved === '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'genesis-block-theme' ); ?></em>
			<?php endif; ?>
		</div>
	</div>
	<?php
}


if ( ! function_exists( 'genesis_block_theme_post_navs' ) ) :
	/**
	 * Displays post next/previous navigation.
	 *
	 * @since Genesis Block Theme 1.0
	 *
	 * @param bool $query
	 */
	function genesis_block_theme_post_navs( $query = false ) {
		// Previous/next post navigation.
		$next_post     = get_next_post();
		$previous_post = get_previous_post();

		the_post_navigation(
			array(
				'next_text' => '<span class="meta-nav-text meta-title">' . esc_html__( 'Next:', 'genesis-block-theme' ) . '</span> ' .
				'<span class="screen-reader-text">' . esc_html__( 'Next post:', 'genesis-block-theme' ) . '</span> ' .
				'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav-text meta-title">' . esc_html__( 'Previous:', 'genesis-block-theme' ) . '</span> ' .
				'<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'genesis-block-theme' ) . '</span> ' .
				'<span class="post-title">%title</span>',
			)
		);
	} endif;


if ( ! function_exists( 'genesis_block_theme_author_box' ) ) :
	/**
	 * Author post widget.
	 *
	 * @since 1.0
	 */
	function genesis_block_theme_author_box() {
		global $post, $current_user;
		$author = get_userdata( $post->post_author );
		if ( $author && ! empty( $author->description ) ) {
			?>
	<div class="author-profile">

		<a class="author-profile-avatar" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Posts by %s', 'genesis-block-theme' ), get_the_author() ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'genesis_block_theme_author_bio_avatar_size', 65 ) ); ?></a>

		<div class="author-profile-info">
			<h3 class="author-profile-title">
				<?php if ( is_archive() ) { ?>
					<?php echo esc_html( sprintf( esc_html__( 'All posts by %s', 'genesis-block-theme' ), get_the_author() ) ); ?>
				<?php } else { ?>
					<?php echo esc_html( sprintf( esc_html__( 'Posted by %s', 'genesis-block-theme' ), get_the_author() ) ); ?>
				<?php } ?>
			</h3>

			<div class="author-description">
				<p><?php the_author_meta( 'description' ); ?></p>
			</div>

			<div class="author-profile-links">
				<a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php esc_html_e( 'All Posts', 'genesis-block-theme' ); ?></a>

				<?php if ( $author->user_url ) { ?>
					<?php printf( '<a href="%1$s">%2$s</a>', esc_url( $author->user_url ), 'Website', 'genesis-block-theme' ); ?>
				<?php } ?>
			</div>
		</div><!-- .author-drawer-text -->
	</div><!-- .author-profile -->

			<?php
		} } endif;


if ( ! function_exists( 'genesis_block_theme_post_byline' ) ) :
	/**
	 * Post byline
	 */
	function genesis_block_theme_post_byline() {
		?>
		<?php
		// Get the post author.
		global $post;
		$author_id = $post->post_author;
		?>
	<p class="entry-byline">
		<!-- Create an avatar link -->
		<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'Posts by %s', 'genesis-block-theme' ), get_the_author() ) ); ?>">
			<?php echo get_avatar( $author_id, apply_filters( 'genesis_block_theme_author_bio_avatar', 44 ) ); ?>
		</a>

		<!-- Create an author post link -->
		<a class="entry-byline-author" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
			<?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?>
		</a>
		<span class="entry-byline-on"><?php esc_html_e( 'on', 'genesis-block-theme' ); ?></span>
		<span class="entry-byline-date"><?php echo get_the_date(); ?></span>
	</p>
		<?php
	} endif;


if ( ! function_exists( 'genesis_block_theme_modify_archive_title' ) ) :
	/**
	 * Modify the archive title prefix.
	 *
	 * @param string $title
	 */
	function genesis_block_theme_modify_archive_title( $title ) {
		// Skip if the site isn't LTR, this is visual, not functional.
		if ( is_rtl() || is_search() || is_404() ) {
			return $title;
		}

		// Split the title into parts so we can wrap them with spans.
		$title_parts = explode( ': ', $title, 2 );

		// Glue it back together again.
		if ( ! empty( $title_parts[1] ) ) {
			$title = wp_kses(
				$title_parts[1],
				array(
					'span' => array(
						'class' => array(),
					),
				)
			);
			$title = '<span class="screen-reader-text">' . esc_html( $title_parts[0] ) . ': </span>' . $title;
		}
		return $title;
	} endif;
add_filter( 'get_the_archive_title', 'genesis_block_theme_modify_archive_title' );
