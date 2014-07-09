<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "update_page_content") {
	
	$db->type = "site";
	
	$locale = (isset($_POST['locale'])) ? $o->makeHTMLsafe($_POST['locale']) : array();
	
	// get page's current settings
	$current = $p->getPageById($_POST['record_id']);

	unset($current['id']);
	unset($current['last_modified']);
	foreach($current as $k=>$v) $values[$k] = $db->sqlify($v);
	
	if (isset($values['path']) && !empty($values['path'])) {
		
		// overwrite new values
		$values['pid'] 			= $db->sqlify($_POST['page_id']);
		$values['content'] 		= $db->sqlify($_POST['content']);
		$values['alt'] 			= $db->sqlify($_POST['alt']);
		$values['credit'] 		= $db->sqlify($_POST['credit']);
		$values['image_link']	= $db->sqlify($_POST['image_link']);
		$values['title'] 		= $db->sqlify($_POST['title']);
		$values['locale'] 		= $db->sqlify(json_encode($locale));
		$values['hide_photo'] 	= (isset($_POST['hide_photo'])) ? 1 : 0; 
		$values['modified_by'] 	= $db->sqlify($_SESSION['userid']);
		
		// process image (or lack there of)
		if (isset($_POST['no_photo'])) {
			$values['image'] = $db->sqlify('', "text");
			
		} elseif($_FILES['image']['size'] > 0) {  
			$img = processImageUpload($_FILES['image'], 'pages', $_POST['page_id']);		
			if (!empty($img)) {
				$values['image'] = $db->sqlify($img, "text");
			}
		} 
		
		// add new record
		$db->insert("pages", $values);
		
		// archive old record
		$archiveid['id'] 		= $db->sqlify($_POST['record_id']);
		$archive['archived'] 	= $db->sqlify(1);
		$db->update("pages", $archiveid, $archive);
		
		
		$db->doCommit();	
	} else {
		$_SESSION['error'] = "Sorry, something went wrong....";
	}
}