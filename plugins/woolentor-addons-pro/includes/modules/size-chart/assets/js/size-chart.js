;(function($){
    "use strict";
    
    $(document).ready(function(){
        
        /* Open */
        $('.wl-size-chart-button').on('click', function(e){
            e.preventDefault();
            const $popup = $('.wl-size-chart-popup')
            $popup.addClass('open')
        });

        /* Close */
        $('.wl-size-chart-popup-close').on('click', function(){
            const $this = $(this),
                $popup = $('.wl-size-chart-popup')
            $popup.removeClass('open')
        });

        /* Close on outside click */
        $(document).on('click', function(e){
            if(e.target.classList.contains('wl-size-chart-popup')) {
                e.target.classList.remove('open')
            }
        });

    });
    
})(jQuery);