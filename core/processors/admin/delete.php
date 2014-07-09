<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "delete_record") {
	require_once '../../lib/bootstrap.php';
	
	if (is_numeric($_POST['id']) && ($db->checkPermissions('delete_all', $_SESSION['userid']) || $db->checkPermissions('delete_'.$_POST['type'], $_SESSION['userid']))) {
		
		$db->type=$_POST['site'];
		$db->delete($_POST['table'], 'id', $_POST['id'], true);
		
		if ($_POST['type']=='blocks') {
			$db->vars['name'] = $_POST['category'];
			$block = $db->select("SELECT * FROM blocks WHERE name=:name");
			$db->delete('blocks_bridge', array('block_id', 'block_type'), array($_POST['id'], $block[0]['id']), true);
		}		
		
		$db->doCommit();
	} else {
		echo "fail";
	}
}