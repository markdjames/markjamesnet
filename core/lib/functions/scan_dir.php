<?php
/************************
* $depth:Int = amount of subdirectories to recurse in to - 0 = infinite
**************************/
function scan_dir ($dir, $depth=0, $current_depth=0) {
	$files = array();
	
	if (is_dir($dir)) {
		if($dh = opendir($dir)) {

			$inner_files = array();
	
			while($file = readdir($dh)) {
				if($file != "." && $file != ".." && $file != "_notes" && $file[0] != '.') {
					if(is_dir($dir . "/" . $file) && ($depth==0 || $current_depth<$depth)) {
						$current_depth++;
						$inner_files = scan_dir($dir . "/" . $file, $depth, $current_depth);
						if(is_array($inner_files)) $files = array_merge($files, $inner_files); 
					} else {
						array_push($files, $dir . "/" . $file);
					}
				}
			}
	
			closedir($dh);
			
		}
		
	}
	return $files;
}