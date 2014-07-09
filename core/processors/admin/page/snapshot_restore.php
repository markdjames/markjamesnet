<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "restore_snapshot") {
	
	if (is_numeric($_POST['page_id'])) {

		$db->type = "site";
		
		// update current live snapshot to archived
		unset($values);
		$pageid['id'] = $db->sqlify($_POST['page_id'], "int");
		$values['archived'] = 1;
		$db->update("pages", $pageid, $values);
		
		// update restored page to indicate it is no longer archived.
		unset($values);
		$id['id'] = $db->sqlify($_POST['restore'], "int");
		$values['archived'] = 0;
		$db->update("pages", $id, $values);
		$db->doCommit();
		
	}
}