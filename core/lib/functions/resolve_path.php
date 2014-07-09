<?php
function resolve($path, $absolute=true) {
	
	global $db;

	$path = "/".ltrim($path, "/");
	$path_no_name = str_replace(basename($path), "", $path);

	// if viewing a sub-site then search relevent folder
	if (defined('SUBDIR') && is_file($_SERVER['DOCUMENT_ROOT'].SUBDIR.'/themes/'.$db->checkSettings('theme').$path)) {
		
		$output = $_SERVER['DOCUMENT_ROOT'].SUBDIR.'/themes/'.$db->checkSettings('theme').$path;	
		
	} elseif (defined('SUBDIR') && is_file($_SERVER['DOCUMENT_ROOT'].SUBDIR.$path)) {
		$output = $_SERVER['DOCUMENT_ROOT'].SUBDIR.$path;	
		
	// if 'lang' session data is set then search theme for relevent language file
	} elseif (!empty($_SESSION['lang']) && is_file($_SERVER['DOCUMENT_ROOT'].BASE."/themes/".$db->checkSettings('theme').$path_no_name."locale/".strtolower($_SESSION['lang'])."/".basename($path))) {
		$output = $_SERVER['DOCUMENT_ROOT'].BASE."/themes/".$db->checkSettings('theme').$path_no_name."locale/".strtolower($_SESSION['lang'])."/".basename($path);
			
	// search theme directory
	} elseif (is_file($_SERVER['DOCUMENT_ROOT'].BASE."/themes/".$db->checkSettings('theme').$path)) {
		$output = $_SERVER['DOCUMENT_ROOT'].BASE."/themes/".$db->checkSettings('theme').$path;
	
	// search custom files
	} elseif (is_file($_SERVER['DOCUMENT_ROOT'].BASE.$path)) {
		$output = $_SERVER['DOCUMENT_ROOT'].BASE.$path;
		
	// search core files
	} else {
		$output = $_SERVER['DOCUMENT_ROOT'].BASE.'/core'.$path;
	}
	
	return ($absolute) ? $output : str_replace($_SERVER['DOCUMENT_ROOT'], "", $output);
}