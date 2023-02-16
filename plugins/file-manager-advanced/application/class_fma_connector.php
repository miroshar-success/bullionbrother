<?php
/*
@package: File Manager Advanced
@Class: fma_connector
*/
if(class_exists('class_fma_connector')) {
	return;
}
class class_fma_connector
{
  // elfinder defaults:
  //read:https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
	public function fma_local_file_system() {
		$settings = get_option('fmaoptions');
		$path = ABSPATH;
		if(isset($settings['public_path']) && !empty($settings['public_path'])) {
			$path = $settings['public_path'];
		}
        $url = site_url();
		if(isset($settings['public_url']) && !empty($settings['public_url'])) {
			$url = $settings['public_url'];
		}
         if(isset($settings['hide_path']) && ($settings['hide_path'] == '1')) {
            $url = '';
         }
		    require 'library/php/autoload.php';
    if(isset($settings['enable_trash']) && ($settings['enable_trash'] == '1')) {
			$trash = array(
			'id'            => '1',
			'driver'        => 'Trash',
			'path'          => FMAFILEPATH.'application/library/files/.trash/',
			'tmbURL'        => site_url() . '/application/library/files/.trash/.tmb/',
			'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
			'uploadDeny'    => array(''),                // Recomend the same settings as the original volume that uses the trash
			'uploadAllow'   => array('all'),// Same as above
			'uploadOrder'   => array('deny', 'allow'),      // Same as above
			'accessControl' => 'access',                    // Same as above
			'attributes' => array(
				array(
						  'pattern' => '/.tmb/',
						  'read' => false,
						  'write' => false,
						  'hidden' => true,
						  'locked' => false
						 ),
				array(
						  'pattern' => '/.quarantine/',
						  'read' => false,
						  'write' => false,
						  'hidden' => true,
						  'locked' => false
				),
				array(
					'pattern' => '/.gitkeep/',
					'read' => false,
					'write' => false,
					'hidden' => true,
					'locked' => false
		      )
			 )
		);		
		$trash_f = 't1_Lw';
	} else {
		$trash = array();
		$trash_f = '';
	}
	$hide_htaccess = array(
		'pattern' => '/.htaccess/',
		'read' => false,
		'write' => false,
		'hidden' => true,
		'locked' => false
	);	
	if(isset($settings['enable_htaccess']) && !empty($settings['enable_htaccess']) && $settings['enable_htaccess'] == '1') {
		$hide_htaccess = array();
    }
	// getting allowed upload
	$allowUpload = array('all');
	if(isset($settings['fma_upload_allow']) && !empty($settings['fma_upload_allow'])) {
		$allowUpload = explode(',',$settings['fma_upload_allow']);
    }

				$opts = array(
				'roots' => array(
					// Items volume
					array(
						'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
						'path'          => $path,                 // path to files (REQUIRED)
						'URL'           => $url, // URL to files (REQUIRED)
						'trashHash'     => $trash_f,                     // elFinder's hash of trash folder
						'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
						'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
						'uploadAllow'   => $allowUpload,// Mimetype `image` and `text/plain` allowed to upload
						'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
						'disabled'      => array('help','preference'),
						'accessControl' => 'access',                     // disable and hide dot starting files (OPTIONAL)
						'acceptedName' => 'validName',
						'attributes' => array(
											   array(
														 'pattern' => '/.tmb/',
														 'read' => false,
														 'write' => false,
														 'hidden' => true,
														 'locked' => false
														),
											   array(
														 'pattern' => '/.quarantine/',
														 'read' => false,
														 'write' => false,
														 'hidden' => true,
														 'locked' => false
											   ),
											   array(
												'pattern' => '/.gitkeep/',
												'read' => false,
												'write' => false,
												'hidden' => true,
												'locked' => false
											   ),
											   $hide_htaccess
											)
					),
					$trash
					// Trash volume

				)
       );
// run elFinder
$fmaconnector = new elFinderConnector(new elFinder($opts));
$fmaconnector->run();
die;
}
public function access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}
}