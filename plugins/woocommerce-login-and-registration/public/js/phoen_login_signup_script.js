jQuery(document).ready(function(){ 

    jQuery('.js_phoen_login_log').click(function(event) {  
  
        event.preventDefault();

        let username = jQuery(this).closest('#js_phoen_login_popup').find('input[name="username"]').val();

        let userpass = jQuery(this).closest('#js_phoen_login_popup').find('input[name="password"]').val();

        let checkbox_terms = jQuery(this).closest('#js_phoen_login_popup').find('input[name="checkbox_terms"]:checked').val();

        let wpnonce = jQuery(this).closest('#js_phoen_login_popup').find('input[name="_wpnonce_phoe_login_pop_form"]').val();

        let wp_http_referer = jQuery(this).closest('#js_phoen_login_popup').find('input[name="_wp_http_referer"]').val();

        jQuery(".loader1").show();
            
            jQuery.ajax({
                type: 'POST',
                url : woo_log_ajaxurl.ajaxurl,
                data : {                
                        action : 'val_header',
                        username : username,
                        password : userpass,
                        checkbox_terms : checkbox_terms,
                        wpnonce : wpnonce
                        }, 
            
                success: function(data,status){

                    if(data == '1') {
                        
                        jQuery(".loader1").hide();
                        window.location.href = wp_http_referer;
                        
                    }else { 
                    
                        jQuery(".loader1").hide();
                        setTimeout(function() {
                            jQuery('.toast').fadeOut('fast');
                        }, 2000);
                       jQuery(".result1").html(data);
                    }
                }

            });
    });

    jQuery('.phoen_registration').click(function(event) {  
  
        event.preventDefault();

        let f_name = jQuery(this).closest('#phoen_signup_popup').find('input[name="first_name"]').val();
       
        let l_name = jQuery(this).closest('#phoen_signup_popup').find('input[name="last_name"]').val();
        
        let u_email = jQuery(this).closest('#phoen_signup_popup').find('input[name="email"]').val();
        
        let u_passd = jQuery(this).closest('#phoen_signup_popup').find('input[name="password"]').val();
        
        let wp_http_referer = jQuery(this).closest('#phoen_signup_popup').find('input[name="_wp_http_referer"]').val();
        
        let wpnonce = jQuery(this).closest('#phoen_signup_popup').find('input[name="_wpnonce_phoe_register_pop_form"]').val();

        jQuery(".loader_reg").show();
            
        jQuery.ajax({
            type: 'POST',
            url : woo_log_ajaxurl.ajaxurl,
            data : {                
                    action : 'val_header_signup',
                    fname:f_name,
                    lname : l_name,
                    email : u_email,
                    password : u_passd,
                    wpnonce : wpnonce
                    },
            success: function(data,status) {
            
                if(data == '1'){
                    
                    jQuery(".loader_reg").hide();
                    window.location.href = wp_http_referer;

                }else{ 
                    jQuery(".loader_reg").hide();
                    setTimeout(function() {
                        jQuery('.toast').fadeOut('fast');
                    }, 2000);
                   jQuery(".result2").html(data);
                } 
                
           }

        }); 

    });

});