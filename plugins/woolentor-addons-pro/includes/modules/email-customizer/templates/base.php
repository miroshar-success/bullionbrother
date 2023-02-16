<?php
/**
 * Base.
 */

$args  = ( ( isset( $args ) && is_array( $args ) ) ? $args : null );
$email = ( ( isset( $email ) && is_object( $email ) ) ? $email : null );
$order = ( ( isset( $order ) && is_object( $order ) ) ? $order : null );

$_REQUEST['woolentor_email_args'] = (array) $args;
?>
<div id="woolentor-email-wrapper"><?php do_action( 'woolentor_email_content', $email, $order, $args ); ?></div>