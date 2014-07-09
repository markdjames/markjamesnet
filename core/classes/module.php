<?php
class Module {
	
	public $module;
	
	public function getModule($id) {
		global $db;

		$db->vars = array();
		$db->vars['id'] = $id;
		$db->type = 'site';
		$pages = $db->select("SELECT m.*
								FROM modules AS m
								WHERE 
								id=:id 
								ORDER BY id DESC");

		return (count($pages)) ? $pages[0] : false;
	}
	
	public function getModuleByPath($path) {
		global $db;
		global $c;
		$path = (strpos($path, "/")!==0) ? "/".$path : $path;
		
		/*******************************
		* Break down the path, if there is a numeric compontent then this is dynamic content
		*******************************/
		$path_array = explode("/", $path);
		if (count($path_array)>1) array_shift($path_array);
		$new_path = "";
		foreach($path_array as $k=>$v) {
			if (is_numeric($v)) {
				$dynamic = $v;
				break;
			}
			$module = $v;
			$new_path .= "/".$v;
		}
		
		$db->vars = array();
				
		$db->vars['path'] = $new_path;
		$db->type = 'site';
		$pages = $db->select("SELECT m.*
								FROM modules AS m
								WHERE 
								path LIKE :path 
								ORDER BY id DESC");
	

		return (count($pages)) ? (count($pages)>1) ? $pages : $pages[0] : false;
	}	
	
	public function getModules() {
		$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/modules";
		$pages_base = dirToArray($dir);
		
		$dir = $_SERVER['DOCUMENT_ROOT'].BASE."/core/modules";
		$pages_core = dirToArray($dir);
		
		$modules = array_merge($pages_base, $pages_core);
		
		return $modules;
	}
	
	// pass block id/type to get page
	public function getModuleByBlock($bid, $btype) {
		global $db;
		global $b;
		$block_type = $b->getBlockByType($btype);
		
		$db->vars['bid'] = $bid;
		$db->vars['btype'] = $block_type['id'];

		$db->type = 'site';
		$pages = $db->select("SELECT p.*
								FROM block_".$btype." AS b
								LEFT JOIN blocks_bridge AS bb ON bb.block_id=b.id 
								LEFT JOIN modules AS p ON bb.page_id=p.id
								WHERE 
								bb.block_id=:bid
								AND
								bb.block_type=:btype
								AND
								bb.page_type='module'
								LIMIT 1");
								
		return (count($pages)) ? $pages[0] : false;
	}	
	
	
	public function installModules() {
		$modules = $this->getModules();
		debug($modules);
	}
}
$m = new Module();
?>
