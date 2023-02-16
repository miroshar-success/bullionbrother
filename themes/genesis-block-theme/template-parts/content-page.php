<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Genesis Block Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post-content">
	<?php
		$hide_title = get_post_meta( get_the_ID(), '_genesis_block_theme_hide_title', true );
	if ( ! $hide_title ) {
		?>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php the_title(); ?>
			</h1>
		</header>
		<?php
	} // End if hide title.

	if ( has_post_thumbnail() ) {
		?>
			<div class="featured-image">
				<?php the_post_thumbnail( 'genesis-block-theme-featured-image' ); ?>
			</div>
		<?php } ?>

		<div class="entry-content">

			<?php
			// Get the content.
			the_content( esc_html__( 'Read More', 'genesis-block-theme' ) );

			// Post pagination links.
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'genesis-block-theme' ),
					'after'  => '</div>',
				)
			);

			// Comments template.
			comments_template();
			?>
		</div><!-- .entry-content -->
	</div><!-- .post-content-->

</article><!-- #post-## -->
