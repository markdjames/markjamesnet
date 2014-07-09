<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "update_module_related") {
	
		$db->type = "site";
	
	$pageid['id'] = $db->sqlify($_POST['page_id'], "int");
	
	foreach ($_POST['related'] as $k=>$v) {
		if (!empty($v)) {
			$rel = "/".trim($v, " / ");
			$_POST['related'][$k] = $rel;
		}
	}
	$unique = array_unique($_POST['related']);
	$values['related'] = $db->sqlify(json_encode($unique), "text");
	$values['modified_by'] = $db->sqlify($_SESSION['userid'], "text");
	
	$db->update("modules", $pageid, $values);
	$db->doCommit();	
	
}