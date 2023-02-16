<?php
/**
 * This template displays the search form.
 *
 * @package Genesis Block Theme
 */

$search_unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label for="<?php echo esc_attr( $search_unique_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'genesis-block-theme' ); ?></label>

		<input id="<?php echo esc_attr( $search_unique_id ); ?>" type="text" value="<?php echo get_search_query(); ?>" name="s" class="search-input" placeholder="<?php esc_attr_e( 'Search here...', 'genesis-block-theme' ); ?>" />

		<button class="searchsubmit" type="submit" aria-label="Search">
			<i class="gbi gbicon-search" aria-hidden="true"></i>
		</button>
	</div>
</form>
