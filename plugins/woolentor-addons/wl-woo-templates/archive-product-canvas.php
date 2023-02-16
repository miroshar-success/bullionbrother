<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( class_exists('\Elementor\Plugin') ){
	\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-canvas' );
}

$template_id = Woolentor_Manage_WC_Template::instance()->archive_template_id();
$width       = Woolentor_Manage_WC_Template::get_template_width( $template_id );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
	<body <?php body_class(); ?>>
		<?php do_action( 'elementor/page_templates/canvas/before_content' ); ?>
			<div class="woolentor-template-container" style="margin:0 auto; max-width:<?php echo $width ? $width.'px; padding: 0 15px;' : '100%;'; ?>">
				<?php do_action( 'woolentor_woocommerce_archive_product_content' ); ?>
			</div>
		<?php
			do_action( 'elementor/page_templates/canvas/after_content' );
			wp_footer();
		?>
	</body>
</html>