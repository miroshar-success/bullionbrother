<?php
/**
 * Add SVG Support help tab to the top of the settings page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function bodhi_svgs_help_tab () {

	$screen = get_current_screen();

	/**
	 * Overview Tab
	 */
	// overview tab content
	$bodhi_svgs_help_tab_overview = '<h3>' . __( 'Overview', 'svg-support' ) . '</h3>';

	$bodhi_svgs_help_tab_overview .= '<p>' . __( 'At it\'s core, SVG Support allows you to upload SVG files and use them as you would any regular image with the added benefit of being scalable and looking great on any screen size, no matter what size it\'s displayed. Additionally, SVG file sizes are (more often than not) much smaller than conventional image formats.', 'svg-support' ) . '</p><p>' . __( 'Even this most basic of usage is very powerful for modern websites, however, there\'s lots of cool stuff you can do with SVG files!', 'svg-support' ) . '</p><p>' . __( 'SVG Support features an "Advanced Mode" which toggles extra features, allowing you to take more control of how your SVG files are used. By rendering SVG files inline, it opens up a huge number of possibilities including animations, embedded links within the SVG, odd shaped link areas, custom CSS targeting elements within the SVG and  whole lot more!', 'svg-support' ) . '</p><p>' . __( 'So let\'s get into some more details! Simply click the tabs to the left to get more of an understanding of how powerful SVG Support is.', 'svg-support' ) . '</p>';

	// register overview tab
	$screen->add_help_tab( array(
		'id'		=> 'bodhi_svgs_help_tab-overview',
		'title'		=> __( 'Overview', 'svg-support' ),
		'content'	=> $bodhi_svgs_help_tab_overview
	));

	/**
	 * The Settings Tab
	 */
	// the settings tab content
	$bodhi_svgs_help_tab_the_settings = '<h3>' . __( 'The Settings', 'svg-support' ) . '</h3>';

	$bodhi_svgs_help_tab_the_settings .= '<p><strong>' . __( 'Restrict To Administrators:', 'svg-support' ) . '</strong><br>' . __( 'SVG files are actually XML code, so allowing regular users to upload them can pose serious security risks. Please leave this checked unless you really know what you\'re doing.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_the_settings .= '<p><strong>' . __( 'Enable Advanced Mode:', 'svg-support' ) . '</strong><br>' . __( 'When using SVG files like regular images just isn\'t enough ;)', 'svg-support' ) . '<br>' . __( 'Enabling "Advanced Mode" displays options to give you more control over how you use SVG files on your site. It also includes extra JS on the front end, so leave this disabled unless you\'re actually using any of the advanced features.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_the_settings .= '<p><strong>' . __( 'Output JS in Footer:', 'svg-support' ) . '</strong><br>' . __( 'This setting allows you to choose whether the SVG Support JS file is enqueued in the header or the footer of the site. Usually you would enqueue in the footer unless you need it to be loaded sooner for some reason.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_the_settings .= '<p><strong>' . __( 'Use Expanded JS:', 'svg-support' ) . '</strong><br>' . __( 'This setting gives you the choice of JS file that is enqueued, the full expanded version or the minified version. You would usually enqueue the minified version, but if you want to bundle the JS file using a caching or minification plugin or similar, then you might want to enqueue the expanded, non-minified version.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_the_settings .= '<p><strong>' . __( 'CSS Class To Target:', 'svg-support' ) . '</strong><br>' . __( 'This allows you to set your own custom class that you will use in your SVG source IMG tags that you would like rendered inline. For example, it might be easier for you to remember to add the class "inline-svg" or something, in which case you would use your desired class name in this field to be used across your site.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_the_settings .= '<p><strong>' . __( 'Automatically Insert Class:', 'svg-support' ) . '</strong><br>' . __( 'When this is checked, you won\'t have to add the class to your SVG files during the embed process in the editor. When you pick your SVG, it will be placed in the editor with just the SVG Support class and others stripped. It does not change existing code, it\'s only a helper to allow you to quickly embed your SVG files and have them render inline without having to fiddle with the classes.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_the_settings .= '<p><strong>' . __( 'Force Inline SVG:', 'svg-support' ) . '</strong><br>' . __( 'This feature is to force all SVG files that are found in your site to be rendered inline. This can help if you aren\'t able to set a custom class on your IMG tags for some reason, usually when used in theme options or page builder elements.', 'svg-support' ) . '</p>';

	// register the settings tab
	$screen->add_help_tab( array(
		'id'		=> 'bodhi_svgs_help_tab-the_settings',
		'title'		=> __( 'The Settings', 'svg-support' ),
		'content'	=> $bodhi_svgs_help_tab_the_settings
	));

	/**
	 * Standard Usage Tab
	 */
	// standard usage tab content
	$bodhi_svgs_help_tab_usage_standard = '<h3>' . __( 'Standard Usage', 'svg-support' ) . '</h3>';

	$bodhi_svgs_help_tab_usage_standard .= '<p>' . __( 'You can simply upload SVG files to your media library like any other image.<br>Make sure to select "Restrict to Administrators" if you only want to allow admins to upload SVG files.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_usage_standard .= '<p>' . __( 'If you want to enable sanitization or minification of uploaded SVG files, please enable advanced settings and then enable sanitization and/or minification.', 'svg-support' ) . '</p>';

	// register standard usage tab
	$screen->add_help_tab( array(
		'id'		=> 'bodhi_svgs_help_tab_usage-standard',
		'title'		=> __( 'Standard Usage', 'svg-support' ),
		'content'	=> $bodhi_svgs_help_tab_usage_standard
	));

	/**
	 * Inline SVG Tab
	 */
	// inline SVG tab content
	$bodhi_svgs_help_tab_inlining_svg = '<h3>' . __( 'Render SVG Inline', 'svg-support' ) . '</h3>';

	$bodhi_svgs_help_tab_inlining_svg .= '<p>' . __( 'You can embed your SVG image like any standard image with the addition of adding the class <code>style-svg</code> (or your custom class) to any IMG tags that you want this plugin to swap out with your actual SVG code.', 'svg-support' ) . '<br>' . __( 'For example:', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_inlining_svg .= '<pre><code>&lt;img class="style-svg" alt="alt-text" src="image-source.svg" /&gt;</code></pre>' . __( 'or', 'svg-support' ) . '<pre><code>&lt;img class="your-custom-class" alt="alt-text" src="image-source.svg" /&gt;</code></pre>';

	$bodhi_svgs_help_tab_inlining_svg .= '<p>' . __( 'The whole IMG tag element will now be dynamically replaced by the actual code of your SVG, making the inner content targetable.', 'svg-support' ) . '<br>' . __( 'This allows you to target elements within your SVG using CSS.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_inlining_svg .= '<p><em>' . __( 'Please Note:', 'svg-support' ) . '</em><br><em>- ' . __( 'You will likely need to set your own height and width in your CSS for SVG files to display correctly.', 'svg-support' ) . '</em><br><em>- ' . __( 'Your uploaded image needs to be an SVG file for this plugin to replace the img tag with the inline SVG code. It will not create SVG files for you.', 'svg-support' ) . '</em><br><em>- ' . __( 'You can set this target class on any element and the script will traverse all children of that target element looking for IMG tags with SVG in the src to replace.', 'svg-support' ) . '</em></p>';

	// register inline SVG tab
	$screen->add_help_tab( array(
		'id'		=> 'bodhi_svgs_help_tab-inlining_svg',
		'title'		=> __( 'Render SVG Inline', 'svg-support' ),
		'content'	=> $bodhi_svgs_help_tab_inlining_svg
	));

	/**
	 * Featured Images Tab
	 */
	// featured images tab content
	$bodhi_svgs_help_tab_featured_images = '<h3>' . __( 'Featured Images', 'svg-support' ) . '</h3>';

	$bodhi_svgs_help_tab_featured_images .= '<p>' . __( 'You can use SVG files as featured images just like any other image format, with the addition of being able to render your featured SVG inline on a per-post basis.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_featured_images .= '<p>' . __( 'To render your featured SVG inline:', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_featured_images .= '<ol><li>' . __( 'Make sure "Advanced Mode" is enabled.', 'svg-support' ) . '</li><li>' . __( 'Add your featured SVG like you would any regular featured image format.', 'svg-support' ) . '</li><li>' . __( 'Publish, Save Draft, or Update the post.', 'svg-support' ) . '</li><li>' . __( 'Once the screen reloads, click the new checkbox below the featured image to render your SVG inline.', 'svg-support' ) . '</li><li>' . __( 'Publish, Save Draft, or Update the post a final time to render the SVG inline.', 'svg-support' ) . '</li></ol>';

	// register featured images tab
	$screen->add_help_tab( array(
		'id'		=> 'bodhi_svgs_help_tab-featured_images',
		'title'		=> __( 'Featured Images', 'svg-support' ),
		'content'	=> $bodhi_svgs_help_tab_featured_images
	));

	/**
	 * Animation Tab
	 */
	$bodhi_svgs_help_tab_animation = '<h3>' . __( 'Animation', 'svg-support' ) . '</h3>';

	$bodhi_svgs_help_tab_animation .= '<p>' . __( 'So you want to animate your SVG?', 'svg-support' ) . '<br>' . __( 'There\'s a number of ways you can animate an SVG. You could use CSS or JS to target elements within your SVG or even embed the animations in the file itself. Whichever way you choose, there is always a little bit of preparation required before uploading your SVG to your media library.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_animation .= '<p><strong>' . __( 'First, let\'s talk about using CSS or JS to target elements within your SVG.', 'svg-support' ) . '</strong><br>' . __( 'Before you upload your SVG, you\'re going to need some classes to target inside your SVG. To do this, open your SVG file in the code editor of choice (I use Sublime Text). You will see each element within your SVG file written in XML code. Each little part of your SVG has it\'s own bit of code, so it\'s up to you which ones you want to target. It\'s in here that you\'ll place your new classes on each element you want to target.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_animation .= '<p>' . __( 'Then there\'s the option of animating the SVG file itself. There is a number of online tools to do this, or you can use the software of your choice. Once your SVG is animated and ready to go, you then upload it like any other image to your WordPress media library. When you embed it on a page/post, you will need to make sure to add the class to the IMG tag so SVG Support can render it inline. This will ensure your animations are displayed.', 'svg-support' ) . '</p>';

	// register animation tab
	$screen->add_help_tab( array(
		'id'		=> 'bodhi_svgs_help_tab-animation',
		'title'		=> __( 'Animation', 'svg-support' ),
		'content'	=> $bodhi_svgs_help_tab_animation
	));

	/**
	 * DONATIONS Tab
	 */
	// donations tab content
	$bodhi_svgs_help_tab_donations = '<h3>' . __( 'Donations', 'svg-support' ) . '</h3>';

	$bodhi_svgs_help_tab_donations .= '<p>' . __( 'SVG Support (this plugin) has grown to be used by over 800,000 websites actively and is maintained solely by one person. I couldn\'t possibly tell you how many hours have gone into the development, maintenance and support of this plugin. If you find it useful and would like to donate to help keep it going, that would be amazing! I truly appreciate the support and how far this has come.', 'svg-support' ) . '</p>';

	$bodhi_svgs_help_tab_donations .= '<p><strong>' . __( 'Donation Methods:', 'svg-support' ) . '</strong></p>';

	$bodhi_svgs_help_tab_donations .= '<p><strong>' . __( 'PayPal: ', 'svg-support' ) . '</strong><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z9R7JERS82EQQ" target="_blank">Click Here</a><br/><strong>' . __( 'BTC: 1qF8r2HkTLifND7WLGfWmvxfXc9ze55DZ', 'svg-support' ) . '</strong><br/><strong>' . __( 'LTC: LUnQPJrSk6cVFmMqBMv5FAqweJbnzRUz4o', 'svg-support' ) . '</strong><br/><strong>' . __( 'ETH: 0x599695Eb51aFe2e5a0DAD60aD9c89Bc8f10B54f4', 'svg-support' ) . '</strong></p>';

	$bodhi_svgs_help_tab_donations .= '<p>' . __( 'Need to buy some crypto to donate?', 'svg-support' ) . '</br>' . __( 'My Coinbase referral link will get $10 USD worth of BTC for free when you spend $100.', 'svg-support' ) . '</br>' . __( '(You don\'t need to send me that much though, anything is appreciated!)', 'svg-support' ) . '<br/><a href="https://www.coinbase.com/join/59be646cb87715012bbdcc6b" target="_blank">https://www.coinbase.com/join/59be646cb87715012bbdcc6b</a></p>';

	// register featured images tab
	$screen->add_help_tab( array(
		'id'		=> 'bodhi_svgs_help_tab-donations',
		'title'		=> __( 'DONATIONS', 'svg-support' ),
		'content'	=> $bodhi_svgs_help_tab_donations
	));

	/**
	 * Help Tab Sidebar
	 */
	// add help tab sidebar
	$screen->set_help_sidebar(
		'<p><strong>' . __( 'For more help, visit:' ) . '</strong></p>' .
		'<p>' . __( '<a target="_blank" href="https://wordpress.org/support/plugin/svg-support">SVG Support Forum</a>' ) . '</p>'
	);

}