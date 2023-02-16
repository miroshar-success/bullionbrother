"use strict";
jQuery(document).ready( function($) {
	hide_points();

	$( document ).on( "click", ".woo-pr-points-editor-popup", function() {

		var username = $(this).parents('._woo_userpoints').siblings( 'td.column-username' ).find( 'strong>a' ).text();
                var user_id = $(this).attr('data-userid');
		var balance = $(this).attr('data-current');
		
                
		$( '#woo_pr_points_user_id' ).html(user_id);
		$( '#woo_pr_points_user_name' ).html(username);
		$( '#woo_pr_points_user_current_balance' ).html(balance);
		
		$( '#woo_pr_points_update_users_balance_amount' ).val('');
		$( '#woo_pr_points_update_users_balance_entry' ).val('');
		
		$( '.woo-pr-points-popup-overlay' ).fadeIn();
        $( '.woo-pr-points-popup-content' ).fadeIn();
	});
	
	//close popup window 
	$( document ).on( "click", ".woo-pr-points-close-button, .woo-pr-points-popup-overlay", function() {
		
		$( '.woo-pr-points-popup-overlay' ).fadeOut();
        $( '.woo-pr-points-popup-content' ).fadeOut();
        
	});

	if(jQuery('.woo-pr-datepicker').length){
		jQuery('.woo-pr-datepicker').datepicker({
			dateFormat : 'yy-m-d',
			minDate: 0,
		}); 
	}
	
	//update user balance
	$( document ).on( "click", "#woo_pr_points_update_users_balance_submit", function() {
		
		var userid = $( 'span#woo_pr_points_user_id' ).text();
		var points = $( '#woo_pr_points_update_users_balance_amount' ).val();
		var log = $( '#woo_pr_points_update_users_balance_entry' ).val();
		var expiry_date = $( '#woo_pr_points_update_users_balance_expiry_date' ).val();
		var issendemail = 'no';

		if( $('#woo_pr_points_update_users_send_email').prop("checked") == true ){

			issendemail = 'yes';

		}
		
		$( '#woo_pr_points_update_users_balance_amount' ).removeClass('woo-pr-points-validate-error');
		
		if( points != '' && $.trim(log).length > 0 ) {
			 
			$('#woo_pr_points_update_users_balance_submit').val( WOO_PR_Points_Admin.processing_balance );
			var data = {
							action		: 'woo_pr_adjust_user_points',
							userid		: userid,
							points		: points,
							expiry_date : expiry_date,
							log			: log,
							issendemail : issendemail
						};
			//call ajax to adjust points
			jQuery.post( ajaxurl, data, function( response ) {

				if( response != 'error' ) {
					$( '#woo_pr_points_user_current_balance' ).html( response );
					$( '#woo_pr_points_user_' + userid + '_balance' ).html( response );
					$( '#woo_pr_points_user_' + userid + '_adjust' ).attr( 'data-current', response );
				}
				$('#woo_pr_points_update_users_balance_amount').val('');
				$('#woo_pr_points_update_users_balance_entry').val('');
				$('#woo_pr_points_update_users_balance_expiry_date').val('');
				$('#woo_pr_points_update_users_balance_submit').val( WOO_PR_Points_Admin.update_balance );
        		
			});
		} else {
			if( points == ''){
				$( '#woo_pr_points_update_users_balance_amount' ).addClass('woo-pr-points-validate-error');
			}
			if( $.trim(log).length <= 0 ){
				$( '#woo_pr_points_update_users_balance_entry' ).addClass('woo-pr-points-validate-error');
			}
		}
	});
	
	$('.woocommerce_page_woo-points-log #filter-by-date option:first').text(WOO_PR_Points_Admin.filter_date_placeholder);
	
	$('.woo-pr-points-dropdown-wrapper select').css('width', '250px').chosen();
	$('select#woo_pr_points_userid').ajaxChosen({
	    method: 		'GET',
	    url: 			ajaxurl,
	    dataType: 		'json',
	    afterTypeDelay: 100,
	    minTermLength: 	1,
	    data: {
		    	action: 		'woo_pr_points_search_users',
		    	select_default: ''
	    }
	}, function (data) {

		var terms = {};

	    jQuery.each(data, function (i, val) {
	        terms[i] = val;
	    });

	    return terms;
	});
	
	//confirmation for applying discount buttons
	$( document ).on( "click", ".woo-pr-points-apply-disocunts-prev-orders", function() {
		
		var confirmdiscount = confirm( WOO_PR_Points_Admin.prev_order_apply_confirm_message );
		 
		if( confirmdiscount ) {
			return true;
		} else {
			return false;
		}
	});

	//confirmation for applying discount buttons
	$( document ).on( "click", ".woo-pr-apply-expiration-previous-points", function() {
		
		var confirmexpiration = confirm( WOO_PR_Points_Admin.prev_points_apply_expiration_confirm_message );
		 
		if( confirmexpiration ) {
			return true;
		} else {
			return false;
		}
	});
	
	$( document ).on( "change", "#woo_pr_product_type", function(){

		hide_points();
	});
	
	function hide_points() {

		var product_type = $('#woo_pr_product_type').find(":selected").val();

		if( product_type == 'points' ) {
			$('#woo_pr_points_and_rewards').hide();
		} else {
			$('#woo_pr_points_and_rewards').show();
		}
	}
	
	// Hide/show the review points setting
	review_points_setting();

	$( document ).on( "change", "#woo_pr_enable_reviews", function(){

		review_points_setting();
	});

	function review_points_setting() {

		var enable_reviews = $('#woo_pr_enable_reviews');
		if( enable_reviews.prop('checked') == false ) {
			$('.woo_pr_review_points').parents('td').parents('tr').hide();
		} else {
			$('.woo_pr_review_points').parents('td').parents('tr').show();
		}
	}

	// Hide/show the account signup setting
	account_signup_points_setting();

	$( document ).on( "change", "#woo_pr_enable_account_signup", function(){

		account_signup_points_setting();
	});

	function account_signup_points_setting() {

		var enable_account_signup = $('#woo_pr_enable_account_signup');
		if( enable_account_signup.prop('checked') == false ) {
			$('#woo_pr_earn_for_account_signup').parents('td').parents('tr').hide();
		} else {
			$('#woo_pr_earn_for_account_signup').parents('td').parents('tr').show();
		}
	}

	// Hide/show the post creation setting
	post_creation_points_setting();

	$( document ).on( "change", "#woo_pr_enable_post_creation_points", function(){

		post_creation_points_setting();
	});

	function post_creation_points_setting() {

		var enable_post_creation = $('#woo_pr_enable_post_creation_points');
		if( enable_post_creation.prop('checked') == false ) {
			$('#woo_pr_post_creation_points').parents('td').parents('tr').hide();
		} else {
			$('#woo_pr_post_creation_points').parents('td').parents('tr').show();
		}
	}

	// Hide/show the product creation setting
	product_creation_points_setting();

	$( document ).on( "change", "#woo_pr_enable_product_creation_points", function(){

		product_creation_points_setting();
	});

	function product_creation_points_setting() {

		var enable_product_creation = $('#woo_pr_enable_product_creation_points');
		if( enable_product_creation.prop('checked') == false ) {
			$('#woo_pr_product_creation_points').parents('td').parents('tr').hide();
		} else {
			$('#woo_pr_product_creation_points').parents('td').parents('tr').show();
		}
	}

	// Hide/show the daily login points setting
	daily_login_points_setting();

	$( document ).on( "change", "#woo_pr_enable_daily_login_points", function(){

		daily_login_points_setting();
	});

	function daily_login_points_setting() {

		var enable_daily_login = $('#woo_pr_enable_daily_login_points');
		if( enable_daily_login.prop('checked') == false ) {
			$('#woo_pr_daily_login_points').parents('td').parents('tr').hide();
		} else {
			$('#woo_pr_daily_login_points').parents('td').parents('tr').show();
		}
	}
	
	// Hide/show the expiration points setting
	woo_pr_expiration_points_setting();
	woo_pr_expiration_points_notice_setting();

	$( document ).on( "change", "#woo_pr_enable_points_expiration", function(){

		woo_pr_expiration_points_setting();
	});

	$( document ).on( "change", "#woo_pr_enable_notice_points_expiration", function(){
		woo_pr_expiration_points_notice_setting();
	});

	function woo_pr_expiration_points_setting() {

		var enable_reviews = $('#woo_pr_enable_points_expiration');		
		var notice_expiration = $('#woo_pr_enable_notice_points_expiration');
		if( enable_reviews.prop('checked') == false ) {
			$('#woo_pr_enable_never_points_expiration_purchased_points').parents('td').parents('tr').hide();
			$('#woo_pr_enable_never_points_expiration_sell_points').parents('td').parents('tr').hide();
			$('#woo_pr_validity_period_days').removeAttr('min').parents('td').parents('tr').hide();
			$('#woo_pr_apply_expiration_previous_points').parents('td').parents('tr').hide();
			$('#woo_pr_expiration_notice_days').parents('td').parents('tr').hide();
			$('#woo_pr_expiration_notice_message').parents('td').parents('tr').hide();
		} else {
			$('#woo_pr_enable_never_points_expiration_purchased_points').parents('td').parents('tr').show();
			$('#woo_pr_enable_never_points_expiration_sell_points').parents('td').parents('tr').show();
			$('#woo_pr_validity_period_days').attr('min',1).parents('td').parents('tr').show();
			$('#woo_pr_apply_expiration_previous_points').parents('td').parents('tr').show();
			$('#woo_pr_enable_notice_points_expiration').parents('td').parents('tr').show();
			
		}
	}

	function woo_pr_expiration_points_notice_setting(){
		var notice_expiration = $('#woo_pr_enable_notice_points_expiration');	
		if( notice_expiration.prop('checked') == false ){
				$('#woo_pr_expiration_notice_days').parents('td').parents('tr').hide();
				$('#woo_pr_expiration_notice_message').parents('td').parents('tr').hide();	
			}
			else {				
				$('#woo_pr_expiration_notice_days').parents('td').parents('tr').show();
				$('#woo_pr_expiration_notice_message').parents('td').parents('tr').show();
			}
	}
	
	// Hide/show the Decimal points setting
	decimal_points_setting();

	$( document ).on( "change", "#woo_pr_enable_decimal_points", function(){

		decimal_points_setting();
	});

	function decimal_points_setting() {

		var enable_reviews = $('#woo_pr_enable_decimal_points');
		if( enable_reviews.prop('checked') == false ) {
			$('#woo_pr_number_decimal').parents('td').parents('tr').hide();
		} else {
			$('#woo_pr_number_decimal').parents('td').parents('tr').show();
		}
	}

	// Hide/show the Earn Points For First Purchase setting
	first_purchase_earn_setting();

	$( document ).on( "change", "#woo_pr_enable_first_purchase_points", function(){

		first_purchase_earn_setting();
	});

	function first_purchase_earn_setting() {

		var enable_reviews = $('#woo_pr_enable_first_purchase_points');
		if( enable_reviews.prop('checked') == false ) {
			$('#woo_pr_first_purchase_earn_points').parents('td').parents('tr').hide();
		} else {
			$('#woo_pr_first_purchase_earn_points').parents('td').parents('tr').show();
		}
	}

	$('#product-type').on('change', function(){
		if( $(this).val() == 'variable'){
			$('.product-points-earned-row').hide();
		} else{
			$('.product-points-earned-row').show();
		}
	});	

	$('.woo_pr_switch').parent('label').addClass('woo_pr_switch_label');
	$('<span class="slider round"> </span>').insertAfter('.woo_pr_switch');


// Hide/show the Export Points select users setting
	 export_points_selected_users_setting();

	$( document ).on( "change", ".woo_pr_export_points_for", function(){

		export_points_selected_users_setting();
	});

	function export_points_selected_users_setting() {
		
		var enable_selected_users = $(".woo_pr_export_points_for:checked").val();		
		if( enable_selected_users == 'selected_users' ) {			
			$('#woo_pr_export_points_for_selected_users').parents('td').parents('tr').show();
		} else {			
			$('#woo_pr_export_points_for_selected_users').parents('td').parents('tr').hide();
		}
	}

	// Hide/show the Export Points select time setting
	 export_points_selected_time_setting();

	$( document ).on( "change", ".woo_pr_export_points_time", function(){

		export_points_selected_time_setting();
	});

	function export_points_selected_time_setting() {
		
		var enable_selected_time_option = $(".woo_pr_export_points_time:checked").val();		
		if( enable_selected_time_option == 'selected_date' ) {			
			$('#woo_pr_export_points_start_date').parents('td').parents('tr').show();
			$('#woo_pr_export_points_end_date').parents('td').parents('tr').show();
		} else {			
			$('#woo_pr_export_points_start_date').parents('td').parents('tr').hide();
			$('#woo_pr_export_points_end_date').parents('td').parents('tr').hide();
		}
	}


	//Export User Points ajax	
	$( document ).on( "click", "#woo_pr_export_user_point", function() {	
		  $("#message").css("display", "none");
		var export_points_for = $("input[name='woo_pr_export_points_for']:checked"). val();
		
		var selected_users = [];
		$.each($("#woo_pr_export_points_for_selected_users option:selected"), function(){            
            selected_users.push($(this).val());
        });

		var points_identified_user 	= $("input[name='woo_pr_export_points_identified_user']:checked"). val();
		var export_points_time 		= $("input[name='woo_pr_export_points_time']:checked"). val();
		var export_points_start_date= $("#woo_pr_export_points_start_date"). val();
		var export_points_end_date	= $("#woo_pr_export_points_end_date"). val();
		
		
		var data = {
			action						: 'woo_pr_export_points_csv',
			export_points_for			: export_points_for,
			selected_users				: selected_users,
			export_points_time 			: export_points_time,
			export_points_start_date 	: export_points_start_date,
			export_points_end_date 		: export_points_end_date,
			points_identified_user 		: points_identified_user,
		};
		
		//call ajax to export points		
		jQuery.post( ajaxurl, data, function( response ) {		

			var is_error = isJson(response);
			
			if(is_error == false ){
	    		var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();

				var date = d.getFullYear() + '-' +
				    ((''+month).length<2 ? '0' : '') + month + '-' +
				    ((''+day).length<2 ? '0' : '') + day;

	              var downloadLink = document.createElement("a");
	              var fileData = ['\ufeff'+response];

	              var blobObject = new Blob(fileData,{
	                 type: "text/csv;charset=utf-8;"
	               });

	              var url = URL.createObjectURL(blobObject);
	              downloadLink.href = url;
	              downloadLink.download = 'rewards-points-'+date+'.csv';              
	             
	              document.body.appendChild(downloadLink);
	              downloadLink.click();
	              document.body.removeChild(downloadLink);
	        }	        
	        else{
	        	var json_obj = JSON.parse(response);
	        	if(json_obj.status == 'error'){	        		
	        		window.location.href = json_obj.e_type
	        	}
	        }
		});
	});

	//Import User Points ajax	
	$( document ).on( "click", "#woo_pr_import_user_point", function() {
		
		$("#woo_pr_import_user_point").addClass('not-active');
		
		var html = '';
	 	var file_data 				 = 	$('.woo_pr_import_csv_file').prop('files')[0];
	 	var import_points_action	 = $("input[name='woo_pr_import_csv_action']:checked"). val();
        $("#message").css("display", "none");
        var form_data 	= 	new FormData();

        form_data.append('file', file_data);
        form_data.append('action', 'woo_pr_import_points_csv');
        form_data.append('import_points_action',import_points_action);
        
        $.ajax({
            type: 'POST',
            url: WOO_PR_Points_Admin.ajaxurl,
            contentType: false,
            processData: false,
            data: form_data,
            success:function(response) {
            	var json_obj = JSON.parse(response);
            	
            	$("#woo_pr_import_user_point").removeClass('not-active');
               	var html = '';
               	if(json_obj.status == 'success'){
               		$('.woo_pr_import_csv_file').val('');
               		window.location.href = json_obj.e_type; 
               	}
               	else{
               		window.location.href = json_obj.e_type;
               	}               
            }
        });
	});

	$( ".datepicker" ).datepicker({
		"dateFormat":'yy-mm-dd',
	});
		


	// Check string is json or not
	function isJson(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}
		
});