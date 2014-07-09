<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "sort_aggregation") {

	require_once '../../../lib/bootstrap.php';
	
	parse_str($_POST['order'], $order);
	$agg = json_encode($order);
	
	$db->type = "site";
	$blockid['id'] = $db->sqlify($_POST['id'], "int");
	$values['settings'] = $db->sqlify($agg, "text");
	$db->update("block_aggregation", $blockid, $values);
	
	$db->doCommit();	
	
}