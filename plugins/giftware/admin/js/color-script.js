jQuery(document).ready(function($){
    var myOptions = {
    defaultColor: false,
    change: function(event, ui){},
    clear: function() {},
    hide: true,
    palettes: true
    };
    $('.my-color-field').wpColorPicker(myOptions);
});