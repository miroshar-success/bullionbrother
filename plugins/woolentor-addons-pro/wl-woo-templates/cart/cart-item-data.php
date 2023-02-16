<?php
/**
 * Cart item data (when outputting non-flat)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-item-data.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$type                = !empty( $type ) ? 'wl-sku' : 'wl-variations';
$enable_swatch_color = apply_filters( 'woolentor_eanble_cart_swatch_color', true );
$color               = '';
?>
<div class="woolentor-cart-product-meta <?php echo esc_attr( $type ); ?>">
	<?php foreach ( $item_data as $data ) :
			if ( $enable_swatch_color && taxonomy_exists( 'pa_'. $data['key'] ) ){
				$term = get_term_by('name', $data['value'], 'pa_'. $data['key']);

				// Add swatchly color swatch support
				if ( !is_wp_error( $term ) && $term && $term->term_id ) {
					$color = get_term_meta( $term->term_id, 'swatchly_color', true );;
				}
			}

			// @todo Add support for custom variation
		?>
		<div class="<?php echo esc_attr( $data['key'] ); ?>">
			<span class="wl-variation-key <?php echo sanitize_html_class( 'variation-' . $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?>:</span> 
			<?php printf( '<span class="wl-variation-value %s" %s>%s</span>',
				sanitize_html_class( 'variation-' . $data['key'] ),
				$color ? 'data-swatch_color="'. esc_attr( $color ) .'" style="background-color:'.esc_attr( $color ).'"' : '',
				wp_kses_post( $data['display'] )
			); ?>
		</div>
	<?php endforeach; ?>
</div>