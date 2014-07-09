<?php

$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/core/classes";
$classes = scan_dir($dir);
if (count($classes)) {
	foreach ($classes as $key=>$file){
		require_once $file;
	} 
}

$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/classes";
$classes = scan_dir($dir);
if (count($classes)) {
	foreach ($classes as $key=>$file){
		require_once $file;
	} 
}

$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/core/blocks";
$classes = scan_dir($dir);
if (count($classes)) {
	foreach ($classes as $key=>$file){
		if (strpos($file, 'controller.php')!==false) {
			require_once $file;
		}
	} 
}

$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/blocks";
$classes = scan_dir($dir);
if (count($classes)) {
	foreach ($classes as $key=>$file){
		if (strpos($file, 'controller.php')!==false) {
			require_once $file;
		}
	} 
}

//require_once $_SERVER['DOCUMENT_ROOT'].BASE."/core/tools/phpmailer/class.phpmailer.php";
