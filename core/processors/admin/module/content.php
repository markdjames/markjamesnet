<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "update_module_content") {
	
	$db->type = "site";
	
	$locale = (isset($_POST['locale'])) ? $o->makeHTMLsafe($_POST['locale']) : array();

	$pageid['id'] 		= $db->sqlify($_POST['module_id']);
	$values['content'] 	= $db->sqlify($_POST['content']);
	$values['alt'] 		= $db->sqlify($_POST['alt']);
	$values['credit'] 	= $db->sqlify($_POST['credit']);
	$values['title'] 	= $db->sqlify($_POST['title']);
	$values['locale'] 	= $db->sqlify(json_encode($locale));
	
	if (isset($_POST['no_photo'])) {
		$values['image'] = $db->sqlify('', "text");
		
	} elseif($_FILES['image']['size'] > 0) {  
		$img = processImageUpload($_FILES['image'], 'modules', $_POST['module_id']);		
		if (!empty($img)) {
			$values['image'] = $db->sqlify($img, "text");
		}
	} 
	
	$values['hide_photo'] 	= (isset($_POST['hide_photo'])) ? 1 : 0; 
	$values['modified_by'] 	= $db->sqlify($_SESSION['userid'], "text");

	$db->update("modules", $pageid, $values);
	$db->doCommit();	
	
}