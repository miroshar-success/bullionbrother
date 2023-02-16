jQuery( document ).ready(function($) {
    
    sanitize_svg_option_check();
    
    $('#sanitize_svg_option input').change(function(){
        sanitize_svg_option_check();
    }); 
    
    function sanitize_svg_option_check() {
        var sanitize_svg_option = $('#sanitize_svg_option input').is(':checked');
        
        if(sanitize_svg_option) {
            $('#sanitize_svg_option_sction').slideDown("slow");
        }
        else {
            $('#sanitize_svg_option_sction').slideUp("slow");
        }
        
    }
    
});