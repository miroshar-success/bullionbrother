<?php
/**
 * The template used for displaying standard post content
 *
 * @package Genesis Block Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> aria-label="<?php the_title_attribute(); ?>">
	<div class="post-content">
		<header class="entry-header">
			<?php if ( is_single() ) { ?>
				<h1 class="entry-title">
					<?php the_title(); ?>
				</h1>
			<?php } else { ?>
				<h2 class="entry-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2>
			<?php } ?>

			<?php genesis_block_theme_post_byline(); ?>
		</header>
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="featured-image">
				<?php if ( is_single() ) { ?>
					<?php the_post_thumbnail( 'genesis-block-theme-featured-image' ); ?>
				<?php } else { ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'genesis-block-theme-featured-image' ); ?></a>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="entry-content">

			<?php
			if ( ! is_single() && has_excerpt() ) {
				the_excerpt();
			} else {
				// Get the content.
				the_content( esc_html__( 'Read More', 'genesis-block-theme' ) . ' <span class="screen-reader-text">' . __( 'about ', 'genesis-block-theme' ) . get_the_title() . '</span>' );
			}

			// Post pagination links.
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'genesis-block-theme' ),
					'after'  => '</div>',
				)
			);

			if ( is_single() ) {
				// Post meta sidebar.
				get_template_part( 'template-parts/content-meta' );

				// Author profile box.
				genesis_block_theme_author_box();

				// Post navigations.
				if ( is_single() ) {
					if ( get_next_post() || get_previous_post() ) {
						genesis_block_theme_post_navs();
					}
				}

				// Comments template.
				comments_template();
			}
			?>
		</div><!-- .entry-content -->
	</div><!-- .post-content-->

</article><!-- #post-## -->
