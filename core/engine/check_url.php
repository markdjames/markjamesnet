<?php
if (empty($_GET['id'])) $_GET['id'] = 'home'; 

// check path, if not set direct to homepage, then get array of path pieces
$path = (!empty($_GET['id'])) ? trim($_GET['id'],'/') : 'home' ;
$path = (defined('SUBDIR')) ? trim(str_replace(trim(SUBDIR,'/'), "", $path),"/") : $path;

$path_array = (strpos($path, '/')!==false) ? explode("/", $path) : array($path);

$path = (!empty($_GET['id'])) ? trim($_GET['id'],'/') : 'home' ;
define('PATH', BASE."/".$path);

// check URL format for problems
// here we check the last section of the url to make sure it fits in with desired schemea (all lower case, alpha numeric or dash/underscore
$urlcheck = strtolower(preg_replace('/[^a-zA-Z0-9-_]/i', '', end($path_array)));

if ($urlcheck != end($path_array)) {
	// if it doesnt match we transform it so it does and then forward to that new address
	$endpath = array_pop($path_array);
	$newurl = str_replace("//", "/", BASE."/".implode("/", $path_array)."/".urlify($endpath));
	header('Location: '.$newurl);
	exit();
}

/* if query string is present reformat to work with mod_rewrite
if (strpos($_SERVER['REQUEST_URI'], '?')!==false && strpos($_SERVER['REQUEST_URI'], '.')===false && $path!='home') {
	parse_str($_SERVER['REQUEST_URI'], $params);
	unset($params['id']);
	
	foreach ($params as $key=>$val) {
		if (!empty($val)) $qs .= $key."=".$val."&";
	}
	if (strpos($qs, "?")!==false) {
		$qs = substr($qs, strpos($qs, "?")+1, -1);
	}
	header('Location: '.substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')).'&'.$qs);
}
*/
?>