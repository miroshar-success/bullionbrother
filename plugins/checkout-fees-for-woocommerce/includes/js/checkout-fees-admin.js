/**
 * checkout-fees-admin.js.
 *
 * @version 2.3.2
 */

jQuery(document).ready(function() {
	jQuery("div.alg_checkout_fees input[name='tabs']").click(function(){
		jQuery("div.alg_checkout_fees label").each( function () {
			jQuery(this).removeClass('alg-clicked');
		});
		jQuery("div.alg_checkout_fees label[for='"+this.id+"']").addClass('alg-clicked');
	});
});
