<?php
/*************************************
* function to rebuild query strings based on current query string and remove/replace given elements
*
* @exclude:Mutatble String or Array = elements that should be removed from the query (by key name)
*************************************/
function buildQuery ($exclude=NULL) { 
	
	if (!is_array($exclude)) {
		$ex = array($exclude);
	} else {
		$ex = $exclude;
	}
	$ex[] = 'id';
	
	parse_str($_SERVER['QUERY_STRING'], $q_a);

	foreach ($ex as $e) {
		unset($q_a[$e]);
	}

	return http_build_query($q_a); 
}