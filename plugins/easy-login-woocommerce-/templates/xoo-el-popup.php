<?php
/**
 * The template is for popup design
 *
 * This template can be overridden by copying it to yourtheme/templates/easy-login-woocommerce/xoo-el-popup.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/easy-login-woocommerce/
 * @version 2.1
 */

if ( ! defined( 'ABSPATH' ) ) { 
        exit; // Exit if accessed directly
}


?>

<div class="xoo-el-container" style="visibility: hidden;">
    <div class="xoo-el-opac"></div>
    <div class="xoo-el-modal">
        <div class="xoo-el-inmodal">
            <span class="xoo-el-close xoo-el-icon-cancel-circle"></span>
            <div class="xoo-el-wrap">
                <div class="xoo-el-sidebar"></div>
                <div class="xoo-el-srcont">
                    <div class="xoo-el-main">
                        <?php
                            $args = array(
                                'form_class'    => 'xoo-el-form-popup',
                                'display'       => 'popup'
                            );  
                            xoo_el_get_form( $args )
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>