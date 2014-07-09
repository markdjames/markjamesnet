<?php
class Page {
	
	public $page;
	public $auto_related = false;
	public $related_count = 0;
	
	function __construct() {
		global $db;
		//$this->page = (isset($_GET['id']) && isset($db)) ? $this->getPageByPath($_GET['id']) : NULL; 
	}
	
	// pass a page id and check if its published and not archived (i.e. visible to public)
	public function pageStatus($id) {
		global $db;
		$db->type = 'site';
		$db->vars['id'] = $id;
		$page = $db->select("SELECT * FROM pages WHERE pid=:id AND archived=0 AND published=1 ORDER BY id DESC LIMIT 1");
		
		return (count($page)) ? true : false ; 
	}
	
	// pass a page id and check if its not archived 
	public function pageCurrent($id) {
		global $db;
		$db->type = 'site';
		$db->vars['id'] = $id;
		$page = $db->select("SELECT * FROM pages WHERE pid=:id AND archived=0 ORDER BY id DESC LIMIT 1");
		
		return (count($page)) ? true : false ; 
	}
	
	// pass a page id and check if its published 
	public function pagePublished($id) {
		global $db;
		$db->type = 'site';
		$db->vars['id'] = $id;
		$page = $db->select("SELECT * FROM pages WHERE pid=:id AND published=1 AND archived=0 ORDER BY id DESC LIMIT 1");
		
		return (count($page)) ? true : false ; 
	}
	
	// pass a page id and type and return the path
	public function getPath($id, $type) {
		global $db;

		$db->vars['id'] = $id;		
		$db->type = 'site';
		$page = $db->select("SELECT * FROM pages WHERE pid=:id AND archived=0 ORDER BY id DESC LIMIT 1");
		$path = $page[0]['path'];
		
		return $path;
	}
	
	// pass a page id and type and return the path
	public function getRandomPage() {
		global $db;
		$db->type = 'site';

		do {
			$pages = $db->select("SELECT * FROM pages WHERE path LIKE '".ltrim(SITE, '/')."%' AND archived=0 AND published=1 ORDER BY id DESC LIMIT 1");
			$slice = array_rand($pages);
		} while (in_array($pages[$slice]['path'], $exclude_pages));
				
		return $pages[$slice];
	}
	
	// pass a page id and type and return the path
	public function getPage($id) {
		global $db;
		
		$db->vars['id'] = $id;
		$db->type = 'site';
		$page = $db->select("SELECT * FROM pages WHERE pid=:id AND archived=0 ORDER BY id DESC LIMIT 1");
								
		return $page[0];
	}
	
	// pass a page id and type and return the path
	public function getPageById($id) {
		global $db;
		
		$db->vars['id'] = $id;
		$db->type = 'site';
		$page = $db->select("SELECT * FROM pages WHERE id=:id AND archived=0");
								
		return $page[0];
	}
	
	public function getPageByPath($path) {
		global $db;
		$db->vars = array();
		$db->vars['path'] = ltrim($path, "/");
		$db->type = 'site';
		$pages = $db->select("SELECT p.*
								FROM pages AS p
								WHERE 
								path=:path 
								AND
								archived=0
								ORDER BY id DESC");
										
		return (count($pages)) ? $pages[0] : false;
	}	
	
	public function lock($id) {
		global $db;
		
		// user can only have one page locked at a time, so unlock all the others
		$this->unlock();

		$db->type = 'site';
		$pageid['id'] 			= $db->sqlify($id);
		$values['locked_by'] 	= $db->sqlify($_SESSION['userid']);
		$values['locked_date'] 	= $db->sqlify(date('Y-m-d H:i:s'));
		$db->update('pages', $pageid, $values);
		$db->doCommit();
										
		return true;
	}
	
	public function unlock() {
		global $db;

		$db->type = 'site';
		$pageid['locked_by'] 	= $db->sqlify($_SESSION['userid']);
		$values['locked_by'] 	= $db->sqlify('0');
		$values['locked_date'] 	= $db->sqlify('0000-00-00 00:00:00');
		$db->update('pages', $pageid, $values);
		
		// update any locks that have been in effect for more than an hour
		$db->custom("UPDATE pages SET locked_by=0, locked_date='0000-00-00 00:00:00' WHERE locked_date<'".date('Y-m-d H:i:s', strtotime('-1 hour'))."' AND locked_by!=0");
		
		$db->doCommit();
										
		return true;
	}
	
	public function getChildren($path) {
		global $db;
		global $m; 
		
		$db->type = 'site';
		$db->vars = array();
		$db->vars['path'] = ltrim($path, "/")."%";
		$db->vars['fpath'] = ltrim($path, "/");
		$pages = $db->select("SELECT p.*
								FROM pages AS p
								WHERE 
								path LIKE :path 
								AND
								path!=:fpath
								AND
								archived=0
								ORDER BY `order`, publish_date ASC");
		
		/**
		 * Check modules for other children
		 */
		$modules = $m->getModules();

		$path_array = explode("/", trim($path,"/"));
		$i=0;
		foreach ($path_array as $chunk) {
			if (!empty($modules[$chunk]) && is_array($modules[$chunk])) {
				$children = array_keys($modules[$chunk]);
				$modules = $modules[$chunk];
				$i++;
				continue;
			} else {
				if ($i>0 && isset($modules[$chunk]) && is_array($modules[$chunk])) {
					$children = array_keys($modules[$chunk]);
				} else {
					$children = array();
					break;
				}
			}
		}

		if (count($children)) {
			foreach ($children as $child) {
				if (strpos($child, ".php")!==false) {
					$mod = $m->getModuleByPath($path."/".basename($child,'.php'));
					$pages[] = array('path'=>$path."/".basename($child,'.php'),
									'title'=>clever_ucwords(str_replace("_", " ", basename($child,'.php'))),
									'page_type'=>'module',
									'id'=>$mod['id'],
									'order'=>$mod['order']);
				}
			}
		}	

		/**
		 * IF pages found then loop through and get rid of any grandchildren 
		 */				
		if (count($pages)) {
			$ref = explode("/", trim($path, "/"));
			foreach ($pages as $k=>$p) {
				$comp = explode("/", trim($p['path'], "/"));
				if ((count($ref)+1) != count($comp)) {
					unset($pages[$k]);
				} else {
					$ordered[$p['order']."_".$k] = $p;
				}
			}
			ksort($ordered);
			return $ordered;
		} else {
			return false;
		}
	}	
	
	public function getParent($path) {
		global $db;
		global $url_vars;
		
		if (!empty($path)) {
			// remove last element of path
			$path_array = explode("/", trim($path, "/"));
			$end = array_pop($path_array);
			$newpath = implode("/", $path_array);
		
			if (is_numeric($url_vars[0])) {
				$parent['path'] = $path;
				$parent['title'] = clever_ucwords(str_replace("_", " ", $end));
				return $parent;
				
			} else {				
				$db->vars = array();
				if ($newpath != "/") {
					$db->type = 'site';
					$db->vars['path'] = $newpath;
					$pages = $db->select("SELECT p.*
											FROM pages AS p
											WHERE 
											path=:path
											AND
											archived=0
											ORDER BY `order`, publish_date ASC");
											
					if (!count($pages)) {
						$newpath = (defined('SITE')) ? "/sites/".$newpath : "/".$newpath ;
						$db->vars['path'] = $newpath;
						$pages = $db->select("SELECT p.*
											FROM modules AS p
											WHERE 
											path=:path");
					}
					return (count($pages)) ? $pages[0] : false;
				}
			}
		} else {
			return false;
		}
	}
	
	public function getSiblings($path) {
		global $db;
		$db->vars = array();
		
		// remove last element of path
		$path_array = explode("/", ltrim($path, "/"));
		array_pop($path_array);
		$ppath = implode("/", $path_array);

		if (!empty($ppath) && $ppath!='/') {
			$db->vars['path'] = $ppath."%";
			$db->type = 'site';
			$pages = $db->select("SELECT p.*
									FROM pages AS p
									WHERE 
									path LIKE :path
									AND
									archived=0
									ORDER BY `order`, publish_date ASC");
	
			if (count($pages)) {
				$ref = explode("/", trim($path, "/"));
				foreach ($pages as $k=>$p) {
					$comp = explode("/", trim($p['path'], "/"));
					if (count($ref) != count($comp)) {
						unset($pages[$k]);
					}
				}
				return $pages;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	
	
	// pass block id/type to get page
	public function getPageByBlock($bid, $btype) {
		global $db;
		global $b;
		$block_type = $b->getBlockByType($btype);
		
		$db->vars['bid'] = $bid;
		$db->vars['btype'] = $block_type['id'];

		$db->type = 'site';
		$pages = $db->select("SELECT p.*
								FROM block_".$btype." AS b
								LEFT JOIN blocks_bridge AS bb ON bb.block_id=b.id 
								LEFT JOIN pages AS p ON bb.page_id=p.id
								WHERE 
								bb.block_id=:bid
								AND
								bb.block_type=:btype
								AND
								bb.page_type='page'
								LIMIT 1");
		return (count($pages)) ? $pages[0] : false;
	}	
	
	public function getPageTypes() {
		global $db;
		
		if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].BASE.'/core/templates/page_types')) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != ".." && $entry != "_notes") $page_types[] = basename($entry, '.php');
			}
			closedir($handle);
		}
		if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].BASE.'/themes/'.$db->checkSettings('theme').'/templates/page_types')) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != ".." && $entry != "_notes") $page_types[] = basename($entry, '.php');
			}
			closedir($handle);
		}
		if (isset($_SESSION['SUB_DIR'])) {
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].$_SESSION['SUB_DIR'].'/templates/page_types')) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && $entry != "_notes") $page_types[] = basename($entry, '.php');
				}
				closedir($handle);
			}
		}
		 sort($page_types);
		return array_unique($page_types);
	}
	
	/**
	 * Check how page b is related to page a, returns one of the following:
	 * unrelated / ancestor / parent / sibling / child / decendant<br />
	 *
	 * Pass two valid page IDs for comparison
	 */
	public function checkRelationship($a, $b) {
		$page1 = $this->getPage($a, 'pages');
		$page2 = $this->getPage($b, 'pages');
		
		if (!$page1 || !$page2) return "Sorry, this only works for CMS pages";
		
		$path1 = explode("/", $page1['path']);
		$path2 = explode("/", $page2['path']);
		
		/**
		 * check if b is below a
		 */
		if (strpos($page2['path'], $page1['path'])!==false) {
			$np = trim(str_replace($page1['path'], "", $page2['path']), "/");
			if (count(explode("/", $np))==1) {
				return 'child';
			} else {
				return 'decendant';
			}
		}
		/**
		 * check if a is below b
		 */
		if (strpos($page1['path'], $page2['path'])!==false) {
			$np = trim(str_replace($page2['path'], "", $page1['path']), "/");
			if (count(explode("/", $np))==0) {
				return 'parent';
			} elseif (count(explode("/", $np))==1) {
				return 'sibling';
			} else {
				return 'ancestor';
			}
		}
		
		return 'unrelated';
	}
	
	
	public function outputRelatedPages() {
		global $b;
		global $_img;
		global $m;
		global $_mod;
		
		if (!empty($_mod)) {
			$related = (!empty($_mod['related'])) ? json_decode($_mod['related'], true) : array();
		} else {
	        $page = $this->getPage($this->page['pid']);
			$related = (!empty($page['related'])) ? json_decode($page['related'], true) : array();
		}
		
		/**
		 * Add any auto-related content set in modules
		 */
		if (is_array($this->auto_related)) {
			$related = array_merge($related, $this->auto_related);
		}

		if (!count($related)) {
			/**
			 * if this page has no related content then check parent for related
			 */
			$path = $this->page['path'];
			do {
				$parent = $this->getParent($path);
				
				$path = $parent['path'];
			} while ($parent && empty($parent['related_1']));
			$page = $parent;
			$related = (!empty($page['related'])) ? json_decode($page['related'], true) : array();
		}

		if (count($related) || is_array($this->auto_related)) {
			/**
			 * Log amount of related pages for reference else where - particularly from related video function
			 */		
			$this->related_count = 0;

			foreach ($related as $rel) {
				$title = $description = $link = $image = $alt = $type = "";

				if (!empty($rel)) {
					$this->related_count++;
					$r = $this->getPageByPath($rel);

					/**
					 * If r is false it must be a module of some sort
					 */
					if (!$r) {
						$r = $m->getModuleByPath($rel);
						$rel = $r['path'];
					}

					if ($r['id']==$this->page['id'] || !$r) {
						continue;
					}
					if ($r) {
						$title = $r['title'];

						$description = (!empty($r['description'])) ? truncateText($r['description'], 200) : truncateText($r['content'], 200);
						$link = $r['path'];
						$path_parts = explode("/", $rel);
						foreach ($path_parts as $k=>$part) {
							if (is_numeric($part) && isset($part[$k-1])) {
								$type = str_replace("_", " ", $part[$k-1]);
								break;
							}
						}		
						if (empty($type)) {
							$type = str_replace("_", " ", $path_parts[count($path_parts)-2]);
						}

						if (empty($r['image'])) {
							/**
							 * check if page has gallery block attached
							 */
							$gallery = $b->getBlock($r['id'], 'gallery');
							if ($gallery) {
								$images = json_decode($gallery['images'], true);
								$first = key($images);
								$image = 'images/galleries/'.$r['id'].'/'.$images[$first]['src'];
								$alt = $images[$first]['caption'];
							} else {
								/**
								 * if no gallery is found on that page, then check the child pages
								 */
								$children = $this->getChildren($r['path']);
								if ($children) {
									foreach ($children as $child) {
										$gallery = $b->getBlock($child['id'], 'gallery');
										if ($gallery) {
											$images = json_decode($gallery['images'], true);
											$first = key($images);
											$image = 'images/galleries/'.$child['id'].'/'.$images[$first]['src'];
											$alt = $images[$first]['caption'];
										}
										break;
									}
								}
							}
						} else {
							$image = str_replace("/assets/", "", $r['image']);
							$alt = (isset($r['alt'])) ? $r['alt'] : "";
						}		
					}
					?>
					<div class='content related_block <?=($_SESSION['tablet']==1)?"y450":""?>'>
						<h4><a href="<?=DIR."/".ltrim($link,"/")?>"><?=(!empty($type)&&!is_numeric($type)&&stripos($title, $type)===false)?$type.": ":"";?><?=$title?></a></h4>
						<a href='<?=DIR."/".ltrim($link,"/")?>'><img src='<?=$_img->getImage('/assets/'.$image, 354, 209, NULL, true)?>' style="width:100%" /></a>
						<p><?=$description?></p>
					</div>
					<?php
                }
			}
		}
	}
	
	public function removeBlockMarkers ($input) {
		global $db;
		// get all blocks
		$db->type = 'site';
		$block_types = $db->select("SELECT id, name FROM blocks");
			
		$output = $input;
		// loop through blocks, check if this page has a block of this type and merge in to template
		if (count($block_types)) {
			foreach ($block_types as $bt) {
				$output = preg_replace('/{('.$bt['name'].')}/i', "", $output);
			}
		}
		return $output;
	}
	
	public function addWidgets($input) {
		global $db;
		
		/**
		 * Look through 'widgets' and see if any need including
		 * Get the custom widgets first so they take priority
		 */
		$dir1 = $_SERVER['DOCUMENT_ROOT'].BASE."/widgets";
		$dir2 = $_SERVER['DOCUMENT_ROOT'].BASE."/core/widgets";
		$widgets = array_merge(scan_dir($dir1), scan_dir($dir2));
	
		if (count($widgets)) {
			$widgs = array();
			/**
			 * Look through each widget, make sure it hasn't already been added (i.e. a custom widget is overwriting a core widget)
			 * Also only look at .php files - any others are assets for the widgets
			 */
			foreach ($widgets as $key=>$file){
				if (!in_array(basename($file), $widgs) && substr($file, -3)=='php') {
					$widgs[] = basename($file);
					
					/**
					 * Get any instances in the page of the widget
					 */
					preg_match_all("/{(".basename($file, ".php")."\[(.*?)\])}/i", $input, $matches);

					if (count($matches)) {
						foreach($matches[2] as $match) {
							/**
							 * Get the widget code
							 */
							ob_start();
								$frag_query = $match;
								require($file);
							$widget = ob_get_clean();
							
							/**
							 * Replace marker with widget code
							 */
							$input = preg_replace('#<p[^>]*>(\s|&nbsp;?){'.basename($file, ".php").'#','{'.basename($file, ".php"), $input);
							$input = preg_replace("/{(".basename($file, ".php")."\[".$match."\])}/i", $widget, $input);
						}
					}
				}
			} 
			/**
			 * Get rid of empty P tags - shouldn't really be here...
			 */
			$output = preg_replace('#<p[^>]*>(\s|&nbsp;?)*</p>#','', preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $input));

			return $output;
		} else {
			return $input;
		}
	}
}
$p = new Page();
?>
