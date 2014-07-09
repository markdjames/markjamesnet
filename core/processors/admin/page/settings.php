<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "update_page_settings") {
	
	$db->type = "site";
	
	$pageid['id'] 				= $db->sqlify($_POST['page_id'], "int");
	$values['title'] 			= $db->sqlify($_POST['title'], "text");
	$values['template'] 		= $db->sqlify($_POST['template'], "text");
	$values['description'] 		= $db->sqlify($_POST['description'], "text");
	$values['keywords'] 		= $db->sqlify($_POST['keywords']);
	$values['publish_date'] 	= $db->sqlify(formatDate($_POST['publish_date']), "text");
	$values['expiry_date'] 		= $db->sqlify(formatDate($_POST['expiry_date']), "text");
	$values['navigation_title'] = $db->sqlify($_POST['navigation_title'], "text");
	$values['show_navigation'] 	= (isset($_POST['show_navigation']) && $_POST['show_navigation']==1) ? 1 : 0;
	$values['published'] 		= (isset($_POST['published']) && $_POST['published']==1) ? 1 : 0;
	$values['modified_by'] 		= $db->sqlify($_SESSION['userid'], "text");
	
	$db->update("pages", $pageid, $values);
	$db->doCommit();		
}