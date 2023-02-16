<?php
/**
 * Styles.
 */

$width = woolentor_get_option_pro( 'width','woolentor_email_customizer_settings', 600 );
$width = absint( $width );
$width = ! empty( $width ) ? $width : 600;
?>

.elementor .elementor-inner,
.elementor .elementor-section-wrap,
.elementor-section,
#elementor-add-new-section,
#woolentor-email-wrapper {
    width: <?php echo $width; ?>px;
    max-width: <?php echo $width; ?>px;
}