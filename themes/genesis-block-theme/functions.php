<?php
/**
 * Genesis Block Theme functions and definitions
 *
 * @package Genesis Block Theme
 */

if ( ! function_exists( 'genesis_block_theme_setup' ) ) :
	/**
	 * Sets up Genesis Block Theme's defaults and registers support for various WordPress features.
	 */
	function genesis_block_theme_setup() {
		/*
		* Add page template switcher watching.
		*/
		require_once get_template_directory() . '/inc/admin/page-template-toggle/php/page-template-toggle.php';

		/*
		 * Tell WordPress that this theme supports the way Gutenberg parses and replaces the style-editor.css file in wp-admin.
		 * @see: https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles
		 */
		add_theme_support( 'editor-styles' );

		/*
		 * Tell WordPress to load the "Gutenberg Theme" stylesheet on the frontend and in the editor.
		 * @see: https://developer.wordpress.org/block-editor/developers/themes/theme-support/#default-block-styles
		 * @see: https://github.com/WordPress/gutenberg/commit/429558ad320c55e3e8b5236dfb6ce139fa3a7d25
		 */
		add_theme_support( 'wp-block-styles' );

		/**
		 * Add support for custom line heights.
		 */
		add_theme_support( 'custom-line-height' );

		/**
		 * Add styles to post editor.
		 */
		add_editor_style( array( genesis_block_theme_fonts_url(), 'style-editor.css' ) );

		/*
		* Make theme available for translation.
		*/
		load_theme_textdomain( 'genesis-block-theme', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/*
		* Post thumbnail support and image sizes.
		*/
		add_theme_support( 'post-thumbnails' );

		/*
		* Add title output.
		*/
		add_theme_support( 'title-tag' );

		/**
		 * Custom Background support.
		 */
		$defaults = array(
			'default-color' => 'ffffff',
		);
		add_theme_support( 'custom-background', $defaults );

		/**
		 * Add wide image support.
		 */
		add_theme_support( 'align-wide' );

		/**
		 * Selective Refresh for Customizer.
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add excerpt support to pages.
		add_post_type_support( 'page', 'excerpt' );

		// Featured image.
		add_image_size( 'genesis-block-theme-featured-image', 1200 );

		// Wide featured image.
		add_image_size( 'genesis-block-theme-featured-image-wide', 1400 );

		// Logo size.
		add_image_size( 'genesis-block-theme-logo', 300 );

		/**
		 * Register Navigation menu.
		 */
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'genesis-block-theme' ),
				'footer'  => esc_html__( 'Footer Menu', 'genesis-block-theme' ),
			)
		);

		/**
		 * Add Site Logo feature.
		 */
		add_theme_support(
			'custom-logo',
			array(
				'header-text' => array( 'titles-wrap' ),
				'size'        => 'genesis-block-theme-logo',
			)
		);

		/**
		 * Enable HTML5 markup.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'gallery',
				'style',
				'script',
			)
		);

		// Make media embeds responsive.
		add_theme_support( 'responsive-embeds' );
		add_filter(
			'body_class',
			function( $classes ) {
				$classes[] = 'wp-embed-responsive';
				return $classes;
			}
		);
	}
endif; // genesis_block_theme_setup.
add_action( 'after_setup_theme', 'genesis_block_theme_setup' );

/**
 * Register widget area.
 */
function genesis_block_theme_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer - Column 1', 'genesis-block-theme' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Widgets added here will appear in the left column of the footer.', 'genesis-block-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer - Column 2', 'genesis-block-theme' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Widgets added here will appear in the center column of the footer.', 'genesis-block-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer - Column 3', 'genesis-block-theme' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Widgets added here will appear in the right column of the footer.', 'genesis-block-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'genesis_block_theme_widgets_init' );


if ( ! function_exists( 'genesis_block_theme_fonts_url' ) ) :
	/**
	 * Return the Google font stylesheet URL.
	 *
	 * @return string
	 */
	function genesis_block_theme_fonts_url() {

		$fonts_url = '';

		/*
		 * Translators: If there are characters in your language that are not
		 * supported by these fonts, translate this to 'off'. Do not translate
		 * into your own language.
		 */

		$font = esc_html_x( 'on', 'Public Sans font: on or off', 'genesis-block-theme' );

		if ( 'off' !== $font ) {
			$fonts_url = get_template_directory_uri() . '/inc/fonts/css/font-style.css';
		}

		return $fonts_url;
	}
endif;


/**
 * Enqueue scripts and styles.
 */
function genesis_block_theme_scripts() {

	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'genesis-block-theme-style', get_stylesheet_uri(), [], $version );

	/**
	* Load fonts.
	*/
	wp_enqueue_style( 'genesis-block-theme-fonts', genesis_block_theme_fonts_url(), [], null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- see https://core.trac.wordpress.org/ticket/49742

	/**
	 * Icons stylesheet.
	 */
	wp_enqueue_style( 'gb-icons', get_template_directory_uri() . '/inc/icons/css/icon-style.css', [], $version, 'screen' );

	/**
	 * Load Genesis Block Theme's javascript.
	 */
	wp_enqueue_script( 'genesis-block-theme-js', get_template_directory_uri() . '/js/genesis-block-theme.js', [ 'jquery' ], $version, true );

	/**
	 * Localizes the genesis-block-theme-js file.
	 */
	wp_localize_script(
		'genesis-block-theme-js',
		'genesis_block_theme_js_vars',
		array(
			'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
		)
	);

	/**
	 * Load the comment reply script.
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'genesis_block_theme_scripts' );


/**
 * Enqueue admin scripts and styles in editor.
 *
 * @param string $hook The admin page.
 */
function genesis_block_theme_admin_scripts( $hook ) {
	if ( 'post.php' !== $hook ) {
		return;
	}

	/**
	* Load editor fonts.
	*/
	wp_enqueue_style( 'genesis-block-theme-admin-fonts', genesis_block_theme_fonts_url(), [], null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- see https://core.trac.wordpress.org/ticket/49742

}
add_action( 'admin_enqueue_scripts', 'genesis_block_theme_admin_scripts', 5 );


/**
 * Enqueue customizer styles for the block editor.
 */
function genesis_block_theme_customizer_styles_for_block_editor() {
	/**
	 * Styles from the customizer.
	 */
	wp_register_style( 'genesis-block-theme-customizer-styles', false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_style( 'genesis-block-theme-customizer-styles' );
	wp_add_inline_style( 'genesis-block-theme-customizer-styles', genesis_block_theme_customizer_css_output_for_block_editor() );
}
add_action( 'enqueue_block_editor_assets', 'genesis_block_theme_customizer_styles_for_block_editor' );


/**
 * Load block editor scripts.
 */
function genesis_block_theme_block_editor_scripts() {

	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$current_screen = get_current_screen();
	$post_type      = $current_screen->post_type ?: '';

	// Remove Title Toggle.
	if ( $post_type === 'page' ) {
		$title_toggle_meta = require_once 'js/title-toggle/title-toggle.asset.php';
		wp_enqueue_script(
			'genesis-block-theme-title-toggle',
			get_template_directory_uri() . '/js/title-toggle/title-toggle.js',
			$title_toggle_meta['dependencies'],
			$title_toggle_meta['version'],
			true
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'genesis_block_theme_block_editor_scripts' );


/**
 * Register _genesis-block-theme-tittle-toggle meta.
 */
function genesis_block_theme_register_post_meta() {
	$args = [
		'auth_callback' => '__return_true',
		'type'          => 'boolean',
		'single'        => true,
		'show_in_rest'  => true,
	];
	register_meta( 'post', '_genesis_block_theme_hide_title', $args );
}
add_action( 'init', 'genesis_block_theme_register_post_meta' );


/**
 * Custom template tags for Genesis Block Theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Customizer theme options.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Theme Updates.
 */
require get_template_directory() . '/inc/updates/updates.php';


/**
 * Add button class to next/previous post links.
 *
 * @return string
 */
function genesis_block_theme_posts_link_attributes() {
	return 'class="button"';
}
add_filter( 'next_posts_link_attributes', 'genesis_block_theme_posts_link_attributes' );
add_filter( 'previous_posts_link_attributes', 'genesis_block_theme_posts_link_attributes' );


/**
 * Add layout style class to body.
 *
 * @param array $classes Original body classes.
 * @return array Modified body classes.
 */
function genesis_block_theme_layout_class( $classes ) {

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_single() && has_post_thumbnail() || is_page() && has_post_thumbnail() ) {
		$classes[] = 'has-featured-image';
	}

	$featured_image = get_theme_mod( 'genesis_block_theme_featured_image_style', 'wide' );

	if ( $featured_image === 'wide' ) {
		$classes[] = 'featured-image-wide';
	}

	return $classes;
}
add_filter( 'body_class', 'genesis_block_theme_layout_class' );


/**
 * Add featured image class to posts.
 *
 * @param array $classes Original body classes.
 * @return array Modified body classes.
 */
function genesis_block_theme_featured_image_class( $classes ) {
	global $post;

	$classes[] = 'post';

	// Check for featured image.
	$classes[] = has_post_thumbnail() ? 'with-featured-image' : 'without-featured-image';

	$page_template = get_post_meta( $post->ID, '_wp_page_template', true );

	if ( $page_template === 'templates/template-wide-image.php' ) {
		$classes[] = 'has-wide-image';
	}

	return $classes;
}
add_filter( 'post_class', 'genesis_block_theme_featured_image_class' );


/**
 * Adjust the grid excerpt length for portfolio items.
 *
 * @return int
 */
function genesis_block_theme_search_excerpt_length() {
	return 40;
}


/**
 * Add an ellipsis read more link.
 *
 * @param string $more The original more.
 *
 * @return string
 */
function genesis_block_theme_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}

	return ' &hellip;';
}
add_filter( 'excerpt_more', 'genesis_block_theme_excerpt_more' );


/**
 * Full size image on attachment pages.
 *
 * @param string $p The attachment HTML output.
 *
 * @return string|none
 */
function genesis_block_theme_attachment_size( $p ) {
	if ( is_attachment() ) {
		return '<p>' . wp_get_attachment_link( 0, 'full-size', false ) . '</p>';
	}
}
add_filter( 'prepend_attachment', 'genesis_block_theme_attachment_size' );


/**
 * Add a js class.
 */
function genesis_block_theme_html_js_class() {
	echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>' . "\n";
}
add_action( 'wp_head', 'genesis_block_theme_html_js_class', 1 );


/**
 * Replaces the footer tagline text.
 *
 * @return string
 */
function genesis_block_theme_filter_footer_text() {

	// Get the footer copyright text.
	$footer_copy_text = get_theme_mod( 'genesis_block_theme_footer_text' );

	if ( $footer_copy_text ) {
		// If we have footer text, use it.
		$footer_text = $footer_copy_text;
	} else {
		// Otherwise show the fallback theme text.
		/* translators: %s: child theme author URL */
		$footer_text = sprintf( esc_html__( ' Theme by %1$s.', 'genesis-block-theme' ), '<a href="https://www.studiopress.com/" rel="noreferrer noopener">StudioPress</a>' );
	}

	return wp_kses_post( $footer_text );

}
add_filter( 'genesis_block_theme_footer_text', 'genesis_block_theme_filter_footer_text' );

/**
 * Check whether the current screen is a Gutenberg Block Editor, or not.
 *
 * @return bool
 */
function genesis_block_theme_is_block_editor() {

	// If the get_current_screen function doesn't exist, we're not even in wp-admin.
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}

	// Get the WP_Screen object.
	$current_screen = get_current_screen();

	// Check to see if this version of WP_Screen has the is_block_editor_method.
	if ( ! method_exists( $current_screen, 'is_block_editor' ) ) {
		return false;
	}

	if ( ! $current_screen->is_block_editor() ) {
		return false;
	}

	// This is a Gutenberg Block Editor page.
	return true;
}
