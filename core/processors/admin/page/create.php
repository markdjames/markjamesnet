<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "create_page") {
	
	$db->type = "site";
	
	$values['path'] 			= $db->sqlify(trim($_POST['path'], '/'));
	$values['title'] 			= $db->sqlify($_POST['title']);
	$values['template'] 		= $db->sqlify($_POST['template']);
	$values['description'] 		= $db->sqlify($_POST['description']);
	$values['keywords'] 		= $db->sqlify($_POST['keywords']);
	$values['publish_date'] 	= $db->sqlify(formatDate($_POST['publish_date']));
	$values['expiry_date'] 		= $db->sqlify(formatDate($_POST['expiry_date']));
	$values['navigation_title'] = $db->sqlify($_POST['navigation_title']);
	$values['show_navigation'] 	= (isset($_POST['show_navigation']) && $_POST['show_navigation']==1) ? 1 : 0;
	$values['published'] 		= (isset($_POST['published']) && $_POST['published']==1) ? 1 : 0;
	$values['modified_by'] 		= $db->sqlify($_SESSION['userid']);
	
	$db->insert("pages", $values);
	$db->doCommit();
	
	$pid['id'] = $db->sqlify($db->lastId);	
	$pvalue['pid'] = $db->sqlify($db->lastId);	

	$db->update("pages", $pid, $pvalue);
	$db->doCommit();
	
}