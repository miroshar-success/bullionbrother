<?php
/*
@package: File Manager Advanced
@Class: fma_main
*/
if(class_exists('class_fma_main')) {
	return;
}
class class_fma_main {
	     var $settings;
          public function __construct()
		    {
			 add_action('admin_menu', array(&$this, 'fma_menus'));
			 add_action( 'admin_enqueue_scripts', array(&$this,'fma_scripts'));
			 add_action( 'wp_ajax_fma_load_fma_ui', array(&$this, 'fma_load_fma_ui'));
			 add_action('wp_ajax_fma_review_ajax', array($this, 'fma_review_ajax'));
			 $this->settings = get_option('fmaoptions');
			}
			public function fma_menus() {
				include('class_fma_admin_menus.php');
				$fma_menus = new class_fma_admin_menus();
				$fma_menus->load_menus();
			}
			public function fma_load_fma_ui() {
				include('class_fma_connector.php');
				$fma_connector = new class_fma_connector();
				 if ( wp_verify_nonce( $_REQUEST['_fmakey'], 'fmaskey' ) ) {
				    $fma_connector->fma_local_file_system();
				 }
			}
			public function fma_scripts() {
				$pageNow = isset($_GET['page']) ?  sanitize_text_field(htmlentities($_GET['page'])) : '';
				if('file_manager_advanced_ui' == $pageNow) {

					$elfCss = [
						'commands.css',
						'common.css',
						'contextmenu.css',
						'cwd.css',
						'dialog.css',
						'fonts.css',
						'navbar.css',
						'quicklook.css',
						'statusbar.css',
						'toast.css',
						'toolbar.css'
					];

				wp_enqueue_style( 'query-ui-1.12.0', plugins_url('library/jquery/jquery-ui-1.12.0.css', __FILE__));

				foreach($elfCss as $elCss) {
					wp_enqueue_style( $elCss, plugins_url('library/css/'.$elCss.'', __FILE__));	
				}
				wp_enqueue_style( 'fma_theme', plugins_url('library/css/theme.css', __FILE__));
				if(isset($this->settings['fma_theme']) && $this->settings ['fma_theme'] == 'dark') {
				  wp_enqueue_style( 'fma_themee', plugins_url('library/themes/dark/css/theme.css', __FILE__));
				}
                else if(isset($this->settings['fma_theme']) && $this->settings ['fma_theme'] == 'grey') {
				  wp_enqueue_style( 'fma_themee', plugins_url('library/themes/grey/css/theme.css', __FILE__));
				}
                else if(isset($this->settings['fma_theme']) && $this->settings ['fma_theme'] == 'windows10') {
				  wp_enqueue_style( 'fma_themee', plugins_url('library/themes/windows10/css/theme.css', __FILE__));
				}
                 else if(isset($this->settings['fma_theme']) && $this->settings ['fma_theme'] == 'bootstrap') {
				  wp_enqueue_style( 'fma_themee', plugins_url('library/themes/bootstrap/css/theme.css', __FILE__));
				}
			    wp_enqueue_style( 'fma_custom', plugins_url('library/css/custom_style_filemanager_advanced.css', __FILE__));

				wp_enqueue_script('afm-init-jquery', plugins_url('library/js/init.js', __FILE__));
			
				wp_enqueue_script( 'afm-elFinder', plugins_url('library/js/elFinder.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-selectable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-dialog', 'jquery-ui-slider', 'jquery-ui-tabs'));

				wp_enqueue_script( 'afm-elFinder.version', plugins_url('library/js/elFinder.version.js', __FILE__));
				wp_enqueue_script( 'afm-jquery.elfinder', plugins_url('library/js/jquery.elfinder.js', __FILE__));
				wp_enqueue_script( 'afm-elFinder.mimetypes', plugins_url('library/js/elFinder.mimetypes.js', __FILE__));
				wp_enqueue_script( 'afm-elFinder.options', plugins_url('library/js/elFinder.options.js', __FILE__));
				wp_enqueue_script( 'afm-elFinder.options.netmount', plugins_url('library/js/elFinder.options.netmount.js', __FILE__));
				wp_enqueue_script( 'afm-elFinder.history', plugins_url('library/js/elFinder.history.js', __FILE__));
				wp_enqueue_script( 'afm-elFinder.command', plugins_url('library/js/elFinder.command.js', __FILE__));
				wp_enqueue_script( 'afm-elFinder.resources', plugins_url('library/js/elFinder.resources.js', __FILE__));
			
				wp_enqueue_script( 'afm-jquery.dialogelfinder', plugins_url('library/js/jquery.dialogelfinder.js', __FILE__));
			

				wp_enqueue_script( 'afm-button', plugins_url('library/js/ui/button.js', __FILE__));
				wp_enqueue_script( 'afm-contextmenu', plugins_url('library/js/ui/contextmenu.js', __FILE__));
				wp_enqueue_script( 'afm-cwd', plugins_url('library/js/ui/cwd.js', __FILE__));
				wp_enqueue_script( 'afm-dialog', plugins_url('library/js/ui/dialog.js', __FILE__));
				wp_enqueue_script( 'afm-fullscreenbutton', plugins_url('library/js/ui/fullscreenbutton.js', __FILE__));
				wp_enqueue_script( 'afm-navbar', plugins_url('library/js/ui/navbar.js', __FILE__));
				wp_enqueue_script( 'afm-navdock', plugins_url('library/js/ui/navdock.js', __FILE__));
				wp_enqueue_script( 'afm-overlay', plugins_url('library/js/ui/overlay.js', __FILE__));
				wp_enqueue_script( 'afm-panel', plugins_url('library/js/ui/panel.js', __FILE__));
				wp_enqueue_script( 'afm-path', plugins_url('library/js/ui/path.js', __FILE__));
				//wp_enqueue_script( 'afm-places', plugins_url('library/js/ui/places.js', __FILE__));
				wp_enqueue_script( 'afm-searchbutton', plugins_url('library/js/ui/searchbutton.js', __FILE__));
				wp_enqueue_script( 'afm-sortbutton', plugins_url('library/js/ui/sortbutton.js', __FILE__));
				wp_enqueue_script( 'afm-stat', plugins_url('library/js/ui/stat.js', __FILE__));
				wp_enqueue_script( 'afm-toast', plugins_url('library/js/ui/toast.js', __FILE__));
				wp_enqueue_script( 'afm-toolbar', plugins_url('library/js/ui/toolbar.js', __FILE__));
				wp_enqueue_script( 'afm-tree', plugins_url('library/js/ui/tree.js', __FILE__));
				wp_enqueue_script( 'afm-uploadButton', plugins_url('library/js/ui/uploadButton.js', __FILE__));
				wp_enqueue_script( 'afm-viewbutton', plugins_url('library/js/ui/viewbutton.js', __FILE__));
				wp_enqueue_script( 'afm-workzone', plugins_url('library/js/ui/workzone.js', __FILE__));
			

				wp_enqueue_script( 'afm-archive', plugins_url('library/js/commands/archive.js', __FILE__));
				wp_enqueue_script( 'afm-back', plugins_url('library/js/commands/back.js', __FILE__));
				wp_enqueue_script( 'afm-chmod', plugins_url('library/js/commands/chmod.js', __FILE__));
				wp_enqueue_script( 'afm-colwidth', plugins_url('library/js/commands/colwidth.js', __FILE__));
				wp_enqueue_script( 'afm-copy', plugins_url('library/js/commands/copy.js', __FILE__));
				wp_enqueue_script( 'afm-cut', plugins_url('library/js/commands/cut.js', __FILE__));
				wp_enqueue_script( 'afm-download', plugins_url('library/js/commands/download.js', __FILE__));
				wp_enqueue_script( 'afm-duplicate', plugins_url('library/js/commands/duplicate.js', __FILE__));
				wp_enqueue_script( 'afm-edit', plugins_url('library/js/commands/edit.js', __FILE__));
				wp_enqueue_script( 'afm-empty', plugins_url('library/js/commands/empty.js', __FILE__));
				wp_enqueue_script( 'afm-extract', plugins_url('library/js/commands/extract.js', __FILE__));
				wp_enqueue_script( 'afm-forward', plugins_url('library/js/commands/forward.js', __FILE__));
				wp_enqueue_script( 'afm-fullscreen', plugins_url('library/js/commands/fullscreen.js', __FILE__));
				wp_enqueue_script( 'afm-getfile', plugins_url('library/js/commands/getfile.js', __FILE__));
				wp_enqueue_script( 'afm-help', plugins_url('library/js/commands/help.js', __FILE__));
				wp_enqueue_script( 'afm-hidden', plugins_url('library/js/commands/hidden.js', __FILE__));
				//wp_enqueue_script( 'afm-hide', plugins_url('library/js/commands/hide.js', __FILE__));
				wp_enqueue_script( 'afm-home', plugins_url('library/js/commands/home.js', __FILE__));
				wp_enqueue_script( 'afm-info', plugins_url('library/js/commands/info.js', __FILE__));
				wp_enqueue_script( 'afm-mkdir', plugins_url('library/js/commands/mkdir.js', __FILE__));
				wp_enqueue_script( 'afm-mkfile', plugins_url('library/js/commands/mkfile.js', __FILE__));
				wp_enqueue_script( 'afm-netmount', plugins_url('library/js/commands/netmount.js', __FILE__));
				wp_enqueue_script( 'afm-open', plugins_url('library/js/commands/open.js', __FILE__));
				wp_enqueue_script( 'afm-opendir', plugins_url('library/js/commands/opendir.js', __FILE__));
				wp_enqueue_script( 'afm-opennew', plugins_url('library/js/commands/opennew.js', __FILE__));
				wp_enqueue_script( 'afm-paste', plugins_url('library/js/commands/paste.js', __FILE__));
				wp_enqueue_script( 'afm-quicklook', plugins_url('library/js/commands/quicklook.js', __FILE__));
				wp_enqueue_script( 'afm-quicklook.plugins', plugins_url('library/js/commands/quicklook.plugins.js', __FILE__));
				wp_enqueue_script( 'afm-reload', plugins_url('library/js/commands/reload.js', __FILE__));
				wp_enqueue_script( 'afm-rename', plugins_url('library/js/commands/rename.js', __FILE__));
				wp_enqueue_script( 'afm-resize', plugins_url('library/js/commands/resize.js', __FILE__));
				wp_enqueue_script( 'afm-restore', plugins_url('library/js/commands/restore.js', __FILE__));
				wp_enqueue_script( 'afm-rm', plugins_url('library/js/commands/rm.js', __FILE__));
				wp_enqueue_script( 'afm-search', plugins_url('library/js/commands/search.js', __FILE__));
				wp_enqueue_script( 'afm-selectall', plugins_url('library/js/commands/selectall.js', __FILE__));
				wp_enqueue_script( 'afm-selectinvert', plugins_url('library/js/commands/selectinvert.js', __FILE__));
				wp_enqueue_script( 'afm-selectnone', plugins_url('library/js/commands/selectnone.js', __FILE__));
				wp_enqueue_script( 'afm-sort', plugins_url('library/js/commands/sort.js', __FILE__));
				wp_enqueue_script( 'afm-undo', plugins_url('library/js/commands/undo.js', __FILE__));
				wp_enqueue_script( 'afm-up', plugins_url('library/js/commands/up.js', __FILE__));
				wp_enqueue_script( 'afm-upload', plugins_url('library/js/commands/upload.js', __FILE__));
				wp_enqueue_script( 'afm-view', plugins_url('library/js/commands/view.js', __FILE__));
				wp_enqueue_script( 'afm-quicklook.googledocs', plugins_url('library/js/extras/quicklook.googledocs.js', __FILE__));

				if(isset($this->settings['fma_locale'])) {
					$locale = $this->settings['fma_locale'];
					 wp_enqueue_script( 'fma_lang', plugins_url('library/js/i18n/elfinder.'.$locale.'.js', __FILE__));
				   } else {
					wp_enqueue_script( 'fma_lang', plugins_url('library/js/i18n/elfinder.en.js', __FILE__));   
				   }

				wp_enqueue_script( 'codemirror', plugins_url('library/codemirror/lib/codemirror.js',  __FILE__ ));
				wp_enqueue_style( 'codemirror', plugins_url('library/codemirror/lib/codemirror.css', __FILE__));
				wp_enqueue_script( 'htmlmixed', plugins_url('library/codemirror/mode/htmlmixed/htmlmixed.js',  __FILE__ ));
				wp_enqueue_script( 'xml', plugins_url('library/codemirror/mode/xml/xml.js',  __FILE__ ));
				wp_enqueue_script( 'css', plugins_url('library/codemirror/mode/css/css.js',  __FILE__ ));
				wp_enqueue_script( 'javascript', plugins_url('library/codemirror/mode/javascript/javascript.js',  __FILE__ ));
				wp_enqueue_script( 'clike', plugins_url('library/codemirror/mode/clike/clike.js',  __FILE__ ));
				wp_enqueue_script( 'php', plugins_url('library/codemirror/mode/php/php.js',  __FILE__ ));	
				wp_enqueue_script( 'elfinder_script', plugins_url('library/js/elfinder_script.js', __FILE__));

				}
		
			}
			/*
         Close Help
        */
        public function fma_review_ajax()
        {
            $task = sanitize_text_field($_POST['task']);
            $done = update_option('fma_hide_review_section', $task);
                if ($done) {
                    echo '1';
                } else {
                    echo '0';
                }
            die;
        }
}