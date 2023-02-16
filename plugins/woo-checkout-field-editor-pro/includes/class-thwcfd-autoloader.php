<?php
/**
 * Auto-loads the required dependencies for this plugin.
 *
 * @link       https://themehigh.com
 * @since      1.5.0
 *
 * @package    woo-checkout-field-editor-pro
 * @subpackage woo-checkout-field-editor-pro/includes
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWCFD_Autoloader')):

class THWCFD_Autoloader {
	private $include_path = '';
	
	private $class_path = array();

	public function __construct() {
		$this->include_path = untrailingslashit(THWCFD_PATH);
		
		if(function_exists("__autoload")){
			spl_autoload_register("__autoload");
		}
		spl_autoload_register(array($this, 'autoload'));
	}

	/** Include a class file. */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			require_once( $path );
			return true;
		}
		return false;
	}
	
	/** Class name to file name. */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}
	
	public function autoload( $class ) {
		$class = strtolower( $class );
		$file  = $this->get_file_name_from_class( $class );
		$path  = '';
		$file_path  = '';

		if(isset($this->class_path[$class])){
			$file_path = $this->include_path . '/' . $this->class_path[$class];

		} else {
			if (strpos($class, 'thwcfd_admin') === 0){
				$path = $this->include_path . '/admin/';

			} elseif (strpos($class, 'thwcfd_public') === 0){
				$path = $this->include_path . '/public/';

			} elseif (strpos($class, 'thwcfd_utils') === 0){
				$path = $this->include_path . '/includes/utils/';

			} elseif (strpos($class, 'wcfe_checkout_field') === 0){
				$path = $this->include_path . '/includes/model/fields/';

			} elseif (strpos($class, 'wcfe_checkout_section') === 0){
				$path = $this->include_path . '/includes/model/';

			} else{
				$path = $this->include_path . '/includes/';
			}
			$file_path = $path . $file;
		}
		
		if( empty($file_path) || (!$this->load_file($file_path) && strpos($class, 'thwcfd_') === 0) ) {
			$this->load_file( $this->include_path . $file );
		}
	}
}

endif;

new THWCFD_Autoloader();
