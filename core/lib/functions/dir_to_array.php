<?php
/***************************
* function to turn directory structure in to an array
*
* @param $dir:String = directory
* returns array()
***************************/
function dirToArray($dir) {
	$contents = array();
	# Foreach node in $dir
	foreach (scandir($dir) as $node) {
		# Skip link to current and parent folder
		if (strpos($node, '.')===0) continue;
		if ($node == '..') continue;
		if ($node == 'index.php') continue;
		
		# Check if it's a node or a folder
		if (is_dir($dir . DIRECTORY_SEPARATOR . $node)) {
			if ($node!='admin') {
				# Add directory recursively, be sure to pass a valid path
				# to the function, not just the folder's name
				$contents[$node] = dirToArray($dir . DIRECTORY_SEPARATOR . $node);
			}

		} else {
			# Add node, the keys will be updated automatically
			$contents[$node] = str_replace(".php", "", $node);
		}
	}

	return $contents;
}