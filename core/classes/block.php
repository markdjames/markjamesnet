<?php
class Block {
	
	// pass a block id and type and return the page id
	public function getPageId($id, $type) {
		global $db;
		
		$db->vars['block_id'] = $id;
		$db->vars['block_type'] = $type;
		$db->type = 'site';
		$block = $db->select("SELECT bb.page_id FROM blocks AS b LEFT JOIN blocks_bridge AS bb ON bb.block_type=b.id WHERE bb.block_id=:block_id AND b.name=:block_type");
		
		return (count($block)) ? $block[0]['page_id'] : false;
	}
	
	// pass page id and block type as string
	public function getBlock($pid, $btype, $page_type='page', $content_id=false) {
		global $db;
		$db->type = 'site';
		$blocktype = $this->getBlockByType($btype);
		$db->vars['bid'] = $blocktype['id'];
		$db->vars['pid'] = $pid;
		$db->vars['type'] = $page_type;
		if ($content_id) {
			$db->vars['content'] = $content_id;
			$sql = " AND bb.content_id=:content";
		} else {
			$sql = "";
		}

		$block = $db->select("SELECT bd.*, bb.block_type FROM blocks_bridge AS bb LEFT JOIN block_".$btype." AS bd ON bb.block_id=bd.id WHERE bb.page_id=:pid AND bb.block_type=:bid AND bb.page_type=:type AND bd.id IS NOT NULL".$sql);
		
		return  (count($block)) ? $block[0] : false;
	}
	
	// pass page id and block id
	public function getBlocks() {
		global $db;
		$db->type = 'site';
		$blocks = $db->select("SELECT * FROM blocks ORDER BY name ASC");
		
		return  (count($blocks)) ? $blocks : false;
	}
	
	// pass a block type and return the basic block
	public function getBlockByType($type) {
		global $db;
		$db->type = 'site';
		$db->vars = array();
		$db->vars['type'] = $type;
		$block = $db->select("SELECT * FROM blocks WHERE name=:type");
		
		return  (count($block)) ? $block[0] : false;
	}
	
	/******************************
	* loop through all block types and replace marker with relevent content
	*
	* @output:String - the template file
	******************************/
	public function addBlocks($output) {
		global $db;
		global $page;
		global $mod;
		global $path;
		global $is_module;
		global $module;
		global $url_vars; 
		global $is_admin; 
		
		
		// if is module convert page id to module id
		//$page['pid'] = ($is_module) ? $url_vars[0] : $page['pid'];
		$pagetype = ($is_module) ? 'module' : "page";
		
		// get all blocks
		$db->type = 'site';
		$block_types = $db->select("SELECT id, name FROM blocks");
		
		// do a general search to see if this page has any blocks - if not its not worth looping through them all	
		$db->vars['id'] = (isset($page['pid'])) ? $page['pid'] : $mod['id'];
		$db->vars['type'] = $pagetype;
		$quick_check = $db->select("SELECT * FROM blocks_bridge WHERE page_id=:id AND page_type=:type");
			
		// loop through blocks, check if this page has a block of this type and merge in to template
		if (count($block_types)) {
			foreach ($block_types as $bt) {
				
				if ((isset($page['pid']) || isset($mod['id'])) && count($quick_check)) {
					$db->type = 'site';
					$db->vars['id'] = (isset($page['pid'])) ? $page['pid'] : $mod['id'];
					$db->vars['type'] = $pagetype;
					$db->vars['block'] = $bt['id'];
					if (is_numeric($url_vars[0])) {
						$db->vars['cid'] = $url_vars[0];
						$sql = " AND content_id=:cid ";
					} else {
						$sql = " AND (content_id=0 OR content_id IS NULL) ";
					}
						
					$block = $db->select("SELECT b.* 
											FROM blocks_bridge AS bb 
											LEFT JOIN block_".$bt['name']." AS b ON bb.block_id=b.id 
											WHERE 
												bb.page_id=:id 
												AND 
												bb.page_type=:type
												AND 
												bb.block_type=:block
												AND 
												b.id!=''".
												$sql);
		
					if (count($block)) {
						// get block
						ob_start();
		
						// include original controller	
						require_once resolve('blocks/'.$bt['name'].'/view.php');			
						
						$b = ob_get_clean();
						
					} else {
						$b = "";
					}
				} else {
					$b = "";
				}
				$output = preg_replace('/{('.$bt['name'].')}/i', $b, $output);
	
			}
		}
		return $output;
	}
}
$b = new Block();
?>