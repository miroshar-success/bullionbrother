<?php
/**
 * Template Name: Full Width
 *
 * This template has width, margin and padding containers removed for use with page builder plugins.
 *
 * @package Genesis Block Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
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
				} // End if hide title
					the_content();
				?>
				</article>

			<?php endwhile; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_footer(); ?>
