<?php

$iconbgcolor 	= $sy_options['s-icon-bgcolor'];
$iconcolor 		= $sy_options['s-icon-color'];
$iconsize 		= $sy_options['s-icon-size'];
$iconwidth 		= $sy_options['s-icon-width'];
$iconborcolor	= $sy_options['s-icon-borcolor'];
$fieldmargin 	= $sy_options['s-field-bmargin'];
$inputbgcolor 	= $sy_options['s-input-bgcolor'];
$inputtxtcolor 	= $sy_options['s-input-txtcolor'];
$focusbgcolor 	= $sy_options['s-input-focusbgcolor'];
$focustxtcolor 	= $sy_options['s-input-focustxtcolor'];

?>

.xoo-aff-input-group .xoo-aff-input-icon{
	background-color: <?php echo esc_html( $iconbgcolor ); ?>;
	color: <?php echo esc_html( $iconcolor ); ?>;
	max-width: <?php echo esc_html( $iconwidth ); ?>px;
	min-width: <?php echo esc_html( $iconwidth ); ?>px;
	border: 1px solid <?php echo esc_html( $iconborcolor ); ?>;
	border-right: 0;
	font-size: <?php echo esc_html( $iconsize ); ?>px;
}
.xoo-aff-group{
	margin-bottom: <?php echo esc_html( $fieldmargin ); ?>px;
}

.xoo-aff-group input[type="text"], .xoo-aff-group input[type="password"], .xoo-aff-group input[type="email"], .xoo-aff-group input[type="number"], .xoo-aff-group select, .xoo-aff-group select + .select2{
	background-color: <?php echo esc_html( $inputbgcolor ); ?>;
	color: <?php echo esc_html( $inputtxtcolor ); ?>;
}

.xoo-aff-group input[type="text"]::placeholder, .xoo-aff-group input[type="password"]::placeholder, .xoo-aff-group input[type="email"]::placeholder, .xoo-aff-group input[type="number"]::placeholder, .xoo-aff-group select::placeholder{
	color: <?php echo esc_html( $inputtxtcolor ); ?>;
	opacity: 0.7;
}

.xoo-aff-group input[type="text"]:focus, .xoo-aff-group input[type="password"]:focus, .xoo-aff-group input[type="email"]:focus, .xoo-aff-group input[type="number"]:focus, .xoo-aff-group select:focus, .xoo-aff-group select + .select2:focus{
	background-color: <?php echo esc_html( $focusbgcolor ); ?>;
	color: <?php echo esc_html( $focustxtcolor ) ?>;
}


<?php if( $sy_options['s-show-icons'] !== "yes" ): ?>

	.xoo-aff-input-group .xoo-aff-input-icon{
		display: none!important;
	}

<?php else: ?>

	.xoo-aff-group input[type="text"], .xoo-aff-group input[type="password"], .xoo-aff-group input[type="email"], .xoo-aff-group input[type="number"], .xoo-aff-group select{
		border-bottom-left-radius: 0;
		border-top-left-radius: 0;
	}

<?php endif; ?>