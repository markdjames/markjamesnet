<?php
// check to see if path points to mini-site / sub-site
// if it does, include custom processors

if (is_dir($_SERVER['DOCUMENT_ROOT'].BASE.'/sites/'.$path_array[0])) {
	
	define('SUBDIR', BASE."/sites/".$path_array[0]);
	define('SITE', BASE."/".$path_array[0]);

	$_SESSION['SUBDIR'] = SUBDIR;
	
	/*****************************
	* get all block controllers local to subsite
	*****************************/
	$dir = $_SERVER['DOCUMENT_ROOT'].SUBDIR."/blocks";
	$classes = scan_dir($dir);
	if (count($classes)) {
		foreach ($classes as $key=>$file){
			if (strpos($file, 'controller.php')!==false) {
				require_once $file;
			}
		} 
	}

	
	if (count($path_array)>1) 
		array_shift($path_array); 
	else 
		$path_array[0] = "home"; // drop of the 'site' element of the url
		
} else {
	unset($_SESSION['SUBDIR']);
}