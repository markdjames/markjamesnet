<?php
ini_set('display_errors', 1);
if (isset($_GET['q'])) {
	if (!isset($db)) {
		require_once '../bootstrap.php';
	}
	
	$input = $_GET['q'];
	
	/**
	 * Get suggested words here
	 */
	$words = $filtered_words = $results = $wordresults = array();
	
	$unique_words = array_unique($words);
	
	foreach ($unique_words as $word) {
		if (strpos(strtolower($word), strtolower($input[0]))===0) {
			$filtered_words[] = $word;
		}
	}
	
	// no shortest distance found, yet
	$shortest = -1;
	$i = 0;
	// loop through words to find the closest
	foreach ($filtered_words as $word) {
	
		// calculate the distance between the input word,
		// and the current word
		$lev = levenshtein(strtolower(str_replace(" ", "", $input)), strtolower(str_replace(" ", "", $word)));
	
		// if Levenshtein value is less than 5 add to array
		if ($lev<5) {
			$results[$lev."_".$i] = array(	'title'=>trim($word,', '), 
											'id'=>$lev,
											'category'=>'Suggestions'
											);
			$i++;
		}
	}
	
	if (count($results)) {
		natksort($results);
		$wordresults = array_slice($results, 0, 5);
	}
	
	$db->type='site';
	$db_results = $db->select("SELECT 
								path, 
								title, 
								description,
								CONCAT(UCASE(SUBSTRING(type, 1, 1)),LCASE(SUBSTRING(type, 2))) AS category,
								MATCH (data) AGAINST ('".addslashes($input)."') as Relevance 
							FROM 
								search 
							WHERE 
								(MATCH (data) AGAINST('".addslashes($input)."' IN BOOLEAN MODE)) HAVING Relevance > 0.5 
							ORDER BY Relevance 
							DESC LIMIT 30");

	
	if (count($db_results)) {
		
		$sorted = array();
		$content_order = array(	'Pages'=>0
							);
		
		foreach($db_results as $dbr) {
			if (!isset($sorted[$content_order[$dbr['category']]]) || count($sorted[$content_order[$dbr['category']]])<2) {
				$dbr['title'] = strip_tags($dbr['title']);
				$sorted[$content_order[$dbr['category']]][] = $dbr;
			} 
		}
		ksort($sorted);
		foreach($sorted as $cat=>$res) {
			foreach($res as $r) {
				$new_results[] = $r;
			}
		}
		
		$dbresults = array_slice($new_results, 0, 10);
		$results = $dbresults;
	} else {
		$results = $wordresults;
	}
		
	echo json_encode($results);
	 
}
