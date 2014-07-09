<?php
function truncateText ($text, $max_length=100, $first_para=true) {
	$text = strip_tags($text, "<p><br><strong>");
	
	if (strpos($text, "</p>")!==false && strpos($text, "</p>")<$max_length && $first_para) {
		$output = substr($text, 0, strpos($text, "</p>"));
	} else if (strlen($text)>$max_length && strpos($text, " ", $max_length)!==false) {
		$output = rtrim(substr($text, 0, strpos($text, " ", $max_length)),",")."...";
	} else {
		$output = $text;
	}
	
	return rtrim($output, ", ");
}
