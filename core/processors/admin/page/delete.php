<?php
if (isset($_POST['function']) && strtolower($_POST['function']) == "delete_page") {

	if (is_numeric($_POST['page_id']) && $db->checkPermissions('delete_pages', $_SESSION['userid'])) {

		// get all blocks associated with original page
		$db->type = 'site';
		$db->vars['pageid'] = $_POST['page_id'];
		$blocks = $db->select("SELECT bb.*, b.name FROM blocks_bridge AS bb LEFT JOIN blocks AS b ON bb.block_type=b.id WHERE bb.page_id=:pageid");
		
		if (count($blocks)) {
			// for each block
			foreach ($blocks as $block) {
				$db->delete("block_".$block['name'], 'id', $block['block_id']);		
			}
		}

		$db->delete("pages", 'pid', $_POST['page_id']);
		$db->delete("blocks_bridge", 'page_id', $_POST['page_id']);
		$db->doCommit();

	}
}