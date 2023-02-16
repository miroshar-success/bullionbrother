<?php
/**
 * Lost Password Form
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/global/xoo-el-lostpw-section.php
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/easy-login-woocommerce/
 * @version 2.1
 */


$sySettings 		= xoo_el_helper()->get_style_option();

$btn_style 			= $sySettings['sy-btns-theme'];
$btn_bg_color 		= $sySettings['sy-btn-bgcolor'];
$btn_txt_color 		= $sySettings['sy-btn-txtcolor'];
$btn_height 		= $sySettings['sy-btn-height'];	
$popup_width 		= $sySettings['sy-popup-width'];
$popup_height 		= $sySettings['sy-popup-height'];
$sidebar_img  		= $sySettings['sy-sidebar-img'];
$sidebar_width 		= $sySettings['sy-sidebar-width'];
$sidebar_pos 		= $sySettings['sy-sidebar-pos'];
$popup_pos 			= $sySettings['sy-popup-pos'];
$tab_bg_color 		= $sySettings['sy-tab-bgcolor'];
$tab_actbg_color 	= $sySettings['sy-taba-bgcolor'];
$tab_txt_color 		= $sySettings['sy-tab-txtcolor'];
$tab_acttxt_color 	= $sySettings['sy-taba-txtcolor'];
$popup_padding 		= $sySettings['sy-popup-padding'];

$pop_bg_color 		= $sySettings['sy-popup-bgcolor'];
$pop_txt_color 		= $sySettings['sy-popup-txtcolor'];

$inputs = array(
	'input[type="text"]', 'input[type="password"]', 'input[type="email"]' 
);

$select_input_string = $select_input_placeholder_string = '';

foreach ($inputs as $input) {
	$select_input_string .= '.xoo-el-group '.$input.',';
}

foreach ($inputs as $input) {
	$select_input_placeholder_string .= '.xoo-el-group '.$input.'::placeholder,';
}

$select_input_string 				= rtrim( $select_input_string, ',' );
$select_input_placeholder_string 	= rtrim( $select_input_placeholder_string, ',' );

?>

<?php if( $btn_style === 'custom' ): ?>
	.xoo-el-form-container button.btn.button.xoo-el-action-btn{
		background-color: <?php echo esc_html( $btn_bg_color ) ?>;
		color: <?php echo esc_html( $btn_txt_color ) ?>;
		font-weight: 600;
		font-size: 15px;
		height: <?php echo esc_html( $btn_height ) ?>px;
	}
<?php endif; ?>

.xoo-el-inmodal{
	max-width: <?php echo esc_html( $popup_width ) ?>px;
	max-height: <?php echo esc_html( $popup_height ) ?>px;
}
.xoo-el-sidebar{
	background-image: url(<?php echo esc_html( $sidebar_img ) ?>);
	min-width: <?php echo esc_html( $sidebar_width ) ?>%;
}
.xoo-el-main, .xoo-el-main a , .xoo-el-main label{
	color: <?php echo esc_html( $pop_txt_color ) ?>;
}
.xoo-el-srcont{
	background-color: <?php echo esc_html( $pop_bg_color ) ?>;
}
.xoo-el-form-container ul.xoo-el-tabs li.xoo-el-active {
	background-color: <?php echo esc_html( $tab_actbg_color ) ?>;
	color: <?php echo esc_html( $tab_acttxt_color ) ?>;
}
.xoo-el-form-container ul.xoo-el-tabs li{
	background-color: <?php echo esc_html( $tab_bg_color ) ?>;
	color: <?php echo esc_html( $tab_txt_color ) ?>;
}
.xoo-el-main{
	padding: <?php echo esc_html( $popup_padding ) ?>;
}

.xoo-el-form-container button.xoo-el-action-btn:not(.button){
    font-weight: 600;
    font-size: 15px;
}

<?php if( $sidebar_pos === 'right' ): ?>

	.xoo-el-wrap{
		direction: rtl;
	}
	.xoo-el-wrap > div{
		direction: ltr;
	}

<?php endif; ?>

<?php if($popup_pos  === 'middle'): ?>

	.xoo-el-modal:before {
	    content: '';
	    display: inline-block;
	    height: 100%;
	    vertical-align: middle;
	    margin-right: -0.25em;
	}

<?php else: ?>

	.xoo-el-inmodal{
		margin-top: 40px;
	}

<?php endif; ?>


<?php if( is_user_logged_in() ): ?>

	.xoo-el-login-tgr, .xoo-el-reg-tgr, .xoo-el-lostpw-tgr{
		display: none!important;
	}

<?php endif; ?>