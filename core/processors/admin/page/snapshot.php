<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "snapshot_page") {
	
	if (is_numeric($_POST['page_id'])) {

		// get original page details
		$db->type = "site";
		$db->vars['id'] = $_POST['page_id'];
		$original = $db->select("SELECT * FROM pages WHERE id=:id");
		
		$hid = (!empty($original[0]['historic_id'])) ? $original[0]['historic_id'] : $original[0]['id'];

		// duplicate page
		$values['path'] 		= $db->sqlify($original[0]['path'], "text");
		$values['title'] 		= $db->sqlify($original[0]['title'], "text");
		$values['template'] 	= $db->sqlify($original[0]['template'], "text");
		$values['publish_date'] = $db->sqlify($original[0]['publish_date'], "text");
		$values['expiry_date'] 	= $db->sqlify($original[0]['expiry_date'], "text");
		$values['content'] 		= $db->sqlify($original[0]['content'], "text");
		$values['alt'] 			= $db->sqlify($original[0]['alt'], "text");
		$values['image'] 		= $db->sqlify($original[0]['image'], "text");
		$values['historic_id'] 	= $db->sqlify($hid, "int");
		$values['show_navigation']= $db->sqlify($original[0]['show_navigation'], "int");
		$values['published'] 	= $db->sqlify($original[0]['published'], "int");
		$values['modified_by'] 	= $db->sqlify($_SESSION['userid'], "text");
		$db->insert("pages", $values);
		$db->doCommit();
		$newpageid = $db->lastId;
		
		// DUPLICATE BLOCKS
		// get all blocks associated with original page
		$db->vars['pageid'] = $original[0]['id'];
		$blocks = $db->select("SELECT bb.*, b.name FROM blocks_bridge AS bb LEFT JOIN blocks AS b ON bb.block_type=b.id WHERE bb.page_id=:pageid");
		
		if (count($blocks)) {
			// for each block
			foreach ($blocks as $block) {
				// get block content
				$db->vars['id'] = $block['block_id'];
				$cblock = $db->select("SELECT * FROM block_".$block['name']." WHERE id=:id");
				
				// add duplicate block content
				unset($values);
				foreach ($cblock[0] as $key=>$val) {
					if ($key!='id') {
						$type = (is_numeric($val) && strpos($val, "0")!==0) ? "int" : "text";
						$values[$key] 	= $db->sqlify($val, $type);
					}
				}
				$db->insert("block_".$block['name'], $values);
				$db->doCommit();
				$newblockid = $db->lastId;
				
				// connect new block to the new page
				unset($values);
				$values['block_id']		= $db->sqlify($newblockid, "int");
				$values['page_id'] 		= $db->sqlify($newpageid, "int");
				$values['block_type']	= $db->sqlify($block['block_type'], "int");
				$values['page_type'] 	= $db->sqlify($block['page_type'], "text");
				$db->insert("blocks_bridge", $values);
				$db->doCommit();
			}
		}
		
		// update original page to indicate it is now archived.
		unset($values);
		$originalid['id'] = $db->sqlify($_POST['page_id'], "int");
		$values['archived'] = 1;
		$values['historic_id'] 	= $db->sqlify($hid, "int");
		$db->update("pages", $originalid, $values);
		$db->doCommit();
		
	}
}