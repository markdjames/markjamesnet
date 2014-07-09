<?php 
if (isset($_POST['function']) && $_POST['function'] == "login") {
	
	$login = $u->login($_POST);
	header('Location: '.DIR);
	exit();
}

if (isset($_POST['function']) && $_POST['function'] == "logout") {
	
	$u->logout();
}
	
if (isset($_POST['function']) && $_POST['function'] == "forgotten_password") {
	
	$u->forgottenPassword($_POST['forgotten_email']);
}

if (isset($_POST['function']) && $_POST['function'] == "reset_password") {
	
	$u->resetPassword($_POST['uid'], $_POST['password'], $_POST['confirm_password']);
}
?>