<?php

/***********************
* slightly complicated logic here: We rebuild the path from the base, so /path, /path/to, /path/to/page etc, checking:
* 1. does the current path match a file in the /modules directory?
* 2. if it is a module we break out of this loop
* 3. we then check that either we are at the base of the module (no modifiers follow in the url) or that a record ID has
*    been passed - if not then we do not register as a module at this stage
* 4. we then search the database for a page matching the current path (so custom pages can sit below modules)
* 5. if a page is found, show it (not a module), if not then revert to the module 
***********************/

// loop through each part of path and check if you reach a module - pop unused parts into array of variables
reset($path_array);
$variables = NULL;
// stage 1
foreach ($path_array as $part) {
	if (is_file(resolve('/modules/'.implode("/", $path_array).'.php'))) {
		$path = implode("/", $path_array);
		$module = $part;
		break;  // stage 2
	} else {
		$site->checkRedirect(implode("/", $path_array));
		$variables[] = array_pop($path_array);
	}
}

$url_vars 	= (count($variables)) 		? array_reverse($variables) : array(0=>NULL);
$path_array = (count($path_array)==0) 	? $variables 				: $path_array	;
$module 	= (is_array($path_array)) 	? end($path_array) 			: $module		;

// stage 3
$db->type='site';
$original_path = trim($_GET['id'], "/");

$_page = $page = $p->getPageByPath($original_path); // use original request var
if (!$page) {
	$modpath = (defined('SUBDIR')) ? SUBDIR."/".$path : $path ;
	$_mod = $mod = $m->getModuleByPath($modpath); // use original request var

} else {
	// record current page in object
	$p->page = $page;
	$_mod = $mod = NULL;
}
// stage 4
// if matches a file, and the next url var is numeric, or is empty, show the then this is a module
if (is_file(resolve('/modules/'.$path.'.php')) && !$page) {// && (is_numeric($url_vars[0]) || empty($url_vars[0]))) {
	$is_module = true;
} else {
	$is_module = false;
}

// if successful prepare page, otherwise attempt to revert to child module if possible (otherwise leads to 404)
// stage 5
if (!$page && is_file('modules/'.$path.'.php')) {
	$pages = $page;	
} else {
	$pages = NULL;
}
