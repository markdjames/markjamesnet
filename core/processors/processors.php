<?php
if (isset($_POST['token']) && isset($_POST['function']) && $_SESSION['token']==$_POST['token']) { 
	
	$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/core/processors";
	$processors = scan_dir($dir);
	if (count($processors)) {
		foreach ($processors as $key=>$file){
			require_once $file;
		} 
	}
	
	$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/processors";
	$processors = scan_dir($dir);
	if (count($processors)) {
		foreach ($processors as $key=>$file){
			require_once $file;
		} 
	}
	
	$db->doCommit();
	unset($_SESSION['token']);
	$_SESSION['token'] = uniqid();
} 
?>