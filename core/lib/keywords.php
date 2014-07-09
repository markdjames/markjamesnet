<?php
if (isset($_GET['id']) && strpos($_GET['id'], "admin")===false) {
	
	
	$output = mb_convert_encoding($output, 'html-entities', 'utf-8'); 

	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	$doc->loadHTML($output);
	libxml_use_internal_errors(false);
	foreach($doc->getElementsByTagName('p') as $key=>$paragraph) {
		$par = (string) $paragraph->textContent;

		foreach ($keywords as $word=>$path) {
			if (stripos($par, $word."</a>")===false && stripos($par, ">".$word)===false && stripos($par, "/".$word)===false && stripos($par, $word."/")===false) { // check not already linked
				$fpath = (strpos($path, "http://")!==false) ? $path : BASE."/".$path;
				$fpath = (strpos($fpath, "mailto:")!==false) ? $path : $fpath;
				$par = preg_replace('/'.$word.'\b/i', "<a href='".$fpath."'>".clever_ucwords($word)."</a>", $par);	
				
				$paragraph->textContent = $par;
			}
		}
	} 
	
	$output = $doc->saveHTML();
	
	preg_match_all('/[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4})/i', $output, $matches);
	if (count($matches[0])) {
		$unique_emails = array_unique($matches[0]);
		foreach ($unique_emails as $match) {
			$output = preg_replace('/^'.$match.'/i', "<a href='mailto:".$match."'>".$match."</a>", $output);
		}
	}
	

}