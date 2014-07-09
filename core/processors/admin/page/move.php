<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "move_page") {
	
	if (is_numeric($_POST['page_id'])) {
		
		// sanatize new url
		$_POST['path'] = str_replace("dontreplaceme","/",urlify(str_replace("/","dontreplaceme",trim($_POST['path'],"/"))));
		
		// get current path
		$current = $p->getPage($_POST['page_id']);

		// get all the pages that need updating
		$db->type = "site";
		$db->vars['path'] = ($_POST['move_subpages']==1) ? $current['path']."%":$current['path'];
		$pages = $db->select("SELECT * FROM pages WHERE path LIKE :path");
		
		foreach ($pages as $page) {
			$new_path = $_POST['path'].str_replace($_POST['current_path'], "", $page['path']);
				
			// check if page already exists at this new path		
			$db->type = "site";
			$db->vars['path'] = $new_path;
			$duplicates = $db->select("SELECT * FROM pages WHERE path=:path");

			// check not the same as original path and not duplicate
			if (!count($duplicates)) {
				
				$dup_path_array = explode("/", $new_path);
				// loop through each part of path and check if you reach a module
				foreach ($dup_path_array as $p) {
					if (is_file('modules/'.implode("/", $dup_path_array).'.php') || is_file('core/modules/'.implode("/", $dup_path_array).'.php')) {
						$dupmodule = implode("/", $dup_path_array);
						break;
					} 
				}
				if (is_file('sites/'.$dup_path_array[0].'.php')) {
					$dupsite = $dup_path_array[0];
					break;
				} 
				
				// if is a module warn that this is a reserved path
				if (!$dupmodule && !$dupsite) {
					// move page
					$db->type = "site";
					$pageid['id']			= $db->sqlify($page['id'], "int");
					$values['path'] 		= $db->sqlify($new_path, "text");
					$values['modified_by'] 	= $db->sqlify($_SESSION['userid'], "text");
					$db->update("pages", $pageid, $values);
					
					if ($_POST['create_redirects']==1) {
						$redirect['path'] = $db->sqlify($page['path']);
						$redirect['target'] = $db->sqlify($new_path);
						$db->insert("redirects", $redirect);
					}
					
				} else {
					$_SESSION['error'] = "Sorry, that is a reserved path, please try again";
				}
			}  else {
				$_SESSION['error'] = "Sorry, a page already exists at that path, please try again";
			}
		} 
		
		$db->doCommit();
		header('Location: '.BASE.'/'.$_POST['path']);	
	}
}