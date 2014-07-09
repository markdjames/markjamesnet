<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "update_module_settings") {
	
	$db->type = "site";
	
	$pageid['id'] 				= $db->sqlify($_POST['module_id'], "int");
	$values['title'] 			= $db->sqlify($_POST['title'], "text");
	$values['template'] 		= $db->sqlify($_POST['template'], "text");
	$values['description'] 		= $db->sqlify($_POST['description'], "text");
	$values['keywords'] 		= $db->sqlify($_POST['keywords']);
	$values['navigation_title'] = $db->sqlify($_POST['navigation_title'], "text");
	$values['show_navigation'] 	= (isset($_POST['show_navigation']) && $_POST['show_navigation']==1) ? 1 : 0;
	
	$db->update("modules", $pageid, $values);
	$db->doCommit();	
	
}