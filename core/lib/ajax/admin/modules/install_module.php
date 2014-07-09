<?php
if (!isset($db)) {
	require_once '../../../../lib/bootstrap.php';
}

$module = $m->getModuleByPath("/".$_POST['id']);
if (!$module) {
	$mod = explode("/", "/".$_POST['id']);
	$module = end($mod);
	$_POST['id'] = trim($_POST['id'], "/");
	
	$values['path'] = $db->sqlify("/".$_POST['id']);
	$values['title'] = $db->sqlify(ucwords(str_replace("_", " ", $module)));
	$db->type='site';
	$db->insert("modules", $values);
	$db->doCommit();		
}