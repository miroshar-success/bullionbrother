<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = array( $uniqClass, 'woolentor-product-addtocart' );
!empty( $settings['className'] ) ? $areaClasses[] = $settings['className'] : '';

$product = wc_get_product();
if ( empty( $product ) ) { return; }
echo '<div class="'.implode(' ', $areaClasses ).'">';
?>
	<div class="<?php echo esc_attr( wc_get_product()->get_type() ); ?>">
		<?php woocommerce_template_single_add_to_cart(); ?>
	</div>
<?php
echo '</div>';