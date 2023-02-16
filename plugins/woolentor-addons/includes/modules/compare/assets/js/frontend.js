;(function($){
"use strict";
    
    var $body = $('body'),
        $popup = $('.htcompare-popup');

    // Add product in compare table
    $body.on('click', 'a.htcompare-btn', function (e) {
        var $this = $(this),
            id = $this.data('product_id'),
            addedText = $this.data('added-text');

        if( evercompare.popup === 'yes' ){
            e.preventDefault();
            if ( $this.hasClass('added') ) {
                $body.find('.htcompare-popup').addClass('open');
                return true;
            }
        }else{
            if ( $this.hasClass('added') ) return true;
        }

        e.preventDefault();

        $this.addClass('loading');

        $.ajax({
            url: evercompare.ajaxurl,
            data: {
                action: 'ever_compare_add_to_compare',
                id: id,
            },
            dataType: 'json',
            method: 'GET',
            success: function ( response ) {
                if ( response.table ) {
                    updateCompareData( response );
                    $popup.addClass('open');
                } else {
                    console.log( 'Something wrong loading compare data' );
                }
            },
            error: function ( data ) {
                console.log('Something wrong with AJAX response.');
            },
            complete: function () {
                $this.removeClass('loading').addClass('added');
                $this.html('<span class="htcompare-btn-text">'+addedText+'</span>');
            },
        });

    });

    // Remove data from compare table
    $body.on('click', 'a.htcompare-remove', function (e) {
        var $table = $('.htcompare-table');

        e.preventDefault();
        var $this = $(this),
            id = $this.data('product_id');

        $table.addClass('loading');
        $this.addClass('loading');

        jQuery.ajax({
            url: evercompare.ajaxurl,
            data: {
                action: 'ever_compare_remove_from_compare',
                id: id,
            },
            dataType: 'json',
            method: 'GET',
            success: function (response) {
                if (response.table) {
                    updateCompareData(response);
                } else {
                    console.log( 'Something wrong loading compare data' );
                }
            },
            error: function (data) {
                console.log('Something wrong with AJAX response.');
            },
            complete: function () {
                $table.removeClass('loading');
                $this.addClass('loading');
            },
        });

    });

    // Update table HTML
    function updateCompareData( data ) {
        if ( $('.htcompare-table').length > 0 ) {
            $('.htcompare-table').replaceWith( data.table );
            $('.evercompare-copy-link').on('click',function(e){
                evercompareCopyToClipboard( $(this).closest('.ever-compare-shareable-link').find('.evercompare-share-link') , this );
            });
        }
    }

    // Close popup
    $body.on('click','.htcompare-popup-close', function(e){
        $popup.removeClass('open');
    });

    // Copy Shareable link
    $('.evercompare-copy-link').on('click',function(e){
        evercompareCopyToClipboard( $(this).closest('.ever-compare-shareable-link').find('.evercompare-share-link') , this );
    });
    function evercompareCopyToClipboard( element, button ) {
        var $tempdata = $("<input>");
        $("body").append($tempdata);
        $tempdata.val($(element).text()).select();
        document.execCommand("copy");
        $tempdata.remove();
        $(button).text( $(button).data('copytext') );
        setTimeout(function() { 
            $( button ).text( $(button).data('btntext') );
        }, 1000);
    }

})(jQuery);