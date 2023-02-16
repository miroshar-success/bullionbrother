<?php
/**
 * The template for displaying Search results.
 *
 * @package Genesis Block Theme
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<div id="post-wrap">
			<?php
			if ( have_posts() ) :

				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content-search' );
				endwhile;

				else :
					?>
					<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'genesis-block-theme' ); ?></p>
			<?php endif; ?>
		</div>

		<?php the_posts_pagination(); ?>
	</main><!-- #main -->
				</div><!-- #primary -->

<?php get_footer(); ?>
