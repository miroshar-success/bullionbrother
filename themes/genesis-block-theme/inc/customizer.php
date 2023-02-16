<?php
/**
 * Genesis Block Theme Customizer.
 *
 * @package Genesis Block Theme
 */

add_action( 'customize_register', 'genesis_block_theme_register' );

if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX && ! is_customize_preview() ) {
	return;
}

/**
 * Sanitize text.
 *
 * @param string $input
 */
function genesis_block_theme_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}


/**
 * Sanitize range slider.
 *
 * @param string $input
 */
function genesis_block_theme_sanitize_range( $input ) {
	filter_var( $input, FILTER_FLAG_ALLOW_FRACTION );
	return ( $input );
}


/**
 * Sanitize select.
 *
 * @param string $input
 * @param object $setting
 */
function genesis_block_theme_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}


/**
 * Get the footer tagline text.
 */
function genesis_block_theme_footer_tagline() {
	return wp_kses_post( get_theme_mod( 'genesis_block_theme_footer_text' ) );
}


/**
 * Get the blog name.
 */
function genesis_block_theme_blog_name() {
	return get_bloginfo( 'name', 'display' );
}


/**
 * Get the blog description.
 */
function genesis_block_theme_blog_description() {
	return get_bloginfo( 'description', 'display' );
}


/**
 * Register customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function genesis_block_theme_register( $wp_customize ) {

	/**
	 * Theme Options Panel.
	 */
	$wp_customize->add_section(
		'genesis_block_theme_theme_options',
		array(
			'priority'   => 1,
			'capability' => 'edit_theme_options',
			'title'      => esc_html__( 'Theme Options', 'genesis-block-theme' ),
		)
	);

	/**
	 * Accent Color.
	 */
	$wp_customize->add_setting(
		'genesis_block_theme_button_color',
		array(
			'default'           => '#0072e5',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'genesis_block_theme_button_color',
			array(
				'label'       => esc_html__( 'Accent Color', 'genesis-block-theme' ),
				'section'     => 'colors',
				'settings'    => 'genesis_block_theme_button_color',
				'description' => esc_html__( 'Change the accent color of buttons and various typographical elements.', 'genesis-block-theme' ),
				'priority'    => 5,
			)
		)
	);

	/**
	 * Footer Tagline.
	 */
	$wp_customize->add_setting(
		'genesis_block_theme_footer_text',
		array(
			'sanitize_callback' => 'genesis_block_theme_sanitize_text',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'genesis_block_theme_footer_text',
		array(
			'label'       => esc_html__( 'Footer Tagline', 'genesis-block-theme' ),
			'section'     => 'genesis_block_theme_theme_options',
			'settings'    => 'genesis_block_theme_footer_text',
			'description' => esc_html__( 'Change the text that appears in the footer tagline at the bottom of your site.', 'genesis-block-theme' ),
			'type'        => 'text',
			'priority'    => 30,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'genesis_block_theme_footer_text',
		array(
			'selector'            => '.site-info',
			'container_inclusive' => false,
			'render_callback'     => genesis_block_theme_footer_tagline(),
		)
	);
}


/**
 * Adjust header height based on theme option.
 */
function genesis_block_theme_css_output() {
	// Theme Options.
	$accent_color = esc_html( get_theme_mod( 'genesis_block_theme_button_color', '#0072e5' ) );

	// Check for styles before outputting.
	if ( $accent_color ) {

		$genesis_block_theme_custom_css = "

	button,
	input[type='button'],
	input[type='submit'],
	.button,
	.page-numbers.current,
	.page-numbers:hover,
	#page #infinite-handle button,
	#page #infinite-handle button:hover,
	.comment-navigation a,
	.su-button,
	.mobile-navigation,
	.toggle-active,
	.main-navigation .menu-cta a:hover {
	      background-color: $accent_color;
	}

	.entry-content p a,
	.entry-content p a:hover,
	.header-text a,
	.header-text a:hover,
	.entry-content .meta-list a,
	.post-navigation a:hover .post-title,
	.entry-header .entry-title a:hover,
	#page .more-link:hover,
	.site-footer a,
	.main-navigation a:hover,
	.main-navigation ul li.current-menu-item a,
	.main-navigation ul li.current-page-item a {
		color: $accent_color;
	}

	.entry-content p a,
	.header-text a {
		box-shadow: inset 0 -1px 0 $accent_color;
	}

	.entry-content p a:hover,
	.header-text a:hover {
		box-shadow: inset 0 -2px 0 $accent_color;
	}

	";

		wp_add_inline_style( 'genesis-block-theme-style', $genesis_block_theme_custom_css );
	} }
add_action( 'wp_enqueue_scripts', 'genesis_block_theme_css_output' );


/**
 * Add postMessage support and selective refresh for site title and description.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function genesis_block_theme_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->selective_refresh->add_partial(
		'header_site_title',
		array(
			'selector'        => '.site-title a',
			'settings'        => array( 'blogname' ),
			'render_callback' => genesis_block_theme_blog_name(),
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header_site_description',
		array(
			'selector'        => '.site-description',
			'settings'        => array( 'blogdescription' ),
			'render_callback' => genesis_block_theme_blog_description(),
		)
	);
}
add_action( 'customize_register', 'genesis_block_theme_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function genesis_block_theme_customize_preview_js() {
	wp_enqueue_script( 'genesis_block_theme_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20180228', true );
}
add_action( 'customize_preview_init', 'genesis_block_theme_customize_preview_js' );


/**
 * Send customization styling to block editor.
 */
function genesis_block_theme_customizer_css_output_for_block_editor() {
	// Theme Options.
	$accent_color = esc_html( get_theme_mod( 'genesis_block_theme_button_color', '#0072e5' ) );

	// CSS for block editor.
	$css  = '';
	$css .= '
		#editor .editor-styles-wrapper p a {
			box-shadow: inset 0 -1px 0 ' . esc_attr( $accent_color ) . ';
			color: ' . esc_attr( $accent_color ) . ';
		}
		#editor .editor-styles-wrapper p a:hover {
			color: ' . esc_attr( $accent_color ) . ';
			box-shadow: inset 0 -2px 0 ' . esc_attr( $accent_color ) . ';
		}
	';
	return wp_strip_all_tags( $css );
}
