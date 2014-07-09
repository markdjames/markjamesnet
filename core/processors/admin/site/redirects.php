<?php
if (strtolower($_POST['function']) == "add_new_redirect") {
	
	$db->type = "site";
	
	$db->vars['path'] = trim(str_replace(DOMAIN, "", str_replace(URL, "", $_POST['origin'])),"/ ");
	$check = $db->select("SELECT * FROM redirects WHERE path=:path");
	
	if (count($check)==0) {
		$values['path'] = $db->sqlify(trim(str_replace(DOMAIN, "", str_replace(URL, "", $_POST['origin'])),"/ "));
		$values['target'] = $db->sqlify(trim(str_replace(DOMAIN, "", str_replace(URL, "", $_POST['destination'])),"/ "));
		$db->insert("redirects", $values);
		$db->doCommit();
	}
		
	
}