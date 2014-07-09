<?php
class Site {
	
	// pass a page id and check if its published and not archived (i.e. visible to public)
	public function checkIfSubSite() {
		if (strpos($_SERVER['PHP_SELF'], '/site/')!==false) {
			return true;
		} else {
			return false;
		}
	}
	
	public function checkRedirect($path) {
		global $db;
	
		$db->type = 'site';
		$db->vars['path'] = ltrim($path, "/");
		$redirect = $db->select("SELECT *
								FROM redirects
								WHERE 
								path=:path");
		
		if (isset($redirect[0]['target'])) {
			if (filter_var(filter_var($redirect[0]['target'], FILTER_SANITIZE_URL), FILTER_VALIDATE_URL)) {		
				header ('Location: '.$redirect[0]['target']);			
			} else {
				header ('Location: '.BASE."/".$redirect[0]['target']);
			}	
			exit();					
		} else {
			return false;
		}
		
	}
	
	/***************************
	* function to output an m-d-array of pages as a site map
	*
	* @param $pages:Array = all pages as multi-diemesional array
	* @param $path:String = directory to current level
	* @param $style:String = defaults to list, optional 'select' (returns only options, not select wrap) or 
	* 'array' (returns all links in flat array)
	***************************/
	public function siteMap($pages, $path, $style='list') {
		static $map_output = array();
		$output = "";
		switch ($style) {
			case 'list':
				$output .= "\n<ul>";
				break;
			case 'array':
				$output = array();
				break;
		}
		foreach ($pages as $key=>$page) {
			if ($key=='_notes' || $key=='dwsync.xml' || $key=='locale') continue;
			if (is_array($page)) {
				$p = $path."/".$key;
				if (!in_array($p, $map_output)) {
					switch ($style) {
						case 'list':
							$output .= "\n\t<li><a href='".$p."'>".ucwords(str_replace("_", " ", $key))."</a>";	
							break;
						case 'select':
							$output .= "\n\t<option value='".str_replace(DIR, "", $p)."'>".str_replace(DIR, "", $path)."/".$key."</option>";	
							break;
						case 'array':
							$output[] = str_replace(DIR, "", $p);
							break;
						
					}
					$map_output[] = $p;
				}
				unset($page[0]); // remove unwanted item from array
				ksort($page);
				if ($style=='array') {
					$output = array_merge($output, $this->siteMap($page, $path."/".$key, $style));
				} else {
					$output .= $this->siteMap($page, $path."/".$key, $style);
				}
			} else {
				$p = $path."/".$page;
				if (!in_array($p, $map_output) && !is_numeric($key)) {
					switch ($style) {
						case 'list':
							$output .= "\n<li><a href='".$p."'>".ucwords(str_replace("_", " ", $page))."</a>";
							break;
						case 'select':
							$output .= "\n\t<option value='".str_replace(DIR, "", $p)."'>".str_replace(DIR, "", $path)."/".$page."</option>";	
							break;
						case 'array':
							$output[] = str_replace(DIR, "", $p);
							break;
						
					}
					$map_output[] = $p;
				}
			}
		}
		switch ($style) {
			case 'list':
				$output .= "\n</ul>";
				break;
		}
		
		return $output;
	}

}
$site = new Site();
?>