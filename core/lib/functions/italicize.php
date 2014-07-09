<?php
function italicize($input) {
	$output = "";
	
	// remove any square bracksts & contents as shouldnt appear on live site.
	$input = preg_replace('/\[.*\]/', '', $input); 
	
	if (strpos($input, "#")!==false) {
		$split = explode("#", $input);
		
		$i=0;
		foreach ($split as $s) {
			if ($i%2==0 && $i!=count($split)-1) {
				$output .= $s."<em>";
			} elseif ($i!=count($split)-1) {
				$output .= $s."</em>";
			} else {
				$output .= $s;
			}
			$i++;
		}
	} else {
		$output = $input;
	}

	return $output;
}
