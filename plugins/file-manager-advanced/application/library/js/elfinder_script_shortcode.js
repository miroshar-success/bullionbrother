jQuery(document).ready(function() {
                 var fmakey = jQuery('#fmakey').val();
				 jQuery('#file_manager_advanced').elfinder(
					// 1st Arg - options
					{
						cssAutoLoad : false,               // Disable CSS auto loading
					    url : ajaxurl,  // connector URL (REQUIRED)
						customData : {action: 'fma_load_fma_ui',_fmakey: fmakey},                   // language (OPTIONAL)
					},
					// 2nd Arg - before boot up function
					function(fm, extraObj) {
						// `init` event callback function
						fm.bind('init', function() {
							// Optional for Japanese decoder "extras/encoding-japanese.min"
							delete fm.options.rawStringDecoder;
							if (fm.lang === 'jp') {
								fm.loadScript(
									[ fm.baseUrl + '/lib/js/extras/encoding-japanese.min.js' ],
									function() {
										if (window.Encoding && Encoding.convert) {
											fm.options.rawStringDecoder = function(s) {
												return Encoding.convert(s,{to:'UNICODE',type:'string'});
											};
										}
									},
									{ loadType: 'tag' }
								);
							}
						});
						// Optional for set document.title dynamically.
						var title = document.title;
						fm.bind('open', function() {
							var path = '',
								cwd  = fm.cwd();
							if (cwd) {
								path = fm.path(cwd.hash) || null;
							}
							document.title = path? path + ':' + title : title;
						}).bind('destroy', function() {
							document.title = title;
						});
					}
				);
				 
				
});