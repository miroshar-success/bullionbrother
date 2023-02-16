jQuery(document).ready(function() {
	
                 var fmakey = jQuery('#fmakey').val();
				  var fma_locale = jQuery('#fma_locale').val();
				 jQuery('#file_manager_advanced').elfinder(
					// 1st Arg - options
					{
						cssAutoLoad : false, // Disable CSS auto loading
					    url : ajaxurl,  // connector URL (REQUIRED)
						customData : {action: 'fma_load_fma_ui',_fmakey: fmakey},
						uploadMaxChunkSize : 10485760000000,
						defaultView : 'list',
						height: 500,
						lang : fma_locale,
						commandsOptions: {
                                        edit : {

                                                mimes : [],

                                                editors : [{

                                                mimes : ['text/plain', 'text/html', 'text/javascript', 'text/css', 'text/x-php', 'application/x-php'],

                                                load : function(textarea) {
                                                    var mimeType = this.file.mime;
                                                    var filename = this.file.name;
													//alert(mimeType);                                                    
                                                    editor = CodeMirror.fromTextArea(textarea, {
                                                        mode: mimeType,
                                                        indentUnit: 4,
                                                        lineNumbers: true,
                                                        //theme: "3024-day",
                                                        //viewportMargin: Infinity,
                                                        lineWrapping: true,                                                        
                                                        lint: true
                                                    });
                                                    return editor;
                                                    

                                                },
                                                close : function(textarea, instance) {
                                                this.myCodeMirror = null;
                                                },

                                                save: function(textarea, editor) {
                                                    jQuery(textarea).val(editor.getValue());
                                                    }

                                                } ]
                                                },

                                }
					}		
				);
				 
				
});