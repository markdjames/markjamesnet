<?php
if (isset($_GET['q'])) {
	if (!isset($db)) {
		require_once 'bootstrap.php';
	}
	
	$searchterm = $_GET['q'];
	
	foreach ($search_ignore as $si) {
		$searchterm = trim(str_replace($si, "", $searchterm));
	}
	
	$db->type='site';
	// if searching within a certain category add extra SQL
	if (!empty($_GET['category']) && $_GET['category']!='all') {
		$db->vars['cat'] = strtolower($_GET['category']);
		$sql = " type=:cat AND ";
	}
	$db->vars['term1'] = $searchterm;
	$db->vars['term2'] = $searchterm;
	$results = $db->select("SELECT 
								path, 
								title, 
								description, 
								CONCAT(UCASE(SUBSTRING(type, 1, 1)),LCASE(SUBSTRING(type, 2))) AS category,
							MATCH (data) AGAINST (:term1) as Relevance 
							FROM 
								search 
							WHERE 
								".$sql."
								(MATCH (data) AGAINST(:term2 IN BOOLEAN MODE)) HAVING Relevance > 0.5 
							ORDER BY Relevance DESC");


	if (count($results)) {	
		// here we lay out the standard order of importance for the categories
		$content_order = array(	'Pages'=>0
							);
		
		// now we sort each result into the correct categories and sort them by the order above
		foreach($results as $dbr) {
			if (count($sorted[$content_order[$dbr['category']]])<5 || !empty($_GET['category'])) {
				$sorted[$content_order[$dbr['category']]][] = $dbr;
			} 
		}
		ksort($sorted);
		
		if ($_GET['format']=='json') {
			echo strip_tags(json_encode($sorted));
			
		} else {
			
			/*************************************
			* loops through each catgeory and save the output to array
			*************************************/
			foreach($sorted as $cat=>$res) {
				$c = array_search($cat, $content_order);
				
				$categories[$c] = "<div class='search_category'>";
				$categories[$c] .= "<h2>".$c."</h2>";
				
				foreach($res as $r) {
					/****************************************
					* if title matches query then save current category as the most relevant
					****************************************/
					if (strtolower($r['title']) == strtolower($_GET['q'])) {
						$rel_category = $c;
					}
					$categories[$c] .= "<p><strong><a href='".BASE."/".$r['path']."'>".$r['title']."</a></strong><br />";
				
					foreach (explode(" ", $_GET['q']) as $kw) {
						$kw = str_replace("'s", "", $kw);
						if (stripos($r['description'], $kw) > 200 && strlen($kw)>2) {
							$r['description'] = "...".str_ireplace($kw, "<strong>".$kw."</strong>", substr($r['description'], stripos($r['description'], $kw)-100));
							
						} elseif (stripos($r['description'], $kw)!==false && strlen($kw)>2) {
							$r['description'] = str_ireplace($kw, "<strong>".$kw."</strong>", $r['description']);
						}
					}
					$categories[$c] .= truncateText($r['description'], 200);
					
					$categories[$c] .= "</p>";	
			
				}
				$categories[$c] .= (empty($_GET['category'])) ? "<p style='float:right;'><a href='".BASE."/search&category=".strtolower($c)."&q=".$_GET['q']."'>More from this category</a></p><div style='clear:both'></div>" : "" ;
				$categories[$c] .= "</div>";
			}
			
			/**************************************
			* if there is a more relevent category, promote this to the top and remove from array 
			* of other categories before outputting
			***************************************/
			if (!empty($rel_category)) {
				echo $categories[$rel_category];
				unset($categories[$rel_category]);
			} 
			foreach ($categories as $output) {
				echo $output;
			}
			//echo $paginator->createPagination($elements);	
		}
	} 
}

