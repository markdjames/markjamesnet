<?php
function getFunctions($dir) {
	if (is_dir($dir)) {
		if($dh = opendir($dir)) {
	
			$files = Array();
			$inner_files = Array();
	
			while($file = readdir($dh)) {
				if($file != "." && $file != ".." && $file[0] != '.' && $file != "_notes") {
					if(is_dir($dir . "/" . $file)) {
						$inner_files = getFunctions($dir . "/" . $file);
						if(is_array($inner_files)) $files = array_merge($files, $inner_files); 
					} else {
						array_push($files, $dir . "/" . $file);
					}
				}
			}
	
			closedir($dh);
			return $files;
		}
	}
}


$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/core/lib/functions";
$functions = getFunctions($dir);
if (count($functions)) {
	foreach ($functions as $key=>$file){
		require_once $file;
	} 
}

$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/lib/functions";
$functions = getFunctions($dir);
if (count($functions)) {
	foreach ($functions as $key=>$file){
		require_once $file;
	} 
}