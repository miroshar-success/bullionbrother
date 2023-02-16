<?php
/**
 * Genesis Block Theme Page Template Toggle server side code.
 *
 * @package Genesis Block Theme
 */

/**
 * Enqueue the javascript and CSS for the page template toggle.
 */
function genesis_block_theme_page_template_toggle_enqueue_scripts() {

	// Only do this if we are editing a page.
	if ( get_post_type() !== 'page' ) {
		return;
	}

	// And also only do this if we are editing a page using the Block Editor (Gutenberg).
	if ( ! genesis_block_theme_is_block_editor() ) {
		return false;
	}

	$page_template_toggle_meta = require get_template_directory() . '/js/page-template-toggle/page-template-toggle.asset.php';
	$dependencies              = $page_template_toggle_meta['dependencies'];
	$dependencies[]            = 'jquery';

	wp_enqueue_script(
		'genesis-block-theme-page-template-toggle',
		get_template_directory_uri() . '/js/page-template-toggle/page-template-toggle.js',
		$dependencies,
		$page_template_toggle_meta['version'],
		true
	);

	/*
	 * Add style to support the full-width template in Gutenberg.
	 * We have to do this here instead of style-editor.css because style-editor.css replaces "body" with ".editor-styles-wrapper".
	 * But we need to target "body" so we can't have WP replace "body" with ".editor-styles-wrapper" in this case.
	 *
	 * @see: https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles
	*/
	echo '<style>body.page-template-full-width .wp-block{ padding: 0; max-width: 100%; }</style>';
}
add_action( 'admin_enqueue_scripts', 'genesis_block_theme_page_template_toggle_enqueue_scripts' );

/**
 * Output an element in the admin footer so that we can replace it with our react element which watches for page template changes.
 */
function genesis_block_theme_page_template_toggle_element() {

	// Only do this if we are editing a page.
	if ( get_post_type() !== 'page' ) {
		return;
	}

	// And also only do this if we are editing a page using the Block Editor (Gutenberg).
	if ( ! genesis_block_theme_is_block_editor() ) {
		return false;
	}

	echo '<div id="genesis-block-theme-page-template-toggle-watcher"></div>';
}
add_action( 'admin_footer', 'genesis_block_theme_page_template_toggle_element' );
