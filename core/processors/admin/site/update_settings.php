<?php
if (strtolower($_POST['function']) == "update_site_settings") {
	
	$db->type = "site";
	
	$facebook = (isset($_POST['facebook'])) ? 1 : 0 ;
	$field['name']		= $db->sqlify('facebook');
	$values['value'] 	= $db->sqlify($facebook);
	$db->update("settings", $field, $values);
	
	$field['name']		= $db->sqlify('site-name');
	$values['value'] 	= $db->sqlify($_POST['site-name']);
	$db->update("settings", $field, $values);
	
	$field['name']		= $db->sqlify('theme');
	$values['value'] 	= $db->sqlify($_POST['theme']);
	$db->update("settings", $field, $values);
	
	$languages_array = explode(",", str_replace(" ", "", ucwords($_POST['languages'])));
	$languages = json_encode($languages_array);
	$field['name']		= $db->sqlify('languages');
	$values['value'] 	= $db->sqlify($languages);
	$db->update("settings", $field, $values);
		
	$db->doCommit();
	
	unset($_SESSION['settings']);
}