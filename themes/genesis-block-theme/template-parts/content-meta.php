<?php
/**
 * The template part for displaying the post meta information
 *
 * @package Genesis Block Theme
 */

?>
	<?php
	// Get the post meta.
	if ( ! is_page() ) {
		?>
		<ul class="meta-list">
			<?php
			// Post categories.
			if ( has_category() ) {
				?>
				<li>
					<span class="meta-title"><?php echo esc_html_e( 'Category:', 'genesis-block-theme' ); ?></span>

					<?php the_category( ', ' ); ?>
				</li>
			<?php } ?>

			<?php
			// Post tags.
			$tags = get_the_tags();
			if ( ! empty( $tags ) ) {
				?>
				<li>
					<span class="meta-title"><?php echo esc_html_e( 'Tag:', 'genesis-block-theme' ); ?></span>
					<?php the_tags( '' ); ?>
				</li>
			<?php } ?>
		</ul><!-- .meta-list -->
	<?php } ?>
