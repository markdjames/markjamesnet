<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "delete_file") {
	require_once '../../lib/bootstrap.php';

	if ($db->checkPermissions('delete_all', $_SESSION['userid']) || $db->checkPermissions('delete_file', $_SESSION['userid'])) {
		echo rtrim($_SERVER['DOCUMENT_ROOT'], "/").$_POST['path'];
		unlink(rtrim($_SERVER['DOCUMENT_ROOT'], "/").$_POST['path']);
	
	} else {
		echo "fail";
	}
}