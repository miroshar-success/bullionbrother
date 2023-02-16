;(function($){
"use strict";

	// key press event
	$(document).ready(function(){

		$('.woolentor_widget_psa input').keyup( function(e) {
			var $this = $(this);
		    clearTimeout( $.data( this, 'timer' ) );
		    if ( e.keyCode == 13 ){
		    	doSearch( $this );
		    } else {
		    	doSearch( $this );
		    	$(this).data( 'timer', setTimeout( doSearch, 100 ) );
		    }
		});

		$('.woolentor_widget_psa_clear_icon').on('click', function(){
			$(this).closest(".woolentor_widget_psa").find('#woolentor_psa_results_wrapper').html('');
			$(this).parents('.woolentor_widget_psa').removeClass('woolentor_widget_psa_clear');
			$(this).siblings('input[type="search"]').val('');
		});

		// Click Outside
		$(document).mouseup(function(e){
		    var container = $(".woolentor_widget_psa");
		    var hidecontainer = $('#woolentor_psa_results_wrapper');
		    // if the target of the click isn't the container nor a descendant of the container
		    if (!container.is(e.target) && container.has(e.target).length === 0){
		        hidecontainer.hide();
		    }else{
		    	hidecontainer.show();
		    }
		});

	});

	function doSearch( $this = '' ) {

		if ( $this.length > 0 ) {
		    var searchString 	 = $this.val(),
				catagoryValue 	 = $this.closest(".woolentor_widget_psa").find(".woolentor_widget_psa_category select").val(),
				searchResultWrap = $this.closest(".woolentor_widget_psa").find("#woolentor_psa_results_wrapper");
		    if( searchString == '' ){
		    	searchResultWrap.html('');
		    	$this.parents('.woolentor_widget_psa').removeClass('woolentor_widget_psa_clear');
		    }
		    if ( searchString.length < 2 ) return; //wasn't enter, not > 2 char
		    var wrapper_width = $this.parents('.woolentor_widget_psa').width(),
		    settings	= $this.parents('.woolentor_widget_psa form').data('settings'),
		    limit		=	settings.limit ? parseInt(settings.limit) : 10;

		    $.ajax({
		    	url: woolentor_addons.woolentorajaxurl,
		    	data: {
		    		'action': 'woolentor_ajax_search',
					'category': catagoryValue,
		    		's'		: searchString,
		    		'limit'	: limit,
		    		'nonce'	: woolentor_addons.ajax_nonce
		    	},
		    	beforeSend:function(){
		    		$this.parents('.woolentor_widget_psa').addClass('woolentor_widget_psa_loading');
		    	},
		    	success:function(response) {
		    		searchResultWrap.css({'width': wrapper_width});
		    		searchResultWrap.html(response);
		    		$this.parents('.woolentor_widget_psa').removeClass('woolentor_widget_psa_loading');
		    	},
		    	error: function(errorThrown){
		    	    console.log(errorThrown);
		    	}
		    }).done(function(response){
		    	$this.parents('.woolentor_widget_psa').addClass('woolentor_widget_psa_clear');
		    });
		}
		
	}

})(jQuery);