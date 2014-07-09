<?php
//require $_SERVER['DOCUMENT_ROOT'].BASE.'/core/tools/twitteroauth/index.php';

//require $_SERVER['DOCUMENT_ROOT'].BASE.'/core/tools/facebook/index.php';

// get user details if session set
if (!empty($_SESSION['userid'])) {
	$db->type='site';
	$user = $u->getUser($_SESSION['userid']);
	$is_logged_in = true;
	
	$edit_mode 		= false;
	$is_admin 		= false;
	if ($user['permissions']>1) {
		$_SESSION['is_admin'] = $is_admin = true;
		$edit_mode = (isset($_GET['edit']) && $_GET['edit']===1)?true:false;
	} 
} else {
	$user 			= NULL;
	$is_logged_in 	= false;
	$edit_mode 		= false;
	$is_admin 		= false;
	$_SESSION['userid'] = NULL;
}