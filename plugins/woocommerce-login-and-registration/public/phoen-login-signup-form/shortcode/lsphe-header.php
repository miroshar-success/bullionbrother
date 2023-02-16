
<div class="woocommerce">
	<p>
		<a href="#" class="header_phoen_login" ><?php _e('Login')?></a> &nbsp;|&nbsp;
		<a href="#" class="header_phoen_signup"><?php _e('Registration')?> </a>
	</p>
</div>
	
<!----------------------- START LOGIN FORM FROM HERE  ----------------------------------->

<?php

    $lsphe_login_styling_setting        = get_option( '_lsphe_login_styling_setting');
    
    $login_username_field_label         = get_option( '_lsphe_un_lbl');
    
    $login_password_field_label         = get_option( '_lsphe_pswd_lbl');

    $common_styling_both_pages          = get_option( '_lsphe_common_setting_style');

    $heading_font_size                  = (!empty($common_styling_both_pages['lsphe_lheading_fsz']))?$common_styling_both_pages['lsphe_lheading_fsz'].'px':'30px'; 

    $heading_color                      = (!empty($common_styling_both_pages['lsphe_heading_color']))?$common_styling_both_pages['lsphe_heading_color']:'black';

    $background_color                   = (!empty($common_styling_both_pages['lsphe_bag_color']))?$common_styling_both_pages['lsphe_bag_color']:'white'; 

    $border_color                      = (!empty($common_styling_both_pages['lsphe_border_color']))?$common_styling_both_pages['lsphe_border_color']:'black'; 

    $border_size                      = (!empty($common_styling_both_pages['lsphe_border_size']))?$common_styling_both_pages['lsphe_border_size'].'px':'0px'; 

    $border_style                      = (!empty($common_styling_both_pages['lsphe_border_style']))?$common_styling_both_pages['lsphe_border_style']:'solid'; 

    $border                             = $border_size.' '.$border_style.' '.$border_color;

    $forget_password_link_label         = (isset($lsphe_login_styling_setting['lsphe_pas_lnk_label']))?$lsphe_login_styling_setting['lsphe_pas_lnk_label']:"Lost You Password"; 

    $forget_password_label_color        = (isset($lsphe_login_styling_setting['lsphe_sheading_color']))?$lsphe_login_styling_setting['lsphe_sheading_color']:"#464646";

    $register_link_label                = (isset($lsphe_login_styling_setting['lsphe_sn_lnk_label']))?$lsphe_login_styling_setting['lsphe_sn_lnk_label']:"Click For Registration";

    $register_link_label_color          = (isset($lsphe_login_styling_setting['lsphe_sn_lnk_labelcolor']))?$lsphe_login_styling_setting['lsphe_sn_lnk_labelcolor']:"#464646";
?>

<div id="id01" style="display: none;">
    
    <div class="popup" style="background: <?= _e($background_color)?>;border: <?= _e($border) ?>;">

        <div class="result1"></div>
     
        <div class="close-btn">&times;</div>
        
        <div class="form">

            <form id="js_phoen_login_popup">
        
                <h2 style="color:<?= _e($heading_color)?>;font-size:<?= _e($heading_font_size)?>;"><?= (!empty($lsphe_login_styling_setting['lsphe_sign_in_text']))?_e($lsphe_login_styling_setting['lsphe_sign_in_text']):'Login'?></h2>
            
                <div class="form-element">
                    <label for="email"><?php (!empty($login_username_field_label))?_e($login_username_field_label):_e('Username or email address ')?><span class="required">*</span></label>
                    <input type="text" name="username" id="username" placeholder="Enter email">
                </div>
            
                <div class="form-element">
                    <label for="password"> <?php (!empty($login_password_field_label))?_e($login_password_field_label):_e('Password')?> <span class="required">*</span></label>
                    <input type="password" name="password" id="password" placeholder="Enter password">
                </div>
                
                <div class="form-element">
                    <input type="hidden" id="_wpnonce" name="_wpnonce" value="70c2c9e9dd">

                    <input id="wp_http_referer" type="hidden" name="_wp_http_referer" value="<?php echo get_site_url(); ?>/my-account/">    

                </div>

                <?php if(get_option('_lsphe_enable_tncond') == 'on'): ?>
                    <p class="form-row">
                        <input type="checkbox" id="checkbox1" name="checkbox_terms" class="input-checkbox">
                        <label for="remember-me">By creating an account you agree to our 
                        <a  href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('terms')) ); ?>" target="_blank"><?php _e( 'Terms & Conditions', 'phoen_login_signup' ); ?></a></label>
                    </p>
                <?php endif; ?>
                
                <div class="form-element">
               
                    <div class="loader1" style="display:none;">
                        <img style="width:8%;" src="<?php echo PLUGINlSPDIRURL.'image/icons8-dots-loading.gif'?>">
                    </div> 
               
                    <input style="width: 91%;" type="submit" class="button js_phoen_login_log" name="login" value="<?= (!empty($lsphe_login_styling_setting['lsphe_sign_in_label']))?_e($lsphe_login_styling_setting['lsphe_sign_in_label']):'Login'?>" id="login1">
                </div>

                 <div class="form-element">

                    <a style="margin-top: 21%;color: <?php _e($forget_password_label_color) ?>;" href="<?php echo get_site_url(); ?>/my-account/lost-password/"><?= _e($forget_password_link_label)?></a>
                </div>

            </form>

        </div>

    </div>

</div>

<!---------------------- START REGISTRATION FORM OPEN HERE  ----------------------------------->

<?php 

    $lsphe_registration_styling_setting = get_option( '_lsphe_registration_setting_style'); 

    $login_link_label_color = (!empty($lsphe_registration_styling_setting['lsphe_lg_lnk_labelcolor']))?$lsphe_registration_styling_setting['lsphe_lg_lnk_labelcolor']:'#464646'; 

    $login_link_label = (!empty($lsphe_registration_styling_setting['lsphe_lg_lnk_label']))?$lsphe_registration_styling_setting['lsphe_lg_lnk_label']:'Click For Login ';
?>

<div id="id02" style="display: none;">
    
    <div class="popup" style="background: <?= _e($background_color)?>;border: <?= _e($border) ?>;">

        <div class="result2"></div>
 
        <div onclick="document.getElementById('id02').style.display='none'" class="close-btn">&times;</div>
    
        <div class="form">
        
            <h2 style="color:<?= _e($heading_color)?>;font-size:<?= _e($heading_font_size)?>;"><?= (!empty($lsphe_registration_styling_setting['lsphe_reg_in_text']))?_e($lsphe_registration_styling_setting['lsphe_reg_in_text']):_e('Register')?></h2>
        
            <?php if($lsphe_registration_styling_setting['lsphe_show_first_name_label'] == '1'): ?>

                <div class="form-element">
                    <label for="registrated_first_name"><?= (!empty($lsphe_registration_styling_setting['lsphe_first_name_label'])) ? $lsphe_registration_styling_setting['lsphe_first_name_label'] :_e('First Name'); ?><span>*</span></label>
                    <input type="text" name="first_name" id="registrated_first_name" placeholder="Enter Your First Name">
                </div>

            <?php endif; ?>
            <?php if($lsphe_registration_styling_setting['lsphe_show_last_name_label'] == '1'): ?>

                <div class="form-element">
                    <label for="registrated_last_name"><?= (!empty($lsphe_registration_styling_setting['lsphe_last_name_label'])) ? $lsphe_registration_styling_setting['lsphe_last_name_label'] :_e('Last Name'); ?><span class="required">*</span></label>
                    <input type="text" name="last_name" id="registrated_last_name" placeholder="Enter Your Last Name">
                </div>

             <?php endif; ?>

            <div class="form-element">
                <label for="reg_email"><?= _e('Email')?><span class="required">*</span></label>
                <input type="email" name="email" id="reg_email" value="" placeholder="Enter Your Email">
            </div>
        
            <div class="form-element">
                <label for="reg_password"><?= _e('Password')?><span class="required">*</span></label>
                <input type="password" name="password" id="reg_password" placeholder="Enter Your Password">
            </div>
            
            <p class="form-row">
                                                
                <input type="hidden" id="_wpnonce" name="_wpnonce" value="70c2c9e9dd">

                <input id="wp_http_referer" type="hidden" name="_wp_http_referer" value="<?php echo get_site_url(); ?>/my-account/">    

                <div class="loader_reg" style="display:none;" >
                    <img style="width:8%;" src="<?php echo PLUGINlSPDIRURL.'image/icons8-dots-loading.gif'?>">
                </div>          
                    
            </p>
            
            <div class="form-element">
                
                <input style="width: 91%;" type="submit" class="button phoen_registration" name="register_header" value="<?= (!empty($lsphe_registration_styling_setting['lsphe_reg_in_label']))?_e($lsphe_registration_styling_setting['lsphe_reg_in_label']):_e('Register')?>">
            </div>

        </div>

    </div>

</div>

<script>
jQuery(document).ready(function(){ 
    jQuery('.header_phoen_login').click(function() {  
       	jQuery(this).colorbox({className: 'plrp_logreg', height:"525px", inline:true, href:"#id01", opacity:0.5});
       	jQuery('.plrp_logreg').show();
        jQuery('#id01').show();
   	});

   	jQuery('.header_phoen_signup').click(function() {  
       	jQuery(this).colorbox({className: 'plrp_logreg', height:"525px", inline:true, href:"#id02", opacity:0.5});
       	jQuery('.plrp_logreg').show();
        jQuery('#id02').show();
   	});

   	jQuery('.close-btn').click(function(){

   		jQuery('#id01').hide();
   		jQuery('#id02').hide();
   		jQuery('.plrp_logreg').hide();
   	})

});
</script>