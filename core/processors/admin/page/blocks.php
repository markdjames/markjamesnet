<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "update_page_blocks") {
	
	$db->type = "site";

	if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {  
		$img = processImageUpload($_FILES['image'], 'blocks/'.$_POST['block_name']);		
		if (!empty($img)) {
			$_POST['block']['image'] = $img;
		}
	} 
	
	if ($_POST['block_name']=='gallery') {
		foreach ($_POST['block']['images'] as $k=>$v) if (empty($v['src'])) unset($_POST['block']['images'][$k]);
	} 
	if ($_POST['block_name']=='files' || $_POST['block_name']=='audio') {
		foreach ($_POST['block']['files'] as $k=>$v) if (empty($v['src'])) unset($_POST['block']['files'][$k]);
	}
	if ($_POST['block_name']=='links' && !empty($_POST['existing_link'])) {
		$db->vars['id'] = $_POST['existing_link'];
		$clone = $db->select("SELECT * FROM block_links WHERE id=:id");
		$parent = $clone[0]['id'];
		unset($clone[0]['id']);
		$_POST['block'] = $clone[0];
		$_POST['block']['parent'] = $parent;
	}

	foreach ($_POST['block'] as $key=>$val) {
		if (is_array($val)) {
			$val=json_encode($val);
			unset($_POST['block'][$key]);
		}
		$type = (is_numeric($val) && strpos($val, '0')!==0) ? "int":"text";
		$values[$key] = $db->sqlify($val, $type);
	}
	
	/*****************************
	* Check the table for any boolean values (i.e. checkboxes) that may not have been sent (i.e. unticked)
	******************************/
	$table = $db->describe("block_".$_POST['block_name']);
	foreach ($table as $field) {
		if (strpos($field['type'], 'tinyint')!==false || strpos($field['type'], 'bool')!==false) {
			if (empty($values[$field['field']])) {
				$values[$field['field']] = 0;
			}
		}
	}
	
	if (!empty($_POST['block_id']) && is_numeric($_POST['block_id'])) {
		$blockid['id'] = $db->sqlify($_POST['block_id'], "int");
		$db->update("block_".$_POST['block_name'], $blockid, $values);
	} else {

		$db->insert("block_".$_POST['block_name'], $values);
		$db->doCommit();
		
		$block_type = $b->getBlockByType($_POST['block_name']);
		
		$bridge['block_id'] = $db->sqlify($db->lastId, "int");
		$bridge['page_id'] = $db->sqlify($_POST['page_id'], "int");
		$bridge['content_id'] = $db->sqlify($_POST['content_id'], "int");
		$bridge['block_type'] = $db->sqlify($block_type['id'], "int");
		$bridge['page_type'] = $db->sqlify($_POST['page_type'], "text");
		$db->insert("blocks_bridge", $bridge);
	}
	$db->doCommit();	
	
}